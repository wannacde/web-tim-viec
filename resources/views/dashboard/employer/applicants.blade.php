@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Quản lý ứng viên</h1>
        <p class="text-gray-600">Danh sách ứng viên cho các tin tuyển dụng của bạn</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        @forelse($applications as $application)
            <div class="border-b border-gray-200 last:border-b-0">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold">
                                        {{ substr($application->user->name, 0, 1) }}
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-medium text-gray-900">{{ $application->user->name }}</h3>
                                    <p class="text-sm text-gray-600">{{ $application->user->email }}</p>
                                    <p class="text-sm text-blue-600 font-medium">{{ $application->job->title }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            <span class="px-3 py-1 text-xs rounded-full 
                                @if($application->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($application->status === 'accepted') bg-green-100 text-green-800
                                @elseif($application->status === 'rejected') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($application->status) }}
                            </span>
                            
                            <div class="text-sm text-gray-500">
                                {{ $application->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h4 class="font-medium text-gray-900 mb-2">Thư xin việc:</h4>
                                <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded">{{ $application->cover_letter }}</p>
                            </div>
                            
                            <div class="space-y-3">
                                <div>
                                    <a href="{{ Storage::url($application->cv_file) }}" target="_blank" 
                                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                                        <i class="fas fa-download mr-2"></i>Tải CV
                                    </a>
                                </div>
                                
                                <form action="{{ route('applications.updateStatus', $application) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('PATCH')
                                    <div class="flex space-x-2">
                                        <select name="status" class="text-sm border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500">
                                            <option value="pending" {{ $application->status === 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                            <option value="reviewing" {{ $application->status === 'reviewing' ? 'selected' : '' }}>Đang xem xét</option>
                                            <option value="shortlisted" {{ $application->status === 'shortlisted' ? 'selected' : '' }}>Lọt vòng trong</option>
                                            <option value="interviewed" {{ $application->status === 'interviewed' ? 'selected' : '' }}>Đã phỏng vấn</option>
                                            <option value="accepted" {{ $application->status === 'accepted' ? 'selected' : '' }}>Chấp nhận</option>
                                            <option value="rejected" {{ $application->status === 'rejected' ? 'selected' : '' }}>Từ chối</option>
                                        </select>
                                        <button type="submit" class="px-3 py-1 bg-green-600 text-white text-sm rounded hover:bg-green-700">
                                            Cập nhật
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <i class="fas fa-users text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Chưa có ứng viên nào</h3>
                <p class="text-gray-600">Các ứng viên sẽ xuất hiện ở đây khi họ ứng tuyển vào công việc của bạn.</p>
            </div>
        @endforelse
    </div>
    
    @if($applications->hasPages())
        <div class="mt-6">
            {{ $applications->links() }}
        </div>
    @endif
</div>
@endsection