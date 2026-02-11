@extends('_user._layout.app')

@section('title', 'Riwayat Peminjaman')

@section('content')
<div class="grid gap-3 md:flex md:justify-between md:items-center py-4">
    <div>
        <h1 class="text-2xl font-extrabold text-gray-800 dark:text-neutral-200 mb-1">
            Peminjaman Anda
        </h1>
        <p class="text-md text-gray-400 dark:text-neutral-400">
            Pantau status dan riwayat peminjaman barang Anda di sini.
        </p>
    </div>
</div>

<div class="flex flex-col">
    {{-- Search & Filter Section --}}
    <div class="px-2 pb-4">
        <form action="{{ route('user.loans.index') }}" method="GET" navigate-form
            class="flex flex-col sm:flex-row gap-3">
            
            <div class="sm:w-80">
                <input type="text" name="keywords" id="keywords" value="{{ $keywords ?? '' }}"
                    class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400"
                    placeholder="Cari nama barang atau kategori...">
            </div>

            <div class="sm:w-56">
                <select name="status"
                    class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                    <option value="all" {{ ($status ?? 'all') == 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="1" {{ ($status ?? '') == '1' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                    <option value="2" {{ ($status ?? '') == '2' ? 'selected' : '' }}>Dalam Peminjaman</option>
                    <option value="3" {{ ($status ?? '') == '3' ? 'selected' : '' }}>Dikembalikan</option>
                    <option value="4" {{ ($status ?? '') == '4' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <div class="flex gap-x-2">
                <button type="submit"
                    class="py-2 px-3 inline-flex items-center gap-x-1 text-sm font-semibold rounded-lg bg-blue-600 text-white hover:bg-blue-700 cursor-pointer">
                    @include('_user._layout.icons.search')
                    Cari
                </button>
                
                @if (!empty($keywords) || ($status ?? 'all') !== 'all')
                    <a href="{{ route('user.loans.index') }}"
                        class="py-2 px-3 inline-flex items-center gap-x-1 text-sm font-semibold rounded-lg border border-gray-200 bg-white text-gray-800 hover:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300">
                        @include('_user._layout.icons.reset')
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>


<div class="space-y-10">
    @forelse($groupedLoans as $date => $items)
        <div class="relative">
            {{-- Sticky Date Header --}}
            <div class="flex items-center gap-x-4 mb-5">
                <div class="flex-none">
                    <span class="inline-flex items-center py-1.5 px-3 rounded-lg text-xs font-bold bg-gray-100 text-gray-800 dark:bg-neutral-800 dark:text-neutral-200 border border-gray-200 dark:border-neutral-700 shadow-sm">
                        <svg class="size-3.5 me-2 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                        </svg>
                        {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
                    </span>
                </div>
                <div class="h-px bg-gray-200 dark:bg-neutral-700 flex-1"></div>
            </div>

            {{-- Loan Items List --}}
            <div class="grid gap-4">
                @foreach($items as $loan)
                <div class="group bg-white border border-gray-200 rounded-xl shadow-md hover:shadow-xl hover:shadow-blue-500/20 transition-all duration-300 dark:bg-neutral-800 dark:border-neutral-700 overflow-hidden">
                    <div class="flex flex-col md:flex-row">
                        
                        {{-- Image Section --}}
                        <div class="relative shrink-0 w-full h-52 md:h-auto md:w-64 lg:w-72 overflow-hidden bg-gray-100 dark:bg-neutral-900">
                            {{-- Kita buat gambar absolute agar dia TIDAK BISA mendorong tinggi container --}}
                            <img 
                                class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" 
                                src="{{ $loan->tool && $loan->tool->image ? asset('storage/' . $loan->tool->image) : asset('admin/images/empty-data.webp') }}" 
                                alt="Barang"
                            >
                            
                            {{-- Floating ID Badge (Muncul di semua ukuran sekarang biar rapi) --}}
                            <div class="absolute bottom-0 left-0 right-0 p-3 bg-gradient-to-t from-black/70 to-transparent">
                                <span class="text-[10px] text-white/90 font-mono font-bold">#L-{{ $loan->hash_id }}</span>
                            </div>
                        </div>

                        {{-- Info Section --}}
                        <div class="flex-1 p-5 md:p-6 flex flex-col">
                            {{-- Header: Stacked on mobile to give room for long names --}}
                            <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-3">
                                <div class="space-y-1">
                                    <span class="inline-flex items-center py-1 px-2 rounded-lg bg-blue-100 text-blue-700 text-[10px] font-bold uppercase tracking-wider dark:bg-blue-500/10 dark:text-blue-400">
                                        {{ $loan->tool->category->name ?? 'Kategori' }}
                                    </span>
                                    <h3 class="text-xl font-bold text-gray-800 dark:text-neutral-200 group-hover:text-blue-600 transition-colors">
                                        {{ $loan->tool->name ?? 'Barang Tidak Diketahui' }}
                                    </h3>
                                </div>

                                {{-- Status Badge (Logika ASLI Tanpa Perubahan) --}}
                                <div class="flex flex-col items-start sm:items-end gap-2 shrink-0">
                                    @if ($loan->status === 'approve' || $loan->status === 'returning' || ($loan->status === 'approve' && $loan->fine_amount > 0))
                                    <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-bold bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                        <span class="size-1.5 rounded-full bg-blue-600 "></span>
                                        @if ($loan->status === 'approve' && $loan->fine_amount > 0)
                                        Dalam Peminjaman ({{ $loan->keterangan_status }})
                                        @else
                                        {{ $loan->keterangan_status }}
                                        @endif
                                    </span>
                                    @elseif ($loan->status === 'returned' && $loan->fine_amount > 0)
                                    <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-bold bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-500">
                                        <span class="size-1.5 rounded-full bg-red-600 "></span>
                                        Dikembalikan {{ $loan->keterangan_status }}
                                    </span>
                                    @elseif ($loan->status === 'reject')
                                    <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-bold bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400">
                                        <span class="size-1.5 rounded-full bg-gray-600 "></span>
                                        {{ $loan->keterangan_status }}
                                    </span>
                                    @elseif ($loan->status === 'pending')
                                    <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                        <span class="size-1.5 rounded-full bg-yellow-600 "></span>
                                        {{ $loan->keterangan_status }}
                                    </span>
                                    @else
                                    <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-bold bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                        <span class="size-1.5 rounded-full bg-green-600 "></span>
                                        {{ $loan->keterangan_status }}
                                    </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Detail Info: Menggunakan grid yang konsisten --}}
                            <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-5">
                                <div class="flex flex-col sm:flex-row sm:items-center">
                                    <span class="text-[10px] sm:text-xs text-gray-400 dark:text-neutral-500 font-bold tracking-tight sm:mr-2">Jumlah</span>
                                    <span class="text-xs font-bold text-gray-700 dark:text-neutral-300">{{ $loan->quantity }} Unit</span>
                                </div>
                                <div class="flex flex-col sm:flex-row sm:items-center">
                                    <span class="text-[10px] sm:text-xs text-gray-400 dark:text-neutral-500 font-bold tracking-tight sm:mr-2">Lokasi</span>
                                    <span class="text-xs font-bold text-gray-700 dark:text-neutral-300 line-clamp-1">{{ $loan->tool->place->name ?? '-' }}</span>
                                </div>
                                <div class="flex flex-col sm:flex-row sm:items-center col-span-2 lg:col-span-1">
                                    <span class="text-[10px] sm:text-xs text-gray-400 dark:text-neutral-500 font-bold tracking-tight sm:mr-2">ID</span>
                                    <span class="text-xs font-mono font-bold text-blue-600 dark:text-blue-400">#L-{{ $loan->hash_id }}</span>
                                </div>
                            </div>

                            {{-- Footer Card: Stacked on mobile, space-between on desktop --}}
                            <div class="mt-auto pt-4 border-t border-gray-100 dark:border-neutral-700">
                                <div class="flex flex-col sm:flex-row gap-4 justify-between items-center">
                                    
                                    {{-- Sisi Kiri: Waktu Pengajuan --}}
                                    <div class="flex items-center gap-2 order-2 sm:order-1">
                                        <div class="size-1.5 rounded-full bg-gray-300 dark:bg-neutral-600"></div>
                                        <span class="text-[11px] text-gray-500 dark:text-neutral-400 font-medium">Diajukan pukul {{ $loan->created_at->format('H:i') }} WIB</span>
                                    </div>
                                    
                                    {{-- Sisi Kanan: Group Tombol Aksi --}}
                                    <div class="flex items-center gap-2 w-full sm:w-auto order-1 sm:order-2">
                                        
                                        {{-- Tombol Utility (Hanya saat Pending) --}}
                                        @if ($loan->status === 'pending')
                                        <div class="flex items-center gap-2 flex-1 sm:flex-none">
                                            <button type="button" onclick="setDeleteData('{{ $loan->id }}', '{{ $loan->tool->name }}')"
                                                data-hs-overlay="#delete-modal"
                                                class="flex-1 sm:flex-none p-2.5 inline-flex items-center justify-center rounded-xl border border-red-200 bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-900/20 dark:border-red-800 transition-colors">
                                                @include('_admin._layout.icons.trash')
                                            </button>
                                            <a navigate href="{{ route('user.loans.update', $loan->id) }}" 
                                                class="flex-1 sm:flex-none p-2.5 inline-flex items-center justify-center rounded-xl border border-blue-200 bg-blue-50 text-blue-600 hover:bg-blue-100 dark:bg-blue-900/20 dark:border-blue-800 transition-colors">
                                                @include('_admin._layout.icons.pencil')
                                            </a>
                                        </div>
                                        @endif

                                        {{-- Tombol Aksi Utama --}}
                                        <div class="flex flex-col sm:flex-row items-center gap-2 w-full">
                                            @if ($loan->status === 'approve')
                                            <form action="{{ route('user.loans.returning', $loan->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                class="w-full sm:w-auto flex-1 sm:flex-none py-2 px-4 inline-flex items-center justify-center gap-x-2 text-xs font-bold rounded-xl bg-emerald-700 text-white hover:bg-emerald-800 shadow-md shadow-emerald-500/10 transition-all active:scale-95 dark:bg-emerald-600">
                                                <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                                                </svg>
                                                <span>Kembalikan</span>
                                            </button>
                                            </form>
                                            @endif

                                            @if ($loan->status === 'returning')
                                                <button type="button" disabled
                                                    class="w-full sm:w-auto flex-1 sm:flex-none py-2 px-4 inline-flex items-center justify-center gap-x-2 text-xs font-bold rounded-xl 
                                                    bg-gray-200 text-gray-600 border border-gray-300 shadow-sm cursor-not-allowed 
                                                    dark:bg-neutral-700 dark:border-neutral-600 dark:text-neutral-400">
                                                    <span>Menunggu Persetujuan...</span>
                                                </button>
                                            @endif

                                            {{-- Button Detail (Warna Tetap Biru Sesuai Request) --}}
                                            <a navigate href="{{ route('user.loans.detail', $loan->id) }}" 
                                                class="w-full sm:w-auto flex-1 sm:flex-none py-2 px-4 inline-flex items-center justify-center gap-x-2 text-xs font-bold rounded-xl bg-blue-600 text-white hoverx:bg-blue-700 shadow-md shadow-blue-500/20 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all dark:bg-blue-500">
                                                Detail
                                                <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="py-20 flex flex-col items-center justify-center text-center">
            <x-admin.empty-state />
            <p class="mt-4 text-gray-500 dark:text-neutral-400">Anda belum memiliki riwayat peminjaman.</p>
            <a href="{{ route('user.tools.index') }}" class="mt-4 text-blue-600 font-semibold hover:underline">Cari barang sekarang &rarr;</a>
        </div>
    @endforelse
</div>

@if(method_exists($loans, 'links'))
<div class="mt-8">
    {{ $loans->links() }}
</div>
@endif


{{-- Delete Confirmation Modal --}}
<div id="delete-modal" class="hs-overlay hidden size-full fixed top-0 start-0 z-80 overflow-x-hidden overflow-y-auto" role="dialog" tabindex="-1">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
        <div class="relative flex flex-col bg-white border shadow-sm rounded-xl dark:bg-neutral-800 dark:border-neutral-700">
            <div class="p-4 sm:p-10 text-center">
                <span class="mb-4 inline-flex justify-center items-center size-14 rounded-full border-4 border-red-50 bg-red-100 text-red-500 dark:bg-red-700 dark:border-red-600 dark:text-red-100">
                    @include('_admin._layout.icons.warning_modal')
                </span>
                <h3 class="mb-2 text-xl font-bold text-gray-800 dark:text-neutral-200">Hapus Peminjaman</h3>
                <p class="text-gray-500 dark:text-neutral-500">
                    Apakah Anda yakin ingin menghapus peminjaman <span id="delete-item-name" class="font-semibold text-gray-800 dark:text-neutral-200"></span>? 
                    Data Peminjaman yang dihapus tidak dapat dipulihkan.
                </p>

                <div class="mt-6 flex justify-center gap-x-4">
                    <button type="button" class="py-2 px-3 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 hover:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300" data-hs-overlay="#delete-modal">Batal</button>
                    <form id="delete-form" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="py-2 px-3 text-sm font-medium rounded-lg bg-red-600 text-white hover:bg-red-700">Ya, Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function setDeleteData(id, name) {
        document.getElementById('delete-item-name').textContent = name;
        document.getElementById('delete-form').action = '{{ url("user/loans/delete") }}/' + id;
    }
</script>
@endsection