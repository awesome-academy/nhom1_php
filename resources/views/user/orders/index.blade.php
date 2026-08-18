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
