@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-2.5">
            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#FAF5F1] text-[#B38352] ring-1 ring-[#EADBCE]">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </span>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-[#2B1E19]">{{ __('Quản lý sản phẩm') }}</h1>
                <p class="text-xs text-[#736357]">{{ __('Quản lý toàn bộ món ăn, thức uống kèm hình ảnh, giá và tồn kho.') }}</p>
            </div>
        </div>

        <a href="{{ route('admin.products.create') }}"
           class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#1b1b18] px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-gray-800">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            {{ __('Thêm sản phẩm') }}
        </a>
    </div>

    <!-- Layout 2 Cột: Sidebar Bên Trái & Danh Sách Sản Phẩm Bên Phải -->
    <div class="flex flex-col gap-6 lg:flex-row">
        
        <!-- Cột 1: Sidebar Filter -->
        @include('admin.products.partials.filter-sidebar')

        <!-- Cột 2: Main Content (Danh sách sản phẩm) -->
        <div class="flex-1 space-y-4">
            {{-- Thanh điều khiển Sắp xếp & Thống kê kết quả --}}
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between rounded-2xl bg-white px-4 py-3 shadow-sm ring-1 ring-black/5">
                <span class="text-xs font-semibold text-gray-500">
                    {{ __('Hiển thị') }} <b class="text-[#1b1b18]">{{ $products->total() }}</b> {{ __('sản phẩm') }}
                </span>

                <form method="GET" action="{{ route('admin.products.index') }}" class="flex items-center gap-2">
                    {{-- Giữ lại các filter đang chọn khi đổi sắp xếp --}}
                    @foreach(request()->except('sort', 'page') as $key => $val)
                        <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                    @endforeach

                    <label class="text-xs font-medium text-gray-500">{{ __('Sắp xếp:') }}</label>
                    <select name="sort" onchange="this.form.submit()"
                            class="rounded-lg border-gray-200 py-1.5 pl-2.5 pr-8 text-xs font-medium text-[#1b1b18] focus:border-amber-500 focus:ring-amber-500">
                        <option value="latest" @selected(request('sort', 'latest') === 'latest')>{{ __('Mới nhất') }}</option>
                        <option value="name_asc" @selected(request('sort') === 'name_asc')>{{ __('Tên A-Z') }}</option>
                        <option value="price_asc" @selected(request('sort') === 'price_asc')>{{ __('Giá tăng dần') }}</option>
                        <option value="price_desc" @selected(request('sort') === 'price_desc')>{{ __('Giá giảm dần') }}</option>
                        <option value="stock_asc" @selected(request('sort') === 'stock_asc')>{{ __('Tồn kho thấp nhất') }}</option>
                    </select>
                </form>
            </div>

            {{-- Grid Sản phẩm --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
                @forelse ($products as $product)
                    @include('admin.products.partials.product-card', ['product' => $product])
                @empty
                    <div class="col-span-full rounded-2xl bg-white p-12 text-center text-gray-400 shadow-sm ring-1 ring-black/5">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-gray-50 text-gray-400">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                        </div>
                        <p class="mt-3 font-semibold text-gray-700">{{ __('Không tìm thấy sản phẩm nào.') }}</p>
                        <p class="mt-1 text-xs text-gray-400">{{ __('Thử thay đổi điều kiện lọc hoặc từ khoá tìm kiếm.') }}</p>
                    </div>
                @endforelse
            </div>

            {{-- Phân trang --}}
            <div class="pt-2">
                {{ $products->links() }}
            </div>
        </div>

    </div>
</div>
@endsection