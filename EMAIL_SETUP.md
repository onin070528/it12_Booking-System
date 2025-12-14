# Email Notification System Setup Guide

This guide will help you configure the email notification system for the Booking Management System.

## Features

1. **Email Notifications**: Automatic email notifications are sent when:
   - A new booking is created (notifies admins)
   - A booking is confirmed (notifies customer)
   - A booking is ready for payment (notifies customer)
   - Payment is received (notifies customer)
   - Booking status changes (notifies customer)
   - Payment is submitted (notifies admins)

2. **Email Validation**: 
   - Validates email format
   - Checks if email domain exists (MX records)
   - Validates during user registration

## Configuration

### 1. Mail Configuration

Edit your `.env` file and configure the mail settings:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

### 2. Gmail Setup (Example)

If using Gmail:

1. Enable 2-Step Verification on your Google account
2. Generate an App Password:
   - Go to Google Account settings
   - Security → 2-Step Verification → App passwords
   - Generate a password for "Mail"
   - Use this password in `MAIL_PASSWORD`

### 3. Other Email Providers

#### Mailtrap (for testing):
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
```

#### SendGrid:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
MAIL_ENCRYPTION=tls
```

#### Mailgun:
```env
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=your-domain.com
MAILGUN_SECRET=your-mailgun-secret
```

## Testing Email Configuration

### Test Email Sending

You can test if emails are working by:

1. Creating a new booking
2. Confirming a booking
3. Submitting a payment

All these actions will trigger email notifications.

### Test Email Validation

The email validation service checks:
- Email format validity
- Domain existence (MX records)
- Domain DNS records

## Email Templates

Email templates are located in `resources/views/emails/booking-notification.blade.php`

You can customize the email design by editing this file.

## Troubleshooting

### Emails not sending

1. Check your `.env` mail configuration
2. Verify SMTP credentials are correct
3. Check Laravel logs: `storage/logs/laravel.log`
4. For Gmail, ensure you're using an App Password, not your regular password

### Email validation failing

- The validation checks DNS records, so ensure the email domain has proper MX records
- Some email providers may have strict validation - check logs for specific errors

### Queue Configuration (Optional)

For better performance, you can queue emails:

1. Set up a queue driver in `.env`:
```env
QUEUE_CONNECTION=database
```

2. Run queue worker:
```bash
php artisan queue:work
```

3. Update `BookingNotificationMail` to implement `ShouldQueue` interface (already included)

## Files Created/Modified

- `app/Services/EmailValidationService.php` - Email validation logic
- `app/Services/EmailNotificationService.php` - Email sending service
- `app/Mail/BookingNotificationMail.php` - Mailable class
- `resources/views/emails/booking-notification.blade.php` - Email template
- `app/Http/Controllers/BookingController.php` - Integrated email sending
- `app/Http/Controllers/PaymentController.php` - Integrated email sending
- `app/Http/Controllers/Auth/RegisteredUserController.php` - Added email validation

## Notes

- Email validation uses DNS checks which may take a moment
- For production, consider using a dedicated email validation API service
- Email sending is logged for debugging purposes
- Invalid emails are logged but don't block the notification system

