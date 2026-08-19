@extends('layouts.admin')

@section('title', 'Quản lý Sản phẩm')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-[#2B1E19]">Danh sách Món ăn & Đồ uống</h1>
        <a href="{{ route('admin.products.create') }}" class="rounded-xl bg-[#B38352] px-4 py-2 text-xs font-semibold text-white">
            + Thêm Món Mới
        </a>
    </div>

    <div class="bg-white p-4 rounded-2xl border border-[#EADBCE]">
        <table class="w-full text-left text-xs">
            <thead>
                <tr class="border-b border-[#EADBCE]">
                    <th class="py-2">Tên món</th>
                    <th>Loại</th>
                    <th>Giá</th>
                    <th>Tồn kho</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr class="border-b border-[#FAF5F1]">
                        <td class="py-2 font-medium">{{ $product->name }}</td>
                        <td>{{ $product->type === 'drink' ? 'Đồ uống' : 'Món ăn' }}</td>
                        <td>{{ number_format($product->price) }}đ</td>
                        <td>{{ $product->stock_quantity }}</td>
                        <td class="flex gap-2 py-2">
                            <a href="{{ route('admin.products.edit', $product) }}" class="text-blue-600">Sửa</a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Xóa sản phẩm này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-4 text-center text-gray-500">Không có sản phẩm nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection