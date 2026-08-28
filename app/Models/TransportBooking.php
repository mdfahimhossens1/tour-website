<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransportBooking extends Model
{
    use HasFactory;

    protected $fillable = [

        'user_id',
        'vendor_id',
        'vehicle_id',

        'booking_code',

        'start_date',
        'end_date',
        'total_days',

        'passengers',

        'price_per_day',
        'subtotal',
        'discount',
        'tax',
        'total_amount',

        'commission_rate',
        'admin_commission',
        'vendor_earning',

        'payment_status',
        'booking_status',

        'pickup_location',
        'dropoff_location',
        'special_request',
    ];

    protected $casts = [

        'start_date' => 'date',
        'end_date' => 'date',

        'total_days' => 'integer',
        'passengers' => 'integer',

        'price_per_day' => 'decimal:2',
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
    | Vehicle
    |--------------------------------------------------------------------------
    */

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Payments
    |--------------------------------------------------------------------------
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