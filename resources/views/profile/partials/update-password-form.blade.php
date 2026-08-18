<section>
    <header class="mb-6">
        <h2 class="text-lg font-semibold text-[#1b1b18]">{{ __('Đổi mật khẩu') }}</h2>
        <p class="mt-1 text-sm text-gray-500">{{ __('Sử dụng mật khẩu dài, ngẫu nhiên để bảo mật tài khoản.') }}</p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Mật khẩu hiện tại')" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('Mật khẩu mới')" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Xác nhận mật khẩu mới')" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="rounded-md bg-[#1b1b18] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-gray-800">
                {{ __('Cập nhật mật khẩu') }}
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-green-600">
                    {{ __('Đã lưu.') }}
                </p>
            @endif
        </div>
    </form>
</section>
