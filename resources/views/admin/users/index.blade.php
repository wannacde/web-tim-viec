@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6"><a href="{{ route('dashboard') }}" class="flex items-center text-gray-600 hover:text-blue-600 transition-colors"><i class="fas fa-arrow-left mr-2"></i> Quay lại</a></div>
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Quản lý người dùng</h1>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">{{ session('error') }}</div>
    @endif

    <!-- Filters -->
    <form method="GET" class="bg-white p-4 rounded-lg shadow mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm theo tên hoặc email"
                   class="px-3 py-2 border border-gray-300 rounded-md">
            <select name="role" class="px-3 py-2 border border-gray-300 rounded-md">
                <option value="">Tất cả vai trò</option>
                <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>Sinh viên</option>
                <option value="employer" {{ request('role') == 'employer' ? 'selected' : '' }}>Nhà tuyển dụng</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Lọc</button>
        </div>
    </form>

    <div class="overflow-x-auto bg-white rounded-xl shadow-sm border border-gray-100">
        <table class="min-w-full">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Người dùng</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Vai trò</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Trạng thái</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $user)
                    <tr class="hover:bg-blue-50/50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            <div>
                                <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                <div class="text-gray-500">{{ $user->email }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($user->role === 'admin') bg-red-100 text-red-800
                                @elseif($user->role === 'employer') bg-blue-100 text-blue-800
                                @else bg-green-100 text-green-800 @endif">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            <span class="px-2 py-1 text-xs rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $user->is_active ? 'Hoạt động' : 'Bị khóa' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600 hover:text-blue-900 font-medium mr-3">Sửa</a>
                            @if($user->role !== 'admin')
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 font-medium">Xóa</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                            Không có người dùng nào.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{ $users->links() }}
</div>
@endsection