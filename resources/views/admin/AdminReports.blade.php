<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Reports - RJ's Event Styling</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Chart.js for visualizations -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- jsPDF for PDF export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
</head>

<body class="font-sans bg-[#ECF4E8]">

    <div class="flex">
        <!-- Admin Sidebar -->
        @include('admin.AdminLayouts.AdminSidebar')

        <!-- Main Content -->
        <div class="flex-1 min-h-screen px-6 py-6 ml-64">
            
            <!-- Header with Filters and Actions -->
            <div class="bg-white shadow-md rounded-xl px-6 py-4 mb-6">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                    <div>
                        <h2 class="text-3xl font-bold" style="color: #93BFC7;">
                            <i class="fas fa-chart-bar mr-2"></i>Reports & Analytics
                        </h2>
                        <p class="text-sm text-gray-600 mt-1">Comprehensive system reports and statistics</p>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <!-- Print/Export Button -->
                        <button onclick="printReport()" 
                            class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition flex items-center">
                            <i class="fas fa-print mr-2"></i>Print
                        </button>
                        <button onclick="exportToPDF()" 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition flex items-center">
                            <i class="fas fa-file-pdf mr-2"></i>Export PDF
                        </button>
                    </div>
                </div>

                <!-- Advanced Filters -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <form method="GET" action="{{ route('admin.reports.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                        <!-- Date Range -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                            <input type="date" name="start_date" value="{{ $startDate }}" 
                                class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-[#93BFC7]">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                            <input type="date" name="end_date" value="{{ $endDate }}" 
                                class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-[#93BFC7]">
                        </div>
                        
                        <!-- Status Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Booking Status</label>
                            <select name="status" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-[#93BFC7]">
                                <option value="">All Statuses</option>
                                @foreach($allStatuses as $status)
                                    <option value="{{ $status }}" {{ $statusFilter == $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Event Type Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Event Type</label>
                            <select name="event_type" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-[#93BFC7]">
                                <option value="">All Types</option>
                                @foreach($allEventTypes as $type)
                                    <option value="{{ $type }}" {{ $eventTypeFilter == $type ? 'selected' : '' }}>
                                        {{ ucfirst($type) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Payment Method Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                            <select name="payment_method" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-[#93BFC7]">
                                <option value="">All Methods</option>
                                @foreach($allPaymentMethods as $method)
                                    <option value="{{ $method }}" {{ $paymentMethodFilter == $method ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $method)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filter Buttons -->
                        <div class="flex items-end gap-2">
                            <button type="submit" 
                                class="w-full px-4 py-2 bg-[#93BFC7] text-white rounded-lg hover:bg-[#7eaab1] transition flex items-center justify-center">
                                <i class="fas fa-filter mr-2"></i>Apply Filters
                            </button>
                            <a href="{{ route('admin.reports.index') }}" 
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition flex items-center">
                                <i class="fas fa-redo mr-2"></i>Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Report Content (for PDF export) -->
            <div id="report-content">
                <!-- Report Header (for Print/PDF) -->
                <div id="report-header" class="bg-white rounded-xl shadow-lg p-6 mb-6 print-header">
                    <div class="flex items-center justify-between border-b-2 border-[#93BFC7] pb-4 mb-4">
                        <div class="flex items-center">
                            <div class="w-16 h-16 bg-[#93BFC7] rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-calendar-check text-white text-2xl"></i>
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold" style="color: #93BFC7;">RJ's Event Styling</h1>
                                <p class="text-gray-600 text-sm">Booking Management System</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Generated: {{ now()->format('F d, Y h:i A') }}</p>
                            <p class="text-sm text-gray-500">Report Period: {{ date('M d', strtotime($startDate)) }} - {{ date('M d, Y', strtotime($endDate)) }}</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Reports & Analytics</h2>
                            <p class="text-gray-600 mt-1">Comprehensive system reports and statistics</p>
                        </div>
                        <div class="text-right">
                            @if($statusFilter)
                                <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm mr-2">
                                    Status: {{ ucfirst($statusFilter) }}
                                </span>
                            @endif
                            @if($eventTypeFilter)
                                <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm mr-2">
                                    Type: {{ ucfirst($eventTypeFilter) }}
                                </span>
                            @endif
                            @if($paymentMethodFilter)
                                <span class="inline-block px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm">
                                    Method: {{ ucfirst(str_replace('_', ' ', $paymentMethodFilter)) }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white rounded-xl shadow-lg p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm font-medium mb-1">Total Bookings</p>
                                <h3 class="text-2xl font-bold" style="color: #93BFC7;">{{ $totalBookings }}</h3>
                                <p class="text-xs text-gray-500 mt-1">{{ date('M d', strtotime($startDate)) }} - {{ date('M d', strtotime($endDate)) }}</p>
                            </div>
                            <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-calendar-check text-blue-600 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm font-medium mb-1">Period Revenue</p>
                                <h3 class="text-2xl font-bold text-green-600">₱{{ number_format($revenueInRange, 2) }}</h3>
                                <p class="text-xs text-gray-500 mt-1">Paid payments</p>
                            </div>
                            <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-money-bill-wave text-green-600 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm font-medium mb-1">Pending Payments</p>
                                <h3 class="text-2xl font-bold text-yellow-600">₱{{ number_format($pendingPayments, 2) }}</h3>
                                <p class="text-xs text-gray-500 mt-1">Awaiting payment</p>
                            </div>
                            <div class="w-14 h-14 bg-yellow-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-hourglass-half text-yellow-600 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm font-medium mb-1">Total Revenue</p>
                                <h3 class="text-2xl font-bold" style="color: #93BFC7;">₱{{ number_format($totalRevenue, 2) }}</h3>
                                <p class="text-xs text-gray-500 mt-1">All time</p>
                            </div>
                            <div class="w-14 h-14 bg-purple-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
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
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <h3 class="text-xl font-bold mb-4" style="color: #93BFC7;">
                        <i class="fas fa-chart-line mr-2"></i>Monthly Revenue Trend
                    </h3>
                    <div style="height: 350px; position: relative;">
                        <canvas id="monthlyRevenueChart"></canvas>
                    </div>
                </div>

                <!-- Payment Methods and Top Customers -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Payment Methods -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-bold mb-4" style="color: #93BFC7;">
                            <i class="fas fa-credit-card mr-2"></i>Payment Methods
                        </h3>
                        <div class="space-y-3">
                            @forelse($paymentsByMethod as $method)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-[#93BFC7] rounded-full flex items-center justify-center text-white mr-3">
                                            <i class="fas fa-{{ $method->payment_method == 'gcash' ? 'mobile-alt' : ($method->payment_method == 'card' ? 'credit-card' : 'wallet') }}"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold capitalize">{{ str_replace('_', ' ', $method->payment_method) }}</p>
                                            <p class="text-sm text-gray-500">{{ $method->count }} transactions</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold" style="color: #93BFC7;">₱{{ number_format($method->total, 2) }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-4">No payment data available</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Top Customers -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-xl font-bold mb-4" style="color: #93BFC7;">
                            <i class="fas fa-users mr-2"></i>Top Customers
                        </h3>
                        <div class="space-y-3">
                            @forelse($topCustomers as $customer)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-[#93BFC7] rounded-full flex items-center justify-center text-white font-semibold mr-3">
                                            {{ strtoupper(substr($customer->user->name ?? 'N/A', 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold">{{ $customer->user->name ?? 'N/A' }}</p>
                                            <p class="text-sm text-gray-500">{{ $customer->user->email ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold" style="color: #93BFC7;">{{ $customer->booking_count }} bookings</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-4">No customer data available</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Recent Payments Table -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-xl font-bold" style="color: #93BFC7;">
                            <i class="fas fa-list mr-2"></i>Recent Payments
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Booking</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($recentPayments as $payment)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 bg-[#93BFC7] rounded-full flex items-center justify-center text-white font-semibold">
                                                    {{ strtoupper(substr($payment->user->name ?? 'N/A', 0, 1)) }}
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $payment->user->name ?? 'N/A' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 capitalize">{{ $payment->booking->event_type ?? 'N/A' }}</div>
                                            <div class="text-sm text-gray-500">{{ $payment->booking->event_date ? $payment->booking->event_date->format('M d, Y') : 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold" style="color: #93BFC7;">₱{{ number_format($payment->amount, 2) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 capitalize">
                                                {{ str_replace('_', ' ', $payment->payment_method ?? 'N/A') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $payment->paid_at ? $payment->paid_at->format('M d, Y h:i A') : 'N/A' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                            No payments found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
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

        let bookingsByTypeChart, bookingsByStatusChart, monthlyRevenueChart;

        // Bookings by Event Type Chart
        const bookingsByTypeCtx = document.getElementById('bookingsByTypeChart');
        if (bookingsByTypeCtx) {
            bookingsByTypeChart = new Chart(bookingsByTypeCtx.getContext('2d'), {
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
            bookingsByStatusChart = new Chart(bookingsByStatusCtx.getContext('2d'), {
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
            monthlyRevenueChart = new Chart(monthlyRevenueCtx.getContext('2d'), {
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

        // Print function
        function printReport() {
            window.print();
        }

        // PDF Export function
        async function exportToPDF() {
            const { jsPDF } = window.jspdf;
            const reportContent = document.getElementById('report-content');
            
            // Show loading
            const loading = document.createElement('div');
            loading.style.cssText = 'position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);background:white;padding:20px;border-radius:8px;box-shadow:0 4px 6px rgba(0,0,0,0.1);z-index:9999;';
            loading.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Generating PDF...';
            document.body.appendChild(loading);

            try {
                // Temporarily change background for better PDF rendering
                const originalBg = reportContent.style.backgroundColor;
                reportContent.style.backgroundColor = 'white';
                
                // Convert HTML to canvas (including header)
                const canvas = await html2canvas(reportContent, {
                    scale: 2,
                    useCORS: true,
                    logging: false,
                    backgroundColor: 'white',
                    windowWidth: reportContent.scrollWidth,
                    windowHeight: reportContent.scrollHeight
                });

                // Restore original background
                reportContent.style.backgroundColor = originalBg;

                const imgData = canvas.toDataURL('image/png', 1.0);
                
                // Create PDF
                const pdf = new jsPDF('p', 'mm', 'a4');
                const pdfWidth = pdf.internal.pageSize.getWidth();
                const pdfHeight = pdf.internal.pageSize.getHeight();
                const margin = 10;
                const contentWidth = pdfWidth - (margin * 2);
                const imgWidth = contentWidth;
                const imgHeight = (canvas.height * contentWidth) / canvas.width;
                
                let heightLeft = imgHeight;
                let position = margin;

                // Add first page with header
                pdf.addImage(imgData, 'PNG', margin, position, imgWidth, imgHeight);
                heightLeft -= (pdfHeight - margin * 2);

                // Add additional pages if needed
                while (heightLeft > 0) {
                    position = margin - (imgHeight - heightLeft);
                    pdf.addPage();
                    pdf.addImage(imgData, 'PNG', margin, position, imgWidth, imgHeight);
                    heightLeft -= (pdfHeight - margin * 2);
                }

                // Save PDF with descriptive filename
                const dateStr = '{{ date("Y-m-d") }}';
                const timeStr = new Date().getTime();
                const filterStr = '{{ $statusFilter ? "_" . $statusFilter : "" }}{{ $eventTypeFilter ? "_" . $eventTypeFilter : "" }}';
                const fileName = `RJ_Reports_${dateStr}${filterStr}_${timeStr}.pdf`;
                pdf.save(fileName);
            } catch (error) {
                console.error('Error generating PDF:', error);
                alert('Error generating PDF. Please try again.');
            } finally {
                if (document.body.contains(loading)) {
                    document.body.removeChild(loading);
                }
            }
        }

        // Print styles
        const style = document.createElement('style');
        style.textContent = `
            @media print {
                @page {
                    margin: 15mm;
                }
                body * {
                    visibility: hidden;
                }
                #report-content, #report-content * {
                    visibility: visible;
                }
                #report-content {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                    background: white;
                }
                #report-header {
                    page-break-after: avoid;
                    margin-bottom: 20px;
                }
                .no-print {
                    display: none !important;
                }
                .bg-\\[\\#ECF4E8\\] {
                    background-color: white !important;
                }
                .rounded-xl {
                    border-radius: 0 !important;
                }
                .shadow-lg {
                    box-shadow: none !important;
                }
            }
        `;
        document.head.appendChild(style);
    </script>

</body>
</html>
