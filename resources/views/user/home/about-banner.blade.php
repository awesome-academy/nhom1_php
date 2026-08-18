<section class="relative overflow-hidden bg-[#FAF5F1] py-20 lg:py-28">
    
    <!-- 1. Hạt cà phê & Lá trang trí Background (Decor Assets) -->
    <!-- Hạt cà phê trôi nổi bên trái -->
    <div class="pointer-events-none absolute left-6 top-12 z-0 hidden w-30 opacity-75 lg:block lg:left-0 lg:top-10 lg:w-40">
        <img src="{{ Vite::asset('resources/images/user-home/about-banner-caffe-bean1.png') }}" 
             alt="Coffee Bean" 
             class="w-full rotate-[-0deg] filter drop-shadow-sm"
             onerror="this.style.display='none'">
    </div>
    
    <!-- Chiếc lá cà phê decor bên phải -->
    <div class="pointer-events-none absolute -right-6 bottom-0 z-0 hidden w-64 opacity-40 md:block lg:right-0 lg:w-80">
        <img src="{{ Vite::asset('resources/images/user-home/about-banner-leaf.png') }}" 
             alt="Coffee Branch Leaf" 
             class="w-full object-contain"
             onerror="this.style.display='none'">
    </div>

    <!-- 2. Container Nội Dung Chính -->
    <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 items-center gap-12 lg:grid-cols-12 lg:gap-14">

            <!-- ================= CỘT TRÁI: KHỐI COLLAGE HÌNH ẢNH NGHỆ THUẬT (6/12) ================= -->
            <div class="relative flex justify-center lg:col-span-6 lg:justify-start">
                
                <!-- Khung viền mỏng vàng nâu đệm phía sau (Outer Frame) -->
                <div class="absolute -left-3 -top-3 h-[92%] w-[88%] rounded-2xl border border-[#B38352]/40 sm:-left-5 sm:-top-5"></div>

                <!-- Lưới ảnh 3 tấm đan xen -->
                <div class="relative grid grid-cols-12 gap-3 sm:gap-4.5 w-full max-w-[500px]">
                    
                    <!-- Ảnh 1 (Dọc lớn bên trái - Bánh mặn / Bakery) -->
                    <div class="col-span-7 overflow-hidden rounded-2xl shadow-xl shadow-[#2B1E19]/10 ring-1 ring-[#EADBCE]">
                        <img src="{{ Vite::asset('resources/images/user-home/about-banner-bakery.png') }}" 
                             alt="Artisan Bakery & Fresh Bites" 
                             class="h-[340px] sm:h-[420px] w-full object-cover transition-transform duration-500 hover:scale-105">
                    </div>

                    <!-- Cột phải: Chứa 2 ảnh Cà phê (Top & Bot) -->
                    <div class="col-span-5 flex flex-col gap-3 sm:gap-4.5">
                        <!-- Ảnh 2 (Cà phê góc trên) -->
                        <div class="overflow-hidden rounded-2xl shadow-lg shadow-[#2B1E19]/08 ring-1 ring-[#EADBCE]">
                            <img src="{{ Vite::asset('resources/images/user-home/about-banner-caffe-top.png') }}" 
                                 alt="Specialty Coffee Cup" 
                                 class="h-[160px] sm:h-[200px] w-full object-cover transition-transform duration-500 hover:scale-105">
                        </div>

                        <!-- Ảnh 3 (Cà phê góc dưới) -->
                        <div class="overflow-hidden rounded-2xl shadow-lg shadow-[#2B1E19]/08 ring-1 ring-[#EADBCE]">
                            <img src="{{ Vite::asset('resources/images/user-home/about-banner-caffe-bot.png') }}" 
                                 alt="Latte Art Detail" 
                                 class="h-[168px] sm:h-[206px] w-full object-cover transition-transform duration-500 hover:scale-105">
                        </div>
                    </div>

                    <!-- Mảng Chấm Bi Decor Phía Dưới (Spot Pattern) -->
                    <div class="pointer-events-none absolute -bottom-6 left-12 -z-10 w-36 sm:-bottom-8 sm:left-16 sm:w-44">
                        <img src="{{ Vite::asset('resources/images/user-home/about-banner-spot.png') }}" 
                             alt="Dot Pattern" 
                             class="w-full opacity-65"
                             onerror="this.style.display='none'">
                    </div>
                </div>
            </div>

            <!-- ================= CỘT PHẢI: NỘI DUNG GIỚI THIỆU THƯƠNG HIỆU (6/12) ================= -->
            <div class="space-y-6 lg:col-span-6 lg:pl-6">
                
                <!-- Sub-badge / Tagline nhỏ -->
                <div class="inline-flex items-center gap-2">
                    <span class="font-serif text-sm font-semibold tracking-[0.25em] text-[#B38352] uppercase">
                        {{ __('Về Brew & Bite') }}
                    </span>
                    <span class="h-px w-10 bg-[#B38352]/60"></span>
                </div>

                <!-- Tiêu đề lớn (Headline) -->
                <h2 class="font-serif text-3xl font-bold tracking-tight text-[#2B1E19] sm:text-4xl lg:text-[42px] lg:leading-[1.2]">
                    {{ __('Trọn Vẹn Đam Mê') }} <br class="hidden sm:inline">
                    <span class="text-[#B38352]">{{ __('Cà Phê & Bánh Nướng') }}<br/></span> {{ __('Thủ Công') }}
                </h2>

                <!-- Đoạn mô tả chuẩn nhận diện thương hiệu -->
                <p class="text-sm sm:text-base leading-relaxed text-[#736357]">
                    {{ __('Tại Brew & Bite, mỗi ly cà phê Specialty là một bản hòa tấu giữa hạt rang mộc tuyển chọn và kỹ nghệ chiết xuất thủ công. Kết hợp cùng những mẻ bánh nướng tươi trong ngày từ bơ Pháp hảo hạng, chúng tôi tạo nên không gian trải nghiệm ẩm thực trọn vẹn và thư thái cho từng thực khách.') }}
                </p>

                <!-- Danh sách 3 Tiêu chí Nổi bật (Features Checklist) -->
                <ul class="space-y-3.5 pt-2">
                    <!-- Tiêu chí 1: Barista & Thợ làm bánh chuyên nghiệp -->
                    <li class="flex items-center gap-3.5">
                        <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-md bg-[#2B1E19] text-[#FAF5F1] shadow-sm">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span class="text-sm sm:text-base font-semibold text-[#2B1E19]">
                            {{ __('Đội ngũ Nghệ nhân Barista & Đầu bếp bánh giàu tâm huyết') }}
                        </span>
                    </li>

                    <!-- Tiêu chí 2: Nguyên liệu tươi & Hữu cơ -->
                    <li class="flex items-center gap-3.5">
                        <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-md bg-[#2B1E19] text-[#FAF5F1] shadow-sm">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span class="text-sm sm:text-base font-semibold text-[#2B1E19]">
                            {{ __('100% Nguyên liệu tươi, bột mì hữu cơ & bơ Pháp hảo hạng') }}
                        </span>
                    </li>

                    <!-- Tiêu chí 3: Menu đa dạng & Thức uống signature -->
                    <li class="flex items-center gap-3.5">
                        <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-md bg-[#2B1E19] text-[#FAF5F1] shadow-sm">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span class="text-sm sm:text-base font-semibold text-[#2B1E19]">
                            {{ __('Menu Specialty Coffee, Craft Tea & Bánh nướng phong phú') }}
                        </span>
                    </li>
                </ul>

                <!-- Nút CTA "Tìm hiểu thêm" (Learn More Button) -->
                <div class="pt-4">
                    <a href="{{ url('/about') }}" 
                       class="group inline-flex items-center gap-2.5 rounded-xl bg-[#B38352] px-7 py-3.5 text-sm font-semibold text-white shadow-lg shadow-[#B38352]/20 transition-all duration-200 hover:bg-[#9E7143] hover:shadow-xl hover:shadow-[#B38352]/30 active:scale-95">
                        <span>{{ __('Khám phá câu chuyện') }}</span>
                        <svg class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>

            </div>

        </div>
    </div>
</section>