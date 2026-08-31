<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction;

class Booking extends Model
{
    protected $fillable = [

        'user_id',
        'vendor_id',

        'tour_id',
        'tour_date_id',

        'booking_code',

        'person_count',

        'unit_price',

        'subtotal',

        'coupon_code',

        'discount',

        'total_amount',

        'payment_status',

        'booking_status',

        'special_request',

    ];


    protected $casts = [

        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'total_amount' => 'decimal:2',

    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }


    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }


    public function tourDate()
    {
        return $this->belongsTo(TourDate::class);
    }

    public function commission()
{
    return $this->hasOne(Commission::class);
}

    public function travelers()
    {
        return $this->hasMany(Traveler::class);
    }


    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }


    public function payments()
    {
        return $this->morphMany(Payment::class, 'paymentable');
    }

    public function payment()
    {
        return $this->morphOne(Payment::class, 'paymentable')
            ->latestOfMany();
    }
}
