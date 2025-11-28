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

            <!-- Right Content - Placeholder -->
            <div class="flex-1 flex items-center justify-center bg-gray-50">
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
        </div>
    </div>
</div>
@endsection