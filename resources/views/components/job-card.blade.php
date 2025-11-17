@props(['job', 'featured' => false])

<div data-aos="fade-up" data-aos-delay="{{ $attributes->get('data-aos-delay', 0) }}" 
     class="job-card bg-white rounded-3xl p-8 shadow-lg hover:shadow-2xl transition-all duration-300 {{ $featured ? 'border border-yellow-200' : '' }}">
    <div class="flex items-start justify-between mb-6">
        <div class="flex-1">
            <div class="flex items-center mb-3">
                @if($featured)
                    <span class="bg-gradient-to-r from-yellow-400 to-orange-500 text-white text-xs px-3 py-1 rounded-full font-semibold">
                        ⭐ Nổi bật
                    </span>
                @endif
                @if($job->is_urgent)
                    <span class="bg-red-500 text-white text-xs px-3 py-1 rounded-full font-semibold {{ $featured ? 'ml-2' : '' }} animate-pulse">
                        🔥 Gấp
                    </span>
                @endif
            </div>
            <h3 class="font-bold text-xl mb-2 text-gray-900">
                <a href="{{ route('jobs.show', $job->slug) }}" class="hover:text-blue-600 transition-colors">
                    {{ $job->title }}
                </a>
            </h3>
            <p class="text-gray-600 font-medium">{{ $job->user ? ($job->user->company_name ?? $job->user->name) : 'N/A' }}</p>
        </div>
        <div class="w-16 h-16 rounded-2xl flex items-center justify-center overflow-hidden {{ $featured ? 'bg-gradient-to-br from-blue-500 to-purple-600' : 'bg-gradient-to-br from-gray-400 to-gray-600' }}">
            @if($job->user && $job->user->company_logo)
                <img src="{{ Storage::url($job->user->company_logo) }}" alt="{{ $job->user ? ($job->user->company_name ?? $job->user->name) : 'Company' }} logo" class="w-full h-full object-cover">
            @else
                <span class="font-bold text-white text-xl">{{ $job->user ? substr($job->user->company_name ?? $job->user->name, 0, 1) : '?' }}</span>
            @endif
        </div>
    </div>
    
    <div class="space-y-3 mb-6">
        @if(!$featured)
            <div class="flex items-center text-gray-600">
                <i class="fas fa-tag w-5 text-orange-500"></i>
                <span class="ml-3">{{ $job->category->name }}</span>
            </div>
        @endif
        <div class="flex items-center text-gray-600">
            <i class="fas fa-map-marker-alt w-5 text-blue-500"></i>
            <span class="ml-3">{{ $job->location->name }}</span>
        </div>
        <div class="flex items-center text-gray-600">
            <i class="fas fa-money-bill-wave w-5 text-green-500"></i>
            <span class="ml-3 font-semibold">{{ number_format($job->salary_min) }} - {{ number_format($job->salary_max) }} VNĐ</span>
        </div>
        @if($featured)
            <div class="flex items-center text-gray-600">
                <i class="fas fa-clock w-5 text-purple-500"></i>
                <span class="ml-3">{{ implode(', ', $job->work_schedule) }}</span>
            </div>
        @endif
    </div>
    
    <div class="flex justify-between items-center">
        <span class="text-sm text-gray-500">{{ $job->created_at->diffForHumans() }}</span>
        <a href="{{ route('jobs.show', $job->slug) }}" 
           class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-6 py-3 rounded-xl font-semibold hover:from-blue-600 hover:to-purple-700 transform hover:scale-105 transition-all duration-300">
            Xem chi tiết
        </a>
    </div>
</div>