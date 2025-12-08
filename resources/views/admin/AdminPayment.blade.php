<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment History - RJ's Event Styling</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="font-sans bg-[#ECF4E8]">
    

    <div class="flex">

        <!-- Sidebar -->
       @include('admin.AdminLayouts.AdminSidebar')

        <!-- Main Content -->
        <div class="flex-1 min-h-screen px-6 py-6 ml-64">

            <!-- Header -->
            @include('admin.layouts.header')


<!-- container -->
<div class="overflow-x-auto rounded-xl shadow-lg">

    <!-- Header -->
    <div class="bg-[#93BFC7] text-white px-6 py-4 rounded-t-xl shadow flex items-center text-3xl font-bold">
        <i class="fas fa-credit-card mr-3"></i>
        <h3 class="text-3xl font-bold">
            Payment History
        </h3>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-white text-[#93BFC7] font-semibold hover:bg-gray-200 border-b border-gray-300">
                    <th class="px-6 py-4 text-left ">Payment Date</th>
                    <th class="px-6 py-4 text-left ">Payment Method</th>
                    <th class="px-6 py-4 text-left ">Amount</th>
                    <th class="px-6 py-4 text-left ">Status</th>
                </tr>
            </thead>

           <tbody>
    <!-- Row 1 – Paid -->
    <tr class="bg-white font-medium text-[#93BFC7] hover:bg-gray-200 border-b border-gray-300">
        <td class="px-6 py-4 ">07/28/2025</td>
        <td class="px-6 py-4 ">Stripe</td>
        <td class="px-6 py-4  ">10,000.00</td>
        <td class="px-6 py-4">
            <span class="px-4 py-1 rounded-full bg-[#D4F6DF] text-green-900 font-medium inline-block">
                Paid
            </span>
        </td>
    </tr>

    <!-- Row 2 – Pending/Partial -->
    <tr class="bg-white font-medium text-[#93BFC7] hover:bg-gray-200 border-b border-gray-300">
        <td class="px-6 py-4 ">08/08/2025</td>
        <td class="px-6 py-4 ">Cash</td>
        <td class="px-6 py-4 ">5,000.00</td>
        <td class="px-6 py-4">
            <span class="px-4 py-1 rounded-full bg-[#FDFCB1] text-yellow-900 font-medium inline-block">
                Partial
            </span>
        </td>
    </tr>

    <!-- Row 3 – Cancelled -->
    <tr class="bg-white font-medium text-[#93BFC7] hover:bg-gray-200 border-b border-gray-300">
        <td class="px-6 py-4">08/20/2025</td>
        <td class="px-6 py-4 ">Cash</td>
        <td class="px-6 py-4 ">67,000.00</td>
        <td class="px-6 py-4">
            <span class="px-4 py-1 rounded-full bg-[#FDB1B1] text-red-900 font-medium inline-block">
                Cancelled
            </span>
        </td>
    </tr>
</tbody>
        </table>
    </div>

</div>


        </div>
    </div>

</body>
</html>

