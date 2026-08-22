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

    {{-- Bảng dữ liệu --}}
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