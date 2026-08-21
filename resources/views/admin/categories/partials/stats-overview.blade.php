<div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
    <!-- Nhóm món chính -->
    <div class="flex items-center gap-4 rounded-2xl border border-[#EADBCE] bg-white/95 p-4 shadow-sm">
        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#FAF5F1] text-[#B38352]">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
        </div>
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-[#A39284]">{{ __('Danh mục gốc') }}</p>
            <p class="font-sans text-xl font-bold text-[#2B1E19]" x-text="stats.rootCount"></p>
        </div>
    </div>

    <!-- Phân loại chi tiết -->
    <div class="flex items-center gap-4 rounded-2xl border border-[#EADBCE] bg-white/95 p-4 shadow-sm">
        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#FAF5F1] text-[#B38352]">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7" />
            </svg>
        </div>
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-[#A39284]">{{ __('Nhóm con chi tiết') }}</p>
            <p class="font-sans text-xl font-bold text-[#2B1E19]" x-text="stats.subCount"></p>
        </div>
    </div>

    <!-- Tổng món trong Menu -->
    <div class="flex items-center gap-4 rounded-2xl border border-[#EADBCE] bg-white/95 p-4 shadow-sm">
        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#2B1E19] text-[#FAF5F1]">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
        </div>
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-[#A39284]">{{ __('Món đang phục vụ') }}</p>
            <p class="font-sans text-xl font-bold text-[#2B1E19]" x-text="stats.totalProducts + ' món'"></p>
        </div>
    </div>
</div>