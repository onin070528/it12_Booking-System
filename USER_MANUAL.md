# RJ's Event Styling - Booking Management System
## Complete User Manual

---

## 📖 Table of Contents

1. [Introduction](#introduction)
2. [System Access](#system-access)
3. [Getting Started](#getting-started)
4. [User Guide (Customers)](#user-guide-customers)
5. [Admin Guide](#admin-guide)
6. [Troubleshooting](#troubleshooting)
7. [FAQ](#faq)

---

## Introduction

Welcome to **RJ's Event Styling Booking Management System**! This platform allows customers to book event styling services for various occasions including weddings, birthdays, debuts, pageants, and more.

### Key Features
- 📅 **Online Booking** - Book events anytime, anywhere
- 💬 **Real-time Messaging** - Chat directly with admins
- 🔔 **Notifications** - Stay updated on your booking status
- 💳 **Payment Tracking** - Monitor payment status
- 📊 **Booking Management** - View and manage all your bookings

---

## System Access

### 🌐 Online Access (Website)

**Primary Website:**
```
https://your-website-domain.com
```

**Fallback (Google Docs) - If Website is Unavailable:**
```
https://docs.google.com/document/d/YOUR_GDOCS_LINK_HERE
```

> ⚠️ **Note:** The website may be temporarily unavailable during trial/maintenance periods. Please use the Google Docs fallback for reference during downtime.

---

### 💻 Localhost Access (For Developers/Local Testing)

#### Prerequisites
- PHP 8.1 or higher
- Composer
- Node.js & NPM
- MySQL/MariaDB database
- Git (optional)

#### Step-by-Step Setup

**1. Clone/Download the Project**
```bash
cd your-desired-folder
git clone [repository-url]
# OR download and extract the ZIP file
```

**2. Install Dependencies**
```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
```

**3. Environment Configuration**
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

**4. Configure Database**
Edit `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=booking_system
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

**5. Run Migrations**
```bash
php artisan migrate
```

**6. Build Assets**
```bash
npm run build
# OR for development with hot-reload:
npm run dev
```

**7. Start the Server**
```bash
php artisan serve
```

**8. Access the Application**
```
http://localhost:8000
```

---

## Getting Started

### Creating an Account

1. Go to the website homepage
2. Click **"Register"** or **"Sign Up"**
3. Fill in your details:
   - First Name
   - Last Name
   - Email Address
   - Phone Number
   - Password (minimum 8 characters)
4. Click **"Create Account"**
5. Wait for admin approval (you'll receive an email notification)

### Logging In

1. Go to the website homepage
2. Click **"Login"**
3. Enter your email and password
4. Click **"Sign In"**

### Forgot Password?

1. On the login page, click **"Forgot Password?"**
2. Enter your registered email address
3. Click **"Send Reset Link"**
4. Check your email for the password reset link
5. Click the link and create a new password

---

## User Guide (Customers)

### 📊 Dashboard

After logging in, you'll see the **Dashboard** with event categories:
- **Wedding** - Elegant styling for your dream wedding
- **Birthday** - Colorful themed setups
- **Pageant** - Stage aesthetics and backdrops
- **Debut** - Stunning 18th birthday celebrations
- **Corporate** - Professional event styling
- **Other Events** - Custom event services

### 📅 Creating a Booking

1. From the Dashboard, click **"Book Now"** on your desired event type
2. Fill in the booking form:

   **Basic Information:**
   - Event Date
   - Event Time
   - Location/Venue
   - Number of Guests

   **Event-Specific Details:**
   - For **Wedding**: Bride & Groom names, theme preference
   - For **Birthday**: Celebrant name, age, theme
   - For **Debut**: Debutante name, theme, cotillion details
   - For **Pageant**: Event name, number of contestants
   - For **Corporate**: Company name, event purpose

   **Additional Options:**
   - Special requests or notes
   - Design preferences

3. Review your booking details
4. Click **"Submit Booking"**

### 📋 My Bookings

View all your bookings by clicking **"My Bookings"** in the sidebar.

**Booking Statuses:**
| Status | Description |
|--------|-------------|
| 🟡 **Pending** | Booking submitted, awaiting admin review |
| 🔵 **Confirmed** | Booking approved, schedule confirmed |
| 🟠 **Pending Payment** | Awaiting your payment |
| 🟢 **Partial Payment** | Down payment received |
| 🎨 **In Design** | Event design in progress |
| ✅ **Completed** | Event successfully completed |
| 🔴 **Cancelled** | Booking was cancelled |
| ❌ **Rejected** | Booking was declined |

### 💬 Messaging

Communicate with the admin team:
1. Click **"Messages"** in the sidebar
2. Type your message in the text box
3. Click **"Send"**
4. View admin responses in real-time

### 🔔 Notifications

Stay updated on your booking status:
1. Click the **bell icon** in the header
2. View recent notifications
3. Click a notification to see details
4. Click **"Mark All as Read"** to clear notifications

### 💳 Payments

Track and manage your payments:
1. Go to **"Payments"** in the sidebar
2. View payment history and status
3. When payment is requested:
   - Click on the booking
   - Follow payment instructions
   - Upload payment confirmation/screenshot

### 👤 Profile Settings

Update your profile:
1. Click your name/avatar in the header
2. Select **"Profile"**
3. Update your information:
   - Name
   - Email
   - Phone Number
   - Password
4. Click **"Save Changes"**

### ❌ Cancelling a Booking

**Before Payment:**
1. Go to **"My Bookings"**
2. Find the booking you want to cancel
3. Click **"Cancel Booking"**
4. Confirm cancellation

**After Payment:**
1. Go to **"My Bookings"**
2. Click **"Request Cancellation"**
3. Provide a reason
4. Wait for admin approval

---

## Admin Guide

### 🔐 Admin Login

1. Access the login page
2. Enter your admin credentials
3. You'll be redirected to the Admin Dashboard

### 📊 Admin Dashboard

The dashboard shows:
- **Total Bookings** - All bookings count
- **Pending Bookings** - Awaiting review
- **Completed Bookings** - Successfully finished
- **Revenue Overview** - Payment statistics
- **Recent Activity** - Latest system actions

### 📅 Calendar View

1. Click **"Calendar"** in the sidebar
2. View all scheduled events by date
3. Click on a date to see event details
4. Use filters to show specific event types

### 📋 Booking Management

**View All Bookings:**
1. Click **"Bookings"** in the sidebar
2. Use filters: Status, Date Range, Event Type
3. Search by customer name or booking ID

**Process a Booking:**
1. Click on a booking to view details
2. Available actions:
   - **Confirm** - Approve the booking
   - **Schedule Meetup** - Set consultation date
   - **Mark for Payment** - Request payment
   - **Mark Partial Paid** - Record down payment
   - **Mark Paid** - Confirm full payment
   - **In Design** - Start design phase
   - **Complete** - Mark as finished
   - **Reject** - Decline the booking
   - **Cancel** - Cancel the booking

### 🚶 Walk-in Booking

Create bookings for walk-in customers:
1. Click **"Walk-in Booking"** in the sidebar
2. Enter client information:
   - Name
   - Email
   - Phone Number
3. Fill in event details
4. Click **"Submit"**
5. System auto-creates customer account

### 📦 Inventory Management

Track event materials and equipment:
1. Click **"Inventory"** in the sidebar
2. **Add New Item:**
   - Click "Add Item"
   - Enter name, quantity, unit
   - Click "Save"
3. **Assign to Booking:**
   - Open a booking
   - Click "Assign Inventory"
   - Select items and quantities
4. **Return Items:**
   - After event, mark items as returned
   - Note any damages

### 💰 Payment Management

1. Click **"Payments"** in the sidebar
2. View all payment records
3. Filter by status: Pending, Partial, Paid
4. Click on payment to view details

### 👥 User Management

1. Click **"Users"** in the sidebar
2. View all registered users
3. **Approve New Users:**
   - Find pending accounts
   - Click "Approve" or "Reject"
4. **Archive Users:**
   - Click on a user
   - Click "Archive" to deactivate

### 📊 Reports

1. Click **"Reports"** in the sidebar
2. Generate reports:
   - Booking statistics
   - Revenue reports
   - Event type breakdown
3. Export to PDF/Excel

### 💬 Admin Messages

1. Click **"Messages"** in the sidebar
2. View conversations with all users
3. Select a user to view/reply to messages

---

## Troubleshooting

### Common Issues

**Can't Login?**
- Verify your email and password
- Check if your account is approved
- Try "Forgot Password" to reset

**Booking Not Submitting?**
- Ensure all required fields are filled
- Check your internet connection
- Try refreshing the page

**Not Receiving Emails?**
- Check your spam/junk folder
- Verify your email address is correct
- Contact admin if issues persist

**Page Not Loading?**
- Clear browser cache
- Try a different browser
- Check internet connection

### Localhost Issues

**Database Connection Error:**
```bash
# Verify MySQL is running
# Check .env database credentials
php artisan config:clear
php artisan cache:clear
```

**Vite/Asset Errors:**
```bash
npm run build
# OR
npm run dev
```

**Permission Errors:**
```bash
chmod -R 775 storage bootstrap/cache
```

---

## FAQ

**Q: How long does booking approval take?**
A: Usually within 24-48 hours during business days.

**Q: Can I modify my booking after submission?**
A: Contact admin through the messaging system to request changes.

**Q: What payment methods are accepted?**
A: Contact admin for available payment options.

**Q: Is my data secure?**
A: Yes, we use industry-standard security practices to protect your information.

**Q: How do I cancel my account?**
A: Contact admin to request account deletion.

---

## Support Contact

For additional support:
- 📧 **Email:** support@rjseventstyling.com
- 📱 **Phone:** [Your Contact Number]
- 💬 **In-App:** Use the messaging feature

---

*Last Updated: December 2024*
*Version 1.0*
