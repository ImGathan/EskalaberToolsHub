@extends('_toolsman._layout.app')

@section('title', 'Proses Pembayaran Denda')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="bg-white overflow-hidden shadow-md shadow-red-500/10 rounded-2xl dark:bg-neutral-800 border border-gray-100 dark:border-neutral-700">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-neutral-700 flex items-center">
            <a href="{{ route('toolsman.fines.index') }}"
                class="py-3 px-3 inline-flex items-center gap-x-2 text-xl rounded-xl border border-gray-200 bg-white text-gray-800 shadow-md hover:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 cursor-pointer">
                <svg class="shrink-0 size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
            </a>
            <div class="ms-3">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-200">Pembayaran Denda</h2>
            </div>
        </div>

        <form action="{{ route('toolsman.fines.paid', $fineLoan->id) }}" method="POST" class="p-6">
            @csrf
            @method('PATCH')

            <div class="space-y-4">

                <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-100 dark:border-blue-800 flex items-center gap-4">
                    <img src="{{ $fineLoan->tool->image ? asset('storage/' . $fineLoan->tool->image) : asset('admin/images/empty-data.webp') }}"
                        class="h-16 w-16 object-cover rounded-lg border shadow-sm">
                    <div>
                        <h4 class="font-bold text-blue-900 dark:text-blue-300">{{ $fineLoan->tool->name }} <span class="text-sm text-blue-700 dark:text-blue-400">({{ $fineLoan->quantity }} Unit)</span></h4>
                        <p class="text-xs text-blue-700 dark:text-blue-400">Terlambat : <span>{{ $fineLoan->hari_terlambat }} Hari</span></p>
                    </div>
                    <input type="hidden" name="tool_id" value="{{ $fineLoan->tool->id }}">
                </div>

                {{-- Input Total Denda (Read Only) --}}
                <div>
                    <label class="block text-sm font-medium mb-2 dark:text-white">Total Denda</label>
                    <div class="relative">
                        <input type="text" value="Rp {{ number_format($fineLoan->fine_amount, 0, ',', '.') }}" 
                            class="py-3 px-4 block w-full bg-gray-100 border-transparent rounded-lg text-lg font-bold text-red-600 dark:bg-neutral-700 dark:text-red-500" readonly>
                        <input type="hidden" id="fine_amount" value="{{ $fineLoan->fine_amount }}">
                    </div>
                </div>

                {{-- Input Jumlah Bayar --}}
                <div>
                    <label for="amount_paid" class="block text-sm font-medium mb-2 dark:text-white">Uang Dibayarkan <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="number" id="amount_paid" name="amount_paid" 
                            oninput="calculateChange()" {{-- Tambahkan ini --}}
                            class="py-3 px-4 block w-full border-gray-200 rounded-lg text-lg focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400"
                            placeholder="Contoh: 50000" required>
                    </div>
                </div>

                {{-- Kembalian Otomatis --}}
                <div class="p-4 bg-gray-50 dark:bg-neutral-900 rounded-xl border border-dashed border-gray-300 dark:border-neutral-700">
                    <p class="text-sm text-gray-500 mb-1">Kembalian</p>
                    <h3 id="display_change" class="text-md font-semibold text-gray-400">Rp 0</h3>
                </div>
            </div>

            <div class="mt-6 flex justify-start gap-x-2">
                <button type="submit" id="btn-submit" disabled
                    class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                    Konfirmasi Pembayaran
                </button>
            </div>
        </form>
    </div>

</div>

<script>
    function calculateChange() {
        const fineInput = document.getElementById('fine_amount');
        const paidInput = document.getElementById('amount_paid');
        const displayChange = document.getElementById('display_change');
        const btnSubmit = document.getElementById('btn-submit');

        if (!fineInput || !paidInput) return;

        const fineAmount = parseInt(fineInput.value) || 0;
        const paid = parseInt(paidInput.value) || 0;
        const change = paid - fineAmount;

        if (paid >= fineAmount) {
            // Format Rupiah
            const formatted = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                maximumFractionDigits: 0
            }).format(change);

            displayChange.innerText = formatted;
            
            // Ubah Warna & Aktifkan Tombol
            displayChange.classList.remove('text-gray-400', 'text-red-600');
            displayChange.classList.add('text-green-600');
            btnSubmit.disabled = false;
        } else {
            displayChange.innerText = paid > 0 ? "Uang Kurang" : "Rp 0";
            displayChange.classList.remove('text-green-600', 'text-gray-400');
            displayChange.classList.add('text-red-600');
            btnSubmit.disabled = true;
        }
    }

    // Jalankan ulang setiap kali halaman dimuat (untuk support Turbo/Livewire)
    document.addEventListener('phx:page-load', calculateChange); // Jika pakai Livewire
    document.addEventListener('turbo:load', calculateChange);    // Jika pakai Turbo

    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            const button = this.querySelector('button[type="submit"]');
            if (button) {
                // Tambahkan efek loading dan disable tombol
                button.disabled = true;
                button.classList.add('opacity-50', 'cursor-not-allowed');
                button.innerHTML = 'Memproses...';
            }
        });
    });

</script>
@endsection