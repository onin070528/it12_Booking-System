<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
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
        .info-box {
            background-color: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .info-box strong {
            color: #2196F3;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .reset-button {
            display: inline-block;
            background-color: #93BFC7;
            color: white !important;
            text-decoration: none;
            padding: 14px 35px;
            border-radius: 25px;
            font-weight: bold;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .reset-button:hover {
            background-color: #7aa8b0;
        }
        .warning-box {
            background-color: #fff9e6;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .warning-box strong {
            color: #856404;
        }
        .email-footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666666;
            font-size: 14px;
        }
        .link-text {
            word-break: break-all;
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            font-size: 12px;
            color: #666;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>🔐 Reset Your Password</h1>
            <p>Secure password reset request</p>
        </div>

        <!-- Body -->
        <div class="email-body">
            <h2>Hello!</h2>
            
            <p>You are receiving this email because we received a password reset request for your account.</p>

            <!-- Reset Button -->
            <div class="button-container">
                <a href="{{ $url }}" class="reset-button">Reset Password →</a>
            </div>

            <!-- Info Box -->
            <div class="info-box">
                <strong>⏰ Important:</strong> This password reset link will expire in <strong>{{ config('auth.passwords.'.config('auth.defaults.passwords').'.expire') }} minutes</strong>.
            </div>

            <!-- Instructions -->
            <p style="margin-top: 25px;"><strong>What happens next?</strong></p>
            <ol style="color: #555; line-height: 1.8;">
                <li>Click the "Reset Password" button above</li>
                <li>You'll be redirected to a secure page</li>
                <li>Enter your new password</li>
                <li>Confirm your new password</li>
                <li>Log in with your new credentials</li>
            </ol>

            <!-- Warning Box -->
            <div class="warning-box">
                <strong>⚠️ Security Notice:</strong> If you did not request a password reset, please ignore this email. Your password will remain unchanged. No further action is required.
            </div>

            <!-- Manual Link -->
            <p style="margin-top: 25px; font-size: 14px; color: #666;">
                If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:
            </p>
            <div class="link-text">
                {{ $url }}
            </div>

            <p style="margin-top: 30px; color: #666;">
                If you have any questions or concerns about this password reset request, please contact our support team immediately.
            </p>

            <p style="margin-top: 20px; color: #666;">Best regards,<br><strong style="color: #93BFC7;">Booking Management Team</strong></p>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p>This is an automated message, please do not reply to this email.</p>
            <p style="margin-top: 15px; color: #999; font-size: 12px;">© {{ date('Y') }} Booking Management System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
