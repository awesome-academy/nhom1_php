<div class="h-fit space-y-4 rounded-[28px] border border-[#EADBCE] bg-white/95 p-7 font-sans shadow-[0_20px_50px_rgba(43,30,25,0.06)] lg:sticky lg:top-24">
    <h2 class="text-xl font-bold tracking-tight text-[#2B1E19]">{{ __('Tóm tắt đơn hàng') }}</h2>

    <div class="space-y-2 border-t border-dashed border-[#EADBCE] pt-4 text-sm">
        <div class="flex justify-between text-[#736357]">
            <span>{{ __('Số lượng món') }}</span>
            <span class="font-semibold text-[#2B1E19]" x-text="itemCount"></span>
        </div>
        <div class="flex justify-between text-[#736357]">
            <span>{{ __('Tạm tính') }}</span>
            <span class="font-semibold text-[#2B1E19]" x-text="formatPrice(total) + '₫'"></span>
        </div>
    </div>

    <div class="flex justify-between border-t border-[#EADBCE] pt-4 text-base font-bold text-[#2B1E19]">
        <span>{{ __('Tổng cộng') }}</span>
        <span x-text="formatPrice(total) + '₫'"></span>
    </div>

    <button type="button" @click="openCheckoutModal()" :disabled="items.length === 0"
            class="mt-2 flex w-full items-center justify-center gap-2 rounded-2xl bg-[#2B1E19] py-3.5 text-xs font-bold uppercase tracking-wider text-[#FAF5F1] shadow-md transition hover:bg-[#B38352] disabled:cursor-not-allowed disabled:opacity-50">
        <span>{{ __('Đặt hàng ngay') }}</span>
    </button>

    <a href="{{ route('orders.index') }}" class="block text-center text-xs font-semibold text-[#B38352] hover:underline">
        {{ __('Xem lịch sử đơn hàng') }}
    </a>
</div>