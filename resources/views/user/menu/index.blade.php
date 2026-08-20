@extends(auth()->check() ? 'layouts.user-app' : 'layouts.user-guest')

@section('content')
<div class="relative min-h-screen overflow-hidden bg-[#FAF5F1] py-10 px-4 sm:px-6 lg:px-8 font-sans"
     style="background:
        radial-gradient(ellipse 900px 600px at 15% -10%, #FFFFFF 0%, rgba(255,255,255,0) 60%),
        linear-gradient(160deg, #FFFFFF 0%, #FFFBF6 38%, #FAF5F1 62%, #F3E7D8 84%, #EADBCE 100%);"
     x-data="menuShop()" x-init="init()">

    <div class="relative mx-auto max-w-7xl">

        <!-- Banner Header giữa nổi bật -->
        <div class="relative mb-10 overflow-hidden rounded-[28px] bg-[#18110e] px-6 py-10 text-center text-white shadow-xl ring-1 ring-[#B38352]/30">
            <div class="pointer-events-none absolute -top-12 left-1/2 h-40 w-96 -translate-x-1/2 rounded-full bg-[#B38352]/20 blur-3xl"></div>
            
            <div class="relative z-10 mx-auto flex max-w-2xl flex-col items-center gap-3">
                <span class="inline-flex items-center gap-2 rounded-full border border-[#B38352]/40 bg-[#B38352]/10 px-4 py-1 text-xs font-bold uppercase tracking-[0.25em] text-[#C7B199]">
                    <span>☕</span> Brew &amp; Bite Artisan
                </span>
                <h1 class="font-sans text-3xl font-extrabold tracking-tight text-white sm:text-4xl lg:text-5xl">
                    {{ __('Thực đơn') }}
                </h1>
                <div class="h-1 w-16 rounded-full bg-[#B38352]"></div>
                <p class="text-sm leading-relaxed text-gray-300 font-sans max-w-lg">
                    {{ __('Khám phá trọn vẹn hương vị cà phê, trà và bánh thủ công được chuẩn bị tươi mới mỗi ngày.') }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-[280px_1fr]">

            <!-- SIDEBAR bộ lọc -->
            @include('user.menu.partials.filters-sidebar')

            <!-- MAIN: TOPBAR + GRID -->
            <div>
                <!-- Top bar: search + sort (Đã có Z-A) -->
                <div class="mb-6 flex flex-col gap-3 rounded-[20px] border border-[#EADBCE] bg-white/95 p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between">
                    <div class="relative w-full max-w-md">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-[#A39284]">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" x-model="filters.search" @input.debounce.400ms="fetchProducts(1)"
                               placeholder="{{ __('Tìm món ăn, thức uống...') }}"
                               class="block w-full rounded-xl border border-[#EADBCE] bg-[#FAF5F1]/60 py-2.5 pl-10 pr-4 text-sm text-[#2B1E19] placeholder-[#A39284] focus:border-[#B38352] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#B38352]/15">
                    </div>

                    <div class="flex items-center justify-between gap-3 sm:justify-end">
                        <span class="hidden text-xs text-[#A39284] sm:inline" x-show="!loading">
                            {{ __('Hiển thị') }} <b class="text-[#2B1E19]" x-text="products.length"></b> / <b class="text-[#2B1E19]" x-text="meta.total"></b> {{ __('sản phẩm') }}
                        </span>
                        <div class="flex items-center gap-2">
                            <label class="text-xs font-semibold text-[#736357]">{{ __('Sắp xếp') }}:</label>
                            <select x-model="filters.sort" @change="fetchProducts(1)"
                                    class="rounded-xl border border-[#EADBCE] bg-[#FAF5F1]/60 py-2 pl-3 pr-8 text-xs font-medium text-[#2B1E19] focus:border-[#B38352] focus:outline-none focus:ring-4 focus:ring-[#B38352]/15">
                                <option value="rating_desc">{{ __('Phổ biến nhất') }}</option>
                                <option value="newest">{{ __('Mới thêm') }}</option>
                                <option value="price_asc">{{ __('Giá tăng dần') }}</option>
                                <option value="price_desc">{{ __('Giá giảm dần') }}</option>
                                <option value="name_asc">{{ __('Tên A - Z') }}</option>
                                <option value="name_desc">{{ __('Tên Z - A') }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Loading skeleton -->
                <div x-show="loading" class="rounded-[24px] border border-[#EADBCE] bg-white/70 py-20 text-center shadow-sm">
                    <div class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-[#B38352] border-r-transparent"></div>
                    <p class="mt-3 text-sm font-medium text-[#736357]">{{ __('Đang tải thực đơn...') }}</p>
                </div>

                <!-- Product grid -->
                <div x-show="!loading" class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">
                    <template x-for="product in products" :key="product.id">
                        @include('user.menu.partials.product-card')
                    </template>
                </div>

                <!-- Empty state -->
                <div x-show="!loading && products.length === 0" class="rounded-[24px] border border-dashed border-[#EADBCE] bg-white/60 p-14 text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-[#FAF5F1] text-[#B38352]">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <p class="mt-3 font-semibold text-[#2B1E19]">{{ __('Không tìm thấy sản phẩm phù hợp') }}</p>
                    <p class="mt-1 text-xs text-[#A39284]">{{ __('Thử thay đổi bộ lọc hoặc từ khoá tìm kiếm của bạn.') }}</p>
                </div>

                <!-- Pagination -->
                <div x-show="!loading && meta.last_page > 1" class="mt-8 flex flex-wrap items-center justify-center gap-2">
                    <button type="button" :disabled="meta.current_page <= 1" @click="fetchProducts(meta.current_page - 1)"
                            class="rounded-xl border border-[#EADBCE] bg-white px-3 py-2 text-xs font-semibold text-[#4A3B32] transition hover:bg-[#FAF5F1] disabled:cursor-not-allowed disabled:opacity-40">
                        &lsaquo;
                    </button>
                    <template x-for="page in pageList()" :key="page">
                        <button type="button" @click="fetchProducts(page)"
                                class="h-9 w-9 rounded-xl text-xs font-bold transition"
                                :class="page === meta.current_page ? 'bg-[#2B1E19] text-[#FAF5F1] shadow-sm' : 'border border-[#EADBCE] bg-white text-[#4A3B32] hover:bg-[#FAF5F1]'"
                                x-text="page"></button>
                    </template>
                    <button type="button" :disabled="meta.current_page >= meta.last_page" @click="fetchProducts(meta.current_page + 1)"
                            class="rounded-xl border border-[#EADBCE] bg-white px-3 py-2 text-xs font-semibold text-[#4A3B32] transition hover:bg-[#FAF5F1] disabled:cursor-not-allowed disabled:opacity-40">
                        &rsaquo;
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function menuShop() {
    return {
        loading: true,
        products: [],
        meta: { current_page: 1, last_page: 1, total: 0 },
        categories: @js($categories),
        priceRanges: [
            { label: '{{ __('Dưới 30.000đ') }}', min: '', max: 30000 },
            { label: '{{ __('30.000đ - 60.000đ') }}', min: 30000, max: 60000 },
            { label: '{{ __('60.000đ - 100.000đ') }}', min: 60000, max: 100000 },
            { label: '{{ __('Trên 100.000đ') }}', min: 100000, max: '' },
        ],
        filters: {
            search: '',
            type: '',
            category_id: '',
            price_range: '',
            min_rating: '',
            sort: 'rating_desc',
        },

        init() {
            this.fetchProducts(1);
        },

        resetFilters() {
            this.filters = { search: '', type: '', category_id: '', price_range: '', min_rating: '', sort: 'rating_desc' };
            this.fetchProducts(1);
        },

        async fetchProducts(page = 1) {
            this.loading = true;
            try {
                const params = new URLSearchParams({ page, per_page: 9, sort: this.filters.sort });

                if (this.filters.search) params.set('search', this.filters.search);
                if (this.filters.type) params.set('type', this.filters.type);
                if (this.filters.category_id !== '') params.set('category_id', this.filters.category_id);
                if (this.filters.min_rating !== '') params.set('min_rating', this.filters.min_rating);

                const range = this.priceRanges.find(r => r.label === this.filters.price_range);
                if (range) {
                    if (range.min !== '') params.set('min_price', range.min);
                    if (range.max !== '') params.set('max_price', range.max);
                }

                const res = await fetch(`{{ url('/api/products') }}?${params.toString()}`, {
                    headers: { Accept: 'application/json' },
                });
                const json = await res.json();

                this.products = json.data ?? [];
                this.meta = json.meta ?? { current_page: 1, last_page: 1, total: 0 };
            } catch (e) {
                this.products = [];
                this.meta = { current_page: 1, last_page: 1, total: 0 };
            } finally {
                this.loading = false;
            }
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

        imageUrl(product) {
            return product.primary_image ? `{{ url('/storage') }}/${product.primary_image.image_path}` : null;
        },

        formatPrice(value) {
            return new Intl.NumberFormat('vi-VN').format(value || 0);
        },
    };
}
</script>
@endsection