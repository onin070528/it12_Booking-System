<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - RJ's Event Styling</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="font-sans bg-[#ECF4E8]">

    <div class="flex">
        <!-- Sidebar -->
        @include('layouts.sidebar')

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
                            Chat with our support team
                        </p>
                    </div>
                </div>
            </div>

            <!-- Chat Container -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden" style="height: calc(100vh - 200px);">
                <div class="flex flex-col h-full">
                    <!-- Chat Header -->
                    <div class="bg-[#93BFC7] px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center mr-3">
                                <i class="fas fa-user-shield text-[#93BFC7] text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-white font-bold text-lg">{{ $admin->name }}</h3>
                                <p class="text-white text-sm opacity-90">Admin Support</p>
                            </div>
                        </div>
                    </div>

                    <!-- Messages Area -->
                    <div id="messagesContainer" class="flex-1 overflow-y-auto p-6 bg-gray-50 space-y-4">
                        @foreach($messages as $message)
                            <div class="flex {{ $message->sender_id === Auth::id() ? 'justify-end' : 'justify-start' }}">
                                <div class="max-w-md">
                                    <div class="flex items-start space-x-2 {{ $message->sender_id === Auth::id() ? 'flex-row-reverse space-x-reverse' : '' }}">
                                        <div class="w-8 h-8 rounded-full {{ $message->sender_id === Auth::id() ? 'bg-[#5394D0]' : 'bg-[#93BFC7]' }} flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-user text-white text-xs"></i>
                                        </div>
                                        <div class="flex flex-col {{ $message->sender_id === Auth::id() ? 'items-end' : 'items-start' }}">
                                            <div class="px-4 py-2 rounded-lg {{ $message->sender_id === Auth::id() ? 'bg-[#5394D0] text-white' : 'bg-white text-gray-800 border border-gray-200' }}">
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
                            <input type="hidden" name="receiver_id" value="{{ $admin->user_id }}">
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
                </div>
            </div>
        </div>
    </div>

    {{-- Pass PHP data to JavaScript via data attributes to avoid linter errors --}}
    <div id="chatConfig" 
         data-last-message-id="{{ $messages->max('message_id') ?? 0 }}"
         data-admin-id="{{ $admin->user_id }}"
         data-current-user-id="{{ Auth::user()->user_id }}"
         data-send-url="{{ route('messages.send') }}"
         data-get-url="{{ route('messages.get') }}"
         data-csrf-token="{{ csrf_token() }}"
         style="display: none;"></div>

    <script>
        // Get config from data attributes
        var chatConfig = document.getElementById('chatConfig');
        var messagesContainer = document.getElementById('messagesContainer');
        var messageForm = document.getElementById('messageForm');
        var messageInput = document.getElementById('messageInput');
        var lastMessageId = parseInt(chatConfig.dataset.lastMessageId) || 0;
        var adminId = parseInt(chatConfig.dataset.adminId);
        var currentUserId = parseInt(chatConfig.dataset.currentUserId);
        var sendUrl = chatConfig.dataset.sendUrl;
        var getUrl = chatConfig.dataset.getUrl;
        var csrfToken = chatConfig.dataset.csrfToken;

        // Scroll to bottom on load
        messagesContainer.scrollTop = messagesContainer.scrollHeight;

        // Update badge on page load (messages are marked as read when page loads)
        setTimeout(function() {
            if (typeof updateUnreadMessagesCount === 'function') {
                updateUnreadMessagesCount();
            }
        }, 1000);

        // Send message
        messageForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            var message = messageInput.value.trim();
            if (!message) return;

            var formData = new FormData(messageForm);
            
            try {
                var response = await fetch(sendUrl, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                var data = await response.json();
                
                if (data.success) {
                    messageInput.value = '';
                    addMessageToChat(data.message);
                    lastMessageId = data.message.message_id;
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
            var isSender = message.sender_id === currentUserId;
            var messageDiv = document.createElement('div');
            messageDiv.className = 'flex ' + (isSender ? 'justify-end' : 'justify-start');
            
            var date = new Date(message.created_at);
            var formattedDate = date.toLocaleDateString('en-US', { 
                month: 'short', 
                day: 'numeric', 
                year: 'numeric',
                hour: 'numeric',
                minute: '2-digit'
            });

            var flexDirection = isSender ? 'flex-row-reverse space-x-reverse' : '';
            var avatarBg = isSender ? 'bg-[#5394D0]' : 'bg-[#93BFC7]';
            var flexAlign = isSender ? 'items-end' : 'items-start';
            var bubbleStyle = isSender ? 'bg-[#5394D0] text-white' : 'bg-white text-gray-800 border border-gray-200';

            messageDiv.innerHTML = '<div class="max-w-md">' +
                '<div class="flex items-start space-x-2 ' + flexDirection + '">' +
                    '<div class="w-8 h-8 rounded-full ' + avatarBg + ' flex items-center justify-center flex-shrink-0">' +
                        '<i class="fas fa-user text-white text-xs"></i>' +
                    '</div>' +
                    '<div class="flex flex-col ' + flexAlign + '">' +
                        '<div class="px-4 py-2 rounded-lg ' + bubbleStyle + '">' +
                            '<p class="text-sm">' + message.message + '</p>' +
                        '</div>' +
                        '<span class="text-xs text-gray-500 mt-1 px-2">' + formattedDate + '</span>' +
                    '</div>' +
                '</div>' +
            '</div>';
            
            messagesContainer.appendChild(messageDiv);
        }

        // Poll for new messages
        setInterval(async function() {
            try {
                var response = await fetch(getUrl + '?last_message_id=' + lastMessageId + '&other_user_id=' + adminId, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                var data = await response.json();
                
                if (data.messages && data.messages.length > 0) {
                    data.messages.forEach(function(message) {
                        addMessageToChat(message);
                        lastMessageId = Math.max(lastMessageId, message.message_id);
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

</body>
</html>

