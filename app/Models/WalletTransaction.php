<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Vendor;

class WalletTransaction extends Model
{
    protected $fillable = [

        'vendor_id',

        'booking_id',

        'booking_type',

        'type',

        'amount',

        'status',

        'note',

    ];


    /*
    |--------------------------------------------------------------------------
    | Vendor
    |--------------------------------------------------------------------------
    */

    public function vendor()
    {
        return $this->belongsTo(
            Vendor::class
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Booking
    |--------------------------------------------------------------------------
    |
    | Supports:
    |
    | Booking
    | RoomBooking
    |
    */

    public function booking()
    {
        return $this->morphTo();
    }
}