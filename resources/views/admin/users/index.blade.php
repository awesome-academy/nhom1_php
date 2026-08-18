@extends('layouts.admin')

@section('content')
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h1 class="text-2xl font-bold text-[#1b1b18]">{{ __('Quản lý người dùng') }}</h1>
        <p class="mt-1 text-sm text-gray-500">{{ __('Danh sách tài khoản khách hàng trong hệ thống.') }}</p>
    </div>

    <!-- Thanh tìm kiếm và bộ lọc sắp xếp -->
    <form method="GET" class="flex flex-wrap items-center gap-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Tìm tên, email, SĐT...') }}"
               class="rounded-md border-gray-300 text-sm shadow-sm focus:border-amber-500 focus:ring-amber-500">

        <select name="sort" class="rounded-md border-gray-300 text-sm shadow-sm focus:border-amber-500 focus:ring-amber-500">
            <option value="latest" @selected(request('sort', 'latest') === 'latest')>{{ __('Mới nhất') }}</option>
            <option value="oldest" @selected(request('sort') === 'oldest')>{{ __('Cũ nhất') }}</option>
            <option value="name_asc" @selected(request('sort') === 'name_asc')>{{ __('Tên (A - Z)') }}</option>
        </select>

        <button type="submit" class="rounded-md bg-[#1b1b18] px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">
            {{ __('Lọc') }}
        </button>
    </form>
</div>

<div class="overflow-x-auto rounded-xl bg-white shadow-sm">
    <table class="min-w-full divide-y divide-gray-100 text-sm">
        <thead class="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
            <tr>
                <th class="px-6 py-3 w-16 text-center">{{ __('STT') }}</th>
                <th class="px-6 py-3">{{ __('Người dùng') }}</th>
                <th class="px-6 py-3">{{ __('Email') }}</th>
                <th class="px-6 py-3">{{ __('Số điện thoại') }}</th>
                <th class="px-6 py-3">{{ __('Địa chỉ') }}</th>
                <th class="px-6 py-3">{{ __('Ngày tạo') }}</th>
                <th class="px-6 py-3 text-right">{{ __('Hành động') }}</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 text-center font-medium text-gray-500">
                        {{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}
                    </td>
                    <td class="flex items-center gap-3 px-6 py-3">
                        <img src="{{ $user->avatar ? asset('storage/'.$user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=f59e0b&color=1b1b18' }}"
                             class="h-9 w-9 rounded-full object-cover" alt="{{ $user->name }}">
                        <span class="font-medium text-gray-900">{{ $user->name }}</span>
                    </td>
                    <td class="px-6 py-3 text-gray-600">{{ $user->email }}</td>
                    <td class="px-6 py-3 text-gray-600">{{ $user->phone ?? '—' }}</td>
                    <td class="px-6 py-3 text-gray-600 max-w-xs truncate" title="{{ $user->address }}">
                        {{ $user->address ?? '—' }}
                    </td>
                    <td class="px-6 py-3 text-gray-500">{{ $user->created_at->format('d/m/Y') }}</td>
                    <td class="px-6 py-3 text-right whitespace-nowrap">
                        <a href="{{ route('admin.users.edit', $user) }}" class="font-medium text-amber-600 hover:text-amber-700">{{ __('Sửa') }}</a>

                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline"
                              onsubmit="return confirm('{{ __('Xoá người dùng này?') }}');">
                            @csrf
                            @method('delete')
                            <button type="submit" class="ml-3 font-medium text-red-600 hover:text-red-700">{{ __('Xoá') }}</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-400">{{ __('Không tìm thấy người dùng nào.') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $users->withQueryString()->links() }}
</div>
@endsection