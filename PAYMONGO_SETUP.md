# PayMongo Integration Setup Guide

This guide will help you set up PayMongo payment gateway in your Laravel booking management system.

## Prerequisites

1. A PayMongo account - Sign up at https://dashboard.paymongo.com/
2. API keys from PayMongo dashboard

## Step 1: Get PayMongo API Keys

1. Log in to your PayMongo dashboard: https://dashboard.paymongo.com/
2. Navigate to **Developers** > **API Keys**
3. Copy your **Secret Key** and **Public Key**
   - For testing, use **Test Keys**
   - For production, use **Live Keys**

## Step 2: Configure Environment Variables

Add the following to your `.env` file:

```env
# PayMongo Configuration
PAYMONGO_SECRET_KEY=sk_test_your_secret_key_here
PAYMONGO_PUBLIC_KEY=pk_test_your_public_key_here
PAYMONGO_WEBHOOK_SECRET=whsec_your_webhook_secret_here
PAYMONGO_BASE_URL=https://api.paymongo.com/v1
```

**Important Notes:**
- Replace `sk_test_` with your actual test secret key
- Replace `pk_test_` with your actual test public key
- For production, use `sk_live_` and `pk_live_` keys
- The webhook secret is obtained when creating a webhook endpoint

## Step 3: Set Up Webhook (Important for Payment Verification)

1. In PayMongo dashboard, go to **Developers** > **Webhooks**
2. Click **Create Webhook**
3. Set the webhook URL to: `https://yourdomain.com/webhook/paymongo`
4. Select events to listen for:
   - `payment.paid`
   - `payment.failed`
5. Copy the **Webhook Secret** and add it to your `.env` file

**For Local Development:**
- Use a tool like ngrok to expose your local server
- Example: `ngrok http 8000`
- Use the ngrok URL for webhook: `https://your-ngrok-url.ngrok.io/webhook/paymongo`

## Step 4: Test the Integration

### Test Payment Flow:

1. Create a booking in your system
2. Navigate to checkout: `/booking/{booking_id}/checkout`
3. Select a payment method (GCash, GrabPay, PayMaya, or Card)
4. Complete the payment process

### Test Cards (for Card Payments):

PayMongo provides test card numbers:
- **Success**: `4242 4242 4242 4242`
- **Decline**: `4000 0000 0000 0002`
- Use any future expiry date and any 3-digit CVC

## Step 5: Database Migration

The migrations have already been created. Run:

```bash
php artisan migrate
```

This will create:
- `bookings` table
- `payments` table

## Supported Payment Methods

1. **GCash** - E-wallet payment via redirect
2. **GrabPay** - E-wallet payment via redirect
3. **PayMaya** - E-wallet payment via redirect
4. **Credit/Debit Card** - Direct card payment

## Payment Flow

1. User creates a booking
2. User is redirected to checkout page
3. User selects payment method
4. For e-wallets: User is redirected to PayMongo checkout
5. For cards: User enters card details on your site
6. Payment is processed
7. Webhook updates payment status
8. User sees success/failure page

## Troubleshooting

### Payment Not Processing

1. Check your API keys are correct in `.env`
2. Verify webhook URL is accessible
3. Check Laravel logs: `storage/logs/laravel.log`
4. Verify PayMongo dashboard for payment status

### Webhook Not Working

1. Ensure webhook URL is publicly accessible
2. Verify webhook secret matches in `.env`
3. Check webhook logs in PayMongo dashboard
4. Verify CSRF protection is disabled for webhook route (already done)

## Production Checklist

- [ ] Switch to Live API keys
- [ ] Update webhook URL to production domain
- [ ] Test all payment methods
- [ ] Set up proper error handling
- [ ] Configure email notifications
- [ ] Set up payment receipts

## Support

For PayMongo API documentation, visit: https://developers.paymongo.com/

For issues with this integration, check the Laravel logs and PayMongo dashboard.

