@extends('layouts.admin')

@section('content')
<div class="space-y-6 font-sans antialiased" 
     x-data="adminOrderManager({
        routes: {
            ordersIndex: '{{ route('admin.orders.index') }}',
            ordersBase: '{{ url('admin/orders') }}'
        },
        translations: {
            loadFailed: '{{ __('Không thể tải danh sách đơn hàng.') }}',
            detailFailed: '{{ __('Không thể tải chi tiết đơn hàng.') }}',
            updateSuccess: '{{ __('Đã cập nhật trạng thái đơn hàng thành công.') }}',
            updateFailed: '{{ __('Có lỗi xảy ra khi cập nhật trạng thái đơn hàng.') }}',
            cancelConfirm: '{{ __('Bạn có chắc chắn muốn huỷ đơn hàng này? Tồn kho sẽ được khôi phục.') }}'
        }
     })" 
     x-init="init()">
    
    <!-- Header -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2.5">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#FAF5F1] text-[#B38352] ring-1 ring-[#EADBCE]">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </span>
                <h1 class="text-2xl font-bold tracking-tight text-[#2B1E19]">{{ __('Quản lý đơn hàng') }}</h1>
            </div>
            <p class="mt-1 text-xs text-gray-500">{{ __('Theo dõi, lọc và cập nhật tiến trình toàn bộ đơn hàng trong hệ thống.') }}</p>
        </div>

        <button type="button" @click="fetchOrders(meta.current_page)" 
                class="inline-flex items-center gap-2 rounded-xl border border-[#EADBCE] bg-white px-3.5 py-2 text-xs font-semibold text-[#4A3B32] shadow-sm transition hover:bg-[#FAF5F1] hover:text-[#B38352] focus:outline-none">
            <svg class="h-4 w-4" :class="{ 'animate-spin': loading }" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            <span>{{ __('Làm mới') }}</span>
        </button>
    </div>

    <!-- Alert / Notice -->
    <div x-show="notice.message" x-cloak x-transition
         class="flex items-center justify-between rounded-2xl border px-4 py-3 text-xs font-semibold shadow-sm transition-all"
         :class="notice.type === 'success' ? 'border-emerald-200 bg-emerald-50/90 text-emerald-800' : 'border-rose-200 bg-rose-50/90 text-rose-800'">
        <div class="flex items-center gap-2">
            <span class="flex h-5 w-5 items-center justify-center rounded-full" :class="notice.type === 'success' ? 'bg-emerald-600 text-white' : 'bg-rose-600 text-white'">
                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" :d="notice.type === 'success' ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12'"/>
                </svg>
            </span>
            <span x-text="notice.message"></span>
        </div>
        <button @click="notice.message = ''" class="text-gray-400 hover:text-gray-600">&times;</button>
    </div>

    <!-- 1. KPI Status Metric Cards -->
    @include('admin.orders.partials.status-metrics')

    <!-- 2. Orders Table & Filters -->
    @include('admin.orders.partials.order-table')

    <!-- 3. Detail & Transition Modal -->
    @include('admin.orders.partials.order-modal')

</div>
@endsection

@push('scripts')
    @vite('resources/js/admin-order-manager.js')
@endpush