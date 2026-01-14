@extends('_admin._layout.app')

@section('title', 'Edit Tugas')

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
                        Edit {{ $page['title'] }}
                    </h2>
                </div>
            </div>

            <form navigate-form action="{{ route('admin.tasks.do_update', $id) }}" method="POST" class="p-6">
                @csrf
                <div class="space-y-4">
                    <!-- Form Group -->
                    <div>
                        <label for="title" class="block text-sm font-medium mb-2 dark:text-white">Judul Tugas <span
                                class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="text" id="title" name="title" value="{{ old('title', $data->title) }}"
                                class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 @error('title') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                                required>
                        </div>
                        @error('title')
                            <p class="text-xs text-red-600 mt-2" id="title-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- End Form Group -->

                    <!-- Form Group -->
                    <div>
                        <label for="task_category_id" class="block text-sm font-medium mb-2 dark:text-white">Kategori <span
                                class="text-red-500">*</span></label>
                        <div class="relative">
                            <select name="task_category_id" id="task_category_id"
                                class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 @error('task_category_id') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                                required>
                                <option value="">Pilih Kategori</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ old('task_category_id', $data->task_category_id) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('task_category_id')
                            <p class="text-xs text-red-600 mt-2" id="task_category_id-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- End Form Group -->

                    <div class="flex gap-4">
                        <!-- Form Group -->
                        <div class="w-1/2">
                            <label for="task_date" class="block text-sm font-medium mb-2 dark:text-white">Tanggal <span
                                    class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="text" id="task_date" name="task_date"
                                    value="{{ old('task_date', $data->task_date->format('Y-m-d')) }}"
                                    class="datepicker py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 @error('task_date') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                                    placeholder="YYYY-MM-DD" required>
                            </div>
                            @error('task_date')
                                <p class="text-xs text-red-600 mt-2" id="task_date-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <!-- End Form Group -->

                        <!-- Form Group -->
                        <div class="w-1/2">
                            <label for="status" class="block text-sm font-medium mb-2 dark:text-white">Status <span
                                    class="text-red-500">*</span></label>
                            <div class="relative">
                                <select name="status" id="status"
                                    class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 @error('status') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                                    required>
                                    @foreach ($statuses as $key => $label)
                                        <option value="{{ $key }}"
                                            {{ old('status', $data->status) == $key ? 'selected' : '' }}>
                                            {{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('status')
                                <p class="text-xs text-red-600 mt-2" id="status-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <!-- End Form Group -->
                    </div>

                    <!-- Form Group -->
                    <div>
                        <label for="description" class="block text-sm font-medium mb-2 dark:text-white">Deskripsi</label>
                        <div class="relative">
                            <textarea id="description" name="description" rows="3"
                                class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 @error('description') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror">{{ old('description', $data->description) }}</textarea>
                        </div>
                        @error('description')
                            <p class="text-xs text-red-600 mt-2" id="description-error">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- End Form Group -->

                </div>

                <div class="mt-4 flex justify-start gap-x-2">
                    <a navigate href="{{ route('admin.tasks.index') }}"
                        class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:bg-gray-50 dark:bg-transparent dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-800">
                        Batal
                    </a>
                    <button type="submit"
                        class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none cursor-pointer">
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                            <polyline points="17 21 17 13 7 13 7 21" />
                            <polyline points="7 3 7 8 15 8" />
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
