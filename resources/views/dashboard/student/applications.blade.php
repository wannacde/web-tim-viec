@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6"><a href="{{ route('dashboard') }}" class="flex items-center text-gray-600 hover:text-blue-600 transition-colors"><i class="fas fa-arrow-left mr-2"></i> Quay lại</a></div>
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Việc làm đã ứng tuyển</h1>
        <p class="text-gray-600">Theo dõi trạng thái các đơn ứng tuyển của bạn tại đây.</p>
    </div>

    <div class="overflow-x-auto bg-white rounded-xl shadow-sm border border-gray-100">
        <table class="min-w-full">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tên công việc</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Công ty</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ngày nộp</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Trạng thái</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($applications as $application)
                    <tr class="hover:bg-blue-50/50 transition-colors duration-200">
                        <td class="px-6 py-4 text-sm text-gray-700">
                            <a href="{{ route('jobs.show', $application->job->slug) }}" class="text-blue-600 hover:text-blue-900 font-medium">
                                {{ $application->job->title }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ $application->job->user ? ($application->job->user->company_name ?? $application->job->user->name) : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ $application->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($application->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($application->status === 'accepted') bg-green-100 text-green-800
                                @elseif($application->status === 'rejected') bg-red-100 text-red-800
                                @else bg-blue-100 text-blue-800 @endif">
                                {{ ucfirst($application->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                            Bạn chưa ứng tuyển công việc nào.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $applications->links() }}
    </div>
</div>
@endsection