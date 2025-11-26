@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6"><a href="{{ route('dashboard') }}" class="flex items-center text-gray-600 hover:text-blue-600 transition-colors"><i class="fas fa-arrow-left mr-2"></i> Quay lại</a></div>
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Quản lý việc làm</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    <!-- Filters -->
    <form method="GET" class="bg-white p-4 rounded-lg shadow mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm theo tiêu đề"
                   class="px-3 py-2 border border-gray-300 rounded-md">
            <select name="status" class="px-3 py-2 border border-gray-300 rounded-md">
                <option value="">Tất cả trạng thái</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                <option value="paused" {{ request('status') == 'paused' ? 'selected' : '' }}>Tạm dừng</option>
                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Đã đóng</option>
            </select>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">Lọc</button>
        </div>
    </form>

    <div class="overflow-x-auto bg-white rounded-xl shadow-sm border border-gray-100">
        <table class="min-w-full">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tiêu đề công việc</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Công ty</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Địa điểm</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Trạng thái</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ngày tạo</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($jobs as $job)
                    <tr class="hover:bg-blue-50/50 transition-colors duration-200">
                        <td class="px-6 py-4 text-sm text-gray-700">
                            <div class="font-medium text-gray-900">{{ $job->title }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ $job->user ? ($job->user->company_name ?? $job->user->name) : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ $job->location->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($job->status === 'active') bg-green-100 text-green-800
                                @elseif($job->status === 'paused') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($job->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ $job->created_at->diffForHumans() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            <a href="{{ route('jobs.show', $job->slug) }}" target="_blank" class="text-blue-600 hover:text-blue-900 font-medium mr-3">Xem</a>
                            <form action="{{ route('admin.jobs.destroy', $job) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 font-medium">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            Không có việc làm nào.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{ $jobs->links() }}
</div>
@endsection