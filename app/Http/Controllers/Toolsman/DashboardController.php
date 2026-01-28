<?php

namespace App\Http\Controllers\Toolsman;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tool;
use App\Models\Loan;
use App\Models\Category;
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

        $userId = auth()->user()->id;

        $category = Category::whereHas('toolsman', function($q) use ($userId){
            $q->where('toolsman_id', $userId);
        })->first()->name;

        // --- Statistik Ringkas ---
        $totalPeminjaman = $formatNumber(Loan::whereHas('tool.category.toolsman', function($q) use ($userId){
            $q->where('toolsman_id', $userId);
        })->where('status', 'approve')->count());
        $totalBarang = $formatNumber(Tool::whereHas('category.toolsman', function($q) use ($userId){
            $q->where('toolsman_id', $userId);
        })->count());
        $totalPengembalianTerlambat = $formatNumber(Loan::whereHas('tool.category.toolsman', function($q) use ($userId){
            $q->where('toolsman_id', $userId);
        })->where('status', 'returned')->where('fine_amount', '>', 0)->count());

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

        return view('_toolsman.dashboard', compact(
            'totalPeminjaman', 
            'totalBarang',
            'category',
            'totalPengembalianTerlambat', 
            'chartLabels',
            'chartData',
            'kategoriLabels',
            'kategoriData'
        ));
    }
}