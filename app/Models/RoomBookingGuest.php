<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoomBookingGuest extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_booking_id',
        'name',
        'age',
        'gender',
        'phone',
        'nid',
        'passport',
    ];

    public function booking()
    {
        return $this->belongsTo(RoomBooking::class, 'room_booking_id');
    }
}