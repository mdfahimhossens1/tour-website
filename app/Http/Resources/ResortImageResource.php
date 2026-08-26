<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResortImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'image' => $this->image
                ? asset('storage/' . $this->image)
                : null,

            'is_cover' => (bool) $this->is_cover,

            'sort_order' => $this->sort_order,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}