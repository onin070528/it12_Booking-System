<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
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
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>Welcome to {{ config('app.name', 'Booking Management System') }}</h1>
        </div>

        <div class="content">
            <h2>Hello {{ $user->name }},</h2>
            
            <p>Thank you for creating an account with us! We're excited to have you on board.</p>
            
            <p>You can now:</p>
            <ul>
                <li>Create and manage your event bookings</li>
                <li>Track your payment status</li>
                <li>Communicate with our team</li>
                <li>View your booking history</li>
            </ul>

            <div style="text-align: center;">
                <a href="{{ url('/') }}" class="button">Get Started</a>
            </div>

            <p>If you have any questions or need assistance, please don't hesitate to contact us.</p>
        </div>

        <div class="footer">
            <p>This is an automated email from {{ config('app.name', 'Booking Management System') }}.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name', 'Booking Management System') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

