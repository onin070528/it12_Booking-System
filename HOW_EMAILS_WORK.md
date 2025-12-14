# How Email Notifications Work

## Overview

The email notification system **automatically uses each user's registered email address** from the database. There is **NO hardcoded email address** - every user receives emails at their own registered email.

## How It Works

### 1. **User Registration**
- When a user registers, their email is saved in the `users` table
- A welcome email is sent to **their registered email address**

### 2. **Booking Notifications**

#### For Customers (Users):
- When a booking is created/updated, the system gets the customer from the booking: `$booking->user`
- The customer's email is retrieved from the database: `$user->email`
- Email is sent to **that specific customer's email address**

#### For Admins:
- When admins need to be notified, the system gets all admin users: `User::where('role', 'admin')->get()`
- Each admin receives an email at **their own registered email address**
- If there are 3 admins with different emails, all 3 will receive the email

### 3. **Payment Notifications**
- Same principle: uses the user's email from the database
- Customer receives payment notifications at **their registered email**
- Admins receive payment notifications at **their registered email addresses**

## Code Examples

### Sending to Customer:
```php
// Get the customer from the booking
$customer = $booking->user;  // This gets the User model with their email

// Send email to the customer's registered email
$this->emailNotificationService->sendNotification(
    $customer,  // User object with email from database
    'booking_confirmed',
    $message,
    $data,
    $booking
);
```

### Sending to Admins:
```php
// Get all admin users (each with their own email)
$admins = User::where('role', 'admin')->get();

// Send email to each admin's registered email
foreach ($admins as $admin) {
    $this->emailNotificationService->sendNotification(
        $admin,  // Each admin's User object with their email
        'booking_created',
        $message,
        $data,
        $booking
    );
}
```

### Email Service:
```php
// In EmailNotificationService
public function sendNotification(User $user, ...) {
    // Uses the user's email from the database
    Mail::to($user->email)->send(...);
    // $user->email comes from the users table in the database
}
```

## Email Addresses Used

| User Type | Email Source | Example |
|-----------|-------------|---------|
| Customer | `$booking->user->email` | `customer@example.com` |
| Admin 1 | `$admin1->email` | `admin1@example.com` |
| Admin 2 | `$admin2->email` | `admin2@example.com` |
| New User | `$user->email` | `newuser@example.com` |

## Verification

### Check Logs
The system logs which email addresses are being used:
```bash
tail -f storage/logs/laravel.log
```

Look for lines like:
```
Attempting to send email to user ID 5 (John Doe) at email: john@example.com
✓ Email notification sent successfully to john@example.com (User: John Doe, ID: 5)
```

### Test with Different Users
1. Create multiple user accounts with different emails
2. Create bookings for different users
3. Check the logs to see emails being sent to different addresses
4. Each user should receive emails at their registered email

## Important Points

✅ **Each user receives emails at their registered email address**
✅ **No hardcoded email addresses** - all emails come from the database
✅ **Multiple admins** - each admin gets emails at their own email
✅ **Dynamic** - works with any number of users with any email addresses

## Troubleshooting

### "Emails not being sent to the right address"
1. Check the user's email in the database: `SELECT email FROM users WHERE id = ?`
2. Check logs to see which email is being used
3. Verify the user object has the correct email: `dd($user->email)`

### "Want to verify which email is used"
Add this temporarily to see the email:
```php
Log::info("Sending email to: " . $user->email . " for user: " . $user->name);
```

### "Multiple users, different emails"
The system handles this automatically:
- User A with email `userA@example.com` → receives emails at `userA@example.com`
- User B with email `userB@example.com` → receives emails at `userB@example.com`
- Admin with email `admin@example.com` → receives emails at `admin@example.com`

Each user's email is stored in the `users` table and used automatically.

