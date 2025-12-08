<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
        'item_name',
        'category',
        'stock',
        'status',
    ];

    protected $casts = [
        'stock' => 'integer',
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

