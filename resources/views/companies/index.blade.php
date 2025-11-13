@extends('layouts.app')

@section('content')
<!-- Header -->
<div class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl font-bold mb-4">Khám phá các công ty</h1>
        <p class="text-xl mb-8">Tìm hiểu về các công ty hàng đầu đang tuyển dụng part-time</p>
        
        <!-- Search Form -->
        <form method="GET" class="bg-white/20 backdrop-blur-md p-6 rounded-2xl max-w-2xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Tìm kiếm công ty..." 
                       class="px-4 py-3 rounded-xl border-0 text-gray-900 placeholder-gray-500">
                <select name="location_id" class="px-4 py-3 rounded-xl border-0 text-gray-900">
                    <option value="">Tất cả địa điểm</option>
                    @foreach($locations as $location)
                        <option value="{{ $location->id }}" {{ request('location_id') == $location->id ? 'selected' : '' }}>
                            {{ $location->name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="bg-orange-500 text-white px-6 py-3 rounded-xl font-semibold hover:bg-orange-600">
                    <i class="fas fa-search mr-2"></i>Tìm kiếm
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Companies Grid -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900">{{ $companies->total() }} công ty</h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($companies as $index => $company)
                <div data-aos="fade-up" data-aos-delay="{{ ($index % 6) * 100 }}" 
                     class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden">
                    <!-- Company Header -->
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 rounded-xl overflow-hidden bg-gray-100 flex items-center justify-center">
                                @if($company->logo)
                                    <img src="{{ Storage::url($company->logo) }}" alt="{{ $company->name }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-2xl font-bold text-gray-500">{{ substr($company->name, 0, 1) }}</span>
                                @endif
                            </div>
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-gray-900">{{ $company->name }}</h3>
                                @if($company->location)
                                    <p class="text-gray-600"><i class="fas fa-map-marker-alt mr-1"></i>{{ $company->location->name }}</p>
                                @endif
                                @if($company->size)
                                    <p class="text-sm text-gray-500">{{ $company->size }} nhân viên</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Company Info -->
                    <div class="p-6">
                        @if($company->description)
                            <p class="text-gray-600 mb-4 line-clamp-3">{{ Str::limit($company->description, 120) }}</p>
                        @endif
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4 text-sm text-gray-500">
                                <span><i class="fas fa-briefcase mr-1"></i>{{ $company->jobs_count }} việc làm</span>
                                @if($company->is_verified)
                                    <span class="text-green-600"><i class="fas fa-check-circle mr-1"></i>Đã xác minh</span>
                                @endif
                            </div>
                            <a href="{{ route('companies.show', $company->slug) }}" 
                               class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition-colors">
                                Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-16">
                    <i class="fas fa-building text-6xl text-gray-400 mb-4"></i>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Không tìm thấy công ty</h3>
                    <p class="text-gray-600">Thử thay đổi từ khóa tìm kiếm của bạn</p>
                </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        @if($companies->hasPages())
            <div class="mt-12 flex justify-center">
                {{ $companies->links() }}
            </div>
        @endif
    </div>
</section>
@endsection