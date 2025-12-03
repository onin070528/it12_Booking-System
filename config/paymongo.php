<?php

return [
    /*
    |--------------------------------------------------------------------------
    | PayMongo Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for PayMongo payment gateway integration.
    | Get your API keys from: https://dashboard.paymongo.com/
    |
    */

    'secret_key' => env('PAYMONGO_SECRET_KEY'),
    'public_key' => env('PAYMONGO_PUBLIC_KEY'),
    'webhook_secret' => env('PAYMONGO_WEBHOOK_SECRET'),
    'base_url' => env('PAYMONGO_BASE_URL', 'https://api.paymongo.com/v1'),
];

