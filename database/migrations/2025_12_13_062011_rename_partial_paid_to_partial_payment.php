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
        // First, add partial_payment to the enum (temporarily allow both)
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending', 'confirmed', 'approved', 'pending_payment', 'partial_paid', 'partial_payment', 'in_design', 'rejected', 'cancelled', 'completed') DEFAULT 'pending'");
        DB::statement("ALTER TABLE payments MODIFY COLUMN status ENUM('pending', 'paid', 'partial_paid', 'partial_payment', 'failed', 'cancelled', 'refunded') DEFAULT 'pending'");
        
        // Update existing records in bookings table
        DB::table('bookings')
            ->where('status', 'partial_paid')
            ->update(['status' => 'partial_payment']);
        
        // Update existing records in payments table
        DB::table('payments')
            ->where('status', 'partial_paid')
            ->update(['status' => 'partial_payment']);
        
        // Now remove partial_paid from the enum
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending', 'confirmed', 'approved', 'pending_payment', 'partial_payment', 'in_design', 'rejected', 'cancelled', 'completed') DEFAULT 'pending'");
        DB::statement("ALTER TABLE payments MODIFY COLUMN status ENUM('pending', 'paid', 'partial_payment', 'failed', 'cancelled', 'refunded') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert existing records in bookings table
        DB::table('bookings')
            ->where('status', 'partial_payment')
            ->update(['status' => 'partial_paid']);
        
        // Revert existing records in payments table
        DB::table('payments')
            ->where('status', 'partial_payment')
            ->update(['status' => 'partial_paid']);
        
        // Revert bookings table enum
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending', 'confirmed', 'approved', 'pending_payment', 'partial_paid', 'in_design', 'rejected', 'cancelled', 'completed') DEFAULT 'pending'");
        
        // Revert payments table enum
        DB::statement("ALTER TABLE payments MODIFY COLUMN status ENUM('pending', 'paid', 'partial_paid', 'failed', 'cancelled', 'refunded') DEFAULT 'pending'");
    }
};
