<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - Users Management - RJ's Event Styling</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="font-sans bg-[#ECF4E8]">
    <div class="flex">
        @include('admin.AdminLayouts.AdminSidebar')
        <div class="flex-1 min-h-screen px-6 py-6 ml-64">
            
            <!-- Header -->
            <div class="bg-white shadow-md rounded-xl px-6 py-4 flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-bold" style="color: #93BFC7;">
                        <i class="fas fa-users-cog mr-2"></i>Users Management
                    </h2>
                    <p class="text-xl font-semibold" style="color: #93BFC7;">Manage user accounts and approvals</p>
                </div>
                <div class="flex items-center gap-4">
                    <!-- Stats Cards -->
                    <div class="flex gap-3">
                        <div class="bg-[#93BFC7] bg-opacity-10 border border-[#93BFC7] rounded-lg px-4 py-2 text-center">
                            <p class="text-2xl font-bold text-[#93BFC7]">{{ $activeUsers->count() }}</p>
                            <p class="text-xs text-gray-600 font-medium">Active</p>
                        </div>
                        @if($pendingUsers->count() > 0)
                        <div class="bg-orange-50 border border-orange-300 rounded-lg px-4 py-2 text-center animate-pulse">
                            <p class="text-2xl font-bold text-orange-600">{{ $pendingUsers->count() }}</p>
                            <p class="text-xs text-orange-600 font-medium">Pending</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Pending Approval Section -->
            @if($pendingUsers->count() > 0)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-orange-500 to-orange-400 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-white flex items-center gap-2">
                            <i class="fas fa-user-clock"></i>
                            Pending Approval
                            <span class="bg-white text-orange-600 text-sm font-bold px-3 py-1 rounded-full ml-2">
                                {{ $pendingUsers->count() }}
                            </span>
                        </h3>
                        <p class="text-orange-100 text-sm">
                            <i class="fas fa-info-circle mr-1"></i>
                            Users waiting for account approval
                        </p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-orange-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-orange-700 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-orange-700 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-orange-700 uppercase tracking-wider">Phone</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-orange-700 uppercase tracking-wider">Registered</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-orange-700 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-orange-100">
                            @foreach($pendingUsers as $user)
                            <tr class="hover:bg-orange-50 transition-all duration-200" id="pending-user-{{ $user->user_id }}">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user text-orange-500"></i>
                                        </div>
                                        <span class="font-semibold text-gray-800">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $user->phone ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $user->created_at->format('M d, Y g:i A') }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-2">
                                        <button data-action="approve" data-user-id="{{ $user->user_id }}"
                                            class="approve-btn bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex items-center gap-2 shadow-sm hover:shadow-md">
                                            <i class="fas fa-check-circle"></i> Approve
                                        </button>
                                        <button data-action="reject" data-user-id="{{ $user->user_id }}" data-user-name="{{ $user->name }}"
                                            class="reject-btn bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex items-center gap-2 shadow-sm hover:shadow-md">
                                            <i class="fas fa-times-circle"></i> Reject
                                        </button>
                                        <a href="{{ route('admin.users.show', ['user' => $user->user_id]) }}" 
                                            class="bg-[#93BFC7] hover:bg-[#7eaab1] text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex items-center gap-2 shadow-sm hover:shadow-md">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Active Users Section -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-[#93BFC7] to-[#7eaab1] px-6 py-4">
                    <h3 class="text-xl font-bold text-white flex items-center gap-2">
                        <i class="fas fa-user-check"></i>
                        Active Users
                        <span class="bg-white text-[#93BFC7] text-sm font-bold px-3 py-1 rounded-full ml-2">
                            {{ $activeUsers->count() }}
                        </span>
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-[#93BFC7] text-white">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Name</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Email</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Role</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Joined</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($activeUsers as $user)
                            <tr class="hover:bg-gray-50 transition-all duration-200">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-[#93BFC7] bg-opacity-20 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user text-[#93BFC7]"></i>
                                        </div>
                                        <span class="font-semibold text-gray-800">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-[#93BFC7] bg-opacity-20 text-[#7eaab1]' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-600">{{ $user->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('admin.users.show', ['user' => $user->user_id]) }}" 
                                        class="bg-[#93BFC7] hover:bg-[#7eaab1] text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 inline-flex items-center gap-2 shadow-sm hover:shadow-md">
                                        <i class="fas fa-eye"></i> View Details
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                            <i class="fas fa-users text-gray-400 text-2xl"></i>
                                        </div>
                                        <p class="text-gray-500 font-medium">No active users found</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Rejected Users Section -->
            @if($rejectedUsers->count() > 0)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
                <div class="bg-gradient-to-r from-red-500 to-red-400 px-6 py-4">
                    <h3 class="text-xl font-bold text-white flex items-center gap-2">
                        <i class="fas fa-user-times"></i>
                        Rejected Users
                        <span class="bg-white text-red-600 text-sm font-bold px-3 py-1 rounded-full ml-2">
                            {{ $rejectedUsers->count() }}
                        </span>
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-red-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-red-700 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-red-700 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-red-700 uppercase tracking-wider">Reason</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-red-700 uppercase tracking-wider">Rejected At</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-red-700 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-red-100">
                            @foreach($rejectedUsers as $user)
                            <tr class="hover:bg-red-50 transition-all duration-200" id="rejected-user-{{ $user->user_id }}">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user-slash text-red-500"></i>
                                        </div>
                                        <span class="font-semibold text-gray-800">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-600 max-w-xs truncate block" title="{{ $user->rejection_reason ?? 'No reason provided' }}">
                                        {{ Str::limit($user->rejection_reason ?? 'No reason provided', 40) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-600">{{ $user->updated_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-center">
                                    <button data-action="delete" data-user-id="{{ $user->user_id }}" data-user-name="{{ $user->name }}"
                                        class="delete-btn bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 inline-flex items-center gap-2 shadow-sm hover:shadow-md">
                                        <i class="fas fa-trash-alt"></i> Delete Permanently
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <!-- Archived Users Section -->
            @if($archivedUsers->count() > 0)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-gray-500 to-gray-400 px-6 py-4">
                    <h3 class="text-xl font-bold text-white flex items-center gap-2">
                        <i class="fas fa-archive"></i>
                        Archived Users
                        <span class="bg-white text-gray-600 text-sm font-bold px-3 py-1 rounded-full ml-2">
                            {{ $archivedUsers->count() }}
                        </span>
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Archived At</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($archivedUsers as $user)
                            <tr class="hover:bg-gray-50 transition-all duration-200">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user text-gray-400"></i>
                                        </div>
                                        <span class="font-semibold text-gray-600">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-500">{{ $user->email }}</td>
                                <td class="px-6 py-4 text-gray-500">{{ $user->archived_at?->format('M d, Y H:i') ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('admin.users.show', ['user' => $user->user_id]) }}" 
                                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 inline-flex items-center gap-2 shadow-sm hover:shadow-md">
                                        <i class="fas fa-eye"></i> View Details
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden transform transition-all">
            <div class="bg-gradient-to-r from-red-500 to-red-400 px-6 py-4 flex items-center justify-between">
                <h3 class="text-xl font-bold text-white flex items-center gap-2">
                    <i class="fas fa-user-times"></i>Reject User
                </h3>
                <button onclick="closeRejectModal()" class="text-white hover:text-gray-200 transition-all duration-200 w-8 h-8 rounded-full bg-white bg-opacity-20 flex items-center justify-center hover:bg-opacity-30">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-slash text-red-500 text-xl"></i>
                    </div>
                    <p class="text-gray-700">Are you sure you want to reject <strong id="rejectUserName" class="text-red-600"></strong>'s registration?</p>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-comment-alt mr-1 text-gray-400"></i>
                        Reason for rejection (optional)
                    </label>
                    <textarea id="rejectionReason" rows="3" 
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-red-300 focus:border-red-400 transition-all duration-200"
                        placeholder="Provide a reason for rejection..."></textarea>
                </div>
                <div class="flex gap-3">
                    <button onclick="confirmReject()" 
                        class="flex-1 bg-gradient-to-r from-red-500 to-red-400 hover:from-red-600 hover:to-red-500 text-white font-bold py-3 rounded-xl transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                        <i class="fas fa-times-circle"></i>
                        Reject User
                    </button>
                    <button onclick="closeRejectModal()" 
                        class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3 rounded-xl transition-all duration-200 flex items-center justify-center gap-2">
                        <i class="fas fa-arrow-left"></i>
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toastContainer" class="fixed top-4 right-4 z-50 space-y-3"></div>

    <script>
        let rejectUserId = null;

        // Show toast notification
        function showAlert(type, message) {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            const bgColor = type === 'success' ? 'bg-gradient-to-r from-green-500 to-green-400' : 'bg-gradient-to-r from-red-500 to-red-400';
            const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
            
            toast.className = `${bgColor} text-white px-6 py-4 rounded-xl shadow-lg flex items-center gap-3 transform transition-all duration-300 translate-x-full`;
            toast.innerHTML = `
                <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas ${icon} text-lg"></i>
                </div>
                <span class="font-medium">${message}</span>
            `;
            
            container.appendChild(toast);
            
            // Animate in
            setTimeout(() => toast.classList.remove('translate-x-full'), 10);
            
            // Remove after 3 seconds
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        function approveUser(userId) {
            if (!confirm('Are you sure you want to approve this user?')) return;
            
            fetch(`/admin/users/${userId}/approve`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', 'User approved successfully! They will receive an email notification.');
                    document.getElementById(`pending-user-${userId}`).remove();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showAlert('error', data.message || 'Failed to approve user');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('error', 'An error occurred while approving the user');
            });
        }

        function showRejectModal(userId, userName) {
            rejectUserId = userId;
            document.getElementById('rejectUserName').textContent = userName;
            document.getElementById('rejectionReason').value = '';
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            rejectUserId = null;
        }

        function confirmReject() {
            if (!rejectUserId) return;
            
            const reason = document.getElementById('rejectionReason').value;
            
            fetch(`/admin/users/${rejectUserId}/reject`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ reason: reason })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', 'User registration rejected.');
                    closeRejectModal();
                    document.getElementById(`pending-user-${rejectUserId}`).remove();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showAlert('error', data.message || 'Failed to reject user');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('error', 'An error occurred while rejecting the user');
            });
        }

        function deleteUser(userId, userName) {
            if (!confirm(`Are you sure you want to permanently delete ${userName}'s account? This action cannot be undone.`)) return;
            
            fetch(`/admin/users/${userId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', 'User account deleted permanently.');
                    document.getElementById(`rejected-user-${userId}`).remove();
                } else {
                    showAlert('error', data.message || 'Failed to delete user');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('error', 'An error occurred while deleting the user');
            });
        }

        // Event delegation for buttons
        document.addEventListener('DOMContentLoaded', function() {
            // Approve buttons
            document.querySelectorAll('.approve-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var userId = this.getAttribute('data-user-id');
                    approveUser(userId);
                });
            });

            // Reject buttons
            document.querySelectorAll('.reject-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var userId = this.getAttribute('data-user-id');
                    var userName = this.getAttribute('data-user-name');
                    showRejectModal(userId, userName);
                });
            });

            // Delete buttons
            document.querySelectorAll('.delete-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var userId = this.getAttribute('data-user-id');
                    var userName = this.getAttribute('data-user-name');
                    deleteUser(userId, userName);
                });
            });
        });
    </script>
</body>

</html>
