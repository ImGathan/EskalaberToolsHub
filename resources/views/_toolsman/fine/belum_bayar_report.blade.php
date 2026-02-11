<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        @page { margin: 0; }
        body { 
            font-family: 'Helvetica', Arial, sans-serif; 
            font-size: 10pt; 
            line-height: 1.4;
            color: #333; 
            margin: 0;
            padding: 0;
            background-color: #fff;
        }

        .wrapper { padding: 40px; }

        /* Header Section */
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 35px; }
        
        /* Logo diperbesar sesuai request */
        .logo { height: 75px; width: auto; }
        
        .invoice-title { 
            text-align: right; 
            font-size: 26pt; 
            font-weight: bold; 
            color: #1d4ed8; /* Biru ToolsHub */
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Top Info Box */
        .info-box { 
            width: 100%; 
            background-color: #f0f4ff; /* Background biru sangat muda */
            padding: 25px; 
            border-top: 2px solid #1d4ed8;
            margin-bottom: 35px;
        }
        .info-table { width: 100%; border-collapse: collapse; }
        .info-table td { vertical-align: top; width: 33%; }
        .info-label { font-size: 8pt; color: #64748b; text-transform: uppercase; margin-bottom: 6px; display: block; font-weight: bold; }
        .info-value { font-weight: bold; font-size: 10pt; color: #1e293b; }

        /* Table Area */
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 35px; }
        .items-table thead th { 
            background-color: #1d4ed8; /* Biru Solid */
            color: white; 
            text-align: left; 
            padding: 15px; 
            text-transform: uppercase;
            font-size: 9pt;
            letter-spacing: 0.5px;
        }
        .items-table tbody td { 
            padding: 18px 15px; 
            border-bottom: 1px solid #e2e8f0;
            background-color: #fff;
        }

        /* Summary Area */
        .bottom-table { width: 100%; border-collapse: collapse; }
        .notes-area { width: 55%; vertical-align: top; padding-right: 50px; }
        .summary-area { width: 45%; }
        
        .summary-table { width: 100%; border-collapse: collapse; }
        .summary-table td { padding: 10px 0; }
        .total-row { 
            background-color: #1d4ed8; 
            color: white; 
        }
        .total-row td { padding: 15px 12px; font-weight: bold; font-size: 13pt; }

        .footer { 
            margin-top: 10px; 
            padding-top: 25px; 
            text-align: center; 
            font-size: 8.5pt; 
            color: #94a3b8;
        }

        .status-tag {
            color: #d81d1dff;
            font-weight: 800;
            font-size: 11pt;
        }

        .clearfix::after { content: ""; clear: both; display: table; }
    </style>
</head>
<body>

    <div class="wrapper">
        <table class="header-table">
            <tr>
                <td>
                    <img class="logo" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('admin/images/logo.webp'))) }}">
                </td>
                <td class="invoice-title">Invoice</td>
            </tr>
        </table>

        <div class="info-box">
            <table class="info-table">
                <tr>
                    <td>
                        <span class="info-label">Nomor Invoice</span>
                        <div class="info-value">#INV-{{ date('Y') }}{{ str_pad($loan->id, 4, '0', STR_PAD_LEFT) }}</div>
                        <span class="info-label" style="margin-top:12px">Tanggal Cetak</span>
                        <div class="info-value">{{ date('d/m/Y') }}</div>
                    </td>
                    <td>
                        <span class="info-label">Peminjam</span>
                        <div class="info-value">{{ strtoupper($loan->user->username) }}</div>
                        <div class="info-value" style="font-weight: normal; color: #475569;">{{ $loan->user->current_class ?? 'Siswa SMKN 1' }}</div>
                    </td>
                    <td>
                        <span class="info-label">Status Tagihan</span>
                        <div class="status-tag">BELUM BAYAR</div>
                    </td>
                </tr>
            </table>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 50%;">Deskripsi Barang</th>
                    <!-- <th>Tgl Pinjam</th>
                    <th>Tgl Batas Kembali</th> -->
                    <th>Terlambat</th>
                    <th style="text-align: right;">Total Denda</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div style="font-weight: bold; font-size: 11pt; color: #1e293b;">{{ $loan->tool->name }}</div>
                        <div style="font-size: 8.5pt; color: #64748b; margin-top: 4px;">Kuantitas: {{ $loan->quantity }} Unit</div>
                    </td>
                    <!-- <td style="color: #475569;">{{ \Carbon\Carbon::parse($loan->loan_date)->format('d M Y') }}</td>
                    <td style="color: #475569;">{{ \Carbon\Carbon::parse($loan->due_date)->format('d M Y') }}</td> -->
                    <td style="color: #475569;">{{ $loan->hari_terlambat }} hari</td>
                    <td style="text-align: right; font-weight: bold; font-size: 11pt; color: #1e293b;">Rp {{ number_format($loan->fine_amount, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            <div style="font-weight: bold; color: #475569; margin-bottom: 4px;">ToolsHub - Manajemen Inventaris Barang</div>
            SMK Negeri 8 Jember • toolshub.eskalaber.my.id
        </div>
    </div>

</body>
</html>