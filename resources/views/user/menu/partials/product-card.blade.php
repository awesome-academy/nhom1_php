<a :href="`{{ url('/menu') }}/${product.slug}`"
   class="group flex flex-col overflow-hidden rounded-[22px] border border-[#EADBCE] bg-white shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-[0_20px_40px_rgba(43,30,25,0.10)] font-sans">
    <div class="relative aspect-[4/3] overflow-hidden bg-[#FAF5F1]">
        <template x-if="imageUrl(product)">
            <img :src="imageUrl(product)" :alt="product.name" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
        </template>
        <template x-if="!imageUrl(product)">
            <div class="flex h-full w-full items-center justify-center text-[#C7B199]">
                <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M4 6h16v12H4V6z" />
                </svg>
            </div>
        </template>

        <!-- Tag loại sản phẩm: Đồ uống = Màu xanh dương -->
        <span class="absolute left-3 top-3 rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wide shadow-sm"
              :class="product.type === 'drink' ? 'bg-blue-600 text-white' : 'bg-[#B38352] text-white'"
              x-text="product.type === 'drink' ? '{{ __('Đồ uống') }}' : '{{ __('Đồ ăn') }}'"></span>

        <template x-if="product.stock_quantity <= 0">
            <span class="absolute right-3 top-3 rounded-full bg-red-600/90 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wide text-white shadow-sm">
                {{ __('Hết hàng') }}
            </span>
        </template>
    </div>
    <div class="flex flex-1 flex-col gap-1.5 p-4">
        <span class="text-[11px] font-bold uppercase tracking-wide text-[#B38352]" x-text="product.category?.name ?? ''"></span>
        <h3 class="font-sans text-sm font-bold leading-snug text-[#2B1E19] line-clamp-2" x-text="product.name"></h3>
        <div class="mt-auto flex items-center justify-between pt-2">
            <div class="flex items-center gap-1 text-xs text-[#736357]">
                <svg class="h-3.5 w-3.5 fill-current text-[#B38352]" viewBox="0 0 20 20">
                    <path d="M10 15l-5.878 3.09L5.64 11.545.762 7.41l6.09-.885L10 1l3.148 5.525 6.09.885-4.878 4.135 1.518 6.545z"/>
                </svg>
                <span x-text="Number(product.rating || 0).toFixed(1)"></span>
                <span class="text-[#A39284]" x-text="`(${product.rating_count ?? 0})`"></span>
            </div>
            <span class="font-sans text-base font-bold text-[#2B1E19]" x-text="formatPrice(product.price) + '₫'"></span>
        </div>
    </div>
</a>