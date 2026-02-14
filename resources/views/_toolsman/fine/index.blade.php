@extends('_toolsman._layout.app')

@section('title', 'Manajemen Denda Pengguna')

@section('content')
{{-- Header Section --}}
<div class="grid gap-3 md:flex md:justify-between md:items-center py-4">
    <div>
        <h1 class="text-2xl font-extrabold text-gray-800 dark:text-neutral-200 mb-1">
            Manajemen Denda Pengguna
        </h1>
        <p class="text-md text-gray-400 dark:text-neutral-400">
            Manajemen pembayaran denda yang diberikan kepada pengguna.
        </p>
    </div>

</div>

<div class="border-b border-gray-200 dark:border-neutral-700 mb-6">
    <nav class="flex space-x-4 overflow-x-auto no-scrollbar" aria-label="Tabs" role="tablist">
        
        <a href="{{ route('toolsman.fines.index', ['fine_status' => 0]) }}"
            class="py-4 px-1 inline-flex items-center gap-x-2 border-b-2 {{ request('fine_status', 0) == 0 ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-blue-600' }} text-sm font-medium whitespace-nowrap flex-shrink-0">
            @include('_toolsman._layout.icons.belum_bayar')
            Belum Bayar
            <span class="inline-flex items-center py-0.5 px-1.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-neutral-700 dark:text-neutral-300">
                {{ $countBelumBayar ?? 0 }}
            </span>
        </a>

        <a href="{{ route('toolsman.fines.index', ['fine_status' => 1]) }}"
            class="py-4 px-1 inline-flex items-center gap-x-2 border-b-2 {{ request('fine_status') == 1 ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-blue-600' }} text-sm font-medium whitespace-nowrap flex-shrink-0">
            @include('_toolsman._layout.icons.lunas')
            Pembayaran Lunas
            <span class="inline-flex items-center py-0.5 px-1.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-neutral-700 dark:text-neutral-300">
                {{ $countLunas ?? 0 }}
            </span>
        </a>

    </nav>
</div>

<div class="flex flex-col">
    {{-- Search & Filter Section --}}
    <div class="px-2 pb-4">
        <form action="{{ route('toolsman.fines.index') }}" method="GET" navigate-form
            class="flex flex-col sm:flex-row gap-3">
            
            <input type="hidden" name="fine_status" value="{{ request('fine_status', 0) }}">

            <div class="sm:w-80">
                <input type="text" name="keywords" id="keywords" value="{{ $keywords ?? '' }}"
                    class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400"
                    placeholder="Cari nama barang atau pengguna...">
            </div>

            <div class="flex gap-x-2">
                <button type="submit"
                    class="py-2 px-3 inline-flex items-center gap-x-1 text-sm font-semibold rounded-lg bg-blue-600 text-white hover:bg-blue-700 cursor-pointer">
                    @include('_toolsman._layout.icons.search')
                    Cari
                </button>
                
                @if (!empty($keywords))
                    <a href="{{ route('toolsman.fines.index') }}"
                        class="py-2 px-3 inline-flex items-center gap-x-1 text-sm font-semibold rounded-lg border border-gray-200 bg-white text-gray-800 hover:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300">
                        @include('_toolsman._layout.icons.reset')
                        Reset
                    </a>
                @endif

                @if (request('fine_status') == 1)
                <a href="{{ route('toolsman.fines.export-paid-fine') }}" 
                    class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-emerald-600 text-white hover:bg-emerald-700 disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:ring-2 focus:ring-emerald-500 shadow-sm transition-all">
                    <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/>
                    </svg>
                    Export Excel
                </a>
                @endif

            </div>
        </form>
    </div>

    {{-- Table Section --}}
    <div class="overflow-x-auto border border-gray-200 rounded-lg dark:border-neutral-700">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
            <thead class="bg-gray-50 dark:bg-neutral-800">
                <tr>
                    <th scope="col" class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">Nama Pengguna</th>
                    <th scope="col" class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">Barang yang dipinjam</th>
                    <th scope="col" class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">Hari Keterlambatan</th>
                    <th scope="col" class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">Total Denda</th>
                    @if (($fineLoans->first()?->fine_status ?? null) == 1)
                        <th scope="col" class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">Jumlah Bayar</th>
                    @endif
                    <th scope="col" class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">Status</th>
                    <th scope="col" class="px-6 py-3 text-end text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                @forelse($fineLoans as $fineLoan)
                    <tr class="hover:bg-gray-50 dark:hover:bg-neutral-700/50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-bold text-gray-800 dark:text-neutral-200">
                                {{ $fineLoan->user->username }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-gray-800 dark:text-neutral-200">{{ $fineLoan->tool->name }}</span>
                                <span class="text-xs text-gray-500 dark:text-neutral-500">{{ $fineLoan->quantity }} Unit</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                            {{ $fineLoan->hari_terlambat }} Hari
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200 font-bold">
                            Rp. {{ number_format($fineLoan->fine_amount, 0, ',', '.') }}
                        </td>
                        @if (($fineLoans->first()?->fine_status ?? null) == 1)
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200 font-bold">
                            Rp. {{ number_format($fineLoan->amount_paid, 0, ',', '.') }}
                        </td>
                        @endif
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusClasses = match((bool)$fineLoan->fine_status) {
                                    true  => 'bg-teal-100 text-teal-800 dark:bg-teal-500/10 dark:text-teal-500',
                                    false => 'bg-red-100 text-red-500 dark:bg-red-500/10 dark:text-red-500',
                                };
                            @endphp
                            <span class="inline-flex items-center py-1 px-2.5 rounded-full text-xs font-bold {{ $statusClasses }}">
                                {{ $fineLoan->fine_status == 1 ? 'Lunas' : 'Belum Bayar' }}
                            </span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                            <div class="flex justify-end items-center gap-x-2">

                                @if($fineLoan->fine_status == 0)
                                <form action="{{ route('toolsman.fines.pay', $fineLoan->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                            class="py-1.5 px-3 inline-flex items-center gap-x-1 text-xs font-bold rounded-lg border border-transparent bg-blue-100 text-blue-600 hover:bg-blue-200 dark:bg-blue-900/30 dark:text-blue-500 cursor-pointer">
                                            Proses Bayar
                                        </button>
                                    </form>
                                @endif

                                <div class="hs-dropdown relative inline-flex">
                                    <button id="hs-dropdown-custom-icon-trigger" type="button" class="hs-dropdown-toggle p-2 inline-flex justify-center items-center gap-2 rounded-lg border border-gray-200 bg-white text-gray-400 shadow-sm hover:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:hover:bg-neutral-700">
                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg>
                                    </button>

                                    <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-60 bg-white shadow-md rounded-lg p-2 mt-2 dark:bg-neutral-800 dark:border dark:border-neutral-700 dark:divide-neutral-700 z-30" aria-labelledby="hs-dropdown-custom-icon-trigger">
                                        
                                        @if($fineLoan->fine_status == 0)
                                        <a href="{{ route('toolsman.fines.unpaid-report', $fineLoan->id) }}" class="flex items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300" href="#">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                            Cetak Laporan Denda (PDF)
                                        </a>
                                        @endif

                                        @if($fineLoan->fine_status == 1)
                                        <a href="{{ route('toolsman.fines.paid-report', $fineLoan->id) }}" class="flex items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300" href="#">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                            Cetak Laporan Pembayaran (PDF)
                                        </a>
                                        @endif
                                        
                                    </div>
                                </div>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center">
                            <x-admin.empty-state />
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

<div class="mt-8">
    {{ $fineLoans->links() }}
</div>

<style>
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>

<script>
    function setDeleteData(id, name) {
        document.getElementById('delete-item-name').textContent = name;
        // Pastikan URL action delete mengarah ke route toolsman yang benar
        document.getElementById('delete-form').action = '{{ url("toolsman/tools/delete") }}/' + id;
    }
</script>
@endsection