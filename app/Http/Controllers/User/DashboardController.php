<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Loan;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request) {
        $keywords = $request->get('keywords');
        $now = Carbon::now()->startOfDay();

        $query = Loan::with(['tool.category'])
            ->where('user_id', Auth::id())
            ->where(function ($query) {
                $query->where('status', 'approve')
                      ->orWhere('status', 'returned');
            })
            ->where('due_date', '<', $now);

        if ($keywords) {
            $query->whereHas('tool', function($q) use ($keywords) {
                $q->where('name', 'like', '%' . $keywords . '%');
            });
        }

        $loans = $query->orderBy('loan_date', 'desc')
                    ->orderBy('id', 'desc')
                    ->paginate(20);

    
        return view('_user.dashboard', compact('loans', 'keywords'));
    }
}
