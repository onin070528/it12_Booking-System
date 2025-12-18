<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - RJ's Event Styling</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-lg shadow-lg">
            
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ session('error') }}
                </div>
            @endif
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif
            
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Payment Checkout
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Complete your booking payment
                </p>
            </div>

            <!-- Booking Summary -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-3" style="color: #93BFC7;">Booking Summary</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Event Type:</span>
                        <span class="font-medium">{{ $booking->event_type }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Event Date:</span>
                        <span class="font-medium">{{ $booking->event_date->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Location:</span>
                        <span class="font-medium">{{ $booking->location }}</span>
                    </div>
                    <div class="flex justify-between pt-2 border-t border-gray-300">
                        <span class="text-sm font-medium text-gray-600">Total Booking Amount:</span>
                        <span class="text-sm font-medium">₱{{ number_format($booking->total_amount, 2) }}</span>
                    </div>
                    @if(isset($isRemainingBalance) && $isRemainingBalance)
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-600">Total Paid:</span>
                            <span class="text-sm font-medium text-green-600">₱{{ number_format($totalPaid, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-600">Remaining Balance:</span>
                            <span class="text-sm font-medium text-orange-600">₱{{ number_format($remainingBalance, 2) }}</span>
                        </div>
                    @else
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-600">Downpayment (30%):</span>
                            <span class="text-sm font-medium">₱{{ number_format($amountToPay, 2) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between pt-2 border-t-2 border-[#93BFC7]">
                        <span class="text-lg font-semibold" style="color: #93BFC7;">Amount to Pay:</span>
                        <span class="text-lg font-bold" style="color: #93BFC7;">₱{{ number_format($amountToPay, 2) }}</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">
                        @if(isset($isRemainingBalance) && $isRemainingBalance)
                            * This is the remaining balance payment. After this payment, your booking will be fully paid.
                        @else
                            * This is a 30% downpayment. The remaining balance will be collected later.
                        @endif
                    </p>
                </div>
            </div>

            <!-- Payment Method Selection -->
            <form id="paymentForm" action="{{ route('payment.process', $booking) }}" method="POST" class="mt-8 space-y-6" enctype="multipart/form-data">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Select Payment Method
                    </label>
                    <div class="space-y-3">
                        <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 transition payment-method-option">
                            <input type="radio" name="payment_method" value="gcash" class="mr-3" required>
                            <i class="fas fa-mobile-alt text-2xl mr-3" style="color: #93BFC7;"></i>
                            <div>
                                <div class="font-semibold">GCash</div>
                                <div class="text-xs text-gray-500">Pay via GCash</div>
                            </div>
                        </label>

                        <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 transition payment-method-option" data-method="paymaya">
                            <input type="radio" name="payment_method" value="paymaya" class="mr-3" required>
                            <i class="fas fa-wallet text-2xl mr-3" style="color: #93BFC7;"></i>
                            <div>
                                <div class="font-semibold">PayMaya</div>
                                <div class="text-xs text-gray-500">Pay via PayMaya</div>
                            </div>
                        </label>

                        <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 transition payment-method-option" data-method="cash">
                            <input type="radio" name="payment_method" value="cash" class="mr-3" required>
                            <i class="fas fa-money-bill-wave text-2xl mr-3" style="color: #93BFC7;"></i>
                            <div>
                                <div class="font-semibold">Cash</div>
                                <div class="text-xs text-gray-500">Pay via Cash</div>
                            </div>
                        </label>
                    </div>
                </div>

                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <ul class="list-disc list-inside text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="flex space-x-4">
                    <a href="{{ route('home') }}" class="flex-1 bg-gray-300 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-400 text-center font-medium transition">
                        Cancel
                    </a>
                    <button type="button" id="proceedBtn" class="flex-1 text-white py-2 px-4 rounded-lg font-medium transition" style="background-color: #93BFC7;" onmouseover="this.style.backgroundColor='#7eaab1'" onmouseout="this.style.backgroundColor='#93BFC7'">
                        Proceed to Payment
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Payment Details Modal for Cash and PayMaya -->
    <div id="paymentDetailsModal" class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-300 scale-95">
            <div class="bg-gradient-to-r from-[#93BFC7] to-[#7eaab1] rounded-t-2xl px-6 py-4 flex items-center justify-between">
                <h3 class="text-xl font-bold text-white">Payment Details</h3>
                <button onclick="closePaymentModal()" class="text-white hover:text-gray-200 transition-colors duration-200 w-8 h-8 flex items-center justify-center rounded-full hover:bg-white/20">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            <form id="paymentDetailsForm" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Reference Number <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="reference_number" name="reference_number" required
                        class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-[#93BFC7] focus:border-[#93BFC7] transition-all duration-200"
                        placeholder="Enter reference number">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Payment Screenshot/Proof <span class="text-gray-400 text-xs">(Optional)</span>
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-[#93BFC7] transition-colors">
                        <div class="space-y-1 text-center">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400"></i>
                            <div class="flex text-sm text-gray-600">
                                <label for="payment_screenshot" class="relative cursor-pointer bg-white rounded-md font-medium text-[#93BFC7] hover:text-[#7eaab1] focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-[#93BFC7]">
                                    <span>Upload a file</span>
                                    <input id="payment_screenshot" name="payment_screenshot" type="file" accept="image/*" class="sr-only">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                            <div id="filePreview" class="hidden mt-2">
                                <img id="previewImage" src="" alt="Preview" class="max-h-32 mx-auto rounded-lg">
                                <p id="fileName" class="text-xs text-gray-600 mt-1"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closePaymentModal()" 
                        class="flex-1 bg-gray-200 text-gray-700 font-semibold py-2.5 rounded-lg hover:bg-gray-300 transition">
                        Cancel
                    </button>
                    <button type="submit" id="modalSubmitBtn"
                        class="flex-1 bg-[#93BFC7] text-white font-semibold py-2.5 rounded-lg hover:bg-[#7eaab1] transition disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-check mr-2"></i><span>Submit Payment</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const paymentForm = document.getElementById('paymentForm');
        const proceedBtn = document.getElementById('proceedBtn');
        const paymentDetailsModal = document.getElementById('paymentDetailsModal');
        const paymentDetailsForm = document.getElementById('paymentDetailsForm');
        const fileInput = document.getElementById('payment_screenshot');
        const filePreview = document.getElementById('filePreview');
        const previewImage = document.getElementById('previewImage');
        const fileName = document.getElementById('fileName');
        const modalSubmitBtn = document.getElementById('modalSubmitBtn');
        let selectedPaymentMethod = null;
        let isSubmitting = false;

        // Handle payment method selection
        document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
            radio.addEventListener('change', function() {
                selectedPaymentMethod = this.value;
            });
        });

        // Handle proceed button click
        proceedBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (isSubmitting) return;
            
            const selectedMethod = document.querySelector('input[name="payment_method"]:checked');
            
            if (!selectedMethod) {
                alert('Please select a payment method');
                return;
            }

            if (selectedMethod.value === 'paymaya' || selectedMethod.value === 'gcash') {
                // Show modal for PayMaya and GCash (requires reference number)
                paymentDetailsModal.classList.remove('hidden');
                setTimeout(() => {
                    paymentDetailsModal.querySelector('.scale-95').style.transform = 'scale(1)';
                }, 10);
            } else if (selectedMethod.value === 'cash') {
                // Direct submit for Cash (no reference number needed) with double-submit prevention
                isSubmitting = true;
                proceedBtn.disabled = true;
                proceedBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
                paymentForm.submit();
            }
        });

        // Handle file preview
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (file.size > 10 * 1024 * 1024) {
                    alert('File size must be less than 10MB');
                    this.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    fileName.textContent = file.name;
                    filePreview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                filePreview.classList.add('hidden');
            }
        });

        // Handle payment details form submission
        paymentDetailsForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (isSubmitting) return;
            
            const referenceNumber = document.getElementById('reference_number').value.trim();
            
            if (!referenceNumber) {
                alert('Please enter a reference number');
                return;
            }

            // Disable submit button and show loading state
            isSubmitting = true;
            modalSubmitBtn.disabled = true;
            modalSubmitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';

            // Add hidden inputs to the main form for reference number and screenshot
            // Remove any existing ones first
            const existingRefInput = paymentForm.querySelector('input[name="reference_number"]');
            if (existingRefInput) existingRefInput.remove();
            
            const existingScreenshotInput = paymentForm.querySelector('input[name="payment_screenshot"][type="hidden"]');
            if (existingScreenshotInput) existingScreenshotInput.remove();
            
            // Add reference number to main form
            const refInput = document.createElement('input');
            refInput.type = 'hidden';
            refInput.name = 'reference_number';
            refInput.value = referenceNumber;
            paymentForm.appendChild(refInput);
            
            // If screenshot was uploaded, move it to the main form
            const screenshot = fileInput.files[0];
            if (screenshot) {
                // Clone the file input and add to main form
                const clonedFileInput = fileInput.cloneNode(true);
                clonedFileInput.id = 'payment_screenshot_main';
                // We need to actually transfer the file - use DataTransfer
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(screenshot);
                clonedFileInput.files = dataTransfer.files;
                
                // Remove existing file input from main form if any, then add new one
                const existingFileInput = paymentForm.querySelector('input[name="payment_screenshot"]');
                if (existingFileInput) existingFileInput.remove();
                paymentForm.appendChild(clonedFileInput);
            }
            
            // Submit the main form
            paymentForm.submit();
        });

        function closePaymentModal() {
            if (isSubmitting) return;
            
            const modal = paymentDetailsModal.querySelector('.scale-95');
            if (modal) {
                modal.style.transform = 'scale(0.95)';
            }
            setTimeout(() => {
                paymentDetailsModal.classList.add('hidden');
                paymentDetailsForm.reset();
                filePreview.classList.add('hidden');
            }, 200);
        }
    </script>
        </div>
    </div>
</body>
</html>

