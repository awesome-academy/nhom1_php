<div x-data="{
        show: false,
        type: 'info',
        title: '',
        message: '',
        timer: null,
        display(e) {
            this.type = e.detail.type ?? 'info';
            this.title = e.detail.title ?? '';
            this.message = e.detail.message ?? '';
            this.show = true;
            clearTimeout(this.timer);
            this.timer = setTimeout(() => { this.show = false; }, 4000);
        },
        get borderClass() {
            return { 
                success: 'border-emerald-200 bg-emerald-50/95 text-emerald-900', 
                error: 'border-rose-200 bg-rose-50/95 text-rose-900', 
                info: 'border-amber-200 bg-amber-50/95 text-amber-900' 
            }[this.type] ?? 'border-[#EADBCE] bg-white text-[#2B1E19]';
        },
        get iconClass() {
            return { 
                success: 'bg-emerald-600 text-white', 
                error: 'bg-rose-600 text-white', 
                info: 'bg-[#B38352] text-white' 
            }[this.type] ?? 'bg-[#B38352] text-white';
        }
    }" 
    x-on:show-toast.window="display($event)" 
    x-show="show" 
    x-cloak
    x-transition:enter="transition ease-out duration-300" 
    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:translate-x-4"
    x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0" 
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" 
    x-transition:leave-end="opacity-0 translate-y-2"
    :class="borderClass"
    class="fixed bottom-6 right-6 z-[9999] flex max-w-sm items-start gap-3 rounded-2xl border p-4 shadow-2xl backdrop-blur-md"
    role="alert">
    
    <div :class="iconClass" class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-xl shadow-xs">
        <svg x-show="type === 'success'" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
        </svg>
        <svg x-show="type === 'error'" x-cloak class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
        <svg x-show="type === 'info'" x-cloak class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
    </div>

    <div class="flex-1 min-w-0">
        <p class="text-xs font-bold" x-text="title"></p>
        <p class="mt-0.5 text-[11px] leading-relaxed opacity-90" x-text="message"></p>
    </div>

    <button type="button" @click="show = false" class="text-gray-400 hover:text-[#2B1E19]">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>