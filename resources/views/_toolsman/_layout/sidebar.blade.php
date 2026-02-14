@php
use App\Constants\UserConst;
@endphp

<!-- Sidebar -->
<div id="hs-application-sidebar"
    class="hs-overlay  [--auto-close:lg]
  hs-overlay-open:translate-x-0
  -translate-x-full transition-all duration-300 transform
  w-65 h-full
  hidden
  fixed inset-y-0 start-0 z-60
   rounded-r-2xl
  lg:block lg:translate-x-0 lg:end-auto lg:bottom-0
  dark:bg-neutral-800 dark:border-neutral-700
  bg-gray-50"
    role="dialog" tabindex="-1" aria-label="Sidebar">
    <div class="relative flex flex-col h-full max-h-full">
        <div class="px-6 pt-4 flex items-center">
            <!-- Logo -->
            <a class="flex-none rounded-xl text-xl inline-block font-semibold focus:outline-hidden focus:opacity-80"
                href="#" aria-label="Preline">
                @include('_admin._layout.icons.sidebar.logo')
            </a>
            <!-- End Logo -->

        </div>

        <!-- Content -->
        <div
            class="flex-1 overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500 mt-2">
            <nav class="hs-accordion-group p-3 w-full flex flex-col flex-wrap" data-hs-accordion-always-open>
                <ul class="flex flex-col space-y-1">
                    <li>
                        <a navigate
                            class="flex items-center gap-x-3.5 py-2.5 px-3 {{ request()->routeIs('toolsman.dashboard') ? 'bg-blue-100 text-blue-600 dark:bg-neutral-700 dark:text-blue-400' : 'text-gray-800 dark:text-white' }} text-sm rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 font-semibold"
                            href="{{ route('toolsman.dashboard') }}">
                            @include('_toolsman._layout.icons.sidebar.dashboard')
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a navigate
                            class="flex items-center gap-x-3.5 py-2.5 px-3 {{ request()->routeIs('toolsman.loans.index') ? 'bg-blue-100 text-blue-600 dark:bg-neutral-700 dark:text-blue-400' : 'text-gray-800 dark:text-white' }} text-sm rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 font-semibold"
                            href="{{ route('toolsman.loans.index') }}">
                            @include('_toolsman._layout.icons.sidebar.user_loan')
                            Peminjaman Pengguna
                        </a>
                    </li>
                    <li>
                        <a navigate
                            class="flex items-center gap-x-3.5 py-2.5 px-3 {{ request()->routeIs('toolsman.fines.index') ? 'bg-blue-100 text-blue-600 dark:bg-neutral-700 dark:text-blue-400' : 'text-gray-800 dark:text-white' }} text-sm rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 font-semibold"
                            href="{{ route('toolsman.fines.index') }}">
                            @include('_toolsman._layout.icons.sidebar.fine_management')
                            Manajemen Denda
                        </a>
                    </li>
                    <li>
                        <a navigate
                            class="flex items-center gap-x-3.5 py-2.5 px-3 {{ request()->routeIs('toolsman.maintenance-tools.index') ? 'bg-blue-100 text-blue-600 dark:bg-neutral-700 dark:text-blue-400' : 'text-gray-800 dark:text-white' }} text-sm rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 font-semibold"
                            href="{{ route('toolsman.maintenance-tools.index') }}">
                            @include('_toolsman._layout.icons.sidebar.maintenance')
                            Maintenance
                        </a>
                    </li>

                    <li class="hs-accordion"
                        id="projects-accordion">
                        <button type="button"
                            class="hs-accordion-toggle w-full text-start flex items-center gap-x-3.5  py-2.5 px-3 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200 cursor-pointer font-semibold"
                            aria-expanded="true" aria-controls="projects-accordion-child">
                            @include('_toolsman._layout.icons.sidebar.data_master')
                            Data Master

                            @include('_toolsman._layout.icons.sidebar.chevron_down')

                            @include('_toolsman._layout.icons.sidebar.chevron_up')
                        </button>

                            
                            <div id="projects-accordion-child"
                            class="hs-accordion-content w-full overflow-hidden transition-[height] duration-300 {{ request()->routeIs('toolsman.tools.*') ? 'block' : 'hidden' }}"
                            role="region" aria-labelledby="projects-accordion">
                            <ul class="ps-8 pt-1 space-y-1">
                                <li>
                                    <a navigate
                                        class="flex items-center gap-x-3.5 py-2.5 px-3 {{ request()->routeIs('toolsman.tools.index') ? 'bg-blue-100 text-blue-600 dark:bg-neutral-700 dark:text-blue-400' : 'text-gray-800 dark:text-white' }} text-sm rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 font-semibold"
                                        href="{{ route('toolsman.tools.index') }}">
                                        @include('_toolsman._layout.icons.sidebar.tool')
                                        Barang
                                    </a>
                                </li>
                                
                            </ul>
                        </div>
                    </li>

                    {{-- <li><a class="w-full flex items-center gap-x-3.5  py-2.5 px-3 {{ request()->routeIs('admin.users.*') ? 'bg-gray-100 dark:bg-neutral-700' : '' }} text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200"
                    href="{{ route('admin.users.index') }}">
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                    Users
                    </a></li> --}}
                    {{-- <li><a class="w-full flex items-center gap-x-3.5  py-2.5 px-3 text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:bg-neutral-800 dark:hover:bg-neutral-700 dark:focus:bg-neutral-700 dark:text-neutral-200" href="#">
            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
            Documentation
          </a></li> --}}
                </ul>
            </nav>
        </div>

        <div
            class="p-4 border-t border-gray-200 dark:border-neutral-700 sticky bottom-0 z-10 bg-gray-50 dark:bg-neutral-800">
            <div class="hs-dropdown relative inline-flex w-full [--placement:top-left]">
                <button id="sidebar-bottom-dropdown" type="button"
                    class="hs-dropdown-toggle w-full group flex items-center gap-x-3.5  py-2.5 px-3 text-start text-sm text-gray-800 rounded-lg hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300"
                    aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                    <img class="shrink-0 size-9 rounded-full"
                        src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=random&length=2"
                        alt="Avatar">
                    <div class="grow">
                        <p class="text-sm font-semibold text-gray-800 dark:text-neutral-200">{{ Auth::user()->username }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-neutral-500">
                            {{ UserConst::getAccessTypes()[Auth::user()->access_type] }}
                        </p>
                    </div>
                    @include('_toolsman._layout.icons.sidebar.dropdown_toggle')
                </button>

                <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-60 bg-white shadow-md rounded-lg mb-2 dark:bg-neutral-800 dark:border dark:border-neutral-700 dark:divide-neutral-700 after:h-4 after:absolute after:-bottom-4 after:start-0 after:w-full before:h-4 before:absolute before:-top-4 before:start-0 before:w-full"
                    role="menu" aria-orientation="vertical" aria-labelledby="sidebar-bottom-dropdown">
                    <div class="p-1.5 space-y-0.5">
                        <!-- Switch/Toggle -->
                        <div
                            class="px-3 py-2 flex items-center justify-between border-b border-gray-200 dark:border-neutral-700 mb-1">
                            <span class="text-sm text-gray-800 dark:text-neutral-200">Theme</span>
                            <div class="flex items-center gap-x-0.5">
                                <button type="button"
                                    class="hs-dark-mode hs-dark-mode-active:hidden flex shrink-0 justify-center items-center gap-x-1 text-xs text-gray-500 hover:text-gray-800 focus:outline-hidden focus:text-gray-800 dark:text-neutral-400 dark:hover:text-neutral-200 dark:focus:text-neutral-200"
                                    data-hs-theme-click-value="dark">
                                    @include('_toolsman._layout.icons.sidebar.theme_dark')
                                    Dark
                                </button>
                                <button type="button"
                                    class="hs-dark-mode hs-dark-mode-active:flex hidden shrink-0 justify-center items-center gap-x-1 text-xs text-gray-500 hover:text-gray-800 focus:outline-hidden focus:text-gray-800 dark:text-neutral-400 dark:hover:text-neutral-200 dark:focus:text-neutral-200"
                                    data-hs-theme-click-value="light">
                                    @include('_toolsman._layout.icons.sidebar.theme_light')
                                    Light
                                </button>
                            </div>
                        </div>
                        <!-- End Switch/Toggle -->
                        <a navigate
                            class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-neutral-300 dark:focus:bg-neutral-700 dark:focus:text-neutral-300"
                            href="{{ route('toolsman.profile.change_password') }}">
                            @include('_toolsman._layout.icons.sidebar.change-password')
                            Ubah Password
                        </a>
                        <form action="{{ route('logout') }}" method="POST"
                            onsubmit="return confirm('Apakah anda yakin ingin keluar?');">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-red-600 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:text-red-500 dark:hover:bg-neutral-700 dark:hover:text-red-500 dark:focus:bg-neutral-700 dark:focus:text-red-500">
                                @include('_toolsman._layout.icons.sidebar.logout')
                                Log out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Content -->
    </div>
</div>
<!-- End Sidebar -->