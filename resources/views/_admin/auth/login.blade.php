@extends('_admin._layout.auth')

@section('title', 'Login')

@section('content')

<div class="flex items-center justify-center min-h-[100dvh] w-full bg-gray-50 dark:bg-neutral-950 px-4 py-8 antialiased">

    {{-- Kunci Lebar di sini: w-[400px] dan max-w-full --}}
    <div class="w-[330px] md:w-[400px] max-w-full mx-auto transition-all duration-300">
        
        {{-- Tombol Kembali --}}
        <div class="mb-4">
            <a href="/" class="inline-flex items-center gap-x-2 text-lg font-medium text-gray-500 hover:text-blue-600 dark:text-neutral-400 dark:hover:text-blue-500 transition-colors group">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>

        {{-- Card Container: overflow-hidden penting agar isi tidak meluap --}}
        <div class="w-full bg-white border border-gray-200 shadow-2xl shadow-gray-200/50 dark:bg-neutral-900 dark:border-neutral-800 dark:shadow-none rounded-2xl overflow-hidden">
            
            <div class="p-8 md:p-10">
                {{-- Header --}}
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">Masuk</h1>
                    <p class="mt-2 text-sm text-gray-500 dark:text-neutral-400">Gunakan akun terdaftar Anda</p>
                </div>

                {{-- Alert Error: Pakai min-w-0 dan break-words supaya tidak melebarkan card --}}
                @error('login_error')
                    <div class="mb-6 p-4 rounded-2xl bg-red-50 border border-red-100 dark:bg-red-500/10 dark:border-red-500/20 text-red-700 dark:text-red-400" role="alert">
                        <div class="flex items-start gap-3">
                            <svg class="flex-shrink-0 size-5 mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-semibold leading-relaxed break-words">
                                    {{ $message }}
                                </p>
                            </div>
                        </div>
                    </div>
                @enderror

                <form id="login-form" action="{{ route('login.post') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    {{-- Email Field --}}
                    <div class="w-full">
                        <label for="email" class="block mb-2 text-[11px] font-bold text-gray-400 dark:text-neutral-500 uppercase tracking-widest ml-1">Email</label>
                        <input type="email" id="email" name="email" 
                            class="block w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all dark:bg-neutral-800 dark:border-neutral-700 dark:text-white" 
                            placeholder="nama@email.com" required>
                    </div>

                    {{-- Password Field --}}
                    <div class="w-full">
                        <label for="password" class="block mb-2 text-[11px] font-bold text-gray-400 dark:text-neutral-500 uppercase tracking-widest ml-1">Password</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" 
                                class="block w-full ps-4 pe-12 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:bg-white focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all dark:bg-neutral-800 dark:border-neutral-700 dark:text-white" 
                                placeholder="••••••••" required>
                            
                            {{-- Tombol Mata --}}
                            <button type="button" onclick="togglePassword()" class="absolute inset-y-0 end-0 flex items-center pe-4 text-gray-400 hover:text-blue-500 transition-colors z-20 cursor-pointer">
                                <svg id="eye-open" xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="eye-closed" xmlns="http://www.w3.org/2000/svg" class="size-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" id="login-btn"
                        class="relative w-full py-4 px-6 mt-4 flex justify-center items-center bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-2xl transition-all shadow-lg shadow-blue-500/30 active:scale-[0.98] disabled:opacity-70">
                        
                        <span id="btn-text" class="flex items-center gap-2 uppercase tracking-widest">
                            Masuk
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </span>

                        <div id="loading-state" class="hidden items-center gap-3">
                            <svg class="animate-spin size-5 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-xs uppercase tracking-widest">Memproses...</span>
                        </div>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePassword() {
        const input = document.getElementById('password');
        const open = document.getElementById('eye-open');
        const closed = document.getElementById('eye-closed');

        if (input.type === 'password') {
            input.type = 'text';
            open.classList.add('hidden');
            closed.classList.remove('hidden');
        } else {
            input.type = 'password';
            open.classList.remove('hidden');
            closed.classList.add('hidden');
        }
    }

    document.getElementById('login-form').addEventListener('submit', function(e) {
        if (this.checkValidity()) {
            const btn = document.getElementById('login-btn');
            const text = document.getElementById('btn-text');
            const loading = document.getElementById('loading-state');

            btn.disabled = true;
            text.classList.add('hidden');
            loading.classList.remove('hidden');
            loading.classList.add('flex');
        }
    });
</script>

@endsection