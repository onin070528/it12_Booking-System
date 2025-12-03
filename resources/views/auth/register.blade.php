<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-200">
    <div class="min-h-screen flex items-center justify-center py-8">
         <div class="bg-white rounded-lg shadow-md w-[900px] flex overflow-hidden">

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

                    <!-- First Name -->
                    <div class="mb-4">
                        <label class="block text-sm mb-1">First Name</label>
                        <input type="text" name="first_name"
                            class="w-full h-10 bg-gray-100 rounded px-3"
                            placeholder="Enter first name" required autofocus
                            value="{{ old('first_name') }}">
                    </div>

                    <!-- Middle Initial -->
                    <div class="mb-4">
                        <label class="block text-sm mb-1">Middle Initial (Optional)</label>
                        <input type="text" name="middle_initial" maxlength="1"
                            class="w-full h-10 bg-gray-100 rounded px-3"
                            placeholder="M"
                            value="{{ old('middle_initial') }}"
                            style="text-transform: uppercase;">
                    </div>

                    <!-- Last Name -->
                    <div class="mb-4">
                        <label class="block text-sm mb-1">Last Name</label>
                        <input type="text" name="last_name"
                            class="w-full h-10 bg-gray-100 rounded px-3"
                            placeholder="Enter last name" required
                            value="{{ old('last_name') }}">
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
                            <input type="password" name="password" id="register-password"
                                class="w-full pl-12 pr-12 h-10 bg-gray-100 rounded"
                                placeholder="••••••••••••" required>
                            <button type="button" onclick="togglePassword('register-password', 'register-password-toggle')" 
                                    class="absolute right-0 mr-3 text-gray-500 hover:text-gray-700 focus:outline-none"
                                    id="register-password-toggle">
                                <i class="fas fa-eye" id="register-password-icon"></i>
                            </button>
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
                            <input type="password" name="password_confirmation" id="register-password-confirmation"
                                class="w-full pl-12 pr-12 h-10 bg-gray-100 rounded"
                                placeholder="••••••••••••" required>
                            <button type="button" onclick="togglePassword('register-password-confirmation', 'register-password-confirmation-toggle')" 
                                    class="absolute right-0 mr-3 text-gray-500 hover:text-gray-700 focus:outline-none"
                                    id="register-password-confirmation-toggle">
                                <i class="fas fa-eye" id="register-password-confirmation-icon"></i>
                            </button>
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
    
    <script>
        function togglePassword(inputId, toggleId) {
            const passwordInput = document.getElementById(inputId);
            let iconId = inputId + '-icon';
            const passwordIcon = document.getElementById(iconId);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.classList.remove('fa-eye');
                passwordIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                passwordIcon.classList.remove('fa-eye-slash');
                passwordIcon.classList.add('fa-eye');
            }
        }

        // Auto-capitalize first letter of first name and last name
        function capitalizeFirstLetter(str) {
            return str.split(' ').map(word => {
                if (word.length === 0) return word;
                return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
            }).join(' ');
        }

        // Auto-uppercase and limit middle initial to 1 character
        document.addEventListener('DOMContentLoaded', function() {
            // First Name auto-capitalize
            const firstNameInput = document.querySelector('input[name="first_name"]');
            if (firstNameInput) {
                // Capitalize on blur (when user leaves the field)
                firstNameInput.addEventListener('blur', function(e) {
                    this.value = capitalizeFirstLetter(this.value);
                });
                // Also capitalize first letter as user types
                firstNameInput.addEventListener('input', function(e) {
                    if (this.value.length === 1) {
                        this.value = this.value.toUpperCase();
                    }
                });
            }

            // Last Name auto-capitalize
            const lastNameInput = document.querySelector('input[name="last_name"]');
            if (lastNameInput) {
                // Capitalize on blur (when user leaves the field)
                lastNameInput.addEventListener('blur', function(e) {
                    this.value = capitalizeFirstLetter(this.value);
                });
                // Also capitalize first letter as user types
                lastNameInput.addEventListener('input', function(e) {
                    if (this.value.length === 1) {
                        this.value = this.value.toUpperCase();
                    }
                });
            }

            // Middle Initial auto-uppercase and limit to 1 character
            const middleInitialInput = document.querySelector('input[name="middle_initial"]');
            if (middleInitialInput) {
                middleInitialInput.addEventListener('input', function(e) {
                    // Limit to 1 character and convert to uppercase
                    this.value = this.value.toUpperCase().slice(0, 1);
                });
            }
        });
    </script>
</body>

</html>
