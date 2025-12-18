<div class="space-y-6">
    <!-- Booking Header -->
    <div class="bg-gradient-to-r from-[#93BFC7] to-[#7aa8b0] rounded-lg p-4 text-white">
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div>
                <h3 class="text-2xl font-bold mb-1">Booking #{{ $booking->booking_id }}</h3>
                <p class="text-sm opacity-90">Created on {{ $booking->created_at->format('F d, Y \a\t g:i A') }}</p>
            </div>
            <div class="text-right">
                <span class="inline-block px-4 py-2 rounded-full text-sm font-bold
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
            </div>
        </div>
    </div>

    <!-- Customer Information -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
            <h4 class="text-lg font-bold text-gray-800 flex items-center">
                <i class="fas fa-user-circle mr-2 text-[#93BFC7]"></i>Customer Information
            </h4>
        </div>
        <div class="p-6">
            <table class="w-full">
                <tbody class="divide-y divide-gray-100">
                    <tr>
                        <td class="py-3 px-4 font-semibold text-gray-700 w-1/3">Customer Name</td>
                        <td class="py-3 px-4 text-gray-900">{{ $booking->user->name }}</td>
                    </tr>
                    <tr>
                        <td class="py-3 px-4 font-semibold text-gray-700">Email Address</td>
                        <td class="py-3 px-4 text-gray-900">{{ $booking->user->email }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Event Information -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
            <h4 class="text-lg font-bold text-gray-800 flex items-center">
                <i class="fas fa-calendar-alt mr-2 text-[#93BFC7]"></i>Event Information
            </h4>
        </div>
        <div class="p-6">
            <table class="w-full">
                <tbody class="divide-y divide-gray-100">
                    <tr>
                        <td class="py-3 px-4 font-semibold text-gray-700 w-1/3">Event Type</td>
                        <td class="py-3 px-4 text-gray-900">
                            <span class="capitalize px-3 py-1 bg-[#93BFC7] text-white rounded-full text-sm font-semibold">
                                {{ $booking->event_type }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="py-3 px-4 font-semibold text-gray-700">Event Date</td>
                        <td class="py-3 px-4 text-gray-900">
                            <i class="fas fa-calendar mr-2 text-[#93BFC7]"></i>
                            {{ $booking->event_date->format('F d, Y') }}
                            @if($booking->event_time)
                                <span class="ml-2">
                                    <i class="fas fa-clock mr-1 text-[#93BFC7]"></i>
                                    {{ date('g:i A', strtotime($booking->event_time)) }}
                                </span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="py-3 px-4 font-semibold text-gray-700">Location</td>
                        <td class="py-3 px-4 text-gray-900">
                            <i class="fas fa-map-marker-alt mr-2 text-[#93BFC7]"></i>
                            {{ $booking->location }}
                        </td>
                    </tr>
                    <tr>
                        <td class="py-3 px-4 font-semibold text-gray-700">Total Amount</td>
                        <td class="py-3 px-4 text-gray-900">
                            <span class="text-xl font-bold text-[#93BFC7]">₱{{ number_format($booking->total_amount, 2) }}</span>
                        </td>
                    </tr>
                    @if($booking->meetup_date && $booking->meetup_time)
                    <tr>
                        <td class="py-3 px-4 font-semibold text-gray-700">Meetup Schedule</td>
                        <td class="py-3 px-4 text-gray-900">
                            <i class="fas fa-handshake mr-2 text-[#93BFC7]"></i>
                            <span class="font-semibold">{{ $booking->meetup_date->format('F d, Y') }}</span>
                            <span class="ml-2">at <span class="font-semibold">{{ date('g:i A', strtotime($booking->meetup_time)) }}</span></span>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Event-Specific Details -->
    @if($booking->event_details)
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
            <h4 class="text-lg font-bold text-gray-800 flex items-center">
                <i class="fas fa-info-circle mr-2 text-[#93BFC7]"></i>
                {{ ucfirst($booking->event_type) }} Details
            </h4>
        </div>
        <div class="p-6">
            @php
                $details = is_array($booking->event_details) ? $booking->event_details : json_decode($booking->event_details, true);
            @endphp
            
            @if($booking->event_type === 'wedding')
                <table class="w-full">
                    <tbody class="divide-y divide-gray-100">
                        <tr>
                            <td class="py-3 px-4 font-semibold text-gray-700 w-1/3">Bride Name</td>
                            <td class="py-3 px-4 text-gray-900">{{ $details['bride'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 font-semibold text-gray-700">Groom Name</td>
                            <td class="py-3 px-4 text-gray-900">{{ $details['groom'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 font-semibold text-gray-700">Number of Guests</td>
                            <td class="py-3 px-4 text-gray-900">{{ $details['guests'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 font-semibold text-gray-700">Ceremony Venue</td>
                            <td class="py-3 px-4 text-gray-900">{{ $details['ceremony_venue'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 font-semibold text-gray-700">Reception Venue</td>
                            <td class="py-3 px-4 text-gray-900">{{ $details['reception_venue'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 font-semibold text-gray-700">Theme / Motif</td>
                            <td class="py-3 px-4 text-gray-900">{{ $details['theme'] ?? 'N/A' }}</td>
                        </tr>
                        @if(isset($details['notes']) && $details['notes'])
                        <tr>
                            <td class="py-3 px-4 font-semibold text-gray-700">Additional Notes</td>
                            <td class="py-3 px-4 text-gray-900">{{ $details['notes'] }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            @elseif($booking->event_type === 'birthday')
                <table class="w-full">
                    <tbody class="divide-y divide-gray-100">
                        <tr>
                            <td class="py-3 px-4 font-semibold text-gray-700 w-1/3">Celebrant Name</td>
                            <td class="py-3 px-4 text-gray-900">{{ $details['celebrant'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 font-semibold text-gray-700">Age</td>
                            <td class="py-3 px-4 text-gray-900">{{ $details['age'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 font-semibold text-gray-700">Number of Guests</td>
                            <td class="py-3 px-4 text-gray-900">{{ $details['guests'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 font-semibold text-gray-700">Theme / Motif</td>
                            <td class="py-3 px-4 text-gray-900">{{ $details['theme'] ?? 'N/A' }}</td>
                        </tr>
                    </tbody>
                </table>
            @elseif($booking->event_type === 'christening')
                <table class="w-full">
                    <tbody class="divide-y divide-gray-100">
                        <tr>
                            <td class="py-3 px-4 font-semibold text-gray-700 w-1/3">Child's Name</td>
                            <td class="py-3 px-4 text-gray-900">{{ $details['child'] ?? $details['child_name'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 font-semibold text-gray-700">Parents' Names</td>
                            <td class="py-3 px-4 text-gray-900">{{ $details['parents'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 font-semibold text-gray-700">Number of Guests</td>
                            <td class="py-3 px-4 text-gray-900">{{ $details['guests'] ?? 'N/A' }}</td>
                        </tr>
                        @if(isset($details['notes']) && $details['notes'])
                        <tr>
                            <td class="py-3 px-4 font-semibold text-gray-700">Additional Notes</td>
                            <td class="py-3 px-4 text-gray-900">{{ $details['notes'] }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            @elseif($booking->event_type === 'debut')
                <table class="w-full">
                    <tbody class="divide-y divide-gray-100">
                        <tr>
                            <td class="py-3 px-4 font-semibold text-gray-700 w-1/3">Debutante Name</td>
                            <td class="py-3 px-4 text-gray-900">{{ $details['debutante'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 font-semibold text-gray-700">Number of Guests</td>
                            <td class="py-3 px-4 text-gray-900">{{ $details['guests'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 font-semibold text-gray-700">18 Roses Participants</td>
                            <td class="py-3 px-4 text-gray-900">
                                @if(isset($details['roses']) && is_array($details['roses']))
                                    {{ implode(', ', $details['roses']) }}
                                @else
                                    {{ $details['roses'] ?? 'N/A' }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 font-semibold text-gray-700">18 Candles Participants</td>
                            <td class="py-3 px-4 text-gray-900">
                                @if(isset($details['candles']) && is_array($details['candles']))
                                    {{ implode(', ', $details['candles']) }}
                                @else
                                    {{ $details['candles'] ?? 'N/A' }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 font-semibold text-gray-700">18 Treasures Participants</td>
                            <td class="py-3 px-4 text-gray-900">
                                @if(isset($details['treasures']) && is_array($details['treasures']))
                                    {{ implode(', ', $details['treasures']) }}
                                @else
                                    {{ $details['treasures'] ?? 'N/A' }}
                                @endif
                            </td>
                        </tr>
                        @if(isset($details['notes']) && $details['notes'])
                        <tr>
                            <td class="py-3 px-4 font-semibold text-gray-700">Program Notes</td>
                            <td class="py-3 px-4 text-gray-900">{{ $details['notes'] }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            @elseif($booking->event_type === 'pageant')
                <table class="w-full">
                    <tbody class="divide-y divide-gray-100">
                        <tr>
                            <td class="py-3 px-4 font-semibold text-gray-700 w-1/3">Organizer Name</td>
                            <td class="py-3 px-4 text-gray-900">{{ $details['organizer'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 font-semibold text-gray-700">Pageant Title</td>
                            <td class="py-3 px-4 text-gray-900">{{ $details['title'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 font-semibold text-gray-700">Number of Guests</td>
                            <td class="py-3 px-4 text-gray-900">{{ $details['guests'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 font-semibold text-gray-700">Number of Contestants</td>
                            <td class="py-3 px-4 text-gray-900">{{ $details['contestants'] ?? 'N/A' }}</td>
                        </tr>
                        @if(isset($details['notes']) && $details['notes'])
                        <tr>
                            <td class="py-3 px-4 font-semibold text-gray-700">Additional Notes</td>
                            <td class="py-3 px-4 text-gray-900">{{ $details['notes'] }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            @elseif($booking->event_type === 'corporate')
                <table class="w-full">
                    <tbody class="divide-y divide-gray-100">
                        <tr>
                            <td class="py-3 px-4 font-semibold text-gray-700 w-1/3">Company Representative</td>
                            <td class="py-3 px-4 text-gray-900">{{ $details['representative'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 font-semibold text-gray-700">Company Name</td>
                            <td class="py-3 px-4 text-gray-900">{{ $details['company'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 font-semibold text-gray-700">Event Title / Theme</td>
                            <td class="py-3 px-4 text-gray-900">{{ $details['title'] ?? $details['theme'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 font-semibold text-gray-700">Number of Attendees</td>
                            <td class="py-3 px-4 text-gray-900">{{ $details['attendees'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 font-semibold text-gray-700">Contact Number</td>
                            <td class="py-3 px-4 text-gray-900">{{ $details['contact'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 font-semibold text-gray-700">Event Requirements</td>
                            <td class="py-3 px-4 text-gray-900">{{ $details['requirements'] ?? 'N/A' }}</td>
                        </tr>
                    </tbody>
                </table>
            @else
                <div class="bg-gray-50 p-4 rounded-lg">
                    <pre class="text-sm text-gray-800 whitespace-pre-wrap">{{ json_encode($details, JSON_PRETTY_PRINT) }}</pre>
                </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Special Request -->
    @if($booking->description)
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
            <h4 class="text-lg font-bold text-gray-800 flex items-center">
                <i class="fas fa-comment-dots mr-2 text-[#93BFC7]"></i>Special Request
            </h4>
        </div>
        <div class="p-6">
            <p class="text-gray-900 leading-relaxed">{{ $booking->description }}</p>
        </div>
    </div>
    @endif

    <!-- Payment Information -->
    @php
        $payments = $booking->payments()->orderBy('created_at', 'desc')->get();
        $totalPaid = $booking->payments()->whereIn('status', ['paid', 'partial_payment'])->sum('amount');
        $remainingAmount = $booking->total_amount - $totalPaid;
        $pendingPayment = $booking->payments()->where('status', 'pending')->first();
        $partialPaidPayment = $booking->payments()->where('status', 'partial_payment')->first();
    @endphp
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
            <h4 class="text-lg font-bold text-gray-800 flex items-center">
                <i class="fas fa-money-bill-wave mr-2 text-[#93BFC7]"></i>Payment Information
            </h4>
        </div>
        <div class="p-6">
            <table class="w-full mb-4">
                <tbody class="divide-y divide-gray-100">
                    <tr>
                        <td class="py-3 px-4 font-semibold text-gray-700 w-1/3">Total Amount</td>
                        <td class="py-3 px-4 text-gray-900">
                            <span class="text-xl font-bold text-[#93BFC7]">₱{{ number_format($booking->total_amount, 2) }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="py-3 px-4 font-semibold text-gray-700">Total Paid</td>
                        <td class="py-3 px-4 text-gray-900">
                            <span class="text-lg font-semibold text-green-600">₱{{ number_format($totalPaid, 2) }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="py-3 px-4 font-semibold text-gray-700">Remaining Balance</td>
                        <td class="py-3 px-4 text-gray-900">
                            <span class="text-lg font-semibold text-orange-600">₱{{ number_format($remainingAmount, 2) }}</span>
                        </td>
                    </tr>
                </tbody>
            </table>
            
            @if($payments->count() > 0)
            <div class="mt-4">
                <h5 class="font-semibold text-gray-700 mb-2">Payment History</h5>
                <div class="space-y-2">
                    @foreach($payments as $payment)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <span class="font-medium">₱{{ number_format($payment->amount, 2) }}</span>
                            <span class="text-sm text-gray-600 ml-2">({{ ucfirst($payment->payment_method) }})</span>
                            <span class="text-xs text-gray-500 ml-2">{{ $payment->created_at->format('M d, Y') }}</span>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            @if($payment->status == 'paid') bg-green-100 text-green-800
                            @elseif($payment->status == 'partial_payment') bg-yellow-100 text-yellow-800
                            @elseif($payment->status == 'pending') bg-orange-100 text-orange-800
                            @elseif($payment->status == 'failed') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ $payment->status == 'partial_payment' ? 'Partial Payment' : ucfirst($payment->status) }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Assigned Inventory Section -->
    @php
        $inventoryAssignments = $booking->inventoryAssignments()->with('inventory')->get();
    @endphp
    @if($inventoryAssignments->count() > 0 || in_array($booking->status, ['approved', 'in_design', 'completed']))
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="bg-gray-50 px-6 py-3 border-b border-gray-200 flex items-center justify-between">
            <h4 class="text-lg font-bold text-gray-800 flex items-center">
                <i class="fas fa-boxes mr-2 text-[#93BFC7]"></i>Assigned Inventory
            </h4>
            @if(in_array($booking->status, ['approved', 'in_design']) && $booking->status !== 'completed')
            <button data-booking-id="{{ $booking->booking_id }}" 
                    onclick="openAssignInventoryModal(this.dataset.bookingId)" 
                    class="px-4 py-2 bg-[#93BFC7] text-white font-semibold rounded-lg hover:bg-[#7eaab1] transition text-sm">
                <i class="fas fa-plus mr-1"></i>Assign Inventory
            </button>
            @endif
        </div>
        <div class="p-6">
            @if($inventoryAssignments->count() > 0)
            <div class="space-y-3">
                @foreach($inventoryAssignments as $assignment)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-box text-indigo-600"></i>
                        </div>
                        <div>
                            <h5 class="font-semibold text-gray-800">{{ $assignment->inventory->item_name }}</h5>
                            <p class="text-sm text-gray-500">{{ $assignment->inventory->category }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-gray-800">{{ number_format($assignment->quantity_assigned, 2) }} {{ $assignment->inventory->unit }}</p>
                        <span class="inline-block px-2 py-1 rounded text-xs font-medium
                            {{ $assignment->status === 'returned' ? 'bg-green-100 text-green-800' : 
                               ($assignment->status === 'assigned' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ ucfirst(str_replace('_', ' ', $assignment->status)) }}
                        </span>
                        @if($assignment->quantity_damaged > 0)
                        <p class="text-xs text-red-600 mt-1">
                            <i class="fas fa-exclamation-triangle mr-1"></i>{{ number_format($assignment->quantity_damaged, 2) }} damaged
                        </p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-6 text-gray-500">
                <i class="fas fa-box-open text-3xl mb-2"></i>
                <p>No inventory assigned to this booking yet.</p>
                @if(in_array($booking->status, ['approved', 'in_design']))
                <button data-booking-id="{{ $booking->booking_id }}" 
                        onclick="openAssignInventoryModal(this.dataset.bookingId)" 
                        class="mt-3 px-4 py-2 bg-[#93BFC7] text-white font-semibold rounded-lg hover:bg-[#7eaab1] transition text-sm">
                    <i class="fas fa-plus mr-1"></i>Assign Inventory Now
                </button>
                @endif
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Print/Export Buttons (Only for Approved Bookings) -->
    @if($booking->status == 'approved')
    <div class="flex gap-3 pt-4 pb-4 border-t border-gray-200">
        <button onclick="printBooking()" 
                class="flex-1 bg-gray-600 text-white font-bold py-3 rounded-lg hover:bg-gray-700 transition flex items-center justify-center">
            <i class="fas fa-print mr-2"></i>Print Booking
        </button>
        <button onclick="exportBookingToPDF()" 
                class="flex-1 bg-red-600 text-white font-bold py-3 rounded-lg hover:bg-red-700 transition flex items-center justify-center">
            <i class="fas fa-file-pdf mr-2"></i>Export PDF
        </button>
    </div>
    @endif

    <!-- Action Buttons -->
    <div class="flex gap-3 pt-4">
        @if($booking->status == 'pending')
            <button data-booking-id="{{ $booking->booking_id }}" 
                    onclick="if(typeof confirmBooking === 'function') confirmBooking(this.dataset.bookingId)" 
                    class="flex-1 bg-green-500 text-white font-bold py-3 rounded-lg hover:bg-green-600 transition flex items-center justify-center">
                <i class="fas fa-check mr-2"></i>Confirm Booking
            </button>
        @endif
        @if(!in_array($booking->status, ['cancelled', 'completed']))
            <button data-booking-id="{{ $booking->booking_id }}"
                    onclick="if(typeof cancelBooking === 'function') cancelBooking(this.dataset.bookingId)"
                    class="flex-1 bg-red-500 text-white font-bold py-3 rounded-lg hover:bg-red-600 transition flex items-center justify-center">
                <i class="fas fa-times mr-2"></i>Cancel Booking
            </button>
        @endif
        @if(!$booking->archived_at)
            <button data-booking-id="{{ $booking->booking_id }}"
                    onclick="if(typeof archiveBooking === 'function') archiveBooking(this.dataset.bookingId)"
                    class="flex-1 bg-gray-500 text-white font-bold py-3 rounded-lg hover:bg-gray-600 transition flex items-center justify-center">
                <i class="fas fa-archive mr-2"></i>Archive
            </button>
        @endif
        @if($booking->status == 'confirmed' && $booking->communication_method == 'meetup' && $booking->meetup_date)
            <div class="flex-1 bg-blue-50 border-2 border-blue-200 rounded-lg p-3 text-center">
                <p class="text-sm text-blue-800 font-semibold">
                    <i class="fas fa-calendar-check mr-2"></i>Customer chose meetup
                </p>
                <p class="text-xs text-blue-600 mt-1">
                    {{ $booking->meetup_date->format('M d, Y') }} at {{ date('g:i A', strtotime($booking->meetup_time)) }}
                </p>
            </div>
        @endif
        @if($booking->status == 'confirmed' && $booking->communication_method == 'messaging')
            <div class="flex-1 bg-purple-50 border-2 border-purple-200 rounded-lg p-3 text-center">
                <p class="text-sm text-purple-800 font-semibold">
                    <i class="fas fa-comments mr-2"></i>Customer chose messaging
                </p>
                <p class="text-xs text-purple-600 mt-1">
                    Continue communication via Messages
                </p>
            </div>
        @endif
        @if($booking->status == 'confirmed' && !$booking->communication_method)
            <div class="flex-1 bg-yellow-50 border-2 border-yellow-200 rounded-lg p-3 text-center">
                <p class="text-sm text-yellow-800 font-semibold">
                    <i class="fas fa-clock mr-2"></i>Waiting for customer's choice
                </p>
                <p class="text-xs text-yellow-600 mt-1">
                    Customer will choose meetup or messaging
                </p>
            </div>
        @endif
        @if(in_array($booking->status, ['confirmed', 'approved']) && $booking->status != 'pending_payment' && !$booking->payments()->where('status', 'paid')->exists())
            <button data-booking-id="{{ $booking->booking_id }}" 
                    onclick="if(typeof markForPayment === 'function') markForPayment(this.dataset.bookingId)" 
                    class="flex-1 bg-blue-500 text-white font-bold py-3 rounded-lg hover:bg-blue-600 transition flex items-center justify-center">
                <i class="fas fa-credit-card mr-2"></i>Mark for Payment
            </button>
        @endif
        @if($booking->status == 'pending_payment')
            <div class="flex-1 bg-orange-50 border-2 border-orange-200 rounded-lg p-3 text-center">
                <p class="text-sm text-orange-800 font-semibold">
                    <i class="fas fa-credit-card mr-2"></i>Pending Payment
                </p>
                <p class="text-xs text-orange-600 mt-1">
                    Customer has been notified to proceed with payment
                </p>
            </div>
            @if($pendingPayment)
            <button data-booking-id="{{ $booking->booking_id }}" 
                    onclick="if(typeof markPaymentAsPartialPaid === 'function') markPaymentAsPartialPaid(this.dataset.bookingId)" 
                    class="flex-1 bg-yellow-500 text-white font-bold py-3 rounded-lg hover:bg-yellow-600 transition flex items-center justify-center">
                <i class="fas fa-check-circle mr-2"></i>Mark as Partial Payment
            </button>
            @endif
        @endif
        @if($booking->status == 'partial_payment')
            <div class="flex-1 bg-yellow-50 border-2 border-yellow-200 rounded-lg p-3 text-center">
                <p class="text-sm text-yellow-800 font-semibold">
                    <i class="fas fa-money-bill-wave mr-2"></i>Partial Payment Received
                </p>
                <p class="text-xs text-yellow-600 mt-1">
                    @if($remainingAmount > 0)
                        Remaining balance: ₱{{ number_format($remainingAmount, 2) }}
                    @else
                        Fully Paid - Ready for Design
                    @endif
                </p>
            </div>
            @if($partialPaidPayment)
            <button data-booking-id="{{ $booking->booking_id }}" 
                    onclick="if(typeof markPaymentAsPaid === 'function') markPaymentAsPaid(this.dataset.bookingId)" 
                    class="flex-1 bg-green-500 text-white font-bold py-3 rounded-lg hover:bg-green-600 transition flex items-center justify-center">
                <i class="fas fa-check-circle mr-2"></i>Mark as Paid
            </button>
            @endif
            @if($remainingAmount <= 0)
            <button data-booking-id="{{ $booking->booking_id }}" 
                    onclick="if(typeof markAsInDesign === 'function') markAsInDesign(this.dataset.bookingId)" 
                    class="flex-1 bg-indigo-500 text-white font-bold py-3 rounded-lg hover:bg-indigo-600 transition flex items-center justify-center">
                <i class="fas fa-palette mr-2"></i>Move to Design Phase
            </button>
            @endif
        @endif
        @if($booking->status == 'in_design')
            <div class="flex-1 bg-indigo-50 border-2 border-indigo-200 rounded-lg p-3 text-center">
                <p class="text-sm text-indigo-800 font-semibold">
                    <i class="fas fa-palette mr-2"></i>In Design Phase
                </p>
                <p class="text-xs text-indigo-600 mt-1">
                    Event design is in progress
                </p>
            </div>
            <button data-booking-id="{{ $booking->booking_id }}" 
                    onclick="if(typeof markAsCompleted === 'function') markAsCompleted(this.dataset.bookingId)" 
                    class="flex-1 bg-purple-500 text-white font-bold py-3 rounded-lg hover:bg-purple-600 transition flex items-center justify-center">
                <i class="fas fa-check-circle mr-2"></i>Mark Event as Successful
            </button>
        @endif
        @if($booking->status == 'completed')
            <div class="flex-1 bg-purple-50 border-2 border-purple-200 rounded-lg p-3 text-center">
                <p class="text-sm text-purple-800 font-semibold">
                    <i class="fas fa-check-circle mr-2"></i>Event Completed Successfully
                </p>
                <p class="text-xs text-purple-600 mt-1">
                    This event has been successfully completed
                </p>
            </div>
        @endif
    </div>
</div>

<!-- Assign Inventory Modal -->
<div id="assignInventoryModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="closeAssignInventoryModal()"></div>
        
        <!-- Modal Content -->
        <div class="relative inline-block align-middle bg-white rounded-xl shadow-2xl text-left overflow-hidden transform transition-all sm:my-8 sm:max-w-2xl sm:w-full">
            <div class="bg-gradient-to-r from-[#93BFC7] to-[#7eaab1] px-6 py-4 flex items-center justify-between">
                <h3 class="text-xl font-bold text-white">
                    <i class="fas fa-boxes mr-2"></i>Assign Inventory
                </h3>
                <button onclick="closeAssignInventoryModal()" class="text-white hover:text-gray-200 transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="assignInventoryForm" class="p-6">
                @csrf
                <input type="hidden" id="assignBookingId" name="booking_id">
                
                <div class="mb-6">
                    <p class="text-sm text-gray-600 mb-4">
                        <i class="fas fa-info-circle mr-1 text-[#93BFC7]"></i>
                        Select inventory items and quantities to assign to this booking.
                    </p>
                </div>
                
                <!-- Inventory Items Container -->
                <div id="inventoryItemsContainer" class="space-y-4 max-h-80 overflow-y-auto mb-6">
                    <!-- Dynamic inventory items will be loaded here -->
                    <div class="text-center py-4 text-gray-500">
                        <i class="fas fa-spinner fa-spin mr-2"></i>Loading available inventory...
                    </div>
                </div>
                
                <!-- Add More Button -->
                <button type="button" onclick="addInventoryRow()" class="w-full py-2 border-2 border-dashed border-gray-300 rounded-lg text-gray-500 hover:border-[#93BFC7] hover:text-[#93BFC7] transition">
                    <i class="fas fa-plus mr-2"></i>Add Another Item
                </button>
                
                <div class="flex gap-3 mt-6 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeAssignInventoryModal()" class="flex-1 bg-gray-200 text-gray-700 font-bold py-3 rounded-lg hover:bg-gray-300 transition">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </button>
                    <button type="submit" class="flex-1 bg-[#93BFC7] text-white font-bold py-3 rounded-lg hover:bg-[#7eaab1] transition">
                        <i class="fas fa-check mr-2"></i>Assign Inventory
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Available inventory data
let availableInventory = [];
let inventoryRowCount = 0;

// Open Assign Inventory Modal
function openAssignInventoryModal(bookingId) {
    document.getElementById('assignBookingId').value = bookingId;
    document.getElementById('assignInventoryModal').classList.remove('hidden');
    loadAvailableInventory();
}

// Close Assign Inventory Modal
function closeAssignInventoryModal() {
    document.getElementById('assignInventoryModal').classList.add('hidden');
    document.getElementById('inventoryItemsContainer').innerHTML = '<div class="text-center py-4 text-gray-500"><i class="fas fa-spinner fa-spin mr-2"></i>Loading available inventory...</div>';
    inventoryRowCount = 0;
}

// Load Available Inventory
function loadAvailableInventory() {
    fetch('/admin/inventory/available/list')
        .then(response => response.json())
        .then(data => {
            availableInventory = data;
            document.getElementById('inventoryItemsContainer').innerHTML = '';
            if (data.length === 0) {
                document.getElementById('inventoryItemsContainer').innerHTML = '<div class="text-center py-4 text-gray-500"><i class="fas fa-box-open mr-2"></i>No inventory available for assignment.</div>';
            } else {
                addInventoryRow();
            }
        })
        .catch(error => {
            console.error('Error loading inventory:', error);
            document.getElementById('inventoryItemsContainer').innerHTML = '<div class="text-center py-4 text-red-500"><i class="fas fa-exclamation-circle mr-2"></i>Error loading inventory.</div>';
        });
}

// Add Inventory Row
function addInventoryRow() {
    if (availableInventory.length === 0) return;
    
    inventoryRowCount++;
    const rowId = inventoryRowCount;
    
    let optionsHtml = '<option value="">Select Item...</option>';
    availableInventory.forEach(item => {
        optionsHtml += `<option value="${item.id}" data-unit="${item.unit}" data-available="${item.available_stock}">${item.item_name} (${parseFloat(item.available_stock).toFixed(2)} ${item.unit} available)</option>`;
    });
    
    const rowHtml = `
        <div id="inventoryRow${rowId}" class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Item</label>
                <select name="inventory[${rowId}][id]" onchange="updateQuantityMax(${rowId}, this)" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#93BFC7] focus:border-transparent">
                    ${optionsHtml}
                </select>
            </div>
            <div class="w-32">
                <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                <input type="number" name="inventory[${rowId}][quantity]" step="0.01" min="0.01" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#93BFC7] focus:border-transparent" 
                       placeholder="0.00" required>
            </div>
            <div class="w-16 text-center pt-6">
                <span id="unit${rowId}" class="text-sm text-gray-500">-</span>
            </div>
            <button type="button" onclick="removeInventoryRow(${rowId})" class="pt-6 text-red-500 hover:text-red-700 transition">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;
    
    document.getElementById('inventoryItemsContainer').insertAdjacentHTML('beforeend', rowHtml);
}

// Update Quantity Max based on selected item
function updateQuantityMax(rowId, selectElement) {
    const option = selectElement.options[selectElement.selectedIndex];
    const unit = option.dataset.unit || '-';
    const available = option.dataset.available || 0;
    
    document.getElementById(`unit${rowId}`).textContent = unit;
    const quantityInput = selectElement.closest('.flex').querySelector('input[type="number"]');
    quantityInput.max = available;
    quantityInput.placeholder = `Max: ${parseFloat(available).toFixed(2)}`;
}

// Remove Inventory Row
function removeInventoryRow(rowId) {
    document.getElementById(`inventoryRow${rowId}`).remove();
}

// Handle Form Submission
document.getElementById('assignInventoryForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const bookingId = document.getElementById('assignBookingId').value;
    const formData = new FormData(this);
    
    // Collect inventory items
    const items = [];
    document.querySelectorAll('#inventoryItemsContainer > div').forEach(row => {
        const select = row.querySelector('select');
        const quantity = row.querySelector('input[type="number"]');
        if (select && select.value && quantity && quantity.value) {
            items.push({
                inventory_id: select.value,
                quantity: parseFloat(quantity.value)
            });
        }
    });
    
    if (items.length === 0) {
        alert('Please add at least one inventory item.');
        return;
    }
    
    fetch(`/admin/bookings/${bookingId}/inventory/assign`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        },
        body: JSON.stringify({ items: items })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Inventory assigned successfully!');
            closeAssignInventoryModal();
            window.location.reload();
        } else {
            alert(data.message || 'Error assigning inventory.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error assigning inventory. Please try again.');
    });
});
</script>
