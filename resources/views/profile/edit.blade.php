<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - RJ's Event Styling</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="bg-gray-100" x-data="{ deleteModalOpen: @if($errors->userDeletion->isNotEmpty()) true @else false @endif }">
    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 min-h-screen bg-[#EEFBE8] py-6 flex flex-col shadow-lg">
            <div class="p-3 text-center">
                <img src="/img/logo.png" alt="RJ's Event Styling" class="logo-img mb-3">
            </div>

            <ul class="flex flex-col">
                <li>
                    <a class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 transition-colors" href="{{ route('dashboard') }}">
                        <i class="fas fa-th-large mr-2"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 transition-colors" href="#">
                        <i class="fas fa-home mr-2"></i> Home
                    </a>
                </li>
                <li>
                    <a class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 transition-colors" href="#">
                        <i class="fas fa-calendar mr-2"></i> Calendar of Events
                    </a>
                </li>
                <li>
                    <a class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 transition-colors" href="#">
                        <i class="fas fa-book mr-2"></i> Create Booking
                    </a>
                </li>
                <li>
                    <a class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 transition-colors" href="#">
                        <i class="fas fa-history mr-2"></i> Payment History
                    </a>
                </li>
                <li>
                    <a class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 transition-colors" href="#">
                        <i class="fas fa-bell mr-2"></i> Notification
                    </a>
                </li>
                <li>
                    <form action="{{ route('logout') }}" method="POST" id="logout-form">
                        @csrf
                        <a class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 transition-colors cursor-pointer" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </a>
                    </form>
                </li>
            </ul>

            <div class="mt-auto p-3">
                <a class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 transition-colors" href="#">
                    <i class="fas fa-question-circle mr-2"></i> Help
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-grow">
            <!-- Header -->
            <nav class="bg-white shadow-sm px-4">
                <div class="w-full flex justify-between items-center">
                    <h1 class="text-xl font-bold mb-0" style="font-family: 'Poppins', sans-serif;">
                        <span style="color: #5394D0;">RJ's</span>
                        <span style="color: #923432;">Event</span>
                        <span style="color: #C49A36;">Styling</span>
                    </h1>
                    <div class="flex items-center gap-2">
                        <span class="font-bold" style="color:#923432">{{ Auth::user()->name }}</span>
                        <a href="{{ route('profile') }}" class="no-underline">
                            <i class="fas fa-user-circle text-2xl" style="color: #923432;"></i>
                        </a>
                    </div>
                </div>
            </nav>

            <!-- Profile Content -->
            <div class="w-full p-4">
                <div class="w-full">
                    <div class="w-full">
                        <h2 class="mb-4 font-bold text-[#923432]">Profile Settings</h2>
                    </div>
                </div>

                <div class="w-full">
                    <div class="w-full lg:w-2/3">
                        <!-- Update Profile Information -->
                        <section class="bg-white rounded-lg p-8 mb-6 shadow-sm">
                            <header class="mb-6">
                                <h2 class="text-xl font-medium text-gray-900 mb-2">Profile Information</h2>
                                <p class="text-sm text-gray-500">Update your account's profile information, email address, and phone number.</p>
                            </header>

                            <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                                @csrf
                            </form>

                            <form method="post" action="{{ route('profile.update') }}" class="mt-4">
                                @csrf
                                @method('patch')

                                <div class="mb-3">
                                    <label for="name" class="block font-medium text-gray-700 mb-2">
                                        <i class="fas fa-user mr-2 text-gray-500"></i>Full Name
                                    </label>
                                    <input type="text" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:border-[#5394D0] focus:outline-none focus:ring-2 focus:ring-[#5394D0]/20" id="name" name="name"
                                        value="{{ old('name', $user->name) }}" required autofocus
                                        placeholder="Enter your full name">
                                    @error('name')
                                    <div class="bg-red-50 border border-red-200 text-red-700 rounded-md p-3 text-sm mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="block font-medium text-gray-700 mb-2">
                                        <i class="fas fa-envelope mr-2 text-gray-500"></i>Email Address
                                    </label>
                                    <input type="email" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:border-[#5394D0] focus:outline-none focus:ring-2 focus:ring-[#5394D0]/20" id="email" name="email"
                                        value="{{ old('email', $user->email) }}" required
                                        placeholder="your.email@example.com">
                                    @error('email')
                                    <div class="bg-red-50 border border-red-200 text-red-700 rounded-md p-3 text-sm mt-2">{{ $message }}</div>
                                    @enderror

                                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                    <div class="bg-yellow-50 border border-yellow-200 p-3 rounded-md text-sm mt-2">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>
                                        Your email address is unverified.
                                        <button type="button" form="send-verification" class="underline p-0">
                                            Click here to re-send the verification email.
                                        </button>
                                    </div>

                                    @if (session('status') === 'verification-link-sent')
                                    <p class="mt-2 text-green-600 font-medium">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        A new verification link has been sent to your email address.
                                    </p>
                                    @endif
                                    @endif
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="block font-medium text-gray-700 mb-2">
                                        <i class="fas fa-phone mr-2 text-gray-500"></i>Phone Number
                                    </label>
                                    <div class="flex">
                                        <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                            <i class="fas fa-mobile-alt"></i>
                                        </span>
                                        <input type="tel" class="flex-1 rounded-r-md border border-gray-300 px-3 py-2 focus:border-[#5394D0] focus:outline-none focus:ring-2 focus:ring-[#5394D0]/20" id="phone" name="phone"
                                            value="{{ old('phone', $user->phone ?? '') }}"
                                            placeholder="09XX XXX XXXX"
                                            pattern="[0-9]{11}"
                                            title="Please enter a valid 11-digit phone number">
                                    </div>
                                    <small class="text-gray-500">Format: 09XX XXX XXXX (11 digits)</small>
                                    @error('phone')
                                    <div class="bg-red-50 border border-red-200 text-red-700 rounded-md p-3 text-sm mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="flex items-center gap-3">
                                    <button type="submit" class="bg-[#5394D0] border-none px-4 py-2 rounded-md text-white font-medium transition-colors hover:bg-[#4280b8]">
                                        <i class="fas fa-save mr-2"></i>Save Changes
                                    </button>

                                    @if (session('status') === 'profile-updated')
                                    <p x-data="{ show: true }" x-show="show" x-transition
                                        x-init="setTimeout(() => show = false, 2000)"
                                        class="text-green-600 mb-0">
                                        <i class="fas fa-check-circle mr-1"></i>Saved successfully!
                                    </p>
                                    @endif
                                </div>
                            </form>
                        </section>

                        <!-- Update Password -->
                        <section class="bg-white rounded-lg p-8 mb-6 shadow-sm">
                            <header class="mb-6">
                                <h2 class="text-xl font-medium text-gray-900 mb-2">Update Password</h2>
                                <p class="text-sm text-gray-500">Ensure your account is using a long, random password to stay secure.</p>
                            </header>

                            <form method="post" action="{{ route('password.update') }}" class="mt-4">
                                @csrf
                                @method('put')

                                <div class="mb-3">
                                    <label for="update_password_current_password" class="block font-medium text-gray-700 mb-2">
                                        <i class="fas fa-lock mr-2 text-gray-500"></i>Current Password
                                    </label>
                                    <input type="password" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:border-[#5394D0] focus:outline-none focus:ring-2 focus:ring-[#5394D0]/20" id="update_password_current_password"
                                        name="current_password" autocomplete="current-password"
                                        placeholder="Enter your current password">
                                    @error('current_password', 'updatePassword')
                                    <div class="bg-red-50 border border-red-200 text-red-700 rounded-md p-3 text-sm mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="update_password_password" class="block font-medium text-gray-700 mb-2">
                                        <i class="fas fa-key mr-2 text-gray-500"></i>New Password
                                    </label>
                                    <input type="password" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:border-[#5394D0] focus:outline-none focus:ring-2 focus:ring-[#5394D0]/20" id="update_password_password"
                                        name="password" autocomplete="new-password"
                                        placeholder="Enter a new password (min. 8 characters)">
                                    <small class="text-gray-500">Password must be at least 8 characters long</small>
                                    @error('password', 'updatePassword')
                                    <div class="bg-red-50 border border-red-200 text-red-700 rounded-md p-3 text-sm mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="update_password_password_confirmation" class="block font-medium text-gray-700 mb-2">
                                        <i class="fas fa-check-double mr-2 text-gray-500"></i>Confirm New Password
                                    </label>
                                    <input type="password" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:border-[#5394D0] focus:outline-none focus:ring-2 focus:ring-[#5394D0]/20" id="update_password_password_confirmation"
                                        name="password_confirmation" autocomplete="new-password"
                                        placeholder="Re-enter your new password">
                                    @error('password_confirmation', 'updatePassword')
                                    <div class="bg-red-50 border border-red-200 text-red-700 rounded-md p-3 text-sm mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="flex items-center gap-3">
                                    <button type="submit" class="bg-[#5394D0] border-none px-4 py-2 rounded-md text-white font-medium transition-colors hover:bg-[#4280b8]">
                                        <i class="fas fa-save mr-2"></i>Update Password
                                    </button>

                                    @if (session('status') === 'password-updated')
                                    <p x-data="{ show: true }" x-show="show" x-transition
                                        x-init="setTimeout(() => show = false, 2000)"
                                        class="text-green-600 mb-0">
                                        <i class="fas fa-check-circle mr-1"></i>Password updated successfully!
                                    </p>
                                    @endif
                                </div>
                            </form>
                        </section>

                        <!-- Delete Account -->
                        <section class="bg-white rounded-lg p-8 mb-6 shadow-sm">
                            <header class="mb-6">
                                <h2 class="text-xl font-medium text-gray-900 mb-2">Delete Account</h2>
                                <p class="text-sm text-gray-500">Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.</p>
                            </header>

                            <button type="button" class="bg-red-600 border-none px-4 py-2 rounded-md text-white font-medium transition-colors hover:bg-red-700 mt-3" 
                                    @click="deleteModalOpen = true">
                                <i class="fas fa-trash-alt mr-2"></i>Delete Account
                            </button>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-show="deleteModalOpen" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" 
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div x-show="deleteModalOpen" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                 @click="deleteModalOpen = false"></div>

            <!-- Modal panel -->
            <div x-show="deleteModalOpen"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                                    <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                                    Are you sure you want to delete your account?
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.</p>
                                </div>
                                <div class="mt-4">
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                                    <input type="password" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:border-red-600 focus:outline-none focus:ring-2 focus:ring-red-600/20" id="password" name="password" placeholder="Enter your password to confirm">
                                    @error('password', 'userDeletion')
                                    <div class="bg-red-50 border border-red-200 text-red-700 rounded-md p-3 text-sm mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            <i class="fas fa-trash-alt mr-2"></i>Delete Account
                        </button>
                        <button type="button" @click="deleteModalOpen = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            <i class="fas fa-times mr-2"></i>Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>