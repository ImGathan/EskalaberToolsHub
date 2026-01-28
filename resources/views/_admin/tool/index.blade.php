@extends('_admin._layout.app')

@section('title', 'Data Inventaris')

@section('content')
{{-- Header Section --}}
<div class="grid gap-3 md:flex md:justify-between md:items-center py-4">
    <div>
        <h1 class="text-2xl font-extrabold text-gray-800 dark:text-neutral-200 mb-1">
            Data Inventaris Barang
        </h1>
        <p class="text-md text-gray-400 dark:text-neutral-400">
            Kelola stok, kategori, dan status ketersediaan aset barang.
        </p>
    </div>

    <div>
        <a navigate
            class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-bolder rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none"
            href="{{ route('admin.tools.add') }}">
            @include('_admin._layout.icons.add')
            Tambah Barang
        </a>
    </div>
</div>

<div class="flex flex-col">
    {{-- Search & Filter Section --}}
    <div class="px-2 pb-4">
        <form action="{{ route('admin.tools.index') }}" method="GET" navigate-form
            class="flex flex-col sm:flex-row gap-3">
            
            <div class="sm:w-80">
                <input type="text" name="keywords" id="keywords" value="{{ $keywords ?? '' }}"
                    class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400"
                    placeholder="Cari nama barang atau kategori...">
            </div>

            <div class="flex gap-x-2">
                <button type="submit"
                    class="py-2 px-3 inline-flex items-center gap-x-1 text-sm font-semibold rounded-lg bg-blue-600 text-white hover:bg-blue-700 cursor-pointer">
                    @include('_admin._layout.icons.search')
                    Cari
                </button>
                
                @if (!empty($keywords))
                    <a href="{{ route('admin.tools.index') }}"
                        class="py-2 px-3 inline-flex items-center gap-x-1 text-sm font-semibold rounded-lg border border-gray-200 bg-white text-gray-800 hover:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300">
                        @include('_admin._layout.icons.reset')
                        Reset
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
                    <th scope="col" class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">Barang</th>
                    <th scope="col" class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">Stok</th>
                    <th scope="col" class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">Kategori & Lokasi</th>
                    <th scope="col" class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">Toolsman</th>
                    <th scope="col" class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">Status</th>
                    <th scope="col" class="px-6 py-3 text-end text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                @forelse($tools as $tool)
                    <tr class="hover:bg-gray-50 dark:hover:bg-neutral-700/50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-x-3">
                                <img class="shrink-0 size-10 rounded-lg object-cover ring-1 ring-gray-200 dark:ring-neutral-700" 
                                     src="{{ $tool->image ? asset('storage/' . $tool->image) : asset('admin/images/empty-data.webp') }}" 
                                     alt="{{ $tool->name }}">
                                <div>
                                    <span class="block text-sm font-bold text-gray-800 dark:text-neutral-200">{{ $tool->name }}</span>
                                    <!-- <span class="block text-[11px] text-gray-500 italic">ID: #{{ $tool->id }}</span> -->
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                            <span class="font-bold">{{ $tool->quantity }}</span>
                            <span class="text-xs text-gray-500 dark:text-neutral-500 ml-0.5">Unit</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-gray-800 dark:text-neutral-200">{{ $tool->category->name }}</span>
                                <span class="text-xs text-gray-500 dark:text-neutral-500">{{ $tool->place->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-gray-800 dark:text-neutral-200">{{ $tool->toolsman->username }}</span>
                                <span class="text-xs text-gray-500 dark:text-neutral-500">{{ $tool->toolsman->class }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusClasses = match($tool->status) {
                                    'Tersedia' => 'bg-teal-100 text-teal-800 dark:bg-teal-500/10 dark:text-teal-500',
                                    default => 'bg-red-100 text-red-500 dark:bg-red-500/10 dark:text-red-500',
                                };
                            @endphp
                            <span class="inline-flex items-center py-1 px-2.5 rounded-full text-xs font-bold {{ $statusClasses }}">
                                {{ $tool->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                            <div class="flex justify-end items-center gap-x-2">
                                <a navigate href="{{ route('admin.tools.update', $tool->id) }}" 
                                    class="p-2 inline-flex items-center rounded-lg border border-blue-200 bg-blue-50 text-blue-600 hover:bg-blue-100 dark:bg-blue-900/20 dark:border-blue-800 dark:text-blue-500" title="Edit">
                                    @include('_admin._layout.icons.pencil')
                                </a>
                                <button type="button" onclick="setDeleteData('{{ $tool->id }}', '{{ $tool->name }}')"
                                    data-hs-overlay="#delete-modal"
                                    class="p-2 inline-flex items-center rounded-lg border border-red-200 bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-900/20 dark:border-red-800 dark:text-red-500 cursor-pointer" title="Delete">
                                    @include('_admin._layout.icons.trash')
                                </button>
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
    {{ $tools->links() }}
</div>

{{-- Delete Confirmation Modal --}}
<div id="delete-modal" class="hs-overlay hidden size-full fixed top-0 start-0 z-80 overflow-x-hidden overflow-y-auto" role="dialog" tabindex="-1">
    <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
        <div class="relative flex flex-col bg-white border shadow-sm rounded-xl dark:bg-neutral-800 dark:border-neutral-700">
            <div class="p-4 sm:p-10 text-center">
                <span class="mb-4 inline-flex justify-center items-center size-14 rounded-full border-4 border-red-50 bg-red-100 text-red-500 dark:bg-red-700 dark:border-red-600 dark:text-red-100">
                    @include('_admin._layout.icons.warning_modal')
                </span>
                <h3 class="mb-2 text-xl font-bold text-gray-800 dark:text-neutral-200">Hapus Barang</h3>
                <p class="text-gray-500 dark:text-neutral-500">
                    Apakah Anda yakin ingin menghapus <span id="delete-item-name" class="font-semibold text-gray-800 dark:text-neutral-200"></span>?<br>
                    Data inventaris yang dihapus tidak dapat dipulihkan.
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
        document.getElementById('delete-form').action = '{{ url("admin/tools/delete") }}/' + id;
    }
</script>
@endsection