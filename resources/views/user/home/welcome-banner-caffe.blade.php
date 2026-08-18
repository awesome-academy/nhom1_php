    <section class="relative min-h-[560px] w-full overflow-hidden bg-[#16100c] text-white flex items-center">
    <!-- 1. Background Image -->
    <div class="absolute inset-0 z-0">
        <img 
            src="{{ Vite::asset('resources/images/user-home/welcome-banner-caffe.png') }}" 
            alt="Hero Banner" 
            class="h-full w-full object-cover object-right lg:object-center"
        >
        <!-- Lớp gradient tạo nền đọc chữ bên trái -->
        <div class="absolute inset-0 bg-gradient-to-r from-[#16100c] via-[#16100c]/85 to-transparent"></div>
    </div>

    <!-- 2. Hero Content -->
    <div class="relative z-10 mx-auto max-w-7xl px-6 py-20 lg:px-8 w-full">
        <div class="max-w-xl space-y-6">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 rounded-lg border border-[#B38352]/40 bg-[#B38352]/10 px-3.5 py-1.5 text-xs font-semibold uppercase tracking-wider text-[#C7B199]">
                <span>☕ {{ __('Welcome to Brew & Bite') }}</span>
            </div>

            <!-- Title -->
            <h1 class="font-serif text-4xl font-bold tracking-tight text-[#FAF5F1] sm:text-5xl lg:text-6xl leading-tight">
                Heal the world <br>
                <span class="text-[#B38352]">with coffee &amp; bakes</span>
            </h1>

            <!-- Subtitle -->
            <p class="text-sm sm:text-base leading-relaxed text-gray-300">
                {{ __('Thưởng thức hương vị cà phê specialty nguyên bản kết hợp cùng các món bánh ngọt thủ công hảo hạng được chuẩn bị tươi mới mỗi ngày.') }}
            </p>

            <!-- Action Buttons -->
            <div class="flex items-center gap-4 pt-2">
                <a href="{{ route('login') }}" class="rounded-xl bg-[#B38352] px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-[#B38352]/25 transition duration-200 hover:bg-[#9E7143] active:scale-95">
                    {{ __('Đặt hàng ngay') }}
                </a>
                <a href="#menu" class="rounded-xl border border-white/20 bg-white/5 px-6 py-3.5 text-sm font-semibold text-[#FAF5F1] backdrop-blur-sm transition hover:border-[#B38352] hover:bg-white/10">
                    {{ __('Xem thực đơn') }}
                </a>
            </div>
        </div>
    </div>
</section>