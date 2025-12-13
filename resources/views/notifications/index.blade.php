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
        @if(request()->routeIs('admin.notifications.*'))
            @include('admin.AdminLayouts.AdminSidebar')
        @else
            @include('layouts.sidebar')
        @endif

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
                        <button 
                            type="button"
                            id="markAllAsReadBtn"
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
                        @php
                            $isBookingNotification = str_starts_with($notification->type, 'booking_');
                            $isUnread = !$notification->read;
                            $isUser = !request()->routeIs('admin.notifications.*');
                        @endphp
                        <div 
                            @if($isBookingNotification && $isUnread && $isUser)
                                data-notification-id="{{ $notification->id }}"
                                data-notification-type="{{ $notification->type }}"
                                class="booking-notification-item bg-[#F9FAFB] rounded-lg border border-gray-200 p-4 flex items-start gap-4 hover:bg-[#F1F5F9] transition cursor-pointer {{ $notification->read ? 'opacity-75' : '' }}"
                            @else
                                class="bg-[#F9FAFB] rounded-lg border border-gray-200 p-4 flex items-start gap-4 hover:bg-[#F1F5F9] transition {{ $notification->read ? 'opacity-75' : '' }}"
                            @endif
                        >
                            <div class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0
                                @if($notification->type == 'booking_created') bg-blue-500
                                @elseif($notification->type == 'booking_confirmed') bg-green-500
                                @elseif($notification->type == 'booking_ready_for_payment') bg-orange-500
                                @else bg-[#93BFC7]
                                @endif">
                                @if($notification->type == 'booking_created')
                                    <i class="fas fa-calendar-plus text-white text-lg"></i>
                                @elseif($notification->type == 'booking_confirmed')
                                    <i class="fas fa-check-circle text-white text-lg"></i>
                                @elseif($notification->type == 'booking_ready_for_payment')
                                    <i class="fas fa-credit-card text-white text-lg"></i>
                                @else
                                    <i class="fas fa-bell text-white text-lg"></i>
                                @endif
                            </div>
                            <div class="flex-1">
                                <p class="text-gray-800 font-semibold {{ !$notification->read ? 'font-bold' : '' }} text-medium">
                                    {{ $notification->message }}
                                </p>
                                <p class="text-gray-500 text-sm mt-1">
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>
                            </div>
                            @if(!$notification->read && (!$isBookingNotification || !$isUser))
                                <button 
                                    type="button"
                                    data-mark-read-id="{{ $notification->id }}"
                                    class="mark-read-btn text-blue-500 hover:text-blue-700 transition">
                                    <i class="fas fa-check fa-2x"></i>
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

    <!-- Hidden configuration container -->
    <div id="notification-config" 
         data-is-admin="{{ request()->routeIs('admin.notifications.*') ? 'true' : 'false' }}"
         data-admin-read-route-base="{{ route('admin.notifications.read', ['notification' => '__NOTIFICATION_ID__']) }}"
         data-user-read-route-base="{{ route('notifications.read', ['notification' => '__NOTIFICATION_ID__']) }}"
         data-admin-read-all-route="{{ route('admin.notifications.read-all') }}"
         data-user-read-all-route="{{ route('notifications.read-all') }}"
         data-payments-index-route="{{ route('payments.index') }}"
         data-home-route="{{ route('home') }}"
         data-csrf-token="{{ csrf_token() }}"
         style="display: none;"></div>

    <script>
        // Configuration - loaded from data attributes to avoid Blade syntax in JavaScript
        (function() {
            const configEl = document.getElementById('notification-config');
            if (!configEl) {
                console.error('Notification config element not found');
                return;
            }
            
            window.NOTIFICATION_CONFIG = {
                isAdmin: configEl.getAttribute('data-is-admin') === 'true',
                adminReadRouteBase: configEl.getAttribute('data-admin-read-route-base') || '',
                userReadRouteBase: configEl.getAttribute('data-user-read-route-base') || '',
                adminReadAllRoute: configEl.getAttribute('data-admin-read-all-route') || '',
                userReadAllRoute: configEl.getAttribute('data-user-read-all-route') || '',
                paymentsIndexRoute: configEl.getAttribute('data-payments-index-route') || '',
                homeRoute: configEl.getAttribute('data-home-route') || '',
                csrfToken: configEl.getAttribute('data-csrf-token') || ''
            };
        })();
        
        const IS_ADMIN = window.NOTIFICATION_CONFIG.isAdmin;
        const ADMIN_READ_ROUTE_BASE = window.NOTIFICATION_CONFIG.adminReadRouteBase;
        const USER_READ_ROUTE_BASE = window.NOTIFICATION_CONFIG.userReadRouteBase;
        const ADMIN_READ_ALL_ROUTE = window.NOTIFICATION_CONFIG.adminReadAllRoute;
        const USER_READ_ALL_ROUTE = window.NOTIFICATION_CONFIG.userReadAllRoute;
        const PAYMENTS_INDEX_ROUTE = window.NOTIFICATION_CONFIG.paymentsIndexRoute;
        const HOME_ROUTE = window.NOTIFICATION_CONFIG.homeRoute;
        const CSRF_TOKEN = window.NOTIFICATION_CONFIG.csrfToken;

        function markAsRead(notificationId) {
            const readRoute = IS_ADMIN ? ADMIN_READ_ROUTE_BASE : USER_READ_ROUTE_BASE;
            const url = readRoute.replace('__NOTIFICATION_ID__', notificationId);
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
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

        function handleBookingNotification(notificationId, notificationType) {
            // Mark notification as read first
            const readRoute = IS_ADMIN ? ADMIN_READ_ROUTE_BASE : USER_READ_ROUTE_BASE;
            const url = readRoute.replace('__NOTIFICATION_ID__', notificationId);
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Redirect to home page for booking notifications (user only)
                    if (!IS_ADMIN) {
                        // If it's a payment notification, redirect to payments
                        if (notificationType === 'booking_ready_for_payment') {
                            window.location.href = PAYMENTS_INDEX_ROUTE;
                        } else {
                            // Store flag to show toast on home page
                            sessionStorage.setItem('showCommunicationToast', 'true');
                            window.location.href = HOME_ROUTE;
                        }
                    } else {
                        location.reload();
                    }
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

            const url = IS_ADMIN ? ADMIN_READ_ALL_ROUTE : USER_READ_ALL_ROUTE;
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
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

        // Event delegation for notification items
        document.addEventListener('DOMContentLoaded', function() {
            // Handle booking notification clicks
            document.addEventListener('click', function(e) {
                const notificationItem = e.target.closest('.booking-notification-item');
                if (notificationItem) {
                    const notificationId = parseInt(notificationItem.getAttribute('data-notification-id'));
                    const notificationType = notificationItem.getAttribute('data-notification-type');
                    if (notificationId && notificationType) {
                        handleBookingNotification(notificationId, notificationType);
                    }
                }
            });

            // Handle mark as read button clicks
            document.addEventListener('click', function(e) {
                const markReadBtn = e.target.closest('.mark-read-btn');
                if (markReadBtn) {
                    const notificationId = parseInt(markReadBtn.getAttribute('data-mark-read-id'));
                    if (notificationId) {
                        markAsRead(notificationId);
                    }
                }
            });

            // Handle mark all as read button
            const markAllBtn = document.getElementById('markAllAsReadBtn');
            if (markAllBtn) {
                markAllBtn.addEventListener('click', markAllAsRead);
            }
        });
    </script>

</body>

</html>
