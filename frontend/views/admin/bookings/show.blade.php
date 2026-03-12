<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Booking #{{ $booking->booking_id }} - RJ's Event Styling</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    @vite(['frontend/css/app.css', 'frontend/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="font-sans bg-[#ECF4E8]">

    <div class="flex">
        <!-- Admin Sidebar -->
        @include('admin.AdminLayouts.AdminSidebar')

        <!-- Main Content -->
        <div class="flex-1 min-h-screen px-4 py-6 ml-0 md:ml-64 md:px-6">
            
            <!-- Header with Back Button -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.bookings.index') }}" 
                       class="bg-white hover:bg-gray-100 text-gray-700 px-4 py-2 rounded-lg font-semibold shadow-sm transition flex items-center gap-2 border border-gray-200">
                        <i class="fas fa-arrow-left"></i>
                        Back to Bookings
                    </a>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Booking Details</h1>
                </div>
                <a href="{{ route('admin.dashboard') }}" 
                   class="bg-[#93BFC7] hover:bg-[#7eaab1] text-white px-4 py-2 rounded-lg font-semibold shadow-sm transition flex items-center gap-2">
                    <i class="fas fa-home"></i>
                    Dashboard
                </a>
            </div>

            <!-- Booking Content Container -->
            <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
                @include('admin.bookings._details', ['booking' => $booking])
            </div> <!-- End of Booking Content Container -->

        </div> <!-- End of Main Content -->
    </div> <!-- End of Flex Container -->

    <!-- Keyboard Shortcut: Press Escape to go back -->
    <script>
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                window.location.href = '{{ route("admin.bookings.index") }}';
            }
        });

        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            if (!container) return alert(message);

            const toast = document.createElement('div');
            toast.className = 'max-w-sm w-full bg-white shadow-lg rounded-md pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden mb-3';
            toast.style.borderLeft = type === 'success' ? '4px solid #16a34a' : (type === 'error' ? '4px solid #dc2626' : '4px solid #2563eb');
            toast.innerHTML = `
                <div class="p-3">
                    <div class="text-sm font-medium text-gray-900">${type === 'success' ? 'Success' : (type === 'error' ? 'Error' : 'Notice')}</div>
                    <div class="mt-1 text-sm text-gray-700">${message}</div>
                </div>
            `;

            container.appendChild(toast);

            setTimeout(() => {
                toast.classList.add('opacity-0');
                setTimeout(() => toast.remove(), 400);
            }, 3000);
        }

        // Approve Cancellation function for standalone page
        function approveCancellation(bookingId) {
            if (confirm('Are you sure you want to approve this cancellation request? This will cancel the booking and notify the customer.')) {
                fetch('/admin/booking/' + bookingId + '/approve-cancellation', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('Cancellation approved. The customer has been notified.', 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showToast(data.message || 'An error occurred.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('An error occurred while processing the cancellation.', 'error');
                });
            }
        }
    </script>

<!-- Toast Container -->
<div id="toastContainer" class="fixed top-6 right-6 z-[200] flex flex-col items-end"></div>

</body>
</html>
