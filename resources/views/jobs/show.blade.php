@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6">
                <!-- Job Header -->
                <div class="border-b pb-6 mb-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $job->title }}</h1>
                            <p class="text-xl text-gray-600">{{ $job->company->name }}</p>
                        </div>
                        <div class="flex space-x-2">
                            @if($job->is_featured)
                                <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm">Nổi bật</span>
                            @endif
                            @if($job->is_urgent)
                                <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm">Gấp</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            {{ $job->location->name }}
                        </div>
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-tag mr-2"></i>
                            {{ $job->category->name }}
                        </div>
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-money-bill-wave mr-2"></i>
                            {{ number_format($job->salary_min) }} - {{ number_format($job->salary_max) }} VNĐ
                        </div>
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-clock mr-2"></i>
                            {{ $job->salary_type }}
                        </div>
                    </div>
                </div>

                <!-- Job Description -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold mb-4">Mô tả công việc</h2>
                    <div class="prose max-w-none">
                        {!! nl2br(e($job->description)) !!}
                    </div>
                </div>

                <!-- Requirements -->
                @if($job->requirements)
                <div class="mb-8">
                    <h2 class="text-xl font-semibold mb-4">Yêu cầu</h2>
                    <div class="prose max-w-none">
                        {!! nl2br(e($job->requirements)) !!}
                    </div>
                </div>
                @endif

                <!-- Benefits -->
                @if($job->benefits)
                <div class="mb-8">
                    <h2 class="text-xl font-semibold mb-4">Quyền lợi</h2>
                    <div class="prose max-w-none">
                        {!! nl2br(e($job->benefits)) !!}
                    </div>
                </div>
                @endif

                <!-- Work Schedule -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold mb-4">Lịch làm việc</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach($job->work_schedule as $schedule)
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                                {{ ucfirst($schedule) }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Apply Section -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="text-center">
                    @auth
                        <button class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 mb-3">
                            Ứng tuyển ngay
                        </button>
                        <button onclick="saveJob({{ $job->id }})" 
                                class="w-full border border-gray-300 text-gray-700 py-2 px-6 rounded-lg hover:bg-gray-50 save-btn">
                            <i class="fas fa-heart mr-2 {{ $isSaved ? 'text-red-500' : '' }}"></i>
                            <span class="save-text">{{ $isSaved ? 'Đã lưu' : 'Lưu việc làm' }}</span>
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-700 block mb-3">
                            Đăng nhập để ứng tuyển
                        </a>
                    @endauth
                </div>
                
                <div class="mt-6 space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Hạn nộp:</span>
                        <span class="font-medium">{{ $job->deadline->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Số lượng:</span>
                        <span class="font-medium">{{ $job->positions }} người</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Lượt xem:</span>
                        <span class="font-medium">{{ $job->views }}</span>
                    </div>
                </div>
            </div>

            <!-- Company Info -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold mb-4">Thông tin công ty</h3>
                <div class="space-y-3">
                    <div>
                        <h4 class="font-medium">{{ $job->company->name }}</h4>
                        @if($job->company->description)
                            <p class="text-gray-600 text-sm mt-2">{{ Str::limit($job->company->description, 150) }}</p>
                        @endif
                    </div>
                    
                    @if($job->company->address)
                    <div class="flex items-start">
                        <i class="fas fa-map-marker-alt text-gray-400 mr-2 mt-1"></i>
                        <span class="text-sm text-gray-600">{{ $job->company->address }}</span>
                    </div>
                    @endif
                    
                    @if($job->company->website)
                    <div class="flex items-center">
                        <i class="fas fa-globe text-gray-400 mr-2"></i>
                        <a href="{{ $job->company->website }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm">
                            Website
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Related Jobs -->
    @if($relatedJobs->count() > 0)
    <div class="mt-12">
        <h2 class="text-2xl font-bold mb-6">Việc làm liên quan</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($relatedJobs as $relatedJob)
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="font-semibold mb-2">
                        <a href="{{ route('jobs.show', $relatedJob->slug) }}" class="text-blue-600 hover:text-blue-800">
                            {{ Str::limit($relatedJob->title, 50) }}
                        </a>
                    </h3>
                    <p class="text-gray-600 text-sm mb-2">{{ $relatedJob->company->name }}</p>
                    <p class="text-gray-500 text-xs">{{ number_format($relatedJob->salary_min) }} - {{ number_format($relatedJob->salary_max) }} VNĐ</p>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
function saveJob(jobId) {
    $.post(`/jobs/${jobId}/save`, {
        _token: '{{ csrf_token() }}'
    })
    .done(function(response) {
        const icon = $('.save-btn i');
        const text = $('.save-text');
        
        if (response.saved) {
            icon.addClass('text-red-500');
            text.text('Đã lưu');
        } else {
            icon.removeClass('text-red-500');
            text.text('Lưu việc làm');
        }
        
        alert(response.message);
    })
    .fail(function() {
        window.location.href = '/login';
    });
}
</script>
@endpush
@endsection