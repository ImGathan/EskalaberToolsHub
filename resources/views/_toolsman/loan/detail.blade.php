@extends('_toolsman._layout.app')

@section('title', 'Detail Peminjaman')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Kolom Kiri: Info Barang & Status --}}
        <div class="lg:col-span-2 bg-white overflow-hidden shadow-lg rounded-2xl dark:bg-neutral-800">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700 flex items-center">

                @php
                    $targetStatus = 'pending';
                    if (in_array($data->status, ['approve', 'returning'])) $targetStatus = 'on_loan';
                    if (in_array($data->status, ['reject', 'returned'])) $targetStatus = 'history';
                @endphp

                <a href="{{ route('toolsman.loans.index', ['status' => $targetStatus]) }}"
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

            </div>
        </div>

        @if($data->status === 'returned' && $data->fine_amount > 0)
            {{-- Kolom Kanan: Ringkasan Biaya/Denda (Jika Ada) --}}
            <div class="space-y-6">

                {{-- Billing Card --}}
                <div class="bg-white shadow-xl rounded-2xl overflow-hidden dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700">
                    {{-- Body Billing --}}
                    <div class="p-6 space-y-4">
                        {{-- Item List --}}
                        <div class="space-y-3">
                            <div class="flex justify-between items-center text-sm">
                                <div class="flex flex-col">
                                    <span class="text-gray-500">Biaya Layanan Pinjam</span>
                                </div>
                                <span class="font-bold text-emerald-600 uppercase text-xs">Gratis</span>
                            </div>

                            <div class="flex justify-between items-center text-sm">
                                <div class="flex flex-col">
                                    <span class="text-gray-500">Kuantitas Barang</span>
                                </div>
                                <span class="font-semibold text-gray-800 dark:text-neutral-200">{{ $data->quantity }} Unit</span>
                            </div>

                            <div class="flex justify-between items-center text-sm">
                                <div class="flex flex-col">
                                    <span class="text-gray-500">Denda Per Hari</span>
                                </div>
                                <span class="font-semibold text-gray-800 dark:text-neutral-200">Rp {{ number_format($data->tool->fine, 0, ',', '.') }}</span>
                            </div>

                            <div class="flex justify-between items-center text-sm">
                                <div class="flex flex-col">
                                    <span class="text-gray-500">Total Keterlambatan</span>
                                </div>
                                <span class="font-bold {{ $data->hari_terlambat > 0 ? 'text-red-600' : 'text-gray-800 dark:text-neutral-200' }}">
                                    {{ $data->hari_terlambat }} Hari
                                </span>
                            </div>
                        </div>

                        {{-- Divider --}}
                        <div class="relative py-4">
                            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                <div class="w-full border-t border-dashed border-gray-200 dark:border-neutral-700"></div>
                            </div>
                        </div>

                        {{-- Total Section --}}
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-bold text-gray-800 dark:text-neutral-200 uppercase">Total Tagihan</span>
                                <div class="text-right">
                                    <span class="block text-xl font-black text-blue-600 dark:text-blue-500 tracking-tighter">
                                        Rp {{ number_format($data->fine_amount, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-neutral-900/50 rounded-xl border border-gray-100 dark:border-neutral-700">
                                <span class="text-xs font-bold text-gray-500 uppercase">Status</span>
                                @if($data->fine_status == 0)
                                    <span class="flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase bg-red-100 text-red-700 border border-red-200">
                                        <span class="size-1.5 rounded-full bg-red-600"></span>
                                        Belum Bayar
                                    </span>
                                @else
                                    <span class="flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase bg-emerald-100 text-emerald-700 border border-emerald-200">
                                        <svg class="size-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        Lunas
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

<script>
    // Mencegah browser otomatis scroll ke elemen tertentu saat load
    if ('scrollRestoration' in history) {
        history.scrollRestoration = 'manual';
    }
    
    // Memastikan halaman mulai dari paling atas
    window.scrollTo(0, 0);
</script>

@endsection