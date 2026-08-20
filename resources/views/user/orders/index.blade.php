@extends('layouts.user-app')

@section('content')
<div class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
    <h1 class="mb-8 text-2xl font-bold text-[#1b1b18]">{{ __('Đơn hàng của tôi') }}</h1>

    <div class="space-y-4">
        @forelse ($orders as $order)
            @php
                $statusStyles = [
                    'pending' => 'bg-yellow-100 text-yellow-700',
                    'confirmed' => 'bg-blue-100 text-blue-700',
                    'preparing' => 'bg-amber-100 text-amber-700',
                    'completed' => 'bg-green-100 text-green-700',
                    'cancelled' => 'bg-red-100 text-red-700',
                ];
                $statusValue = $order->status->value ?? $order->status;
            @endphp

            <div class="rounded-xl bg-white p-5 shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <div>
                        <p class="font-semibold text-[#1b1b18]">{{ __('Đơn hàng') }} #{{ $order->id }}</p>
                        <p class="text-xs text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                    </div>

                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusStyles[$statusValue] ?? 'bg-gray-100 text-gray-600' }}">
                        {{ ucfirst($statusValue) }}
                    </span>
                </div>

                <div class="mt-3 divide-y divide-gray-100 border-t border-gray-100">
                    @foreach ($order->items as $item)
                        <div class="flex items-center justify-between py-2 text-sm text-gray-600">
                            <span>{{ $item->product_name }} × {{ $item->quantity }}</span>
                            <span>{{ number_format($item->subtotal, 0, ',', '.') }}₫</span>
                        </div>
                    @endforeach
                </div>

                <div class="mt-3 flex justify-end border-t border-gray-100 pt-3 text-sm font-semibold text-[#1b1b18]">
                    {{ __('Tổng cộng') }}: {{ number_format($order->total_amount, 0, ',', '.') }}₫
                </div>
            </div>
        @empty
            <div class="rounded-xl bg-white p-10 text-center text-gray-400 shadow-sm">
                {{ __('Bạn chưa có đơn hàng nào.') }}
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $orders->links() }}
    </div>
</div>
@endsection
@extends('layouts.user-app')

@section('content')
<div class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
    <div class="mb-8 flex flex-wrap items-center justify-between gap-3">
        <h1 class="text-2xl font-bold text-[#1b1b18]">{{ __('Đơn hàng của tôi') }}</h1>
        <a href="{{ route('cart.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-[#EADBCE] bg-white px-4 py-2 text-xs font-bold uppercase tracking-wider text-[#4A3B32] shadow-sm transition hover:bg-[#FAF5F1]">
            {{ __('Xem giỏ hàng') }}
        </a>
    </div>

    @if (request('ordered'))
        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4 text-sm font-semibold text-green-700">
            {{ __('Đặt hàng thành công! Cảm ơn bạn đã ủng hộ Brew & Bite.') }}
        </div>
    @endif

    <div class="space-y-4">
        @forelse ($orders as $order)
            @php
                $statusStyles = [
                    'pending' => 'bg-yellow-100 text-yellow-700',
                    'confirmed' => 'bg-blue-100 text-blue-700',
                    'preparing' => 'bg-amber-100 text-amber-700',
                    'completed' => 'bg-green-100 text-green-700',
                    'cancelled' => 'bg-red-100 text-red-700',
                ];
                $statusValue = $order->status->value ?? $order->status;
                $canCancel = in_array($statusValue, ['pending', 'confirmed'], true);
            @endphp

            <div x-data="{ cancelling: false, cancelled: false }" x-show="!cancelled"
                 class="rounded-xl bg-white p-5 shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <div>
                        <p class="font-semibold text-[#1b1b18]">{{ __('Đơn hàng') }} #{{ $order->id }}</p>
                        <p class="text-xs text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                    </div>

                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusStyles[$statusValue] ?? 'bg-gray-100 text-gray-600' }}">
                        {{ ucfirst($statusValue) }}
                    </span>
                </div>

                <div class="mt-3 divide-y divide-gray-100 border-t border-gray-100">
                    @foreach ($order->items as $item)
                        <div class="flex items-center justify-between py-2 text-sm text-gray-600">
                            <span>{{ $item->product_name }} × {{ $item->quantity }}</span>
                            <span>{{ number_format($item->subtotal, 0, ',', '.') }}₫</span>
                        </div>
                    @endforeach
                </div>

                <div class="mt-3 flex flex-wrap items-center justify-between gap-3 border-t border-gray-100 pt-3">
                    <span class="text-sm font-semibold text-[#1b1b18]">
                        {{ __('Tổng cộng') }}: {{ number_format($order->total_amount, 0, ',', '.') }}₫
                    </span>

                    @if ($canCancel)
                        <button type="button" :disabled="cancelling"
                                @click="
                                    cancelling = true;
                                    fetch('{{ url('/api/orders/'.$order->id.'/cancel') }}', {
                                        method: 'PATCH',
                                        headers: {
                                            Accept: 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                        },
                                    })
                                    .then(res => { if (!res.ok) throw new Error(); return res.json(); })
                                    .then(() => window.location.reload())
                                    .catch(() => { cancelling = false; alert('{{ __('Không thể huỷ đơn hàng.') }}'); })
                                "
                                class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-600 transition hover:bg-red-50 disabled:cursor-not-allowed disabled:opacity-50">
                            <span x-show="!cancelling">{{ __('Huỷ đơn') }}</span>
                            <span x-show="cancelling">{{ __('Đang huỷ...') }}</span>
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="rounded-xl bg-white p-10 text-center text-gray-400 shadow-sm">
                {{ __('Bạn chưa có đơn hàng nào.') }}
                <a href="{{ route('menu.index') }}" class="mt-2 block font-semibold text-[#B38352] hover:underline">{{ __('Đặt món ngay') }}</a>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $orders->links() }}
    </div>
</div>
@endsection