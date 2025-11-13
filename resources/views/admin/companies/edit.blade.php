@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Sửa thông tin Công ty</h1>
    
    <form action="{{ route('admin.companies.update', $company) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Tên Công ty</label>
                <input type="text" name="name" id="name" value="{{ old('name', $company->name) }}" 
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" name="is_verified" value="1" 
                           @if(old('is_verified', $company->is_verified)) checked @endif 
                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    <span class="ml-2 text-sm text-gray-700">Đã xác thực (Verified)</span>
                </label>
            </div>
            
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.companies.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                    Hủy
                </a>
                <button type="submit" class="px-4 py-2 border border-gray-300 text-gray-700 bg-indigo-600 rounded-md hover:bg-indigo-700">
                    Lưu thay đổi
                </button>
            </div>
        </div>
    </form>
</div>
@endsection