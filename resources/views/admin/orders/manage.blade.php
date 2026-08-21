@extends('layouts.admin')

@section('content')
<div class="space-y-6 font-sans antialiased" x-data="adminOrderManager()" x-init="init()">
    
    <!-- 1. Header & Quick Actions -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2.5">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#FAF5F1] text-[#B38352] ring-1 ring-[#EADBCE]">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </span>
                <h1 class="text-2xl font-bold tracking-tight text-[#2B1E19]">{{ __('Quản lý đơn hàng') }}</h1>
            </div>
            <p class="mt-1 text-xs text-gray-500">{{ __('Theo dõi, xử lý và cập nhật tiến trình toàn bộ đơn hàng trong hệ thống.') }}</p>
        </div>

        <div class="flex items-center gap-2.5">
            <button type="button" @click="fetchOrders(meta.current_page)" 
                    class="inline-flex items-center gap-2 rounded-xl border border-[#EADBCE] bg-white px-3.5 py-2 text-xs font-semibold text-[#4A3B32] shadow-sm transition hover:bg-[#FAF5F1] hover:text-[#B38352] focus:outline-none">
                <svg class="h-4 w-4" :class="{ 'animate-spin': loading }" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <span>{{ __('Làm mới') }}</span>
            </button>
        </div>
    </div>

    <!-- 2. Notice -->
    <div x-show="notice.message" x-cloak x-transition
         class="flex items-center justify-between rounded-2xl border px-4 py-3 text-xs font-semibold shadow-sm transition-all"
         :class="notice.type === 'success' ? 'border-emerald-200 bg-emerald-50/90 text-emerald-800' : 'border-rose-200 bg-rose-50/90 text-rose-800'">
        <div class="flex items-center gap-2">
            <span class="flex h-5 w-5 items-center justify-center rounded-full" :class="notice.type === 'success' ? 'bg-emerald-600 text-white' : 'bg-rose-600 text-white'">
                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" :d="notice.type === 'success' ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12'"/>
                </svg>
            </span>
            <span x-text="notice.message"></span>
        </div>
        <button @click="notice.message = ''" class="text-gray-400 hover:text-gray-600">&times;</button>
    </div>

    <!-- 3. Status Metric KPI Cards -->
    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-5">
        <button type="button" @click="setFilter('')" 
                class="flex flex-col rounded-2xl border bg-white p-3.5 text-left shadow-xs transition hover:border-[#B38352] hover:shadow-sm"
                :class="filters.status === '' ? 'border-[#B38352] ring-2 ring-[#B38352]/20' : 'border-[#EADBCE]/70'">
            <span class="text-[11px] font-bold uppercase tracking-wider text-gray-500">{{ __('Tất cả đơn') }}</span>
            <span class="mt-1 text-xl font-extrabold text-[#2B1E19]" x-text="metrics.total ?? (meta.total ?? '—')"></span>
        </button>

        <button type="button" @click="setFilter('pending')" 
                class="flex flex-col rounded-2xl border bg-white p-3.5 text-left shadow-xs transition hover:border-amber-500 hover:shadow-sm"
                :class="filters.status === 'pending' ? 'border-amber-500 ring-2 ring-amber-500/20' : 'border-[#EADBCE]/70'">
            <div class="flex items-center gap-1.5 text-[11px] font-bold uppercase tracking-wider text-amber-700">
                <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                <span>{{ __('Chờ xác nhận') }}</span>
            </div>
            <span class="mt-1 text-xl font-extrabold text-amber-900" x-text="metrics.pending ?? '—'"></span>
        </button>

        <button type="button" @click="setFilter('confirmed')" 
                class="flex flex-col rounded-2xl border bg-white p-3.5 text-left shadow-xs transition hover:border-sky-500 hover:shadow-sm"
                :class="filters.status === 'confirmed' ? 'border-sky-500 ring-2 ring-sky-500/20' : 'border-[#EADBCE]/70'">
            <div class="flex items-center gap-1.5 text-[11px] font-bold uppercase tracking-wider text-sky-700">
                <span class="h-2 w-2 rounded-full bg-sky-500"></span>
                <span>{{ __('Đã tiếp nhận') }}</span>
            </div>
            <span class="mt-1 text-xl font-extrabold text-sky-900" x-text="metrics.confirmed ?? '—'"></span>
        </button>

        <button type="button" @click="setFilter('preparing')" 
                class="flex flex-col rounded-2xl border bg-white p-3.5 text-left shadow-xs transition hover:border-indigo-500 hover:shadow-sm"
                :class="filters.status === 'preparing' ? 'border-indigo-500 ring-2 ring-indigo-500/20' : 'border-[#EADBCE]/70'">
            <div class="flex items-center gap-1.5 text-[11px] font-bold uppercase tracking-wider text-indigo-700">
                <span class="h-2 w-2 rounded-full bg-indigo-500 animate-pulse"></span>
                <span>{{ __('Đang làm món') }}</span>
            </div>
            <span class="mt-1 text-xl font-extrabold text-indigo-900" x-text="metrics.preparing ?? '—'"></span>
        </button>

        <button type="button" @click="setFilter('completed')" 
                class="flex flex-col rounded-2xl border bg-white p-3.5 text-left shadow-xs transition hover:border-emerald-500 hover:shadow-sm"
                :class="filters.status === 'completed' ? 'border-emerald-500 ring-2 ring-emerald-500/20' : 'border-[#EADBCE]/70'">
            <div class="flex items-center gap-1.5 text-[11px] font-bold uppercase tracking-wider text-emerald-700">
                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                <span>{{ __('Hoàn thành') }}</span>
            </div>
            <span class="mt-1 text-xl font-extrabold text-emerald-900" x-text="metrics.completed ?? '—'"></span>
        </button>
    </div>

    <!-- 4. Filter & Search Toolbar -->
    <div class="flex flex-col gap-3 rounded-2xl border border-[#EADBCE] bg-white p-4 shadow-xs sm:flex-row sm:items-center sm:justify-between">
        <!-- Search Input (Hỗ trợ tìm Mã đơn, Tên, Email, SĐT) -->
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

        <!-- Filter Controls -->
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

    <!-- 5. Main Orders Table -->
    <div class="overflow-hidden rounded-2xl border border-[#EADBCE] bg-white shadow-xs">
        
        <!-- Loading State -->
        <div x-show="loading" class="py-20 text-center">
            <div class="inline-block h-8 w-8 animate-spin rounded-full border-3 border-solid border-[#B38352] border-r-transparent"></div>
            <p class="mt-3 text-xs font-semibold text-[#736357]">{{ __('Đang tải dữ liệu đơn hàng...') }}</p>
        </div>

        <!-- Table Container -->
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
                            <!-- STT -->
                            <td class="px-5 py-3.5 text-center font-semibold text-gray-400" x-text="index + 1 + (meta.current_page - 1) * (meta.per_page ?? 10)"></td>
                            
                            <!-- Mã đơn -->
                            <td class="px-5 py-3.5">
                                <span class="font-bold text-[#2B1E19]" x-text="'#' + order.id"></span>
                            </td>

                            <!-- Thông tin Khách hàng, Email & SĐT Liên hệ -->
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

                            <!-- Số món -->
                            <td class="px-5 py-3.5 text-center">
                                <span class="inline-flex items-center rounded-lg bg-gray-100 px-2 py-0.5 font-bold text-gray-700" x-text="(order.item_count ?? (order.items?.length ?? 0)) + ' món'"></span>
                            </td>

                            <!-- Tổng tiền -->
                            <td class="px-5 py-3.5 text-right">
                                <span class="font-extrabold text-[#B38352]" x-text="formatPrice(order.total_amount) + '₫'"></span>
                            </td>

                            <!-- Trạng thái -->
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-[11px] font-bold"
                                      :class="statusInfo(order.status).badgeClass">
                                    <span class="h-1.5 w-1.5 rounded-full" :class="statusInfo(order.status).dotClass"></span>
                                    <span x-text="statusInfo(order.status).label"></span>
                                </span>
                            </td>

                            <!-- Ngày tạo -->
                            <td class="px-5 py-3.5 text-gray-500 whitespace-nowrap" x-text="formatDate(order.created_at)"></td>

                            <!-- Thao tác nhanh -->
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

                    <!-- Empty State -->
                    <tr x-show="!loading && filteredOrders().length === 0">
                        <td colspan="8" class="py-14 text-center">
                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-[#FAF5F1] text-[#B38352]">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                            <p class="mt-3 font-bold text-[#2B1E19]">{{ __('Không có đơn hàng nào phù hợp.') }}</p>
                            <p class="mt-1 text-xs text-gray-400">{{ __('Hãy thử thay đổi từ khoá tìm kiếm hoặc đặt lại bộ lọc trạng thái.') }}</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- 6. Pagination Footer -->
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

    <!-- 7. Order Detail & Status Transition Modal -->
    <template x-teleport="body">
        <div x-show="detailOrder" x-cloak x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 px-4 backdrop-blur-xs">
            <div @click.outside="closeDetail()" 
                 class="relative w-full max-w-xl overflow-hidden rounded-3xl border border-[#EADBCE] bg-white p-6 shadow-2xl transition-all sm:p-7">
                <template x-if="detailOrder">
                    <div class="space-y-5">
                        
                        <!-- Modal Header -->
                        <div class="flex items-start justify-between border-b border-[#EADBCE]/70 pb-4">
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="font-serif text-xl font-bold text-[#2B1E19]" x-text="'Chi tiết Đơn hàng #' + detailOrder.id"></h3>
                                    <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-0.5 text-[11px] font-bold"
                                          :class="statusInfo(detailOrder.status).badgeClass">
                                        <span class="h-1.5 w-1.5 rounded-full" :class="statusInfo(detailOrder.status).dotClass"></span>
                                        <span x-text="statusInfo(detailOrder.status).label"></span>
                                    </span>
                                </div>
                                <p class="mt-1 text-xs text-gray-500" x-text="'Ngày đặt: ' + formatDate(detailOrder.created_at)"></p>
                            </div>
                            <button @click="closeDetail()" class="rounded-xl p-1 text-gray-400 hover:bg-[#FAF5F1] hover:text-gray-700">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                        </div>

                        <!-- Customer Info Card (Kèm Phone & Address) -->
                        <div class="rounded-2xl border border-[#EADBCE]/80 bg-[#FAF5F1]/60 p-4 text-xs space-y-2">
                            <div class="flex justify-between">
                                <span class="font-bold text-gray-500">{{ __('Khách hàng') }}:</span>
                                <span class="font-bold text-[#2B1E19]" x-text="detailOrder.user?.name ?? 'Khách vãng lai'"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-bold text-gray-500">{{ __('Email liên hệ') }}:</span>
                                <span class="text-gray-700" x-text="detailOrder.user?.email ?? '—'"></span>
                            </div>
                            <!-- SĐT liên hệ -->
                            <div class="flex justify-between">
                                <span class="font-bold text-gray-500">{{ __('Số điện thoại') }}:</span>
                                <span class="font-bold text-[#8E583C]" x-text="detailOrder.user?.phone || detailOrder.phone || '—'"></span>
                            </div>
                            <template x-if="detailOrder.user?.address || detailOrder.address">
                                <div class="flex justify-between border-t border-[#EADBCE]/40 pt-1.5">
                                    <span class="font-bold text-gray-500">{{ __('Địa chỉ') }}:</span>
                                    <span class="text-gray-700 max-w-[280px] text-right" x-text="detailOrder.user?.address || detailOrder.address"></span>
                                </div>
                            </template>
                            <template x-if="detailOrder.note">
                                <div class="flex justify-between border-t border-[#EADBCE]/60 pt-1.5">
                                    <span class="font-bold text-gray-500">{{ __('Ghi chú') }}:</span>
                                    <span class="italic text-amber-800" x-text="detailOrder.note"></span>
                                </div>
                            </template>
                        </div>

                        <!-- Order Items List -->
                        <div>
                            <p class="mb-2 text-xs font-bold uppercase tracking-wider text-[#736357]">{{ __('Danh sách món đặt') }}</p>
                            <div class="max-h-56 space-y-2.5 overflow-y-auto pr-1">
                                <template x-for="item in (detailOrder.items ?? [])" :key="item.id">
                                    <div class="flex items-center justify-between rounded-xl border border-gray-100 bg-gray-50/50 p-2.5 text-xs">
                                        <div>
                                            <p class="font-bold text-[#2B1E19]" x-text="item.product_name"></p>
                                            <p class="text-[11px] text-gray-500" x-text="formatPrice(item.unit_price) + '₫ × ' + item.quantity"></p>
                                        </div>
                                        <span class="font-bold text-[#2B1E19]" x-text="formatPrice(item.subtotal) + '₫'"></span>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Total Bill Breakdown -->
                        <div class="flex items-center justify-between border-t border-[#EADBCE]/70 pt-4">
                            <span class="text-sm font-bold text-[#2B1E19]">{{ __('Tổng tiền thanh toán') }}</span>
                            <span class="text-lg font-extrabold text-[#B38352]" x-text="formatPrice(detailOrder.total_amount) + '₫'"></span>
                        </div>

                        <!-- Status Transition Action Buttons -->
                        <div class="rounded-2xl border border-[#EADBCE]/80 bg-[#FAF5F1]/40 p-4">
                            <p class="mb-2.5 text-[11px] font-bold uppercase tracking-wider text-[#736357]">{{ __('Chuyển trạng thái đơn hàng') }}</p>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="next in allowedTransitions(detailOrder.status)" :key="next">
                                    <button type="button" :disabled="updating" @click="updateStatus(detailOrder, next)"
                                            class="inline-flex items-center gap-1.5 rounded-xl px-3.5 py-2 text-xs font-bold transition disabled:opacity-50"
                                            :class="transitionButtonClass(next)">
                                        <span x-text="transitionActionLabel(next)"></span>
                                    </button>
                                </template>

                                <span x-show="allowedTransitions(detailOrder.status).length === 0" class="text-xs italic text-gray-400">
                                    {{ __('Đơn hàng đã hoàn tất hoặc đã huỷ (không thể chuyển tiếp).') }}
                                </span>
                            </div>
                        </div>

                    </div>
                </template>
            </div>
        </div>
    </template>
</div>
@endsection

@push('scripts')
<script>
function adminOrderManager() {
    return {
        loading: true,
        updating: false,
        orders: [],
        meta: { current_page: 1, last_page: 1, total: 0, per_page: 10 },
        filters: { status: '', search: '', per_page: 10 },
        metrics: { total: 0, pending: 0, confirmed: 0, preparing: 0, completed: 0, cancelled: 0 },
        detailOrder: null,
        notice: { type: 'success', message: '' },

        init() {
            this.fetchOrders(1);
        },

        setFilter(status) {
            this.filters.status = status;
            this.fetchOrders(1);
        },

        // Hỗ trợ tìm kiếm realtime theo: Mã đơn (#ID), Tên, Email, SĐT
        filteredOrders() {
            if (!this.filters.search) return this.orders;
            const q = this.filters.search.toLowerCase().trim();
            return this.orders.filter(o => 
                String(o.id).includes(q) ||
                (o.user?.name && o.user.name.toLowerCase().includes(q)) ||
                (o.user?.email && o.user.email.toLowerCase().includes(q)) ||
                (o.user?.phone && o.user.phone.includes(q)) ||
                (o.phone && o.phone.includes(q))
            );
        },

        async fetchOrders(page = 1) {
            this.loading = true;
            try {
                const params = new URLSearchParams({ page, per_page: this.filters.per_page });
                if (this.filters.status) params.set('status', this.filters.status);

                const res = await fetch(`{{ route('admin.orders.index') }}?${params.toString()}`, {
                    headers: { Accept: 'application/json' },
                });
                const json = await res.json();
                this.orders = json.data ?? [];
                this.meta = json.meta ?? { current_page: 1, last_page: 1, total: this.orders.length, per_page: this.filters.per_page };
                
                this.calculateMetrics();
            } catch (e) {
                this.showNotice('error', '{{ __('Không thể tải danh sách đơn hàng.') }}');
            } finally {
                this.loading = false;
            }
        },

        calculateMetrics() {
            const counts = { pending: 0, confirmed: 0, preparing: 0, completed: 0, cancelled: 0 };
            this.orders.forEach(o => {
                const s = (o.status?.value ?? o.status);
                if (counts[s] !== undefined) counts[s]++;
            });
            this.metrics = {
                total: this.meta.total || this.orders.length,
                ...counts
            };
        },

        async openDetail(order) {
            this.detailOrder = order;
            try {
                const res = await fetch(`{{ url('admin/orders') }}/${order.id}`, {
                    headers: { Accept: 'application/json' },
                });
                const json = await res.json();
                this.detailOrder = json.data ?? json;
            } catch (e) {
                this.showNotice('error', '{{ __('Không thể tải chi tiết đơn hàng.') }}');
            }
        },

        closeDetail() {
            this.detailOrder = null;
        },

        async updateStatus(order, status) {
            if (status === 'cancelled' && !confirm('{{ __('Bạn có chắc chắn muốn huỷ đơn hàng này? Tồn kho sẽ được khôi phục.') }}')) {
                return;
            }

            this.updating = true;
            try {
                const res = await fetch(`{{ url('admin/orders') }}/${order.id}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ status }),
                });
                const json = await res.json();

                if (res.status === 422) {
                    this.showNotice('error', Object.values(json.errors ?? {}).flat()[0] ?? '{{ __('Không thể cập nhật trạng thái.') }}');
                    return;
                }
                if (!res.ok) throw new Error('request-failed');

                this.detailOrder = json.data ?? json;
                this.showNotice('success', '{{ __('Đã cập nhật trạng thái đơn hàng thành công.') }}');
                await this.fetchOrders(this.meta.current_page);
            } catch (e) {
                this.showNotice('error', '{{ __('Có lỗi xảy ra khi cập nhật trạng thái đơn hàng.') }}');
            } finally {
                this.updating = false;
            }
        },

        allowedTransitions(status) {
            const val = status?.value ?? status;
            const map = {
                pending: ['confirmed', 'cancelled'],
                confirmed: ['preparing', 'cancelled'],
                preparing: ['completed'],
                completed: [],
                cancelled: [],
            };
            return map[val] ?? [];
        },

        nextPrimaryAction(status) {
            const val = status?.value ?? status;
            const actionMap = {
                pending: { nextStatus: 'confirmed', btnLabel: '{{ __('Xác nhận đơn') }}', btnClass: 'bg-sky-600 hover:bg-sky-700' },
                confirmed: { nextStatus: 'preparing', btnLabel: '{{ __('Làm món') }}', btnClass: 'bg-indigo-600 hover:bg-indigo-700' },
                preparing: { nextStatus: 'completed', btnLabel: '{{ __('Hoàn thành') }}', btnClass: 'bg-emerald-600 hover:bg-emerald-700' },
            };
            return actionMap[val] ?? null;
        },

        transitionActionLabel(status) {
            const labels = {
                confirmed: '✓ {{ __('Xác nhận tiếp nhận') }}',
                preparing: '☕ {{ __('Bắt đầu chuẩn bị món') }}',
                completed: '★ {{ __('Đánh dấu hoàn thành') }}',
                cancelled: '✕ {{ __('Huỷ đơn hàng') }}',
            };
            return labels[status] ?? status;
        },

        transitionButtonClass(status) {
            if (status === 'cancelled') return 'border border-rose-200 bg-white text-rose-600 hover:bg-rose-50';
            if (status === 'completed') return 'bg-emerald-600 text-white hover:bg-emerald-700 shadow-xs';
            if (status === 'preparing') return 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-xs';
            return 'bg-[#2B1E19] text-[#FAF5F1] hover:bg-[#B38352] shadow-xs';
        },

        statusInfo(status) {
            const val = status?.value ?? status;
            const map = {
                pending: {
                    label: '{{ __('Chờ xác nhận') }}',
                    badgeClass: 'bg-amber-50 text-amber-800 border-amber-200',
                    dotClass: 'bg-amber-500'
                },
                confirmed: {
                    label: '{{ __('Đã tiếp nhận') }}',
                    badgeClass: 'bg-sky-50 text-sky-800 border-sky-200',
                    dotClass: 'bg-sky-500'
                },
                preparing: {
                    label: '{{ __('Đang làm món') }}',
                    badgeClass: 'bg-indigo-50 text-indigo-800 border-indigo-200',
                    dotClass: 'bg-indigo-500 animate-pulse'
                },
                completed: {
                    label: '{{ __('Hoàn thành') }}',
                    badgeClass: 'bg-emerald-50 text-emerald-800 border-emerald-200',
                    dotClass: 'bg-emerald-500'
                },
                cancelled: {
                    label: '{{ __('Đã huỷ') }}',
                    badgeClass: 'bg-rose-50 text-rose-800 border-rose-200',
                    dotClass: 'bg-rose-500'
                },
            };
            return map[val] ?? {
                label: val,
                badgeClass: 'bg-gray-50 text-gray-700 border-gray-200',
                dotClass: 'bg-gray-400'
            };
        },

        pageList() {
            const total = this.meta.last_page || 1;
            const current = this.meta.current_page || 1;
            let start = Math.max(1, current - 2);
            let end = Math.min(total, start + 4);
            start = Math.max(1, end - 4);
            const pages = [];
            for (let p = start; p <= end; p++) pages.push(p);
            return pages;
        },

        formatPrice(value) {
            return new Intl.NumberFormat('vi-VN').format(value || 0);
        },

        formatDate(value) {
            if (!value) return '—';
            return new Date(value).toLocaleString('vi-VN', {
                hour: '2-digit', minute: '2-digit', day: '2-digit', month: '2-digit', year: 'numeric'
            });
        },

        showNotice(type, message) {
            this.notice = { type, message };
            setTimeout(() => { this.notice.message = ''; }, 3500);
        },
    };
}
</script>
@endpush