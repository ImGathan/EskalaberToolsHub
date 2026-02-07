@extends('_admin._layout.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-6 py-4">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-neutral-200">
                Selamat Datang Kembali, {{ Auth::user()->username ?? 'Admin' }}! 👋
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
                <div class="inline-flex bg-gray-100 rounded-lg p-1 dark:bg-neutral-700">
                    <a href="?filter=day" class="px-3 py-1 text-xs rounded-md {{ request('filter') == 'day' ? 'bg-white shadow-sm text-blue-600' : 'text-gray-500' }}">Hari</a>
                    <a href="?filter=week" class="px-3 py-1 text-xs rounded-md {{ request('filter') == 'week' ? 'bg-white shadow-sm text-blue-600' : 'text-gray-500' }}">Minggu</a>
                    <a href="?filter=month" class="px-3 py-1 text-xs rounded-md {{ request('filter', 'month') == 'month' ? 'bg-white shadow-sm text-blue-600' : 'text-gray-500' }}">Bulan</a>
                </div>
            </div>
            <div class="w-full" style="min-height: 300px;">
                <div id="admin-trend-chart"></div>
            </div>
        </div>

        <div class="p-4 md:p-5 min-h-[400px] flex flex-col bg-white border border-gray-200 shadow-sm rounded-2xl dark:bg-neutral-800 dark:border-neutral-700">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-sm font-semibold text-gray-800 dark:text-neutral-200 uppercase">Kategori Barang Paling Laku</h2>
            </div>
            <div class="w-full" style="min-height: 300px;">
                <div id="admin-bar-chart"></div>
            </div>
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
    const initDashboardCharts = () => {
        // --- 1. Inisialisasi Chart Tren ---
        const trendEl = document.querySelector("#admin-trend-chart");
        if (trendEl) {
            trendEl.innerHTML = ''; // Penting untuk cegah lag/bug
            new ApexCharts(trendEl, {
                chart: { height: 320, type: 'area', toolbar: { show: false } },
                series: [{ name: 'Pinjaman', data: @json($chartData) }],
                xaxis: { categories: @json($chartLabels) },
                colors: ['#3b82f6'],
                stroke: { curve: 'smooth', width: 3 },
                dataLabels: { enabled: false },
                fill: { type: 'gradient', gradient: { opacityFrom: 0.4, opacityTo: 0.1 } }
            }).render();
        }

        // --- 2. Inisialisasi Chart Kategori ---
        const barEl = document.querySelector("#admin-bar-chart");
        if (barEl) {
            barEl.innerHTML = '';
            new ApexCharts(barEl, {
                chart: { height: 320, type: 'bar', toolbar: { show: false } },
                series: [{ 
                    name: 'Total Dipinjam', // Ubah nama series agar lebih jelas
                    data: @json($kategoriData ?? []) 
                }],
                xaxis: { categories: @json($kategoriLabels ?? []) },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return val + " Kali Dipinjam"
                        }
                    }
                },
                plotOptions: { bar: { borderRadius: 4, distributed: true, columnWidth: '50%' } },
                colors: ['#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6', '#6366f1'],
                legend: { show: false }
            }).render();
        }
    };

    if (window.chartWatcher) clearInterval(window.chartWatcher);

    window.chartWatcher = setInterval(() => {
        const trendElem = document.querySelector("#admin-trend-chart");
        const barElem = document.querySelector("#admin-bar-chart");

        // Jika elemen ada di layar tapi isinya masih kosong (belum digambar)
        if (trendElem && trendElem.innerHTML === '') {
            console.log("Admin Chart: Menggambar ulang...");
            initDashboardCharts();
        }
    }, 500); // Cek setiap 0.5 detik

    // Tetap jaga-jaga dengan event standar
    document.addEventListener("turbo:load", initDashboardCharts);
    document.addEventListener("livewire:navigated", initDashboardCharts);

</script>
@endsection