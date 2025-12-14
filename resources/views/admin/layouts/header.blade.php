<!-- Admin Header with Notifications -->
<div class="bg-white shadow-md rounded-xl px-6 py-4 flex justify-between items-center mb-8">
    <div class="flex items-center space-x-2">
        <!-- Mobile menu button -->
        <button id="mobileMenuBtn" class="md:hidden mr-2 p-2 rounded bg-transparent text-gray-600 hover:bg-gray-100">
            <i class="fas fa-bars"></i>
        </button>
        <div>
            <h2 class="text-3xl font-bold" style="color: #93BFC7;">
                <i class="fas fa-user-shield mr-2"></i>Welcome, {{ Auth::user()->name }}
            </h2>
            <p class="text-1xl font-semibold" style="color: #93BFC7;">
                @if(isset($headerSubtitle))
                    {{ $headerSubtitle }}
                @else
                    Admin Control Panel
                @endif
            </p>
        </div>
    </div>

    <div class="flex items-center space-x-6 text-[#93BFC7]">
        <div class="relative" id="adminSearch">
            <i id="openSearchBtn" class="fas fa-search text-xl cursor-pointer"></i>
            <div id="searchBox" class="hidden absolute right-0 mt-2 w-96 bg-white rounded-lg shadow-xl border border-gray-200 z-50">
                <div class="p-3">
                    <input id="adminSearchInput" type="text" placeholder="Search users, bookings, events, inventory..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#93BFC7] focus:border-transparent" />
                </div>
                <div id="adminSearchResults" class="divide-y divide-gray-100 max-h-80 overflow-auto"></div>
                <div id="adminSearchEmpty" class="p-3 text-center text-gray-500 hidden">No results</div>
            </div>
        </div>
        
        <!-- Notification Dropdown -->
        <div class="relative" id="notificationDropdown">
            <button onclick="toggleNotificationDropdown()" class="relative text-[#93BFC7] hover:text-[#7eaab1] transition">
                <i class="fas fa-bell text-xl cursor-pointer"></i>
                <span id="navbarNotificationBadge" class="hidden absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center animate-pulse"></span>
            </button>
            
            <!-- Dropdown Menu -->
            <div id="notificationDropdownMenu" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 z-50 max-h-96 overflow-y-auto">
                <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="font-bold text-gray-800">Notifications</h3>
                    <a href="{{ route('admin.notifications.index') }}" class="text-sm text-[#93BFC7] hover:underline">View All</a>
                </div>
                <div id="navbarNotificationsList" class="divide-y divide-gray-100">
                    <div class="p-4 text-center text-gray-400">
                        <i class="fas fa-spinner fa-spin"></i> Loading...
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Toggle notification dropdown
    function toggleNotificationDropdown() {
        const menu = document.getElementById('notificationDropdownMenu');
        menu.classList.toggle('hidden');
        
        if (!menu.classList.contains('hidden')) {
            loadNavbarNotifications();
        }
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('notificationDropdown');
        if (dropdown && !dropdown.contains(event.target)) {
            const menu = document.getElementById('notificationDropdownMenu');
            if (menu) menu.classList.add('hidden');
        }
    });

    // Load notifications for navbar dropdown
    function loadNavbarNotifications() {
        fetch('{{ route("admin.notifications.recent") }}', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('navbarNotificationsList');
            if (container) {
                if (data.notifications && data.notifications.length > 0) {
                    container.innerHTML = data.notifications.map(notif => {
                        const timeAgo = new Date(notif.created_at).toLocaleDateString('en-US', {
                            month: 'short',
                            day: 'numeric',
                            hour: 'numeric',
                            minute: '2-digit'
                        });
                        const unreadClass = notif.read ? 'bg-white' : 'bg-blue-50';
                        const unreadDot = notif.read ? '' : '<span class="w-2 h-2 bg-red-500 rounded-full inline-block mr-2"></span>';
                        
                        return `
                            <a href="{{ route('admin.notifications.index') }}" 
                               onclick="markNotificationAsRead(${notif.id})"
                               class="block p-4 hover:bg-gray-50 transition ${unreadClass}">
                                <div class="flex items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center mb-1">
                                            ${unreadDot}
                                            <p class="text-sm text-gray-800 font-medium line-clamp-2">${notif.message}</p>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">${timeAgo}</p>
                                    </div>
                                </div>
                            </a>
                        `;
                    }).join('');
                } else {
                    container.innerHTML = `
                        <div class="p-8 text-center text-gray-400">
                            <i class="fas fa-bell-slash text-3xl mb-2"></i>
                            <p class="text-sm">No notifications</p>
                        </div>
                    `;
                }
            }
        })
        .catch(error => {
            console.error('Error fetching notifications:', error);
        });
    }

    // Mark notification as read
    function markNotificationAsRead(notificationId) {
        fetch(`/admin/notifications/${notificationId}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update counts
                if (typeof updateUnreadNotificationsCount === 'function') {
                    updateUnreadNotificationsCount();
                }
                if (typeof loadRecentNotifications === 'function') {
                    loadRecentNotifications();
                }
            }
        })
        .catch(error => console.error('Error marking notification as read:', error));
    }

    // Update navbar notification badge
    function updateNavbarNotificationBadge() {
        fetch('{{ route("admin.notifications.unread-count") }}', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            const badge = document.getElementById('navbarNotificationBadge');
            if (badge) {
                if (data.count > 0) {
                    badge.textContent = data.count > 99 ? '99+' : data.count;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            }
        })
        .catch(error => console.error('Error fetching notification count:', error));
    }

    // Update on page load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', updateNavbarNotificationBadge);
    } else {
        updateNavbarNotificationBadge();
    }

    // Update every 5 seconds
    setInterval(updateNavbarNotificationBadge, 5000);

    // -----------------------
    // Admin global search
    // -----------------------
    (function() {
        const openBtn = document.getElementById('openSearchBtn');
        const searchBox = document.getElementById('searchBox');
        const searchInput = document.getElementById('adminSearchInput');
        const resultsContainer = document.getElementById('adminSearchResults');
        const emptyEl = document.getElementById('adminSearchEmpty');
        let debounceTimer = null;

        function openSearch() {
            searchBox.classList.remove('hidden');
            searchInput.focus();
        }

        function closeSearch() {
            searchBox.classList.add('hidden');
            resultsContainer.innerHTML = '';
            emptyEl.classList.add('hidden');
            searchInput.value = '';
        }

        openBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            if (searchBox.classList.contains('hidden')) openSearch(); else closeSearch();
        });

        // Close when clicking outside
        document.addEventListener('click', (e) => {
            const wrap = document.getElementById('adminSearch');
            if (wrap && !wrap.contains(e.target)) closeSearch();
        });

        // Debounced input handler
        searchInput.addEventListener('input', (e) => {
            const q = e.target.value.trim();
            clearTimeout(debounceTimer);
            if (q.length < 2) {
                resultsContainer.innerHTML = '';
                emptyEl.classList.add('hidden');
                return;
            }
            debounceTimer = setTimeout(() => doSearch(q), 300);
        });

        async function doSearch(q) {
            resultsContainer.innerHTML = '<div class="p-3 text-center text-gray-500"><i class="fas fa-spinner fa-spin"></i> Searching...</div>';
            try {
                const res = await fetch(`{{ route('admin.search') }}?q=${encodeURIComponent(q)}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const data = await res.json();
                renderResults(data.results || []);
            } catch (err) {
                resultsContainer.innerHTML = `<div class="p-3 text-red-500">Search failed</div>`;
            }
        }

        function renderResults(items) {
            resultsContainer.innerHTML = '';
            if (!items || items.length === 0) {
                emptyEl.classList.remove('hidden');
                return;
            }
            emptyEl.classList.add('hidden');
            items.forEach(item => {
                const el = document.createElement('a');
                el.href = item.url || '#';
                el.className = 'block p-3 hover:bg-gray-50';
                el.innerHTML = `<div class="font-medium text-sm">${escapeHtml(item.title)}</div><div class="text-xs text-gray-500 mt-1">${escapeHtml(item.subtitle || item.type)}</div>`;
                resultsContainer.appendChild(el);
            });
        }

        function escapeHtml(s) {
            if (!s) return '';
            return s.replaceAll('&', '&amp;').replaceAll('<', '&lt;').replaceAll('>', '&gt;');
        }
    })();

    // Mobile sidebar toggle
    (function() {
        const btn = document.getElementById('mobileMenuBtn');
        const sidebar = document.getElementById('adminSidebar');
        if (!btn || !sidebar) return;
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            sidebar.classList.toggle('hidden');
        });

        // Close sidebar when clicking outside (mobile)
        document.addEventListener('click', function(e) {
            if (!sidebar.classList.contains('hidden') && !sidebar.contains(e.target) && !btn.contains(e.target)) {
                sidebar.classList.add('hidden');
            }
        });
    })();
</script>

