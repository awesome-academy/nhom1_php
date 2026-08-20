<header x-data="{
        mobileOpen: false,
        userMenu: false,
        cartCount: 0,
        async fetchCartCount() {
            try {
                const res = await fetch('{{ url('/api/cart') }}', { headers: { Accept: 'application/json' } });
                if (res.ok) {
                    const json = await res.json();
                    this.cartCount = (json.data ?? json)?.item_count ?? 0;
                }
            } catch (e) {}
        }
    }"
    x-init="fetchCartCount()"
    class="sticky top-0 z-50 border-b border-[#EADBCE]/80 bg-[#FAF5F1]/95 text-[#2B1E19] backdrop-blur-md transition-all duration-200">    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        
    <div class="flex h-20 items-center justify-between">
            
            <!-- 1. Logo Thương Hiệu (Style Brew & Bite Artisan Cafe) -->
            <a href="{{ url('/') }}" class="group flex items-center gap-2.5 transition">
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

            <!-- 3. Khu Vực Tương Tác Của User (Tìm kiếm, Giỏ hàng, User Dropdown) -->
            <div class="hidden items-center gap-3 lg:flex">
                
                <!-- Nút Tìm kiếm -->
                <button 
                    type="button" 
                    class="flex h-10 w-10 items-center justify-center rounded-xl border border-[#EADBCE] bg-white/80 text-[#736357] shadow-sm transition hover:border-[#B38352] hover:bg-[#FAF5F1] hover:text-[#B38352] focus:outline-none" 
                    title="{{ __('Tìm kiếm món') }}"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>

                <!-- Nút Giỏ Hàng (Kèm Badge số lượng) -->
                <div x-data="{ 
                    count: 0,
                    async init() {
                        try {
                            const res = await fetch('{{ url('/cart/data') }}', { credentials: 'same-origin' });
                            if (res.ok) {
                                const json = await res.json();
                                this.count = json.cart?.items_count ?? json.cart?.items?.reduce((sum, i) => sum + i.quantity, 0) ?? 0;
                            }
                        } catch (e) {}
                    }
                }" 
                @cart-updated.window="
                    count = $event.detail.cart?.items_count ?? $event.detail.cart?.items?.reduce((sum, i) => sum + i.quantity, 0) ?? count + 1;
                "
                class="relative">
                
                <a 
                    href="{{ route('cart.index') }}" 
                    class="relative flex h-10 w-10 items-center justify-center rounded-xl border border-[#EADBCE] bg-white/80 text-[#736357] shadow-sm transition hover:border-[#B38352] hover:bg-[#FAF5F1] hover:text-[#B38352]" 
                    title="{{ __('Giỏ hàng') }}"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>

                    <span 
                        x-show="count > 0" 
                        x-text="count" 
                        x-cloak
                        class="absolute -right-1 -top-1 flex h-4 w-4 items-center justify-center rounded-full bg-[#B38352] text-[10px] font-bold text-white shadow-sm transition-transform duration-200 scale-100"
                    >
                    </span>
                </a>
            </div>

                <!-- User Dropdown Menu -->
                <div class="relative" @click.outside="userMenu = false">
                    <button 
                        type="button" 
                        @click="userMenu = !userMenu" 
                        class="flex items-center gap-2 rounded-xl border border-[#EADBCE] bg-white/80 py-1.5 pl-2 pr-3 text-sm font-semibold text-[#2B1E19] shadow-sm transition hover:border-[#B38352] hover:bg-[#FAF5F1] focus:outline-none"
                    >
                        <!-- Avatar User / Icon -->
                        <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-[#2B1E19] text-[#FAF5F1] text-xs font-bold shadow-sm">
                            {{ substr(auth()->user()?->name ?? 'U', 0, 1) }}
                        </div>
                        <span class="max-w-[120px] truncate text-xs text-[#4A3B32]">
                            {{ auth()->user()?->name }}
                        </span>
                        <svg class="h-3.5 w-3.5 text-[#A39284] transition-transform duration-200" :class="{ 'rotate-180': userMenu }" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Dropdown Content -->
                    <div
                        x-show="userMenu"
                        x-cloak
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                        x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                        class="absolute right-0 z-50 mt-2 w-56 rounded-2xl border border-[#EADBCE] bg-white p-1.5 text-[#2B1E19] shadow-[0_15px_30px_rgba(43,30,25,0.12)] backdrop-blur-sm"
                    >
                        <!-- Header Dropdown: Tên & Email -->
                        <div class="border-b border-[#EADBCE]/70 px-3.5 py-2.5">
                            <p class="text-xs font-bold text-[#2B1E19] truncate">{{ auth()->user()?->name }}</p>
                            <p class="text-[11px] text-[#A39284] truncate">{{ auth()->user()?->email }}</p>
                        </div>

                        <!-- Menu Items -->
                        <div class="py-1 text-xs">
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 rounded-xl px-3.5 py-2 text-[#736357] transition hover:bg-[#FAF5F1] hover:text-[#B38352]">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5z" />
                                </svg>
                                <span>{{ __('Hồ sơ cá nhân') }}</span>
                            </a>
                            <a href="{{ route('orders.index') }}" class="flex items-center gap-2.5 rounded-xl px-3.5 py-2 text-[#736357] transition hover:bg-[#FAF5F1] hover:text-[#B38352]">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <span>{{ __('Đơn hàng của tôi') }}</span>
                            </a>
                        </div>

                        <!-- Logout Form -->
                        <div class="border-t border-[#EADBCE]/70 pt-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex w-full items-center gap-2.5 rounded-xl px-3.5 py-2 text-left text-xs font-semibold text-red-600 transition hover:bg-red-50">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    <span>{{ __('Đăng xuất') }}</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

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
            <!-- Navigation Links -->
            <nav class="flex flex-col gap-2.5 text-sm font-medium text-[#736357]">
                @include('layouts.partials.nav-links')
            </nav>

            <!-- User Info & Links trên Mobile -->
            <div class="flex flex-col gap-2 border-t border-[#EADBCE] pt-4">
                <div class="flex items-center gap-3 px-1 py-1">
                    <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#2B1E19] text-[#FAF5F1] text-xs font-bold">
                        {{ substr(auth()->user()?->name ?? 'U', 0, 1) }}
                    </div>
                    <div>
                        <p class="text-sm font-bold text-[#2B1E19]">{{ auth()->user()?->name }}</p>
                        <p class="text-xs text-[#A39284]">{{ auth()->user()?->email }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-2 pt-2">
                    <a href="{{ route('profile.edit') }}" class="rounded-xl border border-[#EADBCE] bg-white py-2 text-center text-xs font-semibold text-[#4A3B32] transition hover:bg-[#FAF5F1]">
                        {{ __('Hồ sơ') }}
                    </a>
                    <a href="{{ route('orders.index') }}" class="rounded-xl border border-[#EADBCE] bg-white py-2 text-center text-xs font-semibold text-[#4A3B32] transition hover:bg-[#FAF5F1]">
                        {{ __('Đơn hàng') }}
                    </a>
                    <a href="{{ route('cart.index') }}" class="relative rounded-xl border border-[#EADBCE] bg-white py-2 text-center text-xs font-semibold text-[#4A3B32] transition hover:bg-[#FAF5F1]">
                        {{ __('Giỏ hàng') }}
                        <span x-show="cartCount > 0" x-cloak
                            class="absolute -right-1 -top-1 flex h-4 min-w-4 items-center justify-center rounded-full bg-[#B38352] px-1 text-[10px] font-bold text-white"
                            x-text="cartCount"></span>
                    </a>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="pt-1">
                    @csrf
                    <button type="submit" class="w-full rounded-xl bg-red-50 py-2.5 text-center text-xs font-semibold text-red-600 transition hover:bg-red-100">
                        {{ __('Đăng xuất') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>