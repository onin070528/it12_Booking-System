<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            margin: -30px -30px 30px -30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            margin: 20px 0;
        }
        .message-box {
            background-color: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .booking-details {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .booking-details h3 {
            margin-top: 0;
            color: #667eea;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: bold;
            color: #666;
        }
        .detail-value {
            color: #333;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            text-align: center;
        }
        .button:hover {
            background-color: #5568d3;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            margin: 5px 0;
        }
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        .status-confirmed {
            background-color: #d1fae5;
            color: #065f46;
        }
        .status-approved {
            background-color: #dbeafe;
            color: #1e40af;
        }
        .status-completed {
            background-color: #d1fae5;
            color: #065f46;
        }
        .status-payment {
            background-color: #fce7f3;
            color: #831843;
        }
        .status-rejected {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .status-cancelled {
            background-color: #f3f4f6;
            color: #374151;
        }
        .status-failed {
            background-color: #fee2e2;
            color: #991b1b;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>Booking Management System</h1>
        </div>

        <div class="content">
            <h2>Hello {{ $user->name }},</h2>
            
            <div class="message-box">
                <p>{{ $notificationMessage }}</p>
            </div>

            @if($booking)
            <div class="booking-details">
                <h3>Booking Details</h3>
                
                <div class="detail-row">
                    <span class="detail-label">Booking ID:</span>
                    <span class="detail-value">#{{ $booking->id }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Event Type:</span>
                    <span class="detail-value">{{ ucfirst($booking->event_type) }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Event Date:</span>
                    <span class="detail-value">{{ $booking->event_date->format('F d, Y') }}</span>
                </div>
                
                @if($booking->event_time)
                <div class="detail-row">
                    <span class="detail-label">Event Time:</span>
                    <span class="detail-value">{{ date('g:i A', strtotime($booking->event_time)) }}</span>
                </div>
                @endif
                
                @if($booking->location)
                <div class="detail-row">
                    <span class="detail-label">Location:</span>
                    <span class="detail-value">{{ $booking->location }}</span>
                </div>
                @endif
                
                <div class="detail-row">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value">
                        @php
                            $statusClass = 'status-pending';
                            $status = strtolower($booking->status);
                            if (str_contains($status, 'confirmed')) {
                                $statusClass = 'status-confirmed';
                            } elseif (str_contains($status, 'approved')) {
                                $statusClass = 'status-approved';
                            } elseif (str_contains($status, 'completed')) {
                                $statusClass = 'status-completed';
                            } elseif (str_contains($status, 'payment') || str_contains($status, 'paid')) {
                                $statusClass = 'status-payment';
                            } elseif (str_contains($status, 'rejected')) {
                                $statusClass = 'status-rejected';
                            } elseif (str_contains($status, 'cancelled')) {
                                $statusClass = 'status-cancelled';
                            } elseif (str_contains($status, 'failed')) {
                                $statusClass = 'status-failed';
                            }
                        @endphp
                        <span class="status-badge {{ $statusClass }}">
                            {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                        </span>
                    </span>
                </div>
                
                @if($booking->total_amount)
                <div class="detail-row">
                    <span class="detail-label">Total Amount:</span>
                    <span class="detail-value">₱{{ number_format($booking->total_amount, 2) }}</span>
                </div>
                @endif

                @if(isset($data['paid_amount']))
                <div class="detail-row">
                    <span class="detail-label">Paid Amount:</span>
                    <span class="detail-value">₱{{ number_format($data['paid_amount'], 2) }}</span>
                </div>
                @endif

                @if(isset($data['remaining_amount']))
                <div class="detail-row">
                    <span class="detail-label">Remaining Balance:</span>
                    <span class="detail-value">₱{{ number_format($data['remaining_amount'], 2) }}</span>
                </div>
                @endif
            </div>
            @endif

            @if($notificationType === 'booking_ready_for_payment')
            <div style="text-align: center;">
                <a href="{{ url('/payments') }}" class="button">Make Payment</a>
            </div>
            @endif

            @if($notificationType === 'booking_confirmed')
            <div style="text-align: center;">
                <a href="{{ url('/home') }}" class="button">View Booking</a>
            </div>
            @endif

            @if($notificationType === 'payment_failed')
            <div style="text-align: center;">
                <a href="{{ url('/payments') }}" class="button">Try Payment Again</a>
            </div>
            @endif

            @if($notificationType === 'booking_ready_for_payment' || $notificationType === 'payment_received' || $notificationType === 'payment_partial_received')
            <div style="text-align: center;">
                <a href="{{ url('/payments') }}" class="button">View Payments</a>
            </div>
            @endif
        </div>

        <div class="footer">
            <p>This is an automated notification from the Booking Management System.</p>
            <p>Please do not reply to this email. If you have any questions, please contact us through the system.</p>
            <p>&copy; {{ date('Y') }} Booking Management System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

