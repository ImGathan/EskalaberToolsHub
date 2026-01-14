<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Loan;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $lateLoans = Loan::with('tool')
            ->where('user_id', Auth::id())
            ->where('status', 'approve')
            ->where('due_date', '<', Carbon::now())
            ->get();

        return view('_user.dashboard', compact('lateLoans'));
    }
}
