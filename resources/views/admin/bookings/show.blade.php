<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Booking #{{ $booking->booking_id }} - RJ's Event Styling</title>

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
                        alert('Cancellation approved. The customer has been notified.');
                        location.reload();
                    } else {
                        alert(data.message || 'An error occurred.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while processing the cancellation.');
                });
            }
        }
    </script>

</body>
</html>
