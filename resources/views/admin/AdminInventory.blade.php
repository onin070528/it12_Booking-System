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


                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

    <!-- Total Products -->
    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium mb-1">Total Products</p>
                <h3 class="text-3xl font-bold" style="color: #93BFC7;">100</h3>
            </div>
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-boxes text-blue-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Low Stock Items -->
    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium mb-1">Low Stock Items</p>
                <h3 class="text-3xl font-bold" style="color: #93BFC7;">1</h3>
            </div>
            <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-yellow-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- New Items -->
    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium mb-1">New Items</p>
                <h3 class="text-3xl font-bold" style="color: #93BFC7;">0</h3>
            </div>
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-plus-circle text-green-600 text-2xl"></i>
            </div>
        </div>
    </div>

</div>



            <div class="overflow-x-auto rounded-xl shadow-lg">
                    
                <div class="bg-[#93BFC7] text-white px-6 py-4 rounded-t-xl shadow flex items-center text-3xl font-bold">
                    <i class="fas fa-boxes mr-3"></i>
                    <h3>Inventory</h3>

                    <div class="justify-end ml-auto bg-white text-[#93BFC7] px-4 py-1 rounded-md shadow font-semibold hover:bg-gray-100 cursor-pointer text-sm">
                        <button>+ Add New Item</button>
                    </div>
                </div>

                <table class="w-full">
                    <thead>
                        <tr class="bg-white text-[#93BFC7] font-semibold hover:bg-gray-200 border-b border-gray-300">
                            <th class="px-6 py-4 text-left">Item Name</th>
                            <th class="px-6 py-4 text-left">Category</th>
                            <th class="px-6 py-4 text-left">Stock</th>
                            <th class="px-6 py-4 text-left">Status</th>
                            <th class="px-6 py-4 text-left">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        <!-- Sample Row -->
                        <tr class="bg-white text-[#93BFC7] font-medium hover:bg-gray-200 border-b border-gray-300">
                            <td class="px-6 py-4 ">Flower Stand</td>
                            <td class="px-6 py-4 ">Event Props</td>
                            <td class="px-6 py-4 font-semibold">15</td>
                            <td class="px-6 py-4">
                                <span class="px-4 py-1 rounded-full bg-[#D4F6DF] text-green-900 font-medium inline-block">
                                    In Stock
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <button class="text-[#93BFC7] hover:text-[#7eaab1] mr-3"><i class="fas fa-eye"></i></button>
                                <button class="text-yellow-500 hover:text-yellow-600 mr-3"><i class="fas fa-edit"></i></button>
                                <button class="text-red-500 hover:text-red-600"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>

                    </tbody>

                </table>
            </div>
        </div>
    </div>

</body>
</html>
