<div class="mt-8 overflow-hidden rounded-[28px] border border-[#EADBCE] bg-white shadow-sm font-sans">
    <!-- Header Đánh giá -->
    <div class="flex flex-col gap-4 border-b border-[#EADBCE]/60 bg-[#FAF5F1]/80 px-8 py-6 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-3">
            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#2B1E19] text-[#B38352]">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                </svg>
            </div>
            <div>
                <h2 class="font-sans text-lg font-bold text-[#2B1E19]">
                    {{ __('Đánh giá từ khách hàng') }}
                </h2>
                <p class="text-xs text-[#A39284]">{{ __('Tổng hợp phản hồi từ những khách hàng đã trải nghiệm') }}</p>
            </div>
        </div>

        <!-- Tổng quan số sao trung bình -->
        <div class="flex items-center gap-3 rounded-2xl bg-white px-4 py-2 border border-[#EADBCE] shadow-sm">
            <span class="font-sans text-2xl font-black text-[#2B1E19]">
                {{ number_format((float) ($product->ratings_avg_rating ?? 0), 1) }}
            </span>
            <div>
                <div class="flex items-center gap-0.5">
                    @for ($i = 1; $i <= 5; $i++)
                        <svg class="h-3.5 w-3.5 fill-current {{ $i <= round($product->ratings_avg_rating ?? 0) ? 'text-[#B38352]' : 'text-gray-300' }}" viewBox="0 0 20 20">
                            <path d="M10 15l-5.878 3.09L5.64 11.545.762 7.41l6.09-.885L10 1l3.148 5.525 6.09.885-4.878 4.135 1.518 6.545z"/>
                        </svg>
                    @endfor
                </div>
                <p class="text-[11px] text-[#A39284]">({{ $product->ratings_count ?? 0 }} {{ __('đánh giá') }})</p>
            </div>
        </div>
    </div>

    <div class="p-8">
        <!-- Form gửi đánh giá cho User đã mua hàng -->
        @auth
            <div class="mb-8 rounded-2xl border border-[#EADBCE] bg-[#FAF5F1]/60 p-6 shadow-sm">
                <p class="mb-2 text-xs font-bold uppercase tracking-wider text-[#4A3B32]">{{ __('Viết đánh giá của bạn') }}</p>

                <div class="mb-3 flex items-center gap-1">
                    <template x-for="i in 5" :key="i">
                        <button type="button" @click="newRating.rating = i" class="focus:outline-none">
                            <svg class="h-7 w-7 cursor-pointer fill-current transition hover:scale-110"
                                 :class="i <= newRating.rating ? 'text-[#B38352]' : 'text-gray-300'" viewBox="0 0 20 20">
                                <path d="M10 15l-5.878 3.09L5.64 11.545.762 7.41l6.09-.885L10 1l3.148 5.525 6.09.885-4.878 4.135 1.518 6.545z"/>
                            </svg>
                        </button>
                    </template>
                </div>

                <textarea x-model="newRating.comment" rows="3" placeholder="{{ __('Chia sẻ cảm nhận chi tiết của bạn về hương vị và trải nghiệm món này...') }}"
                          class="block w-full rounded-2xl border border-[#EADBCE] bg-white py-3 px-4 text-sm text-[#2B1E19] placeholder-[#A39284] focus:border-[#B38352] focus:outline-none focus:ring-4 focus:ring-[#B38352]/15"></textarea>

                <div class="mt-4 flex flex-wrap items-center justify-between gap-3">
                    <p class="text-[11px] text-[#A39284] italic">{{ __('Chỉ khách hàng đã mua và hoàn tất đơn hàng có món này mới có thể gửi đánh giá.') }}</p>
                    <div class="flex items-center gap-3">
                        <span x-show="ratingMessage" x-text="ratingMessage" class="text-xs font-semibold" :class="ratingSuccess ? 'text-green-600' : 'text-red-600'"></span>
                        <button type="button" @click="submitRating({{ $product->id }})" :disabled="submittingRating || newRating.rating === 0"
                                class="rounded-xl bg-[#2B1E19] px-6 py-2.5 text-xs font-bold uppercase tracking-wider text-[#FAF5F1] shadow-md transition hover:bg-[#B38352] disabled:cursor-not-allowed disabled:opacity-50">
                            <span x-show="!submittingRating">{{ __('Gửi đánh giá') }}</span>
                            <span x-show="submittingRating">{{ __('Đang gửi...') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        @else
            <div class="mb-6 rounded-2xl border border-[#EADBCE] bg-[#FAF5F1]/40 p-4 text-center">
                <p class="text-xs text-[#736357]">
                    {{ __('Vui lòng') }} <a href="{{ route('login') }}" class="font-bold text-[#B38352] hover:underline">{{ __('đăng nhập') }}</a> {{ __('để viết đánh giá cho sản phẩm này.') }}
                </p>
            </div>
        @endauth

        <!-- Danh sách nhận xét -->
        <div class="space-y-6">
            @forelse ($product->ratings as $rating)
                <div class="flex gap-4 border-b border-[#EADBCE]/50 pb-6 last:border-0 last:pb-0">
                    <img src="{{ $rating->user?->avatar ? (Str::startsWith($rating->user->avatar, ['http://', 'https://']) ? $rating->user->avatar : asset('storage/'.$rating->user->avatar)) : 'https://ui-avatars.com/api/?name='.urlencode($rating->user?->name ?? 'User').'&background=B38352&color=FAF5F1' }}"
                         class="h-10 w-10 rounded-full object-cover ring-2 ring-[#EADBCE]" alt="{{ $rating->user?->name }}">
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center justify-between gap-1">
                            <p class="text-sm font-bold text-[#2B1E19]">{{ $rating->user?->name ?? __('Khách hàng') }}</p>
                            <span class="text-[11px] text-[#A39284]">{{ $rating->created_at?->diffForHumans() }}</span>
                        </div>
                        <div class="mt-1 flex items-center gap-0.5">
                            @for ($i = 1; $i <= 5; $i++)
                                <svg class="h-3.5 w-3.5 fill-current {{ $i <= $rating->rating ? 'text-[#B38352]' : 'text-gray-300' }}" viewBox="0 0 20 20">
                                    <path d="M10 15l-5.878 3.09L5.64 11.545.762 7.41l6.09-.885L10 1l3.148 5.525 6.09.885-4.878 4.135 1.518 6.545z"/>
                                </svg>
                            @endfor
                        </div>
                        @if ($rating->comment)
                            <div class="mt-2.5 rounded-2xl bg-[#FAF5F1]/70 p-3.5 border border-[#EADBCE]/60">
                                <p class="text-sm leading-relaxed text-[#736357]">{{ $rating->comment }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="py-8 text-center text-[#A39284]">
                    <p class="text-sm">{{ __('Chưa có đánh giá nào cho sản phẩm này. Hãy là người đầu tiên trải nghiệm!') }}</p>
                </div>
            @endforelse
        </div>
    </div>
</div>