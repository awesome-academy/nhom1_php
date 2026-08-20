<div class="w-full lg:w-72 shrink-0 space-y-6 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-black/5">
    <div class="flex items-center justify-between border-b border-gray-100 pb-3">
        <h2 class="text-base font-bold text-[#1b1b18]">{{ __('Bộ lọc') }}</h2>
        @if (request()->hasAny(['search', 'category_id', 'type', 'status', 'sort', 'price_range']))
            <a href="{{ route('admin.products.index') }}" class="text-xs font-semibold text-amber-600 hover:text-amber-700">
                {{ __('Đặt lại') }}
            </a>
        @endif
    </div>

    <form method="GET" action="{{ route('admin.products.index') }}" id="productFilterForm" class="space-y-5">
        {{-- Giữ nguyên Sort hiện tại nếu có --}}
        <input type="hidden" name="sort" value="{{ request('sort', 'latest') }}">

        {{-- 1. Tìm kiếm từ khóa --}}
        <div>
            <label class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-gray-500">{{ __('Tìm kiếm') }}</label>
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="{{ __('Tên sản phẩm...') }}"
                       class="w-full rounded-xl border-gray-200 bg-gray-50/50 py-2 pl-3 pr-8 text-xs focus:border-amber-500 focus:bg-white focus:ring-amber-500">
                <button type="submit" class="absolute right-2.5 top-2.5 text-gray-400 hover:text-amber-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- 2. Phân loại Danh mục Cha - Con (Thông minh, phân nhóm rõ ràng) --}}
        <div x-data="{ expanded: true }" class="border-t border-gray-100 pt-4">
            <button type="button" @click="expanded = !expanded" class="flex w-full items-center justify-between text-left">
                <span class="text-xs font-bold uppercase tracking-wider text-gray-500">{{ __('Danh mục thực đơn') }}</span>
                <svg class="h-4 w-4 transform text-gray-400 transition-transform" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="expanded" x-collapse class="mt-3 space-y-3">
                {{-- Lựa chọn Tất cả --}}
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="category_id" value="" 
                           @checked(!request('category_id'))
                           onchange="document.getElementById('productFilterForm').submit()"
                           class="h-3.5 w-3.5 border-gray-300 text-amber-500 focus:ring-amber-500">
                    <span class="text-xs {{ !request('category_id') ? 'font-bold text-amber-600' : 'text-gray-600' }}">{{ __('Tất cả danh mục') }}</span>
                </label>

                {{-- Từng nhóm Cha và các Con của nó --}}
                @foreach ($categories->whereNull('parent_id') as $parent)
                    <div x-data="{ openGroup: {{ request('category_id') == $parent->id || $parent->children->pluck('id')->contains(request('category_id')) ? 'true' : 'false' }} }" 
                         class="rounded-xl border border-gray-100 bg-gray-50/40 p-2.5">
                        
                        {{-- Tiêu đề Danh mục Cha --}}
                        <div class="flex items-center justify-between">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="category_id" value="{{ $parent->id }}"
                                       @checked(request('category_id') == $parent->id)
                                       onchange="document.getElementById('productFilterForm').submit()"
                                       class="h-3.5 w-3.5 border-gray-300 text-amber-500 focus:ring-amber-500">
                                <span class="text-xs font-semibold {{ request('category_id') == $parent->id ? 'text-amber-600 font-bold' : 'text-gray-800' }}">
                                    {{ $parent->name }}
                                </span>
                            </label>
                            
                            @if($parent->children->isNotEmpty())
                                <button type="button" @click="openGroup = !openGroup" class="p-1 text-gray-400 hover:text-gray-600">
                                    <svg class="h-3.5 w-3.5 transform transition-transform" :class="openGroup ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            @endif
                        </div>

                        {{-- Danh sách Danh mục Con --}}
                        @if($parent->children->isNotEmpty())
                            <div x-show="openGroup" x-collapse class="mt-2 space-y-1.5 border-t border-gray-200/60 pt-2 pl-4">
                                @foreach ($parent->children as $child)
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="category_id" value="{{ $child->id }}"
                                               @checked(request('category_id') == $child->id)
                                               onchange="document.getElementById('productFilterForm').submit()"
                                               class="h-3 w-3 border-gray-300 text-amber-500 focus:ring-amber-500">
                                        <span class="text-[11px] {{ request('category_id') == $child->id ? 'font-bold text-amber-600' : 'text-gray-600' }}">
                                            {{ $child->name }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        {{-- 3. Phân loại Loại món (Food / Drink) --}}
        <div class="border-t border-gray-100 pt-4">
            <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-gray-500">{{ __('Loại sản phẩm') }}</label>
            <div class="grid grid-cols-3 gap-1.5">
                @foreach (['' => 'Tất cả', 'food' => 'Food 🥐', 'drink' => 'Drink ☕'] as $val => $label)
                    <label class="flex cursor-pointer items-center justify-center rounded-lg border py-1.5 text-center text-xs transition
                                  {{ request('type', '') === $val ? 'border-amber-500 bg-amber-50 font-bold text-amber-700' : 'border-gray-200 bg-white text-gray-600 hover:bg-gray-50' }}">
                        <input type="radio" name="type" value="{{ $val }}" class="sr-only"
                               @checked(request('type', '') === $val)
                               onchange="document.getElementById('productFilterForm').submit()">
                        <span>{{ __($label) }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- 4. Trạng thái kinh doanh --}}
        <div class="border-t border-gray-100 pt-4">
            <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-gray-500">{{ __('Trạng thái') }}</label>
            <div class="space-y-1.5">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="status" value="" @checked(!request('status'))
                           onchange="document.getElementById('productFilterForm').submit()"
                           class="h-3.5 w-3.5 border-gray-300 text-amber-500 focus:ring-amber-500">
                    <span class="text-xs text-gray-600">{{ __('Tất cả trạng thái') }}</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="status" value="active" @checked(request('status') === 'active')
                           onchange="document.getElementById('productFilterForm').submit()"
                           class="h-3.5 w-3.5 border-gray-300 text-amber-500 focus:ring-amber-500">
                    <span class="text-xs text-gray-600">{{ __('Đang bán') }}</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="status" value="inactive" @checked(request('status') === 'inactive')
                           onchange="document.getElementById('productFilterForm').submit()"
                           class="h-3.5 w-3.5 border-gray-300 text-amber-500 focus:ring-amber-500">
                    <span class="text-xs text-gray-600">{{ __('Ngừng bán') }}</span>
                </label>
            </div>
        </div>
    </form>
</div>