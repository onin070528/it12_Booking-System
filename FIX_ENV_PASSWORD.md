# Fix .env File Password Error

## The Problem

The error `Encountered unexpected whitespace at [koli jnuc cxiy pgzo]` means your `.env` file has spaces in the password value without quotes.

Gmail App Passwords come with spaces like: `koli jnuc cxiy pgzo`

## Solution: Two Options

### Option 1: Remove Spaces (Easiest)

In your `.env` file, remove all spaces from the App Password:

```env
MAIL_PASSWORD=koli jnuc cxiy pgzo
```

Change to:

```env
MAIL_PASSWORD=kolijnuccxiypgzo
```

### Option 2: Use Quotes (Recommended)

Wrap the password value in quotes:

```env
MAIL_PASSWORD="koli jnuc cxiy pgzo"
```

**Note:** Make sure there are NO spaces around the `=` sign:
- ✅ Correct: `MAIL_PASSWORD="koli jnuc cxiy pgzo"`
- ❌ Wrong: `MAIL_PASSWORD = "koli jnuc cxiy pgzo"` (spaces around =)

## Complete .env Mail Section

Your mail configuration should look like this:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=dequitneo@gmail.com
MAIL_PASSWORD="koli jnuc cxiy pgzo"
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=dequitneo@gmail.com
MAIL_FROM_NAME="Booking Management System"
```

Or without spaces:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=dequitneo@gmail.com
MAIL_PASSWORD=kolijnuccxiypgzo
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=dequitneo@gmail.com
MAIL_FROM_NAME="Booking Management System"
```

## After Fixing

1. Save the `.env` file
2. Clear config cache:
   ```bash
   php artisan config:clear
   ```
3. Test again:
   ```bash
   php artisan test:email dequitneo@gmail.com
   ```

## Important Notes

- **No spaces around `=`** in .env files
- App Passwords work with or without spaces - both are valid
- If using quotes, make sure they're straight quotes `"` not curly quotes `"`
- The password should be exactly 16 characters (spaces don't count)

