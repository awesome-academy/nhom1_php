<template x-teleport="body">
    <div x-show="selected" x-cloak x-transition.opacity
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 px-4 backdrop-blur-sm">
        <div x-on:click.outside="closeModal()"
             class="relative w-full max-w-lg overflow-hidden rounded-3xl border border-[#EADBCE] bg-white p-6 shadow-2xl sm:p-7">
            <template x-if="selected">
                <div class="space-y-5">
                    {{-- Modal Header --}}
                    <div class="flex items-start justify-between border-b border-[#EADBCE]/70 pb-4">
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="font-sans text-xl font-bold text-[#2B1E19]">{{ __('Chi tiết góp ý') }}</h3>
                                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-[11px] font-bold"
                                      :class="statusBadge(selected.status)"
                                      x-text="statusLabel(selected.status)"></span>
                            </div>
                            <p class="mt-1 text-xs text-gray-400"
                               x-text="'{{ __('Ngày gửi') }}: ' + fmtDate(selected.created_at)"></p>
                        </div>
                        <button x-on:click="closeModal()" class="rounded-xl p-1 text-gray-400 transition hover:bg-[#FAF5F1] hover:text-gray-700">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    {{-- Thông tin người gửi --}}
                    <div class="rounded-2xl border border-[#EADBCE]/80 bg-[#FAF5F1]/60 p-4 text-xs space-y-2">
                        <div class="flex justify-between">
                            <span class="font-bold text-gray-500">{{ __('Người gửi') }}:</span>
                            <span class="font-bold text-[#2B1E19]" x-text="selected.user?.name ?? '—'"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-bold text-gray-500">{{ __('Email') }}:</span>
                            <span class="text-gray-600" x-text="selected.user?.email ?? '—'"></span>
                        </div>
                        <template x-if="selected.reviewer">
                            <div class="flex justify-between border-t border-[#EADBCE]/40 pt-2">
                                <span class="font-bold text-gray-500">{{ __('Đã xét duyệt bởi') }}:</span>
                                <span class="font-semibold text-[#B38352]" x-text="selected.reviewer?.name ?? '—'"></span>
                            </div>
                        </template>
                    </div>

                    {{-- Nội dung góp ý --}}
                    <div>
                        <p class="mb-2 text-[11px] font-bold uppercase tracking-wider text-[#736357]">{{ __('Nội dung góp ý') }}</p>
                        <template x-if="parseTopic(selected.content).topic">
                            <span class="mb-2 inline-block rounded-lg border border-[#EADBCE] bg-[#FAF5F1] px-2.5 py-0.5 text-xs font-semibold text-[#B38352]"
                                  x-text="parseTopic(selected.content).topic"></span>
                        </template>
                        <p class="mt-1.5 text-sm leading-relaxed text-[#2B1E19]"
                           x-text="parseTopic(selected.content).body"></p>
                    </div>

                    {{-- Ghi chú Admin --}}
                    <div class="rounded-2xl border border-[#EADBCE]/80 bg-[#FAF5F1]/40 p-4">
                        <label class="mb-2 block text-[11px] font-bold uppercase tracking-wider text-[#736357]">
                            {{ __('Ghi chú / Phản hồi (tuỳ chọn)') }}
                        </label>
                        <textarea x-model="adminNote" rows="3"
                                  placeholder="{{ __('Nhập lý do hoặc phản hồi cho người dùng...') }}"
                                  class="w-full resize-none rounded-xl border border-[#EADBCE] bg-white px-3.5 py-2.5 text-xs text-[#2B1E19] placeholder-gray-400 outline-none transition focus:border-[#B38352] focus:ring-2 focus:ring-[#B38352]/20"></textarea>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex items-center justify-end gap-3 border-t border-[#EADBCE]/60 pt-4">
                        <button type="button" x-on:click="closeModal()"
                                class="rounded-xl border border-[#EADBCE] bg-white px-4 py-2 text-xs font-semibold text-gray-600 transition hover:bg-[#FAF5F1]">
                            {{ __('Đóng') }}
                        </button>
                        <button type="button" x-on:click="review('rejected')"
                                :disabled="updating || selected?.status === 'rejected'"
                                class="inline-flex items-center gap-1.5 rounded-xl bg-rose-600 px-4 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-rose-700 disabled:cursor-not-allowed disabled:opacity-50">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            {{ __('Từ chối') }}
                        </button>
                        <button type="button" x-on:click="review('reviewed')"
                                :disabled="updating || selected?.status === 'reviewed'"
                                class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-600 px-4 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:opacity-50">
                            <svg x-show="!updating" class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            <svg x-show="updating" x-cloak class="h-3.5 w-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            {{ __('Duyệt') }}
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>