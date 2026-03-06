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
            if (!Schema::hasColumn('inventories', 'unit')) {
                // Add unit field for measurement units (pieces, meters, yards, rolls, etc.)
                $table->string('unit')->default('pcs')->after('category');
            }
        });

        // Change stock from integer to decimal to allow fractional quantities
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE inventories MODIFY COLUMN stock DECIMAL(10,2) DEFAULT 0');
        } else {
            // SQLite: use Laravel's schema builder to change column type
            Schema::table('inventories', function (Blueprint $table) {
                $table->decimal('stock', 10, 2)->default(0)->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventories', function (Blueprint $table) {
            if (Schema::hasColumn('inventories', 'unit')) {
                $table->dropColumn('unit');
            }
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE inventories MODIFY COLUMN stock INT DEFAULT 0');
        } else {
            Schema::table('inventories', function (Blueprint $table) {
                $table->integer('stock')->default(0)->change();
            });
        }
    }
};
