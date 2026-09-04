<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'type',
        'rate',
        'applies_to',
        'is_active',
        'priority',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'is_active' => 'boolean',
        'starts_at' => 'date',
        'ends_at' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Active Scope
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /*
    |--------------------------------------------------------------------------
    | Percentage Tax Scope
    |--------------------------------------------------------------------------
    */

    public function scopePercentage($query)
    {
        return $query->where('type', 'percentage');
    }

    /*
    |--------------------------------------------------------------------------
    | Fixed Tax Scope
    |--------------------------------------------------------------------------
    */

    public function scopeFixed($query)
    {
        return $query->where('type', 'fixed');
    }

    /*
    |--------------------------------------------------------------------------
    | Booking Tax Scope
    |--------------------------------------------------------------------------
    */

    public function scopeForBooking($query)
    {
        return $query->whereIn('applies_to', [
            'booking',
            'both',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Vendor Payout Tax Scope
    |--------------------------------------------------------------------------
    */

    public function scopeForVendorPayout($query)
    {
        return $query->whereIn('applies_to', [
            'vendor_payout',
            'both',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Current / Effective Tax
    |--------------------------------------------------------------------------
    */

    public function scopeCurrentlyEffective($query)
    {
        $today = now()->toDateString();

        return $query
            ->where('is_active', true)
            ->where(function ($q) use ($today) {

                $q->whereNull('starts_at')
                    ->orWhereDate('starts_at', '<=', $today);

            })
            ->where(function ($q) use ($today) {

                $q->whereNull('ends_at')
                    ->orWhereDate('ends_at', '>=', $today);

            });
    }
}