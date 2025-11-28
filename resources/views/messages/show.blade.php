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
                    @foreach($contacts as $contact)
                        <a href="{{ route('messages.show', $contact->id) }}" 
                           class="block p-4 border-b border-gray-200 hover:bg-gray-100 transition-colors {{ $contact->id == $user->id ? 'bg-indigo-50 border-indigo-200' : '' }}">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-indigo-500 rounded-full flex items-center justify-center text-white font-semibold">
                                    {{ strtoupper(substr($contact->name, 0, 1)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $contact->name }}</p>
                                        <span class="text-xs text-gray-500 capitalize">{{ $contact->role }}</span>
                                    </div>
                                    <p class="text-sm text-gray-500 truncate">{{ Str::limit($contact->last_message, 30) }}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Right Content - Chat -->
            <div class="flex-1 flex flex-col">
                <!-- Chat Header -->
                <div class="p-4 border-b border-gray-200 bg-white">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-indigo-500 rounded-full flex items-center justify-center text-white font-semibold">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">{{ $user->name }}</h3>
                            <p class="text-sm text-gray-500 capitalize">{{ $user->role }}</p>
                        </div>
                    </div>
                </div>

                <!-- Messages Area -->
                <div class="flex-1 overflow-y-auto p-4 space-y-4" id="messages-container">
                    @foreach($messages as $message)
                        <div class="flex {{ $message->sender_id == Auth::id() ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg {{ $message->sender_id == Auth::id() ? 'bg-indigo-500 text-white' : 'bg-gray-200 text-gray-900' }}">
                                <p class="text-sm">{{ $message->message }}</p>
                                <p class="text-xs mt-1 {{ $message->sender_id == Auth::id() ? 'text-indigo-200' : 'text-gray-500' }}">
                                    {{ $message->created_at->format('H:i') }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Message Input -->
                <div class="p-4 border-t border-gray-200 bg-white">
                    <form action="{{ route('messages.store') }}" method="POST" class="flex space-x-2">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $user->id }}">
                        <input type="text" 
                               name="message" 
                               placeholder="Type your message..." 
                               class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                               required>
                        <button type="submit" 
                                class="bg-indigo-500 text-white px-6 py-2 rounded-lg hover:bg-indigo-600 transition-colors">
                            Send
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="module">
    // Auto-scroll to bottom of messages
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('messages-container');
        container.scrollTop = container.scrollHeight;
    });

    // Real-time messaging with Laravel Echo
    const currentUserId = {{ Auth::id() }};
    const otherUserId = {{ $user->id }};
    
    window.Echo.private(`chat.${currentUserId}`)
        .listen('MessageSent', (e) => {
            // Check if message is from the current conversation
            if (e.sender_id === otherUserId) {
                // Create new message bubble
                const messageHtml = `
                    <div class="flex justify-start">
                        <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg bg-gray-200 text-gray-900">
                            <p class="text-sm">${e.message}</p>
                            <p class="text-xs mt-1 text-gray-500">${e.created_at}</p>
                        </div>
                    </div>
                `;
                
                // Append to messages container
                const container = document.getElementById('messages-container');
                container.insertAdjacentHTML('beforeend', messageHtml);
                
                // Auto-scroll to bottom
                container.scrollTop = container.scrollHeight;
            }
        });
</script>
@endsection