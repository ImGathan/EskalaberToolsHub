<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Loan extends Model
{
    protected $table = 'loans';

    protected $fillable = [
        'user_id',
        'tool_id',
        'loan_date',
        'quantity',
        'approve_date',
        'due_date',
        'return_date',
        'fine_amount',
        'information',
        'status', // <--- WAJIB ADA agar bisa disimpan ke DB
    ];

    // WAJIB ADA: Agar Laravel tahu kolom ini adalah tanggal
    protected $casts = [
        'due_date' => 'datetime',
        'loan_date' => 'datetime',
        'approve_date' => 'datetime',
        'return_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tool()
    {
        return $this->belongsTo(Tool::class, 'tool_id');
    }

    
    protected static function booted()
    {
        static::retrieved(function ($loan) {
            // Jika masih dipinjam (approve)
            if ($loan->status === 'approve' && $loan->due_date) {
                $now = now()->startOfDay();
                $due = Carbon::parse($loan->due_date)->startOfDay();

                if ($now->greaterThan($due)) {
                    $hariTerlambat = $now->diffInDays($due);
                    $totalDenda = $hariTerlambat * 5000;

                    // Update fisik ke DB jika ada perubahan nilai
                    if ($loan->fine_amount != $totalDenda) {
                        $loan->fine_amount = $totalDenda;
                        $loan->information = "Terlambat $hariTerlambat hari. Denda: Rp " . number_format($totalDenda, 0, ',', '.');
                        
                        // saveQuietly agar tidak memicu event retrieved lagi (mencegah infinite loop)
                        $loan->saveQuietly(); 
                    }
                }
            }
        });
    }

    /**
     * ACCESSOR: Untuk tampilan di View nanti
     */
    public function getKeteranganStatusAttribute()
    {
        if ($this->status === 'pending') return 'Menunggu Persetujuan';
        
        if ($this->status === 'approve') {
            if (now()->greaterThan(Carbon::parse($this->due_date))) {
                return "Terlambat ";
            }
            return 'Dalam Peminjaman';
        }

        if ($this->status === 'returned') return 'Sudah Dikembalikan';

        return '-';
    }

    public function getHariTerlambatAttribute()
    {
        // Jika belum approve atau belum jatuh tempo, maka 0 hari
        if ($this->status !== 'approve' || now()->lessThan($this->due_date)) {
            return 0;
        }

        // Jika sudah kembali, hitung selisih return_date dengan due_date
        if ($this->status === 'returned' && $this->return_date) {
            return $this->due_date->diffInDays($this->return_date, false) > 0 
                ? $this->due_date->diffInDays($this->return_date) 
                : 0;
        }

        // Hitung selisih hari ini dengan due_date
        return (int) now()->diffInDays($this->due_date);
    }

}