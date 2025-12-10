<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Booking - RJ's Event Styling</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="font-sans bg-[#ECF4E8]">

    <div class="flex">
        @include('layouts.sidebar')

        <div class="flex-1 min-h-screen px-6 py-6 ml-64">

            <!-- Header -->
            @php $headerSubtitle = "Welcome to RJ's Event and Styling!"; @endphp
            @include('layouts.header')

            <div class="flex gap-6">

                <!-- LEFT SIDE -->
                <div class="flex-1">

                    <!-- Select Event Type -->
                    <div class="bg-[#93BFC7] rounded-xl shadow p-6 mb-6">
                        <h3 class="text-2xl font-bold text-white mb-4">
                            <i class="fas fa-list mr-2"></i>Select Event Type
                        </h3>

                        <select id="event_type" name="event_type" required class="w-full px-4 py-3 rounded-lg border-0 
                                focus:ring-2 focus:ring-white shadow-[0_2px_8px_rgba(0,0,0,0.15)]">
                            <option value="">Choose event type</option>
                            <option value="wedding">Wedding</option>
                            <option value="birthday">Birthday</option>
                            <option value="debut">Debut</option>
                            <option value="pageant">Pageant</option>
                            <option value="corporate">Corporate Event</option>
                        </select>
                        <p class="text-white text-xs mt-1 opacity-75"><span class="text-red-300">*</span> Required fields</p>
                    </div>

                    <!-- EVENT FORMS -->
                    <form id="bookingForm" action="{{ route('booking.store') }}" method="POST">
                        @csrf

                        <!-- WEDDING FORM -->
                        <div id="form_wedding" class="eventForm hidden bg-[#93BFC7] rounded-xl shadow p-6 mb-6">
                            <h3 class="text-xl font-bold text-white mb-4">Wedding Details</h3>
                            <input type="text" id="wedding_bride" name="wedding_bride" placeholder="Bride Name *" required class="w-full mb-4 px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                            <input type="text" id="wedding_groom" name="wedding_groom" placeholder="Groom Name *" required class="w-full mb-4 px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                            <input type="number" id="wedding_guests" name="wedding_guests" placeholder="Number of Guests *" required min="1" class="w-full mb-4 px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                            <input type="text" id="wedding_ceremony" name="wedding_ceremony" placeholder="Ceremony Venue *" required class="w-full mb-4 px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                            <input type="text" id="wedding_reception" name="wedding_reception" placeholder="Reception Venue *" required class="w-full mb-4 px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                            <input type="text" id="wedding_theme" name="wedding_theme" placeholder="Theme / Motif *" required class="w-full mb-4 px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                            <textarea id="wedding_notes" name="wedding_notes" placeholder="Additional Notes (Optional)" class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white resize-none"></textarea>
                        </div>

                        <!-- BIRTHDAY FORM -->
                        <div id="form_birthday" class="eventForm hidden bg-[#93BFC7] rounded-xl shadow p-6 mb-6">
                            <h3 class="text-xl font-bold text-white mb-4">Birthday Details</h3>
                                <input type="text" id="birthday_celebrant" name="birthday_celebrant" placeholder="Celebrant Name *" required class="w-full mb-4 px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                                <input type="number" id="birthday_age" name="birthday_age" placeholder="Age *" required min="1" class="w-full mb-4 px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                                <input type="text" id="birthday_venue" name="birthday_venue" placeholder="Venue *" required class="w-full mb-4 px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                                <input type="number" id="birthday_guests" name="birthday_guests" placeholder="Number of Guests *" required min="1" class="w-full mb-4 px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                                <textarea id="birthday_theme" name="birthday_theme" placeholder="Theme / Motif *" required class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white resize-none"></textarea>
                        </div>

                        <!-- DEBUT FORM -->
                        <div id="form_debut" class="eventForm hidden bg-[#93BFC7] rounded-xl shadow p-6 mb-6">
                            <h3 class="text-xl font-bold text-white mb-4">Debut Details</h3>
                            <input type="text" id="debut_name" name="debut_name" placeholder="Debutante Name *" required class="w-full mb-4 px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                            <input type="text" id="debut_venue" name="debut_venue" placeholder="Venue *" required class="w-full mb-4 px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                            <input type="number" id="debut_guests" name="debut_guests" placeholder="Number of Guests *" required min="1" class="w-full mb-4 px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                            <input type="text" id="debut_theme" name="debut_theme" placeholder="Theme / Motif *" required class="w-full mb-4 px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                            <input type="text" id="debut_roses" name="debut_roses" placeholder="18 Roses Participants *" required class="w-full mb-4 px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                            <input type="text" id="debut_candles" name="debut_candles" placeholder="18 Candles Participants *" required class="w-full mb-4 px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                            <input type="text" id="debut_treasures" name="debut_treasures" placeholder="18 Treasures Participants *" required class="w-full mb-4 px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                            <textarea id="debut_notes" name="debut_notes" placeholder="Program Notes (Optional)" class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white resize-none"></textarea>
                        </div>

                        <!-- PAGEANT FORM -->
                        <div id="form_pageant" class="eventForm hidden bg-[#93BFC7] rounded-xl shadow p-6 mb-6">
                            <h3 class="text-xl font-bold text-white mb-4">Pageant Details</h3>
                            <input type="text" id="pageant_title" name="pageant_title" placeholder="Pageant Title *" required class="w-full mb-4 px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                            <input type="text" id="pageant_venue" name="pageant_venue" placeholder="Venue *" required class="w-full mb-4 px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                            <input type="number" id="pageant_guests" name="pageant_guests" placeholder="Number of Guests *" required min="1" class="w-full mb-4 px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                            <input type="text" id="pageant_theme" name="pageant_theme" placeholder="Theme / Motif *" required class="w-full mb-4 px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                            <input type="number" id="pageant_contestants" name="pageant_contestants" placeholder="Number of Contestants *" required min="1" class="w-full mb-4 px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                            <input type="text" id="pageant_categories" name="pageant_categories" placeholder="Categories (e.g., Talent, Q&A, Evening Gown) *" required class="w-full mb-4 px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                            <textarea id="pageant_notes" name="pageant_notes" placeholder="Additional Notes (Optional)" class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white resize-none"></textarea>
                        </div>

                        <!-- CORPORATE FORM -->
                        <div id="form_corporate" class="eventForm hidden bg-[#93BFC7] rounded-xl shadow p-6 mb-6">
                            <h3 class="text-xl font-bold text-white mb-4">Corporate Event Details</h3>
                            <input type="text" id="corporate_company" name="corporate_company" placeholder="Company Name *" required class="w-full mb-4 px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                            <input type="text" id="corporate_title" name="corporate_title" placeholder="Event Title / Theme *" required class="w-full mb-4 px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                            <input type="text" id="corporate_venue" name="corporate_venue" placeholder="Venue / Location *" required class="w-full mb-4 px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                            <input type="number" id="corporate_attendees" name="corporate_attendees" placeholder="Number of Attendees *" required min="1" class="w-full mb-4 px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                            <input type="text" id="corporate_representative" name="corporate_representative" placeholder="Company Representative *" required class="w-full mb-4 px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                            <input type="tel" id="corporate_contact" name="corporate_contact" placeholder="Contact Number *" required pattern="[0-9]{10,11}" class="w-full mb-4 px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                            <textarea id="corporate_requirements" name="corporate_requirements" placeholder="Event Requirements (Lights, Sound, Stage, etc.) *" required
                                class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white resize-none"></textarea>

                        </div>

                        <!-- EVENT INFO (ALWAYS LAST) -->
                        <div id="event_info_container" class="bg-[#93BFC7] rounded-xl shadow p-6 hidden mb-6">
                            <h3 class="text-xl font-bold text-white mb-4">Event Information</h3>

                            <div class="mb-4">
                                <input type="date" id="date" name="date" required
                                    class="w-full mb-2 px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                                <div id="dateAvailability" class="text-sm text-white opacity-90 hidden"></div>
                                <p class="text-xs text-white opacity-75 mt-1">Maximum 2 bookings per day. Calendar shows current availability.</p>
                            </div>

                            <div class="mb-4">
                                <input type="time" id="time" name="time" required
                                    class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                                <p class="text-white text-xs mt-1 opacity-75">Event time</p>
                            </div>

                            <div class="mb-4">
                                <input type="text" id="location" name="location" list="gensan_locations" placeholder="Location (Gensan City only) *" required
                                    class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                                <datalist id="gensan_locations">
                                    <option value="Barangay Lagao, Gensan City">
                                    <option value="Barangay Calumpang, Gensan City">
                                    <option value="Barangay Bula, Gensan City">
                                    <option value="Barangay Dadiangas North, Gensan City">
                                    <option value="Barangay Dadiangas South, Gensan City">
                                    <option value="Barangay Dadiangas West, Gensan City">
                                    <option value="Barangay Fatima, Gensan City">
                                    <option value="Barangay Labangal, Gensan City">
                                    <option value="Barangay San Isidro, Gensan City">
                                    <option value="Barangay Tambler, Gensan City">
                                    <option value="Barangay Apopong, Gensan City">
                                    <option value="Barangay Baluan, Gensan City">
                                    <option value="Barangay Buayan, Gensan City">
                                    <option value="Barangay Conel, Gensan City">
                                    <option value="Barangay Katangawan, Gensan City">
                                    <option value="Barangay Mabuhay, Gensan City">
                                    <option value="Barangay San Jose, Gensan City">
                                    <option value="Barangay Sinawal, Gensan City">
                                    <option value="Barangay Upper Labay, Gensan City">
                                    <option value="Barangay City Heights, Gensan City">
                                    <option value="Barangay Ligaya, Gensan City">
                                    <option value="Barangay Olympog, Gensan City">
                                    <option value="Barangay Poblacion, Gensan City">
                                    <option value="General Santos City">
                                </datalist>
                                <p class="text-white text-xs mt-1 opacity-75">Please select a location within General Santos City</p>
                            </div>

                            <textarea id="request" name="request" placeholder="Special Request (Optional)"
                                class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white resize-none"></textarea>
                        </div>

                    </form>
                </div>

                <!-- PREVIEW PANEL -->
                <div class="flex-1 bg-[#93BFC7] rounded-xl shadow p-6 flex flex-col" style="height: fit-content; max-height: 90vh;">
                    <h3 class="text-2xl font-bold text-white mb-6">Preview Booking</h3>

                    <div class="space-y-4 overflow-y-auto flex-1" style="max-height: calc(90vh - 200px);">

                        <div class="bg-white/20 p-4 rounded-lg">
                            <p class="text-white font-bold text-base">Event Type:</p>
                            <p class="text-white font-semibold" id="preview_event_type">-</p>
                        </div>

                        <!-- Wedding Preview Fields -->
                        <div id="preview_wedding" class="hidden space-y-4">
                            <div class="bg-white/20 p-4 rounded-lg">
                                <p class="text-white font-bold text-base">Bride Name:</p>
                                <p class="text-white font-semibold" id="preview_wedding_bride">-</p>
                            </div>
                            <div class="bg-white/20 p-4 rounded-lg">
                                <p class="text-white font-bold text-base">Groom Name:</p>
                                <p class="text-white font-semibold" id="preview_wedding_groom">-</p>
                            </div>
                            <div class="bg-white/20 p-4 rounded-lg">
                                <p class="text-white font-bold text-base">Number of Guests:</p>
                                <p class="text-white font-semibold" id="preview_wedding_guests">-</p>
                            </div>
                            <div class="bg-white/20 p-4 rounded-lg">
                                <p class="text-white font-bold text-base">Ceremony Venue:</p>
                                <p class="text-white font-semibold" id="preview_wedding_ceremony">-</p>
                            </div>
                            <div class="bg-white/20 p-4 rounded-lg">
                                <p class="text-white font-bold text-base">Reception Venue:</p>
                                <p class="text-white font-semibold" id="preview_wedding_reception">-</p>
                            </div>
                            <div class="bg-white/20 p-4 rounded-lg">
                                <p class="text-white font-bold text-base">Theme / Motif:</p>
                                <p class="text-white font-semibold" id="preview_wedding_theme">-</p>
                            </div>
                            <div class="bg-white/20 p-4 rounded-lg">
                                <p class="text-white font-bold text-base">Additional Notes:</p>
                                <p class="text-white font-semibold" id="preview_wedding_notes">-</p>
                            </div>
                        </div>

                        <!-- Birthday Preview Fields -->
                        <div id="preview_birthday" class="hidden space-y-4">
                            <div class="bg-white/20 p-4 rounded-lg">
                                <p class="text-white font-bold text-base">Celebrant Name:</p>
                                <p class="text-white font-semibold" id="preview_birthday_celebrant">-</p>
                            </div>
                            <div class="bg-white/20 p-4 rounded-lg">
                                <p class="text-white font-bold text-base">Age:</p>
                                <p class="text-white font-semibold" id="preview_birthday_age">-</p>
                            </div>
                            <div class="bg-white/20 p-4 rounded-lg">
                                <p class="text-white font-bold text-base">Venue:</p>
                                <p class="text-white font-semibold" id="preview_birthday_venue">-</p>
                            </div>
                            <div class="bg-white/20 p-4 rounded-lg">
                                <p class="text-white font-bold text-base">Number of Guests:</p>
                                <p class="text-white font-semibold" id="preview_birthday_guests">-</p>
                            </div>
                            <div class="bg-white/20 p-4 rounded-lg">
                                <p class="text-white font-bold text-base">Theme / Motif:</p>
                                <p class="text-white font-semibold" id="preview_birthday_theme">-</p>
                            </div>
                        </div>

                        <!-- Debut Preview Fields -->
                        <div id="preview_debut" class="hidden space-y-4">
                            <div class="bg-white/20 p-4 rounded-lg">
                                <p class="text-white font-bold text-base">Debutante Name:</p>
                                <p class="text-white font-semibold" id="preview_debut_name">-</p>
                            </div>
                            <div class="bg-white/20 p-4 rounded-lg">
                                <p class="text-white font-bold text-base">Venue:</p>
                                <p class="text-white font-semibold" id="preview_debut_venue">-</p>
                            </div>
                            <div class="bg-white/20 p-4 rounded-lg">
                                <p class="text-white font-bold text-base">Number of Guests:</p>
                                <p class="text-white font-semibold" id="preview_debut_guests">-</p>
                            </div>
                            <div class="bg-white/20 p-4 rounded-lg">
                                <p class="text-white font-bold text-base">Theme / Motif:</p>
                                <p class="text-white font-semibold" id="preview_debut_theme">-</p>
                            </div>
                            <div class="bg-white/20 p-4 rounded-lg">
                                <p class="text-white font-bold text-base">18 Roses Participants:</p>
                                <p class="text-white font-semibold" id="preview_debut_roses">-</p>
                            </div>
                            <div class="bg-white/20 p-4 rounded-lg">
                                <p class="text-white font-bold text-base">18 Candles Participants:</p>
                                <p class="text-white font-semibold" id="preview_debut_candles">-</p>
                            </div>
                            <div class="bg-white/20 p-4 rounded-lg">
                                <p class="text-white font-bold text-base">18 Treasures Participants:</p>
                                <p class="text-white font-semibold" id="preview_debut_treasures">-</p>
                            </div>
                            <div class="bg-white/20 p-4 rounded-lg">
                                <p class="text-white font-bold text-base">Program Notes:</p>
                                <p class="text-white font-semibold" id="preview_debut_notes">-</p>
                            </div>
                        </div>

                        <!-- Pageant Preview Fields -->
                        <div id="preview_pageant" class="hidden space-y-4">
                            <div class="bg-white/20 p-4 rounded-lg">
                                <p class="text-white font-bold text-base">Pageant Title:</p>
                                <p class="text-white font-semibold" id="preview_pageant_title">-</p>
                            </div>
                            <div class="bg-white/20 p-4 rounded-lg">
                                <p class="text-white font-bold text-base">Venue:</p>
                                <p class="text-white font-semibold" id="preview_pageant_venue">-</p>
                            </div>
                            <div class="bg-white/20 p-4 rounded-lg">
                                <p class="text-white font-bold text-base">Number of Guests:</p>
                                <p class="text-white font-semibold" id="preview_pageant_guests">-</p>
                            </div>
                            <div class="bg-white/20 p-4 rounded-lg">
                                <p class="text-white font-bold text-base">Theme / Motif:</p>
                                <p class="text-white font-semibold" id="preview_pageant_theme">-</p>
                            </div>
                            <div class="bg-white/20 p-4 rounded-lg">
                                <p class="text-white font-bold text-base">Number of Contestants:</p>
                                <p class="text-white font-semibold" id="preview_pageant_contestants">-</p>
                            </div>
                            <div class="bg-white/20 p-4 rounded-lg">
                                <p class="text-white font-bold text-base">Categories:</p>
                                <p class="text-white font-semibold" id="preview_pageant_categories">-</p>
                            </div>
                            <div class="bg-white/20 p-4 rounded-lg">
                                <p class="text-white font-bold text-base">Additional Notes:</p>
                                <p class="text-white font-semibold" id="preview_pageant_notes">-</p>
                            </div>
                        </div>

                        <!-- Corporate Preview Fields -->
                        <div id="preview_corporate" class="hidden space-y-4">
                            <div class="bg-white/20 p-4 rounded-lg">
                                <p class="text-white font-bold text-base">Company Name:</p>
                                <p class="text-white font-semibold" id="preview_corporate_company">-</p>
                            </div>
                            <div class="bg-white/20 p-4 rounded-lg">
                                <p class="text-white font-bold text-base">Event Title / Theme:</p>
                                <p class="text-white font-semibold" id="preview_corporate_title">-</p>
                            </div>
                            <div class="bg-white/20 p-4 rounded-lg">
                                <p class="text-white font-bold text-base">Venue / Location:</p>
                                <p class="text-white font-semibold" id="preview_corporate_venue">-</p>
                            </div>
                            <div class="bg-white/20 p-4 rounded-lg">
                                <p class="text-white font-bold text-base">Number of Attendees:</p>
                                <p class="text-white font-semibold" id="preview_corporate_attendees">-</p>
                            </div>
                            <div class="bg-white/20 p-4 rounded-lg">
                                <p class="text-white font-bold text-base">Company Representative:</p>
                                <p class="text-white font-semibold" id="preview_corporate_representative">-</p>
                            </div>
                            <div class="bg-white/20 p-4 rounded-lg">
                                <p class="text-white font-bold text-base">Contact Number:</p>
                                <p class="text-white font-semibold" id="preview_corporate_contact">-</p>
                            </div>
                            <div class="bg-white/20 p-4 rounded-lg">
                                <p class="text-white font-bold text-base">Event Requirements:</p>
                                <p class="text-white font-semibold" id="preview_corporate_requirements">-</p>
                            </div>
                        </div>

                        <div class="bg-white/20 p-4 rounded-lg">
                            <p class="text-white font-bold text-base">Date:</p>
                            <p class="text-white font-semibold" id="preview_date">-</p>
                        </div>

                        <div class="bg-white/20 p-4 rounded-lg">
                            <p class="text-white font-bold text-base">Time:</p>
                            <p class="text-white font-semibold" id="preview_time">-</p>
                        </div>

                        <div class="bg-white/20 p-4 rounded-lg">
                            <p class="text-white font-bold text-base">Location:</p>
                            <p class="text-white font-semibold" id="preview_location">-</p>
                        </div>

                        <div class="bg-white/20 p-4 rounded-lg">
                            <p class="text-white font-bold text-base">Request:</p>
                            <p class="text-white font-semibold" id="preview_request">-</p>
                        </div>
                    </div>

                    <button type="submit" id="submitBookingBtn" form="bookingForm"
                        class="w-full mt-6 bg-[#EEFBE8] text-[#93BFC7] font-bold py-3 rounded-lg 
                        hover:bg-[#d4e8c8] transition-all shadow cursor-pointer">
                        Submit Booking
                    </button>
                </div>
            </div>

            <!-- Toast Notification Container -->
            <div id="toastContainer" class="fixed top-4 right-4 z-50 space-y-2"></div>

            <!-- Error Modal -->
            <div id="errorModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
                <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 transform transition-all">
                    <div class="bg-[#93BFC7] rounded-t-xl px-6 py-4 flex items-center justify-between" id="modalHeader">
                        <h3 class="text-xl font-bold text-white" id="modalTitle">
                            <i class="fas fa-exclamation-triangle mr-2"></i>Validation Error
                        </h3>
                        <button id="closeModal" class="text-white hover:text-gray-200 transition-colors">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-700 mb-4" id="modalMessage">Please fill in all required fields.</p>
                        <ul id="errorList" class="list-disc list-inside text-gray-600 space-y-2 mb-4"></ul>
                        <button id="modalOkBtn" class="w-full bg-[#93BFC7] text-white font-bold py-3 rounded-lg 
                            hover:bg-[#7aa8b0] transition-all shadow">
                            OK
                        </button>
                    </div>
                </div>
            </div>

            <!-- LOGIC -->
            <script>
                // Toast Notification Function
                function showToast(message, type = 'success') {
                    const toastContainer = document.getElementById('toastContainer');
                    const toast = document.createElement('div');
                    
                    const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
                    const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
                    
                    toast.className = `${bgColor} text-white px-6 py-4 rounded-lg shadow-lg flex items-center space-x-3 min-w-[300px] max-w-md transform transition-all duration-300 translate-x-full opacity-0`;
                    toast.innerHTML = `
                        <i class="fas ${icon} text-xl"></i>
                        <p class="flex-1 font-medium">${message}</p>
                        <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    
                    toastContainer.appendChild(toast);
                    
                    // Trigger animation
                    setTimeout(() => {
                        toast.classList.remove('translate-x-full', 'opacity-0');
                        toast.classList.add('translate-x-0', 'opacity-100');
                    }, 10);
                    
                    // Auto remove after 5 seconds
                    setTimeout(() => {
                        toast.classList.add('translate-x-full', 'opacity-0');
                        setTimeout(() => {
                            if (toast.parentElement) {
                                toast.remove();
                            }
                        }, 300);
                    }, 5000);
                }

                // Modal functions
                const errorModal = document.getElementById('errorModal');
                const modalMessage = document.getElementById('modalMessage');
                const errorList = document.getElementById('errorList');
                const closeModal = document.getElementById('closeModal');
                const modalOkBtn = document.getElementById('modalOkBtn');

                function showErrorModal(message, errors = []) {
                    modalMessage.textContent = message;
                    errorList.innerHTML = '';
                    
                    const modalTitle = document.getElementById('modalTitle');
                    const modalHeader = document.getElementById('modalHeader');
                    
                    // Check if it's a success message
                    if (message.toLowerCase().includes('valid') || message.toLowerCase().includes('success')) {
                        modalTitle.innerHTML = '<i class="fas fa-check-circle mr-2"></i>Success';
                        modalHeader.className = 'bg-green-500 rounded-t-xl px-6 py-4 flex items-center justify-between';
                    } else {
                        modalTitle.innerHTML = '<i class="fas fa-exclamation-triangle mr-2"></i>Validation Error';
                        modalHeader.className = 'bg-[#93BFC7] rounded-t-xl px-6 py-4 flex items-center justify-between';
                    }
                    
                    if (errors.length > 0) {
                        errorList.style.display = 'block';
                        errors.forEach(error => {
                            const li = document.createElement('li');
                            li.textContent = error;
                            errorList.appendChild(li);
                        });
                    } else {
                        errorList.style.display = 'none';
                    }
                    
                    errorModal.classList.remove('hidden');
                }

                function hideErrorModal() {
                    errorModal.classList.add('hidden');
                }

                // Close modal events
                closeModal.addEventListener('click', hideErrorModal);
                modalOkBtn.addEventListener('click', hideErrorModal);
                errorModal.addEventListener('click', function(e) {
                    if (e.target === errorModal) {
                        hideErrorModal();
                    }
                });

                // Set date restrictions: minimum 2 weeks (14 days) from today, no past dates
                const dateInput = document.getElementById('date');
                if (dateInput) {
                    const today = new Date();
                    const minDate = new Date(today);
                    minDate.setDate(today.getDate() + 14); // 2 weeks from today
                    
                    const minDateString = minDate.toISOString().split('T')[0];
                    dateInput.setAttribute('min', minDateString);
                    
                    // Check booking availability when date is selected
                    dateInput.addEventListener('change', function(e) {
                        const selectedDate = new Date(e.target.value);
                        if (selectedDate < minDate) {
                            showErrorModal('Please select a date at least 2 weeks from today.');
                            e.target.value = '';
                            document.getElementById('preview_date').textContent = '-';
                            return;
                        }
                        
                        // Check if date is fully booked
                        const dateStr = e.target.value;
                        const availabilityDiv = document.getElementById('dateAvailability');
                        
                        fetch(`{{ route('events.booking-count') }}?date=${dateStr}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.isFull) {
                                showErrorModal('This date is fully booked (2/2 bookings). Please select another date.');
                                e.target.value = '';
                                document.getElementById('preview_date').textContent = '-';
                                if (availabilityDiv) {
                                    availabilityDiv.classList.add('hidden');
                                }
                            } else if (data.count > 0) {
                                // Show availability info
                                if (availabilityDiv) {
                                    availabilityDiv.textContent = `⚠️ This date has ${data.count}/2 bookings. ${2 - data.count} slot(s) remaining.`;
                                    availabilityDiv.classList.remove('hidden');
                                    availabilityDiv.classList.add('text-yellow-200');
                                }
                            } else {
                                // Date is available
                                if (availabilityDiv) {
                                    availabilityDiv.textContent = '✓ This date is available (0/2 bookings)';
                                    availabilityDiv.classList.remove('hidden');
                                    availabilityDiv.classList.remove('text-yellow-200');
                                    availabilityDiv.classList.add('text-green-200');
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error checking booking availability:', error);
                            if (availabilityDiv) {
                                availabilityDiv.classList.add('hidden');
                            }
                        });
                    });
                }

                // Location validation: must be in Gensan City
                const locationInput = document.getElementById('location');
                if (locationInput) {
                    locationInput.addEventListener('blur', function(e) {
                        const locationValue = e.target.value.toLowerCase();
                        const validKeywords = ['gensan', 'general santos', 'general santos city'];
                        const isValid = validKeywords.some(keyword => locationValue.includes(keyword));
                        
                        if (e.target.value && !isValid) {
                            showErrorModal('Please enter a location within General Santos City only.');
                            e.target.value = '';
                            document.getElementById('preview_location').textContent = '-';
                        }
                    });
                }

                const eventSelect = document.getElementById('event_type');
                const forms = document.querySelectorAll('.eventForm');
                const eventInfo = document.getElementById('event_info_container');
                const previewSections = ['preview_wedding', 'preview_birthday', 'preview_debut', 'preview_pageant', 'preview_corporate'];
                const submitButton = document.getElementById('submitBookingBtn');

                // Hide all preview sections
                function hideAllPreviewSections() {
                    previewSections.forEach(section => {
                        document.getElementById(section).classList.add('hidden');
                    });
                }

                // Show preview section for selected event type
                function showPreviewSection(eventType) {
                    hideAllPreviewSections();
                    if (eventType) {
                        document.getElementById(`preview_${eventType}`).classList.remove('hidden');
                    }
                }

                eventSelect.addEventListener('change', () => {
                    forms.forEach(form => form.classList.add('hidden'));

                    if (eventSelect.value) {
                        document.getElementById(`form_${eventSelect.value}`).classList.remove('hidden');
                        eventInfo.classList.remove('hidden');
                        showPreviewSection(eventSelect.value);
                    } else {
                        eventInfo.classList.add('hidden');
                        hideAllPreviewSections();
                    }

                    document.getElementById('preview_event_type').textContent =
                        eventSelect.value ? eventSelect.value.toUpperCase() : "-";
                });

                // Wedding form listeners
                document.getElementById('wedding_bride')?.addEventListener('input', e =>
                    document.getElementById('preview_wedding_bride').textContent = e.target.value || '-'
                );
                document.getElementById('wedding_groom')?.addEventListener('input', e =>
                    document.getElementById('preview_wedding_groom').textContent = e.target.value || '-'
                );
                document.getElementById('wedding_guests')?.addEventListener('input', e =>
                    document.getElementById('preview_wedding_guests').textContent = e.target.value || '-'
                );
                document.getElementById('wedding_ceremony')?.addEventListener('input', e =>
                    document.getElementById('preview_wedding_ceremony').textContent = e.target.value || '-'
                );
                document.getElementById('wedding_reception')?.addEventListener('input', e =>
                    document.getElementById('preview_wedding_reception').textContent = e.target.value || '-'
                );
                document.getElementById('wedding_theme')?.addEventListener('input', e =>
                    document.getElementById('preview_wedding_theme').textContent = e.target.value || '-'
                );
                document.getElementById('wedding_notes')?.addEventListener('input', e =>
                    document.getElementById('preview_wedding_notes').textContent = e.target.value || '-'
                );

                // Birthday form listeners
                document.getElementById('birthday_celebrant')?.addEventListener('input', e =>
                    document.getElementById('preview_birthday_celebrant').textContent = e.target.value || '-'
                );
                document.getElementById('birthday_age')?.addEventListener('input', e =>
                    document.getElementById('preview_birthday_age').textContent = e.target.value || '-'
                );
                document.getElementById('birthday_venue')?.addEventListener('input', e =>
                    document.getElementById('preview_birthday_venue').textContent = e.target.value || '-'
                );
                document.getElementById('birthday_guests')?.addEventListener('input', e =>
                    document.getElementById('preview_birthday_guests').textContent = e.target.value || '-'
                );
                document.getElementById('birthday_theme')?.addEventListener('input', e =>
                    document.getElementById('preview_birthday_theme').textContent = e.target.value || '-'
                );

                // Debut form listeners
                document.getElementById('debut_name')?.addEventListener('input', e =>
                    document.getElementById('preview_debut_name').textContent = e.target.value || '-'
                );
                document.getElementById('debut_venue')?.addEventListener('input', e =>
                    document.getElementById('preview_debut_venue').textContent = e.target.value || '-'
                );
                document.getElementById('debut_guests')?.addEventListener('input', e =>
                    document.getElementById('preview_debut_guests').textContent = e.target.value || '-'
                );
                document.getElementById('debut_theme')?.addEventListener('input', e =>
                    document.getElementById('preview_debut_theme').textContent = e.target.value || '-'
                );
                document.getElementById('debut_roses')?.addEventListener('input', e =>
                    document.getElementById('preview_debut_roses').textContent = e.target.value || '-'
                );
                document.getElementById('debut_candles')?.addEventListener('input', e =>
                    document.getElementById('preview_debut_candles').textContent = e.target.value || '-'
                );
                document.getElementById('debut_treasures')?.addEventListener('input', e =>
                    document.getElementById('preview_debut_treasures').textContent = e.target.value || '-'
                );
                document.getElementById('debut_notes')?.addEventListener('input', e =>
                    document.getElementById('preview_debut_notes').textContent = e.target.value || '-'
                );

                // Pageant form listeners
                document.getElementById('pageant_title')?.addEventListener('input', e =>
                    document.getElementById('preview_pageant_title').textContent = e.target.value || '-'
                );
                document.getElementById('pageant_venue')?.addEventListener('input', e =>
                    document.getElementById('preview_pageant_venue').textContent = e.target.value || '-'
                );
                document.getElementById('pageant_guests')?.addEventListener('input', e =>
                    document.getElementById('preview_pageant_guests').textContent = e.target.value || '-'
                );
                document.getElementById('pageant_theme')?.addEventListener('input', e =>
                    document.getElementById('preview_pageant_theme').textContent = e.target.value || '-'
                );
                document.getElementById('pageant_contestants')?.addEventListener('input', e =>
                    document.getElementById('preview_pageant_contestants').textContent = e.target.value || '-'
                );
                document.getElementById('pageant_categories')?.addEventListener('input', e =>
                    document.getElementById('preview_pageant_categories').textContent = e.target.value || '-'
                );
                document.getElementById('pageant_notes')?.addEventListener('input', e =>
                    document.getElementById('preview_pageant_notes').textContent = e.target.value || '-'
                );

                // Corporate form listeners
                document.getElementById('corporate_company')?.addEventListener('input', e =>
                    document.getElementById('preview_corporate_company').textContent = e.target.value || '-'
                );
                document.getElementById('corporate_title')?.addEventListener('input', e =>
                    document.getElementById('preview_corporate_title').textContent = e.target.value || '-'
                );
                document.getElementById('corporate_venue')?.addEventListener('input', e =>
                    document.getElementById('preview_corporate_venue').textContent = e.target.value || '-'
                );
                document.getElementById('corporate_attendees')?.addEventListener('input', e =>
                    document.getElementById('preview_corporate_attendees').textContent = e.target.value || '-'
                );
                document.getElementById('corporate_representative')?.addEventListener('input', e =>
                    document.getElementById('preview_corporate_representative').textContent = e.target.value || '-'
                );
                document.getElementById('corporate_contact')?.addEventListener('input', e =>
                    document.getElementById('preview_corporate_contact').textContent = e.target.value || '-'
                );
                document.getElementById('corporate_requirements')?.addEventListener('input', e =>
                    document.getElementById('preview_corporate_requirements').textContent = e.target.value || '-'
                );

                // Event info listeners
                document.getElementById('date')?.addEventListener('change', e =>
                    document.getElementById('preview_date').textContent = e.target.value || '-'
                );

                document.getElementById('time')?.addEventListener('change', e => {
                    const timeValue = e.target.value;
                    if (timeValue) {
                        // Format time for display (e.g., "14:30" -> "2:30 PM")
                        const [hours, minutes] = timeValue.split(':');
                        const hour = parseInt(hours);
                        const ampm = hour >= 12 ? 'PM' : 'AM';
                        const displayHour = hour % 12 || 12;
                        document.getElementById('preview_time').textContent = `${displayHour}:${minutes} ${ampm}`;
                    } else {
                        document.getElementById('preview_time').textContent = '-';
                    }
                });

                document.getElementById('location')?.addEventListener('input', e =>
                    document.getElementById('preview_location').textContent = e.target.value || '-'
                );

                document.getElementById('request')?.addEventListener('input', e =>
                    document.getElementById('preview_request').textContent = e.target.value || '-'
                );

                // Form validation on submit
                const bookingForm = document.getElementById('bookingForm');
                
                // Function to handle form submission
                function handleFormSubmit(e) {
                    if (e) {
                        e.preventDefault();
                    }
                        
                        const missingFields = [];
                        
                        // Check if event type is selected
                        if (!eventSelect.value) {
                            showErrorModal('Please select an event type to continue.', ['Event Type']);
                            eventSelect.focus();
                            return false;
                        }

                        // Get the active form based on event type
                        const activeForm = document.getElementById(`form_${eventSelect.value}`);
                        if (!activeForm) {
                            showErrorModal('Please fill in all required event details.');
                            return false;
                        }

                        // Helper function to get field label from placeholder or name
                        function getFieldLabel(field) {
                            if (field.placeholder && field.placeholder !== '') {
                                return field.placeholder.replace(' *', '').replace(' (Optional)', '');
                            }
                            if (field.name) {
                                return field.name.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                            }
                            return 'Field';
                        }

                        // Check all required fields in the active event form
                        const requiredFields = activeForm.querySelectorAll('[required]');
                        let isValid = true;
                        let firstInvalidField = null;

                        requiredFields.forEach(field => {
                            if (!field.value.trim()) {
                                isValid = false;
                                missingFields.push(getFieldLabel(field));
                                if (!firstInvalidField) {
                                    firstInvalidField = field;
                                }
                                field.classList.add('border-2', 'border-red-400');
                            } else {
                                field.classList.remove('border-2', 'border-red-400');
                            }
                        });

                        // Check event info required fields
                        const dateField = document.getElementById('date');
                        const timeField = document.getElementById('time');
                        const locationField = document.getElementById('location');

                        if (dateField && !dateField.value) {
                            isValid = false;
                            missingFields.push('Date');
                            if (!firstInvalidField) firstInvalidField = dateField;
                            dateField.classList.add('border-2', 'border-red-400');
                        } else if (dateField) {
                            dateField.classList.remove('border-2', 'border-red-400');
                        }

                        if (timeField && !timeField.value) {
                            isValid = false;
                            missingFields.push('Time');
                            if (!firstInvalidField) firstInvalidField = timeField;
                            timeField.classList.add('border-2', 'border-red-400');
                        } else if (timeField) {
                            timeField.classList.remove('border-2', 'border-red-400');
                        }

                        if (locationField && !locationField.value.trim()) {
                            isValid = false;
                            missingFields.push('Location');
                            if (!firstInvalidField) firstInvalidField = locationField;
                            locationField.classList.add('border-2', 'border-red-400');
                        } else if (locationField) {
                            locationField.classList.remove('border-2', 'border-red-400');
                        }

                        // Validate location contains Gensan
                        if (locationField && locationField.value.trim()) {
                            const locationValue = locationField.value.toLowerCase();
                            const validKeywords = ['gensan', 'general santos', 'general santos city'];
                            const isLocationValid = validKeywords.some(keyword => locationValue.includes(keyword));
                            
                            if (!isLocationValid) {
                                showErrorModal('Location must be within General Santos City.');
                                locationField.focus();
                                locationField.classList.add('border-2', 'border-red-400');
                                return false;
                            }
                        }

                        if (!isValid) {
                            showErrorModal('Please fill in all required fields before submitting.', missingFields);
                            if (firstInvalidField) {
                                firstInvalidField.focus();
                                firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            }
                            return false;
                        }

                        // If all validations pass, submit the form via AJAX
                        const formData = new FormData(bookingForm);
                        const eventType = eventSelect.value;
                        formData.append('event_type', eventType);
                        formData.append('date', dateField.value);
                        formData.append('time', timeField.value);
                        formData.append('location', locationField.value);
                        formData.append('request', document.getElementById('request').value || '');
                        
                        // Get theme from event-specific field
                        let themeValue = '';
                        if (eventType === 'wedding') {
                            themeValue = document.getElementById('wedding_theme')?.value || '';
                        } else if (eventType === 'birthday') {
                            themeValue = document.getElementById('birthday_theme')?.value || '';
                        } else if (eventType === 'debut') {
                            themeValue = document.getElementById('debut_theme')?.value || '';
                        } else if (eventType === 'pageant') {
                            themeValue = document.getElementById('pageant_theme')?.value || '';
                        } else if (eventType === 'corporate') {
                            themeValue = document.getElementById('corporate_title')?.value || '';
                        }
                        formData.append('theme', themeValue);
                        
                        // Calculate total amount based on event type and guests
                        let totalAmount = 50000; // Base amount
                        
                        // Get guest count based on event type
                        let guestCount = 0;
                        if (eventType === 'wedding') {
                            guestCount = parseInt(document.getElementById('wedding_guests').value) || 0;
                        } else if (eventType === 'birthday') {
                            guestCount = parseInt(document.getElementById('birthday_guests').value) || 0;
                        } else if (eventType === 'debut') {
                            guestCount = parseInt(document.getElementById('debut_guests').value) || 0;
                        } else if (eventType === 'pageant') {
                            guestCount = parseInt(document.getElementById('pageant_guests').value) || 0;
                        } else if (eventType === 'corporate') {
                            guestCount = parseInt(document.getElementById('corporate_attendees').value) || 0;
                        }
                        
                        // Calculate amount: base + (guests * 500)
                        totalAmount = totalAmount + (guestCount * 500);
                        formData.append('total_amount', totalAmount);

                        // Show loading state
                        const originalText = submitButton.innerHTML;
                        submitButton.disabled = true;
                        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Submitting...';

                        fetch('{{ route("booking.store") }}', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        })
                        .then(async response => {
                            const contentType = response.headers.get('content-type');
                            
                            if (contentType && contentType.includes('application/json')) {
                                const data = await response.json();
                                
                                if (response.ok && data.success) {
                                    // Show success toast
                                    showToast(data.message || 'Booking submitted successfully!', 'success');
                                    
                                    // Reset form
                                    bookingForm.reset();
                                    eventSelect.value = '';
                                    forms.forEach(form => form.classList.add('hidden'));
                                    eventInfo.classList.add('hidden');
                                    hideAllPreviewSections();
                                    
                                    // Redirect after 2 seconds
                                    setTimeout(() => {
                                        window.location.href = '{{ route("home") }}';
                                    }, 2000);
                                } else {
                                    // Show error toast
                                    let errorMessage = data.message || 'An error occurred. Please try again.';
                                    
                                    // If there are validation errors, show them
                                    if (data.errors) {
                                        const errorMessages = Object.values(data.errors).flat();
                                        errorMessage = errorMessages.join(', ');
                                    }
                                    
                                    showToast(errorMessage, 'error');
                                    submitButton.disabled = false;
                                    submitButton.innerHTML = originalText;
                                }
                            } else {
                                // Handle non-JSON response (shouldn't happen, but just in case)
                                const text = await response.text();
                                console.error('Non-JSON response:', text);
                                showToast('An unexpected error occurred. Please try again.', 'error');
                                submitButton.disabled = false;
                                submitButton.innerHTML = originalText;
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showToast('An error occurred while submitting the booking. Please check your connection and try again.', 'error');
                            submitButton.disabled = false;
                            submitButton.innerHTML = originalText;
                        });
                }
                
                // Attach event listeners
                if (bookingForm) {
                    bookingForm.addEventListener('submit', handleFormSubmit);
                }
                
                // Also attach click handler to button as backup
                if (submitButton) {
                    submitButton.addEventListener('click', function(e) {
                        e.preventDefault();
                        handleFormSubmit(e);
                    });
                }

                        // Remove error styling on input
                        document.querySelectorAll('[required]').forEach(field => {
                            field.addEventListener('input', function() {
                                this.classList.remove('border-2', 'border-red-400');
                            });
                            field.addEventListener('change', function() {
                                this.classList.remove('border-2', 'border-red-400');
                            });
                        });
            </script>

        </div>
    </div>

</body>
</html>
