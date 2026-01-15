@extends('_toolsman._layout.app')

@section('title', 'Data Peminjaman')

@section('content')
<div class="grid gap-3 md:flex md:justify-between md:items-center py-4">
    <div>
        <h1 class="text-2xl font-extrabold text-gray-800 dark:text-neutral-200 mb-1">
            Data Peminjaman User
        </h1>
        <p class="text-md text-gray-400 dark:text-neutral-400">
            Kelola persetujuan dan pemantauan barang.
        </p>
    </div>
</div>

{{-- Navigation Tabs --}}
<div class="border-b border-gray-200 dark:border-neutral-700 mb-6">
    <nav class="flex space-x-2" aria-label="Tabs" role="tablist">
        <a href="{{ route('toolsman.loans.index', ['status' => 'pending']) }}"
            class="py-4 px-1 inline-flex items-center gap-x-2 border-b-2 {{ request('status', 'pending') == 'pending' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-blue-600' }} text-sm font-medium whitespace-nowrap">
            Menunggu Persetujuan
            <span class="inline-flex items-center py-0.5 px-1.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-neutral-700 dark:text-neutral-300">
                {{ $countPending ?? 0 }}
            </span>
        </a>
        <a href="{{ route('toolsman.loans.index', ['status' => 'approve']) }}"
            class="py-4 px-1 inline-flex items-center gap-x-2 border-b-2 {{ request('status') == 'approve' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-blue-600' }} text-sm font-medium whitespace-nowrap">
            Dalam Peminjaman
            <span class="inline-flex items-center py-0.5 px-1.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-neutral-700 dark:text-neutral-300">
                {{ $countApprove ?? 0 }}
            </span>
        </a>
        <a href="{{ route('toolsman.loans.index', ['status' => 'reject']) }}"
            class="py-4 px-1 inline-flex items-center gap-x-2 border-b-2 {{ request('status') == 'reject' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-blue-600' }} text-sm font-medium whitespace-nowrap">
            Peminjaman Ditolak
            <!-- <span class="inline-flex items-center py-0.5 px-1.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-neutral-700 dark:text-neutral-300">
                {{ $countReject ?? 0 }}
            </span> -->
        </a>
    </nav>
</div>

<div class="flex flex-col">
    {{-- Bagian Form Search tetap ada di sini --}}
    <div class="px-2 pb-4">
        <form action="{{ route('toolsman.loans.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <input type="hidden" name="status" value="{{ request('status', 'pending') }}">
            <div class="sm:w-64">
                <input type="text" name="keywords" value="{{ request('keywords') }}"
                    class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400"
                    placeholder="Cari Nama User/Barang...">
            </div>
            <button type="submit" class="py-2 px-3 inline-flex items-center gap-x-1 text-sm font-semibold rounded-lg bg-blue-600 text-white hover:bg-blue-700 cursor-pointer">
                @include('_toolsman._layout.icons.search') Cari
            </button>
        </form>
    </div>

    {{-- Table Area --}}
    <div class="overflow-x-auto border border-gray-200 rounded-lg dark:border-neutral-700">
        <table class="w-full divide-y divide-gray-200 dark:divide-neutral-700">
            <thead class="bg-gray-50 dark:bg-neutral-800">
                <tr>
                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">User & Barang</th>
                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">Tanggal Pinjam</th>
                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">Batas Kembali</th>
                    <th class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">Status</th>
                    <th class="px-6 py-3 text-end text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                @forelse($loans as $loan)
                <tr class="hover:bg-gray-50 dark:hover:bg-neutral-700">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-gray-800 dark:text-neutral-200">{{ $loan->user->username }}</span>
                            <span class="text-xs text-gray-500">{{ $loan->tool->name }} ({{ $loan->quantity }} Unit)</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                        {{ \Carbon\Carbon::parse($loan->loan_date)->translatedFormat('d F Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                        {{ \Carbon\Carbon::parse($loan->due_date)->translatedFormat('d F Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                        <div class="flex flex-col">
                            <span class="inline-flex items-center py-1 rounded-full text-sm font-medium {{ $loan->status_color }}">{{ $loan->keterangan_status }}</span>
                            @if($loan->fine_amount > 0)
                                <span class="text-xs text-gray-500">Denda {{ $loan->fine_amount }}</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                        <div class="flex justify-end items-center gap-x-2">
                            @if($loan->status === 'pending')
                                {{-- Tombol Setujui --}}
                                <form action="{{ route('toolsman.loans.approve', $loan->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                        class="py-1.5 px-3 inline-flex items-center gap-x-1 text-xs font-bold rounded-lg border border-transparent bg-blue-100 text-blue-600 hover:bg-blue-200 dark:bg-blue-900/30 dark:text-blue-500 cursor-pointer">
                                        Setujui
                                    </button>
                                </form>

                                {{-- Tombol Tolak --}}
                                <form action="{{ route('toolsman.loans.reject', $loan->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                        class="py-1.5 px-3 inline-flex items-center gap-x-1 text-xs font-bold rounded-lg border border-transparent bg-red-100 text-red-600 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-500 cursor-pointer">
                                        Tolak
                                    </button>
                                </form>
                            @else
                                {{-- Tombol Dikembalikan --}}
                                @if($loan->status === 'approve')
                                <form action="{{ route('toolsman.loans.returned', $loan->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                        class="py-1.5 px-3 inline-flex items-center gap-x-1 text-xs font-bold rounded-lg border border-transparent bg-blue-100 text-blue-600 hover:bg-blue-200 dark:bg-blue-900/30 dark:text-blue-500 cursor-pointer">
                                        Tandai Dikembalikan
                                    </button>
                                </form>
                                @endif
                                {{-- Tombol Detail untuk status selain pending --}}
                                <a href="{{ route('toolsman.loans.index', $loan->id) }}" 
                                    class="py-1.5 px-3 inline-flex items-center gap-x-1 text-xs font-bold rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300 dark:hover:bg-neutral-700">
                                    Detail
                                </a>
                            @endif
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

<script>
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