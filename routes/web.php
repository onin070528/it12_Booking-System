<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ChatController;

// Redirect root to login if not authenticated
Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('dashboard');
    }
    return view('auth.login');
});

// Admin routes - require admin role
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
   
    Route::get('/calendar', [EventController::class, 'adminCalendar'])->name('calendar');
   
    Route::get('/events', [EventController::class, 'adminEvents'])->name('events');
    Route::post('/events', [EventController::class, 'adminStore'])->name('events.store');


    Route::get('/AdminBooking', [BookingController::class, 'index'])->name('AdminBooking');
    Route::get('/booking/{booking}', [BookingController::class, 'show'])->name('booking.show');
    Route::post('/booking/{booking}/schedule', [BookingController::class, 'updateSchedule'])->name('booking.schedule');
    Route::post('/booking/{booking}/confirm', [BookingController::class, 'confirm'])->name('booking.confirm');
    Route::get('/AdminPayment', [EventController::class, 'AdminPayment'])->name('AdminPayment');
    Route::get('/AdminInventory', [EventController::class, 'AdminInventory'])->name('AdminInventory');
    Route::post('/AdminInventory', [EventController::class, 'storeInventory'])->name('AdminInventory.store');
    Route::get('/AdminReports', [EventController::class, 'AdminReports'])->name('AdminReports');
    
    // Admin Messages
    Route::get('/messages', [ChatController::class, 'adminIndex'])->name('messages.index');
    Route::post('/messages/send', [ChatController::class, 'send'])->name('messages.send');
    Route::get('/messages/get', [ChatController::class, 'getMessages'])->name('messages.get');
    Route::get('/messages/unread-count', [ChatController::class, 'getUnreadCount'])->name('messages.unread-count');
});

// Protected routes - require authentication
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard route
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Home route - My Bookings page
    Route::get('/home', [BookingController::class, 'userBookings'])->name('home');

    // Calendar of Events
    Route::get('/calendar', function () {
        return view('calendar');
    })->name('calendar');

    // Create Booking
    Route::get('/booking/create', function () {
        return view('booking.create');
    })->name('booking.create');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    
    // Choose Communication Method
    Route::post('/booking/{booking}/choose-communication', [BookingController::class, 'chooseCommunicationMethod'])->name('booking.choose-communication');

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

//calendar
Route::get('/calendar', [EventController::class, 'index'])->name('calendar');
Route::get('/events', [EventController::class, 'events'])->name('events');
Route::post('/events', [EventController::class, 'store'])->name('events.store');

// PayMongo webhook (no auth required, signature verification handles security)
Route::post('/webhook/paymongo', [PaymentController::class, 'webhook'])->name('payment.webhook');

// Include authentication routes (login, register, password reset, etc.)
require __DIR__ . '/auth.php';
