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

            <div class="flex gap-x-2">
                <button type="submit"
                    class="py-2 px-3 inline-flex items-center gap-x-1 text-sm font-semibold rounded-lg bg-blue-600 text-white hover:bg-blue-700 cursor-pointer">
                    @include('_user._layout.icons.search')
                    Cari
                </button>
                
                @if (!empty($keywords))
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
                <div class="group bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-900 dark:border-neutral-700 hover:border-blue-300 dark:hover:border-blue-900 transition-all duration-200">
                    <div class="flex flex-col md:flex-row items-stretch">
                        {{-- Image Section --}}
                        <div class="md:w-48 shrink-0 relative overflow-hidden bg-gray-100 dark:bg-neutral-800">
                            <img class="w-full h-40 md:h-full object-cover transition-transform duration-500 group-hover:scale-105" 
                                src="{{ $loan->tool && $loan->tool->image ? asset('storage/' . $loan->tool->image) : asset('admin/images/empty-data.webp') }}" 
                                alt="Barang">
                        </div>

                        {{-- Info Section --}}
                        <div class="flex-1 p-4 md:p-5 flex flex-col justify-between">
                            <div class="flex flex-col md:flex-row justify-between items-start gap-4">
                                <div class="space-y-1">
                                    <span class="text-[10px] font-bold tracking-widest text-blue-600 uppercase dark:text-blue-500">
                                        {{ $loan->tool->category->name ?? 'Kategori' }}
                                    </span>
                                    <h3 class="text-lg font-bold text-gray-800 dark:text-neutral-200 group-hover:text-blue-600 transition-colors">
                                        {{ $loan->tool->name ?? 'Barang Tidak Diketahui' }}
                                    </h3>
                                    <div class="flex items-center gap-3 text-sm text-gray-500 dark:text-neutral-400">
                                        <div class="flex items-center gap-1">
                                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                            <span class="font-semibold">{{ $loan->quantity }} Unit</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Status Badge --}}
                                <div class="flex flex-col items-end gap-2 shrink-0">
                                    <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-bold 
                                        {{ $loan->status === 'approve' && now()->startOfDay()->greaterThan($loan->due_date->startOfDay()) ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-500' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' }}">
                                        <span class="size-1.5 rounded-full {{ $loan->status === 'approve' && now()->startOfDay()->greaterThan($loan->due_date->startOfDay()) ? 'bg-red-600' : 'bg-blue-600' }}"></span>
                                        {{ $loan->keterangan_status }}
                                    </span>
                                </div>
                            </div>

                            <div class="mt-4 pt-4 border-t border-gray-100 dark:border-neutral-800 flex justify-between items-center">
                                <span class="text-xs text-gray-400 dark:text-neutral-500">
                                    ID Peminjaman: <span class="font-mono text-gray-600 dark:text-neutral-300">#LOAN-{{ $loan->id }}</span>
                                </span>
                                <a navigate href="{{ route('user.loans.detail', $loan->id) }}" 
                                    class="inline-flex items-center gap-x-2 text-sm font-semibold text-blue-600 hover:text-blue-500 dark:text-blue-500 dark:hover:text-blue-400">
                                    Lihat Detail
                                    <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                    </svg>
                                </a>
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
<div class="mt-10">
    {{ $loans->links() }}
</div>
@endif

@endsection