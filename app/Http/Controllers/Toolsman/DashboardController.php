<?php

namespace App\Http\Controllers\Toolsman;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tool;
use App\Models\Loan;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->user()->id;
        $filter = $request->get('filter', 'day'); // Default harian agar update

        $formatNumber = function($num) {
            if ($num >= 1000) {
                return round($num / 1000, 1) . 'k';
            }
            return $num;
        };

        // Mengambil kategori pertama yang dikelola Toolsman
        $categoryModel = Category::whereHas('toolsman', function($q) use ($userId){
            $q->where('toolsman_id', $userId);
        })->first();
        
        $categoryName = $categoryModel ? $categoryModel->name : 'N/A';

        // --- Statistik Ringkas (Tetap menggunakan relasi Toolsman) ---
        $totalPeminjaman = $formatNumber(Loan::whereHas('tool.category.toolsman', function($q) use ($userId){
            $q->where('toolsman_id', $userId);
        })->where('status', 'approve')->count());

        $totalBarang = $formatNumber(Tool::whereHas('category.toolsman', function($q) use ($userId){
            $q->where('toolsman_id', $userId);
        })->count());

        $totalDendaKeterlambatan = $formatNumber(Loan::whereHas('tool.category.toolsman', function($q) use ($userId){
            $q->where('toolsman_id', $userId);
        })->where('status', 'returned')->where('fine_amount', '>', 0)->where('fine_status', 0)->count());

        // --- 1. DATA UNTUK TREN PEMINJAMAN (Dynamic Filter) ---
        $query = Loan::whereHas('tool.category.toolsman', function($q) use ($userId){
            $q->where('toolsman_id', $userId);
        });

        if ($filter === 'month') {
            // Tren 6 Bulan Terakhir
            $query->select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as key_date"),
                DB::raw("DATE_FORMAT(created_at, '%M') as label"),
                DB::raw("COUNT(*) as total")
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('key_date', 'label')
            ->orderBy('key_date', 'asc');
        } elseif ($filter === 'week') {
            // Tren 4 Minggu Terakhir
            $query->select(
                DB::raw("YEARWEEK(created_at) as key_date"),
                DB::raw("CONCAT('Minggu ', WEEK(created_at)) as label"),
                DB::raw("COUNT(*) as total")
            )
            ->where('created_at', '>=', now()->subWeeks(4))
            ->groupBy('key_date', 'label')
            ->orderBy('key_date', 'asc');
        } else {
            // Default: Tren 7 Hari Terakhir
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

        // --- 2. DATA UNTUK KATEGORI (Bar Chart) ---
        // Optimasi: Hanya hitung barang yang ada dalam kategori milik Toolsman ini
        $categoryStats = Category::whereHas('toolsman', function($q) use ($userId){
                $q->where('toolsman_id', $userId);
            })
            ->withCount('tools')
            ->orderBy('tools_count', 'desc')
            ->take(6)
            ->get();

        $kategoriLabels = $categoryStats->pluck('name');
        $kategoriData = $categoryStats->pluck('tools_count');

        return view('_toolsman.dashboard', compact(
            'totalPeminjaman', 
            'totalBarang',
            'categoryName',
            'totalDendaKeterlambatan', 
            'chartLabels',
            'chartData',
            'kategoriLabels',
            'kategoriData',
            'filter'
        ));
    }
}