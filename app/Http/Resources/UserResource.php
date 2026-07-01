<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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

            'role_id' => $this->role_id,

            'role' => $this->role?->name,

            'name' => $this->name,

            'username' => $this->username,

            'slug' => $this->slug,

            'email' => $this->email,

            'phone' => $this->phone,

            /*
            |--------------------------------------------------------------------------
            | Profile
            |--------------------------------------------------------------------------
            */

            'photo' => $this->photo
                ? asset('uploads/users/' . $this->photo)
                : null,

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            'status' => (bool) $this->status,

            'email_verified_at' => $this->email_verified_at,

            /*
            |--------------------------------------------------------------------------
            | Statistics
            |--------------------------------------------------------------------------
            */

            'total_bookings' => $this->whenLoaded(
                'bookings',
                fn () => $this->bookings->count()
            ),

            'total_reviews' => $this->whenLoaded(
                'reviews',
                fn () => $this->reviews->count()
            ),

            /*
            |--------------------------------------------------------------------------
            | Vendor
            |--------------------------------------------------------------------------
            */

            'is_vendor' => !is_null($this->vendor),

            'vendor_id' => $this->vendor?->id,

            /*
            |--------------------------------------------------------------------------
            | Audit
            |--------------------------------------------------------------------------
            */

            'creator' => $this->creator,

            'editor' => $this->editor,

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