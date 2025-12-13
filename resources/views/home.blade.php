<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - RJ's Event Styling</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fadeIn {
            animation: fadeIn 0.3s ease-out;
        }
        
        .animate-slideDown {
            animation: slideDown 0.3s ease-out;
        }
        
        .modal-content {
            animation: slideDown 0.3s ease-out;
        }
        
        #communicationModal:not(.hidden) .modal-content {
            transform: scale(1);
        }
        
        /* Custom scrollbar for table */
        .overflow-x-auto::-webkit-scrollbar {
            height: 8px;
        }
        
        .overflow-x-auto::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #93BFC7;
            border-radius: 10px;
        }
        
        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #7eaab1;
        }
        
        /* Toast Notification */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }
        
        .toast {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            padding: 16px 20px;
            margin-bottom: 12px;
            min-width: 320px;
            max-width: 400px;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideInRight 0.3s ease-out, fadeOut 0.3s ease-out 4.7s forwards;
            border-left: 4px solid #93BFC7;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes fadeOut {
            to {
                opacity: 0;
                transform: translateX(100%);
            }
        }
        
        .toast-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #93BFC7 0%, #7eaab1 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .toast-content {
            flex: 1;
        }
        
        .toast-title {
            font-weight: 600;
            color: #1f2937;
            font-size: 14px;
            margin-bottom: 4px;
        }
        
        .toast-message {
            color: #6b7280;
            font-size: 13px;
            line-height: 1.4;
        }
    </style>
</head>

<body class="font-sans bg-[#ECF4E8]">

    <div class="flex">
        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Main Content -->
        <div class="flex-1 min-h-screen px-6 py-6 bg-transparent ml-64">
            <!-- Header -->
            @php $headerSubtitle = "Welcome to RJ's Event and Styling!"; @endphp
            @include('layouts.header')

            <div class="overflow-x-auto rounded-2xl shadow-xl bg-white border border-gray-100">
                <!-- Header -->
                <div class="bg-[#93BFC7] text-white px-8 py-6 rounded-t-2xl flex items-center justify-center backdrop-blur-sm">
                    <div class="flex items-center gap-3">
                        <h2 class="text-3xl font-bold tracking-tight">My Bookings</h2>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full bg-white">
                        <thead class="bg-gradient-to-b from-gray-50 to-white border-b-2 border-gray-100 text-base text-[#93BFC7] font-bold ">
                            <tr>
                                <th class="px-8 py-5 text-left hidden font-semibold text-gray-600 uppercase tracking-wider">Booking ID</th>
                                <th class="px-8 py-5 text-left  uppercase  tracking-wider">Event</th>
                                <th class="px-8 py-5 text-left  uppercase tracking-wider">Date</th>
                                <th class="px-8 py-5 text-left  uppercase tracking-wider">Location</th>
                                <th class="px-8 py-5 text-left  uppercase tracking-wider">Amount</th>
                                <th class="px-8 py-5 text-center  uppercase tracking-wider">Status</th>
                                <th class="px-8 py-5 text-center uppercase tracking-wider">Action</th>
                                 <th class="px-8 py-5 text-center uppercase tracking-wider" id="methodColumnHeader">Method</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse($bookings as $booking)
                                <tr class="bg-white text-gray-700 hover:bg-gradient-to-r hover:from-gray-50 hover:to-white transition-all duration-300 group">
                                    <td class="px-8 py-5 text-left hidden font-semibold text-gray-500">#{{ $booking->id }}</td>
                                    <td class="px-8 py-5 text-left">
                                        <div class="flex items-center gap-3">
                                            <div class="w-2 h-2 rounded-full bg-[#93BFC7] opacity-60 group-hover:opacity-100 transition-opacity"></div>
                                            <span class="font-semibold text-gray-800 capitalize">{{ $booking->event_type }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 text-left">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-calendar text-[#93BFC7] text-sm opacity-70"></i>
                                            <span class="font-medium text-gray-700">{{ $booking->event_date->format('M d, Y') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 text-left">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-map-marker-alt text-[#93BFC7] text-sm opacity-70"></i>
                                            <span class="font-medium text-gray-700">{{ $booking->location }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 text-left">
                                        <span class=" text-gray-800 text-lg font-medium">₱{{ number_format($booking->total_amount, 2) }}</span>
                                    </td>
                                    <td class="px-8 py-5 text-center">
                                        <span class="inline-flex items-center px-4 py-2 rounded-full text-xs font-semibold shadow-sm
                                            @if($booking->status == 'pending') bg-yellow-100 text-yellow-800 border border-yellow-200
                                            @elseif($booking->status == 'confirmed') bg-green-100 text-green-800 border border-green-200
                                            @elseif($booking->status == 'approved') bg-blue-100 text-blue-800 border border-blue-200
                                            @elseif($booking->status == 'pending_payment') bg-orange-100 text-orange-800 border border-orange-200
                                            @elseif($booking->status == 'in_design') bg-indigo-100 text-indigo-800 border border-indigo-200
                                            @elseif($booking->status == 'rejected') bg-red-100 text-red-800 border border-red-200
                                            @elseif($booking->status == 'completed') bg-purple-100 text-purple-800 border border-purple-200
                                            @else bg-gray-100 text-gray-800 border border-gray-200
                                            @endif">
                                            <span class="w-1.5 h-1.5 rounded-full mr-2
                                                @if($booking->status == 'pending') bg-yellow-600
                                                @elseif($booking->status == 'confirmed') bg-green-600
                                                @elseif($booking->status == 'approved') bg-blue-600
                                            @elseif($booking->status == 'pending_payment') bg-orange-600
                                            @elseif($booking->status == 'partial_payment') bg-yellow-600
                                            @elseif($booking->status == 'in_design') bg-indigo-600
                                            @elseif($booking->status == 'rejected') bg-red-600
                                            @elseif($booking->status == 'completed') bg-purple-600
                                            @else bg-gray-600
                                            @endif"></span>
                                            {{ $booking->status == 'pending_payment' ? 'Pending Payment' : ($booking->status == 'partial_payment' ? 'Partial Payment' : ($booking->status == 'in_design' ? 'In Design' : ucfirst($booking->status))) }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5 text-center">
                                        <button 
                                            data-booking-data="{{ json_encode([
                                                'id' => $booking->id,
                                                'event_type' => $booking->event_type,
                                                'event_date' => $booking->event_date->format('Y-m-d'),
                                                'event_time' => $booking->event_time ?? 'Not specified',
                                                'location' => $booking->location,
                                                'total_amount' => $booking->total_amount,
                                                'status' => $booking->status,
                                                'description' => $booking->description ?? '',
                                                'communication_method' => $booking->communication_method ?? '',
                                                'meetup_date' => $booking->meetup_date ? $booking->meetup_date->format('Y-m-d') : '',
                                                'meetup_time' => $booking->meetup_time ?? ''
                                            ]) }}"
                                            onclick="openViewModalFromData(this)"
                                            class="inline-flex items-center justify-center w-10 h-10 bg-white text-[#93BFC7] rounded-lg hover:bg-gray-100 transition-all duration-300 shadow-sm hover:shadow-md border border-gray-200 hover:border-[#93BFC7] transform hover:scale-110">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                    <td class="px-8 py-5 text-center method-cell" data-booking-id="{{ $booking->id }}" data-has-method="{{ $booking->communication_method ? 'true' : 'false' }}">
                                        @if($booking->status == 'confirmed' && !$booking->communication_method)
                                            <button 
                                                data-booking-id="{{ $booking->id }}"
                                                onclick="openCommunicationModal(this.dataset.bookingId)"
                                                class="inline-flex items-center gap-2 bg-[#93BFC7] text-white px-4 py-2 rounded-lg hover:bg-[#7eaab1] transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105 font-semibold text-xs">
                                                <i class="fas fa-comments"></i>
                                                <span>Choose</span>
                                            </button>
                                        @elseif($booking->status == 'pending_payment')
                                            <button 
                                                data-booking-id="{{ $booking->id }}"
                                                data-booking-amount="{{ $booking->total_amount }}"
                                                onclick="openPaymentMethodModal(this.dataset.bookingId, this.dataset.bookingAmount)"
                                                class="inline-flex items-center gap-2 bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105 font-semibold text-xs">
                                                <i class="fas fa-credit-card"></i>
                                                <span>Pay Now</span>
                                            </button>
                                        @elseif($booking->communication_method)
                                            <span class="settled-badge inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-800 border border-purple-200">
                                                <i class="fas fa-check-circle mr-1.5"></i>
                                                <span>Settled</span>
                                            </span>
                                        @elseif($booking->status == 'pending')
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                                                <i class="fas fa-clock mr-1.5"></i>
                                                <span>Awaiting Confirmation</span>
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-8 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center gap-4">
                                            <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center">
                                                <i class="fas fa-calendar-times text-4xl text-gray-400"></i>
                                            </div>
                                            <div class="text-gray-500 text-lg font-medium">No bookings found</div>
                                            <a href="{{ route('booking.create') }}" class="inline-flex items-center gap-2 text-[#93BFC7] hover:text-[#7eaab1] font-semibold transition-colors duration-200 hover:underline">
                                                <i class="fas fa-plus-circle"></i>
                                                <span>Create a booking</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification Container -->
    <div id="toastContainer" class="toast-container"></div>

    <!-- Payment Method Selection Modal -->
    <div id="paymentMethodModal" class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4 animate-fadeIn">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4 transform transition-all duration-300 scale-95 modal-content">
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-t-2xl px-8 py-5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                        <i class="fas fa-credit-card text-white text-lg"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white tracking-tight">
                        Select Payment Method
                    </h3>
                </div>
                <button onclick="closePaymentMethodModal()" class="text-white hover:text-gray-200 transition-colors duration-200 w-8 h-8 flex items-center justify-center rounded-full hover:bg-white/20">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <div class="p-8">
                <div class="mb-6 text-center">
                    <div class="w-16 h-16 rounded-full bg-orange-100 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-money-bill-wave text-orange-600 text-2xl"></i>
                    </div>
                    <p class="text-gray-700 text-base leading-relaxed font-medium mb-2">
                        Choose your preferred payment method
                    </p>
                    <p class="text-gray-500 text-sm" id="paymentAmountDisplay">
                        Amount: <span class="font-bold text-orange-600">₱0.00</span>
                    </p>
                </div>
                
                <form id="paymentMethodForm">
                    @csrf
                    <input type="hidden" id="payment_booking_id" name="booking_id">
                    
                    <div class="space-y-3 mb-6">
                        <!-- Cash Option -->
                        <label class="flex items-start p-5 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-orange-500 hover:bg-orange-50 transition-all duration-300 payment-option group">
                            <input type="radio" name="payment_method" value="cash" class="mt-1 mr-4 w-5 h-5 text-orange-500 focus:ring-2 focus:ring-orange-500 focus:ring-offset-2" required>
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-1">
                                    <div class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center group-hover:bg-orange-200 transition-colors">
                                        <i class="fas fa-money-bill-wave text-orange-600 text-lg"></i>
                                    </div>
                                    <span class="font-bold text-gray-800 text-lg">Cash Payment</span>
                                </div>
                                <p class="text-sm text-gray-600 ml-[3.25rem]">Pay in cash during meetup or event</p>
                            </div>
                        </label>

                        <!-- GCash Option -->
                        <label class="flex items-start p-5 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-orange-500 hover:bg-orange-50 transition-all duration-300 payment-option group">
                            <input type="radio" name="payment_method" value="gcash" class="mt-1 mr-4 w-5 h-5 text-orange-500 focus:ring-2 focus:ring-orange-500 focus:ring-offset-2" required>
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-1">
                                    <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center group-hover:bg-green-200 transition-colors">
                                        <i class="fas fa-mobile-alt text-green-600 text-lg"></i>
                                    </div>
                                    <span class="font-bold text-gray-800 text-lg">GCash</span>
                                </div>
                                <p class="text-sm text-gray-600 ml-[3.25rem]">Pay via GCash mobile wallet</p>
                            </div>
                        </label>

                        <!-- PayMaya Option -->
                        <label class="flex items-start p-5 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-orange-500 hover:bg-orange-50 transition-all duration-300 payment-option group">
                            <input type="radio" name="payment_method" value="paymaya" class="mt-1 mr-4 w-5 h-5 text-orange-500 focus:ring-2 focus:ring-orange-500 focus:ring-offset-2" required>
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-1">
                                    <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                                        <i class="fas fa-credit-card text-blue-600 text-lg"></i>
                                    </div>
                                    <span class="font-bold text-gray-800 text-lg">PayMaya</span>
                                </div>
                                <p class="text-sm text-gray-600 ml-[3.25rem]">Pay via PayMaya wallet</p>
                            </div>
                        </label>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" onclick="proceedToPayment()" 
                            class="flex-1 inline-flex items-center justify-center gap-2 bg-orange-500 text-white font-bold py-3.5 rounded-lg hover:bg-orange-600 transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-[1.02]">
                            <i class="fas fa-arrow-right"></i>
                            <span>Proceed</span>
                        </button>
                        <button type="button" onclick="closePaymentMethodModal()" 
                            class="flex-1 inline-flex items-center justify-center gap-2 bg-gray-200 text-gray-700 font-bold py-3.5 rounded-lg hover:bg-gray-300 transition-all duration-300 shadow-sm hover:shadow-md">
                            <span>Cancel</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Booking Details Modal -->
    <div id="viewBookingModal" class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4 animate-fadeIn">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full mx-4 transform transition-all duration-300 scale-95 modal-content max-h-[90vh] overflow-y-auto">
            <div class="bg-gradient-to-r from-[#93BFC7] to-[#7eaab1] rounded-t-2xl px-8 py-5 flex items-center justify-between sticky top-0 z-10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                        <i class="fas fa-calendar-check text-white text-lg"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white tracking-tight">
                        Booking Details
                    </h3>
                </div>
                <button onclick="closeViewModal()" class="text-white hover:text-gray-200 transition-colors duration-200 w-8 h-8 flex items-center justify-center rounded-full hover:bg-white/20">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <div class="p-8" id="bookingDetailsContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Communication Method Choice Modal -->
    <div id="communicationModal" class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4 animate-fadeIn">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4 transform transition-all duration-300 scale-95 modal-content">
            <div class="bg-gradient-to-r from-[#93BFC7] to-[#7eaab1] rounded-t-2xl px-8 py-5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                        <i class="fas fa-comments text-white text-lg"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white tracking-tight">
                        Choose Communication Method
                    </h3>
                </div>
                <button onclick="closeCommunicationModal()" class="text-white hover:text-gray-200 transition-colors duration-200 w-8 h-8 flex items-center justify-center rounded-full hover:bg-white/20">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <div class="p-8">
                <div class="mb-6 text-center">
                    <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                    </div>
                    <p class="text-gray-700 text-base leading-relaxed font-medium">
                        Your booking has been confirmed! How would you like to proceed with further updates?
                    </p>
                </div>
                
                <form id="communicationForm">
                    @csrf
                    <input type="hidden" id="booking_id_comm" name="booking_id">
                    
                    <div class="space-y-3 mb-6">
                        <!-- Meetup Option -->
                        <label class="flex items-start p-5 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-[#93BFC7] hover:bg-[#93BFC7]/5 transition-all duration-300 communication-option group">
                            <input type="radio" name="communication_method" value="meetup" class="mt-1 mr-4 w-5 h-5 text-[#93BFC7] focus:ring-2 focus:ring-[#93BFC7] focus:ring-offset-2" onchange="toggleMeetupFields()">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-1">
                                    <div class="w-10 h-10 rounded-lg bg-[#93BFC7]/10 flex items-center justify-center group-hover:bg-[#93BFC7]/20 transition-colors">
                                        <i class="fas fa-calendar-alt text-[#93BFC7] text-lg"></i>
                                    </div>
                                    <span class="font-bold text-gray-800 text-lg">Schedule a Meetup</span>
                                </div>
                                <p class="text-sm text-gray-600 ml-[3.25rem]">Meet in person to discuss details</p>
                            </div>
                        </label>

                        <!-- Messaging Option -->
                        <label class="flex items-start p-5 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-[#93BFC7] hover:bg-[#93BFC7]/5 transition-all duration-300 communication-option group">
                            <input type="radio" name="communication_method" value="messaging" class="mt-1 mr-4 w-5 h-5 text-[#93BFC7] focus:ring-2 focus:ring-[#93BFC7] focus:ring-offset-2" onchange="toggleMeetupFields()">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-1">
                                    <div class="w-10 h-10 rounded-lg bg-[#93BFC7]/10 flex items-center justify-center group-hover:bg-[#93BFC7]/20 transition-colors">
                                        <i class="fas fa-comments text-[#93BFC7] text-lg"></i>
                                    </div>
                                    <span class="font-bold text-gray-800 text-lg">Continue via Messaging</span>
                                </div>
                                <p class="text-sm text-gray-600 ml-[3.25rem]">Communicate through the Messages section</p>
                            </div>
                        </label>
                    </div>

                    <!-- Meetup Date/Time Fields (Hidden by default) -->
                    <div id="meetupFields" class="hidden space-y-4 mb-6 p-5 bg-gray-50 rounded-xl border border-gray-200 animate-slideDown">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2.5 flex items-center gap-2">
                                <i class="fas fa-calendar text-[#93BFC7] text-sm"></i>
                                <span>Meetup Date</span>
                            </label>
                            <input type="date" id="meetup_date_comm" name="meetup_date" 
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-[#93BFC7] focus:border-[#93BFC7] transition-all duration-200 bg-white">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2.5 flex items-center gap-2">
                                <i class="fas fa-clock text-[#93BFC7] text-sm"></i>
                                <span>Meetup Time</span>
                            </label>
                            <input type="time" id="meetup_time_comm" name="meetup_time" 
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-[#93BFC7] focus:border-[#93BFC7] transition-all duration-200 bg-white">
                        </div>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" onclick="saveCommunicationMethod()" 
                            class="flex-1 inline-flex items-center justify-center gap-2 bg-[#93BFC7] text-white font-bold py-3.5 rounded-lg hover:bg-[#7eaab1] transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-[1.02]">
                            <i class="fas fa-check"></i>
                            <span>Submit</span>
                        </button>
                        <button type="button" onclick="closeCommunicationModal()" 
                            class="flex-1 inline-flex items-center justify-center gap-2 bg-gray-200 text-gray-700 font-bold py-3.5 rounded-lg hover:bg-gray-300 transition-all duration-300 shadow-sm hover:shadow-md">
                            <span>Cancel</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Show toast notification on page load if flag is set
        document.addEventListener('DOMContentLoaded', function() {
            if (sessionStorage.getItem('showCommunicationToast') === 'true') {
                showToast();
                sessionStorage.removeItem('showCommunicationToast');
            }
            
            // Hide settled badges after 3 seconds
            setTimeout(function() {
                document.querySelectorAll('.settled-badge').forEach(badge => {
                    const cell = badge.closest('.method-cell');
                    if (cell && cell.dataset.hasMethod === 'true') {
                        badge.style.opacity = '0';
                        badge.style.transform = 'scale(0.9)';
                        badge.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                        setTimeout(() => {
                            badge.remove();
                            // Hide column header if no methods are shown
                            checkMethodColumnVisibility();
                        }, 500);
                    }
                });
            }, 3000);
        });

        function showToast() {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = 'toast';
            toast.innerHTML = `
                <div class="toast-icon">
                    <i class="fas fa-info-circle text-white"></i>
                </div>
                <div class="toast-content">
                    <div class="toast-title">Action Required</div>
                    <div class="toast-message">Please select your preferred communication method to proceed with your confirmed booking.</div>
                </div>
            `;
            container.appendChild(toast);
            
            // Remove toast after animation
            setTimeout(() => {
                toast.remove();
            }, 5000);
        }

        function checkMethodColumnVisibility() {
            const methodCells = document.querySelectorAll('.method-cell');
            const hasVisibleMethods = Array.from(methodCells).some(cell => {
                const badge = cell.querySelector('.settled-badge');
                const button = cell.querySelector('button');
                return (badge && badge.style.opacity !== '0') || button;
            });
            
            const header = document.getElementById('methodColumnHeader');
            if (!hasVisibleMethods && header) {
                header.style.display = 'none';
                methodCells.forEach(cell => cell.style.display = 'none');
            }
        }

        function openViewModalFromData(button) {
            const bookingData = JSON.parse(button.getAttribute('data-booking-data'));
            openViewModal(bookingData);
        }

        function openViewModal(booking) {
            const modal = document.getElementById('viewBookingModal');
            const content = document.getElementById('bookingDetailsContent');
            
            const statusColors = {
                'pending': 'bg-yellow-100 text-yellow-800 border-yellow-200',
                'confirmed': 'bg-green-100 text-green-800 border-green-200',
                'approved': 'bg-blue-100 text-blue-800 border-blue-200',
                'rejected': 'bg-red-100 text-red-800 border-red-200'
            };
            const statusColor = statusColors[booking.status] || 'bg-gray-100 text-gray-800 border-gray-200';
            
            const eventDate = new Date(booking.event_date).toLocaleDateString('en-US', { 
                month: 'long', 
                day: 'numeric', 
                year: 'numeric' 
            });
            
            const formattedAmount = parseFloat(booking.total_amount).toLocaleString('en-US', { 
                minimumFractionDigits: 2, 
                maximumFractionDigits: 2 
            });
            
            let meetupInfo = '';
            if (booking.communication_method === 'meetup' && booking.meetup_date) {
                const meetupDate = new Date(booking.meetup_date).toLocaleDateString('en-US', { 
                    month: 'long', 
                    day: 'numeric', 
                    year: 'numeric' 
                });
                const meetupTime = booking.meetup_time ? new Date('2000-01-01T' + booking.meetup_time).toLocaleTimeString('en-US', { 
                    hour: 'numeric', 
                    minute: '2-digit' 
                }) : '';
                meetupInfo = `
                    <div class="bg-blue-50 border-2 border-blue-200 rounded-lg p-4">
                        <p class="text-sm text-blue-800 font-semibold mb-1">
                            <i class="fas fa-calendar-check mr-2"></i>Scheduled Meetup
                        </p>
                        <p class="text-xs text-blue-600">${meetupDate}${meetupTime ? ' at ' + meetupTime : ''}</p>
                    </div>
                `;
            }
            
            content.innerHTML = `
                <div class="space-y-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">Event Type</p>
                            <p class="font-semibold text-gray-800 capitalize">${booking.event_type}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">Status</p>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold ${statusColor}">
                                ${booking.status.charAt(0).toUpperCase() + booking.status.slice(1)}
                            </span>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">Event Date</p>
                            <p class="font-semibold text-gray-800">${eventDate}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">Event Time</p>
                            <p class="font-semibold text-gray-800">${booking.event_time}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4 col-span-2">
                            <p class="text-sm text-gray-600 mb-1">Location</p>
                            <p class="font-semibold text-gray-800">${booking.location}</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">Total Amount</p>
                            <p class="font-semibold text-gray-800 text-lg">₱${formattedAmount}</p>
                        </div>
                        ${booking.communication_method ? `
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-1">Communication Method</p>
                            <p class="font-semibold text-gray-800 capitalize">
                                <i class="fas ${booking.communication_method === 'meetup' ? 'fa-calendar-alt' : 'fa-comments'} mr-2"></i>
                                ${booking.communication_method === 'meetup' ? 'Meetup' : 'Messaging'}
                            </p>
                        </div>
                        ` : ''}
                    </div>
                    ${meetupInfo}
                    ${booking.description ? `
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600 mb-2">Description</p>
                        <p class="text-gray-800">${booking.description}</p>
                    </div>
                    ` : ''}
                    ${(booking.status === 'pending_payment' || booking.status === 'partial_payment') ? `
                    <div class="flex gap-3 pt-4 border-t border-gray-200">
                        <a href="/booking/${booking.id}/checkout" 
                           class="flex-1 inline-flex items-center justify-center gap-2 bg-orange-500 text-white font-bold py-3 rounded-lg hover:bg-orange-600 transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-[1.02]">
                            <i class="fas fa-credit-card"></i>
                            <span>Pay Now</span>
                        </a>
                    </div>
                    ` : ''}
                </div>
            `;
            
            modal.classList.remove('hidden');
            
            // Trigger animation
            setTimeout(() => {
                const modalContent = modal.querySelector('.modal-content');
                if (modalContent) {
                    modalContent.style.transform = 'scale(1)';
                }
            }, 10);
        }

        function closeViewModal() {
            const modal = document.getElementById('viewBookingModal');
            const modalContent = modal.querySelector('.modal-content');
            if (modalContent) {
                modalContent.style.transform = 'scale(0.95)';
            }
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 200);
        }

        function openCommunicationModal(bookingId) {
            document.getElementById('booking_id_comm').value = bookingId;
            const modal = document.getElementById('communicationModal');
            modal.classList.remove('hidden');
            document.getElementById('communicationForm').reset();
            document.getElementById('meetupFields').classList.add('hidden');
            
            // Reset radio buttons
            document.querySelectorAll('input[name="communication_method"]').forEach(radio => {
                radio.checked = false;
            });
            
            // Reset option styling
            document.querySelectorAll('.communication-option').forEach(opt => {
                opt.classList.remove('border-[#93BFC7]', 'bg-[#93BFC7]/10');
            });
            
            // Trigger animation
            setTimeout(() => {
                const modalContent = modal.querySelector('.modal-content');
                if (modalContent) {
                    modalContent.style.transform = 'scale(1)';
                }
            }, 10);
        }

        function closeCommunicationModal() {
            const modal = document.getElementById('communicationModal');
            const modalContent = modal.querySelector('.modal-content');
            if (modalContent) {
                modalContent.style.transform = 'scale(0.95)';
            }
            setTimeout(() => {
                modal.classList.add('hidden');
                document.getElementById('communicationForm').reset();
                document.getElementById('meetupFields').classList.add('hidden');
            }, 200);
        }

        function toggleMeetupFields() {
            const meetupOption = document.querySelector('input[name="communication_method"][value="meetup"]');
            const meetupFields = document.getElementById('meetupFields');
            
            if (meetupOption.checked) {
                meetupFields.classList.remove('hidden');
                document.getElementById('meetup_date_comm').required = true;
                document.getElementById('meetup_time_comm').required = true;
            } else {
                meetupFields.classList.add('hidden');
                document.getElementById('meetup_date_comm').required = false;
                document.getElementById('meetup_time_comm').required = false;
            }
        }

        function saveCommunicationMethod() {
            const form = document.getElementById('communicationForm');
            const formData = new FormData(form);
            const bookingId = document.getElementById('booking_id_comm').value;
            const communicationMethod = form.querySelector('input[name="communication_method"]:checked')?.value;

            if (!communicationMethod) {
                alert('Please select a communication method.');
                return;
            }

            if (communicationMethod === 'meetup') {
                const meetupDate = document.getElementById('meetup_date_comm').value;
                const meetupTime = document.getElementById('meetup_time_comm').value;
                
                if (!meetupDate || !meetupTime) {
                    alert('Please fill in meetup date and time.');
                    return;
                }
            }

            fetch(`/booking/${bookingId}/choose-communication`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success toast
                    showSuccessToast(data.message);
                    // Reload after short delay
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    alert(data.message || 'An error occurred.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while saving your choice.');
            });
        }

        // Highlight selected option
        document.querySelectorAll('.communication-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.communication-option').forEach(opt => {
                    opt.classList.remove('border-[#93BFC7]', 'bg-[#93BFC7]/10');
                });
                this.classList.add('border-[#93BFC7]', 'bg-[#93BFC7]/10');
            });
        });
        
        // Update styling when radio is checked
        document.querySelectorAll('input[name="communication_method"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.communication-option').forEach(opt => {
                    opt.classList.remove('border-[#93BFC7]', 'bg-[#93BFC7]/10');
                });
                if (this.checked) {
                    this.closest('.communication-option').classList.add('border-[#93BFC7]', 'bg-[#93BFC7]/10');
                }
            });
        });
        
        // Close modal when clicking outside
        document.getElementById('communicationModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeCommunicationModal();
            }
        });
        
        document.getElementById('viewBookingModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeViewModal();
            }
        });

        function showSuccessToast(message) {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = 'toast';
            toast.style.borderLeftColor = '#10b981';
            toast.innerHTML = `
                <div class="toast-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <i class="fas fa-check-circle text-white"></i>
                </div>
                <div class="toast-content">
                    <div class="toast-title">Success</div>
                    <div class="toast-message">${message}</div>
                </div>
            `;
            container.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 5000);
        }

        // Payment Method Modal Functions
        function openPaymentMethodModal(bookingId, bookingAmount) {
            document.getElementById('payment_booking_id').value = bookingId;
            const amount = parseFloat(bookingAmount).toLocaleString('en-US', { 
                minimumFractionDigits: 2, 
                maximumFractionDigits: 2 
            });
            document.getElementById('paymentAmountDisplay').innerHTML = 
                `Amount: <span class="font-bold text-orange-600">₱${amount}</span>`;
            
            const modal = document.getElementById('paymentMethodModal');
            modal.classList.remove('hidden');
            document.getElementById('paymentMethodForm').reset();
            
            // Reset option styling
            document.querySelectorAll('.payment-option').forEach(opt => {
                opt.classList.remove('border-orange-500', 'bg-orange-50');
            });
            
            // Trigger animation
            setTimeout(() => {
                const modalContent = modal.querySelector('.modal-content');
                if (modalContent) {
                    modalContent.style.transform = 'scale(1)';
                }
            }, 10);
        }

        function closePaymentMethodModal() {
            const modal = document.getElementById('paymentMethodModal');
            const modalContent = modal.querySelector('.modal-content');
            if (modalContent) {
                modalContent.style.transform = 'scale(0.95)';
            }
            setTimeout(() => {
                modal.classList.add('hidden');
                document.getElementById('paymentMethodForm').reset();
            }, 200);
        }

        function proceedToPayment() {
            const form = document.getElementById('paymentMethodForm');
            const bookingId = document.getElementById('payment_booking_id').value;
            const paymentMethod = form.querySelector('input[name="payment_method"]:checked')?.value;

            if (!paymentMethod) {
                alert('Please select a payment method.');
                return;
            }

            // If cash, submit directly to process payment
            if (paymentMethod === 'cash') {
                const formData = new FormData();
                formData.append('payment_method', 'cash');
                formData.append('_token', '{{ csrf_token() }}');
                
                fetch(`{{ route('payment.process', ':id') }}`.replace(':id', bookingId), {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (response.redirected) {
                        window.location.href = response.url;
                    } else {
                        return response.json();
                    }
                })
                .then(data => {
                    if (data) {
                        if (data.success) {
                            window.location.href = '{{ route("payments.index") }}';
                        } else {
                            alert(data.message || 'An error occurred.');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while processing cash payment.');
                });
            } else {
                // For online payments, redirect to checkout with selected method
                window.location.href = `{{ route('payment.checkout', ':id') }}`.replace(':id', bookingId) + `?method=${paymentMethod}`;
            }
        }

        // Highlight selected payment option
        document.querySelectorAll('.payment-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.payment-option').forEach(opt => {
                    opt.classList.remove('border-orange-500', 'bg-orange-50');
                });
                this.classList.add('border-orange-500', 'bg-orange-50');
            });
        });
        
        // Update styling when radio is checked
        document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.payment-option').forEach(opt => {
                    opt.classList.remove('border-orange-500', 'bg-orange-50');
                });
                if (this.checked) {
                    this.closest('.payment-option').classList.add('border-orange-500', 'bg-orange-50');
                }
            });
        });
        
        // Close payment modal when clicking outside
        document.getElementById('paymentMethodModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePaymentMethodModal();
            }
        });
    </script>

</body>
</html>
