@auth
<div class="relative" x-data="{ 
    open: false, 
    unreadCount: {{ Auth::user()->unreadNotifications->count() }},
    notifications: []
}" x-init="
    Echo.private('App.Models.User.{{ Auth::id() }}')
        .notification((notification) => {
            unreadCount++;
            notifications.unshift(notification);
            if (notifications.length > 10) notifications.pop();
        })
        .listen('UnreadCountUpdated', (e) => {
            unreadCount = e.unreadCount;
        });
">
    <!-- Bell Icon -->
    <button @click="open = !open" class="relative text-gray-700 hover:text-blue-600 font-medium transition-colors duration-200">
        <i class="fas fa-bell text-xl"></i>
        <span x-show="unreadCount > 0" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center" x-text="unreadCount"></span>
    </button>

    <!-- Dropdown -->
    <div x-show="open" @click.away="open = false" x-transition
         class="absolute right-0 mt-2 w-[600px] bg-white rounded-xl shadow-2xl border border-gray-200 z-50">
        <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-t-xl">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-gray-900 text-xl">Thông báo</h3>
                    <p class="text-sm text-gray-600 mt-1" x-text="`${unreadCount} thông báo chưa đọc`"></p>
                </div>
                <button @click="unreadCount = 0" class="text-sm text-blue-600 hover:text-blue-800 font-medium px-3 py-1 rounded-md hover:bg-blue-100 transition-all duration-200">
                    Đánh dấu tất cả đã đọc
                </button>
            </div>
        </div>
        
        <div class="max-h-96 overflow-y-auto">
            <template x-for="notification in notifications" :key="notification.id">
                <a :href="`/notifications/mark-read/${notification.id}`" 
                   class="block px-4 py-3 hover:bg-blue-50 border-b border-gray-100 bg-blue-25 transition ease-in-out duration-150 break-words whitespace-normal">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 mt-1">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-bell text-blue-600"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 leading-relaxed break-words whitespace-normal" x-text="notification.message"></p>
                            <p class="text-xs text-gray-500 mt-1">Vừa xong</p>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="w-3 h-3 bg-blue-500 rounded-full animate-pulse"></div>
                        </div>
                    </div>
                </a>
            </template>
            @forelse(Auth::user()->unreadNotifications->take(6) as $notification)
                <a href="{{ route('notifications.read', $notification->id) }}" 
                   class="block px-4 py-3 hover:bg-blue-50 border-b border-gray-100 bg-blue-25 transition ease-in-out duration-150 break-words whitespace-normal">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 mt-1">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                @if($notification->data['type'] === 'application_new')
                                    <i class="fas fa-user-plus text-blue-600"></i>
                                @elseif($notification->data['type'] === 'application_status')
                                    <i class="fas fa-clipboard-check text-green-600"></i>
                                @else
                                    <i class="fas fa-bell text-blue-600"></i>
                                @endif
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 leading-relaxed break-words whitespace-normal">{{ $notification->data['message'] }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="p-12 text-center text-gray-500" x-show="notifications.length === 0">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-bell-slash text-3xl text-gray-400"></i>
                    </div>
                    <p class="font-semibold text-lg text-gray-700">Không có thông báo chưa đọc</p>
                    <p class="text-sm mt-2 text-gray-500">Các thông báo mới sẽ xuất hiện ở đây</p>
                </div>
            @endforelse
        </div>
        
        <!-- Footer với nút xem tất cả -->
        <div class="p-3 border-t border-gray-200 bg-gray-50 rounded-b-xl text-center">
            <a href="{{ route('messages.index') }}" 
               class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                Xem tất cả thông báo
            </a>
        </div>
    </div>
</div>
@endauth