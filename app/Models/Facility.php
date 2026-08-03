<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $fillable = [
        'vendor_id',
        'name',
        'icon',
        'type',
        'status'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

public function resorts()
{
    return $this->belongsToMany(Resort::class);
}

public function rooms()
{
    return $this->belongsToMany(Room::class);
}
}
