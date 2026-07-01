<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DestinationResource extends JsonResource
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

            'description' => $this->description,

            'image' => $this->image
                ? asset('storage/' . $this->image)
                : null,

            /*
            |--------------------------------------------------------------------------
            | Total Tours
            |--------------------------------------------------------------------------
            */

            'tour_count' => $this->whenCounted('tours'),

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            'status' => (bool) $this->status,

        ];
    }
}