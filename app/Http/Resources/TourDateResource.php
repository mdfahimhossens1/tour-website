<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TourDateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,

            'tour_id' => $this->tour_id,

            'start_date' => $this->start_date,

            'end_date' => $this->end_date,

            'special_price' => $this->special_price
                ? (float) $this->special_price
                : null,

            'available_seat' => (int) $this->available_seat,

            'status' => (bool) $this->status,

        ];
    }
}