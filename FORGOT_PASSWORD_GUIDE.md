# 🔐 Forgot Password Feature - Complete Guide

## 📖 Overview

The Forgot Password feature allows users (both customers and admins) to reset their password when they forget it. The system sends a secure password reset link via email, ensuring only the account owner can reset the password.

---

## ✨ Features Implemented

### 1. **Forgot Password Page**

-   Beautiful, branded design matching your app
-   Clear instructions for users
-   Animated background
-   Info box with step-by-step process
-   Email validation
-   "Back to Login" link

### 2. **Reset Password Page**

-   Secure token-based authentication
-   Password strength indicator (Real-time)
-   Password match checker
-   Show/hide password toggle
-   Password requirements display
-   Beautiful, user-friendly design

### 3. **Email Notification**

-   Professional HTML email template
-   Branded with your colors (#93BFC7)
-   Clear call-to-action button
-   Security information
-   Expiration time displayed
-   Manual link fallback
-   Mobile-responsive design

### 4. **Security Features**

-   ✅ Token-based password reset (expires in 60 minutes)
-   ✅ Email verification required
-   ✅ Secure password hashing
-   ✅ One-time use reset links
-   ✅ No password stored in email
-   ✅ Laravel's built-in security

---

## 🔄 How It Works

### User Flow:

```
1. User clicks "Forgot Password?" on login page
   ↓
2. User enters their email address
   ↓
3. System sends password reset email
   ↓
4. User receives email with reset link
   ↓
5. User clicks "Reset Password" button in email
   ↓
6. User is redirected to reset password page
   ↓
7. User enters new password (twice)
   ↓
8. Password is updated
   ↓
9. User is redirected to login with success message
   ↓
10. User logs in with new password
```

### System Flow:

```
Request → Validate Email → Generate Token → Send Email →
User Clicks Link → Validate Token → Update Password →
Invalidate Token → Redirect to Login
```

---

## 📁 Files Created/Modified

### New Files:

1. **`app/Notifications/ResetPasswordNotification.php`**

    - Custom notification class for password reset
    - Handles email generation and sending

2. **`resources/views/emails/reset-password.blade.php`**
    - Beautiful HTML email template
    - Professional design with branding

### Modified Files:

1. **`app/Models/User.php`**

    - Added `sendPasswordResetNotification()` method
    - Imports custom notification class

2. **`resources/views/auth/forgot-password.blade.php`**

    - Enhanced design matching app theme
    - Added instructions and info boxes

3. **`resources/views/auth/reset-password.blade.php`**
    - Complete redesign with modern UI
    - Password strength indicator
    - Real-time password matching

---

## 🎨 Design Features

### Forgot Password Page:

-   🎨 Matches login page design
-   🔒 Lock icon in header
-   📧 Email input with icon
-   📋 Step-by-step instructions
-   ⬅️ Back to login link
-   🎭 Animated background

### Reset Password Page:

-   🎨 Consistent branding
-   🔑 Key icon in header
-   👁️ Show/hide password toggle
-   📊 Password strength meter (Weak/Medium/Strong)
-   ✓ Password match indicator
-   📝 Password requirements list
-   🎯 Clear visual feedback

### Email Template:

-   🎨 Professional design with gradient header
-   🔘 Large "Reset Password" button
-   ⏰ Expiration time highlighted
-   ⚠️ Security warnings
-   📱 Mobile-responsive
-   🔗 Manual link fallback

---

## 🚀 Usage Instructions

### For Users:

#### Step 1: Request Password Reset

1. Go to the login page
2. Click "Forgot Password?" link
3. Enter your email address
4. Click "Send Reset Link"
5. Check your email inbox

#### Step 2: Check Email

1. Open the email from Booking Management System
2. Subject: "Reset Password Notification - Booking Management System"
3. Click the big "Reset Password" button
4. Or copy/paste the manual link if button doesn't work

#### Step 3: Reset Password

1. You'll be redirected to reset password page
2. Your email is already filled in
3. Enter your new password
4. Watch the strength indicator (aim for "Strong")
5. Re-enter the password in "Confirm Password"
6. Check that passwords match (green ✓)
7. Click "Reset Password"

#### Step 4: Login

1. You'll be redirected to login page
2. Use your email and NEW password
3. Successfully logged in!

---

## 🔒 Security Information

### Token Security:

-   **Expiration Time:** 60 minutes (configurable)
-   **One-Time Use:** Each token can only be used once
-   **Random Generation:** Tokens are cryptographically secure
-   **Database Storage:** Tokens stored in `password_reset_tokens` table

### Password Requirements:

-   Minimum 8 characters (Laravel default)
-   Can be changed in `config/auth.php`
-   Passwords are hashed with bcrypt
-   No password history (can be added if needed)

### Email Security:

-   Passwords never sent via email
-   Only secure reset links sent
-   Links expire automatically
-   User must have access to email account

---

## ⚙️ Configuration

### Email Settings (Important!)

Make sure your `.env` file has email configured:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="Booking Management System"
```

### Token Expiration

To change reset link expiration time:

**File:** `config/auth.php`

```php
'passwords' => [
    'users' => [
        'provider' => 'users',
        'table' => 'password_reset_tokens',
        'expire' => 60, // Minutes (change this)
        'throttle' => 60,
    ],
],
```

### Password Requirements

**File:** `config/auth.php`

```php
'password_timeout' => 10800, // Seconds before re-auth required
```

**For stronger password rules, update:**
`app/Rules/Password` or use validation rules in controllers

---

## 🧪 Testing

### Test the Complete Flow:

1. **Test Forgot Password Page**

    ```
    Navigate to: http://your-app/forgot-password
    - Page loads correctly ✓
    - Design matches login page ✓
    - Instructions are clear ✓
    ```

2. **Test Email Sending**

    ```
    - Enter valid email address
    - Click "Send Reset Link"
    - Success message appears ✓
    - Email received (check spam) ✓
    ```

3. **Test Email Content**

    ```
    - Email has correct subject ✓
    - Professional design ✓
    - "Reset Password" button works ✓
    - Manual link works ✓
    - Expiration time shown ✓
    ```

4. **Test Reset Password Page**

    ```
    - Click link in email
    - Reset page loads ✓
    - Email pre-filled ✓
    - Password strength indicator works ✓
    - Password match checker works ✓
    - Show/hide password works ✓
    ```

5. **Test Password Reset**

    ```
    - Enter new password
    - Confirm password
    - Click "Reset Password"
    - Success redirect to login ✓
    - Login with new password ✓
    ```

6. **Test Security**
    ```
    - Try using old password (fails) ✓
    - Try reusing reset link (fails) ✓
    - Wait for expiration (link fails) ✓
    - Try invalid email (error shown) ✓
    ```

---

## 🛠️ Troubleshooting

### Problem: Email not received

**Solutions:**

1. Check spam/junk folder
2. Verify email configuration in `.env`
3. Check Laravel logs: `storage/logs/laravel.log`
4. Test email with: `php artisan tinker`
    ```php
    Mail::raw('Test', fn($m) => $m->to('test@example.com')->subject('Test'));
    ```
5. Check if email exists in database
6. Verify mail driver is working

### Problem: Reset link expired

**Solutions:**

1. Request a new reset link
2. Links expire after 60 minutes by default
3. Check configuration in `config/auth.php`
4. Make sure you click the link soon after receiving email

### Problem: Password not updating

**Solutions:**

1. Check validation errors on page
2. Ensure passwords match
3. Meet password requirements (min 8 characters)
4. Check Laravel logs for errors
5. Verify database connection

### Problem: "Token invalid" error

**Solutions:**

1. Link may have been used already
2. Link may have expired
3. Request a new password reset
4. Clear browser cache
5. Check `password_reset_tokens` table in database

---

## 💡 Tips & Best Practices

### For Users:

-   ✅ Use a strong, unique password
-   ✅ Don't share reset links with anyone
-   ✅ Complete reset process promptly (within 60 min)
-   ✅ Check spam folder if email doesn't arrive
-   ✅ Bookmark the login page for easy access

### For Admins:

-   ✅ Ensure email configuration is correct
-   ✅ Monitor password reset requests for abuse
-   ✅ Educate users about password security
-   ✅ Keep Laravel and dependencies updated
-   ✅ Test the flow periodically

---

## 📊 Database

### Password Reset Tokens Table:

```
password_reset_tokens
├── email (primary key)
├── token (hashed)
└── created_at
```

**Note:** Tokens are automatically cleaned up after expiration or use.

---

## 🔄 Customization Options

### Change Email Design:

Edit: `resources/views/emails/reset-password.blade.php`

-   Modify colors, fonts, layout
-   Add company logo
-   Change text content

### Change Page Design:

Edit views:

-   `resources/views/auth/forgot-password.blade.php`
-   `resources/views/auth/reset-password.blade.php`

### Add Additional Security:

1. **Two-Factor Authentication**
2. **Security Questions**
3. **SMS Verification**
4. **Email Verification before reset**
5. **IP Address Logging**
6. **Password History**

---

## 📧 Email Preview

### Subject:

**Reset Password Notification - Booking Management System**

### Content Includes:

-   Professional gradient header
-   Greeting
-   Clear explanation
-   Large "Reset Password" button
-   Expiration time (60 minutes)
-   Step-by-step instructions
-   Security warning
-   Manual link fallback
-   Footer with company info

---

## 🎯 Success Criteria

✅ **Feature is working if:**

-   Users can request password reset from login page
-   Email is sent successfully
-   Email has professional design and correct content
-   Reset link works when clicked
-   Password can be updated
-   Old password no longer works
-   New password works immediately
-   Security measures are in place
-   User experience is smooth

---

## 📝 Routes

```php
// Forgot Password Routes
GET  /forgot-password          → Show forgot password form
POST /forgot-password          → Send reset link email

// Reset Password Routes
GET  /reset-password/{token}   → Show reset password form
POST /reset-password           → Process password reset
```

---

## 🎉 Summary

**The Problem:**
Users who forget their password had no way to recover access to their account.

**The Solution:**
Complete forgot password functionality with:

-   Email-based password reset
-   Secure token system
-   Professional email template
-   Beautiful, user-friendly interface
-   Real-time password validation
-   Strong security measures

**The Result:**

-   Users can recover their accounts independently
-   Reduced support requests for password issues
-   Professional, secure password reset process
-   Better user experience
-   Industry-standard security practices

---

## 📚 Additional Resources

-   [Laravel Password Reset Documentation](https://laravel.com/docs/passwords)
-   [Laravel Mail Documentation](https://laravel.com/docs/mail)
-   [Laravel Notifications Documentation](https://laravel.com/docs/notifications)

---

**Everything is ready! Users can now reset their passwords via email! 🎉**

For support or questions, refer to this guide or check the Laravel logs.
