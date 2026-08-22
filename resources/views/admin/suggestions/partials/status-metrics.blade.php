<div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
    <!-- Tất cả -->
    <button type="button" x-on:click="setFilter('')"
            class="flex flex-col rounded-2xl border bg-white p-3.5 text-left shadow-xs transition hover:border-[#B38352] hover:shadow-sm"
            :class="filters.status === '' ? 'border-[#B38352] ring-2 ring-[#B38352]/20' : 'border-[#EADBCE]/70'">
        <span class="text-[11px] font-bold uppercase tracking-wider text-gray-500">{{ __('Tất cả') }}</span>
        <span class="mt-1 text-xl font-extrabold text-[#2B1E19]" x-text="meta.total ?? '—'"></span>
    </button>

    <!-- Chờ xử lý -->
    <button type="button" x-on:click="setFilter('pending')"
            class="flex flex-col rounded-2xl border bg-white p-3.5 text-left shadow-xs transition hover:border-amber-500 hover:shadow-sm"
            :class="filters.status === 'pending' ? 'border-amber-500 ring-2 ring-amber-500/20' : 'border-[#EADBCE]/70'">
        <div class="flex items-center gap-1.5 text-[11px] font-bold uppercase tracking-wider text-amber-700">
            <span class="h-2 w-2 animate-pulse rounded-full bg-amber-500"></span>
            <span>{{ __('Chờ xử lý') }}</span>
        </div>
        <span class="mt-1 text-xl font-extrabold text-amber-900" x-text="metrics.pending"></span>
    </button>

    <!-- Đã duyệt -->
    <button type="button" x-on:click="setFilter('reviewed')"
            class="flex flex-col rounded-2xl border bg-white p-3.5 text-left shadow-xs transition hover:border-emerald-500 hover:shadow-sm"
            :class="filters.status === 'reviewed' ? 'border-emerald-500 ring-2 ring-emerald-500/20' : 'border-[#EADBCE]/70'">
        <div class="flex items-center gap-1.5 text-[11px] font-bold uppercase tracking-wider text-emerald-700">
            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
            <span>{{ __('Đã duyệt') }}</span>
        </div>
        <span class="mt-1 text-xl font-extrabold text-emerald-900" x-text="metrics.reviewed"></span>
    </button>

    <!-- Từ chối -->
    <button type="button" x-on:click="setFilter('rejected')"
            class="flex flex-col rounded-2xl border bg-white p-3.5 text-left shadow-xs transition hover:border-rose-500 hover:shadow-sm"
            :class="filters.status === 'rejected' ? 'border-rose-500 ring-2 ring-rose-500/20' : 'border-[#EADBCE]/70'">
        <div class="flex items-center gap-1.5 text-[11px] font-bold uppercase tracking-wider text-rose-700">
            <span class="h-2 w-2 rounded-full bg-rose-500"></span>
            <span>{{ __('Từ chối') }}</span>
        </div>
        <span class="mt-1 text-xl font-extrabold text-rose-900" x-text="metrics.rejected"></span>
    </button>
</div>