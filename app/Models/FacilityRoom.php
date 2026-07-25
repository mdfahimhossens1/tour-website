<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacilityRoom extends Model
{
    use HasFactory;

    protected $table = 'facility_room';

    protected $fillable = [
        'facility_id',
        'room_id',
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}