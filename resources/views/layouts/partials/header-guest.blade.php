<header x-data="{ mobileOpen: false }" class="bg-[#1b1b18] text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <a href="{{ url('/') }}" class="shrink-0 text-xl font-bold">
                Brew<span class="text-amber-400">Bite</span>
            </a>

            <nav class="hidden items-center gap-8 text-sm lg:flex">
                @include('layouts.partials.nav-links')
            </nav>

            <div class="hidden items-center gap-3 lg:flex">
                <a
                    href="{{ route('login') }}"
                    class="rounded-md border border-white/20 px-4 py-2 text-sm transition hover:bg-white/10"
                >
                    {{ __('Đăng nhập') }}
                </a>
                <a
                    href="{{ route('register') }}"
                    class="rounded-md bg-amber-500 px-4 py-2 text-sm font-semibold text-[#1b1b18] transition hover:bg-amber-600"
                >
                    {{ __('Đăng ký') }}
                </a>
            </div>

            <button @click="mobileOpen = ! mobileOpen" class="p-2 text-white lg:hidden" aria-label="{{ __('Menu') }}">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <div x-show="mobileOpen" x-cloak class="space-y-3 pb-4 lg:hidden">
            <nav class="flex flex-col gap-3 text-sm">
                @include('layouts.partials.nav-links')
            </nav>
            <div class="flex flex-col gap-2 border-t border-white/10 pt-3">
                <a href="{{ route('login') }}" class="rounded-md border border-white/20 px-4 py-2 text-center text-sm">
                    {{ __('Đăng nhập') }}
                </a>
                <a href="{{ route('register') }}" class="rounded-md bg-amber-500 px-4 py-2 text-center text-sm font-semibold text-[#1b1b18]">
                    {{ __('Đăng ký') }}
                </a>
            </div>
        </div>
    </div>
</header>
