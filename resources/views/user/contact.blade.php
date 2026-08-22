@extends(auth()->check() ? 'layouts.user-app' : 'layouts.user-guest')

@section('content')

    {{-- ============================================================
    HERO MINI SECTION
    ============================================================ --}}
    <section class="relative overflow-hidden bg-[#16100c] py-16 text-white sm:py-20">
        {{-- Ambient glow --}}
        <div
            class="pointer-events-none absolute -top-24 left-1/2 h-72 w-72 -translate-x-1/2 rounded-full bg-[#B38352]/15 blur-3xl">
        </div>
        {{-- Hoa văn chấm bi phong cách thương hiệu --}}
        <div class="pointer-events-none absolute inset-0 opacity-10"
            style="background-image:url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60'%3E%3Ccircle cx='30' cy='30' r='1.5' fill='%23B38352'/%3E%3C/svg%3E&quot;); background-size:60px 60px;">
        </div>

        <div class="relative z-10 mx-auto max-w-3xl px-4 text-center sm:px-6 lg:px-8">
            {{-- Badge --}}
            <h1 class="font-serif text-3xl font-bold tracking-tight text-[#FAF5F1] sm:text-4xl lg:text-5xl">
                {{ __('Chúng Tôi Lắng Nghe') }}
                <span class="block text-[#B38352]">{{ __('Mọi Ý Kiến Của Bạn') }}</span>
            </h1>
            <p class="mt-4 text-sm leading-relaxed text-gray-300 sm:text-base">
                {{ __('Hãy chia sẻ cảm nhận, góp ý hoặc đặt câu hỏi. Mỗi phản hồi đều giúp Brew & Bite trở nên tốt hơn mỗi ngày.') }}
            </p>
        </div>
    </section>

    {{-- ============================================================
    MAIN CONTENT: 2-COLUMN GRID
    ============================================================ --}}
    <section class="bg-[#FAF5F1] py-14 lg:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-10 lg:grid-cols-2 lg:gap-16">

                {{-- =========================================
                CỘT TRÁI: THÔNG TIN LIÊN HỆ
                ========================================= --}}
                <div class="space-y-8">
                    <div>
                        <div class="mb-2 inline-flex items-center gap-2">
                            <span class="font-serif text-xs font-semibold uppercase tracking-[0.2em] text-[#B38352]">Thông
                                tin</span>
                            <span class="h-px w-8 bg-[#B38352]/60"></span>
                        </div>
                        <h2 class="font-serif text-2xl font-bold text-[#2B1E19] sm:text-3xl">{{ __('Tìm Chúng Tôi') }}</h2>
                        <p class="mt-2 text-sm leading-relaxed text-[#736357]">
                            {{ __('Ghé thăm quán hoặc liên hệ trực tiếp qua các kênh bên dưới.') }}
                        </p>
                    </div>

                    <div class="space-y-4">
                        {{-- Địa chỉ --}}
                        <div
                            class="flex items-start gap-4 rounded-2xl border border-[#EADBCE] bg-white p-5 shadow-sm transition hover:shadow-md">
                            <div
                                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#2B1E19] text-[#B38352] shadow-sm">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-[#2B1E19]">{{ __('Địa chỉ') }}</p>
                                <p class="mt-1 text-sm text-[#736357]">123 Đường Cà Phê, Quận 1<br>TP. Hồ Chí Minh, Việt Nam
                                </p>
                            </div>
                        </div>

                        {{-- Giờ mở cửa --}}
                        <div
                            class="flex items-start gap-4 rounded-2xl border border-[#EADBCE] bg-white p-5 shadow-sm transition hover:shadow-md">
                            <div
                                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#2B1E19] text-[#B38352] shadow-sm">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-[#2B1E19]">{{ __('Giờ mở cửa') }}</p>
                                <p class="mt-1 text-sm text-[#736357]">
                                    Thứ 2 – Thứ 6: 07:00 – 22:00<br>
                                    Thứ 7 – Chủ nhật: 07:00 – 23:00
                                </p>
                            </div>
                        </div>

                        {{-- Email --}}
                        <div
                            class="flex items-start gap-4 rounded-2xl border border-[#EADBCE] bg-white p-5 shadow-sm transition hover:shadow-md">
                            <div
                                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#2B1E19] text-[#B38352] shadow-sm">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-[#2B1E19]">{{ __('Email') }}</p>
                                <a href="mailto:hello@brewbite.vn"
                                    class="mt-1 block text-sm text-[#B38352] transition hover:text-[#9E7143]">hello@brewbite.vn</a>
                            </div>
                        </div>

                        {{-- Điện thoại --}}
                        <div
                            class="flex items-start gap-4 rounded-2xl border border-[#EADBCE] bg-white p-5 shadow-sm transition hover:shadow-md">
                            <div
                                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#2B1E19] text-[#B38352] shadow-sm">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-[#2B1E19]">{{ __('Điện thoại') }}</p>
                                <a href="tel:+84901234567"
                                    class="mt-1 block text-sm text-[#B38352] transition hover:text-[#9E7143]">+84 90 123
                                    4567</a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- =========================================
                CỘT PHẢI: FORM GỬI GÓP Ý
                ========================================= --}}
                <div>
                    <div class="rounded-3xl border border-[#EADBCE] bg-white p-7 shadow-xl sm:p-9">
                        <div class="mb-7">
                            <div class="mb-2 inline-flex items-center gap-2">
                                <span class="font-serif text-xs font-semibold uppercase tracking-[0.2em] text-[#B38352]">Góp
                                    ý</span>
                                <span class="h-px w-8 bg-[#B38352]/60"></span>
                            </div>
                            <h2 class="font-serif text-2xl font-bold text-[#2B1E19]">{{ __('Gửi Góp Ý Cho Chúng Tôi') }}
                            </h2>
                            <p class="mt-1.5 text-sm text-[#736357]">{{ __('Mọi góp ý đều được xem xét và phản hồi.') }}</p>
                        </div>

                        @auth
                            {{-- ===== FORM (đã đăng nhập) – Alpine.js ===== --}}
                            <div x-data="{
                                        topic: '',
                                        content: '',
                                        loading: false,
                                        topicError: false,
                                        contentError: false,
                                        charCount: 0,
                                        maxChar: 1000,
                                        get charLabel() { return this.charCount + ' / ' + this.maxChar; },
                                        onInput(e) {
                                            if (e.target.value.length > this.maxChar) {
                                                e.target.value = e.target.value.slice(0, this.maxChar);
                                            }
                                            this.content = e.target.value;
                                            this.charCount = this.content.length;
                                            if (this.content.trim()) this.contentError = false;
                                        },
                                        async submit() {
                                            this.topicError   = !this.topic;
                                            this.contentError = !this.content.trim();
                                            if (this.topicError || this.contentError) return;
                                            this.loading = true;
                                            try {
                                                const res = await fetch('/api/suggestions', {
                                                    method: 'POST',
                                                    headers: {
                                                        'Content-Type': 'application/json',
                                                        'Accept': 'application/json',
                                                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                                    },
                                                    credentials: 'same-origin',
                                                    body: JSON.stringify({ content: this.topic + ' ' + this.content.trim() }),
                                                });
                                                const json = await res.json();
                                                if (res.ok) {
                                                    this.topic = ''; this.content = ''; this.charCount = 0;
                                                    window.dispatchEvent(new CustomEvent('suggestion-submitted'));
                                                    window.dispatchEvent(new CustomEvent('show-toast', { detail: { type: 'success', title: 'Đã gửi thành công!', message: 'Cảm ơn bạn đã góp ý. Chúng tôi sẽ xem xét sớm nhất.' } }));
                                                } else if (res.status === 401) {
                                                    window.dispatchEvent(new CustomEvent('show-toast', { detail: { type: 'error', title: 'Phiên đăng nhập hết hạn', message: 'Vui lòng đăng nhập lại.' } }));
                                                } else if (res.status === 422) {
                                                    const msg = Object.values(json.errors ?? {}).flat()[0] ?? 'Dữ liệu không hợp lệ.';
                                                    window.dispatchEvent(new CustomEvent('show-toast', { detail: { type: 'error', title: 'Dữ liệu không hợp lệ', message: msg } }));
                                                } else {
                                                    window.dispatchEvent(new CustomEvent('show-toast', { detail: { type: 'error', title: 'Gửi thất bại', message: 'Đã xảy ra lỗi. Vui lòng thử lại.' } }));
                                                }
                                            } catch {
                                                window.dispatchEvent(new CustomEvent('show-toast', { detail: { type: 'error', title: 'Lỗi kết nối', message: 'Không thể kết nối đến máy chủ.' } }));
                                            } finally {
                                                this.loading = false;
                                            }
                                        }
                                    }">
                                <form x-on:submit.prevent="submit()" novalidate class="space-y-5">
                                    @csrf

                                    {{-- Dropdown chủ đề --}}
                                    <div>
                                        <label for="suggestion-topic" class="mb-1.5 block text-sm font-semibold text-[#2B1E19]">
                                            {{ __('Chủ đề') }} <span class="text-[#B38352]">*</span>
                                        </label>
                                        <div class="relative">
                                            <select id="suggestion-topic" x-model="topic" x-on:change="topicError = !topic"
                                                :class="topicError ? 'border-red-400' : 'border-[#EADBCE]'"
                                                class="w-full appearance-none rounded-xl border bg-[#FAF5F1] py-3 pl-4 pr-10 text-sm text-[#2B1E19] shadow-sm outline-none transition focus:border-[#B38352] focus:ring-2 focus:ring-[#B38352]/20">
                                                <option value="" disabled>{{ __('— Chọn chủ đề góp ý —') }}</option>
                                                <option value="[Chất lượng sản phẩm]">{{ __('Chất lượng sản phẩm') }}
                                                </option>
                                                <option value="[Dịch vụ & Phục vụ]">{{ __('Dịch vụ & Phục vụ') }}</option>
                                                <option value="[Menu & Thực đơn]">{{ __('Menu & Thực đơn') }}</option>
                                                <option value="[Giá cả]">{{ __('Giá cả') }}</option>
                                                <option value="[Không gian quán]">{{ __('Không gian quán') }}</option>
                                                <option value="[Giao hàng & Đặt hàng]">{{ __('Giao hàng & Đặt hàng') }}
                                                </option>
                                                <option value="[Khác]">{{ __('Khác') }}</option>
                                            </select>
                                            <div
                                                class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-[#B38352]">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                    stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                                </svg>
                                            </div>
                                        </div>
                                        <p x-show="topicError" x-cloak class="mt-1.5 text-xs font-medium text-red-500">
                                            {{ __('Vui lòng chọn chủ đề.') }}</p>
                                    </div>

                                    {{-- Nội dung góp ý --}}
                                    <div>
                                        <label for="suggestion-content"
                                            class="mb-1.5 block text-sm font-semibold text-[#2B1E19]">
                                            {{ __('Nội dung góp ý') }} <span class="text-[#B38352]">*</span>
                                        </label>
                                        <textarea id="suggestion-content" rows="5"
                                            :class="contentError ? 'border-red-400' : 'border-[#EADBCE]'"
                                            x-on:input="onInput($event)"
                                            placeholder="{{ __('Chia sẻ ý kiến, nhận xét hoặc đề xuất của bạn...') }}"
                                            class="w-full resize-none rounded-xl border bg-[#FAF5F1] px-4 py-3 text-sm text-[#2B1E19] placeholder-[#A39284] shadow-sm outline-none transition focus:border-[#B38352] focus:ring-2 focus:ring-[#B38352]/20"></textarea>
                                        <div class="mt-1 flex items-center justify-between">
                                            <p x-show="contentError" x-cloak class="text-xs font-medium text-red-500">
                                                {{ __('Vui lòng nhập nội dung góp ý.') }}</p>
                                            <span x-text="charLabel"
                                                :class="charCount >= maxChar ? 'text-red-500 font-medium' : 'text-[#A39284]'"
                                                class="ml-auto text-xs">0 / 1000</span>
                                        </div>
                                    </div>

                                    {{-- Nút Submit --}}
                                    <button type="submit" :disabled="loading"
                                        class="group flex w-full items-center justify-center gap-2.5 rounded-xl bg-[#B38352] px-6 py-3.5 text-sm font-semibold text-white shadow-lg shadow-[#B38352]/20 transition-all duration-200 hover:bg-[#9E7143] hover:shadow-xl hover:shadow-[#B38352]/30 active:scale-95 disabled:cursor-not-allowed disabled:opacity-60">
                                        <svg x-show="!loading" class="h-4 w-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                        </svg>
                                        <svg x-show="loading" x-cloak class="h-4 w-4 animate-spin" fill="none"
                                            viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                        </svg>
                                        <span
                                            x-text="loading ? 'Đang gửi...' : '{{ __('Gửi góp ý') }}'">{{ __('Gửi góp ý') }}</span>
                                    </button>
                                </form>
                            </div>

                        @else
                            {{-- ===== CHƯA ĐĂNG NHẬP ===== --}}
                            <div
                                class="flex flex-col items-center rounded-2xl border border-[#EADBCE] bg-[#FAF5F1] py-12 text-center">
                                <div
                                    class="mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-[#2B1E19]/5 text-[#B38352]">
                                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        stroke-width="1.6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <p class="mb-1 text-base font-semibold text-[#2B1E19]">{{ __('Vui lòng đăng nhập') }}</p>
                                <p class="mb-6 text-sm text-[#736357]">{{ __('Bạn cần đăng nhập để gửi góp ý cho chúng tôi.') }}
                                </p>
                                <a href="{{ route('login') }}"
                                    class="inline-flex items-center gap-2 rounded-xl bg-[#B38352] px-6 py-3 text-sm font-semibold text-white shadow-md shadow-[#B38352]/20 transition hover:bg-[#9E7143] active:scale-95">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                    </svg>
                                    {{ __('Đăng nhập ngay') }}
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>

            </div>{{-- end grid --}}
        </div>
    </section>

    {{-- ============================================================
    SECTION: LỊCH SỬ GÓP Ý (chỉ hiện khi đã đăng nhập)
    ============================================================ --}}
    @auth
        <section class="bg-white py-14 lg:py-20" x-data="{
                suggestions: [],
                loadState: 'loading',
                parseTopic(content) {
                    const m = content.match(/^\[([^\]]+)\]\s*/);
                    return m ? { topic: m[1], body: content.slice(m[0].length) } : { topic: null, body: content };
                },
                fmtDate(iso) {
                    return new Date(iso).toLocaleString('vi-VN', { day:'2-digit', month:'2-digit', year:'numeric', hour:'2-digit', minute:'2-digit' });
                },
                statusLabel(s) {
                    return { pending:'⏳ Đang xem xét', approved:'✅ Đã duyệt', rejected:'❌ Không duyệt' }[s] ?? s;
                },
                statusClass(s) {
                    return { pending:'bg-amber-100 text-amber-700 border-amber-200', approved:'bg-green-100 text-green-700 border-green-200', rejected:'bg-red-100 text-red-600 border-red-200' }[s] ?? 'bg-gray-100 text-gray-600 border-gray-200';
                },
                async load() {
                    this.loadState = 'loading';
                    try {
                        const res = await fetch('/api/suggestions/me', {
                            headers: { 'Accept':'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                            credentials: 'same-origin',
                        });
                        if (!res.ok) throw new Error();
                        const json = await res.json();
                        this.suggestions = json.data ?? json;
                        this.loadState = this.suggestions.length ? 'loaded' : 'empty';
                    } catch { this.loadState = 'error'; }
                }
            }" x-init="load()" x-on:suggestion-submitted.window="load()">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">

                {{-- Section header --}}
                <div class="mb-8 flex items-center justify-between">
                    <div>
                        <div class="mb-2 inline-flex items-center gap-2">
                            <span class="font-serif text-xs font-semibold uppercase tracking-[0.2em] text-[#B38352]">Của
                                tôi</span>
                            <span class="h-px w-8 bg-[#B38352]/60"></span>
                        </div>
                        <h2 class="font-serif text-2xl font-bold text-[#2B1E19]">{{ __('Lịch Sử Góp Ý') }}</h2>
                    </div>
                    {{-- Nút tải lại --}}
                    <button x-on:click="load()" title="{{ __('Tải lại') }}"
                        class="flex h-9 w-9 items-center justify-center rounded-xl border border-[#EADBCE] bg-[#FAF5F1] text-[#736357] transition hover:border-[#B38352] hover:text-[#B38352]">
                        <svg :class="loadState === 'loading' ? 'animate-spin' : ''" class="h-4 w-4" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </button>
                </div>

                {{-- Skeleton loader --}}
                <div x-show="loadState === 'loading'" class="space-y-4">
                    <template x-for="i in 2" :key="i">
                        <div class="animate-pulse rounded-2xl border border-[#EADBCE] bg-[#FAF5F1] p-5">
                            <div class="flex items-center justify-between">
                                <div class="h-4 w-32 rounded-lg bg-[#EADBCE]"></div>
                                <div class="h-6 w-20 rounded-full bg-[#EADBCE]"></div>
                            </div>
                            <div class="mt-3 space-y-2">
                                <div class="h-3 w-full rounded bg-[#EADBCE]"></div>
                                <div class="h-3 w-4/5 rounded bg-[#EADBCE]"></div>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Danh sách góp ý --}}
                <div x-show="loadState === 'loaded'" x-cloak class="space-y-4">
                    <template x-for="s in suggestions" :key="s.id">
                        <div class="rounded-2xl border border-[#EADBCE] bg-[#FAF5F1] p-5 transition hover:shadow-md">
                            <div class="flex flex-wrap items-start justify-between gap-2">
                                <div class="flex flex-wrap items-center gap-1.5">
                                    <template x-if="parseTopic(s.content).topic">
                                        <span
                                            class="inline-block rounded-lg border border-[#EADBCE] bg-white px-2.5 py-0.5 text-xs font-semibold text-[#B38352]"
                                            x-text="parseTopic(s.content).topic"></span>
                                    </template>
                                    <span class="text-xs text-[#A39284]" x-text="fmtDate(s.created_at)"></span>
                                </div>
                                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold"
                                    :class="statusClass(s.status)" x-text="statusLabel(s.status)"></span>
                            </div>
                            <p class="mt-3 text-sm leading-relaxed text-[#2B1E19]" x-text="parseTopic(s.content).body"></p>
                            <template x-if="s.admin_note">
                                <div
                                    class="mt-3 flex items-start gap-2 rounded-xl border border-[#EADBCE] bg-white p-3 text-xs text-[#736357]">
                                    <svg class="mt-0.5 h-3.5 w-3.5 shrink-0 text-[#B38352]" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span><strong class="font-semibold text-[#2B1E19]">{{ __('Phản hồi:') }}</strong> <span
                                            x-text="s.admin_note"></span></span>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>

                {{-- Trạng thái rỗng --}}
                <div x-show="loadState === 'empty'" x-cloak
                    class="rounded-2xl border border-[#EADBCE] bg-[#FAF5F1] py-14 text-center">
                    <div
                        class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-2xl bg-[#2B1E19]/5 text-[#B38352]">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-3 3v-3z" />
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-[#2B1E19]">{{ __('Chưa có góp ý nào') }}</p>
                    <p class="mt-1 text-xs text-[#736357]">{{ __('Hãy là người đầu tiên gửi góp ý!') }}</p>
                </div>

                {{-- Trạng thái lỗi --}}
                <div x-show="loadState === 'error'" x-cloak
                    class="rounded-2xl border border-red-100 bg-red-50 py-10 text-center">
                    <p class="text-sm text-red-500">{{ __('Không thể tải dữ liệu. Vui lòng thử lại.') }}</p>
                </div>

            </div>
        </section>
    @endauth

    {{-- ============================================================
    TOAST NOTIFICATION – Alpine.js component
    ============================================================ --}}
    <div x-data="{
            show: false,
            type: 'info',
            title: '',
            message: '',
            timer: null,
            display(e) {
                this.type = e.detail.type ?? 'info';
                this.title = e.detail.title ?? '';
                this.message = e.detail.message ?? '';
                this.show = true;
                clearTimeout(this.timer);
                this.timer = setTimeout(() => { this.show = false; }, 4000);
            },
            get borderClass() {
                return { success:'border-green-200', error:'border-red-200', info:'border-amber-200' }[this.type] ?? 'border-amber-200';
            },
            get iconBgClass() {
                return { success:'bg-green-500', error:'bg-red-500', info:'bg-[#B38352]' }[this.type] ?? 'bg-[#B38352]';
            }
        }" x-on:show-toast.window="display($event)" x-show="show" x-cloak
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-4"
        :class="borderClass"
        class="fixed bottom-6 right-6 z-50 flex max-w-sm items-start gap-3 rounded-2xl border bg-white px-5 py-4 shadow-2xl"
        role="alert" aria-live="polite">
        <div :class="iconBgClass" class="mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-lg text-white">
            <svg x-show="type === 'success'" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            <svg x-show="type === 'error'" x-cloak class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
            <svg x-show="type === 'info'" x-cloak class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01" />
            </svg>
        </div>
        <div>
            <p class="text-sm font-semibold text-[#2B1E19]" x-text="title"></p>
            <p class="mt-0.5 text-xs text-[#736357]" x-text="message"></p>
        </div>
    </div>

@endsection