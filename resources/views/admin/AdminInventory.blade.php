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
            </div>

            <!-- Active Items Section -->
            <div id="activeItemsSection" class="overflow-x-auto rounded-xl shadow-lg">
                <div class="bg-[#93BFC7] text-white px-6 py-4 rounded-t-xl shadow flex items-center text-3xl font-bold">
                    <i class="fas fa-boxes mr-3"></i>
                    <h3>Active Inventory</h3>

                    <div class="justify-end ml-auto bg-white text-[#93BFC7] px-4 py-1 rounded-md shadow font-semibold hover:bg-gray-100 cursor-pointer text-sm">
                        <button id="openAddItemModal">+ Add New Item</button>
                    </div>
                </div>

                <table class="w-full">
                    <thead>
                        <tr class="bg-white text-[#93BFC7] font-semibold hover:bg-gray-200 border-b border-gray-300">
                            <th class="px-6 py-4 text-left">Item Name</th>
                            <th class="px-6 py-4 text-left">Category</th>
                            <th class="px-6 py-4 text-left">Stock</th>
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
                            @endphp
                            <tr class="bg-white text-[#93BFC7] font-medium hover:bg-gray-200 border-b border-gray-300">
                        <td class="px-6 py-4" data-item-name="{{ $inventory->item_name }}">{{ $inventory->item_name }}</td>
                        <td class="px-6 py-4">{{ $inventory->category }}</td>
                        <td class="px-6 py-4 font-semibold">{{ $inventory->stock }}</td>
                        <td class="px-6 py-4">
                            <span class="px-4 py-1 rounded-full {{ $statusBg }} {{ $statusClass }} font-medium inline-block">
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
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
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
                            <tr class="bg-white text-gray-600 font-medium hover:bg-gray-200 border-b border-gray-300">
                                <td class="px-6 py-4">{{ $inventory->item_name }}</td>
                                <td class="px-6 py-4">{{ $inventory->category }}</td>
                                <td class="px-6 py-4 font-semibold">{{ $inventory->stock }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-4 py-1 rounded-full {{ $statusBg }} {{ $statusClass }} font-medium inline-block">
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
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-archive text-4xl mb-2 block"></i>
                                    No archived items.
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
                </select>
            </div>

            <div class="mb-6">
                <label for="stock" class="block text-gray-700 font-semibold mb-2">
                    Stock / Quantity <span class="text-red-500">*</span>
                </label>
                <input type="number" id="stock" name="stock" required min="0"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#93BFC7] focus:border-transparent"
                    placeholder="Enter stock quantity">
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
                </select>
            </div>

            <div class="mb-6">
                <label for="edit_stock" class="block text-gray-700 font-semibold mb-2">
                    Stock <span class="text-red-500">*</span>
                </label>
                <input type="number" id="edit_stock" name="stock" required min="0"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#93BFC7] focus:border-transparent">
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
const activeItemsSection = document.getElementById('activeItemsSection');
const archivedItemsSection = document.getElementById('archivedItemsSection');

if (activeTab && archivedTab) {
    activeTab.addEventListener('click', () => {
        activeTab.classList.add('active', 'bg-[#93BFC7]', 'text-white');
        activeTab.classList.remove('bg-gray-300', 'text-gray-700');
        archivedTab.classList.remove('active', 'bg-[#93BFC7]', 'text-white');
        archivedTab.classList.add('bg-gray-300', 'text-gray-700');
        activeItemsSection.classList.remove('hidden');
        archivedItemsSection.classList.add('hidden');
    });

    archivedTab.addEventListener('click', () => {
        archivedTab.classList.add('active', 'bg-[#93BFC7]', 'text-white');
        archivedTab.classList.remove('bg-gray-300', 'text-gray-700');
        activeTab.classList.remove('active', 'bg-[#93BFC7]', 'text-white');
        activeTab.classList.add('bg-gray-300', 'text-gray-700');
        archivedItemsSection.classList.remove('hidden');
        activeItemsSection.classList.add('hidden');
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
</script>


</body>
</html>
