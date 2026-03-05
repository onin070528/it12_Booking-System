<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->invoice_number }} - RJ's Event Styling</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f3f4f6; color: #333; }
        
        .no-print { }
        @media print {
            body { background: white; }
            .no-print { display: none !important; }
            .invoice-container { box-shadow: none !important; margin: 0 !important; max-width: 100% !important; }
        }

        .invoice-container {
            max-width: 800px;
            margin: 30px auto;
            background: white;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            border-radius: 12px;
            overflow: hidden;
        }

        .invoice-header {
            background: linear-gradient(135deg, #93BFC7, #7aa8b0);
            color: white;
            padding: 30px 40px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .invoice-header .brand h1 { font-size: 28px; font-weight: 700; }
        .invoice-header .brand p { font-size: 14px; opacity: 0.9; margin-top: 4px; }
        .invoice-header .invoice-title { text-align: right; }
        .invoice-header .invoice-title h2 { font-size: 32px; font-weight: 700; letter-spacing: 2px; }
        .invoice-header .invoice-title .invoice-num { font-size: 16px; opacity: 0.9; margin-top: 4px; font-family: monospace; }

        .invoice-body { padding: 30px 40px; }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            gap: 20px;
        }

        .info-block label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #93BFC7;
            margin-bottom: 4px;
        }
        .info-block p { font-size: 15px; color: #333; }
        .info-block p.name { font-weight: 600; font-size: 17px; }

        .status-badge {
            display: inline-block;
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }
        .status-paid { background: #d1fae5; color: #065f46; }
        .status-partial { background: #fef3c7; color: #92400e; }
        .status-unpaid { background: #fee2e2; color: #991b1b; }

        .service-table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        .service-table thead th {
            background: #93BFC7;
            color: white;
            padding: 12px 16px;
            text-align: left;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .service-table thead th:last-child { text-align: right; }
        .service-table tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
        }
        .service-table tbody td:last-child { text-align: right; font-weight: 600; }

        .totals-section {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 30px;
        }
        .totals-table { width: 280px; }
        .totals-table .row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 14px;
            color: #555;
        }
        .totals-table .row.total {
            border-top: 3px solid #93BFC7;
            padding-top: 12px;
            margin-top: 4px;
            font-size: 18px;
            font-weight: 700;
        }
        .totals-table .row.total.due { color: #ea580c; }
        .totals-table .row.total.fully-paid { color: #16a34a; }

        .payment-history { margin-bottom: 30px; }
        .payment-history h3 {
            font-size: 16px;
            font-weight: 700;
            color: #93BFC7;
            margin-bottom: 12px;
        }
        .payment-history table { width: 100%; border-collapse: collapse; }
        .payment-history table th {
            background: #f3f4f6;
            padding: 10px 12px;
            text-align: left;
            font-size: 12px;
            font-weight: 600;
            color: #93BFC7;
            text-transform: uppercase;
        }
        .payment-history table th:nth-child(4) { text-align: right; }
        .payment-history table td {
            padding: 10px 12px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 13px;
        }
        .payment-history table td:nth-child(4) { text-align: right; font-weight: 600; }

        .notes-section {
            background: #f9fafb;
            border-left: 4px solid #93BFC7;
            padding: 16px 20px;
            margin-bottom: 30px;
            border-radius: 0 8px 8px 0;
        }
        .notes-section h3 { font-size: 14px; font-weight: 600; color: #93BFC7; margin-bottom: 6px; }
        .notes-section p { font-size: 14px; color: #555; }

        .invoice-footer {
            text-align: center;
            padding: 20px 40px 30px;
            border-top: 2px solid #e5e7eb;
            color: #9ca3af;
            font-size: 13px;
        }
        .invoice-footer p { margin-top: 4px; }

        .action-bar {
            max-width: 800px;
            margin: 20px auto;
            display: flex;
            gap: 12px;
            justify-content: center;
        }
        .action-bar button, .action-bar a {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 24px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            border: none;
            transition: all 0.2s;
        }
        .btn-print { background: #93BFC7; color: white; }
        .btn-print:hover { background: #7aa8b0; }
        .btn-pdf { background: #dc2626; color: white; }
        .btn-pdf:hover { background: #b91c1c; }
        .btn-back { background: #6b7280; color: white; }
        .btn-back:hover { background: #4b5563; }
    </style>
</head>
<body>

    {{-- Action Buttons --}}
    <div class="action-bar no-print">
        <a href="{{ route('admin.payments.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Back to Payments
        </a>
        <button onclick="window.print()" class="btn-print">
            <i class="fas fa-print"></i> Print Invoice
        </button>
        <button onclick="downloadInvoicePDF()" class="btn-pdf">
            <i class="fas fa-file-pdf"></i> Download PDF
        </button>
    </div>

    {{-- Invoice --}}
    <div class="invoice-container" id="invoicePrintArea">
        {{-- Header --}}
        <div class="invoice-header">
            <div class="brand">
                <h1>RJ's Event Styling</h1>
                <p>Event Styling & Decoration Services</p>
            </div>
            <div class="invoice-title">
                <h2>INVOICE</h2>
                <div class="invoice-num">{{ $invoice->invoice_number }}</div>
            </div>
        </div>

        {{-- Body --}}
        <div class="invoice-body">
            {{-- Bill To / Invoice Info --}}
            <div class="info-row">
                <div class="info-block">
                    <label>Billed To</label>
                    <p class="name">{{ $invoice->user->name ?? 'N/A' }}</p>
                    <p>{{ $invoice->user->email ?? '' }}</p>
                    @if($invoice->user->phone ?? false)
                        <p>{{ $invoice->user->phone }}</p>
                    @endif
                </div>
                <div class="info-block" style="text-align: right;">
                    <label>Invoice Date</label>
                    <p>{{ $invoice->issued_at ? $invoice->issued_at->format('F d, Y') : now()->format('F d, Y') }}</p>

                    @if($invoice->due_date)
                        <label style="margin-top: 12px;">Due Date</label>
                        <p>{{ $invoice->due_date->format('F d, Y') }}</p>
                    @endif

                    <label style="margin-top: 12px;">Status</label>
                    <p>
                        @if($invoice->status === 'paid')
                            <span class="status-badge status-paid">PAID</span>
                        @elseif($invoice->status === 'partially_paid')
                            <span class="status-badge status-partial">PARTIALLY PAID</span>
                        @else
                            <span class="status-badge status-unpaid">UNPAID</span>
                        @endif
                    </p>
                </div>
            </div>

            {{-- Service Table --}}
            <table class="service-table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Event Date</th>
                        <th>Location</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <strong style="text-transform: capitalize;">{{ $invoice->booking->event_type ?? 'Event Styling Service' }}</strong>
                            @if($invoice->booking->description)
                                <br><span style="color: #6b7280; font-size: 13px;">{{ $invoice->booking->description }}</span>
                            @endif
                        </td>
                        <td>
                            {{ $invoice->booking->event_date ? $invoice->booking->event_date->format('M d, Y') : 'TBD' }}
                            @if($invoice->booking->event_time)
                                <br><span style="color: #6b7280; font-size: 13px;">{{ date('g:i A', strtotime($invoice->booking->event_time)) }}</span>
                            @endif
                        </td>
                        <td>{{ $invoice->booking->location ?? 'TBD' }}</td>
                        <td>₱{{ number_format($invoice->total_amount, 2) }}</td>
                    </tr>
                </tbody>
            </table>

            {{-- Totals --}}
            <div class="totals-section">
                <div class="totals-table">
                    <div class="row">
                        <span>Subtotal:</span>
                        <span>₱{{ number_format($invoice->subtotal, 2) }}</span>
                    </div>
                    <div class="row" style="color: #16a34a;">
                        <span>Total Paid:</span>
                        <span>₱{{ number_format($invoice->total_paid, 2) }}</span>
                    </div>
                    <div class="row total {{ $invoice->remaining_balance > 0 ? 'due' : 'fully-paid' }}">
                        <span>Balance Due:</span>
                        <span>₱{{ number_format($invoice->remaining_balance, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- Payment History --}}
            @if($paymentHistory->count() > 0)
            <div class="payment-history">
                <h3><i class="fas fa-history" style="margin-right: 6px;"></i> Payment History</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Method</th>
                            <th>Reference</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($paymentHistory as $pmt)
                        <tr>
                            <td>{{ $pmt->created_at->format('M d, Y') }}</td>
                            <td style="text-transform: capitalize;">{{ $pmt->payment_method ?? 'N/A' }}</td>
                            <td>
                                @if($pmt->reference_number)
                                    <span style="font-family: monospace; background: #f3f4f6; padding: 2px 8px; border-radius: 4px; font-size: 12px;">{{ $pmt->reference_number }}</span>
                                @else
                                    <span style="color: #9ca3af;">-</span>
                                @endif
                            </td>
                            <td>₱{{ number_format($pmt->amount, 2) }}</td>
                            <td>
                                @if($pmt->status === 'paid')
                                    <span class="status-badge status-paid" style="font-size: 11px; padding: 2px 10px;">Paid</span>
                                @elseif($pmt->status === 'partial_payment')
                                    <span class="status-badge status-partial" style="font-size: 11px; padding: 2px 10px;">Partial</span>
                                @elseif($pmt->status === 'pending')
                                    <span style="background: #f3f4f6; color: #6b7280; padding: 2px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;">Pending</span>
                                @else
                                    <span style="background: #fee2e2; color: #991b1b; padding: 2px 10px; border-radius: 20px; font-size: 11px; font-weight: 600;">{{ ucfirst($pmt->status) }}</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            {{-- Notes --}}
            @if($invoice->notes)
            <div class="notes-section">
                <h3>Notes</h3>
                <p>{{ $invoice->notes }}</p>
            </div>
            @endif
        </div>

        {{-- Footer --}}
        <div class="invoice-footer">
            <p><strong>Thank you for choosing RJ's Event Styling!</strong></p>
            <p>Invoice generated on {{ $invoice->issued_at ? $invoice->issued_at->format('F d, Y h:i A') : now()->format('F d, Y h:i A') }}
                @if($invoice->generator) by {{ $invoice->generator->name }} @endif
            </p>
        </div>
    </div>

    <script>
        async function downloadInvoicePDF() {
            const { jsPDF } = window.jspdf;
            const element = document.getElementById('invoicePrintArea');
            
            const loading = document.createElement('div');
            loading.style.cssText = 'position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);background:white;padding:20px 30px;border-radius:10px;box-shadow:0 4px 20px rgba(0,0,0,0.15);z-index:9999;font-family:Inter,sans-serif;';
            loading.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right:8px;"></i> Generating PDF...';
            document.body.appendChild(loading);

            try {
                const canvas = await html2canvas(element, {
                    scale: 2,
                    useCORS: true,
                    logging: false,
                    backgroundColor: 'white',
                });

                const imgData = canvas.toDataURL('image/png', 1.0);
                const pdf = new jsPDF('p', 'mm', 'a4');
                const pdfWidth = pdf.internal.pageSize.getWidth();
                const pdfHeight = pdf.internal.pageSize.getHeight();
                const margin = 5;
                const contentWidth = pdfWidth - (margin * 2);
                const imgHeight = (canvas.height * contentWidth) / canvas.width;

                let heightLeft = imgHeight;
                let position = margin;

                pdf.addImage(imgData, 'PNG', margin, position, contentWidth, imgHeight);
                heightLeft -= (pdfHeight - margin * 2);

                while (heightLeft > 0) {
                    position = margin - (imgHeight - heightLeft);
                    pdf.addPage();
                    pdf.addImage(imgData, 'PNG', margin, position, contentWidth, imgHeight);
                    heightLeft -= (pdfHeight - margin * 2);
                }

                pdf.save('{{ $invoice->invoice_number }}.pdf');
            } catch (error) {
                console.error('Error generating PDF:', error);
                alert('Error generating PDF. Please try again.');
            } finally {
                if (document.body.contains(loading)) {
                    document.body.removeChild(loading);
                }
            }
        }
    </script>
</body>
</html>
