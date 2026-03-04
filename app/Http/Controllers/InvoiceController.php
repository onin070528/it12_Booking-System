<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    /**
     * Generate an invoice for a booking (from the payment management page).
     */
    public function generate(Request $request, $bookingId)
    {
        $booking = Booking::with(['user', 'payments'])->where('booking_id', $bookingId)->firstOrFail();

        // Calculate payment totals
        $totalPaid = $booking->payments()
            ->whereIn('status', ['paid', 'partial_payment'])
            ->sum('amount');
        $remainingBalance = $booking->total_amount - $totalPaid;

        // Determine invoice status
        if ($totalPaid <= 0) {
            $invoiceStatus = 'unpaid';
        } elseif ($remainingBalance > 0) {
            $invoiceStatus = 'partially_paid';
        } else {
            $invoiceStatus = 'paid';
        }

        // Create the invoice
        $invoice = Invoice::create([
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'booking_id' => $booking->booking_id,
            'user_id' => $booking->user_id,
            'subtotal' => $booking->total_amount,
            'total_paid' => $totalPaid,
            'remaining_balance' => max($remainingBalance, 0),
            'total_amount' => $booking->total_amount,
            'status' => $invoiceStatus,
            'notes' => $request->input('notes'),
            'issued_at' => now(),
            'due_date' => $request->input('due_date') ? \Carbon\Carbon::parse($request->input('due_date')) : null,
            'generated_by' => Auth::id(),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Invoice generated successfully!',
                'invoice_id' => $invoice->id,
                'invoice_number' => $invoice->invoice_number,
            ]);
        }

        return redirect()->route('admin.invoices.show', $invoice->id)
            ->with('success', 'Invoice generated successfully!');
    }

    /**
     * Show a specific invoice (printable view).
     */
    public function show($id)
    {
        $invoice = Invoice::with(['booking.payments', 'user', 'generator'])->findOrFail($id);

        // Get payment history for this booking
        $paymentHistory = Payment::where('booking_id', $invoice->booking_id)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        if (request()->ajax()) {
            return view('admin.invoices.show', compact('invoice', 'paymentHistory'))->render();
        }

        return view('admin.invoices.print', compact('invoice', 'paymentHistory'));
    }

    /**
     * Get list of invoices for a specific booking (AJAX).
     */
    public function bookingInvoices($bookingId)
    {
        $invoices = Invoice::where('booking_id', $bookingId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'invoices' => $invoices->map(function ($invoice) {
                return [
                    'id' => $invoice->id,
                    'invoice_number' => $invoice->invoice_number,
                    'total_amount' => number_format($invoice->total_amount, 2),
                    'total_paid' => number_format($invoice->total_paid, 2),
                    'remaining_balance' => number_format($invoice->remaining_balance, 2),
                    'status' => $invoice->status,
                    'issued_at' => $invoice->issued_at ? $invoice->issued_at->format('M d, Y') : 'N/A',
                ];
            }),
        ]);
    }
}
