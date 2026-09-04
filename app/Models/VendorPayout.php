<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorPayout extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'vendor_id',

        'commission_id',

        'booking_id',

        'payout_code',

        'amount',
        'tax_amount',
        'payment_method',

        'reference_id',

        'status',

        'admin_note',

        'paid_at',

        'processed_at',

    ];


    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected $casts = [

        'amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'paid_at' => 'datetime',

        'processed_at' => 'datetime',

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
    | Commission
    |--------------------------------------------------------------------------
    */

    public function commission()
    {
        return $this->belongsTo(
            Commission::class
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Booking
    |--------------------------------------------------------------------------
    */

    public function booking()
    {
        return $this->belongsTo(
            Booking::class
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Scope - Pending
    |--------------------------------------------------------------------------
    */

    public function scopePending($query)
    {
        return $query->where(
            'status',
            'pending'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Scope - Processing
    |--------------------------------------------------------------------------
    */

    public function scopeProcessing($query)
    {
        return $query->where(
            'status',
            'processing'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Scope - Completed
    |--------------------------------------------------------------------------
    */

    public function scopeCompleted($query)
    {
        return $query->where(
            'status',
            'completed'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Scope - Failed
    |--------------------------------------------------------------------------
    */

    public function scopeFailed($query)
    {
        return $query->where(
            'status',
            'failed'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Scope - Rejected
    |--------------------------------------------------------------------------
    */

    public function scopeRejected($query)
    {
        return $query->where(
            'status',
            'rejected'
        );
    }
}