<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TourType extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'image',
        'description',
        'status',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function tours()
    {
        return $this->hasMany(Tour::class);
    }
}