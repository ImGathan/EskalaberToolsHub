@extends('_user._layout.app')

@section('title', 'Barang')


@section('content')
<div class="grid gap-3 md:flex md:justify-between md:items-center py-4">
    <div>
        <h1 class="text-2xl font-extrabold text-gray-800 dark:text-neutral-200 mb-1">
            Jelajahi Barang
        </h1>
        <p class="text-md text-gray-400 dark:text-neutral-400">
            Barang
        </p>
    </div>

</div>
<div class="flex flex-col">
    <div class="overflow-x-auto">
        <div class="min-w-full inline-block align-middle">
            <div class="overflow-hidden">

                <div class="px-2 pt-4">
                    <form action="{{ route('user.tools.index') }}" method="GET" navigate-form
                        class="flex flex-col sm:flex-row gap-3">
                        <div class="sm:w-64">
                            <label for="keywords" class="sr-only">Search</label>
                            <div class="relative">
                                <input type="text" name="keywords" id="keywords" value="{{ $keywords ?? '' }}"
                                    class="py-1 px-3 block w-full border-gray-200 rounded-lg text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 
                                        placeholder-neutral-300 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
                                    placeholder="Cari Kategori">
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
                                href="{{ route('user.tools.index') }}">
                                @include('_user._layout.icons.reset')
                                Reset
                            </a>
                            @endif
                        </div>
                    </form>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 py-6">
                    @forelse($tools as $tool)
                    <div class="group flex flex-col h-full bg-white border border-gray-200 shadow-sm rounded-xl dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
                        <div class="h-40 flex flex-col justify-center items-center bg-gray-100 rounded-t-xl overflow-hidden dark:bg-neutral-800">
                            <img 
                                src="{{ $tool->image ? asset('storage/' . $tool->image) : asset('admin/images/empty-data.webp') }}" 
                                alt="{{ $tool->name }}" 
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 ease-in-out"
                            >
                        </div>
                    
                        <div class="p-4 md:p-5">
                            <div class="flex justify-between items-start mb-2">
                                <span class="inline-flex items-center gap-1.5 py-0.5 px-2 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-500">
                                    {{ $tool->category->name }}
                                </span>
                                <span class="text-sm font-bold text-gray-800 dark:text-neutral-200">
                                    Stok: {{ $tool->quantity }}
                                </span>
                            </div>

                            <h3 class="text-lg font-bold text-gray-800 dark:text-white truncate">
                                {{ $tool->name }}
                            </h3>

                            <div class="mt-1 space-y-1">
                                <!-- <div class="flex items-center gap-x-2">
                                    <span class="text-xs font-semibold uppercase text-gray-400">Toolsman:</span>
                                    <span class="text-sm text-gray-600 dark:text-neutral-400">
                                        {{ $tool->toolsman->username ?? 'Belum Ada' }}
                                    </span>
                                </div> -->
                                <div class="flex items-center gap-x-2">
                                    <span class="text-xs font-semibold uppercase text-gray-400">Status:</span>
                                    <span class="text-xs {{ $tool->status == 'Tersedia' ? 'text-green-600' : 'text-red-600' }} font-medium">
                                        {{ $tool->status }}
                                    </span>
                                </div>
                            </div>

                            <div class="mt-3">
                                <a href="{{ route('user.loans.add', ['tool_id' => $tool->id]) }}" 
                                    class="w-full py-2 px-3 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                                        Pinjam Barang Ini
                                    </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full">
                        <x-admin.empty-state />
                    </div>
                    @endforelse
                </div>

            </div>
        </div>
    </div>
</div>


@endsection