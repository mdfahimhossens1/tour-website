<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\TourCardResource;

class TourTypeResource extends JsonResource
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
    'icon' => $this->icon,

    'image' => $this->image
        ? asset('uploads/tour-types/' . $this->image)
        : null,

    'short_description' => $this->short_description,

    'sort_order' => $this->sort_order,

    'status' => (bool) $this->status,

    'tour_count' => $this->tours_count ?? $this->tours()->count(),

    'tours' => TourCardResource::collection(
        $this->whenLoaded('tours')
    ),

    'created_at' => $this->created_at,
    'updated_at' => $this->updated_at,
];
    }
}