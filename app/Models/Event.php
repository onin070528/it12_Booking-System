<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $primaryKey = 'event_id';

    protected $fillable = ['title', 'description', 'start', 'end'];
    
    protected $casts = [
        'start' => 'date',
        'end' => 'date',
    ];
}
