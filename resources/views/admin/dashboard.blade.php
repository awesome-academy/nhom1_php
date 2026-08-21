@extends('layouts.admin')

@section('content')
<div class="space-y-6 font-sans antialiased">

    <!-- 1. Header & Quick Actions -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2.5">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#FAF5F1] text-[#B38352] ring-1 ring-[#EADBCE]">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </span>
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-[#2B1E19]">{{ __('Tổng quan hệ thống') }}</h1>
                    <p class="text-xs text-[#736357]">{{ __('Hiệu suất bán hàng và tình trạng vận hành thực tế của Brew & Bite.') }}</p>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.products.create') }}" 
               class="inline-flex items-center gap-1.5 rounded-xl border border-[#EADBCE] bg-white px-3.5 py-2 text-xs font-bold text-[#4A3B32] shadow-xs transition hover:bg-[#FAF5F1] hover:text-[#B38352]">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>{{ __('Thêm món mới') }}</span>
            </a>
            <a href="{{ route('admin.orders.manage') }}" 
               class="inline-flex items-center gap-1.5 rounded-xl bg-[#2B1E19] px-3.5 py-2 text-xs font-bold text-[#FAF5F1] shadow-xs transition hover:bg-[#B38352]">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <span>{{ __('Quản lý đơn hàng') }}</span>
            </a>
        </div>
    </div>

    <!-- 2. KPI Metrics Cards -->
    @include('admin.dashboard.partials.kpi-metrics')

    <!-- 3. Charts Section -->
    @include('admin.dashboard.partials.charts-section')

    <!-- 4. Recent Orders & Top Products -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        @include('admin.dashboard.partials.recent-orders')
        @include('admin.dashboard.partials.top-products')
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    // 1. Biểu đồ Doanh thu 7 ngày
    const revCtx = document.getElementById('revenueChart')?.getContext('2d');
    if (revCtx) {
        new Chart(revCtx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: @json($chartRevenueData),
                    borderColor: '#B38352',
                    backgroundColor: 'rgba(179, 131, 82, 0.12)',
                    fill: true,
                    tension: 0.35,
                    borderWidth: 2.5,
                    pointRadius: 4,
                    pointBackgroundColor: '#2B1E19'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => new Intl.NumberFormat('vi-VN').format(ctx.raw) + ' VNĐ'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: val => val >= 1000000 ? (val / 1000000).toFixed(1) + 'M' : (val >= 1000 ? (val / 1000).toFixed(0) + 'k' : val),
                            font: { size: 10 }
                        },
                        grid: { color: '#FAF5F1' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10 } }
                    }
                }
            }
        });
    }

    // 2. Biểu đồ Danh mục Thực đơn
    const catCtx = document.getElementById('categoryChart')?.getContext('2d');
    if (catCtx) {
        const catLabels = @json($categoryLabels);
        const catData = @json($categoryCounts);
        
        new Chart(catCtx, {
            type: 'doughnut',
            data: {
                labels: catLabels.length ? catLabels : ['Chưa có danh mục'],
                datasets: [{
                    data: catData.length ? catData : [1],
                    backgroundColor: ['#2B1E19', '#B38352', '#D8C3B0', '#736357', '#FAF5F1'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 10, font: { size: 11 } }
                    }
                },
                cutout: '68%'
            }
        });
    }
});
</script>
@endpush