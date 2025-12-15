<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Event;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

        // Monthly Revenue Trend (last 6 months) - ensure months with zero totals are present
        $end = now()->endOfMonth();
        $start = now()->subMonths(5)->startOfMonth();

        // Build list of last 6 months in YYYY-MM format (oldest -> newest)
        $months = collect();
        for ($i = 0; $i < 6; $i++) {
            $months->push(now()->subMonths(5 - $i)->format('Y-m'));
        }

        $paymentsByMonth = Payment::where('status', 'paid')
            ->whereNotNull('paid_at')
            ->whereBetween('paid_at', [$start, $end])
            ->selectRaw('DATE_FORMAT(paid_at, "%Y-%m") as month, SUM(amount) as total')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $monthlyRevenue = $months->map(function($m) use ($paymentsByMonth) {
            return (object)[
                'month' => $m,
                'total' => (float) ($paymentsByMonth[$m] ?? 0),
            ];
        });

        // Payment Methods Statistics
        $paymentsByMethod = Payment::where('status', 'paid')
            ->selectRaw('payment_method, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('payment_method')
            ->get();

        // Archived users (for dashboard quick view)
        $archivedUsers = User::whereNotNull('archived_at')->where('role', 'user')->latest('archived_at')->take(5)->get();

        // Inventory status counts
        $inStockCount = Inventory::where('status', 'In Stock')->count();
        $lowStockCount = Inventory::where('status', 'Low Stock')->count();
        $outOfStockCount = Inventory::where('status', 'Out of Stock')->count();

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
            'paymentsByMethod',
            'archivedUsers'
            , 'inStockCount', 'lowStockCount', 'outOfStockCount'
        ));
    }

    /**
     * Return JSON chart data for given date range (used by dashboard filters).
     * Expects `start_date` and `end_date` as YYYY-MM-DD (optional).
     */
    public function chartsData(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        // Determine date range; default to last 6 months
        $end = $endDate ? \Carbon\Carbon::parse($endDate)->endOfDay() : now()->endOfMonth();
        $start = $startDate ? \Carbon\Carbon::parse($startDate)->startOfDay() : now()->subMonths(5)->startOfMonth();

        // Bookings by type within range (based on created_at)
        $bookingsByTypeQuery = Booking::selectRaw('event_type, COUNT(*) as count')
            ->groupBy('event_type')
            ->whereBetween('created_at', [$start, $end]);

        $bookingsByType = $bookingsByTypeQuery->get()->map(function($item) {
            return (object)[
                'event_type' => $item->event_type,
                'count' => (int)$item->count
            ];
        });

        // Bookings by status within range
        $bookingsByStatus = Booking::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->whereBetween('created_at', [$start, $end])
            ->get()
            ->map(function($item) {
                return (object)[
                    'status' => $item->status,
                    'count' => (int)$item->count
                ];
            });

        // Monthly revenue for months between start and end (inclusive)
        // Build months in YYYY-MM format between start and end (limit to 24 months to be safe)
        $months = collect();
        $cursor = $start->copy()->startOfMonth();
        while ($cursor->lte($end) && $months->count() < 24) {
            $months->push($cursor->format('Y-m'));
            $cursor->addMonth();
        }

        $paymentsByMonth = \App\Models\Payment::where('status', 'paid')
            ->whereNotNull('paid_at')
            ->whereBetween('paid_at', [$start, $end])
            ->selectRaw('DATE_FORMAT(paid_at, "%Y-%m") as month, SUM(amount) as total')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $monthlyRevenue = $months->map(function($m) use ($paymentsByMonth) {
            return (object)[
                'month' => $m,
                'total' => (float) ($paymentsByMonth[$m] ?? 0),
            ];
        })->values();

        return response()->json([
            'bookingsByType' => [
                'labels' => $bookingsByType->map(fn($i) => ucfirst($i->event_type))->values(),
                'data' => $bookingsByType->pluck('count')->values(),
            ],
            'bookingsByStatus' => [
                'labels' => $bookingsByStatus->map(fn($i) => ucfirst($i->status))->values(),
                'data' => $bookingsByStatus->pluck('count')->values(),
            ],
            'monthlyRevenue' => [
                'labels' => $monthlyRevenue->pluck('month')->values(),
                'data' => $monthlyRevenue->pluck('total')->values(),
            ]
        ]);
    }

    /**
     * Restore an archived user (remove archived_at timestamp).
     */
    public function restoreUser(User $user)
    {
        try {
            $user->archived_at = null;
            $user->save();

            Log::info('User restored', ['user_id' => $user->id]);

            return response()->json(['success' => true, 'message' => 'User restored']);
        } catch (\Exception $e) {
            Log::error('Failed to restore user: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to restore user'], 500);
        }
    }

    /**
     * Global admin search across bookings, users, events, and inventory.
     * Returns JSON with categorized results.
     */
    public function search(Request $request)
    {
        $q = trim($request->query('q', ''));

        if ($q === '') {
            return response()->json(['results' => []]);
        }

        $limit = 6;

        // Search users by name or email
        $users = User::where('name', 'like', "%{$q}%")
            ->orWhere('email', 'like', "%{$q}%")
            ->take($limit)
            ->get(['id', 'name', 'email'])
            ->map(fn($u) => [
                'type' => 'user',
                'id' => $u->id,
                'title' => $u->name,
                'subtitle' => $u->email,
                'url' => route('admin.dashboard') // change if you have a user detail page
            ]);

        // Search bookings by id or event_type or user name
        $bookings = Booking::where('id', $q)
            ->orWhere('event_type', 'like', "%{$q}%")
            ->orWhereHas('user', fn($qbd) => $qbd->where('name', 'like', "%{$q}%"))
            ->take($limit)
            ->get()
            ->map(fn($b) => [
                'type' => 'booking',
                'id' => $b->id,
                'title' => ucfirst($b->event_type) . ' - #' . $b->id,
                'subtitle' => $b->user?->name ?? '',
                'url' => route('admin.booking.show', ['booking' => $b->id]) ?? '#'
            ]);

        // Search events by title
        $events = Event::where('title', 'like', "%{$q}%")
            ->take($limit)
            ->get()
            ->map(fn($e) => [
                'type' => 'event',
                'id' => $e->id,
                'title' => $e->title,
                'subtitle' => $e->start?->toDateString() ?? '',
                'url' => route('admin.events')
            ]);

        // Search inventory by item_name
        $inventories = Inventory::where('item_name', 'like', "%{$q}%")
            ->take($limit)
            ->get()
            ->map(fn($i) => [
                'type' => 'inventory',
                'id' => $i->inventory_id ?? $i->id,
                'title' => $i->item_name,
                'subtitle' => $i->category ?? '',
                'url' => route('admin.inventory.index')
            ]);

        $results = array_merge($users->toArray(), $bookings->toArray(), $events->toArray(), $inventories->toArray());

        return response()->json(['results' => $results]);
    }

    /**
     * Show user details for admin.
     */
    public function showUser(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * List users for admin - shows active and archived users.
     */
    public function usersIndex()
    {
        $activeUsers = User::whereNull('archived_at')->where('role', 'user')->orderBy('created_at', 'desc')->get();
        $archivedUsers = User::whereNotNull('archived_at')->where('role', 'user')->orderBy('archived_at', 'desc')->get();

        return view('admin.users.index', compact('activeUsers', 'archivedUsers'));
    }

    /**
     * Return JSON data for a single user (used by admin dashboard modal).
     */
    public function userData(User $user)
    {
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => $user->role,
            'created_at' => $user->created_at?->toDateTimeString(),
            'archived_at' => $user->archived_at?->toDateTimeString(),
        ]);
    }

    /**
     * Archive a user (soft archive via archived_at timestamp).
     */
    public function archiveUser(User $user)
    {
        try {
            $user->archived_at = now();
            $user->save();

            // Optionally log
            Log::info('User archived', ['user_id' => $user->id]);

            return response()->json(['success' => true, 'message' => 'User archived']);
        } catch (\Exception $e) {
            Log::error('Failed to archive user: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to archive user'], 500);
        }
    }
}
