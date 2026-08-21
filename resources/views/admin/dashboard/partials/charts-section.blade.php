<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <!-- Biểu đồ Doanh thu 7 ngày -->
    <div class="rounded-2xl border border-[#EADBCE]/80 bg-white p-5 shadow-xs lg:col-span-2">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-sm font-bold text-[#2B1E19]">{{ __('Doanh thu 7 ngày gần nhất') }}</h2>
                <p class="text-[11px] text-gray-500">{{ __('Tổng giá trị các đơn hàng hoàn tất theo từng ngày.') }}</p>
            </div>
            <span class="inline-flex items-center rounded-lg bg-[#FAF5F1] px-2.5 py-1 text-xs font-bold text-[#B38352] ring-1 ring-[#EADBCE]">
                {{ __('VNĐ') }}
            </span>
        </div>
        <div class="mt-4 h-64 w-full">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <!-- Biểu đồ Phân bố Danh mục -->
    <div class="flex flex-col justify-between rounded-2xl border border-[#EADBCE]/80 bg-white p-5 shadow-xs">
        <div>
            <h2 class="text-sm font-bold text-[#2B1E19]">{{ __('Phân bố món theo Menu') }}</h2>
            <p class="text-[11px] text-gray-500">{{ __('Số lượng sản phẩm trong từng nhóm thực đơn chính.') }}</p>
            <div class="mt-4 h-48 w-full flex items-center justify-center">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
        <div class="mt-3 text-center text-xs text-gray-500 border-t border-gray-100 pt-2.5">
            {{ __('Dữ liệu cập nhật theo thời gian thực') }}
        </div>
    </div>
</div>