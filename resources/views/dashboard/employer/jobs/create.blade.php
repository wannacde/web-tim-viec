@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Đăng tin tuyển dụng</h1>
        <p class="text-gray-600">Tạo tin tuyển dụng mới để tìm ứng viên phù hợp</p>
    </div>

    <form action="{{ route('employer.jobs.store') }}" method="POST" class="bg-white shadow rounded-lg p-6">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tiêu đề công việc *</label>
                <input type="text" name="title" value="{{ old('title') }}" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Ngành nghề *</label>
                <select name="category_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Chọn ngành nghề</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Địa điểm *</label>
                <select name="location_id" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Chọn địa điểm</option>
                    @foreach($locations as $location)
                        <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
                            {{ $location->name }}
                        </option>
                    @endforeach
                </select>
                @error('location_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Mức lương tối thiểu *</label>
                <input type="number" name="salary_min" value="{{ old('salary_min') }}" required min="0"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('salary_min')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Mức lương tối đa *</label>
                <input type="number" name="salary_max" value="{{ old('salary_max') }}" required min="0"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('salary_max')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Loại lương *</label>
                <select name="salary_type" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="hourly" {{ old('salary_type') == 'hourly' ? 'selected' : '' }}>Theo giờ</option>
                    <option value="daily" {{ old('salary_type') == 'daily' ? 'selected' : '' }}>Theo ngày</option>
                    <option value="weekly" {{ old('salary_type') == 'weekly' ? 'selected' : '' }}>Theo tuần</option>
                    <option value="monthly" {{ old('salary_type') == 'monthly' ? 'selected' : '' }}>Theo tháng</option>
                </select>
                @error('salary_type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Hình thức làm việc *</label>
                <select name="work_type" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="offline" {{ old('work_type') == 'offline' ? 'selected' : '' }}>Tại văn phòng</option>
                    <option value="online" {{ old('work_type') == 'online' ? 'selected' : '' }}>Làm việc từ xa</option>
                    <option value="hybrid" {{ old('work_type') == 'hybrid' ? 'selected' : '' }}>Kết hợp</option>
                </select>
                @error('work_type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kinh nghiệm yêu cầu *</label>
                <select name="experience_level" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="no_experience" {{ old('experience_level') == 'no_experience' ? 'selected' : '' }}>Không yêu cầu</option>
                    <option value="under_1_year" {{ old('experience_level') == 'under_1_year' ? 'selected' : '' }}>Dưới 1 năm</option>
                    <option value="1_3_years" {{ old('experience_level') == '1_3_years' ? 'selected' : '' }}>1-3 năm</option>
                    <option value="over_3_years" {{ old('experience_level') == 'over_3_years' ? 'selected' : '' }}>Trên 3 năm</option>
                </select>
                @error('experience_level')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Số lượng tuyển *</label>
                <input type="number" name="positions" value="{{ old('positions', 1) }}" required min="1"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('positions')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Hạn nộp hồ sơ *</label>
                <input type="date" name="deadline" value="{{ old('deadline') }}" required min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('deadline')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Lịch làm việc *</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                    @php $schedules = ['morning' => 'Sáng', 'afternoon' => 'Chiều', 'evening' => 'Tối', 'weekend' => 'Cuối tuần']; @endphp
                    @foreach($schedules as $key => $label)
                        <label class="flex items-center">
                            <input type="checkbox" name="work_schedule[]" value="{{ $key }}" 
                                   {{ in_array($key, old('work_schedule', [])) ? 'checked' : '' }}
                                   class="mr-2">
                            {{ $label }}
                        </label>
                    @endforeach
                </div>
                @error('work_schedule')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
        
        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Mô tả công việc *</label>
            <textarea name="description" rows="6" required placeholder="Mô tả chi tiết về công việc, nhiệm vụ..."
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
            @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        
        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Yêu cầu ứng viên</label>
            <textarea name="requirements" rows="4" placeholder="Các yêu cầu về kỹ năng, kinh nghiệm..."
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('requirements') }}</textarea>
            @error('requirements')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        
        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Quyền lợi</label>
            <textarea name="benefits" rows="4" placeholder="Các quyền lợi, phúc lợi cho ứng viên..."
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('benefits') }}</textarea>
            @error('benefits')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        
        <div class="mt-8 flex justify-end space-x-4">
            <a href="{{ route('employer.jobs.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                Hủy
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Đăng tin
            </button>
        </div>
    </form>
</div>
@endsection