<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archived Bookings - Admin</title>
    @vite(['frontend/css/app.css', 'frontend/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="font-sans bg-[#ECF4E8]">
    <div class="flex">
        @include('admin.AdminLayouts.AdminSidebar')
        <div class="flex-1 min-h-screen px-6 py-6 ml-64">
            <div class="bg-white shadow-md rounded-xl px-6 py-4 flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-bold" style="color: #93BFC7;">Archived Bookings</h2>
                    <p class="text-sm text-gray-600">List of archived bookings</p>
                </div>
               <div>
    <a href="{{ route('admin.bookings.index') }}"
       class="inline-flex items-center gap-2 bg-[#93BFC7] px-4 py-2 rounded-lg
              text-white text-sm font-medium hover:bg-[#7eaab1] transition">
        <i class="fas fa-arrow-left"></i>
        <span>Back to Bookings</span>
    </a>
</div>

            </div>

            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-[#93BFC7] text-white text-lg">
                            <tr>
                                <th class="px-6 py-4 text-left hidden">Booking #</th>
                                <th class="px-6 py-4 text-left">Customer</th>
                                <th class="px-6 py-4 text-left">Event Date</th>
                                <th class="px-6 py-4 text-left">Amount</th>
                                <th class="px-6 py-4 text-left">Archived At</th>
                                <!-- <th class="px-6 py-4 text-center">Actions</th>
                            </tr> -->
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($bookings as $booking)
                                <tr class="hover:bg-gray-50 font-medium">
                                    <td class="px-6 py-4 hidden">{{ $booking->id }}</td>
                                    <td class="px-6 py-4">{{ $booking->user->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4">{{ $booking->event_date?->format('M d, Y') }}</td>
                                    <td class="px-6 py-4">₱{{ number_format($booking->total_amount,2) }}</td>
                                    <td class="px-6 py-4">{{ $booking->archived_at?->format('M d, Y H:i') ?? 'N/A' }}</td>
                                    <!-- <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-3">
                                            <button onclick="restoreBooking('{{ $booking->id }}')" class="bg-green-500 text-white px-3 py-2 rounded-lg">Restore</button>
                                        </div>
                                    </td> -->
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">No archived bookings</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            if (!container) return alert(message);

            const toast = document.createElement('div');
            toast.className = 'max-w-sm w-full bg-white shadow-lg rounded-md pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden mb-3';
            toast.style.borderLeft = type === 'success' ? '4px solid #16a34a' : (type === 'error' ? '4px solid #dc2626' : '4px solid #2563eb');
            toast.innerHTML = `
                <div class="p-3">
                    <div class="text-sm font-medium text-gray-900">${type === 'success' ? 'Success' : (type === 'error' ? 'Error' : 'Notice')}</div>
                    <div class="mt-1 text-sm text-gray-700">${message}</div>
                </div>
            `;

            container.appendChild(toast);

            setTimeout(() => {
                toast.classList.add('opacity-0');
                setTimeout(() => toast.remove(), 400);
            }, 3000);
        }

        function restoreBooking(id) {
            if (!confirm('Restore this booking?')) return;
            fetch(`/admin/booking/${id}/restore`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(r=>r.json())
            .then(d=>{
                if(d.success){
                    showToast('Booking restored successfully!', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast(d.message||'Error restoring booking.', 'error');
                }
            })
            .catch(e=>{ console.error(e); showToast('Error restoring booking.', 'error'); });
        }
    </script>

<!-- Toast Container -->
<div id="toastContainer" class="fixed top-6 right-6 z-[200] flex flex-col items-end"></div>

</body>
</html>
