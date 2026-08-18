<template x-teleport="body">
    <div x-show="modalOpen" x-cloak x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-[#2B1E19]/50 px-4 backdrop-blur-full">
        <div @click.outside="closeModal()" class="relative w-full max-w-lg rounded-[28px] border border-[#EADBCE] bg-white p-8 shadow-2xl">
            <button @click="closeModal()" class="absolute right-5 top-5 text-[#A39284] transition hover:text-[#2B1E19]">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
    
            <h2 class="font-sans text-2xl font-bold text-[#2B1E19]" x-text="form.id ? '{{ __('Chỉnh sửa nhóm món') }}' : '{{ __('Thêm nhóm món mới') }}'"></h2>
    
            <form @submit.prevent="submitForm()" class="mt-6 space-y-4">
                <div>
                    <label class="mb-1.5 block text-[11px] font-bold uppercase tracking-wider text-[#4A3B32]">{{ __('Tên nhóm món (VD: Cà Phê Pha Máy, Bánh Mì Nướng...)') }}</label>
                    <input x-model="form.name" required placeholder="{{ __('Nhập tên danh mục...') }}"
                           class="block w-full rounded-2xl border border-[#EADBCE] bg-[#FAF5F1]/50 py-3 px-4 text-sm text-[#2B1E19] focus:border-[#B38352] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#B38352]/15">
                    <p x-show="errors.name" x-text="errors.name?.[0]" class="mt-1 text-xs text-red-600"></p>
                </div>
    
                <div>
                    <label class="mb-1.5 block text-[11px] font-bold uppercase tracking-wider text-[#4A3B32]">{{ __('Slug / Định danh URL (Tự động nếu bỏ trống)') }}</label>
                    <input x-model="form.slug" placeholder="ca-phe-pha-may"
                           class="block w-full rounded-2xl border border-[#EADBCE] bg-[#FAF5F1]/50 py-3 px-4 font-mono text-sm text-[#2B1E19] focus:border-[#B38352] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#B38352]/15">
                    <p x-show="errors.slug" x-text="errors.slug?.[0]" class="mt-1 text-xs text-red-600"></p>
                </div>
    
                <div>
                    <label class="mb-1.5 block text-[11px] font-bold uppercase tracking-wider text-[#4A3B32]">{{ __('Phân nhóm trực thuộc (Danh mục cha)') }}</label>
                    <select x-model="form.parent_id"
                            class="block w-full rounded-2xl border border-[#EADBCE] bg-[#FAF5F1]/50 py-3 px-4 text-sm text-[#2B1E19] focus:border-[#B38352] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#B38352]/15">
                        <option value="">{{ __('— Không thuộc nhóm nào (Là danh mục gốc) —') }}</option>
                        <template x-for="cat in parentOptions" :key="cat.id">
                            <option :value="cat.id" x-text="cat.name"></option>
                        </template>
                    </select>
                    <p x-show="errors.parent_id" x-text="errors.parent_id?.[0]" class="mt-1 text-xs text-red-600"></p>
                </div>
    
                <div>
                    <label class="mb-1.5 block text-[11px] font-bold uppercase tracking-wider text-[#4A3B32]">{{ __('Ghi chú / Mô tả') }}</label>
                    <textarea x-model="form.description" rows="3" placeholder="{{ __('Mô tả ngắn về phân loại món ăn/đồ uống này...') }}"
                              class="block w-full rounded-2xl border border-[#EADBCE] bg-[#FAF5F1]/50 py-3 px-4 text-sm text-[#2B1E19] focus:border-[#B38352] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#B38352]/15"></textarea>
                    <p x-show="errors.description" x-text="errors.description?.[0]" class="mt-1 text-xs text-red-600"></p>
                </div>
    
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="closeModal()" class="rounded-2xl border border-[#EADBCE] bg-white px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-[#4A3B32] transition hover:bg-[#FAF5F1]">
                        {{ __('Huỷ') }}
                    </button>
                    <button type="submit" :disabled="submitting"
                            class="rounded-2xl bg-[#2B1E19] px-6 py-2.5 text-xs font-bold uppercase tracking-wider text-white shadow-md transition hover:bg-[#B38352] disabled:cursor-not-allowed disabled:opacity-50">
                        <span x-show="!submitting" x-text="form.id ? '{{ __('Lưu thay đổi') }}' : '{{ __('Tạo nhóm món') }}'"></span>
                        <span x-show="submitting">{{ __('Đang lưu...') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>