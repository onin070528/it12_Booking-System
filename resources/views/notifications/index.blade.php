<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - RJ's Event Styling</title>

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
        <div class="flex-1 min-h-screen px-6 py-10 ml-64">
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

            <!-- Notifications Panel -->
<div class="bg-[#93BFC7] rounded-xl shadow-lg p-6">
    <h2 class="text-3xl font-bold mb-4 text-white">Notifications</h2>

    <div class="space-y-4">

        <!-- Notification Item -->
        <div class="bg-[#F9FAFB] rounded-lg border border-gray-200 p-4 flex items-center gap-4 hover:bg-[#F1F5F9] transition">
            <div class="w-12 h-12 bg-[#93BFC7] rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-user text-white text-lg"></i>
            </div>
            <div class="flex-1">
                <p class="text-gray-800 font-semibold">Username</p>
                <p class="text-gray-500 text-sm">5 mins ago</p>
            </div>
        </div>

        <!-- Notification Item -->
        <div class="bg-[#F9FAFB] rounded-lg border border-gray-200 p-4 flex items-center gap-4 hover:bg-[#F1F5F9] transition">
            <div class="w-12 h-12 bg-[#93BFC7] rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-user text-white text-lg"></i>
            </div>
            <div class="flex-1">
                <p class="text-gray-800 font-semibold">Username</p>
                <p class="text-gray-500 text-sm">5 mins ago</p>
            </div>
        </div>

        <!-- Notification Item -->
        <div class="bg-[#F9FAFB] rounded-lg border border-gray-200 p-4 flex items-center gap-4 hover:bg-[#F1F5F9] transition">
            <div class="w-12 h-12 bg-[#93BFC7] rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-user text-white text-lg"></i>
            </div>
            <div class="flex-1">
                <p class="text-gray-800 font-semibold">Username</p>
                <p class="text-gray-500 text-sm">5 mins ago</p>
            </div>
        </div>

        <!-- Notification Item -->
        <div class="bg-[#F9FAFB] rounded-lg border border-gray-200 p-4 flex items-center gap-4 hover:bg-[#F1F5F9] transition">
            <div class="w-12 h-12 bg-[#93BFC7] rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-user text-white text-lg"></i>
            </div>
            <div class="flex-1">
                <p class="text-gray-800 font-semibold">Username</p>
                <p class="text-gray-500 text-sm">5 mins ago</p>
            </div>
        </div>

    </div>
</div>


        </div>
    </div>

</body>

</html>
