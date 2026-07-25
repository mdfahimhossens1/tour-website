<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResortRoom extends Model
{
    use HasFactory;

    protected $fillable = [

        'resort_id',
        'room_name',
        'slug',
        'room_number',
        'room_type',
        'capacity',
        'price_per_night',
        'total_rooms',
        'available_rooms',
        'breakfast',
        'ac',
        'wifi',
        'parking',
        'status',
        'description'

    ];

    public function resort()
    {
        return $this->belongsTo(Resort::class);
    }
}
