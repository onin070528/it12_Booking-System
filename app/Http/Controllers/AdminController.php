<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Event;
use App\Models\Inventory;
use App\Mail\AccountApprovedMail;
use App\Mail\AccountRejectedMail;
use App\Mail\WelcomeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{

    public function dashboard()
    {
        $totalUsers = User::where('role', 'user')->where('account_status', 'approved')->count();
        $totalAdmins = User::where('role', 'admin')->count();
        $pendingUsersCount = User::where('role', 'user')->where('account_status', 'pending')->count();
        $recentUsers = User::where('role', 'user')->where('account_status', 'approved')->latest()->take(5)->get();

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

        $bookingsByStatus = Booking::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->map(function($item) {
                return (object)[
                    'status' => $item->status,
                    'count' => (int)$item->count
                ];
            });


        // Total Profit: Only from bookings with status 'completed'
        $completedBookingIds = Booking::where('status', 'completed')->pluck('booking_id');
        $totalProfit = Payment::where('status', 'paid')
            ->whereIn('booking_id', $completedBookingIds)
            ->sum('amount');
        
        // This Month's Revenue: Payments received this month
        $thisMonthRevenue = Payment::where('status', 'paid')
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('amount');
        $totalBookingValue = Booking::sum('total_amount');
        $expectedRevenue = $totalBookingValue * 0.30;


        $end = now()->endOfMonth();
        $start = now()->subMonths(5)->startOfMonth();

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

 
        $paymentsByMethod = Payment::whereIn('status', ['paid', 'partial_payment'])
            ->selectRaw('payment_method, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('payment_method')
            ->get();

        $archivedUsers = User::whereNotNull('archived_at')->where('role', 'user')->latest('archived_at')->take(5)->get();

        $inStockCount = Inventory::where('status', 'In Stock')->count();
        $lowStockCount = Inventory::where('status', 'Low Stock')->count();
        $outOfStockCount = Inventory::where('status', 'Out of Stock')->count();

        // Upcoming booking reminders (all upcoming events)
        $upcomingBookings = Booking::with('user')
            ->whereIn('status', ['confirmed', 'approved', 'pending_payment', 'partial_payment', 'design'])
            ->whereNull('archived_at')
            ->where('event_date', '>=', now()->startOfDay())
            ->orderBy('event_date', 'asc')
            ->orderBy('event_time', 'asc')
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
            'totalProfit',
            'thisMonthRevenue',
            'totalBookingValue',
            'expectedRevenue',
            'monthlyRevenue',
            'paymentsByMethod',
            'archivedUsers',
            'pendingUsersCount',
            'inStockCount', 'lowStockCount', 'outOfStockCount',
            'upcomingBookings'
        ));
    }

    public function chartsData(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $end = $endDate ? \Carbon\Carbon::parse($endDate)->endOfDay() : now()->endOfMonth();
        $start = $startDate ? \Carbon\Carbon::parse($startDate)->startOfDay() : now()->subMonths(5)->startOfMonth();

        $bookingsByTypeQuery = Booking::selectRaw('event_type, COUNT(*) as count')
            ->groupBy('event_type')
            ->whereBetween('created_at', [$start, $end]);

        $bookingsByType = $bookingsByTypeQuery->get()->map(function($item) {
            return (object)[
                'event_type' => $item->event_type,
                'count' => (int)$item->count
            ];
        });

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

    public function search(Request $request)
    {
        $q = trim($request->query('q', ''));

        if ($q === '') {
            return response()->json(['results' => []]);
        }

        $limit = 6;
        $users = User::where('name', 'like', "%{$q}%")
            ->orWhere('email', 'like', "%{$q}%")
            ->take($limit)
            ->get(['id', 'name', 'email'])
            ->map(fn($u) => [
                'type' => 'user',
                'id' => $u->id,
                'title' => $u->name,
                'subtitle' => $u->email,
                'url' => route('admin.dashboard')
            ]);
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

    public function showUser(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function usersIndex()
    {
        $pendingUsers = User::where('account_status', 'pending')
            ->where('role', 'user')
            ->orderBy('created_at', 'desc')
            ->get();
            
        $activeUsers = User::whereNull('archived_at')
            ->where('role', 'user')
            ->where('account_status', 'approved')
            ->orderBy('created_at', 'desc')
            ->get();
            
        $archivedUsers = User::whereNotNull('archived_at')
            ->where('role', 'user')
            ->orderBy('archived_at', 'desc')
            ->get();
            
        $rejectedUsers = User::where('account_status', 'rejected')
            ->where('role', 'user')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('admin.users.index', compact('pendingUsers', 'activeUsers', 'archivedUsers', 'rejectedUsers'));
    }

   
    public function userData(User $user)
    {
        return response()->json([
            'id' => $user->user_id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => $user->role,
            'created_at' => $user->created_at?->toDateTimeString(),
            'archived_at' => $user->archived_at?->toDateTimeString(),
        ]);
    }

    public function pendingUsersCount()
    {
        $count = User::where('role', 'user')
            ->where('account_status', 'pending')
            ->count();
            
        return response()->json(['count' => $count]);
    }


    public function archiveUser(User $user)
    {
        try {
            $user->archived_at = now();
            $user->save();
            Log::info('User archived', ['user_id' => $user->id]);
            return response()->json(['success' => true, 'message' => 'User archived']);
        } catch (\Exception $e) {
            Log::error('Failed to archive user: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to archive user'], 500);
        }
    }

    public function approveUser(User $user)
    {
        try {
            if ($user->account_status !== 'pending') {
                return response()->json(['success' => false, 'message' => 'User is not pending approval'], 400);
            }

            $user->account_status = 'approved';
            $user->approved_at = now();
            $user->approved_by = Auth::id();
            $user->save();

            try {
                Mail::to($user->email)->send(new AccountApprovedMail($user));
                Mail::to($user->email)->send(new WelcomeMail($user));
            } catch (\Exception $e) {
                Log::error("Failed to send approval email to {$user->email}: " . $e->getMessage());
            }

            Log::info('User approved', ['user_id' => $user->id, 'approved_by' => Auth::id()]);

            return response()->json(['success' => true, 'message' => 'User account approved successfully']);
        } catch (\Exception $e) {
            Log::error('Failed to approve user: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to approve user'], 500);
        }
    }

    /**
     * Reject a pending user account.
     */
    public function rejectUser(Request $request, User $user)
    {
        try {
            if ($user->account_status !== 'pending') {
                return response()->json(['success' => false, 'message' => 'User is not pending approval'], 400);
            }

            $reason = $request->input('reason');

            $user->account_status = 'rejected';
            $user->rejection_reason = $reason;
            $user->save();

            // Send rejection email
            try {
                Mail::to($user->email)->send(new AccountRejectedMail($user, $reason));
            } catch (\Exception $e) {
                Log::error("Failed to send rejection email to {$user->email}: " . $e->getMessage());
            }

            Log::info('User rejected', ['user_id' => $user->id, 'rejected_by' => Auth::id(), 'reason' => $reason]);

            return response()->json(['success' => true, 'message' => 'User account rejected']);
        } catch (\Exception $e) {
            Log::error('Failed to reject user: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to reject user'], 500);
        }
    }

    /**
     * Delete a rejected user account permanently.
     */
    public function deleteUser(User $user)
    {
        try {
            // Only allow deleting rejected accounts
            if ($user->account_status !== 'rejected') {
                return response()->json(['success' => false, 'message' => 'Only rejected accounts can be deleted'], 400);
            }

            $userId = $user->id;
            $user->delete();

            Log::info('Rejected user deleted', ['user_id' => $userId, 'deleted_by' => Auth::id()]);

            return response()->json(['success' => true, 'message' => 'User account deleted permanently']);
        } catch (\Exception $e) {
            Log::error('Failed to delete user: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to delete user'], 500);
        }
    }
}
