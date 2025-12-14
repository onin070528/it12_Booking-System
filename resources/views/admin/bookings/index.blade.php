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
            
            <!-- Header -->
            <div class="bg-white shadow-md rounded-xl px-6 py-4 flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-bold" style="color: #93BFC7;">
                        <i class="fas fa-calendar-check mr-2"></i>Booking Management
                    </h2>
                    <p class="text-xl font-semibold" style="color: #93BFC7;">Review and manage customer bookings</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.bookings.archived') }}" class="bg-[#93BFC7] px-4 py-2 rounded-lg font-sm text-white hover:bg-gray-200">Archived Bookings</a>
                </div>
            </div>

            <!-- Bookings Table -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-[#93BFC7] text-white text-lg">
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
                                <tr class="hover:bg-gray-50 font-medium">
                                    <td class="px-6 py-4 hidden">{{ $booking->id }}</td>
                                    <td class="px-6 py-4">{{ $booking->user->name }}</td>
                                    <td class="px-6 py-4 capitalize">{{ $booking->event_type }}</td>
                                    <td class="px-6 py-4">{{ $booking->event_date->format('M d, Y') }}</td>
                                    <td class="px-6 py-4">{{ $booking->location }}</td>
                                    <td class="px-6 py-4">
                                        @php
                                            $totalPaid = $bookingPayments[$booking->id]['total_paid'] ?? 0;
                                            $remainingBalance = $bookingPayments[$booking->id]['remaining_balance'] ?? $booking->total_amount;
                                        @endphp
                                        <div class="space-y-1">
                                            <div class="font-semibold">₱{{ number_format($booking->total_amount, 2) }}</div>
                                            @if($totalPaid > 0)
                                                <div class="text-xs">
                                                    <span class="text-green-600 font-medium">Paid: ₱{{ number_format($totalPaid, 2) }}</span>
                                                    @if($remainingBalance > 0)
                                                        <span class="text-orange-600 font-medium block">Remaining: ₱{{ number_format($remainingBalance, 2) }}</span>
                                                    @else
                                                        <span class="text-green-600 font-medium block">Fully Paid</span>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="text-xs text-gray-500">No payment yet</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                                            @if($booking->status == 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($booking->status == 'confirmed') bg-green-100 text-green-800
                                            @elseif($booking->status == 'approved') bg-blue-100 text-blue-800
                                            @elseif($booking->status == 'pending_payment') bg-orange-100 text-orange-800
                                            @elseif($booking->status == 'partial_payment') bg-yellow-100 text-yellow-800
                                            @elseif($booking->status == 'in_design') bg-indigo-100 text-indigo-800
                                            @elseif($booking->status == 'rejected') bg-red-100 text-red-800
                                            @elseif($booking->status == 'completed') bg-purple-100 text-purple-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ $booking->status == 'pending_payment' ? 'Pending Payment' : ($booking->status == 'partial_payment' ? 'Partial Payment' : ($booking->status == 'in_design' ? 'In Design' : ucfirst($booking->status))) }}
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
                                                    class="relative p-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 shadow-sm hover:shadow transition-all duration-200"
                                                    title="View Booking"
                                                >
                                                    <i class="fas fa-eye"></i>
                                                    @if(!$booking->admin_viewed_at)
                                                        <span class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-xs font-bold text-white">
                                                            1
                                                        </span>
                                                    @endif
                                                </button>

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
            <div id="bookingModalFooter" class="border-t border-gray-200 px-6 py-4 bg-gray-50 flex-shrink-0 hidden">
                <!-- Footer buttons will be loaded here from booking details -->
            </div>
        </div>
    </div>

    <script>
        function openScheduleModal(bookingId) {
            document.getElementById('booking_id').value = bookingId;
            document.getElementById('scheduleModal').classList.remove('hidden');
        }
        
        function confirmBooking(bookingId) {
            showConfirm({
                title: 'Confirm Booking',
                message: 'Are you sure you want to confirm this booking? The customer will be notified and can proceed with payment.',
                confirmText: 'Confirm',
                onConfirm: () => {
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
                            showToast('Booking confirmed successfully!', 'success');
                            setTimeout(() => location.reload(), 900);
                        } else {
                            showToast(data.message || 'An error occurred.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('An error occurred while confirming the booking.', 'error');
                    });
                }
            });
        }

        function cancelBooking(bookingId) {
            showConfirm({
                title: 'Cancel Booking',
                message: 'Are you sure you want to cancel this booking? This will notify the customer and cannot be undone.',
                confirmText: 'Cancel Booking',
                onConfirm: () => {
                    fetch(`/admin/booking/${bookingId}/cancel`, {
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
                            showToast('Booking cancelled. Notifications have been sent.', 'success');
                            setTimeout(() => location.reload(), 900);
                        } else {
                            showToast(data.message || 'An error occurred while cancelling the booking.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('An error occurred while cancelling the booking.', 'error');
                    });
                }
            });
        }

        function archiveBooking(bookingId) {
            showConfirm({
                title: 'Archive Booking',
                message: 'Archive this booking? It will be moved to archived bookings list.',
                confirmText: 'Archive',
                onConfirm: () => {
                    fetch(`/admin/booking/${bookingId}/archive`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(r => r.json())
                    .then(d => {
                        if (d.success) {
                            showToast('Booking archived.', 'success');
                            setTimeout(() => location.reload(), 800);
                        } else {
                            showToast(d.message || 'Error archiving booking', 'error');
                        }
                    }).catch(e => { console.error(e); showToast('Error archiving booking', 'error'); });
                }
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
            if (!confirm('Are you sure you have received the payment? This will mark the payment as partial payment and update the booking status.')) {
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
                    alert('Payment marked as partial payment! Customer has been notified.');
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
                    const bookingDetails = document.getElementById('bookingDetails');
                    bookingDetails.innerHTML = html;
                    
                    // Extract action buttons from the loaded content and move to footer
                    const actionButtons = bookingDetails.querySelector('.flex.gap-3.pt-4');
                    const modalFooter = document.getElementById('bookingModalFooter');
                    
                    if (actionButtons) {
                        modalFooter.innerHTML = actionButtons.outerHTML;
                        modalFooter.classList.remove('hidden');
                        actionButtons.remove();
                    } else {
                        modalFooter.classList.add('hidden');
                    }
                    
                    document.getElementById('viewModal').classList.remove('hidden');
                    // Scroll to top of modal content
                    document.getElementById('bookingDetailsContainer').scrollTop = 0;
                    
                    // Remove the badge from the view button since booking is now viewed
                    const viewButton = document.querySelector(`button[onclick="viewBooking('${bookingId}')"]`);
                    if (viewButton) {
                        const badge = viewButton.querySelector('span.absolute');
                        if (badge) {
                            badge.remove();
                        }
                    }
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

        // Print Booking Function
        function printBooking() {
            const bookingDetails = document.getElementById('bookingDetails');
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Booking Print</title>
                    <style>
                        body { font-family: Arial, sans-serif; padding: 20px; }
                        .print-header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #93BFC7; padding-bottom: 20px; }
                        .print-header h1 { color: #93BFC7; margin: 0; }
                        .print-section { margin-bottom: 20px; page-break-inside: avoid; }
                        .print-section h3 { color: #93BFC7; border-bottom: 2px solid #93BFC7; padding-bottom: 5px; }
                        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                        table td { padding: 8px; border-bottom: 1px solid #eee; }
                        table td:first-child { font-weight: bold; width: 30%; }
                        @media print {
                            body { margin: 0; }
                            .no-print { display: none; }
                        }
                    </style>
                </head>
                <body>
                    <div class="print-header">
                        <h1>RJ's Event Styling</h1>
                        <p>Booking Management System</p>
                        <p>Generated: ${new Date().toLocaleString()}</p>
                    </div>
                    ${bookingDetails.innerHTML}
                </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.focus();
            setTimeout(() => {
                printWindow.print();
            }, 250);
        }

        // Export Booking to PDF Function
        async function exportBookingToPDF() {
            const { jsPDF } = window.jspdf;
            const bookingDetails = document.getElementById('bookingDetails');
            
            // Show loading
            const loading = document.createElement('div');
            loading.style.cssText = 'position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);background:white;padding:20px;border-radius:8px;box-shadow:0 4px 6px rgba(0,0,0,0.1);z-index:9999;';
            loading.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Generating PDF...';
            document.body.appendChild(loading);

            try {
                // Create a temporary container for PDF generation
                const tempContainer = document.createElement('div');
                tempContainer.style.cssText = 'position:absolute;left:-9999px;width:800px;background:white;padding:20px;';
                tempContainer.innerHTML = `
                    <div style="text-align:center;margin-bottom:30px;border-bottom:3px solid #93BFC7;padding-bottom:20px;">
                        <h1 style="color:#93BFC7;margin:0;">RJ's Event Styling</h1>
                        <p>Booking Management System</p>
                        <p style="font-size:12px;color:#666;">Generated: ${new Date().toLocaleString()}</p>
                    </div>
                    ${bookingDetails.innerHTML}
                `;
                document.body.appendChild(tempContainer);

                // Convert to canvas
                const canvas = await html2canvas(tempContainer, {
                    scale: 2,
                    useCORS: true,
                    logging: false,
                    backgroundColor: 'white',
                    windowWidth: tempContainer.scrollWidth,
                    windowHeight: tempContainer.scrollHeight
                });

                // Remove temporary container
                document.body.removeChild(tempContainer);

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

                // Add first page
                pdf.addImage(imgData, 'PNG', margin, position, imgWidth, imgHeight);
                heightLeft -= (pdfHeight - margin * 2);

                // Add additional pages if needed
                while (heightLeft > 0) {
                    position = margin - (imgHeight - heightLeft);
                    pdf.addPage();
                    pdf.addImage(imgData, 'PNG', margin, position, imgWidth, imgHeight);
                    heightLeft -= (pdfHeight - margin * 2);
                }

                // Save PDF - extract booking ID from content if possible
                const bookingIdMatch = bookingDetails.textContent.match(/Booking #(\d+)/);
                const bookingId = bookingIdMatch ? bookingIdMatch[1] : 'Unknown';
                const fileName = `Booking_${bookingId}_${new Date().toISOString().split('T')[0]}.pdf`;
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
        
        /* Confirmation modal and toast helpers */
        function showConfirm({title = 'Confirm', message = '', confirmText = 'OK', onConfirm = null}) {
            // Create modal if not exists
            let modal = document.getElementById('actionConfirmModal');
            if (!modal) return alert(message);

            modal.querySelector('.confirm-title').textContent = title;
            modal.querySelector('.confirm-message').textContent = message;
            const confirmBtn = modal.querySelector('.confirm-action-btn');
            confirmBtn.textContent = confirmText;

            // Remove previous handlers
            const newConfirm = confirmBtn.cloneNode(true);
            confirmBtn.parentNode.replaceChild(newConfirm, confirmBtn);

            newConfirm.addEventListener('click', function() {
                modal.classList.add('hidden');
                if (typeof onConfirm === 'function') onConfirm();
            });

            // Cancel button
            modal.querySelector('.confirm-cancel-btn').onclick = function() {
                modal.classList.add('hidden');
            };

            modal.classList.remove('hidden');
        }

        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            if (!container) return alert(message);

            const toast = document.createElement('div');
            toast.className = `max-w-sm w-full bg-white shadow-lg rounded-md pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden mb-3`;
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
    </script>

<!-- Confirm Modal -->
<div id="actionConfirmModal" class="fixed inset-0 bg-black bg-opacity-40 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-2xl max-w-lg w-full mx-4">
        <div class="bg-[#93BFC7] rounded-t-xl px-6 py-4 flex items-center justify-between">
            <h3 class="text-xl font-bold text-white confirm-title">Confirm</h3>
            <button onclick="document.getElementById('actionConfirmModal').classList.add('hidden')" class="text-white hover:text-gray-200">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-6">
            <p class="confirm-message text-gray-800"></p>
            <div class="mt-6 flex justify-end gap-3">
                <button class="confirm-cancel-btn bg-gray-200 text-gray-800 font-bold py-2 px-4 rounded-lg hover:bg-gray-300">Cancel</button>
                <button class="confirm-action-btn bg-[#93BFC7] text-white font-bold py-2 px-4 rounded-lg hover:bg-[#7aa8b0]">OK</button>
            </div>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div id="toastContainer" class="fixed top-6 right-6 z-60 flex flex-col items-end"></div>

</body>
</html>

