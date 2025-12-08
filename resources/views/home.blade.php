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

            <div class="overflow-x-auto rounded-xl shadow-lg">
                <!-- Header -->
                <div class="text-center p-4 bg-white">
                    <h2 class="text-3xl font-bold mb-2" style="color: #93BFC7;">My Bookings</h2>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full bg-white">
                        <thead class="bg-[#93BFC7] text-white text-lg">
                            <tr>
                                <th class="px-6 py-4 text-center font-semibold">Booking ID</th>
                                <th class="px-6 py-4 text-center font-semibold">Event</th>
                                <th class="px-6 py-4 text-center font-semibold">Date</th>
                                <th class="px-6 py-4 text-center font-semibold">Location</th>
                                <th class="px-6 py-4 text-center font-semibold">Amount</th>
                                <th class="px-6 py-4 text-center font-semibold">Status</th>
                                <th class="px-6 py-4 text-center font-semibold">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($bookings as $booking)
                                <tr class="bg-white text-[#93BFC7] hover:bg-gray-50 transition-all duration-200">
                                    <td class="px-6 py-4 text-center">#{{ $booking->id }}</td>
                                    <td class="px-6 py-4 text-center font-medium capitalize">{{ $booking->event_type }}</td>
                                    <td class="px-6 py-4 text-center">{{ $booking->event_date->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 text-center">{{ $booking->location }}</td>
                                    <td class="px-6 py-4 text-center">₱{{ number_format($booking->total_amount, 2) }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-4 py-1 rounded-full font-medium
                                            @if($booking->status == 'pending') bg-yellow-200 text-yellow-800
                                            @elseif($booking->status == 'confirmed') bg-green-200 text-green-800
                                            @elseif($booking->status == 'approved') bg-blue-200 text-blue-800
                                            @elseif($booking->status == 'rejected') bg-red-200 text-red-800
                                            @else bg-gray-200 text-gray-800
                                            @endif">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($booking->status == 'confirmed' && !$booking->communication_method)
                                            <button 
                                                data-booking-id="{{ $booking->id }}"
                                                onclick="openCommunicationModal(this.dataset.bookingId)"
                                                class="bg-[#93BFC7] text-white px-4 py-2 rounded-lg hover:bg-[#7eaab1] transition shadow font-semibold">
                                                <i class="fas fa-comments mr-2"></i>Choose Method
                                            </button>
                                        @else
                                            <button class="bg-white text-[#93BFC7] px-3 py-1 rounded-full hover:bg-gray-200 transition shadow">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                        No bookings found. <a href="{{ route('booking.create') }}" class="text-[#93BFC7] hover:underline">Create a booking</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Communication Method Choice Modal -->
    <div id="communicationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4">
            <div class="bg-[#93BFC7] rounded-t-xl px-6 py-4 flex items-center justify-between">
                <h3 class="text-xl font-bold text-white">
                    <i class="fas fa-comments mr-2"></i>Choose Communication Method
                </h3>
                <button onclick="closeCommunicationModal()" class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6">
                <p class="text-gray-700 mb-6 text-center">
                    Your booking has been confirmed! How would you like to proceed with further updates?
                </p>
                
                <form id="communicationForm">
                    @csrf
                    <input type="hidden" id="booking_id_comm" name="booking_id">
                    
                    <div class="space-y-4 mb-6">
                        <!-- Meetup Option -->
                        <label class="flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-[#93BFC7] transition communication-option">
                            <input type="radio" name="communication_method" value="meetup" class="mt-1 mr-3" onchange="toggleMeetupFields()">
                            <div class="flex-1">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-alt text-[#93BFC7] text-xl mr-3"></i>
                                    <span class="font-bold text-gray-800">Schedule a Meetup</span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1 ml-9">Meet in person to discuss details</p>
                            </div>
                        </label>

                        <!-- Messaging Option -->
                        <label class="flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-[#93BFC7] transition communication-option">
                            <input type="radio" name="communication_method" value="messaging" class="mt-1 mr-3" onchange="toggleMeetupFields()">
                            <div class="flex-1">
                                <div class="flex items-center">
                                    <i class="fas fa-comments text-[#93BFC7] text-xl mr-3"></i>
                                    <span class="font-bold text-gray-800">Continue via Messaging</span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1 ml-9">Communicate through the Messages section</p>
                            </div>
                        </label>
                    </div>

                    <!-- Meetup Date/Time Fields (Hidden by default) -->
                    <div id="meetupFields" class="hidden space-y-4 mb-6">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Meetup Date</label>
                            <input type="date" id="meetup_date_comm" name="meetup_date" 
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#93BFC7]">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">Meetup Time</label>
                            <input type="time" id="meetup_time_comm" name="meetup_time" 
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#93BFC7]">
                        </div>
                    </div>

                    <div class="flex space-x-3">
                        <button type="button" onclick="saveCommunicationMethod()" 
                            class="flex-1 bg-[#93BFC7] text-white font-bold py-3 rounded-lg hover:bg-[#7aa8b0] transition">
                            <i class="fas fa-check mr-2"></i>Submit
                        </button>
                        <button type="button" onclick="closeCommunicationModal()" 
                            class="flex-1 bg-gray-300 text-gray-700 font-bold py-3 rounded-lg hover:bg-gray-400 transition">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openCommunicationModal(bookingId) {
            document.getElementById('booking_id_comm').value = bookingId;
            document.getElementById('communicationModal').classList.remove('hidden');
            document.getElementById('communicationForm').reset();
            document.getElementById('meetupFields').classList.add('hidden');
            
            // Reset radio buttons
            document.querySelectorAll('input[name="communication_method"]').forEach(radio => {
                radio.checked = false;
            });
            
            // Reset option styling
            document.querySelectorAll('.communication-option').forEach(opt => {
                opt.classList.remove('border-[#93BFC7]', 'bg-blue-50');
            });
        }

        function closeCommunicationModal() {
            document.getElementById('communicationModal').classList.add('hidden');
            document.getElementById('communicationForm').reset();
            document.getElementById('meetupFields').classList.add('hidden');
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
                    alert(data.message);
                    location.reload();
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
                    opt.classList.remove('border-[#93BFC7]', 'bg-blue-50');
                });
                this.classList.add('border-[#93BFC7]', 'bg-blue-50');
            });
        });
    </script>

</body>
</html>
