<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'booking_id',
        'transaction_id',
        'payment_method',
        'amount',
        'status',
        'payment_data',
        'paid_at',
    ];

protected $casts = [

    'amount' => 'float',

    'payment_data' => 'array',

    'paid_at' => 'datetime',

];

    // =========================

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function transaction()
{
    return $this->belongsTo(Transaction::class, 'transaction_id', 'transaction_id');
}
}