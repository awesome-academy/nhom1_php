<div class="flex flex-col gap-3 rounded-2xl border border-[#EADBCE] bg-white/90 p-4 shadow-sm sm:flex-row sm:items-center">
    <div class="relative flex-1">
        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-[#A39284]">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        <input type="text" x-model="filters.keyword" @input.debounce.400ms="fetchCategories()"
               placeholder="{{ __('Tìm nhóm món theo tên...') }}"
               class="w-full rounded-xl border border-[#EADBCE] bg-[#FAF5F1]/60 py-2.5 pl-10 pr-4 text-sm text-[#2B1E19] placeholder-[#A39284] focus:border-[#B38352] focus:outline-none focus:ring-4 focus:ring-[#B38352]/15">
    </div>
    
    <select x-model="filters.parent" @change="fetchCategories()"
            class="rounded-xl border border-[#EADBCE] bg-[#FAF5F1]/60 py-2.5 px-3.5 text-sm text-[#2B1E19] focus:border-[#B38352] focus:outline-none focus:ring-4 focus:ring-[#B38352]/15">
        <option value="">{{ __('Tất cả phân loại') }}</option>
        <option value="null">{{ __('Chỉ danh mục gốc') }}</option>
        <template x-for="cat in categories.filter(c => !c.parent_id)" :key="cat.id">
            <option :value="cat.id" x-text="'Thuộc nhóm: ' + cat.name"></option>
        </template>
    </select>
</div>