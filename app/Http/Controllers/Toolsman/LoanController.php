<?php

namespace App\Http\Controllers\Toolsman;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tool;
use App\Models\Loan;
use App\MOdels\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

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
                } elseif ($status === 'on_loan') {
                    $query->whereIn('status', ['approve', 'returning']);
                } else {
                    $query->where('status', $status);
                }
            })
            ->when($keywords, function($query) use ($keywords) {
                $query->where(function($q) use ($keywords) {
                    $q->whereHas('user', function($userQuery) use ($keywords) {
                        $userQuery->where('username', 'like', "%$keywords%");
                    })
                    ->orWhereHas('tool', function($toolQuery) use ($keywords) {
                        $toolQuery->where('name', 'like', "%$keywords%");
                    })
                    ->orWhere('id', 'like', "%$keywords%");
                });
            })
            ->orderBy('loan_date', 'desc')
            ->paginate(10)->withQueryString();

        

        // Menghitung jumlah yang pending untuk ditampilkan di badge tab
        $countPending = Loan::whereHas('tool.category.toolsman', function($query) use ($userId) {
            $query->where('toolsman_id', $userId);
        })->where('status', 'pending')->count();

        $countOnLoan = Loan::whereHas('tool.category.toolsman', function($query) use ($userId) {
            $query->where('toolsman_id', $userId);
        })->whereIn('status', ['approve', 'returning'])->count();
        
        $countReject = Loan::whereHas('tool.category.toolsman', function($query) use ($userId) {
            $query->where('toolsman_id', $userId);
        })->where('status', 'reject')->count();

        
        return view('_toolsman.loan.index', compact('keywords','loans', 'countPending', 'countOnLoan', 'countReject'));
    }

    public function detail($id) {
        $userId = Auth::user()->id;
        $data = Loan::with(['tool.category'])
            ->whereHas('tool.category.toolsman', function($query) use ($userId) {
                $query->where('toolsman_id', $userId);
            })
            ->findOrFail($id);
        return view('_toolsman.loan.detail', compact('data'));
    }

    public function approve($id) 
    {
        $loan = Loan::findOrFail($id);

        if ($loan->tool->quantity < $loan->quantity) {
            $loan->delete($id);
            return back()->with('error', 'Stok barang tidak mencukupi untuk disetujui.');
        }

        DB::transaction(function() use ($loan) {
            $loan->update([
                'status' => 'approve',
                'approval_date' => now()
            ]);
            $tool = $loan->tool()->first();
            
            $tool->quantity = $tool->quantity - $loan->quantity;
            
            $tool->status = ($tool->quantity <= 0) ? 'Tidak Tersedia' : 'Tersedia';
            
            $tool->save(); 
        });

        ActivityLog::record( 'Penyetujuan Pinjaman', Auth::user()->username . ' menyetujui pinjaman oleh ' . $loan->user->username . ' yaitu ' . $loan->tool->name . ' sebanyak ' . $loan->quantity . ' unit.');

        return redirect()->route('toolsman.loans.index', ['status' => 'on_loan'])->with('success', 'Peminjaman berhasil disetujui.');
    }

    public function reject($id) 
    {
        $loan = Loan::findOrFail($id);

        DB::transaction(function() use ($loan) {
            $loan->update(['status'=>'reject']);
        });

        ActivityLog::record( 'Penolakan Pinjaman', Auth::user()->username . ' menolak pinjaman oleh ' . $loan->user->username . ' yaitu ' . $loan->tool->name . ' sebanyak ' . $loan->quantity . ' unit.');
        return redirect()->route('toolsman.loans.index', ['status' => 'history'])->with('success', 'Peminjaman berhasil ditolak.');
    }

    public function returned ($id) 
    {
        $loan = Loan::findOrFail($id);

        DB::transaction(function() use ($loan) {
   
            $loan->update([
                'status' => 'returned',
                'return_date' => now()
            ]);

            $tool = $loan->tool()->first();
            $tool->quantity = $tool->quantity + $loan->quantity;
            $tool->status = ($tool->quantity <= 0) ? 'Tidak Tersedia' : 'Tersedia';
            $tool->save(); 
        });

        ActivityLog::record( 'Pengembalian Pinjaman', Auth::user()->username . ' menyetujui pengembalian pinjaman oleh ' . $loan->user->username . ' yaitu ' . $loan->tool->name . ' sebanyak ' . $loan->quantity . ' unit.');
        return redirect()->route('toolsman.loans.index', ['status' => 'history'])->with('success', 'Peminjaman berhasil dikembalikan.');
    }


    public function downloadLateReport($id)
    {
        $loan = Loan::with(['user', 'tool'])->findOrFail($id);

        if ($loan->fine_amount <= 0) {
            return back()->with('error', 'Peminjaman ini tidak memiliki riwayat keterlambatan.');
        }

        $data = [
            'title' => 'Laporan Keterlambatan Peminjaman',
            'date' => date('d/m/Y'),
            'loan' => $loan
        ];

        $pdf = Pdf::loadView('_toolsman.loan.keterlambatan_report', $data)
                ->setPaper('a4', 'portrait');

        return $pdf->download('Laporan_Terlambat_' . $loan->user->username . '_' . date('Ymd') . '.pdf');
    }

}
