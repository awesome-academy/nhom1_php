<div class="space-y-3 font-sans">
    <div class="aspect-square overflow-hidden rounded-[28px] border border-[#EADBCE] bg-white shadow-sm">
        @if ($product->images->isNotEmpty())
            @php $firstImage = $product->images->firstWhere('is_primary', true) ?? $product->images->first(); @endphp
            <img id="mainProductImage" src="{{ asset('storage/'.$firstImage->image_path) }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
        @else
            <div class="flex h-full w-full items-center justify-center text-[#C7B199]">
                <svg class="h-16 w-16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M4 6h16v12H4V6z" />
                </svg>
            </div>
        @endif
    </div>

    @if ($product->images->count() > 1)
        <div class="grid grid-cols-4 gap-3">
            @foreach ($product->images as $image)
                <button type="button"
                        onclick="document.getElementById('mainProductImage').src = '{{ asset('storage/'.$image->image_path) }}'"
                        class="aspect-square overflow-hidden rounded-xl border-2 {{ $image->is_primary ? 'border-[#B38352]' : 'border-[#EADBCE]' }} transition hover:border-[#B38352]">
                    <img src="{{ asset('storage/'.$image->image_path) }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                </button>
            @endforeach
        </div>
    @endif
</div>