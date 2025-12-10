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



            <div class="overflow-x-auto rounded-xl shadow-lg">
                    
                <div class="bg-[#93BFC7] text-white px-6 py-4 rounded-t-xl shadow flex items-center text-3xl font-bold">
                    <i class="fas fa-boxes mr-3"></i>
                    <h3>Inventory</h3>

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
                                <td class="px-6 py-4">{{ $inventory->item_name }}</td>
                                <td class="px-6 py-4">{{ $inventory->category }}</td>
                                <td class="px-6 py-4 font-semibold">{{ $inventory->stock }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-4 py-1 rounded-full {{ $statusBg }} {{ $statusClass }} font-medium inline-block">
                                        {{ $status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <button class="text-[#93BFC7] hover:text-[#7eaab1] mr-3"><i class="fas fa-eye"></i></button>
                                    <button class="text-yellow-500 hover:text-yellow-600 mr-3"><i class="fas fa-edit"></i></button>
                                    <button class="text-red-500 hover:text-red-600"><i class="fas fa-trash"></i></button>
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
                    <input type="text" id="category" name="category" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#93BFC7] focus:border-transparent"
                        placeholder="Enter category (e.g., Event Props, Decorations)">
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

    <!-- Toast Notification Container -->
    <div id="toastContainer" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <script>
        // Toast Notification Function
        function showToast(message, type = 'success') {
            const toastContainer = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            
            const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
            const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
            
            toast.className = `${bgColor} text-white px-6 py-4 rounded-lg shadow-lg flex items-center space-x-3 min-w-[300px] max-w-md transform transition-all duration-300 translate-x-full opacity-0`;
            toast.innerHTML = `
                <i class="fas ${icon} text-xl"></i>
                <p class="flex-1 font-medium">${message}</p>
                <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            toastContainer.appendChild(toast);
            
            // Trigger animation
            setTimeout(() => {
                toast.classList.remove('translate-x-full', 'opacity-0');
                toast.classList.add('translate-x-0', 'opacity-100');
            }, 10);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                toast.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => {
                    if (toast.parentElement) {
                        toast.remove();
                    }
                }, 300);
            }, 5000);
        }

        // Modal elements
        const addItemModal = document.getElementById('addItemModal');
        const openAddItemModalBtn = document.getElementById('openAddItemModal');
        const closeAddItemModalBtn = document.getElementById('closeAddItemModal');
        const cancelAddItemBtn = document.getElementById('cancelAddItem');
        const addItemForm = document.getElementById('addItemForm');
        const submitAddItemBtn = document.getElementById('submitAddItem');

        // Open modal
        openAddItemModalBtn.addEventListener('click', () => {
            addItemModal.classList.remove('hidden');
        });

        // Close modal functions
        function closeModal() {
            addItemModal.classList.add('hidden');
            addItemForm.reset();
        }

        closeAddItemModalBtn.addEventListener('click', closeModal);
        cancelAddItemBtn.addEventListener('click', closeModal);

        // Close modal when clicking outside
        addItemModal.addEventListener('click', (e) => {
            if (e.target === addItemModal) {
                closeModal();
            }
        });

        // Form submission
        addItemForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(addItemForm);
            const submitBtnText = submitAddItemBtn.innerHTML;
            submitAddItemBtn.disabled = true;
            submitAddItemBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Adding...';

            try {
                const response = await fetch('{{ route("admin.inventory.store") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || formData.get('_token')
                    }
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    showToast(data.message || 'Item added successfully!', 'success');
                    closeModal();
                    
                    // Reload page after 1 second to show new item
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    let errorMessage = data.message || 'An error occurred. Please try again.';
                    
                    if (data.errors) {
                        const errorMessages = Object.values(data.errors).flat();
                        errorMessage = errorMessages.join(', ');
                    }
                    
                    showToast(errorMessage, 'error');
                    submitAddItemBtn.disabled = false;
                    submitAddItemBtn.innerHTML = submitBtnText;
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('An error occurred while adding the item. Please check your connection and try again.', 'error');
                submitAddItemBtn.disabled = false;
                submitAddItemBtn.innerHTML = submitBtnText;
            }
        });
    </script>

</body>
</html>
