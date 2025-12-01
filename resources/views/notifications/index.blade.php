@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6" x-data="notificationApp()">
    <div class="flex h-[75vh] bg-gray-100 overflow-hidden border border-gray-200 rounded-2xl shadow-xl">
    
        <!-- Left Sidebar - Notification Types -->
        <div class="w-80 md:w-96 bg-white border-r border-gray-200 flex flex-col flex-shrink-0 relative z-20">
            <div class="p-4 border-b border-gray-200 flex justify-between items-center bg-white">
                <h2 class="font-bold text-xl text-gray-800">Thông báo</h2>
                <button @click="markAllRead()" 
                        x-show="unreadCount > 0"
                        class="text-sm text-blue-600 hover:text-blue-800">
                    Đánh dấu tất cả đã đọc
                </button>
            </div>
            
            <div class="p-3 border-b border-gray-100">
                <input type="text" x-model="searchQuery" placeholder="Tìm kiếm thông báo..." 
                       class="w-full px-4 py-2 bg-gray-100 border-none rounded-full text-sm focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Filter Tabs -->
            <div class="flex border-b border-gray-100">
                <button @click="activeFilter = 'all'" 
                        :class="activeFilter === 'all' ? 'bg-blue-50 text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-gray-900'"
                        class="flex-1 px-4 py-3 text-sm font-medium">
                    Tất cả (<span x-text="notifications.length"></span>)
                </button>
                <button @click="activeFilter = 'unread'" 
                        :class="activeFilter === 'unread' ? 'bg-blue-50 text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-gray-900'"
                        class="flex-1 px-4 py-3 text-sm font-medium">
                    Chưa đọc (<span x-text="unreadCount"></span>)
                </button>
            </div>

            <!-- Notifications List -->
            <div class="flex-1 overflow-y-auto">
                <template x-for="notification in filteredNotifications" :key="notification.id">
                    <div @click="selectNotification(notification)" 
                         class="group flex items-start p-4 cursor-pointer hover:bg-gray-50 transition border-b border-gray-100"
                         :class="{
                             'bg-blue-50': !notification.read_at,
                             'bg-blue-100 border-l-4 border-blue-600': selectedNotificationId === notification.id
                         }">
                        <div class="flex-shrink-0 mr-3">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center"
                                 :class="notification.type === 'App\\Notifications\\NewApplicationReceived' ? 'bg-green-100' : 'bg-blue-100'">
                                <i :class="notification.type === 'App\\Notifications\\NewApplicationReceived' ? 'fas fa-user-plus text-green-600' : 'fas fa-bell text-blue-600'"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 mb-1" x-text="notification.data.message"></p>
                            <p class="text-xs text-gray-500" x-text="notification.created_at_human"></p>
                        </div>
                        <div class="flex-shrink-0 flex items-center space-x-2">
                            <div x-show="!notification.read_at" class="w-2 h-2 bg-blue-600 rounded-full"></div>
                            <button @click.stop="deleteNotification(notification)" 
                                    class="text-gray-400 hover:text-red-500 transition p-1 rounded hover:bg-gray-100">
                                <i class="fas fa-times text-sm"></i>
                            </button>
                        </div>
                    </div>
                </template>
                
                <div x-show="filteredNotifications.length === 0" class="p-8 text-center text-gray-500">
                    <i class="fas fa-bell-slash text-4xl mb-4 text-gray-300"></i>
                    <p>Không có thông báo nào</p>
                </div>
            </div>
        </div>

        <!-- Right Content - Notification Detail -->
        <div class="flex-1 flex flex-col bg-white relative w-full h-full">
            <!-- Empty State -->
            <div x-show="!selectedNotificationId" class="absolute inset-0 flex flex-col items-center justify-center bg-gray-50 z-0">
                <div class="w-24 h-24 bg-white rounded-full shadow-sm flex items-center justify-center mb-4">
                    <i class="fas fa-bell text-4xl text-blue-500"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800">Chào mừng đến với Thông báo</h3>
                <p class="text-gray-500 mt-2">Chọn một thông báo để xem chi tiết</p>
            </div>

            <!-- Notification Detail -->
            <div x-show="selectedNotificationId" class="flex-1 flex flex-col h-full relative z-10" style="display: none;">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-200 bg-white flex justify-between items-center shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center"
                             :class="selectedNotification?.type === 'App\\Notifications\\NewApplicationReceived' ? 'bg-green-100' : 'bg-blue-100'">
                            <i :class="selectedNotification?.type === 'App\\Notifications\\NewApplicationReceived' ? 'fas fa-user-plus text-green-600' : 'fas fa-bell text-blue-600'"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 text-base" x-text="getNotificationTitle(selectedNotification)"></h3>
                            <span class="text-xs text-gray-500" x-text="selectedNotification?.created_at_human"></span>
                        </div>
                    </div>
                    <button @click="markAsRead(selectedNotification)" 
                            x-show="selectedNotification && !selectedNotification.read_at"
                            class="text-sm text-blue-600 hover:text-blue-800">
                        Đánh dấu đã đọc
                    </button>
                </div>

                <!-- Content -->
                <div class="flex-1 overflow-y-auto p-6 bg-slate-50">
                    <div class="bg-white rounded-lg p-6 shadow-sm">
                        <div class="mb-4">
                            <h4 class="text-lg font-semibold text-gray-900 mb-2">Chi tiết thông báo</h4>
                            <p class="text-gray-700" x-text="selectedNotification?.data.message"></p>
                        </div>
                        
                        <template x-if="selectedNotification?.data.job_title">
                            <div class="mb-4">
                                <h5 class="font-medium text-gray-900 mb-1">Công việc:</h5>
                                <p class="text-gray-700" x-text="selectedNotification?.data.job_title"></p>
                            </div>
                        </template>
                        
                        <template x-if="selectedNotification?.data.student_name">
                            <div class="mb-4">
                                <h5 class="font-medium text-gray-900 mb-1">Sinh viên:</h5>
                                <p class="text-gray-700" x-text="selectedNotification?.data.student_name"></p>
                            </div>
                        </template>
                        
                        <template x-if="selectedNotification?.data.status_text">
                            <div class="mb-4">
                                <h5 class="font-medium text-gray-900 mb-1">Trạng thái:</h5>
                                <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium"
                                      :class="{
                                          'bg-green-100 text-green-800': selectedNotification?.data.status === 'accepted',
                                          'bg-red-100 text-red-800': selectedNotification?.data.status === 'rejected',
                                          'bg-yellow-100 text-yellow-800': selectedNotification?.data.status === 'reviewing',
                                          'bg-gray-100 text-gray-800': selectedNotification?.data.status === 'pending'
                                      }"
                                      x-text="selectedNotification?.data.status_text">
                                </span>
                            </div>
                        </template>
                        
                        <div class="pt-4 border-t border-gray-200">
                            <template x-if="selectedNotification?.data.url">
                                <a :href="selectedNotification?.data.url" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                    <i class="fas fa-external-link-alt mr-2"></i>
                                    Xem chi tiết
                                </a>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('notificationApp', () => ({
        notifications: @json($notifications ?? []),
        selectedNotificationId: null,
        selectedNotification: null,
        searchQuery: '',
        activeFilter: 'all',
        unreadCount: {{ auth()->user()->unreadNotifications->count() }},

        get filteredNotifications() {
            let filtered = this.notifications;
            
            if (this.activeFilter === 'unread') {
                filtered = filtered.filter(n => !n.read_at);
            }
            
            if (this.searchQuery) {
                filtered = filtered.filter(n => 
                    n.data.message.toLowerCase().includes(this.searchQuery.toLowerCase())
                );
            }
            
            return filtered.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
        },

        init() {
            if (typeof Echo !== 'undefined') {
                Echo.private('notifications.' + {{ auth()->id() }})
                    .notification((notification) => {
                        this.notifications.unshift({
                            id: notification.id,
                            type: notification.type,
                            data: notification.data,
                            read_at: null,
                            created_at: new Date().toISOString(),
                            created_at_human: 'Vừa xong'
                        });
                        this.unreadCount++;
                        this.updateBadge();
                    });
            }
        },

        selectNotification(notification) {
            this.selectedNotificationId = notification.id;
            this.selectedNotification = notification;
            
            if (!notification.read_at) {
                this.markAsRead(notification);
            }
        },

        markAsRead(notification) {
            if (notification.read_at) return;
            
            fetch(`/notifications/${notification.id}/read`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }).then(() => {
                notification.read_at = new Date().toISOString();
                this.unreadCount = Math.max(0, this.unreadCount - 1);
                this.updateBadge();
            });
        },

        markAllRead() {
            fetch('/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            }).then(() => {
                this.notifications.forEach(n => n.read_at = new Date().toISOString());
                this.unreadCount = 0;
                this.updateBadge();
            });
        },

        getNotificationTitle(notification) {
            if (!notification) return '';
            if (notification.type === 'App\\Notifications\\NewApplicationReceived') {
                return 'Đơn ứng tuyển mới';
            }
            return 'Cập nhật trạng thái';
        },

        deleteNotification(notification) {
            if (confirm('Bạn có chắc chắn muốn xóa thông báo này?')) {
                fetch(`/notifications/${notification.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const index = this.notifications.findIndex(n => n.id === notification.id);
                        if (index > -1) {
                            this.notifications.splice(index, 1);
                        }
                        
                        if (!notification.read_at) {
                            this.unreadCount = Math.max(0, this.unreadCount - 1);
                        }
                        
                        if (this.selectedNotificationId === notification.id) {
                            this.selectedNotificationId = null;
                            this.selectedNotification = null;
                        }
                        
                        this.updateBadge();
                    }
                });
            }
        },

        updateBadge() {
            const badge = document.querySelector('.notification-badge');
            if (this.unreadCount > 0) {
                if (badge) {
                    badge.textContent = this.unreadCount;
                    badge.style.display = 'flex';
                }
            } else {
                if (badge) {
                    badge.style.display = 'none';
                }
            }
        }
    }));
});
</script>
@endsection