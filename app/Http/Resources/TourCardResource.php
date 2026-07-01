<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TourCardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,

            'title' => $this->title,

            'slug' => $this->slug,

            'category' => optional($this->destination)->name,

            'location' => $this->location,

            'duration' => $this->duration,

            'featured_image' => $this->featured_image
                ? asset('uploads/tours/' . $this->featured_image)
                : null,

            'price' => (float) $this->price,

            'discount_price' => (float) ($this->discount_price ?? 0),

            'rating' => round(
                $this->reviews()->avg('rating') ?? 0,
                1
            ),

            'review_count' => $this->reviews()->count(),

            'available_seat' => max(
                0,
                $this->max_seat -
                $this->bookings()
                    ->where('booking_status', 'Confirmed')
                    ->sum('person_count')
            ),

            'is_featured' => (bool) $this->is_featured,

        ];
    }
}