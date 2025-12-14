<?php

namespace App\Console\Commands;

use App\Mail\BookingNotificationMail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class TestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:email {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email configuration by sending a test email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Email Configuration...');
        $this->newLine();

        // Check mail configuration
        $this->info('Mail Configuration:');
        $this->line('  Mailer: ' . config('mail.default'));
        $this->line('  Host: ' . config('mail.mailers.smtp.host'));
        $this->line('  Port: ' . config('mail.mailers.smtp.port'));
        $this->line('  Username: ' . (config('mail.mailers.smtp.username') ? 'Set' : 'Not Set'));
        $this->line('  Password: ' . (config('mail.mailers.smtp.password') ? 'Set' : 'Not Set'));
        $this->line('  From Address: ' . config('mail.from.address'));
        $this->line('  From Name: ' . config('mail.from.name'));
        $this->newLine();

        // Get email address
        $email = $this->argument('email');
        if (!$email) {
            $email = $this->ask('Enter email address to send test email to');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Invalid email address format!');
            return 1;
        }

        $this->info("Sending test email to: {$email}");
        $this->newLine();

        try {
            // Create a dummy user for testing
            $testUser = new User();
            $testUser->name = 'Test User';
            $testUser->email = $email;

            // Send test email
            Mail::to($email)->send(
                new BookingNotificationMail(
                    $testUser,
                    'booking_created',
                    'This is a test email from the Booking Management System. If you receive this, your email configuration is working correctly!',
                    [],
                    null
                )
            );

            $this->info('✓ Test email sent successfully!');
            $this->line('Please check your inbox (and spam folder) for the test email.');
            $this->newLine();
            $this->info('If you did not receive the email, check:');
            $this->line('  1. Your .env file has correct SMTP credentials');
            $this->line('  2. Check storage/logs/laravel.log for error messages');
            $this->line('  3. For Gmail, ensure you\'re using an App Password, not your regular password');
            $this->line('  4. Check your spam/junk folder');

            return 0;
        } catch (\Exception $e) {
            $this->error('✗ Failed to send test email!');
            $this->error('Error: ' . $e->getMessage());
            $this->newLine();
            $this->warn('Full error details have been logged. Check storage/logs/laravel.log');
            
            Log::error('Test email failed: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return 1;
        }
    }
}
