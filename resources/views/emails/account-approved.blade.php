<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Approved</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #93BFC7;
            margin: 0;
            font-size: 28px;
        }
        .success-icon {
            font-size: 60px;
            color: #22c55e;
            text-align: center;
            margin-bottom: 20px;
        }
        .content h2 {
            color: #22c55e;
            text-align: center;
        }
        .btn {
            display: inline-block;
            padding: 14px 30px;
            background-color: #93BFC7;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            margin: 20px 0;
        }
        .btn:hover {
            background-color: #7eaab1;
        }
        .btn-container {
            text-align: center;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #888;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 RJ's Events Style</h1>
        </div>
        
        <div class="success-icon">✓</div>
        
        <div class="content">
            <h2>Your Account Has Been Approved!</h2>
            
            <p>Hello {{ $user->first_name ?? $user->name }},</p>
            
            <p>Great news! Your RJ's Events Style account has been reviewed and <strong>approved</strong> by our team.</p>
            
            <p>You can now log in and start exploring our event styling services:</p>
            
            <div class="btn-container">
                <a href="{{ route('login') }}" class="btn">Log In to Your Account</a>
            </div>
            
            <p>With your account, you can:</p>
            <ul>
                <li>Browse and book catering services for your events</li>
                <li>Track your booking status in real-time</li>
                <li>Communicate with our team</li>
                <li>Manage your payments securely</li>
            </ul>
            
            <p>If you have any questions, feel free to reach out to our support team.</p>
            
            <p>Welcome aboard!</p>
            <p><strong>The RJ's Events Style Team</strong></p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} RJ's Events Style. All rights reserved.</p>
            <p>This is an automated message. Please do not reply directly to this email.</p>
        </div>
    </div>
</body>
</html>
