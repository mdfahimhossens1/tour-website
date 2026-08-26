<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoomBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vendor_id',
        'resort_id',
        'room_id',

        'booking_code',

        'room_count',

        'check_in',
        'check_out',
        'total_nights',

        'adults',
        'children',

        'room_price',
        'subtotal',
        'discount',
        'tax',
        'total_amount',

        'commission_rate',
        'admin_commission',
        'vendor_earning',

        'payment_status',
        'booking_status',

        'special_request',
    ];

    protected $casts = [

        'check_in' => 'date',
        'check_out' => 'date',

        'room_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total_amount' => 'decimal:2',

        'commission_rate' => 'decimal:2',
        'admin_commission' => 'decimal:2',
        'vendor_earning' => 'decimal:2',
    ];


    /*
    |--------------------------------------------------------------------------
    | User
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    /*
    |--------------------------------------------------------------------------
    | Vendor
    |--------------------------------------------------------------------------
    */

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }


    /*
    |--------------------------------------------------------------------------
    | Resort
    |--------------------------------------------------------------------------
    */

    public function resort()
    {
        return $this->belongsTo(Resort::class);
    }


    /*
    |--------------------------------------------------------------------------
    | Room
    |--------------------------------------------------------------------------
    */

    public function room()
    {
        return $this->belongsTo(Room::class);
    }


    /*
    |--------------------------------------------------------------------------
    | Guests
    |--------------------------------------------------------------------------
    */

    public function guests()
    {
        return $this->hasMany(RoomBookingGuest::class);
    }


    /*
    |--------------------------------------------------------------------------
    | Payments
    |--------------------------------------------------------------------------
    |
    | Payment model uses polymorphic relation:
    |
    | paymentable_id
    | paymentable_type
    |
    */

    public function payments()
    {
        return $this->morphMany(
            Payment::class,
            'paymentable'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Latest Payment
    |--------------------------------------------------------------------------
    */

    public function latestPayment()
    {
        return $this->morphOne(
            Payment::class,
            'paymentable'
        )->latestOfMany();
    }
}