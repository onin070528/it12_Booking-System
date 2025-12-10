<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Bookings - RJ's Event Styling</title>

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
        <div class="flex-1 min-h-screen px-6 py-6 ml-64">
            
            <!-- Header -->
            <div class="bg-white shadow-md rounded-xl px-6 py-4 flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-bold" style="color: #93BFC7;">
                        <i class="fas fa-calendar-check mr-2"></i>Booking Management
                    </h2>
                    <p class="text-xl font-semibold" style="color: #93BFC7;">Review and manage customer bookings</p>
                </div>
            </div>

            <!-- Bookings Table -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-[#93BFC7] text-white">
                            <tr>
                                <th class="px-6 py-4 text-left">Customer</th>
                                <th class="px-6 py-4 text-left">Event Type</th>
                                <th class="px-6 py-4 text-left">Event Date</th>
                                <th class="px-6 py-4 text-left">Location</th>
                                <th class="px-6 py-4 text-left">Amount</th>
                                <th class="px-6 py-4 text-left">Status</th>
                                <th class="px-6 py-4 text-left">Meetup Schedule</th>
                                <th class="px-6 py-4 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($bookings as $booking)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 hidden">{{ $booking->id }}</td>
                                    <td class="px-6 py-4">{{ $booking->user->name }}</td>
                                    <td class="px-6 py-4 capitalize">{{ $booking->event_type }}</td>
                                    <td class="px-6 py-4">{{ $booking->event_date->format('M d, Y') }}</td>
                                    <td class="px-6 py-4">{{ $booking->location }}</td>
                                    <td class="px-6 py-4">₱{{ number_format($booking->total_amount, 2) }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                                            @if($booking->status == 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($booking->status == 'confirmed') bg-green-100 text-green-800
                                            @elseif($booking->status == 'approved') bg-blue-100 text-blue-800
                                            @elseif($booking->status == 'pending_payment') bg-orange-100 text-orange-800
                                            @elseif($booking->status == 'partial_paid') bg-yellow-100 text-yellow-800
                                            @elseif($booking->status == 'in_design') bg-indigo-100 text-indigo-800
                                            @elseif($booking->status == 'rejected') bg-red-100 text-red-800
                                            @elseif($booking->status == 'completed') bg-purple-100 text-purple-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ $booking->status == 'pending_payment' ? 'Pending Payment' : ($booking->status == 'partial_paid' ? 'Partial Paid' : ($booking->status == 'in_design' ? 'In Design' : ucfirst($booking->status))) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($booking->meetup_date && $booking->meetup_time)
                                            {{ $booking->meetup_date->format('M d, Y') }} at {{ date('g:i A', strtotime($booking->meetup_time)) }}
                                        @else
                                            <span class="text-gray-400">Not set</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                            <div class="flex items-center justify-center gap-3">

                                                <!-- View -->
                                                <button 
                                                    onclick="viewBooking('{{ $booking->id }}')"
                                                    class="p-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 shadow-sm hover:shadow transition-all duration-200"
                                                    title="View Booking"
                                                >
                                                    <i class="fas fa-eye"></i>
                                                </button>

                                                <!-- Confirm Booking -->
                                                @if($booking->status == 'pending')
                                                    <button 
                                                        data-booking-id="{{ $booking->id }}"
                                                        onclick="confirmBooking(this.dataset.bookingId)"
                                                        class="px-4 py-2 rounded-xl bg-green-500 hover:bg-green-600 text-white shadow-sm hover:shadow transition-all duration-200 flex items-center gap-2"
                                                        title="Confirm Booking"
                                                    >
                                                        <i class="fas fa-check"></i>
                                                        <span class="hidden md:inline">Confirm</span>
                                                    </button>
                                                @endif


                                            </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                                        No bookings found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Schedule Modal -->
    <div id="scheduleModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4">
            <div class="bg-[#93BFC7] rounded-t-xl px-6 py-4 flex items-center justify-between">
                <h3 class="text-xl font-bold text-white">
                    <i class="fas fa-calendar mr-2"></i>Set Meetup Schedule
                </h3>
                <button onclick="closeScheduleModal()" class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6">
                <form id="scheduleForm">
                    @csrf
                    <input type="hidden" id="booking_id" name="booking_id">
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Meetup Date</label>
                        <input type="date" id="meetup_date" name="meetup_date" required
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#93BFC7]">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-2">Meetup Time</label>
                        <input type="time" id="meetup_time" name="meetup_time" required
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#93BFC7]">
                    </div>
                    <div class="flex space-x-3">
                        <button type="button" onclick="saveSchedule()" 
                            class="flex-1 bg-[#93BFC7] text-white font-bold py-3 rounded-lg hover:bg-[#7aa8b0] transition">
                            Save Schedule
                        </button>
                        <button type="button" onclick="closeScheduleModal()" 
                            class="flex-1 bg-gray-300 text-gray-700 font-bold py-3 rounded-lg hover:bg-gray-400 transition">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Booking Modal -->
    <div id="viewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-3xl w-full max-h-[90vh] flex flex-col">
            <div class="bg-[#93BFC7] rounded-t-xl px-6 py-4 flex items-center justify-between flex-shrink-0">
                <h3 class="text-xl font-bold text-white">
                    <i class="fas fa-info-circle mr-2"></i>Booking Details
                </h3>
                <button onclick="closeViewModal()" class="text-white hover:text-gray-200 transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="overflow-y-auto flex-1" id="bookingDetailsContainer">
                <div class="p-6" id="bookingDetails">
                    <!-- Booking details will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script>
        function openScheduleModal(bookingId) {
            document.getElementById('booking_id').value = bookingId;
            document.getElementById('scheduleModal').classList.remove('hidden');
        }
        
        function confirmBooking(bookingId) {
            if (!confirm('Are you sure you want to confirm this booking? The customer will be notified and can proceed with payment.')) {
                return;
            }

            fetch(`/admin/booking/${bookingId}/confirm`, {
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
                    alert('Booking confirmed successfully!');
                    location.reload();
                } else {
                    alert(data.message || 'An error occurred.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while confirming the booking.');
            });
        }

        function closeScheduleModal() {
            document.getElementById('scheduleModal').classList.add('hidden');
            document.getElementById('scheduleForm').reset();
        }

        function saveSchedule() {
            const bookingId = document.getElementById('booking_id').value;
            const meetupDate = document.getElementById('meetup_date').value;
            const meetupTime = document.getElementById('meetup_time').value;

            if (!meetupDate || !meetupTime) {
                alert('Please fill in all fields.');
                return;
            }

            fetch(`/admin/booking/${bookingId}/schedule`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    meetup_date: meetupDate,
                    meetup_time: meetupTime
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Schedule saved successfully!');
                    location.reload();
                } else {
                    alert(data.message || 'An error occurred.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while saving the schedule.');
            });
        }

        function confirmBooking(bookingId) {
            if (!confirm('Are you sure you want to confirm this booking? The customer will be notified and can proceed with payment.')) {
                return;
            }

            fetch(`/admin/booking/${bookingId}/confirm`, {
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
                    alert('Booking confirmed successfully!');
                    location.reload();
                } else {
                    alert(data.message || 'An error occurred.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while confirming the booking.');
            });
        }

        function markForPayment(bookingId) {
            if (!confirm('Are you sure you want to notify the customer that their booking is ready for payment?')) {
                return;
            }

            fetch(`/admin/booking/${bookingId}/mark-for-payment`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Customer has been notified that their booking is ready for payment!');
                    location.reload();
                } else {
                    alert(data.message || 'An error occurred.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while notifying the customer.');
            });
        }

        function markPaymentAsPartialPaid(bookingId) {
            if (!confirm('Are you sure you have received the payment? This will mark the payment as partial paid and update the booking status.')) {
                return;
            }

            fetch(`/admin/booking/${bookingId}/mark-payment-partial-paid`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Payment marked as partial paid! Customer has been notified.');
                    location.reload();
                } else {
                    alert(data.message || 'An error occurred.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while marking the payment.');
            });
        }

        function markPaymentAsPaid(bookingId) {
            if (!confirm('Are you sure you have received the full payment? This will mark the payment as paid. If the booking is fully paid, it will automatically move to design phase.')) {
                return;
            }

            fetch(`/admin/booking/${bookingId}/mark-payment-paid`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message || 'An error occurred.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while marking the payment.');
            });
        }

        function markAsInDesign(bookingId) {
            if (!confirm('Are you sure you want to move this booking to the design phase? This indicates that the booking is fully paid and ready for design work.')) {
                return;
            }

            fetch(`/admin/booking/${bookingId}/mark-in-design`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Booking moved to design phase! Customer has been notified.');
                    location.reload();
                } else {
                    alert(data.message || 'An error occurred.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the booking status.');
            });
        }

        function markAsCompleted(bookingId) {
            if (!confirm('Are you sure the event was successful? This will mark the booking as completed.')) {
                return;
            }

            fetch(`/admin/booking/${bookingId}/mark-completed`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Booking marked as completed! Customer has been notified.');
                    location.reload();
                } else {
                    alert(data.message || 'An error occurred.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the booking status.');
            });
        }

        function viewBooking(bookingId) {
            fetch(`/admin/booking/${bookingId}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(html => {
                    document.getElementById('bookingDetails').innerHTML = html;
                    document.getElementById('viewModal').classList.remove('hidden');
                    // Scroll to top of modal content
                    document.getElementById('bookingDetailsContainer').scrollTop = 0;
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while loading booking details.');
                });
        }

        function closeViewModal() {
            document.getElementById('viewModal').classList.add('hidden');
            // Clear content when closing
            document.getElementById('bookingDetails').innerHTML = '';
        }
        
        // Close modal when clicking outside
        document.getElementById('viewModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeViewModal();
            }
        });
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('viewModal').classList.contains('hidden')) {
                closeViewModal();
            }
        });
    </script>

</body>
</html>

