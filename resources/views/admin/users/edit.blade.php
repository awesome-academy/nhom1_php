@extends('layouts.admin')

@section('content')
<div class="max-w-xl">
    <h1 class="mb-6 text-2xl font-bold text-[#1b1b18]">{{ __('Chỉnh sửa người dùng') }}</h1>

    <form method="POST" action="{{ route('admin.users.update', $targetUser) }}" class="space-y-6 rounded-xl bg-white p-6 shadow-sm">
        @csrf
        @method('put')

        <div>
            <x-input-label for="name" :value="__('Họ và tên')" />
            <x-text-input id="name" name="name" class="mt-1 block w-full" :value="old('name', $targetUser->name)" required />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $targetUser->email)" required />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div>
            <x-input-label for="role" :value="__('Vai trò')" />
            <select id="role" name="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">
                <option value="user" @selected($targetUser->role === 'user')>User</option>
                <option value="admin" @selected($targetUser->role === 'admin')>Admin</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('role')" />
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="rounded-md bg-[#1b1b18] px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">
                {{ __('Lưu thay đổi') }}
            </button>
            <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-500 hover:text-gray-700">{{ __('Huỷ') }}</a>
        </div>
    </form>
</div>
@endsection
