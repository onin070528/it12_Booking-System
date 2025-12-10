<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    /**
     * Store a newly created booking.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'event_type' => 'required|string',
                'date' => 'required|date',
                'time' => 'required|date_format:H:i',
                'location' => 'required|string',
                'theme' => 'required|string',
                'request' => 'nullable|string',
                'total_amount' => 'required|numeric|min:0',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed. Please check your input.',
                'errors' => $e->errors(),
            ], 422);
        }

        // Check if the selected date already has 2 confirmed bookings
        $selectedDate = $request->input('date');
        $confirmedBookingsCount = Booking::where('status', 'confirmed')
            ->whereDate('event_date', $selectedDate)
            ->count();
        
        if ($confirmedBookingsCount >= 2) {
            return response()->json([
                'success' => false,
                'message' => 'This date is fully booked. Maximum 2 bookings per day allowed. Please select another date.',
                'errors' => ['date' => ['This date is fully booked.']],
            ], 422);
        }

        // Collect event-specific details based on event type
        $eventDetails = [];
        $eventType = $request->input('event_type');

        try {
            if ($eventType === 'wedding') {
                $request->validate([
                    'wedding_bride' => 'required|string',
                    'wedding_groom' => 'required|string',
                    'wedding_guests' => 'required|integer|min:1',
                    'wedding_ceremony' => 'required|string',
                    'wedding_reception' => 'required|string',
                    'wedding_theme' => 'required|string',
                ]);
                $eventDetails = [
                    'bride' => $request->input('wedding_bride'),
                    'groom' => $request->input('wedding_groom'),
                    'guests' => $request->input('wedding_guests'),
                    'ceremony_venue' => $request->input('wedding_ceremony'),
                    'reception_venue' => $request->input('wedding_reception'),
                    'theme' => $request->input('wedding_theme'),
                    'notes' => $request->input('wedding_notes'),
                ];
            } elseif ($eventType === 'birthday') {
                $request->validate([
                    'birthday_celebrant' => 'required|string',
                    'birthday_age' => 'required|integer|min:1',
                    'birthday_venue' => 'required|string',
                    'birthday_guests' => 'required|integer|min:1',
                    'birthday_theme' => 'required|string',
                ]);
                $eventDetails = [
                    'celebrant' => $request->input('birthday_celebrant'),
                    'age' => $request->input('birthday_age'),
                    'venue' => $request->input('birthday_venue'),
                    'guests' => $request->input('birthday_guests'),
                    'theme' => $request->input('birthday_theme'),
                ];
            } elseif ($eventType === 'debut') {
                $request->validate([
                    'debut_name' => 'required|string',
                    'debut_venue' => 'required|string',
                    'debut_guests' => 'required|integer|min:1',
                    'debut_theme' => 'required|string',
                    'debut_roses' => 'required|string',
                    'debut_candles' => 'required|string',
                    'debut_treasures' => 'required|string',
                ]);
                $eventDetails = [
                    'debutante' => $request->input('debut_name'),
                    'venue' => $request->input('debut_venue'),
                    'guests' => $request->input('debut_guests'),
                    'theme' => $request->input('debut_theme'),
                    'roses' => $request->input('debut_roses'),
                    'candles' => $request->input('debut_candles'),
                    'treasures' => $request->input('debut_treasures'),
                    'notes' => $request->input('debut_notes'),
                ];
            } elseif ($eventType === 'corporate') {
                $request->validate([
                    'corporate_company' => 'required|string',
                    'corporate_title' => 'required|string',
                    'corporate_venue' => 'required|string',
                    'corporate_attendees' => 'required|integer|min:1',
                    'corporate_representative' => 'required|string',
                    'corporate_contact' => 'required|string',
                    'corporate_requirements' => 'required|string',
                ]);
                $eventDetails = [
                    'company' => $request->input('corporate_company'),
                    'title' => $request->input('corporate_title'),
                    'theme' => $request->input('corporate_title'), // Use title as theme for corporate
                    'venue' => $request->input('corporate_venue'),
                    'attendees' => $request->input('corporate_attendees'),
                    'representative' => $request->input('corporate_representative'),
                    'contact' => $request->input('corporate_contact'),
                    'requirements' => $request->input('corporate_requirements'),
                ];
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid event type selected.',
                ], 422);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed. Please check your input.',
                'errors' => $e->errors(),
            ], 422);
        }

        try {
            // Create the booking
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'event_type' => $eventType,
                'event_date' => $request->input('date'),
                'event_time' => $request->input('time'),
                'location' => $request->input('location'),
                'description' => $request->input('request'),
                'total_amount' => $request->input('total_amount'),
                'status' => 'pending',
                'event_details' => $eventDetails,
            ]);

            // Send notification to all admins
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'type' => 'booking_created',
                    'notifiable_type' => Booking::class,
                    'notifiable_id' => $booking->id,
                    'message' => "New booking submitted by " . Auth::user()->name . " for {$eventType} event on " . $booking->event_date->format('F d, Y'),
                    'read' => false,
                    'data' => [
                        'booking_id' => $booking->id,
                        'customer_name' => Auth::user()->name,
                        'event_type' => $eventType,
                    ],
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Booking submitted successfully! The admin will review your booking and contact you soon.',
                'booking_id' => $booking->id,
            ]);
        } catch (\Exception $e) {
            \Log::error('Booking creation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the booking. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Display a listing of bookings for admin.
     */
    public function index()
    {
        $bookings = Booking::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * Display user's bookings.
     */
    public function userBookings()
    {
        $bookings = Booking::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('home', compact('bookings'));
    }

    /**
     * Show the form for editing the specified booking.
     */
    public function show(Booking $booking)
    {
        $booking->load('user');
        
        // If it's an AJAX request, return the view content
        if (request()->ajax()) {
            return view('admin.bookings.show', compact('booking'))->render();
        }
        
        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Update the booking schedule and confirm it.
     */
    public function updateSchedule(Request $request, Booking $booking)
    {
        $request->validate([
            'meetup_date' => 'required|date',
            'meetup_time' => 'required|date_format:H:i',
        ]);

        $booking->update([
            'meetup_date' => $request->input('meetup_date'),
            'meetup_time' => $request->input('meetup_time'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Schedule updated successfully.',
        ]);
    }

    /**
     * Confirm the booking.
     */
    public function confirm(Request $request, Booking $booking)
    {
        // Confirm booking without requiring meetup schedule
        $booking->update([
            'status' => 'confirmed',
        ]);

        // Send notification to customer asking them to choose communication method
        Notification::create([
            'user_id' => $booking->user_id,
            'type' => 'booking_confirmed',
            'notifiable_type' => Booking::class,
            'notifiable_id' => $booking->id,
            'message' => "Your booking for {$booking->event_type} event has been confirmed! Please choose how you'd like to proceed: schedule a meetup or continue via messaging.",
            'read' => false,
            'data' => [
                'booking_id' => $booking->id,
            ],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking confirmed successfully. Customer will be notified to choose their preferred communication method.',
        ]);
    }

    /**
     * Handle user's choice of communication method (meetup or messaging).
     */
    public function chooseCommunicationMethod(Request $request, Booking $booking)
    {
        // Verify booking belongs to authenticated user
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Verify booking is confirmed
        if ($booking->status !== 'confirmed') {
            return response()->json([
                'success' => false,
                'message' => 'Booking must be confirmed first.',
            ], 422);
        }

        $request->validate([
            'communication_method' => 'required|in:meetup,messaging',
            'meetup_date' => 'required_if:communication_method,meetup|nullable|date',
            'meetup_time' => 'required_if:communication_method,meetup|nullable|date_format:H:i',
        ]);

        $updateData = [
            'communication_method' => $request->input('communication_method'),
        ];

        if ($request->input('communication_method') === 'meetup') {
            $updateData['meetup_date'] = $request->input('meetup_date');
            $updateData['meetup_time'] = $request->input('meetup_time');
        } else {
            // For messaging, clear meetup schedule if it was set
            $updateData['meetup_date'] = null;
            $updateData['meetup_time'] = null;
        }

        $booking->update($updateData);

        // Send notification to admin
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            if ($request->input('communication_method') === 'meetup') {
                $message = "Customer {$booking->user->name} has chosen to schedule a meetup for booking #{$booking->id}. Meetup scheduled on " . 
                          \Carbon\Carbon::parse($request->input('meetup_date'))->format('F d, Y') . 
                          " at " . date('g:i A', strtotime($request->input('meetup_time'))) . ".";
            } else {
                $message = "Customer {$booking->user->name} has chosen to continue via messaging for booking #{$booking->id}. You can communicate through the Messages section.";
            }
            
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'booking_communication_chosen',
                'notifiable_type' => Booking::class,
                'notifiable_id' => $booking->id,
                'message' => $message,
                'read' => false,
                'data' => [
                    'booking_id' => $booking->id,
                    'communication_method' => $request->input('communication_method'),
                ],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => $request->input('communication_method') === 'meetup' 
                ? 'Meetup schedule saved successfully!' 
                : 'You can now communicate with admin through the Messages section.',
        ]);
    }

    /**
     * Get count of incomplete bookings (not completed).
     */
    public function getIncompleteCount()
    {
        $count = Booking::where('status', '!=', 'completed')
            ->count();
        
        return response()->json(['count' => $count]);
    }

    /**
     * Mark booking as ready for payment and notify user.
     */
    public function markForPayment(Request $request, Booking $booking)
    {
        // Check if booking is already paid
        $existingPayment = $booking->payments()->where('status', 'paid')->first();
        if ($existingPayment) {
            return response()->json([
                'success' => false,
                'message' => 'This booking already has a paid downpayment.',
            ], 400);
        }

        // Update booking status to pending_payment
        $booking->update([
            'status' => 'pending_payment',
        ]);

        // Send notification to customer
        Notification::create([
            'user_id' => $booking->user_id,
            'type' => 'booking_ready_for_payment',
            'notifiable_type' => Booking::class,
            'notifiable_id' => $booking->id,
            'message' => "Your booking for {$booking->event_type} event on {$booking->event_date->format('F d, Y')} is ready for payment. Please proceed to make your downpayment.",
            'read' => false,
            'data' => [
                'booking_id' => $booking->id,
                'amount' => $booking->total_amount,
            ],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking status updated to pending payment. Customer has been notified.',
        ]);
    }

    /**
     * Mark payment as partial_paid (admin manually confirms payment received).
     */
    public function markPaymentAsPartialPaid(Request $request, Booking $booking)
    {
        // Find the pending payment for this booking
        $payment = $booking->payments()
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'No pending payment found for this booking.',
            ], 404);
        }

        // Update payment status to partial_paid
        $payment->update([
            'status' => 'partial_paid',
            'paid_at' => now(),
        ]);

        // Update booking status to partial_paid
        $booking->update([
            'status' => 'partial_paid',
        ]);

        // Send notification to customer
        Notification::create([
            'user_id' => $booking->user_id,
            'type' => 'payment_partial_received',
            'notifiable_type' => Booking::class,
            'notifiable_id' => $booking->id,
            'message' => "Your partial payment of ₱" . number_format($payment->amount, 2) . " for booking #{$booking->id} has been received. Remaining balance: ₱" . number_format($booking->total_amount - $payment->amount, 2) . ".",
            'read' => false,
            'data' => [
                'booking_id' => $booking->id,
                'payment_id' => $payment->id,
                'paid_amount' => $payment->amount,
                'remaining_amount' => $booking->total_amount - $payment->amount,
            ],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Payment marked as partial paid. Booking status updated and customer notified.',
        ]);
    }

    /**
     * Mark payment as paid (admin manually confirms full payment received).
     */
    public function markPaymentAsPaid(Request $request, Booking $booking)
    {
        // Find the partial_paid payment for this booking (or pending if no partial_paid exists)
        $payment = $booking->payments()
            ->whereIn('status', ['partial_paid', 'pending'])
            ->orderByRaw("CASE WHEN status = 'partial_paid' THEN 0 ELSE 1 END")
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'No partial_paid or pending payment found for this booking.',
            ], 404);
        }

        // Update payment status to paid
        $payment->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        // Refresh the booking relationship to get updated payments
        $booking->refresh();

        // Calculate total paid - include all paid payments and any remaining partial_paid payments
        $totalPaid = $booking->payments()
            ->whereIn('status', ['paid', 'partial_paid'])
            ->sum('amount');
        $remainingBalance = $booking->total_amount - $totalPaid;

        // If fully paid, update booking status to in_design
        if ($remainingBalance <= 0) {
            $booking->update([
                'status' => 'in_design',
            ]);

            // Send notification to customer
            Notification::create([
                'user_id' => $booking->user_id,
                'type' => 'payment_full_received',
                'notifiable_type' => Booking::class,
                'notifiable_id' => $booking->id,
                'message' => "Your payment of ₱" . number_format($payment->amount, 2) . " for booking #{$booking->id} has been received. Your booking is now fully paid and has moved to the design phase!",
                'read' => false,
                'data' => [
                    'booking_id' => $booking->id,
                    'payment_id' => $payment->id,
                    'status' => 'in_design',
                ],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment marked as paid. Booking is fully paid and moved to design phase. Customer has been notified.',
            ]);
        } else {
            // Still has remaining balance, update to partial_paid
            $booking->update([
                'status' => 'partial_paid',
            ]);

            // Send notification to customer
            Notification::create([
                'user_id' => $booking->user_id,
                'type' => 'payment_received',
                'notifiable_type' => Booking::class,
                'notifiable_id' => $booking->id,
                'message' => "Your payment of ₱" . number_format($payment->amount, 2) . " for booking #{$booking->id} has been received. Remaining balance: ₱" . number_format($remainingBalance, 2) . ".",
                'read' => false,
                'data' => [
                    'booking_id' => $booking->id,
                    'payment_id' => $payment->id,
                    'paid_amount' => $payment->amount,
                    'remaining_amount' => $remainingBalance,
                ],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment marked as paid. Remaining balance: ₱' . number_format($remainingBalance, 2) . '. Customer has been notified.',
            ]);
        }
    }

    /**
     * Mark booking as in_design (when fully paid).
     */
    public function markAsInDesign(Request $request, Booking $booking)
    {
        // Check if booking is fully paid
        $totalPaid = $booking->payments()->whereIn('status', ['paid', 'partial_paid'])->sum('amount');
        $remainingBalance = $booking->total_amount - $totalPaid;

        if ($remainingBalance > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Booking must be fully paid before moving to design phase. Remaining balance: ₱' . number_format($remainingBalance, 2),
            ], 400);
        }

        // Check if booking status allows this transition
        if (!in_array($booking->status, ['partial_paid', 'pending_payment'])) {
            return response()->json([
                'success' => false,
                'message' => 'Booking status must be partial_paid or pending_payment to move to design phase.',
            ], 400);
        }

        // Update booking status to in_design
        $booking->update([
            'status' => 'in_design',
        ]);

        // Send notification to customer
        Notification::create([
            'user_id' => $booking->user_id,
            'type' => 'booking_in_design',
            'notifiable_type' => Booking::class,
            'notifiable_id' => $booking->id,
            'message' => "Great news! Your booking #{$booking->id} for {$booking->event_type} event is now in the design phase. Our team is working on your event design.",
            'read' => false,
            'data' => [
                'booking_id' => $booking->id,
                'status' => 'in_design',
            ],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking status updated to In Design. Customer has been notified.',
        ]);
    }

    /**
     * Mark booking as completed (event successful).
     */
    public function markAsCompleted(Request $request, Booking $booking)
    {
        // Check if booking status allows this transition
        if (!in_array($booking->status, ['in_design', 'partial_paid', 'pending_payment'])) {
            return response()->json([
                'success' => false,
                'message' => 'Booking must be in design phase or have payments to mark as completed.',
            ], 400);
        }

        // Update booking status to completed
        $booking->update([
            'status' => 'completed',
        ]);

        // Send notification to customer
        Notification::create([
            'user_id' => $booking->user_id,
            'type' => 'booking_completed',
            'notifiable_type' => Booking::class,
            'notifiable_id' => $booking->id,
            'message' => "Congratulations! Your {$booking->event_type} event on {$booking->event_date->format('F d, Y')} has been marked as successfully completed. Thank you for choosing us!",
            'read' => false,
            'data' => [
                'booking_id' => $booking->id,
                'status' => 'completed',
            ],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking marked as completed. Customer has been notified.',
        ]);
    }
}
