<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,

            'name' => $this->name,

            'email' => $this->email,

            'phone' => $this->phone,

            'username' => $this->username,

            'photo' => $this->photo
                ? asset($this->photo)
                : null,

            'role' => $this->role?->name,

            'status' => $this->status,

            'created_at' => optional($this->created_at)
                ->format('d M Y'),

        ];
    }
}