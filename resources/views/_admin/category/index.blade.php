@extends('_admin._layout.app')

@section('title', 'Kategori')


@section('content')
<div class="grid gap-3 md:flex md:justify-between md:items-center py-4">
    <div>
        <h1 class="text-2xl font-extrabold text-gray-800 dark:text-neutral-200 mb-1">
            Data Kategori
        </h1>
        <p class="text-md text-gray-400 dark:text-neutral-400">
            Kategori
        </p>
    </div>

    <div>
        <div class="inline-flex gap-x-2">
            <a navigate
                class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-hidden focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none font-bolder"
                href="{{ route('admin.categories.add') }}">
                @include('_admin._layout.icons.add')
                Tambah Data
            </a>
        </div>
    </div>
</div>
<div class="flex flex-col">
    <div class="overflow-x-auto">
        <div class="min-w-full inline-block align-middle">
            <div class="overflow-hidden">

                <div class="px-2 pt-4">
                    <form action="{{ route('admin.categories.index') }}" method="GET" navigate-form
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
                                @include('_admin._layout.icons.search')
                                Cari
                            </button>
                            @if (!empty($keywords))
                            <a class="py-1 px-3 inline-flex items-center gap-x-1 text-sm font-semibold rounded-lg border border-blue-600 text-blue-600 hover:border-blue-500 hover:text-blue-500 hover:bg-blue-50 disabled:opacity-50 disabled:pointer-events-none dark:border-blue-500 dark:text-blue-500 dark:hover:bg-blue-500/10 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600 cursor-pointer"
                                href="{{ route('admin.categories.index') }}">
                                @include('_admin._layout.icons.reset')
                                Reset
                            </a>
                            @endif
                        </div>
                    </form>
                </div>

                <div class="mx-0 my-4 overflow-x-auto border border-gray-200 rounded-lg dark:border-neutral-700">
                    <table class="w-full divide-y divide-gray-200 dark:divide-neutral-700">
                        <thead class=" dark:bg-neutral-800">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-start">
                                    <div class="flex items-center gap-x-2">
                                        <span
                                            class="text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">
                                            Nama Kategori
                                        </span>
                                    </div>
                                </th>

                                <th scope="col" class="px-6 py-3 text-start">
                                    <div class="flex items-center gap-x-2">
                                        <span
                                            class="text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">
                                            Toolsman
                                        </span>
                                    </div>
                                </th>

                                <!-- <th scope="col" class="px-6 py-3 text-start">
                                        <div class="flex items-center gap-x-2">
                                            <span
                                                class="text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">
                                                Hak Akses
                                            </span>
                                        </div>
                                    </th> -->

                                <th scope="col" class="px-6 py-3 text-end"></th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                            @forelse($categories as $category)
                            <tr class="hover:bg-gray-100 dark:hover:bg-neutral-700">
                                <td class="size-px whitespace-nowrap">
                                    <div class="px-6 py-3">
                                        <div class="flex items-center gap-x-3">
                                            <div class="grow">
                                                <span
                                                    class="block text-sm font-semibold text-gray-800 dark:text-neutral-200">{{ $category->name }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="size-px whitespace-nowrap">
                                    <div class="px-6 py-3">
                                        @if ($category->toolsman)
                                        <div class="flex flex-col">
                                            <span
                                                class="text-sm font-medium text-gray-800 dark:text-neutral-200">{{ $category->toolsman->username }}</span>
                                            <span class="text-xs text-gray-500 dark:text-neutral-500">
                                                {{ $category->toolsman->class ?? '-' }}</span>
                                        </div>
                                        @else
                                        <span class="text-sm text-gray-500 italic">Belum Ada</span>
                                        @endif
                                    </div>
                                </td>

                                <td class="size-px whitespace-nowrap">
                                    <div class="px-6 py-1.5 flex items-center gap-x-1">
                                        <a navigate
                                            class="inline-flex items-center justify-center size-8 text-sm font-semibold rounded-lg border border-blue-200 bg-blue-50 text-blue-600 hover:bg-blue-100 hover:border-blue-300 focus:outline-none focus:bg-blue-100 disabled:opacity-50 disabled:pointer-events-none dark:border-blue-800 dark:bg-blue-900/20 dark:text-blue-500 dark:hover:bg-blue-800/30 dark:hover:border-blue-700"
                                            href="{{ route('admin.categories.update', $category->id) }}" title="Edit">
                                            @include('_admin._layout.icons.pencil')
                                        </a>
                                        <button type="button"
                                            class="inline-flex items-center justify-center size-8 text-sm font-semibold rounded-lg border border-red-200 bg-red-50 text-red-600 hover:bg-red-100 hover:border-red-300 focus:outline-none focus:bg-red-100 disabled:opacity-50 disabled:pointer-events-none dark:border-red-800 dark:bg-red-900/20 dark:text-red-500 dark:hover:bg-red-800/30 dark:hover:border-red-700 cursor-pointer"
                                            title="Delete" data-hs-overlay="#delete-modal"
                                            onclick="setDeleteData('{{ $category->id }}', '{{ $category->name }}')">
                                            @include('_admin._layout.icons.trash')
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6"
                                    class="px-6 py-4 text-center text-sm text-gray-500 dark:text-neutral-500">
                                    <x-admin.empty-state />
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>


            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="hs-overlay hidden size-full fixed top-0 start-0 z-80 overflow-x-hidden overflow-y-auto"
    role="dialog" tabindex="-1" aria-labelledby="delete-modal-label">
    <div
        class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
        <div
            class="relative flex flex-col bg-white border shadow-sm rounded-xl dark:bg-neutral-800 dark:border-neutral-700">
            <div class="absolute top-2 end-2">
                <button type="button"
                    class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-gray-100 text-gray-800 hover:bg-gray-200 focus:outline-none focus:bg-gray-200 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-700 dark:hover:bg-neutral-600 dark:text-neutral-400 dark:focus:bg-neutral-600"
                    aria-label="Close" data-hs-overlay="#delete-modal">
                    <span class="sr-only">Close</span>
                    @include('_admin._layout.icons.close_modal')
                </button>
            </div>

            <div class="p-4 sm:p-10 text-center overflow-y-auto">
                <!-- Icon -->
                <span
                    class="mb-4 inline-flex justify-center items-center size-14 rounded-full border-4 border-red-50 bg-red-100 text-red-500 dark:bg-red-700 dark:border-red-600 dark:text-red-100">
                    @include('_admin._layout.icons.warning_modal')
                </span>
                <!-- End Icon -->

                <h3 id="delete-modal-label" class="mb-2 text-xl font-bold text-gray-800 dark:text-neutral-200">
                    Hapus Kategori
                </h3>
                <p class="text-gray-500 dark:text-neutral-500">
                    Apakah Anda yakin ingin menghapus <span id="delete-item-name"
                        class="font-semibold text-gray-800 dark:text-neutral-200"></span>?
                    <br>Tindakan ini tidak dapat dibatalkan.
                </p>

                <div class="mt-6 flex justify-center gap-x-4">
                    <button type="button"
                        class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:bg-gray-50 dark:bg-transparent dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800"
                        data-hs-overlay="#delete-modal">
                        Batal
                    </button>
                    <form id="delete-form" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-red-600 text-white hover:bg-red-700 focus:outline-none focus:bg-red-700 disabled:opacity-50 disabled:pointer-events-none">
                            Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function setDeleteData(id, name) {
        document.getElementById('delete-item-name').textContent = name;
        document.getElementById('delete-form').action = '{{ url('admin/categories/delete') }}/' + id;
    }
</script>
@endsection