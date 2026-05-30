<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TourDate extends Model
{
    protected $fillable = [
        'tour_id',
        'start_date',
        'end_date',
        'available_seat',
        'status',
    ];

    // =========================

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}