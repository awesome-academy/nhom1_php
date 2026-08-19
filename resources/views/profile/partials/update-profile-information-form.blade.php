<section>
    <header class="mb-6">
        <span class="block font-serif text-[11px] font-semibold tracking-[0.25em] text-[#B38352] uppercase">
            {{ __('Hồ sơ cá nhân') }}
        </span>
        <h2 class="mt-1 font-serif text-2xl font-semibold text-[#2B1E19] sm:text-3xl">
            {{ __('Thông tin tài khoản') }}
        </h2>
        <p class="mt-1.5 text-xs text-[#736357]">
            {{ __('Cập nhật thông tin định danh và địa chỉ giao nhận hàng của bạn.') }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
        @csrf
        @method('patch')

        <!-- Name -->
        <div>
            <label for="name" class="mb-1.5 block text-[11px] font-bold uppercase tracking-wider text-[#4A3B32]">
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
                    name="name"
                    type="text"
                    value="{{ old('name', $user->name) }}"
                    required
                    autofocus
                    autocomplete="name"
                    class="block w-full rounded-2xl border border-[#EADBCE] bg-[#FAF5F1]/50 py-3 pl-11 pr-4 text-sm text-[#2B1E19] placeholder-[#A39284] transition duration-200 focus:border-[#B38352] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#B38352]/15"
                />
            </div>
            <x-input-error class="mt-1 text-xs" :messages="$errors->get('name')" />
        </div>

        <!-- Email (Disabled / Readonly) -->
        <div>
            <label for="email" class="mb-1.5 block text-[11px] font-bold uppercase tracking-wider text-[#4A3B32]">
                {{ __('Địa chỉ Email (Cố định)') }}
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
                    value="{{ $user->email }}"
                    disabled
                    class="block w-full cursor-not-allowed rounded-2xl border border-[#EADBCE]/60 bg-gray-100/70 py-3 pl-11 pr-4 text-sm text-[#736357] select-none"
                />
            </div>
            <p class="mt-1 text-[11px] text-[#A39284]">{{ __('Email tài khoản đã được bảo mật cố định.') }}</p>

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 rounded-xl bg-amber-50 p-3 border border-amber-200">
                    <p class="text-xs text-[#736357]">
                        {{ __('Email của bạn chưa được xác minh.') }}
                        <button form="send-verification" class="font-bold text-[#B38352] underline hover:text-[#8E6238]">
                            {{ __('Gửi lại email xác minh.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-1.5 text-xs font-semibold text-green-600">
                            {{ __('Liên kết xác minh mới đã được gửi tới email của bạn.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Phone -->
        <div>
            <label for="phone" class="mb-1.5 block text-[11px] font-bold uppercase tracking-wider text-[#4A3B32]">
                {{ __('Số điện thoại') }}
            </label>
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-[#A39284]">
                    <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                </div>
                <input
                    id="phone"
                    name="phone"
                    type="text"
                    value="{{ old('phone', $user->phone) }}"
                    placeholder="0912 345 678"
                    autocomplete="tel"
                    class="block w-full rounded-2xl border border-[#EADBCE] bg-[#FAF5F1]/50 py-3 pl-11 pr-4 text-sm text-[#2B1E19] placeholder-[#A39284] transition duration-200 focus:border-[#B38352] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#B38352]/15"
                />
            </div>
            <x-input-error class="mt-1 text-xs" :messages="$errors->get('phone')" />
        </div>

        <!-- Address -->
        <div>
            <label for="address" class="mb-1.5 block text-[11px] font-bold uppercase tracking-wider text-[#4A3B32]">
                {{ __('Địa chỉ nhận hàng') }}
            </label>
            <div class="relative">
                <div class="pointer-events-none absolute top-3.5 left-0 flex items-start pl-3.5 text-[#A39284]">
                    <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <textarea
                    id="address"
                    name="address"
                    rows="2"
                    placeholder="{{ __('Số nhà, tên đường, phường/xã, quận/huyện...') }}"
                    class="block w-full rounded-2xl border border-[#EADBCE] bg-[#FAF5F1]/50 py-3 pl-11 pr-4 text-sm text-[#2B1E19] placeholder-[#A39284] transition duration-200 focus:border-[#B38352] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#B38352]/15"
                >{{ old('address', $user->address) }}</textarea>
            </div>
            <x-input-error class="mt-1 text-xs" :messages="$errors->get('address')" />
        </div>

        <!-- Submit Button -->
        <div class="flex items-center gap-4 pt-3">
            <button
                type="submit"
                class="group relative flex items-center justify-center gap-2 rounded-2xl bg-[#2B1E19] py-3.5 px-6 text-xs font-bold uppercase tracking-wider text-[#FAF5F1] shadow-md transition-all duration-200 hover:bg-[#B38352] active:scale-[0.99]"
            >
                <span>{{ __('Lưu thay đổi') }}</span>
                <svg class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </button>
        </div>
    </form>
</section>