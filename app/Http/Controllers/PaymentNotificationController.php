<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use Illuminate\Support\Facades\Log;

class PaymentNotificationController extends Controller
{
    public function handle(Request $request)
    {
        $status = $request->transaction_status;
        $orderId = $request->order_id; // "FINE-113-1770742715"

        // 1. Ambil ID asli (angka 113)
        $parts = explode('-', $orderId);
        $actualId = $parts[1] ?? null;

        // 2. Cari datanya
        $loan = Loan::find($actualId);

        if (!$loan) {
            Log::error("Midtrans Callback: Loan ID $actualId tidak ketemu.");
            return response()->json(['message' => 'Not Found'], 404);
        }

        // 3. Update Status
        if ($status == 'settlement' || $status == 'capture') {
            // Kita pakai update() langsung ke query agar tidak memicu logic 'retrieved' yang ribet di Model
            $loan->update([
                'fine_status' => 1,
                'amount_paid' => $request->gross_amount,
                'fine_paid_at' => now(), // Tambahkan ini biar tahu kapan lunasnya
            ]);

            Log::info("Midtrans Callback: Loan ID $actualId BERHASIL LUNAS.");
        }

        return response()->json(['message' => 'OK']);
    }
}