@extends('_user._layout.app')

@section('title', 'Barang')


@section('content')
<div class="grid gap-3 md:flex md:justify-between md:items-center py-4">
    <div>
        <h1 class="text-2xl font-extrabold text-gray-800 dark:text-neutral-200 mb-1">
            Peminjaman Anda
        </h1>
        <p class="text-md text-gray-400 dark:text-neutral-400">
            Peminjaman
        </p>
    </div>

    
</div>
<div class="flex flex-col">
    <div class="overflow-x-auto">
        <div class="min-w-full inline-block align-middle">
            <div class="overflow-hidden">

                <div class="px-2 pt-4">
                    <form action="{{ route('user.loans.index') }}" method="GET" navigate-form
                        class="flex flex-col sm:flex-row gap-3">
                        <div class="sm:w-64">
                            <label for="keywords" class="sr-only">Search</label>
                            <div class="relative">
                                <input type="text" name="keywords" id="keywords" value="{{ $keywords ?? '' }}"
                                    class="py-1 px-3 block w-full border-gray-200 rounded-lg text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 
                                        placeholder-neutral-300 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
                                    placeholder="Cari Peminjaman">
                            </div>
                        </div>
                        <!-- <div class="sm:w-48">
                                <select name="access_type"
                                    class="py-1 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600">
                                    <option value="all" {{ ($access_type ?? 'all') == 'all' ? 'selected' : '' }}>
                                        Semua Hak Akses
                                    </option>
                                    <option value="admin" {{ ($access_type ?? '') == 'admin' ? 'selected' : '' }}>Admin
                                    </option>
                                    <option value="user" {{ ($access_type ?? '') == 'user' ? 'selected' : '' }}>User
                                    </option>
                                </select>
                            </div> -->
                        <div>
                            <button type="submit"
                                class="py-1 px-3 inline-flex items-center gap-x-1 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600 cursor-pointer">
                                @include('_user._layout.icons.search')
                                Cari
                            </button>

                            @if (!empty($keywords))
                            <a class="py-1 px-3 inline-flex items-center gap-x-1 text-sm font-semibold rounded-lg border border-blue-600 text-blue-600 hover:border-blue-500 hover:text-blue-500 hover:bg-blue-50 disabled:opacity-50 disabled:pointer-events-none dark:border-blue-500 dark:text-blue-500 dark:hover:bg-blue-500/10 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600 cursor-pointer"
                                href="{{ route('user.loans.index') }}">
                                @include('_user._layout.icons.reset')
                                Reset
                            </a>
                            @endif

                        </div>
                    </form>
                </div>

               <div class="space-y-8 mt-6">
                    @forelse($loans as $date => $items)
                        <div>
                            {{-- Header Tanggal --}}
                            <div class="flex items-center gap-x-3 mb-4">
                                <span class="text-sm font-bold text-gray-800 dark:text-neutral-200 bg-gray-100 dark:bg-neutral-800 px-3 py-1 rounded-lg shadow-sm">
                                    {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
                                </span>
                                <div class="h-px bg-gray-200 dark:bg-neutral-700 flex-1"></div>
                            </div>

                            {{-- List Card Memanjang --}}
                            <div class="flex flex-col gap-y-3">
                                @foreach($items as $loan)
                                <div class="group bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-neutral-900 dark:border-neutral-700">
                                    <div class="flex flex-col md:flex-row items-center">
                                        {{-- Gambar di Kiri (Lebih Kecil) --}}
                                        <div class="md:w-48 w-full h-36 md:h-28 bg-gray-100 dark:bg-neutral-800">
                                            <img class="w-full h-full object-cover" 
                                                src="{{ $loan->tool && $loan->tool->image ? asset('storage/' . $loan->tool->image) : asset('admin/images/empty-data.webp') }}" 
                                                alt="Barang">
                                        </div>

                                        {{-- Konten di Kanan (Memanjang) --}}
                                        <div class="flex-1 p-4 md:px-6 py-2 w-full">
                                            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-2">
                                                <div>
                                                    <p class="text-xs font-medium text-blue-600 uppercase mb-0.5">
                                                        {{ $loan->tool->category->name ?? 'Kategori' }}
                                                    </p>
                                                    <h3 class="text-lg font-bold text-gray-800 dark:text-neutral-200">
                                                        {{ $loan->tool->name ?? 'Barang Tidak Diketahui' }}
                                                    </h3>
                                                    <p class="text-xs text-gray-500 dark:text-neutral-400">
                                                        <span class="font-semibold text-gray-700 dark:text-neutral-300">{{ $loan->quantity }} Unit</span> 
                                                    </p>
                                                    <span class="inline-flex items-center gap-x-1 py-1 px-2 rounded-full text-xs font-medium my-2 
                                                        {{ $loan->status === 'approve' && now()->greaterThan($loan->due_date) ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                                        {{ $loan->keterangan_status }}
                                                    </span>
                                                </div>

                                                {{-- Status/Action --}}
                                                <div class="flex items-center gap-x-3">
                                                    <a navigate
                                                    class="inline-flex items-center justify-center size-8 text-sm font-semibold rounded-lg border border-gray-200 bg-white text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:border-neutral-700 dark:bg-neutral-800 dark:text-white dark:hover:bg-neutral-700"
                                                    href="{{ route('user.loans.detail', $loan->id) }}" title="View">
                                                    @include('_user._layout.icons.view_detail')
                                                </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="py-10 text-center">
                            <x-admin.empty-state />
                        </div>
                    @endforelse
                </div>


            </div>
        </div>
    </div>
</div>


@endsection