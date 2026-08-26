<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    protected $fillable = [
        'booking_id',
        'room_booking_id',
        'total_amount',
        'commission_rate',
        'admin_earning',
        'vendor_earning',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'admin_earning' => 'decimal:2',
        'vendor_earning' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | TOUR BOOKING
    |--------------------------------------------------------------------------
    */

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /*
    |--------------------------------------------------------------------------
    | ROOM BOOKING
    |--------------------------------------------------------------------------
    */

    public function roomBooking()
    {
        return $this->belongsTo(RoomBooking::class);
    }

    /*
    |--------------------------------------------------------------------------
    | TYPE
    |--------------------------------------------------------------------------
    */

    public function getBookingTypeAttribute()
    {
        if ($this->booking_id) {
            return 'tour';
        }

        if ($this->room_booking_id) {
            return 'room';
        }

        return null;
    }
}