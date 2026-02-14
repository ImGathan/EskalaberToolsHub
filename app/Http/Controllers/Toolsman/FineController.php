<?php

namespace App\Http\Controllers\Toolsman;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\FineExport;
use Maatwebsite\Excel\Facades\Excel;

class FineController extends Controller
{
    
    public function index(Request $request) 
    {
        $userId = Auth::id();
        
        // 1. Ambil status dari request (default 0 jika tidak ada)
        $status = $request->query('fine_status', 0);

        // 2. Query dasar (supaya tidak nulis ulang)
        $baseQuery = Loan::with(['tool.category', 'user'])
            ->whereHas('tool.category.toolsman', function($q) use ($userId) {
                $q->where('toolsman_id', $userId);
            })
            ->where('fine_amount', '>', 0)
            ->where('status', 'returned');

        // 3. Hitung TOTAL counter (harus sebelum dipaginasi & difilter status per halaman)
        $countBelumBayar = (clone $baseQuery)->where('fine_status', 0)->count();
        $countLunas = (clone $baseQuery)->where('fine_status', 1)->count();

        $keywords = $request->keywords;
        
        $fineLoans = $baseQuery->where('fine_status', $status)
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
            ->orderBy('updated_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('_toolsman.fine.index', compact('fineLoans', 'countBelumBayar', 'countLunas', 'keywords'));    
    }

    public function pay($id)
    {
        $fineLoan = Loan::findOrFail($id);
        return view('_toolsman.fine.pay', compact('fineLoan'));
    }
    
    public function paid(Request $request, $id)
    {
        $fineLoan = Loan::findOrFail($id);
        $request->validate([
            'amount_paid' => 'required|numeric|min:' . $fineLoan->fine_amount,
        ]);

        $fineLoan->update([
            'amount_paid' => $request->amount_paid,
            'fine_status' => true,
            'fine_paid_at' => now(),
        ]);

        return redirect()->route('toolsman.fines.index', ['fine_status' => 1 ])->with('success', 'Pembayaran denda berhasil!');
    }

    public function downloadUnpaidReport($id)
    {
        $loan = Loan::with(['user', 'tool'])->findOrFail($id);

        if ($loan->fine_status === 1) {
            return back()->with('error', 'Peminjaman ini sudah lunas.');
        }

        $data = [
            'title' => 'Laporan Denda Keterlambatan',
            'date' => date('d/m/Y'),
            'loan' => $loan
        ];

        $pdf = Pdf::loadView('_toolsman.fine.belum_bayar_report', $data)
                ->setPaper('a4', 'portrait');

        return $pdf->download('Laporan_Denda_Terlambat_' . $loan->user->username . '_' . date('Ymd') . '.pdf');
    }

    public function downloadPaidReport($id)
    {
        $loan = Loan::with(['user', 'tool'])->findOrFail($id);

        if ($loan->fine_status === 0) {
            return back()->with('error', 'Peminjaman ini belum lunas.');
        }

        $data = [
            'title' => 'Laporan Denda Keterlambatan',
            'date' => date('d/m/Y'),
            'loan' => $loan
        ];

        $pdf = Pdf::loadView('_toolsman.fine.lunas_report', $data)
                ->setPaper('a4', 'portrait');

        return $pdf->download('Laporan_Pembayaran_Denda_' . $loan->user->username . '_' . date('Ymd') . '.pdf');
    }

    public function exportPaidFineExcel()
    {
        $userId = Auth::user()->id;
        $date = date('d-m-Y');
        
        // Nama file agar lebih spesifik
        $fileName = 'Laporan_Denda_Keterlambatan_' . $date . '.xlsx';
        
        return Excel::download(new FineExport($userId), $fileName);
    }

}
