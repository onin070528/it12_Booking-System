<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Account Pending Approval</title>
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
        .alert-icon {
            font-size: 50px;
            text-align: center;
            margin-bottom: 20px;
        }
        .content h2 {
            color: #f59e0b;
            text-align: center;
        }
        .user-info {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .user-info table {
            width: 100%;
        }
        .user-info td {
            padding: 8px 0;
        }
        .user-info td:first-child {
            font-weight: bold;
            color: #64748b;
            width: 40%;
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
            <h1>RJ's Events Style Admin</h1>
        </div>
        
        <div class="alert-icon">🔔</div>
        
        <div class="content">
            <h2>New Account Pending Approval</h2>
            
            <p>A new user has registered and is awaiting your approval.</p>
            
            <div class="user-info">
                <table>
                    <tr>
                        <td>Name:</td>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <td>Email:</td>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <td>Phone:</td>
                        <td>{{ $user->phone ?? 'Not provided' }}</td>
                    </tr>
                    <tr>
                        <td>Registered:</td>
                        <td>{{ $user->created_at->format('F d, Y \a\t g:i A') }}</td>
                    </tr>
                </table>
            </div>
            
            <p>Please review this registration and take appropriate action.</p>
            
            <div class="btn-container">
                <a href="{{ route('admin.users.index') }}" class="btn">Review Pending Users</a>
            </div>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} RJ's Events Style. All rights reserved.</p>
            <p>This is an automated admin notification.</p>
        </div>
    </div>
</body>
</html>
