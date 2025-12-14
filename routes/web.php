<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\LandingController;

// Landing page route - shows landing page to guests, redirects authenticated users
Route::get('/', function () {
    if (Auth::check()) {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('dashboard');
    }
    // Show landing page to unauthenticated users
    return app(LandingController::class)->index();
})->name('landing');

// Contact form submission
Route::post('/contact', [LandingController::class, 'submit'])
    ->middleware('throttle:5,1')
    ->name('landing.contact');


// Admin routes - require admin role ONLY
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
   
    Route::get('/calendar', [EventController::class, 'adminCalendar'])->name('calendar');
   
    Route::get('/events', [EventController::class, 'adminEvents'])->name('events');
    Route::post('/events', [EventController::class, 'adminStore'])->name('events.store');
    Route::get('/events/booking-count', [EventController::class, 'getBookingCount'])->name('events.booking-count');

    // Booking Management (renamed from AdminBooking for consistency)
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/incomplete-count', [BookingController::class, 'getIncompleteCount'])->name('bookings.incomplete-count');
    Route::get('/booking/{booking}', [BookingController::class, 'show'])->name('booking.show');
    Route::post('/booking/{booking}/schedule', [BookingController::class, 'updateSchedule'])->name('booking.schedule');
    Route::post('/booking/{booking}/confirm', [BookingController::class, 'confirm'])->name('booking.confirm');
    Route::post('/booking/{booking}/mark-for-payment', [BookingController::class, 'markForPayment'])->name('booking.mark-for-payment');
    Route::post('/booking/{booking}/mark-payment-partial-paid', [BookingController::class, 'markPaymentAsPartialPaid'])->name('booking.mark-payment-partial-paid');
    Route::post('/booking/{booking}/mark-payment-paid', [BookingController::class, 'markPaymentAsPaid'])->name('booking.mark-payment-paid');
    Route::post('/booking/{booking}/mark-in-design', [BookingController::class, 'markAsInDesign'])->name('booking.mark-in-design');
    Route::post('/booking/{booking}/mark-completed', [BookingController::class, 'markAsCompleted'])->name('booking.mark-completed');
    Route::post('/booking/{booking}/reject', [BookingController::class, 'reject'])->name('booking.reject');
    Route::post('/booking/{booking}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');
    
    // Payment Management (renamed from AdminPayment)
    Route::get('/payments', [EventController::class, 'AdminPayment'])->name('payments.index');
    Route::get('/payment/{id}/details', [EventController::class, 'getPaymentDetails'])->name('payment.details');
    
    // Inventory Management (renamed from AdminInventory)
    Route::get('/inventory', [EventController::class, 'AdminInventory'])->name('inventory.index');
    Route::post('/inventory', [EventController::class, 'storeInventory'])->name('inventory.store');
    Route::get('/inventory/{id}', [EventController::class, 'getInventory'])->name('inventory.get');
    Route::post('/inventory/{id}', [EventController::class, 'updateInventory'])->name('inventory.update');
    Route::post('/inventory/{id}/archive', [EventController::class, 'archiveInventory'])->name('inventory.archive');
    Route::post('/inventory/{id}/restore', [EventController::class, 'restoreInventory'])->name('inventory.restore');
    
    // Reports (renamed from AdminReports)
    Route::get('/reports', [EventController::class, 'AdminReports'])->name('reports.index');
    
    // Admin Messages
    Route::get('/messages', [ChatController::class, 'adminIndex'])->name('messages.index');
    Route::post('/messages/send', [ChatController::class, 'send'])->name('messages.send');
    Route::get('/messages/get', [ChatController::class, 'getMessages'])->name('messages.get');
    Route::get('/messages/unread-count', [ChatController::class, 'getUnreadCount'])->name('messages.unread-count');
    
    // Admin Notifications
    Route::get('/notifications', [NotificationController::class, 'adminIndex'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
    Route::get('/notifications/recent', [NotificationController::class, 'getRecent'])->name('notifications.recent');
    
    // Backward compatibility routes (redirect to new names)
    Route::get('/AdminBooking', function() {
        return redirect()->route('admin.bookings.index');
    })->name('AdminBooking');
    Route::get('/AdminPayment', function() {
        return redirect()->route('admin.payments.index');
    })->name('AdminPayment');
    Route::get('/AdminInventory', function() {
        return redirect()->route('admin.inventory.index');
    })->name('AdminInventory');
    Route::get('/AdminReports', function() {
        return redirect()->route('admin.reports.index');
    })->name('AdminReports');
});

// User routes - require authentication and user role ONLY (admins cannot access)
Route::middleware(['auth', 'user'])->group(function () {
    // Dashboard route
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Home route - My Bookings page
    Route::get('/home', [BookingController::class, 'userBookings'])->name('home');

    // Calendar of Events
    Route::get('/calendar', [EventController::class, 'index'])->name('calendar');
    Route::get('/events', [EventController::class, 'events'])->name('events');
    Route::get('/events/booking-count', [EventController::class, 'getBookingCount'])->name('events.booking-count');

    // Create Booking
    Route::get('/booking/create', function () {
        return view('booking.create');
    })->name('booking.create');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    
    // Choose Communication Method
    Route::post('/booking/{booking}/choose-communication', [BookingController::class, 'chooseCommunicationMethod'])->name('booking.choose-communication');
    
    // Cancel Booking (users can cancel their own bookings)
    Route::post('/booking/{booking}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');

    // Payment routes
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/booking/{booking}/checkout', [PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::post('/booking/{booking}/payment', [PaymentController::class, 'processPayment'])->name('payment.process');
    Route::get('/payment/return', [PaymentController::class, 'paymentReturn'])->name('payment.return');
    Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/failed', [PaymentController::class, 'failed'])->name('payment.failed');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
    Route::get('/notifications/recent', [NotificationController::class, 'getRecent'])->name('notifications.recent');

    // Messages
    Route::get('/messages', [ChatController::class, 'index'])->name('messages.index');
    Route::post('/messages/send', [ChatController::class, 'send'])->name('messages.send');
    Route::get('/messages/get', [ChatController::class, 'getMessages'])->name('messages.get');
    Route::get('/messages/unread-count', [ChatController::class, 'getUnreadCount'])->name('messages.unread-count');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// PayMongo webhook (no auth required, signature verification handles security)
Route::post('/webhook/paymongo', [PaymentController::class, 'webhook'])->name('payment.webhook');

// Include authentication routes (login, register, password reset, etc.)
require __DIR__ . '/auth.php';
