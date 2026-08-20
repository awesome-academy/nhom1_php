<div class="space-y-5 font-sans">
    <div>
        <span class="text-[11px] font-bold uppercase tracking-wide text-[#B38352]">{{ $product->category->name ?? '' }}</span>
        <h1 class="mt-1 font-sans text-3xl font-bold leading-tight text-[#2B1E19]">{{ $product->name }}</h1>

        <div class="mt-2 flex items-center gap-2">
            <div class="flex items-center gap-0.5">
                @for ($i = 1; $i <= 5; $i++)
                    <svg class="h-4 w-4 fill-current {{ $i <= round($product->ratings_avg_rating ?? 0) ? 'text-[#B38352]' : 'text-gray-300' }}" viewBox="0 0 20 20">
                        <path d="M10 15l-5.878 3.09L5.64 11.545.762 7.41l6.09-.885L10 1l3.148 5.525 6.09.885-4.878 4.135 1.518 6.545z"/>
                    </svg>
                @endfor
            </div>
            <span class="text-sm font-semibold text-[#2B1E19]">{{ number_format((float) ($product->ratings_avg_rating ?? 0), 1) }}</span>
            <span class="text-xs text-[#A39284]">({{ $product->ratings_count ?? 0 }} {{ __('đánh giá') }})</span>
        </div>
    </div>

    <p id="displayPrice" class="font-sans text-3xl font-bold text-[#2B1E19]">
        {{ number_format((float) $product->price, 0, ',', '.') }}₫
    </p>

    @if ($product->summary)
        <p class="text-sm leading-relaxed text-[#736357]">{{ $product->summary }}</p>
    @endif

    <!-- Hiển thị Trạng thái tồn kho-->
    <div>
        @if ($product->stock_quantity > 0)
            <div class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3.5 py-1.5 text-xs font-bold text-emerald-700 ring-1 ring-emerald-200/70 shadow-sm">
                <span class="relative flex h-2 w-2">
                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                </span>
                <span>{{ __('Còn hàng') }} - {{ __('còn') }} <b class="font-extrabold text-emerald-800">{{ $product->stock_quantity }}</b> {{ __('sản phẩm') }}</span>
            </div>
        @else
            <div class="inline-flex items-center gap-2 rounded-full bg-rose-50 px-3.5 py-1.5 text-xs font-bold text-rose-700 ring-1 ring-rose-200/70 shadow-sm">
                <span class="h-2 w-2 rounded-full bg-rose-500"></span>
                <span>{{ __('Tạm hết hàng') }}</span>
            </div>
        @endif
    </div>

    <!-- Tùy chọn đồ uống (Topping bật/tắt Toggle độc lập) -->
    @if ($variantGroups->isNotEmpty())
        <div class="space-y-4 border-t border-[#EADBCE]/70 pt-5">
            <h3 class="text-xs font-bold uppercase tracking-wider text-[#4A3B32]">{{ __('Tuỳ chọn đồ uống') }}</h3>

            @foreach ($variantGroups as $groupKey => $groupVariants)
                <div>
                    <p class="mb-2 text-xs font-semibold text-[#736357]">{{ $groupLabels[$groupKey] ?? ucfirst($groupKey) }}</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($groupVariants as $variant)
                            @if ($groupKey === 'topping')
                                <!-- Topping: Nút Toggle chọn / bỏ chọn độc lập -->
                                <button type="button"
                                        @click="toggleTopping({{ $variant->id }}, {{ (float)$variant->extra_price }})"
                                        class="rounded-xl border px-3.5 py-2 text-xs font-semibold transition cursor-pointer"
                                        :class="selectedToppings.includes({{ $variant->id }})
                                            ? 'border-[#B38352] bg-[#B38352] text-white shadow-sm'
                                            : 'border-[#EADBCE] bg-white text-[#4A3B32] hover:border-[#B38352]/60'">
                                    {{ $variant->name }}
                                    @if ($variant->extra_price > 0)
                                        <span :class="selectedToppings.includes({{ $variant->id }}) ? 'text-white/90' : 'text-[#B38352]'">
                                            (+{{ number_format((float) $variant->extra_price, 0, ',', '.') }}đ)
                                        </span>
                                    @endif
                                </button>
                            @else
                                <!-- Các nhóm còn lại (Size, Đường, Đá): Radio chọn 1 -->
                                <label class="cursor-pointer">
                                    <input type="radio"
                                           name="variant_{{ $groupKey }}"
                                           value="{{ $variant->id }}"
                                           data-group="{{ $groupKey }}"
                                           data-extra="{{ $variant->extra_price }}"
                                           class="peer sr-only"
                                           @change="updateVariant('{{ $groupKey }}', {{ $variant->id }})"
                                           {{ $loop->first ? 'checked' : '' }}>
                                    <span class="block rounded-xl border border-[#EADBCE] bg-white px-3.5 py-2 text-xs font-semibold text-[#4A3B32] transition peer-checked:border-[#B38352] peer-checked:bg-[#B38352] peer-checked:text-white">
                                        {{ $variant->name }}
                                        @if ($variant->extra_price > 0)
                                            <span class="ml-1 opacity-80">(+{{ number_format((float) $variant->extra_price, 0, ',', '.') }}đ)</span>
                                        @endif
                                    </span>
                                </label>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Số lượng + Thêm vào giỏ -->
    <div class="flex items-center gap-4 border-t border-[#EADBCE]/70 pt-5">
        <div class="flex items-center rounded-xl border border-[#EADBCE] bg-white">
            <button type="button" @click="quantity > 1 && quantity--" class="px-3 py-2.5 text-[#736357] transition hover:text-[#B38352]">−</button>
            <span class="w-8 text-center text-sm font-bold text-[#2B1E19]" x-text="quantity"></span>
            <button type="button" @click="quantity++" class="px-3 py-2.5 text-[#736357] transition hover:text-[#B38352]">+</button>
        </div>
        <button type="button" @click="addToCart({{ $product->id }})" :disabled="adding || {{ $product->stock_quantity > 0 ? 'false' : 'true' }}"
                class="flex-1 rounded-xl bg-[#2B1E19] py-3 text-sm font-bold uppercase tracking-wider text-[#FAF5F1] shadow-md transition hover:bg-[#B38352] disabled:cursor-not-allowed disabled:opacity-50">
            <span x-show="!adding">{{ $product->stock_quantity > 0 ? __('Thêm vào giỏ hàng') : __('Tạm hết hàng') }}</span>
            <span x-show="adding">{{ __('Đang thêm...') }}</span>
        </button>
    </div>
    <p x-show="cartMessage" x-text="cartMessage" class="text-xs font-semibold" :class="cartSuccess ? 'text-green-600' : 'text-red-600'"></p>

    <!-- Chia sẻ mạng xã hội -->
    <div class="flex items-center gap-2.5 border-t border-[#EADBCE]/70 pt-5">
        <span class="text-xs font-bold uppercase tracking-wider text-[#4A3B32]">{{ __('Chia sẻ') }}:</span>

        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" rel="noopener"
           class="flex h-8 w-8 items-center justify-center rounded-full border border-[#EADBCE] text-[#736357] transition hover:border-[#B38352] hover:text-[#B38352]" title="Facebook">
            <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24">
                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
            </svg>
        </a>

        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($product->name) }}" target="_blank" rel="noopener"
           class="flex h-8 w-8 items-center justify-center rounded-full border border-[#EADBCE] text-[#736357] transition hover:border-[#B38352] hover:text-[#B38352]" title="X (Twitter)">
            <svg class="h-3.5 w-3.5 fill-current" viewBox="0 0 24 24">
                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
            </svg>
        </a>

        <a href="https://api.whatsapp.com/send?text={{ urlencode($product->name.' - '.url()->current()) }}" target="_blank" rel="noopener"
           class="flex h-8 w-8 items-center justify-center rounded-full border border-[#EADBCE] text-[#736357] transition hover:border-[#B38352] hover:text-[#B38352]" title="WhatsApp">
            <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24">
                <path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2.05 22l5.29-1.39a9.87 9.87 0 004.7 1.2h.01c5.46 0 9.9-4.45 9.9-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0012.04 2m0 1.67c2.2 0 4.26.86 5.82 2.42a8.19 8.19 0 012.41 5.82c0 4.54-3.7 8.23-8.24 8.23a8.2 8.2 0 01-4.19-1.15l-.3-.18-3.14.82.84-3.06-.2-.32a8.18 8.18 0 01-1.26-4.38c0-4.54 3.7-8.2 8.26-8.2M8.53 6.85c-.16 0-.43.06-.65.31s-.86.84-.86 2.05.88 2.38 1 2.55c.13.16 1.72 2.7 4.24 3.68 2.1.82 2.52.66 2.98.62.46-.05 1.48-.6 1.69-1.19s.21-1.09.15-1.19c-.06-.11-.23-.17-.48-.29-.25-.13-1.48-.73-1.71-.82s-.4-.13-.56.13c-.17.25-.65.82-.79.99s-.29.19-.54.06c-.25-.13-1.05-.39-2-1.24a7.55 7.55 0 01-1.4-1.73c-.14-.25-.02-.39.11-.51.11-.11.25-.29.37-.44.13-.14.17-.25.25-.41.08-.17.04-.31-.02-.44s-.56-1.36-.78-1.86c-.2-.49-.41-.42-.56-.43H8.53z"/>
            </svg>
        </a>

        <button type="button" @click="copyLink()"
                class="flex h-8 w-8 items-center justify-center rounded-full border border-[#EADBCE] text-[#736357] transition hover:border-[#B38352] hover:text-[#B38352]" title="{{ __('Sao chép liên kết') }}">
            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 010 5.656l-3 3a4 4 0 01-5.656-5.656l1.5-1.5M10.172 13.828a4 4 0 010-5.656l3-3a4 4 0 015.656 5.656l-1.5 1.5"/>
            </svg>
        </button>
        <span x-show="linkCopied" x-cloak class="text-xs font-semibold text-green-600">{{ __('Đã sao chép!') }}</span>
    </div>
</div>