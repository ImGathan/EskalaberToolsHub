<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tool;
use App\Models\Category;
use App\Models\Loan;

class LandingController extends Controller
{
    public function index()
{
    $tools = Tool::all();
    
    // Fungsi pembantu untuk format ribuan
    $formatNumber = function($num) {
        if ($num >= 1000) {
            // Membagi dengan 1000, lalu bulatkan 1 angka di belakang koma
            return round($num / 1000, 1) . 'k';
        }
        return $num;
    };

    $totalBarang = $formatNumber(Tool::count());
    $totalKategori = $formatNumber(Category::count());
    $totalPeminjaman = $formatNumber(Loan::count());

    return view('welcome', compact('tools', 'totalBarang', 'totalKategori', 'totalPeminjaman'));
}
}
