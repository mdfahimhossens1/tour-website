<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'type',
        'value',
        'max_usage',
        'used_count',
        'start_date',
        'end_date',
        'status',
    ];

        protected $casts = [

        'value' => 'float',
        'status' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',

    ];
}