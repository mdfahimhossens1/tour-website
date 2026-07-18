<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiTripPlan extends Model
{
    protected $fillable = [

    'user_id',
    'from_location',
    'destination',
    'days',
    'travelers',
    'budget',
    'travel_type',
    'interests',
    'hotel_type',
    'transport',
    'extra_note',
    'prompt',
    'response',
    'response_json',

    ];

    protected $casts = [

    'interests'=>'array',
    'response_json'=>'array',

    ];

    public function user()
{
    return $this->belongsTo(User::class);
}
}
