<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\RoomTypeResource;
use App\Http\Resources\FacilityResource;
use App\Http\Resources\RoomPriceResource;
use App\Http\Resources\RoomImageResource;

class RoomResource extends JsonResource
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

            'room_no' => $this->room_no,

            'description' => $this->description,

            'featured_image' => $this->featured_image
                ? asset('storage/' . $this->featured_image)
                : null,

            'extra_bed_price' => $this->extra_bed_price
                ? (float) $this->extra_bed_price
                : null,

            'total_rooms' => $this->total_rooms,

            'max_adult' => $this->max_adult,

            'max_child' => $this->max_child,

            'beds' => $this->beds,

            'bathrooms' => $this->bathrooms,

            'size' => $this->size,

            'size_unit' => $this->size_unit,

            'view_type' => $this->view_type,

            'is_featured' => (bool) $this->is_featured,

            'status' => $this->status,

            'room_type' => new RoomTypeResource(
                $this->whenLoaded('roomType')
            ),

            'prices' => RoomPriceResource::collection(
                $this->whenLoaded('prices')
            ),

            'facilities' => FacilityResource::collection(
                $this->whenLoaded('facilities')
            ),

            'images' => RoomImageResource::collection(
                $this->whenLoaded('images')
            ),

        ];
    }
}