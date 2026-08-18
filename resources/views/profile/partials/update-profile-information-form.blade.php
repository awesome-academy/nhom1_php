<section>
    <header class="mb-6">
        <h2 class="text-lg font-semibold text-[#1b1b18]">{{ __('Thông tin cá nhân') }}</h2>
        <p class="mt-1 text-sm text-gray-500">{{ __('Cập nhật thông tin liên hệ của bạn.') }}</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Họ và tên')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" class="mt-1 block w-full bg-gray-50 text-gray-500" value="{{ $user->email }}" disabled />
            <p class="mt-1 text-xs text-gray-400">{{ __('Email không thể thay đổi.') }}</p>

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Email của bạn chưa được xác minh.') }}
                        <button form="send-verification" class="underline text-sm text-amber-600 hover:text-amber-700">
                            {{ __('Gửi lại email xác minh.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('Một liên kết xác minh mới đã được gửi tới email của bạn.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="phone" :value="__('Số điện thoại')" />
            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone)" autocomplete="tel" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <div>
            <x-input-label for="address" :value="__('Địa chỉ')" />
            <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address', $user->address)" autocomplete="street-address" />
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="rounded-md bg-[#1b1b18] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-gray-800">
                {{ __('Lưu thay đổi') }}
            </button>
        </div>
    </form>
</section>
