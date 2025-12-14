<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EmailValidationService
{
    /**
     * Validate if an email address exists and is valid
     * 
     * @param string $email
     * @return array ['valid' => bool, 'exists' => bool, 'message' => string]
     */
    public function validateEmail(string $email): array
    {
        // First, do basic format validation
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                'valid' => false,
                'exists' => false,
                'message' => 'Invalid email format'
            ];
        }

        // Extract domain from email
        $domain = substr(strrchr($email, "@"), 1);
        
        // Check if domain has MX records (mail exchange records)
        if (!checkdnsrr($domain, 'MX')) {
            return [
                'valid' => false,
                'exists' => false,
                'message' => 'Email domain does not exist or has no mail servers'
            ];
        }

        // Additional validation: Check if domain has A or AAAA records
        if (!checkdnsrr($domain, 'A') && !checkdnsrr($domain, 'AAAA')) {
            return [
                'valid' => false,
                'exists' => false,
                'message' => 'Email domain is not valid'
            ];
        }

        // Note: Actual email existence verification (checking if the specific email address exists)
        // requires SMTP connection which can be slow and may be blocked by mail servers.
        // For production, consider using a service like:
        // - ZeroBounce API
        // - EmailListVerify API
        // - NeverBounce API
        
        return [
            'valid' => true,
            'exists' => true, // We can't verify actual existence without SMTP, but domain is valid
            'message' => 'Email format and domain are valid'
        ];
    }

    /**
     * Quick validation check (returns boolean)
     * 
     * @param string $email
     * @return bool
     */
    public function isValid(string $email): bool
    {
        $result = $this->validateEmail($email);
        return $result['valid'];
    }

    /**
     * Validate email using external API (optional, requires API key)
     * Uncomment and configure if you want to use an external service
     */
    /*
    public function validateEmailWithAPI(string $email): array
    {
        try {
            $response = Http::timeout(5)->get('https://api.emailvalidation.io/v1/info', [
                'apikey' => env('EMAIL_VALIDATION_API_KEY'),
                'email' => $email
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'valid' => $data['format_valid'] ?? false,
                    'exists' => $data['mx_found'] ?? false,
                    'message' => $data['message'] ?? 'Validation completed'
                ];
            }
        } catch (\Exception $e) {
            Log::warning('Email validation API error: ' . $e->getMessage());
        }

        // Fallback to basic validation
        return $this->validateEmail($email);
    }
    */
}

