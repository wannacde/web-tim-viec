<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Hồ sơ Sinh viên
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Cập nhật các thông tin chuyên môn của bạn. Nhà tuyển dụng sẽ thấy thông tin này.
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="headline" value="Tiêu đề hồ sơ (Headline)" />
            <x-text-input id="headline" name="headline" type="text" class="mt-1 block w-full" :value="old('headline', $user->headline)" placeholder="Vd: Sinh viên IT năm 3" />
            <x-input-error class="mt-2" :messages="$errors->get('headline')" />
        </div>

        <div>
            <x-input-label for="bio" value="Giới thiệu bản thân (Bio)" />
            <textarea id="bio" name="bio" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                      placeholder="Viết một đoạn ngắn về kỹ năng và mục tiêu của bạn...">{{ old('bio', $user->bio) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>Lưu</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >Đã lưu.</p>
            @endif
        </div>
    </form>
</section>