# 🧪 Walk-In Customer Login - Testing Guide

## Quick Test Scenarios

Use these scenarios to test the walk-in customer login feature.

---

## Test 1: New Customer Registration

### Prerequisites:

-   Admin logged in
-   Email does not exist in database

### Steps:

1. Navigate to Admin Dashboard → Create Walk-In Booking
2. Enter client information:
    - **Name:** Test User One
    - **Email:** testuser1@example.com (new email)
    - **Phone:** 555-0001
3. Select event type: Wedding
4. Fill in all required fields
5. Submit the booking

### Expected Results:

✅ Success message: "Walk-in booking created successfully! The client has been sent login credentials via email."
✅ User account created in database
✅ Welcome email sent to testuser1@example.com
✅ Email contains temporary password
✅ Booking created and linked to user

### Verification:

1. Check database: `SELECT * FROM users WHERE email = 'testuser1@example.com';`
2. Check email inbox (or log file if using log driver)
3. Copy password from email
4. Try logging in with testuser1@example.com and the password
5. Verify booking appears in customer dashboard

---

## Test 2: Existing Customer

### Prerequisites:

-   Admin logged in
-   Email already exists in database (use testuser1@example.com from Test 1)

### Steps:

1. Navigate to Admin Dashboard → Create Walk-In Booking
2. Enter client information:
    - **Name:** Test User One
    - **Email:** testuser1@example.com (existing email)
    - **Phone:** 555-0001
3. Select event type: Birthday
4. Fill in all required fields
5. Submit the booking

### Expected Results:

✅ Success message: "Walk-in booking created successfully! The client will be notified."
✅ NO new user account created
✅ NO welcome email sent (customer already has credentials)
✅ New booking created and linked to existing user

### Verification:

1. Check database: User count should be the same
2. Check that no email was sent
3. Log in as testuser1@example.com
4. Verify both bookings (Wedding and Birthday) appear in dashboard

---

## Test 3: Email Delivery

### Prerequisites:

-   SMTP configured in `.env`
-   Valid email address for testing

### Steps:

1. Create walk-in booking with your real email address
2. Check your email inbox
3. Check spam/junk folder if not in inbox

### Expected Results:

✅ Email received within 1-2 minutes
✅ Subject: "Welcome! Your Account Has Been Created - Booking Management System"
✅ Email displays correctly (not broken HTML)
✅ Password is visible and readable
✅ "Login to Your Account" button works

### Verification:

-   Email received? ✓
-   Password clearly visible? ✓
-   Booking details correct? ✓
-   Login link works? ✓
-   Email looks professional? ✓

---

## Test 4: Customer Login

### Prerequisites:

-   New walk-in booking created
-   Welcome email received

### Steps:

1. Open the email
2. Copy the temporary password exactly
3. Click "Login to Your Account" button
4. Enter email and password
5. Click Login

### Expected Results:

✅ Successfully logged in
✅ Redirected to customer dashboard
✅ Booking appears in "My Bookings"
✅ All booking details are correct
✅ No error messages

### Verification:

-   Can access dashboard? ✓
-   Booking visible? ✓
-   Can view booking details? ✓
-   Navigation works? ✓
-   Can log out and log in again? ✓

---

## Test 5: Password Change

### Prerequisites:

-   Customer logged in with temporary password

### Steps:

1. Navigate to Profile/Settings
2. Click "Change Password"
3. Enter current (temporary) password
4. Enter new password
5. Confirm new password
6. Submit

### Expected Results:

✅ Password changed successfully
✅ Success message displayed
✅ Can log out
✅ Can log in with NEW password
✅ Cannot log in with OLD password anymore

### Verification:

-   Logout and login with new password? ✓
-   Old password no longer works? ✓
-   Account still accessible? ✓

---

## Test 6: Multiple Bookings

### Prerequisites:

-   Walk-in customer with existing account

### Steps:

1. Create first booking for customer@example.com
2. Customer logs in and sees first booking
3. Create second booking for customer@example.com (walk-in)
4. Customer refreshes dashboard

### Expected Results:

✅ First booking visible immediately
✅ Second booking appears after refresh
✅ Both bookings in same account
✅ Only ONE welcome email sent (for first booking)
✅ Both bookings have correct details

---

## Test 7: Validation

### Prerequisites:

-   Admin logged in

### Test Cases:

#### 7a. Missing Email

-   Try creating booking without email
-   Expected: ✅ Validation error

#### 7b. Invalid Email Format

-   Enter "notanemail" as email
-   Expected: ✅ Validation error

#### 7c. Missing Name

-   Try creating booking without name
-   Expected: ✅ Validation error

#### 7d. Missing Phone

-   Try creating booking without phone
-   Expected: ✅ Validation error

---

## Test 8: Email Configuration

### Test if email is properly configured:

#### Option 1: Using Tinker

```bash
php artisan tinker
```

Then run:

```php
Mail::raw('Test email', function($message) {
    $message->to('your-email@example.com')
            ->subject('Test Email');
});
```

Expected: ✅ Receive test email

#### Option 2: Check Configuration

```bash
php artisan config:clear
php artisan config:cache
```

Check `.env`:

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
```

---

## Test 9: Database Verification

### Check User Table:

```sql
SELECT id, name, email, role, created_at
FROM users
WHERE email = 'testuser1@example.com';
```

Expected Results:

-   User exists ✓
-   Role is 'user' ✓
-   Password is hashed ✓
-   Created timestamp is recent ✓

### Check Booking Table:

```sql
SELECT id, user_id, event_type, status, created_at
FROM bookings
WHERE user_id = (SELECT id FROM users WHERE email = 'testuser1@example.com');
```

Expected Results:

-   Booking exists ✓
-   Linked to correct user ✓
-   Status is 'pending' ✓

---

## Test 10: Error Handling

### Test error scenarios:

#### 10a. Email Server Down

-   Temporarily break MAIL_HOST in .env
-   Create walk-in booking
-   Expected: ✅ Booking still created, error logged

#### 10b. Invalid Email Format

-   Enter invalid email
-   Expected: ✅ Validation error before processing

#### 10c. Database Error

-   (Don't actually do this in production!)
-   Simulate database connection issue
-   Expected: ✅ Graceful error message

---

## Test 11: Browser Testing

### Test email display in different email clients:

-   [ ] Gmail (Desktop)
-   [ ] Gmail (Mobile)
-   [ ] Outlook (Desktop)
-   [ ] Outlook (Mobile)
-   [ ] Apple Mail
-   [ ] Yahoo Mail

Checklist:

-   Email displays correctly ✓
-   Formatting preserved ✓
-   Button clickable ✓
-   Images load (if any) ✓
-   Responsive design works ✓

---

## Test 12: Security Testing

### Test security features:

#### 12a. Password Strength

-   Check generated password: Should be 12 characters, random
-   Expected: ✅ Strong, unpredictable password

#### 12b. Password Storage

-   Check database: Password should be hashed
-   Expected: ✅ Bcrypt hash, not plain text

#### 12c. Email Security

-   Verify SMTP uses TLS/SSL encryption
-   Expected: ✅ Encrypted transmission

---

## Performance Tests

### Test 13: Bulk Creation

Create multiple walk-in bookings in quick succession:

1. Create 5 bookings with different emails
2. Monitor email delivery
3. Check database for all records
4. Verify system performance

Expected Results:
✅ All bookings created
✅ All emails sent
✅ No system slowdown
✅ No duplicate accounts

---

## Test 14: Edge Cases

### 14a. Very Long Name

-   Enter name with 250+ characters
-   Expected: ✅ Validation handles it appropriately

### 14b. Special Characters in Name

-   Enter: "O'Brien-Smith"
-   Expected: ✅ Handled correctly

### 14c. International Characters

-   Enter: "José García"
-   Expected: ✅ Stored and displayed correctly

### 14d. Uppercase/Lowercase Email

-   Create: TEST@EXAMPLE.COM
-   Later create: test@example.com
-   Expected: ✅ Recognized as same email

---

## Automated Test Script

### Create a test script: `test_walk_in_login.php`

```php
<?php

// Run from command line: php test_walk_in_login.php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Booking;
use Illuminate\Support\Str;

echo "🧪 Testing Walk-In Customer Login Feature\n\n";

// Test 1: Create user
echo "Test 1: Creating new walk-in user...\n";
$testEmail = 'test_' . time() . '@example.com';
$password = Str::random(12);

$user = User::create([
    'name' => 'Test User',
    'email' => $testEmail,
    'password' => bcrypt($password),
    'role' => 'user',
    'first_name' => 'Test',
    'last_name' => 'User',
    'phone' => '555-0000',
]);

echo "✅ User created: {$user->email}\n";
echo "   Password: {$password}\n\n";

// Test 2: Create booking
echo "Test 2: Creating booking for user...\n";
$booking = Booking::create([
    'user_id' => $user->id,
    'event_type' => 'wedding',
    'event_date' => now()->addDays(30),
    'event_time' => '14:00:00',
    'location' => 'Test Location, Gensan City',
    'description' => 'Test booking',
    'total_amount' => 50000,
    'status' => 'pending',
]);

echo "✅ Booking created: #{$booking->id}\n\n";

// Test 3: Verify data
echo "Test 3: Verifying data...\n";
$foundUser = User::where('email', $testEmail)->first();
$foundBooking = Booking::where('user_id', $user->id)->first();

if ($foundUser && $foundBooking) {
    echo "✅ User and booking verified in database\n\n";
} else {
    echo "❌ Verification failed\n\n";
}

// Cleanup
echo "Cleaning up test data...\n";
$booking->delete();
$user->delete();
echo "✅ Cleanup complete\n\n";

echo "🎉 All tests passed!\n";
```

---

## Testing Checklist

Use this checklist to track your testing:

### Basic Functionality

-   [ ] New customer account creation
-   [ ] Existing customer handling
-   [ ] Email delivery
-   [ ] Customer login
-   [ ] Dashboard access
-   [ ] Booking visibility

### Security

-   [ ] Password generation (random, 12 chars)
-   [ ] Password hashing (bcrypt)
-   [ ] Email security (TLS/SSL)
-   [ ] No plain text passwords

### User Experience

-   [ ] Email looks professional
-   [ ] Email readable on mobile
-   [ ] Login process smooth
-   [ ] Dashboard intuitive
-   [ ] Password change works

### Edge Cases

-   [ ] Duplicate email handling
-   [ ] Special characters
-   [ ] Very long inputs
-   [ ] Missing data validation
-   [ ] Email server errors

### Performance

-   [ ] Multiple bookings creation
-   [ ] Bulk email sending
-   [ ] Database queries optimized
-   [ ] No system slowdown

---

## Bug Report Template

If you find issues, use this template:

```
**Bug Title:** Brief description

**Test Scenario:** Which test scenario (Test 1, Test 2, etc.)

**Steps to Reproduce:**
1. First step
2. Second step
3. Third step

**Expected Result:**
What should happen

**Actual Result:**
What actually happened

**Screenshots:**
(if applicable)

**Environment:**
- Laravel version:
- PHP version:
- Database:
- Email driver:

**Logs:**
```

Paste relevant log entries

```

**Additional Notes:**
Any other relevant information
```

---

## Success Criteria

✅ **All Tests Passed** means:

-   New customers get accounts and emails
-   Existing customers don't get duplicate accounts
-   Emails are delivered successfully
-   Email content is correct and professional
-   Customers can log in with temporary password
-   Customers can change their password
-   Bookings appear in customer dashboard
-   No security vulnerabilities
-   System performs well under load

---

## Testing Schedule

Recommended testing timeline:

1. **Day 1:** Basic functionality (Tests 1-6)
2. **Day 2:** Validation and configuration (Tests 7-8)
3. **Day 3:** Database and error handling (Tests 9-10)
4. **Day 4:** Browser and security (Tests 11-12)
5. **Day 5:** Performance and edge cases (Tests 13-14)

---

## Final Verification

Before going live, verify:

✅ All tests passed
✅ Email configuration correct
✅ Database backups in place
✅ Documentation reviewed
✅ Admin team trained
✅ Test customer can log in
✅ Production environment tested

---

**Happy Testing! 🧪**

Remember: Testing ensures a smooth experience for your walk-in customers!
