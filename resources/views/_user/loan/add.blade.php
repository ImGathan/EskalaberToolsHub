@extends('_user._layout.app') {{-- Pastikan layout sesuai role --}}

@section('title', 'Ajukan Peminjaman')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="bg-white overflow-hidden shadow-md shadow-indigo-500/10 rounded-2xl dark:bg-neutral-800 border border-gray-100 dark:border-neutral-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700 flex items-center">
            <a href="{{ route('user.tools.index') }}"
                class="py-3 px-3 inline-flex items-center gap-x-2 text-xl rounded-xl border border-gray-200 bg-white text-gray-800 shadow-md hover:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 cursor-pointer">
                <svg class="shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m12 19-7-7 7-7" />
                    <path d="M19 12H5" />
                </svg>
            </a>
            <div class="ms-3">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">
                    Form Peminjaman Barang
                </h2>
            </div>
        </div>

        <form id="add-form" class="p-6" navigate-form action="{{ route('user.loans.create') }}" method="POST">
            @csrf

            <div class="space-y-4">
                {{-- Preview Barang yang Dipilih --}}
                <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-100 dark:border-blue-800 flex items-center gap-4">
                    <img src="{{ $selectedTool->image ? asset('storage/' . $selectedTool->image) : asset('admin/images/empty-data.webp') }}"
                        class="h-16 w-16 object-cover rounded-lg border shadow-sm">
                    <div>
                        <h4 class="font-bold text-blue-900 dark:text-blue-300">{{ $selectedTool->name }}</h4>
                        <p class="text-xs text-blue-700 dark:text-blue-400">Stok Tersedia: <span id="max-stock">{{ $selectedTool->quantity }}</span></p>
                    </div>
                    <input type="hidden" name="tool_id" value="{{ $selectedTool->id }}">
                </div>

                {{-- Quantity --}}
                <div>
                    <label for="quantity" class="block text-sm font-medium mb-2 dark:text-white">Jumlah Pinjam <span class="text-red-500">*</span></label>
                    <input type="number" id="quantity" name="quantity" value="{{ old('quantity', 1) }}"
                        min="1" max="{{ $selectedTool->quantity }}"
                        class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 @error('quantity') border-red-500 @enderror"
                        placeholder="Masukkan jumlah yang dipinjam" required>
                    @error('quantity')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Due Date (Tanggal Kembali) --}}
                <div>
                    <label for="due_date" class="block text-sm font-medium mb-2 dark:text-white">Tanggal Pengembalian <span class="text-red-500">*</span></label>
                    <input type="date" id="due_date" name="due_date" value="{{ old('due_date') }}"
                        class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 @error('due_date') border-red-500 @enderror"
                        required>
                    <p class="text-xs text-gray-500 mt-1 italic">* Harap kembalikan barang tepat waktu untuk menghindari denda.</p>
                    @error('due_date')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- Footer --}}
            <div class="mt-6 flex justify-start gap-x-2">
                <a href="{{ route('user.tools.index') }}"
                    class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 dark:bg-transparent dark:border-neutral-700 dark:text-neutral-300">
                    Batal
                </a>
                <button type="submit"
                    class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none cursor-pointer">
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M5 12h14" />
                        <path d="M12 5v14" />
                    </svg>
                    Ajukan Peminjaman
                </button>
            </div>
        </form>
    </div>

    {{-- Info Card (Samping) --}}
    <div class="hidden md:block">
        <div class="p-6 bg-gradient-to-br from-blue-600 to-blue-800 rounded-2xl shadow-xl text-white">
            <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info">
                    <circle cx="12" cy="12" r="10" />
                    <path d="M12 16v-4" />
                    <path d="M12 8h.01" />
                </svg>
                Aturan Peminjaman
            </h3>
            <ul class="space-y-3 text-sm text-blue-100">
                <li class="flex gap-2"><span>•</span> Status peminjaman akan diverifikasi oleh Admin/Toolsman.</li>
                <li class="flex gap-2"><span>•</span> Keterlambatan akan dikenakan denda otomatis sebesar Rp 5.000 / hari.</li>
                <li class="flex gap-2"><span>•</span> Harap menjaga kondisi barang yang dipinjam.</li>
            </ul>
        </div>
    </div>
</div>

<script>
    // Validasi sederhana agar input quantity tidak melebihi stok di client-side
    const qtyInput = document.getElementById('quantity');
    const maxStock = parseInt(document.getElementById('max-stock').innerText);

    qtyInput.addEventListener('input', function() {
        if (parseInt(this.value) > maxStock) {
            this.value = maxStock;
            alert('Jumlah tidak boleh melebihi stok tersedia');
        }
        if (parseInt(this.value) < 1) {
            this.value = 1;
        }
    });
</script>
@endsection