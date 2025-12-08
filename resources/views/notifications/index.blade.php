<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - RJ's Event Styling</title>

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
        <div class="flex-1 min-h-screen px-6 py-10 ml-64">
            <!-- Header -->
            <div class="bg-white shadow-md rounded-xl px-6 py-4 flex justify-between items-center mb-8">
                <div class="flex items-center space-x-2">
                    <div>
                        <h2 class="text-3xl font-bold" style="color: #93BFC7;">
                            <i class="fas fa-bell mr-2"></i>Notifications
                        </h2>
                        <p class="text-1xl font-semibold" style="color: #93BFC7;">
                            Stay updated with your bookings
                        </p>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    @if($notifications->where('read', false)->count() > 0)
                        <button onclick="markAllAsRead()" 
                            class="bg-[#93BFC7] text-white px-4 py-2 rounded-lg hover:bg-[#7aa8b0] transition">
                            <i class="fas fa-check-double mr-2"></i>Mark All as Read
                        </button>
                    @endif
                </div>
            </div>

            <!-- Notifications Panel -->
            <div class="bg-[#93BFC7] rounded-xl shadow-lg p-6">
                <h2 class="text-3xl font-bold mb-4 text-white">Your Notifications</h2>

                <div class="space-y-4">
                    @forelse($notifications as $notification)
                        <div class="bg-[#F9FAFB] rounded-lg border border-gray-200 p-4 flex items-start gap-4 hover:bg-[#F1F5F9] transition {{ $notification->read ? 'opacity-75' : '' }}">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0
                                @if($notification->type == 'booking_created') bg-blue-500
                                @elseif($notification->type == 'booking_confirmed') bg-green-500
                                @else bg-[#93BFC7]
                                @endif">
                                @if($notification->type == 'booking_created')
                                    <i class="fas fa-calendar-plus text-white text-lg"></i>
                                @elseif($notification->type == 'booking_confirmed')
                                    <i class="fas fa-check-circle text-white text-lg"></i>
                                @else
                                    <i class="fas fa-bell text-white text-lg"></i>
                                @endif
                            </div>
                            <div class="flex-1">
                                <p class="text-gray-800 font-semibold {{ !$notification->read ? 'font-bold' : '' }}">
                                    {{ $notification->message }}
                                </p>
                                <p class="text-gray-500 text-sm mt-1">
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>
                            </div>
                            @if(!$notification->read)
                                <button onclick="markAsRead({{ $notification->id }})" 
                                    class="text-blue-500 hover:text-blue-700 transition">
                                    <i class="fas fa-check"></i>
                                </button>
                            @endif
                        </div>
                    @empty
                        <div class="bg-[#F9FAFB] rounded-lg border border-gray-200 p-8 text-center">
                            <i class="fas fa-bell-slash text-4xl text-gray-400 mb-4"></i>
                            <p class="text-gray-600 text-lg">No notifications yet</p>
                            <p class="text-gray-500 text-sm mt-2">You'll see notifications here when there are updates about your bookings.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($notifications->hasPages())
                    <div class="mt-6">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function markAsRead(notificationId) {
            fetch(`/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        function markAllAsRead() {
            if (!confirm('Mark all notifications as read?')) {
                return;
            }

            fetch('/notifications/read-all', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    </script>

</body>

</html>
