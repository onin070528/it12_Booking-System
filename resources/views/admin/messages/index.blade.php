<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - Admin Panel</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="font-sans bg-[#ECF4E8]">

    <div class="flex">
        <!-- Admin Sidebar -->
        @include('admin.AdminLayouts.AdminSidebar')

        <!-- Main Content -->
        <div class="flex-1 min-h-screen px-6 py-6 ml-64">
            <!-- Header -->
            <div class="bg-white shadow-md rounded-xl px-6 py-4 flex justify-between items-center mb-8">
                <div class="flex items-center space-x-2">
                    <div>
                        <h2 class="text-3xl font-bold" style="color: #93BFC7;">
                            <i class="fas fa-comments mr-2"></i>Messages
                        </h2>
                        <p class="text-xl font-semibold" style="color: #93BFC7;">
                            Chat with users
                        </p>
                    </div>
                </div>
            </div>

            <!-- Chat Container -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden" style="height: calc(100vh - 200px);">
                <div class="flex h-full">
                    <!-- Users List -->
                    <div class="w-1/3 border-r border-gray-200 flex flex-col">
                        <div class="bg-[#93BFC7] px-4 py-3 border-b border-gray-200">
                            <h3 class="text-white font-bold">Conversations</h3>
                        </div>
                        <div class="flex-1 overflow-y-auto">
                            @forelse($users as $user)
                                @php
                                    $unreadCount = \App\Models\Message::where('sender_id', $user->id)
                                        ->where('receiver_id', Auth::id())
                                        ->where('read', false)
                                        ->count();
                                @endphp
                                <a href="{{ route('admin.messages.index', ['user_id' => $user->id]) }}" 
                                   class="block px-4 py-3 border-b border-gray-100 hover:bg-gray-50 transition {{ $selectedUser && $selectedUser->id == $user->id ? 'bg-blue-50' : '' }}">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 rounded-full bg-[#93BFC7] flex items-center justify-center">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-800">{{ $user->name }}</p>
                                                <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                        @if($unreadCount > 0)
                                            <span class="bg-red-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center">
                                                {{ $unreadCount }}
                                            </span>
                                        @endif
                                    </div>
                                </a>
                            @empty
                                <div class="p-8 text-center text-gray-500">
                                    <i class="fas fa-comments text-4xl mb-4"></i>
                                    <p>No conversations yet</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Chat Area -->
                    <div class="flex-1 flex flex-col">
                        @if($selectedUser)
                            <!-- Chat Header -->
                            <div class="bg-[#93BFC7] px-6 py-4 border-b border-gray-200">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center mr-3">
                                        <i class="fas fa-user text-[#93BFC7] text-xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-white font-bold text-lg">{{ $selectedUser->name }}</h3>
                                        <p class="text-white text-sm opacity-90">{{ $selectedUser->email }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Messages Area -->
                            <div id="messagesContainer" class="flex-1 overflow-y-auto p-6 bg-gray-50 space-y-4">
                                @foreach($messages as $message)
                                    <div class="flex {{ $message->sender_id === Auth::id() ? 'justify-end' : 'justify-start' }}">
                                        <div class="max-w-md">
                                            <div class="flex items-start space-x-2 {{ $message->sender_id === Auth::id() ? 'flex-row-reverse space-x-reverse' : '' }}">
                                                <div class="w-8 h-8 rounded-full {{ $message->sender_id === Auth::id() ? 'bg-[#93BFC7]' : 'bg-[#5394D0]' }} flex items-center justify-center flex-shrink-0">
                                                    <i class="fas fa-user text-white text-xs"></i>
                                                </div>
                                                <div class="flex flex-col {{ $message->sender_id === Auth::id() ? 'items-end' : 'items-start' }}">
                                                    <div class="px-4 py-2 rounded-lg {{ $message->sender_id === Auth::id() ? 'bg-[#93BFC7] text-white' : 'bg-white text-gray-800 border border-gray-200' }}">
                                                        <p class="text-sm">{{ $message->message }}</p>
                                                    </div>
                                                    <span class="text-xs text-gray-500 mt-1 px-2">
                                                        {{ $message->created_at->format('M d, Y h:i A') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Message Input -->
                            <div class="border-t border-gray-200 p-4 bg-white">
                                <form id="messageForm" class="flex space-x-3">
                                    @csrf
                                    <input type="hidden" name="receiver_id" value="{{ $selectedUser->id }}">
                                    <input type="text" 
                                           id="messageInput" 
                                           name="message" 
                                           placeholder="Type your message..." 
                                           class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#93BFC7] focus:border-transparent"
                                           required>
                                    <button type="submit" 
                                            class="bg-[#93BFC7] text-white px-6 py-3 rounded-lg hover:bg-[#7aa8b0] transition font-semibold">
                                        <i class="fas fa-paper-plane mr-2"></i>Send
                                    </button>
                                </form>
                            </div>
                        @else
                            <!-- Empty State -->
                            <div class="flex-1 flex items-center justify-center">
                                <div class="text-center text-gray-500">
                                    <i class="fas fa-comments text-6xl mb-4"></i>
                                    <p class="text-xl font-semibold mb-2">Select a conversation</p>
                                    <p class="text-sm">Choose a user from the list to start chatting</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($selectedUser)
    <script>
        const messagesContainer = document.getElementById('messagesContainer');
        const messageForm = document.getElementById('messageForm');
        const messageInput = document.getElementById('messageInput');
        let lastMessageId = {{ $messages->max('id') ?? 0 }};
        const selectedUserId = {{ $selectedUser->id }};

        // Scroll to bottom on load
        messagesContainer.scrollTop = messagesContainer.scrollHeight;

        // Update badge on page load (messages are marked as read when page loads)
        setTimeout(() => {
            if (typeof updateUnreadMessagesCount === 'function') {
                updateUnreadMessagesCount();
            }
        }, 1000);

        // Send message
        messageForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const message = messageInput.value.trim();
            if (!message) return;

            const formData = new FormData(messageForm);
            
            try {
                const response = await fetch('{{ route("admin.messages.send") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                
                if (data.success) {
                    messageInput.value = '';
                    addMessageToChat(data.message);
                    lastMessageId = data.message.id;
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                    // Update badge after sending
                    if (typeof updateUnreadMessagesCount === 'function') {
                        updateUnreadMessagesCount();
                    }
                }
            } catch (error) {
                console.error('Error sending message:', error);
            }
        });

        // Add message to chat
        function addMessageToChat(message) {
            const isSender = message.sender_id === {{ Auth::id() }};
            const messageDiv = document.createElement('div');
            messageDiv.className = `flex ${isSender ? 'justify-end' : 'justify-start'}`;
            
            const date = new Date(message.created_at);
            const formattedDate = date.toLocaleDateString('en-US', { 
                month: 'short', 
                day: 'numeric', 
                year: 'numeric',
                hour: 'numeric',
                minute: '2-digit'
            });

            messageDiv.innerHTML = `
                <div class="max-w-md">
                    <div class="flex items-start space-x-2 ${isSender ? 'flex-row-reverse space-x-reverse' : ''}">
                        <div class="w-8 h-8 rounded-full ${isSender ? 'bg-[#93BFC7]' : 'bg-[#5394D0]'} flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-user text-white text-xs"></i>
                        </div>
                        <div class="flex flex-col ${isSender ? 'items-end' : 'items-start'}">
                            <div class="px-4 py-2 rounded-lg ${isSender ? 'bg-[#93BFC7] text-white' : 'bg-white text-gray-800 border border-gray-200'}">
                                <p class="text-sm">${message.message}</p>
                            </div>
                            <span class="text-xs text-gray-500 mt-1 px-2">${formattedDate}</span>
                        </div>
                    </div>
                </div>
            `;
            
            messagesContainer.appendChild(messageDiv);
        }

        // Poll for new messages
        setInterval(async function() {
            try {
                const response = await fetch(`{{ route("admin.messages.get") }}?last_message_id=${lastMessageId}&other_user_id=${selectedUserId}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();
                
                if (data.messages && data.messages.length > 0) {
                    data.messages.forEach(message => {
                        addMessageToChat(message);
                        lastMessageId = Math.max(lastMessageId, message.id);
                    });
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                    // Update badge when new messages arrive
                    if (typeof updateUnreadMessagesCount === 'function') {
                        updateUnreadMessagesCount();
                    }
                }
            } catch (error) {
                console.error('Error fetching messages:', error);
            }
        }, 2000); // Poll every 2 seconds

        // Update sidebar badge when messages are read
        if (typeof updateUnreadMessagesCount === 'function') {
            updateUnreadMessagesCount();
        }
    </script>
    @endif

</body>
</html>

