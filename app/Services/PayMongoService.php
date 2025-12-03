<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayMongoService
{
    private ?string $secretKey;
    private ?string $publicKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->secretKey = config('paymongo.secret_key');
        $this->publicKey = config('paymongo.public_key');
        $this->baseUrl = config('paymongo.base_url', 'https://api.paymongo.com/v1');
    }

    /**
     * Check if PayMongo is configured
     */
    private function isConfigured(): bool
    {
        return !empty($this->secretKey) && !empty($this->publicKey);
    }

    /**
     * Create a payment intent
     */
    public function createPaymentIntent(float $amount, string $currency = 'PHP', array $metadata = [])
    {
        if (!$this->isConfigured()) {
            Log::error('PayMongo not configured: Missing API keys');
            return null;
        }

        try {
            $response = Http::withBasicAuth($this->secretKey, '')
                ->post("{$this->baseUrl}/payment_intents", [
                    'data' => [
                        'attributes' => [
                            'amount' => (int)($amount * 100), // Convert to centavos
                            'currency' => $currency,
                            'payment_method_allowed' => [
                                'card',
                                'paymaya',
                                'gcash',
                                'grab_pay',
                            ],
                            'metadata' => $metadata,
                        ],
                    ],
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('PayMongo Payment Intent Error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('PayMongo Service Exception', [
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Attach payment method to payment intent
     */
    public function attachPaymentMethod(string $paymentIntentId, string $paymentMethodId)
    {
        if (!$this->isConfigured()) {
            Log::error('PayMongo not configured: Missing API keys');
            return null;
        }

        try {
            $response = Http::withBasicAuth($this->secretKey, '')
                ->post("{$this->baseUrl}/payment_intents/{$paymentIntentId}/attach", [
                    'data' => [
                        'attributes' => [
                            'payment_method' => $paymentMethodId,
                            'return_url' => route('payment.return'),
                        ],
                    ],
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('PayMongo Attach Payment Method Error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('PayMongo Attach Exception', [
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Create a payment source (for redirect-based payments like GCash, GrabPay)
     */
    public function createSource(float $amount, string $currency = 'PHP', string $type = 'gcash', array $metadata = [])
    {
        if (!$this->isConfigured()) {
            Log::error('PayMongo not configured: Missing API keys');
            return null;
        }

        try {
            $response = Http::withBasicAuth($this->secretKey, '')
                ->post("{$this->baseUrl}/sources", [
                    'data' => [
                        'attributes' => [
                            'amount' => (int)($amount * 100), // Convert to centavos
                            'currency' => $currency,
                            'type' => $type,
                            'redirect' => [
                                'success' => route('payment.success'),
                                'failed' => route('payment.failed'),
                            ],
                            'metadata' => $metadata,
                        ],
                    ],
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('PayMongo Source Error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('PayMongo Source Exception', [
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Retrieve payment intent
     */
    public function retrievePaymentIntent(string $paymentIntentId)
    {
        if (!$this->isConfigured()) {
            Log::error('PayMongo not configured: Missing API keys');
            return null;
        }

        try {
            $response = Http::withBasicAuth($this->secretKey, '')
                ->get("{$this->baseUrl}/payment_intents/{$paymentIntentId}");

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error('PayMongo Retrieve Exception', [
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Verify webhook signature
     */
    public function verifyWebhookSignature(string $signature, string $payload): bool
    {
        $webhookSecret = config('paymongo.webhook_secret');
        
        if (!$webhookSecret) {
            return false;
        }

        $computedSignature = hash_hmac('sha256', $payload, $webhookSecret);
        
        return hash_equals($signature, $computedSignature);
    }
}
