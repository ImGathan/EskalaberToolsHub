<?php

namespace App\Http\Controllers\Toolsman;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class FineController extends Controller
{
    
    public function index(Request $request) 
    {
        $userId = Auth::id();
        
        // 1. Ambil status dari request (default 0 jika tidak ada)
        $status = $request->query('fine_status', 0);

        // 2. Query dasar (supaya tidak nulis ulang)
        $baseQuery = Loan::with(['tool.category', 'user'])
            ->whereHas('tool.category.toolsman', function($q) use ($userId) {
                $q->where('toolsman_id', $userId);
            })
            ->where('fine_amount', '>', 0)
            ->where('status', 'returned');

        // 3. Hitung TOTAL counter (harus sebelum dipaginasi & difilter status per halaman)
        $countBelumBayar = (clone $baseQuery)->where('fine_status', 0)->count();
        $countLunas = (clone $baseQuery)->where('fine_status', 1)->count();

        // 4. Ambil data dengan filter status tab dan pencarian
        $fineLoans = $baseQuery->where('fine_status', $status)
            ->when($request->keywords, function($q) use ($request) {
                $q->whereHas('user', function($u) use ($request) {
                    $u->where('username', 'like', '%' . $request->keywords . '%');
                });
            })
            ->orderBy('updated_at', 'desc') // Biasanya lebih enak urut yang terbaru
            ->paginate(10)
            ->withQueryString(); // Penting! Agar saat pindah halaman, filter tab tidak hilang

        return view('_toolsman.fine.index', compact('fineLoans', 'countBelumBayar', 'countLunas'));    
    }

    public function pay($id)
    {
        $fineLoan = Loan::findOrFail($id);
        return view('_toolsman.fine.pay', compact('fineLoan'));
    }
    
    public function paid(Request $request, $id)
    {
        $fineLoan = Loan::findOrFail($id);
        $request->validate([
            'amount_paid' => 'required|numeric|min:' . $fineLoan->fine_amount,
        ]);

        $fineLoan->update([
            'fine_status' => true,
            'fine_paid_at' => now(),
        ]);

        return redirect()->route('toolsman.fines.index')
            ->with('success', 'Pembayaran denda berhasil!');
    }

}
