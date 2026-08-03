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

    protected $casts = [
        'total_amount' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'admin_earning' => 'decimal:2',
        'vendor_earning' => 'decimal:2',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
