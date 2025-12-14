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
    <!-- jsPDF for PDF export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
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
                <tr class="bg-[#F6F6F6] text-[#93BFC7] font-semibold hover:bg-gray-200 border-b border-gray-300 text-lg">
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
                <tr class="bg-[#F6F6F6] font-medium text-[#93BFC7] hover:bg-gray-200 border-b border-gray-300 text-base">
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
                        @if($payment->status === 'partial_payment')
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
                        @elseif($payment->status === 'partial_payment')
                            <span class="px-4 py-1 rounded-full bg-yellow-100 text-yellow-900 font-medium inline-block">
                                Partial Payment
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
                        <div class="flex items-center gap-2">
                            @if($payment->booking)
                                <button data-payment-id="{{ $payment->id }}" 
                                        onclick="viewPaymentDetails(this)" 
                                        class="inline-flex items-center gap-1 bg-[#93BFC7] text-white px-3 py-1.5 rounded-lg hover:bg-[#7eaab1] transition text-sm font-semibold"
                                        title="View Payment Details">
                                    <i class="fas fa-eye"></i>
                                    Payment
                                </button>
                                <button data-booking-id="{{ $payment->booking->id }}" 
                                        data-payment-status="{{ $payment->status }}"
                                        data-show-mark-received="{{ $payment->status == 'pending' ? 1 : 0 }}"
                                        onclick="viewBookingModalFromButton(this)" 
                                        class="inline-flex items-center gap-1 bg-blue-500 text-white px-3 py-1.5 rounded-lg hover:bg-blue-600 transition text-sm font-semibold"
                                        title="View Booking">
                                    <i class="fas fa-calendar-alt"></i>
                                    Booking
                                </button>
                            @else
                                <button data-payment-id="{{ $payment->id }}" 
                                        onclick="viewPaymentDetails(this)" 
                                        class="inline-flex items-center gap-1 bg-[#93BFC7] text-white px-3 py-1.5 rounded-lg hover:bg-[#7eaab1] transition text-sm font-semibold"
                                        title="View Payment Details">
                                    <i class="fas fa-eye"></i>
                                    View
                                </button>
                            @endif
                        </div>
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

    <!-- Payment Details Modal -->
    <div id="paymentModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-5xl w-full max-h-[90vh] overflow-hidden flex flex-col">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-[#93BFC7] to-[#7aa8b0] text-white px-6 py-4 flex items-center justify-between">
                <h3 class="text-2xl font-bold">Payment Details</h3>
                <button onclick="closePaymentModal()" class="text-white hover:text-gray-200 transition">
                    <i class="fas fa-times text-3xl"></i>
                </button>
            </div>
            
            <!-- Modal Content -->
            <div id="paymentModalContent" class="flex-1 overflow-y-auto p-6">
                <div class="flex items-center justify-center py-8">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#93BFC7]"></div>
                </div>
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
                    <i class="fas fa-times text-3xl"></i>
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
                <div class="flex justify-between items-center gap-3">
                    <!-- Print/Export Buttons (Only for Approved Bookings) -->
                    <div id="printExportButtons" class="flex gap-3 hidden">
                        <button onclick="printBookingFromModal()" 
                                class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition font-semibold">
                            <i class="fas fa-print mr-2"></i>Print
                        </button>
                        <button onclick="exportBookingToPDFFromModal()" 
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-semibold">
                            <i class="fas fa-file-pdf mr-2"></i>Export PDF
                        </button>
                    </div>
                    <!-- Action Buttons -->
                    <div class="flex justify-end gap-3 ml-auto">
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
    </div>

    <script>
        let currentBookingId = null;

        function viewPaymentDetails(button) {
            const paymentId = parseInt(button.getAttribute('data-payment-id'));
            const modal = document.getElementById('paymentModal');
            const modalContent = document.getElementById('paymentModalContent');
            
            // Show modal
            modal.classList.remove('hidden');
            
            // Show loading
            modalContent.innerHTML = '<div class="flex items-center justify-center py-8"><div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#93BFC7]"></div></div>';
            
            // Fetch payment details
            fetch(`/admin/payment/${paymentId}/details`, {
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
            })
            .catch(error => {
                console.error('Error:', error);
                modalContent.innerHTML = '<div class="text-center py-8 text-red-600">An error occurred while loading payment details.</div>';
            });
        }

        function closePaymentModal() {
            const modal = document.getElementById('paymentModal');
            modal.classList.add('hidden');
        }

        // Close payment modal when clicking outside
        document.getElementById('paymentModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePaymentModal();
            }
        });

        // Close payment modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !document.getElementById('paymentModal').classList.contains('hidden')) {
                closePaymentModal();
            }
        });

        // Print Booking Function
        function printBookingFromModal() {
            const modalContent = document.getElementById('bookingModalContent');
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Booking #${currentBookingId} - Print</title>
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
                    ${modalContent.innerHTML}
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
        async function exportBookingToPDFFromModal() {
            const { jsPDF } = window.jspdf;
            const modalContent = document.getElementById('bookingModalContent');
            
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
                    ${modalContent.innerHTML}
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

                // Save PDF
                const fileName = `Booking_${currentBookingId}_${new Date().toISOString().split('T')[0]}.pdf`;
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
                const markPaidBtn = document.getElementById('markPaidBtn');
                const markInDesignBtn = document.getElementById('markInDesignBtn');
                const markCompletedBtn = document.getElementById('markCompletedBtn');
                
                markPaidBtn.classList.add('hidden');
                markInDesignBtn.classList.add('hidden');
                markCompletedBtn.classList.add('hidden');
                
                // Extract booking status from the loaded HTML
                const statusElement = modalContent.querySelector('[class*="bg-"]');
                let detectedStatus = null;
                if (statusElement) {
                    const statusText = statusElement.textContent.trim();
                    if (statusText.includes('Partial Payment')) detectedStatus = 'partial_payment';
                    else if (statusText.includes('In Design')) detectedStatus = 'in_design';
                    else if (statusText.includes('Completed')) detectedStatus = 'completed';
                    else if (statusText.includes('Pending Payment')) detectedStatus = 'pending_payment';
                }
                
                // Show print/export buttons for approved bookings
                const printExportButtons = document.getElementById('printExportButtons');
                if (detectedStatus === 'approved' || modalContent.textContent.includes('Status') && modalContent.querySelector('[class*="bg-blue"]')) {
                    printExportButtons.classList.remove('hidden');
                } else {
                    printExportButtons.classList.add('hidden');
                }

                // Show appropriate button based on payment status and booking status
                if (paymentStatus === 'partial_payment' || detectedStatus === 'partial_payment') {
                    // Show paid button for partial_payment payments
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

            if (!confirm('Are you sure you have received the payment? This will mark the payment as partial payment and update the booking status.')) {
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
                    alert('Payment marked as partial payment! Customer has been notified.');
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

