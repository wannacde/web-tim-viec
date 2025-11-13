@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard Admin</h1>
        <p class="text-gray-600">Quản trị hệ thống</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <x-stat-card title="Tổng việc làm" :value="$stats['total_jobs']" icon="fas fa-briefcase" color="blue" />
        <x-stat-card title="Tổng người dùng" :value="$stats['total_users']" icon="fas fa-users" color="green" />
        <x-stat-card title="Tổng công ty" :value="$stats['total_companies']" icon="fas fa-building" color="purple" />
        <x-stat-card title="Tổng ứng tuyển" :value="$stats['total_applications']" icon="fas fa-file-alt" color="yellow" />
    </div>

    <!-- Management Sections -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center mb-4">
                <i class="fas fa-users text-2xl text-blue-600 mr-3"></i>
                <h2 class="text-lg font-semibold text-gray-900">Quản lý người dùng</h2>
            </div>
            <p class="text-gray-600 mb-4">Quản lý tài khoản sinh viên và nhà tuyển dụng</p>
            <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">Xem chi tiết →</a>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center mb-4">
                <i class="fas fa-briefcase text-2xl text-green-600 mr-3"></i>
                <h2 class="text-lg font-semibold text-gray-900">Quản lý việc làm</h2>
            </div>
            <p class="text-gray-600 mb-4">Duyệt và quản lý các tin tuyển dụng</p>
            <a href="{{ route('admin.jobs.index') }}" class="text-green-600 hover:text-green-800 font-medium">Xem chi tiết →</a>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center mb-4">
                <i class="fas fa-building text-2xl text-purple-600 mr-3"></i>
                <h2 class="text-lg font-semibold text-gray-900">Quản lý công ty</h2>
            </div>
            <p class="text-gray-600 mb-4">Xác minh và quản lý thông tin công ty</p>
            <a href="#" class="text-purple-600 hover:text-purple-800 font-medium">Xem chi tiết →</a>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center mb-4">
                <i class="fas fa-tags text-2xl text-orange-600 mr-3"></i>
                <h2 class="text-lg font-semibold text-gray-900">Quản lý danh mục</h2>
            </div>
            <p class="text-gray-600 mb-4">Quản lý ngành nghề và địa điểm</p>
            <a href="{{ route('admin.categories.index') }}" class="text-orange-600 hover:text-orange-800 font-medium">Xem chi tiết →</a>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center mb-4">
                <i class="fas fa-chart-bar text-2xl text-red-600 mr-3"></i>
                <h2 class="text-lg font-semibold text-gray-900">Báo cáo thống kê</h2>
            </div>
            <p class="text-gray-600 mb-4">Xem báo cáo và thống kê hệ thống</p>
            <a href="#" class="text-red-600 hover:text-red-800 font-medium">Xem chi tiết →</a>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center mb-4">
                <i class="fas fa-cog text-2xl text-gray-600 mr-3"></i>
                <h2 class="text-lg font-semibold text-gray-900">Cài đặt hệ thống</h2>
            </div>
            <p class="text-gray-600 mb-4">Cấu hình và cài đặt hệ thống</p>
            <a href="#" class="text-gray-600 hover:text-gray-800 font-medium">Xem chi tiết →</a>
        </div>
    </div>
</div>
@endsection