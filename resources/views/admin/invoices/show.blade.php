{{-- Invoice Modal Content (loaded via AJAX inside the payment details modal) --}}
<div class="space-y-6" id="invoiceContent">
    {{-- Invoice Header --}}
    <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-[#93BFC7]">
        <div class="flex justify-between items-start">
            <div>
                <h2 class="text-2xl font-bold text-[#93BFC7]">INVOICE</h2>
                <p class="text-gray-600 font-mono text-lg mt-1">#{{ $invoice->invoice_number }}</p>
            </div>
            <div class="text-right">
                <h3 class="text-xl font-bold text-gray-800">RJ's Event Styling</h3>
                <p class="text-gray-500 text-sm">Event Styling & Decoration Services</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-6 mt-6">
            <div>
                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Billed To</label>
                <p class="text-gray-900 font-semibold text-lg mt-1">{{ $invoice->user->name ?? 'N/A' }}</p>
                <p class="text-gray-600">{{ $invoice->user->email ?? '' }}</p>
                @if($invoice->user->phone ?? false)
                    <p class="text-gray-600">{{ $invoice->user->phone }}</p>
                @endif
            </div>
            <div class="text-right">
                <div class="mb-3">
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Invoice Date</label>
                    <p class="text-gray-900 font-medium">{{ $invoice->issued_at ? $invoice->issued_at->format('F d, Y') : now()->format('F d, Y') }}</p>
                </div>
                @if($invoice->due_date)
                <div class="mb-3">
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Due Date</label>
                    <p class="text-gray-900 font-medium">{{ $invoice->due_date->format('F d, Y') }}</p>
                </div>
                @endif
                <div>
                    <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</label>
                    <p class="mt-1">
                        @if($invoice->status === 'paid')
                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-800 font-semibold text-sm">PAID</span>
                        @elseif($invoice->status === 'partially_paid')
                            <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 font-semibold text-sm">PARTIALLY PAID</span>
                        @else
                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-800 font-semibold text-sm">UNPAID</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Booking / Service Details --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-[#93BFC7] mb-4 flex items-center">
            <i class="fas fa-calendar-alt mr-2"></i>
            Service Details
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-[#93BFC7] text-white">
                        <th class="px-4 py-3 text-left font-semibold">Description</th>
                        <th class="px-4 py-3 text-left font-semibold">Event Date</th>
                        <th class="px-4 py-3 text-left font-semibold">Location</th>
                        <th class="px-4 py-3 text-right font-semibold">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-gray-200">
                        <td class="px-4 py-4">
                            <p class="font-semibold text-gray-800 capitalize">{{ $invoice->booking->event_type ?? 'Event Styling Service' }}</p>
                            @if($invoice->booking->description)
                                <p class="text-sm text-gray-500 mt-1">{{ $invoice->booking->description }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-gray-700">
                            {{ $invoice->booking->event_date ? $invoice->booking->event_date->format('F d, Y') : 'TBD' }}
                            @if($invoice->booking->event_time)
                                <span class="block text-sm text-gray-500">{{ date('g:i A', strtotime($invoice->booking->event_time)) }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-gray-700">{{ $invoice->booking->location ?? 'TBD' }}</td>
                        <td class="px-4 py-4 text-right font-bold text-gray-800">₱{{ number_format($invoice->total_amount, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Totals --}}
        <div class="mt-6 border-t-2 border-gray-200 pt-4">
            <div class="flex justify-end">
                <div class="w-64 space-y-2">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal:</span>
                        <span class="font-medium">₱{{ number_format($invoice->subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-green-600">
                        <span>Total Paid:</span>
                        <span class="font-medium">₱{{ number_format($invoice->total_paid, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold border-t-2 border-[#93BFC7] pt-2 {{ $invoice->remaining_balance > 0 ? 'text-orange-600' : 'text-green-600' }}">
                        <span>Balance Due:</span>
                        <span>
                            @if($invoice->remaining_balance > 0)
                                ₱{{ number_format($invoice->remaining_balance, 2) }}
                            @else
                                ₱0.00
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Payment History --}}
    @if($paymentHistory->count() > 0)
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-[#93BFC7] mb-4 flex items-center">
            <i class="fas fa-history mr-2"></i>
            Payment History
        </h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-100 text-[#93BFC7] font-semibold">
                        <th class="px-4 py-3 text-left">Date</th>
                        <th class="px-4 py-3 text-left">Method</th>
                        <th class="px-4 py-3 text-left">Reference</th>
                        <th class="px-4 py-3 text-right">Amount</th>
                        <th class="px-4 py-3 text-left">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($paymentHistory as $pmt)
                    <tr class="border-b border-gray-200">
                        <td class="px-4 py-3 text-gray-700">{{ $pmt->created_at->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-gray-700 capitalize">{{ $pmt->payment_method ?? 'N/A' }}</td>
                        <td class="px-4 py-3">
                            @if($pmt->reference_number)
                                <span class="font-mono text-sm bg-gray-50 px-2 py-1 rounded border">{{ $pmt->reference_number }}</span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right font-semibold text-gray-800">₱{{ number_format($pmt->amount, 2) }}</td>
                        <td class="px-4 py-3">
                            @if($pmt->status === 'paid')
                                <span class="px-2 py-1 rounded-full bg-green-100 text-green-800 text-xs font-medium">Paid</span>
                            @elseif($pmt->status === 'partial_payment')
                                <span class="px-2 py-1 rounded-full bg-yellow-100 text-yellow-800 text-xs font-medium">Partial</span>
                            @elseif($pmt->status === 'pending')
                                <span class="px-2 py-1 rounded-full bg-gray-100 text-gray-800 text-xs font-medium">Pending</span>
                            @else
                                <span class="px-2 py-1 rounded-full bg-red-100 text-red-800 text-xs font-medium">{{ ucfirst($pmt->status) }}</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Notes --}}
    @if($invoice->notes)
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-bold text-[#93BFC7] mb-2 flex items-center">
            <i class="fas fa-sticky-note mr-2"></i>
            Notes
        </h3>
        <p class="text-gray-700">{{ $invoice->notes }}</p>
    </div>
    @endif

    {{-- Footer --}}
    <div class="text-center text-gray-500 text-sm mt-6">
        <p>Thank you for choosing RJ's Event Styling!</p>
        <p class="mt-1">Generated on {{ $invoice->issued_at ? $invoice->issued_at->format('F d, Y h:i A') : now()->format('F d, Y h:i A') }}
            @if($invoice->generator)
                by {{ $invoice->generator->name }}
            @endif
        </p>
    </div>
</div>
