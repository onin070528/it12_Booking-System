<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingInventory extends Model
{
    protected $table = 'booking_inventory';
    protected $primaryKey = 'booking_inventory_id';

    protected $fillable = [
        'booking_id',
        'inventory_id',
        'quantity_assigned',
        'quantity_returned',
        'quantity_damaged',
        'damage_notes',
        'status',
        'assigned_at',
        'returned_at',
    ];

    protected $casts = [
        'quantity_assigned' => 'decimal:2',
        'quantity_returned' => 'decimal:2',
        'quantity_damaged' => 'decimal:2',
        'assigned_at' => 'datetime',
        'returned_at' => 'datetime',
    ];

    /**
     * Get the booking that owns this assignment.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the inventory item that is assigned.
     */
    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class, 'inventory_id', 'inventory_id');
    }

    /**
     * Get the quantity still in use (assigned but not returned)
     */
    public function getQuantityInUseAttribute(): float
    {
        return $this->quantity_assigned - $this->quantity_returned - $this->quantity_damaged;
    }

    /**
     * Check if all items have been returned
     */
    public function isFullyReturned(): bool
    {
        return ($this->quantity_returned + $this->quantity_damaged) >= $this->quantity_assigned;
    }
}
