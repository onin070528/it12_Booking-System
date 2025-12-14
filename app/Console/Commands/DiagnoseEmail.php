<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class DiagnoseEmail extends Command
{
    protected $signature = 'diagnose:email';
    protected $description = 'Diagnose email configuration and test sending';

    public function handle()
    {
        $this->info('=== Email System Diagnosis ===');
        $this->newLine();

        // 1. Check Mail Configuration
        $this->info('1. Mail Configuration:');
        $mailer = config('mail.default');
        $host = config('mail.mailers.smtp.host');
        $port = config('mail.mailers.smtp.port');
        $username = config('mail.mailers.smtp.username');
        $password = config('mail.mailers.smtp.password');
        $fromAddress = config('mail.from.address');
        $fromName = config('mail.from.name');

        $this->line("   Mailer: {$mailer}");
        $this->line("   Host: {$host}");
        $this->line("   Port: {$port}");
        $this->line("   Username: " . ($username ?: 'NOT SET'));
        $this->line("   Password: " . ($password ? 'SET (' . strlen($password) . ' chars)' : 'NOT SET'));
        $this->line("   From Address: {$fromAddress}");
        $this->line("   From Name: {$fromName}");

        // Check for placeholder values
        $hasPlaceholders = false;
        if (str_contains($username, 'your-email') || str_contains($username, 'example.com')) {
            $this->error("   ⚠ Username contains placeholder value!");
            $hasPlaceholders = true;
        }
        if (str_contains($password, 'your-app-password') || str_contains($password, 'your-password')) {
            $this->error("   ⚠ Password contains placeholder value!");
            $hasPlaceholders = true;
        }
        if (str_contains($fromAddress, 'your-email') || str_contains($fromAddress, 'example.com')) {
            $this->error("   ⚠ From Address contains placeholder value!");
            $hasPlaceholders = true;
        }

        if ($hasPlaceholders) {
            $this->newLine();
            $this->error('   ❌ PLACEHOLDER VALUES DETECTED!');
            $this->warn('   You need to update your .env file with real email credentials.');
            $this->newLine();
        } else {
            $this->info('   ✓ No placeholder values detected');
        }

        $this->newLine();

        // 2. Check Users in Database
        $this->info('2. Users in Database:');
        $users = User::select('id', 'name', 'email', 'role')->get();
        if ($users->isEmpty()) {
            $this->warn('   No users found in database');
        } else {
            foreach ($users as $user) {
                $emailStatus = filter_var($user->email, FILTER_VALIDATE_EMAIL) ? '✓' : '✗';
                $this->line("   {$emailStatus} ID: {$user->id} | {$user->name} | {$user->email} | Role: {$user->role}");
            }
        }

        $this->newLine();

        // 3. Check Recent Logs
        $this->info('3. Recent Email Logs (last 20 lines):');
        $logFile = storage_path('logs/laravel.log');
        if (file_exists($logFile)) {
            $lines = file($logFile);
            $emailLines = array_filter($lines, function($line) {
                return str_contains($line, 'email') || 
                       str_contains($line, 'Email') || 
                       str_contains($line, 'mail') ||
                       str_contains($line, 'SMTP');
            });
            $recentLines = array_slice($emailLines, -20);
            if (empty($recentLines)) {
                $this->warn('   No email-related log entries found');
            } else {
                foreach ($recentLines as $line) {
                    $this->line('   ' . trim($line));
                }
            }
        } else {
            $this->warn('   Log file not found');
        }

        $this->newLine();

        // 4. Test Email Sending
        $this->info('4. Test Email Sending:');
        $testEmail = $this->ask('Enter email address to test (or press Enter to skip)', '');
        
        if ($testEmail && filter_var($testEmail, FILTER_VALIDATE_EMAIL)) {
            $this->line("   Attempting to send test email to: {$testEmail}");
            
            try {
                Mail::raw('This is a test email from the Booking Management System.', function($message) use ($testEmail) {
                    $message->to($testEmail)
                            ->subject('Test Email - Booking Management System');
                });
                
                $this->info('   ✓ Test email sent successfully!');
                $this->line('   Check your inbox (and spam folder)');
            } catch (\Exception $e) {
                $this->error('   ✗ Failed to send test email!');
                $this->error('   Error: ' . $e->getMessage());
                $this->newLine();
                $this->warn('   Common issues:');
                $this->line('   - Invalid SMTP credentials');
                $this->line('   - Gmail requires App Password (not regular password)');
                $this->line('   - Firewall blocking SMTP port');
                $this->line('   - Check storage/logs/laravel.log for details');
            }
        } else {
            $this->warn('   Skipped - no valid email provided');
        }

        $this->newLine();
        $this->info('=== Diagnosis Complete ===');
        $this->newLine();
        $this->info('Next steps:');
        $this->line('1. If placeholders detected, update .env file with real credentials');
        $this->line('2. For Gmail, get App Password from Google Account settings');
        $this->line('3. Run: php artisan config:clear');
        $this->line('4. Test again with: php artisan test:email your-email@gmail.com');
        
        return 0;
    }
}

