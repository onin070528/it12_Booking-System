<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @keyframes backgroundFade {
            0%, 100% { opacity: 0.2; }
            50% { opacity: 0.25; }
        }
        .animated-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url("{{ asset('img/login_background.jpg') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.2;
            animation: backgroundFade 6s ease-in-out infinite;
            z-index: -1;
        }
    </style>
</head>
<body class="bg-gray-300 relative">
    <div class="animated-bg"></div>
    <div class="min-h-screen flex items-center justify-center py-4 px-4">
        <div class="bg-white rounded-lg shadow-md w-full max-w-md p-8">
            
            <!-- Header -->
            <div class="text-center mb-6">
                <div class="inline-block bg-[#93BFC7] rounded-full p-4 mb-4">
                    <i class="fas fa-lock text-white text-3xl"></i>
                </div>
                <h2 class="text-3xl text-[#93BFC7] font-bold">Forgot Password?</h2>
                <p class="text-gray-600 text-sm mt-2">No problem! Enter your email and we'll send you a reset link.</p>
            </div>

            @if (session('status'))
                <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span>{{ session('status') }}</span>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <div class="relative flex items-center">
                        <div class="absolute left-0 bg-[#93BFC7] w-12 h-12 flex items-center justify-center rounded-l">
                            <i class="fas fa-envelope text-white text-xl"></i>
                        </div>
                        <input type="email" name="email" value="{{ old('email') }}" 
                               placeholder="Enter your email address"
                               class="w-full pl-14 pr-4 h-12 bg-gray-100 rounded border border-gray-300 focus:border-[#93BFC7] focus:outline-none" 
                               required autofocus>
                    </div>
                </div>

                <button type="submit" 
                        class="w-full bg-[#93BFC7] text-white rounded h-12 hover:bg-[#7aa8b0] transition-colors font-semibold flex items-center justify-center">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Send Reset Link
                </button>

                <div class="mt-6 text-center">
                    <a href="{{ route('login') }}" class="text-sm text-[#93BFC7] hover:text-[#7aa8b0] flex items-center justify-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Login
                    </a>
                </div>
            </form>

            <!-- Info Box -->
            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-500 mt-1 mr-2"></i>
                    <div class="text-sm text-blue-700">
                        <strong>How it works:</strong>
                        <ol class="list-decimal list-inside mt-2 space-y-1">
                            <li>Enter your email address</li>
                            <li>Check your inbox for reset link</li>
                            <li>Click the link to reset password</li>
                            <li>Enter your new password</li>
                        </ol>
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>
</html>
