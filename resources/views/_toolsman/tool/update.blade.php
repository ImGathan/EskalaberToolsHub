@extends('_toolsman._layout.app')

@section('title', 'Update Barang')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="bg-white overflow-hidden shadow-lg rounded-2xl dark:bg-neutral-800 border-2 border-gray-100">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700 flex items-center">
            <a href="{{ route('toolsman.tools.index') }}"
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
                    Edit Barang
                </h2>
            </div>
        </div>

        <form id="update-form" class="p-6" navigate-form action="{{ route('toolsman.tools.do_update', $tool->id) }}"
            method="POST" enctype="multipart/form-data">
            @csrf

            <div>
                <label for="image" class="block text-sm font-medium mb-2 dark:text-white">Gambar Barang (Opsional)</label>
                
                <label for="image" class="group mt-1 flex flex-col justify-center items-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:bg-gray-50 dark:hover:bg-neutral-800 hover:border-blue-400 transition-all cursor-pointer relative">
                    
                    <input id="image" name="image" type="file" class="sr-only" onchange="previewImage(event)">
                    
                    <input type="hidden" name="remove_image" id="remove-image-flag" value="0">
                    
                    <div class="space-y-2 text-center">
                        <div class="flex justify-center relative">
                            <div class="relative inline-block">
                                <img src="{{ $tool->image ? asset('storage/' . $tool->image) : asset('admin/images/empty-data.webp') }}" 
                                    id="preview-img" 
                                    class="h-24 w-24 object-cover rounded-lg border shadow-sm {{ !$tool->image ? 'opacity-50' : '' }}">

                                <button type="button" id="remove-btn" onclick="removeImage(event)" 
                                    class="{{ $tool->image ? 'flex' : 'hidden' }} absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 shadow-md items-center justify-center w-6 h-6 transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="text-center">
                            <span class="text-sm font-semibold text-blue-600 group-hover:text-blue-500">Klik untuk unggah berkas</span>
                            <p class="text-xs text-gray-500 dark:text-neutral-500 mt-1">PNG, JPG, GIF hingga 2MB</p>
                        </div>
                    </div>
                </label>
            </div>

            {{-- Name --}}
            <div>
                <label for="name" class="block text-sm font-medium mb-2 dark:text-white">Nama Barang <span
                        class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ $tool->name }}"
                    class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 placeholder-neutral-300 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 @error('name') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                    placeholder="Contoh: Laptop" required>
                @error('name')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Quantity --}}
            <div>
                <label for="quantity" class="block text-sm font-medium mb-2 dark:text-white">Jumlah <span
                        class="text-red-500">*</span></label>
                <input type="number" id="quantity" name="quantity" value="{{ $tool->quantity }}"
                    class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 placeholder-neutral-300 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 @error('quantity') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                    placeholder="Contoh: 10" required>
                @error('quantity')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Category --}}
            <div>
                <label for="category_id" class="block text-sm font-medium mb-2 dark:text-white">Kategori <span
                        class="text-red-500">*</span></label>
                <input type="text" id="category_id" name="category_id" value="{{ $tool->category->name }}"
                        class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 placeholder-neutral-300 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 @error('category_id') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror" 
                        disabled>
                @error('category_id')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="place_id" class="block text-sm font-medium mb-2 dark:text-white">Lokasi Barang <span
                        class="text-red-500">*</span></label>
                <select id="place_id" name="place_id"
                    class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 @error('category_id') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                    required>
                    <option value="">-- Pilih Lokasi Barang --</option>
                    @foreach ($places as $place)
                    <option value="{{ $place->id }}"
                        {{ $tool->place_id == $place->id ? 'selected' : '' }}>
                        {{ $place->name }}
                    </option>
                    @endforeach
                </select>
                @error('place_id')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="quantity" class="block text-sm font-medium mb-2 dark:text-white">Harga Denda Keterlambatan<span
                        class="text-red-500">*</span></label>
                <input type="number" id="fine" name="fine" value="{{ $tool->fine }}"
                    class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 placeholder-neutral-300 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 @error('quantity') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                    placeholder="Contoh: 5000" required>
                @error('fine')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>


            {{-- Email --}}
            <!-- <div>
                    <label for="email" class="block text-sm font-medium mb-2 dark:text-white">Email Address <span
                            class="text-red-500">*</span></label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 placeholder-neutral-300 dark:border-neutral-700 dark:text-neutral-400 placeholder-neutral-300 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 @error('email') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                        placeholder="Contoh: john@example.com" required>
                    @error('email')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div> -->

            {{-- Access Type --}}
            <!-- <div>
                    <label for="access_type" class="block text-sm font-medium mb-2 dark:text-white">Hak Akses <span
                            class="text-red-500">*</span></label>
                    <select id="access_type" name="access_type"
                        class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 @error('access_type') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                        required>
                        <option value="">-- Pilih Hak Akses --</option>
                        <option value="1" {{ old('data.access_type') == '1' ? 'selected' : '' }}>Admin</option>
                        <option value="2" {{ old('data.access_type') == '2' ? 'selected' : '' }}>Toolsman</option>
                        <option value="3" {{ old('data.access_type') == '3' ? 'selected' : '' }}>User</option>
                    </select>
                    @error('access_type')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div> -->

            <div class="flex justify-start gap-x-2 mt-4">
                <a navigate href="{{ route('toolsman.tools.index') }}"
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
    function previewImage(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('preview-img');
        const removeBtn = document.getElementById('remove-btn');
        const removeFlag = document.getElementById('remove-image-flag');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('opacity-50');
                removeBtn.classList.remove('hidden');
                removeBtn.classList.add('flex');
                removeFlag.value = "0"; // Reset flag hapus karena ada file baru
            }
            reader.readAsDataURL(file);
        }
    }

    function removeImage(event) {
        event.preventDefault();
        event.stopPropagation();
        
        const preview = document.getElementById('preview-img');
        const input = document.getElementById('image');
        const removeBtn = document.getElementById('remove-btn');
        const removeFlag = document.getElementById('remove-image-flag');
        const defaultImage = "{{ asset('admin/images/empty-data.webp') }}";

        input.value = ""; // Kosongkan input file
        preview.src = defaultImage;
        preview.classList.add('opacity-50');
        removeBtn.classList.add('hidden');
        removeBtn.classList.remove('flex');
        
        removeFlag.value = "1"; // Tandai ke server bahwa gambar harus di-null-kan
    }
</script>

@endsection