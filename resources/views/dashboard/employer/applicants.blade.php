@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6"><a href="{{ route('dashboard') }}" class="flex items-center text-gray-600 hover:text-blue-600 transition-colors"><i class="fas fa-arrow-left mr-2"></i> Quay lại</a></div>
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Quản lý ứng viên</h1>
        <p class="text-gray-600">Danh sách ứng viên cho các tin tuyển dụng của bạn</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto bg-white rounded-xl shadow-sm border border-gray-100">
        <table class="min-w-full">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ứng viên</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Công việc</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Thư xin việc</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Trạng thái</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ngày nộp</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($applications as $application)
                    <tr class="hover:bg-blue-50/50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white font-semibold text-xs mr-3">
                                    {{ substr($application->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $application->user->name }}</div>
                                    <div class="text-gray-500">{{ $application->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            <div class="font-medium text-blue-600">{{ $application->job->title }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700 max-w-xs">
                            <div class="truncate">{{ Str::limit($application->cover_letter, 50) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($application->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($application->status === 'accepted') bg-green-100 text-green-800
                                @elseif($application->status === 'rejected') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($application->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ $application->created_at->diffForHumans() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            <div class="flex items-center space-x-2">
                                <a href="{{ Storage::url($application->cv_path) }}" target="_blank" class="text-blue-600 hover:text-blue-900 font-medium">Tải CV</a>
                                <form action="{{ route('applications.updateStatus', $application) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" class="text-xs border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500">
                                        <option value="pending" {{ $application->status === 'pending' ? 'selected' : '' }}>Chờ</option>
                                        <option value="reviewing" {{ $application->status === 'reviewing' ? 'selected' : '' }}>Xem xét</option>
                                        <option value="accepted" {{ $application->status === 'accepted' ? 'selected' : '' }}>Chấp nhận</option>
                                        <option value="rejected" {{ $application->status === 'rejected' ? 'selected' : '' }}>Từ chối</option>
                                    </select>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <i class="fas fa-users text-4xl text-gray-400 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Chưa có ứng viên nào</h3>
                            <p class="text-gray-600">Các ứng viên sẽ xuất hiện ở đây khi họ ứng tuyển vào công việc của bạn.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($applications->hasPages())
        <div class="mt-6">
            {{ $applications->links() }}
        </div>
    @endif
</div>
@endsection