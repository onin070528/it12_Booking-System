<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Registration Update</title>
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
        .content h2 {
            color: #ef4444;
            text-align: center;
        }
        .reason-box {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }
        .reason-box strong {
            color: #dc2626;
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
            <h1>RJ's Events Style</h1>
        </div>
        
        <div class="content">
            <h2>Account Registration Update</h2>
            
            <p>Hello {{ $user->first_name ?? $user->name }},</p>
            
            <p>Thank you for your interest in RJ's Events Style. After reviewing your registration, we regret to inform you that your account application has not been approved at this time.</p>
            
            @if($reason)
            <div class="reason-box">
                <strong>Reason:</strong>
                <p>{{ $reason }}</p>
            </div>
            @endif
            
            <p>If you believe this was a mistake or would like more information, please contact our support team and we'll be happy to assist you.</p>
            
            <p>You're welcome to submit a new registration application in the future.</p>
            
            <p>Thank you for your understanding.</p>
            <p><strong>The RJ's Events Style Team</strong></p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} RJ's Events Style. All rights reserved.</p>
            <p>This is an automated message. Please do not reply directly to this email.</p>
        </div>
    </div>
</body>
</html>
