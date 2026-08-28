<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'business_name',
        'phone',
        'address',
        'trade_license',
        'logo',
        'website',
        'bkash',
        'nagad',
        'bank_name',
        'bank_account',
        'description',
        'status',
        'commission_rate',
    ];

    protected $casts = [
        'commission_rate' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
public function facilities()
{
    return $this->hasMany(Facility::class);
}
    public function tours()
    {
        return $this->hasMany(Tour::class);
    }

    public function resorts()
    {
        return $this->hasMany(Resort::class);
    }

public function vehicles()
{
    return $this->hasMany(Vehicle::class);
}


public function transportBookings()
{
    return $this->hasMany(TransportBooking::class);
}


    public function wallet()
    {
        return $this->hasOne(VendorWallet::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(VendorWithdrawal::class);
    }

    public function paymentMethods()
{
    return $this->hasMany(VendorPaymentMethod::class);
}
}