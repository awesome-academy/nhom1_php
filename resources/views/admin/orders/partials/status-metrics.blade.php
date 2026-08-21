<div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-5">
    <button type="button" @click="setFilter('')" 
            class="flex flex-col rounded-2xl border bg-white p-3.5 text-left shadow-xs transition hover:border-[#B38352] hover:shadow-sm"
            :class="filters.status === '' ? 'border-[#B38352] ring-2 ring-[#B38352]/20' : 'border-[#EADBCE]/70'">
        <span class="text-[11px] font-bold uppercase tracking-wider text-gray-500">{{ __('Tất cả đơn') }}</span>
        <span class="mt-1 text-xl font-extrabold text-[#2B1E19]" x-text="metrics.total ?? (meta.total ?? '—')"></span>
    </button>

    <button type="button" @click="setFilter('pending')" 
            class="flex flex-col rounded-2xl border bg-white p-3.5 text-left shadow-xs transition hover:border-amber-500 hover:shadow-sm"
            :class="filters.status === 'pending' ? 'border-amber-500 ring-2 ring-amber-500/20' : 'border-[#EADBCE]/70'">
        <div class="flex items-center gap-1.5 text-[11px] font-bold uppercase tracking-wider text-amber-700">
            <span class="h-2 w-2 rounded-full bg-amber-500"></span>
            <span>{{ __('Chờ xác nhận') }}</span>
        </div>
        <span class="mt-1 text-xl font-extrabold text-amber-900" x-text="metrics.pending ?? '—'"></span>
    </button>

    <button type="button" @click="setFilter('confirmed')" 
            class="flex flex-col rounded-2xl border bg-white p-3.5 text-left shadow-xs transition hover:border-sky-500 hover:shadow-sm"
            :class="filters.status === 'confirmed' ? 'border-sky-500 ring-2 ring-sky-500/20' : 'border-[#EADBCE]/70'">
        <div class="flex items-center gap-1.5 text-[11px] font-bold uppercase tracking-wider text-sky-700">
            <span class="h-2 w-2 rounded-full bg-sky-500"></span>
            <span>{{ __('Đã tiếp nhận') }}</span>
        </div>
        <span class="mt-1 text-xl font-extrabold text-sky-900" x-text="metrics.confirmed ?? '—'"></span>
    </button>

    <button type="button" @click="setFilter('preparing')" 
            class="flex flex-col rounded-2xl border bg-white p-3.5 text-left shadow-xs transition hover:border-indigo-500 hover:shadow-sm"
            :class="filters.status === 'preparing' ? 'border-indigo-500 ring-2 ring-indigo-500/20' : 'border-[#EADBCE]/70'">
        <div class="flex items-center gap-1.5 text-[11px] font-bold uppercase tracking-wider text-indigo-700">
            <span class="h-2 w-2 rounded-full bg-indigo-500 animate-pulse"></span>
            <span>{{ __('Đang làm món') }}</span>
        </div>
        <span class="mt-1 text-xl font-extrabold text-indigo-900" x-text="metrics.preparing ?? '—'"></span>
    </button>

    <button type="button" @click="setFilter('completed')" 
            class="flex flex-col rounded-2xl border bg-white p-3.5 text-left shadow-xs transition hover:border-emerald-500 hover:shadow-sm"
            :class="filters.status === 'completed' ? 'border-emerald-500 ring-2 ring-emerald-500/20' : 'border-[#EADBCE]/70'">
        <div class="flex items-center gap-1.5 text-[11px] font-bold uppercase tracking-wider text-emerald-700">
            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
            <span>{{ __('Hoàn thành') }}</span>
        </div>
        <span class="mt-1 text-xl font-extrabold text-emerald-900" x-text="metrics.completed ?? '—'"></span>
    </button>
</div>