@extends('layouts.user-app')

@section('content')
<div class="relative min-h-[calc(100vh-5rem)] overflow-hidden bg-[#FAF5F1] py-12 px-4 sm:px-6 lg:px-8"
     style="background:
        radial-gradient(ellipse 900px 600px at 15% -10%, #FFFFFF 0%, rgba(255,255,255,0) 60%),
        linear-gradient(160deg, #FFFFFF 0%, #FFFBF6 38%, #FAF5F1 62%, #F3E7D8 84%, #EADBCE 100%);">

    <!-- Hoa văn hạt cà phê mộc đặc trưng thương hiệu -->
    <div class="pointer-events-none absolute inset-0 opacity-40"
         style="background-image:url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='120' height='120'%3E%3Cg fill='none' stroke='%23B38352' stroke-width='1.3' opacity='0.16'%3E%3Cellipse cx='30' cy='30' rx='9' ry='15' transform='rotate(24 30 30)'/%3E%3Cpath d='M30 17 Q26 30 30 43' transform='rotate(24 30 30)'/%3E%3Cellipse cx='92' cy='86' rx='9' ry='15' transform='rotate(-18 92 86)'/%3E%3Cpath d='M92 73 Q88 86 92 99' transform='rotate(-18 92 86)'/%3E%3C/g%3E%3C/svg%3E&quot;); background-size:220px 220px;">
    </div>

    <div class="relative mx-auto max-w-5xl" x-data="{ tab: 'info' }">
        
        <!-- Header Tiêu đề Trang -->
        <div class="mb-8 flex flex-col items-start gap-1">
            <span class="font-serif text-[11px] font-semibold tracking-[0.25em] text-[#B38352] uppercase">
                Brew &amp; Bite Artisan
            </span>
            <h1 class="font-serif text-3xl font-bold tracking-tight text-[#2B1E19] sm:text-4xl">
                {{ __('Tài khoản của tôi') }}
            </h1>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-[290px_1fr]">
            
            <!-- Sidebar Trái: Avatar & Tab Navigation -->
            <div class="space-y-6">
                <!-- User Profile Card -->
                <div class="relative rounded-[24px] border border-[#EADBCE] bg-white/95 p-6 text-center shadow-[0_15px_35px_rgba(43,30,25,0.06)] backdrop-blur-sm">
                    <div class="relative mx-auto inline-block">
                        <img src="{{ $user->avatar ? (Str::startsWith($user->avatar, ['http://', 'https://']) ? $user->avatar : asset('storage/'.$user->avatar)) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=B38352&color=FAF5F1&size=128' }}"
                             class="h-24 w-24 rounded-full object-cover ring-4 ring-[#EADBCE]/80 shadow-md" 
                             alt="{{ $user->name }}">

                        <form method="POST" action="{{ route('profile.avatar.update') }}" enctype="multipart/form-data" id="avatar-form">
                            @csrf
                            <input type="file" name="avatar" id="avatar-input" accept="image/*" class="hidden"
                                   onchange="document.getElementById('avatar-form').submit();">
                            <label for="avatar-input"
                                   class="absolute bottom-0 right-0 flex h-8 w-8 cursor-pointer items-center justify-center rounded-full bg-[#2B1E19] text-[#FAF5F1] shadow-md transition hover:bg-[#B38352]"
                                   title="{{ __('Đổi ảnh đại diện') }}">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                    <circle cx="12" cy="13" r="3"/>
                                </svg>
                            </label>
                        </form>
                    </div>

                    <h2 class="mt-4 font-serif text-lg font-bold text-[#2B1E19]">{{ $user->name }}</h2>
                    <p class="text-xs text-[#736357]">{{ $user->email }}</p>

                    @if (session('success'))
                        <div class="mt-3 rounded-xl bg-green-50 p-2 text-xs font-semibold text-green-700 border border-green-200">
                            {{ session('success') }}
                        </div>
                    @endif
                </div>

                <!-- Navigation Tabs -->
                <nav class="space-y-1.5 rounded-[22px] border border-[#EADBCE] bg-white/95 p-3 shadow-sm">
                    <button type="button" @click="tab = 'info'"
                            :class="tab === 'info' ? 'bg-[#2B1E19] text-[#FAF5F1] shadow-sm font-semibold' : 'text-[#736357] hover:bg-[#FAF5F1] hover:text-[#2B1E19]'"
                            class="flex w-full items-center gap-3 rounded-xl px-4 py-3 text-left text-xs font-bold uppercase tracking-wider transition">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5z" />
                        </svg>
                        <span>{{ __('Thông tin tài khoản') }}</span>
                    </button>
                    
                    <button type="button" @click="tab = 'password'"
                            :class="tab === 'password' ? 'bg-[#2B1E19] text-[#FAF5F1] shadow-sm font-semibold' : 'text-[#736357] hover:bg-[#FAF5F1] hover:text-[#2B1E19]'"
                            class="flex w-full items-center gap-3 rounded-xl px-4 py-3 text-left text-xs font-bold uppercase tracking-wider transition">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <span>{{ __('Đổi mật khẩu') }}</span>
                    </button>
                    
                    <a href="{{ route('orders.index') }}"
                       class="flex w-full items-center gap-3 rounded-xl px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-[#736357] transition hover:bg-[#FAF5F1] hover:text-[#2B1E19]">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <span>{{ __('Đơn hàng của tôi') }}</span>
                    </a>
                    
                    <div class="my-1 border-t border-[#EADBCE]/60"></div>

                    <button type="button" @click="tab = 'delete'"
                            :class="tab === 'delete' ? 'bg-red-600 text-white font-semibold' : 'text-red-600 hover:bg-red-50'"
                            class="flex w-full items-center gap-3 rounded-xl px-4 py-3 text-left text-xs font-bold uppercase tracking-wider transition">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <span>{{ __('Xoá tài khoản') }}</span>
                    </button>
                </nav>
            </div>

            <!-- Cột Phải: Nội Dung Tab Form Container -->
            <div class="relative space-y-6">
                <!-- Tab: Thông tin cá nhân -->
                <div x-show="tab === 'info'" x-cloak class="relative rounded-[28px] border border-[#EADBCE] bg-white/95 p-8 shadow-[0_20px_50px_rgba(43,30,25,0.06)] backdrop-blur-sm sm:p-10">
                    <div class="pointer-events-none absolute inset-2.5 rounded-[20px] border border-dashed border-[#B38352]/20"></div>
                    <div class="relative">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <!-- Tab: Đổi mật khẩu -->
                <div x-show="tab === 'password'" x-cloak class="relative rounded-[28px] border border-[#EADBCE] bg-white/95 p-8 shadow-[0_20px_50px_rgba(43,30,25,0.06)] backdrop-blur-sm sm:p-10">
                    <div class="pointer-events-none absolute inset-2.5 rounded-[20px] border border-dashed border-[#B38352]/20"></div>
                    <div class="relative">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <!-- Tab: Xoá tài khoản -->
                <div x-show="tab === 'delete'" x-cloak class="relative rounded-[28px] border border-red-200 bg-white/95 p-8 shadow-[0_20px_50px_rgba(43,30,25,0.06)] backdrop-blur-sm sm:p-10">
                    <div class="pointer-events-none absolute inset-2.5 rounded-[20px] border border-dashed border-red-200"></div>
                    <div class="relative">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection