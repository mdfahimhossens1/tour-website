<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'image',
        'description',
        'status',
    ];

    // =========================
public function resorts()
{
    return $this->hasMany(Resort::class);
}
    public function tours()
    {
        return $this->hasMany(Tour::class);
    }
}