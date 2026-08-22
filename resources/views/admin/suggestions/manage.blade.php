@extends('layouts.admin')

@section('content')
<div class="space-y-6 font-sans antialiased" x-data="adminSuggestionManager()" x-init="init()">

    {{-- 1. Header --}}
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

    {{-- 2. Notice Alert --}}
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

    {{-- 3. KPI Metrics --}}
    @include('admin.suggestions.partials.status-metrics')

    {{-- 4. Bảng danh sách & Bộ lọc --}}
    @include('admin.suggestions.partials.table-list')

    {{-- 5. Modal Xem xét/Duyệt --}}
    @include('admin.suggestions.partials.review-modal')

</div>
@endsection

@push('scripts')
<script>
function adminSuggestionManager() {
    return {
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

        get apiBase() { 
            return '{{ url('/admin/suggestions/data') }}'; 
        },
        get csrfToken() { 
            return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
                || document.querySelector('input[name="_token"]')?.value 
                || ''; 
        },

        init() { 
            this.load(); 
        },

        async load(p) {
            if (p !== undefined) this.page = p;
            this.loading = true;
            try {
                const params = new URLSearchParams({ page: this.page });
                if (this.filters.status) params.set('status', this.filters.status);
                
                const res = await fetch(this.apiBase + '?' + params.toString(), {
                    headers: { 
                        'Accept': 'application/json', 
                        'X-CSRF-TOKEN': this.csrfToken 
                    },
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
                const res = await fetch(`{{ url('/admin/suggestions') }}/${this.selected.id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken,
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ 
                        status: status, 
                        admin_note: this.adminNote 
                    }),
                });

                const json = await res.json().catch(() => ({}));

                if (!res.ok) {
                    throw new Error(json.message || Object.values(json.errors ?? {}).flat()[0] || '{{ __('Cập nhật thất bại.') }}');
                }

                this.showNotice('success', '{{ __('Đã cập nhật góp ý thành công.') }}');
                this.closeModal();
                await this.load(this.page);
            } catch (e) {
                this.showNotice('error', e.message || '{{ __('Cập nhật thất bại. Vui lòng thử lại.') }}');
            } finally {
                this.updating = false;
            }
        },

        statusLabel(s) {
            return { pending: '{{ __('Chờ xử lý') }}', reviewed: '{{ __('Đã duyệt') }}', rejected: '{{ __('Từ chối') }}' }[s] ?? s;
        },

        statusBadge(s) {
            return {
                pending:  'border-amber-200 bg-amber-50 text-amber-700',
                reviewed: 'border-emerald-200 bg-emerald-50 text-emerald-700',
                rejected: 'border-rose-200 bg-rose-50 text-rose-700',
            }[s] ?? 'border-gray-200 bg-gray-50 text-gray-600';
        },

        parseTopic(content) {
            if (!content) return { topic: null, body: '' };
            const m = content.match(/^\[([^\]]+)\]\s*/);
            return m ? { topic: m[1], body: content.slice(m[0].length) } : { topic: null, body: content };
        },

        fmtDate(iso) {
            if (!iso) return '—';
            return new Date(iso).toLocaleString('vi-VN', { 
                day: '2-digit', 
                month: '2-digit', 
                year: 'numeric', 
                hour: '2-digit', 
                minute: '2-digit' 
            });
        },

        showNotice(type, message) {
            this.notice = { type, message };
            setTimeout(() => { this.notice.message = ''; }, 4000);
        },
    };
}
</script>
@endpush
