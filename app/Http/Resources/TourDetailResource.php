<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TourDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,

            'destination_id' => $this->destination_id,

            'title' => $this->title,

            'slug' => $this->slug,

            'short_description' => $this->short_description,

            'description' => $this->description,

            'price' => (float) $this->price,

            'discount_price' => (float) ($this->discount_price ?? 0),

            'duration' => $this->duration,

            'location' => $this->location,

            'featured_image' => $this->featured_image
                ? asset('uploads/tours/' . $this->featured_image)
                : null,

            /*
            |--------------------------------------------------------------------------
            | Gallery Images
            |--------------------------------------------------------------------------
            */

            'images' => $this->galleries->map(function ($image) {

                return asset('uploads/tours/' . $image->image);

            })->values(),

            /*
            |--------------------------------------------------------------------------
            | Tour Information
            |--------------------------------------------------------------------------
            */

            'included' => $this->included
                ? preg_split('/\r\n|\r|\n/', $this->included)
                : [],

            'excluded' => $this->excluded
                ? preg_split('/\r\n|\r|\n/', $this->excluded)
                : [],

            'tour_plan' => $this->tour_plan,

            'hotel_name' => $this->hotel_name,

            'food_menu' => $this->food_menu,

            'backpack_price' => (float) $this->backpack_price,

            'moderate_price' => (float) $this->moderate_price,

            'luxury_price' => (float) $this->luxury_price,

            'ai_highlights' => $this->ai_highlights
                ? preg_split('/\r\n|\r|\n/', $this->ai_highlights)
                : [],

            /*
            |--------------------------------------------------------------------------
            | Seats
            |--------------------------------------------------------------------------
            */

            'max_seat' => (int) $this->max_seat,

            'available_seat' => max(
                0,
                $this->max_seat -
                $this->bookings()
                    ->where('booking_status', 'Confirmed')
                    ->sum('person_count')
            ),

            /*
            |--------------------------------------------------------------------------
            | Rating
            |--------------------------------------------------------------------------
            */

            'rating' => round(
                $this->reviews()->avg('rating') ?? 0,
                1
            ),

            'review_count' => $this->reviews()->count(),

            /*
            |--------------------------------------------------------------------------
            | Google Map
            |--------------------------------------------------------------------------
            */

            'map_iframe' => $this->map_iframe,

            /*
            |--------------------------------------------------------------------------
            | Flags
            |--------------------------------------------------------------------------
            */

            'is_featured' => (bool) $this->is_featured,

        ];
    }
}