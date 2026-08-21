<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <!-- Tổng Doanh thu -->
    <div class="rounded-2xl border border-[#EADBCE]/80 bg-white p-5 shadow-xs transition hover:shadow-sm">
        <div class="flex items-center justify-between">
            <span class="text-[11px] font-bold uppercase tracking-wider text-[#736357]">{{ __('Tổng doanh thu') }}</span>
            <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 ring-1 ring-emerald-200">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </span>
        </div>
        <div class="mt-3">
            <h3 class="text-2xl font-extrabold text-[#2B1E19]">
                {{ number_format($totalRevenue, 0, ',', '.') }}₫
            </h3>
            <p class="mt-1 text-[11px] font-medium text-emerald-600">
                {{ __('Từ tất cả các đơn hoàn thành') }}
            </p>
        </div>
    </div>

    <!-- Đơn hàng cần xử lý -->
    <div class="rounded-2xl border border-[#EADBCE]/80 bg-white p-5 shadow-xs transition hover:shadow-sm">
        <div class="flex items-center justify-between">
            <span class="text-[11px] font-bold uppercase tracking-wider text-[#736357]">{{ __('Đơn chờ xử lý') }}</span>
            <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-amber-50 text-amber-600 ring-1 ring-amber-200">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </span>
        </div>
        <div class="mt-3">
            <h3 class="text-2xl font-extrabold text-amber-900">
                {{ $pendingOrdersCount }} <span class="text-xs font-semibold text-gray-500">{{ __('đơn mới') }}</span>
            </h3>
            <p class="mt-1 text-[11px] font-medium text-indigo-600">
                {{ $preparingOrdersCount }} {{ __('đơn đang làm món') }}
            </p>
        </div>
    </div>

    <!-- Khách hàng -->
    <div class="rounded-2xl border border-[#EADBCE]/80 bg-white p-5 shadow-xs transition hover:shadow-sm">
        <div class="flex items-center justify-between">
            <span class="text-[11px] font-bold uppercase tracking-wider text-[#736357]">{{ __('Khách hàng') }}</span>
            <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-sky-50 text-sky-600 ring-1 ring-sky-200">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4" />
                </svg>
            </span>
        </div>
        <div class="mt-3">
            <h3 class="text-2xl font-extrabold text-[#2B1E19]">
                {{ $usersCount }} <span class="text-xs font-semibold text-gray-500">{{ __('tài khoản') }}</span>
            </h3>
            <p class="mt-1 text-[11px] font-medium text-sky-600">
                {{ __('Người dùng đã đăng ký') }}
            </p>
        </div>
    </div>

    <!-- Thực đơn & Tồn kho -->
    <div class="rounded-2xl border border-[#EADBCE]/80 bg-white p-5 shadow-xs transition hover:shadow-sm">
        <div class="flex items-center justify-between">
            <span class="text-[11px] font-bold uppercase tracking-wider text-[#736357]">{{ __('Sản phẩm & Tồn kho') }}</span>
            <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-[#FAF5F1] text-[#B38352] ring-1 ring-[#EADBCE]">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </span>
        </div>
        <div class="mt-3">
            <h3 class="text-2xl font-extrabold text-[#2B1E19]">
                {{ $productsCount }} <span class="text-xs font-semibold text-gray-500">{{ __('món') }}</span>
            </h3>
            <p class="mt-1 text-[11px] font-semibold {{ $lowStockCount > 0 ? 'text-rose-600' : 'text-gray-500' }}">
                {{ $lowStockCount > 0 ? $lowStockCount . ' món tồn kho ≤ 5' : 'Tồn kho ổn định' }}
            </p>
        </div>
    </div>
</div>