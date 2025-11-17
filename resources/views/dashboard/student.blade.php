@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard Sinh viên</h1>
        <p class="text-gray-600">Xin chào, {{ Auth::user()->name }}!</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <x-stat-card title="Đơn ứng tuyển" :value="$stats['applications']" icon="fas fa-file-alt" color="blue" />
        <x-stat-card title="Việc đã lưu" :value="$stats['saved_jobs']" icon="fas fa-heart" color="green" />
        <x-stat-card title="Đang chờ" :value="$stats['pending_applications']" icon="fas fa-clock" color="yellow" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Applications -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Ứng tuyển gần đây</h2>
            </div>
            <div class="p-6">
                @forelse($recentApplications as $application)
                    <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                        <div>
                            <h3 class="font-medium text-gray-900">{{ $application->job->title }}</h3>
                            <p class="text-sm text-gray-600">{{ $application->job->company->name }}</p>
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
                @empty
                    <p class="text-gray-500 text-center py-4">Chưa có đơn ứng tuyển nào</p>
                @endforelse
            </div>
        </div>

        <!-- Saved Jobs -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Việc làm đã lưu</h2>
            </div>
            <div class="p-6">
                @forelse($savedJobs as $savedJob)
                    <div class="py-3 border-b border-gray-100 last:border-b-0">
                        <h3 class="font-medium text-gray-900">
                            <a href="{{ route('jobs.show', $savedJob->job->slug) }}" class="text-blue-600 hover:text-blue-800">
                                {{ $savedJob->job->title }}
                            </a>
                        </h3>
                        <p class="text-sm text-gray-600">{{ $savedJob->job->company->name }}</p>
                        <div class="flex items-center justify-between mt-2">
                            <span class="text-xs text-gray-500">{{ $savedJob->created_at->diffForHumans() }}</span>
                            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">
                                {{ $savedJob->job->category->name }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">Chưa lưu việc làm nào</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Hành động nhanh</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('home') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                <i class="fas fa-search text-blue-600 mr-3"></i>
                <span>Tìm việc làm mới</span>
            </a>
            <a href="{{ route('student.applications') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                <i class="fas fa-history text-green-600 mr-3"></i>
                <span>Lịch sử ứng tuyển</span>
            </a>
            <a href="{{ route('profile.edit') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                <i class="fas fa-user-edit text-purple-600 mr-3"></i>
                <span>Cập nhật hồ sơ</span>
            </a>
        </div>
    </div>
</div>
@endsection