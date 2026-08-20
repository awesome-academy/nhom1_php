@if ($product->full_description)
    <div class="mt-12 overflow-hidden rounded-[28px] border border-[#EADBCE] bg-white shadow-sm font-sans">
        <div class="flex items-center gap-2.5 border-b border-[#EADBCE]/60 bg-[#FAF5F1]/80 px-8 py-5">
            <div class="flex h-8 w-8 items-center justify-center rounded-xl bg-[#2B1E19] text-[#B38352]">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h2 class="font-sans text-lg font-bold text-[#2B1E19]">{{ __('Mô tả chi tiết') }}</h2>
        </div>
        <div class="p-8">
            <p class="whitespace-pre-line text-sm leading-relaxed text-[#736357]">{{ $product->full_description }}</p>
        </div>
    </div>
@endif