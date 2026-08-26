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
        'tour_type_id',
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
        'hotel_name',
        'food_menu',
        'backpack_price',
        'moderate_price',
        'luxury_price',
        'ai_highlights',
        'is_featured',
        'status',
        'approval_status',
        'approved_by',
    ];

    /*
    |--------------------------------------------------------------------------
    | Append custom attributes
    |--------------------------------------------------------------------------
    */

    protected $appends = [
        'image_url',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

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

    public function tourType()
    {
        return $this->belongsTo(TourType::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Featured Image URL
    |--------------------------------------------------------------------------
    */

    public function getImageUrlAttribute()
    {
        if (empty($this->featured_image)) {
            return null;
        }

        $image = ltrim($this->featured_image, '/');

        /*
        | Already full URL
        */

        if (filter_var($image, FILTER_VALIDATE_URL)) {
            return $image;
        }

        /*
        | uploads/tours/filename.jpg
        */

        if (str_starts_with($image, 'uploads/tours/')) {
            return asset($image);
        }

        /*
        | uploads/filename.jpg
        */

        if (str_starts_with($image, 'uploads/')) {
            return asset($image);
        }

        /*
        | Only filename
        */

        return asset('uploads/tours/' . $image);
    }
}