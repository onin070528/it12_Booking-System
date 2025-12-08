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
       @include('layouts.sidebar')

        <!-- Main Content -->
        <div class="flex-1 min-h-screen px-6 py-6 ml-64">

            <!-- Header -->
            @php $headerSubtitle = "Payment History"; @endphp
            @include('layouts.header')


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
                    <th class="px-6 py-4 text-left">Payment Date</th>
                    <th class="px-6 py-4 text-left">Booking</th>
                    <th class="px-6 py-4 text-left">Payment Method</th>
                    <th class="px-6 py-4 text-left">Amount</th>
                    <th class="px-6 py-4 text-left">Status</th>
                </tr>
            </thead>

           <tbody>
                @forelse($payments as $payment)
                <tr class="bg-white font-medium text-[#93BFC7] hover:bg-gray-200 border-b border-gray-300">
                    <td class="px-6 py-4">{{ $payment->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4">
                        {{ $payment->booking->event_type ?? 'N/A' }}
                        <span class="text-xs text-gray-500 block">{{ $payment->booking->event_date->format('M d, Y') ?? '' }}</span>
                    </td>
                    <td class="px-6 py-4 capitalize">{{ $payment->payment_method ?? 'N/A' }}</td>
                    <td class="px-6 py-4">₱{{ number_format($payment->amount, 2) }}</td>
                    <td class="px-6 py-4">
                        @if($payment->status === 'paid')
                            <span class="px-4 py-1 rounded-full bg-[#D4F6DF] text-green-900 font-medium inline-block">
                                Paid
                            </span>
                        @elseif($payment->status === 'pending')
                            <span class="px-4 py-1 rounded-full bg-[#FDFCB1] text-yellow-900 font-medium inline-block">
                                Pending
                            </span>
                        @elseif($payment->status === 'failed')
                            <span class="px-4 py-1 rounded-full bg-[#FDB1B1] text-red-900 font-medium inline-block">
                                Failed
                            </span>
                        @elseif($payment->status === 'cancelled')
                            <span class="px-4 py-1 rounded-full bg-[#FDB1B1] text-red-900 font-medium inline-block">
                                Cancelled
                            </span>
                        @else
                            <span class="px-4 py-1 rounded-full bg-gray-200 text-gray-700 font-medium inline-block">
                                {{ ucfirst($payment->status) }}
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                        No payment history found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($payments->hasPages())
    <div class="bg-white px-6 py-4 border-t border-gray-200">
        {{ $payments->links() }}
    </div>
    @endif

</div>


        </div>
    </div>

</body>
</html>