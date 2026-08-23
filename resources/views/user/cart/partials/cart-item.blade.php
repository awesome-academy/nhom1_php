<div class="space-y-4 font-sans">
    {{-- Giỏ hàng rỗng --}}
    <div x-show="items.length === 0" class="rounded-[28px] border border-dashed border-[#EADBCE] bg-white/70 p-14 text-center">
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-[#FAF5F1] text-[#B38352]">
            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
        </div>
        <p class="mt-4 text-xl font-bold text-[#2B1E19]">{{ __('Giỏ hàng đang trống') }}</p>
        <p class="mt-1.5 text-sm text-[#A39284]">{{ __('Hãy khám phá thực đơn và thêm món yêu thích vào giỏ hàng nhé.') }}</p>
        <a href="{{ route('menu.index') }}"
           class="mt-6 inline-flex items-center gap-2 rounded-2xl bg-[#2B1E19] px-6 py-3 text-xs font-bold uppercase tracking-wider text-[#FAF5F1] shadow-md transition hover:bg-[#B38352]">
            {{ __('Xem thực đơn') }}
        </a>
    </div>

    {{-- Danh sách item --}}
    <template x-for="item in items" :key="item.id">
        <div class="flex flex-col gap-4 rounded-[24px] border border-[#EADBCE] bg-white/95 p-5 shadow-sm sm:flex-row sm:items-center">
            <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-[#FAF5F1] text-[#B38352] overflow-hidden">
                <template x-if="item.image_url || (item.product && item.product.image_url)">
                    <img :src="item.image_url || (item.product && item.product.image_url)" 
                         :alt="getItemName(item)"
                         class="h-full w-full object-cover rounded-2xl">
                </template>
                
                <template x-if="!item.image_url && !(item.product && item.product.image_url)">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M4 6h16v12H4V6z" />
                    </svg>
                </template>
            </div>

            <div class="flex-1">
                <p class="text-sm font-bold text-[#2B1E19]" x-text="getItemName(item)"></p>
                <p x-show="item.variant_name || (item.variant && item.variant.name)" class="mt-0.5 text-xs text-[#A39284]" x-text="item.variant_name || (item.variant && item.variant.name)"></p>
                <p class="mt-1 text-xs font-semibold text-[#B38352]" x-text="formatPrice(getUnitPrice(item)) + '₫ / món'"></p>
            </div>

            <div class="flex items-center gap-3">
                <div class="flex items-center rounded-xl border border-[#EADBCE] bg-white">
                    <button type="button" 
                            @click="updateQuantity(item, Number(item.quantity) - 1)"
                            :disabled="!!updating[item.id] || Number(item.quantity) <= 1"
                            class="px-3 py-2 text-[#736357] transition hover:text-[#B38352] disabled:cursor-not-allowed disabled:opacity-40">
                        −</button>
                    
                    <span class="w-8 text-center text-sm font-bold text-[#2B1E19]" x-text="item.quantity"></span>
                    
                    <button type="button" 
                            @click="updateQuantity(item, Number(item.quantity) + 1)"
                            :disabled="!!updating[item.id]"
                            class="px-3 py-2 text-[#736357] transition hover:text-[#B38352] disabled:cursor-not-allowed disabled:opacity-40">
                        +</button>
                </div>

                <p class="w-24 text-right text-sm font-bold text-[#2B1E19]" x-text="formatPrice(getLineTotal(item)) + '₫'"></p>

                <button type="button" 
                        @click="removeItem(item)" 
                        :disabled="!!removing[item.id]"
                        class="flex h-9 w-9 items-center justify-center rounded-xl border border-transparent text-red-500 transition hover:border-red-200 hover:bg-red-50 disabled:cursor-not-allowed disabled:opacity-40"
                        title="{{ __('Xoá món') }}">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>
        </div>
    </template>

    <div x-show="items.length > 0" class="pt-2">
        <a href="{{ route('menu.index') }}"
           class="inline-flex items-center gap-2 rounded-2xl border border-dashed border-[#B38352] px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-[#B38352] transition hover:bg-amber-50/50">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
            {{ __('Thêm món khác') }}
        </a>
    </div>
</div>