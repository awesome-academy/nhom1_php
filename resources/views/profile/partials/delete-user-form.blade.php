<section class="space-y-4">
    <header>
        <span class="block font-serif text-[11px] font-semibold tracking-[0.25em] text-red-600 uppercase">
            {{ __('Vùng nguy hiểm') }}
        </span>
        <h2 class="mt-1 font-serif text-2xl font-semibold text-red-600 sm:text-3xl">
            {{ __('Xoá tài khoản') }}
        </h2>
        <p class="mt-1.5 text-xs text-[#736357]">
            {{ __('Sau khi tài khoản bị xoá, toàn bộ dữ liệu lịch sử của bạn sẽ bị xoá vĩnh viễn khỏi hệ thống.') }}
        </p>
    </header>

    <button
        type="button"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="inline-flex items-center gap-2 rounded-2xl bg-red-600 px-6 py-3.5 text-xs font-bold uppercase tracking-wider text-white shadow-md transition duration-200 hover:bg-red-700 active:scale-95"
    >
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
        </svg>
        <span>{{ __('Yêu cầu xoá tài khoản') }}</span>
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-7 sm:p-9 bg-white rounded-3xl">
            @csrf
            @method('delete')

            <h2 class="font-serif text-2xl font-bold text-[#2B1E19]">
                {{ __('Xác nhận xoá tài khoản vĩnh viễn?') }}
            </h2>

            <p class="mt-2 text-xs leading-relaxed text-[#736357]">
                {{ __('Vui lòng nhập mật khẩu hiện tại của bạn để xác nhận hành động này. Thao tác này không thể hoàn tác.') }}
            </p>

            <div class="mt-5">
                <label for="delete_password" class="sr-only">{{ __('Mật khẩu') }}</label>
                <input
                    id="delete_password"
                    name="password"
                    type="password"
                    placeholder="{{ __('Nhập mật khẩu để xác nhận') }}"
                    class="block w-full rounded-2xl border border-[#EADBCE] bg-[#FAF5F1]/50 py-3 pl-4 pr-4 text-sm text-[#2B1E19] placeholder-[#A39284] focus:border-red-500 focus:outline-none focus:ring-4 focus:ring-red-500/15"
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-xs" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button
                    type="button"
                    x-on:click="$dispatch('close')"
                    class="rounded-2xl border border-[#EADBCE] bg-white px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-[#4A3B32] transition hover:bg-[#FAF5F1]"
                >
                    {{ __('Huỷ bỏ') }}
                </button>

                <button
                    type="submit"
                    class="rounded-2xl bg-red-600 px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-white shadow-md transition hover:bg-red-700 active:scale-95"
                >
                    {{ __('Xác nhận xoá') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>