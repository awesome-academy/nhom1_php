@extends('layouts.admin')

@section('content')
<div class="mx-auto max-w-4xl space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-gray-500 hover:text-[#2B1E19] transition">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                {{ __('Quay lại danh sách') }}
            </a>
            <h1 class="mt-1 font-sans text-2xl font-bold tracking-tight text-[#2B1E19] sm:text-3xl">{{ __('Chỉnh sửa sản phẩm') }}</h1>
        </div>
    </div>

    <!-- Thông báo kết quả thao tác ảnh nếu có -->
    @if (session('success'))
        <div class="rounded-2xl border border-green-200 bg-green-50 p-4 text-xs font-bold text-green-700">
            ✓ {{ session('success') }}
        </div>
    @endif

    <div class="space-y-6 rounded-[24px] border border-[#EADBCE] bg-white p-6 shadow-sm ring-1 ring-black/5 sm:p-8">
        
        <!-- 1. Quản lý Ảnh hiện có trong hệ thống -->
        <div class="border-b border-[#EADBCE]/60 pb-6">
            <div class="flex items-center justify-between mb-2">
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-[#4A3B32]">{{ __('Ảnh hiện tại của món') }}</h3>
                    <p class="text-xs text-gray-500">{{ __('Mỗi sản phẩm có 1 ảnh đại diện. Click để đổi ảnh đại diện hoặc xoá bớt ảnh.') }}</p>
                </div>
                <span class="text-xs font-semibold text-gray-400">{{ $product->images->count() }} {{ __('ảnh') }}</span>
            </div>

            @if ($product->images->isEmpty())
                <div class="rounded-2xl border border-dashed border-gray-200 bg-gray-50 p-6 text-center text-xs text-gray-400">
                    {{ __('Chưa có ảnh nào. Hãy thêm ảnh bên dưới.') }}
                </div>
            @else
                <div class="mt-3 grid grid-cols-2 gap-3.5 sm:grid-cols-4">
                    @foreach ($product->images as $image)
                        <div class="group relative overflow-hidden rounded-2xl border-2 {{ $image->is_primary ? 'border-[#B38352] ring-2 ring-[#B38352]/20' : 'border-gray-200' }}">
                            <img src="{{ asset('storage/'.$image->image_path) }}" class="h-32 w-full object-cover">

                            <!-- Badge Đại diện hoặc Nút Đặt làm đại diện -->
                            @if ($image->is_primary)
                                <span class="absolute left-2 top-2 rounded-full bg-[#B38352] px-2.5 py-0.5 text-[10px] font-bold text-[#FAF5F1] shadow-sm">
                                    ★ {{ __('Đại diện') }}
                                </span>
                            @else
                                <form method="POST" action="{{ route('admin.products.images.primary', [$product, $image]) }}" class="absolute left-2 top-2">
                                    @csrf
                                    @method('patch')
                                    <button type="submit" class="rounded-full bg-black/70 px-2.5 py-0.5 text-[10px] font-bold text-white opacity-0 transition group-hover:opacity-100 hover:bg-[#B38352]">
                                        {{ __('Đặt làm chính') }}
                                    </button>
                                </form>
                            @endif

                            <!-- Nút Xoá Ảnh -->
                            <form method="POST" action="{{ route('admin.products.images.destroy', [$product, $image]) }}" 
                                  class="absolute right-2 top-2"
                                  onsubmit="return confirm('{{ __('Bạn chắc chắn muốn xoá ảnh này?') }}');">
                                @csrf
                                @method('delete')
                                <button type="submit" class="flex h-6 w-6 items-center justify-center rounded-full bg-black/70 text-xs text-white opacity-0 transition group-hover:opacity-100 hover:bg-red-600">
                                    &times;
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- 2. Form Cập nhật thông tin & Upload thêm ảnh mới -->
        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
            @csrf
            @method('put')

            <!-- Trường dữ liệu -->
            @include('admin.products._form-fields', ['categories' => $categories, 'product' => $product])

            <!-- Upload Thêm Ảnh Mới -->
            <div x-data="editProductImageUploader()" class="border-t border-[#EADBCE]/60 pt-6 mt-6">
                <label class="block text-xs font-bold uppercase tracking-wider text-[#4A3B32] mb-1">
                    {{ __('Tải thêm ảnh mới vào Album') }}
                </label>
                <p class="text-xs text-gray-500 mb-3">{{ __('Ảnh mới tải lên sẽ tự động lưu vào bộ sưu tập của món.') }}</p>

                <div class="grid grid-cols-2 gap-3.5 sm:grid-cols-4">
                    <template x-for="(image, index) in images" :key="index">
                        <div class="group relative overflow-hidden rounded-2xl border border-gray-200">
                            <img :src="image.url" class="h-32 w-full object-cover">
                            <button type="button" @click="removeImage(index)"
                                    class="absolute right-2 top-2 flex h-6 w-6 items-center justify-center rounded-full bg-black/70 text-xs text-white shadow-md hover:bg-red-600">
                                &times;
                            </button>
                        </div>
                    </template>

                    <label class="flex h-32 cursor-pointer flex-col items-center justify-center gap-1.5 rounded-2xl border-2 border-dashed border-[#EADBCE] bg-[#FAF5F1]/50 text-gray-400 transition hover:border-[#B38352] hover:bg-amber-50/40 hover:text-[#B38352]">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        <span class="text-xs font-bold uppercase tracking-wider">{{ __('Thêm ảnh mới') }}</span>
                        <input type="file" name="images[]" x-ref="fileInput" multiple accept="image/*" class="hidden" @change="handleFiles($event)">
                    </label>
                </div>
                <x-input-error class="mt-2 text-xs" :messages="$errors->get('images.*')" />
            </div>

            <!-- Nút Lưu -->
            <div class="mt-8 flex items-center justify-end gap-3 border-t border-[#EADBCE]/60 pt-6">
                <a href="{{ route('admin.products.index') }}" class="rounded-xl border border-[#EADBCE] bg-white px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-[#4A3B32] transition hover:bg-[#FAF5F1]">
                    {{ __('Huỷ bỏ') }}
                </a>
                <button type="submit" class="rounded-xl bg-[#2B1E19] px-6 py-2.5 text-xs font-bold uppercase tracking-wider text-[#FAF5F1] shadow-md transition hover:bg-[#B38352] active:scale-95">
                    {{ __('Lưu thay đổi') }}
                </button>
            </div>
        </form>

    </div>
</div>
@endsection

@push('scripts')
<script>
    function editProductImageUploader() {
        return {
            images: [],
            rawFiles: [],

            handleFiles(event) {
                const selectedFiles = Array.from(event.target.files);
                if (!selectedFiles.length) return;

                // Nối tiếp file mới
                this.rawFiles = [...this.rawFiles, ...selectedFiles];

                this.syncData();
            },

            removeImage(index) {
                this.rawFiles.splice(index, 1);
                this.syncData();
            },

            syncData() {
                this.images = this.rawFiles.map(file => ({
                    url: URL.createObjectURL(file)
                }));

                const dt = new DataTransfer();
                this.rawFiles.forEach(file => dt.items.add(file));
                this.$refs.fileInput.files = dt.files;
            }
        };
    }

    function productFormManager(initialVariants = [], defaultType = 'drink') {
        return {
            productType: defaultType,
            variants: initialVariants.length > 0 ? initialVariants : [],

            addVariant(group = 'topping', name = '', price = 0) {
                this.variants.push({
                    variant_group: group,
                    name: name,
                    extra_price: price
                });
            },

            removeVariant(index) {
                this.variants.splice(index, 1);
            },

            addPresetVariants() {
                const presets = [
                    { variant_group: 'size', name: 'Size M (Tiêu chuẩn)', extra_price: 0 },
                    { variant_group: 'size', name: 'Size L (+500ml)', extra_price: 10000 },
                    { variant_group: 'sugar', name: '100% Đường', extra_price: 0 },
                    { variant_group: 'sugar', name: '50% Đường', extra_price: 0 },
                    { variant_group: 'sugar', name: 'Không đường (0%)', extra_price: 0 },
                    { variant_group: 'ice', name: '100% Đá', extra_price: 0 },
                    { variant_group: 'ice', name: 'Ít đá', extra_price: 0 },
                ];

                presets.forEach(p => {
                    const exists = this.variants.some(v => v.variant_group === p.variant_group && v.name === p.name);
                    if (!exists) this.variants.push(p);
                });
            }
        };
    }
</script>
@endpush