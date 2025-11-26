@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6"><a href="{{ route('admin.users.index') }}" class="flex items-center text-gray-600 hover:text-blue-600 transition-colors"><i class="fas fa-arrow-left mr-2"></i> Quay lại</a></div>
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Chỉnh sửa người dùng</h1>

    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="bg-white shadow rounded-lg p-6">
        @csrf @method('PUT')
        
        <div class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tên *</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Vai trò *</label>
                <select name="role" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="student" {{ old('role', $user->role) == 'student' ? 'selected' : '' }}>Sinh viên</option>
                    <option value="employer" {{ old('role', $user->role) == 'employer' ? 'selected' : '' }}>Nhà tuyển dụng</option>
                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                @error('role')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            
            <div class="flex items-center">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                       class="mr-2">
                <label class="text-sm text-gray-700">Tài khoản hoạt động</label>
            </div>
        </div>
        
        <div class="mt-8 flex justify-end space-x-4">
            <a href="{{ route('admin.users.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">Hủy</a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Cập nhật</button>
        </div>
    </form>
</div>
@endsection