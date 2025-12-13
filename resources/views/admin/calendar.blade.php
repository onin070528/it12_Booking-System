<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Calendar of Events - RJ's Event Styling</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- FullCalendar CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    
    <style>
        /* Style for booking count badges on calendar days */
        .booking-count-badge {
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            font-size: 11px;
            line-height: 1.2;
            margin-left: 4px;
            vertical-align: middle;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        }
        
        .fc-daygrid-day-number {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }
        
        .fc-daygrid-day-number > a {
            flex: 1;
        }
    </style>

</head>

<body class="font-sans bg-[#ECF4E8]">

    <div class="flex">
        <!-- Admin Sidebar -->
        @include('admin.AdminLayouts.AdminSidebar')

        <!-- Main Content -->
        <div class="flex-1 min-h-screen px-6 py-6 ml-64">
            
        <!-- Header -->
            @include('admin.layouts.header')

            <!-- Calendar Content -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <!-- Calendar Legend -->
                <div class="mb-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Booking Availability Legend:</h3>
                    <div class="flex flex-wrap gap-4 text-xs">
                        <div class="flex items-center gap-2">
                            <span class="inline-block w-4 h-4 bg-green-500 rounded-full"></span>
                            <span>Available (0/2 bookings)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-block w-4 h-4 bg-orange-500 rounded-full"></span>
                            <span>1 Booking (1/2 - 1 slot remaining)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-block w-4 h-4 bg-red-500 rounded-full"></span>
                            <span>Fully Booked (2/2 - No slots available)</span>
                        </div>
                    </div>
                    <p class="text-xs text-gray-600 mt-2">Calendar auto-updates every 30 seconds. Maximum 2 confirmed bookings per day.</p>
                </div>
                <div id="calendar"></div>
            </div>
        </div>
    </div>

    <!-- Toast Notification Container -->
    <div id="toastContainer" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <!-- Create Event Modal -->
    <div id="createEventModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800">Create New Event</h3>
                <button id="closeEventModal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="createEventForm">
                <div class="mb-4">
                    <label for="eventTitle" class="block text-sm font-medium text-gray-700 mb-2">Event Title *</label>
                    <input type="text" id="eventTitle" name="title" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#93BFC7] focus:border-transparent"
                        placeholder="Enter event title">
                </div>
                
                <div class="mb-4">
                    <label for="eventDate" class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                    <input type="text" id="eventDate" readonly
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600">
                </div>
                
                <div class="flex justify-end gap-3">
                    <button type="button" id="cancelEventBtn" 
                        class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition">
                        Cancel
                    </button>
                    <button type="submit" id="saveEventBtn"
                        class="px-4 py-2 bg-[#93BFC7] text-white rounded-lg hover:bg-[#7eaab1] transition">
                        <i class="fas fa-save mr-2"></i>Save Event
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
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

            // Modal elements
            const createEventModal = document.getElementById('createEventModal');
            const createEventForm = document.getElementById('createEventForm');
            const eventTitleInput = document.getElementById('eventTitle');
            const eventDateInput = document.getElementById('eventDate');
            const closeEventModal = document.getElementById('closeEventModal');
            const cancelEventBtn = document.getElementById('cancelEventBtn');
            const saveEventBtn = document.getElementById('saveEventBtn');
            
            let selectedDateInfo = null;

            // Modal functions
            function openEventModal(dateInfo) {
                selectedDateInfo = dateInfo;
                const date = new Date(dateInfo.startStr);
                const formattedDate = date.toLocaleDateString('en-US', { 
                    weekday: 'long', 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric' 
                });
                eventDateInput.value = formattedDate;
                eventTitleInput.value = '';
                createEventModal.classList.remove('hidden');
                eventTitleInput.focus();
            }

            function closeEventModalFunc() {
                createEventModal.classList.add('hidden');
                selectedDateInfo = null;
                createEventForm.reset();
            }

            // Event listeners for modal
            closeEventModal.addEventListener('click', closeEventModalFunc);
            cancelEventBtn.addEventListener('click', closeEventModalFunc);
            
            // Close modal when clicking outside
            createEventModal.addEventListener('click', function(e) {
                if (e.target === createEventModal) {
                    closeEventModalFunc();
                }
            });

            // Form submission
            createEventForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const title = eventTitleInput.value.trim();
                if (!title) {
                    showToast('Please enter an event title', 'error');
                    return;
                }

                // Disable submit button
                saveEventBtn.disabled = true;
                saveEventBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';

                fetch("{{ route('admin.events.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        title: title,
                        start: selectedDateInfo.startStr,
                        end: selectedDateInfo.endStr || selectedDateInfo.startStr
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw new Error(err.message || 'Failed to create event');
                        });
                    }
                    return response.json();
                })
                .then(event => {
                    // Add the event to the calendar
                    calendar.addEvent({
                        id: event.id,
                        title: event.title,
                        start: event.start,
                        end: event.end,
                        color: event.color,
                        extendedProps: event.extendedProps || { isBooking: false }
                    });
                    calendar.unselect(); // Clear the selection
                    
                    // Show success toast
                    showToast('Event created successfully!', 'success');
                    
                    // Close modal
                    closeEventModalFunc();
                    
                    // Refresh events to ensure everything is in sync
                    calendar.refetchEvents();
                })
                .catch(error => {
                    console.error('Error creating event:', error);
                    showToast(error.message || 'Failed to create event. Please try again.', 'error');
                })
                .finally(() => {
                    // Re-enable submit button
                    saveEventBtn.disabled = false;
                    saveEventBtn.innerHTML = '<i class="fas fa-save mr-2"></i>Save Event';
                });
            });

            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                events: "{{ route('admin.events') }}", // fetch all events from admin route
                selectable: true,
                select: function(info) {
                    openEventModal(info);
                },
                editable: true,
                eventDrop: function(info) {
                    // Optional: Add AJAX to update event in backend on drag
                },
                dayCellDidMount: function(info) {
                    // Store date on the cell for later reference
                    info.el.setAttribute('data-booking-date', info.dateStr);
                    
                    // Initial badge update (will be updated again when events load)
                    setTimeout(() => {
                        updateBadgeForCell(info.el, info.dateStr);
                    }, 100);
                },
                eventDidMount: function(info) {
                    // Add tooltip with booking details for booking events
                    if (info.event.extendedProps && info.event.extendedProps.bookingCount) {
                        const bookings = info.event.extendedProps.bookings || [];
                        const tooltip = bookings.map(b => 
                            `${b.event_type} - ${b.customer} (${b.location})`
                        ).join('\n');
                        
                        info.el.setAttribute('title', tooltip);
                    }
                    
                    // Regular events (admin-created) should display normally with their titles
                    if (info.event.extendedProps && info.event.extendedProps.isBooking === false) {
                        // Regular event - display normally
                        info.el.style.display = 'block';
                    }
                },
                // Auto-refresh events every 30 seconds
                eventSources: [{
                    url: "{{ route('admin.events') }}",
                    method: 'GET',
                    failure: function() {
                        console.error('Failed to fetch events');
                    }
                }]
            });
            
            // Function to update badge for a specific day cell
            function updateBadgeForCell(cell, dateStr) {
                const dayNumberEl = cell.querySelector('.fc-daygrid-day-number');
                if (!dayNumberEl) return;
                
                // Find events for this date
                const eventsForDate = calendar.getEvents().filter(event => {
                    if (!event.startStr) return false;
                    const eventStart = event.startStr.split('T')[0];
                    return eventStart === dateStr;
                });
                
                // Check if any event has booking count info
                let bookingCount = 0;
                let isFull = false;
                
                eventsForDate.forEach(event => {
                    if (event.extendedProps && event.extendedProps.bookingCount !== undefined) {
                        bookingCount = event.extendedProps.bookingCount;
                        isFull = event.extendedProps.isFull;
                    }
                });
                
                // Remove existing badge if any
                const existingBadge = dayNumberEl.querySelector('.booking-count-badge');
                if (existingBadge) {
                    existingBadge.remove();
                }
                
                // Always show badge if there are bookings, or fetch from API as fallback
                if (bookingCount > 0) {
                    addBadgeToCell(dayNumberEl, bookingCount, isFull);
                } else {
                    // Fallback: fetch booking count from API if no events found
                    fetch(`{{ route('events.booking-count') }}?date=${dateStr}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.count > 0) {
                            addBadgeToCell(dayNumberEl, data.count, data.isFull);
                        }
                    })
                    .catch(error => {
                        // Silently fail if API call fails
                    });
                }
            }
            
            // Helper function to add badge to day cell
            function addBadgeToCell(dayNumberEl, bookingCount, isFull) {
                // Remove existing badge if any
                const existingBadge = dayNumberEl.querySelector('.booking-count-badge');
                if (existingBadge) {
                    existingBadge.remove();
                }
                
                // Add booking count badge
                const badge = document.createElement('span');
                badge.className = 'booking-count-badge';
                badge.textContent = `${bookingCount}/2`;
                badge.style.cssText = 'display: inline-flex !important; align-items: center; justify-content: center; min-width: 40px; font-size: 11px; font-weight: bold; line-height: 1.2; margin-left: 4px; padding: 2px 8px; vertical-align: middle; border-radius: 9999px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);';
                
                if (isFull) {
                    badge.style.backgroundColor = '#dc2626';
                    badge.style.color = '#ffffff';
                } else {
                    badge.style.backgroundColor = '#f59e0b';
                    badge.style.color = '#ffffff';
                }
                
                dayNumberEl.appendChild(badge);
            }
            
            // Function to update all day cell badges after events are loaded
            function updateDayCellBadges() {
                const dayCells = document.querySelectorAll('.fc-daygrid-day[data-booking-date]');
                dayCells.forEach(cell => {
                    const dateStr = cell.getAttribute('data-booking-date');
                    if (dateStr) {
                        updateBadgeForCell(cell, dateStr);
                    }
                });
            }
            
            calendar.render();
            
            // Update badges after events are loaded
            calendar.on('eventsSet', function() {
                setTimeout(updateDayCellBadges, 300);
            });
            
            // Also update when view changes
            calendar.on('datesSet', function() {
                setTimeout(updateDayCellBadges, 300);
            });
            
            // Update badges when calendar is rendered
            calendar.on('viewDidMount', function() {
                setTimeout(updateDayCellBadges, 400);
            });
            
            // Initial update after a short delay to ensure calendar is fully rendered
            setTimeout(function() {
                updateDayCellBadges();
            }, 500);
            
            // Auto-refresh calendar every 30 seconds
            setInterval(function() {
                calendar.refetchEvents();
            }, 30000);
            
            // Also refresh when window gains focus
            window.addEventListener('focus', function() {
                calendar.refetchEvents();
            });
        });
    </script>

</body>
</html>

