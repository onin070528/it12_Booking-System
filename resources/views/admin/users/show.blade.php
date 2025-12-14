<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - User Details</title>

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
            @include('admin.layouts.header')

            <div class="bg-white rounded-xl shadow-lg p-6 max-w-4xl mx-auto">
                    <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold">User Details</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Name</p>
                        <p class="font-medium">{{ $user->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-medium">{{ $user->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Role</p>
                        <p class="font-medium">{{ $user->role }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Joined</p>
                        <p class="font-medium">{{ $user->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    <!-- archive removed -->
    </script>

</body>
</html>
