@extends('_toolsman._layout.app')

@section('title', 'Manajemen Denda Pengguna')

@section('content')
{{-- Header Section --}}
<div class="grid gap-3 md:flex md:justify-between md:items-center py-4">
    <div>
        <h1 class="text-2xl font-extrabold text-gray-800 dark:text-neutral-200 mb-1">
            Manajemen Denda Pengguna
        </h1>
        <p class="text-md text-gray-400 dark:text-neutral-400">
            Manajemen pembayaran denda yang diberikan kepada pengguna.
        </p>
    </div>

</div>

<div class="border-b border-gray-200 dark:border-neutral-700 mb-6">
    <nav class="flex space-x-4 overflow-x-auto no-scrollbar" aria-label="Tabs" role="tablist">
        
        <a href="{{ route('toolsman.fines.index', ['fine_status' => 0]) }}"
            class="py-4 px-1 inline-flex items-center gap-x-2 border-b-2 {{ request('fine_status', 0) == 0 ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-blue-600' }} text-sm font-medium whitespace-nowrap flex-shrink-0">
            @include('_toolsman._layout.icons.belum_bayar')
            Belum Bayar
            <span class="inline-flex items-center py-0.5 px-1.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-neutral-700 dark:text-neutral-300">
                {{ $countBelumBayar ?? 0 }}
            </span>
        </a>

        <a href="{{ route('toolsman.fines.index', ['fine_status' => 1]) }}"
            class="py-4 px-1 inline-flex items-center gap-x-2 border-b-2 {{ request('fine_status') == 1 ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-blue-600' }} text-sm font-medium whitespace-nowrap flex-shrink-0">
            @include('_toolsman._layout.icons.lunas')
            Pembayaran Lunas
            <span class="inline-flex items-center py-0.5 px-1.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-neutral-700 dark:text-neutral-300">
                {{ $countLunas ?? 0 }}
            </span>
        </a>

    </nav>
</div>

<div class="flex flex-col">
    {{-- Search & Filter Section --}}
    <div class="px-2 pb-4">
        <form action="{{ route('toolsman.tools.index') }}" method="GET" navigate-form
            class="flex flex-col sm:flex-row gap-3">
            
            <div class="sm:w-80">
                <input type="text" name="keywords" id="keywords" value="{{ $keywords ?? '' }}"
                    class="py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400"
                    placeholder="Cari nama pengguna...">
            </div>

            <div class="flex gap-x-2">
                <button type="submit"
                    class="py-2 px-3 inline-flex items-center gap-x-1 text-sm font-semibold rounded-lg bg-blue-600 text-white hover:bg-blue-700 cursor-pointer">
                    @include('_toolsman._layout.icons.search')
                    Cari
                </button>
                
                @if (!empty($keywords))
                    <a href="{{ route('toolsman.tools.index') }}"
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
                    <th scope="col" class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">Nama Pengguna</th>
                    <th scope="col" class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">Barang yang dipinjam</th>
                    <th scope="col" class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">Hari Keterlambatan</th>
                    <th scope="col" class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">Total Denda</th>
                    <th scope="col" class="px-6 py-3 text-start text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">Status</th>
                    <th scope="col" class="px-6 py-3 text-end text-xs font-semibold uppercase text-gray-800 dark:text-neutral-200">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                @forelse($fineLoans as $fineLoan)
                    <tr class="hover:bg-gray-50 dark:hover:bg-neutral-700/50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-bold text-gray-800 dark:text-neutral-200">
                                {{ $fineLoan->user->username }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-gray-800 dark:text-neutral-200">{{ $fineLoan->tool->name }}</span>
                                <span class="text-xs text-gray-500 dark:text-neutral-500">{{ $fineLoan->quantity }} Unit</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                            {{ $fineLoan->hari_terlambat }} Hari
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200 font-bold">
                            Rp. {{ number_format($fineLoan->fine_amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200 font-medium">
                            @if($fineLoan->fine_status == 0)
                                <span class="text-red-500">Belum Bayar</span>
                            @elseif($fineLoan->fine_status == 1)
                                <span class="text-green-500">Lunas</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                            <a navigate href="{{ route('toolsman.fines.pay', $fineLoan->id) }}" 
                                class="py-2 px-3 inline-flex items-center rounded-lg border-transparent bg-blue-100 text-blue-600 hover:bg-blue-200 dark:bg-blue-900/30 dark:text-blue-500 font-bold text-xs" title="View">
                                Proses Bayar
                            </a>
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
    {{ $fineLoans->links() }}
</div>

<style>
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>

<script>
    function setDeleteData(id, name) {
        document.getElementById('delete-item-name').textContent = name;
        // Pastikan URL action delete mengarah ke route toolsman yang benar
        document.getElementById('delete-form').action = '{{ url("toolsman/tools/delete") }}/' + id;
    }
</script>
@endsection