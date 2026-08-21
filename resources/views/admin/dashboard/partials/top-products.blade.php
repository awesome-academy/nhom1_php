<div class="rounded-2xl border border-[#EADBCE]/80 bg-white p-5 shadow-xs">
    <div class="flex items-center justify-between border-b border-gray-100 pb-3">
        <h2 class="text-sm font-bold text-[#2B1E19]">{{ __('Món bán chạy (Top Sellers)') }}</h2>
        <span class="text-[11px] font-bold text-amber-700">★ {{ __('Doanh thu cao') }}</span>
    </div>

    <div class="mt-3 divide-y divide-gray-100">
        @forelse ($topProducts as $index => $item)
            <div class="flex items-center justify-between py-2.5 text-xs">
                <div class="flex items-center gap-2.5">
                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-[#FAF5F1] font-bold text-[#B38352] text-xs">
                        {{ $index + 1 }}
                    </span>
                    <div>
                        <p class="font-bold text-[#2B1E19] max-w-[140px] truncate" title="{{ $item->product_name }}">
                            {{ $item->product_name }}
                        </p>
                        <p class="text-[10px] text-gray-400">
                            {{ number_format((float) $item->total_earned, 0, ',', '.') }}₫
                        </p>
                    </div>
                </div>
                <span class="font-bold text-[#736357]">{{ $item->total_sold }} {{ __('phần') }}</span>
            </div>
        @empty
            <div class="py-6 text-center text-xs text-gray-400">
                {{ __('Chưa có dữ liệu bán hàng hoàn tất.') }}
            </div>
        @endforelse
    </div>
</div>