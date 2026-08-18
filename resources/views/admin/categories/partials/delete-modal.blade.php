<template x-teleport="body">
    <div x-show="deleteTarget" x-cloak x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-[#2B1E19]/50 px-4 backdrop-blur-sm">
        <div @click.outside="deleteTarget = null" class="w-full max-w-sm rounded-[28px] border border-red-200 bg-white p-7 shadow-2xl">
            <h3 class="font-sans text-xl font-bold text-red-600">{{ __('Xoá nhóm món?') }}</h3>
            <p class="mt-2 text-sm leading-relaxed text-[#736357]">
                {{ __('Bạn có chắc muốn xoá') }} <strong class="text-[#2B1E19]" x-text="deleteTarget?.name"></strong>?
                <br><span class="text-xs text-[#A39284]">{{ __('Lưu ý: Không thể xoá nếu danh mục đang chứa sản phẩm.') }}</span>
            </p>
            <div class="mt-6 flex justify-end gap-3">
                <button @click="deleteTarget = null" class="rounded-2xl border border-[#EADBCE] bg-white px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-[#4A3B32] transition hover:bg-[#FAF5F1]">
                    {{ __('Huỷ') }}
                </button>
                <button @click="deleteCategory()" class="rounded-2xl bg-red-600 px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-white shadow-md transition hover:bg-red-700">
                    {{ __('Xác nhận xoá') }}
                </button>
            </div>
        </div>
    </div>
</template>