<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment History - RJ's Event Styling</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="font-sans bg-[#ECF4E8]">
    

    <div class="flex">

        <!-- Sidebar -->
       @include('admin.AdminLayouts.AdminSidebar')

        <!-- Main Content -->
        <div class="flex-1 min-h-screen px-6 py-6 ml-64">

            <!-- Header -->
            @include('admin.layouts.header')


<!-- container -->
<div class="overflow-x-auto rounded-xl shadow-lg">

    <!-- Header -->
    <div class="bg-[#93BFC7] text-white px-6 py-4 rounded-t-xl shadow flex items-center text-3xl font-bold">
        <i class="fas fa-credit-card mr-3"></i>
        <h3 class="text-3xl font-bold">
            Payment History
        </h3>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-white text-[#93BFC7] font-semibold hover:bg-gray-200 border-b border-gray-300">
                    <th class="px-6 py-4 text-left">Payment Date</th>
                    <th class="px-6 py-4 text-left">Customer</th>
                    <th class="px-6 py-4 text-left">Booking</th>
                    <th class="px-6 py-4 text-left">Payment Method</th>
                    <th class="px-6 py-4 text-left">Amount Paid</th>
                    <th class="px-6 py-4 text-left">Remaining Balance</th>
                    <th class="px-6 py-4 text-left">Status</th>
                    <th class="px-6 py-4 text-left">Actions</th>
                </tr>
            </thead>

           <tbody>
                @forelse($payments as $payment)
                @php
                    $bookingId = $payment->booking->id ?? null;
                    $balance = $bookingId && isset($bookingBalances[$bookingId]) ? $bookingBalances[$bookingId] : null;
                @endphp
                <tr class="bg-white font-medium text-[#93BFC7] hover:bg-gray-200 border-b border-gray-300">
                    <td class="px-6 py-4">{{ $payment->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4">
                        <div>
                            <span class="font-semibold">{{ $payment->user->name ?? 'N/A' }}</span>
                            <span class="text-xs text-gray-500 block">{{ $payment->user->email ?? '' }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div>
                            <span class="font-semibold">{{ $payment->booking->event_type ?? 'N/A' }}</span>
                            <span class="text-xs text-gray-500 block">{{ $payment->booking->event_date->format('M d, Y') ?? '' }}</span>
                            @if($balance)
                            <span class="text-xs text-gray-500 block mt-1">
                                Total: ₱{{ number_format($balance['total_amount'], 2) }}
                            </span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 capitalize">{{ $payment->payment_method ?? 'N/A' }}</td>
                    <td class="px-6 py-4">
                        <span class="font-semibold">₱{{ number_format($payment->amount, 2) }}</span>
                        @if($payment->status === 'partial_paid')
                        <span class="text-xs text-yellow-600 block mt-1">(Partial Payment)</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($balance && $balance['remaining_balance'] > 0)
                            <span class="font-semibold text-orange-600">₱{{ number_format($balance['remaining_balance'], 2) }}</span>
                        @elseif($balance && $balance['remaining_balance'] <= 0)
                            <span class="text-green-600 font-semibold">Fully Paid</span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($payment->status === 'paid')
                            <span class="px-4 py-1 rounded-full bg-[#D4F6DF] text-green-900 font-medium inline-block">
                                Paid
                            </span>
                        @elseif($payment->status === 'partial_paid')
                            <span class="px-4 py-1 rounded-full bg-yellow-100 text-yellow-900 font-medium inline-block">
                                Partial Paid
                            </span>
                        @elseif($payment->status === 'pending')
                            <span class="px-4 py-1 rounded-full bg-[#FDFCB1] text-yellow-900 font-medium inline-block">
                                Pending
                            </span>
                        @elseif($payment->status === 'failed')
                            <span class="px-4 py-1 rounded-full bg-[#FDB1B1] text-red-900 font-medium inline-block">
                                Failed
                            </span>
                        @elseif($payment->status === 'cancelled')
                            <span class="px-4 py-1 rounded-full bg-[#FDB1B1] text-red-900 font-medium inline-block">
                                Cancelled
                            </span>
                        @else
                            <span class="px-4 py-1 rounded-full bg-gray-200 text-gray-700 font-medium inline-block">
                                {{ ucfirst($payment->status) }}
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($payment->booking)
                            <button data-booking-id="{{ $payment->booking->id }}" 
                                    data-payment-status="{{ $payment->status }}"
                                    data-show-mark-received="{{ $payment->status == 'pending' ? 1 : 0 }}"
                                    onclick="viewBookingModalFromButton(this)" 
                                    class="inline-flex items-center gap-1 bg-[#93BFC7] text-white px-3 py-1.5 rounded-lg hover:bg-[#7eaab1] transition text-sm font-semibold"
                                    title="View Booking">
                                <i class="fas fa-eye"></i>
                                View
                            </button>
                        @else
                            <span class="text-xs text-gray-400">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                        No payment history found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($payments->hasPages())
    <div class="bg-white px-6 py-4 border-t border-gray-200">
        {{ $payments->links() }}
    </div>
    @endif

</div>


        </div>
    </div>

    <!-- Booking Details Modal -->
    <div id="bookingModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden flex flex-col">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-[#93BFC7] to-[#7aa8b0] text-white px-6 py-4 flex items-center justify-between">
                <h3 class="text-2xl font-bold">Booking Details</h3>
                <button onclick="closeBookingModal()" class="text-white hover:text-gray-200 transition">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            
            <!-- Modal Content -->
            <div id="bookingModalContent" class="flex-1 overflow-y-auto p-6">
                <div class="flex items-center justify-center py-8">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#93BFC7]"></div>
                </div>
            </div>
            
            <!-- Modal Footer with Action Buttons -->
            <div id="bookingModalFooter" class="border-t border-gray-200 px-6 py-4 bg-gray-50 hidden">
                <div class="flex justify-end gap-3">
                    <button onclick="closeBookingModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition font-semibold">
                        Close
                    </button>
                    <button id="markReceivedBtn" onclick="markPaymentAsPartialPaid()" 
                            class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition font-semibold hidden">
                        <i class="fas fa-check-circle mr-2"></i>
                        Mark as Partial Paid
                    </button>
                    <button id="markPaidBtn" onclick="markPaymentAsPaid()" 
                            class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition font-semibold hidden">
                        <i class="fas fa-check-circle mr-2"></i>
                        Mark as Paid
                    </button>
                    <button id="markInDesignBtn" onclick="markAsInDesign()" 
                            class="px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition font-semibold hidden">
                        <i class="fas fa-palette mr-2"></i>
                        Move to Design Phase
                    </button>
                    <button id="markCompletedBtn" onclick="markAsCompleted()" 
                            class="px-4 py-2 bg-purple-500 text-white rounded-lg hover:bg-purple-600 transition font-semibold hidden">
                        <i class="fas fa-check-circle mr-2"></i>
                        Mark Event as Successful
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentBookingId = null;

        function viewBookingModalFromButton(button) {
            const bookingId = parseInt(button.getAttribute('data-booking-id'));
            const showMarkReceived = parseInt(button.getAttribute('data-show-mark-received')) === 1;
            const paymentStatus = button.getAttribute('data-payment-status');
            viewBookingModal(bookingId, showMarkReceived, null, paymentStatus);
        }

        function viewBookingModal(bookingId, showMarkReceived = false, bookingStatus = null, paymentStatus = null) {
            currentBookingId = bookingId;
            const modal = document.getElementById('bookingModal');
            const modalContent = document.getElementById('bookingModalContent');
            const modalFooter = document.getElementById('bookingModalFooter');
            const markReceivedBtn = document.getElementById('markReceivedBtn');
            
            // Show modal
            modal.classList.remove('hidden');
            
            // Show loading
            modalContent.innerHTML = '<div class="flex items-center justify-center py-8"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#93BFC7]"></div></div>';
            modalFooter.classList.add('hidden');
            
            // Fetch booking details
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
                modalContent.innerHTML = html;
                modalFooter.classList.remove('hidden');
                
                // Hide all buttons first
                const markReceivedBtn = document.getElementById('markReceivedBtn');
                const markPaidBtn = document.getElementById('markPaidBtn');
                const markInDesignBtn = document.getElementById('markInDesignBtn');
                const markCompletedBtn = document.getElementById('markCompletedBtn');
                
                markReceivedBtn.classList.add('hidden');
                markPaidBtn.classList.add('hidden');
                markInDesignBtn.classList.add('hidden');
                markCompletedBtn.classList.add('hidden');
                
                // Extract booking status from the loaded HTML
                const statusElement = modalContent.querySelector('[class*="bg-"]');
                let detectedStatus = null;
                if (statusElement) {
                    const statusText = statusElement.textContent.trim();
                    if (statusText.includes('Partial Paid')) detectedStatus = 'partial_paid';
                    else if (statusText.includes('In Design')) detectedStatus = 'in_design';
                    else if (statusText.includes('Completed')) detectedStatus = 'completed';
                    else if (statusText.includes('Pending Payment')) detectedStatus = 'pending_payment';
                }
                
                // Show appropriate button based on payment status and booking status
                if (paymentStatus === 'pending' || showMarkReceived) {
                    // Show partial paid button for pending payments
                    markReceivedBtn.classList.remove('hidden');
                } else if (paymentStatus === 'partial_paid' || detectedStatus === 'partial_paid') {
                    // Show paid button for partial_paid payments
                    markPaidBtn.classList.remove('hidden');
                    // Also check if fully paid by looking for remaining balance
                    const remainingBalanceText = modalContent.textContent;
                    if (remainingBalanceText.includes('Fully Paid') || remainingBalanceText.includes('₱0.00')) {
                        markInDesignBtn.classList.remove('hidden');
                    }
                } else if (detectedStatus === 'in_design') {
                    markCompletedBtn.classList.remove('hidden');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                modalContent.innerHTML = '<div class="text-center py-8 text-red-600">An error occurred while loading booking details.</div>';
                modalFooter.classList.remove('hidden');
            });
        }

        function closeBookingModal() {
            const modal = document.getElementById('bookingModal');
            modal.classList.add('hidden');
            currentBookingId = null;
        }

        function markPaymentAsPartialPaid(bookingId = null) {
            const bookingIdToUse = bookingId || currentBookingId;
            
            if (!bookingIdToUse) {
                return;
            }

            if (!confirm('Are you sure you have received the payment? This will mark the payment as partial paid and update the booking status.')) {
                return;
            }

            fetch(`/admin/booking/${bookingIdToUse}/mark-payment-partial-paid`, {
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
                    if (currentBookingId) {
                        closeBookingModal();
                    }
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

        function markPaymentAsPaid(bookingId = null) {
            const bookingIdToUse = bookingId || currentBookingId;
            
            if (!bookingIdToUse) {
                return;
            }

            if (!confirm('Are you sure you have received the full payment? This will mark the payment as paid. If the booking is fully paid, it will automatically move to design phase.')) {
                return;
            }

            fetch(`/admin/booking/${bookingIdToUse}/mark-payment-paid`, {
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
                    if (currentBookingId) {
                        closeBookingModal();
                    }
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

        function markAsInDesign() {
            if (!currentBookingId) {
                return;
            }

            if (!confirm('Are you sure you want to move this booking to the design phase? This indicates that the booking is fully paid and ready for design work.')) {
                return;
            }

            fetch(`/admin/booking/${currentBookingId}/mark-in-design`, {
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
                    closeBookingModal();
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

        function markAsCompleted() {
            if (!currentBookingId) {
                return;
            }

            if (!confirm('Are you sure the event was successful? This will mark the booking as completed.')) {
                return;
            }

            fetch(`/admin/booking/${currentBookingId}/mark-completed`, {
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
                    closeBookingModal();
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

        // Close modal when clicking outside
        document.getElementById('bookingModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeBookingModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('bookingModal').classList.contains('hidden')) {
                closeBookingModal();
            }
        });
    </script>

</body>
</html>

