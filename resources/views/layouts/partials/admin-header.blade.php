<header x-data="{ userMenu: false }" class="sticky top-0 z-20 flex h-16 items-center justify-between border-b border-[#EADBCE] bg-[#FAF5F1]/95 px-4 backdrop-blur-md sm:px-6 lg:px-8">
    <div class="flex items-center gap-3">
        <button @click="sidebarOpen = ! sidebarOpen" class="rounded-xl p-2 text-[#736357] transition hover:bg-white hover:text-[#2B1E19] lg:hidden" aria-label="{{ __('Menu') }}">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <span class="text-lg font-bold tracking-tight text-[#2B1E19] lg:hidden">Brew<span class="text-[#B38352]">Bite</span></span>
        <span class="hidden font-sans text-sm font-semibold tracking-wide text-[#736357] lg:block">{{ __('Admin dashboard') }}</span>
    </div>

    <div class="relative" @click.outside="userMenu = false">
        <button
            type="button"
            @click="userMenu = !userMenu"
            class="flex items-center gap-2.5 rounded-xl border border-[#EADBCE] bg-white py-1.5 pl-2 pr-3 text-sm shadow-sm transition hover:border-[#B38352] focus:outline-none"
        >
            <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-[#2B1E19] text-xs font-bold text-[#FAF5F1]">
                {{ substr(auth('admin')->user()?->name ?? 'A', 0, 1) }}
            </div>
            <span class="max-w-[140px] truncate text-xs font-semibold text-[#2B1E19]">{{ auth('admin')->user()?->name }}</span>
            <svg class="h-3.5 w-3.5 text-[#A39284] transition-transform duration-200" :class="{ 'rotate-180': userMenu }" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <div
            x-show="userMenu"
            x-cloak
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 translate-y-1 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-1 scale-95"
            class="absolute right-0 z-50 mt-2 w-56 rounded-2xl border border-[#EADBCE] bg-white p-1.5 shadow-[0_15px_30px_rgba(43,30,25,0.12)]"
        >
            <div class="border-b border-[#EADBCE]/70 px-3.5 py-2.5">
                <p class="truncate text-xs font-bold text-[#2B1E19]">{{ auth('admin')->user()?->name }}</p>
                <p class="truncate text-[11px] text-[#A39284]">{{ auth('admin')->user()?->email }}</p>
            </div>

            <div class="border-t border-[#EADBCE]/70 pt-1">
                <form method="POST" action="{{ route('admin.logout') }}">
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
</header>