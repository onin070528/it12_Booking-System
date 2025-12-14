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
        // Get active bookings (approved, confirmed) and format them as calendar events
        // For user calendar, we only return booking data for availability badges
        // Regular events are NOT shown to users - only availability numbers
        $confirmedBookings = Booking::whereIn('status', ['confirmed', 'approved'])
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
                'title' => '', // Empty title - user calendar only shows badges, not event titles
                'start' => $date,
                'end' => $date,
                'display' => 'none', // Hide the event title, only show badge
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
        
        // Do NOT include regular events for user calendar - users only see availability numbers
        
        // Return JSON response for FullCalendar
        return response()->json($events);
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
     * Get all events for admin (shows all bookings and regular events)
     */
    public function adminEvents()
    {
        // Get active bookings (approved, confirmed) and format them as calendar events
        $confirmedBookings = Booking::whereIn('status', ['confirmed', 'approved'])
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
                    'isBooking' => true, // Mark as booking event
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
        
        // Include regular events (admin-created events) - these should display with their titles
        $regularEvents = Event::all()->map(function($event) {
            $startDate = is_string($event->start) ? $event->start : $event->start->format('Y-m-d');
            $endDate = $event->end ? (is_string($event->end) ? $event->end : $event->end->format('Y-m-d')) : $startDate;
            
            return [
                'id' => 'event-' . $event->id,
                'title' => $event->title,
                'start' => $startDate,
                'end' => $endDate,
                'color' => '#3b82f6',
                'extendedProps' => [
                    'isBooking' => false, // Mark as regular event
                ],
            ];
        })->toArray();
        
        $allEvents = array_merge($events, $regularEvents);
        
        // Return JSON response for FullCalendar
        return response()->json($allEvents);
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
        
        // Count all active bookings (approved, confirmed, partial_payment, pending_payment, in_design)
        // These are bookings that have been accepted and should count toward availability
        $count = Booking::whereIn('status', ['confirmed', 'approved'])
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
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'start' => 'required|date',
            'end' => 'nullable|date',
        ]);

        $event = Event::create([
            'title' => $validated['title'],
            'start' => $validated['start'],
            'end' => $validated['end'] ?? $validated['start'],
            'description' => $request->input('description', ''),
        ]);

        // Format response for FullCalendar - ensure dates are in Y-m-d format
        $startDate = is_string($event->start) ? $event->start : $event->start->format('Y-m-d');
        $endDate = $event->end ? (is_string($event->end) ? $event->end : $event->end->format('Y-m-d')) : $startDate;

        return response()->json([
            'id' => 'event-' . $event->id,
            'title' => $event->title,
            'start' => $startDate,
            'end' => $endDate,
            'color' => '#3b82f6',
            'extendedProps' => [
                'isBooking' => false,
            ],
        ]);
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
                        ->whereIn('status', ['paid', 'partial_payment'])
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

    /**
     * Get payment details with history
     */
    public function getPaymentDetails($id)
    {
        $payment = \App\Models\Payment::with(['booking', 'user'])
            ->findOrFail($id);

        // Get all payments for this booking (payment history)
        $paymentHistory = \App\Models\Payment::where('booking_id', $payment->booking_id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate payment totals
        $totalPaid = $paymentHistory->whereIn('status', ['paid', 'partial_payment'])->sum('amount');
        $remainingBalance = $payment->booking->total_amount - $totalPaid;

        if (request()->ajax()) {
            return view('admin.payments.details', compact('payment', 'paymentHistory', 'totalPaid', 'remainingBalance'))->render();
        }

        return view('admin.payments.details', compact('payment', 'paymentHistory', 'totalPaid', 'remainingBalance'));
    }

     public function AdminReports(Request $request)
    {
        // Get filters
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));
        $statusFilter = $request->input('status');
        $eventTypeFilter = $request->input('event_type');
        $paymentMethodFilter = $request->input('payment_method');

        // Base queries with date range
        $bookingQuery = \App\Models\Booking::whereBetween('created_at', [$startDate, $endDate]);
        $paymentQuery = \App\Models\Payment::whereBetween('paid_at', [$startDate, $endDate]);

        // Apply filters
        if ($statusFilter) {
            $bookingQuery->where('status', $statusFilter);
        }
        if ($eventTypeFilter) {
            $bookingQuery->where('event_type', $eventTypeFilter);
        }
        if ($paymentMethodFilter) {
            $paymentQuery->where('payment_method', $paymentMethodFilter);
        }

        // Booking Statistics (filtered)
        $totalBookings = $bookingQuery->count();
        $pendingBookings = \App\Models\Booking::whereBetween('created_at', [$startDate, $endDate])->where('status', 'pending')->count();
        $confirmedBookings = \App\Models\Booking::whereBetween('created_at', [$startDate, $endDate])->where('status', 'confirmed')->count();
        $approvedBookings = \App\Models\Booking::whereBetween('created_at', [$startDate, $endDate])->where('status', 'approved')->count();
        $completedBookings = \App\Models\Booking::whereBetween('created_at', [$startDate, $endDate])->where('status', 'completed')->count();
        $cancelledBookings = \App\Models\Booking::whereBetween('created_at', [$startDate, $endDate])->where('status', 'cancelled')->count();

        // Bookings by Event Type (filtered by date range)
        $bookingsByType = \App\Models\Booking::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('event_type, COUNT(*) as count')
            ->groupBy('event_type')
            ->get()
            ->map(function($item) {
                return (object)[
                    'event_type' => $item->event_type,
                    'count' => (int)$item->count
                ];
            });

        // Bookings by Status (filtered by date range)
        $bookingsByStatus = \App\Models\Booking::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->map(function($item) {
                return (object)[
                    'status' => $item->status,
                    'count' => (int)$item->count
                ];
            });

        // Revenue Statistics (filtered)
        $totalRevenue = \App\Models\Payment::where('status', 'paid')->sum('amount');
        $revenueInRange = $paymentQuery->where('status', 'paid')->sum('amount');
        $downpaymentsReceived = \App\Models\Payment::where('status', 'paid')->sum('amount');
        $pendingPayments = \App\Models\Payment::where('status', 'pending')->sum('amount');
        $totalBookingValue = \App\Models\Booking::sum('total_amount');
        $expectedRevenue = $totalBookingValue * 0.30;

        $bookingsInRange = \App\Models\Booking::whereBetween('created_at', [$startDate, $endDate])->count();

        // Recent Payments (filtered)
        $recentPaymentsQuery = \App\Models\Payment::with(['booking', 'user'])
            ->where('status', 'paid')
            ->whereBetween('paid_at', [$startDate, $endDate]);
        
        if ($paymentMethodFilter) {
            $recentPaymentsQuery->where('payment_method', $paymentMethodFilter);
        }
        
        $recentPayments = $recentPaymentsQuery->orderBy('paid_at', 'desc')->take(20)->get();

        // Top Customers (filtered by date range)
        $topCustomers = \App\Models\Booking::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('user_id, COUNT(*) as booking_count')
            ->with('user')
            ->groupBy('user_id')
            ->orderBy('booking_count', 'desc')
            ->take(10)
            ->get();

        // Monthly Revenue Trend (last 6 months, filtered)
        $monthlyRevenue = \App\Models\Payment::where('status', 'paid')
            ->whereNotNull('paid_at')
            ->selectRaw('DATE_FORMAT(paid_at, "%Y-%m") as month, SUM(amount) as total')
            ->where('paid_at', '>=', now()->subMonths(6))
            ->where('paid_at', '<=', $endDate)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function($item) {
                return (object)[
                    'month' => $item->month,
                    'total' => (float)$item->total
                ];
            });

        // Payment Methods Statistics (filtered by date range)
        $paymentsByMethod = \App\Models\Payment::where('status', 'paid')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->selectRaw('payment_method, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('payment_method')
            ->get();

        // Get unique values for filter dropdowns
        $allEventTypes = \App\Models\Booking::distinct()->pluck('event_type')->filter();
        $allStatuses = \App\Models\Booking::distinct()->pluck('status')->filter();
        $allPaymentMethods = \App\Models\Payment::distinct()->pluck('payment_method')->filter();

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
            'endDate',
            'statusFilter',
            'eventTypeFilter',
            'paymentMethodFilter',
            'allEventTypes',
            'allStatuses',
            'allPaymentMethods'
        ));
    }

     public function AdminInventory()
    {
        // Get active (non-archived) inventories
        $inventories = Inventory::whereNull('archived_at')->orderBy('created_at', 'desc')->get();
        $archivedInventories = Inventory::whereNotNull('archived_at')->orderBy('archived_at', 'desc')->get();
        $totalProducts = Inventory::whereNull('archived_at')->count();
        $lowStockItems = Inventory::whereNull('archived_at')->where('stock', '<', 10)->where('stock', '>', 0)->count();
        $newItems = Inventory::whereNull('archived_at')->where('created_at', '>=', now()->subDays(30))->count();
        $archivedCount = Inventory::whereNotNull('archived_at')->count();
        
        return view('admin.AdminInventory', compact('inventories', 'archivedInventories', 'totalProducts', 'lowStockItems', 'newItems', 'archivedCount'));
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

    /**
     * Get a single inventory item
     */
    public function getInventory($id)
    {
        $inventory = Inventory::where('inventory_id', $id)->firstOrFail();
        // Return with both id and inventory_id for compatibility
        $inventory->id = $inventory->inventory_id;
        return response()->json($inventory);
    }

    /**
     * Update an inventory item
     */
    public function updateInventory(Request $request, $id)
    {
        $inventory = Inventory::where('inventory_id', $id)->firstOrFail();

        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
        ]);

        $inventory->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Item updated successfully!',
            'item' => $inventory
        ]);
    }

    /**
     * Archive an inventory item
     */
    public function archiveInventory($id)
    {
        $inventory = Inventory::where('inventory_id', $id)->firstOrFail();
        
        // Mark as archived instead of deleting
        $inventory->archived_at = now();
        $inventory->save();

        return response()->json([
            'success' => true,
            'message' => 'Item archived successfully!'
        ]);
    }

    /**
     * Restore an archived inventory item
     */
    public function restoreInventory($id)
    {
        $inventory = Inventory::where('inventory_id', $id)->firstOrFail();
        
        // Restore by removing archived_at
        $inventory->archived_at = null;
        $inventory->save();

        return response()->json([
            'success' => true,
            'message' => 'Item restored successfully!'
        ]);
    }
}
