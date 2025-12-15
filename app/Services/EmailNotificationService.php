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

        // Use dedicated notifications mailer if configured
        $notificationsMailer = 'notifications';

        $mailerConfig = config("mail.mailers.{$notificationsMailer}", []);
        $mailerDriver = $mailerConfig['transport'] ?? config('mail.default');
        $notifHost = $mailerConfig['host'] ?? null;
        $notifUsername = $mailerConfig['username'] ?? null;
        $notifPassword = $mailerConfig['password'] ?? null;

        Log::info("Notifications Mailer - driver: {$mailerDriver}, host: " . ($notifHost ?: 'NOT SET') . ", username: " . ($notifUsername ?: 'NOT SET'));

        // Check for placeholder values on notifications mailer
        if (($notifUsername && str_contains($notifUsername, 'your-email')) || ($notifPassword && str_contains($notifPassword, 'your-app-password'))) {
            Log::error("PLACEHOLDER VALUES DETECTED in notifications mail configuration! Username or password contains placeholder text.");
            Log::error("Please update your .env file with real notification email credentials.");
            return false;
        }

        // Optional: Check domain (but don't block if it fails - just log a warning)
        $emailValidation = $this->emailValidationService->validateEmail($user->email);
        if (!$emailValidation['valid']) {
            Log::warning("Email validation warning for user {$user->id}: {$user->email} - {$emailValidation['message']}. Attempting to send anyway.");
            // Don't return false - try to send anyway as DNS checks can be unreliable
        }

        try {
            // Log which user's email is being used
            Log::info("Attempting to send notification email to user ID {$user->id} ({$user->name}) at email: {$user->email}");

            // Build mailable and ensure it uses the notifications sender if configured
            $mailable = new BookingNotificationMail($user, $notificationType, $message, $data, $booking);

            // If a custom from address is set for notifications, apply it to the mailable
            $notifFrom = env('NOTIF_FROM_ADDRESS') ?: config('mail.from.address');
            $notifFromName = env('NOTIF_FROM_NAME') ?: config('mail.from.name');
            if ($notifFrom) {
                $mailable->from($notifFrom, $notifFromName);
            }

            // Send using the dedicated notifications mailer so we don't affect default mailer
            Mail::mailer($notificationsMailer)->to($user->email)->send($mailable);

            Log::info("✓ Email notification sent successfully to {$user->email} (User: {$user->name}, ID: {$user->id}) for notification type: {$notificationType}");
            Log::info("=== EMAIL SEND COMPLETE ===");
            return true;
        } catch (\Exception $e) {
            // Log transport/network errors and any other exceptions here
            Log::error("✗ Failed to send email notification to {$user->email} (User: {$user->name}, ID: {$user->id})");
            Log::error("Exception Class: " . get_class($e));
            Log::error("Error: " . $e->getMessage());
            Log::error("File: " . $e->getFile() . ":" . $e->getLine());
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

