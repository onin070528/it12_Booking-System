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
        Schema::table('inventories', function (Blueprint $table) {
            // Add unit field for measurement units (pieces, meters, yards, rolls, etc.)
            $table->string('unit')->default('pcs')->after('category');
            
            // Change stock from integer to decimal to allow fractional quantities
            // First drop the existing column and recreate as decimal
        });

        // Use raw SQL to change column type (Laravel's change() may have issues)
        DB::statement('ALTER TABLE inventories MODIFY COLUMN stock DECIMAL(10,2) DEFAULT 0');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->dropColumn('unit');
        });

        DB::statement('ALTER TABLE inventories MODIFY COLUMN stock INT DEFAULT 0');
    }
};
