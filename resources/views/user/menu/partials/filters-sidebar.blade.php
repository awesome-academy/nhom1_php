<aside class="space-y-5 lg:sticky lg:top-24 lg:self-start">
    <div class="rounded-[24px] border border-[#EADBCE] bg-white/95 p-5 shadow-sm backdrop-blur-sm font-sans">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="font-sans text-base font-bold text-[#2B1E19]">{{ __('Bộ lọc') }}</h2>
            <button type="button" @click="resetFilters()" class="text-xs font-semibold text-[#B38352] transition hover:text-[#8E6238] hover:underline">
                {{ __('Đặt lại') }}
            </button>
        </div>

        <!-- Loại sản phẩm -->
        <div class="border-t border-[#EADBCE]/70 pt-4">
            <h3 class="mb-2 text-xs font-bold uppercase tracking-wider text-[#4A3B32]">{{ __('Loại sản phẩm') }}</h3>
            <div class="space-y-1.5">
                <label class="flex cursor-pointer items-center gap-2">
                    <input type="radio" name="type" value="" x-model="filters.type" @change="fetchProducts(1)"
                           class="h-3.5 w-3.5 border-[#EADBCE] text-[#B38352] focus:ring-[#B38352]/30">
                    <span class="text-xs" :class="filters.type === '' ? 'font-bold text-[#B38352]' : 'text-[#736357]'">{{ __('Tất cả') }}</span>
                </label>
                <label class="flex cursor-pointer items-center gap-2">
                    <input type="radio" name="type" value="drink" x-model="filters.type" @change="fetchProducts(1)"
                           class="h-3.5 w-3.5 border-[#EADBCE] text-[#B38352] focus:ring-[#B38352]/30">
                    <span class="text-xs" :class="filters.type === 'drink' ? 'font-bold text-[#B38352]' : 'text-[#736357]'">{{ __('Đồ uống') }} ☕</span>
                </label>
                <label class="flex cursor-pointer items-center gap-2">
                    <input type="radio" name="type" value="food" x-model="filters.type" @change="fetchProducts(1)"
                           class="h-3.5 w-3.5 border-[#EADBCE] text-[#B38352] focus:ring-[#B38352]/30">
                    <span class="text-xs" :class="filters.type === 'food' ? 'font-bold text-[#B38352]' : 'text-[#736357]'">{{ __('Đồ ăn') }} 🥐</span>
                </label>
            </div>
        </div>

        <!-- Danh mục -->
        <div class="mt-4 border-t border-[#EADBCE]/70 pt-4">
            <h3 class="mb-2 text-xs font-bold uppercase tracking-wider text-[#4A3B32]">{{ __('Danh mục') }}</h3>
            <div class="max-h-72 space-y-2 overflow-y-auto pr-1">
                <label class="flex cursor-pointer items-center gap-2">
                    <input type="radio" name="category" value="" x-model="filters.category_id" @change="fetchProducts(1)"
                           class="h-3.5 w-3.5 border-[#EADBCE] text-[#B38352] focus:ring-[#B38352]/30">
                    <span class="text-xs" :class="filters.category_id === '' ? 'font-bold text-[#B38352]' : 'text-[#736357]'">{{ __('Tất cả danh mục') }}</span>
                </label>
                <template x-for="cat in categories" :key="cat.id">
                    <div>
                        <label class="flex cursor-pointer items-center gap-2">
                            <input type="radio" name="category" :value="cat.id" x-model.number="filters.category_id" @change="fetchProducts(1)"
                                   class="h-3.5 w-3.5 border-[#EADBCE] text-[#B38352] focus:ring-[#B38352]/30">
                            <span class="text-xs font-semibold" :class="filters.category_id === cat.id ? 'text-[#B38352]' : 'text-[#2B1E19]'" x-text="cat.name"></span>
                        </label>
                        <template x-for="child in cat.children" :key="child.id">
                            <label class="ml-5 mt-1 flex cursor-pointer items-center gap-2">
                                <input type="radio" name="category" :value="child.id" x-model.number="filters.category_id" @change="fetchProducts(1)"
                                       class="h-3 w-3 border-[#EADBCE] text-[#B38352] focus:ring-[#B38352]/30">
                                <span class="text-xs" :class="filters.category_id === child.id ? 'font-bold text-[#B38352]' : 'text-[#736357]'" x-text="child.name"></span>
                            </label>
                        </template>
                    </div>
                </template>
            </div>
        </div>

        <!-- Khoảng giá -->
        <div class="mt-4 border-t border-[#EADBCE]/70 pt-4">
            <h3 class="mb-2 text-xs font-bold uppercase tracking-wider text-[#4A3B32]">{{ __('Khoảng giá') }}</h3>
            <div class="space-y-1.5">
                <label class="flex cursor-pointer items-center gap-2">
                    <input type="radio" name="price_range" value="" x-model="filters.price_range" @change="fetchProducts(1)"
                           class="h-3.5 w-3.5 border-[#EADBCE] text-[#B38352] focus:ring-[#B38352]/30">
                    <span class="text-xs" :class="filters.price_range === '' ? 'font-bold text-[#B38352]' : 'text-[#736357]'">{{ __('Tất cả mức giá') }}</span>
                </label>
                <template x-for="range in priceRanges" :key="range.label">
                    <label class="flex cursor-pointer items-center gap-2">
                        <input type="radio" name="price_range" :value="range.label" x-model="filters.price_range" @change="fetchProducts(1)"
                               class="h-3.5 w-3.5 border-[#EADBCE] text-[#B38352] focus:ring-[#B38352]/30">
                        <span class="text-xs" :class="filters.price_range === range.label ? 'font-bold text-[#B38352]' : 'text-[#736357]'" x-text="range.label"></span>
                    </label>
                </template>
            </div>
        </div>

        <!-- Đánh giá -->
        <div class="mt-4 border-t border-[#EADBCE]/70 pt-4">
            <h3 class="mb-2 text-xs font-bold uppercase tracking-wider text-[#4A3B32]">{{ __('Đánh giá') }}</h3>
            <div class="space-y-1.5">
                <label class="flex cursor-pointer items-center gap-2">
                    <input type="radio" name="min_rating" value="" x-model="filters.min_rating" @change="fetchProducts(1)"
                           class="h-3.5 w-3.5 border-[#EADBCE] text-[#B38352] focus:ring-[#B38352]/30">
                    <span class="text-xs" :class="filters.min_rating === '' ? 'font-bold text-[#B38352]' : 'text-[#736357]'">{{ __('Tất cả đánh giá') }}</span>
                </label>
                <template x-for="star in [5, 4, 3, 2]" :key="star">
                    <label class="flex cursor-pointer items-center gap-1.5">
                        <input type="radio" name="min_rating" :value="star" x-model.number="filters.min_rating" @change="fetchProducts(1)"
                               class="h-3.5 w-3.5 border-[#EADBCE] text-[#B38352] focus:ring-[#B38352]/30">
                        <span class="flex items-center gap-0.5">
                            <template x-for="i in 5" :key="i">
                                <svg class="h-3 w-3 fill-current" :class="i <= star ? 'text-[#B38352]' : 'text-gray-300'" viewBox="0 0 20 20">
                                    <path d="M10 15l-5.878 3.09L5.64 11.545.762 7.41l6.09-.885L10 1l3.148 5.525 6.09.885-4.878 4.135 1.518 6.545z"/>
                                </svg>
                            </template>
                            <span class="ml-1 text-xs text-[#736357]">{{ __('trở lên') }}</span>
                        </span>
                    </label>
                </template>
            </div>
        </div>
    </div>
</aside>