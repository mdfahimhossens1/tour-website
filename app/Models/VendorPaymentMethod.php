<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VendorPaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'name',
        'type',
        'service_type',
        'account_number',
        'api_key',
        'secret_key',
        'status',
        'description',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

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
    | Active
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /*
    |--------------------------------------------------------------------------
    | Service Type
    |--------------------------------------------------------------------------
    */

    public function scopeForService($query, string $service)
    {
        return $query->where(function ($q) use ($service) {

            $q->where('service_type', $service)
              ->orWhere('service_type', 'all');

        });
    }
}