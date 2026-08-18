<section class="space-y-4">
    <header>
        <h2 class="text-lg font-semibold text-red-600">{{ __('Xoá tài khoản') }}</h2>
        <p class="mt-1 text-sm text-gray-500">
            {{ __('Sau khi tài khoản bị xoá, toàn bộ dữ liệu liên quan sẽ bị xoá vĩnh viễn. Vui lòng tải xuống dữ liệu bạn muốn giữ lại trước khi tiếp tục.') }}
        </p>
    </header>

    <x-danger-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
        {{ __('Xoá tài khoản') }}
    </x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Bạn có chắc chắn muốn xoá tài khoản?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Vui lòng nhập mật khẩu để xác nhận việc xoá tài khoản vĩnh viễn.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Mật khẩu') }}" class="sr-only" />
                <x-text-input id="password" name="password" type="password" class="mt-1 block w-3/4" placeholder="{{ __('Mật khẩu') }}" />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">{{ __('Huỷ') }}</x-secondary-button>
                <x-danger-button class="ms-3">{{ __('Xoá tài khoản') }}</x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
