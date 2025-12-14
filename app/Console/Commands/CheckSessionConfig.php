<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

class CheckSessionConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'session:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and display current session configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== Session Configuration Check ===');
        $this->newLine();

        // Check current session driver
        $driver = Config::get('session.driver');
        $this->line("Current Session Driver: <fg=cyan>{$driver}</>");

        // Check if .env file exists
        $envPath = base_path('.env');
        if (File::exists($envPath)) {
            $envContent = File::get($envPath);
            if (preg_match('/SESSION_DRIVER=(.*)/', $envContent, $matches)) {
                $envDriver = trim($matches[1]);
                $this->line(".env SESSION_DRIVER: <fg=cyan>{$envDriver}</>");
            } else {
                $this->line(".env SESSION_DRIVER: <fg=yellow>Not set (using default)</>");
            }
        } else {
            $this->line(".env file: <fg=yellow>Not found</>");
        }

        $this->newLine();

        // Check sessions directory for file driver
        if ($driver === 'file') {
            $sessionsPath = storage_path('framework/sessions');
            if (File::isDirectory($sessionsPath)) {
                $this->line("Sessions directory: <fg=green>Exists</> ({$sessionsPath})");
            } else {
                $this->line("Sessions directory: <fg=red>Missing</> ({$sessionsPath})");
                $this->warn('Creating sessions directory...');
                File::makeDirectory($sessionsPath, 0755, true);
                $this->info('Sessions directory created!');
            }
        }

        // Check sessions table for database driver
        if ($driver === 'database') {
            try {
                if (\Illuminate\Support\Facades\Schema::hasTable('sessions')) {
                    $this->line("Sessions table: <fg=green>Exists</>");
                } else {
                    $this->line("Sessions table: <fg=red>Missing</>");
                    $this->warn('Run: php artisan sessions:fix');
                }
            } catch (\Exception $e) {
                $this->line("Sessions table: <fg=red>Error checking</> - " . $e->getMessage());
            }
        }

        $this->newLine();
        $this->info('=== Configuration Check Complete ===');

        return Command::SUCCESS;
    }
}

