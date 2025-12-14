<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - RJ's Event Styling</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Chart.js for visualizations -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="font-sans bg-[#ECF4E8]">

    <div class="flex">
        <!-- Admin Sidebar -->
        @include('admin.AdminLayouts.AdminSidebar')

        <!-- Main Content -->
        <div class="flex-1 min-h-screen px-6 py-6 ml-64">
            
        <!-- Header -->
            @include('admin.layouts.header')

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Users Card -->
                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium mb-1">Total Users</p>
                            <h3 class="text-3xl font-bold" style="color: #93BFC7;">{{ $totalUsers }}</h3>
                        </div>
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-users text-blue-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Bookings Card -->
                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium mb-1">Total Bookings</p>
                            <h3 class="text-3xl font-bold" style="color: #93BFC7;">{{ $totalBookings }}</h3>
                        </div>
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-calendar-check text-green-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Revenue Card -->
                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium mb-1">Total Revenue</p>
                            <h3 class="text-2xl font-bold" style="color: #93BFC7;">₱{{ number_format($totalRevenue, 2) }}</h3>
                        </div>
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-money-bill-wave text-green-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Pending Payments Card -->
                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium mb-1">Pending Payments</p>
                            <h3 class="text-2xl font-bold text-yellow-600">₱{{ number_format($pendingPayments, 2) }}</h3>
                        </div>
                        <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-hourglass-half text-yellow-600 text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Status Cards -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
                <div class="bg-white rounded-xl shadow-lg p-4 hover:shadow-xl transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-xs font-medium mb-1">Pending</p>
                            <h3 class="text-2xl font-bold text-yellow-600">{{ $pendingBookings }}</h3>
                        </div>
                        <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-clock text-yellow-600 text-lg"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-4 hover:shadow-xl transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-xs font-medium mb-1">Confirmed</p>
                            <h3 class="text-2xl font-bold text-green-600">{{ $confirmedBookings }}</h3>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600 text-lg"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-4 hover:shadow-xl transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-xs font-medium mb-1">Approved</p>
                            <h3 class="text-2xl font-bold text-blue-600">{{ $approvedBookings }}</h3>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-check-double text-blue-600 text-lg"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-4 hover:shadow-xl transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-xs font-medium mb-1">Completed</p>
                            <h3 class="text-2xl font-bold text-purple-600">{{ $completedBookings }}</h3>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-trophy text-purple-600 text-lg"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-4 hover:shadow-xl transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-xs font-medium mb-1">Cancelled</p>
                            <h3 class="text-2xl font-bold text-red-600">{{ $cancelledBookings }}</h3>
                        </div>
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-times-circle text-red-600 text-lg"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-4 hover:shadow-xl transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-xs font-medium mb-1">Downpayments</p>
                            <h3 class="text-xl font-bold text-blue-600">₱{{ number_format($downpaymentsReceived, 2) }}</h3>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-wallet text-blue-600 text-lg"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Bookings by Event Type Chart -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold mb-4" style="color: #93BFC7;">
                        <i class="fas fa-chart-pie mr-2"></i>Bookings by Event Type
                    </h3>
                    <div style="height: 300px; position: relative;">
                        <canvas id="bookingsByTypeChart"></canvas>
                    </div>
                </div>

                <!-- Bookings by Status Chart -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold mb-4" style="color: #93BFC7;">
                        <i class="fas fa-chart-doughnut mr-2"></i>Bookings by Status
                    </h3>
                    <div style="height: 300px; position: relative;">
                        <canvas id="bookingsByStatusChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Monthly Revenue Trend -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                <h3 class="text-xl font-bold mb-4" style="color: #93BFC7;">
                    <i class="fas fa-chart-line mr-2"></i>Monthly Revenue Trend (Last 6 Months)
                </h3>
                <div style="height: 400px; position: relative;">
                    <canvas id="monthlyRevenueChart"></canvas>
                </div>
            </div>

            <!-- Recent Users Table -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-xl font-bold" style="color: #93BFC7;">
                        <i class="fas fa-list mr-2"></i>Recent Users
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($recentUsers as $user)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-[#93BFC7] rounded-full flex items-center justify-center text-white font-semibold">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $user->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button class="text-[#93BFC7] hover:text-[#7eaab1] mr-3">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                    No users found
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-8 bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold mb-4" style="color: #93BFC7;">
                    <i class="fas fa-bolt mr-2"></i>Quick Actions
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('admin.bookings.index') }}" class="px-6 py-3 bg-[#93BFC7] text-white rounded-lg hover:bg-[#7eaab1] transition font-semibold text-center">
                        <i class="fas fa-calendar-check mr-2"></i>Manage Bookings
                    </a>
                    <a href="{{ route('admin.payments.index') }}" class="px-6 py-3 bg-[#5394D0] text-white rounded-lg hover:bg-[#3e78a9] transition font-semibold text-center">
                        <i class="fas fa-money-bill-wave mr-2"></i>View Payments
                    </a>
                    <a href="{{ route('admin.reports.index') }}" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold text-center">
                        <i class="fas fa-chart-bar mr-2"></i>View Reports
                    </a>
                </div>
            </div>
        </div>
    </div>

    @php
        $bookingsByTypeLabels = $bookingsByType->map(function($item) {
            return ucfirst($item->event_type);
        })->values()->toArray();
        
        $bookingsByTypeData = $bookingsByType->pluck('count')->toArray();
        
        $bookingsByStatusLabels = $bookingsByStatus->map(function($item) {
            return ucfirst($item->status);
        })->values()->toArray();
        
        $bookingsByStatusData = $bookingsByStatus->pluck('count')->toArray();
        
        $monthlyRevenueLabels = $monthlyRevenue->pluck('month')->toArray();
        $monthlyRevenueData = $monthlyRevenue->pluck('total')->toArray();
    @endphp

    <!-- Hidden data container for chart data -->
    <div id="chart-data" 
         data-bookings-type-labels="{{ json_encode($bookingsByTypeLabels) }}"
         data-bookings-type-data="{{ json_encode($bookingsByTypeData) }}"
         data-bookings-status-labels="{{ json_encode($bookingsByStatusLabels) }}"
         data-bookings-status-data="{{ json_encode($bookingsByStatusData) }}"
         data-monthly-revenue-labels="{{ json_encode($monthlyRevenueLabels) }}"
         data-monthly-revenue-data="{{ json_encode($monthlyRevenueData) }}"
         style="display: none;"></div>

    <script>
        // Prepare data for charts
        const chartDataEl = document.getElementById('chart-data');
        const bookingsByTypeLabels = JSON.parse(chartDataEl.getAttribute('data-bookings-type-labels'));
        const bookingsByTypeData = JSON.parse(chartDataEl.getAttribute('data-bookings-type-data'));
        const bookingsByStatusLabels = JSON.parse(chartDataEl.getAttribute('data-bookings-status-labels'));
        const bookingsByStatusData = JSON.parse(chartDataEl.getAttribute('data-bookings-status-data'));
        const monthlyRevenueLabels = JSON.parse(chartDataEl.getAttribute('data-monthly-revenue-labels'));
        const monthlyRevenueData = JSON.parse(chartDataEl.getAttribute('data-monthly-revenue-data'));

        // Bookings by Event Type Chart
        const bookingsByTypeCtx = document.getElementById('bookingsByTypeChart');
        if (bookingsByTypeCtx) {
            new Chart(bookingsByTypeCtx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: bookingsByTypeLabels,
                    datasets: [{
                        data: bookingsByTypeData,
                        backgroundColor: [
                            '#93BFC7',
                            '#7eaab1',
                            '#6b9ba3',
                            '#5a8c95',
                            '#497d87',
                            '#3a6e79'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        // Bookings by Status Chart
        const bookingsByStatusCtx = document.getElementById('bookingsByStatusChart');
        if (bookingsByStatusCtx) {
            new Chart(bookingsByStatusCtx.getContext('2d'), {
                type: 'pie',
                data: {
                    labels: bookingsByStatusLabels,
                    datasets: [{
                        data: bookingsByStatusData,
                        backgroundColor: [
                            '#fbbf24', // yellow for pending
                            '#10b981', // green for confirmed
                            '#3b82f6', // blue for approved
                            '#8b5cf6', // purple for completed
                            '#ef4444'  // red for cancelled
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        // Monthly Revenue Trend Chart
        const monthlyRevenueCtx = document.getElementById('monthlyRevenueChart');
        if (monthlyRevenueCtx) {
            new Chart(monthlyRevenueCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: monthlyRevenueLabels,
                    datasets: [{
                        label: 'Revenue (₱)',
                        data: monthlyRevenueData,
                        borderColor: '#93BFC7',
                        backgroundColor: 'rgba(147, 191, 199, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '₱' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        }
    </script>

</body>
</html>
