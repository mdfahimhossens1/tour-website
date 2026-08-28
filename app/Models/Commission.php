<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Commission extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'room_booking_id',
        'transport_booking_id',

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
        return $this->belongsTo(
            Booking::class,
            'booking_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ROOM BOOKING
    |--------------------------------------------------------------------------
    */

    public function roomBooking()
    {
        return $this->belongsTo(
            RoomBooking::class,
            'room_booking_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | TRANSPORT BOOKING
    |--------------------------------------------------------------------------
    */

    public function transportBooking()
    {
        return $this->belongsTo(
            TransportBooking::class,
            'transport_booking_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | BOOKING TYPE
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

        if ($this->transport_booking_id) {
            return 'transport';
        }

        return null;
    }


    /*
    |--------------------------------------------------------------------------
    | BOOKING RELATION
    |--------------------------------------------------------------------------
    */

    public function getBookingRelationAttribute()
    {
        if ($this->booking_id) {
            return $this->booking;
        }

        if ($this->room_booking_id) {
            return $this->roomBooking;
        }

        if ($this->transport_booking_id) {
            return $this->transportBooking;
        }

        return null;
    }
}