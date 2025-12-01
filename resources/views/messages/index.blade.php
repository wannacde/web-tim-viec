@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="chatApp()">
    <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden flex h-[600px]">
        
        <div class="w-1/3 bg-white border-r border-gray-200 flex flex-col">
            <div class="p-4 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                <h2 class="font-bold text-lg text-gray-800">Tin nhắn</h2>
                <button @click="toggleNotifications()" class="relative text-gray-600 hover:text-blue-600">
                    <i class="fas fa-bell text-xl"></i>
                    <span x-show="unreadNotificationCount > 0" x-text="unreadNotificationCount" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center"></span>
                </button>
            </div>

            <div x-show="showNotifications" @click.away="showNotifications = false" class="absolute top-0 left-0 w-1/3 h-full bg-white z-10 border-r border-gray-200 shadow-lg transition-transform transform" x-transition>
                <div class="p-4 border-b border-gray-200 flex justify-between items-center bg-blue-50">
                    <h3 class="font-semibold text-blue-800">Thông báo</h3>
                    <button @click="markAllRead()" class="text-xs text-blue-600 underline">Đã đọc tất cả</button>
                </div>
                <div class="overflow-y-auto h-full pb-20">
                    <template x-for="notif in notifications" :key="notif.id">
                        <div class="p-3 border-b border-gray-100 hover:bg-gray-50 cursor-pointer" :class="{'bg-blue-50': !notif.read_at}">
                            <p class="text-sm text-gray-800" x-text="notif.data.message"></p>
                            <span class="text-xs text-gray-500" x-text="notif.created_at_human"></span>
                        </div>
                    </template>
                     <div x-show="notifications.length === 0" class="p-4 text-center text-gray-500 text-sm">Không có thông báo mới</div>
                </div>
            </div>
            
            <div class="flex-1 overflow-y-auto">
                <template x-for="user in contacts" :key="user.id">
                    <div @click="selectUser(user)" 
                         class="p-4 border-b border-gray-100 cursor-pointer hover:bg-gray-50 transition flex items-center"
                         :class="{'bg-blue-50': activeUserId === user.id}">
                        <div class="relative flex-shrink-0">
                            <img :src="user.avatar_url" class="w-12 h-12 rounded-full object-cover border border-gray-200">
                            <div x-show="user.unread_count > 0" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center shadow-sm">
                                <span x-text="user.unread_count"></span>
                            </div>
                        </div>
                        <div class="ml-3 flex-1 min-w-0">
                            <div class="flex justify-between items-baseline mb-1">
                                <span class="font-semibold text-gray-900 truncate" x-text="user.name"></span>
                                <span class="text-xs text-gray-500" x-text="user.last_message_time"></span>
                            </div>
                            <p class="text-sm text-gray-500 truncate" x-text="user.last_message_content" :class="{'font-bold text-gray-800': user.unread_count > 0}"></p>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <div class="w-2/3 bg-gray-50 flex flex-col relative">
            <div x-show="!activeUserId" class="flex-1 flex flex-col items-center justify-center text-gray-400">
                <div class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-comments text-4xl text-gray-400"></i>
                </div>
                <p class="text-lg">Chọn một người để bắt đầu trò chuyện</p>
            </div>

            <div x-show="activeUserId" class="flex-1 flex flex-col h-full" style="display: none;">
                <div class="p-4 bg-white border-b border-gray-200 flex items-center shadow-sm z-10">
                    <h3 class="font-bold text-gray-800 text-lg" x-text="activeUserName"></h3>
                </div>
                
                <div id="chat-container" class="flex-1 overflow-y-auto p-4 space-y-4 bg-slate-50">
                    <template x-for="msg in messages" :key="msg.id">
                        <div class="flex w-full" :class="msg.sender_id === authUserId ? 'justify-end' : 'justify-start'">
                            <div class="max-w-[70%] rounded-2xl px-5 py-3 shadow-sm text-sm leading-relaxed" 
                                 :class="msg.sender_id === authUserId ? 'bg-blue-600 text-white rounded-br-none' : 'bg-white text-gray-800 border border-gray-100 rounded-bl-none'">
                                <p x-text="msg.message" class="break-words"></p>
                                <div class="text-xs mt-1 text-right opacity-70" x-text="msg.time"></div>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="p-4 bg-white border-t border-gray-200">
                    <form @submit.prevent="sendMessage" class="flex gap-3 items-center">
                        <input type="text" x-model="newMessage" 
                               class="flex-1 rounded-full border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 py-3 px-5 bg-gray-50" 
                               placeholder="Nhập tin nhắn...">
                        <button type="submit" class="bg-blue-600 text-white w-12 h-12 rounded-full hover:bg-blue-700 transition shadow-md flex items-center justify-center disabled:opacity-50" :disabled="!newMessage.trim()">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('chatApp', () => ({
            authUserId: {{ Auth::id() }},
            contacts: @json($users),
            notifications: @json($notifications ?? []),
            unreadNotificationCount: {{ $unreadNotificationCount ?? 0 }},
            activeUserId: null,
            activeUserName: '',
            messages: [],
            newMessage: '',
            showNotifications: false,

            init() {
                Echo.private('chat.' + this.authUserId)
                    .listen('MessageSent', (e) => {
                        this.handleIncomingMessage(e.message);
                    });
            },

            toggleNotifications() {
                this.showNotifications = !this.showNotifications;
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
                }).catch(err => {
                    console.error(err);
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
                this.unreadNotificationCount = 0;
                this.notifications.forEach(n => n.read_at = new Date());
                axios.post('/notifications/mark-all-read');
            },

            scrollToBottom() {
                const container = document.getElementById('chat-container');
                if(container) container.scrollTop = container.scrollHeight;
            }
        }));
    });
</script>
@endsection));
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