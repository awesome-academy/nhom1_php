<div class="lg:col-span-7">
    <div class="rounded-3xl border border-[#EADBCE] bg-white p-6 shadow-xl shadow-[#2B1E19]/5 sm:p-8">
        <div class="mb-6">
            <div class="mb-2 inline-flex items-center gap-2">
                <span class="text-[11px] font-bold uppercase tracking-[0.2em] text-[#B38352]">{{ __('Đóng góp ý kiến') }}</span>
                <span class="h-px w-8 bg-[#B38352]/60"></span>
            </div>
            <h2 class="text-2xl font-bold tracking-tight text-[#2B1E19]">{{ __('Gửi Phản Hồi Cho Chúng Tôi') }}</h2>
            <p class="mt-1 text-xs leading-relaxed text-[#736357]">
                {{ __('Ý kiến của bạn là động lực để Brew & Bite nâng cao chất lượng món và dịch vụ mỗi ngày.') }}
            </p>
        </div>

        @auth
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
                    this.topicError = !this.topic;
                    this.contentError = !this.content.trim();
                    if (this.topicError || this.contentError) return;

                    this.loading = true;
                    try {
                        const res = await fetch('{{ route('suggestions.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.getAttribute('content') || '',
                            },
                            credentials: 'same-origin',
                            body: JSON.stringify({ content: this.topic + ' ' + this.content.trim() }),
                        });

                        const json = await res.json().catch(() => ({}));

                        if (res.ok) {
                            this.topic = '';
                            this.content = '';
                            this.charCount = 0;
                            window.dispatchEvent(new CustomEvent('show-toast', { 
                                detail: { type: 'success', title: '{{ __('Gửi thành công!') }}', message: '{{ __('Cảm ơn bạn đã đóng góp. Chúng tôi sẽ ghi nhận và phản hồi sớm.') }}' } 
                            }));
                        } else if (res.status === 401) {
                            window.dispatchEvent(new CustomEvent('show-toast', { 
                                detail: { type: 'error', title: '{{ __('Hết phiên làm việc') }}', message: '{{ __('Vui lòng đăng nhập lại để tiếp tục.') }}' } 
                            }));
                        } else if (res.status === 422) {
                            const msg = Object.values(json.errors ?? {}).flat()[0] ?? json.message ?? '{{ __('Dữ liệu không hợp lệ.') }}';
                            window.dispatchEvent(new CustomEvent('show-toast', { 
                                detail: { type: 'error', title: '{{ __('Lỗi nhập liệu') }}', message: msg } 
                            }));
                        } else {
                            window.dispatchEvent(new CustomEvent('show-toast', { 
                                detail: { type: 'error', title: '{{ __('Gửi thất bại') }}', message: json.message || '{{ __('Đã có lỗi xảy ra. Vui lòng thử lại.') }}' } 
                            }));
                        }
                    } catch {
                        window.dispatchEvent(new CustomEvent('show-toast', { 
                            detail: { type: 'error', title: '{{ __('Lỗi kết nối') }}', message: '{{ __('Không thể kết nối đến máy chủ.') }}' } 
                        }));
                    } finally {
                        this.loading = false;
                    }
                }
            }">
                <form x-on:submit.prevent="submit()" novalidate class="space-y-4">
                    {{-- Dropdown Chủ đề --}}
                    <div>
                        <label for="suggestion-topic" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-[#4A3B32]">
                            {{ __('Chủ đề góp ý') }} <span class="text-[#B38352]">*</span>
                        </label>
                        <div class="relative">
                            <select id="suggestion-topic" x-model="topic" x-on:change="topicError = !topic"
                                :class="topicError ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500/20' : 'border-[#EADBCE] focus:border-[#B38352] focus:ring-[#B38352]/20'"
                                class="w-full appearance-none rounded-xl border bg-[#FAF5F1]/60 py-3 pl-4 pr-10 text-xs font-semibold text-[#2B1E19] shadow-2xs outline-none transition focus:bg-white focus:ring-2">
                                <option value="" disabled selected>{{ __('— Chọn danh mục chủ đề —') }}</option>
                                <option value="[Chất lượng sản phẩm]">{{ __('Chất lượng thức uống & bánh ngọt') }}</option>
                                <option value="[Dịch vụ & Phục vụ]">{{ __('Thái độ phục vụ & Nhân viên') }}</option>
                                <option value="[Menu & Thực đơn]">{{ __('Đóng góp món mới / Thực đơn') }}</option>
                                <option value="[Giá cả]">{{ __('Giá thành & Chương trình khuyến mãi') }}</option>
                                <option value="[Không gian quán]">{{ __('Không gian & Cơ sở vật chất') }}</option>
                                <option value="[Giao hàng & Đặt hàng]">{{ __('Dịch vụ giao hàng & Đóng gói') }}</option>
                                <option value="[Khác]">{{ __('Ý kiến khác') }}</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-3.5 flex items-center text-[#B38352]">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                        <p x-show="topicError" x-cloak class="mt-1 text-xs font-medium text-rose-500">
                            {{ __('Vui lòng chọn một chủ đề trước khi gửi.') }}
                        </p>
                    </div>

                    {{-- Nội dung chi tiết --}}
                    <div>
                        <label for="suggestion-content" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-[#4A3B32]">
                            {{ __('Nội dung chi tiết') }} <span class="text-[#B38352]">*</span>
                        </label>
                        <textarea id="suggestion-content" rows="5"
                            :class="contentError ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-500/20' : 'border-[#EADBCE] focus:border-[#B38352] focus:ring-[#B38352]/20'"
                            x-on:input="onInput($event)"
                            :value="content"
                            placeholder="{{ __('Hãy chia sẻ chi tiết trải nghiệm hoặc ý kiến đóng góp của bạn tại đây...') }}"
                            class="w-full resize-none rounded-xl border bg-[#FAF5F1]/60 px-4 py-3 text-xs leading-relaxed text-[#2B1E19] placeholder-[#A39284] shadow-2xs outline-none transition focus:bg-white focus:ring-2"></textarea>
                        <div class="mt-1 flex items-center justify-between">
                            <p x-show="contentError" x-cloak class="text-xs font-medium text-rose-500">
                                {{ __('Vui lòng nhập nội dung góp ý.') }}
                            </p>
                            <span x-text="charLabel"
                                :class="charCount >= maxChar ? 'text-rose-500 font-bold' : 'text-[#A39284]'"
                                class="ml-auto text-[11px] font-medium">0 / 1000</span>
                        </div>
                    </div>

                    {{-- Nút Submit --}}
                    <button type="submit" :disabled="loading"
                        class="group flex w-full items-center justify-center gap-2 rounded-xl bg-[#2B1E19] px-6 py-3.5 text-xs font-bold uppercase tracking-wider text-[#FAF5F1] shadow-md transition-all duration-200 hover:bg-[#B38352] hover:shadow-lg active:scale-[0.99] disabled:cursor-not-allowed disabled:opacity-60">
                        <svg x-show="!loading" class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        <svg x-show="loading" x-cloak class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        <span x-text="loading ? '{{ __('Đang gửi...') }}' : '{{ __('Gửi phản hồi ngay') }}'">{{ __('Gửi phản hồi ngay') }}</span>
                    </button>
                </form>
            </div>
        @else
            {{-- Chưa đăng nhập --}}
            <div class="flex flex-col items-center rounded-2xl border border-dashed border-[#EADBCE] bg-[#FAF5F1]/80 py-12 px-4 text-center">
                <div class="mb-3.5 flex h-14 w-14 items-center justify-center rounded-2xl bg-white text-[#B38352] ring-1 ring-[#EADBCE] shadow-xs">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5z" />
                    </svg>
                </div>
                <p class="text-sm font-bold text-[#2B1E19]">{{ __('Đăng nhập để gửi góp ý') }}</p>
                <p class="mt-1 max-w-sm text-xs text-[#736357]">
                    {{ __('Bạn vui lòng đăng nhập tài khoản để gửi ý kiến phản hồi cho chúng tôi.') }}
                </p>
                <a href="{{ route('login') }}"
                    class="mt-5 inline-flex items-center gap-2 rounded-xl bg-[#2B1E19] px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-[#FAF5F1] shadow-xs transition hover:bg-[#B38352] active:scale-95">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    <span>{{ __('Đăng nhập ngay') }}</span>
                </a>
            </div>
        @endauth
    </div>
</div>