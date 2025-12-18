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
        Schema::create('booking_inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('inventory_id');
            $table->decimal('quantity_assigned', 10, 2); // Amount assigned to this booking
            $table->decimal('quantity_returned', 10, 2)->default(0); // Amount returned after event
            $table->decimal('quantity_damaged', 10, 2)->default(0); // Amount marked as damaged
            $table->text('damage_notes')->nullable(); // Description of damage if any
            $table->enum('status', ['assigned', 'in_use', 'returned', 'partially_returned'])->default('assigned');
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('returned_at')->nullable();
            $table->timestamps();

            $table->foreign('inventory_id')
                  ->references('inventory_id')
                  ->on('inventories')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_inventory');
    }
};
