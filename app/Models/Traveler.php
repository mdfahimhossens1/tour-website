<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Traveler extends Model
{
    protected $fillable = [

        'booking_id',
        'name',
        'phone',
        'email',
        'age',
        'gender',
        'address',

    ];

    // =========================
    // RELATION
    // =========================

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}