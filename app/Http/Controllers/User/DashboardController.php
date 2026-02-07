<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Loan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $filter = $request->get('filter', 'month'); // Default ke bulan jika tidak ada input

        // 1. Statistik Cards (Tetap sama)
        $totalPeminjaman = Loan::where('user_id', $userId)->count();
        $totalTagihanKeterlambatan = Loan::where('user_id', $userId)->where('fine_amount', '>', 0)->where('fine_status', 0)->count();
        $totalDalamPeminjaman = Loan::where('user_id', $userId)->where('status', 'approve')->count();

        // 2. Logika Tren Peminjaman Berdasarkan Filter
        $query = Loan::where('user_id', $userId);

        if ($filter === 'day') {
            $query->select(
                DB::raw("DATE_FORMAT(loan_date, '%Y-%m-%d') as key_date"),
                DB::raw("DATE_FORMAT(loan_date, '%d %b') as label"),
                DB::raw("COUNT(*) as total")
            )
            ->where('loan_date', '>=', now()->subDays(30))
            ->groupBy('key_date', 'label')
            ->orderBy('key_date', 'asc');
        } elseif ($filter === 'week') {
            $query->select(
                DB::raw("YEARWEEK(loan_date) as key_date"),
                DB::raw("CONCAT('Minggu ', WEEK(loan_date)) as label"),
                DB::raw("COUNT(*) as total")
            )
            ->where('loan_date', '>=', now()->subWeeks(4))
            ->groupBy('key_date', 'label')
            ->orderBy('key_date', 'asc');
        } else {
            $query->select(
                DB::raw("DATE_FORMAT(loan_date, '%Y-%m') as key_date"),
                DB::raw("DATE_FORMAT(loan_date, '%M') as label"),
                DB::raw("COUNT(*) as total")
            )
            ->where('loan_date', '>=', now()->subMonths(6))
            ->groupBy('key_date', 'label')
            ->orderBy('key_date', 'asc');
        }

        $trendData = $query->get();
        $chartLabels = $trendData->pluck('label');
        $chartData = $trendData->pluck('total')->map(fn($val) => (int)$val)->toArray();

        // 3. Data Chart Kategori (Tetap sama)
        $topCategories = DB::table('loans')
            ->join('tools', 'loans.tool_id', '=', 'tools.id')
            ->join('categories', 'tools.category_id', '=', 'categories.id')
            ->where('loans.user_id', $userId)
            ->select('categories.name', DB::raw('count(*) as total'))
            ->groupBy('categories.name')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();

        $kategoriLabels = $topCategories->pluck('name');
        $kategoriData = $topCategories->pluck('total')->map(fn($val) => (int)$val)->toArray();

        return view('_user.dashboard', compact(
            'totalPeminjaman', 'totalTagihanKeterlambatan', 'totalDalamPeminjaman',
            'chartLabels', 'chartData', 'kategoriLabels', 'kategoriData', 'filter'
        ));
    }

}