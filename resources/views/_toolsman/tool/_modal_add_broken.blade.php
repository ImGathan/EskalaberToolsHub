<div id="broken-modal" class="hs-overlay hidden w-full h-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none flex items-center justify-center">
    <div class="hs-overlay-open:opacity-100 hs-overlay-open:duration-500 opacity-0 ease-out transition-all sm:max-w-sm w-[calc(100%-2rem)] m-4 sm:mx-auto">
        {{-- Card Modal --}}
        <div class="flex flex-col bg-white border border-gray-100 rounded-xl pointer-events-auto shadow-xl dark:bg-neutral-800 dark:border-neutral-700 overflow-hidden">
            
            <form id="broken-form" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="p-6">
                    {{-- Header --}}
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Maintenance</h3>
                        <button type="button" class="inline-flex justify-center items-center size-8 rounded-lg text-gray-400 hover:bg-gray-100 dark:hover:bg-neutral-700" data-hs-overlay="#broken-modal">
                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                        </button>
                    </div>

                    {{-- Counter Section --}}
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg dark:bg-neutral-900/50 border border-transparent dark:border-neutral-700 text-center">
                        <span class="text-3xl font-bold text-blue-600 dark:text-blue-500" id="display-qty">1</span>
                        <p class="text-[10px] text-gray-400 uppercase tracking-widest mt-1 font-bold">Unit Terpilih</p>
                    </div>

                    {{-- Input Area --}}
                    <div class="space-y-3">
                        <div class="max-w-full">
                            <label class="text-[11px] font-bold text-gray-500 dark:text-neutral-400 uppercase ml-1 mb-1 block">Nama Barang</label>
                            <div class="relative">
                                <div id="broken-item-name" 
                                     class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-4 text-sm font-bold text-gray-800 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-300 truncate block" 
                                     title="">
                                    -
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="text-[11px] font-bold text-gray-500 dark:text-neutral-400 uppercase ml-1 mb-1 block">Jumlah Rusak</label>
                            <div class="flex items-center gap-x-2">
                                <button type="button" onclick="changeBrokenQty(-1)" class="flex-shrink-0 size-11 inline-flex justify-center items-center bg-gray-50 border border-gray-200 text-gray-800 rounded-lg hover:bg-gray-100 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white transition-all">
                                    <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/></svg>
                                </button>
                                
                                <input type="number" name="broken_qty" id="broken_qty_input" value="1" min="1" 
                                    oninput="syncDisplay()"
                                    class="w-full bg-gray-50 border-gray-200 rounded-lg py-2.5 px-4 text-center text-lg font-bold focus:bg-white focus:ring-2 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white">
                                
                                <button type="button" onclick="changeBrokenQty(1)" class="flex-shrink-0 size-11 inline-flex justify-center items-center bg-gray-50 border border-gray-200 text-gray-800 rounded-lg hover:bg-gray-100 dark:bg-neutral-900 dark:border-neutral-700 dark:text-white transition-all">
                                    <svg class="size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14m-7-7v14"/></svg>
                                </button>
                            </div>
                            <p id="msg-error" class="text-[10px] text-red-500 mt-2 text-center hidden font-semibold">Melebihi stok!</p>
                        </div>
                    </div>
                </div>
                
                {{-- Footer --}}
                <div class="px-6 pb-6 flex flex-col gap-y-2">
                    <button type="submit" id="submit-broken" 
                        class="w-full py-3 px-4 rounded-lg text-sm font-bold bg-blue-600 text-white hover:bg-blue-700 shadow-md transition-all">
                        Konfirmasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentWarehouseStock = 0;

function setBrokenData(id, name, stock) {
    currentWarehouseStock = stock;
    const nameEl = document.getElementById('broken-item-name');
    nameEl.innerText = name;
    nameEl.title = name;
    
    document.getElementById('broken_qty_input').value = 1;
    document.getElementById('broken_qty_input').max = stock;
    document.getElementById('broken-form').action = `{{ url('toolsman/tools') }}/${id}/move-to-broken`;
    syncDisplay();
}

function changeBrokenQty(step) {
    const input = document.getElementById('broken_qty_input');
    let value = (parseInt(input.value) || 0) + step;
    if (value >= 1 && value <= currentWarehouseStock) {
        input.value = value;
        syncDisplay();
    }
}

function syncDisplay() {
    const input = document.getElementById('broken_qty_input');
    const display = document.getElementById('display-qty');
    const submitBtn = document.getElementById('submit-broken');
    const errorMsg = document.getElementById('msg-error');
    
    let val = parseInt(input.value) || 0;
    display.innerText = val;

    if (val > currentWarehouseStock || val < 1) {
        submitBtn.disabled = true;
        display.classList.remove('text-blue-600');
        display.classList.add('text-red-500');
        errorMsg.classList.remove('hidden');
    } else {
        submitBtn.disabled = false;
        display.classList.remove('text-red-500');
        display.classList.add('text-blue-600');
        errorMsg.classList.add('hidden');
    }
}
</script>