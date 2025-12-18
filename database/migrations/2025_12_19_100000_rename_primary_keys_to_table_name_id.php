<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Rename primary keys from 'id' to 'table_name_id' for consistency
     */
    public function up(): void
    {
        // Rename users.id to users.user_id
        if (Schema::hasColumn('users', 'id')) {
            DB::statement('ALTER TABLE users CHANGE id user_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        }

        // Rename bookings.id to bookings.booking_id
        if (Schema::hasColumn('bookings', 'id')) {
            DB::statement('ALTER TABLE bookings CHANGE id booking_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        }

        // Rename payments.id to payments.payment_id
        if (Schema::hasColumn('payments', 'id')) {
            DB::statement('ALTER TABLE payments CHANGE id payment_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        }

        // Rename notifications.id to notifications.notification_id
        if (Schema::hasColumn('notifications', 'id')) {
            DB::statement('ALTER TABLE notifications CHANGE id notification_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        }

        // Rename messages.id to messages.message_id
        if (Schema::hasColumn('messages', 'id')) {
            DB::statement('ALTER TABLE messages CHANGE id message_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        }

        // Rename events.id to events.event_id
        if (Schema::hasColumn('events', 'id')) {
            DB::statement('ALTER TABLE events CHANGE id event_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        }

        // Rename booking_inventory.id to booking_inventory.booking_inventory_id
        if (Schema::hasColumn('booking_inventory', 'id')) {
            DB::statement('ALTER TABLE booking_inventory CHANGE id booking_inventory_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert users.user_id to users.id
        if (Schema::hasColumn('users', 'user_id')) {
            DB::statement('ALTER TABLE users CHANGE user_id id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        }

        // Revert bookings.booking_id to bookings.id
        if (Schema::hasColumn('bookings', 'booking_id')) {
            DB::statement('ALTER TABLE bookings CHANGE booking_id id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        }

        // Revert payments.payment_id to payments.id
        if (Schema::hasColumn('payments', 'payment_id')) {
            DB::statement('ALTER TABLE payments CHANGE payment_id id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        }

        // Revert notifications.notification_id to notifications.id
        if (Schema::hasColumn('notifications', 'notification_id')) {
            DB::statement('ALTER TABLE notifications CHANGE notification_id id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        }

        // Revert messages.message_id to messages.id
        if (Schema::hasColumn('messages', 'message_id')) {
            DB::statement('ALTER TABLE messages CHANGE message_id id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        }

        // Revert events.event_id to events.id
        if (Schema::hasColumn('events', 'event_id')) {
            DB::statement('ALTER TABLE events CHANGE event_id id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        }

        // Revert booking_inventory.booking_inventory_id to booking_inventory.id
        if (Schema::hasColumn('booking_inventory', 'booking_inventory_id')) {
            DB::statement('ALTER TABLE booking_inventory CHANGE booking_inventory_id id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
        }
    }
};
