<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\Tool;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;

class LoanController extends Controller
{

    public function index(Request $request): View|Response {
        $keywords = $request->get('keywords');
        $status = $request->get('status');

        $query = Loan::with(['tool.category'])
            ->where('user_id', Auth::id())
            ->when($request->get('keywords'), function ($q, $keywords) {
                return $q->whereHas('tool', function($toolQuery) use ($keywords) {
                    $toolQuery->where('name', 'like', '%' . $keywords . '%');
                });
            })
            ->when($request->get('status'), function ($q, $status) {
            if ($status !== 'all') {
                $map = ['1' => 'pending', '2' => 'approve', '3' => 'returned', '4' => 'reject'];
                $dbStatus = $map[$status] ?? $status; 
                return $q->where('status', $dbStatus);
            }
        });

        // Filter Kata Kunci
        if ($keywords) {
            $query->whereHas('tool', function($q) use ($keywords) {
                $q->where('name', 'like', '%' . $keywords . '%');
            });
        }

        // Urutkan dan Paginate
        $loans = $query->orderBy('loan_date', 'desc')
                    ->orderBy('id', 'desc')
                    ->paginate(10)
                    ->withQueryString(); // Penting: Agar filter tidak hilang saat ganti halaman pagination

        // Pengelompokan (Logika grouping kamu tetap sama)
        $groupedLoans = $loans->getCollection()
            ->groupBy(function($item) {
                return \Carbon\Carbon::parse($item->loan_date)->format('Y-m-d');
            })
            ->map(function ($items) {
                return $items->sort(function ($a, $b) {
                    if ($a->loan_date === $b->loan_date) {
                        return $b->id <=> $a->id;
                    }
                    return $b->loan_date <=> $a->loan_date;
                });
            })
            ->sortKeysDesc();

        // Kirim status ke view agar dropdown tetap terpilih (selected)
        return view('_user.loan.index', compact('loans', 'groupedLoans', 'keywords', 'status'));
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

        $existingLoan = Loan::where('tool_id', $request->tool_id)
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->first();

        if ($existingLoan) {
            return redirect()->back()
                ->withInput()
                ->with('error_duplicate', 'Anda masih memiliki pengajuan pending untuk alat ini.');
        }

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

    public function update(int $id)
    {
        $userId = Auth::user()->id;
        $loan = Loan::where('user_id', $userId)->findOrFail($id);
        return view('_user.loan.update', compact('loan'));
    }

    public function doUpdate(Request $request, int $id)
    {
        $userId = Auth::user()->id;
        $loan = Loan::where('user_id', $userId)->where('status', 'pending')->findOrFail($id);
        $loan->update($request->all());
        return redirect()->route('user.loans.index')->with('success', 'Loan updated successfully');
    }

    public function delete(int $id)
    {
        $userId = Auth::user()->id;
        $loan = Loan::where('user_id', $userId)->where('status', 'pending')->findOrFail($id);
        $loan->delete();
        return redirect()->route('user.loans.index')->with('success', 'Loan deleted successfully');
    }

    public function returning($id) 
    {
        $loan = Loan::findOrFail($id);

        DB::transaction(function() use ($loan) {
            $loan->update(['status'=>'returning']);
        });

        ActivityLog::record( 'Pengajuan Pengembalian', Auth::user()->username . ' mengajukan pengembalian alat: ' . $loan->tool->name . ' sebanyak ' . $loan->quantity . ' unit.');
        return back()->with('success', 'Pengajuan pengembalian berhasil dikirim.');
    }

    public function scan()
    {
        return view('_user.loan.scan');
    }

    public function addLoan(Request $request)
    {
        $toolId = $request->query('tool_id');
        $tool = Tool::findOrFail($toolId);

        // Cek apakah barang tersedia
        if ($tool->status !== 'available') {
            return redirect()->route('user.dashboard')
                ->with('error', 'Maaf, barang ini sedang dipinjam atau tidak tersedia.');
        }

        // Tampilkan form peminjaman dengan data barang yang sudah otomatis terisi
        return view('_user.loan.add', compact('tool'));
    }

}
