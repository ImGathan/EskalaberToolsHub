@extends('_user._layout.app')

@section('title', 'Detail Peminjaman')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Kolom Kiri: Info Barang & Status --}}
        <div class="lg:col-span-2 bg-white overflow-hidden shadow-lg rounded-2xl dark:bg-neutral-800">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700 flex items-center">
                <a href="{{ route('user.loans.index') }}"
                    class="py-2 px-2 inline-flex items-center gap-x-2 rounded-xl border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 cursor-pointer">
                    <svg class="shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="m12 19-7-7 7-7" />
                        <path d="M19 12H5" />
                    </svg>
                </a>
                <div class="ms-3">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                        Detail Peminjaman
                    </h2>
                </div>
            </div>

            <div class="p-6">
                {{-- Header: Gambar & Nama Barang --}}
                <div class="flex flex-col md:flex-row items-center gap-6 mb-8">
                    <img class="size-24 md:size-32 rounded-2xl object-cover shadow-md" 
                         src="{{ $data->tool && $data->tool->image ? asset('storage/' . $data->tool->image) : asset('admin/images/empty-data.webp') }}" 
                         alt="Barang">
                    
                    <div class="text-center md:text-left">
                        <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">
                            {{ $data->tool->category->name ?? 'Kategori' }}
                        </span>
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-1">
                            {{ $data->tool->name ?? 'Barang Tidak Diketahui' }}
                        </h3>
                        <div class="mt-3 flex flex-wrap justify-center md:justify-start gap-2">
                            {{-- Badge Status Menggunakan Accessor yang tadi --}}
                           
                            <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-neutral-700 dark:text-neutral-200">
                                {{ $data->quantity }} Unit
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Grid Detail Waktu --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="p-4 bg-gray-50 rounded-xl dark:bg-neutral-700/50 border border-gray-100 dark:border-neutral-700">
                        <p class="text-xs text-gray-500 dark:text-neutral-400 uppercase tracking-wide font-semibold mb-1">
                            Tanggal Pinjam
                        </p>
                        <p class="text-sm font-medium text-gray-800 dark:text-neutral-200">
                            {{ \Carbon\Carbon::parse($data->loan_date)->translatedFormat('d F Y') }}
                        </p>
                    </div>

                    <div class="p-4 bg-gray-50 rounded-xl dark:bg-neutral-700/50 border border-gray-100 dark:border-neutral-700">
                        <p class="text-xs text-gray-500 dark:text-neutral-400 uppercase tracking-wide font-semibold mb-1">
                            Batas Pengembalian
                        </p>
                        <p class="text-sm font-medium text-gray-800 dark:text-neutral-200">
                            {{ \Carbon\Carbon::parse($data->due_date)->translatedFormat('d F Y') }}
                        </p>
                    </div>

                    @if($data->return_date)
                    <div class="p-4 bg-gray-50 rounded-xl dark:bg-neutral-700/50 border border-gray-100 dark:border-neutral-700">
                        <p class="text-xs text-gray-500 dark:text-neutral-400 uppercase tracking-wide font-semibold mb-1">
                            Tanggal Dikembalikan
                        </p>
                        <p class="text-sm font-medium text-gray-800 dark:text-neutral-200">
                            {{ \Carbon\Carbon::parse($data->return_date)->translatedFormat('d F Y') }}
                        </p>
                    </div>
                    @endif
                </div>

                {{-- Informasi Tambahan/Keterangan --}}
                <div class="mt-6 p-4 bg-gray-50 rounded-xl dark:bg-neutral-700/50 border border-gray-100 dark:border-neutral-700">
                    <p class="text-xs text-gray-500 dark:text-neutral-400 uppercase tracking-wide font-semibold mb-2">
                        Keterangan Peminjaman
                    </p>
                    <p class="text-sm text-gray-800 dark:text-neutral-200 italic">
                        "{{ $data->keterangan_status }}"
                    </p>
                </div>

                <div class="mt-6 p-4 bg-gray-50 rounded-xl dark:bg-neutral-700/50 border border-gray-100 dark:border-neutral-700">
                    <p class="text-xs text-gray-500 dark:text-neutral-400 uppercase tracking-wide font-semibold mb-2">
                        Keterangan Lokasi
                    </p>
                    <p class="text-sm text-gray-800 dark:text-neutral-200 italic">
                        "Barang ini tersedia di {{ $data->tool->place->name }}"
                    </p>
                </div>
            </div>
        </div>

        @if($data->status === 'returned' && $data->fine_amount > 0)
            {{-- Kolom Kanan: Ringkasan Biaya/Denda (Jika Ada) --}}
            <div class="space-y-6">

                {{-- Alert Jika Terlambat --}}
                @if(now()->startOfDay()->greaterThan($data->due_date->startOfDay()) && $data->status === 'approve' || $data->status === 'returned')
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 dark:bg-red-800/10 dark:border-red-900">
                    <div class="flex">
                        <svg class="shrink-0 size-4 text-red-600 mt-0.5 dark:text-red-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <div class="ms-3">
                            <p class="text-sm text-red-700 dark:text-red-400 font-medium">
                                Anda mempunyai tagihan keterlambatan. Segera lakukan pembayaran denda ke admin!
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                <div class="bg-white shadow-lg rounded-2xl p-6 dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700">
                    <h4 class="text-sm font-bold text-gray-800 dark:text-neutral-200 uppercase mb-4">Ringkasan Biaya</h4>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Biaya Pinjam</span>
                            <span class="font-medium text-gray-800 dark:text-neutral-200">Gratis</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Jumlah Barang Dipinjam</span>
                            <span class="font-medium text-gray-800 dark:text-neutral-200">{{ $data->quantity }} Unit</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Harga Denda Barang</span>
                            <span class="font-medium text-gray-800 dark:text-neutral-200">Rp. {{ number_format($data->tool->fine, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Hari Keterlambatan</span>
                            <span class="font-medium {{ $data->hari_terlambat > 0 ? 'text-red-600' : 'text-gray-800 dark:text-neutral-200' }}">
                                {{ $data->hari_terlambat }} Hari
                            </span>
                        </div>
                        <div class="border-t border-gray-100 dark:border-neutral-700 pt-3 flex justify-between">
                            <span class="font-bold text-gray-800 dark:text-neutral-200">Total</span>
                            <span class="font-bold text-blue-600">Rp {{ number_format($data->fine_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                
                
            </div>
        @endif
    </div>
@endsection