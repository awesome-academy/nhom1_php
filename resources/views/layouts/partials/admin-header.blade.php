<header class="sticky top-0 z-20 flex h-16 items-center justify-between border-b border-black/5 bg-white px-4 sm:px-6 lg:px-8">
    <div class="flex items-center gap-3">
        <button @click="sidebarOpen = ! sidebarOpen" class="rounded-md p-2 text-gray-500 hover:bg-gray-100 lg:hidden" aria-label="{{ __('Menu') }}">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <span class="text-lg font-bold lg:hidden">Brew<span class="text-amber-500">Bite</span></span>
        <span class="hidden text-sm text-gray-500 lg:block">{{ __('Trang quản trị') }}</span>
    </div>

    <x-dropdown align="right" width="48">
        <x-slot name="trigger">
            <button class="flex items-center gap-2 rounded-md px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                <span>{{ Auth::user()->name }}</span>
                <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </x-slot>

        <x-slot name="content">
            <x-dropdown-link >
                {{ __('Hồ sơ Admin') }}
            </x-dropdown-link>

            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <x-dropdown-link :href="route('admin.logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                    {{ __('Đăng xuất') }}
                </x-dropdown-link>
            </form>
        </x-slot>
    </x-dropdown>
</header>
