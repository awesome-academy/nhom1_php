<div class="overflow-hidden rounded-2xl border border-[#EADBCE]/80 bg-white shadow-xs lg:col-span-2">
    <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4">
        <div>
            <h2 class="text-sm font-bold text-[#2B1E19]">{{ __('Đơn hàng mới nhận') }}</h2>
            <p class="text-[11px] text-gray-500">{{ __('Các đơn hàng gần nhất được đặt trong hệ thống.') }}</p>
        </div>
        <a href="{{ route('admin.orders.manage') }}" class="text-xs font-bold text-[#B38352] hover:underline">
            {{ __('Xem tất cả') }} &rarr;
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100 text-left text-xs">
            <thead class="bg-[#FAF5F1]/60 text-[11px] font-bold uppercase tracking-wider text-[#736357]">
                <tr>
                    <th class="px-5 py-3">Mã đơn</th>
                    <th class="px-5 py-3">Khách hàng</th>
                    <th class="px-5 py-3 text-center">Số món</th>
                    <th class="px-5 py-3 text-right">Tổng tiền</th>
                    <th class="px-5 py-3">Trạng thái</th>
                    <th class="px-5 py-3 text-right">Chi tiết</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($recentOrders as $order)
                    @php
                        $sVal = $order->status->value ?? $order->status;
                        $statusBadge = match($sVal) {
                            'pending'   => ['bg-amber-50 text-amber-800 border-amber-200', 'bg-amber-500', 'Chờ xác nhận'],
                            'confirmed' => ['bg-sky-50 text-sky-800 border-sky-200', 'bg-sky-500', 'Đã tiếp nhận'],
                            'preparing' => ['bg-indigo-50 text-indigo-800 border-indigo-200', 'bg-indigo-500 animate-pulse', 'Đang làm món'],
                            'completed' => ['bg-emerald-50 text-emerald-800 border-emerald-200', 'bg-emerald-500', 'Hoàn thành'],
                            'cancelled' => ['bg-rose-50 text-rose-800 border-rose-200', 'bg-rose-500', 'Đã huỷ'],
                            default     => ['bg-gray-50 text-gray-800 border-gray-200', 'bg-gray-400', $sVal],
                        };
                    @endphp
                    <tr class="hover:bg-[#FAF5F1]/30 transition">
                        <td class="px-5 py-3 font-bold text-[#2B1E19]">#{{ $order->id }}</td>
                        <td class="px-5 py-3">
                            <p class="font-bold text-[#2B1E19]">{{ $order->user->name ?? 'Khách vãng lai' }}</p>
                            <p class="text-[10px] text-gray-500">{{ $order->user->phone ?? $order->user->email ?? '—' }}</p>
                        </td>
                        <td class="px-5 py-3 text-center font-medium text-gray-600">
                            {{ $order->items->count() }}
                        </td>
                        <td class="px-5 py-3 text-right font-extrabold text-[#B38352]">
                            {{ number_format((float) $order->total_amount, 0, ',', '.') }}₫
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1 rounded-full border px-2 py-0.5 text-[10px] font-bold {{ $statusBadge[0] }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ $statusBadge[1] }}"></span>
                                {{ $statusBadge[2] }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-right">
                            <a href="{{ route('admin.orders.manage') }}" 
                               class="rounded-lg border border-[#EADBCE] bg-white px-2.5 py-1 text-[11px] font-bold text-[#4A3B32] hover:bg-[#FAF5F1] hover:text-[#B38352]">
                                {{ __('Xử lý') }}
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-xs text-gray-400">
                            {{ __('Chưa có đơn hàng nào trong hệ thống.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>