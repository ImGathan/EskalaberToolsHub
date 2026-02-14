<div id="hs-modal-return-{{ $loan->id }}" class="hs-overlay hidden w-full h-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none flex items-center justify-center">
    <div class="hs-overlay-open:opacity-100 hs-overlay-open:duration-500 opacity-0 ease-out transition-all sm:max-w-md sm:w-full m-3 sm:mx-auto">
        {{-- Card Modal --}}
        <div class="flex flex-col bg-white border border-gray-100 rounded-xl pointer-events-auto shadow-xl dark:bg-neutral-800 dark:border-neutral-700">
            
            <form action="{{ route('toolsman.loans.returned', $loan->id) }}" method="POST" id="form-return-{{ $loan->id }}">
                @csrf
                @method('PATCH')
                
                <div class="p-6">
                    {{-- Header & Close Button --}}
                    <div class="flex justify-between items-center mb-6 px-1">
                        <div class="flex flex-col">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white leading-tight">
                                ID : #L{{ $loan->hash_id }}
                            </h3>
                        </div>

                        <button type="button" class="inline-flex flex-shrink-0 justify-center items-center size-9 rounded-xl text-gray-400 hover:bg-gray-100 hover:text-gray-900 transition-all dark:text-neutral-500 dark:hover:bg-neutral-700 dark:hover:text-white" data-hs-overlay="#hs-modal-return-{{ $loan->id }}">
                            <svg class="size-5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Counter Section --}}
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg dark:bg-neutral-900/50 border border-transparent dark:border-neutral-700 text-center">
                        <span class="text-3xl font-bold">
                            <span id="current-{{ $loan->id }}" class="text-blue-600 dark:text-blue-500">0</span><span class="text-gray-300 dark:text-neutral-600">/</span><span id="target-{{ $loan->id }}" class="text-gray-900 dark:text-white">{{ $loan->quantity }}</span>
                        </span>
                        <p class="text-[10px] text-gray-400 uppercase tracking-widest mt-1">Total Unit Terinput</p>
                    </div>

                    {{-- Input Area Stacked --}}
                    <div class="space-y-3">
                        <div>
                            <label class="text-[11px] font-bold text-gray-500 dark:text-neutral-400 uppercase ml-1 mb-1 block">1. Kondisi Baik</label>
                            <input type="number" name="qty_good" id="good-{{ $loan->id }}" value="{{ $loan->quantity }}" min="0" max="{{ $loan->quantity }}" 
                                oninput="validateReturn('{{ $loan->id }}')"
                                class="w-full bg-gray-50 border-gray-200 rounded-lg py-3 px-4 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white transition-all">
                        </div>

                        <div>
                            <label class="text-[11px] font-bold text-gray-500 dark:text-neutral-400 uppercase ml-1 mb-1 block">2. Kondisi Rusak</label>
                            <input type="number" name="qty_damaged" id="damaged-{{ $loan->id }}" value="0" min="0" max="{{ $loan->quantity }}" 
                                oninput="validateReturn('{{ $loan->id }}')"
                                class="w-full bg-gray-50 border-gray-200 rounded-lg py-3 px-4 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white transition-all">
                        </div>

                        <div>
                            <label class="text-[11px] font-bold text-gray-500 dark:text-neutral-400 uppercase ml-1 mb-1 block">3. Kondisi Hilang</label>
                            <input type="number" name="qty_lost" id="lost-{{ $loan->id }}" value="0" min="0" max="{{ $loan->quantity }}" 
                                oninput="validateReturn('{{ $loan->id }}')"
                                class="w-full bg-gray-50 border-gray-200 rounded-lg py-3 px-4 text-sm focus:bg-white focus:ring-2 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white transition-all">
                        </div>
                    </div>

                </div>
                
                {{-- Footer --}}
                <div class="px-6 pb-6 space-y-2">
                    <button type="submit" id="submit-{{ $loan->id }}" 
                        class="w-full py-3 px-4 rounded-lg text-sm font-bold bg-blue-600 text-white hover:bg-blue-700 disabled:bg-gray-100 disabled:text-gray-400 dark:disabled:bg-neutral-700 dark:disabled:text-neutral-500 shadow-md transition-all">
                        Konfirmasi Pengembalian
                    </button>
                    <button type="button" class="w-full py-3 text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-neutral-400 dark:hover:text-neutral-200 transition-colors" data-hs-overlay="#hs-modal-return-{{ $loan->id }}">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function validateReturn(id) {
    const targetElement = document.getElementById(`target-${id}`);
    const target = parseInt(targetElement.innerText);
    const good = parseInt(document.getElementById(`good-${id}`).value) || 0;
    const damaged = parseInt(document.getElementById(`damaged-${id}`).value) || 0;
    const lost = parseInt(document.getElementById(`lost-${id}`).value) || 0;
    
    const totalInput = good + damaged + lost;
    const currentDisplay = document.getElementById(`current-${id}`);
    const submitBtn = document.getElementById(`submit-${id}`);
    const msg = document.getElementById(`msg-${id}`);

    currentDisplay.innerText = totalInput;

    if (totalInput === target) {
        // PAS
        submitBtn.disabled = false;
        currentDisplay.classList.remove('text-gray-400', 'text-red-500');
        currentDisplay.classList.add('text-blue-600');
        msg.innerText = ""; // Bersih jika benar
    } else {
        // TIDAK PAS
        submitBtn.disabled = true;
        currentDisplay.classList.remove('text-blue-600', 'text-gray-400');
        currentDisplay.classList.add('text-red-500');
        
        let diff = target - totalInput;
        msg.innerText = diff > 0 ? `${diff} unit belum terdata` : `Kelebihan ${Math.abs(diff)} unit`;
        msg.classList.add('text-red-400');
    }
}

// Auto-run saat load
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[id^="target-"]').forEach(el => {
        validateReturn(el.id.split('-')[1]);
    });
});
</script>