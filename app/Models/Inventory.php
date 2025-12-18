<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Inventory extends Model
{
    protected $primaryKey = 'inventory_id';
    
    protected $fillable = [
        'item_name',
        'category',
        'unit',
        'stock',
        'status',
        'archived_at',
    ];

    protected $casts = [
        'stock' => 'decimal:2',
        'archived_at' => 'datetime',
    ];

    /**
     * Common units for inventory items
     */
    public static array $units = [
        'pcs' => 'Pieces',
        'meters' => 'Meters',
        'yards' => 'Yards',
        'rolls' => 'Rolls',
        'sets' => 'Sets',
        'pairs' => 'Pairs',
        'boxes' => 'Boxes',
        'kg' => 'Kilograms',
        'liters' => 'Liters',
    ];

    /**
     * Get the status based on stock level
     */
    public function getStatusAttribute($value)
    {
        // Auto-determine status based on stock if not explicitly set
        if ($value) {
            return $value;
        }
        
        $stock = $this->attributes['stock'] ?? $this->stock ?? 0;
        
        if ($stock <= 0) {
            return 'Out of Stock';
        } elseif ($stock < 10) {
            return 'Low Stock';
        } else {
            return 'In Stock';
        }
    }

    /**
     * Get all bookings that use this inventory item
     */
    public function bookings(): BelongsToMany
    {
        return $this->belongsToMany(Booking::class, 'booking_inventory', 'inventory_id', 'booking_id')
                    ->using(BookingInventory::class)
                    ->withPivot(['quantity_assigned', 'quantity_returned', 'quantity_damaged', 'damage_notes', 'status', 'assigned_at', 'returned_at'])
                    ->withTimestamps();
    }

    /**
     * Get booking inventory assignments
     */
    public function bookingAssignments(): HasMany
    {
        return $this->hasMany(BookingInventory::class, 'inventory_id', 'inventory_id');
    }

    /**
     * Get total quantity currently assigned to bookings (in use)
     */
    public function getQuantityInUseAttribute(): float
    {
        return $this->bookingAssignments()
            ->whereIn('status', ['assigned', 'in_use'])
            ->sum(\Illuminate\Support\Facades\DB::raw('quantity_assigned - quantity_returned - quantity_damaged'));
    }

    /**
     * Get available stock (total stock minus in-use)
     */
    public function getAvailableStockAttribute(): float
    {
        return max(0, $this->stock - $this->quantity_in_use);
    }

    /**
     * Format stock display with unit
     */
    public function getFormattedStockAttribute(): string
    {
        $stock = number_format($this->stock, $this->stock == intval($this->stock) ? 0 : 2);
        return $stock . ' ' . ($this->unit ?? 'pcs');
    }

    /**
     * Automatically set status when stock is updated
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($inventory) {
            $stock = $inventory->stock ?? 0;
            
            if ($stock <= 0) {
                $inventory->status = 'Out of Stock';
            } elseif ($stock < 10) {
                $inventory->status = 'Low Stock';
            } else {
                $inventory->status = 'In Stock';
            }
        });
    }
}

