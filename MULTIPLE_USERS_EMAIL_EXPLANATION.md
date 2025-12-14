# How Emails Work with Multiple Users

## ✅ **NO Manual Email Entry Needed!**

The system **automatically** uses each user's email address from the database. You don't need to manually add or configure anything.

## How It Works

### 1. **User Registration**
When users register, their email is automatically saved in the database:

```
User 1 registers → Email saved: user1@example.com
User 2 registers → Email saved: user2@example.com
User 3 registers → Email saved: user3@example.com
```

### 2. **Automatic Email Sending**

#### When a Booking is Created:

**For Admins:**
- System automatically finds ALL admin users: `User::where('role', 'admin')->get()`
- Sends email to EACH admin's registered email
- If you have 3 admins, all 3 get the email automatically

**For Customers:**
- System gets the customer from the booking: `$booking->user`
- Sends email to that customer's registered email
- Each customer gets emails at their own email address

### 3. **Example Scenario**

Let's say you have:
- **Admin 1**: admin1@example.com
- **Admin 2**: admin2@example.com
- **Customer A**: customerA@example.com
- **Customer B**: customerB@example.com

**When Customer A creates a booking:**
1. ✅ Email sent to admin1@example.com (Admin 1)
2. ✅ Email sent to admin2@example.com (Admin 2)
3. ✅ Email sent to customerA@example.com (Customer A)

**When Customer B creates a booking:**
1. ✅ Email sent to admin1@example.com (Admin 1)
2. ✅ Email sent to admin2@example.com (Admin 2)
3. ✅ Email sent to customerB@example.com (Customer B)

**All automatic!** No manual configuration needed.

## Code Behind the Scenes

### Sending to All Admins:
```php
// Gets ALL admin users automatically
$admins = User::where('role', 'admin')->get();

// Sends email to EACH admin's email address
foreach ($admins as $admin) {
    $this->emailNotificationService->sendNotification(
        $admin,  // Uses $admin->email from database
        'booking_created',
        $message,
        $data,
        $booking
    );
}
```

### Sending to Customer:
```php
// Gets the customer from the booking
$customer = $booking->user;  // Customer's User model

// Sends email to customer's registered email
$this->emailNotificationService->sendNotification(
    $customer,  // Uses $customer->email from database
    'booking_confirmed',
    $message,
    $data,
    $booking
);
```

## What You Need to Do

### ✅ **Nothing!** It's all automatic.

The system:
- ✅ Uses each user's email from the database
- ✅ Sends to all admins automatically
- ✅ Sends to the correct customer automatically
- ✅ Works with any number of users
- ✅ No manual email configuration needed

### The Only Configuration Needed:

**Just set up your SMTP credentials once** in `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-gmail@gmail.com
MAIL_PASSWORD="your-app-password"
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-gmail@gmail.com
MAIL_FROM_NAME="Booking Management System"
```

This is the **sender** email (your system's email). All users receive emails at **their own registered email addresses**.

## Real-World Example

**Database has:**
- Admin: admin@company.com
- User 1: john@example.com
- User 2: jane@example.com
- User 3: bob@example.com

**When John creates a booking:**
- Email sent to: admin@company.com (admin notification)
- Email sent to: john@example.com (John's confirmation)

**When Jane creates a booking:**
- Email sent to: admin@company.com (admin notification)
- Email sent to: jane@example.com (Jane's confirmation)

**When Bob creates a booking:**
- Email sent to: admin@company.com (admin notification)
- Email sent to: bob@example.com (Bob's confirmation)

Each user automatically receives emails at their registered email address!

## Verification

You can verify which emails are being used by checking the logs:

```bash
tail -f storage/logs/laravel.log
```

You'll see lines like:
```
Attempting to send email to user ID 1 (Admin User) at email: admin@company.com
Attempting to send email to user ID 3 (John Doe) at email: john@example.com
```

## Summary

- ✅ **Automatic** - Uses emails from database
- ✅ **No manual entry** - Everything is automatic
- ✅ **Scales** - Works with 1 user or 1000 users
- ✅ **Dynamic** - New users automatically get emails
- ✅ **Correct** - Each user gets emails at their registered email

**You only need to configure SMTP once** (the sender email). The system handles everything else automatically!

