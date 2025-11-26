@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6"><a href="{{ route('admin.categories.index') }}" class="flex items-center text-gray-600 hover:text-blue-600 transition-colors"><i class="fas fa-arrow-left mr-2"></i> Quay lại</a></div>
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Thêm danh mục mới</h1>

    <form action="{{ route('admin.categories.store') }}" method="POST" class="bg-white shadow rounded-lg p-6">
        @csrf
        
        <div class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tên danh mục *</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Mô tả</label>
                <textarea name="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
                @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Icon (FontAwesome class)</label>
                <input type="text" name="icon" value="{{ old('icon', 'fas fa-briefcase') }}" placeholder="fas fa-briefcase"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('icon')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            
            <div class="flex items-center">
                <input type="checkbox" name="is_active" value="1" checked class="mr-2">
                <label class="text-sm text-gray-700">Kích hoạt danh mục</label>
            </div>
        </div>
        
        <div class="mt-8 flex justify-end space-x-4">
            <a href="{{ route('admin.categories.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">Hủy</a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Tạo danh mục</button>
        </div>
    </form>
</div>
@endsection