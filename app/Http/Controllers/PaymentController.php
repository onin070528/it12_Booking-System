<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Services\PayMongoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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

        // Check if booking is confirmed
        if ($booking->status !== 'confirmed') {
            return redirect()->route('home')
                ->with('error', 'This booking has not been confirmed yet. Please wait for admin confirmation before making payment.');
        }

        // Check if booking already has a paid downpayment
        $existingPayment = $booking->payments()->where('status', 'paid')->first();
        if ($existingPayment) {
            return redirect()->route('payments.index')
                ->with('error', 'Downpayment for this booking has already been paid.');
        }

        // Calculate 30% downpayment
        $downpaymentAmount = $booking->total_amount * 0.30;

        return view('payments.checkout', compact('booking', 'downpaymentAmount'));
    }

    /**
     * Create payment intent and redirect to payment
     */
    public function processPayment(Request $request, Booking $booking)
    {
        $request->validate([
            'payment_method' => 'required|in:card,gcash,grab_pay,paymaya',
        ]);

        // Verify booking belongs to user
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Check if booking is confirmed
        if ($booking->status !== 'confirmed') {
            return back()->with('error', 'This booking has not been confirmed yet. Please wait for admin confirmation before making payment.');
        }

        // Check if downpayment already paid
        $existingPayment = $booking->payments()->where('status', 'paid')->first();
        if ($existingPayment) {
            return back()->with('error', 'Downpayment for this booking has already been paid.');
        }

        $paymentMethod = $request->payment_method;

        // Calculate 30% downpayment
        $downpaymentAmount = $booking->total_amount * 0.30;

        // Create payment record for downpayment
        $payment = Payment::create([
            'booking_id' => $booking->id,
            'user_id' => Auth::id(),
            'amount' => $downpaymentAmount,
            'currency' => 'PHP',
            'status' => 'pending',
            'payment_method' => $paymentMethod,
            'description' => "30% Downpayment for {$booking->event_type} booking",
        ]);

        $metadata = [
            'booking_id' => $booking->id,
            'payment_id' => $payment->id,
            'user_id' => Auth::id(),
        ];

        // Check if PayMongo is configured
        if (empty(config('paymongo.secret_key')) || empty(config('paymongo.public_key'))) {
            $payment->update(['status' => 'failed']);
            return back()->with('error', 'Payment gateway is not configured. Please contact support.');
        }

        if (in_array($paymentMethod, ['gcash', 'grab_pay', 'paymaya'])) {
            // For e-wallet payments, create a source
            $sourceResponse = $this->payMongoService->createSource(
                $downpaymentAmount,
                'PHP',
                $paymentMethod,
                $metadata
            );

            if ($sourceResponse && isset($sourceResponse['data'])) {
                $source = $sourceResponse['data'];
                $payment->update([
                    'paymongo_source_id' => $source['id'],
                    'paymongo_response' => $sourceResponse,
                ]);

                // Redirect to payment URL
                return redirect($source['attributes']['redirect']['checkout_url']);
            }
        } else {
            // For card payments, create payment intent
            $intentResponse = $this->payMongoService->createPaymentIntent(
                $downpaymentAmount,
                'PHP',
                $metadata
            );

            if ($intentResponse && isset($intentResponse['data'])) {
                $intent = $intentResponse['data'];
                $payment->update([
                    'paymongo_payment_intent_id' => $intent['id'],
                    'paymongo_response' => $intentResponse,
                ]);

                return view('payments.card-checkout', [
                    'payment' => $payment,
                    'paymentIntent' => $intent,
                    'publicKey' => config('paymongo.public_key'),
                ]);
            }
        }

        $payment->update(['status' => 'failed']);
        return back()->with('error', 'Failed to initialize payment. Please try again or contact support.');
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

        return view('payments.index', compact('payments'));
    }
}
