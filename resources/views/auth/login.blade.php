@extends('layouts.user-guest')

@section('content')
    <div class="flex min-h-[calc(100vh-16rem)] items-center justify-center bg-gray-50 px-4 py-16">
        <div class="w-full max-w-md rounded-xl bg-white p-8 shadow-lg">
            <div class="mb-8 text-center">
                <a href="{{ url('/') }}" class="text-2xl font-bold text-[#1b1b18]">
                    Food<span class="text-amber-500">tuck</span>
                </a>
                <h1 class="mt-4 text-xl font-semibold text-gray-900">{{ __('Đăng nhập') }}</h1>
                <p class="mt-1 text-sm text-gray-500">{{ __('Chào mừng bạn quay lại!') }}</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input
                        id="email"
                        class="mt-1 block w-full"
                        type="email"
                        name="email"
                        :value="old('email')"
                        required
                        autofocus
                        autocomplete="username"
                    />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('Mật khẩu')" />
                    <x-text-input
                        id="password"
                        class="mt-1 block w-full"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                    />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="flex items-center justify-between">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-amber-600 shadow-sm focus:ring-amber-500" name="remember">
                        <span class="ms-2 text-sm text-gray-600">{{ __('Ghi nhớ đăng nhập') }}</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-sm text-amber-600 underline hover:text-amber-700" href="{{ route('password.request') }}">
                            {{ __('Quên mật khẩu?') }}
                        </a>
                    @endif
                </div>

                <button
                    type="submit"
                    class="w-full rounded-md bg-[#1b1b18] px-4 py-2.5 text-sm font-semibold uppercase tracking-widest text-white transition hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2"
                >
                    {{ __('Đăng nhập') }}
                </button>
            </form>

            <div class="my-6 flex items-center gap-3">
                <div class="h-px flex-1 bg-gray-200"></div>
                <span class="text-xs uppercase text-gray-400">{{ __('Hoặc') }}</span>
                <div class="h-px flex-1 bg-gray-200"></div>
            </div>

            <div class="space-y-3">
                <a
                    href="{{ route('social.redirect', 'google') }}"
                    class="flex w-full items-center justify-center gap-2 rounded-md border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                >
                    {{ __('Tiếp tục với Google') }}
                </a>
                <a
                    href="{{ route('social.redirect', 'facebook') }}"
                    class="flex w-full items-center justify-center gap-2 rounded-md border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                >
                    {{ __('Tiếp tục với Facebook') }}
                </a>
                <a
                    href="{{ route('social.redirect', 'twitter') }}"
                    class="flex w-full items-center justify-center gap-2 rounded-md border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                >
                    {{ __('Tiếp tục với Twitter') }}
                </a>
            </div>

            <p class="mt-8 text-center text-sm text-gray-500">
                {{ __('Chưa có tài khoản?') }}
                <a href="{{ route('register') }}" class="font-semibold text-amber-600 underline hover:text-amber-700">
                    {{ __('Đăng ký ngay') }}
                </a>
            </p>
        </div>
    </div>
@endsection