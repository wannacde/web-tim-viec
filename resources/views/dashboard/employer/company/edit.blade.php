@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Thông tin công ty</h1>
        <p class="text-gray-600">Cập nhật thông tin công ty của bạn</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('company.update') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow rounded-lg p-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tên công ty *</label>
                <input type="text" name="name" value="{{ old('name', $company->name) }}" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email công ty</label>
                <input type="email" name="email" value="{{ old('email', $company->email) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Số điện thoại</label>
                <input type="text" name="phone" value="{{ old('phone', $company->phone) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Website</label>
                <input type="url" name="website" value="{{ old('website', $company->website) }}" placeholder="https://example.com"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('website')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Địa điểm</label>
                <select name="location_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Chọn địa điểm</option>
                    @foreach($locations as $location)
                        <option value="{{ $location->id }}" {{ old('location_id', $company->location_id) == $location->id ? 'selected' : '' }}>
                            {{ $location->name }}
                        </option>
                    @endforeach
                </select>
                @error('location_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Quy mô công ty</label>
                <select name="size" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Chọn quy mô</option>
                    <option value="1-10" {{ old('size', $company->size) == '1-10' ? 'selected' : '' }}>1-10 nhân viên</option>
                    <option value="11-50" {{ old('size', $company->size) == '11-50' ? 'selected' : '' }}>11-50 nhân viên</option>
                    <option value="51-200" {{ old('size', $company->size) == '51-200' ? 'selected' : '' }}>51-200 nhân viên</option>
                    <option value="201-500" {{ old('size', $company->size) == '201-500' ? 'selected' : '' }}>201-500 nhân viên</option>
                    <option value="500+" {{ old('size', $company->size) == '500+' ? 'selected' : '' }}>500+ nhân viên</option>
                </select>
                @error('size')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Địa chỉ</label>
                <textarea name="address" rows="2" placeholder="Địa chỉ chi tiết của công ty"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('address', $company->address) }}</textarea>
                @error('address')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Mô tả công ty</label>
                <textarea name="description" rows="6" placeholder="Giới thiệu về công ty, văn hóa, sứ mệnh..."
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $company->description) }}</textarea>
                @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Logo công ty</label>
                
                @if($company->logo)
                    <div class="mb-4">
                        <img src="{{ Storage::url($company->logo) }}" alt="Company Logo" class="w-24 h-24 object-cover rounded-lg border">
                        <p class="text-sm text-gray-500 mt-1">Logo hiện tại</p>
                    </div>
                @endif
                
                <input type="file" name="logo" accept="image/*"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <p class="text-xs text-gray-500 mt-1">Chấp nhận: JPG, PNG (tối đa 2MB)</p>
                @error('logo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
        
        <div class="mt-8 flex justify-end space-x-4">
            <a href="{{ route('dashboard') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                Hủy
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Cập nhật
            </button>
        </div>
    </form>
</div>
@endsection