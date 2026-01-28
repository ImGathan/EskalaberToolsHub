<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        /* Setup Dasar */
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            font-size: 12px; 
            line-height: 1.6;
            color: #333; 
            margin: 0;
            padding: 0;
        }
        
        /* Kop Surat */
        .kop-surat {
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
            text-align: center;
        }
        .logo-container {
            float: left;
            width: 80px;
        }
        .header-text {
            margin-right: 80px; /* Menyeimbangkan logo agar teks tetap center */
        }
        .header-text h1 { 
            margin: 0; 
            font-size: 20px; 
            color: #1d4ed8; /* Blue-700 */
            text-transform: uppercase;
        }
        .header-text p { margin: 2px 0; font-size: 10px; color: #666; }

        /* Judul Dokumen */
        .judul-dokumen {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 30px;
        }
        .judul-dokumen h2 {
            text-decoration: underline;
            margin-bottom: 5px;
            font-size: 16px;
        }

        /* Tabel Data */
        .table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 25px; 
        }
        .table th { 
            background-color: #f8fafc;
            width: 30%; 
        }
        .table th, .table td { 
            border: 1px solid #e2e8f0; 
            padding: 12px; 
            text-align: left; 
        }

        /* Box Denda */
        .alert-denda {
            background-color: #fef2f2;
            border: 1px solid #fee2e2;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .denda-text { 
            color: #b91c1c; 
            font-weight: bold; 
            font-size: 16px; 
            text-align: center;
            display: block;
        }

        /* Tanda Tangan */
        .footer-section {
            width: 100%;
            margin-top: 60px;
        }
        .ttd {
            float: right;
            width: 200px;
            text-align: center;
        }
        .ttd .space { height: 80px; }
        .ttd .nama-petugas { font-weight: bold; text-decoration: underline; }

        /* Clearfix */
        .clearfix::after { content: ""; clear: both; display: table; }
    </style>
</head>
<body>

    <div class="kop-surat clearfix">
        <div class="logo-container">
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('admin/images/logo.webp'))) }}" style="width: 70px;">
        </div>
        <div class="header-text">
            @include('_admin._layout.icons.sidebar.logo')
            <p>Sistem Manajemen Peminjaman Barang Otomatis</p>
            <p>SMK Negeri 1 Lokasi Anda • Telp: (021) 1234567 • Email: support@toolshub.id</p>
        </div>
    </div>

    <div class="judul-dokumen">
        <h2>SURAT KETERANGAN KETERLAMBATAN</h2>
        <p>Nomor: {{ str_pad($loan->id, 5, '0', STR_PAD_LEFT) }}/TH/LATE/{{ date('m/Y') }}</p>
    </div>

    <p>Yang bertanda tangan di bawah ini, Administrator ToolsHub menyatakan bahwa peminjaman berikut:</p>
    
    <table class="table">
        <tr>
            <th>Nama Lengkap</th>
            <td>{{ $loan->user->username }}</td>
        </tr>
        <tr>
            <th>Barang Peminjaman</th>
            <td>{{ $loan->tool->name }}</td>
        </tr>
        <tr>
            <th>Kuantitas</th>
            <td>{{ $loan->quantity }} Unit</td>
        </tr>
        <tr>
            <th>Waktu Peminjaman</th>
            <td>{{ \Carbon\Carbon::parse($loan->loan_date)->translatedFormat('d F Y, H:i') }} WIB</td>
        </tr>
        <tr>
            <th>Batas Pengembalian</th>
            <td>{{ \Carbon\Carbon::parse($loan->due_date)->translatedFormat('d F Y, H:i') }} WIB</td>
        </tr>
    </table>

    <div class="alert-denda">
        <p style="text-align: center; margin-top: 0;">Status: <strong>TERLAMBAT</strong></p>
        <span class="denda-text">TOTAL DENDA: Rp.{{ number_format($loan->fine_amount, 0, ',', '.') }}</span>
    </div>

    <p>Harap segera melakukan pelunasan denda dan pengembalian barang sesuai dengan ketentuan yang berlaku di ToolsHub.</p>

    <div class="footer-section clearfix">
        <div class="ttd">
            <p>Dicetak pada, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p>Petugas ToolsHub,</p>
            <div class="space"></div>
            <p class="nama-petugas">( Administrator )</p>
            <p>NIP. 1987654321012345</p>
        </div>
    </div>

</body>
</html>