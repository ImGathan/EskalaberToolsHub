@extends('_user._layout.app')

@section('title', 'Scan QR Code Barang')

@section('content')
<div class="min-h-[70vh] w-full flex items-center justify-center p-4 antialiased">
    <div class="w-full max-w-md bg-white dark:bg-neutral-800 rounded-xl shadow-lg border border-gray-200 dark:border-neutral-700 overflow-hidden transition-all duration-300">
        
        <div class="bg-white dark:bg-neutral-800 pt-6 text-center">
            <h2 class="text-xl font-extrabold text-slate-800 dark:text-white tracking-tight">Scan Cepat</h2>
            <p class="text-slate-500 dark:text-slate-400 text-xs opacity-90 mt-1">Arahkan kamera ke QR Code barang</p>
        </div>

        <div class="p-6">
            <div class="relative group aspect-square rounded-xl overflow-hidden border border-gray-200 dark:border-neutral-700 bg-gray-100 dark:bg-neutral-900 shadow-inner">
                <div id="reader" class="w-full h-full"></div>
                
                <div class="absolute inset-0 pointer-events-none flex flex-col items-center justify-center">
                    <div class="relative w-48 h-48">
                        <div class="absolute top-0 left-0 w-8 h-8 border-t-4 border-l-4 border-blue-600 rounded-tl-lg"></div>
                        <div class="absolute top-0 right-0 w-8 h-8 border-t-4 border-r-4 border-blue-600 rounded-tr-lg"></div>
                        <div class="absolute bottom-0 left-0 w-8 h-8 border-b-4 border-l-4 border-blue-600 rounded-bl-lg"></div>
                        <div class="absolute bottom-0 right-0 w-8 h-8 border-b-4 border-r-4 border-blue-600 rounded-br-lg"></div>
                        
                        <div class="w-full h-[2px] bg-blue-400 shadow-[0_0_15px_rgba(59,130,246,0.8)] absolute top-0 animate-scanning"></div>
                    </div>
                </div>
            </div>

            <div id="result" class="hidden mt-4 p-3 bg-emerald-100 dark:bg-emerald-500/20 border border-emerald-200 dark:border-emerald-500/30 rounded-xl text-center">
                <span class="text-emerald-700 dark:text-emerald-400 text-xs font-bold flex items-center justify-center gap-2">
                    <svg class="size-4 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    BERHASIL DIBACA! MENGALIHKAN...
                </span>
            </div>

            <div class="mt-8 flex justify-center gap-4">
                <button id="switch-camera" class="inline-flex items-center justify-center gap-x-2 font-bold transition-all active:scale-90 shadow-sm group
                    /* Spek HP: Jadi Bulat Sempurna */
                    size-14 rounded-full 
                    /* Spek Laptop: Jadi Memanjang */
                    sm:w-auto sm:px-6 sm:py-3 sm:rounded-xl
                    /* Warna */
                    border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 text-gray-800 dark:text-neutral-300 hover:bg-gray-50 dark:hover:bg-neutral-700">
                    
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-blue-600 group-hover:rotate-180 transition-transform duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>

                    <span class="hidden sm:inline text-xs">Ganti Lensa</span>
                </button>

                <label class="inline-flex items-center justify-center gap-x-2 font-bold transition-all active:scale-90 shadow-sm group cursor-pointer
                    /* Spek HP: Jadi Bulat Sempurna */
                    size-14 rounded-full 
                    /* Spek Laptop: Jadi Memanjang */
                    sm:w-auto sm:px-6 sm:py-3 sm:rounded-xl
                    /* Warna */
                    border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 text-gray-800 dark:text-neutral-300 hover:bg-gray-50 dark:hover:bg-neutral-700">
                    
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-indigo-600 group-hover:-translate-y-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>

                    <span class="hidden sm:inline text-xs">Upload File</span>
                    <input type="file" id="file-input" accept="image/*" class="hidden">
                </label>
            </div>

    </div>
</div>

<style>
    /* Professional Scan Animation */
    @keyframes scanMove {
        0%, 100% { top: 0; opacity: 0; }
        10% { opacity: 1; }
        90% { opacity: 1; }
        50% { top: 100%; }
    }
    .animate-scanning { animation: scanMove 2.5s ease-in-out infinite; }

    /* Mencegah Video Gepeng */
    #reader video {
        object-fit: cover !important;
        width: 100% !important;
        height: 100% !important;
        border-radius: 0.75rem;
    }
    
    /* Pembersihan UI Bawaan Library */
    #reader__dashboard_section_csr button { display: none !important; }
    #reader img { display: none !important; }
    #reader { border: none !important; }
</style>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    window.scannerInstance = window.scannerInstance || null;
    window.currentFacingMode = "environment";
    window.isStarting = false;
    window.isFileScanning = false; // Flag baru untuk mengunci satpam saat scan file

    async function stopScanner() {
        if (window.scannerInstance) {
            try {
                if (window.scannerInstance.isScanning) {
                    await window.scannerInstance.stop();
                }
                window.scannerInstance = null;
                console.log("Scanner stopped.");
            } catch (err) {
                console.warn("Stop error:", err);
            }
        }
    }

    async function startScanner(facingMode = "environment") {
        const reader = document.getElementById('reader');
        // Jangan start kamera kalau sedang proses pilih file atau sedang starting
        if (!reader || window.isStarting || window.isFileScanning) return;

        await stopScanner();
        
        window.isStarting = true;
        window.currentFacingMode = facingMode;
        window.scannerInstance = new Html5Qrcode("reader");

        try {
            await window.scannerInstance.start(
                { facingMode: facingMode },
                { fps: 10, qrbox: { width: 250, height: 250 } },
                (text) => {
                    document.getElementById('result').classList.remove('hidden');
                    stopScanner().then(() => window.location.href = text);
                }
            );
        } catch (err) {
            console.error("Gagal start kamera:", err);
        } finally {
            window.isStarting = false;
        }
    }

    // --- FIX FITUR PILIH FILE ---
    document.getElementById('file-input')?.addEventListener('change', async (e) => {
        if (e.target.files.length === 0) return;
        const file = e.target.files[0];
        
        // 1. Kunci satpam agar tidak menyalakan kamera otomatis
        window.isFileScanning = true;
        
        // 2. Matikan kamera jika sedang menyala
        await stopScanner();

        // 3. Buat instance sementara untuk scan file
        const fileScanner = new Html5Qrcode("reader");

        try {
            const text = await fileScanner.scanFile(file, true);
            document.getElementById('result').classList.remove('hidden');
            window.location.href = text;
        } catch (err) {
            alert("QR Code tidak ditemukan pada gambar ini.");
            // 4. Jika gagal, bebaskan satpam agar kamera menyala lagi
            window.isFileScanning = false;
        }
    });

    // Tombol Switch Kamera
    document.getElementById('switch-camera')?.addEventListener('click', () => {
        const newMode = window.currentFacingMode === "environment" ? "user" : "environment";
        startScanner(newMode);
    });

    // --- SATPAM GLOBAL (DIPERBARUI) ---
    if (window.scannerWatcher) clearInterval(window.scannerWatcher);
    
    window.scannerWatcher = setInterval(() => {
        const reader = document.getElementById('reader');
        const isScanning = window.scannerInstance && window.scannerInstance.isScanning;

        // Tambahkan pengecekan !window.isFileScanning
        if (reader && !isScanning && !window.isStarting && !window.isFileScanning) {
            startScanner(window.currentFacingMode);
        } 
        else if (!reader && isScanning) {
            stopScanner();
        }
    }, 500);

    document.addEventListener("turbo:load", startScanner);
    document.addEventListener("turbo:before-visit", stopScanner);
    window.addEventListener('beforeunload', stopScanner);
</script>
@endsection