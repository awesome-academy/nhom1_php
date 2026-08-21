<template x-teleport="body">
    <div x-show="cartSuccess" 
         x-cloak 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[9999] flex items-center justify-center bg-[#2B1E19]/60 px-4 backdrop-blur-md">
        
        <div @click.outside="cartSuccess = false"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             class="relative w-full max-w-md overflow-hidden rounded-[28px] border border-[#EADBCE] bg-white p-7 text-center shadow-2xl">
            
            <!-- Nút đóng góc phải -->
            <button type="button" @click="cartSuccess = false" 
                    class="absolute right-4 top-4 rounded-xl p-1.5 text-gray-400 transition hover:bg-[#FAF5F1] hover:text-[#2B1E19]">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Icon Thành công -->
            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 ring-8 ring-emerald-50/50">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <h3 class="mt-5 font-sans text-xl font-bold text-[#2B1E19]">
                {{ __('Thêm vào giỏ thành công!') }}
            </h3>
            
            <p class="mt-2 text-xs leading-relaxed text-[#736357]">
                {{ __('Món') }} <b class="text-[#2B1E19]">{{ $product->name }}</b> (x<span x-text="quantity"></span>) {{ __('đã được thêm vào giỏ hàng của bạn.') }}
            </p>

            <!-- Các nút hành động -->
            <div class="mt-6 flex flex-col gap-2.5 sm:flex-row">
                <button type="button" 
                        @click="cartSuccess = false"
                        class="flex-1 rounded-xl border border-[#EADBCE] bg-white py-2.5 text-xs font-bold uppercase tracking-wider text-[#4A3B32] shadow-xs transition hover:bg-[#FAF5F1] hover:text-[#B38352]">
                    {{ __('Tiếp tục chọn món') }}
                </button>
                <a href="{{ route('cart.index') }}"
                   class="flex-1 rounded-xl bg-[#2B1E19] py-2.5 text-xs font-bold uppercase tracking-wider text-[#FAF5F1] shadow-md transition hover:bg-[#B38352]">
                    {{ __('Đến giỏ hàng') }}
                </a>
            </div>
        </div>
    </div>
</template>