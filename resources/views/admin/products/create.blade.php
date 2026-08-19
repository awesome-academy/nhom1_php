@extends('layouts.admin')

@section('content')
<div class="mx-auto max-w-4xl space-y-6">
    <!-- Navigation Header -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-gray-500 hover:text-[#2B1E19] transition">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                {{ __('Quay lại danh sách') }}
            </a>
            <h1 class="mt-1 font-sans text-2xl font-bold tracking-tight text-[#2B1E19] sm:text-3xl">{{ __('Thêm sản phẩm mới') }}</h1>
        </div>
    </div>

    <!-- Main Card Form -->
    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data"
          class="rounded-[24px] border border-[#EADBCE] bg-white p-6 shadow-sm ring-1 ring-black/5 sm:p-8">
        @csrf

        <!-- Thông tin trường nhập -->
        @include('admin.products._form-fields', ['categories' => $categories, 'product' => null])

        <!-- Upload Album Ảnh (Alpine.js Uploader) -->
        <div x-data="createProductImageUploader()" class="border-t border-[#EADBCE]/60 pt-6 mt-6">
            <div class="flex items-center justify-between mb-2">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-[#4A3B32]">
                        {{ __('Bộ sưu tập hình ảnh') }} <span class="text-red-500">*</span>
                    </label>
                    <p class="text-xs text-gray-500">{{ __('Tải lên một hoặc nhiều ảnh. Click chọn nút radio để đặt ảnh làm đại diện.') }}</p>
                </div>
                <span class="text-xs font-semibold text-[#B38352]" x-show="images.length > 0" x-text="images.length + ' {{ __('ảnh đã chọn') }}'"></span>
            </div>

            <div class="mt-3 grid grid-cols-2 gap-3.5 sm:grid-cols-4">
                <!-- Danh sách preview ảnh vừa chọn -->
                <template x-for="(image, index) in images" :key="index">
                    <div class="group relative overflow-hidden rounded-2xl border-2 transition"
                         :class="primaryIndex === index ? 'border-[#B38352] ring-2 ring-[#B38352]/20' : 'border-gray-200'">
                        <img :src="image.url" class="h-32 w-full object-cover">
                        
                        <!-- Nút Xoá Preview -->
                        <button type="button" @click="removeImage(index)"
                                class="absolute right-2 top-2 flex h-6 w-6 items-center justify-center rounded-full bg-black/70 text-xs text-white shadow-md transition hover:bg-red-600">
                            &times;
                        </button>

                        <!-- Đặt làm ảnh đại diện -->
                        <label class="absolute inset-x-0 bottom-0 flex cursor-pointer items-center justify-center gap-1.5 bg-black/65 py-1.5 text-[11px] font-bold text-white backdrop-blur-xs transition group-hover:bg-[#2B1E19]/90">
                            <input type="radio" name="primary_image_index" :value="index" x-model="primaryIndex" class="h-3.5 w-3.5 text-[#B38352] focus:ring-[#B38352]">
                            <span x-text="primaryIndex === index ? '★ Ảnh đại diện' : 'Đặt đại diện'"></span>
                        </label>
                    </div>
                </template>

                <!-- Nút Bấm Thêm Ảnh -->
                <label class="flex h-32 cursor-pointer flex-col items-center justify-center gap-1.5 rounded-2xl border-2 border-dashed border-[#EADBCE] bg-[#FAF5F1]/50 text-gray-400 transition hover:border-[#B38352] hover:bg-amber-50/40 hover:text-[#B38352]">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    <span class="text-xs font-bold uppercase tracking-wider">{{ __('Chọn ảnh') }}</span>
                    <input type="file" name="images[]" x-ref="fileInput" multiple accept="image/*" class="hidden" @change="handleFiles($event)">
                </label>
            </div>
            <x-input-error class="mt-2 text-xs" :messages="$errors->get('images.*')" />
        </div>

        <!-- Actions Buttons -->
        <div class="mt-8 flex items-center justify-end gap-3 border-t border-[#EADBCE]/60 pt-6">
            <a href="{{ route('admin.products.index') }}" class="rounded-xl border border-[#EADBCE] bg-white px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-[#4A3B32] transition hover:bg-[#FAF5F1]">
                {{ __('Huỷ bỏ') }}
            </a>
            <button type="submit" class="rounded-xl bg-[#2B1E19] px-6 py-2.5 text-xs font-bold uppercase tracking-wider text-[#FAF5F1] shadow-md transition hover:bg-[#B38352] active:scale-95">
                {{ __('Tạo sản phẩm') }}
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function createProductImageUploader() {
        return {
            images: [],       
            rawFiles: [],     
            primaryIndex: 0,

            handleFiles(event) {
                const selectedFiles = Array.from(event.target.files);
                if (!selectedFiles.length) return;

                
                this.rawFiles = [...this.rawFiles, ...selectedFiles];

                this.syncData();
            },

            removeImage(index) {
                this.rawFiles.splice(index, 1);
                
                if (this.primaryIndex >= this.rawFiles.length) {
                    this.primaryIndex = this.rawFiles.length ? 0 : null;
                }
                
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
</script>
@endpush