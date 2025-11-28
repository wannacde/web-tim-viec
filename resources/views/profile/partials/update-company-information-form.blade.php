<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Thông tin Công ty
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            Cập nhật thông tin công ty/thương hiệu của bạn.
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <label for="company_name" class="block text-sm font-medium text-gray-700">Tên công ty/thương hiệu</label>
            <input id="company_name" name="company_name" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" 
                   value="{{ old('company_name', $user->company_name) }}" required />
            <x-input-error class="mt-2" :messages="$errors->get('company_name')" />
        </div>

        <div>
            <label for="company_website" class="block text-sm font-medium text-gray-700">Website</label>
            <input id="company_website" name="company_website" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" 
                   value="{{ old('company_website', $user->company_website) }}" />
            <x-input-error class="mt-2" :messages="$errors->get('company_website')" />
        </div>

        <div>
            <label for="company_address" class="block text-sm font-medium text-gray-700">Địa chỉ</label>
            <input id="company_address" name="company_address" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" 
                   value="{{ old('company_address', $user->company_address) }}" />
            <x-input-error class="mt-2" :messages="$errors->get('company_address')" />
        </div>

        <div>
            <label for="company_description" class="block text-sm font-medium text-gray-700">Mô tả công ty</label>
            <textarea id="company_description" name="company_description" rows="4" 
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('company_description', $user->company_description) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('company_description')" />
        </div>

        <div>
            <label for="company_logo" class="block text-sm font-medium text-gray-700">Logo</label>
            <input id="company_logo" name="company_logo" type="file" accept="image/jpeg,image/png,image/jpg" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
            @if ($user->company_logo)
                <img src="{{ Storage::url($user->company_logo) }}" alt="Logo" class="mt-4 h-16 w-16 object-cover rounded-md">
            @endif
            <x-input-error class="mt-2" :messages="$errors->get('company_logo')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>Lưu</x-primary-button>
        </div>
    </form>
</section>