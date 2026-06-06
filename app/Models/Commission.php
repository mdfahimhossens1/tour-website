<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    protected $fillable = [
        'booking_id',
        'total_amount',
        'commission_rate',
        'admin_earning',
        'vendor_earning'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
