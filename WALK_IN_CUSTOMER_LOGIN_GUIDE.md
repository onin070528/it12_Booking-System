# Walk-In Customer Online Access Guide

## Overview

Walk-in customers now automatically receive online account access when their booking is created by an admin. This allows them to log in later and manage their bookings online.

---

## 🎯 How It Works

### For Admins (Creating Walk-In Bookings)

1. **Navigate to Admin Booking Page**

    - Go to the admin dashboard
    - Click on "Create Walk-In Booking"

2. **Enter Client Information**

    - Client Full Name
    - Client Email (required for account creation)
    - Client Phone Number

3. **Complete Booking Details**

    - Select event type
    - Fill in event-specific information
    - Set date, time, and location

4. **Submit Booking**
    - When you click "Submit Booking":
        - ✅ A booking is created
        - ✅ A user account is automatically created (if the email doesn't exist)
        - ✅ A temporary password is generated
        - ✅ A welcome email with login credentials is sent to the customer

### For Walk-In Customers

#### First-Time Access

1. **Check Your Email**

    - Look for an email with subject: "Welcome! Your Account Has Been Created - Booking Management System"
    - This email contains:
        - Your login email
        - Temporary password
        - Booking confirmation details
        - Link to login page

2. **Login to Your Account**

    - Click the "Login to Your Account" button in the email
    - Or visit the website and click "Login"
    - Enter your email and the temporary password from the email

3. **Change Your Password (Recommended)**
    - After logging in, go to your profile settings
    - Change the temporary password to a secure password of your choice
    - This ensures your account security

#### What You Can Do Online

Once logged in, walk-in customers can:

-   📋 **View All Bookings** - See all their current and past bookings
-   💰 **Track Payments** - View payment status and history
-   🔔 **Receive Notifications** - Get updates about booking status changes
-   📅 **Create New Bookings** - Make additional bookings online without visiting in person
-   👤 **Update Profile** - Manage personal information and contact details
-   🔐 **Change Password** - Update login credentials for security

---

## 📧 Welcome Email Content

The welcome email includes:

### Login Credentials Section

```
🔐 Your Login Credentials
Email: customer@example.com
Temporary Password: [12-character password]
```

### Security Note

-   Customers are advised to change their temporary password immediately
-   Password can be changed in profile settings

### Booking Details

-   Booking ID
-   Event Type
-   Date and Time
-   Current Status

### Access Instructions

Step-by-step guide on how to:

1. Access the login page
2. Enter credentials
3. Change password
4. Navigate the dashboard

---

## 🔄 Account Creation Logic

### New Customer (Email Doesn't Exist)

1. System creates a new user account
2. Generates a secure 12-character random password
3. Sends welcome email with credentials
4. Customer can log in immediately

### Existing Customer (Email Already Exists)

1. System uses the existing account
2. No new password is generated
3. Booking is added to their existing account
4. No welcome email is sent (they already have login access)

---

## 🔒 Security Features

1. **Temporary Password**

    - Random 12-character password generated for new accounts
    - Secure and unique for each customer
    - Customers encouraged to change it immediately

2. **Password Hashing**

    - All passwords are encrypted using bcrypt
    - Passwords are never stored in plain text

3. **Email Verification**
    - Credentials sent only to the email provided
    - Ensures only the customer can access their account

---

## 💡 Benefits

### For Customers

-   ✅ Convenient online access without having to visit in person
-   ✅ 24/7 access to booking information
-   ✅ Real-time updates and notifications
-   ✅ Easy tracking of payments and status
-   ✅ Ability to make future bookings online

### For Admins

-   ✅ Reduced phone calls and inquiries
-   ✅ Customers can self-service their information
-   ✅ Automated account creation process
-   ✅ Better customer engagement
-   ✅ Professional service experience

---

## 📱 Customer Dashboard Features

Once logged in, customers see:

1. **My Bookings Page**

    - All bookings with status indicators
    - Event details and dates
    - Payment information

2. **Notifications**

    - Status updates
    - Payment confirmations
    - Admin messages

3. **Profile Settings**

    - Personal information
    - Contact details
    - Password change option

4. **New Booking Form**
    - Create additional bookings online
    - Same process as walk-in but online

---

## 🛠️ Troubleshooting

### Customer Didn't Receive Email

**Possible Causes:**

-   Email in spam/junk folder
-   Incorrect email address entered
-   Email server issues

**Solutions:**

1. Check spam/junk folders
2. Admin can verify the email address in the system
3. Admin can manually resend credentials
4. Admin can reset the password if needed

### Customer Forgot Password

**Solution:**

1. Customer can use "Forgot Password" feature (if implemented)
2. Or contact admin to reset password manually

### Customer Can't Log In

**Checklist:**

1. ✅ Using the correct email address
2. ✅ Copying password exactly (no extra spaces)
3. ✅ Password is case-sensitive
4. ✅ Account exists in system

---

## 📊 Example Workflow

### Scenario: New Walk-In Customer "John Doe"

1. **Admin creates booking:**

    - Name: John Doe
    - Email: john.doe@example.com
    - Event: Wedding on June 15, 2026

2. **System processes:**

    - ✅ Creates user account for john.doe@example.com
    - ✅ Generates password: `aB3$xY9#mK2!`
    - ✅ Creates booking record
    - ✅ Sends welcome email

3. **John receives email:**

    - Opens email on his phone
    - Sees his login credentials
    - Clicks "Login to Your Account"

4. **John logs in:**

    - Enters email and temporary password
    - Views his wedding booking
    - Changes password to something memorable
    - Bookmarks the page for future access

5. **John can now:**
    - Check booking status anytime
    - Track payments online
    - Receive automated updates
    - Make additional bookings if needed

---

## 🎨 Technical Implementation

### Files Created/Modified:

1. **WalkInCustomerWelcomeMail.php**

    - Mail class for sending welcome emails
    - Located in: `app/Mail/`

2. **walk-in-welcome.blade.php**

    - Email template with credentials and instructions
    - Located in: `resources/views/emails/`

3. **BookingController.php** (Modified)
    - Added temporary password generation
    - Added logic to send welcome emails
    - Enhanced account creation for walk-in customers

### Key Features:

-   Automatic account creation
-   Secure password generation
-   Professional email template
-   Comprehensive user instructions
-   Security best practices

---

## 📝 Notes

-   Temporary passwords are 12 characters long for security
-   Passwords use `Str::random(12)` for generation
-   Each new walk-in customer gets a unique password
-   Existing customers don't receive new credentials
-   All password changes are logged for security

---

## 🎉 Summary

Walk-in customers now have seamless online access to their bookings! The system automatically creates accounts, generates secure passwords, and sends professional welcome emails with all the information customers need to get started.

This improves customer experience and reduces administrative overhead by empowering customers to self-service their booking information.

---

**Need Help?**
Contact the system administrator for any issues or questions about walk-in customer accounts.
