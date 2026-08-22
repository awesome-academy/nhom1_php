@extends(auth()->check() ? 'layouts.user-app' : 'layouts.user-guest')

@section('content')
<div class="font-sans antialiased selection:bg-[#B38352]/20 selection:text-[#2B1E19]">

    {{-- 1. Mini Hero Banner --}}
    @include('user.contact.partials.hero')

    {{-- 2. Khung Thông tin & Form Góp ý --}}
    <section class="bg-[#FAF5F1] py-12 lg:py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-10 lg:grid-cols-12 lg:gap-12 items-start">
                @include('user.contact.partials.store-info')
                @include('user.contact.partials.feedback-form')
            </div>
        </div>
    </section>

    {{-- 3. Toast Notification --}}
    @include('user.contact.partials.toast')

</div>
@endsection