<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success - RJ's Event Styling</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center py-12 px-4">
        <div class="max-w-md w-full bg-white p-8 rounded-lg shadow-lg text-center">
            <div class="mb-6">
                <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-100">
                    <i class="fas fa-check text-4xl text-green-600"></i>
                </div>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Payment Successful!</h2>
            <p class="text-gray-600 mb-8">
                Your payment has been processed successfully. You will receive a confirmation email shortly.
            </p>
            <div class="space-y-4">
                <a href="{{ route('payments.index') }}" class="block w-full text-white py-3 px-4 rounded-lg font-medium transition" style="background-color: #93BFC7;" onmouseover="this.style.backgroundColor='#7eaab1'" onmouseout="this.style.backgroundColor='#93BFC7'">
                    View Payment History
                </a>
                <a href="{{ route('home') }}" class="block w-full bg-gray-200 text-gray-700 py-3 px-4 rounded-lg font-medium hover:bg-gray-300 transition">
                    Back to Home
                </a>
            </div>
        </div>
    </div>
</body>
</html>

