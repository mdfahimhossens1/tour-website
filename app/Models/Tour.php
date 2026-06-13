<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tour extends Model
{

    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'destination_id',
        'title',
        'slug',
        'short_description',
        'description',
        'price',
        'discount_price',
        'duration',
        'location',
        'featured_image',
        'included',
        'excluded',
        'tour_plan',
        'max_seat',
        'map_iframe',
        'is_featured',
        'status',
    ];

    // =========================
    // RELATIONS
    // =========================

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function dates()
    {
        return $this->hasMany(TourDate::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }

    

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}