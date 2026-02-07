<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tool;
use App\Models\Loan;
use App\Models\ActivityLog;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'day'); // Default filter harian

        $formatNumber = function($num) {
            if ($num >= 1000) {
                return round($num / 1000, 1) . 'k';
            }
            return $num;
        };

        // --- Statistik Ringkas ---
        $totalPengguna = $formatNumber(User::whereIn('access_type', [1, 2])->count());
        $totalPeminjaman = $formatNumber(Loan::where('status', 'approve')->count());
        $totalBarang = $formatNumber(Tool::count());
        $totalPengembalianTerlambat = $formatNumber(Loan::where('status', 'returned')->where('fine_amount', '>', 0)->count());

        // --- Data Aktivitas ---
        $activityLogs = ActivityLog::orderBy('created_at', 'desc')->take(5)->get();

        // --- 1. DATA UNTUK TREN PEMINJAMAN (Dynamic Filter) ---
        $query = Loan::query();

        if ($filter === 'month') {
            $query->select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as key_date"),
                DB::raw("DATE_FORMAT(created_at, '%M') as label"),
                DB::raw("COUNT(*) as total")
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('key_date', 'label')
            ->orderBy('key_date', 'asc');
        } elseif ($filter === 'week') {
            $query->select(
                DB::raw("YEARWEEK(created_at) as key_date"),
                DB::raw("CONCAT('Minggu ', WEEK(created_at)) as label"),
                DB::raw("COUNT(*) as total")
            )
            ->where('created_at', '>=', now()->subWeeks(4))
            ->groupBy('key_date', 'label')
            ->orderBy('key_date', 'asc');
        } else {
            // Default 7 Hari Terakhir
            $query->select(
                DB::raw("DATE(created_at) as key_date"),
                DB::raw("DATE_FORMAT(created_at, '%d %b') as label"),
                DB::raw("COUNT(*) as total")
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('key_date', 'label')
            ->orderBy('key_date', 'asc');
        }

        $loanTrends = $query->get();
        $chartLabels = $loanTrends->pluck('label');
        $chartData = $loanTrends->pluck('total');

        // --- 2. DATA UNTUK KATEGORI (Kategori Paling Sering Dipinjam) ---
    $categoryStats = DB::table('loans')
        ->join('tools', 'loans.tool_id', '=', 'tools.id')
        ->join('categories', 'tools.category_id', '=', 'categories.id')
        ->select('categories.name', DB::raw('count(loans.id) as total_dipinjam'))
        // Jika untuk Toolsman, tambahkan where untuk filter user/kategori miliknya di sini
        ->groupBy('categories.id', 'categories.name')
        ->orderBy('total_dipinjam', 'desc')
        ->take(6)
        ->get();

    $kategoriLabels = $categoryStats->pluck('name');
    $kategoriData = $categoryStats->pluck('total_dipinjam');

        return view('_admin.dashboard', compact(
            'totalPengguna', 'totalPeminjaman', 'totalBarang', 
            'totalPengembalianTerlambat', 'activityLogs',
            'chartLabels', 'chartData', 'kategoriLabels', 'kategoriData', 'filter'
        ));
    }
}