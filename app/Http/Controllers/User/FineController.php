<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Midtrans\Snap;

class FineController extends Controller
{
    public function index(Request $request) 
    {
        $userId = Auth::id();
        
        $status = $request->query('fine_status', 0);

        $baseQuery = Loan::with(['tool.category', 'user'])
            ->where('user_id', $userId)
            ->where('fine_amount', '>', 0)
            ->where('status', 'returned');

        $keywords = $request->keywords;
        
        $countBelumBayar = (clone $baseQuery)->where('fine_status', 0)->count();
        $countLunas = (clone $baseQuery)->where('fine_status', 1)->count();

        $fineLoans = $baseQuery->where('fine_status', $status)
            ->when($keywords, function($query) use ($keywords) {
                $query->where(function($q) use ($keywords) {
                    $q->whereHas('user', function($userQuery) use ($keywords) {
                        $userQuery->where('username', 'like', "%$keywords%");
                    })
                    ->orWhereHas('tool', function($toolQuery) use ($keywords) {
                        $toolQuery->where('name', 'like', "%$keywords%");
                    })
                    ->orWhere('id', 'like', "%$keywords%");
                });
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('_user.fine.index', compact('fineLoans', 'keywords', 'countBelumBayar', 'countLunas'));    
    }

    public function pay($id)
    {
        
        // Ambil data loan milik user yang sedang login
        $loan = Loan::where('user_id', Auth::id())->findOrFail($id);

        // Setup Midtrans (Data diambil dari config/services.php)
        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        \Midtrans\Config::$clientKey = config('services.midtrans.client_key');
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => 'FINE-' . $loan->id . '-' . time(),
                'gross_amount' => (int) $loan->fine_amount, // Mengambil nominal denda dari tabel
            ],
            'customer_details' => [
                'first_name' => Auth::user()->username,
                'email' => Auth::user()->email ?? 'user@mail.com',
            ],
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            return response()->json(['token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
