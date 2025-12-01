@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden" style="height: 600px;">
        <div class="h-full flex" x-data="chatApp()">
            <div class="w-1/3 bg-white border-r border-gray-200 flex flex-col">
                <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="font-bold text-lg">Tin nhắn</h2>
                    <button @click="markAllRead()" class="text-sm text-blue-600 hover:text-blue-800">Đã đọc tất cả</button>
                </div>
                
                <div class="flex-1 overflow-y-auto">
                    <!-- Thông báo hệ thống -->
                    <div class="border-b border-gray-200 bg-blue-50">
                        <div class="p-4 cursor-pointer hover:bg-blue-100 transition-colors" @click="showNotifications = !showNotifications">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white">
                                    <i class="fas fa-bell"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-medium text-gray-900">Thông báo hệ thống</p>
                                        <span class="bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center" x-show="unreadNotificationCount > 0" x-text="unreadNotificationCount"></span>
                                    </div>
                                    <p class="text-sm text-gray-600">Thông báo về ứng tuyển và công việc</p>
                                </div>
                            </div>
                        </div>
                    </div>
                <template x-for="user in contacts" :key="user.id">
                    <div @click="selectUser(user)" 
                         class="p-4 border-b border-gray-100 cursor-pointer hover:bg-gray-50 transition"
                         :class="{'bg-blue-50': activeUserId === user.id}">
                        <div class="flex items-center">
                            <div class="relative">
                                <img :src="user.avatar_url" class="w-10 h-10 rounded-full object-cover">
                                <div x-show="user.unread_count > 0" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                    <span x-text="user.unread_count"></span>
                                </div>
                            </div>
                            <div class="ml-3 flex-1">
                                <div class="flex justify-between">
                                    <span class="font-medium" x-text="user.name"></span>
                                    <span class="text-xs text-gray-500" x-text="user.last_message_time"></span>
                                </div>
                                <p class="text-sm text-gray-500 truncate" x-text="user.last_message_content" :class="{'font-bold text-black': user.unread_count > 0}"></p>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

            <div class="w-2/3 bg-gray-50 flex flex-col">
                <!-- Default view -->
                <div x-show="!activeUserId && !showNotifications" class="h-full flex items-center justify-center text-gray-400">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gray-300 rounded-full mx-auto mb-4 flex items-center justify-center">
                            <i class="fas fa-comments text-2xl text-gray-500"></i>
                        </div>
                        <p>Chọn một người để bắt đầu trò chuyện</p>
                    </div>
                </div>
                
                <!-- Notifications view -->
                <div x-show="showNotifications" class="h-full flex flex-col">
                    <div class="p-4 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Thông báo hệ thống</h3>
                    </div>
                    <div class="flex-1 overflow-y-auto p-4">
                        <template x-for="notification in notifications" :key="notification.id">
                            <div class="mb-4 p-4 bg-white rounded-lg shadow-sm border" :class="notification.read_at ? 'opacity-75' : 'border-blue-200'">
                                <div class="flex items-start space-x-3">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center" :class="notification.read_at ? 'bg-gray-100' : 'bg-blue-100'">
                                        <i class="fas fa-bell" :class="notification.read_at ? 'text-gray-500' : 'text-blue-600'"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm" :class="notification.read_at ? 'text-gray-600' : 'font-medium text-gray-900'" x-text="notification.message"></p>
                                        <p class="text-xs text-gray-500 mt-1" x-text="notification.time"></p>
                                    </div>
                                </div>
                            </div>
                        </template>
                        <div x-show="notifications.length === 0" class="text-center py-8">
                            <div class="w-16 h-16 bg-gray-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                                <i class="fas fa-bell-slash text-2xl text-gray-400"></i>
                            </div>
                            <p class="text-gray-500">Không có thông báo nào</p>
                        </div>
                    </div>
                </div>

                <!-- Chat view -->
                <div x-show="activeUserId" class="h-full flex flex-col">
                <div class="p-4 bg-white border-b border-gray-200 font-bold" x-text="activeUserName"></div>
                
                <div id="chat-container" class="flex-1 overflow-y-auto p-4 space-y-4">
                    <template x-for="msg in messages" :key="msg.id">
                        <div class="flex" :class="msg.sender_id === authUserId ? 'justify-end' : 'justify-start'">
                            <div class="max-w-[70%] rounded-lg px-4 py-2" 
                                 :class="msg.sender_id === authUserId ? 'bg-blue-600 text-white' : 'bg-white shadow-sm'">
                                <p x-text="msg.message"></p>
                                <span class="text-xs block mt-1 opacity-70" x-text="msg.time"></span>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="p-4 bg-white border-t border-gray-200">
                    <form @submit.prevent="sendMessage" class="flex gap-2">
                        <input type="text" x-model="newMessage" class="flex-1 rounded-full border-gray-300" placeholder="Nhập tin nhắn...">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-full">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function chatApp() {
            return {
                authUserId: {{ Auth::id() }},
                contacts: @json($users),
                activeUserId: null,
                activeUserName: '',
                messages: [],
                newMessage: '',
                showNotifications: false,
                notifications: @json($notifications ?? []),
                unreadNotificationCount: {{ $unreadNotificationCount ?? 0 }},

                init() {
                    Echo.private('App.Models.User.' + this.authUserId)
                        .listen('MessageSent', (e) => {
                            this.handleIncomingMessage(e.message);
                        });
                },

                selectUser(user) {
                    this.activeUserId = user.id;
                    this.activeUserName = user.name;
                    this.showNotifications = false;
                    user.unread_count = 0;
                    
                    axios.get(`/messages/${user.id}`)
                        .then(res => {
                            this.messages = res.data;
                            this.$nextTick(() => this.scrollToBottom());
                        });
                },

                sendMessage() {
                    if (!this.newMessage.trim()) return;
                    
                    const tempMsg = {
                        id: Date.now(), 
                        sender_id: this.authUserId, 
                        message: this.newMessage, 
                        time: 'Just now'
                    };
                    this.messages.push(tempMsg);
                    const msgToSend = this.newMessage;
                    this.newMessage = '';
                    this.$nextTick(() => this.scrollToBottom());

                    axios.post('/messages', {
                        receiver_id: this.activeUserId,
                        message: msgToSend
                    });
                },

                handleIncomingMessage(msg) {
                    if (this.activeUserId === msg.sender_id) {
                        this.messages.push(msg);
                        this.$nextTick(() => this.scrollToBottom());
                    } else {
                        const contact = this.contacts.find(u => u.id === msg.sender_id);
                        if (contact) {
                            contact.unread_count++;
                            contact.last_message_content = msg.message;
                        }
                    }
                },
                
                markAllRead() {
                    axios.post('/notifications/mark-all-read').then(() => {
                        this.contacts.forEach(c => c.unread_count = 0);
                    });
                },

                scrollToBottom() {
                    const container = document.getElementById('chat-container');
                    if(container) container.scrollTop = container.scrollHeight;
                }
            }
        }
    </script>
        </div>
    </div>
</div>
@endsection