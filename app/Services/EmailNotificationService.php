<?php

namespace App\Services;

use App\Mail\BookingNotificationMail;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailNotificationService
{
    protected $emailValidationService;

    public function __construct(EmailValidationService $emailValidationService)
    {
        $this->emailValidationService = $emailValidationService;
    }

    /**
     * Send email notification to a user
     * 
     * @param User $user
     * @param string $notificationType
     * @param string $message
     * @param array $data
     * @param Booking|null $booking
     * @return bool
     */
    public function sendNotification(User $user, string $notificationType, string $message, array $data = [], ?Booking $booking = null): bool
    {
        // Log that the method was called
        Log::info("=== EMAIL NOTIFICATION SERVICE CALLED ===");
        Log::info("User ID: {$user->id}, Name: {$user->name}, Email: {$user->email}");
        Log::info("Notification Type: {$notificationType}");
        
        // Check if user has email
        if (empty($user->email)) {
            Log::error("User {$user->id} has no email address!");
            return false;
        }

        // Basic email format validation (don't block on DNS checks - they can fail for valid emails)
        if (!filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
            Log::warning("Invalid email format for user {$user->id}: {$user->email}");
            return false;
        }

        // Check mail configuration
        $mailer = config('mail.default');
        $host = config('mail.mailers.smtp.host');
        $username = config('mail.mailers.smtp.username');
        $password = config('mail.mailers.smtp.password');
        
        Log::info("Mail Configuration - Mailer: {$mailer}, Host: {$host}, Username: " . ($username ?: 'NOT SET'));
        
        // Check for placeholder values
        if (str_contains($username, 'your-email') || str_contains($password, 'your-app-password')) {
            Log::error("PLACEHOLDER VALUES DETECTED in mail configuration! Username or password contains placeholder text.");
            Log::error("Please update your .env file with real email credentials.");
            return false;
        }

        // Check if password might be regular password instead of App Password
        // Gmail App Passwords are 16 characters (with or without spaces)
        if ($host === 'smtp.gmail.com' && $password && strlen(str_replace(' ', '', $password)) !== 16) {
            Log::warning("Gmail password length is not 16 characters. Gmail requires App Passwords (16 chars), not regular passwords.");
            Log::warning("If you're using your regular Gmail password, you need to generate an App Password.");
            Log::warning("See: https://support.google.com/accounts/answer/185833");
        }

        // Optional: Check domain (but don't block if it fails - just log a warning)
        $emailValidation = $this->emailValidationService->validateEmail($user->email);
        if (!$emailValidation['valid']) {
            Log::warning("Email validation warning for user {$user->id}: {$user->email} - {$emailValidation['message']}. Attempting to send anyway.");
            // Don't return false - try to send anyway as DNS checks can be unreliable
        }

        try {
            // Log which user's email is being used
            Log::info("Attempting to send email to user ID {$user->id} ({$user->name}) at email: {$user->email}");
            Log::info("Mail::to() will be called with: {$user->email}");
            
            Mail::to($user->email)->send(
                new BookingNotificationMail($user, $notificationType, $message, $data, $booking)
            );

            Log::info("✓ Email notification sent successfully to {$user->email} (User: {$user->name}, ID: {$user->id}) for notification type: {$notificationType}");
            Log::info("=== EMAIL SEND COMPLETE ===");
            return true;
        } catch (\Swift_TransportException $e) {
            Log::error("✗ SMTP Transport Error sending email to {$user->email} (User: {$user->name}, ID: {$user->id})");
            Log::error("Error: " . $e->getMessage());
            Log::error("This usually means: Invalid SMTP credentials, wrong host/port, or network issue");
            Log::error("Stack trace: " . $e->getTraceAsString());
            return false;
        } catch (\Exception $e) {
            Log::error("✗ Failed to send email notification to {$user->email} (User: {$user->name}, ID: {$user->id})");
            Log::error("Error Type: " . get_class($e));
            Log::error("Error Message: " . $e->getMessage());
            Log::error("Error File: " . $e->getFile() . ":" . $e->getLine());
            Log::error("Stack trace: " . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * Send email notification to multiple users
     * 
     * @param array $users
     * @param string $notificationType
     * @param string $message
     * @param array $data
     * @param Booking|null $booking
     * @return int Number of emails sent successfully
     */
    public function sendBulkNotifications(array $users, string $notificationType, string $message, array $data = [], ?Booking $booking = null): int
    {
        $sentCount = 0;

        foreach ($users as $user) {
            if ($this->sendNotification($user, $notificationType, $message, $data, $booking)) {
                $sentCount++;
            }
        }

        return $sentCount;
    }

    /**
     * Check if email exists and is valid
     * 
     * @param string $email
     * @return array
     */
    public function validateEmail(string $email): array
    {
        return $this->emailValidationService->validateEmail($email);
    }
}

