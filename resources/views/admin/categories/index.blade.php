@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6"><a href="{{ route('dashboard') }}" class="flex items-center text-gray-600 hover:text-blue-600 transition-colors"><i class="fas fa-arrow-left mr-2"></i> Quay lại</a></div>
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Quản lý danh mục</h1>
        <a href="{{ route('admin.categories.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i>Thêm danh mục
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">{{ session('error') }}</div>
    @endif

    <div class="overflow-x-auto bg-white rounded-xl shadow-sm border border-gray-100">
        <table class="min-w-full">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Danh mục</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Mô tả</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Số việc làm</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Trạng thái</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($categories as $category)
                    <tr class="hover:bg-blue-50/50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            <div class="flex items-center">
                                <i class="{{ $category->icon }} text-xl text-blue-600 mr-3"></i>
                                <div class="font-medium text-gray-900">{{ $category->name }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            {{ $category->description }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ $category->jobs_count }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            <span class="px-2 py-1 text-xs rounded-full {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $category->is_active ? 'Hoạt động' : 'Tạm dừng' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="text-blue-600 hover:text-blue-900 font-medium mr-3">Sửa</a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 font-medium">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            Chưa có danh mục nào.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{ $categories->links() }}
</div>
@endsection