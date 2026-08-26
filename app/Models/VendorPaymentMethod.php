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
        'account_number',
        'api_key',
        'secret_key',
        'status',
        'description',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}