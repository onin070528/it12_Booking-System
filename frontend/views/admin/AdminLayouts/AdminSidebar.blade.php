<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.tailwindcss.com"></script>

    <title>RJ's Event Styling Dashboard</title>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="bg-gray-100">

    <div id="adminSidebar" class="hidden md:flex fixed top-0 left-0 w-64 h-screen bg-[#E3F4E4] py-6 flex-col shadow-[4px_0_20px_rgba(0,0,0,0.08)] z-50">

        <!-- Logo + Title -->
        <div class="flex items-center justify-center gap-3 px-4 mb-10">
            <img src="/img/rj_logo.jpg" alt="RJ Logo" class="h-16 w-16 object-contain rounded-md">

            <div class="flex flex-col leading-tight">
                <h1 class="text-[30px] font-bold leading-none" style="color: #93BFC7;">RJ's</h1>
                <h2 class="text-lg font-semibold tracking-wide" style="color: #93BFC7;">ADMIN PANEL</h2>
            </div>
        </div>

        <!-- Menu -->
        <ul class="flex flex-col px-4 space-y-3 flex-1">
            <!-- Admin Dashboard -->
            <li>
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center {{ request()->routeIs('admin.dashboard') ? 'bg-[#93BFC7] text-white' : 'text-gray-700 hover:bg-gray-100' }} px-4 py-3 rounded-lg font-medium transition">
                    <i class="fas fa-th-large mr-3"></i> Admin Dashboard
                </a>
            </li>

            <!-- Bookings Management -->
            <li>
                <a href="{{ route('admin.bookings.index') }}"
                   class="flex items-center justify-between {{ request()->routeIs('admin.AdminBooking') || request()->routeIs('admin.bookings.*') || request()->routeIs('admin.booking.show') ? 'bg-[#93BFC7] text-white' : 'text-gray-700 hover:bg-gray-100' }} px-4 py-3 rounded-lg font-medium transition relative">
                    <div class="flex items-center">
                        <i class="fas fa-calendar-check mr-3"></i> Bookings Management
                    </div>
                    <span id="incompleteBookingsBadge" class="hidden bg-red-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center min-w-[24px] animate-pulse"></span>
                </a>
            </li>

            <!-- Walk-in Booking -->
            <li>
                <a href="{{ route('admin.booking.walk-in') }}"
                   class="flex items-center {{ request()->routeIs('admin.booking.walk-in') ? 'bg-[#93BFC7] text-white' : 'text-gray-700 hover:bg-gray-100' }} px-4 py-3 rounded-lg font-medium transition">
                    <i class="fas fa-user-plus mr-3"></i> Walk-in Booking
                </a>
            </li>

            <!-- Calendar of Events -->
            <li>
                <a href="{{ route('admin.calendar') }}"
                   class="flex items-center {{ request()->routeIs('admin.calendar') ? 'bg-[#93BFC7] text-white' : 'text-gray-700 hover:bg-gray-100' }} px-4 py-3 rounded-lg font-medium transition">
                    <i class="fas fa-calendar mr-3"></i> Calendar of Events
                </a>
            </li>

            <!-- Payments Management -->
            <li>
                <a href="{{ route('admin.payments.index') }}"
                   class="flex items-center {{ request()->routeIs('admin.AdminPayment') || request()->routeIs('admin.payments.*') ? 'bg-[#93BFC7] text-white' : 'text-gray-700 hover:bg-gray-100' }} px-4 py-3 rounded-lg font-medium transition">
                    <i class="fas fa-credit-card mr-3"></i> Payments Management
                </a>
            </li>

            <!-- Reports -->
            <li>
                <a href="{{ route('admin.reports.index') }}"
                   class="flex items-center {{ request()->routeIs('admin.AdminReports') || request()->routeIs('admin.reports.*') ? 'bg-[#93BFC7] text-white' : 'text-gray-700 hover:bg-gray-100' }} px-4 py-3 rounded-lg font-medium transition">
                    <i class="fas fa-chart-bar mr-3"></i> Reports
                </a>
            </li>

            <!-- Inventory -->
            <li>
                <a href="{{ route('admin.inventory.index') }}"
                class="flex items-center {{ request()->routeIs('admin.AdminInventory') || request()->routeIs('admin.inventory.*') ? 'bg-[#93BFC7] text-white' : 'text-gray-700 hover:bg-gray-100' }} px-4 py-3 rounded-lg font-medium transition">
                    <i class="fas fa-boxes mr-3"></i> Inventory
                </a>
            </li>

            <!-- Notification -->
            <li>
                <a href="{{ route('admin.notifications.index') }}"
                   class="flex items-center justify-between {{ request()->routeIs('admin.notifications.*') ? 'bg-[#93BFC7] text-white' : 'text-gray-700 hover:bg-gray-100' }} px-4 py-3 rounded-lg font-medium transition relative">
                    <div class="flex items-center">
                        <i class="fas fa-bell mr-3"></i> Notification
                    </div>
                    <span id="unreadNotificationsBadge" class="hidden bg-red-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center min-w-[24px] animate-pulse"></span>
                </a>
            </li>

            <!-- Messages -->
            <li>
                <a href="{{ route('admin.messages.index') }}"
                   class="flex items-center justify-between {{ request()->routeIs('admin.messages.*') ? 'bg-[#93BFC7] text-white' : 'text-gray-700 hover:bg-gray-100' }} px-4 py-3 rounded-lg font-medium transition relative">
                    <div class="flex items-center">
                        <i class="fas fa-comments mr-3"></i> Messages
                    </div>
                    <span id="unreadMessagesBadge" class="hidden bg-red-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center min-w-[24px] animate-pulse"></span>
                </a>
            </li>

            <!-- Users Management -->
            <li>
                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center justify-between {{ request()->routeIs('admin.users.*') ? 'bg-[#93BFC7] text-white' : 'text-gray-700 hover:bg-gray-100' }} px-4 py-3 rounded-lg font-medium transition relative">
                    <div class="flex items-center">
                        <i class="fas fa-users-cog mr-3"></i> Users Management
                    </div>
                    <span id="pendingUsersBadge" class="hidden bg-orange-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center min-w-[24px] animate-pulse"></span>
                </a>
            </li>

        </ul>

            <div class="mt-auto px-4 pb-6 pt-6">
                <form action="{{ route('logout') }}" method="POST" id="logout-form">
                    @csrf
                    <a class="flex items-center text-gray-700 px-4 py-3 rounded-lg hover:bg-gray-100 transition font-medium cursor-pointer"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt mr-3"></i> Logout
                    </a>
                </form>
            </div>


    </div>

    <script>
        // Update unread messages count for admin (make it globally accessible)
        window.updateUnreadMessagesCount = function() {
            fetch('{{ route("admin.messages.unread-count") }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                const badge = document.getElementById('unreadMessagesBadge');
                if (badge) {
                    if (data.count > 0) {
                        badge.textContent = data.count > 99 ? '99+' : data.count;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                }
            })
            .catch(error => console.error('Error fetching unread count:', error));
        };

        // Update on page load
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', updateUnreadMessagesCount);
        } else {
            updateUnreadMessagesCount();
        }

        // Update every 5 seconds
        setInterval(updateUnreadMessagesCount, 5000);

        // Update unread notifications count (make it globally accessible)
        window.updateUnreadNotificationsCount = function() {
            fetch('{{ route("admin.notifications.unread-count") }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                const badge = document.getElementById('unreadNotificationsBadge');
                if (badge) {
                    if (data.count > 0) {
                        badge.textContent = data.count > 99 ? '99+' : data.count;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                }
            })
            .catch(error => console.error('Error fetching unread notifications count:', error));
        };

        // Update on page load
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', updateUnreadNotificationsCount);
        } else {
            updateUnreadNotificationsCount();
        }

        // Update every 5 seconds
        setInterval(updateUnreadNotificationsCount, 5000);

        // Update incomplete bookings count (make it globally accessible)
        window.updateIncompleteBookingsCount = function() {
            fetch('{{ route("admin.bookings.incomplete-count") }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                const badge = document.getElementById('incompleteBookingsBadge');
                if (badge) {
                    if (data.count > 0) {
                        badge.textContent = data.count > 99 ? '99+' : data.count;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                }
            })
            .catch(error => console.error('Error fetching incomplete bookings count:', error));
        };

        // Update on page load
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', updateIncompleteBookingsCount);
        } else {
            updateIncompleteBookingsCount();
        }

        // Update every 5 seconds
        setInterval(updateIncompleteBookingsCount, 5000);

        // Update pending users count (make it globally accessible)
        window.updatePendingUsersCount = function() {
            fetch('{{ route("admin.users.pending-count") }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                const badge = document.getElementById('pendingUsersBadge');
                if (badge) {
                    if (data.count > 0) {
                        badge.textContent = data.count > 99 ? '99+' : data.count;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                }
            })
            .catch(error => console.error('Error fetching pending users count:', error));
        };

        // Update on page load
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', updatePendingUsersCount);
        } else {
            updatePendingUsersCount();
        }

        // Update every 10 seconds
        setInterval(updatePendingUsersCount, 10000);
    </script>

</body>
</html>
