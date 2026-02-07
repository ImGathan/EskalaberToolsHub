<!DOCTYPE html>
<html>
<head>
    <title>Stiker QR - {{ $tool->name }}</title>
    <style>
        /* Hilangkan semua margin default browser & PDF */
        @page {
            margin: 0;
        }
        html, body {
            margin: 0;
            padding: 0;
            width: 300pt;
            height: 300pt;
            overflow: hidden;
            font-family: 'Helvetica', sans-serif;
        }
        
        .stiker-container {
            width: 300pt;
            height: 300pt;
            position: relative; /* Menjadi patokan posisi elemen di dalamnya */
            text-align: center;
        }

        /* QR diletakkan secara absolut agar tidak mendorong footer */
        .qr-wrapper {
            position: absolute;
            top: 50pt;
            left: 40pt; /* (300pt - 220pt) / 2 = 40pt agar center */
            width: 220pt;
            height: 220pt;
        }

        .qr-wrapper img {
            width: 85%;
            height: 85%;
            display: block;
        }

        /* Footer dipaksa di bagian bawah */
        .footer {
            position: absolute;
            bottom: 15pt;
            width: 100%;
            left: 0;
        }

        .tool-name {
            font-weight: bold;
            font-size: 18pt;
            margin: 0;
            padding: 0 10pt;
            display: block;
            width: 280pt; /* Beri lebar sedikit di bawah lebar container */
            white-space: nowrap; /* Paksa teks tetap satu baris */
            overflow: hidden; /* Sembunyikan jika ada teks yang lolos dari limit */
            text-align: center;
        }

        .asset-id {
            font-size: 11pt;
            color: #555;
            margin-top: 2pt;
            display: block;
        }
    </style>
</head>
<body>
    <div class="stiker-container">
        <img src="{{ public_path('admin/images/logo.webp') }}" style="width: 110pt; height: auto; margin-top: 15pt;">
        <div class="qr-wrapper">
            <img src="data:image/svg+xml;base64,{{ $qrcode }}">
        </div>

        <div class="footer">
            <div class="tool-name">
                {{ \Illuminate\Support\Str::limit($tool->name, 25, '...') }}
            </div>
            <div class="asset-id">ID: #{{ str_pad($tool->id, 5, '0', STR_PAD_LEFT) }}</div>
        </div>
    </div>
</body>
</html>