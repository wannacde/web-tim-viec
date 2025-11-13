@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="relative min-h-screen hero-gradient flex items-center justify-center overflow-hidden">
    <div class="floating-shapes"></div>
    
    <!-- Floating Elements -->
    <div class="absolute top-20 left-10 w-20 h-20 bg-white bg-opacity-20 rounded-full animate-float"></div>
    <div class="absolute top-40 right-20 w-16 h-16 bg-white bg-opacity-15 rounded-full animate-float" style="animation-delay: 2s;"></div>
    <div class="absolute bottom-40 left-20 w-12 h-12 bg-white bg-opacity-25 rounded-full animate-float" style="animation-delay: 4s;"></div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
        <div data-aos="fade-up" data-aos-duration="1000">
            <h1 class="text-5xl md:text-7xl font-bold mb-6 leading-tight">
                Tìm Việc Làm 
                <span class="bg-gradient-to-r from-yellow-400 to-orange-500 bg-clip-text text-transparent">
                    Part-time
                </span>
                <br>Dành Cho Sinh Viên
            </h1>
            <p class="text-xl md:text-2xl mb-12 text-gray-100 max-w-3xl mx-auto leading-relaxed">
                Khám phá hàng nghìn cơ hội việc làm part-time phù hợp với lịch học của bạn. 
                Kiếm tiền và tích lũy kinh nghiệm ngay từ khi còn ngồi trên ghế nhà trường.
            </p>
        </div>
        
        <!-- Search Form -->
        <div data-aos="fade-up" data-aos-delay="200" class="glass-effect p-8 rounded-3xl shadow-2xl max-w-5xl mx-auto">
            <form method="GET" action="{{ route('home') }}" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="relative">
                        <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Tìm kiếm công việc..." 
                               class="w-full pl-12 pr-4 py-4 bg-white bg-opacity-90 border-0 rounded-2xl text-gray-900 placeholder-gray-500 focus:ring-4 focus:ring-white focus:ring-opacity-50 transition-all duration-300">
                    </div>
                    <div class="relative">
                        <i class="fas fa-briefcase absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <select name="category_id" class="w-full pl-12 pr-4 py-4 bg-white bg-opacity-90 border-0 rounded-2xl text-gray-900 focus:ring-4 focus:ring-white focus:ring-opacity-50 transition-all duration-300">
                            <option value="">Tất cả ngành nghề</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="relative">
                        <i class="fas fa-map-marker-alt absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <select name="location_id" class="w-full pl-12 pr-4 py-4 bg-white bg-opacity-90 border-0 rounded-2xl text-gray-900 focus:ring-4 focus:ring-white focus:ring-opacity-50 transition-all duration-300">
                            <option value="">Tất cả địa điểm</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}" {{ request('location_id') == $location->id ? 'selected' : '' }}>
                                    {{ $location->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="bg-gradient-to-r from-orange-500 to-pink-500 text-white px-8 py-4 rounded-2xl font-semibold hover:from-orange-600 hover:to-pink-600 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl">
                        <i class="fas fa-search mr-2"></i>Tìm kiếm
                    </button>
                </div>
            </form>
        </div>

        <!-- Stats -->
        <div data-aos="fade-up" data-aos-delay="400" class="grid grid-cols-2 md:grid-cols-4 gap-8 mt-16">
            <div class="text-center">
                <div class="text-4xl font-bold mb-2">1000+</div>
                <div class="text-gray-200">Việc làm</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold mb-2">500+</div>
                <div class="text-gray-200">Công ty</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold mb-2">5000+</div>
                <div class="text-gray-200">Sinh viên</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold mb-2">98%</div>
                <div class="text-gray-200">Hài lòng</div>
            </div>
        </div>
    </div>
</div>

<!-- Categories Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Ngành nghề phổ biến</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">Khám phá các cơ hội việc làm part-time trong nhiều lĩnh vực khác nhau</p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($categories->take(8) as $index => $category)
                <x-category-card :category="$category" data-aos-delay="{{ $index * 100 }}" />
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Jobs -->
@if($featuredJobs->count() > 0)
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Việc làm nổi bật</h2>
            <p class="text-xl text-gray-600">Những cơ hội việc làm tốt nhất dành cho bạn</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($featuredJobs as $index => $job)
                <x-job-card :job="$job" :featured="true" data-aos-delay="{{ $index * 100 }}" />
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- All Jobs -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-12" data-aos="fade-up">
            <div>
                <h2 class="text-4xl font-bold text-gray-900 mb-2">Tất cả việc làm</h2>
                <p class="text-gray-600">{{ $jobs->total() }} cơ hội việc làm đang chờ bạn</p>
            </div>
            <div class="flex space-x-4">
                <button class="px-6 py-3 bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300">
                    <i class="fas fa-filter mr-2"></i>Lọc
                </button>
                <button class="px-6 py-3 bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300">
                    <i class="fas fa-sort mr-2"></i>Sắp xếp
                </button>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($jobs as $index => $job)
                <x-job-card :job="$job" data-aos-delay="{{ ($index % 6) * 100 }}" />
            @empty
                <div class="col-span-full text-center py-16" data-aos="fade-up">
                    <div class="text-6xl mb-4">😔</div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Không tìm thấy công việc</h3>
                    <p class="text-gray-600">Thử thay đổi từ khóa tìm kiếm hoặc bộ lọc của bạn</p>
                </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        <div class="mt-12 flex justify-center" data-aos="fade-up">
            {{ $jobs->links() }}
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 text-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div data-aos="fade-up">
            <h2 class="text-4xl font-bold mb-6">Sẵn sàng bắt đầu hành trình của bạn?</h2>
            <p class="text-xl mb-8 opacity-90">Tham gia cùng hàng nghìn sinh viên đã tìm được việc làm part-time phù hợp</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" 
                   class="bg-white text-blue-600 px-8 py-4 rounded-2xl font-bold hover:bg-gray-100 transform hover:scale-105 transition-all duration-300 shadow-lg">
                    Đăng ký ngay
                </a>
                <a href="{{ route('home') }}" 
                   class="border-2 border-white text-white px-8 py-4 rounded-2xl font-bold hover:bg-white hover:text-blue-600 transform hover:scale-105 transition-all duration-300">
                    Tìm việc ngay
                </a>
            </div>
        </div>
    </div>
</section>
@endsection