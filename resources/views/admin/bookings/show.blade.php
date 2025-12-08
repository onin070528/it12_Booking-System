<div class="space-y-6">
    <!-- Booking Header -->
    <div class="bg-gradient-to-r from-[#93BFC7] to-[#7aa8b0] rounded-lg p-4 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-2xl font-bold mb-1">Booking #{{ $booking->id }}</h3>
                <p class="text-sm opacity-90">Created on {{ $booking->created_at->format('F d, Y \a\t g:i A') }}</p>
            </div>
            <div class="text-right">
                <span class="px-4 py-2 rounded-full text-sm font-bold
                    @if($booking->status == 'pending') bg-yellow-100 text-yellow-800
                    @elseif($booking->status == 'confirmed') bg-green-100 text-green-800
                    @elseif($booking->status == 'approved') bg-blue-100 text-blue-800
                    @elseif($booking->status == 'rejected') bg-red-100 text-red-800
                    @else bg-gray-100 text-gray-800
                    @endif">
                    {{ ucfirst($booking->status) }}
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
                            <td class="py-3 px-4 font-semibold text-gray-700">Venue</td>
                            <td class="py-3 px-4 text-gray-900">{{ $details['venue'] ?? 'N/A' }}</td>
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
            @elseif($booking->event_type === 'debut')
                <table class="w-full">
                    <tbody class="divide-y divide-gray-100">
                        <tr>
                            <td class="py-3 px-4 font-semibold text-gray-700 w-1/3">Debutante Name</td>
                            <td class="py-3 px-4 text-gray-900">{{ $details['debutante'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 font-semibold text-gray-700">Venue</td>
                            <td class="py-3 px-4 text-gray-900">{{ $details['venue'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 font-semibold text-gray-700">Number of Guests</td>
                            <td class="py-3 px-4 text-gray-900">{{ $details['guests'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 font-semibold text-gray-700">Theme / Motif</td>
                            <td class="py-3 px-4 text-gray-900">{{ $details['theme'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 font-semibold text-gray-700">18 Roses Participants</td>
                            <td class="py-3 px-4 text-gray-900">{{ $details['roses'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 font-semibold text-gray-700">18 Candles Participants</td>
                            <td class="py-3 px-4 text-gray-900">{{ $details['candles'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 font-semibold text-gray-700">18 Treasures Participants</td>
                            <td class="py-3 px-4 text-gray-900">{{ $details['treasures'] ?? 'N/A' }}</td>
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
                            <td class="py-3 px-4 font-semibold text-gray-700 w-1/3">Pageant Title</td>
                            <td class="py-3 px-4 text-gray-900">{{ $details['title'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 font-semibold text-gray-700">Venue</td>
                            <td class="py-3 px-4 text-gray-900">{{ $details['venue'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 font-semibold text-gray-700">Number of Guests</td>
                            <td class="py-3 px-4 text-gray-900">{{ $details['guests'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 font-semibold text-gray-700">Theme / Motif</td>
                            <td class="py-3 px-4 text-gray-900">{{ $details['theme'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 font-semibold text-gray-700">Number of Contestants</td>
                            <td class="py-3 px-4 text-gray-900">{{ $details['contestants'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 font-semibold text-gray-700">Categories</td>
                            <td class="py-3 px-4 text-gray-900">{{ $details['categories'] ?? 'N/A' }}</td>
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
                            <td class="py-3 px-4 font-semibold text-gray-700 w-1/3">Company Name</td>
                            <td class="py-3 px-4 text-gray-900">{{ $details['company'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 font-semibold text-gray-700">Event Title / Theme</td>
                            <td class="py-3 px-4 text-gray-900">{{ $details['title'] ?? $details['theme'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 font-semibold text-gray-700">Venue / Location</td>
                            <td class="py-3 px-4 text-gray-900">{{ $details['venue'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 font-semibold text-gray-700">Number of Attendees</td>
                            <td class="py-3 px-4 text-gray-900">{{ $details['attendees'] ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 px-4 font-semibold text-gray-700">Company Representative</td>
                            <td class="py-3 px-4 text-gray-900">{{ $details['representative'] ?? 'N/A' }}</td>
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

    <!-- Action Buttons -->
    <div class="flex gap-3 pt-4">
        @if($booking->status == 'pending')
            <button data-booking-id="{{ $booking->id }}" 
                    onclick="if(typeof confirmBooking === 'function') confirmBooking(this.dataset.bookingId)" 
                    class="flex-1 bg-green-500 text-white font-bold py-3 rounded-lg hover:bg-green-600 transition flex items-center justify-center">
                <i class="fas fa-check mr-2"></i>Confirm Booking
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
    </div>
</div>
