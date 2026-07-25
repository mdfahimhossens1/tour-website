<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $fillable = [
        'name',
        'icon',
        'type',
        'status'
    ];

public function resorts()
{
    return $this->belongsToMany(Resort::class);
}

public function rooms()
{
    return $this->belongsToMany(Room::class);
}
}
