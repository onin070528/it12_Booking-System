<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-300">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-md w-[800px] flex overflow-hidden">
            
            <!-- Left side -->
            <div class="w-1/2 p-8">
                <h2 class="text-2xl font-bold text-center mb-6">Login</h2>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    @if (session('status'))
                        <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                            <ul class="list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Email -->
                    <div class="mb-4">
                        <label class="block text-sm mb-1">Email ID</label>
                        <div class="relative flex items-center">
                            <div class="absolute left-0 bg-[#93BFC7] w-10 h-10 flex items-center justify-center rounded">
                                <i class="fas fa-user text-white text-xl"></i>
                            </div>
                            <input type="email" name="email" placeholder="Enter email here"
                                   class="w-full pl-12 h-10 bg-gray-100 rounded" required>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label class="block text-sm mb-1">Password</label>
                        <div class="relative flex items-center">
                            <div class="absolute left-0 bg-[#93BFC7] w-10 h-10 flex items-center justify-center rounded">
                                <i class="fas fa-lock text-white text-xl"></i>
                            </div>
                            <input type="password" name="password" placeholder="Password"
                                   class="w-full pl-12 h-10 bg-gray-100 rounded" required>
                        </div>
                    </div>

                    <div class="text-right mb-4">
                        <a href="{{ route('password.request') }}" class="text-sm text-[#93BFC7] hover:text-blue-700">
                            Forgot Password?
                        </a>
                    </div>

                    <button type="submit" class="w-full bg-[#93BFC7] text-white rounded h-10 hover:bg-blue-600">
                        Login
                    </button>

                    <p class="mt-4 text-center text-sm text-gray-600">
                        Don't have an account?
                        <a href="{{ route('register') }}" class="text-[#93BFC7] hover:text-blue-700">Sign up</a>
                    </p>
                </form>
            </div>

            <!-- Right side - Logo (NO PADDING) -->
            <div class="w-1/2 bg-white flex items-center justify-center p-0 !p-0">
                <img src="/img/rj_logo.jpg" alt="RJ Logo" class="w-full h-full object-contain">
            </div>

        </div>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</body>

</html>
