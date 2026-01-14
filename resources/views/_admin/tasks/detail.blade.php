@extends('_admin._layout.app')

@section('title', 'Detail Tugas')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div
            class="bg-white overflow-hidden shadow-lg rounded-2xl dark:bg-neutral-800 border-2 border-gray-100 dark:border-neutral-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700 flex items-center">
                <a href="{{ route('admin.tasks.index') }}"
                    class="py-3 px-3 inline-flex items-center gap-x-2 text-xl rounded-xl border border-gray-200 bg-white text-gray-800 shadow-md hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 cursor-pointer">
                    <svg class="shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="90" height="90"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="m12 19-7-7 7-7" />
                        <path d="M19 12H5" />
                    </svg>
                </a>
                <div class="ms-3">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                        Detail {{ $page['title'] }}
                    </h2>
                </div>
            </div>

            <div class="p-6">
                <div class="space-y-6">
                    <div>
                        <h3 class="text-sm font-medium uppercase tracking-wide text-gray-500 dark:text-neutral-500">
                            Judul Tugas
                        </h3>
                        <p class="mt-1 text-lg font-semibold text-gray-800 dark:text-neutral-200">
                            {{ $data->title }}
                        </p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium uppercase tracking-wide text-gray-500 dark:text-neutral-500">
                            Deskripsi
                        </h3>
                        <p class="mt-1 text-lg font-semibold text-gray-800 dark:text-neutral-200">
                            {{ $data->description }}
                        </p>
                    </div>

                    <div
                        class="grid grid-cols-1 sm:grid-cols-2 gap-6 border-t border-gray-200 dark:border-neutral-700 pt-6">
                        <div>
                            <h3 class="text-sm font-medium uppercase tracking-wide text-gray-500 dark:text-neutral-500">
                                Kategori
                            </h3>
                            <p class="mt-1 text-base font-medium text-gray-800 dark:text-neutral-200">
                                {{ $data->task_category_name }}
                            </p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium uppercase tracking-wide text-gray-500 dark:text-neutral-500">
                                Status
                            </h3>
                            <div class="mt-1">
                                @if ($data->status == \App\Constants\TaskStatusConst::TODO)
                                    <span
                                        class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-white/10 dark:text-white">
                                        To Do
                                    </span>
                                @elseif($data->status == \App\Constants\TaskStatusConst::IN_PROGRESS)
                                    <span
                                        class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800/30 dark:text-blue-500">
                                        In Progress
                                    </span>
                                @elseif($data->status == \App\Constants\TaskStatusConst::DONE)
                                    <span
                                        class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-teal-100 text-teal-800 dark:bg-teal-800/30 dark:text-teal-500">
                                        Done
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-neutral-700 dark:text-neutral-200">
                                        {{ $data->status }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium uppercase tracking-wide text-gray-500 dark:text-neutral-500">
                                Tanggal Tugas
                            </h3>
                            <p class="mt-1 text-base font-medium text-gray-800 dark:text-neutral-200">
                                {{ \Carbon\Carbon::parse($data->task_date)->format('d F Y') }}
                            </p>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium uppercase tracking-wide text-gray-500 dark:text-neutral-500">
                                Dibuat Pada
                            </h3>
                            <div class="mt-1">
                                <p class="text-base font-medium text-gray-800 dark:text-neutral-200">
                                    {{ \Carbon\Carbon::parse($data->created_at)->format('d F Y, H:i') }}
                                </p>
                                <p class="text-xs text-gray-400 dark:text-neutral-500">
                                    {{ \Carbon\Carbon::parse($data->created_at)->diffForHumans() }}
                                </p>
                            </div>
                        </div>

                        @if (!empty($data->updated_at))
                            <div>
                                <h3 class="text-sm font-medium uppercase tracking-wide text-gray-500 dark:text-neutral-500">
                                    Terakhir Diupdate
                                </h3>
                                <div class="mt-1">
                                    <p class="text-base font-medium text-gray-800 dark:text-neutral-200">
                                        {{ \Carbon\Carbon::parse($data->updated_at)->format('d F Y, H:i') }}
                                    </p>
                                    <p class="text-xs text-gray-400 dark:text-neutral-500">
                                        {{ \Carbon\Carbon::parse($data->updated_at)->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="mt-8 flex justify-end gap-x-2 border-t border-gray-200 dark:border-neutral-700 pt-6">
                        <button type="button"
                            class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-red-100 text-red-800 hover:bg-red-200 focus:outline-none focus:bg-red-200 disabled:opacity-50 disabled:pointer-events-none dark:text-red-500 dark:bg-red-800/30 dark:hover:bg-red-800/20 dark:focus:bg-red-800/20 cursor-pointer"
                            data-hs-overlay="#delete-modal"
                            onclick="setDeleteData('{{ $data->id }}', '{{ $data->title }}')">
                            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 6h18"></path>
                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                            </svg>
                            Hapus
                        </button>
                        <a href="{{ route('admin.tasks.update', $data->id) }}" navigate
                            class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none cursor-pointer">
                            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                            </svg>
                            Edit
                        </a>
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
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M18 6 6 18"></path>
                            <path d="m6 6 12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="p-4 sm:p-10 text-center overflow-y-auto">
                    <!-- Icon -->
                    <span
                        class="mb-4 inline-flex justify-center items-center size-14 rounded-full border-4 border-red-50 bg-red-100 text-red-500 dark:bg-red-700 dark:border-red-600 dark:text-red-100">
                        <svg class="shrink-0 size-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"></path>
                            <path d="M12 9v4"></path>
                            <path d="M12 17h.01"></path>
                        </svg>
                    </span>
                    <!-- End Icon -->

                    <h3 id="delete-modal-label" class="mb-2 text-xl font-bold text-gray-800 dark:text-neutral-200">
                        Hapus Tugas
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
            document.getElementById('delete-form').action = '{{ url('admin/tasks/delete') }}/' + id;
        }
    </script>
@endsection
