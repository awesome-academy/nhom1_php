<template x-teleport="body">
    <div x-show="detailOrder" x-cloak x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 px-4 backdrop-blur-xs">
        <div @click.outside="closeDetail()" class="relative w-full max-w-xl overflow-hidden rounded-3xl border border-[#EADBCE] bg-white p-6 shadow-2xl transition-all sm:p-7">
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

                    <!-- Customer Info (Name, Email, Phone, Address) -->
                    <div class="rounded-2xl border border-[#EADBCE]/80 bg-[#FAF5F1]/60 p-4 text-xs space-y-2">
                        <div class="flex justify-between">
                            <span class="font-bold text-gray-500">{{ __('Khách hàng') }}:</span>
                            <span class="font-bold text-[#2B1E19]" x-text="detailOrder.user?.name ?? 'Khách vãng lai'"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-bold text-gray-500">{{ __('Email liên hệ') }}:</span>
                            <span class="text-gray-700" x-text="detailOrder.user?.email ?? '—'"></span>
                        </div>
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

                    <!-- Order Items -->
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

                    <!-- Total Amount -->
                    <div class="flex items-center justify-between border-t border-[#EADBCE]/70 pt-4">
                        <span class="text-sm font-bold text-[#2B1E19]">{{ __('Tổng tiền thanh toán') }}</span>
                        <span class="text-lg font-extrabold text-[#B38352]" x-text="formatPrice(detailOrder.total_amount) + '₫'"></span>
                    </div>

                    <!-- Actions -->
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