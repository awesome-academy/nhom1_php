<div class="space-y-6 lg:col-span-5">
    <div>
        <div class="mb-2 inline-flex items-center gap-2">
            <span class="text-[11px] font-bold uppercase tracking-[0.2em] text-[#B38352]">{{ __('Thông tin') }}</span>
            <span class="h-px w-8 bg-[#B38352]/60"></span>
        </div>
        <h2 class="text-2xl font-bold tracking-tight text-[#2B1E19] sm:text-3xl">{{ __('Kết Nối Với Quán') }}</h2>
        <p class="mt-2 text-sm leading-relaxed text-[#736357]">
            {{ __('Ghé thăm trực tiếp trải nghiệm không gian hoặc liên hệ với chúng tôi qua các kênh hỗ trợ.') }}
        </p>
    </div>

    <div class="space-y-3.5">
        {{-- Địa chỉ --}}
        <div class="group flex items-start gap-4 rounded-2xl border border-[#EADBCE] bg-white p-5 shadow-xs transition hover:border-[#B38352]/60 hover:shadow-md">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#2B1E19] text-[#FAF5F1] shadow-xs transition group-hover:bg-[#B38352]">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-[#A39284]">{{ __('Địa chỉ cửa hàng') }}</p>
                <p class="mt-0.5 text-sm font-semibold text-[#2B1E19]">123 Đường Cà Phê, Quận 1</p>
                <p class="text-xs text-[#736357]">TP. Hồ Chí Minh, Việt Nam</p>
            </div>
        </div>

        {{-- Giờ mở cửa --}}
        <div class="group flex items-start gap-4 rounded-2xl border border-[#EADBCE] bg-white p-5 shadow-xs transition hover:border-[#B38352]/60 hover:shadow-md">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#2B1E19] text-[#FAF5F1] shadow-xs transition group-hover:bg-[#B38352]">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-[#A39284]">{{ __('Thời gian phục vụ') }}</p>
                <p class="mt-0.5 text-sm font-semibold text-[#2B1E19]">Thứ 2 – Thứ 6: <span class="font-normal text-[#736357]">07:00 – 22:00</span></p>
                <p class="text-sm font-semibold text-[#2B1E19]">Thứ 7 – Chủ nhật: <span class="font-normal text-[#736357]">07:00 – 23:00</span></p>
            </div>
        </div>

        {{-- Email & Hotline --}}
        <div class="grid grid-cols-1 gap-3.5 sm:grid-cols-2">
            <div class="group flex items-start gap-3.5 rounded-2xl border border-[#EADBCE] bg-white p-4 shadow-xs transition hover:border-[#B38352]/60 hover:shadow-md">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#FAF5F1] text-[#B38352] ring-1 ring-[#EADBCE] transition group-hover:bg-[#B38352] group-hover:text-white">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-[#A39284]">{{ __('Email') }}</p>
                    <a href="mailto:hello@brewbite.vn" class="mt-0.5 block truncate text-xs font-bold text-[#2B1E19] transition hover:text-[#B38352]">
                        hello@brewbite.vn
                    </a>
                </div>
            </div>

            <div class="group flex items-start gap-3.5 rounded-2xl border border-[#EADBCE] bg-white p-4 shadow-xs transition hover:border-[#B38352]/60 hover:shadow-md">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#FAF5F1] text-[#B38352] ring-1 ring-[#EADBCE] transition group-hover:bg-[#B38352] group-hover:text-white">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-[#A39284]">{{ __('Hotline') }}</p>
                    <a href="tel:+84901234567" class="mt-0.5 block truncate text-xs font-bold text-[#2B1E19] transition hover:text-[#B38352]">
                        +84 90 123 4567
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>