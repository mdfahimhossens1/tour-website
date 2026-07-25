<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomPrice extends Model
{
    protected $fillable=[

    'room_id',

    'from_date',

    'to_date',

    'price',

    'type'

];

protected $casts=[

    'from_date'=>'date',

    'to_date'=>'date',

];

public function room()
{
    return $this->belongsTo(Room::class);
}
}
