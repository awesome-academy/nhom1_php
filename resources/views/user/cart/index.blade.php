@extends('layouts.user-app')

@section('content')
<div class="relative min-h-[calc(100vh-5rem)] overflow-hidden bg-[#FAF5F1] py-12 px-4 sm:px-6 lg:px-8"
     style="background:
        radial-gradient(ellipse 900px 600px at 15% -10%, #FFFFFF 0%, rgba(255,255,255,0) 60%),
        linear-gradient(160deg, #FFFFFF 0%, #FFFBF6 38%, #FAF5F1 62%, #F3E7D8 84%, #EADBCE 100%);"
     x-data="cartPage()" x-init="init()">

    <!-- Hoa văn hạt cà phê đồng bộ style toàn site -->
    <div class="pointer-events-none absolute inset-0 opacity-40"
         style="background-image:url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='120' height='120'%3E%3Cg fill='none' stroke='%23B38352' stroke-width='1.3' opacity='0.16'%3E%3Cellipse cx='30' cy='30' rx='9' ry='15' transform='rotate(24 30 30)'/%3E%3Cpath d='M30 17 Q26 30 30 43' transform='rotate(24 30 30)'/%3E%3Cellipse cx='92' cy='86' rx='9' ry='15' transform='rotate(-18 92 86)'/%3E%3Cpath d='M92 73 Q88 86 92 99' transform='rotate(-18 92 86)'/%3E%3C/g%3E%3C/svg%3E&quot;); background-size:220px 220px;">
    </div>

    <div class="relative mx-auto max-w-6xl">
        <!-- Header -->
        <div class="mb-8 flex flex-col items-start gap-1">
            <span class="font-serif text-[11px] font-semibold tracking-[0.25em] text-[#B38352] uppercase">
                Brew &amp; Bite Artisan
            </span>
            <h1 class="font-serif text-3xl font-bold tracking-tight text-[#2B1E19] sm:text-4xl">
                {{ __('Giỏ hàng của bạn') }}
            </h1>
        </div>

        <!-- Error banner -->
        <div x-show="errorMessage" x-cloak x-transition
             class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-600">
            <span x-text="errorMessage"></span>
        </div>

        <!-- Loading skeleton -->
        <div x-show="loading" class="rounded-[24px] border border-[#EADBCE] bg-white/70 py-20 text-center shadow-sm">
            <div class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-[#B38352] border-r-transparent"></div>
            <p class="mt-3 text-sm font-medium text-[#736357]">{{ __('Đang tải giỏ hàng...') }}</p>
        </div>

        <div x-show="!loading" x-cloak class="grid grid-cols-1 gap-8 lg:grid-cols-[1fr_360px]">

            <!-- ===== Cột trái: Danh sách món trong giỏ ===== -->
            <div class="space-y-4">

                <!-- Empty state -->
                <div x-show="items.length === 0" class="rounded-[28px] border border-dashed border-[#EADBCE] bg-white/70 p-14 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-[#FAF5F1] text-[#B38352]">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <p class="mt-4 font-serif text-xl font-bold text-[#2B1E19]">{{ __('Giỏ hàng đang trống') }}</p>
                    <p class="mt-1.5 text-sm text-[#A39284]">{{ __('Hãy khám phá thực đơn và thêm món yêu thích vào giỏ hàng nhé.') }}</p>
                    <a href="{{ route('menu.index') }}"
                       class="mt-6 inline-flex items-center gap-2 rounded-2xl bg-[#2B1E19] px-6 py-3 text-xs font-bold uppercase tracking-wider text-[#FAF5F1] shadow-md transition hover:bg-[#B38352]">
                        {{ __('Xem thực đơn') }}
                    </a>
                </div>

                <!-- Cart item row -->
                <template x-for="item in items" :key="item.id">
                    <div class="flex flex-col gap-4 rounded-[24px] border border-[#EADBCE] bg-white/95 p-5 shadow-sm sm:flex-row sm:items-center">
                        <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-[#FAF5F1] text-[#B38352]">
                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M4 6h16v12H4V6z" />
                            </svg>
                        </div>

                        <div class="flex-1">
                            <p class="font-sans text-sm font-bold text-[#2B1E19]" x-text="item.product_name"></p>
                            <p x-show="item.variant_name" class="mt-0.5 text-xs text-[#A39284]" x-text="item.variant_name"></p>
                            <p class="mt-1 text-xs font-semibold text-[#B38352]" x-text="formatPrice(item.unit_price) + '₫ / món'"></p>
                        </div>

                        <div class="flex items-center gap-3">
                            <!-- Quantity stepper -->
                            <div class="flex items-center rounded-xl border border-[#EADBCE] bg-white">
                                <button type="button" @click="updateQuantity(item, item.quantity - 1)"
                                        :disabled="updating[item.id] || item.quantity <= 1"
                                        class="px-3 py-2 text-[#736357] transition hover:text-[#B38352] disabled:cursor-not-allowed disabled:opacity-40">−</button>
                                <span class="w-8 text-center text-sm font-bold text-[#2B1E19]" x-text="item.quantity"></span>
                                <button type="button" @click="updateQuantity(item, item.quantity + 1)"
                                        :disabled="updating[item.id]"
                                        class="px-3 py-2 text-[#736357] transition hover:text-[#B38352] disabled:cursor-not-allowed disabled:opacity-40">+</button>
                            </div>

                            <p class="w-24 text-right font-sans text-sm font-bold text-[#2B1E19]" x-text="formatPrice(item.line_total) + '₫'"></p>

                            <button type="button" @click="removeItem(item)" :disabled="removing[item.id]"
                                    class="flex h-9 w-9 items-center justify-center rounded-xl border border-transparent text-red-500 transition hover:border-red-200 hover:bg-red-50 disabled:cursor-not-allowed disabled:opacity-40"
                                    title="{{ __('Xoá món') }}">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </template>

                <!-- Thêm món khác -->
                <div x-show="items.length > 0" class="pt-2">
                    <a href="{{ route('menu.index') }}"
                       class="inline-flex items-center gap-2 rounded-2xl border border-dashed border-[#B38352] px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-[#B38352] transition hover:bg-amber-50/50">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                        {{ __('Thêm món khác') }}
                    </a>
                </div>
            </div>

            <!-- ===== Cột phải: Tóm tắt & Đặt hàng ===== -->
            <div class="h-fit space-y-4 rounded-[28px] border border-[#EADBCE] bg-white/95 p-7 shadow-[0_20px_50px_rgba(43,30,25,0.06)] lg:sticky lg:top-24">
                <h2 class="font-serif text-xl font-bold text-[#2B1E19]">{{ __('Tóm tắt đơn hàng') }}</h2>

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

                <button type="button" @click="checkout()" :disabled="checkingOut || items.length === 0"
                        class="mt-2 flex w-full items-center justify-center gap-2 rounded-2xl bg-[#2B1E19] py-3.5 text-xs font-bold uppercase tracking-wider text-[#FAF5F1] shadow-md transition hover:bg-[#B38352] disabled:cursor-not-allowed disabled:opacity-50">
                    <span x-show="!checkingOut">{{ __('Đặt hàng ngay') }}</span>
                    <span x-show="checkingOut">{{ __('Đang xử lý...') }}</span>
                </button>

                <a href="{{ route('orders.index') }}" class="block text-center text-xs font-semibold text-[#B38352] hover:underline">
                    {{ __('Xem lịch sử đơn hàng') }}
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function cartPage() {
    return {
        loading: true,
        items: [],
        itemCount: 0,
        total: 0,
        updating: {},
        removing: {},
        checkingOut: false,
        errorMessage: '',

        init() {
            this.fetchCart();
        },

        applyCartData(data) {
            this.items = data.items ?? [];
            this.itemCount = data.item_count ?? 0;
            this.total = data.total ?? 0;
        },

        async fetchCart() {
            this.loading = true;
            try {
                const res = await fetch('{{ url('/api/cart') }}', {
                    headers: { Accept: 'application/json' },
                });
                const json = await res.json();
                this.applyCartData(json.data ?? json);
            } catch (e) {
                this.errorMessage = '{{ __('Không thể tải giỏ hàng.') }}';
            } finally {
                this.loading = false;
            }
        },

        async updateQuantity(item, quantity) {
            if (quantity < 1) return;
            this.updating[item.id] = true;
            this.errorMessage = '';
            try {
                const res = await fetch(`{{ url('/api/cart/items') }}/${item.id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ quantity }),
                });
                const json = await res.json();
                if (!res.ok) throw new Error(json.message || Object.values(json.errors ?? {}).flat()[0] || '{{ __('Không thể cập nhật số lượng.') }}');
                this.applyCartData(json.data ?? json);
            } catch (e) {
                this.errorMessage = e.message;
            } finally {
                delete this.updating[item.id];
            }
        },

        async removeItem(item) {
            this.removing[item.id] = true;
            this.errorMessage = '';
            try {
                const res = await fetch(`{{ url('/api/cart/items') }}/${item.id}`, {
                    method: 'DELETE',
                    headers: {
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                });
                const json = await res.json();
                if (!res.ok) throw new Error(json.message || '{{ __('Không thể xoá món.') }}');
                this.applyCartData(json.data ?? json);
            } catch (e) {
                this.errorMessage = e.message;
            } finally {
                delete this.removing[item.id];
            }
        },

        async checkout() {
            if (this.items.length === 0) return;
            this.checkingOut = true;
            this.errorMessage = '';
            try {
                const res = await fetch('{{ url('/api/checkout') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                });
                const json = await res.json();
                if (!res.ok) throw new Error(json.message || Object.values(json.errors ?? {}).flat()[0] || '{{ __('Đặt hàng không thành công.') }}');
                window.location.href = '{{ route('orders.index') }}?ordered=1';
            } catch (e) {
                this.errorMessage = e.message;
                this.checkingOut = false;
            }
        },

        formatPrice(value) {
            return new Intl.NumberFormat('vi-VN').format(value || 0);
        },
    };
}
</script>
@endsection