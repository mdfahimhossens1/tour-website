<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DestinationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,

            'name' => $this->name,

            'slug' => $this->slug,

            'description' => $this->description,

            'image' => $this->image
                ? asset('uploads/destinations/' . $this->image)
                : null,

            'package_count' => $this->whenCounted('tours'),

            'status' => (bool) $this->status,

        ];
    }
}