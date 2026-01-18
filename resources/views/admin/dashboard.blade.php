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
        <div class="flex-1 min-h-screen px-4 py-6 ml-0 md:ml-64 md:px-6">
            
        <!-- Header -->
            @include('admin.layouts.header')

            <!-- Pending Users Alert -->
            @if($pendingUsersCount > 0)
            <div class="mb-6 bg-orange-50 border-l-4 border-orange-500 p-4 rounded-r-lg shadow-sm">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-user-clock text-orange-500 text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-orange-800">Pending Account Approvals</h3>
                            <p class="text-orange-700">You have <strong>{{ $pendingUsersCount }}</strong> user{{ $pendingUsersCount > 1 ? 's' : '' }} waiting for account approval.</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.users.index') }}" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg font-medium transition flex items-center gap-2">
                        <i class="fas fa-user-check"></i>
                        Review Now
                    </a>
                </div>
            </div>
            @endif

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 mb-8">

    <!-- Total Users -->
    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium mb-1">Total Users</p>
                <h3 class="text-3xl font-bold text-[#93BFC7]">{{ $totalUsers }}</h3>
            </div>
            <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-users text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Bookings -->
    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium mb-1">Total Bookings</p>
                <h3 class="text-3xl font-bold text-[#93BFC7]">{{ $totalBookings }}</h3>
            </div>
            <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-calendar-check text-green-600 text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Profit -->
    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium mb-1">Total Profit</p>
                <h3 class="text-2xl font-bold text-[#93BFC7]">
                    ₱{{ number_format($totalProfit, 2) }}
                </h3>
                <p class="text-xs text-gray-400 mt-1">From completed events</p>
            </div>
            <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-money-bill-wave text-green-600 text-xl"></i>
            </div>
        </div>
    </div>

    <!-- This Month's Revenue -->
    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium mb-1">This Month's Revenue</p>
                <h3 class="text-2xl font-bold text-blue-600">
                    ₱{{ number_format($thisMonthRevenue, 2) }}
                </h3>
                <p class="text-xs text-gray-400 mt-1">{{ now()->format('F Y') }}</p>
            </div>
            <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-calendar-alt text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>

</div>

            

          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

    <!-- LEFT: STOCK STATUS -->
    <div class="bg-white rounded-2xl shadow-xl p-6">
        <h2 class="text-lg font-semibold text-gray-700 mb-5">Stock Status</h2>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <!-- Card -->
            <div class="bg-gray-50 border border-gray-100 rounded-xl p-4 
                        shadow-xl shadow-black/5 
                        hover:shadow-2xl hover:-translate-y-0.5 
                        transition-all duration-300 flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">In Stock</p>
                    <h3 class="text-2xl font-bold text-green-600">{{ $inStockCount }}</h3>
                </div>
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-check text-green-600"></i>
                </div>
            </div>

            <div class="bg-gray-50 border border-gray-100 rounded-xl p-4 
                        shadow-xl shadow-black/5 
                        hover:shadow-2xl hover:-translate-y-0.5 
                        transition-all duration-300 flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Low Stock</p>
                    <h3 class="text-2xl font-bold text-yellow-600">{{ $lowStockCount }}</h3>
                </div>
                <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                </div>
            </div>

            <div class="bg-gray-50 border border-gray-100 rounded-xl p-4 
                        shadow-xl shadow-black/5 
                        hover:shadow-2xl hover:-translate-y-0.5 
                        transition-all duration-300 flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Out of Stock</p>
                    <h3 class="text-2xl font-bold text-red-600">{{ $outOfStockCount }}</h3>
                </div>
                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-box-open text-red-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT: BOOKING STATUS -->
    <div class="bg-white rounded-2xl shadow-xl p-6">
        <h2 class="text-lg font-semibold text-gray-700 mb-5">Booking Status</h2>

        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
            <!-- Card -->
            <div class="bg-gray-50 border border-gray-100 rounded-xl p-4 
                        shadow-xl shadow-black/5 
                        hover:shadow-2xl hover:-translate-y-0.5 
                        transition-all duration-300 flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-xs">Pending</p>
                    <h3 class="text-xl font-bold text-yellow-600">{{ $pendingBookings }}</h3>
                </div>
                <i class="fas fa-clock text-yellow-500"></i>
            </div>

            <div class="bg-gray-50 border border-gray-100 rounded-xl p-4 
                        shadow-xl shadow-black/5 
                        hover:shadow-2xl hover:-translate-y-0.5 
                        transition-all duration-300 flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-xs">Confirmed</p>
                    <h3 class="text-xl font-bold text-green-600">{{ $confirmedBookings }}</h3>
                </div>
                <i class="fas fa-check-circle text-green-500"></i>
            </div>

            <div class="bg-gray-50 border border-gray-100 rounded-xl p-4 
                        shadow-xl shadow-black/5 
                        hover:shadow-2xl hover:-translate-y-0.5 
                        transition-all duration-300 flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-xs">Approved</p>
                    <h3 class="text-xl font-bold text-blue-600">{{ $approvedBookings }}</h3>
                </div>
                <i class="fas fa-check-double text-blue-500"></i>
            </div>

            <div class="bg-gray-50 border border-gray-100 rounded-xl p-4 
                        shadow-xl shadow-black/5 
                        hover:shadow-2xl hover:-translate-y-0.5 
                        transition-all duration-300 flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-xs">Cancelled</p>
                    <h3 class="text-xl font-bold text-red-600">{{ $cancelledBookings }}</h3>
                </div>
                <i class="fas fa-times-circle text-red-500"></i>
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
                    <h3 class="text-xl font-bold flex items-center" style="color: #93BFC7;">
                        <i class="fas fa-list mr-2"></i>
                        <span>Recent Users</span>
                        <span class="ml-3 inline-flex items-center justify-center px-2 py-0.5 rounded-full bg-gray-100 text-sm text-gray-700">{{ $recentUsers->count() }}</span>
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
                                    <div class="flex items-center space-x-3">
                                        <button type="button" data-user-id="{{ $user->user_id }}" class="view-user-btn text-[#93BFC7] hover:text-[#7eaab1]">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <!-- archive removed -->
                                    </div>
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
        // Helper: update Chart.js chart with new labels/data
        function updateChart(chart, labels, data) {
            if (!chart) return;
            chart.data.labels = labels;
            if (chart.data.datasets && chart.data.datasets[0]) {
                chart.data.datasets[0].data = data;
            }
            chart.update();
        }
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
            window.bookingsByTypeChart = new Chart(bookingsByTypeCtx.getContext('2d'), {
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
                    animation: {
                        animateRotate: true,
                        animateScale: true,
                        duration: 1500,
                        easing: 'easeOutQuart'
                    },
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        // Archive removed from dashboard

        // Restore/archiving UI removed from dashboard

        // Bookings by Status Chart
        const bookingsByStatusCtx = document.getElementById('bookingsByStatusChart');
        if (bookingsByStatusCtx) {
            window.bookingsByStatusChart = new Chart(bookingsByStatusCtx.getContext('2d'), {
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
                    animation: {
                        animateRotate: true,
                        animateScale: true,
                        duration: 1500,
                        easing: 'easeOutQuart'
                    },
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
            window.monthlyRevenueChart = new Chart(monthlyRevenueCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: monthlyRevenueLabels,
                    datasets: [{
                        label: 'Revenue (₱)',
                        data: monthlyRevenueData,
                        borderColor: '#93BFC7',
                        backgroundColor: 'rgba(147, 191, 199, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#93BFC7',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 2000,
                        easing: 'easeOutQuart'
                    },
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

        // Fetch and apply filtered chart data from server
        async function fetchAndApplyChartData(startDate, endDate) {
            // Build query
            const params = new URLSearchParams();
            if (startDate) params.set('start_date', startDate);
            if (endDate) params.set('end_date', endDate);

            try {
                const res = await fetch(`/admin/dashboard/charts?${params.toString()}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                if (!res.ok) throw new Error('Network response was not ok');
                const payload = await res.json();

                // Update charts if they exist
                if (window.bookingsByTypeChart) {
                    updateChart(window.bookingsByTypeChart, payload.bookingsByType.labels, payload.bookingsByType.data);
                }
                if (window.bookingsByStatusChart) {
                    updateChart(window.bookingsByStatusChart, payload.bookingsByStatus.labels, payload.bookingsByStatus.data);
                }
                if (window.monthlyRevenueChart) {
                    updateChart(window.monthlyRevenueChart, payload.monthlyRevenue.labels, payload.monthlyRevenue.data);
                }
            } catch (err) {
                console.error('Failed to fetch chart data', err);
            }
        }

        // Initialize global chart references so update function can access them
        window.bookingsByTypeChart = window.bookingsByTypeChart || null;
        window.bookingsByStatusChart = window.bookingsByStatusChart || null;
        window.monthlyRevenueChart = window.monthlyRevenueChart || null;

        // Attach filter handlers
        document.getElementById('chartFilterBtn').addEventListener('click', function() {
            const start = document.getElementById('chartStart').value;
            const end = document.getElementById('chartEnd').value;
            fetchAndApplyChartData(start, end);
        });

        document.getElementById('chartResetBtn').addEventListener('click', function() {
            document.getElementById('chartStart').value = '';
            document.getElementById('chartEnd').value = '';
            fetchAndApplyChartData('', '');
        });
    </script>

    <!-- User Detail Modal -->
    <div id="userModal" class="fixed inset-0 z-60 hidden items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full mx-4">
            <div class="flex items-center justify-between px-4 py-3 border-b">
                <h3 class="text-lg font-semibold">User Details</h3>
                <button id="closeUserModal" class="text-gray-600 hover:text-gray-800">&times;</button>
            </div>
                <div class="p-4" id="userModalBody">
                <div class="mb-2"><strong>Name:</strong> <span id="modalUserName"></span></div>
                <div class="mb-2"><strong>Email:</strong> <span id="modalUserEmail"></span></div>
                <div class="mb-2"><strong>Contact:</strong> <span id="modalUserPhone"></span></div>
                <div class="mb-2"><strong>Role:</strong> <span id="modalUserRole"></span></div>
                <div class="mb-2"><strong>Joined:</strong> <span id="modalUserJoined"></span></div>
                <div class="mb-2 hidden"><strong>Archived At:</strong> <span id="modalUserArchived"></span></div>
            </div>
            <div class="px-4 py-3 border-t text-right">
                <button id="modalCloseBtn" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Close</button>
            </div>
        </div>
    </div>

    <script>
      
        function openUserModal(userId) {
            const modal = document.getElementById('userModal');
            const nameEl = document.getElementById('modalUserName');
            const emailEl = document.getElementById('modalUserEmail');
            const roleEl = document.getElementById('modalUserRole');
            const joinedEl = document.getElementById('modalUserJoined');
            const archivedEl = document.getElementById('modalUserArchived');

            // Clear
            nameEl.textContent = 'Loading...';
            emailEl.textContent = '';
            roleEl.textContent = '';
            joinedEl.textContent = '';
            archivedEl.textContent = '';

            fetch(`/admin/users/${userId}/data`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                }).then(res => res.json()).then(data => {
                nameEl.textContent = data.name || '';
                emailEl.textContent = data.email || '';
                document.getElementById('modalUserPhone').textContent = data.phone || 'N/A';
                roleEl.textContent = data.role || '';
                joinedEl.textContent = data.created_at ? new Date(data.created_at).toLocaleString() : '';
                archivedEl.textContent = data.archived_at ? new Date(data.archived_at).toLocaleString() : 'N/A';
            }).catch(() => {
                nameEl.textContent = 'Failed to load';
            });

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeUserModal() {
            const modal = document.getElementById('userModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        document.addEventListener('click', function(e) {
            const modal = document.getElementById('userModal');
            if (!modal) return;
            if (!modal.classList.contains('hidden') && e.target.id === 'userModal') {
                closeUserModal();
            }
        });

        document.getElementById('closeUserModal').addEventListener('click', closeUserModal);
        document.getElementById('modalCloseBtn').addEventListener('click', closeUserModal);

        // Attach view button handlers
        document.querySelectorAll('.view-user-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-user-id');
                if (id) openUserModal(id);
            });
        });
    </script>

    <!-- Booking Reminders Modal -->
    @if(isset($upcomingBookings) && $upcomingBookings->count() > 0)
    <div id="bookingRemindersModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full mx-4 max-h-[85vh] flex flex-col animate-slideIn">
            <!-- Header -->
            <div class="bg-gradient-to-r from-[#93BFC7] to-[#7aa8b0] rounded-t-2xl px-6 py-4 flex items-center justify-between flex-shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-bell text-white text-lg animate-pulse"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">Upcoming Booking Reminders</h3>
                        <p class="text-white/80 text-sm">All upcoming events</p>
                    </div>
                </div>
                <button onclick="closeBookingRemindersModal()" class="text-white hover:text-gray-200 transition w-8 h-8 flex items-center justify-center rounded-full hover:bg-white/20">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            
            <!-- Body -->
            <div class="p-6 overflow-y-auto flex-1">
                <div class="space-y-4">
                    @foreach($upcomingBookings as $booking)
                        @php
                            $daysUntil = now()->startOfDay()->diffInDays($booking->event_date, false);
                            $isToday = $daysUntil === 0;
                            $isTomorrow = $daysUntil === 1;
                            $urgencyClass = $isToday ? 'border-red-400 bg-red-50' : ($isTomorrow ? 'border-orange-400 bg-orange-50' : 'border-blue-200 bg-blue-50');
                            $badgeClass = $isToday ? 'bg-red-500' : ($isTomorrow ? 'bg-orange-500' : 'bg-blue-500');
                        @endphp
                        <div class="border-l-4 {{ $urgencyClass }} rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="px-2 py-1 {{ $badgeClass }} text-white text-xs font-bold rounded-full">
                                            @if($isToday)
                                                TODAY
                                            @elseif($isTomorrow)
                                                TOMORROW
                                            @else
                                                {{ $daysUntil }} DAYS
                                            @endif
                                        </span>
                                        <span class="px-2 py-1 bg-gray-200 text-gray-700 text-xs font-semibold rounded-full capitalize">
                                            {{ $booking->event_type }}
                                        </span>
                                    </div>
                                    <h4 class="font-semibold text-gray-800 text-lg">
                                        {{ $booking->user->first_name ?? '' }} {{ $booking->user->last_name ?? $booking->user->name }}
                                    </h4>
                                    <div class="mt-2 space-y-1 text-sm text-gray-600">
                                        <p class="flex items-center gap-2">
                                            <i class="fas fa-calendar text-[#93BFC7] w-4"></i>
                                            {{ $booking->event_date->format('F d, Y (l)') }}
                                        </p>
                                        @if($booking->event_time)
                                        <p class="flex items-center gap-2">
                                            <i class="fas fa-clock text-[#93BFC7] w-4"></i>
                                            {{ date('g:i A', strtotime($booking->event_time)) }}
                                        </p>
                                        @endif
                                        <p class="flex items-center gap-2">
                                            <i class="fas fa-map-marker-alt text-[#93BFC7] w-4"></i>
                                            {{ $booking->location }}
                                        </p>
                                    </div>
                                </div>
                                <a href="{{ route('admin.booking.show', $booking) }}" 
                                   class="flex-shrink-0 bg-[#93BFC7] hover:bg-[#7aa8b0] text-white px-4 py-2 rounded-lg text-sm font-semibold transition flex items-center gap-2">
                                    <i class="fas fa-eye"></i>
                                    View
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Footer -->
            <div class="border-t border-gray-200 px-6 py-4 bg-gray-50 rounded-b-2xl flex-shrink-0">
                <div class="flex items-center justify-between">
                    <p class="text-gray-600 text-sm">
                        <i class="fas fa-info-circle mr-1"></i>
                        {{ $upcomingBookings->count() }} upcoming event{{ $upcomingBookings->count() > 1 ? 's' : '' }}
                    </p>
                    <div class="flex gap-3">
                        <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                            <input type="checkbox" id="dontShowAgainToday" class="rounded border-gray-300 text-[#93BFC7] focus:ring-[#93BFC7]">
                            <span>Don't show again today</span>
                        </label>
                        <button onclick="closeBookingRemindersModal()" 
                            class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg font-semibold transition">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        .animate-slideIn {
            animation: slideIn 0.3s ease-out;
        }
    </style>

    <script>
        // Check if modal should be shown on page load
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('bookingRemindersModal');
            if (!modal) return;
            
            const lastDismissed = localStorage.getItem('bookingRemindersLastDismissed');
            const today = new Date().toDateString();
            
            // Show modal by default, hide only if dismissed today
            if (lastDismissed === today) {
                modal.style.display = 'none';
            } else {
                modal.style.display = 'flex';
            }
        });

        function closeBookingRemindersModal() {
            const modal = document.getElementById('bookingRemindersModal');
            const dontShowAgain = document.getElementById('dontShowAgainToday');
            
            if (dontShowAgain && dontShowAgain.checked) {
                localStorage.setItem('bookingRemindersLastDismissed', new Date().toDateString());
            }
            
            modal.style.display = 'none';
        }

        // Close on backdrop click
        document.getElementById('bookingRemindersModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeBookingRemindersModal();
            }
        });

        // Clear old dismissal on new day (cleanup)
        (function() {
            const lastDismissed = localStorage.getItem('bookingRemindersLastDismissed');
            const today = new Date().toDateString();
            if (lastDismissed && lastDismissed !== today) {
                localStorage.removeItem('bookingRemindersLastDismissed');
            }
        })();
    </script>
    @endif

</body>
</html>
