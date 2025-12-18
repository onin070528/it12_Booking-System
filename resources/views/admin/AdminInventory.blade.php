<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - RJ's Event Styling</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="font-sans bg-[#ECF4E8]">

    <div class="flex">
        <!-- Admin Sidebar -->
        @include('admin.AdminLayouts.AdminSidebar')

        <!-- Main Content -->
        <div class="flex-1 min-h-screen px-6 py-6 ml-64">
            
        <!-- Header -->
            @include('admin.layouts.header')


                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

    <!-- Total Products -->
    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium mb-1">Total Products</p>
                <h3 class="text-3xl font-bold" style="color: #93BFC7;">{{ $totalProducts }}</h3>
            </div>
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-boxes text-blue-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Low Stock Items -->
    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium mb-1">Low Stock Items</p>
                <h3 class="text-3xl font-bold" style="color: #93BFC7;">{{ $lowStockItems }}</h3>
            </div>
            <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-yellow-600 text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- New Items -->
    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium mb-1">New Items</p>
                <h3 class="text-3xl font-bold" style="color: #93BFC7;">{{ $newItems }}</h3>
            </div>
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-plus-circle text-green-600 text-2xl"></i>
            </div>
        </div>
    </div>

</div>



            <!-- Tabs -->
            <div class="mb-4 flex gap-2">
                <button id="activeTab" class="tab-btn active bg-[#93BFC7] text-white px-6 py-3 rounded-t-lg font-semibold shadow-md">
                    <i class="fas fa-boxes mr-2"></i>Active Items ({{ $totalProducts }})
                </button>
                <button id="archivedTab" class="tab-btn bg-gray-300 text-gray-700 px-6 py-3 rounded-t-lg font-semibold shadow-md hover:bg-gray-400">
                    <i class="fas fa-archive mr-2"></i>Archived Items ({{ $archivedCount }})
                </button>
                <button id="assignedTab" class="tab-btn bg-gray-300 text-gray-700 px-6 py-3 rounded-t-lg font-semibold shadow-md hover:bg-gray-400">
                    <i class="fas fa-clipboard-list mr-2"></i>Assigned To ({{ $assignedInventories->count() }})
                </button>
            </div>

            <!-- Active Items Section -->
            <div id="activeItemsSection" class="overflow-x-auto rounded-xl shadow-lg">
                <div class="bg-[#93BFC7] text-white px-6 py-4 rounded-t-xl shadow flex items-center text-3xl font-bold">
                    <i class="fas fa-boxes mr-3"></i>
                    <h3>Active Inventory</h3>
                    <div class="ml-auto flex items-center gap-3">
                        <div class="bg-white text-[#93BFC7] px-3 py-1 rounded-md shadow font-semibold hover:bg-gray-100 cursor-pointer text-sm">
                            <button id="openAddItemModal">+ Add New Item</button>
                        </div>
                       <div class="flex items-center gap-3 bg-white px-3 py-1 rounded-md shadow text-[#93BFC7] text-sm">
    <label for="inventoryStatusFilter" class="font-medium">
        Status
    </label>

    <select
        id="inventoryStatusFilter"
        class="appearance-none bg-transparent pr-5
               focus:outline-none cursor-pointer">
        <option value="">All</option>
        <option value="In Stock">In Stock</option>
        <option value="Low Stock">Low Stock</option>
        <option value="Out of Stock">Out of Stock</option>
    </select>
</div>

                    </div>
                </div>

                <table class="w-full">
                    <thead>
                        <tr class="bg-white text-[#93BFC7] font-semibold hover:bg-gray-200 border-b border-gray-300">
                            <th class="px-6 py-4 text-left">Item Name</th>
                            <th class="px-6 py-4 text-left">Category</th>
                            <th class="px-6 py-4 text-left">Unit</th>
                            <th class="px-6 py-4 text-left">Stock</th>
                            <th class="px-6 py-4 text-left">Available</th>
                            <th class="px-6 py-4 text-left">Status</th>
                            <th class="px-6 py-4 text-left">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($inventories as $inventory)
                            @php
                                $status = $inventory->status;
                                $statusClass = '';
                                $statusBg = '';
                                
                                if ($status === 'In Stock') {
                                    $statusClass = 'text-green-900';
                                    $statusBg = 'bg-[#D4F6DF]';
                                } elseif ($status === 'Low Stock') {
                                    $statusClass = 'text-yellow-900';
                                    $statusBg = 'bg-yellow-100';
                                } else {
                                    $statusClass = 'text-red-900';
                                    $statusBg = 'bg-red-100';
                                }
                                
                                $availableStock = $inventory->available_stock;
                            @endphp
                            <tr data-status="{{ $status }}" class="bg-white text-[#93BFC7] font-medium hover:bg-gray-200 border-b border-gray-300">
                        <td class="px-6 py-4" data-item-name="{{ $inventory->item_name }}">{{ $inventory->item_name }}</td>
                        <td class="px-6 py-4">{{ $inventory->category }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 bg-gray-100 rounded text-gray-700 text-sm">{{ $units[$inventory->unit] ?? ucfirst($inventory->unit ?? 'pcs') }}</span>
                        </td>
                        <td class="px-6 py-4 font-semibold">{{ number_format($inventory->stock, $inventory->stock == intval($inventory->stock) ? 0 : 2) }}</td>
                        <td class="px-6 py-4">
                            @if($availableStock < $inventory->stock)
                                <span class="text-orange-600 font-semibold">{{ number_format($availableStock, $availableStock == intval($availableStock) ? 0 : 2) }}</span>
                                <span class="text-xs text-gray-500 block">{{ number_format($inventory->stock - $availableStock, 2) }} in use</span>
                            @else
                                <span class="text-green-600 font-semibold">{{ number_format($availableStock, $availableStock == intval($availableStock) ? 0 : 2) }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-2">
                            <span class="inline-flex items-center h-full px-4 py-2 rounded-full {{ $statusBg }} {{ $statusClass }} font-medium">
                                {{ $status }}
                            </span>
                        </td>

                        <td class="px-6 py-4">

                            <!-- EDIT BUTTON (opens modal) -->
                            <button 
                                type="button"
                                data-edit-id="{{ $inventory->inventory_id }}"
                                class="edit-item-btn text-yellow-500 hover:text-yellow-600 mr-3">
                                <i class="fas fa-edit"></i>
                            </button>

                            <!-- ARCHIVE BUTTON -->
                            <button 
                                type="button"
                                data-archive-id="{{ $inventory->inventory_id }}"
                                data-archive-name="{{ $inventory->item_name }}"
                                class="archive-item-btn text-gray-500 hover:text-gray-700">
                                <i class="fas fa-archive"></i>
                            </button>

                        </td>
                    </tr>

                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-box-open text-4xl mb-2 block"></i>
                                    No items in inventory yet. Add your first item!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            <!-- Archived Items Section -->
            <div id="archivedItemsSection" class="overflow-x-auto rounded-xl shadow-lg hidden">
                <div class="bg-gray-500 text-white px-6 py-4 rounded-t-xl shadow flex items-center text-3xl font-bold">
                    <i class="fas fa-archive mr-3"></i>
                    <h3>Archived Inventory</h3>
                </div>

                <table class="w-full">
                    <thead>
                        <tr class="bg-white text-gray-600 font-semibold hover:bg-gray-200 border-b border-gray-300">
                            <th class="px-6 py-4 text-left">Item Name</th>
                            <th class="px-6 py-4 text-left">Category</th>
                            <th class="px-6 py-4 text-left">Unit</th>
                            <th class="px-6 py-4 text-left">Stock</th>
                            <th class="px-6 py-4 text-left">Status</th>
                            <th class="px-6 py-4 text-left">Archived Date</th>
                            <th class="px-6 py-4 text-left">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($archivedInventories as $inventory)
                            @php
                                $status = $inventory->status;
                                $statusClass = '';
                                $statusBg = '';
                                
                                if ($status === 'In Stock') {
                                    $statusClass = 'text-green-900';
                                    $statusBg = 'bg-[#D4F6DF]';
                                } elseif ($status === 'Low Stock') {
                                    $statusClass = 'text-yellow-900';
                                    $statusBg = 'bg-yellow-100';
                                } else {
                                    $statusClass = 'text-red-900';
                                    $statusBg = 'bg-red-100';
                                }
                            @endphp
                            <tr data-status="{{ $status }}" class="bg-white text-gray-600 font-medium hover:bg-gray-200 border-b border-gray-300">
                                <td class="px-6 py-4">{{ $inventory->item_name }}</td>
                                <td class="px-6 py-4">{{ $inventory->category }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 bg-gray-100 rounded text-gray-700 text-sm">{{ $units[$inventory->unit] ?? ucfirst($inventory->unit ?? 'pcs') }}</span>
                                </td>
                                <td class="px-6 py-4 font-semibold">{{ number_format($inventory->stock, $inventory->stock == intval($inventory->stock) ? 0 : 2) }}</td>
                                <td class="px-6 py-2">
                                    <span class="inline-flex items-center h-full px-4 py-2 rounded-full {{ $statusBg }} {{ $statusClass }} font-medium">
                                        {{ $status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-500">
                                    {{ $inventory->archived_at ? $inventory->archived_at->format('M d, Y') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4">
                                    <!-- RESTORE BUTTON -->
                                    <button 
                                        type="button"
                                        data-restore-id="{{ $inventory->inventory_id }}"
                                        data-restore-name="{{ $inventory->item_name }}"
                                        class="restore-item-btn text-green-500 hover:text-green-600">
                                        <i class="fas fa-undo mr-1"></i>Restore
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-archive text-4xl mb-2 block"></i>
                                    No archived items.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Assigned To Section -->
            <div id="assignedItemsSection" class="overflow-x-auto rounded-xl shadow-lg hidden">
                <div class="bg-indigo-600 text-white px-6 py-4 rounded-t-xl shadow flex items-center text-3xl font-bold">
                    <i class="fas fa-clipboard-list mr-3"></i>
                    <h3>Inventory Assigned to Bookings</h3>
                </div>

                <table class="w-full">
                    <thead>
                        <tr class="bg-white text-indigo-600 font-semibold hover:bg-gray-200 border-b border-gray-300">
                            <th class="px-6 py-4 text-left">Item Name</th>
                            <th class="px-6 py-4 text-left">Assigned To</th>
                            <th class="px-6 py-4 text-left">Event Date</th>
                            <th class="px-6 py-4 text-left">Qty Assigned</th>
                            <th class="px-6 py-4 text-left">Status</th>
                            <th class="px-6 py-4 text-left">Assigned Date</th>
                            <th class="px-6 py-4 text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($assignedInventories as $assignment)
                            @php
                                $statusBg = match($assignment->status) {
                                    'assigned' => 'bg-blue-100 text-blue-800',
                                    'in_use' => 'bg-yellow-100 text-yellow-800',
                                    'partially_returned' => 'bg-orange-100 text-orange-800',
                                    'returned' => 'bg-green-100 text-green-800',
                                    default => 'bg-gray-100 text-gray-800',
                                };
                            @endphp
                            <tr class="bg-white text-gray-700 font-medium hover:bg-gray-50 border-b border-gray-300">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-box text-indigo-400"></i>
                                        {{ $assignment->inventory->item_name ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold">{{ $assignment->booking->user->name ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">{{ ucfirst($assignment->booking->event_type ?? '') }}</div>
                                </td>
                                <td class="px-6 py-4">{{ $assignment->booking->event_date?->format('M d, Y') ?? 'N/A' }}</td>
                                <td class="px-6 py-4 font-semibold">
                                    {{ number_format($assignment->quantity_assigned, 2) }} {{ $assignment->inventory->unit ?? 'pcs' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statusBg }}">
                                        {{ ucfirst(str_replace('_', ' ', $assignment->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-500">{{ $assignment->assigned_at?->format('M d, Y g:i A') ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-center">
                                    @if(in_array($assignment->status, ['assigned', 'in_use', 'partially_returned']))
                                        <button 
                                            type="button"
                                            data-return-booking-id="{{ $assignment->booking_id }}"
                                            data-return-assignment-id="{{ $assignment->id }}"
                                            class="return-inventory-btn bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                                            <i class="fas fa-check-circle mr-1"></i>Complete & Return
                                        </button>
                                    @else
                                        <span class="text-green-600"><i class="fas fa-check-circle"></i> Returned</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-clipboard-check text-4xl mb-2 block"></i>
                                    No inventory currently assigned to bookings.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Item Modal -->
    <div id="addItemModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 transform transition-all">
            <div class="bg-[#93BFC7] rounded-t-xl px-6 py-4 flex items-center justify-between">
                <h3 class="text-xl font-bold text-white">
                    <i class="fas fa-plus-circle mr-2"></i>Add New Item
                </h3>
                <button id="closeAddItemModal" class="text-white hover:text-gray-200 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form id="addItemForm" class="p-6">
            @csrf
            <div class="mb-4">
                <label for="item_name" class="block text-gray-700 font-semibold mb-2">
                    Item Name <span class="text-red-500">*</span>
                </label>
                <input type="text" id="item_name" name="item_name" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#93BFC7] focus:border-transparent"
                    placeholder="Enter item name">
            </div>

            <div class="mb-4">
                <label for="category" class="block text-gray-700 font-semibold mb-2">
                    Category <span class="text-red-500">*</span>
                </label>

                <select id="category" name="category" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-[#93BFC7] focus:border-transparent">
                    <option value="" disabled selected>Select category</option>
                    <option value="Event Props">Event Props</option>
                    <option value="Decorations">Decorations</option>
                    <option value="Styling Materials">Styling Materials</option>
                    <option value="Furniture">Furniture</option>
                    <option value="Lights & Effects">Lights & Effects</option>
                    <option value="Fabrics & Cloth">Fabrics & Cloth</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="stock" class="block text-gray-700 font-semibold mb-2">
                        Stock / Quantity <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="stock" name="stock" required min="0" step="0.01"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#93BFC7] focus:border-transparent"
                        placeholder="Enter quantity">
                </div>
                <div>
                    <label for="unit" class="block text-gray-700 font-semibold mb-2">
                        Unit <span class="text-red-500">*</span>
                    </label>
                    <select id="unit" name="unit" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-[#93BFC7] focus:border-transparent">
                        @foreach($units as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex space-x-3">
                <button type="button" id="cancelAddItem" 
                    class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
                    Cancel
                </button>
                <button type="submit" id="submitAddItem"
                    class="flex-1 px-4 py-2 bg-[#93BFC7] text-white font-semibold rounded-lg hover:bg-[#7eaab1] transition">
                    <i class="fas fa-save mr-2"></i>Add Item
                </button>
            </div>
        </form>

        </div>
    </div>

    <!-- Archive Confirmation Modal -->
    <div id="archiveItemModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 transform transition-all">
            <div class="bg-orange-500 rounded-t-xl px-6 py-4 flex items-center justify-between">
                <h3 class="text-xl font-bold text-white">
                    <i class="fas fa-archive mr-2"></i>Archive Item
                </h3>
                <button id="closeArchiveItemModal" class="text-white hover:text-gray-200 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6">
                <div class="mb-4">
                    <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-orange-100 rounded-full">
                        <i class="fas fa-exclamation-triangle text-orange-600 text-2xl"></i>
                    </div>
                    <p class="text-gray-700 text-center font-semibold mb-2">Are you sure you want to archive this item?</p>
                    <p class="text-gray-600 text-center text-sm mb-4">
                        The item "<span id="archiveItemName" class="font-semibold"></span>" will be moved to archived items. You can restore it later if needed.
                    </p>
                </div>
                <div class="flex space-x-3">
                    <button type="button" id="cancelArchiveItem" 
                        class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </button>
                    <button type="button" id="confirmArchiveItem"
                        class="flex-1 px-4 py-2 bg-orange-500 text-white font-semibold rounded-lg hover:bg-orange-600 transition">
                        <i class="fas fa-archive mr-2"></i>Archive
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Inventory status filter (client-side)
        (function() {
            const filter = document.getElementById('inventoryStatusFilter');
            if (!filter) return;

            function applyFilter() {
                const val = filter.value;
                const rows = document.querySelectorAll('#activeItemsSection tbody tr');
                rows.forEach(r => {
                    if (!val) {
                        r.style.display = '';
                        return;
                    }
                    const status = (r.getAttribute('data-status') || '').trim();
                    if (status === val) {
                        r.style.display = '';
                    } else {
                        r.style.display = 'none';
                    }
                });
            }

            filter.addEventListener('change', applyFilter);
        })();
    </script>

    <!-- Restore Confirmation Modal -->
    <div id="restoreItemModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 transform transition-all">
            <div class="bg-green-500 rounded-t-xl px-6 py-4 flex items-center justify-between">
                <h3 class="text-xl font-bold text-white">
                    <i class="fas fa-undo mr-2"></i>Restore Item
                </h3>
                <button id="closeRestoreItemModal" class="text-white hover:text-gray-200 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6">
                <div class="mb-4">
                    <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-green-100 rounded-full">
                        <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                    </div>
                    <p class="text-gray-700 text-center font-semibold mb-2">Are you sure you want to restore this item?</p>
                    <p class="text-gray-600 text-center text-sm mb-4">
                        The item "<span id="restoreItemName" class="font-semibold"></span>" will be moved back to active inventory and will be available for use.
                    </p>
                </div>
                <div class="flex space-x-3">
                    <button type="button" id="cancelRestoreItem" 
                        class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </button>
                    <button type="button" id="confirmRestoreItem"
                        class="flex-1 px-4 py-2 bg-green-500 text-white font-semibold rounded-lg hover:bg-green-600 transition">
                        <i class="fas fa-undo mr-2"></i>Restore
                    </button>
                </div>
            </div>
        </div>
    </div>

                                <!-- Edit Item Modal -->
<div id="editItemModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 transform transition-all">
        
        <!-- Header -->
        <div class="bg-[#93BFC7] rounded-t-xl px-6 py-4 flex items-center justify-between">
            <h3 class="text-xl font-bold text-white">
                <i class="fas fa-edit mr-2"></i>Edit Item
            </h3>
            <button id="closeEditItemModal" class="text-white hover:text-gray-200 transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Edit Form -->
        <form id="editItemForm" class="p-6">
            @csrf

            <input type="hidden" id="edit_item_id" name="id">

            <div class="mb-4">
                <label for="edit_item_name" class="block text-gray-700 font-semibold mb-2">
                    Item Name <span class="text-red-500">*</span>
                </label>
                <input type="text" id="edit_item_name" name="item_name" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#93BFC7] focus:border-transparent">
            </div>

            <div class="mb-4">
                <label for="edit_category" class="block text-gray-700 font-semibold mb-2">
                    Category <span class="text-red-500">*</span>
                </label>
                <select id="edit_category" name="category" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-[#93BFC7] focus:border-transparent">
                    <option value="Event Props">Event Props</option>
                    <option value="Decorations">Decorations</option>
                    <option value="Styling Materials">Styling Materials</option>
                    <option value="Furniture">Furniture</option>
                    <option value="Lights & Effects">Lights & Effects</option>
                    <option value="Fabrics & Cloth">Fabrics & Cloth</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="edit_stock" class="block text-gray-700 font-semibold mb-2">
                        Stock <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="edit_stock" name="stock" required min="0" step="0.01"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#93BFC7] focus:border-transparent">
                </div>
                <div>
                    <label for="edit_unit" class="block text-gray-700 font-semibold mb-2">
                        Unit <span class="text-red-500">*</span>
                    </label>
                    <select id="edit_unit" name="unit" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-[#93BFC7] focus:border-transparent">
                        @foreach($units as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex space-x-3">
                <button type="button" id="cancelEditItem"
                    class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
                    Cancel
                </button>
                <button type="submit"
                    class="flex-1 px-4 py-2 bg-[#93BFC7] text-white font-semibold rounded-lg hover:bg-[#7eaab1] transition">
                    <i class="fas fa-save mr-2"></i>Update Item
                </button>
            </div>
        </form>
    </div>
</div>


    <!-- Toast Notification Container -->
    <div id="toastContainer" class="fixed top-4 right-4 z-50 space-y-2"></div>

        <script>
/* ==========================================
   TOAST NOTIFICATION
========================================== */
function showToast(message, type = 'success') {
    const toastContainer = document.getElementById('toastContainer');
    const toast = document.createElement('div');

    const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
    const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';

    toast.className = `${bgColor} text-white px-6 py-4 rounded-lg shadow-lg flex items-center 
                       space-x-3 min-w-[300px] max-w-md transform transition-all duration-300 
                       translate-x-full opacity-0`;
    toast.innerHTML = `
        <i class="fas ${icon} text-xl"></i>
        <p class="flex-1 font-medium">${message}</p>
        <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200">
            <i class="fas fa-times"></i>
        </button>
    `;

    toastContainer.appendChild(toast);

    setTimeout(() => {
        toast.classList.remove('translate-x-full', 'opacity-0');
        toast.classList.add('translate-x-0', 'opacity-100');
    }, 10);

    setTimeout(() => {
        toast.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => toast.remove(), 300);
    }, 5000);
}


/* ==========================================
   ADD ITEM MODAL
========================================== */
const addItemModal = document.getElementById('addItemModal');
const openAddItemModalBtn = document.getElementById('openAddItemModal');
const closeAddItemModalBtn = document.getElementById('closeAddItemModal');
const cancelAddItemBtn = document.getElementById('cancelAddItem');
const addItemForm = document.getElementById('addItemForm');
const submitAddItemBtn = document.getElementById('submitAddItem');

openAddItemModalBtn.addEventListener('click', () => {
    addItemModal.classList.remove('hidden');
});

function closeAddModal() {
    addItemModal.classList.add('hidden');
    addItemForm.reset();
}

closeAddItemModalBtn.addEventListener('click', closeAddModal);
cancelAddItemBtn.addEventListener('click', closeAddModal);

addItemModal.addEventListener('click', (e) => {
    if (e.target === addItemModal) closeAddModal();
});

addItemForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const formData = new FormData(addItemForm);
    const originalText = submitAddItemBtn.innerHTML;

    submitAddItemBtn.disabled = true;
    submitAddItemBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Adding...';

    try {
        const response = await fetch('{{ route("admin.inventory.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector("meta[name='csrf-token']").content
            }
        });

        const data = await response.json();

        if (response.ok && data.success) {
            showToast('Item added successfully!', 'success');
            closeAddModal();
            setTimeout(() => window.location.reload(), 800);
        } else {
            showToast(data.message || 'Error adding item.', 'error');
        }
    } catch (error) {
        showToast('Connection error. Try again.', 'error');
    }

    submitAddItemBtn.disabled = false;
    submitAddItemBtn.innerHTML = originalText;
});


/* ==========================================
   EDIT ITEM MODAL
========================================== */
const editItemModal = document.getElementById('editItemModal');
const closeEditItemModalBtn = document.getElementById('closeEditItemModal');
const cancelEditItemBtn = document.getElementById('cancelEditItem');
const editItemForm = document.getElementById('editItemForm');

// Event delegation for edit buttons
document.addEventListener('click', (e) => {
    if (e.target.closest('.edit-item-btn')) {
        const btn = e.target.closest('.edit-item-btn');
        const id = btn.getAttribute('data-edit-id');
        if (id) {
            openEditModal(parseInt(id));
        }
    }
    if (e.target.closest('.archive-item-btn')) {
        const btn = e.target.closest('.archive-item-btn');
        const id = btn.getAttribute('data-archive-id');
        const itemName = btn.getAttribute('data-archive-name');
        if (id) {
            archiveItem(parseInt(id), itemName);
        }
    }
});

function openEditModal(id) {
    editItemModal.classList.remove('hidden');

    fetch(`/admin/inventory/${id}`, {
        method: 'GET',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        // Use inventory_id if available, otherwise fall back to id
        const itemId = data.inventory_id || data.id;
        document.getElementById('edit_item_id').value = itemId;
        document.getElementById('edit_item_name').value = data.item_name;
        document.getElementById('edit_category').value = data.category;
        document.getElementById('edit_stock').value = data.stock;
        document.getElementById('edit_unit').value = data.unit || 'pcs';
    })
    .catch(() => showToast('Failed to load item data.', 'error'));
}

function closeEditModal() {
    editItemModal.classList.add('hidden');
    editItemForm.reset();
}

closeEditItemModalBtn.addEventListener('click', closeEditModal);
cancelEditItemBtn.addEventListener('click', closeEditModal);

editItemModal.addEventListener('click', (e) => {
    if (e.target === editItemModal) closeEditModal();
});

editItemForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const id = document.getElementById('edit_item_id').value;
    const formData = new FormData(editItemForm);

    try {
        const response = await fetch(`/admin/inventory/${id}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector("meta[name='csrf-token']").content
            }
        });

        const data = await response.json();

        if (response.ok && data.success) {
            showToast('Item updated successfully!', 'success');
            closeEditModal();
            setTimeout(() => window.location.reload(), 800);
        } else {
            showToast(data.message || 'Update failed.', 'error');
        }
    } catch (error) {
        showToast('Connection error.', 'error');
    }
});


/* ==========================================
   ARCHIVE ITEM MODAL
========================================== */
const archiveItemModal = document.getElementById('archiveItemModal');
const closeArchiveItemModalBtn = document.getElementById('closeArchiveItemModal');
const cancelArchiveItemBtn = document.getElementById('cancelArchiveItem');
const confirmArchiveItemBtn = document.getElementById('confirmArchiveItem');
let currentArchiveId = null;
let currentArchiveItemName = '';

function openArchiveModal(id, itemName) {
    currentArchiveId = id;
    currentArchiveItemName = itemName;
    document.getElementById('archiveItemName').textContent = itemName;
    archiveItemModal.classList.remove('hidden');
}

function closeArchiveModal() {
    archiveItemModal.classList.add('hidden');
    currentArchiveId = null;
    currentArchiveItemName = '';
}

if (closeArchiveItemModalBtn) {
    closeArchiveItemModalBtn.addEventListener('click', closeArchiveModal);
}
if (cancelArchiveItemBtn) {
    cancelArchiveItemBtn.addEventListener('click', closeArchiveModal);
}

if (archiveItemModal) {
    archiveItemModal.addEventListener('click', (e) => {
        if (e.target === archiveItemModal) closeArchiveModal();
    });
}

if (confirmArchiveItemBtn) {
    confirmArchiveItemBtn.addEventListener('click', () => {
        if (!currentArchiveId) return;
        
        // Disable button during request
        confirmArchiveItemBtn.disabled = true;
        confirmArchiveItemBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Archiving...';

        fetch(`/admin/inventory/${currentArchiveId}/archive`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector("meta[name='csrf-token']").content,
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast('Item archived successfully!', 'success');
                closeArchiveModal();
                setTimeout(() => window.location.reload(), 800);
            } else {
                showToast('Failed to archive.', 'error');
                confirmArchiveItemBtn.disabled = false;
                confirmArchiveItemBtn.innerHTML = '<i class="fas fa-archive mr-2"></i>Archive';
            }
        })
        .catch(() => {
            showToast('Server error.', 'error');
            confirmArchiveItemBtn.disabled = false;
            confirmArchiveItemBtn.innerHTML = '<i class="fas fa-archive mr-2"></i>Archive';
        });
    });
}

function archiveItem(id, itemName = null) {
    // Get item name from attribute or table row
    if (!itemName) {
        const btn = document.querySelector(`[data-archive-id="${id}"]`);
        if (!btn) return;
        
        itemName = btn.getAttribute('data-archive-name') || 
                   (btn.closest('tr')?.querySelector('td:first-child')?.textContent.trim()) || 
                   'this item';
    }
    
    openArchiveModal(id, itemName);
}

/* ==========================================
   TAB SWITCHING
========================================== */
const activeTab = document.getElementById('activeTab');
const archivedTab = document.getElementById('archivedTab');
const assignedTab = document.getElementById('assignedTab');
const activeItemsSection = document.getElementById('activeItemsSection');
const archivedItemsSection = document.getElementById('archivedItemsSection');
const assignedItemsSection = document.getElementById('assignedItemsSection');

function setActiveTabState(activeBtn) {
    // Reset all tabs
    [activeTab, archivedTab, assignedTab].forEach(tab => {
        if (tab) {
            tab.classList.remove('active', 'bg-[#93BFC7]', 'text-white');
            tab.classList.add('bg-gray-300', 'text-gray-700');
        }
    });
    // Set active tab
    activeBtn.classList.add('active', 'bg-[#93BFC7]', 'text-white');
    activeBtn.classList.remove('bg-gray-300', 'text-gray-700');
}

if (activeTab) {
    activeTab.addEventListener('click', () => {
        setActiveTabState(activeTab);
        activeItemsSection.classList.remove('hidden');
        archivedItemsSection.classList.add('hidden');
        if (assignedItemsSection) assignedItemsSection.classList.add('hidden');
    });
}

if (archivedTab) {
    archivedTab.addEventListener('click', () => {
        setActiveTabState(archivedTab);
        archivedItemsSection.classList.remove('hidden');
        activeItemsSection.classList.add('hidden');
        if (assignedItemsSection) assignedItemsSection.classList.add('hidden');
    });
}

if (assignedTab) {
    assignedTab.addEventListener('click', () => {
        setActiveTabState(assignedTab);
        if (assignedItemsSection) assignedItemsSection.classList.remove('hidden');
        activeItemsSection.classList.add('hidden');
        archivedItemsSection.classList.add('hidden');
    });
}

/* ==========================================
   RESTORE ITEM MODAL
========================================== */
const restoreItemModal = document.getElementById('restoreItemModal');
const closeRestoreItemModalBtn = document.getElementById('closeRestoreItemModal');
const cancelRestoreItemBtn = document.getElementById('cancelRestoreItem');
const confirmRestoreItemBtn = document.getElementById('confirmRestoreItem');
let currentRestoreId = null;
let currentRestoreItemName = '';

function openRestoreModal(id, itemName) {
    currentRestoreId = id;
    currentRestoreItemName = itemName;
    document.getElementById('restoreItemName').textContent = itemName;
    restoreItemModal.classList.remove('hidden');
}

function closeRestoreModal() {
    restoreItemModal.classList.add('hidden');
    currentRestoreId = null;
    currentRestoreItemName = '';
}

if (closeRestoreItemModalBtn) {
    closeRestoreItemModalBtn.addEventListener('click', closeRestoreModal);
}
if (cancelRestoreItemBtn) {
    cancelRestoreItemBtn.addEventListener('click', closeRestoreModal);
}

if (restoreItemModal) {
    restoreItemModal.addEventListener('click', (e) => {
        if (e.target === restoreItemModal) closeRestoreModal();
    });
}

if (confirmRestoreItemBtn) {
    confirmRestoreItemBtn.addEventListener('click', () => {
        if (!currentRestoreId) return;
        
        // Disable button during request
        confirmRestoreItemBtn.disabled = true;
        confirmRestoreItemBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Restoring...';

        fetch(`/admin/inventory/${currentRestoreId}/restore`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector("meta[name='csrf-token']").content,
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showToast('Item restored successfully!', 'success');
                closeRestoreModal();
                setTimeout(() => window.location.reload(), 800);
            } else {
                showToast('Failed to restore.', 'error');
                confirmRestoreItemBtn.disabled = false;
                confirmRestoreItemBtn.innerHTML = '<i class="fas fa-undo mr-2"></i>Restore';
            }
        })
        .catch(() => {
            showToast('Server error.', 'error');
            confirmRestoreItemBtn.disabled = false;
            confirmRestoreItemBtn.innerHTML = '<i class="fas fa-undo mr-2"></i>Restore';
        });
    });
}

document.addEventListener('click', (e) => {
    if (e.target.closest('.restore-item-btn')) {
        const btn = e.target.closest('.restore-item-btn');
        const id = btn.getAttribute('data-restore-id');
        const itemName = btn.getAttribute('data-restore-name');
        if (id) {
            restoreItem(parseInt(id), itemName);
        }
    }
});

function restoreItem(id, itemName = null) {
    // Get item name from attribute or table row
    if (!itemName) {
        const btn = document.querySelector(`[data-restore-id="${id}"]`);
        if (!btn) return;
        
        itemName = btn.getAttribute('data-restore-name') || 
                   (btn.closest('tr')?.querySelector('td:first-child')?.textContent.trim()) || 
                   'this item';
    }
    
    openRestoreModal(id, itemName);
}

/* ==========================================
   RETURN INVENTORY MODAL
========================================== */
const returnInventoryModal = document.getElementById('returnInventoryModal');
let currentReturnBookingId = null;

// Event delegation for return buttons
document.addEventListener('click', (e) => {
    if (e.target.closest('.return-inventory-btn')) {
        const btn = e.target.closest('.return-inventory-btn');
        const bookingId = btn.getAttribute('data-return-booking-id');
        if (bookingId) {
            openReturnModal(parseInt(bookingId));
        }
    }
});

function openReturnModal(bookingId) {
    currentReturnBookingId = bookingId;
    returnInventoryModal.classList.remove('hidden');
    
    // Load booking inventory
    fetch(`/admin/bookings/${bookingId}/inventory`, {
        method: 'GET',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById('returnBookingInfo').innerHTML = `
            <div class="font-semibold text-lg">${data.customer}</div>
            <div class="text-gray-600">${data.event_type} - ${data.event_date}</div>
        `;
        
        const container = document.getElementById('returnItemsContainer');
        container.innerHTML = '';
        
        if (data.assignments.length === 0) {
            container.innerHTML = '<p class="text-gray-500 text-center py-4">No inventory assigned to this booking.</p>';
            return;
        }
        
        data.assignments.forEach(item => {
            const remainingQty = item.quantity_assigned - item.quantity_returned - item.quantity_damaged;
            const itemHtml = `
                <div class="border rounded-lg p-4 mb-3 bg-gray-50" data-assignment-id="${item.id}">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h4 class="font-semibold text-gray-800">${item.item_name}</h4>
                            <p class="text-sm text-gray-500">${item.category} • Assigned: ${item.quantity_assigned} ${item.unit}</p>
                        </div>
                        <span class="px-2 py-1 rounded text-xs font-medium ${item.status === 'returned' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'}">
                            ${item.status.replace('_', ' ')}
                        </span>
                    </div>
                    ${item.status !== 'returned' ? `
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Good Condition</label>
                            <input type="number" name="quantity_returned_${item.id}" value="${remainingQty}" 
                                min="0" max="${remainingQty}" step="0.01"
                                class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Damaged</label>
                            <input type="number" name="quantity_damaged_${item.id}" value="0" 
                                min="0" max="${remainingQty}" step="0.01"
                                class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-red-300">
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Damage Notes (if any)</label>
                        <input type="text" name="damage_notes_${item.id}" 
                            placeholder="Describe the damage..."
                            class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-gray-300">
                    </div>
                    ` : '<p class="text-green-600 text-sm"><i class="fas fa-check-circle mr-1"></i>Already returned</p>'}
                </div>
            `;
            container.innerHTML += itemHtml;
        });
    })
    .catch(() => {
        showToast('Failed to load inventory data.', 'error');
        closeReturnModal();
    });
}

function closeReturnModal() {
    returnInventoryModal.classList.add('hidden');
    currentReturnBookingId = null;
}

document.getElementById('closeReturnModal')?.addEventListener('click', closeReturnModal);
document.getElementById('cancelReturnModal')?.addEventListener('click', closeReturnModal);

returnInventoryModal?.addEventListener('click', (e) => {
    if (e.target === returnInventoryModal) closeReturnModal();
});

document.getElementById('confirmReturnInventory')?.addEventListener('click', async () => {
    if (!currentReturnBookingId) return;
    
    const btn = document.getElementById('confirmReturnInventory');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
    
    // Gather all items
    const items = [];
    document.querySelectorAll('#returnItemsContainer [data-assignment-id]').forEach(div => {
        const assignmentId = div.getAttribute('data-assignment-id');
        const returnedInput = div.querySelector(`[name="quantity_returned_${assignmentId}"]`);
        const damagedInput = div.querySelector(`[name="quantity_damaged_${assignmentId}"]`);
        const notesInput = div.querySelector(`[name="damage_notes_${assignmentId}"]`);
        
        if (returnedInput && damagedInput) {
            items.push({
                assignment_id: parseInt(assignmentId),
                quantity_returned: parseFloat(returnedInput.value) || 0,
                quantity_damaged: parseFloat(damagedInput.value) || 0,
                damage_notes: notesInput?.value || ''
            });
        }
    });
    
    if (items.length === 0) {
        showToast('No items to return.', 'error');
        btn.disabled = false;
        btn.innerHTML = originalText;
        return;
    }
    
    try {
        const response = await fetch(`/admin/bookings/${currentReturnBookingId}/inventory/return`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector("meta[name='csrf-token']").content,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ items })
        });
        
        const data = await response.json();
        
        if (response.ok && data.success) {
            showToast(data.message || 'Inventory returned successfully!', 'success');
            closeReturnModal();
            setTimeout(() => window.location.reload(), 800);
        } else {
            showToast(data.message || 'Failed to return inventory.', 'error');
        }
    } catch (error) {
        showToast('Connection error. Please try again.', 'error');
    }
    
    btn.disabled = false;
    btn.innerHTML = originalText;
});
</script>

    <!-- Return Inventory Modal -->
    <div id="returnInventoryModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-lg w-full max-h-[90vh] flex flex-col">
            <div class="bg-green-600 rounded-t-xl px-6 py-4 flex items-center justify-between flex-shrink-0">
                <h3 class="text-xl font-bold text-white">
                    <i class="fas fa-clipboard-check mr-2"></i>Complete Booking & Return Items
                </h3>
                <button id="closeReturnModal" class="text-white hover:text-gray-200 transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 overflow-y-auto flex-1">
                <div id="returnBookingInfo" class="mb-4 pb-4 border-b">
                    <!-- Booking info will be loaded here -->
                </div>
                
                <h4 class="font-semibold text-gray-700 mb-3">
                    <i class="fas fa-boxes mr-2"></i>Check Returned Items
                </h4>
                <p class="text-sm text-gray-500 mb-4">Verify the condition of each item. Items marked as damaged will be deducted from inventory.</p>
                
                <div id="returnItemsContainer">
                    <!-- Items will be loaded here -->
                    <div class="text-center py-4">
                        <i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i>
                        <p class="text-gray-500 mt-2">Loading items...</p>
                    </div>
                </div>
            </div>
            <div class="border-t px-6 py-4 flex gap-3 flex-shrink-0">
                <button id="cancelReturnModal" 
                    class="flex-1 px-4 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
                    <i class="fas fa-times mr-2"></i>Cancel
                </button>
                <button id="confirmReturnInventory"
                    class="flex-1 px-4 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition">
                    <i class="fas fa-check-circle mr-2"></i>Complete & Return
                </button>
            </div>
        </div>
    </div>

</body>
</html>
