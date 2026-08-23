<template x-teleport="body">
    <div x-show="showCheckoutModal" x-cloak 
         x-transition.opacity 
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 px-4 font-sans backdrop-blur-xs">
        
        <div @click.outside="showCheckoutModal = false"
             class="relative w-full max-w-lg overflow-hidden rounded-[28px] border border-[#EADBCE] bg-white p-6 shadow-2xl transition-all sm:p-7">
            
            {{-- Modal Header --}}
            <div class="flex items-center justify-between border-b border-[#EADBCE]/70 pb-4">
                <div class="flex items-center gap-2.5">
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#FAF5F1] text-[#B38352] ring-1 ring-[#EADBCE]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </span>
                    <div>
                        <h3 class="text-lg font-bold tracking-tight text-[#2B1E19]">{{ __('Xác nhận thông tin giao hàng') }}</h3>
                        <p class="text-xs text-gray-500">{{ __('Kiểm tra và điền thông tin để chúng tôi giao hàng chính xác.') }}</p>
                    </div>
                </div>
                <button type="button" @click="showCheckoutModal = false" class="rounded-xl p-1 text-gray-400 hover:bg-[#FAF5F1] hover:text-gray-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            {{-- Modal Form --}}
            <form @submit.prevent="submitOrder()" class="mt-5 space-y-4">
                <div x-show="modalError" class="rounded-xl border border-rose-200 bg-rose-50 p-3 text-xs font-semibold text-rose-700" x-text="modalError"></div>

                <div>
                    <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-[#4A3B32]">
                        {{ __('Họ và tên người nhận') }} <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" x-model="checkoutForm.customer_name" required
                           placeholder="{{ __('Nhập họ và tên...') }}"
                           class="w-full rounded-xl border border-[#EADBCE] bg-[#FAF5F1]/40 px-3.5 py-2 text-xs font-semibold text-[#2B1E19] focus:border-[#B38352] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#B38352]/20">
                </div>

                <div>
                    <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-[#4A3B32]">
                        {{ __('Số điện thoại nhận hàng') }} <span class="text-rose-500">*</span>
                    </label>
                    <input type="tel" x-model="checkoutForm.phone" required
                           placeholder="{{ __('VD: 0912 345 678') }}"
                           class="w-full rounded-xl border border-[#EADBCE] bg-[#FAF5F1]/40 px-3.5 py-2 text-xs font-semibold text-[#2B1E19] focus:border-[#B38352] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#B38352]/20">
                </div>

                <div>
                    <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-[#4A3B32]">
                        {{ __('Địa chỉ nhận hàng') }} <span class="text-rose-500">*</span>
                    </label>
                    <textarea x-model="checkoutForm.address" required rows="2"
                              placeholder="{{ __('Số nhà, tên đường, phường/xã, quận/huyện...') }}"
                              class="w-full rounded-xl border border-[#EADBCE] bg-[#FAF5F1]/40 px-3.5 py-2 text-xs font-semibold text-[#2B1E19] focus:border-[#B38352] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#B38352]/20"></textarea>
                </div>

                <div>
                    <label class="mb-1 block text-xs font-bold uppercase tracking-wider text-[#4A3B32]">
                        {{ __('Ghi chú cho quán (Tùy chọn)') }}
                    </label>
                    <input type="text" x-model="checkoutForm.note"
                           placeholder="{{ __('VD: Giao trước 12h, ít đá, nhiều đường...') }}"
                           class="w-full rounded-xl border border-[#EADBCE] bg-[#FAF5F1]/40 px-3.5 py-2 text-xs font-semibold text-[#2B1E19] focus:border-[#B38352] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#B38352]/20">
                </div>

                <div class="flex items-center justify-between rounded-xl bg-[#FAF5F1] p-3 text-xs">
                    <span class="font-bold text-[#736357]">{{ __('Tổng tiền thanh toán (COD):') }}</span>
                    <span class="text-sm font-extrabold text-[#B38352]" x-text="formatPrice(total) + '₫'"></span>
                </div>

                <div class="flex items-center justify-end gap-2.5 pt-2">
                    <button type="button" @click="showCheckoutModal = false"
                            class="rounded-xl border border-[#EADBCE] bg-white px-4 py-2.5 text-xs font-bold uppercase tracking-wider text-[#4A3B32] transition hover:bg-[#FAF5F1]">
                        {{ __('Quay lại') }}
                    </button>
                    <button type="submit" :disabled="submittingOrder"
                            class="inline-flex items-center gap-2 rounded-xl bg-[#2B1E19] px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-[#FAF5F1] shadow-md transition hover:bg-[#B38352] disabled:opacity-50">
                        <span x-show="!submittingOrder">{{ __('Xác nhận đặt hàng') }}</span>
                        <span x-show="submittingOrder">{{ __('Đang xử lý...') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>