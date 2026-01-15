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
    <div class="group flex flex-col h-full bg-white border border-gray-200 shadow-sm rounded-xl dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70 overflow-hidden hover:shadow-md transition-all duration-300">
        <div class="aspect-video relative overflow-hidden bg-gray-100 dark:bg-neutral-700">
            <img 
                src="{{ $tool->image ? asset('storage/' . $tool->image) : asset('admin/images/empty-data.webp') }}" 
                alt="{{ $tool->name }}" 
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-in-out"
            >
            <div class="absolute top-2 right-2">
                <span class="inline-flex items-center px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $tool->status == 'Tersedia' ? 'bg-teal-100 text-teal-800 dark:bg-teal-800 dark:text-teal-300 border-teal-800 dark:border-teal-300 border-[1.5px]' : 'bg-red-100 text-red-700 dark:bg-red-700 dark:text-red-200 border-red-700 dark:border-red-200 border-[1.5px]' }}">
                    {{ $tool->status }}
                </span>
            </div>
        </div>
    
        <div class="p-4 flex flex-col flex-grow">
            <div class="flex justify-between items-center mb-1">
                <span class="text-[11px] font-semibold text-blue-600 uppercase dark:text-blue-500">
                    {{ $tool->category->name }}
                </span>
                <span class="text-xs text-gray-500 dark:text-neutral-400">
                    Stok: <span class="font-bold text-gray-800 dark:text-neutral-200">{{ $tool->quantity }}</span>
                </span>
            </div>

            <div class="mb-4">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white group-hover:text-blue-600 transition-colors line-clamp-1">
                    {{ $tool->name }}
                </h3>
                <div class="flex items-center gap-1 text-gray-500 dark:text-neutral-400">
                    <svg class="size-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                    </svg>
                    <span class="text-xs font-medium">{{ $tool->place->name }}</span>
                </div>
            </div>

            <div class="mt-auto">
                <a href="{{ route('user.loans.add', ['tool_id' => $tool->id]) }}" 
                    class="w-full py-2.5 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all disabled:opacity-50 {{ $tool->status != 'Tersedia' ? 'pointer-events-none opacity-60 grayscale' : '' }}">
                    @if($tool->status == 'Tersedia')
                        Pinjam Sekarang
                    @else
                        Tidak Tersedia
                    @endif
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full py-20">
        <x-admin.empty-state />
    </div>
    @endforelse
</div>

<div class="mt-8">
    {{ $tools->links() }}
</div>

@endsection