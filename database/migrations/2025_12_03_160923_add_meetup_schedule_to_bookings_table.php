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
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'meetup_date')) {
                $table->date('meetup_date')->nullable()->after('status');
            }
            if (!Schema::hasColumn('bookings', 'meetup_time')) {
                $table->time('meetup_time')->nullable()->after('meetup_date');
            }
        });

        // Update status enum to include 'confirmed' (MySQL only)
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending', 'confirmed', 'approved', 'rejected', 'cancelled', 'completed') DEFAULT 'pending'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'meetup_date')) {
                $table->dropColumn('meetup_date');
            }
            if (Schema::hasColumn('bookings', 'meetup_time')) {
                $table->dropColumn('meetup_time');
            }
        });

        // Revert status enum (MySQL only)
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'cancelled', 'completed') DEFAULT 'pending'");
        }
    }
};
