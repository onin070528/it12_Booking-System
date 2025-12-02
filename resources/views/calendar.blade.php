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

</head>

<body class="font-sans bg-[#ECF4E8]">

    <div class="flex">
        <!-- Sidebar -->
        @include('layouts.sidebar')

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
                            Welcome to RJ's Event and Styling!
                        </p>
                    </div>
                </div>

                <div class="flex items-center space-x-6 text-[#93BFC7]">
                    <i class="fas fa-search text-xl cursor-pointer"></i>
                    <i class="fas fa-bell text-xl cursor-pointer"></i>
                </div>
            </div>

            <!-- Calendar Content -->
            <div class="bg-white rounded-xl shadow-md p-6">
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
                selectable: true,
                select: function(info) {
                    let title = prompt('Enter Event Title:');
                    if (title) {
                        fetch("{{ route('events') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                title: title,
                                start: info.startStr,
                                end: info.endStr
                            })
                        })
                        .then(response => response.json())
                        .then(event => calendar.addEvent(event));
                    }
                },
                editable: true,
                eventDrop: function(info) {
                    // Optional: Add AJAX to update event in backend on drag
                }
            });
            calendar.render();
        });
    </script>

</body>
</html>
