<aside
    class="fixed inset-y-0 left-0 z-40 w-64 flex-col bg-[#1b1b18] text-white lg:flex"
    :class="sidebarOpen ? 'flex' : 'hidden'"
>
    <div class="flex h-16 items-center gap-2 border-b border-white/10 px-6">
        <span class="text-xl font-bold">Brew<span class="text-amber-400">Bite</span></span>
        <span class="ml-auto rounded-full bg-white/10 px-2 py-0.5 text-[10px] uppercase tracking-wide text-white/60">{{ __('Admin') }}</span>
    </div>

    <nav class="flex-1 space-y-1 px-4 py-6 text-sm">
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-3 rounded-md px-3 py-2 transition {{ request()->routeIs('admin.dashboard') ? 'bg-amber-500 text-[#1b1b18] font-semibold' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            {{ __('Dashboard') }}
        </a>

        <a href="{{ route('admin.users.index') }}"
           class="flex items-center gap-3 rounded-md px-3 py-2 transition {{ request()->routeIs('admin.users.*') ? 'bg-amber-500 text-[#1b1b18] font-semibold' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4" />
            </svg>
            {{ __('Users') }}
        </a>

        <span class="flex cursor-not-allowed items-center gap-3 rounded-md px-3 py-2 text-white/40" title="{{ __('Sắp ra mắt') }}">
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            {{ __('Orders') }}
        </span>

        <span class="flex cursor-not-allowed items-center gap-3 rounded-md px-3 py-2 text-white/40" title="{{ __('Sắp ra mắt') }}">
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h10M4 18h10" />
            </svg>
            {{ __('Menu') }}
        </span>
    </nav>
</aside>

<div
    x-show="sidebarOpen"
    x-cloak
    @click="sidebarOpen = false"
    class="fixed inset-0 z-30 bg-black/40 lg:hidden"
></div>
