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
        $renames = [
            'users' => ['id', 'user_id'],
            'bookings' => ['id', 'booking_id'],
            'payments' => ['id', 'payment_id'],
            'notifications' => ['id', 'notification_id'],
            'messages' => ['id', 'message_id'],
            'events' => ['id', 'event_id'],
            'booking_inventory' => ['id', 'booking_inventory_id'],
        ];

        foreach ($renames as $table => [$from, $to]) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, $from)) {
                Schema::table($table, function (Blueprint $table) use ($from, $to) {
                    $table->renameColumn($from, $to);
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $renames = [
            'users' => ['user_id', 'id'],
            'bookings' => ['booking_id', 'id'],
            'payments' => ['payment_id', 'id'],
            'notifications' => ['notification_id', 'id'],
            'messages' => ['message_id', 'id'],
            'events' => ['event_id', 'id'],
            'booking_inventory' => ['booking_inventory_id', 'id'],
        ];

        foreach ($renames as $table => [$from, $to]) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, $from)) {
                Schema::table($table, function (Blueprint $table) use ($from, $to) {
                    $table->renameColumn($from, $to);
                });
            }
        }
    }
};
