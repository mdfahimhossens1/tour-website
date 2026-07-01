<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TravelerResource extends JsonResource
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

            'booking_id' => $this->booking_id,

            'booking_code' => $this->booking?->booking_code,

            /*
            |--------------------------------------------------------------------------
            | Traveler Information
            |--------------------------------------------------------------------------
            */

            'name' => $this->name,

            'phone' => $this->phone,

            'email' => $this->email,

            'age' => $this->age,

            'gender' => $this->gender,

            'address' => $this->address,

            /*
            |--------------------------------------------------------------------------
            | Dates
            |--------------------------------------------------------------------------
            */

            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),

            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),

        ];
    }
}