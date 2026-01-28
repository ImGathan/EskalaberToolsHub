@extends('_admin._layout.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-6 py-4">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            @php
                $hour = date('H');
                $greeting = 'Selamat Malam';
                if ($hour >= 5 && $hour < 11) $greeting = 'Selamat Pagi';
                elseif ($hour >= 11 && $hour < 15) $greeting = 'Selamat Siang';
                elseif ($hour >= 15 && $hour < 18) $greeting = 'Selamat Sore';
            @endphp
            <h1 class="text-2xl font-bold text-gray-800 dark:text-neutral-200">
                {{ $greeting }}, {{ Auth::user()->username ?? 'Admin' }}! 👋
            </h1>
            <p class="text-sm text-gray-500 dark:text-neutral-500">
                Pantau performa dan aktivitas peminjaman hari ini.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800/30 dark:text-blue-500">
                <span class="size-2 inline-block rounded-full bg-blue-800 dark:bg-blue-500"></span>
                Sistem Online: {{ date('d M, Y') }}
            </span>
        </div>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <div class="flex flex-col bg-white border border-gray-200 shadow-sm rounded-2xl dark:bg-neutral-800 dark:border-neutral-700">
            <div class="p-4 md:p-5">
                <div class="flex items-center gap-x-2">
                    <p class="text-xs uppercase text-gray-500 font-semibold">Total Pengguna</p>
                </div>
                <div class="mt-1 flex items-center gap-x-2">
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-neutral-200">{{ $totalPengguna }}</h3>
                    <span class="text-gray-400 text-sm font-medium">Pengguna</span>
                </div>
            </div>
        </div>

        <div class="flex flex-col bg-white border border-gray-200 shadow-sm rounded-2xl dark:bg-neutral-800 dark:border-neutral-700">
            <div class="p-4 md:p-5">
                <div class="flex items-center gap-x-2">
                    <p class="text-xs uppercase text-gray-500 font-semibold">Barang Tersedia</p>
                </div>
                <div class="mt-1 flex items-center gap-x-2">
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-neutral-200">{{ $totalBarang }}</h3>
                    <span class="text-gray-400 text-sm">Unit</span>
                </div>
            </div>
        </div>

        <div class="flex flex-col bg-white border border-gray-200 shadow-sm rounded-2xl dark:bg-neutral-800 dark:border-neutral-700">
            <div class="p-4 md:p-5">
                <div class="flex items-center gap-x-2">
                    <p class="text-xs uppercase text-gray-500 font-semibold">Sedang Dipinjam</p>
                </div>
                <div class="mt-1 flex items-center gap-x-2">
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-neutral-200">{{ $totalPeminjaman }}</h3>
                    <span class="text-gray-400 text-sm">Unit</span>
                </div>
            </div>
        </div>

        <div class="flex flex-col bg-white border border-gray-200 shadow-sm rounded-2xl dark:bg-neutral-800 dark:border-neutral-700">
            <div class="p-4 md:p-5">
                <div class="flex items-center gap-x-2">
                    <p class="text-xs uppercase text-red-500 font-bold">Pengembalian Terlambat</p>
                </div>
                <div class="mt-1 flex items-center gap-x-2">
                    <h3 class="text-xl sm:text-2xl font-bold text-red-600 dark:text-red-500">{{ $totalPengembalianTerlambat }}</h3>
                    <span class="text-xs text-gray-400">Perlu Dicek</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-4 sm:gap-6">
        <div class="p-4 md:p-5 min-h-[400px] flex flex-col bg-white border border-gray-200 shadow-sm rounded-2xl dark:bg-neutral-800 dark:border-neutral-700">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-sm font-semibold text-gray-800 dark:text-neutral-200 uppercase">Tren Peminjaman Bulanan</h2>
            </div>
            <div id="hs-curved-area-line-chart"></div>
        </div>

        <div class="p-4 md:p-5 min-h-[400px] flex flex-col bg-white border border-gray-200 shadow-sm rounded-2xl dark:bg-neutral-800 dark:border-neutral-700">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-sm font-semibold text-gray-800 dark:text-neutral-200 uppercase">Kategori Barang Paling Laku</h2>
            </div>
            <div id="hs-single-bar-chart"></div>
        </div>
    </div>

    <div class="flex flex-col bg-white border border-gray-200 shadow-sm rounded-2xl dark:bg-neutral-800 dark:border-neutral-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700 flex justify-between items-center">
            <h2 class="text-lg font-bold text-gray-800 dark:text-neutral-200">Aktivitas Terbaru</h2>
            <a href="{{ route('admin.activity_logs.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-500">Lihat Semua</button>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700 text-sm">
                <thead class="bg-gray-50 dark:bg-neutral-700/50">
                    <tr>
                        <th class="px-6 py-3 text-start font-medium text-gray-500">Tanggal</th>
                        <th class="px-6 py-3 text-start font-medium text-gray-500">Aktivitas</th>
                        <th class="px-6 py-3 text-start font-medium text-gray-500">Deskripsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                    @foreach ($activityLogs as $activityLog)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-800 dark:text-neutral-200">{{ $activityLog->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-600 dark:text-neutral-400">{{ $activityLog->activity }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $activityLog->description }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    window.addEventListener('load', () => {
        // Ambil data dari Laravel (Blade)
        const labelsPeminjaman = @json($chartLabels ?? []);
        const dataPeminjaman = @json($chartData ?? []);
        const labelsKategori = @json($kategoriLabels ?? []);
        const dataKategori = @json($kategoriData ?? []);

        // 1. Line Chart (Tren Peminjaman)
        if (document.querySelector("#hs-curved-area-line-chart")) {
            new ApexCharts(document.querySelector("#hs-curved-area-line-chart"), {
                chart: {
                    height: 300,
                    type: 'area',
                    toolbar: { show: false },
                    zoom: { enabled: false }
                },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 3 },
                colors: ['#3b82f6'],
                series: [{
                    name: 'Total Pinjam',
                    data: dataPeminjaman
                }],
                xaxis: {
                    categories: labelsPeminjaman,
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.4,
                        opacityTo: 0.1,
                        stops: [0, 90, 100]
                    }
                },
                grid: { borderColor: '#e5e7eb', strokeDashArray: 5 }
            }).render();
        }

        // 2. Bar Chart (Kategori Barang)
        if (document.querySelector("#hs-single-bar-chart")) {
            new ApexCharts(document.querySelector("#hs-single-bar-chart"), {
                chart: {
                    height: 300,
                    type: 'bar',
                    toolbar: { show: false }
                },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        columnWidth: '50%',
                        distributed: true // Membuat warna tiap bar berbeda jika mau
                    }
                },
                dataLabels: { enabled: false },
                legend: { show: false },
                colors: ['#3b82f6', '#22c55e', '#f59e0b', '#ef4444', '#8b5cf6'],
                series: [{
                    name: 'Jumlah Unit',
                    data: dataKategori
                }],
                xaxis: {
                    categories: labelsKategori,
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                grid: { borderColor: '#e5e7eb', strokeDashArray: 5 }
            }).render();
        }
    });
</script>
@endsection