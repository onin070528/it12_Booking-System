<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - RJ's Event Styling</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-lg shadow-lg">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Payment Checkout
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Complete your booking payment
                </p>
            </div>

            <!-- Booking Summary -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-3" style="color: #93BFC7;">Booking Summary</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Event Type:</span>
                        <span class="font-medium">{{ $booking->event_type }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Event Date:</span>
                        <span class="font-medium">{{ $booking->event_date->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Location:</span>
                        <span class="font-medium">{{ $booking->location }}</span>
                    </div>
                    <div class="flex justify-between pt-2 border-t border-gray-300">
                        <span class="text-lg font-semibold" style="color: #93BFC7;">Total Amount:</span>
                        <span class="text-lg font-bold">₱{{ number_format($booking->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Payment Method Selection -->
            <form action="{{ route('payment.process', $booking) }}" method="POST" class="mt-8 space-y-6">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Select Payment Method
                    </label>
                    <div class="space-y-3">
                        <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                            <input type="radio" name="payment_method" value="gcash" class="mr-3" required>
                            <i class="fas fa-mobile-alt text-2xl mr-3" style="color: #93BFC7;"></i>
                            <div>
                                <div class="font-semibold">GCash</div>
                                <div class="text-xs text-gray-500">Pay via GCash</div>
                            </div>
                        </label>

                        <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                            <input type="radio" name="payment_method" value="grab_pay" class="mr-3" required>
                            <i class="fas fa-wallet text-2xl mr-3" style="color: #93BFC7;"></i>
                            <div>
                                <div class="font-semibold">GrabPay</div>
                                <div class="text-xs text-gray-500">Pay via GrabPay</div>
                            </div>
                        </label>

                        <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                            <input type="radio" name="payment_method" value="paymaya" class="mr-3" required>
                            <i class="fas fa-credit-card text-2xl mr-3" style="color: #93BFC7;"></i>
                            <div>
                                <div class="font-semibold">PayMaya</div>
                                <div class="text-xs text-gray-500">Pay via PayMaya</div>
                            </div>
                        </label>

                        <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                            <input type="radio" name="payment_method" value="card" class="mr-3" required>
                            <i class="fas fa-credit-card text-2xl mr-3" style="color: #93BFC7;"></i>
                            <div>
                                <div class="font-semibold">Credit/Debit Card</div>
                                <div class="text-xs text-gray-500">Visa, Mastercard, etc.</div>
                            </div>
                        </label>
                    </div>
                </div>

                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <ul class="list-disc list-inside text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="flex space-x-4">
                    <a href="{{ route('home') }}" class="flex-1 bg-gray-300 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-400 text-center font-medium transition">
                        Cancel
                    </a>
                    <button type="submit" class="flex-1 text-white py-2 px-4 rounded-lg font-medium transition" style="background-color: #93BFC7;" onmouseover="this.style.backgroundColor='#7eaab1'" onmouseout="this.style.backgroundColor='#93BFC7'">
                        Proceed to Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

