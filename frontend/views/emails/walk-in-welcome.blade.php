<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Booking Management System</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #93BFC7 0%, #7aa8b0 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }
        .email-header p {
            margin: 10px 0 0;
            font-size: 16px;
            opacity: 0.9;
        }
        .email-body {
            padding: 30px 40px;
            color: #333333;
            line-height: 1.6;
        }
        .email-body h2 {
            color: #93BFC7;
            font-size: 22px;
            margin-bottom: 15px;
        }
        .credentials-box {
            background-color: #f8f9fa;
            border-left: 4px solid #93BFC7;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .credentials-box p {
            margin: 8px 0;
            font-size: 15px;
        }
        .credentials-box strong {
            color: #93BFC7;
            font-weight: 600;
        }
        .credentials-box .password {
            background-color: #e9ecef;
            padding: 8px 12px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-size: 16px;
            letter-spacing: 1px;
            display: inline-block;
            margin-top: 5px;
        }
        .booking-info {
            background-color: #ECF4E8;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }
        .booking-info h3 {
            color: #93BFC7;
            margin-top: 0;
            font-size: 18px;
        }
        .booking-info p {
            margin: 10px 0;
            font-size: 15px;
        }
        .login-button {
            display: inline-block;
            background-color: #93BFC7;
            color: white;
            text-decoration: none;
            padding: 14px 35px;
            border-radius: 25px;
            font-weight: bold;
            font-size: 16px;
            margin: 20px 0;
            transition: background-color 0.3s;
        }
        .login-button:hover {
            background-color: #7aa8b0;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .instructions {
            background-color: #fff9e6;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .instructions h4 {
            margin: 0 0 10px 0;
            color: #856404;
            font-size: 16px;
        }
        .instructions ol {
            margin: 10px 0;
            padding-left: 20px;
        }
        .instructions li {
            margin: 8px 0;
            color: #856404;
        }
        .email-footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666666;
            font-size: 14px;
        }
        .security-note {
            background-color: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            font-size: 14px;
        }
        .security-note strong {
            color: #2196F3;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>🎉 Welcome to Our Service!</h1>
            <p>Your account has been created successfully</p>
        </div>

        <!-- Body -->
        <div class="email-body">
            <h2>Hello {{ $userName }}!</h2>
            
            <p>Thank you for visiting us! We've created an online account for you so you can easily manage and view your bookings anytime, anywhere.</p>

            <!-- Login Credentials -->
            <div class="credentials-box">
                <h3 style="margin-top: 0; color: #93BFC7;">🔐 Your Login Credentials</h3>
                <p><strong>Email:</strong> {{ $userEmail }}</p>
                <p><strong>Temporary Password:</strong></p>
                <div class="password">{{ $temporaryPassword }}</div>
            </div>

            <!-- Security Note -->
            <div class="security-note">
                <strong>🔒 Important Security Note:</strong> This is a temporary password. For your security, please change it immediately after your first login by going to your profile settings.
            </div>

            <!-- Booking Information -->
            <div class="booking-info">
                <h3>📅 Your Booking Details</h3>
                <p><strong>Booking ID:</strong> #{{ $bookingId }}</p>
                <p><strong>Event Type:</strong> {{ $eventType }}</p>
                <p><strong>Date:</strong> {{ $eventDate }}</p>
                <p><strong>Time:</strong> {{ $eventTime }}</p>
                <p><strong>Status:</strong> <span style="color: #ffc107;">Pending Review</span></p>
            </div>

            <!-- Instructions -->
            <div class="instructions">
                <h4>📝 How to Access Your Account:</h4>
                <ol>
                    <li>Click the "Login to Your Account" button below</li>
                    <li>Enter your email and the temporary password provided above</li>
                    <li>Change your password in your profile settings</li>
                    <li>View and manage your bookings from your dashboard</li>
                </ol>
            </div>

            <!-- Login Button -->
            <div class="button-container">
                <a href="{{ $loginUrl }}" class="login-button">Login to Your Account →</a>
            </div>

            <p style="margin-top: 30px;">Once logged in, you'll be able to:</p>
            <ul style="color: #555; line-height: 1.8;">
                <li>✓ View all your bookings and their status</li>
                <li>✓ Track payment information</li>
                <li>✓ Receive instant updates and notifications</li>
                <li>✓ Make new bookings online anytime</li>
                <li>✓ Update your profile information</li>
            </ul>

            <p style="margin-top: 25px;">If you have any questions or need assistance, please don't hesitate to contact us. We're here to help!</p>

            <p style="margin-top: 20px; color: #666;">Best regards,<br><strong style="color: #93BFC7;">Booking Management Team</strong></p>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p>This email was sent to {{ $userEmail }}</p>
            <p>If you didn't make this booking, please contact us immediately.</p>
            <p style="margin-top: 15px; color: #999; font-size: 12px;">© {{ date('Y') }} Booking Management System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
