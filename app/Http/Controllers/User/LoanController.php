<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\Tool;
use Illuminate\Support\Facades\Auth;


class LoanController extends Controller
{

    public function index() {
        $loans = Loan::with(['tool.category'])
            ->where('user_id', Auth::id())
            ->orderBy('loan_date', 'desc') // Terbaru di atas
            ->get()
            ->groupBy(function($item) {
                // Mengelompokkan berdasarkan tanggal saja (tanpa jam)
                return \Carbon\Carbon::parse($item->loan_date)->format('Y-m-d');
        });
        
        return view('_user.loan.index', compact('loans'));
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
        $data['information'] = "Menunggu Persetujuan";

        Loan::create($data);
        return redirect()->route('user.dashboard')->with('success', 'Loan created successfully');
    }

    public function detail($id) {
        $data = Loan::with(['tool.category'])->find($id);
        return view('_user.loan.detail', compact('data'));
    }

}
