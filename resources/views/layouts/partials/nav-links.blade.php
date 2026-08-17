@php
    $navLinks = [
        ['label' => __('Home'), 'url' => url('/'), 'active' => request()->is('/')],
        ['label' => __('Menu'), 'url' => '#', 'active' => false],
        ['label' => __('Blog'), 'url' => '#', 'active' => false],
        ['label' => __('Pages'), 'url' => '#', 'active' => false],
        ['label' => __('About'), 'url' => '#', 'active' => false],
        ['label' => __('Shop'), 'url' => '#', 'active' => false],
        ['label' => __('Contact'), 'url' => '#', 'active' => false],
    ];
@endphp

@foreach ($navLinks as $link)
    <a
        href="{{ $link['url'] }}"
        class="transition hover:text-amber-400 {{ $link['active'] ? 'text-amber-400' : 'text-white' }}"
    >
        {{ $link['label'] }}
    </a>
@endforeach
