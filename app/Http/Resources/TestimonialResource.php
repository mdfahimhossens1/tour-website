<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TestimonialResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,

            'name' => $this->name,

            'designation' => $this->designation,

            'message' => $this->message,

            'rating' => (float) $this->rating,

            'image' => $this->image
                ? asset('storage/' . $this->image)
                : null,

            'status' => $this->status,

            'created_at' => optional($this->created_at)->format('Y-m-d'),

        ];
    }
}