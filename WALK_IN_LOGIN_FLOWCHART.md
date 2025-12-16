# Walk-In Customer Login Flow

## 📊 Visual Process Flow

```
┌─────────────────────────────────────────────────────────────────────┐
│                    ADMIN CREATES WALK-IN BOOKING                    │
│                                                                     │
│  • Enters client name, email, phone                                │
│  • Fills in event details (type, date, time, location)             │
│  • Clicks "Submit Booking"                                          │
└───────────────────────────────┬─────────────────────────────────────┘
                                │
                                ▼
┌─────────────────────────────────────────────────────────────────────┐
│                    SYSTEM CHECKS EMAIL                              │
│                                                                     │
│              Does this email already exist?                         │
└───────────────┬───────────────────────────────────┬─────────────────┘
                │                                   │
          ✗ NO │                                   │ YES ✓
                │                                   │
                ▼                                   ▼
┌───────────────────────────────┐   ┌──────────────────────────────┐
│    CREATE NEW ACCOUNT         │   │   USE EXISTING ACCOUNT       │
│                               │   │                              │
│  ✓ Generate user account     │   │  ✓ Link booking to existing  │
│  ✓ Create random password    │   │    account                   │
│    (12 characters)            │   │  ✓ No password generated     │
│  ✓ Hash and store password   │   │  ✓ No welcome email sent     │
│  ✓ Save user to database     │   │                              │
└───────────────┬───────────────┘   └──────────────┬───────────────┘
                │                                   │
                ▼                                   │
┌───────────────────────────────┐                  │
│    SEND WELCOME EMAIL         │                  │
│                               │                  │
│  To: customer@email.com       │                  │
│  Subject: Welcome!            │                  │
│                               │                  │
│  Email Contains:              │                  │
│  📧 Login email               │                  │
│  🔐 Temporary password        │                  │
│  📋 Booking details           │                  │
│  🔗 Login page link           │                  │
│  📝 Step-by-step instructions │                  │
└───────────────┬───────────────┘                  │
                │                                   │
                └───────────────┬───────────────────┘
                                │
                                ▼
┌─────────────────────────────────────────────────────────────────────┐
│                    BOOKING CREATED                                  │
│                                                                     │
│  ✓ Booking saved in database                                       │
│  ✓ Admin notifications sent                                        │
│  ✓ Success message displayed                                       │
└───────────────────────────────┬─────────────────────────────────────┘
                                │
                                ▼
┌─────────────────────────────────────────────────────────────────────┐
│                    CUSTOMER RECEIVES EMAIL                          │
│                                                                     │
│  📧 Customer checks their inbox                                    │
│  📱 Opens welcome email                                            │
│  👀 Reads login credentials                                        │
└───────────────────────────────┬─────────────────────────────────────┘
                                │
                                ▼
┌─────────────────────────────────────────────────────────────────────┐
│                    CUSTOMER LOGS IN                                 │
│                                                                     │
│  1. Clicks "Login to Your Account" button in email                 │
│  2. Enters email address                                           │
│  3. Copies and pastes temporary password                           │
│  4. Clicks "Login"                                                 │
└───────────────────────────────┬─────────────────────────────────────┘
                                │
                                ▼
┌─────────────────────────────────────────────────────────────────────┐
│                    CUSTOMER DASHBOARD                               │
│                                                                     │
│  Customer can now:                                                 │
│  📋 View their booking(s)                                          │
│  💰 Check payment status                                           │
│  🔔 Receive notifications                                          │
│  📅 Create new bookings                                            │
│  👤 Update profile                                                 │
│  🔐 Change password (recommended!)                                 │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 🔄 Password Change Flow (Recommended)

```
┌─────────────────────────────────────────────────────────────────────┐
│                    CUSTOMER LOGGED IN                               │
└───────────────────────────────┬─────────────────────────────────────┘
                                │
                                ▼
┌─────────────────────────────────────────────────────────────────────┐
│                    GO TO PROFILE SETTINGS                           │
│                                                                     │
│  • Click on profile/settings icon                                  │
│  • Navigate to "Change Password" section                           │
└───────────────────────────────┬─────────────────────────────────────┘
                                │
                                ▼
┌─────────────────────────────────────────────────────────────────────┐
│                    CHANGE PASSWORD                                  │
│                                                                     │
│  1. Enter current (temporary) password                             │
│  2. Enter new password                                             │
│  3. Confirm new password                                           │
│  4. Click "Update Password"                                        │
└───────────────────────────────┬─────────────────────────────────────┘
                                │
                                ▼
┌─────────────────────────────────────────────────────────────────────┐
│                    PASSWORD UPDATED                                 │
│                                                                     │
│  ✓ New password saved securely                                     │
│  ✓ Can now use new password for future logins                      │
│  ✓ Account is more secure                                          │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 📧 Email Flow Details

### NEW Customer Email Journey

```
Admin Creates Booking
        ↓
System Generates Password: "aB3$xY9#mK2!"
        ↓
System Sends Email
        ↓
Customer Inbox: "Welcome! Your Account Has Been Created"
        ↓
Customer Opens Email
        ↓
Email Shows:
  ┌──────────────────────────────────┐
  │ 🔐 Your Login Credentials        │
  │                                  │
  │ Email: john@example.com          │
  │ Password: aB3$xY9#mK2!          │
  │                                  │
  │ [Login to Your Account →]       │
  └──────────────────────────────────┘
        ↓
Customer Clicks Button
        ↓
Redirected to Login Page
        ↓
Enters Credentials
        ↓
Successfully Logged In! 🎉
```

---

## 🎯 Decision Points

### For System

```
Email exists in database?
    │
    ├── NO  → Create account + Send email
    │
    └── YES → Use existing account + No email
```

### For Customer

```
Received email?
    │
    ├── YES → Login with temporary password
    │         └→ Change password
    │            └→ Full access ✓
    │
    └── NO  → Check spam folder
              │
              ├── Found → Proceed as above
              │
              └── Not found → Contact admin
                             └→ Admin resends or resets
```

---

## 💼 Business Logic Flow

```
Walk-In Customer Arrives
        ↓
Admin Takes Booking Information
        ↓
Admin Enters Data in System
        ↓
┌───────────────────────────┐
│   System Automation       │
│                           │
│ ✓ Create user account     │
│ ✓ Generate secure password│
│ ✓ Create booking record   │
│ ✓ Send email to customer  │
│ ✓ Notify admins           │
└───────────────────────────┘
        ↓
Customer Leaves with:
  • Physical booking confirmation
  • Knowledge that email is coming
  • Ability to track online
        ↓
Customer Accesses Online (Later)
  • Checks booking status
  • Makes payments
  • Books future events
        ↓
Reduced Admin Workload
  • Fewer "status check" calls
  • Customers self-service
  • Professional experience
```

---

## 🔐 Security Flow

```
Password Generation
        ↓
Random 12-character string generated
        ↓
Password: "aB3$xY9#mK2!"
        ↓
Hashed with bcrypt
        ↓
Stored in database: "$2y$10$abc..."
        ↓
Sent in plain text via email (one time only)
        ↓
Customer receives and uses
        ↓
Customer changes password (recommended)
        ↓
New password hashed and stored
        ↓
Old temporary password no longer valid
        ↓
Secure account ✓
```

---

## 📊 Data Flow

```
┌──────────────┐
│ Admin Input  │
└──────┬───────┘
       │ • Client name
       │ • Client email
       │ • Client phone
       │ • Event details
       ▼
┌──────────────┐
│   System     │
└──────┬───────┘
       │ • Validates data
       │ • Checks for existing email
       │ • Generates password (if new)
       │ • Creates user record
       │ • Creates booking record
       ▼
┌──────────────┐
│   Database   │
└──────┬───────┘
       │ • User table updated
       │ • Booking table updated
       │ • Notification table updated
       ▼
┌──────────────┐
│   Email      │
└──────┬───────┘
       │ • Welcome email sent
       │ • Credentials provided
       ▼
┌──────────────┐
│   Customer   │
└──────┬───────┘
       │ • Receives email
       │ • Logs in
       │ • Views dashboard
       ▼
┌──────────────┐
│   Success!   │
└──────────────┘
```

---

## 🎨 User Journey Map

```
BEFORE: Walk-In Booking Without Online Access
────────────────────────────────────────────────
Customer walks in → Books event → Leaves → Has to call/visit to check status

AFTER: Walk-In Booking With Online Access
──────────────────────────────────────────
Customer walks in → Books event → Receives email → Logs in →
Checks status anytime → Books again online → Happy customer! 😊
```

---

## ✅ System Status Indicators

```
✅ Account Created     = User record in database
✅ Password Generated  = Random secure password created
✅ Email Sent          = Welcome email in customer inbox
✅ Can Login           = Customer can access system
✅ Booking Visible     = Shows in customer dashboard
✅ Fully Functional    = Customer has complete access
```

---

This flowchart provides a visual representation of the entire walk-in customer login process, making it easy to understand and troubleshoot!
