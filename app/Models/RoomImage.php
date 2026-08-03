<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoomImage extends Model
{
       use HasFactory;

    protected $fillable = [
        'room_id',
        'image',
        'is_cover',
        'sort_order',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    
}
