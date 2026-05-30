<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'tour_id',
        'tour_date_id',
        'booking_code',
        'person_count',
        'total_amount',
        'payment_status',
        'booking_status',
        'special_request',
    ];

    // =========================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }

    public function tourDate()
    {
        return $this->belongsTo(TourDate::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function travelers()
    {
        return $this->hasMany(Traveler::class);
    }
}