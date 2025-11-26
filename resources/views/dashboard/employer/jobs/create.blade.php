@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6"><a href="{{ route('employer.jobs.index') }}" class="flex items-center text-gray-600 hover:text-blue-600 transition-colors"><i class="fas fa-arrow-left mr-2"></i> Quay lại</a></div>
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
                <label class="block text-sm font-medium text-gray-700 mb-2">Mức lương tối thiểu * (tối đa 999,999,999 VNĐ)</label>
                <input type="number" name="salary_min" value="{{ old('salary_min') }}" required min="0" max="999999999"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 salary-input">
                @error('salary_min')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Mức lương tối đa * (tối đa 999,999,999 VNĐ)</label>
                <input type="number" name="salary_max" value="{{ old('salary_max') }}" required min="0" max="999999999"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 salary-input">
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

<!-- Popup Modal -->
<div id="salaryWarningModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md mx-4">
        <div class="flex items-center mb-4">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.962-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-lg font-medium text-gray-900">Giá trị quá lớn!</h3>
            </div>
        </div>
        <div class="mb-4">
            <p class="text-sm text-gray-700">
                Mức lương không được vượt quá 999,999,999 VNĐ. Vui lòng nhập lại giá trị hợp lệ.
            </p>
        </div>
        <div class="flex justify-end">
            <button type="button" onclick="closeSalaryModal()" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                Đã hiểu
            </button>
        </div>
    </div>
</div>

<script>
function showSalaryModal() {
    document.getElementById('salaryWarningModal').classList.remove('hidden');
    document.getElementById('salaryWarningModal').classList.add('flex');
}

function closeSalaryModal() {
    document.getElementById('salaryWarningModal').classList.add('hidden');
    document.getElementById('salaryWarningModal').classList.remove('flex');
}

// Add event listeners to salary inputs
document.addEventListener('DOMContentLoaded', function() {
    const salaryInputs = document.querySelectorAll('.salary-input');
    
    salaryInputs.forEach(function(input) {
        input.addEventListener('input', function() {
            const value = parseInt(this.value);
            if (value > 999999999) {
                this.value = '';
                showSalaryModal();
            }
        });
        
        input.addEventListener('blur', function() {
            const value = parseInt(this.value);
            if (value > 999999999) {
                this.value = '';
                showSalaryModal();
            }
        });
    });
});
</script>
@endsection