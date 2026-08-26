<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'paymentable_id',
        'paymentable_type',
        'trx_id',
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

    /**
     * Polymorphic payment relation
     */
    public function paymentable()
    {
        return $this->morphTo();
    }
}