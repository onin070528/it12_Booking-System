<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar of Events - RJ's Event Styling</title>

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
        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Main Content -->
        <div class="flex-1 min-h-screen px-6 py-6 ml-64">
            
        <!-- Header -->
            @php $headerSubtitle = "Welcome to RJ's Event and Styling!"; @endphp
            @include('layouts.header')

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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                events: "{{ route('events') }}", // fetch events from Laravel route
                selectable: false, // Disable selection for users
                editable: false, // Disable editing for users
                dayCellDidMount: function(info) {
                    // Store date on the cell for later reference
                    info.el.setAttribute('data-booking-date', info.dateStr);
                    
                    // Initial badge update (will be updated again when events load)
                    setTimeout(() => {
                        updateBadgeForCell(info.el, info.dateStr);
                    }, 100);
                },
                eventDidMount: function(info) {
                    // Hide event titles - users only see availability badges
                    if (info.event.title === '' || info.event.display === 'none') {
                        info.el.style.display = 'none';
                    }
                    
                    // Add tooltip with booking details
                    if (info.event.extendedProps && info.event.extendedProps.bookingCount) {
                        const bookings = info.event.extendedProps.bookings || [];
                        const tooltip = bookings.map(b => 
                            `${b.event_type} - ${b.customer} (${b.location})`
                        ).join('\n');
                        
                        // Store tooltip for badge hover
                        const dateStr = info.event.startStr.split('T')[0];
                        const cell = document.querySelector(`[data-booking-date="${dateStr}"]`);
                        if (cell) {
                            cell.setAttribute('title', tooltip);
                        }
                    }
                },
                // Auto-refresh events every 30 seconds
                eventSources: [{
                    url: "{{ route('events') }}",
                    method: 'GET',
                    failure: function() {
                        console.error('Failed to fetch events');
                    }
                }]
            });
            
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
            
            // Function to update badge for a specific day cell
            function updateBadgeForCell(cell, dateStr) {
                const dayNumberEl = cell.querySelector('.fc-daygrid-day-number');
                if (!dayNumberEl) return;
                
                // First try to get from events
                let bookingCount = 0;
                let isFull = false;
                
                const eventsForDate = calendar.getEvents().filter(event => {
                    if (!event.startStr) return false;
                    const eventStart = event.startStr.split('T')[0];
                    return eventStart === dateStr;
                });
                
                eventsForDate.forEach(event => {
                    if (event.extendedProps && event.extendedProps.bookingCount !== undefined) {
                        bookingCount = event.extendedProps.bookingCount;
                        isFull = event.extendedProps.isFull;
                    }
                });
                
                // If we found booking count from events, use it
                if (bookingCount > 0) {
                    addBadgeToCell(dayNumberEl, bookingCount, isFull);
                    return;
                }
                
                // Otherwise, fetch from API
                fetch(`{{ route('events.booking-count') }}?date=${dateStr}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.count > 0) {
                        addBadgeToCell(dayNumberEl, data.count, data.isFull);
                    }
                })
                .catch(error => {
                    // Silently fail
                });
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
                setTimeout(updateDayCellBadges, 500);
            });
            
            // Also update when view changes
            calendar.on('datesSet', function() {
                setTimeout(updateDayCellBadges, 500);
            });
            
            // Update badges when calendar is rendered
            calendar.on('viewDidMount', function() {
                setTimeout(updateDayCellBadges, 600);
            });
            
            // Initial update after a short delay to ensure calendar is fully rendered
            setTimeout(function() {
                updateDayCellBadges();
            }, 1000);
            
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
