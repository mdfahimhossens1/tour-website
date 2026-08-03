<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomPriceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,

            'from_date' => optional($this->from_date)->format('Y-m-d'),

            'to_date' => optional($this->to_date)->format('Y-m-d'),

            'price' => (float) $this->price,

            'discount_price' => $this->discount_price !== null
                ? (float) $this->discount_price
                : null,

            'type' => $this->type,

            /*
            |----------------------------------------------------------
            | Computed Price
            |----------------------------------------------------------
            */

            'final_price' => $this->discount_price !== null
                ? (float) $this->discount_price
                : (float) $this->price,

        ];
    }
}