<div class="space-y-4">
    <!-- Filter Toolbar -->
    <div class="flex flex-col gap-3 rounded-2xl border border-[#EADBCE] bg-white p-4 shadow-xs sm:flex-row sm:items-center sm:justify-between">
        <div class="relative flex-1 max-w-sm">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="text" x-model="filters.search" @input.debounce.400ms="fetchOrders(1)"
                   placeholder="{{ __('Tìm mã đơn, tên, email hoặc SĐT...') }}"
                   class="w-full rounded-xl border border-[#EADBCE] bg-[#FAF5F1]/40 py-2 pl-9 pr-3.5 text-xs text-[#2B1E19] placeholder-gray-400 transition focus:border-[#B38352] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#B38352]/20">
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <div class="flex items-center gap-2">
                <label class="text-xs font-semibold text-gray-500">{{ __('Trạng thái:') }}</label>
                <select x-model="filters.status" @change="fetchOrders(1)"
                        class="rounded-xl border border-[#EADBCE] bg-white py-1.5 pl-3 pr-8 text-xs font-semibold text-[#2B1E19] focus:border-[#B38352] focus:outline-none focus:ring-2 focus:ring-[#B38352]/20">
                    <option value="">{{ __('Tất cả trạng thái') }}</option>
                    <option value="pending">{{ __('Chờ xác nhận') }}</option>
                    <option value="confirmed">{{ __('Đã tiếp nhận') }}</option>
                    <option value="preparing">{{ __('Đang chuẩn bị món') }}</option>
                    <option value="completed">{{ __('Hoàn thành') }}</option>
                    <option value="cancelled">{{ __('Đã huỷ') }}</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <label class="text-xs font-semibold text-gray-500">{{ __('Hiển thị:') }}</label>
                <select x-model="filters.per_page" @change="fetchOrders(1)"
                        class="rounded-xl border border-[#EADBCE] bg-white py-1.5 pl-2.5 pr-7 text-xs font-semibold text-[#2B1E19] focus:border-[#B38352] focus:outline-none focus:ring-2 focus:ring-[#B38352]/20">
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="overflow-hidden rounded-2xl border border-[#EADBCE] bg-white shadow-xs">
        <div x-show="loading" class="py-20 text-center">
            <div class="inline-block h-8 w-8 animate-spin rounded-full border-3 border-solid border-[#B38352] border-r-transparent"></div>
            <p class="mt-3 text-xs font-semibold text-[#736357]">{{ __('Đang tải dữ liệu đơn hàng...') }}</p>
        </div>

        <div x-show="!loading" class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 text-left text-xs">
                <thead class="bg-[#FAF5F1]/80 text-[11px] font-bold uppercase tracking-wider text-[#736357]">
                    <tr>
                        <th class="px-5 py-3.5 text-center w-14">#</th>
                        <th class="px-5 py-3.5">{{ __('Mã đơn') }}</th>
                        <th class="px-5 py-3.5">{{ __('Khách hàng & SĐT') }}</th>
                        <th class="px-5 py-3.5 text-center">{{ __('Món') }}</th>
                        <th class="px-5 py-3.5 text-right">{{ __('Tổng tiền') }}</th>
                        <th class="px-5 py-3.5">{{ __('Trạng thái') }}</th>
                        <th class="px-5 py-3.5">{{ __('Thời gian tạo') }}</th>
                        <th class="px-5 py-3.5 text-right">{{ __('Thao tác') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <template x-for="(order, index) in filteredOrders()" :key="order.id">
                        <tr class="transition hover:bg-[#FAF5F1]/50">
                            <td class="px-5 py-3.5 text-center font-semibold text-gray-400" x-text="index + 1 + (meta.current_page - 1) * (meta.per_page ?? 10)"></td>
                            <td class="px-5 py-3.5 font-bold text-[#2B1E19]" x-text="'#' + order.id"></td>
                            <td class="px-5 py-3.5">
                                <div class="font-bold text-[#2B1E19]" x-text="order.user?.name ?? 'Khách vãng lai'"></div>
                                <div class="text-[11px] text-gray-500" x-text="order.user?.email ?? ''"></div>
                                <div class="mt-0.5 flex items-center gap-1 text-[11px] font-semibold text-[#8E583C]" x-show="order.user?.phone || order.phone">
                                    <svg class="h-3 w-3 text-[#B38352]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    <span x-text="order.user?.phone || order.phone"></span>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="inline-flex items-center rounded-lg bg-gray-100 px-2 py-0.5 font-bold text-gray-700" x-text="(order.item_count ?? (order.items?.length ?? 0)) + ' món'"></span>
                            </td>
                            <td class="px-5 py-3.5 text-right font-extrabold text-[#B38352]" x-text="formatPrice(order.total_amount) + '₫'"></td>
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-[11px] font-bold" :class="statusInfo(order.status).badgeClass">
                                    <span class="h-1.5 w-1.5 rounded-full" :class="statusInfo(order.status).dotClass"></span>
                                    <span x-text="statusInfo(order.status).label"></span>
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-gray-500 whitespace-nowrap" x-text="formatDate(order.created_at)"></td>
                            <td class="px-5 py-3.5 text-right whitespace-nowrap space-x-1.5">
                                <button type="button" @click="openDetail(order)"
                                        class="inline-flex items-center rounded-lg border border-[#EADBCE] bg-white px-2.5 py-1 text-[11px] font-bold text-[#4A3B32] shadow-xs transition hover:border-[#B38352] hover:bg-[#FAF5F1] hover:text-[#B38352]">
                                    {{ __('Chi tiết') }}
                                </button>
                                <template x-if="nextPrimaryAction(order.status)">
                                    <button type="button" :disabled="updating"
                                            @click="updateStatus(order, nextPrimaryAction(order.status).nextStatus)"
                                            class="inline-flex items-center rounded-lg px-2.5 py-1 text-[11px] font-bold text-white shadow-xs transition disabled:opacity-50"
                                            :class="nextPrimaryAction(order.status).btnClass"
                                            x-text="nextPrimaryAction(order.status).btnLabel">
                                    </button>
                                </template>
                            </td>
                        </tr>
                    </template>

                    <tr x-show="!loading && filteredOrders().length === 0">
                        <td colspan="8" class="py-14 text-center">
                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-[#FAF5F1] text-[#B38352]">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                            <p class="mt-3 font-bold text-[#2B1E19]">{{ __('Không có đơn hàng nào phù hợp.') }}</p>
                            <p class="mt-1 text-xs text-gray-400">{{ __('Hãy thử thay đổi từ khoá tìm kiếm hoặc bộ lọc trạng thái.') }}</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div x-show="!loading && meta.last_page > 1" class="flex flex-col items-center justify-between gap-3 border-t border-gray-100 bg-[#FAF5F1]/40 px-5 py-3.5 sm:flex-row">
            <span class="text-xs font-semibold text-[#736357]">
                {{ __('Trang') }} <b class="text-[#2B1E19]" x-text="meta.current_page"></b> / <span x-text="meta.last_page"></span> ({{ __('Tổng') }} <b class="text-[#2B1E19]" x-text="meta.total"></b> {{ __('đơn') }})
            </span>

            <div class="flex items-center gap-1.5">
                <button type="button" :disabled="meta.current_page <= 1" @click="fetchOrders(meta.current_page - 1)"
                        class="rounded-xl border border-[#EADBCE] bg-white px-3 py-1.5 text-xs font-bold text-[#4A3B32] transition hover:bg-[#FAF5F1] disabled:opacity-40 disabled:cursor-not-allowed">
                    &lsaquo; {{ __('Trước') }}
                </button>

                <template x-for="p in pageList()" :key="p">
                    <button type="button" @click="fetchOrders(p)"
                            class="h-8 w-8 rounded-xl text-xs font-bold transition"
                            :class="p === meta.current_page ? 'bg-[#2B1E19] text-[#FAF5F1] shadow-xs' : 'border border-[#EADBCE] bg-white text-[#4A3B32] hover:bg-[#FAF5F1]'"
                            x-text="p"></button>
                </template>

                <button type="button" :disabled="meta.current_page >= meta.last_page" @click="fetchOrders(meta.current_page + 1)"
                        class="rounded-xl border border-[#EADBCE] bg-white px-3 py-1.5 text-xs font-bold text-[#4A3B32] transition hover:bg-[#FAF5F1] disabled:opacity-40 disabled:cursor-not-allowed">
                    {{ __('Sau') }} &rsaquo;
                </button>
            </div>
        </div>
    </div>
</div>