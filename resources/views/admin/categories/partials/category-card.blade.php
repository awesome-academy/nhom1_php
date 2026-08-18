<div class="overflow-hidden rounded-2xl border border-[#EADBCE] bg-white shadow-sm transition hover:shadow-md">
    <!-- Parent Row -->
    <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4">
        <div class="flex items-center gap-3.5">
            <!-- F&B Icon badge -->
            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#2B1E19] text-[#FAF5F1] shadow-inner">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <p class="font-serif text-base font-bold text-[#2B1E19]" x-text="parent.name"></p>
                    <span class="inline-flex items-center rounded-md bg-[#FAF5F1] px-2 py-0.5 font-mono text-[11px] text-[#A39284] ring-1 ring-[#EADBCE]" x-text="'slug: ' + parent.slug"></span>
                </div>
                <p x-show="parent.description" class="mt-0.5 text-xs text-[#736357]" x-text="parent.description"></p>
            </div>
        </div>

        <div class="flex items-center gap-2.5">
            <!-- Product Count Pill -->
            <span class="inline-flex items-center gap-1 rounded-full bg-[#FAF5F1] px-3.5 py-1 text-xs font-bold text-[#8E6238] ring-1 ring-[#EADBCE]">
                <span class="h-1.5 w-1.5 rounded-full bg-[#8E6238]"></span>
                <span x-text="(parent.total_products_count ?? parent.products_count ?? 0) + ' món ăn/uống'"></span>
            </span>

            <button @click="openEditModal(parent)" title="Chỉnh sửa" class="rounded-xl border border-transparent p-2 text-[#736357] transition hover:border-[#EADBCE] hover:bg-[#FAF5F1] hover:text-[#B38352]">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
            </button>
            <button @click="confirmDelete(parent)" title="Xoá" class="rounded-xl border border-transparent p-2 text-red-500 transition hover:border-red-200 hover:bg-red-50">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
            </button>
        </div>
    </div>

    <!-- Children List (Sub-categories) -->
    <div x-show="parent.children.length" class="divide-y divide-[#EADBCE]/60 border-t border-dashed border-[#EADBCE] bg-[#FAF5F1]/40">
        <template x-for="child in parent.children" :key="child.id">
            <div class="flex flex-wrap items-center justify-between gap-3 py-3 pl-14 pr-5 transition hover:bg-[#FAF5F1]/80">
                <div class="flex items-center gap-2">
                    <span class="text-xs text-[#B38352]">↳</span>
                    <p class="text-sm font-semibold text-[#4A3B32]" x-text="child.name"></p>
                    <span class="rounded bg-white px-1.5 py-0.5 font-mono text-[10px] text-[#A39284] ring-1 ring-[#EADBCE]/80" x-text="child.slug"></span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="rounded-full bg-white px-2.5 py-0.5 text-[11px] font-semibold text-[#8E6238] ring-1 ring-[#EADBCE]"
                          x-text="(child.products_count ?? 0) + ' món'"></span>
                    <button @click="openEditModal(child)" class="rounded-lg p-1.5 text-[#736357] transition hover:bg-white hover:text-[#B38352]">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                    </button>
                    <button @click="confirmDelete(child)" class="rounded-lg p-1.5 text-red-500 transition hover:bg-white">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                </div>
            </div>
        </template>
    </div>
</div>