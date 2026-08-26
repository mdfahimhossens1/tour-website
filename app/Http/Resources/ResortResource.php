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
        /*
        |--------------------------------------------------------------------------
        | Cover Image
        |--------------------------------------------------------------------------
        */

        $coverImage = null;

        if ($this->relationLoaded('coverImage')) {
            $coverImage = $this->coverImage?->image
                ? asset('storage/' . $this->coverImage->image)
                : null;
        }


        /*
        |--------------------------------------------------------------------------
        | Fallback Cover Image
        |--------------------------------------------------------------------------
        |
        | If coverImage relation is not available, find cover from images.
        |
        */

        if (!$coverImage && $this->relationLoaded('images')) {
            $cover = $this->images->firstWhere('is_cover', true);

            if ($cover?->image) {
                $coverImage = asset('storage/' . $cover->image);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Featured Image
        |--------------------------------------------------------------------------
        */

        $featuredImage = $this->featured_image
            ? asset('storage/' . $this->featured_image)
            : null;


        return [

            /*
            |--------------------------------------------------------------------------
            | Basic Information
            |--------------------------------------------------------------------------
            */

            'id' => $this->id,

            'name' => $this->name,

            'slug' => $this->slug,

            'short_description' => $this->short_description,

            'description' => $this->description,


            /*
            |--------------------------------------------------------------------------
            | Location
            |--------------------------------------------------------------------------
            */

            'division' => $this->division,

            'district' => $this->district,

            'area' => $this->area,

            'address' => $this->address,

            'google_map' => $this->google_map,

            'latitude' => $this->latitude,

            'longitude' => $this->longitude,


            /*
            |--------------------------------------------------------------------------
            | Images
            |--------------------------------------------------------------------------
            */

            'featured_image' => $featuredImage,

            'cover_image' => $coverImage,


            /*
            |--------------------------------------------------------------------------
            | Pricing
            |--------------------------------------------------------------------------
            */

            'lowest_price' => (float) $this->lowest_price,


            /*
            |--------------------------------------------------------------------------
            | Rating
            |--------------------------------------------------------------------------
            */

            'rating' => (float) ($this->rating ?? 0),

            'total_reviews' => (int) ($this->total_reviews ?? 0),


            /*
            |--------------------------------------------------------------------------
            | Check In / Check Out
            |--------------------------------------------------------------------------
            */

            'check_in' => $this->check_in,

            'check_out' => $this->check_out,


            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            'is_featured' => (bool) $this->is_featured,

            'is_verified' => (bool) $this->is_verified,

            'status' => $this->status,


            /*
            |--------------------------------------------------------------------------
            | Destination / Vendor
            |--------------------------------------------------------------------------
            */

            'destination' => $this->destination?->name,

            'vendor' => $this->vendor?->business_name,


            /*
            |--------------------------------------------------------------------------
            | Resort Gallery
            |--------------------------------------------------------------------------
            */

            'images' => ResortImageResource::collection(
                $this->whenLoaded('images')
            ),


            /*
            |--------------------------------------------------------------------------
            | Facilities
            |--------------------------------------------------------------------------
            */

            'facilities' => FacilityResource::collection(
                $this->whenLoaded('facilities')
            ),


            /*
            |--------------------------------------------------------------------------
            | Rooms
            |--------------------------------------------------------------------------
            */

            'rooms' => RoomResource::collection(
                $this->whenLoaded('rooms')
            ),


            /*
            |--------------------------------------------------------------------------
            | Timestamps
            |--------------------------------------------------------------------------
            */

            'created_at' => $this->created_at,

            'updated_at' => $this->updated_at,
        ];
    }
}
