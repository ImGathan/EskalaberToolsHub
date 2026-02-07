@extends('_user._layout.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6 py-4">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-neutral-200">
                Selamat Datang Kembali, {{ Auth::user()->username ?? 'User' }}! 👋
            </h1>
            <p class="text-sm text-gray-500 dark:text-neutral-500">
                Pantau aktivitas peminjaman anda.
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
                    <p class="text-xs uppercase text-gray-500 font-semibold">Total Peminjaman Anda</p>
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
                    <p class="text-xs uppercase text-gray-500 font-semibold">Dalam Peminjaman</p>
                </div>
                <div class="mt-1 flex items-center gap-x-2">
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-neutral-200">{{ $totalDalamPeminjaman }}</h3>
                    <span class="text-gray-400 text-sm">Unit</span>
                </div>
            </div>
        </div>

        <div class="flex flex-col bg-white border border-gray-200 shadow-sm rounded-2xl dark:bg-neutral-800 dark:border-neutral-700">
            <div class="p-4 md:p-5">
                <div class="flex items-center gap-x-2">
                    <p class="text-xs uppercase text-red-500 font-bold">Tagihan Keterlambatan</p>
                </div>
                <div class="mt-1 flex items-center gap-x-2">
                    <h3 class="text-xl sm:text-2xl font-bold text-red-600 dark:text-red-500">{{ $totalTagihanKeterlambatan }}</h3>
                    <span class="text-xs text-gray-400">Cek Kembali</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-4 sm:gap-6">
        <div class="p-4 md:p-5 min-h-[400px] flex flex-col bg-white border border-gray-200 shadow-sm rounded-2xl dark:bg-neutral-800 dark:border-neutral-700">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-sm font-semibold text-gray-800 dark:text-neutral-200 uppercase">Peminjaman Bulanan Anda</h2>
                <div class="inline-flex bg-gray-100 rounded-lg p-1 dark:bg-neutral-700">
                    <a href="?filter=day" class="px-3 py-1 text-xs rounded-md {{ request('filter') == 'day' ? 'bg-white shadow-sm text-blue-600' : 'text-gray-500' }}">Hari</a>
                    <a href="?filter=week" class="px-3 py-1 text-xs rounded-md {{ request('filter') == 'week' ? 'bg-white shadow-sm text-blue-600' : 'text-gray-500' }}">Minggu</a>
                    <a href="?filter=month" class="px-3 py-1 text-xs rounded-md {{ request('filter', 'month') == 'month' ? 'bg-white shadow-sm text-blue-600' : 'text-gray-500' }}">Bulan</a>
                </div>
            </div>
            <div class="w-full" style="min-height: 300px;">
                <div id="hs-curved-area-line-chart"></div>
            </div>
        </div>

        <div class="p-4 md:p-5 min-h-[400px] flex flex-col bg-white border border-gray-200 shadow-sm rounded-2xl dark:bg-neutral-800 dark:border-neutral-700">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-sm font-semibold text-gray-800 dark:text-neutral-200 uppercase">Kategori Barang Paling Sering Dipinjam</h2>
            </div>
            <div class="w-full" style="min-height: 300px;">
                <div id="hs-single-bar-chart"></div>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    const initDashboardCharts = () => {
        // --- 1. DATA DARI LARAVEL ---
        const labelsPeminjaman = @json($chartLabels ?? []);
        const dataPeminjaman = @json($chartData ?? []);
        const labelsKategori = @json($kategoriLabels ?? []);
        const dataKategori = @json($kategoriData ?? []);

        // --- 2. LOGIKA GRAFIK TREN (Disamakan dengan Admin: Ada Gradient) ---
        const trendElement = document.querySelector("#hs-curved-area-line-chart");
        if (trendElement) {
            trendElement.innerHTML = ''; 
            new ApexCharts(trendElement, {
                chart: {
                    height: 320, // Samakan tinggi dengan admin
                    type: 'area',
                    toolbar: { show: false },
                    zoom: { enabled: false }
                },
                series: [{ name: 'Pinjaman Anda', data: dataPeminjaman }],
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 3 },
                colors: ['#3b82f6'],
                xaxis: { categories: labelsPeminjaman },
                fill: { 
                    type: 'gradient', 
                    gradient: { opacityFrom: 0.4, opacityTo: 0.1 } 
                },
                grid: { padding: { left: 0, right: 0 } }
            }).render();
        }

        // --- 3. LOGIKA GRAFIK KATEGORI (Disamakan dengan Admin: Ada Tooltip) ---
        const categoryElement = document.querySelector("#hs-single-bar-chart");
        if (categoryElement) {
            categoryElement.innerHTML = ''; 
            new ApexCharts(categoryElement, {
                chart: {
                    height: 320, // Samakan tinggi dengan admin
                    type: 'bar',
                    toolbar: { show: false }
                },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        columnWidth: '50%',
                        distributed: true 
                    }
                },
                series: [{ 
                    name: 'Total Dipinjam', 
                    data: dataKategori 
                }],
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return val + " Kali Anda Pinjam"
                        }
                    }
                },
                xaxis: { 
                    categories: labelsKategori,
                    labels: { style: { fontSize: '10px' } }
                },
                colors: ['#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6'],
                legend: { show: false },
                grid: { padding: { left: 0, right: 0 } }
            }).render();
        }
    };

    if (window.chartWatcher) clearInterval(window.chartWatcher);

    window.chartWatcher = setInterval(() => {
        const trendElem = document.querySelector("#hs-curved-area-line-chart");
        const categoryElem = document.querySelector("#hs-single-bar-chart");

        // Jika elemen ada di layar tapi isinya masih kosong (belum digambar)
        if (trendElem && trendElem.innerHTML === '') {
            console.log("Satpam Chart: Menggambar ulang...");
            initDashboardCharts();
        }
    }, 500); 

    document.addEventListener("turbo:load", initDashboardCharts);
    document.addEventListener("livewire:navigated", initDashboardCharts);
    
</script>
@endsection