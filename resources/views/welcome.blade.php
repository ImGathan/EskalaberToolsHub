<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>

    <script>
        (function() {
            // Preline menggunakan kunci 'hs_theme' secara default
            const savedTheme = localStorage.getItem('hs_theme') || 'default';
            const isDark = savedTheme === 'dark' || 
                (savedTheme === 'default' && window.matchMedia('(prefers-color-scheme: dark)').matches);

            if (isDark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'EskalaberToolsHub') }}</title>

    {{-- Favicon --}}
    @include('_admin._layout.favicon')

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @else
    <!-- Fallback styles would go here -->
    @endif
</head>

<body class="bg-gray-50 text-gray-900 dark:bg-neutral-800 dark:text-gray-100 font-sans antialiased transition-colors duration-300">

    <div id="loader" class="fixed inset-0 z-[9999] flex flex-col items-center justify-center overflow-hidden transition-opacity duration-1000">
    
        <div class="absolute inset-0 bg-white dark:bg-neutral-900">
            <!-- <div class="absolute inset-0 opacity-30 dark:opacity-20 blur-[100px] animate-mesh">
                <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] bg-blue-600 rounded-full"></div>
                <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] bg-blue-500 rounded-full"></div>
                <div class="absolute top-[20%] right-[10%] w-[40%] h-[40%] bg-blue-400 rounded-full"></div>
            </div> -->
        </div>

        <div class="relative z-10 flex flex-col items-center">
            
            <img src="{{ asset('admin/images/logo.webp') }}" alt="" class="h-28 md:h-40 dark:brightness-1000">

            <div class="relative mb-16 flex items-center justify-center">
        
                <div class="absolute w-12 h-12 border-[3px] border-transparent border-t-blue-600 border-r-blue-600 dark:border-t-white dark:border-r-gray-500 rounded-full animate-spin-slow"></div>
                <div class="absolute w-12 h-12 border-[3px] border-transparent border-b-blue-500 border-l-blue-500 dark:border-b-white dark:border-l-gray-500 rounded-full animate-spin-fast"></div>

            </div>

            <div class="flex flex-col items-center gap-2">
                <div class="flex items-center gap-1">
                    <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-gray-400 dark:text-neutral-500">Loading</span>
                    <span class="flex gap-1">
                        <span class="w-1 h-1 bg-blue-600 dark:bg-white rounded-full animate-bounce"></span>
                        <span class="w-1 h-1 bg-blue-600 dark:bg-white rounded-full animate-bounce [animation-delay:-0.15s]"></span>
                        <span class="w-1 h-1 bg-blue-600 dark:bg-white rounded-full animate-bounce [animation-delay:-0.3s]"></span>
                    </span>
                </div>
            </div>

        </div>
    </div>

    <!-- Navbar -->
    <nav class="fixed w-full z-50 top-0 start-0 border-b border-gray-200 dark:border-gray-800 bg-white/80 dark:bg-neutral-800 backdrop-blur-md transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 relative">
                
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center gap-2">
                        @include('_admin._layout.icons.sidebar.logo')
                    </a>
                </div>

                <div class="hidden lg:flex space-x-8 items-center justify-center absolute left-1/2 transform -translate-x-1/2">
                    <a href="#hero" class="nav-link text-gray-600 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400 font-medium transition-colors">Beranda</a>
                    <a href="#about" class="nav-link text-gray-600 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400 font-medium transition-colors">Tentang Kami</a>
                    <a href="#loan" class="nav-link text-gray-600 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400 font-medium transition-colors">Peminjaman</a>
                    <a href="#faq" class="nav-link text-gray-600 hover:text-blue-600 dark:text-gray-300 dark:hover:text-blue-400 font-medium transition-colors">FAQ</a>
                </div>

                <div class="flex items-center gap-2 sm:gap-4">
                    <button id="theme-toggle" type="button" class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5 transition-colors">
                        <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                        </svg>
                        <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
                        </svg>
                    </button>

                    <div class="hidden sm:block">
                        @auth
                        <a href="{{ route('login') }}" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors shadow-lg shadow-blue-500/30">
                            Dashboard
                        </a>
                        @else
                        <a href="{{ route('login') }}" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors shadow-lg shadow-blue-500/30 flex gap-2 items-center">
                            @include('_admin._layout.icons.sidebar.user')
                            Login
                        </a>
                        @endauth
                    </div>

                    <div class="lg:hidden flex items-center">
                        <button type="button" class="mobile-menu-button inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-blue-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-blue-400 dark:hover:bg-gray-800 focus:outline-none transition-colors">
                            <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="hidden lg:hidden overflow-hidden transition-all duration-300" id="mobile-menu">
            <div class="px-4 pt-4 pb-8 bg-transparent backdrop-blur-md border-b border-gray-200/20 dark:border-gray-700/50 shadow-xl">
                <div class="flex flex-col items-center justify-center">
                    
                    <a href="#hero" class="w-full text-center py-3 text-lg font-semibold text-gray-700 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                        Beranda
                    </a>

                    <a href="#about" class="w-full text-center py-3 text-lg font-semibold text-gray-700 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                        Tentang Kami
                    </a>

                    <a href="#loan" class="w-full text-center py-3 text-lg font-semibold text-gray-700 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                        Peminjaman
                    </a>

                    <a href="#faq" class="w-full text-center py-3 text-lg font-semibold text-gray-700 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                        FAQ
                    </a>

                    <div class="w-full flex items-center justify-center pt-4">
                        <a href="{{ route('login') }}" class="block w-full md:w-1/2 text-center py-4 bg-blue-600 text-white font-bold rounded-2xl shadow-lg shadow-blue-500/30 active:scale-95 transition-transform">
                            Masuk Ke Akun
                        </a>
                    </div>
                    
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="hero" class="relative flex flex-col items-center justify-center pt-28 md:pt-24 pb-12 md:pb-16 overflow-hidden bg-white dark:bg-neutral-800 transition-colors duration-300">
        <div class="absolute inset-0 -z-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-blue-400/30 rounded-full blur-3xl dark:bg-blue-600/25"></div>
            <div class="absolute top-40 -left-20 w-72 h-72 bg-indigo-400/30 rounded-full blur-3xl dark:bg-indigo-600/25"></div>
            
            <svg class="absolute inset-0 h-full w-full stroke-gray-300 dark:stroke-neutral-700 [mask-image:radial-gradient(100%_100%_at_top_right,white,transparent)]" aria-hidden="true">
                <defs>
                    <pattern id="grid-ornament" width="200" height="200" x="50%" y="-1" patternUnits="userSpaceOnUse">
                        <path d="M100 200V.5M.5 .5H200" fill="none" />
                    </pattern>
                </defs>
                <rect width="100%" height="100%" stroke-width="0" fill="url(#grid-ornament)" />
            </svg>
        </div>

        <div class="hidden lg:block absolute inset-x-0 bottom-0 h-20 bg-gradient-to-t from-white/100 to-transparent dark:from-neutral-800 dark:to-transparent z-20 transition-colors duration-300"></div>

        <div class="flex flex-col-reverse lg:flex-row justify-center items-center relative mx-auto px-4 sm:px-6 lg:px-8 gap-12">

            <div class="relative max-w-7xl flex flex-col items-center lg:items-start text-center lg:text-left z-10 order-2 lg:order-1">

                <div class="hidden md:inline-flex items-center px-3 py-1 rounded-full border border-blue-200 bg-blue-50 text-blue-600 text-sm font-medium mb-6 dark:bg-blue-900/30 dark:border-blue-800 dark:text-blue-300">
                    <span class="flex h-2 w-2 rounded-full bg-blue-600 mr-2"></span>
                    Sarana Peminjaman Barang SMKN 8 Jember
                </div>

                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-[1.1] text-gray-900 dark:text-white mb-2">
                    Kelola Peminjaman <br class="hidden sm:block" />
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-blue-500 dark:from-blue-500 dark:to-blue-400">Lebih Efisien</span>
                </h1>

                <p class="mt-4 text-md md:text-lg text-gray-600 dark:text-gray-300 max-w-2xl mb-6">
                    Solusi digital untuk memantau stok, mengajukan peminjaman, dan mengembalikan barang secara terorganisir. Semua tercatat otomatis dalam satu sistem.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    @auth
                    <a href="{{ route('login') }}" class="inline-flex justify-center items-center py-3 px-8 text-base font-medium text-white rounded-lg bg-gradient-to-r from-blue-600 to-blue-500 hover:bg-blue-700 shadow-xl shadow-blue-500/30 transition-all hover:scale-105">
                        Dashboard
                    </a>
                    @else
                    <a href="{{ route('login') }}" class="inline-flex justify-center items-center py-3 px-8 text-base font-medium text-white rounded-lg bg-gradient-to-r from-blue-600 to-blue-500 hover:bg-blue-700 shadow-xl shadow-blue-500/30 transition-all hover:scale-105">
                        Mulai Sekarang
                    </a>
                    <a href="#" class="inline-flex justify-center items-center py-3 px-8 text-base font-medium text-gray-900 bg-slate-200 rounded-lg border border-gray-200 hover:bg-gray-100 dark:bg-neutral-700 dark:text-gray-200 dark:border-neutral-600 dark:hover:bg-neutral-600 transition-all">
                        Pelajari Selengkapnya
                    </a>
                    @endauth
                </div>
            </div>
            
            <div class="hidden lg:flex justify-center items-center w-full lg:w-1/3 h-full relative order-1 lg:order-2 mt-6">
                <img src="{{ asset('admin/images/hero-img.webp') }}" alt="" class="scale-100 lg:scale-150 z-10 transition-transform duration-300">
                <div class="absolute w-64 h-64 lg:w-[400px] lg:h-[400px] bg-gradient-to-tr from-blue-500/50 to-blue-600/60 dark:from-blue-400/50 dark:to-blue-500/60 rounded-full"></div>
            </div>

        </div>
    </section>

    <!-- Feature Section (Placeholder) -->
    <section id="about" class="md:py-16 flex flex-col items-center justify-center bg-white dark:bg-neutral-800 transition-colors duration-300">
        <div class="max-w-7xl px-4 sm:px-6 lg:px-16">

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 mb-20">
                <div class="text-center p-6 rounded-3xl bg-gray-50 dark:bg-neutral-900/50 border border-gray-100 dark:border-neutral-700 shadow-lg group hover:border-blue-500 group hover:shadow-blue-500/30 transition-colors">
                    <p class="text-4xl font-black text-blue-600 mb-2">{{ $totalBarang }}</p>
                    <p class="text-xs md:text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Total Barang</p>
                </div>
                <div class="text-center p-6 rounded-3xl bg-gray-50 dark:bg-neutral-900/50 border border-gray-100 dark:border-neutral-700 shadow-lg group hover:border-blue-500 group hover:shadow-blue-500/30 transition-colors">
                    <p class="text-4xl font-black text-blue-600 mb-2">{{ $totalPeminjaman }}</p>
                    <p class="text-xs md:text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Peminjaman</p>
                </div>
                <div class="text-center p-6 rounded-3xl bg-gray-50 dark:bg-neutral-900/50 border border-gray-100 dark:border-neutral-700 shadow-lg group hover:border-blue-500 group hover:shadow-blue-500/30 transition-colors">
                    <p class="text-4xl font-black text-blue-600 mb-2">{{ $totalKategori }}</p>
                    <p class="text-xs md:text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Kategori</p>
                </div>
                <div class="text-center p-6 rounded-3xl bg-gray-50 dark:bg-neutral-900/50 border border-gray-100 dark:border-neutral-700 shadow-lg group hover:border-blue-500 group hover:shadow-blue-500/30 transition-colors">
                    <p class="text-4xl font-black text-blue-600 mb-2">100%</p>
                    <p class="text-xs md:text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Transparan</p>
                </div>
            </div>

            <div class="text-center mb-12">
                <h2 class="text-base font-semibold text-blue-600 tracking-wide uppercase">Tentang Kami</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 dark:text-white sm:text-4xl">
                    Mengapa Harus ToolsHub?
                </p>
                <p class="mt-4 max-w-2xl text-md md:text-xl text-gray-500 dark:text-gray-400 mx-auto">
                    ToolsHub adalah ekosistem digital yang dirancang untuk efisiensi manajemen peminjaman barang SMKN 8 Jember secara menyeluruh dan transparan.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-10 sm:grid-cols-1 lg:grid-cols-3">
                <div class="group relative pt-6">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 to-indigo-400 rounded-2xl opacity-20 group-hover:opacity-100 transition duration-300 blur"></div>
                    
                    <div class="relative flex flex-col items-center justify-center h-full bg-white dark:bg-neutral-800 border border-gray-100 dark:border-neutral-700 rounded-2xl p-8 shadow-xl transition-all duration-300 group-hover:-translate-y-2">
                        <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 bg-blue-50 dark:bg-gray-700/50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>

                        <div class="relative">
                            <div class="inline-flex items-center justify-center p-4 bg-gradient-to-br from-blue-600 to-blue-500 rounded-2xl shadow-lg transform group-hover:rotate-6 transition-transform duration-300">
                                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                        </div>

                        <h3 class="mt-8 text-xl font-bold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                            Penjaga Amanah Aset
                        </h3>
                        <p class="mt-4 text-sm md:text-md text-gray-500 dark:text-gray-400 leading-relaxed text-center">
                            Kami bertanggung jawab penuh dalam mencatat dan memelihara seluruh aset SMKN 8 Jember. Dari peralatan teknologi hingga fasilitas belajar, kami memastikan setiap inventaris terjaga rapi untuk mendukung kualitas pendidikan.
                        </p>

                    </div>
                </div>

                <div class="group relative pt-6">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 to-indigo-400 rounded-2xl opacity-20 group-hover:opacity-100 transition duration-300 blur"></div>
                    
                    <div class="relative flex flex-col items-center justify-center h-full bg-white dark:bg-neutral-800 border border-gray-100 dark:border-neutral-700 rounded-2xl p-8 shadow-xl transition-all duration-300 group-hover:-translate-y-2">
                        <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 bg-purple-50 dark:bg-gray-700/50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>

                        <div class="relative">
                            <div class="inline-flex items-center justify-center p-4 bg-gradient-to-br from-blue-600 to-blue-500 rounded-2xl shadow-lg transform group-hover:rotate-6 transition-transform duration-300">
                                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                        </div>

                        <h3 class="mt-8 text-xl font-bold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                            Layanan Tanpa Batas
                        </h3>
                        <p class="mt-4 text-sm md:text-md text-gray-500 dark:text-gray-400 leading-relaxed text-center">
                            Kami hadir untuk mempermudah akses bagi seluruh warga sekolah. Dengan sistem yang transparan, peminjaman peralatan untuk kebutuhan kelas, praktik, maupun kegiatan sekolah lainnya kini menjadi lebih cepat dan efisien.
                        </p>
                    </div>
                </div>

                <div class="group relative pt-6">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 to-indigo-400 rounded-2xl opacity-20 group-hover:opacity-100 transition duration-300 blur"></div>
                    
                    <div class="relative flex flex-col items-center justify-center h-full bg-white dark:bg-neutral-800 border border-gray-100 dark:border-neutral-700 rounded-2xl p-8 shadow-xl transition-all duration-300 group-hover:-translate-y-2">
                        <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 bg-emerald-50 dark:bg-gray-700/50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>

                        <div class="relative">
                            <div class="inline-flex items-center justify-center p-4 bg-gradient-to-br from-blue-600 to-blue-500 rounded-2xl shadow-lg transform group-hover:rotate-6 transition-transform duration-300">
                                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                        </div>

                        <h3 class="mt-8 text-xl font-bold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                            Standar Budaya Kerja
                        </h3>
                        <p class="mt-4 text-sm md:text-md text-gray-500 dark:text-gray-400 leading-relaxed text-center">
                            Manajemen kami mengadopsi standar kedisiplinan industri. Kami mengajak siswa untuk belajar bertanggung jawab dalam penggunaan alat, membentuk karakter yang jujur dan tertib dalam mengelola fasilitas bersama.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section id="loan" class="pt-12 pb-24 bg-white flex flex-col items-center justify-center dark:bg-neutral-800 transition-colors duration-300 overflow-x-hidden">

        <div class="max-w-7xl w-full px-4 sm:px-6 lg:px-16">
            
            <div class="text-center mb-12">
                <h2 class="text-base font-semibold text-blue-600 tracking-wide uppercase">Alur Sistem</h2>
                <p class="mt-2 text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-white leading-tight">
                    Proses Peminjaman <span class="text-blue-600">Tanpa Ribet</span>
                </p>
                <p class="mt-4 max-w-2xl text-md md:text-lg sm:text-xl text-gray-500 dark:text-gray-400 mx-auto">
                    Dirancang untuk memudahkan koordinasi antara siswa, guru, dan tim manajemen aset sekolah.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">

                <div class="relative sm:scale-100 lg:scale-100 min-h-[450px] flex items-center justify-center order-2 lg:order-1">
                    <div class="absolute inset-0 bg-blue-500/5 rounded-full blur-[100px]"></div>

                    <div class="relative w-full max-w-[450px] sm:max-w-[500px] bg-white dark:bg-neutral-800 rounded-3xl shadow-2xl border border-gray-200 dark:border-neutral-700 overflow-hidden">
                        <div class="bg-gray-50 dark:bg-neutral-900/50 border-b border-gray-200 dark:border-neutral-700 p-3 flex items-center gap-2">
                            <div class="flex gap-1.5">
                                <div class="w-2.5 h-2.5 rounded-full bg-red-400"></div>
                                <div class="w-2.5 h-2.5 rounded-full bg-yellow-400"></div>
                                <div class="w-2.5 h-2.5 rounded-full bg-green-400"></div>
                            </div>
                            <div class="mx-auto h-2 w-32 bg-gray-200 dark:bg-neutral-700 rounded-full"></div>
                        </div>

                        <div class="relative h-[380px] overflow-hidden">
                            <div class="absolute inset-0 p-4 transition-opacity duration-300 animate-[page-list_12s_infinite]">
                                <div class="bg-gray-200 w-fit dark:bg-neutral-700 rounded-lg mb-4 flex items-center font-semibold text-xs text-gray-400 px-4 gap-2">
                                    @include('_admin._layout.icons.search')
                                    <p class="py-2">Jelajahi Barang</p>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="bg-white dark:bg-neutral-800 border-2 border-gray-100 dark:border-neutral-700 rounded-xl p-2 shadow-sm animate-[card-target_12s_infinite]">
                                        <div class="aspect-video bg-blue-50 dark:bg-blue-900/20 rounded-lg mb-2 flex items-center justify-center overflow-hidden">
                                            <img src="admin/images/laptop.webp" alt="">
                                        </div>
                                        <p class="h-2 w-full text-gray-400 dark:text-neutral-500 rounded text-sm font-semibold py-2">Laptop</p>
                                        <div class="flex items-center justify-center bg-blue-600 rounded mt-6">
                                            <p class="text-white text-xs p-2 font-semibold">Ajukan Pinjam</p>
                                        </div>
                                    </div>
                                    <div class="bg-white dark:bg-neutral-800 border-2 border-gray-100 dark:border-neutral-700 rounded-xl p-2 shadow-sm">
                                        <div class="aspect-video bg-blue-50 dark:bg-blue-900/20 rounded-lg mb-2 flex items-center justify-center overflow-hidden">
                                            <svg class="w-20 h-20 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9.75 17L9 21h6l-.75-4M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        </div>
                                        <div class="h-4 w-full bg-gray-200 dark:bg-neutral-700 rounded my-4"></div>
                                        <div class="flex items-center justify-center bg-blue-300 rounded mt-2 h-8"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="absolute inset-0 p-4 bg-white dark:bg-neutral-800 opacity-0 translate-y-0 animate-[page-form_12s_infinite]">
                                <div class="flex items-center gap-2 mb-4">
                                    <div class="w-6 h-6 rounded-full bg-gray-200 dark:bg-neutral-700"></div>
                                    <p class="py-2 text-sm text-gray-400 font-semibold">Buat Peminjaman</p>
                                </div>
                                <div class="space-y-4">
                                    <div class="p-3 bg-blue-50 dark:bg-blue-900/10 rounded-xl border border-blue-100 dark:border-blue-900/30 flex gap-3">
                                        <div class="w-10 h-10 rounded-lg overflow-hidden shadow-md">
                                            <img src="admin/images/laptop.webp" class="w-full h-full object-cover" alt="">
                                        </div>
                                        <div class="flex items-center">
                                            <p class="text-blue-500 dark:text-blue-400 font-semibold">Laptop</p>
                                        </div>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-sm text-gray-400 font-semibold text-xs">Jumlah Pinjam</p>
                                        <div class="h-10 w-full border border-gray-200 dark:border-neutral-700 rounded-lg flex items-center px-3">
                                            <p class="text-sm text-blue-600 font-semibold animate-[typing_12s_infinite] opacity-0">1</p>
                                        </div>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-sm text-gray-400 font-semibold text-xs">Tanggal Kembali</p>
                                        <div class="h-10 w-full border border-gray-200 dark:border-neutral-700 rounded-lg flex items-center px-3">
                                            <p class="text-sm text-blue-600 font-semibold animate-[typing_12s_infinite] opacity-0">xx/xx/xxxx</p>
                                        </div>
                                    </div>
                                    <div class="h-10 w-full bg-blue-600 rounded-lg flex items-center justify-center animate-[btn-submit_12s_infinite]">
                                        <p class="text-white text-xs p-2 font-semibold">Kirim Pengajuan</p>
                                    </div>
                                </div>
                            </div>

                            <div class="absolute inset-x-0 top-6 flex justify-center z-50 pointer-events-none px-4">
                                <div class="bg-emerald-500 text-white p-3 rounded-xl shadow-lg flex items-center gap-2 opacity-0 animate-[notif-pop_12s_infinite] border border-emerald-400">
                                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider whitespace-nowrap">Pengajuan Berhasil!</span>
                                </div>
                            </div>

                            <div class="absolute z-50 pointer-events-none animate-[cursor-master_12s_infinite]">
                                <svg class="w-6 h-6 text-black dark:text-white drop-shadow-lg" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M7 2l12 11.2-5.8.8 3.5 5.4-1.9 1.2-3.5-5.4-4.3 4.2V2z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-12 order-1 lg:order-2">
                    <div class="relative ml-8 lg:ml-0 pl-8 border-l-2 border-dashed border-gray-200 dark:border-neutral-700 space-y-10 sm:space-y-12">
                        
                        <div class="relative group">
                            <div class="absolute -left-[41px] top-0 w-5 h-5 rounded-full bg-white dark:bg-neutral-800 border-4 border-blue-600 z-10 transition-transform lg:group-hover:scale-150"></div>
                            <div>
                                <h4 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white mb-2 flex items-center gap-3">
                                    <span class="text-blue-600">01.</span> Cari & Pilih Barang
                                </h4>
                                <p class="text-gray-500 dark:text-gray-400 leading-relaxed text-sm">Jelajahi ribuan katalog barang sekolah. Cek ketersediaan stok secara real-time dari mana saja.</p>
                            </div>
                        </div>

                        <div class="relative group">
                            <div class="absolute -left-[41px] top-0 w-5 h-5 rounded-full bg-white dark:bg-neutral-800 border-4 border-blue-600 z-10 transition-transform lg:group-hover:scale-150"></div>
                            <div>
                                <h4 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white mb-2 flex items-center gap-3">
                                    <span class="text-blue-600">02.</span> Ajukan Request
                                </h4>
                                <p class="text-gray-500 dark:text-gray-400 leading-relaxed text-sm">Isi formulir peminjaman digital. Sistem akan otomatis mengirimkan notifikasi ke admin untuk persetujuan.</p>
                            </div>
                        </div>

                        <div class="relative group">
                            <div class="absolute -left-[41px] top-0 w-5 h-5 rounded-full bg-white dark:bg-neutral-800 border-4 border-blue-600 z-10 transition-transform lg:group-hover:scale-150"></div>
                            <div>
                                <h4 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white mb-2 flex items-center gap-3">
                                    <span class="text-blue-600">03.</span> Ambil & Gunakan
                                </h4>
                                <p class="text-gray-500 dark:text-gray-400 leading-relaxed text-sm">Setelah disetujui, ambil barang di ruang aset dengan scan QR Code sebagai verifikasi keamanan.</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="mt-14 md:mt-18 lg:mt-20">
                <div class="text-center mb-4 md:mb-10">
                    <p class="text-xs md:text-sm font-semibold text-gray-400 uppercase tracking-widest px-4">Jelajahi Berbagai Barang di <br class="md:hidden"> SMKN 8 Jember</p>
                </div>

                <div class="swiper mySwiper !overflow-visible">
                    <div class="swiper-wrapper">
                        @foreach ($tools as $tool)
                        <div class="swiper-slide w-[280px] sm:w-72 h-[450px] py-10">
                            <div class="group relative h-full w-full bg-white dark:bg-neutral-800 rounded-3xl shadow-xl transition-all duration-500 lg:hover:scale-105 lg:hover:-translate-y-4 overflow-hidden border border-gray-100 dark:border-neutral-700">
                                
                                <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-blue-500/10 rounded-full blur-2xl group-hover:bg-blue-500/20 transition-all"></div>
                                
                                <div class="relative h-56 overflow-hidden flex flex-col justify-center items-center p-5">
                                    <img 
                                        src="{{ $tool->image ? asset('storage/' . $tool->image) : asset('admin/images/empty-data.webp') }}" 
                                        alt="{{ $tool->name }}" 
                                        class="w-full h-full rounded-xl object-cover transform transition-transform duration-700 group-hover:scale-110"
                                    >
                                    <div class="absolute top-4 left-4 z-20">
                                        <span class="px-3 py-1.5 bg-blue-600/90 backdrop-blur-sm text-white text-[10px] font-bold uppercase tracking-wider rounded-lg shadow-lg">
                                            {{ $tool->category->name }}
                                        </span>
                                    </div>
                                </div>

                                <div class="relative p-6 -mt-14 z-20">
                                    <div class="bg-white/80 dark:bg-neutral-800/80 backdrop-blur-md rounded-2xl p-4 shadow-sm border border-white/20">
                                        <h5 class="font-black text-lg sm:text-xl text-gray-800 dark:text-white mb-1 group-hover:text-blue-600 transition-colors truncate">
                                            {{ $tool->name }}
                                        </h5>
                                        <div class="flex items-center gap-2 mb-3">
                                            <div class="h-1 w-10 bg-blue-600 rounded-full"></div>
                                        </div>
                                        <div class="flex justify-between items-end">
                                            <div>
                                                <p class="text-[10px] text-gray-400 uppercase font-bold">Ketersediaan</p>
                                                <p class="text-2xl font-black text-blue-600">{{ $tool->quantity }} <span class="text-xs text-gray-500 font-medium">Unit</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="absolute bottom-0 w-full h-1.5 bg-blue-600 scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-8 flex justify-center px-4">
                    <a href="#katalog" class="group relative inline-flex items-center justify-center px-4 md:px-8 py-4 font-semibold text-white transition-all duration-500 w-full sm:w-auto text-center">
                        <div class="absolute inset-0 rounded-full bg-blue-600 shadow-lg transition-all duration-500 group-hover:bg-blue-700 group-hover:scale-105"></div>
                        <span class="relative flex items-center justify-center gap-3 text-xs md:text-md lg:text-lg">
                            Mulai Jelajahi Semua Barang
                            <svg class="w-5 h-5 transform transition-transform duration-500 group-hover:translate-x-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section id="faq" class="pt-6 pb-14 md:pb-20 lg:pb-24 bg-white dark:bg-neutral-800 transition-colors duration-300 relative overflow-hidden">
        <div class="absolute top-0 left-0 -ml-20 mt-20 w-80 h-80 bg-blue-400/10 rounded-full blur-[100px]"></div>

        <div class="absolute inset-0 -z-0 overflow-hidden pointer-events-none">
            </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-16 relative">
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-start">
                
                <div class="text-center lg:text-left">
                    <h2 class="text-base font-semibold text-blue-600 tracking-wide uppercase">Tanya Jawab</h2>
                    <p class="mt-2 text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-white leading-tight">
                        Bingung Mengenai <br class="hidden sm:block"> <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-500">Alur Peminjaman?</span>
                    </p>
                    <p class="mt-4 max-w-2xl text-md md:text-lg sm:text-xl text-gray-500 dark:text-gray-400 mx-auto">
                        Berikut adalah daftar pertanyaan yang paling sering ditanyakan oleh siswa dan guru mengenai penggunaan ToolsHub.
                    </p>
                    
                    <div class="mt-10 space-y-8">
                        <div class="flex gap-4 group">
                            <div class="flex-shrink-0 w-12 h-12 rounded-2xl bg-indigo-600/10 dark:bg-indigo-600/20 flex items-center justify-center text-indigo-600 transition-colors group-hover:bg-indigo-600 group-hover:text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2"/></svg>
                            </div>
                            <div>
                                <h4 class="text-left text-sm font-black text-gray-900 dark:text-white uppercase tracking-wider">Jam Operasional</h4>
                                <p class="text-left text-sm text-gray-500 dark:text-gray-400 mt-1">Senin s/d Jumat (07:00 - 15:30). Libur nasional tutup.</p>
                            </div>
                        </div>

                        <div class="flex gap-4 group">
                            <div class="flex-shrink-0 w-12 h-12 rounded-2xl bg-green-600/10 dark:bg-green-600/20 flex items-center justify-center text-green-600 transition-colors group-hover:bg-green-600 group-hover:text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" stroke-width="2"/></svg>
                            </div>
                            <div>
                                <h4 class="text-left text-sm font-black text-gray-900 dark:text-white uppercase tracking-wider">Verifikasi Keamanan</h4>
                                <p class="text-left text-sm text-gray-500 dark:text-gray-400 mt-1">Setiap peminjaman dipantau secara real-time oleh tim manajemen aset.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="faq-item group">
                        <button class="flex items-center justify-between w-full p-5 sm:p-6 text-left bg-gray-50 dark:bg-neutral-900/50 rounded-2xl border border-gray-100 dark:border-neutral-700 transition-all hover:border-blue-500">
                            <span class="text-md sm:text-lg font-semibold text-gray-900 dark:text-white group-hover:text-blue-600 transition-colors">Bagaimana jika barang yang saya pinjam rusak?</span>
                            <svg class="w-5 h-5 flex-shrink-0 text-gray-400 transition-transform duration-300 transform group-focus:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                        <div class="max-h-0 overflow-hidden transition-all duration-300 ease-in-out group-focus-within:max-h-48 sm:group-focus-within:max-h-40">
                            <p class="p-6 text-sm sm:text-base text-gray-500 dark:text-gray-400 border-x border-b border-gray-50 dark:border-neutral-700 -mt-2 rounded-b-2xl bg-white dark:bg-neutral-800/30">
                                Segera lapor ke Toolman di ruang aset. Kerusakan akan diperiksa, jika karena kelalaian, peminjam wajib bertanggung jawab sesuai kesepakatan sekolah.
                            </p>
                        </div>
                    </div>

                    <div class="faq-item group">
                        <button class="flex items-center justify-between w-full p-5 sm:p-6 text-left bg-gray-50 dark:bg-neutral-900/50 rounded-2xl border border-gray-100 dark:border-neutral-700 transition-all hover:border-blue-500">
                            <span class="text-md sm:text-lg font-semibold text-gray-900 dark:text-white group-hover:text-blue-600 transition-colors">Berapa lama batas waktu maksimal peminjaman?</span>
                            <svg class="w-5 h-5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                        <div class="max-h-0 overflow-hidden transition-all duration-300 ease-in-out group-focus-within:max-h-48 sm:group-focus-within:max-h-40">
                            <p class="p-6 text-sm sm:text-base text-gray-500 dark:text-gray-400 border-x border-b border-gray-50 dark:border-neutral-700 -mt-2 rounded-b-2xl bg-white dark:bg-neutral-800/30">
                                Standar peminjaman adalah 1 hari kerja. Untuk kebutuhan proyek jangka panjang, diperlukan surat rekomendasi dari Kaprogli masing-masing.
                            </p>
                        </div>
                    </div>

                    <div class="faq-item group">
                        <button class="flex items-center justify-between w-full p-5 sm:p-6 text-left bg-gray-50 dark:bg-neutral-900/50 rounded-2xl border border-gray-100 dark:border-neutral-700 transition-all hover:border-blue-500">
                            <span class="text-md sm:text-lg font-semibold text-gray-900 dark:text-white group-hover:text-blue-600 transition-colors">Apakah saya bisa meminjam lebih dari 1 alat?</span>
                            <svg class="w-5 h-5 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                        <div class="max-h-0 overflow-hidden transition-all duration-300 ease-in-out group-focus-within:max-h-48 sm:group-focus-within:max-h-40">
                            <p class="p-6 text-sm sm:text-base text-gray-500 dark:text-gray-400 border-x border-b border-gray-50 dark:border-neutral-700 -mt-2 rounded-b-2xl bg-white dark:bg-neutral-800/30">
                                Bisa, selama stok tersedia dan alat tersebut memang dibutuhkan untuk satu rangkaian praktik yang sama.
                            </p>
                        </div>
                    </div>

                    <div class="mt-8 p-6 bg-blue-600 rounded-3xl shadow-xl shadow-blue-500/20 flex flex-col sm:flex-row items-center gap-4 justify-between text-center sm:text-left">
                        <div>
                            <p class="text-white font-bold">Masih punya pertanyaan lain?</p>
                            <p class="text-blue-100 text-sm">Hubungi admin melalui form di bawah.</p>
                        </div>
                        <a href="#footer-contact" class="w-full sm:w-auto px-5 py-2.5 bg-white text-blue-600 rounded-xl text-xs font-black uppercase hover:bg-gray-100 transition-colors">
                            Tanya Admin
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-gray-50 dark:bg-neutral-900 border-t border-gray-200 dark:border-neutral-800 pt-16 pb-10 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-16">
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-12 lg:gap-16 items-start mb-16">
            
            <div class="md:col-span-2 lg:col-span-3">
                <a href="/" class="flex mb-6 justify-start">
                    <img src="{{ asset('admin/images/logo.webp') }}" alt="Logo" class="w-48 dark:brightness-1000">
                </a>
                <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed lg:pr-10 font-medium">
                    Sistem Manajemen Aset Terintegrasi SMKN 8 Jember. Solusi cerdas untuk efisiensi barang.
                </p>
            </div>

            <div class="lg:col-span-2">
                <h4 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-widest mb-6 border-l-4 border-blue-600 pl-3 lg:border-none lg:pl-0">Navigasi</h4>
                <ul class="grid grid-cols-2 md:grid-cols-1 gap-4">
                    <li><a href="#" class="text-sm font-semibold text-gray-400 hover:text-blue-600 transition-colors">Beranda</a></li>
                    <li><a href="#" class="text-sm font-semibold text-gray-400 hover:text-blue-600 transition-colors">Katalog Alat</a></li>
                    <li><a href="#" class="text-sm font-semibold text-gray-400 hover:text-blue-600 transition-colors">Pusat FAQ</a></li>
                    <li><a href="#" class="text-sm font-semibold text-gray-400 hover:text-blue-600 transition-colors">Panduan</a></li>
                </ul>
            </div>

            <div class="lg:col-span-2">
                <h4 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-widest mb-6 border-l-4 border-blue-600 pl-3 lg:border-none lg:pl-0">Sosial</h4>
                <ul class="flex flex-wrap md:flex-col gap-5">
                    <li>
                        <a href="#" class="group flex items-center gap-3 text-sm font-semibold text-gray-400 hover:text-blue-600 transition-colors">
                            <!-- <svg class="w-5 h-5 stroke-gray-400 group-hover:stroke-blue-600 transition-colors" fill="none" stroke-width="1.5" viewBox="0 0 24 24">
                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                                <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                                <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                            </svg> -->
                            <span>Instagram</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="group flex items-center gap-3 text-sm font-semibold text-gray-400 hover:text-blue-600 transition-colors">
                            <!-- <svg class="w-5 h-5 stroke-gray-400 group-hover:stroke-blue-600 transition-colors" fill="none" stroke-width="1.5" viewBox="0 0 24 24">
                                <path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"></path>
                                <polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon>
                            </svg> -->
                            <span>YouTube</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="group flex items-center gap-3 text-sm font-semibold text-gray-400 hover:text-blue-600 transition-colors">
                            <!-- <svg class="w-5 h-5 stroke-gray-400 group-hover:stroke-blue-600 transition-colors" fill="none" stroke-width="1.5" viewBox="0 0 24 24">
                                <path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"></path>
                            </svg> -->
                            <span>Github</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div id="footer-contact" class="md:col-span-2 lg:col-span-5">
                <div class="space-y-8">
                    <div>
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-widest mb-2">Hubungi Kami</h4>
                        <div class="h-1 w-12 bg-blue-600 rounded-full"></div>
                    </div>
                    
                    <form action="#" class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6">
                        <div class="sm:col-span-1 shadow-md shadow-blue-500/10 rounded-xl">
                            <input type="text" name="name" placeholder="Nama Lengkap" 
                                class="w-full bg-white dark:bg-neutral-900 border-2 border-gray-200 dark:border-neutral-800 px-5 py-3.5 rounded-xl outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-500/5 transition-all text-sm font-bold text-gray-900 dark:text-white placeholder:font-normal placeholder-gray-500">
                        </div>

                        <div class="sm:col-span-1 shadow-md shadow-blue-500/10 rounded-xl">
                            <input type="email" name="email" placeholder="Alamat Email" 
                                class="w-full bg-white dark:bg-neutral-900 border-2 border-gray-200 dark:border-neutral-800 px-5 py-3.5 rounded-xl outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-500/5 transition-all text-sm font-bold text-gray-900 dark:text-white placeholder:font-normal placeholder-gray-500">
                        </div>

                        <div class="col-span-1 sm:col-span-2 shadow-md shadow-blue-500/10 rounded-xl">
                            <textarea name="message" rows="3" placeholder="Tulis pesan atau kendala Anda..." 
                                class="w-full bg-white dark:bg-neutral-900 border-2 border-gray-200 dark:border-neutral-800 px-5 py-3.5 rounded-xl outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-500/5 transition-all text-sm font-bold text-gray-900 dark:text-white placeholder:font-normal placeholder-gray-500 resize-none"></textarea>
                        </div>

                        <div class="col-span-1 sm:col-span-2">
                            <button type="submit" class="w-full sm:w-auto group flex items-center justify-center gap-3 text-[11px] font-bold uppercase tracking-[0.2em] text-white bg-blue-600 px-8 py-4 rounded-xl hover:bg-blue-700 transition-all">
                                Kirim Pesan
                                <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        <div class="pt-10 border-t border-gray-100 dark:border-neutral-800 flex flex-col items-center">
            <p class="text-[10px] sm:text-xs text-center font-medium text-gray-400 uppercase tracking-[0.3em]">© 2026 ToolsHub SMKN 8 Jember. All Rights Reserved</p>
        </div>
    </div>
</footer>

    <style>

        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        .animate-marquee {
            display: flex;
            width: max-content;
            animation: marquee 30s linear infinite;
        }

        @keyframes cursor-master {
            0% { transform: translate(350px, 350px); opacity: 0; }
            5% { opacity: 1; }
            15% { transform: translate(90px, 250px); } 
            18% { transform: translate(90px, 250px) scale(0.8); }
            22% { transform: translate(90px, 250px) scale(1); }
            30% { transform: translate(150px, 150px); opacity: 0; }
            45% { transform: translate(100px, 240px); opacity: 1; }
            55% { transform: translate(210px, 320px); } 
            58% { transform: translate(210px, 320px) scale(0.8); }
            62% { transform: translate(210px, 320px) scale(1); }
            75%, 100% { transform: translate(350px, 350px); opacity: 0; }
        }

        @keyframes notif-pop {
            0%, 65% { opacity: 0; transform: translateY(-20px); }
            70%, 90% { opacity: 1; transform: translateY(0px); }
            95%, 100% { opacity: 0; transform: translateY(-20px); }
        }

        @keyframes page-list {
            0%, 20% { opacity: 1; }
            22%, 75% { opacity: 0; }
            80%, 100% { opacity: 1; }
        }

        @keyframes page-form {
            0%, 20% { opacity: 0; transform: translateY(10px); }
            25%, 65% { opacity: 1; transform: translateY(0); }
            70%, 100% { opacity: 0; transform: translateY(-10px); }
        }

        @keyframes typing {
            48%, 65% { opacity: 1; }
            0%, 47%, 66%, 100% { opacity: 0; }
        }

        @keyframes btn-submit {
            58% { background-color: #1e40af; transform: scale(0.95); }
            62% { background-color: #2563eb; transform: scale(1); }
        }

        .nav-link {
            position: relative;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -4px;
            left: 0;
            background-color: #2563eb; /* Warna biru-600 */
            transition: width 0.3s ease;
        }

        .nav-link.active-link {
            color: #2563eb !important; /* Biru saat aktif */
        }

        .nav-link.active-link::after {
            width: 100%;
        }

        .dark .nav-link.active-link {
            color: #60a5fa !important; /* Biru terang untuk dark mode */
        }

        .dark .nav-link.active-link::after {
            background-color: #60a5fa;
        }

        .swiper-wrapper {
            transition-timing-function: linear !important;
        }

        @keyframes spin-slow {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
        }
        .animate-spin-slow { 
            animation: spin-slow 2s linear infinite; 
        }

        /* Putaran Cepat & Terbalik (Inner) */
        @keyframes spin-fast {
            from { transform: rotate(360deg); }
            to { transform: rotate(0deg); }
        }
        .animate-spin-fast { 
            animation: spin-fast 1s linear infinite; 
        }

        /* Animasi melayang logo */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .animate-float { 
            animation: float 3s infinite ease-in-out; 
        }

    </style>

    <!-- Script for Dark Mode Toggle & Mobile Menu -->
    <script>
       
        document.addEventListener('DOMContentLoaded', () => {
            const sections = document.querySelectorAll('section[id]');
            const navLinks = document.querySelectorAll('.nav-link');

            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.4 // Section dianggap aktif jika 60% terlihat di layar
            };

            const observerCallback = (entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const id = entry.target.getAttribute('id');
                        
                        // Hapus class active-link dari semua link
                        navLinks.forEach(link => {
                            link.classList.remove('active-link');
                            if (link.getAttribute('href') === `#${id}`) {
                                link.classList.add('active-link');
                            }
                        });
                    }
                });
            };

            const observer = new IntersectionObserver(observerCallback, observerOptions);
            sections.forEach(section => observer.observe(section));
        });


        if (localStorage.getItem('hs_theme') === 'dark' || (!('hs_theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

        // 2. UBAH DI SINI: Atur ikon (Ganti 'color-theme' jadi 'hs_theme')
        if (localStorage.getItem('hs_theme') === 'dark' || (!('hs_theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            themeToggleLightIcon.classList.remove('hidden');
        } else {
            themeToggleDarkIcon.classList.remove('hidden');
        }

        const themeToggleBtn = document.getElementById('theme-toggle');

        themeToggleBtn.addEventListener('click', function() {
            // Toggle icons
            themeToggleDarkIcon.classList.toggle('hidden');
            themeToggleLightIcon.classList.toggle('hidden');

            // 3. UBAH DI SINI: Logika simpan tema (Ganti 'color-theme' jadi 'hs_theme')
            if (localStorage.getItem('hs_theme')) {
                if (localStorage.getItem('hs_theme') === 'light') {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('hs_theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('hs_theme', 'light');
                }
            } else {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('hs_theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('hs_theme', 'dark');
                }
            }
        });

        
        const btn = document.querySelector("button.mobile-menu-button");
        const menu = document.querySelector("#mobile-menu");

        btn.addEventListener("click", () => {
            menu.classList.toggle("hidden");
        });

        var swiper = new Swiper(".mySwiper", {
            slidesPerView: "auto", // Ukuran card fleksibel
            spaceBetween: 30,      // Jarak antar card
            loop: true,            // Membuat loop berkelanjutan
            speed: 5000,           // Kecepatan transisi (ms)
            autoplay: {
            delay: 0,            // Langsung jalan tanpa nunggu
            disableOnInteraction: false,
            },
            breakpoints: {
            640: { slidesPerView: 2 },
            1024: { slidesPerView: 4 },
            },
        });
            
        
        document.documentElement.style.overflow = 'hidden';

        window.addEventListener('load', function() {
            const loader = document.getElementById('loader');
            
            // Fungsi untuk menghilangkan loader
            const hideLoader = () => {
                loader.classList.add('opacity-0');
                document.documentElement.style.overflow = ''; // Aktifkan scroll kembali
                
                setTimeout(() => {
                    loader.style.display = 'none';
                }, 1000); 
            };

            // Cek apakah ada gambar yang masih loading
            const images = document.querySelectorAll('img');
            let imagesLoaded = 0;

            if (images.length === 0) {
                hideLoader();
            } else {
                images.forEach((img) => {
                    if (img.complete) {
                        incrementCounter();
                    } else {
                        img.addEventListener('load', incrementCounter);
                        img.addEventListener('error', incrementCounter); // Tetap lanjut jika gambar error
                    }
                });
            }

            function incrementCounter() {
                imagesLoaded++;
                if (imagesLoaded === images.length) {
                    // Semua gambar sudah siap, sekarang boleh tutup loader
                    hideLoader();
                }
            }
        });

    </script>
</body>

</html>