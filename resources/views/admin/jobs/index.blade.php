@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
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

    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        @forelse($jobs as $job)
            <div class="border-b border-gray-200 last:border-b-0 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-medium">{{ $job->title }}</h3>
                        <p class="text-gray-600">{{ $job->company->name }} - {{ $job->location->name }}</p>
                        <div class="flex items-center space-x-2 mt-1">
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($job->status === 'active') bg-green-100 text-green-800
                                @elseif($job->status === 'paused') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($job->status) }}
                            </span>
                            <span class="text-xs text-gray-500">{{ $job->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('jobs.show', $job->slug) }}" target="_blank" class="text-blue-600 hover:text-blue-800">Xem</a>
                        <form action="{{ route('admin.jobs.destroy', $job) }}" method="POST" class="inline"
                              onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">Xóa</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <p class="text-gray-500">Không có việc làm nào.</p>
            </div>
        @endforelse
    </div>
    
    {{ $jobs->links() }}
</div>
@endsection