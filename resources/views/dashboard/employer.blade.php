@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard Nhà tuyển dụng</h1>
        <p class="text-gray-600">Xin chào, {{ Auth::user()->name }}!</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-briefcase text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Tổng tin đăng</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_jobs'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Đang hoạt động</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['active_jobs'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Tổng ứng viên</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_applications'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Chờ duyệt</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['pending_applications'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Jobs -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900">Tin đăng gần đây</h2>
                <a href="#" class="text-blue-600 hover:text-blue-800 text-sm">Đăng tin mới</a>
            </div>
            <div class="p-6">
                @forelse($recentJobs as $job)
                    <div class="py-3 border-b border-gray-100 last:border-b-0">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-medium text-gray-900">{{ $job->title }}</h3>
                                <p class="text-sm text-gray-600">{{ $job->applications_count }} ứng viên</p>
                                <p class="text-xs text-gray-500">{{ $job->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($job->status === 'active') bg-green-100 text-green-800
                                @elseif($job->status === 'paused') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($job->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">Chưa có tin đăng nào</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Applications -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Ứng viên mới</h2>
            </div>
            <div class="p-6">
                @forelse($recentApplications as $application)
                    <div class="py-3 border-b border-gray-100 last:border-b-0">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-medium text-gray-900">{{ $application->user->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $application->job->title }}</p>
                                <p class="text-xs text-gray-500">{{ $application->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($application->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($application->status === 'accepted') bg-green-100 text-green-800
                                @elseif($application->status === 'rejected') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($application->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">Chưa có ứng viên nào</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Hành động nhanh</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <a href="#" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                <i class="fas fa-plus text-blue-600 mr-3"></i>
                <span>Đăng tin tuyển dụng</span>
            </a>
            <a href="#" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                <i class="fas fa-users text-green-600 mr-3"></i>
                <span>Quản lý ứng viên</span>
            </a>
            <a href="#" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                <i class="fas fa-building text-purple-600 mr-3"></i>
                <span>Thông tin công ty</span>
            </a>
            <a href="#" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                <i class="fas fa-chart-bar text-orange-600 mr-3"></i>
                <span>Báo cáo thống kê</span>
            </a>
        </div>
    </div>
</div>
@endsection