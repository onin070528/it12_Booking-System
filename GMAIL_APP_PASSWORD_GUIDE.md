# Gmail App Password Setup Guide

## The Error You're Seeing

```
Application-specific password required
```

This means Gmail requires an **App Password**, not your regular Gmail password.

## Step-by-Step: Get Gmail App Password

### Step 1: Enable 2-Step Verification (if not already enabled)

1. Go to https://myaccount.google.com/
2. Click **Security** (left sidebar)
3. Under "How you sign in to Google", find **2-Step Verification**
4. If it says "Off", click it and follow the steps to enable it
5. You'll need to verify your phone number

### Step 2: Generate App Password

1. Go back to https://myaccount.google.com/security
2. Under "How you sign in to Google", find **2-Step Verification**
3. Scroll down and click **App passwords**
   - If you don't see "App passwords", make sure 2-Step Verification is enabled first
4. You may need to sign in again
5. Under "Select app", choose **Mail**
6. Under "Select device", choose **Other (Custom name)**
7. Type: **Booking Management System** (or any name you want)
8. Click **Generate**
9. **Copy the 16-character password** (it will look like: `abcd efgh ijkl mnop`)
   - ⚠️ **IMPORTANT**: Copy this password NOW - you won't be able to see it again!

### Step 3: Update Your .env File

Open your `.env` file and update these lines:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=dequitneo@gmail.com
MAIL_PASSWORD=abcd efgh ijkl mnop
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=dequitneo@gmail.com
MAIL_FROM_NAME="Booking Management System"
```

**Important Notes:**
- Use the **16-character App Password** you just generated
- You can include or remove spaces - both work
- This is NOT your regular Gmail password
- The App Password is 16 characters (with or without spaces)

### Step 4: Clear Config Cache

```bash
php artisan config:clear
php artisan cache:clear
```

### Step 5: Test Again

```bash
php artisan test:email dequitneo@gmail.com
```

## Quick Reference

| What | Value |
|------|-------|
| **MAIL_USERNAME** | Your Gmail address (dequitneo@gmail.com) |
| **MAIL_PASSWORD** | 16-character App Password (NOT your regular password) |
| **MAIL_HOST** | smtp.gmail.com |
| **MAIL_PORT** | 587 |
| **MAIL_ENCRYPTION** | tls |

## Troubleshooting

### "App passwords" option not showing
- Make sure 2-Step Verification is enabled
- Wait a few minutes after enabling 2-Step Verification
- Try refreshing the page

### Still getting authentication errors
1. Double-check you copied the App Password correctly (no extra spaces)
2. Make sure you cleared config cache: `php artisan config:clear`
3. Verify the App Password in Google Account → Security → App passwords
4. Try generating a new App Password

### Alternative: Use a Different Email Service

If Gmail continues to cause issues, consider using:

**Mailtrap (for testing):**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
```

**SendGrid (for production):**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
MAIL_ENCRYPTION=tls
```

## Visual Guide

```
Google Account
  └── Security
      └── 2-Step Verification
          └── App passwords
              └── Generate new
                  └── Select: Mail
                  └── Select: Other (Custom name)
                  └── Name: Booking Management System
                  └── Generate
                      └── Copy 16-character password
                          └── Paste in .env file
```

## Security Note

- App Passwords are safer than using your regular password
- You can revoke App Passwords anytime from Google Account settings
- Each App Password is unique and can be deleted independently
- If you suspect a password is compromised, revoke it and generate a new one

