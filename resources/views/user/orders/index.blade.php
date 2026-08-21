@extends('layouts.user-app')

@section('content')
<div class="min-h-screen bg-[#FDFBF9] py-8 sm:py-12">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
        
        {{-- Header Bar --}}
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-black tracking-tight text-[#2D221E] sm:text-3xl">
                    {{ __('Lịch sử đơn hàng') }}
                </h1>
                <p class="mt-1 text-sm text-[#7A6E65]">
                    {{ __('Theo dõi trạng thái và lịch sử thưởng thức món ăn của bạn') }}
                </p>
            </div>
            <a href="{{ route('cart.index') }}" 
               class="inline-flex items-center justify-center gap-2 rounded-xl border border-[#EADBCE] bg-white px-4 py-2.5 text-xs font-bold uppercase tracking-wider text-[#4A3B32] shadow-sm transition hover:bg-[#FAF5F1] hover:border-[#D4C3B3]">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                {{ __('Xem giỏ hàng') }}
            </a>
        </div>

        {{-- Flash message đặt hàng thành công --}}
        @if (request('ordered'))
            <div class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50/80 p-4 text-sm font-medium text-emerald-800 shadow-sm">
                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-500 text-white">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold">{{ __('Đặt hàng thành công!') }}</p>
                    <p class="text-xs text-emerald-700">{{ __('Cảm ơn bạn đã ủng hộ Brew & Bite. Quán đang chuẩn bị món ngay đây!') }}</p>
                </div>
            </div>
        @endif

        @php
            $currentStatus = request('status', 'all');
        @endphp

        <div class="flex items-center gap-2 overflow-x-auto border-b border-[#EADBCE] pb-3 text-sm no-scrollbar">
            {{-- Tất cả --}}
            <a href="{{ route('orders.index', ['status' => 'all']) }}"
            class="whitespace-nowrap rounded-xl px-4 py-2 font-semibold transition {{ $currentStatus === 'all' ? 'bg-[#4A3B32] text-white shadow-sm' : 'text-[#7A6E65] hover:bg-[#FAF5F1]' }}">
                {{ __('Tất cả') }}
            </a>

            {{-- Chờ xử lý --}}
            <a href="{{ route('orders.index', ['status' => 'processing']) }}"
            class="whitespace-nowrap rounded-xl px-4 py-2 font-semibold transition {{ $currentStatus === 'processing' ? 'bg-[#4A3B32] text-white shadow-sm' : 'text-[#7A6E65] hover:bg-[#FAF5F1]' }}">
                {{ __('Đang xử lý') }}
            </a>

            {{-- Đã xác nhận --}}
            <a href="{{ route('orders.index', ['status' => 'confirmed']) }}"
            class="whitespace-nowrap rounded-xl px-4 py-2 font-semibold transition {{ $currentStatus === 'confirmed' ? 'bg-[#4A3B32] text-white shadow-sm' : 'text-[#7A6E65] hover:bg-[#FAF5F1]' }}">
                {{ __('Đã xác nhận') }}
            </a>

            {{-- Hoàn thành --}}
            <a href="{{ route('orders.index', ['status' => 'completed']) }}"
            class="whitespace-nowrap rounded-xl px-4 py-2 font-semibold transition {{ $currentStatus === 'completed' ? 'bg-[#4A3B32] text-white shadow-sm' : 'text-[#7A6E65] hover:bg-[#FAF5F1]' }}">
                {{ __('Hoàn thành') }}
            </a>

            {{-- Đã huỷ --}}
            <a href="{{ route('orders.index', ['status' => 'cancelled']) }}"
            class="whitespace-nowrap rounded-xl px-4 py-2 font-semibold transition {{ $currentStatus === 'cancelled' ? 'bg-[#4A3B32] text-white shadow-sm' : 'text-[#7A6E65] hover:bg-[#FAF5F1]' }}">
                {{ __('Đã huỷ') }}
            </a>
        </div>

        {{-- Danh sách đơn hàng --}}
        <div class="space-y-4">
            @forelse ($orders as $order)
                @php
                    $statusConfig = [
                        'pending' => [
                            'label' => 'Chờ quán nhận đơn',
                            'class' => 'bg-amber-50 text-amber-700 border-amber-200/60',
                            'dot' => 'bg-amber-500',
                        ],
                        'confirmed' => [
                            'label' => 'Đã nhận đơn',
                            'class' => 'bg-blue-50 text-blue-700 border-blue-200/60',
                            'dot' => 'bg-blue-500',
                        ],
                        'preparing' => [
                            'label' => 'Đang làm món',
                            'class' => 'bg-orange-50 text-orange-700 border-orange-200/60',
                            'dot' => 'bg-orange-500 animate-pulse',
                        ],
                        'completed' => [
                            'label' => 'Giao thành công',
                            'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200/60',
                            'dot' => 'bg-emerald-500',
                        ],
                        'cancelled' => [
                            'label' => 'Đã huỷ',
                            'class' => 'bg-gray-100 text-gray-600 border-gray-200',
                            'dot' => 'bg-gray-400',
                        ],
                    ];

                    $statusValue = $order->status->value ?? $order->status;
                    $currentStatusConfig = $statusConfig[$statusValue] ?? [
                        'label' => ucfirst($statusValue),
                        'class' => 'bg-gray-100 text-gray-700 border-gray-200',
                        'dot' => 'bg-gray-400'
                    ];

                    $canCancel = in_array($statusValue, ['pending', 'confirmed'], true);
                @endphp

                <div x-data="{ cancelling: false, cancelled: false }" 
                     x-show="!cancelled"
                     class="overflow-hidden rounded-2xl border border-[#F0EAE4] bg-white shadow-sm transition hover:shadow-md">
                    
                    {{-- Header của mỗi Card Đơn hàng --}}
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-[#F6F2EE] bg-[#FAF8F5]/60 px-5 py-3.5 sm:px-6">
                        <div class="flex items-center gap-3">
                            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#F0EAE4] text-sm font-bold text-[#4A3B32]">
                                #{{ $order->id }}
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-[#8C7E74]">
                                    {{ $order->created_at->format('d/m/Y • H:i') }}
                                </p>
                            </div>
                        </div>

                        {{-- Badge trạng thái --}}
                        <div class="inline-flex items-center gap-1.5 rounded-full border px-3 py-1 text-xs font-semibold {{ $currentStatusConfig['class'] }}">
                            <span class="h-1.5 w-1.5 rounded-full {{ $currentStatusConfig['dot'] }}"></span>
                            {{ __($currentStatusConfig['label']) }}
                        </div>
                    </div>

                    {{-- Danh sách Item món ăn kèm ảnh --}}
                    <div class="divide-y divide-[#F6F2EE] px-5 sm:px-6">
                        @foreach ($order->items as $item)
                            @php
                                $imagePath = $item->product?->primaryImage?->image_path;
                                $imageUrl = $imagePath 
                                    ? asset('storage/' . $imagePath) 
                                    : 'https://images.unsplash.com/photo-1541167760496-1628856ab772?q=80&w=200&auto=format&fit=crop';
                            @endphp

                            <div class="flex items-center gap-4 py-3.5">
                                <img src="{{ $imageUrl }}" 
                                    alt="{{ $item->product_name ?? ($item->product->name ?? 'Món ăn') }}" 
                                    class="h-14 w-14 shrink-0 rounded-xl border border-[#EADBCE]/60 object-cover shadow-inner"
                                    onerror="this.src='https://images.unsplash.com/photo-1541167760496-1628856ab772?q=80&w=200&auto=format&fit=crop'">
                                
                                <div class="flex min-w-0 flex-1 flex-col justify-center">
                                    <h4 class="truncate text-sm font-semibold text-[#2D221E]">
                                        {{ $item->product_name ?? ($item->product->name ?? '') }}
                                    </h4>
                                    <p class="mt-0.5 text-xs text-[#8C7E74]">
                                        {{ __('Số lượng:') }} <span class="font-medium text-[#4A3B32]">×{{ $item->quantity }}</span>
                                    </p>
                                </div>

                                <div class="text-right">
                                    <p class="text-sm font-bold text-[#4A3B32]">
                                        {{ number_format($item->subtotal ?? ($item->unit_price * $item->quantity), 0, ',', '.') }}₫
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Footer: Tổng tiền và Nút thao tác --}}
                    <div class="flex flex-wrap items-center justify-between gap-3 border-t border-[#F6F2EE] bg-[#FAF8F5]/30 px-5 py-4 sm:px-6">
                        <div class="flex items-baseline gap-1.5">
                            <span class="text-xs font-medium text-[#8C7E74]">{{ __('Tổng thanh toán:') }}</span>
                            <span class="text-base font-black text-[#B38352]">
                                {{ number_format($order->total_amount, 0, ',', '.') }}₫
                            </span>
                        </div>

                        <div class="flex items-center gap-2">
                            @if ($canCancel)
                                <button type="button" 
                                    :disabled="cancelling"
                                    @click="
                                        if (confirm('{{ __('Bạn có chắc chắn muốn huỷ đơn hàng này không?') }}')) {
                                            cancelling = true;
                                            fetch('{{ route('orders.cancel', $order->id) }}', {
                                                method: 'PATCH',
                                                headers: {
                                                    'Accept': 'application/json',
                                                    'Content-Type': 'application/json',
                                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.getAttribute('content') || '',
                                                },
                                            })
                                            .then(async (res) => { 
                                                const data = await res.json();
                                                if (!res.ok) {
                                                    throw new Error(data.message || 'Huỷ đơn thất bại');
                                                }
                                                return data;
                                            })
                                            .then(() => {
                                                window.location.reload();
                                            })
                                            .catch((err) => { 
                                                cancelling = false; 
                                                alert(err.message || '{{ __('Không thể huỷ đơn vào lúc này. Vui lòng liên hệ trực tiếp quán!') }}'); 
                                            });
                                        }
                                    "
                                    class="rounded-xl border border-red-200 bg-white px-3.5 py-1.5 text-xs font-semibold text-red-600 shadow-sm transition hover:bg-red-50 disabled:cursor-not-allowed disabled:opacity-50">
                                <span x-show="!cancelling">{{ __('Huỷ đơn') }}</span>
                                <span x-show="cancelling">{{ __('Đang xử lý...') }}</span>
                            </button>
                            @endif

                            <a href="{{ route('menu.index') }}" 
                               class="rounded-xl bg-[#4A3B32] px-3.5 py-1.5 text-xs font-semibold text-white shadow-sm transition hover:bg-[#382c25]">
                                {{ __('Đặt lại món') }}
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center rounded-3xl border border-dashed border-[#EADBCE] bg-white p-12 text-center shadow-sm">
                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-[#FAF5F1] text-[#B38352]">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <h3 class="mt-4 text-base font-bold text-[#2D221E]">{{ __('Chưa có đơn hàng nào') }}</h3>
                    <p class="mt-1 max-w-sm text-xs text-[#8C7E74]">
                        {{ request('status') ? __('Không tìm thấy đơn hàng nào ở trạng thái này.') : __('Bạn chưa đặt đơn nào tại Brew & Bite. Khám phá menu và thưởng thức nhé!') }}
                    </p>
                    <a href="{{ route('menu.index') }}" 
                       class="mt-5 inline-flex items-center gap-2 rounded-xl bg-[#B38352] px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-white shadow-sm transition hover:bg-[#9B6E40]">
                        {{ __('Khám phá menu ngay') }}
                    </a>
                </div>
            @endforelse
        </div>

        {{-- Phân trang --}}
        <div class="mt-8">
            {{ $orders->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection