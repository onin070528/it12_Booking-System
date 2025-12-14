<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixSessionsTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sessions:fix';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix corrupted sessions table by dropping and recreating it';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fixing corrupted sessions table...');

        try {
            // Drop the corrupted table if it exists
            if (Schema::hasTable('sessions')) {
                $this->info('Dropping corrupted sessions table...');
                Schema::dropIfExists('sessions');
                $this->info('Sessions table dropped successfully.');
            }

            // Recreate the sessions table
            $this->info('Recreating sessions table...');
            Schema::create('sessions', function ($table) {
                $table->string('id')->primary();
                $table->foreignId('user_id')->nullable()->index();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
            });

            // Force InnoDB engine to avoid corruption issues
            try {
                DB::statement('ALTER TABLE sessions ENGINE=InnoDB');
                $this->info('InnoDB engine set successfully.');
            } catch (\Exception $e) {
                $this->warn('Could not set InnoDB engine: ' . $e->getMessage());
            }

            $this->info('Sessions table recreated successfully!');
            $this->info('The sessions table has been fixed. You can now use your application normally.');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Error fixing sessions table: ' . $e->getMessage());
            $this->error('Please try fixing it manually using MySQL commands:');
            $this->line('DROP TABLE IF EXISTS sessions;');
            $this->line('Then run: php artisan migrate');
            
            return Command::FAILURE;
        }
    }
}

