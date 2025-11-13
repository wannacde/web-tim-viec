@extends('layouts.app')

@section('content')
<!-- Company Header -->
<div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex items-center space-x-6">
            <div class="w-24 h-24 rounded-2xl overflow-hidden bg-white/20 flex items-center justify-center">
                @if($company->logo)
                    <img src="{{ Storage::url($company->logo) }}" alt="{{ $company->name }}" class="w-full h-full object-cover">
                @else
                    <span class="text-4xl font-bold text-white">{{ substr($company->name, 0, 1) }}</span>
                @endif
            </div>
            <div class="flex-1">
                <div class="flex items-center space-x-3 mb-2">
                    <h1 class="text-4xl font-bold">{{ $company->name }}</h1>
                    @if($company->is_verified)
                        <span class="bg-green-500 text-white px-3 py-1 rounded-full text-sm">
                            <i class="fas fa-check-circle mr-1"></i>Đã xác minh
                        </span>
                    @endif
                </div>
                
                <div class="flex items-center space-x-6 text-white/90">
                    @if($company->location)
                        <span><i class="fas fa-map-marker-alt mr-2"></i>{{ $company->location->name }}</span>
                    @endif
                    @if($company->size)
                        <span><i class="fas fa-users mr-2"></i>{{ $company->size }} nhân viên</span>
                    @endif
                    <span><i class="fas fa-briefcase mr-2"></i>{{ $jobs->total() }} việc làm</span>
                </div>
                
                @if($company->website)
                    <div class="mt-4">
                        <a href="{{ $company->website }}" target="_blank" 
                           class="inline-flex items-center text-white/90 hover:text-white">
                            <i class="fas fa-globe mr-2"></i>{{ $company->website }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- About Company -->
            @if($company->description)
                <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Giới thiệu công ty</h2>
                    <div class="prose max-w-none text-gray-700 leading-relaxed whitespace-pre-line">
                        {{ $company->description }}
                    </div>
                </div>
            @endif
            
            <!-- Jobs -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Việc làm đang tuyển ({{ $jobs->total() }})</h2>
                </div>
                
                <div class="space-y-6">
                    @forelse($jobs as $job)
                        <div class="border border-gray-200 rounded-xl p-6 hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex-1">
                                    <h3 class="text-xl font-semibold text-gray-900 mb-2">
                                        <a href="{{ route('jobs.show', $job->slug) }}" class="hover:text-blue-600">
                                            {{ $job->title }}
                                        </a>
                                    </h3>
                                    <div class="flex items-center space-x-4 text-sm text-gray-600">
                                        <span><i class="fas fa-tag mr-1"></i>{{ $job->category->name }}</span>
                                        <span><i class="fas fa-map-marker-alt mr-1"></i>{{ $job->location->name }}</span>
                                        <span><i class="fas fa-money-bill-wave mr-1"></i>{{ number_format($job->salary_min) }} - {{ number_format($job->salary_max) }} VNĐ</span>
                                    </div>
                                </div>
                                @if($job->is_urgent)
                                    <span class="bg-red-500 text-white px-3 py-1 rounded-full text-xs font-semibold">
                                        🔥 Gấp
                                    </span>
                                @endif
                            </div>
                            
                            <p class="text-gray-600 mb-4 line-clamp-2">{{ Str::limit($job->description, 150) }}</p>
                            
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">Đăng {{ $job->created_at->diffForHumans() }}</span>
                                <a href="{{ route('jobs.show', $job->slug) }}" 
                                   class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                    Xem chi tiết
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <i class="fas fa-briefcase text-4xl text-gray-400 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Chưa có việc làm nào</h3>
                            <p class="text-gray-600">Công ty này hiện chưa có tin tuyển dụng.</p>
                        </div>
                    @endforelse
                </div>
                
                @if($jobs->hasPages())
                    <div class="mt-8">
                        {{ $jobs->links() }}
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Company Info -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Thông tin công ty</h3>
                
                <div class="space-y-4">
                    @if($company->email)
                        <div class="flex items-center">
                            <i class="fas fa-envelope text-gray-400 w-5 mr-3"></i>
                            <a href="mailto:{{ $company->email }}" class="text-blue-600 hover:text-blue-800">
                                {{ $company->email }}
                            </a>
                        </div>
                    @endif
                    
                    @if($company->phone)
                        <div class="flex items-center">
                            <i class="fas fa-phone text-gray-400 w-5 mr-3"></i>
                            <a href="tel:{{ $company->phone }}" class="text-blue-600 hover:text-blue-800">
                                {{ $company->phone }}
                            </a>
                        </div>
                    @endif
                    
                    @if($company->address)
                        <div class="flex items-start">
                            <i class="fas fa-map-marker-alt text-gray-400 w-5 mr-3 mt-1"></i>
                            <span class="text-gray-700">{{ $company->address }}</span>
                        </div>
                    @endif
                    
                    @if($company->website)
                        <div class="flex items-center">
                            <i class="fas fa-globe text-gray-400 w-5 mr-3"></i>
                            <a href="{{ $company->website }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                Website
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Quick Stats -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Thống kê</h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Việc làm đang tuyển:</span>
                        <span class="font-semibold">{{ $jobs->total() }}</span>
                    </div>
                    @if($company->size)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Quy mô:</span>
                            <span class="font-semibold">{{ $company->size }} nhân viên</span>
                        </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tham gia:</span>
                        <span class="font-semibold">{{ $company->created_at->format('m/Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection