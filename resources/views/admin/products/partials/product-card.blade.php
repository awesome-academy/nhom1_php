<div class="group flex flex-col justify-between overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/5 transition hover:shadow-md">
    <div>
        {{-- Ảnh sản phẩm --}}
        <div class="relative aspect-[4/3] overflow-hidden bg-gray-100">
            @if ($product->primaryImage)
                <img src="{{ asset('storage/'.$product->primaryImage->image_path) }}"
                     alt="{{ $product->name }}"
                     class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
            @else
                <div class="flex h-full w-full items-center justify-center text-gray-300">
                    <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M4 6h16v12H4V6z" />
                    </svg>
                </div>
            @endif

            <span class="absolute left-2.5 top-2.5 rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wide {{ $product->type->value === 'drink' ? 'bg-blue-500/90 text-white' : 'bg-amber-500/90 text-[#1b1b18]' }}">
                {{ $product->type->value === 'drink' ? __('Drink') : __('Food') }}
            </span>

            <span class="absolute right-2.5 top-2.5 rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wide {{ $product->is_active ? 'bg-green-500/90 text-white' : 'bg-gray-500/90 text-white' }}">
                {{ $product->is_active ? __('Đang bán') : __('Ngừng bán') }}
            </span>
        </div>

        {{-- Chi tiết thông tin --}}
        <div class="space-y-1.5 p-4">
            <p class="text-[11px] font-bold uppercase tracking-wide text-amber-600">
                {{ $product->category->name ?? __('Chưa phân loại') }}
            </p>
            <h3 class="truncate text-sm font-bold text-[#1b1b18]" title="{{ $product->name }}">
                {{ $product->name }}
            </h3>

            <div class="flex items-center justify-between pt-1">
                <span class="text-base font-bold text-[#1b1b18]">
                    {{ number_format((float) $product->price, 0, ',', '.') }}₫
                </span>
                <span class="text-xs text-gray-500">
                    {{ __('Kho') }}: {{ $product->stock_quantity }}
                </span>
            </div>
        </div>
    </div>

    {{-- Thao tác nút bấm --}}
    <div class="flex items-center gap-2 p-4 pt-0">
        <a href="{{ route('admin.products.edit', $product) }}"
           class="flex-1 rounded-lg bg-[#1b1b18] py-2 text-center text-xs font-semibold text-white hover:bg-gray-800">
            {{ __('Chỉnh sửa') }}
        </a>

        <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
              onsubmit="return confirm('{{ __('Xoá sản phẩm này?') }}');">
            @csrf
            @method('delete')
            <button type="submit"
                    class="rounded-lg border border-red-200 px-3 py-2 text-xs font-semibold text-red-600 hover:bg-red-50">
                {{ __('Xoá') }}
            </button>
        </form>
    </div>
</div>