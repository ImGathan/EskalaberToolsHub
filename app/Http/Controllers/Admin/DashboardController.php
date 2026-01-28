<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tool;
use App\Models\Loan;
use App\Models\ActivityLog;
use App\Models\Category; // Pastikan model Category diimport jika ada
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
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

        // --- 1. DATA UNTUK TREN PEMINJAMAN (Line Chart) ---
        // Mengambil jumlah peminjaman dalam 7 hari terakhir
        $loanTrends = Loan::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->where('created_at', '>=', now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $chartLabels = $loanTrends->pluck('date')->map(function($date) {
            return date('d M', strtotime($date)); // Ubah format jadi "28 Jan"
        });
        $chartData = $loanTrends->pluck('total');

        // --- 2. DATA UNTUK KATEGORI (Bar Chart) ---
        // Mengambil kategori dan jumlah barang di dalamnya
        // Sesuaikan 'category' dan 'tools' dengan nama relasi/tabel kamu
        $categoryStats = DB::table('categories')
            ->leftJoin('tools', 'categories.id', '=', 'tools.category_id')
            ->select('categories.name', DB::raw('count(tools.id) as total'))
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total', 'desc')
            ->take(6)
            ->get();

        $kategoriLabels = $categoryStats->pluck('name');
        $kategoriData = $categoryStats->pluck('total');

        return view('_admin.dashboard', compact(
            'totalPengguna', 
            'totalPeminjaman', 
            'totalBarang', 
            'totalPengembalianTerlambat', 
            'activityLogs',
            'chartLabels',
            'chartData',
            'kategoriLabels',
            'kategoriData'
        ));
    }
}