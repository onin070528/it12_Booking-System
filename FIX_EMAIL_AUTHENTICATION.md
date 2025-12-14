# Fix Email Authentication Error

## Problem
You're seeing this error:
```
Failed to authenticate on SMTP server with username "rollyjagonob31@gmail.com"
Error: "535-5.7.8 Username and Password not accepted"
```

## Solution: Get a Gmail App Password

The password in your `.env` file is not being accepted by Gmail. You need to generate a **Gmail App Password**.

### Step 1: Enable 2-Step Verification
1. Go to https://myaccount.google.com/security
2. Under "How you sign in to Google", find **2-Step Verification**
3. If it says "Off", click it and enable it
4. Follow the steps to verify your phone number

### Step 2: Generate App Password
1. Go back to https://myaccount.google.com/security
2. Under "How you sign in to Google", find **2-Step Verification**
3. Scroll down and click **App passwords**
4. You may need to sign in again
5. Under "Select app", choose **Mail**
6. Under "Select device", choose **Other (Custom name)**
7. Type: **Booking Management System**
8. Click **Generate**
9. **Copy the 16-character password** (it looks like: `abcd efgh ijkl mnop`)
   - ⚠️ **IMPORTANT**: Copy this NOW - you won't see it again!

### Step 3: Update .env File
Open your `.env` file and update the password:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=rollyjagonob31@gmail.com
MAIL_PASSWORD=abcd efgh ijkl mnop
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=rollyjagonob31@gmail.com
MAIL_FROM_NAME="Rj's Event Styling and Booking"
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

### Step 5: Test Email
```bash
php artisan test:email your-email@gmail.com
```

## Alternative: Use a Different Email Service

If Gmail continues to cause issues, consider using:

### Mailtrap (for testing):
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
```

### SendGrid (for production):
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
MAIL_ENCRYPTION=tls
```

## Quick Checklist
- [ ] 2-Step Verification is enabled on Google account
- [ ] App Password is generated (16 characters)
- [ ] `.env` file has the App Password (not regular password)
- [ ] Config cache is cleared
- [ ] Test email is sent successfully

