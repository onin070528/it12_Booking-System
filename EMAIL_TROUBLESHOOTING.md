# Email Troubleshooting Guide

## Quick Diagnosis

Run this command to test your email configuration:
```bash
php artisan test:email your-email@gmail.com
```

## Common Issues and Solutions

### 1. Emails Not Sending

**Check 1: Mail Configuration**
```bash
php artisan config:show mail.default
```
Should show `smtp`, not `log`.

**Check 2: .env File**
Make sure your `.env` file has these settings:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="Your App Name"
```

**Check 3: Clear Config Cache**
```bash
php artisan config:clear
php artisan cache:clear
```

### 2. Gmail Specific Issues

**Problem: "Username and Password not accepted"**

**Solution:**
1. Enable 2-Step Verification on your Google account
2. Go to Google Account → Security → 2-Step Verification → App passwords
3. Generate a new App Password for "Mail"
4. Use this App Password (16 characters) in `MAIL_PASSWORD`, NOT your regular Gmail password

**Problem: "Less secure app access"**

**Solution:**
- Gmail no longer supports "less secure apps"
- You MUST use App Passwords (see above)

### 3. Emails Going to Spam

**Solutions:**
- Check spam/junk folder
- Add the sender email to your contacts
- Use a professional email service (SendGrid, Mailgun) instead of Gmail for production
- Set up SPF and DKIM records for your domain

### 4. DNS/Email Validation Errors

**Problem: Email validation fails but email is valid**

**Solution:**
- The system now only checks basic format, not DNS records
- DNS checks can fail for valid emails (especially Gmail)
- Emails will still be sent even if DNS validation fails (with a warning logged)

### 5. Check Logs

Always check `storage/logs/laravel.log` for detailed error messages:

```bash
tail -f storage/logs/laravel.log
```

Look for lines containing:
- "Failed to send email"
- "Email notification sent"
- SMTP connection errors

### 6. Testing Email Configuration

**Method 1: Use the test command**
```bash
php artisan test:email your-email@gmail.com
```

**Method 2: Check mail configuration**
```bash
php artisan config:show mail
```

**Method 3: Test in code**
Add this to a controller temporarily:
```php
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;

Mail::to('test@example.com')->send(new WelcomeMail(auth()->user()));
```

### 7. Alternative Email Services

If Gmail doesn't work, try these alternatives:

**Mailtrap (Testing):**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
```

**SendGrid:**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
MAIL_ENCRYPTION=tls
```

### 8. Queue Configuration

If emails are slow, use queues:

1. Set in `.env`:
```env
QUEUE_CONNECTION=database
```

2. Run migrations:
```bash
php artisan queue:table
php artisan migrate
```

3. Start queue worker:
```bash
php artisan queue:work
```

### 9. Common Error Messages

**"Connection could not be established"**
- Check MAIL_HOST and MAIL_PORT
- Verify firewall/network allows SMTP connections
- Try port 465 with SSL instead of 587 with TLS

**"Authentication failed"**
- Check MAIL_USERNAME and MAIL_PASSWORD
- For Gmail, use App Password, not regular password
- Verify credentials are correct

**"Could not instantiate mailer"**
- Check all MAIL_* variables in .env
- Clear config cache: `php artisan config:clear`

### 10. Verification Checklist

- [ ] MAIL_MAILER is set to `smtp` in .env
- [ ] MAIL_HOST is correct for your email provider
- [ ] MAIL_PORT matches your provider (587 for TLS, 465 for SSL)
- [ ] MAIL_USERNAME is your full email address
- [ ] MAIL_PASSWORD is correct (App Password for Gmail)
- [ ] MAIL_ENCRYPTION matches port (tls for 587, ssl for 465)
- [ ] MAIL_FROM_ADDRESS is set
- [ ] Config cache is cleared
- [ ] Test email command works
- [ ] Check spam folder
- [ ] Check Laravel logs for errors

## Still Having Issues?

1. Check `storage/logs/laravel.log` for specific error messages
2. Run `php artisan test:email your-email@example.com` and check the output
3. Verify your email provider's SMTP settings
4. Try a different email provider (Mailtrap for testing)
5. Check if your server/hosting allows outbound SMTP connections

