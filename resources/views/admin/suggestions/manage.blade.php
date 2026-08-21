@extends('layouts.admin')

@section('content')
<div class="space-y-6 font-sans antialiased"
     x-data="{
        suggestions: [],
        meta: { current_page: 1, last_page: 1, total: 0 },
        metrics: { pending: 0, reviewed: 0, rejected: 0 },
        loading: false,
        updating: false,
        notice: { message: '', type: 'success' },
        filters: { status: '' },
        page: 1,
        selected: null,
        adminNote: '',

        get apiBase() { return '{{ url('/api/admin/suggestions') }}'; },
        get csrfToken() { return document.querySelector('meta[name=csrf-token]').content; },

        init() { this.load(); },

        async load(p) {
            if (p !== undefined) this.page = p;
            this.loading = true;
            try {
                const params = new URLSearchParams({ page: this.page });
                if (this.filters.status) params.set('status', this.filters.status);
                const res = await fetch(this.apiBase + '?' + params.toString(), {
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': this.csrfToken },
                    credentials: 'same-origin',
                });
                if (!res.ok) throw new Error();
                const json = await res.json();
                this.suggestions = json.data ?? json;
                if (json.meta) this.meta = json.meta;
                this.calcMetrics();
            } catch {
                this.showNotice('error', '{{ __('Không thể tải danh sách góp ý.') }}');
            } finally {
                this.loading = false;
            }
        },

        calcMetrics() {
            this.metrics.pending  = this.suggestions.filter(s => s.status === 'pending').length;
            this.metrics.reviewed = this.suggestions.filter(s => s.status === 'reviewed').length;
            this.metrics.rejected = this.suggestions.filter(s => s.status === 'rejected').length;
        },

        setFilter(status) {
            this.filters.status = status;
            this.load(1);
        },

        openModal(s) {
            this.selected = s;
            this.adminNote = s.admin_note ?? '';
        },

        closeModal() {
            this.selected = null;
            this.adminNote = '';
        },

        async review(status) {
            if (!this.selected) return;
            this.updating = true;
            try {
                const res = await fetch(this.apiBase + '/' + this.selected.id, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken,
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ status, admin_note: this.adminNote }),
                });
                const json = await res.json();
                if (!res.ok) throw new Error(json.message ?? '');
                this.showNotice('success', '{{ __('Đã cập nhật góp ý thành công.') }}');
                this.closeModal();
                this.load(this.page);
            } catch (e) {
                this.showNotice('error', e.message || '{{ __('Cập nhật thất bại. Vui lòng thử lại.') }}');
            } finally {
                this.updating = false;
            }
        },

        statusLabel(s) {
            return { pending: 'Chờ xử lý', reviewed: 'Đã duyệt', rejected: 'Từ chối' }[s] ?? s;
        },

        statusBadge(s) {
            return {
                pending:  'border-amber-200 bg-amber-50 text-amber-700',
                reviewed: 'border-emerald-200 bg-emerald-50 text-emerald-700',
                rejected: 'border-rose-200 bg-rose-50 text-rose-700',
            }[s] ?? 'border-gray-200 bg-gray-50 text-gray-600';
        },

        parseTopic(content) {
            const m = content?.match(/^\[([^\]]+)\]\s*/);
            return m ? { topic: m[1], body: content.slice(m[0].length) } : { topic: null, body: content };
        },

        fmtDate(iso) {
            return new Date(iso).toLocaleString('vi-VN', { day:'2-digit', month:'2-digit', year:'numeric', hour:'2-digit', minute:'2-digit' });
        },

        showNotice(type, message) {
            this.notice = { type, message };
            setTimeout(() => { this.notice.message = ''; }, 4000);
        },
     }"
     x-init="init()">

    {{-- Page Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2.5">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#FAF5F1] text-[#B38352] ring-1 ring-[#EADBCE]">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-3 3v-3z" />
                    </svg>
                </span>
                <h1 class="text-2xl font-bold tracking-tight text-[#2B1E19]">{{ __('Quản lý góp ý') }}</h1>
            </div>
            <p class="mt-1 text-xs text-gray-500">{{ __('Xem xét, duyệt hoặc từ chối các góp ý từ khách hàng.') }}</p>
        </div>
        <button type="button" x-on:click="load(page)"
                class="inline-flex items-center gap-2 rounded-xl border border-[#EADBCE] bg-white px-3.5 py-2 text-xs font-semibold text-[#4A3B32] shadow-sm transition hover:bg-[#FAF5F1] hover:text-[#B38352] focus:outline-none">
            <svg class="h-4 w-4" :class="{ 'animate-spin': loading }" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            <span>{{ __('Làm mới') }}</span>
        </button>
    </div>

    {{-- Notice Alert --}}
    <div x-show="notice.message" x-cloak x-transition
         class="flex items-center justify-between rounded-2xl border px-4 py-3 text-xs font-semibold shadow-sm"
         :class="notice.type === 'success' ? 'border-emerald-200 bg-emerald-50/90 text-emerald-800' : 'border-rose-200 bg-rose-50/90 text-rose-800'">
        <div class="flex items-center gap-2">
            <span class="flex h-5 w-5 items-center justify-center rounded-full"
                  :class="notice.type === 'success' ? 'bg-emerald-600 text-white' : 'bg-rose-600 text-white'">
                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          :d="notice.type === 'success' ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12'"/>
                </svg>
            </span>
            <span x-text="notice.message"></span>
        </div>
        <button x-on:click="notice.message = ''" class="text-gray-400 hover:text-gray-600">&times;</button>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
        <button type="button" x-on:click="setFilter('')"
                class="flex flex-col rounded-2xl border bg-white p-3.5 text-left shadow-xs transition hover:border-[#B38352] hover:shadow-sm"
                :class="filters.status === '' ? 'border-[#B38352] ring-2 ring-[#B38352]/20' : 'border-[#EADBCE]/70'">
            <span class="text-[11px] font-bold uppercase tracking-wider text-gray-500">{{ __('Tất cả') }}</span>
            <span class="mt-1 text-xl font-extrabold text-[#2B1E19]" x-text="meta.total ?? '—'"></span>
        </button>
        <button type="button" x-on:click="setFilter('pending')"
                class="flex flex-col rounded-2xl border bg-white p-3.5 text-left shadow-xs transition hover:border-amber-500 hover:shadow-sm"
                :class="filters.status === 'pending' ? 'border-amber-500 ring-2 ring-amber-500/20' : 'border-[#EADBCE]/70'">
            <div class="flex items-center gap-1.5 text-[11px] font-bold uppercase tracking-wider text-amber-700">
                <span class="h-2 w-2 animate-pulse rounded-full bg-amber-500"></span>
                <span>{{ __('Chờ xử lý') }}</span>
            </div>
            <span class="mt-1 text-xl font-extrabold text-amber-900" x-text="metrics.pending"></span>
        </button>
        <button type="button" x-on:click="setFilter('reviewed')"
                class="flex flex-col rounded-2xl border bg-white p-3.5 text-left shadow-xs transition hover:border-emerald-500 hover:shadow-sm"
                :class="filters.status === 'reviewed' ? 'border-emerald-500 ring-2 ring-emerald-500/20' : 'border-[#EADBCE]/70'">
            <div class="flex items-center gap-1.5 text-[11px] font-bold uppercase tracking-wider text-emerald-700">
                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                <span>{{ __('Đã duyệt') }}</span>
            </div>
            <span class="mt-1 text-xl font-extrabold text-emerald-900" x-text="metrics.reviewed"></span>
        </button>
        <button type="button" x-on:click="setFilter('rejected')"
                class="flex flex-col rounded-2xl border bg-white p-3.5 text-left shadow-xs transition hover:border-rose-500 hover:shadow-sm"
                :class="filters.status === 'rejected' ? 'border-rose-500 ring-2 ring-rose-500/20' : 'border-[#EADBCE]/70'">
            <div class="flex items-center gap-1.5 text-[11px] font-bold uppercase tracking-wider text-rose-700">
                <span class="h-2 w-2 rounded-full bg-rose-500"></span>
                <span>{{ __('Từ chối') }}</span>
            </div>
            <span class="mt-1 text-xl font-extrabold text-rose-900" x-text="metrics.rejected"></span>
        </button>
    </div>

    {{-- Table Card --}}
    <div class="space-y-4">
        {{-- Toolbar --}}
        <div class="flex flex-col gap-3 rounded-2xl border border-[#EADBCE] bg-white p-4 shadow-xs sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm font-semibold text-[#2B1E19]">
                {{ __('Danh sách góp ý') }}
                <span class="ml-1.5 rounded-full bg-[#FAF5F1] px-2 py-0.5 text-xs font-bold text-[#B38352]"
                      x-text="meta.total ?? suggestions.length"></span>
            </p>
            <div class="flex items-center gap-2">
                <label class="text-xs font-semibold text-gray-500">{{ __('Lọc:') }}</label>
                <select x-model="filters.status" x-on:change="load(1)"
                        class="rounded-xl border border-[#EADBCE] bg-white py-1.5 pl-3 pr-8 text-xs font-semibold text-[#2B1E19] focus:border-[#B38352] focus:outline-none focus:ring-2 focus:ring-[#B38352]/20">
                    <option value="">{{ __('Tất cả') }}</option>
                    <option value="pending">{{ __('Chờ xử lý') }}</option>
                    <option value="reviewed">{{ __('Đã duyệt') }}</option>
                    <option value="rejected">{{ __('Từ chối') }}</option>
                </select>
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-[#EADBCE] bg-white shadow-xs">
            {{-- Loading --}}
            <div x-show="loading" class="py-20 text-center">
                <div class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-[#B38352] border-r-transparent"></div>
                <p class="mt-3 text-xs font-semibold text-[#736357]">{{ __('Đang tải dữ liệu...') }}</p>
            </div>

            <div x-show="!loading" class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 text-left text-xs">
                    <thead class="bg-[#FAF5F1]/80 text-[11px] font-bold uppercase tracking-wider text-[#736357]">
                        <tr>
                            <th class="w-12 px-5 py-3.5 text-center">#</th>
                            <th class="px-5 py-3.5">{{ __('Người gửi') }}</th>
                            <th class="px-5 py-3.5">{{ __('Chủ đề') }}</th>
                            <th class="max-w-xs px-5 py-3.5">{{ __('Nội dung') }}</th>
                            <th class="px-5 py-3.5">{{ __('Trạng thái') }}</th>
                            <th class="px-5 py-3.5">{{ __('Thời gian') }}</th>
                            <th class="px-5 py-3.5 text-right">{{ __('Thao tác') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <template x-if="suggestions.length === 0 && !loading">
                            <tr>
                                <td colspan="7" class="py-14 text-center text-sm text-gray-400">
                                    {{ __('Không có góp ý nào.') }}
                                </td>
                            </tr>
                        </template>
                        <template x-for="(s, index) in suggestions" :key="s.id">
                            <tr class="transition hover:bg-[#FAF5F1]/50">
                                <td class="px-5 py-3.5 text-center font-medium text-gray-400"
                                    x-text="((page - 1) * 15) + index + 1"></td>
                                <td class="px-5 py-3.5">
                                    <p class="font-semibold text-[#2B1E19]" x-text="s.user?.name ?? '—'"></p>
                                    <p class="text-[11px] text-gray-400" x-text="s.user?.email ?? ''"></p>
                                </td>
                                <td class="px-5 py-3.5">
                                    <template x-if="parseTopic(s.content).topic">
                                        <span class="inline-block rounded-lg border border-[#EADBCE] bg-[#FAF5F1] px-2 py-0.5 text-[11px] font-semibold text-[#B38352]"
                                              x-text="parseTopic(s.content).topic"></span>
                                    </template>
                                    <template x-if="!parseTopic(s.content).topic">
                                        <span class="text-gray-400">—</span>
                                    </template>
                                </td>
                                <td class="max-w-xs px-5 py-3.5">
                                    <p class="line-clamp-2 text-[#4A3B32]" x-text="parseTopic(s.content).body"></p>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-[11px] font-bold"
                                          :class="statusBadge(s.status)"
                                          x-text="statusLabel(s.status)"></span>
                                </td>
                                <td class="px-5 py-3.5 text-gray-400" x-text="fmtDate(s.created_at)"></td>
                                <td class="px-5 py-3.5 text-right">
                                    <button type="button" x-on:click="openModal(s)"
                                            class="inline-flex items-center gap-1.5 rounded-xl border border-[#EADBCE] bg-white px-3 py-1.5 text-xs font-semibold text-[#4A3B32] shadow-xs transition hover:border-[#B38352] hover:text-[#B38352]">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        {{ __('Xem xét') }}
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        <div x-show="meta.last_page > 1" class="flex items-center justify-between px-1">
            <p class="text-xs text-gray-500">
                {{ __('Trang') }} <span x-text="meta.current_page"></span> / <span x-text="meta.last_page"></span>
            </p>
            <div class="flex items-center gap-1.5">
                <button type="button" x-on:click="load(meta.current_page - 1)"
                        :disabled="meta.current_page <= 1"
                        class="rounded-lg border border-[#EADBCE] bg-white px-3 py-1.5 text-xs font-semibold text-[#4A3B32] transition hover:bg-[#FAF5F1] disabled:cursor-not-allowed disabled:opacity-40">
                    ‹ {{ __('Trước') }}
                </button>
                <button type="button" x-on:click="load(meta.current_page + 1)"
                        :disabled="meta.current_page >= meta.last_page"
                        class="rounded-lg border border-[#EADBCE] bg-white px-3 py-1.5 text-xs font-semibold text-[#4A3B32] transition hover:bg-[#FAF5F1] disabled:cursor-not-allowed disabled:opacity-40">
                    {{ __('Sau') }} ›
                </button>
            </div>
        </div>
    </div>

    {{-- Review Modal --}}
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
                                    <h3 class="font-serif text-xl font-bold text-[#2B1E19]">{{ __('Chi tiết góp ý') }}</h3>
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

</div>
@endsection

