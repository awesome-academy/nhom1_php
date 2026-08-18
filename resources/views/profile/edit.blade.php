@extends('layouts.user-app')

@section('content')
<div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8" x-data="{ tab: 'info' }">
    <h1 class="mb-8 text-2xl font-bold text-[#1b1b18]">{{ __('Tài khoản của tôi') }}</h1>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-[280px_1fr]">
        <!-- Sidebar -->
        <div class="space-y-6">
            <div class="flex flex-col items-center rounded-xl bg-white p-6 text-center shadow-sm">
                <div class="relative">
                    <img src="{{ $user->avatar ? asset('storage/'.$user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=f59e0b&color=1b1b18&size=128' }}"
                         class="h-24 w-24 rounded-full object-cover ring-4 ring-amber-100" alt="{{ $user->name }}">

                    <form method="POST" action="{{ route('profile.avatar.update') }}" enctype="multipart/form-data" id="avatar-form">
                        @csrf
                        <input type="file" name="avatar" id="avatar-input" accept="image/*" class="hidden"
                               onchange="document.getElementById('avatar-form').submit();">
                        <label for="avatar-input"
                               class="absolute bottom-0 right-0 flex h-8 w-8 cursor-pointer items-center justify-center rounded-full bg-amber-500 text-white shadow hover:bg-amber-600"
                               title="{{ __('Đổi ảnh đại diện') }}">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <circle cx="12" cy="13" r="3" stroke-width="2"/>
                            </svg>
                        </label>
                    </form>
                </div>

                <h2 class="mt-4 font-semibold text-[#1b1b18]">{{ $user->name }}</h2>
                <p class="text-sm text-gray-500">{{ $user->email }}</p>

                @if (session('success'))
                    <p class="mt-3 text-xs font-medium text-green-600">{{ session('success') }}</p>
                @endif
            </div>

            <nav class="space-y-1 rounded-xl bg-white p-3 shadow-sm">
                <button type="button" @click="tab = 'info'"
                        :class="tab === 'info' ? 'bg-amber-500 text-[#1b1b18]' : 'text-gray-600 hover:bg-gray-50'"
                        class="flex w-full items-center gap-3 rounded-md px-4 py-2.5 text-left text-sm font-medium transition">
                    {{ __('Thông tin tài khoản') }}
                </button>
                <button type="button" @click="tab = 'password'"
                        :class="tab === 'password' ? 'bg-amber-500 text-[#1b1b18]' : 'text-gray-600 hover:bg-gray-50'"
                        class="flex w-full items-center gap-3 rounded-md px-4 py-2.5 text-left text-sm font-medium transition">
                    {{ __('Đổi mật khẩu') }}
                </button>
                <a href="{{ route('orders.index') }}"
                   class="flex w-full items-center gap-3 rounded-md px-4 py-2.5 text-left text-sm font-medium text-gray-600 transition hover:bg-gray-50">
                    {{ __('Đơn hàng của tôi') }}
                </a>
                <button type="button" @click="tab = 'delete'"
                        :class="tab === 'delete' ? 'bg-red-500 text-white' : 'text-red-600 hover:bg-red-50'"
                        class="flex w-full items-center gap-3 rounded-md px-4 py-2.5 text-left text-sm font-medium transition">
                    {{ __('Xoá tài khoản') }}
                </button>
            </nav>
        </div>

        <!-- Content -->
        <div class="space-y-6">
            <div x-show="tab === 'info'" x-cloak class="rounded-xl bg-white p-6 shadow-sm sm:p-8">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div x-show="tab === 'password'" x-cloak class="rounded-xl bg-white p-6 shadow-sm sm:p-8">
                @include('profile.partials.update-password-form')
            </div>

            <div x-show="tab === 'delete'" x-cloak class="rounded-xl border border-red-100 bg-white p-6 shadow-sm sm:p-8">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>
@endsection
