<div class="space-y-6">
    <!-- Payment Information -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold text-[#93BFC7] mb-4 flex items-center">
            <i class="fas fa-credit-card mr-2"></i>
            Payment Information
        </h3>
        
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-semibold text-gray-600">Payment ID</label>
                <p class="text-gray-900 font-medium">#{{ $payment->id }}</p>
            </div>
            <div>
                <label class="text-sm font-semibold text-gray-600">Payment Date</label>
                <p class="text-gray-900 font-medium">{{ $payment->created_at->format('F d, Y h:i A') }}</p>
            </div>
            <div>
                <label class="text-sm font-semibold text-gray-600">Payment Method</label>
                <p class="text-gray-900 font-medium capitalize">{{ $payment->payment_method ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="text-sm font-semibold text-gray-600">Amount</label>
                <p class="text-gray-900 font-bold text-lg">₱{{ number_format($payment->amount, 2) }}</p>
            </div>
            <div>
                <label class="text-sm font-semibold text-gray-600">Status</label>
                <p class="mt-1">
                    @if($payment->status === 'paid')
                        <span class="px-3 py-1 rounded-full bg-[#D4F6DF] text-green-900 font-medium inline-block">
                            Paid
                        </span>
                    @elseif($payment->status === 'partial_payment')
                        <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-900 font-medium inline-block">
                            Partial Payment
                        </span>
                    @elseif($payment->status === 'pending')
                        <span class="px-3 py-1 rounded-full bg-[#FDFCB1] text-yellow-900 font-medium inline-block">
                            Pending
                        </span>
                    @elseif($payment->status === 'failed')
                        <span class="px-3 py-1 rounded-full bg-[#FDB1B1] text-red-900 font-medium inline-block">
                            Failed
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full bg-gray-200 text-gray-700 font-medium inline-block">
                            {{ ucfirst($payment->status) }}
                        </span>
                    @endif
                </p>
            </div>
            @if($payment->paid_at)
            <div>
                <label class="text-sm font-semibold text-gray-600">Paid At</label>
                <p class="text-gray-900 font-medium">{{ $payment->paid_at->format('F d, Y h:i A') }}</p>
            </div>
            @endif
        </div>

        <!-- Reference Number (for GCash/Maya) -->
        @if($payment->reference_number)
        <div class="mt-4 pt-4 border-t border-gray-200">
            <label class="text-sm font-semibold text-gray-600">Reference Number</label>
            <div class="mt-2 flex items-center gap-2">
                <p class="text-gray-900 font-mono font-semibold text-lg bg-gray-50 px-4 py-2 rounded-lg border-2 border-[#93BFC7]">
                    {{ $payment->reference_number }}
                </p>
                <button onclick="copyReferenceNumber('{{ $payment->reference_number }}')" 
                        class="px-3 py-2 bg-[#93BFC7] text-white rounded-lg hover:bg-[#7eaab1] transition"
                        title="Copy Reference Number">
                    <i class="fas fa-copy"></i>
                </button>
            </div>
        </div>
        @endif

        <!-- Payment Screenshot (for GCash/Maya) -->
        @if($payment->payment_screenshot)
        <div class="mt-4 pt-4 border-t border-gray-200">
            <label class="text-sm font-semibold text-gray-600 mb-2 block">Payment Screenshot/Proof</label>
            <div class="relative">
                <img src="{{ asset('storage/' . $payment->payment_screenshot) }}" 
                     alt="Payment Screenshot" 
                     class="max-w-full h-auto rounded-lg border-2 border-gray-200 shadow-md cursor-pointer"
                     onclick="openImageModal('{{ asset('storage/' . $payment->payment_screenshot) }}')">
                <div class="absolute top-2 right-2">
                    <a href="{{ asset('storage/' . $payment->payment_screenshot) }}" 
                       download
                       class="px-3 py-2 bg-white bg-opacity-90 rounded-lg hover:bg-opacity-100 transition shadow-md"
                       title="Download Screenshot">
                        <i class="fas fa-download text-[#93BFC7]"></i>
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Customer Information -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold text-[#93BFC7] mb-4 flex items-center">
            <i class="fas fa-user mr-2"></i>
            Customer Information
        </h3>
        
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-semibold text-gray-600">Name</label>
                <p class="text-gray-900 font-medium">{{ $payment->user->name ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="text-sm font-semibold text-gray-600">Email</label>
                <p class="text-gray-900 font-medium">{{ $payment->user->email ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    <!-- Booking Information -->
    @if($payment->booking)
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold text-[#93BFC7] mb-4 flex items-center">
            <i class="fas fa-calendar-alt mr-2"></i>
            Booking Information
        </h3>
        
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-semibold text-gray-600">Booking ID</label>
                <p class="text-gray-900 font-medium">#{{ $payment->booking->id }}</p>
            </div>
            <div>
                <label class="text-sm font-semibold text-gray-600">Event Type</label>
                <p class="text-gray-900 font-medium capitalize">{{ $payment->booking->event_type }}</p>
            </div>
            <div>
                <label class="text-sm font-semibold text-gray-600">Event Date</label>
                <p class="text-gray-900 font-medium">{{ $payment->booking->event_date->format('F d, Y') }}</p>
            </div>
            @if($payment->booking->event_time)
            <div>
                <label class="text-sm font-semibold text-gray-600">Event Time</label>
                <p class="text-gray-900 font-medium">{{ date('g:i A', strtotime($payment->booking->event_time)) }}</p>
            </div>
            @endif
            <div>
                <label class="text-sm font-semibold text-gray-600">Total Amount</label>
                <p class="text-gray-900 font-bold text-lg">₱{{ number_format($payment->booking->total_amount, 2) }}</p>
            </div>
            <div>
                <label class="text-sm font-semibold text-gray-600">Remaining Balance</label>
                <p class="text-gray-900 font-bold text-lg {{ $remainingBalance > 0 ? 'text-orange-600' : 'text-green-600' }}">
                    @if($remainingBalance > 0)
                        ₱{{ number_format($remainingBalance, 2) }}
                    @else
                        Fully Paid
                    @endif
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Payment History -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-bold text-[#93BFC7] mb-4 flex items-center">
            <i class="fas fa-history mr-2"></i>
            Payment History for Booking #{{ $payment->booking_id }}
        </h3>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-100 text-[#93BFC7] font-semibold">
                        <th class="px-4 py-3 text-left">Date</th>
                        <th class="px-4 py-3 text-left">Payment Method</th>
                        <th class="px-4 py-3 text-left">Amount</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Reference</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($paymentHistory as $histPayment)
                    <tr class="border-b border-gray-200 {{ $histPayment->id === $payment->id ? 'bg-blue-50' : '' }}">
                        <td class="px-4 py-3">{{ $histPayment->created_at->format('M d, Y h:i A') }}</td>
                        <td class="px-4 py-3 capitalize">{{ $histPayment->payment_method ?? 'N/A' }}</td>
                        <td class="px-4 py-3 font-semibold">₱{{ number_format($histPayment->amount, 2) }}</td>
                        <td class="px-4 py-3">
                            @if($histPayment->status === 'paid')
                                <span class="px-2 py-1 rounded-full bg-[#D4F6DF] text-green-900 text-xs font-medium">
                                    Paid
                                </span>
                            @elseif($histPayment->status === 'partial_payment')
                                <span class="px-2 py-1 rounded-full bg-yellow-100 text-yellow-900 text-xs font-medium">
                                    Partial
                                </span>
                            @elseif($histPayment->status === 'pending')
                                <span class="px-2 py-1 rounded-full bg-[#FDFCB1] text-yellow-900 text-xs font-medium">
                                    Pending
                                </span>
                            @elseif($histPayment->status === 'failed')
                                <span class="px-2 py-1 rounded-full bg-[#FDB1B1] text-red-900 text-xs font-medium">
                                    Failed
                                </span>
                            @else
                                <span class="px-2 py-1 rounded-full bg-gray-200 text-gray-700 text-xs font-medium">
                                    {{ ucfirst($histPayment->status) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($histPayment->reference_number)
                                <span class="font-mono text-sm bg-gray-50 px-2 py-1 rounded border">
                                    {{ $histPayment->reference_number }}
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-4 pt-4 border-t border-gray-200">
            <div class="flex justify-between items-center">
                <span class="text-sm font-semibold text-gray-600">Total Paid:</span>
                <span class="text-lg font-bold text-green-600">₱{{ number_format($totalPaid, 2) }}</span>
            </div>
            <div class="flex justify-between items-center mt-2">
                <span class="text-sm font-semibold text-gray-600">Remaining Balance:</span>
                <span class="text-lg font-bold {{ $remainingBalance > 0 ? 'text-orange-600' : 'text-green-600' }}">
                    @if($remainingBalance > 0)
                        ₱{{ number_format($remainingBalance, 2) }}
                    @else
                        Fully Paid
                    @endif
                </span>
            </div>
        </div>
    </div>
</div>

<script>
function copyReferenceNumber(refNumber) {
    navigator.clipboard.writeText(refNumber).then(function() {
        alert('Reference number copied to clipboard!');
    }, function() {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = refNumber;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        alert('Reference number copied to clipboard!');
    });
}

function openImageModal(imageSrc) {
    // Create modal overlay
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4';
    modal.onclick = function(e) {
        if (e.target === modal) {
            document.body.removeChild(modal);
        }
    };
    
    // Create image container
    const imgContainer = document.createElement('div');
    imgContainer.className = 'max-w-4xl max-h-full';
    
    const img = document.createElement('img');
    img.src = imageSrc;
    img.className = 'max-w-full max-h-[90vh] rounded-lg shadow-2xl';
    img.alt = 'Payment Screenshot';
    
    // Close button
    const closeBtn = document.createElement('button');
    closeBtn.className = 'absolute top-4 right-4 text-white bg-black bg-opacity-50 hover:bg-opacity-75 rounded-full p-2 transition';
    closeBtn.innerHTML = '<i class="fas fa-times text-xl"></i>';
    closeBtn.onclick = function() {
        document.body.removeChild(modal);
    };
    
    imgContainer.appendChild(img);
    modal.appendChild(imgContainer);
    modal.appendChild(closeBtn);
    document.body.appendChild(modal);
}
</script>

