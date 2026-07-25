<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoomAvailability extends Model
{
    use HasFactory;

    protected $fillable = [

        'room_id',

        'date',

        'price',

        'total_rooms',

        'available_rooms',

        'is_closed',

        'is_sold_out'

    ];

    protected $casts = [

        'date'=>'date',

        'is_closed'=>'boolean',

        'is_sold_out'=>'boolean',

    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}