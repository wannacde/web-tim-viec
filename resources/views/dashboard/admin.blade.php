@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard Admin</h1>
        <p class="text-gray-600">Quản trị hệ thống</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-briefcase text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Tổng việc làm</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_jobs'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Tổng người dùng</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_users'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-building text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Tổng công ty</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_companies'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-file-alt text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Tổng ứng tuyển</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_applications'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Management Sections -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center mb-4">
                <i class="fas fa-users text-2xl text-blue-600 mr-3"></i>
                <h2 class="text-lg font-semibold text-gray-900">Quản lý người dùng</h2>
            </div>
            <p class="text-gray-600 mb-4">Quản lý tài khoản sinh viên và nhà tuyển dụng</p>
            <a href="#" class="text-blue-600 hover:text-blue-800 font-medium">Xem chi tiết →</a>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center mb-4">
                <i class="fas fa-briefcase text-2xl text-green-600 mr-3"></i>
                <h2 class="text-lg font-semibold text-gray-900">Quản lý việc làm</h2>
            </div>
            <p class="text-gray-600 mb-4">Duyệt và quản lý các tin tuyển dụng</p>
            <a href="#" class="text-green-600 hover:text-green-800 font-medium">Xem chi tiết →</a>
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
            <a href="#" class="text-orange-600 hover:text-orange-800 font-medium">Xem chi tiết →</a>
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