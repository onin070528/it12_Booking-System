<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-200">
    <div class="min-h-screen flex items-center justify-center">
         <div class="bg-white rounded-lg shadow-md w-[900px] h-[500px] flex overflow-hidden">

            <!-- Left side - Registration Form -->
            <div class="w-1/2 p-8">
                <h2 class="text-2xl font-bold text-center mb-0">Registration</h2>

                <form action="{{ route('register') }}" method="POST">
                    @csrf

                    @if ($errors->any())
                        <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                            <ul class="list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Name -->
                    <div class="mb-4">
                        <label class="block text-sm mb-1">Your name</label>
                        <input type="text" name="name"
                            class="w-full h-10 bg-gray-100 rounded px-3"
                            placeholder="Enter your name" required autofocus>
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label class="block text-sm mb-1">Email</label>
                        <div class="relative flex items-center">
                            <div class="absolute left-0 bg-[#5394D0] w-10 h-10 flex items-center justify-center rounded">
                                <i class="fas fa-envelope text-white text-xl"></i>
                            </div>
                            <input type="email" name="email"
                                class="w-full pl-12 h-10 bg-gray-100 rounded"
                                placeholder="Enter email here" required>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label class="block text-sm mb-1">Password</label>
                        <div class="relative flex items-center">
                            <div class="absolute left-0 bg-[#5394D0] w-10 h-10 flex items-center justify-center rounded">
                                <i class="fas fa-lock text-white text-xl"></i>
                            </div>
                            <input type="password" name="password"
                                class="w-full pl-12 h-10 bg-gray-100 rounded"
                                placeholder="••••••••••••" required>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Minimum 8 characters</p>
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-6">
                        <label class="block text-sm mb-1">Confirm Password</label>
                        <div class="relative flex items-center">
                            <div class="absolute left-0 bg-[#5394D0] w-10 h-10 flex items-center justify-center rounded">
                                <i class="fas fa-lock text-white text-xl"></i>
                            </div>
                            <input type="password" name="password_confirmation"
                                class="w-full pl-12 h-10 bg-gray-100 rounded"
                                placeholder="••••••••••••" required>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-[#5394D0] text-white rounded h-10 hover:bg-blue-600">
                        Register
                    </button>

                    <p class="mt-4 text-center text-sm text-gray-600">
                        Already have an account?
                        <a href="{{ route('login') }}" class="text-[#5394D0] hover:text-blue-700">Login</a>
                    </p>
                </form>
            </div>

            <!-- Right side - Full Logo, No spacing -->
            <div class="w-1/2 h-full">
                <img src="/img/rj_logo.jpg"
                     class="w-full h-full object-cover"
                     alt="RJ Logo">
            </div>

        </div>
    </div>

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</body>

</html>
