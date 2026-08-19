@extends('layouts.admin')

@section('content')
<div class="mx-auto max-w-6xl space-y-6" x-data="categoryManager()" x-init="init()">

    <!-- Header -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <span class="block font-serif text-[11px] font-semibold tracking-[0.25em] text-[#B38352] uppercase">Brew &amp; Bite Menu Admin</span>
            <h1 class="mt-1 font-serif text-2xl font-bold text-[#2B1E19] sm:text-3xl">{{ __('Phân Loại Thực Đơn') }}</h1>
            <p class="mt-1 text-sm text-[#736357]">{{ __('Quản lý các nhóm món ăn, thức uống và phân cấp danh mục cho cửa hàng.') }}</p>
        </div>
        <button @click="openCreateModal()" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#2B1E19] px-5 py-3 text-sm font-bold text-[#FAF5F1] shadow-md transition hover:bg-[#B38352] active:scale-95">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            {{ __('Thêm nhóm món mới') }}
        </button>
    </div>

    <!-- 1. Stats Overview -->
    @include('admin.categories.partials.stats-overview')

    <!-- 2. Filter Bar -->
    @include('admin.categories.partials.filter-bar')

    <!-- Notice Notification -->
    <div x-show="notice.message" x-transition x-cloak
         class="rounded-xl border px-4 py-3 text-sm"
         :class="notice.type === 'success' ? 'border-green-200 bg-green-50 text-green-700' : 'border-red-200 bg-red-50 text-red-700'"
         x-text="notice.message"></div>

    <!-- Loading Skeleton -->
    <div x-show="loading" x-cloak class="rounded-2xl border border-[#EADBCE] bg-white/70 py-16 text-center shadow-sm">
        <div class="inline-block h-8 w-8 animate-spin rounded-full border-4 border-solid border-[#B38352] border-r-transparent"></div>
        <p class="mt-3 text-sm font-medium text-[#736357]">{{ __('Đang tải cấu trúc thực đơn...') }}</p>
    </div>

    <!-- 3. Category Tree List -->
    <div x-show="!loading" x-cloak class="space-y-3.5">
        <template x-for="parent in tree" :key="parent.id">
            @include('admin.categories.partials.category-card')
        </template>

        <!-- Empty State -->
        <div x-show="!tree.length" class="rounded-2xl border border-dashed border-[#EADBCE] bg-white/60 p-12 text-center">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-[#FAF5F1] text-[#B38352]">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
            </div>
            <p class="mt-3 font-semibold text-[#2B1E19]">{{ __('Chưa có danh mục nào') }}</p>
            <p class="mt-1 text-xs text-[#A39284]">{{ __('Thử thay đổi từ khoá tìm kiếm hoặc thêm nhóm món đầu tiên.') }}</p>
        </div>
    </div>

    <!-- 4. Modals -->
    @include('admin.categories.partials.category-modal')
    @include('admin.categories.partials.delete-modal')
</div>
@endsection

@push('scripts')
<script>
function categoryManager() {
    return {
        loading: true,
        submitting: false,
        categories: [],
        tree: [],
        filters: { keyword: '', parent: '' },
        modalOpen: false,
        deleteTarget: null,
        errors: {},
        notice: { type: 'success', message: '' },
        form: { id: null, name: '', slug: '', parent_id: '', description: '' },

        init() {
            this.fetchCategories();
        },

        get stats() {
            const rootCount = this.categories.filter(c => !c.parent_id).length;
            const subCount = this.categories.filter(c => !!c.parent_id).length;
            const totalProducts = this.categories.reduce((acc, cur) => acc + (Number(cur.products_count) || 0), 0);
            return { rootCount, subCount, totalProducts };
        },

        get parentOptions() {
            return this.categories.filter((c) => {
                if (!this.form.id) return true;
                if (c.id === this.form.id) return false;
                if (c.parent_id === this.form.id) return false;
                return true;
            });
        },

        buildTree(list) {
            const byId = {};
            list.forEach((c) => { byId[c.id] = { ...c, children: [] }; });
            const roots = [];
            list.forEach((c) => {
                if (c.parent_id && byId[c.parent_id]) {
                    byId[c.parent_id].children.push(byId[c.id]);
                } else {
                    roots.push(byId[c.id]);
                }
            });
            return roots;
        },

        async fetchCategories() {
            this.loading = true;
            try {
                const params = new URLSearchParams({ per_page: 100 });
                if (this.filters.keyword) params.set('keyword', this.filters.keyword);
                if (this.filters.parent === 'null') {
                    params.set('parent_id', 'null');
                } else if (this.filters.parent) {
                    params.set('parent_id', this.filters.parent);
                }

                const res = await fetch(`{{ route('admin.categories.index') }}?${params.toString()}`, {
                    headers: { Accept: 'application/json' },
                });
                const json = await res.json();
                this.categories = json.data ?? [];
                this.tree = this.buildTree(this.categories);
            } catch (e) {
                this.showNotice('error', 'Không thể tải danh sách danh mục.');
            } finally {
                this.loading = false;
            }
        },

        openCreateModal() {
            this.form = { id: null, name: '', slug: '', parent_id: '', description: '' };
            this.errors = {};
            this.modalOpen = true;
        },

        openEditModal(category) {
            this.form = {
                id: category.id,
                name: category.name,
                slug: category.slug,
                parent_id: category.parent_id ?? '',
                description: category.description ?? '',
            };
            this.errors = {};
            this.modalOpen = true;
        },

        closeModal() {
            this.modalOpen = false;
        },

        confirmDelete(category) {
            this.deleteTarget = category;
        },

        async submitForm() {
            this.submitting = true;
            this.errors = {};

            const payload = {
                name: this.form.name,
                parent_id: this.form.parent_id === '' ? null : Number(this.form.parent_id),
                description: this.form.description || null,
            };
            if (this.form.slug) payload.slug = this.form.slug;

            const isEdit = !!this.form.id;
            const url = isEdit
                ? `{{ url('admin/categories') }}/${this.form.id}`
                : `{{ route('admin.categories.store') }}`;

            try {
                const res = await fetch(url, {
                    method: isEdit ? 'PUT' : 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify(payload),
                });

                if (res.status === 422) {
                    const json = await res.json();
                    this.errors = json.errors ?? {};
                    return;
                }
                if (!res.ok) throw new Error('request-failed');

                this.modalOpen = false;
                this.showNotice('success', isEdit ? 'Đã cập nhật nhóm món.' : 'Đã thêm nhóm món mới.');
                await this.fetchCategories();
            } catch (e) {
                this.showNotice('error', 'Có lỗi xảy ra, vui lòng thử lại.');
            } finally {
                this.submitting = false;
            }
        },

        async deleteCategory() {
            if (!this.deleteTarget) return;
            const id = this.deleteTarget.id;

            try {
                const res = await fetch(`{{ url('admin/categories') }}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                });

                if (res.status === 422) {
                    const json = await res.json();
                    this.showNotice('error', json.message ?? 'Không thể xoá danh mục.');
                    return;
                }
                if (!res.ok) throw new Error('request-failed');

                this.showNotice('success', 'Đã xoá danh mục.');
                await this.fetchCategories();
            } catch (e) {
                this.showNotice('error', 'Có lỗi xảy ra khi xoá danh mục.');
            } finally {
                this.deleteTarget = null;
            }
        },

        showNotice(type, message) {
            this.notice = { type, message };
            setTimeout(() => { this.notice.message = ''; }, 3500);
        },
    };
}
</script>
@endpush