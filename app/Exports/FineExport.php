<?php

namespace App\Exports;

use App\Models\Loan;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FineExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithEvents
{
    protected $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    public function query()
    {
        return Loan::with(['user', 'tool.category'])
            ->whereHas('tool.category.toolsman', function($query) {
                $query->where('toolsman_id', $this->userId);
            })
            ->whereIn('fine_status', ['1'])
            ->orderBy('fine_paid_at', 'desc');
    }

    public function headings(): array
    {
        return [
            'Nama Peminjam',
            'Nama Barang',
            'Jumlah',
            'Jumlah Terlambat',
            'Total Denda',
            'Total Bayar',
            'Status Akhir',
        ];
    }

    public function map($loan): array
    {
        // Tetap menggunakan parameter asli kamu
        return [
            $loan->user->username,
            $loan->tool->name,
            $loan->quantity,
            $loan->hari_terlambat . " hari",
            'Rp ' . number_format($loan->fine_amount, 0, ',', '.'),
            'Rp ' . number_format($loan->amount_paid, 0, ',', '.'),
            "Lunas",
        ];
    }

    // Styling Header
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true, 
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1E40AF'] // Warna Biru Gelap
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
                ]
            ],
        ];
    }

    // Styling Border & Alignment Konten
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $lastRow = $event->sheet->getHighestRow();
                $lastCol = 'G'; 

                // Border untuk seluruh tabel
                $event->sheet->getStyle("A1:{$lastCol}{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // Tengahin kolom ID, Jumlah, dan Tanggal agar lebih rapi
                $event->sheet->getStyle("A2:A{$lastRow}")->getAlignment()->setHorizontal('center');
                $event->sheet->getStyle("D2:F{$lastRow}")->getAlignment()->setHorizontal('center');
            },
        ];
    }
}