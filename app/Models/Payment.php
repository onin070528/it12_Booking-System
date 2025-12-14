<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'booking_id',
        'user_id',
        'paymongo_payment_intent_id',
        'paymongo_payment_method_id',
        'paymongo_source_id',
        'amount',
        'currency',
        'status',
        'payment_method',
        'description',
        'paymongo_response',
        'paid_at',
        'reference_number',
        'payment_screenshot',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paymongo_response' => 'array',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the booking that owns the payment.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the user that owns the payment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
