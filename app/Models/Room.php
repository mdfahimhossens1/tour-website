<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [

        'resort_id',

        'room_type_id',

        'name',

        'slug',

        'room_no',

        'description',

        'price',

        'discount_price',

        'extra_bed_price',

        'total_rooms',

        'max_adult',

        'max_child',

        'beds',

        'bathrooms',

        'size',

        'size_unit',

        'view_type',

        'featured_image',

        'is_featured',

        'status',

    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function resort()
    {
        return $this->belongsTo(Resort::class);
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    public function facilities()
    {
        return $this->belongsToMany(
            Facility::class,
            'facility_room'
        );
    }

    public function images()
    {
        return $this->hasMany(RoomImage::class);
    }

    public function availabilities()
    {
        return $this->hasMany(RoomAvailability::class);
    }

    public function prices()
    {
        return $this->hasMany(RoomPrice::class);
    }

    public function bookings()
    {
        return $this->hasMany(ResortBooking::class);
    }
}