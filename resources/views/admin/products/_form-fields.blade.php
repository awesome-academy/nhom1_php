@php
    $product = $product ?? null;
@endphp

<div class="space-y-6">
    <!-- Nhóm 1: Thông tin cơ bản -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <!-- Tên sản phẩm -->
        <div>
            <label for="name" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-[#4A3B32]">
                {{ __('Tên sản phẩm') }} <span class="text-red-500">*</span>
            </label>
            <input id="name" name="name" type="text" required autofocus
                   value="{{ old('name', $product?->name ?? '') }}"
                   placeholder="VD: Cà phê Latte Hạnh Nhân, Bánh Croissant Bơ..."
                   class="block w-full rounded-xl border border-[#EADBCE] bg-[#FAF5F1]/40 py-2.5 px-3.5 text-sm text-[#2B1E19] placeholder-gray-400 transition focus:border-[#B38352] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#B38352]/15">
            <x-input-error class="mt-1 text-xs" :messages="$errors->get('name')" />
        </div>

        <!-- Danh mục (Có phân nhóm cha - con rõ ràng) -->
        <div>
            <label for="category_id" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-[#4A3B32]">
                {{ __('Danh mục thực đơn') }} <span class="text-red-500">*</span>
            </label>
            <select id="category_id" name="category_id" required
                    class="block w-full rounded-xl border border-[#EADBCE] bg-[#FAF5F1]/40 py-2.5 px-3.5 text-sm text-[#2B1E19] transition focus:border-[#B38352] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#B38352]/15">
                <option value="">-- {{ __('Chọn danh mục phù hợp') }} --</option>
                @foreach ($categories->whereNull('parent_id') as $parent)
                    <optgroup label="☕ {{ $parent->name }}">
                        <option value="{{ $parent->id }}" @selected(old('category_id', $product?->category_id ?? '') == $parent->id)>
                            {{ $parent->name }} ({{ __('Chính') }})
                        </option>
                        @foreach ($categories->where('parent_id', $parent->id) as $child)
                            <option value="{{ $child->id }}" @selected(old('category_id', $product?->category_id ?? '') == $child->id)>
                                ↳ {{ $child->name }}
                            </option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
            <x-input-error class="mt-1 text-xs" :messages="$errors->get('category_id')" />
        </div>

        <!-- Loại sản phẩm (Food / Drink) -->
        <div>
            <label for="type" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-[#4A3B32]">
                {{ __('Loại sản phẩm') }} <span class="text-red-500">*</span>
            </label>
            <select id="type" name="type" required
                    class="block w-full rounded-xl border border-[#EADBCE] bg-[#FAF5F1]/40 py-2.5 px-3.5 text-sm text-[#2B1E19] transition focus:border-[#B38352] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#B38352]/15">
                <option value="drink" @selected(old('type', $product?->type?->value ?? ($product?->type ?? 'drink')) === 'drink')>Drink ☕ (Đồ uống)</option>
                <option value="food" @selected(old('type', $product?->type?->value ?? ($product?->type ?? 'drink')) === 'food')>Food 🥐 (Bánh & Đồ ăn)</option>
            </select>
            <x-input-error class="mt-1 text-xs" :messages="$errors->get('type')" />
        </div>

        <!-- Trạng thái kinh doanh -->
        <div class="flex items-center pt-6">
            <input type="hidden" name="is_active" value="0">
            <label class="relative flex cursor-pointer items-center gap-3 select-none">
                <input type="checkbox" name="is_active" value="1"
                       class="h-5 w-5 rounded-lg border-[#EADBCE] text-[#B38352] transition focus:ring-[#B38352]/20"
                       @checked(old('is_active', $product?->is_active ?? true))>
                <div>
                    <span class="block text-xs font-bold uppercase tracking-wider text-[#2B1E19]">{{ __('Đang mở bán') }}</span>
                    <span class="block text-[11px] text-gray-500">{{ __('Cho phép hiển thị trên thực đơn khách hàng') }}</span>
                </div>
            </label>
        </div>

        <!-- Giá bán -->
        <div>
            <label for="price" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-[#4A3B32]">
                {{ __('Giá bán (₫)') }} <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <input id="price" name="price" type="number" step="1000" min="0" required
                       value="{{ old('price', $product ? (int)$product->price : '') }}"
                       placeholder="45000"
                       class="block w-full rounded-xl border border-[#EADBCE] bg-[#FAF5F1]/40 py-2.5 pl-3.5 pr-8 text-sm font-semibold text-[#2B1E19] transition focus:border-[#B38352] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#B38352]/15">
                <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-xs font-bold text-[#B38352]">₫</span>
            </div>
            <x-input-error class="mt-1 text-xs" :messages="$errors->get('price')" />
        </div>

        <!-- Tồn kho -->
        <div>
            <label for="stock_quantity" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-[#4A3B32]">
                {{ __('Số lượng tồn kho') }} <span class="text-red-500">*</span>
            </label>
            <input id="stock_quantity" name="stock_quantity" type="number" min="0" required
                   value="{{ old('stock_quantity', $product?->stock_quantity ?? 50) }}"
                   placeholder="50"
                   class="block w-full rounded-xl border border-[#EADBCE] bg-[#FAF5F1]/40 py-2.5 px-3.5 text-sm text-[#2B1E19] transition focus:border-[#B38352] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#B38352]/15">
            <x-input-error class="mt-1 text-xs" :messages="$errors->get('stock_quantity')" />
        </div>
    </div>

    <!-- Nhóm 2: Mô tả & Tóm tắt -->
    <div class="space-y-4 border-t border-[#EADBCE]/60 pt-6">
        <div>
            <label for="summary" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-[#4A3B32]">
                {{ __('Tóm tắt ngắn (Product Summary)') }}
            </label>
            <textarea id="summary" name="summary" rows="2"
                      placeholder="{{ __('Một câu mô tả hương vị ngắn hiển thị trên thẻ món ngoài danh mục...') }}"
                      class="block w-full rounded-xl border border-[#EADBCE] bg-[#FAF5F1]/40 py-2.5 px-3.5 text-sm text-[#2B1E19] placeholder-gray-400 transition focus:border-[#B38352] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#B38352]/15">{{ old('summary', $product?->summary ?? '') }}</textarea>
            <x-input-error class="mt-1 text-xs" :messages="$errors->get('summary')" />
        </div>

        <div>
            <label for="full_description" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-[#4A3B32]">
                {{ __('Mô tả chi tiết món ăn / thức uống') }}
            </label>
            <textarea id="full_description" name="full_description" rows="4"
                      placeholder="{{ __('Mô tả nguồn gốc hạt, phương pháp pha chế hoặc công thức bơ nướng...') }}"
                      class="block w-full rounded-xl border border-[#EADBCE] bg-[#FAF5F1]/40 py-2.5 px-3.5 text-sm text-[#2B1E19] placeholder-gray-400 transition focus:border-[#B38352] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#B38352]/15">{{ old('full_description', $product?->full_description ?? '') }}</textarea>
            <x-input-error class="mt-1 text-xs" :messages="$errors->get('full_description')" />
        </div>
    </div>
</div>

