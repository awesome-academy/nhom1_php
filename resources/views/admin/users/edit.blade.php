@extends('layouts.admin')

@section('content')
<div class="mx-auto max-w-2xl space-y-6 font-sans antialiased">
    
    <!-- 1. Header Bar -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2.5">
            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#FAF5F1] text-[#B38352] ring-1 ring-[#EADBCE]">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </span>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-[#2B1E19]">{{ __('Chỉnh sửa người dùng') }}</h1>
                <p class="text-xs text-[#736357]">{{ __('Cập nhật thông tin chi tiết tài khoản #') . $targetUser->id }}</p>
            </div>
        </div>

        <a href="{{ route('admin.users.index') }}" 
           class="inline-flex items-center gap-1.5 rounded-xl border border-[#EADBCE] bg-white px-3.5 py-2 text-xs font-semibold text-[#4A3B32] shadow-2xs transition hover:bg-[#FAF5F1] hover:text-[#B38352]">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>{{ __('Quay lại') }}</span>
        </a>
    </div>

    <!-- 2. Form Card nằm giữa -->
    <div class="overflow-hidden rounded-2xl border border-[#EADBCE] bg-white p-6 shadow-2xs sm:p-7">
        
        <!-- User Info Preview -->
        <div class="mb-6 flex items-center gap-4 rounded-xl border border-[#EADBCE]/70 bg-[#FAF5F1]/50 p-3.5">
            <img src="{{ $targetUser->avatar ? (Str::startsWith($targetUser->avatar, ['http://', 'https://']) ? $targetUser->avatar : asset('storage/'.$targetUser->avatar)) : 'https://ui-avatars.com/api/?name='.urlencode($targetUser->name).'&background=FAF5F1&color=B38352&bold=true' }}"
                 class="h-12 w-12 rounded-full object-cover ring-2 ring-[#EADBCE] shadow-2xs" 
                 alt="{{ $targetUser->name }}">
            <div>
                <div class="flex items-center gap-2">
                    <p class="font-bold text-[#2B1E19]">{{ $targetUser->name }}</p>
                    <span class="inline-flex items-center rounded-lg bg-[#FAF5F1] px-2 py-0.5 font-mono text-[10px] font-bold text-[#B38352] ring-1 ring-[#EADBCE]">
                        #{{ $targetUser->id }}
                    </span>
                </div>
                <div class="mt-0.5 flex items-center gap-2 text-xs text-[#736357]">
                    <span>{{ $targetUser->email }}</span>
                    <span>•</span>
                    <span class="font-semibold text-emerald-600">● {{ __('Khách hàng') }}</span>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.users.update', $targetUser) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Họ và tên -->
            <div>
                <label for="name" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-[#4A3B32]">
                    {{ __('Họ và tên') }} <span class="text-rose-500">*</span>
                </label>
                <input id="name" name="name" type="text" required autofocus
                       value="{{ old('name', $targetUser->name) }}"
                       class="block w-full rounded-xl border border-[#EADBCE] bg-[#FAF5F1]/30 py-2.5 px-3.5 text-sm text-[#2B1E19] placeholder-gray-400 transition focus:border-[#B38352] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#B38352]/15">
                <x-input-error class="mt-1 text-xs" :messages="$errors->get('name')" />
            </div>

            <!-- Email (Cố định, không chỉnh sửa) -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-[#4A3B32]">
                        {{ __('Địa chỉ Email') }}
                    </label>
                    <span class="text-[11px] font-medium text-gray-400">{{ __('(Cố định)') }}</span>
                </div>
                <input id="email" type="email" value="{{ $targetUser->email }}" disabled readonly
                       class="block w-full cursor-not-allowed rounded-xl border border-[#EADBCE]/60 bg-gray-50 py-2.5 px-3.5 text-sm text-gray-500 select-none">
                <x-input-error class="mt-1 text-xs" :messages="$errors->get('email')" />
            </div>

            <!-- Số điện thoại -->
            <div>
                <label for="phone" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-[#4A3B32]">
                    {{ __('Số điện thoại') }}
                </label>
                <input id="phone" name="phone" type="text"
                       value="{{ old('phone', $targetUser->phone) }}"
                       placeholder="{{ __('VD: 0912 345 678') }}"
                       class="block w-full rounded-xl border border-[#EADBCE] bg-[#FAF5F1]/30 py-2.5 px-3.5 text-sm text-[#2B1E19] placeholder-gray-400 transition focus:border-[#B38352] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#B38352]/15">
                <x-input-error class="mt-1 text-xs" :messages="$errors->get('phone')" />
            </div>

            <!-- Địa chỉ -->
            <div>
                <label for="address" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-[#4A3B32]">
                    {{ __('Địa chỉ') }}
                </label>
                <textarea id="address" name="address" rows="3"
                          placeholder="{{ __('Nhập địa chỉ của khách hàng...') }}"
                          class="block w-full rounded-xl border border-[#EADBCE] bg-[#FAF5F1]/30 py-2.5 px-3.5 text-sm text-[#2B1E19] placeholder-gray-400 transition focus:border-[#B38352] focus:bg-white focus:outline-none focus:ring-4 focus:ring-[#B38352]/15">{{ old('address', $targetUser->address) }}</textarea>
                <x-input-error class="mt-1 text-xs" :messages="$errors->get('address')" />
            </div>

            <!-- Thao tác nút -->
            <div class="flex items-center justify-end gap-3 border-t border-[#EADBCE]/60 pt-5">
                <a href="{{ route('admin.users.index') }}" 
                   class="rounded-xl border border-[#EADBCE] bg-white px-4 py-2.5 text-xs font-bold uppercase tracking-wider text-[#4A3B32] transition hover:bg-[#FAF5F1]">
                    {{ __('Huỷ') }}
                </a>
                <button type="submit" 
                        class="inline-flex items-center gap-2 rounded-xl bg-[#2B1E19] px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-[#FAF5F1] shadow-2xs transition hover:bg-[#B38352] active:scale-95">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ __('Lưu thay đổi') }}</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection