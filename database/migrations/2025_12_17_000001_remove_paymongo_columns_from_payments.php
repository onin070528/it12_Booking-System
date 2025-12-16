<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Remove PayMongo-related columns if they exist
            if (Schema::hasColumn('payments', 'paymongo_payment_intent_id')) {
                $table->dropColumn('paymongo_payment_intent_id');
            }
            if (Schema::hasColumn('payments', 'paymongo_payment_method_id')) {
                $table->dropColumn('paymongo_payment_method_id');
            }
            if (Schema::hasColumn('payments', 'paymongo_source_id')) {
                $table->dropColumn('paymongo_source_id');
            }
            if (Schema::hasColumn('payments', 'paymongo_response')) {
                $table->dropColumn('paymongo_response');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('paymongo_payment_intent_id')->nullable();
            $table->string('paymongo_payment_method_id')->nullable();
            $table->string('paymongo_source_id')->nullable();
            $table->json('paymongo_response')->nullable();
        });
    }
};
