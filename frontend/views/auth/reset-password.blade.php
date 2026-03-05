<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
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
        .password-strength {
            height: 4px;
            background-color: #e0e0e0;
            border-radius: 2px;
            overflow: hidden;
            margin-top: 8px;
        }
        .password-strength-bar {
            height: 100%;
            transition: width 0.3s, background-color 0.3s;
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
                    <i class="fas fa-key text-white text-3xl"></i>
                </div>
                <h2 class="text-3xl text-[#93BFC7] font-bold">Reset Password</h2>
                <p class="text-gray-600 text-sm mt-2">Create a new secure password for your account</p>
            </div>

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.store') }}" id="resetForm">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <div class="relative flex items-center">
                        <div class="absolute left-0 bg-[#93BFC7] w-12 h-12 flex items-center justify-center rounded-l">
                            <i class="fas fa-envelope text-white text-xl"></i>
                        </div>
                        <input type="email" name="email" value="{{ old('email', $request->email) }}" 
                               class="w-full pl-14 pr-4 h-12 bg-gray-100 rounded border border-gray-300 focus:border-[#93BFC7] focus:outline-none" 
                               required autofocus readonly>
                    </div>
                </div>

                <!-- New Password -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                    <div class="relative flex items-center">
                        <div class="absolute left-0 bg-[#93BFC7] w-12 h-12 flex items-center justify-center rounded-l">
                            <i class="fas fa-lock text-white text-xl"></i>
                        </div>
                        <input type="password" name="password" id="password" placeholder="Enter new password"
                               class="w-full pl-14 pr-12 h-12 bg-gray-100 rounded border border-gray-300 focus:border-[#93BFC7] focus:outline-none" 
                               required>
                        <button type="button" onclick="togglePasswordVisibility('password', 'password-icon')" 
                                class="absolute right-0 mr-3 w-8 h-12 flex items-center justify-center text-gray-500 hover:text-gray-700 focus:outline-none">
                            <i class="fas fa-eye" id="password-icon"></i>
                        </button>
                    </div>
                    <!-- Password Strength Indicator -->
                    <div class="password-strength">
                        <div class="password-strength-bar" id="strength-bar"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2" id="strength-text">Password strength: <span id="strength-level">Not set</span></p>
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                    <div class="relative flex items-center">
                        <div class="absolute left-0 bg-[#93BFC7] w-12 h-12 flex items-center justify-center rounded-l">
                            <i class="fas fa-lock text-white text-xl"></i>
                        </div>
                        <input type="password" name="password_confirmation" id="password_confirmation" 
                               placeholder="Confirm new password"
                               class="w-full pl-14 pr-12 h-12 bg-gray-100 rounded border border-gray-300 focus:border-[#93BFC7] focus:outline-none" 
                               required>
                        <button type="button" onclick="togglePasswordVisibility('password_confirmation', 'confirm-icon')" 
                                class="absolute right-0 mr-3 w-8 h-12 flex items-center justify-center text-gray-500 hover:text-gray-700 focus:outline-none">
                            <i class="fas fa-eye" id="confirm-icon"></i>
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-2" id="match-text"></p>
                </div>

                <button type="submit" 
                        class="w-full bg-[#93BFC7] text-white rounded h-12 hover:bg-[#7aa8b0] transition-colors font-semibold flex items-center justify-center">
                    <i class="fas fa-check mr-2"></i>
                    Reset Password
                </button>

                <div class="mt-6 text-center">
                    <a href="{{ route('login') }}" class="text-sm text-[#93BFC7] hover:text-[#7aa8b0] flex items-center justify-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Login
                    </a>
                </div>
            </form>

            <!-- Password Requirements -->
            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-500 mt-1 mr-2"></i>
                    <div class="text-sm text-blue-700">
                        <strong>Password Requirements:</strong>
                        <ul class="list-disc list-inside mt-2 space-y-1">
                            <li>At least 8 characters long</li>
                            <li>Mix of uppercase and lowercase letters</li>
                            <li>Include numbers and special characters</li>
                            <li>Avoid common words or patterns</li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePasswordVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Password strength checker
        const passwordInput = document.getElementById('password');
        const strengthBar = document.getElementById('strength-bar');
        const strengthLevel = document.getElementById('strength-level');

        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;

            if (password.length >= 8) strength++;
            if (password.length >= 12) strength++;
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
            if (/\d/.test(password)) strength++;
            if (/[^a-zA-Z\d]/.test(password)) strength++;

            const percentage = (strength / 5) * 100;
            strengthBar.style.width = percentage + '%';

            if (strength <= 2) {
                strengthBar.style.backgroundColor = '#ef4444';
                strengthLevel.textContent = 'Weak';
                strengthLevel.style.color = '#ef4444';
            } else if (strength <= 3) {
                strengthBar.style.backgroundColor = '#f59e0b';
                strengthLevel.textContent = 'Medium';
                strengthLevel.style.color = '#f59e0b';
            } else {
                strengthBar.style.backgroundColor = '#10b981';
                strengthLevel.textContent = 'Strong';
                strengthLevel.style.color = '#10b981';
            }
        });

        // Password match checker
        const confirmInput = document.getElementById('password_confirmation');
        const matchText = document.getElementById('match-text');

        function checkPasswordMatch() {
            if (confirmInput.value === '') {
                matchText.textContent = '';
                return;
            }

            if (passwordInput.value === confirmInput.value) {
                matchText.textContent = '✓ Passwords match';
                matchText.style.color = '#10b981';
            } else {
                matchText.textContent = '✗ Passwords do not match';
                matchText.style.color = '#ef4444';
            }
        }

        confirmInput.addEventListener('input', checkPasswordMatch);
        passwordInput.addEventListener('input', checkPasswordMatch);
    </script>
</body>
</html>
