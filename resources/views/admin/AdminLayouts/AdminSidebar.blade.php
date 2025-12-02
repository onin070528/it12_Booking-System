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

    <div class="fixed top-0 left-0 w-64 h-screen bg-[#E3F4E4] py-6 flex flex-col shadow-[4px_0_20px_rgba(0,0,0,0.08)] z-10">

        <!-- Logo + Title -->
        <div class="flex items-center justify-center gap-3 px-4 mb-10">
            <img src="/img/rj_logo.jpg" alt="RJ Logo" class="h-16 w-16 object-contain rounded-md">

            <div class="flex flex-col leading-tight">
                <h1 class="text-[30px] font-bold leading-none" style="color: #93BFC7;">RJ's</h1>
                <h2 class="text-lg font-semibold tracking-wide" style="color: #93BFC7;">ADMIN PANEL</h2>
            </div>
        </div>

        <!-- Menu -->
        <ul class="flex flex-col px-4 space-y-1">

            <!-- Admin Dashboard -->
            <li>
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center {{ request()->routeIs('admin.dashboard') ? 'bg-[#93BFC7] text-white' : 'text-gray-700 hover:bg-gray-100' }} px-4 py-3 rounded-lg font-medium transition">
                    <i class="fas fa-th-large mr-3"></i> Admin Dashboard
                </a>
            </li>

            <!-- Bookings Management -->
            <li>
                <a href="{{ route('admin.AdminBooking') }}"
                   class="flex items-center {{ request()->routeIs('admin.bookings.*') ? 'bg-[#93BFC7] text-white' : 'text-gray-700 hover:bg-gray-100' }} px-4 py-3 rounded-lg font-medium transition">
                    <i class="fas fa-calendar-check mr-3"></i> Bookings Management
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
                <a href="{{ route('admin.AdminPayment') }}"
                   class="flex items-center {{ request()->routeIs('admin.payments.*') ? 'bg-[#93BFC7] text-white' : 'text-gray-700 hover:bg-gray-100' }} px-4 py-3 rounded-lg font-medium transition">
                    <i class="fas fa-credit-card mr-3"></i> Payments Management
                </a>
            </li>

            <!-- Reports -->
            <li>
                <a href="{{ route('admin.AdminReports') }}"
                   class="flex items-center {{ request()->routeIs('admin.reports.*') ? 'bg-[#93BFC7] text-white' : 'text-gray-700 hover:bg-gray-100' }} px-4 py-3 rounded-lg font-medium transition">
                    <i class="fas fa-chart-bar mr-3"></i> Reports
                </a>
            </li>

            <!-- Inventory -->
            <li>
                <a href="{{ route('admin.AdminInventory') }}"
                class="flex items-center {{ request()->routeIs('admin.settings.*') ? 'bg-[#93BFC7] text-white' : 'text-gray-700 hover:bg-gray-100' }} px-4 py-3 rounded-lg font-medium transition">
                    <i class="fas fa-boxes mr-3"></i> Inventory
                </a>
            </li>

            <!-- Logout -->
            <li class="mt-auto pt-5 pb-2">
                <form action="{{ route('logout') }}" method="POST" id="logout-form">
                    @csrf
                    <a class="flex items-center text-gray-700 px-4 py-3 rounded-lg hover:bg-gray-100 transition font-medium cursor-pointer"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt mr-3"></i> Logout
                    </a>
                </form>
            </li>

        </ul>

    </div>

</body>
</html>
