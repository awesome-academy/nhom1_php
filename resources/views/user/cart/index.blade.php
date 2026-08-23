@extends('layouts.user-app')

@section('content')
<div class="relative min-h-[calc(100vh-5rem)] overflow-hidden bg-[#FAF5F1] py-12 px-4 font-sans sm:px-6 lg:px-8"
     style="background:
        radial-gradient(ellipse 900px 600px at 15% -10%, #FFFFFF 0%, rgba(255,255,255,0) 60%),
        linear-gradient(160deg, #FFFFFF 0%, #FFFBF6 38%, #FAF5F1 62%, #F3E7D8 84%, #EADBCE 100%);"
     x-data="cartPage({
        user: {
            name: @js(auth()->user()->name ?? ''),
            phone: @js(auth()->user()->phone ?? ''),
            address: @js(auth()->user()->address ?? '')
        },
        routes: {
            cartData: '{{ url('/cart/data') }}',
            cartItems: '{{ url('/cart/items') }}',
            checkout: '{{ url('/checkout') }}',
            orderRedirect: '{{ route('orders.index', ['ordered' => 1]) }}'
        }
     })" 
     x-init="init()">

    {{-- Background Pattern --}}
    <div class="pointer-events-none absolute inset-0 opacity-40"
         style="background-image:url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='120' height='120'%3E%3Cg fill='none' stroke='%23B38352' stroke-width='1.3' opacity='0.16'%3E%3Cellipse cx='30' cy='30' rx='9' ry='15' transform='rotate(24 30 30)'/%3E%3Cpath d='M30 17 Q26 30 30 43' transform='rotate(24 30 30)'/%3E%3Cellipse cx='92' cy='86' rx='9' ry='15' transform='rotate(-18 92 86)'/%3E%3Cpath d='M92 73 Q88 86 92 99' transform='rotate(-18 92 86)'/%3E%3C/g%3E%3C/svg%3E&quot;); background-size:220px 220px;">
    </div>

    <div class="relative mx-auto max-w-6xl">
        {{-- Header --}}
        <div class="mb-8 flex flex-col items-start gap-1">
            <span class="text-[11px] font-semibold tracking-[0.25em] text-[#B38352] uppercase">
                Brew &amp; Bite Artisan
            </span>
            <h1 class="text-3xl font-extrabold tracking-tight text-[#2B1E19] sm:text-4xl">
                {{ __('Giỏ hàng của bạn') }}
            </h1>
        </div>

        {{-- Error Alert --}}
        <div x-show="errorMessage" x-cloak x-transition
             class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-600">
            <span x-text="errorMessage"></span>
        </div>

        {{-- Loading Skeleton --}}
        <div x-show="loading" class="rounded-[24px] border border-[#EADBCE] bg-white/70 py-20 text-center shadow-sm">
            <div class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-[#B38352] border-r-transparent"></div>
            <p class="mt-3 text-sm font-medium text-[#736357]">{{ __('Đang tải giỏ hàng...') }}</p>
        </div>

        {{-- Cart Content --}}
        <div x-show="!loading" x-cloak class="grid grid-cols-1 gap-8 lg:grid-cols-[1fr_360px]">
            @include('user.cart.partials.cart-item')
            @include('user.cart.partials.cart-summary')
        </div>
    </div>

    {{-- Modal Checkout --}}
    @include('user.cart.partials.checkout-modal')
</div>
@endsection

@push('scripts')
    @vite(['resources/js/cart.js'])
@endpush