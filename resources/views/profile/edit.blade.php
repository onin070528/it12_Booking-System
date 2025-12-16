<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Management - RJ's Event Styling</title>

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
            @php $headerSubtitle = "Manage your personal information and account settings"; @endphp
            @include('layouts.header')

            <!-- Success Message -->
            @if(session('status') === 'profile-updated')
            <div id="successToast" class="fixed top-4 right-4 z-50 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg flex items-center space-x-3 min-w-[300px] max-w-md transform transition-all duration-300">
                <i class="fas fa-check-circle text-xl"></i>
                <p class="flex-1 font-medium">Profile updated successfully!</p>
                <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            @endif

            @if(session('status') === 'password-updated')
            <div id="successToast" class="fixed top-4 right-4 z-50 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg flex items-center space-x-3 min-w-[300px] max-w-md transform transition-all duration-300">
                <i class="fas fa-check-circle text-xl"></i>
                <p class="flex-1 font-medium">Password updated successfully!</p>
                <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            @endif

            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    <!-- Left Column - Profile Card -->
                    <div class="lg:col-span-1">
                        <div class="bg-[#93BFC7] rounded-xl shadow-xl p-6 text-center">
                            <!-- Avatar -->
                            <div class="mb-6">
                                <div class="w-32 h-32 mx-auto bg-white rounded-full flex items-center justify-center shadow-lg">
                                    <i class="fas fa-user text-6xl text-[#93BFC7]"></i>
                                </div>
                            </div>
                            
                            <!-- User Info -->
                            <h2 class="text-2xl font-bold text-white mb-2">{{ $user->name }}</h2>
                            <p class="text-white/80 mb-1">
                                <i class="fas fa-envelope mr-2"></i>{{ $user->email }}
                            </p>
                            @if($user->phone)
                            <p class="text-white/80 mb-4">
                                <i class="fas fa-phone mr-2"></i>{{ $user->phone }}
                            </p>
                            @endif
                            
                            <!-- Account Info -->
                            <div class="mt-6 pt-6 border-t border-white/20">
                                <div class="flex items-center justify-center text-white/80 text-sm mb-2">
                                    <i class="fas fa-user-tag mr-2"></i>
                                    <span class="font-medium">{{ ucfirst($user->role) }}</span>
                                </div>
                                <div class="flex items-center justify-center text-white/80 text-sm">
                                    <i class="fas fa-calendar-alt mr-2"></i>
                                    <span>Member since {{ $user->created_at->format('M Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Forms -->
                    <div class="lg:col-span-2 space-y-6">
                        
                        <!-- Update Profile Information -->
                        <div class="bg-white rounded-xl shadow-xl p-6">
                            <div class="flex items-center mb-6">
                                <div class="bg-[#93BFC7] p-3 rounded-lg">
                                    <i class="fas fa-user-edit text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-2xl font-bold text-gray-800">Profile Information</h3>
                                    <p class="text-gray-600 text-sm">Update your account's profile information and email address</p>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('profile.update') }}">
                                @csrf
                                @method('PATCH')

                                <!-- Name -->
                                <div class="mb-4">
                                    <label for="name" class="block text-gray-700 font-semibold mb-2">
                                        <i class="fas fa-user mr-2 text-[#93BFC7]"></i>Full Name
                                    </label>
                                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                                        class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-[#93BFC7] focus:ring-2 focus:ring-[#93BFC7]/20 transition-all">
                                    @error('name')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="mb-4">
                                    <label for="email" class="block text-gray-700 font-semibold mb-2">
                                        <i class="fas fa-envelope mr-2 text-[#93BFC7]"></i>Email Address
                                    </label>
                                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                                        class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-[#93BFC7] focus:ring-2 focus:ring-[#93BFC7]/20 transition-all">
                                    @error('email')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div class="mb-6">
                                    <label for="phone" class="block text-gray-700 font-semibold mb-2">
                                        <i class="fas fa-phone mr-2 text-[#93BFC7]"></i>Phone Number
                                    </label>
                                    <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                                        class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-[#93BFC7] focus:ring-2 focus:ring-[#93BFC7]/20 transition-all">
                                    @error('phone')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                    <p class="text-gray-500 text-xs mt-1">Optional - for contact purposes</p>
                                </div>

                                <button type="submit" 
                                    class="w-full bg-[#93BFC7] text-white font-bold py-3 rounded-lg hover:bg-[#7aa8b0] transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                    <i class="fas fa-save mr-2"></i>Save Changes
                                </button>
                            </form>
                        </div>

                        <!-- Update Password -->
                        <div class="bg-white rounded-xl shadow-xl p-6">
                            <div class="flex items-center mb-6">
                                <div class="bg-[#93BFC7] p-3 rounded-lg">
                                    <i class="fas fa-lock text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-2xl font-bold text-gray-800">Update Password</h3>
                                    <p class="text-gray-600 text-sm">Ensure your account is using a strong password</p>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('password.update') }}">
                                @csrf
                                @method('PUT')

                                <!-- Current Password -->
                                <div class="mb-4">
                                    <label for="current_password" class="block text-gray-700 font-semibold mb-2">
                                        <i class="fas fa-key mr-2 text-[#93BFC7]"></i>Current Password
                                    </label>
                                    <div class="relative">
                                        <input type="password" id="current_password" name="current_password" required
                                            class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-[#93BFC7] focus:ring-2 focus:ring-[#93BFC7]/20 transition-all pr-12">
                                        <button type="button" onclick="togglePassword('current_password')" 
                                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-[#93BFC7]">
                                            <i class="fas fa-eye" id="current_password_icon"></i>
                                        </button>
                                    </div>
                                    @error('current_password')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- New Password -->
                                <div class="mb-4">
                                    <label for="password" class="block text-gray-700 font-semibold mb-2">
                                        <i class="fas fa-lock mr-2 text-[#93BFC7]"></i>New Password
                                    </label>
                                    <div class="relative">
                                        <input type="password" id="password" name="password" required
                                            class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-[#93BFC7] focus:ring-2 focus:ring-[#93BFC7]/20 transition-all pr-12">
                                        <button type="button" onclick="togglePassword('password')" 
                                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-[#93BFC7]">
                                            <i class="fas fa-eye" id="password_icon"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                    
                                    <!-- Password Strength Indicator -->
                                    <div class="mt-2">
                                        <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                            <div id="password_strength_bar" class="h-full transition-all duration-300" style="width: 0%"></div>
                                        </div>
                                        <p id="password_strength_text" class="text-sm mt-1"></p>
                                    </div>
                                </div>

                                <!-- Confirm Password -->
                                <div class="mb-4">
                                    <label for="password_confirmation" class="block text-gray-700 font-semibold mb-2">
                                        <i class="fas fa-check-circle mr-2 text-[#93BFC7]"></i>Confirm New Password
                                    </label>
                                    <div class="relative">
                                        <input type="password" id="password_confirmation" name="password_confirmation" required
                                            class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-[#93BFC7] focus:ring-2 focus:ring-[#93BFC7]/20 transition-all pr-12">
                                        <button type="button" onclick="togglePassword('password_confirmation')" 
                                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-[#93BFC7]">
                                            <i class="fas fa-eye" id="password_confirmation_icon"></i>
                                        </button>
                                    </div>
                                    
                                    <!-- Password Match Indicator -->
                                    <div id="password_match" class="mt-2 text-sm"></div>
                                </div>

                                <!-- Password Requirements -->
                                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                                    <p class="text-gray-700 font-semibold mb-2 text-sm">Password Requirements:</p>
                                    <ul class="text-gray-600 text-xs space-y-1">
                                        <li><i class="fas fa-check text-green-500 mr-2"></i>At least 8 characters</li>
                                        <li><i class="fas fa-check text-green-500 mr-2"></i>Mix of uppercase and lowercase letters</li>
                                        <li><i class="fas fa-check text-green-500 mr-2"></i>Include numbers and special characters</li>
                                    </ul>
                                </div>

                                <button type="submit" 
                                    class="w-full bg-[#93BFC7] text-white font-bold py-3 rounded-lg hover:bg-[#7aa8b0] transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                    <i class="fas fa-lock mr-2"></i>Update Password
                                </button>
                            </form>
                        </div>

                        <!-- Delete Account 
                        <div class="bg-white rounded-xl shadow-xl p-6 border-2 border-red-200">
                            <div class="flex items-center mb-6">
                                <div class="bg-red-500 p-3 rounded-lg">
                                    <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-2xl font-bold text-gray-800">Delete Account</h3>
                                    <p class="text-gray-600 text-sm">Permanently delete your account and all data</p>
                                </div>
                            </div>

                            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                                <p class="text-red-700 text-sm">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    <strong>Warning:</strong> Once your account is deleted, all of your resources and data will be permanently deleted. This action cannot be undone.
                                </p>
                            </div>

                            <button type="button" onclick="openDeleteModal()" 
                                class="w-full bg-red-500 text-white font-bold py-3 rounded-lg hover:bg-red-600 transition-all shadow-lg hover:shadow-xl">
                                <i class="fas fa-trash-alt mr-2"></i>Delete Account
                            </button>
                        </div>  -->

                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Delete Account Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 transform transition-all">
            <div class="bg-red-500 rounded-t-xl px-6 py-4 flex items-center justify-between">
                <h3 class="text-xl font-bold text-white">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Confirm Account Deletion
                </h3>
                <button onclick="closeDeleteModal()" class="text-white hover:text-gray-200 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6">
                <p class="text-gray-700 mb-4">
                    Are you sure you want to delete your account? This action cannot be undone. All your bookings, payments, and data will be permanently deleted.
                </p>
                
                <form method="POST" action="{{ route('profile.destroy') }}" id="deleteAccountForm">
                    @csrf
                    @method('DELETE')
                    
                    <div class="mb-4">
                        <label for="delete_password" class="block text-gray-700 font-semibold mb-2">
                            Please enter your password to confirm:
                        </label>
                        <input type="password" id="delete_password" name="password" required
                            class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-red-500 focus:ring-2 focus:ring-red-500/20 transition-all">
                        @error('password', 'userDeletion')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-3">
                        <button type="button" onclick="closeDeleteModal()" 
                            class="flex-1 bg-gray-200 text-gray-700 font-bold py-3 rounded-lg hover:bg-gray-300 transition-all">
                            Cancel
                        </button>
                        <button type="submit" 
                            class="flex-1 bg-red-500 text-white font-bold py-3 rounded-lg hover:bg-red-600 transition-all">
                            Delete Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<script>
    // Auto-hide success toast after 5 seconds
    const successToast = document.getElementById('successToast');
    if (successToast) {
        setTimeout(() => {
            successToast.style.transition = 'opacity 0.3s';
            successToast.style.opacity = '0';
            setTimeout(() => successToast.remove(), 300);
        }, 5000);
    }

    // Toggle password visibility
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = document.getElementById(fieldId + '_icon');

        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // Password strength checker
    const passwordField = document.getElementById('password');
    const strengthBar = document.getElementById('password_strength_bar');
    const strengthText = document.getElementById('password_strength_text');

    if (passwordField) {
        passwordField.addEventListener('input', function () {
            const password = this.value;
            let strength = 0;

            if (password.length >= 8) strength++;
            if (password.length >= 12) strength++;
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
            if (/\d/.test(password)) strength++;
            if (/[^a-zA-Z0-9]/.test(password)) strength++;

            let color, text, width;

            if (strength <= 2) {
                color = 'bg-red-500';
                text = 'Weak';
                width = '33%';
            } else if (strength <= 4) {
                color = 'bg-orange-500';
                text = 'Medium';
                width = '66%';
            } else {
                color = 'bg-green-500';
                text = 'Strong';
                width = '100%';
            }

            strengthBar.className = `h-full transition-all duration-300 ${color}`;
            strengthBar.style.width = width;
            strengthText.textContent = password ? `Password Strength: ${text}` : '';
            strengthText.className = `text-sm mt-1 ${color.replace('bg-', 'text-')}`;
        });
    }

    // Password match checker
    const confirmField = document.getElementById('password_confirmation');
    const matchIndicator = document.getElementById('password_match');

    if (confirmField && passwordField) {
        function checkPasswordMatch() {
            if (!confirmField.value) {
                matchIndicator.innerHTML = '';
                return;
            }

            matchIndicator.innerHTML =
                passwordField.value === confirmField.value
                    ? '<i class="fas fa-check-circle text-green-500 mr-2"></i><span class="text-green-500">Passwords match</span>'
                    : '<i class="fas fa-times-circle text-red-500 mr-2"></i><span class="text-red-500">Passwords do not match</span>';
        }

        passwordField.addEventListener('input', checkPasswordMatch);
        confirmField.addEventListener('input', checkPasswordMatch);
    }

    // Delete modal functions
    function openDeleteModal() {
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }

    // Close modal when clicking outside
    var deleteModalEl = document.getElementById('deleteModal');
    if (deleteModalEl) {
        deleteModalEl.addEventListener('click', function(e) {
            if (e.target === this) closeDeleteModal();
        });
    }
</script>

@if($errors->userDeletion->isNotEmpty())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        openDeleteModal();
    });
</script>
@endif

</body>
</html>
