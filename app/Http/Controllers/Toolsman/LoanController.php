<?php

namespace App\Http\Controllers\Toolsman;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tool;
use App\Models\Loan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::user()->id;
        $status = $request->get('status', 'pending');
        $keywords = $request->get('keywords');

        $loans = Loan::with(['user', 'tool.category.toolsman'])
            ->whereHas('tool.category.toolsman', function($query) use ($userId) {
                $query->where('toolsman_id', $userId);
            })
            ->where(function ($query) use ($status) {
                if ($status === 'history') {
                    $query->whereIn('status', ['reject', 'returned']);
                } else {
                    $query->where('status', $status);
                }
            })
            ->when($keywords, function($query) use ($keywords) {
                $query->whereHas('user', function($q) use ($keywords) {
                    $q->where('username', 'like', "%$keywords%");
                })->orWhereHas('tool', function($q) use ($keywords) {
                    $q->where('name', 'like', "%$keywords%");
                });
            })
            ->orderBy('loan_date', 'desc')
            ->paginate(10);

        

        // Menghitung jumlah yang pending untuk ditampilkan di badge tab
        $countPending = Loan::whereHas('tool.category.toolsman', function($query) use ($userId) {
            $query->where('toolsman_id', $userId);
        })->where('status', 'pending')->count();
        $countApprove = Loan::whereHas('tool.category.toolsman', function($query) use ($userId) {
            $query->where('toolsman_id', $userId);
        })->where('status', 'approve')->count();
        $countReject = Loan::whereHas('tool.category.toolsman', function($query) use ($userId) {
            $query->where('toolsman_id', $userId);
        })->where('status', 'reject')->count();

        return view('_toolsman.loan.index', compact('loans', 'countPending', 'countApprove', 'countReject'));
    }

    public function approve($id) 
    {
        $loan = Loan::findOrFail($id);

        if ($loan->tool->quantity < $loan->quantity) {
            $loan->delete($id);
            return back()->with('error', 'Stok barang tidak mencukupi untuk disetujui.');
        }

        DB::transaction(function() use ($loan) {
            $loan->update(['status'=>'approve']);
            $loan->tool->update(['quantity' => $loan->tool->quantity - $loan->quantity]);
        });


        return back()->with('success', 'Peminjaman berhasil disetujui.');
    }

    public function reject($id) 
    {
        $loan = Loan::findOrFail($id);

        DB::transaction(function() use ($loan) {
            $loan->update(['status'=>'reject']);
        });


        return back()->with('success', 'Peminjaman berhasil ditolak.');
    }

    public function returned ($id) 
    {
        $loan = Loan::findOrFail($id);

        DB::transaction(function() use ($loan) {
            $loan->update(['status'=>'returned']);
            $loan->tool->update(['quantity' => $loan->tool->quantity + $loan->quantity]);
        });

        return back()->with('success', 'Peminjaman berhasil dikembalikan.');
    }

}
