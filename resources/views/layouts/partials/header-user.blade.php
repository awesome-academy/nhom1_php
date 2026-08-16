<header x-data="{ mobileOpen: false, userMenu: false }" class="bg-[#1b1b18] text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <a href="{{ route('dashboard') }}" class="shrink-0 text-xl font-bold">
                Brew<span class="text-amber-400">Bite</span>
            </a>

            <nav class="hidden items-center gap-8 text-sm lg:flex">
                @include('layouts.partials.nav-links')
            </nav>

            <div class="hidden items-center gap-2 lg:flex">
                <button type="button" class="rounded-md p-2 transition hover:text-amber-400" title="{{ __('Tìm kiếm') }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>

                <div class="relative" @click.outside="userMenu = false">
                    <button type="button" @click="userMenu = ! userMenu" class="rounded-md p-2 transition hover:text-amber-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </button>

                    <div
                        x-show="userMenu"
                        x-cloak
                        x-transition
                        class="absolute right-0 z-50 mt-2 w-52 rounded-md bg-white py-1 text-gray-800 shadow-lg"
                    >
                        <div class="border-b px-4 py-2 text-sm text-gray-500">
                            {{ __('Xin chào, :name', ['name' => auth()->user()?->name]) }}
                        </div>
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm hover:bg-gray-100">
                            {{ __('Hồ sơ') }}
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full px-4 py-2 text-left text-sm hover:bg-gray-100">
                                {{ __('Đăng xuất') }}
                            </button>
                        </form>
                    </div>
                </div>

                <a href="#" class="rounded-md p-2 transition hover:text-amber-400" title="{{ __('Giỏ hàng') }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
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
            <div class="flex flex-col gap-2 border-t border-white/10 pt-3 text-sm">
                <span class="px-1 text-white/70">{{ __('Xin chào, :name', ['name' => auth()->user()?->name]) }}</span>
                <a href="{{ route('profile.edit') }}" class="px-1 py-1">{{ __('Hồ sơ') }}</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full px-1 py-1 text-left">{{ __('Đăng xuất') }}</button>
                </form>
            </div>
        </div>
    </div>
</header>
