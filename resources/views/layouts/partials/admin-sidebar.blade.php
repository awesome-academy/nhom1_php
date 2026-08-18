<aside
    class="fixed inset-y-0 left-0 z-40 w-64 flex-col bg-gradient-to-b from-[#2B1E19] to-[#1c130f] text-[#FAF5F1] lg:flex"
    :class="sidebarOpen ? 'flex' : 'hidden'"
>
    <div class="flex h-16 items-center gap-2.5 border-b border-white/10 px-6">
        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/10 text-[#B38352] ring-1 ring-[#B38352]/30">
            <svg class="h-4.5 w-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636a9 9 0 010 12.728m0 0l-2.829-2.829m2.829 2.829L21 21M15.536 8.464a5 5 0 010 7.072m0 0l-2.829-2.829m-4.243 4.243a9 9 0 01-12.728 0m0 0l2.829-2.829m-2.829 2.829L3 21m2.828-15.364a9 9 0 0112.728 0" />
                <circle cx="12" cy="12" r="3" />
            </svg>
        </div>
        <div class="flex flex-col leading-tight">
            <span class="text-lg font-bold tracking-tight">Brew<span class="text-[#B38352]">Bite</span></span>
            <span class="text-[9px] font-semibold uppercase tracking-[0.2em] text-[#C7B199]">Admin Panel</span>
        </div>
    </div>

    <nav class="flex-1 space-y-1 px-4 py-6 text-sm">
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 font-medium transition {{ request()->routeIs('admin.dashboard') ? 'bg-[#B38352] text-[#2B1E19] font-semibold shadow-sm' : 'text-[#C7B199] hover:bg-white/5 hover:text-white' }}">
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            {{ __('Dashboard') }}
        </a>

        <a href="{{ route('admin.users.index') }}"
           class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 font-medium transition {{ request()->routeIs('admin.users.*') ? 'bg-[#B38352] text-[#2B1E19] font-semibold shadow-sm' : 'text-[#C7B199] hover:bg-white/5 hover:text-white' }}">
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4" />
            </svg>
            {{ __('Users') }}
        </a>

        <a href="{{ route('admin.categories.manage') }}"
           class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 font-medium transition {{ request()->routeIs('admin.categories.*') ? 'bg-[#B38352] text-[#2B1E19] font-semibold shadow-sm' : 'text-[#C7B199] hover:bg-white/5 hover:text-white' }}">
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
            </svg>
            {{ __('Categories') }}
        </a>

         <span class="flex cursor-not-allowed items-center gap-3 rounded-xl px-3.5 py-2.5 text-[#8A7767]" title="{{ __('Sắp ra mắt') }}">
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            {{ __('Product') }}
        </span>

        <span class="flex cursor-not-allowed items-center gap-3 rounded-xl px-3.5 py-2.5 text-[#8A7767]" title="{{ __('Sắp ra mắt') }}">
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            {{ __('Orders') }}
        </span>

        <span class="flex cursor-not-allowed items-center gap-3 rounded-xl px-3.5 py-2.5 text-[#8A7767]" title="{{ __('Sắp ra mắt') }}">
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
            {{ __('Sản phẩm') }}
        </span>
    </nav>

    <div class="border-t border-white/10 px-6 py-4">
        <a href="{{ url('/') }}" class="flex items-center gap-2 text-xs font-semibold text-[#C7B199] transition hover:text-white">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            {{ __('Xem trang web') }}
        </a>
    </div>
</aside>

<div
    x-show="sidebarOpen"
    x-cloak
    @click="sidebarOpen = false"
    class="fixed inset-0 z-30 bg-[#2B1E19]/50 backdrop-blur-sm lg:hidden"
></div>