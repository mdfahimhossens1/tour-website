<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TourResource extends JsonResource
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

            'discount_price' => (float) $this->discount_price,

            'duration' => $this->duration,

            'location' => $this->location,

            'featured_image' => $this->featured_image
                ? asset('uploads/tours/'.$this->featured_image)
                : null,

            'included' => $this->included,

            'excluded' => $this->excluded,

            'tour_plan' => $this->tour_plan,

            'max_seat' => $this->max_seat,

            'map_iframe' => $this->map_iframe,

            /*
            |--------------------------------------------------------------------------
            | AI Planner Fields
            |--------------------------------------------------------------------------
            */

            'hotel_name' => $this->hotel_name,

            'food_menu' => $this->food_menu,

            'backpack_price' => (float) $this->backpack_price,

            'moderate_price' => (float) $this->moderate_price,

            'luxury_price' => (float) $this->luxury_price,

            'ai_highlights' => $this->ai_highlights
                ? explode("\n", $this->ai_highlights)
                : [],

            'is_featured' => (bool) $this->is_featured,

            'status' => $this->status,

            'created_at' => $this->created_at,

        ];
    }
}