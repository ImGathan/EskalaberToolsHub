@extends('_admin._layout.app')

@section('title', 'Update User')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white overflow-hidden shadow-lg rounded-2xl dark:bg-neutral-800 border-2 border-gray-100">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700 flex items-center">
                <a href="{{ route('admin.users.index') }}"
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
                        Edit Data Pengguna
                    </h2>
                </div>
            </div>

            <form id="update-form" class="p-6" navigate-form action="{{ route('admin.users.doUpdate', $data->id) }}"
                method="POST">
                @csrf

                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium mb-2 dark:text-white">Username <span
                            class="text-red-500">*</span></label>
                    <input type="text" id="username" name="username" value="{{ $data->username ?? '' }}"
                        class="py-2.5 sm:py-3 px-4 block w-full border-gray-200 rounded-lg sm:text-sm focus:border-blue-500 focus:ring-blue-500 placeholder-neutral-300 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 @error('username') border-red-500 @enderror"
                        placeholder="Enter full name" required>
                    @error('username')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium mb-2 dark:text-white">Email <span
                            class="text-red-500">*</span></label>
                    <input type="email" id="email" name="email" value="{{ $data->email ?? '' }}"
                        class="py-2.5 sm:py-3 px-4 block w-full border-gray-200 rounded-lg sm:text-sm focus:border-blue-500 focus:ring-blue-500 placeholder-neutral-300 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 @error('email') border-red-500 @enderror"
                        placeholder="you@site.com" required>
                    @error('email')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Department --}}
                    <div class="mb-4">
                        <label for="department_id" class="block text-sm font-medium mb-2 dark:text-white">Jurusan/Unit <span
                                class="text-red-500">*</span></label>
                        <select id="department_id" name="department_id"
                            class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 @error('department_id') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                            required>
                            <option value="">-- Pilih Jurusan/Unit --</option>
                            @foreach ($departments as $department)
                            <option value="{{ $department->id }}" data-name="{{ $department->name }}"
                                {{ $data->department_id == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('department_id')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    @php
                        $currentYear = date('Y');
                        $currentMonth = date('n');
                        $maxYear = ($currentMonth >= 7) ? $currentYear : $currentYear - 1;
                        $minYear = $maxYear - 2; 
                    @endphp
                    <div id="container_years_in" style="display: none;">
                        <label for="years_in" class="block text-sm font-medium mb-2 dark:text-white">Tahun Masuk <span class="text-red-500">*</span></label>
                        <input type="number"
                            min="{{ $minYear }}"
                            max="{{ $maxYear }}"
                            id="years_in" 
                            name="years_in" 
                            value="{{ $data->years_in }}"
                            {{-- Trik agar tidak bisa input lebih dari 4 digit --}}
                            oninput="if(this.value.length > 4) this.value = this.value.slice(0, 4);"
                            class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 @error('years_in') border-red-500 @enderror"
                            placeholder="Contoh: 2024">   
                        @error('years_in')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>


                <div class="flex justify-start gap-x-2 mt-4">
                    <a navigate href="{{ route('admin.users.index') }}"
                        class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:bg-gray-50 dark:bg-transparent dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800">
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
    
    <script>
        function initYearToggle() {
            const deptSelect = document.getElementById('department_id');
            const yearContainer = document.getElementById('container_years_in');
            const yearInput = document.getElementById('years_in');

            if (!deptSelect || !yearContainer) return;

            function toggleYearInput() {
                const selectedOption = deptSelect.options[deptSelect.selectedIndex];
                const deptName = selectedOption ? selectedOption.getAttribute('data-name') : '';

                if (deptName && deptName !== 'Tenaga Pendidik/Karyawan') {
                    yearContainer.style.display = 'block';
                    yearInput.setAttribute('required', 'required');
                } else {
                    yearContainer.style.display = 'none';
                    yearInput.removeAttribute('required');
                    yearInput.value = '';
                }
            }

            // Hapus listener lama biar gak tumpang tindih
            deptSelect.removeEventListener('change', toggleYearInput);
            deptSelect.addEventListener('change', toggleYearInput);
            
            // Jalankan sekali saat inisialisasi
            toggleYearInput();
        }

        // 1. Handle Navigasi SPA (Livewire/Turbo)
        document.addEventListener("turbo:load", initYearToggle);
        document.addEventListener("livewire:navigated", initYearToggle);
        document.addEventListener("DOMContentLoaded", initYearToggle);

        // 2. Satpam Pintar (MutationObserver) - Pengganti setInterval
        const observer = new MutationObserver((mutations) => {
            if (document.getElementById('department_id')) {
                initYearToggle();
            }
        });

        observer.observe(document.body, { childList: true, subtree: true });
    </script>


@endsection
