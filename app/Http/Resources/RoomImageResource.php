<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomImageResource extends JsonResource
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

            'sort_order' => (int) $this->sort_order,

        ];
    }
}