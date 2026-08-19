@extends('layouts.user-guest')

@section('content')
<div class="relative flex min-h-[calc(100vh-5rem)] items-center justify-center overflow-hidden px-4 py-24 sm:px-6 lg:px-8"
     style="background:
        radial-gradient(ellipse 900px 600px at 15% -10%, #FFFFFF 0%, rgba(255,255,255,0) 60%),
        linear-gradient(160deg, #FFFFFF 0%, #FFFBF6 38%, #FAF5F1 62%, #F3E7D8 84%, #EADBCE 100%);">

    <!-- Kết cấu hạt cà phê nghệ thuật, tinh tế và gắn với thương hiệu -->
    <div class="pointer-events-none absolute inset-0 opacity-60"
         style="background-image:url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='120' height='120'%3E%3Cg fill='none' stroke='%23B38352' stroke-width='1.3' opacity='0.16'%3E%3Cellipse cx='30' cy='30' rx='9' ry='15' transform='rotate(24 30 30)'/%3E%3Cpath d='M30 17 Q26 30 30 43' transform='rotate(24 30 30)'/%3E%3Cellipse cx='92' cy='86' rx='9' ry='15' transform='rotate(-18 92 86)'/%3E%3Cpath d='M92 73 Q88 86 92 99' transform='rotate(-18 92 86)'/%3E%3C/g%3E%3C/svg%3E&quot;); background-size:220px 220px;">
    </div>

    <!-- Nắp hộp bánh cách điệu phía sau thẻ -->
    <div class="pointer-events-none absolute left-1/2 top-[6%] h-[460px] w-[min(560px,92vw)] -translate-x-1/2 rounded-b-[260px] opacity-50 blur-[2px]"
         style="background:linear-gradient(180deg, #F3E7D8, #EADBCE);">
    </div>

    <div class="relative w-full max-w-md">

        <!-- Thẻ treo kraft — điểm nhấn nhận diện thương hiệu -->
        <div class="relative z-10 mb-0 flex h-[70px] justify-center">
            <svg class="absolute left-1/2 top-0 h-[52px] w-[120px] -translate-x-1/2" viewBox="0 0 120 52">
                <path d="M60 0 C60 18, 44 20, 40 34" fill="none" stroke="#C7B199" stroke-width="1.6" stroke-dasharray="3 4" stroke-linecap="round"/>
            </svg>
            <div class="relative top-[26px] flex -rotate-[5deg] items-center gap-2 border border-[#2B1E19]/10 py-2.5 pl-7 pr-5 shadow-[0_10px_24px_rgba(43,30,25,0.10)]"
                 style="background:linear-gradient(155deg, #F3E7D8, #EADBCE); clip-path:polygon(18% 0, 100% 0, 100% 100%, 18% 100%, 0 50%);">
                <span class="absolute left-2.5 top-1/2 h-[7px] w-[7px] -translate-y-1/2 rounded-full bg-[#FAF5F1] ring-1 ring-[#2B1E19]/25"></span>
                <svg class="h-4 w-4 shrink-0 text-[#8E6238]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636a9 9 0 010 12.728m0 0l-2.829-2.829m2.829 2.829L21 21M15.536 8.464a5 5 0 010 7.072m0 0l-2.829-2.829m-4.243 4.243a9 9 0 01-12.728 0m0 0l2.829-2.829m-2.829 2.829L3 21m2.828-15.364a9 9 0 0112.728 0" />
                    <circle cx="12" cy="12" r="3" />
                </svg>
                <span class="whitespace-nowrap text-[10px] font-extrabold uppercase tracking-[0.18em] text-[#4A3B32]">Brew &amp; Bite</span>
            </div>
        </div>

        <!-- Auth Card Container -->
        <div class="relative rounded-[28px] border border-[#EADBCE] bg-white/95 p-9 shadow-[0_30px_70px_rgba(43,30,25,0.10)] backdrop-blur-sm sm:p-11">

            <!-- Viền chỉ khâu bên trong, gợi liên tưởng bao bì/tạp dề thủ công -->
            <div class="pointer-events-none absolute inset-2.5 rounded-[20px] border border-dashed border-[#B38352]/20"></div>

            <!-- 1. Header & Brand Title -->
            <div class="relative text-center">
                <span class="block font-serif text-[11px] font-semibold tracking-[0.3em] text-[#B38352] uppercase">
                    Brew &amp; Bite Artisan
                </span>
                <h1 class="mt-2 font-serif text-[31px] font-semibold leading-tight tracking-tight text-[#2B1E19]">
                    {{ __('Tạo tài khoản') }}
                </h1>
                <p class="mt-2.5 text-[13.5px] leading-relaxed text-[#736357]">
                    {{ __('Chỉ mất một phút để bắt đầu hành trình cà phê của bạn') }}
                </p>
            </div>

            <!-- 2. Form Đăng ký -->
            <form method="POST" action="{{ route('register') }}" class="relative mt-7 space-y-[18px]">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="mb-2 block text-[11px] font-bold uppercase tracking-wider text-[#4A3B32]">
                        {{ __('Họ và tên') }}
                    </label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-[#A39284]">
                            <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5z" />
                            </svg>
                        </div>
                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            autofocus
                            autocomplete="name"
                            placeholder="{{ __('Nguyễn Văn A') }}"
                            class="block w-full rounded-2xl border border-[#EADBCE] bg-[#FAF5F1]/50 py-3.5 pl-11 pr-4 text-sm text-[#2B1E19] placeholder-[#A39284] transition duration-200 focus:border-[#B38352] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#B38352]/15"
                        />
                    </div>
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email Address -->
                <div>
                    <label for="email" class="mb-2 block text-[11px] font-bold uppercase tracking-wider text-[#4A3B32]">
                        {{ __('Địa chỉ Email') }}
                    </label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-[#A39284]">
                            <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autocomplete="username"
                            placeholder="name@example.com"
                            class="block w-full rounded-2xl border border-[#EADBCE] bg-[#FAF5F1]/50 py-3.5 pl-11 pr-4 text-sm text-[#2B1E19] placeholder-[#A39284] transition duration-200 focus:border-[#B38352] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#B38352]/15"
                        />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password (Alpine.js Toggle Show/Hide) -->
                <div x-data="{ show: false }">
                    <label for="password" class="mb-2 block text-[11px] font-bold uppercase tracking-wider text-[#4A3B32]">
                        {{ __('Mật khẩu') }}
                    </label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-[#A39284]">
                            <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input
                            id="password"
                            :type="show ? 'text' : 'password'"
                            name="password"
                            required
                            autocomplete="new-password"
                            placeholder="••••••••"
                            class="block w-full rounded-2xl border border-[#EADBCE] bg-[#FAF5F1]/50 py-3.5 pl-11 pr-11 text-sm text-[#2B1E19] placeholder-[#A39284] transition duration-200 focus:border-[#B38352] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#B38352]/15"
                        />
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-[#A39284] hover:text-[#2B1E19] focus:outline-none" tabindex="-1">
                            <svg x-show="!show" class="h-[18px] w-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="show" x-cloak class="h-[18px] w-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password (Alpine.js Toggle Show/Hide) -->
                <div x-data="{ show: false }">
                    <label for="password_confirmation" class="mb-2 block text-[11px] font-bold uppercase tracking-wider text-[#4A3B32]">
                        {{ __('Xác nhận mật khẩu') }}
                    </label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-[#A39284]">
                            <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input
                            id="password_confirmation"
                            :type="show ? 'text' : 'password'"
                            name="password_confirmation"
                            required
                            autocomplete="new-password"
                            placeholder="••••••••"
                            class="block w-full rounded-2xl border border-[#EADBCE] bg-[#FAF5F1]/50 py-3.5 pl-11 pr-11 text-sm text-[#2B1E19] placeholder-[#A39284] transition duration-200 focus:border-[#B38352] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#B38352]/15"
                        />
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-[#A39284] hover:text-[#2B1E19] focus:outline-none" tabindex="-1">
                            <svg x-show="!show" class="h-[18px] w-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="show" x-cloak class="h-[18px] w-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <!-- Nút Submit Đăng Ký -->
                <button
                    type="submit"
                    class="group relative mt-1 flex w-full items-center justify-center gap-2 rounded-2xl bg-[#2B1E19] py-4 px-4 text-sm font-bold text-[#FAF5F1] shadow-[0_12px_24px_rgba(43,30,25,0.16)] transition-all duration-200 hover:bg-[#B38352] hover:shadow-[0_14px_26px_rgba(179,131,82,0.28)] active:scale-[0.99] focus:outline-none focus:ring-2 focus:ring-[#B38352] focus:ring-offset-2"
                >
                    <span>{{ __('Đăng ký') }}</span>
                    <svg class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </form>

            <!-- 3. Social Register Divider (kiểu vé giấy đục lỗ) -->
            <div class="relative my-8 flex items-center gap-3.5">
                <div class="relative h-0 flex-1 border-t-[1.5px] border-dashed border-[#EADBCE]">
                    <span class="absolute -left-1 top-1/2 h-2.5 w-2.5 -translate-y-1/2 rounded-full bg-[#FAF5F1] ring-1 ring-[#EADBCE]"></span>
                </div>
                <span class="whitespace-nowrap text-[11px] font-bold uppercase tracking-wider text-[#A39284]">{{ __('Hoặc đăng ký với') }}</span>
                <div class="relative h-0 flex-1 border-t-[1.5px] border-dashed border-[#EADBCE]">
                    <span class="absolute -right-1 top-1/2 h-2.5 w-2.5 -translate-y-1/2 rounded-full bg-[#FAF5F1] ring-1 ring-[#EADBCE]"></span>
                </div>
            </div>

            <!-- 4. Social Register Buttons Grid (Google, Facebook, Twitter) -->
            <div class="relative grid grid-cols-3 gap-3">
                <!-- Google -->
                <a
                    href="{{ route('social.redirect', 'google') }}"
                    class="flex items-center justify-center rounded-2xl border border-[#EADBCE] bg-white py-3 px-3 transition duration-200 hover:-translate-y-0.5 hover:border-[#B38352] hover:bg-[#FAF5F1]/80 hover:shadow-sm"
                    title="{{ __('Đăng ký với Google') }}"
                >
                    <svg class="h-5 w-5" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.82-2.4 3.68v3.05h3.88c2.27-2.09 3.66-5.17 3.66-9.17z"/>
                        <path fill="#34A853" d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.25v3.15C3.26 21.36 7.34 24 12 24z"/>
                        <path fill="#FBBC05" d="M5.28 14.27c-.25-.72-.38-1.49-.38-2.27s.13-1.55.38-2.27V6.58H1.25C.45 8.18 0 10.03 0 12s.45 3.82 1.25 5.42l4.03-3.15z"/>
                        <path fill="#EA4335" d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.34 0 3.26 2.64 1.25 6.58l4.03 3.15c.95-2.83 3.6-4.98 6.72-4.98z"/>
                    </svg>
                </a>

                <!-- Facebook -->
                <a
                    href="{{ route('social.redirect', 'facebook') }}"
                    class="flex items-center justify-center rounded-2xl border border-[#EADBCE] bg-white py-3 px-3 transition duration-200 hover:-translate-y-0.5 hover:border-[#B38352] hover:bg-[#FAF5F1]/80 hover:shadow-sm"
                    title="{{ __('Đăng ký với Facebook') }}"
                >
                    <svg class="h-5 w-5 text-[#1877F2]" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                </a>

                <!-- Twitter / X -->
                <a
                    href="{{ route('social.redirect', 'twitter') }}"
                    class="flex items-center justify-center rounded-2xl border border-[#EADBCE] bg-white py-3 px-3 transition duration-200 hover:-translate-y-0.5 hover:border-[#B38352] hover:bg-[#FAF5F1]/80 hover:shadow-sm"
                    title="{{ __('Đăng ký với X (Twitter)') }}"
                >
                    <svg class="h-5 w-5 text-[#2B1E19]" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                    </svg>
                </a>
            </div>

            <!-- 5. Footer Switch Link -->
            <p class="relative mt-8 text-center text-[13.5px] text-[#736357]">
                {{ __('Đã có tài khoản?') }}
                <a href="{{ route('login') }}" class="font-bold text-[#B38352] transition hover:text-[#8E6238] hover:underline">
                    {{ __('Đăng nhập') }}
                </a>
            </p>
        </div>
    </div>
</div>
@endsection