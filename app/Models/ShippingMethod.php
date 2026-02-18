<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
     protected $fillable = [
        'name','slug','zone','cost','min_order','is_active','sort_order'
    ];

    protected $casts = [
        'cost' => 'float',
        'min_order' => 'float',
        'is_active' => 'integer',
        'sort_order' => 'integer',
    ];
}
