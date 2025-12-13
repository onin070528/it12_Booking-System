<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Notification;
use App\Models\User;
use App\Services\PayMongoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    protected PayMongoService $payMongoService;

    public function __construct(PayMongoService $payMongoService)
    {
        $this->payMongoService = $payMongoService;
    }

    /**
     * Show checkout page
     */
    public function checkout(Request $request, Booking $booking)
    {
        // Verify booking belongs to user
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Check if booking is confirmed, approved, pending payment, or partial_payment
        if (!in_array($booking->status, ['confirmed', 'approved', 'pending_payment', 'partial_payment'])) {
            return redirect()->route('home')
                ->with('error', 'This booking is not ready for payment yet. Please wait for admin confirmation.');
        }

        // Calculate total paid and remaining balance
        $totalPaid = $booking->payments()->whereIn('status', ['paid', 'partial_payment'])->sum('amount');
        $remainingBalance = $booking->total_amount - $totalPaid;

        // If partial_payment, allow paying remaining balance
        if ($booking->status === 'partial_payment') {
            if ($remainingBalance <= 0) {
                return redirect()->route('payments.index')
                    ->with('error', 'This booking is already fully paid.');
            }
            $amountToPay = $remainingBalance;
            $isRemainingBalance = true;
        } else {
            // Check if booking already has a paid downpayment
            $existingPayment = $booking->payments()->whereIn('status', ['paid', 'partial_payment'])->first();
            if ($existingPayment) {
                return redirect()->route('payments.index')
                    ->with('error', 'Downpayment for this booking has already been paid.');
            }
            // Calculate 30% downpayment
            $amountToPay = $booking->total_amount * 0.30;
            $isRemainingBalance = false;
        }

        // Calculate days until event for display
        $eventDate = \Carbon\Carbon::parse($booking->event_date);
        $daysUntilEvent = now()->diffInDays($eventDate, false);

        return view('payments.checkout', compact('booking', 'amountToPay', 'isRemainingBalance', 'totalPaid', 'remainingBalance', 'daysUntilEvent'));
    }

    /**
     * Create payment intent and redirect to payment
     */
    public function processPayment(Request $request, Booking $booking)
    {
        $request->validate([
            'payment_method' => 'required|in:gcash,paymaya,cash',
            'reference_number' => 'required_if:payment_method,paymaya,gcash|nullable|string|max:255',
            'payment_screenshot' => 'required_if:payment_method,paymaya,gcash|nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        // Verify booking belongs to user
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Check if booking is confirmed, approved, pending payment, or partial_payment
        if (!in_array($booking->status, ['confirmed', 'approved', 'pending_payment', 'partial_payment'])) {
            return back()->with('error', 'This booking is not ready for payment yet. Please wait for admin confirmation.');
        }

        // Validate payment timing - must be at least 2 weeks before event
        $eventDate = \Carbon\Carbon::parse($booking->event_date);
        $daysUntilEvent = now()->diffInDays($eventDate, false);
        
        if ($daysUntilEvent < 14) {
            return back()->with('error', 'Full payment must be made at least 2 weeks before the event date. Your event is in ' . $daysUntilEvent . ' day(s).');
        }

        $paymentMethod = $request->payment_method;

        // Calculate amount to pay
        $totalPaid = $booking->payments()->whereIn('status', ['paid', 'partial_payment'])->sum('amount');
        $remainingBalance = $booking->total_amount - $totalPaid;

        if ($booking->status === 'partial_payment') {
            if ($remainingBalance <= 0) {
                return back()->with('error', 'This booking is already fully paid.');
            }
            $paymentAmount = $remainingBalance;
            $isRemainingBalance = true;
        } else {
            // Check if downpayment already paid
            $existingPayment = $booking->payments()->whereIn('status', ['paid', 'partial_payment'])->first();
            if ($existingPayment) {
                return back()->with('error', 'Downpayment for this booking has already been paid.');
            }
            // Calculate 30% downpayment
            $paymentAmount = $booking->total_amount * 0.30;
            $isRemainingBalance = false;
        }

        // Handle cash payments (no reference number or screenshot needed)
        if ($paymentMethod === 'cash') {
            $description = $isRemainingBalance 
                ? "Remaining Balance (Cash) for {$booking->event_type} booking"
                : "30% Downpayment (Cash) for {$booking->event_type} booking";
            
            // Create payment record
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'user_id' => Auth::id(),
                'amount' => $paymentAmount,
                'currency' => 'PHP',
                'status' => 'pending',
                'payment_method' => 'cash',
                'description' => $description,
            ]);

            // Notify all admins about the payment
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'type' => 'payment_submitted',
                    'notifiable_type' => Payment::class,
                    'notifiable_id' => $payment->id,
                    'message' => Auth::user()->name . " has submitted a cash payment of ₱" . number_format($paymentAmount, 2) . " for booking #{$booking->id} ({$booking->event_type}).",
                    'read' => false,
                    'data' => [
                        'booking_id' => $booking->id,
                        'payment_id' => $payment->id,
                        'customer_name' => Auth::user()->name,
                        'amount' => $paymentAmount,
                        'payment_method' => 'cash',
                    ],
                ]);
            }

            $successMessage = $isRemainingBalance
                ? 'Cash payment has been recorded. Please bring the remaining balance amount (₱' . number_format($paymentAmount, 2) . ') during your meetup or event.'
                : 'Cash payment has been recorded. Please bring the downpayment amount (₱' . number_format($paymentAmount, 2) . ') during your meetup or event.';

            return redirect()->route('payments.index')
                ->with('success', $successMessage);
        }

        // Handle PayMaya and GCash payments (require reference number and screenshot)
        if (in_array($paymentMethod, ['paymaya', 'gcash'])) {
            $referenceNumber = $request->input('reference_number');
            $screenshot = $request->file('payment_screenshot');
            
            if (!$referenceNumber || !$screenshot) {
                return back()->with('error', 'Reference number and payment screenshot are required for ' . ucfirst($paymentMethod) . ' payments.');
            }

            // Store screenshot
            $screenshotPath = $screenshot->store('payment-proofs', 'public');
            
            $description = $isRemainingBalance 
                ? "Remaining Balance ({$paymentMethod}) for {$booking->event_type} booking"
                : "30% Downpayment ({$paymentMethod}) for {$booking->event_type} booking";
            
            // Create payment record
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'user_id' => Auth::id(),
                'amount' => $paymentAmount,
                'currency' => 'PHP',
                'status' => 'pending',
                'payment_method' => $paymentMethod,
                'description' => $description,
                'reference_number' => $referenceNumber,
                'payment_screenshot' => $screenshotPath,
            ]);

            // Notify all admins about the payment
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'type' => 'payment_submitted',
                    'notifiable_type' => Payment::class,
                    'notifiable_id' => $payment->id,
                    'message' => Auth::user()->name . " has submitted a " . ucfirst($paymentMethod) . " payment of ₱" . number_format($paymentAmount, 2) . " for booking #{$booking->id} ({$booking->event_type}). Reference: {$referenceNumber}",
                    'read' => false,
                    'data' => [
                        'booking_id' => $booking->id,
                        'payment_id' => $payment->id,
                        'customer_name' => Auth::user()->name,
                        'amount' => $paymentAmount,
                        'payment_method' => $paymentMethod,
                        'reference_number' => $referenceNumber,
                    ],
                ]);
            }

            $successMessage = $isRemainingBalance
                ? ucfirst($paymentMethod) . ' payment has been submitted. Your payment is pending admin confirmation. Reference: ' . $referenceNumber
                : ucfirst($paymentMethod) . ' payment has been submitted. Your payment is pending admin confirmation. Reference: ' . $referenceNumber;

            return redirect()->route('payments.index')
                ->with('success', $successMessage);
        }

        // This section should not be reached as all payment methods are handled above
        // Cash, PayMaya, and GCash are all handled in their respective sections
        return back()->with('error', 'Invalid payment method selected.');
    }


    /**
     * Handle payment return (for redirect-based payments)
     */
    public function paymentReturn(Request $request)
    {
        $sourceId = $request->query('source_id');
        
        if (!$sourceId) {
            return redirect()->route('payments.index')
                ->with('error', 'Invalid payment source.');
        }

        $payment = Payment::where('paymongo_source_id', $sourceId)->first();

        if (!$payment) {
            return redirect()->route('payments.index')
                ->with('error', 'Payment not found.');
        }

        // Retrieve source status from PayMongo
        $sourceResponse = $this->payMongoService->retrievePaymentIntent($sourceId);

        if ($sourceResponse && isset($sourceResponse['data'])) {
            $source = $sourceResponse['data'];
            $status = $source['attributes']['status'];

            if ($status === 'paid') {
                $payment->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                    'paymongo_response' => $sourceResponse,
                ]);

                return redirect()->route('payment.success');
            } elseif ($status === 'failed') {
                $payment->update([
                    'status' => 'failed',
                    'paymongo_response' => $sourceResponse,
                ]);

                return redirect()->route('payment.failed');
            }
        }

        return redirect()->route('payments.index')
            ->with('error', 'Payment status could not be determined.');
    }

    /**
     * Payment success page
     */
    public function success()
    {
        return view('payments.success');
    }

    /**
     * Payment failed page
     */
    public function failed()
    {
        return view('payments.failed');
    }

    /**
     * Handle PayMongo webhook
     */
    public function webhook(Request $request)
    {
        $signature = $request->header('Paymongo-Signature');
        $payload = $request->getContent();

        // Verify webhook signature
        if (!$this->payMongoService->verifyWebhookSignature($signature, $payload)) {
            Log::warning('PayMongo webhook signature verification failed');
            abort(401, 'Invalid signature');
        }

        $data = json_decode($payload, true);

        if (!isset($data['data'])) {
            return response()->json(['error' => 'Invalid webhook data'], 400);
        }

        $event = $data['data'];
        $type = $event['type'] ?? null;

        Log::info('PayMongo webhook received', ['type' => $type, 'data' => $event]);

        // Handle payment.paid event
        if ($type === 'payment.paid') {
            $attributes = $event['attributes'] ?? [];
            $paymentIntentId = $attributes['data']['attributes']['payment_intent']['id'] ?? null;

            if ($paymentIntentId) {
                $payment = Payment::where('paymongo_payment_intent_id', $paymentIntentId)->first();

                if ($payment && $payment->status === 'pending') {
                    $payment->update([
                        'status' => 'paid',
                        'paid_at' => now(),
                        'paymongo_response' => $data,
                    ]);

                    // Optionally update booking status
                    $booking = $payment->booking;
                    if ($booking && $booking->status === 'pending') {
                        $booking->update(['status' => 'approved']);
                    }
                }
            }
        }

        return response()->json(['received' => true]);
    }

    /**
     * Show payment history
     */
    public function index()
    {
        $payments = Payment::where('user_id', Auth::id())
            ->with('booking')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

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

        return view('payments.index', compact('payments', 'bookingBalances'));
    }
}
