@extends('_user._layout.app')

@section('title', 'Jelajahi Barang')

@section('content')
<div class="grid gap-3 md:flex md:justify-between md:items-center py-4">
    <div>
        <h1 class="text-2xl font-extrabold text-gray-800 dark:text-neutral-200 mb-1">
            Jelajahi Barang
        </h1>
        <p class="text-md text-gray-400 dark:text-neutral-400">
            Pilih dan pinjam barang yang Anda butuhkan dengan mudah.
        </p>
    </div>
</div>

<div class="flex flex-col">
    {{-- Search & Filter Section --}}
    <div class="px-2 pb-4">
        <form action="{{ route('user.tools.index') }}" method="GET" navigate-form
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
                    <a href="{{ route('user.tools.index') }}"
                        class="py-2 px-3 inline-flex items-center gap-x-1 text-sm font-semibold rounded-lg border border-gray-200 bg-white text-gray-800 hover:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300">
                        @include('_user._layout.icons.reset')
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @forelse($tools as $tool)
        <div class="group flex flex-col h-full bg-white border border-gray-200 rounded-xl dark:bg-neutral-800 dark:border-neutral-700 shadow-lg hover:shadow-xl hover:shadow-blue-500/20 transition-all duration-300 overflow-hidden">
            
            {{-- Image Container --}}
            <div class="relative pt-[65%] overflow-hidden bg-gray-100 dark:bg-neutral-700">
                <img 
                    src="{{ $tool->image ? asset('storage/' . $tool->image) : asset('admin/images/empty-data.webp') }}" 
                    alt="{{ $tool->name }}" 
                    class="absolute top-0 start-0 size-full object-cover group-hover:scale-110 transition-transform duration-500 ease-in-out"
                >
                {{-- Status Badge --}}
                <div class="absolute top-3 end-3">
                    <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-[10px] font-bold uppercase tracking-wide {{ $tool->status == 'Tersedia' ? 'bg-emerald-600 text-white dark:bg-emerald-500 dark:text-neutral-900' : 'bg-red-600 text-white dark:bg-red-500 dark:text-neutral-900' }}">
                        <span class="size-1.5 rounded-full bg-white dark:bg-neutral-900"></span>
                        {{ $tool->status }}
                    </span>
                </div>
            </div>

            {{-- Content --}}
            <div class="p-5 flex flex-col flex-grow">
                <div class="flex items-center justify-between mb-2">
                    <span class="inline-flex items-center py-1 px-2 rounded-lg bg-blue-100 text-blue-700 text-[10px] font-bold uppercase tracking-wider dark:bg-neutral-700 dark:text-blue-400">
                        {{ $tool->category->name }}
                    </span>
                    <div class="flex items-center gap-1.5 text-gray-500 dark:text-neutral-400">
                        <svg class="size-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <span class="text-xs font-medium">Stok: {{ $tool->quantity }}</span>
                    </div>
                </div>

                <h2 class="text-lg font-bold text-gray-700 dark:text-neutral-200 group-hover:text-blue-600 transition-colors line-clamp-2 truncate">
                    {{ $tool->name }}
                </h2>

                <div class="flex items-center gap-x-2 mb-3">
                    <div class="size-5 rounded-full bg-gray-100 dark:bg-neutral-700 flex items-center justify-center text-gray-500 dark:text-neutral-400">
                        <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <span class="text-xs text-gray-600 dark:text-neutral-400 font-medium">{{ $tool->place->name }}</span>
                </div>

                {{-- Action Button --}}
                <div class="mt-auto">
                    <a href="{{ route('user.loans.add', ['tool_id' => $tool->id]) }}" 
                        class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-xs font-bold rounded-xl border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all shadow-md shadow-blue-500/20 disabled:opacity-50 {{ $tool->status != 'Tersedia' ? 'pointer-events-none opacity-50 grayscale' : '' }}">
                        @if($tool->status == 'Tersedia')
                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM12 12.75v5.25m3-3h-6" />
                            </svg>
                            Ajukan Pinjaman
                        @else
                            Stok Kosong
                        @endif
                    </a>
                </div>
            </div>
        </div>
        @empty
        {{-- Bagian empty state --}}
        @endforelse
</div>

<div class="mt-8">
    {{ $tools->links() }}
</div>

@endsection