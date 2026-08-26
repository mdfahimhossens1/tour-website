<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\RoomPrice;

class Resort extends Model
{
    protected $fillable = [
        'vendor_id',
        'destination_id',
        'name',
        'slug',
        'short_description',
        'description',
        'division',
        'district',
        'area',
        'address',
        'google_map',
        'latitude',
        'longitude',
        'featured_image',
        'check_in',
        'check_out',
        'rating',
        'total_reviews',
        'is_featured',
        'is_verified',
        'status',
        'meta_title',
        'meta_description',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function facilities()
    {
        return $this->belongsToMany(Facility::class);
    }

    public function images()
    {
        return $this->hasMany(ResortImage::class)
            ->orderBy('sort_order');
    }

    public function coverImage()
    {
        return $this->hasOne(ResortImage::class)
            ->where('is_cover', true)
            ->latest('id');
    }

    public function bookings()
    {
        return $this->hasMany(ResortBooking::class);
    }
    public function roomBookings()
    {
        return $this->hasMany(RoomBooking::class);
    }
    public function reviews()
    {
        return $this->hasMany(ResortReview::class);
    }

    public function wishlists()
    {
        return $this->hasMany(ResortWishlist::class);
    }

    public function getLowestPriceAttribute()
    {
        return RoomPrice::whereHas('room', function ($query) {
            $query->where('resort_id', $this->id);
        })->min('price') ?? 0;
    }

    public function scopeVendor($query, $vendorId)
    {
        return $query->where('vendor_id', $vendorId);
    }
}