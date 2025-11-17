@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Quản lý tin đăng</h1>
            <p class="text-gray-600">Danh sách các tin tuyển dụng của bạn</p>
        </div>
        <a href="{{ route('employer.jobs.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i>Đăng tin mới
        </a>
    </div>
    
    <div class="mb-6">
        <a href="{{ route('dashboard') }}" class="text-sm text-indigo-600 hover:text-indigo-900">
            &larr; Quay lại Dashboard
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        @forelse($jobs as $job)
            <div class="border-b border-gray-200 last:border-b-0">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900">{{ $job->title }}</h3>
                            <div class="mt-2 flex items-center text-sm text-gray-600 space-x-4">
                                <span><i class="fas fa-tag mr-1"></i>{{ $job->category->name }}</span>
                                <span><i class="fas fa-map-marker-alt mr-1"></i>{{ $job->location->name }}</span>
                                <span><i class="fas fa-users mr-1"></i>{{ $job->applications_count }} ứng viên</span>
                                <span><i class="fas fa-eye mr-1"></i>{{ $job->views }} lượt xem</span>
                            </div>
                            <div class="mt-2">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    @if($job->status === 'active') bg-green-100 text-green-800
                                    @elseif($job->status === 'paused') bg-yellow-100 text-yellow-800
                                    @elseif($job->status === 'draft') bg-gray-100 text-gray-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($job->status) }}
                                </span>
                                <span class="text-xs text-gray-500 ml-2">
                                    Đăng {{ $job->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('jobs.show', $job->slug) }}" target="_blank" 
                               class="text-blue-600 hover:text-blue-800 px-3 py-1 text-sm">
                                <i class="fas fa-external-link-alt mr-1"></i>Xem
                            </a>
                            <a href="{{ route('employer.jobs.edit', $job) }}" 
                               class="text-green-600 hover:text-green-800 px-3 py-1 text-sm">
                                <i class="fas fa-edit mr-1"></i>Sửa
                            </a>
                            <form action="{{ route('employer.jobs.destroy', $job) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Bạn có chắc muốn xóa tin này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 px-3 py-1 text-sm">
                                    <i class="fas fa-trash mr-1"></i>Xóa
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <i class="fas fa-briefcase text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Chưa có tin đăng nào</h3>
                <p class="text-gray-600 mb-4">Bắt đầu đăng tin tuyển dụng để tìm ứng viên phù hợp.</p>
                <a href="{{ route('employer.jobs.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                    Đăng tin đầu tiên
                </a>
            </div>
        @endforelse
    </div>
    
    @if($jobs->hasPages())
        <div class="mt-6">
            {{ $jobs->links() }}
        </div>
    @endif
</div>
@endsection