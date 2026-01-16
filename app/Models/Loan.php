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
            $now = now()->startOfDay();

            if ($loan->status === 'pending') {
                if ($loan->created_at->diffInHours($now) >= 24) {
                    $loan->status = 'reject';
                    $loan->information = "Ditolak otomatis karena tidak ada persetujuan dalam 24 jam.";
                    $loan->saveQuietly();
                    return;
                }
            }

            if ($loan->status === 'approve' && $loan->due_date) {
                $due = Carbon::parse($loan->due_date)->startOfDay();

                if ($now->greaterThan($due)) {
                    $hariTerlambat = abs($now->diffInDays($due));
                    $jumlahPinjam = $loan->quantity;
                    $totalDenda = $hariTerlambat * 5000 * $jumlahPinjam;

                    if ($loan->fine_amount != $totalDenda) {
                        $loan->fine_amount = $totalDenda;
                        $loan->information = "Terlambat $hariTerlambat hari. Denda: Rp " . number_format($totalDenda, 0, ',', '.');
                        
                        $loan->saveQuietly(); 
                    }
                }
            }
        });
    }

    
    public function getKeteranganStatusAttribute()
    {
        if ($this->status === 'pending') return 'Menunggu Persetujuan';
        
        if ($this->status === 'approve') {
            $now = now()->startOfDay(); 
            $dueDate = \Carbon\Carbon::parse($this->due_date)->startOfDay();

            if ($now->greaterThan($dueDate)) {
                $days = abs($now->diffInDays($dueDate));
                return "Terlambat " . $days . " Hari";
            }
                        
            return 'Dalam Peminjaman';
        }

        if ($this->status === 'reject') return 'Peminjaman Ditolak';

        if ($this->status === 'returned') {
            
            $now = now()->startOfDay(); 
            $dueDate = \Carbon\Carbon::parse($this->due_date)->startOfDay();

            if ($now->greaterThan($dueDate)) {
                $days = abs($now->diffInDays($dueDate));
                return "Terlambat " . $days . " Hari";
            }

            return 'Dikembalikan Tepat Waktu';
        } 

        return '-';
    }

    public function getStatusColorAttribute()
    {
        // Menggunakan tailwind classes (sesuaikan dengan framework CSS kamu)
        switch ($this->status) {
            case 'pending':
                return 'text-yellow-700 dark:text-yellow-500';
            
            case 'approve':
                // Cek jika sudah approve tapi terlambat
                $now = now()->startOfDay();
                $due = \Carbon\Carbon::parse($this->due_date)->startOfDay();
                
                if ($now->greaterThan($due)) {
                    return 'text-red-700 dark:text-red-500';
                }
                return 'text-blue-700 dark:text-blue-500';

            case 'reject':
                return 'text-gray-700 dark:text-neutral-300';

            case 'returned':
                $now = now()->startOfDay();
                $due = \Carbon\Carbon::parse($this->due_date)->startOfDay();
                
                if ($now->greaterThan($due)) {
                    return 'text-red-700 dark:text-red-500';
                }
                return 'text-green-700 dark:text-green-500';

            default:
                return 'text-gray-700';
        }
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
        return (int) abs(now()->diffInDays($this->due_date));
    }

}