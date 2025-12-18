<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Event;
use App\Models\Inventory;
use App\Models\Booking;
use App\Models\BookingInventory;
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

        // Monthly Revenue Trend (last 6 months) - ensure all months are present
        $end = now()->endOfMonth();
        $start = now()->subMonths(5)->startOfMonth();

        // Build list of last 6 months in YYYY-MM format (oldest -> newest)
        $months = collect();
        for ($i = 0; $i < 6; $i++) {
            $months->push(now()->subMonths(5 - $i)->format('Y-m'));
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
        
        // Get all active bookings with inventory assignments
        $assignedInventories = \App\Models\BookingInventory::with(['booking.user', 'inventory'])
            ->whereIn('status', ['assigned', 'in_use', 'partially_returned'])
            ->orderBy('assigned_at', 'desc')
            ->get();
        
        // Get inventory units for the form
        $units = Inventory::$units;
        
        return view('admin.AdminInventory', compact(
            'inventories', 
            'archivedInventories', 
            'totalProducts', 
            'lowStockItems', 
            'newItems', 
            'archivedCount',
            'assignedInventories',
            'units'
        ));
    }

    /**
     * Store a new inventory item
     */
    public function storeInventory(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'stock' => 'required|numeric|min:0',
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
        $inventory->available_stock = $inventory->available_stock;
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
            'unit' => 'required|string|max:50',
            'stock' => 'required|numeric|min:0',
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
        
        // Check if item is currently assigned to any booking
        $inUse = $inventory->bookingAssignments()
            ->whereIn('status', ['assigned', 'in_use'])
            ->exists();
            
        if ($inUse) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot archive this item. It is currently assigned to a booking.'
            ], 400);
        }
        
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

    /**
     * Assign inventory items to a booking
     */
    public function assignInventory(Request $request, $bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.inventory_id' => 'required|exists:inventories,inventory_id',
            'items.*.quantity' => 'required|numeric|min:0.01',
        ]);

        $errors = [];
        $assigned = [];

        foreach ($validated['items'] as $item) {
            $inventory = Inventory::where('inventory_id', $item['inventory_id'])->first();
            
            if (!$inventory) {
                $errors[] = "Inventory item not found.";
                continue;
            }

            // Check available stock
            if ($inventory->available_stock < $item['quantity']) {
                $errors[] = "{$inventory->item_name}: Only {$inventory->available_stock} {$inventory->unit} available.";
                continue;
            }

            // Create or update the assignment
            $assignment = BookingInventory::updateOrCreate(
                [
                    'booking_id' => $bookingId,
                    'inventory_id' => $item['inventory_id'],
                ],
                [
                    'quantity_assigned' => DB::raw('quantity_assigned + ' . $item['quantity']),
                    'status' => 'assigned',
                    'assigned_at' => now(),
                ]
            );

            // If new record, set quantity directly
            if ($assignment->wasRecentlyCreated) {
                $assignment->quantity_assigned = $item['quantity'];
                $assignment->save();
            }

            $assigned[] = $inventory->item_name;
        }

        if (!empty($errors)) {
            return response()->json([
                'success' => false,
                'message' => implode(' ', $errors),
                'assigned' => $assigned
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Inventory assigned successfully!',
            'assigned' => $assigned
        ]);
    }

    /**
     * Get available inventory for assignment
     */
    public function getAvailableInventory()
    {
        $inventories = Inventory::whereNull('archived_at')
            ->where('stock', '>', 0)
            ->orderBy('item_name')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->inventory_id,
                    'item_name' => $item->item_name,
                    'category' => $item->category,
                    'unit' => $item->unit,
                    'total_stock' => $item->stock,
                    'available_stock' => $item->available_stock,
                    'status' => $item->status,
                ];
            });

        return response()->json($inventories);
    }

    /**
     * Get inventory assigned to a specific booking
     */
    public function getBookingInventory($bookingId)
    {
        $booking = Booking::with(['inventoryAssignments.inventory'])->findOrFail($bookingId);
        
        $assignments = $booking->inventoryAssignments->map(function ($assignment) {
            return [
                'id' => $assignment->id,
                'inventory_id' => $assignment->inventory_id,
                'item_name' => $assignment->inventory->item_name,
                'category' => $assignment->inventory->category,
                'unit' => $assignment->inventory->unit,
                'quantity_assigned' => $assignment->quantity_assigned,
                'quantity_returned' => $assignment->quantity_returned,
                'quantity_damaged' => $assignment->quantity_damaged,
                'damage_notes' => $assignment->damage_notes,
                'status' => $assignment->status,
                'assigned_at' => $assignment->assigned_at?->format('M d, Y g:i A'),
            ];
        });

        return response()->json([
            'booking_id' => $bookingId,
            'customer' => $booking->user->name,
            'event_type' => $booking->event_type,
            'event_date' => $booking->event_date->format('M d, Y'),
            'assignments' => $assignments,
        ]);
    }

    /**
     * Return inventory items from a booking (complete booking)
     */
    public function returnInventory(Request $request, $bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.assignment_id' => 'required|exists:booking_inventory,id',
            'items.*.quantity_returned' => 'required|numeric|min:0',
            'items.*.quantity_damaged' => 'required|numeric|min:0',
            'items.*.damage_notes' => 'nullable|string|max:500',
        ]);

        foreach ($validated['items'] as $item) {
            $assignment = \App\Models\BookingInventory::findOrFail($item['assignment_id']);
            
            // Validate totals don't exceed assigned
            $totalReturned = $item['quantity_returned'] + $item['quantity_damaged'];
            if ($totalReturned > $assignment->quantity_assigned) {
                return response()->json([
                    'success' => false,
                    'message' => "Return quantity exceeds assigned quantity for {$assignment->inventory->item_name}."
                ], 400);
            }

            // Update the assignment
            $assignment->quantity_returned = $item['quantity_returned'];
            $assignment->quantity_damaged = $item['quantity_damaged'];
            $assignment->damage_notes = $item['damage_notes'] ?? null;
            $assignment->returned_at = now();
            
            // Set status based on return
            if ($assignment->isFullyReturned()) {
                $assignment->status = 'returned';
            } else {
                $assignment->status = 'partially_returned';
            }
            
            $assignment->save();

            // If there's damage, reduce the main inventory stock
            if ($item['quantity_damaged'] > 0) {
                $inventory = $assignment->inventory;
                $inventory->stock = max(0, $inventory->stock - $item['quantity_damaged']);
                $inventory->save();
            }
        }

        // Check if all items are returned
        if ($booking->allInventoryReturned()) {
            // Optionally update booking status to completed
            $booking->status = 'completed';
            $booking->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Inventory returned successfully! Stock has been updated.'
        ]);
    }

    /**
     * Remove inventory assignment from a booking
     */
    public function removeInventoryAssignment($assignmentId)
    {
        $assignment = \App\Models\BookingInventory::findOrFail($assignmentId);
        
        // Only allow removal if not already returned
        if ($assignment->status === 'returned') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot remove a returned assignment.'
            ], 400);
        }

        $assignment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Assignment removed successfully.'
        ]);
    }
}
