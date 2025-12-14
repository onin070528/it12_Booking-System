<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Try to drop the table if it exists (in case it's corrupted)
        // Use raw SQL to handle corrupted tables that Schema can't drop
        try {
            DB::statement('DROP TABLE IF EXISTS sessions');
        } catch (\Exception $e) {
            // If drop fails, try to repair first
            try {
                DB::statement('REPAIR TABLE sessions');
                DB::statement('DROP TABLE IF EXISTS sessions');
            } catch (\Exception $e2) {
                // If repair also fails, force drop with IGNORE
                DB::statement('SET FOREIGN_KEY_CHECKS=0');
                DB::statement('DROP TABLE IF EXISTS sessions');
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
            }
        }
        
        // Recreate the sessions table with explicit InnoDB engine
        // This prevents corruption issues by ensuring proper engine from the start
        DB::statement("
            CREATE TABLE sessions (
                id VARCHAR(255) NOT NULL PRIMARY KEY,
                user_id BIGINT UNSIGNED NULL,
                ip_address VARCHAR(45) NULL,
                user_agent TEXT NULL,
                payload LONGTEXT NOT NULL,
                last_activity INT NOT NULL,
                INDEX sessions_user_id_index (user_id),
                INDEX sessions_last_activity_index (last_activity)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
        
        // Add foreign key constraint if users table exists
        try {
            if (Schema::hasTable('users')) {
                DB::statement('ALTER TABLE sessions ADD CONSTRAINT sessions_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE');
            }
        } catch (\Exception $e) {
            // Foreign key might already exist or users table might not exist yet
            // This is not critical for sessions to work
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};

