<footer class="relative overflow-hidden bg-[#1b1210] text-white">
    <img src="{{ Vite::asset('resources/images/footer-bg.jpg') }}" alt="" class="absolute inset-0 h-full w-full object-cover opacity-40"> 
    <div class="absolute inset-0 bg-gradient-to-r from-[#1b1210] via-[#1b1210]/95 to-[#2a1a12]"></div>

    <div class="relative mx-auto grid max-w-7xl grid-cols-1 gap-10 px-6 py-16 sm:grid-cols-2 lg:grid-cols-4 lg:px-8">
        <div>
            <a href="{{ url('/') }}" class="text-2xl font-bold">
                Brew<span class="text-amber-400">Bite</span>
            </a>
            <p class="mt-4 max-w-xs text-sm leading-relaxed text-gray-300">
                {{ __('Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old.') }}
            </p>
        </div>

        <div>
            <h3 class="mb-4 text-lg font-semibold">{{ __('Explore') }}</h3>
            <ul class="space-y-3 text-sm text-gray-300">
                <li><a href="{{ url('/') }}" class="transition hover:text-amber-400">&rsaquo; {{ __('Home') }}</a></li>
                <li><a href="#" class="transition hover:text-amber-400">&rsaquo; {{ __('About us') }}</a></li>
                <li><a href="#" class="transition hover:text-amber-400">&rsaquo; {{ __('Contact us') }}</a></li>
            </ul>
        </div>

        <div>
            <h3 class="mb-4 text-lg font-semibold opacity-0 sm:opacity-100" aria-hidden="true">&nbsp;</h3>
            <ul class="space-y-3 text-sm text-gray-300">
                <li><a href="#" class="transition hover:text-amber-400">&rsaquo; {{ __('Blog') }}</a></li>
                <li><a href="#" class="transition hover:text-amber-400">&rsaquo; {{ __('Team') }}</a></li>
                <li><a href="#" class="transition hover:text-amber-400">&rsaquo; {{ __('Our Menu') }}</a></li>
            </ul>
        </div>

        <div>
            <h3 class="mb-4 text-lg font-semibold">{{ __('Contact us') }}</h3>
            <ul class="space-y-3 text-sm text-gray-300">
                <li>{{ __('Kolkata India, 3rd Floor, Office 45') }}</li>
                <li>00965 - 96659986</li>
                <li>M.Alyaqout@4house.Co</li>
                <li>{{ __('Sun - Sat / 10:00 AM - 8:00 PM') }}</li>
            </ul>
        </div>
    </div>

    <div class="relative border-t border-white/10 bg-amber-700/90">
        <p class="py-4 text-center text-xs text-white/90 sm:text-sm">
            {{ __('Copyright © :year by :name. All Rights Reserved.', ['year' => date('Y'), 'name' => config('app.name')]) }}
        </p>
    </div>
</footer>
