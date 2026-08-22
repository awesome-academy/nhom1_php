@extends('layouts.admin')

@section('content')
<div class="space-y-6 font-sans antialiased">

    <!-- 1. Header & Search / Filter Toolbar -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2.5">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#FAF5F1] text-[#B38352] ring-1 ring-[#EADBCE]">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4" />
                    </svg>
                </span>
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-[#2B1E19]">{{ __('Quản lý người dùng') }}</h1>
                    <p class="text-xs text-[#736357]">{{ __('Danh sách tất cả tài khoản khách hàng trong hệ thống Brew & Bite.') }}</p>
                </div>
            </div>
        </div>

        <!-- Bộ lọc & Tìm kiếm -->
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap items-center gap-2.5">
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="{{ __('Tìm tên, email, SĐT...') }}"
                       class="w-56 rounded-xl border border-[#EADBCE] bg-white py-2 pl-9 pr-3 text-xs text-[#2B1E19] placeholder-gray-400 shadow-2xs transition focus:border-[#B38352] focus:outline-none focus:ring-2 focus:ring-[#B38352]/20 sm:w-64">
            </div>

            <select name="sort" 
                    class="rounded-xl border border-[#EADBCE] bg-white py-2 pl-3 pr-8 text-xs font-semibold text-[#4A3B32] shadow-2xs focus:border-[#B38352] focus:outline-none focus:ring-2 focus:ring-[#B38352]/20">
                <option value="latest" @selected(request('sort', 'latest') === 'latest')>{{ __('Mới nhất') }}</option>
                <option value="oldest" @selected(request('sort') === 'oldest')>{{ __('Cũ nhất') }}</option>
                <option value="name_asc" @selected(request('sort') === 'name_asc')>{{ __('Tên (A - Z)') }}</option>
            </select>

            <button type="submit" 
                    class="inline-flex items-center gap-1.5 rounded-xl bg-[#2B1E19] px-4 py-2 text-xs font-bold text-[#FAF5F1] shadow-2xs transition hover:bg-[#B38352] active:scale-95">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                {{ __('Lọc') }}
            </button>

            @if(request()->hasAny(['search', 'sort']))
                <a href="{{ route('admin.users.index') }}" 
                   class="rounded-xl border border-[#EADBCE] bg-white px-3 py-2 text-xs font-semibold text-[#736357] transition hover:bg-[#FAF5F1] hover:text-[#2B1E19]">
                    {{ __('Đặt lại') }}
                </a>
            @endif
        </form>
    </div>

    <!-- 2. Main Users Table Card -->
    <div class="overflow-hidden rounded-2xl border border-[#EADBCE] bg-white shadow-2xs">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 text-left text-xs">
                <thead class="bg-[#FAF5F1]/80 text-[11px] font-bold uppercase tracking-wider text-[#736357]">
                    <tr>
                        <th class="px-5 py-3.5 text-center w-14">{{ __('#') }}</th>
                        <th class="px-5 py-3.5 w-24 text-center">{{ __('User ID') }}</th>
                        <th class="px-5 py-3.5">{{ __('Người dùng') }}</th>
                        <th class="px-5 py-3.5">{{ __('Email') }}</th>
                        <th class="px-5 py-3.5">{{ __('Số điện thoại') }}</th>
                        <th class="px-5 py-3.5">{{ __('Địa chỉ') }}</th>
                        <th class="px-5 py-3.5">{{ __('Ngày đăng ký') }}</th>
                        <th class="px-5 py-3.5 text-right">{{ __('Thao tác') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($users as $user)
                        <tr class="transition hover:bg-[#FAF5F1]/40">
                            <!-- STT -->
                            <td class="px-5 py-3.5 text-center font-medium text-gray-400">
                                {{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}
                            </td>

                            <!-- # / User ID -->
                            <td class="px-5 py-3.5 text-center">
                                <span class="inline-flex items-center rounded-lg bg-[#FAF5F1] px-2 py-0.5 font-mono text-[11px] font-bold text-[#B38352] ring-1 ring-[#EADBCE]">
                                    #{{ $user->id }}
                                </span>
                            </td>

                            <!-- User Info (Avatar + Tên) -->
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $user->avatar ? asset('storage/'.$user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=FAF5F1&color=B38352&bold=true' }}"
                                         class="h-9 w-9 rounded-full object-cover ring-1 ring-[#EADBCE] shadow-2xs" 
                                         alt="{{ $user->name }}">
                                    <div>
                                        <p class="font-bold text-[#2B1E19]">{{ $user->name }}</p>
                                        <span class="text-[10px] font-semibold text-emerald-600">● {{ __('Khách hàng') }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Email -->
                            <td class="px-5 py-3.5 font-medium text-gray-600">
                                {{ $user->email }}
                            </td>

                            <!-- Phone -->
                            <td class="px-5 py-3.5 text-gray-600">
                                @if($user->phone)
                                    <span class="font-mono text-gray-700">{{ $user->phone }}</span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>

                            <!-- Address -->
                            <td class="px-5 py-3.5 text-gray-600 max-w-xs truncate" title="{{ $user->address }}">
                                {{ $user->address ?? '—' }}
                            </td>

                            <!-- Created At -->
                            <td class="px-5 py-3.5 text-gray-500 whitespace-nowrap">
                                {{ $user->created_at ? $user->created_at->format('d/m/Y') : '—' }}
                            </td>

                            <!-- Actions -->
                            <td class="px-5 py-3.5 text-right whitespace-nowrap space-x-1.5">
                                <a href="{{ route('admin.users.edit', $user) }}" 
                                   class="inline-flex items-center rounded-lg border border-[#EADBCE] bg-white px-2.5 py-1 text-[11px] font-bold text-[#4A3B32] shadow-2xs transition hover:border-[#B38352] hover:bg-[#FAF5F1] hover:text-[#B38352]">
                                    {{ __('Sửa') }}
                                </a>

                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline"
                                        onsubmit="return confirm('{{ __('Xoá vĩnh viễn tài khoản người dùng #') . $user->id . ' - ' . $user->name . '?' }}');">                                    @csrf
                                    @method('delete')
                                    <button type="submit" 
                                            class="inline-flex items-center rounded-lg border border-rose-200 bg-rose-50/60 px-2.5 py-1 text-[11px] font-bold text-rose-700 shadow-2xs transition hover:bg-rose-100 hover:text-rose-800">
                                        {{ __('Xoá') }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-14 text-center">
                                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-[#FAF5F1] text-[#B38352]">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4" />
                                    </svg>
                                </div>
                                <p class="mt-3 font-bold text-[#2B1E19]">{{ __('Không tìm thấy người dùng nào.') }}</p>
                                <p class="mt-1 text-xs text-gray-400">{{ __('Thử thay đổi từ khoá tìm kiếm hoặc thiết lập lại bộ lọc.') }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- 3. Pagination Footer -->
        @if ($users->hasPages())
            <div class="border-t border-gray-100 bg-[#FAF5F1]/40 px-5 py-3.5">
                {{ $users->withQueryString()->links() }}
            </div>
        @endif
    </div>

</div>
@endsection