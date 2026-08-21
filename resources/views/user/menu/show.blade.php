@extends(auth()->check() ? 'layouts.user-app' : 'layouts.user-guest')

@php
    $groupLabels = [
        'size' => __('Kích cỡ'),
        'sugar' => __('Độ ngọt'),
        'ice' => __('Lượng đá'),
        'topping' => __('Topping'),
    ];
    $variantGroups = $product->type->value === 'drink'
        ? $product->variants->groupBy(fn ($variant) => $variant->variant_group->value)
        : collect();
@endphp

@section('content')
<div class="bg-[#FAF5F1] py-10 px-4 sm:px-6 lg:px-8 font-sans" x-data="productDetail()" x-init="init()">
    <div class="mx-auto max-w-6xl">

        <!-- Breadcrumb Navigation -->
        <nav class="mb-6 flex flex-wrap items-center gap-1.5 text-xs text-[#A39284]">
            <a href="{{ route('menu.index') }}" class="transition hover:text-[#B38352]">{{ __('Thực đơn') }}</a>
            @if ($product->category)
                <span>/</span>
                <a href="{{ route('menu.index') }}?category_id={{ $product->category_id }}" class="transition hover:text-[#B38352]">{{ $product->category->name }}</a>
            @endif
            <span>/</span>
            <span class="font-semibold text-[#2B1E19]">{{ $product->name }}</span>
        </nav>

        <div class="grid grid-cols-1 gap-10 lg:grid-cols-2">
            <!-- GALLERY PARTIAL -->
            @include('user.menu.partials.product-gallery')

            <!-- INFO PARTIAL -->
            @include('user.menu.partials.product-info')
        </div>

        <!-- FULL DESCRIPTION PARTIAL -->
        @include('user.menu.partials.product-description')

        <!-- CUSTOMER RATINGS PARTIAL -->
        @include('user.menu.partials.product-ratings')

        <!-- SẢN PHẨM LIÊN QUAN -->
        @if ($relatedProducts->isNotEmpty())
            <div class="mt-12 font-sans">
                <h2 class="mb-5 font-sans text-xl font-bold text-[#2B1E19]">{{ __('Có thể bạn cũng thích') }}</h2>
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                    @foreach ($relatedProducts as $related)
                        <a href="{{ route('menu.show', $related) }}" class="group overflow-hidden rounded-2xl border border-[#EADBCE] bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                            <div class="aspect-square overflow-hidden bg-[#FAF5F1]">
                                @if ($related->primaryImage)
                                    <img src="{{ asset('storage/'.$related->primaryImage->image_path) }}" alt="{{ $related->name }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                                @endif
                            </div>
                            <div class="p-3">
                                <p class="truncate text-xs font-bold text-[#2B1E19]">{{ $related->name }}</p>
                                <p class="mt-1 text-xs font-bold text-[#B38352]">{{ number_format((float) $related->price, 0, ',', '.') }}₫</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

<script>
function productDetail() {
    return {
        quantity: 1,
        selectedVariantId: null,
        selectedToppings: [], // Lưu danh sách ID topping được chọn
        basePrice: {{ (float) $product->price }},
        adding: false,
        cartMessage: '',
        cartSuccess: false,
        newRating: { rating: 0, comment: '' },
        submittingRating: false,
        ratingMessage: '',
        ratingSuccess: false,
        linkCopied: false,

        init() {
            this.recalculatePrice();
        },

        // Xử lý bật/tắt (Toggle) Topping
        toggleTopping(id, extraPrice) {
            const index = this.selectedToppings.indexOf(id);
            if (index > -1) {
                this.selectedToppings.splice(index, 1); // Đã chọn -> Bỏ chọn (thành màu trắng)
            } else {
                this.selectedToppings.push(id); // Chưa chọn -> Chọn (thành màu nâu)
            }
            this.recalculatePrice();
        },

        updateVariant(group, id) {
            if (this.selectedVariantId === null || group === 'size') {
                this.selectedVariantId = id;
            }
            this.recalculatePrice();
        },

        recalculatePrice() {
            // Tính giá phụ thu từ các radio (Size, Đá, Đường...)
            let extras = Array.from(document.querySelectorAll('input[type=radio][data-extra]:checked'))
                .reduce((sum, el) => sum + parseFloat(el.dataset.extra || 0), 0);

            // Cộng thêm phụ thu từ các Topping đang được chọn
            @if ($variantGroups->has('topping'))
                const toppingPrices = @js($variantGroups->get('topping')->pluck('extra_price', 'id'));
                this.selectedToppings.forEach(id => {
                    extras += parseFloat(toppingPrices[id] || 0);
                });
            @endif

            const total = this.basePrice + extras;
            const priceEl = document.getElementById('displayPrice');
            if (priceEl) {
                priceEl.textContent = new Intl.NumberFormat('vi-VN').format(total) + '₫';
            }
        },

        async addToCart(productId) {
            this.adding = true;
            this.cartMessage = '';
            try {
                const res = await fetch('{{ url('/cart/items') }}', { // Đổi sang /cart/items
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        product_variant_id: this.selectedVariantId || null,
                        quantity: this.quantity,
                    }),
                });

                if (res.status === 401) {
                    window.location.href = '{{ route('login') }}';
                    return;
                }

                const json = await res.json().catch(() => ({}));

                if (!res.ok) {
                    throw new Error(json.message || Object.values(json.errors ?? {}).flat()[0] || '{{ __('Không thể thêm vào giỏ hàng.') }}');
                }

                this.cartSuccess = true;
                this.cartMessage = '{{ __('Đã thêm vào giỏ hàng thành công!') }}';
                
                // Cập nhật sự kiện giỏ hàng cho Header (nếu có component Alpine đón nhận)
                window.dispatchEvent(new CustomEvent('cart-updated', { detail: json }));

            } catch (e) {
                this.cartSuccess = false;
                this.cartMessage = e.message || '{{ __('Có lỗi xảy ra, vui lòng thử lại.') }}';
            } finally {
                this.adding = false;
                setTimeout(() => { this.cartMessage = ''; }, 3500);
            }
        },

        async submitRating(productId) {
            this.submittingRating = true;
            this.ratingMessage = '';
            try {
                const res = await fetch(`{{ url('/products') }}/${productId}/ratings`, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ 
                        rating: this.newRating.rating, 
                        comment: this.newRating.comment || null 
                    }),
                });

                const json = await res.json().catch(() => ({}));

                if (res.status === 401) {
                    window.location.href = '{{ route('login') }}';
                    return;
                }

                if (res.status === 403) {
                    throw new Error(json.message || '{{ __('Bạn không có quyền thực hiện đánh giá này.') }}');
                }
                if (res.status === 422) {
                    throw new Error(Object.values(json.errors ?? {}).flat()[0] || '{{ __('Dữ liệu không hợp lệ.') }}');
                }
                if (!res.ok) {
                    throw new Error(json.message || '{{ __('Không thể gửi đánh giá.') }}');
                }

                this.ratingSuccess = true;
                this.ratingMessage = '{{ __('Cảm ơn bạn đã đánh giá! Đang tải lại trang...') }}';
                setTimeout(() => window.location.reload(), 1200);
            } catch (e) {
                this.ratingSuccess = false;
                this.ratingMessage = e.message;
            } finally {
                this.submittingRating = false;
            }
        },

        copyLink() {
            navigator.clipboard.writeText(window.location.href).then(() => {
                this.linkCopied = true;
                setTimeout(() => { this.linkCopied = false; }, 2000);
            });
        },
    };
}
</script>
@endsection