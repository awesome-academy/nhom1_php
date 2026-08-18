<header x-data="{ mobileOpen: false }" class="sticky top-0 z-50 border-b border-[#EADBCE]/80 bg-[#FAF5F1] text-[#2B1E19] backdrop-blur-md transition-all duration-200">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-20 items-center justify-between">
            
            <!-- 1. Logo Thương Hiệu (Style Brew & Bite Artisan) -->
            <a href="{{ url('/') }}" class="group flex items-center gap-2.5 transition">
                <!-- Icon hạt cà phê nghệ thuật tinh tế -->
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white text-[#B38352] shadow-sm ring-1 ring-[#EADBCE] transition group-hover:scale-105 group-hover:border-[#B38352]/50">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636a9 9 0 010 12.728m0 0l-2.829-2.829m2.829 2.829L21 21M15.536 8.464a5 5 0 010 7.072m0 0l-2.829-2.829m-4.243 4.243a9 9 0 01-12.728 0m0 0l2.829-2.829m-2.829 2.829L3 21m2.828-15.364a9 9 0 0112.728 0" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                </div>
                
                <div class="flex flex-col">
                    <span class="text-xl font-bold tracking-tight text-[#2B1E19] transition group-hover:text-[#4A3B32]">
                        Brew<span class="text-[#B38352]">Bite</span>
                    </span>
                    <span class="text-[9px] font-semibold uppercase tracking-[0.2em] text-[#A39284]">
                        Artisan Cafe
                    </span>
                </div>
            </a>

            <!-- 2. Menu Navigation Trên Desktop -->
            <nav class="hidden items-center gap-8 text-sm font-medium text-[#736357] lg:flex">
                @include('layouts.partials.nav-links')
            </nav>

            <!-- 3. Nút Thao Tác (Đăng nhập / Đăng ký / Trang cá nhân) -->
            <div class="hidden items-center gap-3 lg:flex">
                @guest
                    <!-- Nút Đăng nhập -->
                    <a
                        href="{{ route('login') }}"
                        class="rounded-xl border border-[#EADBCE] bg-white/80 px-4 py-2 text-sm font-semibold text-[#4A3B32] shadow-sm transition hover:border-[#B38352] hover:bg-[#FAF5F1] hover:text-[#2B1E19]"
                    >
                        {{ __('Đăng nhập') }}
                    </a>

                    <!-- Nút Đăng ký -->
                    <a
                        href="{{ route('register') }}"
                        class="rounded-xl bg-[#2B1E19] px-5 py-2 text-sm font-semibold text-[#FAF5F1] shadow-md shadow-[#2B1E19]/10 transition duration-200 hover:bg-[#B38352] hover:shadow-lg hover:shadow-[#B38352]/20 active:scale-95"
                    >
                        {{ __('Đăng ký') }}
                    </a>
                @else
                    <!-- Nút khi đã đăng nhập -->
                    <div class="flex items-center gap-3">
                        <a
                            href="{{ route('dashboard') }}"
                            class="inline-flex items-center gap-2 rounded-xl bg-[#2B1E19] px-4 py-2 text-sm font-semibold text-[#FAF5F1] shadow-sm transition hover:bg-[#B38352]"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5z" />
                            </svg>
                            <span>{{ __('Trang cá nhân') }}</span>
                        </a>
                    </div>
                @endguest
            </div>

            <!-- 4. Nút Toggle Mobile Menu -->
            <button 
                @click="mobileOpen = !mobileOpen" 
                class="rounded-xl border border-[#EADBCE] bg-white p-2 text-[#736357] transition hover:bg-[#FAF5F1] hover:text-[#2B1E19] lg:hidden focus:outline-none" 
                aria-label="{{ __('Toggle Menu') }}"
            >
                <svg x-show="!mobileOpen" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-show="mobileOpen" x-cloak class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- 5. Navigation Menu Trên Di Động (Dropdown) -->
        <div 
            x-show="mobileOpen" 
            x-cloak 
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="space-y-4 border-t border-[#EADBCE] bg-[#FAF5F1] py-5 lg:hidden"
        >
            <nav class="flex flex-col gap-2.5 text-sm font-medium text-[#736357]">
                @include('layouts.partials.nav-links')
            </nav>

            <div class="flex flex-col gap-2.5 border-t border-[#EADBCE] pt-4">
                @guest
                    <a 
                        href="{{ route('login') }}" 
                        class="rounded-xl border border-[#EADBCE] bg-white py-2.5 text-center text-sm font-semibold text-[#4A3B32] transition hover:bg-[#FAF5F1]"
                    >
                        {{ __('Đăng nhập') }}
                    </a>
                    <a 
                        href="{{ route('register') }}" 
                        class="rounded-xl bg-[#2B1E19] py-2.5 text-center text-sm font-semibold text-[#FAF5F1] shadow-md transition hover:bg-[#B38352]"
                    >
                        {{ __('Đăng ký') }}
                    </a>
                @else
                    <a 
                        href="{{ route('dashboard') }}" 
                        class="rounded-xl bg-[#2B1E19] py-2.5 text-center text-sm font-semibold text-[#FAF5F1]"
                    >
                        {{ __('Trang cá nhân') }}
                    </a>
                @endguest
            </div>
        </div>
    </div>
</header>