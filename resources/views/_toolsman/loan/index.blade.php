@extends('_toolsman._layout.app')

@section('title', 'Data Peminjaman')

@section('content')
<div class="grid gap-3 md:flex md:justify-between md:items-center py-4">
    <div>
        <h1 class="text-2xl font-extrabold text-gray-800 dark:text-neutral-200 mb-1">
            Data Peminjaman User
        </h1>
        <p class="text-md text-gray-400 dark:text-neutral-400">
            Kelola persetujuan, pemantauan, dan riwayat peminjaman barang.
        </p>
    </div>
</div>

{{-- Navigation Tabs --}}
<div class="border-b border-gray-200 dark:border-neutral-700 mb-6">
    <nav class="flex space-x-4 overflow-x-auto no-scrollbar" aria-label="Tabs" role="tablist">
        
        <a href="{{ route('toolsman.loans.index', ['status' => 'pending']) }}"
            class="py-4 px-1 inline-flex items-center gap-x-2 border-b-2 {{ request('status', 'pending') == 'pending' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-blue-600' }} text-sm font-medium whitespace-nowrap flex-shrink-0">
            @include('_toolsman._layout.icons.sidebar.pending_loan')
            Konfirmasi Peminjaman
            <span class="inline-flex items-center py-0.5 px-1.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-neutral-700 dark:text-neutral-300">
                {{ $countPending ?? 0 }}
            </span>
        </a>

        <a href="{{ route('toolsman.loans.index', ['status' => 'on_loan']) }}"
            class="py-4 px-1 inline-flex items-center gap-x-2 border-b-2 {{ request('status') == 'on_loan' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-blue-600' }} text-sm font-medium whitespace-nowrap flex-shrink-0">
            @include('_toolsman._layout.icons.sidebar.on_loan')
            Dalam Peminjaman
            <span class="inline-flex items-center py-0.5 px-1.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-neutral-700 dark:text-neutral-300">
                {{ $countOnLoan ?? 0 }}
            </span>
        </a>

        <a href="{{ route('toolsman.loans.index', ['status' => 'history']) }}"
            class="py-4 px-1 inline-flex items-center gap-x-2 border-b-2 {{ request('status') == 'history' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-blue-600' }} text-sm font-medium whitespace-nowrap flex-shrink-0">
            @include('_toolsman._layout.icons.sidebar.loan_history')
            Riwayat Peminjaman
        </a>

    </nav>
</div>


<div class="flex flex-col">
    {{-- Bagian Form Search tetap ada di sini --}}
    <div class="px-2 pb-4">
        <form action="{{ route('toolsman.loans.index') }}" method="GET" navigate-form
            class="flex flex-col sm:flex-row gap-3">
            
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
                    <a href="{{ route('toolsman.tools.index') }}"
                        class="py-2 px-3 inline-flex items-center gap-x-1 text-sm font-semibold rounded-lg border border-gray-200 bg-white text-gray-800 hover:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300">
                        @include('_toolsman._layout.icons.reset')
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Table Area --}}
    <div class="overflow-x-auto border border-gray-200 rounded-lg dark:border-neutral-700">
        <table class="w-full divide-y divide-gray-200 dark:divide-neutral-700">
            <thead class="bg-gray-50 dark:bg-neutral-800">
                <tr>
                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">User & Barang</th>
                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">Tanggal Pinjam</th>
                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">Batas Kembali</th>
                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">Status</th>
                    <th class="px-6 py-3 text-end text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                @forelse($loans as $loan)
                <tr class="hover:bg-gray-50 dark:hover:bg-neutral-700">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-gray-800 dark:text-neutral-200">{{ $loan->user->username }}</span>
                            <span class="text-xs text-gray-500">{{ $loan->tool->name }} ({{ $loan->quantity }} Unit)</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                        {{ \Carbon\Carbon::parse($loan->loan_date)->translatedFormat('d F Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                        {{ \Carbon\Carbon::parse($loan->due_date)->translatedFormat('d F Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                        <div class="flex flex-col">
                            @if($loan->status === 'approve' && $loan->fine_amount > 0)
                                <span class="inline-flex items-center py-1 rounded-full text-sm font-medium {{ $loan->status_color }}">Dalam Peminjaman ({{ $loan->keterangan_status }})</span>
                            @elseif($loan->status === 'approve')
                                <span class="inline-flex items-center py-1 rounded-full text-sm font-medium {{ $loan->status_color }}">{{ $loan->keterangan_status }}</span>
                            @elseif($loan->status === 'returned' && $loan->fine_amount > 0)
                                <span class="inline-flex items-center py-1 rounded-full text-sm font-medium {{ $loan->status_color }}">Dikembalikan {{ $loan->keterangan_status }}</span>
                            @elseif($loan->status === 'returned')
                                <span class="inline-flex items-center py-1 rounded-full text-sm font-medium {{ $loan->status_color }}">{{ $loan->keterangan_status }}</span>
                            @else
                                <span class="inline-flex items-center py-1 rounded-full text-sm font-medium {{ $loan->status_color }}">{{ $loan->keterangan_status }}</span>
                            @endif

                            @if($loan->status === 'returned' && $loan->fine_amount > 0)
                                <span class="text-xs text-gray-500">Denda Rp.{{ number_format($loan->fine_amount, 0, ',', '.') }}</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                        <div class="flex justify-end items-center gap-x-2">
                            @if($loan->status === 'pending')
                                {{-- Tombol Setujui --}}
                                <form action="{{ route('toolsman.loans.approve', $loan->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                        class="py-1.5 px-3 inline-flex items-center gap-x-1 text-xs font-bold rounded-lg border border-transparent bg-blue-100 text-blue-600 hover:bg-blue-200 dark:bg-blue-900/30 dark:text-blue-500 cursor-pointer">
                                        Setujui
                                    </button>
                                </form>

                                {{-- Tombol Tolak --}}
                                <form action="{{ route('toolsman.loans.reject', $loan->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                        class="py-1.5 px-3 inline-flex items-center gap-x-1 text-xs font-bold rounded-lg border border-transparent bg-red-100 text-red-600 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-500 cursor-pointer">
                                        Tolak
                                    </button>
                                </form>
                            @else
                                {{-- Tombol Dikembalikan --}}
                                @if($loan->status === 'returning')
                                <form action="{{ route('toolsman.loans.returned', $loan->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                        class="py-1.5 px-3 inline-flex items-center gap-x-1 text-xs font-bold rounded-lg border border-transparent bg-blue-100 text-blue-600 hover:bg-blue-200 dark:bg-blue-900/30 dark:text-blue-500 cursor-pointer">
                                        Konfirmasi Pengembalian
                                    </button>
                                </form>
                                @endif
                            
                                {{-- Tombol Detail untuk status selain pending --}}
                                <a navigate href="{{ route('toolsman.loans.index', $loan->id) }}" 
                                    class="p-2 inline-flex items-center rounded-lg border border-gray-200 bg-white text-gray-800 hover:bg-gray-100 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300" title="View">
                                    @include('_admin._layout.icons.view_detail')
                                </a>

                                <div class="hs-dropdown relative inline-flex">
                                    <button id="hs-dropdown-custom-icon-trigger" type="button" class="hs-dropdown-toggle p-2 inline-flex justify-center items-center gap-2 rounded-lg border border-gray-200 bg-white text-gray-400 shadow-sm hover:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:hover:bg-neutral-700">
                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg>
                                    </button>

                                    <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-60 bg-white shadow-md rounded-lg p-2 mt-2 dark:bg-neutral-800 dark:border dark:border-neutral-700 dark:divide-neutral-700 z-30" aria-labelledby="hs-dropdown-custom-icon-trigger">
                                        
                                        @if($loan->fine_amount > 0)
                                        <a href="{{ route('toolsman.loans.late-report', $loan->id) }}" class="flex items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300" href="#">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                            Cetak Laporan Keterlambatan (PDF)
                                        </a>
                                        @endif
                                        
                                        <form action="#" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="w-full flex items-center gap-x-3 py-2 px-3 rounded-lg text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                                Daftar Hitamkan User
                                            </button>
                                        </form>

                                        

                                    </div>
                                </div>

                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center">
                        <x-admin.empty-state />
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-8">
    {{ $loans->links() }}
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
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            const button = this.querySelector('button[type="submit"]');
            if (button) {
                // Tambahkan efek loading dan disable tombol
                button.disabled = true;
                button.classList.add('opacity-50', 'cursor-not-allowed');
                button.innerHTML = 'Memproses...';
            }
        });
    });
</script>
@endsection