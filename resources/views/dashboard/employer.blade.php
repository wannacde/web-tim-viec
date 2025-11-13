@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard Nhà tuyển dụng</h1>
        <p class="text-gray-600">Xin chào, {{ Auth::user()->name }}!</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <x-stat-card title="Tổng tin đăng" :value="$stats['total_jobs']" icon="fas fa-briefcase" color="blue" />
        <x-stat-card title="Đang hoạt động" :value="$stats['active_jobs']" icon="fas fa-check-circle" color="green" />
        <x-stat-card title="Tổng ứng viên" :value="$stats['total_applications']" icon="fas fa-users" color="purple" />
        <x-stat-card title="Chờ duyệt" :value="$stats['pending_applications']" icon="fas fa-clock" color="yellow" />
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
                                <h3 class="font-medium text-gray-900">
                                    <a href="{{ route('employer.jobs.edit', $job) }}" class="hover:text-blue-600">
                                        {{ $job->title }}
                                    </a>
                                </h3>
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
                                <h3 class="font-medium text-gray-900">
                                    <a href="{{ route('employer.applicants') }}" class="hover:text-blue-600">
                                        {{ $application->user->name }}
                                    </a>
                                </h3>
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
            <a href="{{ route('employer.jobs.index') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                <i class="fas fa-briefcase text-blue-600 mr-3"></i>
                <span>Quản lý tin đăng</span>
            </a>
            <a href="{{ route('employer.applicants') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                <i class="fas fa-users text-green-600 mr-3"></i>
                <span>Quản lý ứng viên</span>
            </a>
            <a href="{{ route('company.edit') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
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