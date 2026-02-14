@extends('_toolsman._layout.app')

@section('title', 'Data Barang Rusak')

@section('content')
{{-- Header Section --}}
<div class="grid gap-3 md:flex md:justify-between md:items-center py-4">
    <div>
        <h1 class="text-2xl font-extrabold text-gray-800 dark:text-neutral-200 mb-1">
            Data Barang Rusak
        </h1>
        <p class="text-md text-gray-400 dark:text-neutral-400">
            Kelola dan maintenance barang yang sedang dalam perbaikan.
        </p>
    </div>

</div>

<div class="flex flex-col">
    {{-- Search & Filter Section --}}
    <div class="px-2 pb-4">
        <form action="{{ route('toolsman.maintenance-tools.index') }}" method="GET" navigate-form
            class="flex flex-col sm:flex-row gap-3">
            
            <div class="sm:w-80">
                <input type="text" name="keywords" id="keywords" value="{{ $keywords ?? '' }}"
                    class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400"
                    placeholder="Cari nama barang atau kategori...">
            </div>

            <div class="flex gap-x-2">
                <button type="submit"
                    class="py-2 px-3 inline-flex items-center gap-x-1 text-sm font-semibold rounded-lg bg-blue-600 text-white hover:bg-blue-700 cursor-pointer">
                    @include('_toolsman._layout.icons.search')
                    Cari
                </button>
                
                @if (!empty($keywords))
                    <a href="{{ route('toolsman.maintenance-tools.index') }}"
                        class="py-2 px-3 inline-flex items-center gap-x-1 text-sm font-semibold rounded-lg border border-gray-200 bg-white text-gray-800 hover:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300">
                        @include('_toolsman._layout.icons.reset')
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
                    <th scope="col" class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">Kategori & Lokasi</th>
                    <th scope="col" class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">Jenis Barang</th>
                    <th scope="col" class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">Jumlah Rusak</th>
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
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-gray-800 dark:text-neutral-200">{{ $tool->category->name }}</span>
                                <span class="text-xs text-gray-500 dark:text-neutral-500">{{ $tool->place->name ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-gray-800 dark:text-neutral-200">{{ $tool->type->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                            <span class="font-bold">{{ $tool->broken_qty }}</span>
                            <span class="text-xs text-gray-500 dark:text-neutral-500 ml-0.5">Unit</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                            <form action="{{ route('toolsman.maintenance-tools.restore', $tool->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="flex justify-end items-center gap-x-3">
                                    
                                    {{-- Input Wrapper --}}
                                    <div class="flex items-center bg-white border border-gray-200 rounded-lg dark:bg-neutral-900 dark:border-neutral-700">
                                        {{-- Tombol Minus --}}
                                        <button type="button" 
                                            onclick="this.parentNode.querySelector('input[type=number]').stepDown()"
                                            class="size-9 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-s-lg border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 dark:text-white dark:hover:bg-neutral-800">
                                            <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/></svg>
                                        </button>

                                        {{-- Input Angka --}}
                                        <input type="number" 
                                            name="qty_to_restore" 
                                            value="0" 
                                            min="0" 
                                            max="{{ $tool->broken_qty }}"
                                            class="p-0 w-10 bg-transparent border-0 text-gray-800 text-center focus:ring-0 dark:text-white [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                            readonly>

                                        {{-- Tombol Plus --}}
                                        <button type="button" 
                                            onclick="this.parentNode.querySelector('input[type=number]').stepUp()"
                                            class="size-9 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-e-lg border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 dark:text-white dark:hover:bg-neutral-800">
                                            <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                                        </button>
                                    </div>

                                    {{-- Tombol Submit --}}
                                    <button type="submit" 
                                        class="py-2 px-3 inline-flex items-center gap-x-2 text-xs font-semibold rounded-lg border border-transparent bg-emerald-600 text-white hover:bg-emerald-700 disabled:opacity-50 shadow-sm"
                                        {{ $tool->broken_qty <= 0 ? 'disabled' : '' }}
                                        onclick="return confirm('Pindahkan barang ke stok tersedia?')">
                                        Pulihkan Stok
                                    </button>
                                </div>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center">
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

<script>
    function setDeleteData(id, name) {
        document.getElementById('delete-item-name').textContent = name;
        // Pastikan URL action delete mengarah ke route toolsman yang benar
        document.getElementById('delete-form').action = '{{ url("toolsman/tools/delete") }}/' + id;
    }

    async function updateBrokenQty(id, action) {
        const display = document.getElementById(`qty-display-${id}`);
        const btnDec = document.getElementById(`btn-dec-${id}`);
        const btnRestore = document.getElementById(`btn-restore-${id}`);
        let currentQty = parseInt(display.innerText);

        // Logic instan di UI
        if (action === 'increment') {
            currentQty++;
        } else if (action === 'decrement' && currentQty > 0) {
            currentQty--;
        }

        // Update Tampilan Langsung (Instan)
        display.innerText = currentQty;
        btnDec.disabled = (currentQty <= 0);
        if(btnRestore) btnRestore.disabled = (currentQty <= 0);

        // Kirim ke Server di Background
        try {
            const response = await fetch(`{{ url('toolsman/maintenance-tools') }}/${id}/update-qty`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ action: action })
            });

            const data = await response.json();

            if (!response.ok) {
                // Jika server menolak (misal melebihi stok), balikkan angka
                alert(data.message || 'Gagal update');
                location.reload(); 
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

</script>
@endsection