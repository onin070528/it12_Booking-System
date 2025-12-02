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
                    <th class="px-6 py-4 text-center font-semibold">Number</th>
                    <th class="px-6 py-4 text-center font-semibold">Event</th>
                    <th class="px-6 py-4 text-center font-semibold">Date</th>
                    <th class="px-6 py-4 text-center font-semibold">Location</th>
                    <th class="px-6 py-4 text-center font-semibold">Status</th>
                    <th class="px-6 py-4 text-center font-semibold">Action</th>
                </tr>
            </thead>

            <tbody>

    <!-- Row 1 -->
    <tr class="bg-[#FFFFF] text-[#93BFC7] hover:bg-[#7eaab1] transition-all duration-200">
        <td class="px-6 py-4 text-center">1</td>
        <td class="px-6 py-4 text-center font-medium">Wedding</td>
        <td class="px-6 py-4 text-center">Nov 25, 2025</td>
        <td class="px-6 py-4 text-center">Gensan park</td>
        <td class="px-6 py-4 text-center">
            <span class="bg-green-200 text-green-800 px-4 py-1 rounded-full  font-medium">
                Approved
            </span>
        </td>
        <td class="px-6 py-4 text-center">
            <button class="bg-white text-[#93BFC7] px-3 py-1 rounded-full hover:bg-gray-200 transition shadow">
                <i class="fas fa-eye"></i>
            </button>
        </td>
    </tr>

    <!-- Row 2 -->
    <tr class="bg-[#FFFFF] text-[#93BFC7] hover:bg-[#7eaab1] transition-all duration-200">
        <td class="px-6 py-4 text-center">2</td>
        <td class="px-6 py-4 text-center font-medium">Birthday</td>
        <td class="px-6 py-4 text-center">Dec 1, 2025</td>
        <td class="px-6 py-4 text-center">City Hall</td>
        <td class="px-6 py-4 text-center">
            <span class="px-4 py-1 rounded-full bg-[#FDFCB1] text-yellow-900 font-medium inline-block">
                Pending
            </span>
        </td>
        <td class="px-6 py-4 text-center">
            <button class="bg-white text-[#93BFC7] px-3 py-1 rounded-full hover:bg-gray-200 transition shadow">
                <i class="fas fa-eye"></i>
            </button>
        </td>
    </tr>

</tbody>

        </table>
    </div>

</div>


            <!-- Pagination -->
            <div class="flex justify-center items-center gap-2 mt-6">
                <button class="px-3 py-2 bg-[#93BFC7] text-white rounded-md hover:bg-[#7eaab1] transition-all duration-200 shadow-[0_4px_12px_rgba(0,0,0,0.20)] hover:shadow-[0_6px_16px_rgba(0,0,0,0.30)] hover:-translate-y-0.5">
                    <i class="fas fa-angle-double-left"></i>
                </button>
                <button class="px-3 py-2 bg-[#93BFC7] text-white rounded-md hover:bg-[#7eaab1] transition-all duration-200 shadow-[0_4px_12px_rgba(0,0,0,0.20)] hover:shadow-[0_6px_16px_rgba(0,0,0,0.30)] hover:-translate-y-0.5">
                    <i class="fas fa-angle-left"></i>
                </button>
                <button class="px-4 py-2 bg-[#5394D0] text-white rounded-md font-semibold shadow-[0_4px_12px_rgba(83,148,208,0.40)]">1</button>
                <button class="px-4 py-2 bg-[#93BFC7] text-white rounded-md hover:bg-[#7eaab1] transition-all duration-200 shadow-[0_4px_12px_rgba(0,0,0,0.20)] hover:shadow-[0_6px_16px_rgba(0,0,0,0.30)] hover:-translate-y-0.5">2</button>
                <button class="px-4 py-2 bg-[#93BFC7] text-white rounded-md hover:bg-[#7eaab1] transition-all duration-200 shadow-[0_4px_12px_rgba(0,0,0,0.20)] hover:shadow-[0_6px_16px_rgba(0,0,0,0.30)] hover:-translate-y-0.5">3</button>
                <button class="px-4 py-2 bg-[#93BFC7] text-white rounded-md hover:bg-[#7eaab1] transition-all duration-200 shadow-[0_4px_12px_rgba(0,0,0,0.20)] hover:shadow-[0_6px_16px_rgba(0,0,0,0.30)] hover:-translate-y-0.5">4</button>
                <button class="px-4 py-2 bg-[#93BFC7] text-white rounded-md hover:bg-[#7eaab1] transition-all duration-200 shadow-[0_4px_12px_rgba(0,0,0,0.20)] hover:shadow-[0_6px_16px_rgba(0,0,0,0.30)] hover:-translate-y-0.5">5</button>
                <button class="px-3 py-2 bg-[#93BFC7] text-white rounded-md hover:bg-[#7eaab1] transition-all duration-200 shadow-[0_4px_12px_rgba(0,0,0,0.20)] hover:shadow-[0_6px_16px_rgba(0,0,0,0.30)] hover:-translate-y-0.5">
                    <i class="fas fa-angle-right"></i>
                </button>
                <button class="px-3 py-2 bg-[#93BFC7] text-white rounded-md hover:bg-[#7eaab1] transition-all duration-200 shadow-[0_4px_12px_rgba(0,0,0,0.20)] hover:shadow-[0_6px_16px_rgba(0,0,0,0.30)] hover:-translate-y-0.5">
                    <i class="fas fa-angle-double-right"></i>
                </button>
            </div>

        </div>
    </div>
</body>
</html>
