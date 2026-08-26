<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Basic Information
            |--------------------------------------------------------------------------
            */

            'id' => $this->id,

            'name' => $this->name,

            'slug' => $this->slug,

            'room_no' => $this->room_no,

            'description' => $this->description,


            /*
            |--------------------------------------------------------------------------
            | Image
            |--------------------------------------------------------------------------
            */

            'featured_image' => $this->featured_image
                ? asset('storage/' . $this->featured_image)
                : null,


            /*
            |--------------------------------------------------------------------------
            | Pricing
            |--------------------------------------------------------------------------
            */

            'extra_bed_price' => $this->extra_bed_price !== null
                ? (float) $this->extra_bed_price
                : null,


            /*
            |--------------------------------------------------------------------------
            | Room Capacity
            |--------------------------------------------------------------------------
            */

            'total_rooms' => (int) $this->total_rooms,

            'max_adult' => (int) $this->max_adult,

            'max_child' => (int) $this->max_child,

            'beds' => (int) $this->beds,

            'bathrooms' => (int) $this->bathrooms,


            /*
            |--------------------------------------------------------------------------
            | Room Size
            |--------------------------------------------------------------------------
            */

            'size' => $this->size !== null
                ? (float) $this->size
                : null,

            'size_unit' => $this->size_unit,

            'view_type' => $this->view_type,


            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            'is_featured' => (bool) $this->is_featured,

            'status' => $this->status,


/*
|--------------------------------------------------------------------------
| Room Type
|--------------------------------------------------------------------------
*/

'room_type_id' => $this->room_type_id,

'room_type' => new RoomTypeResource(
    $this->whenLoaded('roomType')
),

/*
|--------------------------------------------------------------------------
| Resort
|--------------------------------------------------------------------------
*/

'resort' => $this->whenLoaded('resort', function () {
    return [
        'id' => $this->resort->id,
        'name' => $this->resort->name,
        'slug' => $this->resort->slug,
    ];
}),


            /*
            |--------------------------------------------------------------------------
            | Prices
            |--------------------------------------------------------------------------
            */

            'prices' => RoomPriceResource::collection(
                $this->whenLoaded('prices')
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
            | Images
            |--------------------------------------------------------------------------
            */

            'images' => RoomImageResource::collection(
                $this->whenLoaded('images')
            ),

        ];
    }
}