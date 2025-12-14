<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function dashboard()
    {
        $totalUsers = User::where('role', 'user')->count();
        $totalAdmins = User::where('role', 'admin')->count();
        $recentUsers = User::where('role', 'user')->latest()->take(5)->get();

        // Booking Statistics
        $totalBookings = Booking::count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $confirmedBookings = Booking::where('status', 'confirmed')->count();
        $approvedBookings = Booking::where('status', 'approved')->count();
        $completedBookings = Booking::where('status', 'completed')->count();
        $cancelledBookings = Booking::where('status', 'cancelled')->count();

        // Bookings by Event Type
        $bookingsByType = Booking::selectRaw('event_type, COUNT(*) as count')
            ->groupBy('event_type')
            ->get()
            ->map(function($item) {
                return (object)[
                    'event_type' => $item->event_type,
                    'count' => (int)$item->count
                ];
            });

        // Bookings by Status
        $bookingsByStatus = Booking::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->map(function($item) {
                return (object)[
                    'status' => $item->status,
                    'count' => (int)$item->count
                ];
            });

        // Revenue Statistics
        $totalRevenue = Payment::where('status', 'paid')->sum('amount');
        $downpaymentsReceived = Payment::where('status', 'paid')->sum('amount');
        $pendingPayments = Payment::where('status', 'pending')->sum('amount');
        $totalBookingValue = Booking::sum('total_amount');
        $expectedRevenue = $totalBookingValue * 0.30; // 30% downpayment expected

        // Monthly Revenue Trend (last 6 months)
        $monthlyRevenue = Payment::where('status', 'paid')
            ->whereNotNull('paid_at')
            ->selectRaw('DATE_FORMAT(paid_at, "%Y-%m") as month, SUM(amount) as total')
            ->where('paid_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function($item) {
                return (object)[
                    'month' => $item->month,
                    'total' => (float)$item->total
                ];
            });

        // Payment Methods Statistics
        $paymentsByMethod = Payment::where('status', 'paid')
            ->selectRaw('payment_method, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('payment_method')
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalAdmins',
            'recentUsers',
            'totalBookings',
            'pendingBookings',
            'confirmedBookings',
            'approvedBookings',
            'completedBookings',
            'cancelledBookings',
            'bookingsByType',
            'bookingsByStatus',
            'totalRevenue',
            'downpaymentsReceived',
            'pendingPayments',
            'totalBookingValue',
            'expectedRevenue',
            'monthlyRevenue',
            'paymentsByMethod'
        ));
    }
}
