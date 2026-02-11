@extends('_toolsman._layout.app')

@section('title', 'Toolsman Dashboard')

@section('content')
<div class="space-y-6 py-4">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-neutral-200 leading-tight">
                Selamat Datang Kembali, {{ Auth::user()->username ?? 'Toolsman' }}! 👋
            </h1>
            <h3 class="text-lg text-gray-600 dark:text-neutral-400 font-semibold pb-3">
                Toolsman {{ $categoryName }}
            </h3>
            <p class="text-sm text-gray-500 dark:text-neutral-500">
                Pantau performa dan aktivitas peminjaman barang sesuai kategori.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800/30 dark:text-blue-500">
                <span class="size-2 inline-block rounded-full bg-blue-800 dark:bg-blue-500"></span>
                Sistem Online: {{ date('d M, Y') }}
            </span>
        </div>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
       

        <div class="flex flex-col bg-white border border-gray-200 shadow-sm rounded-2xl dark:bg-neutral-800 dark:border-neutral-700">
            <div class="p-4 md:p-5">
                <div class="flex items-center gap-x-2">
                    <p class="text-xs uppercase text-gray-500 font-semibold">Barang Anda</p>
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

        <a href="{{ route('toolsman.fines.index') }}" class="flex flex-col bg-white border border-gray-200 shadow-sm rounded-2xl dark:bg-neutral-800 dark:border-neutral-700">
            <div class="p-4 md:p-5">
                <div class="flex items-center gap-x-2">
                    <p class="text-xs uppercase text-red-500 font-bold">Tagihan Pengguna</p>
                </div>
                <div class="mt-1 flex items-center gap-x-2">
                    <h3 class="text-xl sm:text-2xl font-bold text-red-600 dark:text-red-500">{{ $totalDendaKeterlambatan }}</h3>
                    <span class="text-xs text-gray-400">Perlu Dicek</span>
                </div>
            </div>
        </a>
    </div>

    <div class="grid lg:grid-cols-1 gap-4 sm:gap-6">
        <div class="p-4 md:p-5 min-h-[400px] flex flex-col bg-white border border-gray-200 shadow-sm rounded-2xl dark:bg-neutral-800 dark:border-neutral-700">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-sm font-semibold text-gray-800 dark:text-neutral-200 uppercase">Tren Peminjaman Bulanan</h2>
                <div class="inline-flex bg-gray-100 rounded-lg p-1 dark:bg-neutral-700">
                    <a href="?filter=day" class="px-3 py-1 text-xs rounded-md {{ request('filter') == 'day' ? 'bg-white shadow-sm text-blue-600' : 'text-gray-500' }}">Hari</a>
                    <a href="?filter=week" class="px-3 py-1 text-xs rounded-md {{ request('filter') == 'week' ? 'bg-white shadow-sm text-blue-600' : 'text-gray-500' }}">Minggu</a>
                    <a href="?filter=month" class="px-3 py-1 text-xs rounded-md {{ request('filter', 'month') == 'month' ? 'bg-white shadow-sm text-blue-600' : 'text-gray-500' }}">Bulan</a>
                </div>
            </div>
            <div class="w-full" style="min-height: 300px;">
                <div id="hs-toolsman-trend-chart"></div>
            </div>
        </div>

    </div>

    

</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    const initToolsmanCharts = () => {
        const trendElement = document.querySelector("#hs-toolsman-trend-chart");
        const barElement = document.querySelector("#hs-toolsman-bar-chart");

        // 1. Trend Chart dengan Angka Mengapung
        if (trendElement && trendElement.innerHTML === '') {
            new ApexCharts(trendElement, {
                chart: { height: 320, type: 'area', toolbar: { show: false } },
                series: [{ name: 'Aktivitas', data: @json($chartData ?? []) }],
                xaxis: { categories: @json($chartLabels ?? []) },
                stroke: { curve: 'smooth', width: 3 },
                // --- INI BAGIAN ANGKA MENGAPUNG ---
                dataLabels: { 
                    enabled: false, 
                    style: { colors: ['#3b82f6'] },
                    background: { enabled: true, padding: 4, borderRadius: 2, borderWidth: 0, opacity: 0.9 }
                },
                // ----------------------------------
                colors: ['#3b82f6'],
                fill: { type: 'gradient', gradient: { opacityFrom: 0.4, opacityTo: 0.1 } }
            }).render();
        }

        // 2. Bar Chart dengan Angka Mengapung
        if (barElement && barElement.innerHTML === '') {
            new ApexCharts(barElement, {
                chart: { height: 320, type: 'bar', toolbar: { show: false } },
                series: [{ name: 'Jumlah', data: @json($kategoriData ?? []) }],
                xaxis: { categories: @json($kategoriLabels ?? []) },
                // --- INI BAGIAN ANGKA MENGAPUNG DI BAR ---
                dataLabels: { 
                    enabled: true,
                    offsetY: -20, // Angka naik ke atas bar
                    style: { fontSize: '12px', colors: ["#304758"] }
                },
                plotOptions: { 
                    bar: { 
                        borderRadius: 4, 
                        distributed: true,
                        dataLabels: { position: 'top' } // Pastikan posisi di atas
                    } 
                },
                // -----------------------------------------
                colors: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6']
            }).render();
        }
    };

    // Observer tetap sama (Sistem Pengamat)
    const toolsmanObserver = new MutationObserver((mutations) => {
        if (document.querySelector("#hs-toolsman-trend-chart")?.innerHTML === '') {
            initToolsmanCharts();
        }
    });

    toolsmanObserver.observe(document.body, { childList: true, subtree: true });

    document.addEventListener("turbo:load", initToolsmanCharts);
    document.addEventListener("livewire:navigated", initToolsmanCharts);
    document.addEventListener("DOMContentLoaded", initToolsmanCharts);
</script>
@endsection