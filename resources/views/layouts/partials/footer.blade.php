<footer class="relative overflow-hidden bg-[#694b37] text-white">
    
    <!-- 1. Background Pattern Hạt Cà Phê Mộc Tinh Tế (Nổi bật trên nền tối) -->
    <div class="pointer-events-none absolute inset-0 opacity-20"
         style="background-image:url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='120' height='120'%3E%3Cg fill='none' stroke='%23B38352' stroke-width='1.3' opacity='0.3'%3E%3Cellipse cx='30' cy='30' rx='9' ry='15' transform='rotate(24 30 30)'/%3E%3Cpath d='M30 17 Q26 30 30 43' transform='rotate(24 30 30)'/%3E%3Cellipse cx='92' cy='86' rx='9' ry='15' transform='rotate(-18 92 86)'/%3E%3Cpath d='M92 73 Q88 86 92 99' transform='rotate(-18 92 86)'/%3E%3C/g%3E%3C/svg%3E&quot;); background-size:220px 220px;">
    </div>

    <!-- Lớp Gradient & Ánh sáng Ambient ấm -->
    <div class="pointer-events-none absolute inset-0 bg-gradient-to-b from-[#18110e]/95 via-[#16100c]/90 to-[#120d0a]"></div>
    <div class="pointer-events-none absolute -bottom-20 -left-20 h-72 w-72 rounded-full bg-[#b38352]/10 blur-3xl"></div>

    <!-- 2. Khối Nội Dung Chính Của Footer -->
    <div class="relative z-10 mx-auto max-w-7xl px-6 pt-16 pb-14 lg:px-8">
        <div class="grid grid-cols-1 gap-10 md:grid-cols-2 lg:grid-cols-12 lg:gap-8">
            
            <!-- Cột 1: Logo, Giới thiệu & Mạng xã hội (4/12 cột) -->
            <div class="space-y-4 lg:col-span-4">
                <!-- Logo Brand Dark Mode -->
                <a href="{{ url('/') }}" class="group inline-flex items-center gap-2.5 transition">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/10 text-[#b38352] ring-1 ring-[#b38352]/30 backdrop-blur-sm transition group-hover:scale-105 group-hover:ring-[#b38352]">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636a9 9 0 010 12.728m0 0l-2.829-2.829m2.829 2.829L21 21M15.536 8.464a5 5 0 010 7.072m0 0l-2.829-2.829m-4.243 4.243a9 9 0 01-12.728 0m0 0l2.829-2.829m-2.829 2.829L3 21m2.828-15.364a9 9 0 0112.728 0" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xl font-bold tracking-tight text-white">
                            Brew<span class="text-[#b38352]">Bite</span>
                        </span>
                        <span class="text-[9px] font-semibold uppercase tracking-[0.2em] text-[#c7b199]">
                            Artisan Cafe &amp; Bakery
                        </span>
                    </div>
                </a>

                <p class="max-w-sm text-sm leading-relaxed text-gray-300">
                    {{ __('Thương hiệu cà phê specialty và bánh ngọt thủ công cao cấp. Mang trọn đam mê, hương vị mộc nguyên bản và không gian ấm cúng đến từng trải nghiệm của bạn.') }}
                </p>

                <!-- Nút Mạng Xã Hội Hộp Tối Viền Vàng Nâu -->
                <div class="flex items-center gap-2.5 pt-2">
                    <!-- Facebook -->
                    <a href="#" class="flex h-9 w-9 items-center justify-center rounded-xl border border-white/10 bg-white/5 text-gray-300 transition hover:border-[#b38352] hover:bg-[#b38352]/20 hover:text-white" title="Facebook">
                        <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    <!-- Twitter / X -->
                    <a href="#" class="flex h-9 w-9 items-center justify-center rounded-xl border border-white/10 bg-white/5 text-gray-300 transition hover:border-[#b38352] hover:bg-[#b38352]/20 hover:text-white" title="X (Twitter)">
                        <svg class="h-3.5 w-3.5 fill-current" viewBox="0 0 24 24">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                    </a>
                    <!-- Instagram -->
                    <a href="#" class="flex h-9 w-9 items-center justify-center rounded-xl border border-white/10 bg-white/5 text-gray-300 transition hover:border-[#b38352] hover:bg-[#b38352]/20 hover:text-white" title="Instagram">
                        <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Cột 2: Khám Phá (Chia 2 nhánh link) -->
            <div class="lg:col-span-4 lg:pl-4">
                <h3 class="mb-4 text-xs font-bold uppercase tracking-[0.2em] text-[#b38352]">
                    {{ __('Khám Phá & Thực Đơn') }}
                </h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <ul class="space-y-2.5">
                        <li>
                            <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 text-gray-300 transition hover:text-[#b38352]">
                                <span class="text-[#b38352]">&rsaquo;</span> {{ __('Trang chủ') }}
                            </a>
                        </li>
                        <li>
                            <a href="#" class="inline-flex items-center gap-1.5 text-gray-300 transition hover:text-[#b38352]">
                                <span class="text-[#b38352]">&rsaquo;</span> {{ __('Thực đơn') }}
                            </a>
                        </li>
                        <li>
                            <a href="#" class="inline-flex items-center gap-1.5 text-gray-300 transition hover:text-[#b38352]">
                                <span class="text-[#b38352]">&rsaquo;</span> {{ __('Cửa hàng') }}
                            </a>
                        </li>
                    </ul>
                    <ul class="space-y-2.5">
                        <li>
                            <a href="#" class="inline-flex items-center gap-1.5 text-gray-300 transition hover:text-[#b38352]">
                                <span class="text-[#b38352]">&rsaquo;</span> {{ __('Tin tức & Blog') }}
                            </a>
                        </li>
                        <li>
                            <a href="#" class="inline-flex items-center gap-1.5 text-gray-300 transition hover:text-[#b38352]">
                                <span class="text-[#b38352]">&rsaquo;</span> {{ __('Về chúng tôi') }}
                            </a>
                        </li>
                        <li>
                            <a href="#" class="inline-flex items-center gap-1.5 text-gray-300 transition hover:text-[#b38352]">
                                <span class="text-[#b38352]">&rsaquo;</span> {{ __('Liên hệ') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Cột 3: Thông Tin Liên Hệ -->
            <div class="lg:col-span-4">
                <h3 class="mb-4 text-xs font-bold uppercase tracking-[0.2em] text-[#b38352]">
                    {{ __('Thông Tin Liên Hệ') }}
                </h3>
                <ul class="space-y-3 text-sm text-gray-300">
                    <!-- Địa chỉ -->
                    <li class="flex items-start gap-3">
                        <div class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-white/10 text-[#b38352] ring-1 ring-white/10">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <span>{{ __('Tầng 3, Tòa nhà Sun Asterisk, Q. Cầu Giấy, Hà Nội') }}</span>
                    </li>

                    <!-- Hotline -->
                    <li class="flex items-center gap-3">
                        <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-white/10 text-[#b38352] ring-1 ring-white/10">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <span class="font-medium text-white">(+84) 0912 345 678</span>
                    </li>

                    <!-- Email -->
                    <li class="flex items-center gap-3">
                        <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-white/10 text-[#b38352] ring-1 ring-white/10">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <span>contact@brewandbite.vn</span>
                    </li>

                    <!-- Giờ mở cửa -->
                    <li class="flex items-center gap-3">
                        <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg bg-white/10 text-[#b38352] ring-1 ring-white/10">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span>{{ __('Thứ Hai - Chủ Nhật: 09:30 - 22:00') }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- 4. Chiếc Lá Cà Phê Decor Cắt Ngang Đáy Footer (Nếu có asset footer-leaf.png) -->
    <img 
        src="{{ Vite::asset('resources/images/footer-leaf.png') }}" 
        alt="Coffee Leaf" 
        class="pointer-events-none absolute -right-10 md:-right-14 bottom-0 z-20 w-28 select-none opacity-90 sm:w-36 md:w-48 lg:w-60"
        onerror="this.style.display='none'"
    >

    <!-- 3. Thanh Bản Quyền (Copyright Bar Tông Caramel Nâu Rang #b38352) -->
    <div class="relative z-10 bg-[#B38352] text-[#fdf8f3]">
        <div class="mx-auto flex max-w-7xl items-center justify-center px-6 py-3.5 text-center text-xs tracking-wide sm:text-sm lg:px-8">
            <p>
                {{ __('Copyright © :year by :name. All Rights Reserved.', ['year' => date('Y'), 'name' => config('app.name', 'Brew & Bite')]) }}
            </p>
        </div>
    </div>
</footer>