<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResortBookingGuest extends Model
{
    protected $fillable = [

    'resort_booking_id',

    'name',

    'age',

    'gender',

    'phone',

    'nid',

    'passport'

];

public function booking()
{
    return $this->belongsTo(ResortBooking::class,'resort_booking_id');
}
}
