@php
    $navLinks = [
        ['label' => __('Trang chủ'), 'url' => url('/'), 'active' => request()->is('/')],
        ['label' => __('Menu'), 'url' => '#', 'active' => request()->is('menu*')],
        ['label' => __('Shop'), 'url' => '#', 'active' => request()->is('shop*')],
        ['label' => __('Về chúng tôi'), 'url' => '#', 'active' => request()->is('about*')],
        ['label' => __('Liên hệ'), 'url' => '#', 'active' => request()->is('contact*')],
    ];
@endphp

@foreach ($navLinks as $link)
    <a
        href="{{ $link['url'] }}"
        class="relative px-1 py-1 text-sm font-medium transition-all duration-200 
               {{ $link['active'] 
                   ? 'font-semibold text-[#B38352]' 
                   : 'text-[#736357] hover:text-[#2B1E19]' }}"
    >
        {{ $link['label'] }}
        
        {{-- Đường gạch chân active / hover tinh tế phong cách mộc --}}
        @if ($link['active'])
            <span class="absolute inset-x-0 -bottom-1 h-0.5 rounded-full bg-[#B38352]"></span>
        @endif
    </a>
@endforeach