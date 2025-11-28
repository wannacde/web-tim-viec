@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6"><a href="{{ route('dashboard') }}" class="flex items-center text-gray-600 hover:text-blue-600 transition-colors"><i class="fas fa-arrow-left mr-2"></i> Quay lại</a></div>
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Quản lý tin đăng</h1>
            <p class="text-gray-600">Danh sách các tin tuyển dụng của bạn</p>
        </div>
        @if(Auth::user()->is_verified)
            <a href="{{ route('employer.jobs.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i>Đăng tin mới
            </a>
        @else
            <div class="bg-yellow-100 text-yellow-800 px-4 py-2 rounded-lg">
                <i class="fas fa-clock mr-2"></i>Chờ xác thực
            </div>
        @endif
    </div>

    @if(!Auth::user()->is_verified)
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle mr-3 text-yellow-600"></i>
                <div>
                    <h4 class="font-semibold">Tài khoản chưa được xác thực</h4>
                    <p class="text-sm">Bạn cần chờ admin phê duyệt tài khoản trước khi có thể đăng tin tuyển dụng. Quá trình này thường mất 1-2 ngày làm việc.</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="overflow-x-auto bg-white rounded-xl shadow-sm border border-gray-100">
        <table class="min-w-full">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tiêu đề công việc</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Danh mục</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Địa điểm</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ứng viên</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Lượt xem</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Trạng thái</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($jobs as $job)
                    <tr class="hover:bg-blue-50/50 transition-colors duration-200">
                        <td class="px-6 py-4 text-sm text-gray-700">
                            <div class="font-medium text-gray-900">{{ $job->title }}</div>
                            <div class="text-xs text-gray-500">Đăng {{ $job->created_at->diffForHumans() }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ $job->category->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ $job->location->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ $job->applications_count }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ $job->views }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($job->status === 'active') bg-green-100 text-green-800
                                @elseif($job->status === 'paused') bg-yellow-100 text-yellow-800
                                @elseif($job->status === 'draft') bg-gray-100 text-gray-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($job->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            <a href="{{ route('jobs.show', $job->slug) }}" target="_blank" class="text-blue-600 hover:text-blue-900 font-medium mr-3">Xem</a>
                            <a href="{{ route('employer.jobs.edit', $job) }}" class="text-blue-600 hover:text-blue-900 font-medium mr-3">Sửa</a>
                            <form action="{{ route('employer.jobs.destroy', $job) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Bạn có chắc muốn xóa tin này?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 font-medium">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <i class="fas fa-briefcase text-4xl text-gray-400 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Chưa có tin đăng nào</h3>
                            <p class="text-gray-600 mb-4">Bắt đầu đăng tin tuyển dụng để tìm ứng viên phù hợp.</p>
                            <a href="{{ route('employer.jobs.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                                Đăng tin đầu tiên
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($jobs->hasPages())
        <div class="mt-6">
            {{ $jobs->links() }}
        </div>
    @endif
</div>
@endsection