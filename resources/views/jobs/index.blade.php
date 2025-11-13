@extends('layouts.app')

@section('title', 'Tìm việc làm')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold mb-4">Tìm việc làm part-time</h1>
            <p class="text-xl opacity-90">{{ $jobs->total() }} việc làm đang chờ bạn</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Search & Filter Form -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
            <form method="GET" action="{{ route('jobs.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Tìm kiếm việc làm..." 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Category -->
                    <div>
                        <select name="category" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Tất cả ngành nghề</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Location -->
                    <div>
                        <select name="location" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Tất cả địa điểm</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}" {{ request('location') == $location->id ? 'selected' : '' }}>
                                    {{ $location->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Work Type -->
                    <div>
                        <select name="work_type" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Hình thức làm việc</option>
                            <option value="online" {{ request('work_type') == 'online' ? 'selected' : '' }}>Online</option>
                            <option value="offline" {{ request('work_type') == 'offline' ? 'selected' : '' }}>Offline</option>
                            <option value="hybrid" {{ request('work_type') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Salary Range -->
                    <div class="flex gap-2">
                        <input type="number" name="salary_min" value="{{ request('salary_min') }}" 
                               placeholder="Lương tối thiểu" 
                               class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <input type="number" name="salary_max" value="{{ request('salary_max') }}" 
                               placeholder="Lương tối đa" 
                               class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Sort -->
                    <div>
                        <select name="sort" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Mới nhất</option>
                            <option value="salary_high" {{ request('sort') == 'salary_high' ? 'selected' : '' }}>Lương cao nhất</option>
                            <option value="salary_low" {{ request('sort') == 'salary_low' ? 'selected' : '' }}>Lương thấp nhất</option>
                            <option value="deadline" {{ request('sort') == 'deadline' ? 'selected' : '' }}>Sắp hết hạn</option>
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all duration-200 font-medium">
                            Tìm kiếm
                        </button>
                        <a href="{{ route('jobs.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            Xóa bộ lọc
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Job Results -->
        @if($jobs->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($jobs as $job)
                    <x-job-card :job="$job" />
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex justify-center">
                {{ $jobs->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Không tìm thấy việc làm phù hợp</h3>
                <p class="text-gray-600 mb-6">Thử thay đổi bộ lọc hoặc từ khóa tìm kiếm</p>
                <a href="{{ route('jobs.index') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                    Xem tất cả việc làm
                </a>
            </div>
        @endif
    </div>
</div>
@endsection