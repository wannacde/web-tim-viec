@props(['category', 'jobCount' => null])

<a href="{{ route('jobs.index', ['category' => $category->id]) }}" 
   data-aos="fade-up" data-aos-delay="{{ $attributes->get('data-aos-delay', 0) }}" 
   class="category-card p-6 rounded-2xl text-white text-center hover:scale-105 transition-all duration-300 cursor-pointer block">
    <div class="text-4xl mb-4">
        <i class="{{ $category->icon }}"></i>
    </div>
    <h3 class="font-semibold text-lg">{{ $category->name }}</h3>
    <p class="text-sm opacity-90 mt-2">{{ $jobCount ?? $category->jobs_count ?? rand(10, 50) }} việc làm</p>
</a>