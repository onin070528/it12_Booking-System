<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
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
        return $this->belongsTo(User::class);
    }

    /**
     * Get the payments for the booking.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
