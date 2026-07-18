<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AITripPlanResource extends JsonResource
{

   public function toArray($request): array
{
    return [

        'id' => $this->id,

        'destination' => $this->destination,

        'days' => $this->days,

        'travelers' => $this->travelers,

        'budget' => $this->budget,

        'travel_type' => $this->travel_type,

        'interests' => $this->interests,

        'hotel_type' => $this->hotel_type,

        'transport' => $this->transport,

        'response' => $this->response,

        'response_json' => $this->response_json,

        'created_at' => $this->created_at,

    ];
}
}
