<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Booking extends Model
{
    protected $primaryKey = 'booking_id';

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'booking_id';
    }

    protected $fillable = [
        'user_id',
        'event_type',
        'event_date',
        'event_time',
        'location',
        'description',
        'total_amount',
        'status',
        'archived_at',
        'event_details',
        'meetup_date',
        'meetup_time',
        'communication_method',
    ];

    protected $casts = [
        'event_date' => 'date',
        'event_time' => 'string', // TIME column returns as string (HH:MM:SS format)
        'total_amount' => 'decimal:2',
        'event_details' => 'array',
        'meetup_date' => 'date',
        'meetup_time' => 'datetime',
        'archived_at' => 'datetime',
    ];

    /**
     * Get the user that owns the booking.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Get the payments for the booking.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'booking_id', 'booking_id');
    }

    /**
     * Get all inventory items assigned to this booking
     */
    public function inventories(): BelongsToMany
    {
        return $this->belongsToMany(Inventory::class, 'booking_inventory', 'booking_id', 'inventory_id')
                    ->withPivot(['quantity_assigned', 'quantity_returned', 'quantity_damaged', 'damage_notes', 'status', 'assigned_at', 'returned_at'])
                    ->withTimestamps();
    }

    /**
     * Get booking inventory assignments
     */
    public function inventoryAssignments(): HasMany
    {
        return $this->hasMany(BookingInventory::class, 'booking_id', 'booking_id');
    }

    /**
     * Check if booking has any inventory assigned
     */
    public function hasInventoryAssigned(): bool
    {
        return $this->inventoryAssignments()->exists();
    }

    /**
     * Check if all inventory has been returned
     */
    public function allInventoryReturned(): bool
    {
        if (!$this->hasInventoryAssigned()) {
            return true;
        }

        return $this->inventoryAssignments()
            ->whereIn('status', ['assigned', 'in_use', 'partially_returned'])
            ->doesntExist();
    }
}
