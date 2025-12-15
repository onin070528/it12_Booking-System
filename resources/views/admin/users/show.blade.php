<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - User Details</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="font-inter bg-[#ECF4E8]">

<div class="flex min-h-screen">

    <!-- Sidebar -->
    @include('admin.AdminLayouts.AdminSidebar')

    <!-- Main Content -->
    <div class="flex-1 ml-64 px-8 py-6">

        @include('admin.layouts.header')

        <!-- Page Wrapper -->
        <div class="max-w-5xl mx-auto mt-8">

            <!-- Card -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">

                <!-- Header -->
                <div class="bg-[#93BFC7] px-8 py-5 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-white/30 flex items-center justify-center">
                        <i class="fas fa-user text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-white">User Details</h2>
                        <p class="text-white/80 text-sm">Account information</p>
                    </div>
                </div>

                <!-- Body -->
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">

                    <div>
                        <p class="text-xs uppercase tracking-wider text-gray-400 mb-1">Name</p>
                        <p class="text-gray-800 font-semibold text-base">
                            {{ $user->name }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs uppercase tracking-wider text-gray-400 mb-1">Email</p>
                        <p class="text-gray-800 font-semibold text-base break-all">
                            {{ $user->email }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs uppercase tracking-wider text-gray-400 mb-1">Role</p>
                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold
                            bg-blue-100 text-blue-700">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>

                    <div>
                        <p class="text-xs uppercase tracking-wider text-gray-400 mb-1">Joined</p>
                        <p class="text-gray-800 font-semibold text-base">
                            {{ $user->created_at->format('M d, Y • h:i A') }}
                        </p>
                    </div>

                </div>

                <!-- Footer -->
                <div class="bg-gray-50 px-8 py-4 flex justify-end border-t">
                    <a href="{{ url()->previous() }}"
                       class="px-5 py-2 rounded-lg text-sm font-medium
                              bg-gray-200 hover:bg-gray-300 transition">
                        Back
                    </a>
                </div>

            </div>

        </div>

    </div>
</div>

</body>
</html>
