<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Inventory;
use App\Models\Booking;
use Carbon\Carbon;

class EventController extends Controller
{
    public function index()
    {
        return view('calendar');
    }

    public function events()
    {
        // Get confirmed bookings and format them as calendar events
        $confirmedBookings = Booking::where('status', 'confirmed')
            ->with('user')
            ->get();
        
        $events = [];
        
        // Group bookings by date to show counts
        $bookingsByDate = $confirmedBookings->groupBy(function($booking) {
            return $booking->event_date->format('Y-m-d');
        });
        
        foreach ($bookingsByDate as $date => $bookings) {
            $count = $bookings->count();
            $eventType = $bookings->first()->event_type;
            $customerName = $bookings->first()->user->name;
            
            // Determine color based on booking count
            $color = $count >= 2 ? '#dc2626' : ($count == 1 ? '#f59e0b' : '#10b981'); // Red if full, orange if 1, green if available
            
            $events[] = [
                'id' => 'booking-' . $date,
                'title' => $count . '/2 Bookings - ' . ucfirst($eventType) . ' (' . $customerName . ($count > 1 ? ' +' . ($count - 1) . ' more' : '') . ')',
                'start' => $date,
                'end' => $date,
                'color' => $color,
                'extendedProps' => [
                    'bookingCount' => $count,
                    'maxBookings' => 2,
                    'isFull' => $count >= 2,
                    'bookings' => $bookings->map(function($b) {
                        return [
                            'id' => $b->id,
                            'event_type' => $b->event_type,
                            'customer' => $b->user->name,
                            'location' => $b->location,
                        ];
                    })->toArray(),
                ],
            ];
        }
        
        // Also include regular events if any
        $regularEvents = Event::all()->map(function($event) {
            return [
                'id' => 'event-' . $event->id,
                'title' => $event->title,
                'start' => $event->start,
                'end' => $event->end ?? $event->start,
                'color' => '#3b82f6',
            ];
        })->toArray();
        
        return array_merge($events, $regularEvents);
    }

    public function store(Request $request)
    {
        $event = Event::create($request->all());
        return response()->json($event);
    }

    /**
     * Display the admin calendar view
     */
    public function adminCalendar()
    {
        return view('admin.calendar');
    }

    /**
     * Get all events for admin (shows all user events)
     */
    public function adminEvents()
    {
        // Get confirmed bookings and format them as calendar events
        $confirmedBookings = Booking::where('status', 'confirmed')
            ->with('user')
            ->get();
        
        $events = [];
        
        // Group bookings by date to show counts
        $bookingsByDate = $confirmedBookings->groupBy(function($booking) {
            return $booking->event_date->format('Y-m-d');
        });
        
        foreach ($bookingsByDate as $date => $bookings) {
            $count = $bookings->count();
            $eventType = $bookings->first()->event_type;
            $customerName = $bookings->first()->user->name;
            
            // Determine color based on booking count
            $color = $count >= 2 ? '#dc2626' : ($count == 1 ? '#f59e0b' : '#10b981'); // Red if full, orange if 1, green if available
            
            $events[] = [
                'id' => 'booking-' . $date,
                'title' => $count . '/2 Bookings - ' . ucfirst($eventType) . ' (' . $customerName . ($count > 1 ? ' +' . ($count - 1) . ' more' : '') . ')',
                'start' => $date,
                'end' => $date,
                'color' => $color,
                'extendedProps' => [
                    'bookingCount' => $count,
                    'maxBookings' => 2,
                    'isFull' => $count >= 2,
                    'bookings' => $bookings->map(function($b) {
                        return [
                            'id' => $b->id,
                            'event_type' => $b->event_type,
                            'customer' => $b->user->name,
                            'location' => $b->location,
                        ];
                    })->toArray(),
                ],
            ];
        }
        
        // Also include regular events if any
        $regularEvents = Event::all()->map(function($event) {
            return [
                'id' => 'event-' . $event->id,
                'title' => $event->title,
                'start' => $event->start,
                'end' => $event->end ?? $event->start,
                'color' => '#3b82f6',
            ];
        })->toArray();
        
        return array_merge($events, $regularEvents);
    }
    
    /**
     * Get booking count for a specific date
     */
    public function getBookingCount(Request $request)
    {
        $date = $request->input('date');
        if (!$date) {
            return response()->json(['count' => 0, 'max' => 2, 'isFull' => false]);
        }
        
        $count = Booking::where('status', 'confirmed')
            ->whereDate('event_date', $date)
            ->count();
        
        return response()->json([
            'count' => $count,
            'max' => 2,
            'isFull' => $count >= 2,
        ]);
    }

    /**
     * Store a new event (admin can create events)
     */
    public function adminStore(Request $request)
    {
        $event = Event::create($request->all());
        return response()->json($event);
    }

    /**
     * Display the admin bookings management page
     */
    public function AdminBooking()
    {
        return view('admin.AdminBooking');
    }

   /**
     * Display the admin payment management page
     */
    public function AdminPayment()
    {
        $payments = \App\Models\Payment::with(['booking', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Calculate remaining balances for each booking
        $bookingBalances = [];
        foreach ($payments as $payment) {
            if ($payment->booking) {
                $bookingId = $payment->booking->id;
                if (!isset($bookingBalances[$bookingId])) {
                    $totalPaid = $payment->booking->payments()
                        ->whereIn('status', ['paid', 'partial_paid'])
                        ->sum('amount');
                    $bookingBalances[$bookingId] = [
                        'total_amount' => $payment->booking->total_amount,
                        'total_paid' => $totalPaid,
                        'remaining_balance' => $payment->booking->total_amount - $totalPaid,
                    ];
                }
            }
        }

        return view('admin.AdminPayment', compact('payments', 'bookingBalances'));
    }

     public function AdminReports(Request $request)
    {
        // Get date range filters
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));

        // Booking Statistics
        $totalBookings = \App\Models\Booking::count();
        $pendingBookings = \App\Models\Booking::where('status', 'pending')->count();
        $confirmedBookings = \App\Models\Booking::where('status', 'confirmed')->count();
        $approvedBookings = \App\Models\Booking::where('status', 'approved')->count();
        $completedBookings = \App\Models\Booking::where('status', 'completed')->count();
        $cancelledBookings = \App\Models\Booking::where('status', 'cancelled')->count();

        // Bookings by Event Type
        $bookingsByType = \App\Models\Booking::selectRaw('event_type, COUNT(*) as count')
            ->groupBy('event_type')
            ->get()
            ->map(function($item) {
                return (object)[
                    'event_type' => $item->event_type,
                    'count' => (int)$item->count
                ];
            });

        // Bookings by Status
        $bookingsByStatus = \App\Models\Booking::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->map(function($item) {
                return (object)[
                    'status' => $item->status,
                    'count' => (int)$item->count
                ];
            });

        // Revenue Statistics
        $totalRevenue = \App\Models\Payment::where('status', 'paid')->sum('amount');
        $downpaymentsReceived = \App\Models\Payment::where('status', 'paid')->sum('amount');
        $pendingPayments = \App\Models\Payment::where('status', 'pending')->sum('amount');
        $totalBookingValue = \App\Models\Booking::sum('total_amount');
        $expectedRevenue = $totalBookingValue * 0.30; // 30% downpayment expected

        // Revenue by Date Range
        $revenueInRange = \App\Models\Payment::where('status', 'paid')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->sum('amount');

        $bookingsInRange = \App\Models\Booking::whereBetween('created_at', [$startDate, $endDate])->count();

        // Recent Payments
        $recentPayments = \App\Models\Payment::with(['booking', 'user'])
            ->where('status', 'paid')
            ->orderBy('paid_at', 'desc')
            ->take(10)
            ->get();

        // Top Customers (by booking count)
        $topCustomers = \App\Models\Booking::selectRaw('user_id, COUNT(*) as booking_count')
            ->with('user')
            ->groupBy('user_id')
            ->orderBy('booking_count', 'desc')
            ->take(10)
            ->get();

        // Monthly Revenue Trend (last 6 months)
        $monthlyRevenue = \App\Models\Payment::where('status', 'paid')
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
        $paymentsByMethod = \App\Models\Payment::where('status', 'paid')
            ->selectRaw('payment_method, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('payment_method')
            ->get();

        return view('admin.AdminReports', compact(
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
            'revenueInRange',
            'bookingsInRange',
            'recentPayments',
            'topCustomers',
            'monthlyRevenue',
            'paymentsByMethod',
            'startDate',
            'endDate'
        ));
    }

     public function AdminInventory()
    {
        $inventories = Inventory::orderBy('created_at', 'desc')->get();
        $totalProducts = Inventory::count();
        $lowStockItems = Inventory::where('stock', '<', 10)->where('stock', '>', 0)->count();
        $newItems = Inventory::where('created_at', '>=', now()->subDays(30))->count();
        
        return view('admin.AdminInventory', compact('inventories', 'totalProducts', 'lowStockItems', 'newItems'));
    }

    /**
     * Store a new inventory item
     */
    public function storeInventory(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
        ]);

        $inventory = Inventory::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Item added successfully!',
            'item' => $inventory
        ]);
    }
}
