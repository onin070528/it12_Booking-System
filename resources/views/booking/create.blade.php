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

            <!-- Select Event Type -->
            <div class="bg-[#93BFC7] rounded-xl shadow p-6 mb-6">
                <h3 class="text-2xl font-bold text-white mb-4">
                    <i class="fas fa-list mr-2"></i>Choose the event you are planning
                </h3>

                <!-- Hidden input to submit event_type when select is disabled -->
                <input type="hidden" id="event_type_hidden" name="event_type" value="">
                
                <div class="relative">
                    <select id="event_type" name="event_type_select" required class="w-full px-4 py-3 rounded-lg border-0 
                            focus:ring-2 focus:ring-white shadow-[0_2px_8px_rgba(0,0,0,0.15)] text-lg font-medium
                            disabled:bg-gray-100 disabled:text-gray-600 disabled:cursor-not-allowed">
                        <option value="">Choose event type</option>
                        <option value="wedding">Wedding</option>
                        <option value="birthday">Birthday</option>
                        <option value="christening">Christening</option>
                        <option value="debut">Debut</option>
                        <option value="pageant">Pageant</option>
                        <option value="corporate">Corporate Event</option>
                    </select>
                    <span id="event_lock_indicator" class="hidden absolute right-10 top-1/2 transform -translate-y-1/2 text-gray-500">
                        <i class="fas fa-lock"></i>
                    </span>
                </div>
                <p id="event_type_hint" class="text-white text-xs mt-1 opacity-75"><span class="text-red-300">*</span> Required fields</p>
            </div>

            <div class="flex gap-6">

                <!-- LEFT SIDE - Event Details -->
                <div class="flex-1">

                    <!-- EVENT FORMS -->
                    <form id="bookingForm" action="{{ route('booking.store') }}" method="POST">

                        @csrf

                        <!-- WEDDING FORM -->
                        <div id="form_wedding" class="eventForm hidden bg-[#93BFC7] rounded-xl shadow p-6 mb-6">
                            <h3 class="text-xl font-bold text-white mb-4">Wedding Details</h3>
                            
                            <!-- Bride Name -->
                            <div class="mb-4">
                                <label class="text-white font-semibold mb-2 block">Bride's Name *</label>
                                <div class="grid grid-cols-3 gap-2">
                                    <input type="text" id="wedding_bride_firstname" name="wedding_bride_firstname" placeholder="First Name " required class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                                    <input type="text" id="wedding_bride_middlename" name="wedding_bride_middlename" placeholder="Middle Name" class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                                    <input type="text" id="wedding_bride_lastname" name="wedding_bride_lastname" placeholder="Last Name " required class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                                </div>
                            </div>
                            
                            <!-- Groom Name -->
                            <div class="mb-4">
                                <label class="text-white font-semibold mb-2 block">Groom's Name *</label>
                                <div class="grid grid-cols-3 gap-2">
                                    <input type="text" id="wedding_groom_firstname" name="wedding_groom_firstname" placeholder="First Name " required class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                                    <input type="text" id="wedding_groom_middlename" name="wedding_groom_middlename" placeholder="Middle Name" class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                                    <input type="text" id="wedding_groom_lastname" name="wedding_groom_lastname" placeholder="Last Name " required class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="text-white font-semibold mb-2 block">Number of Guests *</label>
                                <input type="number" id="wedding_guests" name="wedding_guests" placeholder="Enter number of guests" required min="1" class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                            </div>
                            
                            <div class="mb-4">
                                <label class="text-white font-semibold mb-2 block">Ceremony Venue *</label>
                                <input type="text" id="wedding_ceremony" name="wedding_ceremony" placeholder="Enter ceremony venue" required class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                            </div>
                            
                            <div class="mb-4">
                                <label class="text-white font-semibold mb-2 block">Reception Venue *</label>
                                <input type="text" id="wedding_reception" name="wedding_reception" placeholder="Enter reception venue" required class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                            </div>
                            
                            <div class="mb-4">
                                <label class="text-white font-semibold mb-2 block">Theme / Motif *</label>
                                <input type="text" id="wedding_theme" name="wedding_theme" placeholder="Enter theme or motif" required class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                            </div>
                            
                            <div class="mb-4">
                                <label class="text-white font-semibold mb-2 block">Additional Notes (Optional)</label>
                                <textarea id="wedding_notes" name="wedding_notes" placeholder="Enter any additional notes" class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white resize-none" rows="3"></textarea>
                            </div>
                        </div>

                        <!-- BIRTHDAY FORM -->
                        <div id="form_birthday" class="eventForm hidden bg-[#93BFC7] rounded-xl shadow p-6 mb-6">
                            <h3 class="text-xl font-bold text-white mb-4">Birthday Details</h3>
                            
                            <!-- Celebrant Name -->
                            <div class="mb-4">
                                <label class="text-white font-semibold mb-2 block">Celebrant's Name *</label>
                                <div class="grid grid-cols-3 gap-2">
                                    <input type="text" id="birthday_celebrant_firstname" name="birthday_celebrant_firstname" placeholder="First Name " required class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                                    <input type="text" id="birthday_celebrant_middlename" name="birthday_celebrant_middlename" placeholder="Middle Name" class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                                    <input type="text" id="birthday_celebrant_lastname" name="birthday_celebrant_lastname" placeholder="Last Name " required class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="text-white font-semibold mb-2 block">Age *</label>
                                <input type="number" id="birthday_age" name="birthday_age" placeholder="Enter age" required min="1" class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                            </div>
                            
                            <div class="mb-4">
                                <label class="text-white font-semibold mb-2 block">Number of Guests *</label>
                                <input type="number" id="birthday_guests" name="birthday_guests" placeholder="Enter number of guests" required min="1" class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                            </div>
                            
                            <div class="mb-4">
                                <label class="text-white font-semibold mb-2 block">Theme / Motif *</label>
                                <textarea id="birthday_theme" name="birthday_theme" placeholder="Enter theme or motif" required class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white resize-none" rows="3"></textarea>
                            </div>
                        </div>

                        <!-- CHRISTENING FORM -->
                        <div id="form_christening" class="eventForm hidden bg-[#93BFC7] rounded-xl shadow p-6 mb-6">
                            <h3 class="text-xl font-bold text-white mb-4">Christening Details</h3>
                            
                            <!-- Child's Name -->
                            <div class="mb-4">
                                <label class="text-white font-semibold mb-2 block">Child's Name *</label>
                                <div class="grid grid-cols-3 gap-2">
                                    <input type="text" id="christening_child_firstname" name="christening_child_firstname" placeholder="First Name " required class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                                    <input type="text" id="christening_child_middlename" name="christening_child_middlename" placeholder="Middle Name" class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                                    <input type="text" id="christening_child_lastname" name="christening_child_lastname" placeholder="Last Name " required class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="text-white font-semibold mb-2 block">Parents' Names *</label>
                                <input type="text" id="christening_parents" name="christening_parents" placeholder="Enter parents' names (e.g., John & Jane Doe)" required class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                            </div>
                            
                            <div class="mb-4">
                                <label class="text-white font-semibold mb-2 block">Number of Guests *</label>
                                <input type="number" id="christening_guests" name="christening_guests" placeholder="Enter number of guests" required min="1" class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                            </div>
                            
                            <div class="mb-4">
                                <label class="text-white font-semibold mb-2 block">Additional Notes (Optional)</label>
                                <textarea id="christening_notes" name="christening_notes" placeholder="Enter any additional notes" class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white resize-none" rows="3"></textarea>
                            </div>
                        </div>

                        <!-- DEBUT FORM -->
                        <div id="form_debut" class="eventForm hidden bg-[#93BFC7] rounded-xl shadow p-6 mb-6">
                            <h3 class="text-xl font-bold text-white mb-4">Debut Details</h3>
                            
                            <!-- Debutante Name -->
                            <div class="mb-4">
                                <label class="text-white font-semibold mb-2 block">Debutante's Name *</label>
                                <div class="grid grid-cols-3 gap-2">
                                    <input type="text" id="debut_firstname" name="debut_firstname" placeholder="First Name " required class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                                    <input type="text" id="debut_middlename" name="debut_middlename" placeholder="Middle Name" class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                                    <input type="text" id="debut_lastname" name="debut_lastname" placeholder="Last Name " required class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="text-white font-semibold mb-2 block">Number of Guests *</label>
                                <input type="number" id="debut_guests" name="debut_guests" placeholder="Enter number of guests" required min="1" class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                            </div>
                            
                            <!-- 18 Roses - Collapsible -->
                            <div class="mb-4">
                                <button type="button" onclick="toggleSection('rosesSection')" class="w-full flex items-center justify-between text-white font-semibold mb-2 px-3 py-2 bg-white/20 rounded-lg hover:bg-white/30 transition">
                                    <span>18 Roses *</span>
                                    <i class="fas fa-chevron-down" id="rosesIcon"></i>
                                </button>
                                <div id="rosesSection" class="hidden grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2 mt-2">
                                    @for($i = 1; $i <= 18; $i++)
                                        <div>
                                            <label class="text-white text-xs mb-1 block">Rose {{ $i }}</label>
                                            <input type="text" id="debut_rose_{{ $i }}" name="debut_roses[]" placeholder="Enter name" required class="w-full px-2 py-1.5 rounded-lg focus:ring-2 focus:ring-white text-sm">
                                        </div>
                                    @endfor
                                </div>
                            </div>

                            <!-- 18 Candles - Collapsible -->
                            <div class="mb-4">
                                <button type="button" onclick="toggleSection('candlesSection')" class="w-full flex items-center justify-between text-white font-semibold mb-2 px-3 py-2 bg-white/20 rounded-lg hover:bg-white/30 transition">
                                    <span>18 Candles *</span>
                                    <i class="fas fa-chevron-down" id="candlesIcon"></i>
                                </button>
                                <div id="candlesSection" class="hidden grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2 mt-2">
                                    @for($i = 1; $i <= 18; $i++)
                                        <div>
                                            <label class="text-white text-xs mb-1 block">Candle {{ $i }}</label>
                                            <input type="text" id="debut_candle_{{ $i }}" name="debut_candles[]" placeholder="Enter name" required class="w-full px-2 py-1.5 rounded-lg focus:ring-2 focus:ring-white text-sm">
                                        </div>
                                    @endfor
                                </div>
                            </div>

                            <!-- 18 Treasures - Collapsible -->
                            <div class="mb-4">
                                <button type="button" onclick="toggleSection('treasuresSection')" class="w-full flex items-center justify-between text-white font-semibold mb-2 px-3 py-2 bg-white/20 rounded-lg hover:bg-white/30 transition">
                                    <span>18 Treasures *</span>
                                    <i class="fas fa-chevron-down" id="treasuresIcon"></i>
                                </button>
                                <div id="treasuresSection" class="hidden grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-2 mt-2">
                                    @for($i = 1; $i <= 18; $i++)
                                        <div>
                                            <label class="text-white text-xs mb-1 block">Treasure {{ $i }}</label>
                                            <input type="text" id="debut_treasure_{{ $i }}" name="debut_treasures[]" placeholder="Enter name" required class="w-full px-2 py-1.5 rounded-lg focus:ring-2 focus:ring-white text-sm">
                                        </div>
                                    @endfor
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="text-white font-semibold mb-2 block">Program Notes (Optional)</label>
                                <textarea id="debut_notes" name="debut_notes" placeholder="Enter any program notes" class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white resize-none" rows="3"></textarea>
                            </div>
                        </div>

                        <!-- PAGEANT FORM -->
                        <div id="form_pageant" class="eventForm hidden bg-[#93BFC7] rounded-xl shadow p-6 mb-6">
                            <h3 class="text-xl font-bold text-white mb-4">Pageant Details</h3>
                            
                            <!-- Organizer Name -->
                            <div class="mb-4">
                                <label class="text-white font-semibold mb-2 block">Organizer's Name *</label>
                                <div class="grid grid-cols-3 gap-2">
                                    <input type="text" id="pageant_organizer_firstname" name="pageant_organizer_firstname" placeholder="First Name " required class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                                    <input type="text" id="pageant_organizer_middlename" name="pageant_organizer_middlename" placeholder="Middle Name" class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                                    <input type="text" id="pageant_organizer_lastname" name="pageant_organizer_lastname" placeholder="Last Name " required class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="text-white font-semibold mb-2 block">Pageant Title *</label>
                                <input type="text" id="pageant_title" name="pageant_title" placeholder="Enter pageant title" required class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                            </div>
                            
                            <div class="mb-4">
                                <label class="text-white font-semibold mb-2 block">Number of Guests *</label>
                                <input type="number" id="pageant_guests" name="pageant_guests" placeholder="Enter number of guests" required min="1" class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                            </div>
                            
                            <div class="mb-4">
                                <label class="text-white font-semibold mb-2 block">Number of Contestants *</label>
                                <input type="number" id="pageant_contestants" name="pageant_contestants" placeholder="Enter number of contestants" required min="1" class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                            </div>
                            
                            <div class="mb-4">
                                <label class="text-white font-semibold mb-2 block">Additional Notes (Optional)</label>
                                <textarea id="pageant_notes" name="pageant_notes" placeholder="Enter any additional notes" class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white resize-none" rows="3"></textarea>
                            </div>
                        </div>

                        <!-- CORPORATE FORM -->
                        <div id="form_corporate" class="eventForm hidden bg-[#93BFC7] rounded-xl shadow p-6 mb-6">
                            <h3 class="text-xl font-bold text-white mb-4">Corporate Event Details</h3>
                            
                            <!-- Representative Name -->
                            <div class="mb-4">
                                <label class="text-white font-semibold mb-2 block">Company Representative *</label>
                                <div class="grid grid-cols-3 gap-2">
                                    <input type="text" id="corporate_rep_firstname" name="corporate_rep_firstname" placeholder="First Name " required class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                                    <input type="text" id="corporate_rep_middlename" name="corporate_rep_middlename" placeholder="Middle Name" class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                                    <input type="text" id="corporate_rep_lastname" name="corporate_rep_lastname" placeholder="Last Name " required class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="text-white font-semibold mb-2 block">Company Name *</label>
                                <input type="text" id="corporate_company" name="corporate_company" placeholder="Enter company name" required class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                            </div>
                            
                            <div class="mb-4">
                                <label class="text-white font-semibold mb-2 block">Event Title / Theme *</label>
                                <input type="text" id="corporate_title" name="corporate_title" placeholder="Enter event title or theme" required class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                            </div>
                            
                            <div class="mb-4">
                                <label class="text-white font-semibold mb-2 block">Number of Attendees *</label>
                                <input type="number" id="corporate_attendees" name="corporate_attendees" placeholder="Enter number of attendees" required min="1" class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                            </div>
                            
                            <div class="mb-4">
                                <label class="text-white font-semibold mb-2 block">Contact Number *</label>
                                <input type="tel" id="corporate_contact" name="corporate_contact" placeholder="Enter contact number (10-11 digits)" required pattern="[0-9]{10,11}" class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                            </div>
                            
                            <div class="mb-4">
                                <label class="text-white font-semibold mb-2 block">Event Requirements *</label>
                                <textarea id="corporate_requirements" name="corporate_requirements" placeholder="Enter event requirements (Lights, Sound, Stage, etc.)" required
                                    class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white resize-none" rows="3"></textarea>
                            </div>
                        </div>

                    </form>
                </div>

                <!-- RIGHT SIDE - Event Information -->
                <div class="flex-1">
                    <div id="event_info_container" class="bg-[#93BFC7] rounded-xl shadow p-6 hidden">
                        <h3 class="text-xl font-bold text-white mb-4">Event Information</h3>

                        <div class="mb-4">
                            <label class="text-white font-semibold mb-2 block">Event Date *</label>
                            <input type="date" id="date" name="date" required
                                class="w-full mb-2 px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                            <div id="dateAvailability" class="text-sm text-white opacity-90 hidden"></div>
                            <p class="text-xs text-white opacity-75 mt-1">Maximum 2 bookings per day. Calendar shows current availability.</p>
                        </div>

                        <div class="mb-4">
                            <label class="text-white font-semibold mb-2 block">Event Time *</label>
                            <input type="time" id="time" name="time" required
                                class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white">
                            <p class="text-white text-xs mt-1 opacity-75">Event time</p>
                        </div>

                        <div class="mb-4">
                            <label class="text-white font-semibold mb-2 block">Event Venue / Location *</label>
                            <input type="text" id="location" name="location" list="gensan_locations" placeholder="Enter venue address (Gensan City only)" required
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

                        <div class="mb-6">
                            <label class="text-white font-semibold mb-2 block">Special Request (Optional)</label>
                            <textarea id="request" name="request" placeholder="Enter any special requests..."
                                class="w-full px-4 py-3 rounded-lg focus:ring-2 focus:ring-white resize-none" rows="4"></textarea>
                        </div>
                        <div>       
                            <button type="submit" id="submitBookingBtn" form="bookingForm"
                                class="w-full bg-white text-[#93BFC7] font-bold py-3 rounded-lg hover:bg-gray-100 
                                transition-all shadow">
                                Submit Booking Request
                            </button>
                    </div>
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
                // Toggle collapsible sections
                function toggleSection(sectionId) {
                    const section = document.getElementById(sectionId);
                    const icon = document.getElementById(sectionId.replace('Section', 'Icon'));
                    
                    if (section.classList.contains('hidden')) {
                        section.classList.remove('hidden');
                        if (icon) {
                            icon.classList.remove('fa-chevron-down');
                            icon.classList.add('fa-chevron-up');
                        }
                    } else {
                        section.classList.add('hidden');
                        if (icon) {
                            icon.classList.remove('fa-chevron-up');
                            icon.classList.add('fa-chevron-down');
                        }
                    }
                }


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
                        }
                    });
                }

                const eventSelect = document.getElementById('event_type');
                const forms = document.querySelectorAll('.eventForm');
                const eventInfo = document.getElementById('event_info_container');
                const submitButton = document.getElementById('submitBookingBtn');

                eventSelect.addEventListener('change', () => {
                    forms.forEach(form => form.classList.add('hidden'));

                    if (eventSelect.value) {
                        document.getElementById(`form_${eventSelect.value}`).classList.remove('hidden');
                        eventInfo.classList.remove('hidden');
                    } else {
                        eventInfo.classList.add('hidden');
                    }
                });



                // Form validation on submit
                const bookingForm = document.getElementById('bookingForm');
                
                // Function to handle form submission
                function handleFormSubmit(e) {
                    if (e) {
                        e.preventDefault();
                    }
                        
                        const missingFields = [];
                        
                        // Check if event type is selected (check hidden field first for locked state)
                        const hiddenEventTypeVal = document.getElementById('event_type_hidden');
                        const currentEventType = (hiddenEventTypeVal && hiddenEventTypeVal.value) ? hiddenEventTypeVal.value : eventSelect.value;
                        
                        if (!currentEventType) {
                            showErrorModal('Please select an event type to continue.', ['Event Type']);
                            eventSelect.focus();
                            return false;
                        }

                        // Get the active form based on event type
                        const activeForm = document.getElementById(`form_${currentEventType}`);
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
                        // Get event type from hidden field (if locked) or from select
                        const hiddenEventType = document.getElementById('event_type_hidden');
                        const eventType = (hiddenEventType && hiddenEventType.value) ? hiddenEventType.value : eventSelect.value;
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
                            // Debut doesn't have theme field anymore
                            themeValue = '';
                        } else if (eventType === 'pageant') {
                            // Pageant doesn't have theme field anymore
                            themeValue = '';
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
                        } else if (eventType === 'christening') {
                            guestCount = parseInt(document.getElementById('christening_guests').value) || 0;
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
                                    
                                    // Only reset event select if it's not locked
                                    const hiddenEvt = document.getElementById('event_type_hidden');
                                    if (!hiddenEvt || !hiddenEvt.value) {
                                        eventSelect.value = '';
                                        forms.forEach(form => form.classList.add('hidden'));
                                        eventInfo.classList.add('hidden');
                                    }
                                    
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

                        // Prefill event type from query parameter (e.g., ?event_type=wedding)
                        (function prefillFromQuery() {
                            try {
                                const params = new URLSearchParams(window.location.search);
                                const pre = params.get('event_type');
                                if (!pre) return;
                                const normalized = pre.toLowerCase();
                                // If the option exists in the select, set it and trigger change
                                const optionExists = Array.from(eventSelect.options).some(o => o.value === normalized);
                                if (optionExists) {
                                    eventSelect.value = normalized;
                                    eventSelect.dispatchEvent(new Event('change'));
                                    
                                    // Disable the select so user can't change it
                                    eventSelect.disabled = true;
                                    eventSelect.removeAttribute('required'); // Remove required since it's disabled
                                    
                                    // Set hidden input value for form submission
                                    const hiddenInput = document.getElementById('event_type_hidden');
                                    if (hiddenInput) {
                                        hiddenInput.value = normalized;
                                    }
                                    
                                    // Show lock indicator
                                    const lockIndicator = document.getElementById('event_lock_indicator');
                                    if (lockIndicator) {
                                        lockIndicator.classList.remove('hidden');
                                    }
                                    
                                    // Update hint text
                                    const hintText = document.getElementById('event_type_hint');
                                    if (hintText) {
                                        hintText.innerHTML = '<i class="fas fa-info-circle mr-1"></i> Event type is locked based on your selection from dashboard';
                                    }
                                    
                                    // Focus first required input of the active form
                                    setTimeout(() => {
                                        const activeForm = document.getElementById(`form_${normalized}`);
                                        if (activeForm) {
                                            const firstInput = activeForm.querySelector('[required]');
                                            if (firstInput) firstInput.focus();
                                        }
                                    }, 250);
                                }
                            } catch (e) {
                                // ignore
                            }
                        })();
            </script>

        </div>
    </div>

</body>
</html>
