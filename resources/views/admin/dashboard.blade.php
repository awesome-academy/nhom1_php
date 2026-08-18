@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold text-[#1b1b18]">{{ __('Admin Dashboard') }}</h1>
<p class="mt-2 text-sm text-gray-500">{{ __("Chào mừng bạn quay lại!") }}</p>

<div class="mt-6 rounded-xl bg-white p-6 shadow-sm">
    {{ __("You're logged in!") }}

    {{-- Nội dung dashboard admin sẽ được bổ sung sau. --}}
</div>
@endsection
