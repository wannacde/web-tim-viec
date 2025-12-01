@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-lg shadow-md overflow-hidden" style="height: 600px;">
        <div class="flex h-full">
            <!-- Left Sidebar - Contacts -->
            <div class="w-1/3 border-r border-gray-200 bg-gray-50">
                <div class="p-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Messages</h2>
                </div>
                <div class="overflow-y-auto h-full">
                    <!-- Thông báo hệ thống -->
                    <div class="border-b border-gray-200 bg-blue-50">
                        <div class="p-4 cursor-pointer hover:bg-blue-100 transition-colors" onclick="showNotifications()">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white">
                                    <i class="fas fa-bell"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-medium text-gray-900">Thông báo hệ thống</p>
                                        @if($unreadNotificationCount > 0)
                                            <span class="bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">{{ $unreadNotificationCount }}</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-600">Thông báo về ứng tuyển và công việc</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @forelse($contacts as $contact)
                        <a href="{{ route('messages.show', $contact->id) }}" 
                           class="block p-4 border-b border-gray-200 hover:bg-gray-100 transition-colors">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-indigo-500 rounded-full flex items-center justify-center text-white font-semibold">
                                    {{ strtoupper(substr($contact->name, 0, 1)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $contact->name }}</p>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-xs text-gray-500 capitalize">{{ $contact->role }}</span>
                                            @php
                                                $unread = \App\Models\Message::where('sender_id', $contact->id)->where('receiver_id', Auth::id())->where('is_read', false)->count();
                                            @endphp
                                            @if($unread > 0)
                                                <span class="bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">{{ $unread }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-500 truncate">{{ Str::limit($contact->last_message, 30) }}</p>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="p-4 text-center text-gray-500">
                            <p>No conversations yet</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Right Content -->
            <div class="flex-1 bg-gray-50">
                <!-- Default view -->
                <div id="default-view" class="flex items-center justify-center h-full">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gray-300 rounded-full mx-auto mb-4 flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Select a conversation</h3>
                        <p class="text-gray-500">Choose a user from the sidebar to start chatting</p>
                    </div>
                </div>
                
                <!-- Notifications view -->
                <div id="notifications-view" class="hidden h-full flex flex-col">
                    <div class="p-4 border-b border-gray-200 bg-white">
                        <h3 class="text-lg font-semibold text-gray-900">Thông báo hệ thống</h3>
                    </div>
                    <div class="flex-1 overflow-y-auto p-4">
                        @forelse($notifications as $notification)
                            <div class="mb-4 p-4 bg-white rounded-lg shadow-sm border {{ $notification->read_at ? 'opacity-75' : 'border-blue-200' }}">
                                <div class="flex items-start space-x-3">
                                    <div class="w-10 h-10 {{ $notification->read_at ? 'bg-gray-100' : 'bg-blue-100' }} rounded-full flex items-center justify-center">
                                        @if($notification->data['type'] === 'application_new')
                                            <i class="fas fa-user-plus {{ $notification->read_at ? 'text-gray-500' : 'text-blue-600' }}"></i>
                                        @elseif($notification->data['type'] === 'application_status')
                                            <i class="fas fa-clipboard-check {{ $notification->read_at ? 'text-gray-500' : 'text-green-600' }}"></i>
                                        @else
                                            <i class="fas fa-bell {{ $notification->read_at ? 'text-gray-500' : 'text-blue-600' }}"></i>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm {{ $notification->read_at ? 'text-gray-600' : 'font-medium text-gray-900' }}">{{ $notification->data['message'] }}</p>
                                        <p class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                        @if(!$notification->read_at)
                                            <a href="{{ route('notifications.read', $notification->id) }}" class="text-xs text-blue-600 hover:text-blue-800 mt-2 inline-block">Đánh dấu đã đọc</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <div class="w-16 h-16 bg-gray-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                                    <i class="fas fa-bell-slash text-2xl text-gray-400"></i>
                                </div>
                                <p class="text-gray-500">Không có thông báo nào</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
function showNotifications() {
    document.getElementById('default-view').classList.add('hidden');
    document.getElementById('notifications-view').classList.remove('hidden');
}
</script>