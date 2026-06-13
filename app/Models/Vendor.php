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
        'status',
        'commission_rate'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tours()
    {
        return $this->hasMany(Tour::class);
    }
}