<section>
    <header class="mb-6">
        <span class="block font-serif text-[11px] font-semibold tracking-[0.25em] text-[#B38352] uppercase">
            {{ __('Bảo mật tài khoản') }}
        </span>
        <h2 class="mt-1 font-serif text-2xl font-semibold text-[#2B1E19] sm:text-3xl">
            {{ __('Đổi mật khẩu') }}
        </h2>
        <p class="mt-1.5 text-xs text-[#736357]">
            {{ __('Sử dụng mật khẩu có độ dài tối thiểu 8 ký tự để bảo vệ tài khoản.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-4">
        @csrf
        @method('put')

        <!-- Current Password -->
        <div x-data="{ show: false }">
            <label for="update_password_current_password" class="mb-1.5 block text-[11px] font-bold uppercase tracking-wider text-[#4A3B32]">
                {{ __('Mật khẩu hiện tại') }}
            </label>
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-[#A39284]">
                    <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <input
                    id="update_password_current_password"
                    name="current_password"
                    :type="show ? 'text' : 'password'"
                    autocomplete="current-password"
                    placeholder="••••••••"
                    class="block w-full rounded-2xl border border-[#EADBCE] bg-[#FAF5F1]/50 py-3 pl-11 pr-11 text-sm text-[#2B1E19] placeholder-[#A39284] transition duration-200 focus:border-[#B38352] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#B38352]/15"
                />
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-[#A39284] hover:text-[#2B1E19]" tabindex="-1">
                    <svg x-show="!show" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <svg x-show="show" x-cloak class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1 text-xs" />
        </div>

        <!-- New Password -->
        <div x-data="{ show: false }">
            <label for="update_password_password" class="mb-1.5 block text-[11px] font-bold uppercase tracking-wider text-[#4A3B32]">
                {{ __('Mật khẩu mới') }}
            </label>
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-[#A39284]">
                    <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <input
                    id="update_password_password"
                    name="password"
                    :type="show ? 'text' : 'password'"
                    autocomplete="new-password"
                    placeholder="••••••••"
                    class="block w-full rounded-2xl border border-[#EADBCE] bg-[#FAF5F1]/50 py-3 pl-11 pr-11 text-sm text-[#2B1E19] placeholder-[#A39284] transition duration-200 focus:border-[#B38352] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#B38352]/15"
                />
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-[#A39284] hover:text-[#2B1E19]" tabindex="-1">
                    <svg x-show="!show" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <svg x-show="show" x-cloak class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1 text-xs" />
        </div>

        <!-- Confirm Password -->
        <div x-data="{ show: false }">
            <label for="update_password_password_confirmation" class="mb-1.5 block text-[11px] font-bold uppercase tracking-wider text-[#4A3B32]">
                {{ __('Xác nhận mật khẩu mới') }}
            </label>
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-[#A39284]">
                    <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <input
                    id="update_password_password_confirmation"
                    name="password_confirmation"
                    :type="show ? 'text' : 'password'"
                    autocomplete="new-password"
                    placeholder="••••••••"
                    class="block w-full rounded-2xl border border-[#EADBCE] bg-[#FAF5F1]/50 py-3 pl-11 pr-11 text-sm text-[#2B1E19] placeholder-[#A39284] transition duration-200 focus:border-[#B38352] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#B38352]/15"
                />
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-[#A39284] hover:text-[#2B1E19]" tabindex="-1">
                    <svg x-show="!show" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <svg x-show="show" x-cloak class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                </button>
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1 text-xs" />
        </div>

        <!-- Submit Button -->
        <div class="flex items-center gap-4 pt-3">
            <button
                type="submit"
                class="group relative flex items-center justify-center gap-2 rounded-2xl bg-[#2B1E19] py-3.5 px-6 text-xs font-bold uppercase tracking-wider text-[#FAF5F1] shadow-md transition-all duration-200 hover:bg-[#B38352] active:scale-[0.99]"
            >
                <span>{{ __('Cập nhật mật khẩu') }}</span>
                <svg class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2500)" class="text-xs font-bold text-green-600">
                    {{ __('Mật khẩu đã được cập nhật.') }}
                </p>
            @endif
        </div>
    </form>
</section>