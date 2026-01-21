<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\Tool;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;


class LoanController extends Controller
{

    public function index(Request $request) {
        $keywords = $request->get('keywords');

        $query = Loan::with(['tool.category'])
            ->where('user_id', Auth::id());

        if ($keywords) {
            $query->whereHas('tool', function($q) use ($keywords) {
                $q->where('name', 'like', '%' . $keywords . '%');
            });
        }

        // 1. Di tingkat Database: Urutkan loan_date DESC, lalu ID DESC
        $loans = $query->orderBy('loan_date', 'desc')
                    ->orderBy('id', 'desc')
                    ->paginate(20);

        // 2. Di tingkat Collection (PHP):
        $groupedLoans = $loans->getCollection()
            ->groupBy(function($item) {
                return \Carbon\Carbon::parse($item->loan_date)->format('Y-m-d');
            })
            ->map(function ($items) {
                // Urutkan item di dalam grup: 
                // Pertama berdasarkan loan_date DESC, jika sama, berdasarkan ID DESC
                return $items->sort(function ($a, $b) {
                    if ($a->loan_date === $b->loan_date) {
                        return $b->id <=> $a->id; // ID terbaru di atas
                    }
                    return $b->loan_date <=> $a->loan_date; // Tanggal terbaru di atas
                });
            })
            ->sortKeysDesc(); // Grup tanggal terbaru tetap di paling atas

    
        return view('_user.loan.index', compact('loans', 'groupedLoans', 'keywords'));
    }
    
    public function add(Request $request) {
        $selectedToolId = $request->query('tool_id');
        $selectedTool = Tool::find($selectedToolId);
        return view('_user.loan.add', compact('selectedTool'));
    }

    public function doCreate(Request $request) {

        $tool = Tool::find($request->tool_id);
        $request->validate([
            'tool_id' => 'required|exists:tools,id',
            'due_date' => 'required|date|after:yesterday',
            'quantity'  => [
                'required', 
                'numeric', 
                'min:1', 
                // Validasi: tidak boleh lebih besar dari stok di database
                'max:' . ($tool->quantity ?? 0) 
            ],
        ], [
            'quantity.max' => 'Jumlah yang dipinjam melebihi stok yang tersedia (Maks: ' . ($tool->quantity ?? 0) . ').',
            'quantity.min' => 'Jumlah minimal peminjaman adalah 1.'
        ]);

        $data = $request->all();
        $data['user_id']     = Auth::id();
        $data['loan_date']   = now();
        $data['status']      = 'pending';
        $data['fine_amount'] = 0;
        $data['information'] = "";

        Loan::create($data);
        ActivityLog::record( 'Pengajuan Pinjaman', Auth::user()->username . ' mengajukan pinjaman alat: ' . $tool->name . ' sebanyak ' . $request->quantity . ' unit.');
        return redirect()->route('user.loans.index')->with('success', 'Loan created successfully');
    }

    public function detail($id) {
        $data = Loan::with(['tool.category'])
            ->where('user_id', Auth::id()) // Pastikan ini milik user yang login
            ->findOrFail($id);
        return view('_user.loan.detail', compact('data'));
    }

}
