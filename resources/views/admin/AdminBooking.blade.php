<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - RJ's Event Styling</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="font-sans bg-[#ECF4E8]">

    <div class="flex">
        <!-- Admin Sidebar -->
        @include('admin.AdminLayouts.AdminSidebar')

        <!-- Main Content -->
        <div class="flex-1 min-h-screen px-6 py-6 ml-64">
            
        <!-- Header -->
            <div class="bg-white shadow-md rounded-xl px-6 py-4 flex justify-between items-center mb-8">
                <div class="flex items-center space-x-2">
                    <div>
                    <h2 class="text-3xl font-bold" style="color: #93BFC7;">
                        <i class="fas fa-user-shield mr-2"></i>Welcome, {{ Auth::user()->name }}
                    </h2>
                    <p class="text-1xl font-semibold" style="color: #93BFC7;">
                        Admin Control Panel
                    </p>
                </div>
                </div>

                <div class="flex items-center space-x-6 text-[#93BFC7]">
                    <i class="fas fa-search text-xl cursor-pointer"></i>
                    <i class="fas fa-bell text-xl cursor-pointer"></i>
                </div>

            </div>

        
             <!-- Booking Form Content -->
            <div class="flex gap-6">
                <!-- Event Details Panel -->
                <div class="flex-1 bg-[#93BFC7] rounded-xl shadow-[0_8px_20px_rgba(0,0,0,0.20)] p-6" style="border-radius: 0.75rem;">
                    <h3 class="text-2xl font-bold text-white mb-6">Event Details</h3>
                    
                    <form id="bookingForm">
                        <!-- Event Type -->
                        <div class="mb-4">
                            <label class="block text-white font-semibold mb-2">
                                <i class="fas fa-calendar-alt mr-2"></i>Choose an event type
                            </label>
                            <select name="event_type" id="event_type" class="w-full px-4 py-3 rounded-lg border-0 focus:ring-2 focus:ring-white focus:outline-none transition-all shadow-[0_2px_8px_rgba(0,0,0,0.15)]">
                                <option value="">Select event type</option>
                                <option value="wedding">Wedding</option>
                                <option value="birthday">Birthday</option>
                                <option value="pageant">Pageant</option>
                                <option value="christening">Christening</option>
                                <option value="corporate">Corporate</option>
                            </select>
                        </div>

                        <!-- Date -->
                        <div class="mb-4">
                            <label class="block text-white font-semibold mb-2">
                                <i class="fas fa-calendar mr-2"></i>Date
                            </label>
                            <input type="date" name="date" id="date" class="w-full px-4 py-3 rounded-lg border-0 focus:ring-2 focus:ring-white focus:outline-none transition-all shadow-[0_2px_8px_rgba(0,0,0,0.15)]" placeholder="Select a date">
                        </div>

                        <!-- Location -->
                        <div class="mb-4">
                            <label class="block text-white font-semibold mb-2">
                                <i class="fas fa-map-marker-alt mr-2"></i>Location
                            </label>
                            <input type="text" name="location" id="location" class="w-full px-4 py-3 rounded-lg border-0 focus:ring-2 focus:ring-white focus:outline-none transition-all shadow-[0_2px_8px_rgba(0,0,0,0.15)]" placeholder="Enter location">
                        </div>

                        <!-- Preferences/Theme -->
                        <div class="mb-4">
                            <label class="block text-white font-semibold mb-2">
                                <i class="fas fa-palette mr-2"></i>Preferences
                            </label>
                            <input type="text" name="theme" id="theme" class="w-full px-4 py-3 rounded-lg border-0 focus:ring-2 focus:ring-white focus:outline-none transition-all shadow-[0_2px_8px_rgba(0,0,0,0.15)]" placeholder="Theme">
                        </div>

                        <!-- Request -->
                        <div class="mb-4">
                            <label class="block text-white font-semibold mb-2">
                                <i class="fas fa-comment-alt mr-2"></i>Request
                            </label>
                            <textarea name="request" id="request" rows="4" class="w-full px-4 py-3 rounded-lg border-0 focus:ring-2 focus:ring-white focus:outline-none transition-all shadow-[0_2px_8px_rgba(0,0,0,0.15)] resize-none" placeholder="Add any special Request"></textarea>
                        </div>
                    </form>
                </div>

                <!-- Preview Booking Panel -->
                <div class="flex-1 bg-[#93BFC7] rounded-xl shadow-[0_8px_20px_rgba(0,0,0,0.20)] p-6 flex flex-col" style="border-radius: 0.75rem;">
                    <h3 class="text-2xl font-bold text-white mb-6">Preview Booking</h3>
                    
                    <div class="flex-1 space-y-4 mb-6">
                        <div class="bg-white/20 backdrop-blur-sm rounded-lg p-4">
                            <p class="text-white text-sm font-semibold mb-1">Event Type:</p>
                            <p class="text-white font-medium" id="preview_event_type">-</p>
                        </div>

                        <div class="bg-white/20 backdrop-blur-sm rounded-lg p-4">
                            <p class="text-white text-sm font-semibold mb-1">Date:</p>
                            <p class="text-white font-medium" id="preview_date">-</p>
                        </div>

                        <div class="bg-white/20 backdrop-blur-sm rounded-lg p-4">
                            <p class="text-white text-sm font-semibold mb-1">Location:</p>
                            <p class="text-white font-medium" id="preview_location">-</p>
                        </div>

                        <div class="bg-white/20 backdrop-blur-sm rounded-lg p-4">
                            <p class="text-white text-sm font-semibold mb-1">Theme:</p>
                            <p class="text-white font-medium" id="preview_theme">-</p>
                        </div>

                        <div class="bg-white/20 backdrop-blur-sm rounded-lg p-4">
                            <p class="text-white text-sm font-semibold mb-1">Request:</p>
                            <p class="text-white font-medium" id="preview_request">-</p>
                        </div>
                    </div>

                    <button type="submit" form="bookingForm" class="w-full bg-[#EEFBE8] text-[#93BFC7] font-bold py-3 rounded-lg hover:bg-[#d4e8c8] transition-all duration-200 shadow-[0_4px_12px_rgba(0,0,0,0.20)] hover:shadow-[0_6px_16px_rgba(0,0,0,0.30)] hover:-translate-y-0.5">
                        Submit booking
                    </button>
                </div>
            </div>

            <script>
                // Update preview in real-time
                document.getElementById('event_type').addEventListener('change', function() {
                    document.getElementById('preview_event_type').textContent = this.value ? this.value.charAt(0).toUpperCase() + this.value.slice(1) : '-';
                });

                document.getElementById('date').addEventListener('change', function() {
                    if (this.value) {
                        const date = new Date(this.value);
                        const options = { year: 'numeric', month: 'short', day: 'numeric' };
                        document.getElementById('preview_date').textContent = date.toLocaleDateString('en-US', options);
                    } else {
                        document.getElementById('preview_date').textContent = '-';
                    }
                });

                document.getElementById('location').addEventListener('input', function() {
                    document.getElementById('preview_location').textContent = this.value || '-';
                });

                document.getElementById('theme').addEventListener('input', function() {
                    document.getElementById('preview_theme').textContent = this.value || '-';
                });

                document.getElementById('request').addEventListener('input', function() {
                    document.getElementById('preview_request').textContent = this.value || '-';
                });
            </script>


</body>
</html>

