<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResortResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,

            'name' => $this->name,

            'slug' => $this->slug,

            'short_description' => $this->short_description,

            'description' => $this->description,

            'division' => $this->division,

            'district' => $this->district,

            'area' => $this->area,

            'address' => $this->address,

            'google_map' => $this->google_map,

            'latitude' => $this->latitude,

            'longitude' => $this->longitude,

            'featured_image' => $this->featured_image
                ? asset('uploads/' . $this->featured_image)
                : null,

            'cover_image' => $this->cover_image
                ? asset('uploads/' . $this->cover_image)
                : null,

            'lowest_price' => $this->lowest_price,

            'rating' => $this->rating,

            'total_reviews' => $this->total_reviews,

            'check_in' => $this->check_in,

            'check_out' => $this->check_out,

            'is_featured' => (bool) $this->is_featured,

            'is_verified' => (bool) $this->is_verified,

            'status' => $this->status,

            'destination' => $this->destination?->name,

            'vendor' => $this->vendor?->business_name,

            /*
            |--------------------------------------------------------------------------
            | Relations
            |--------------------------------------------------------------------------
            */

            'images' => ResortImageResource::collection(
                $this->whenLoaded('images')
            ),

            'facilities' => FacilityResource::collection(
                $this->whenLoaded('facilities')
            ),

            'rooms' => RoomResource::collection(
                $this->whenLoaded('rooms')
            ),

            'created_at' => $this->created_at,

            'updated_at' => $this->updated_at,

        ];
    }
}