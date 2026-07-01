<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommissionResource extends JsonResource
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
            | Tour Information
            |--------------------------------------------------------------------------
            */

            'tour_id' => $this->booking?->tour_id,

            'tour_title' => $this->booking?->tour?->title,

            /*
            |--------------------------------------------------------------------------
            | Customer
            |--------------------------------------------------------------------------
            */

            'customer_name' => $this->booking?->user?->name,

            /*
            |--------------------------------------------------------------------------
            | Commission
            |--------------------------------------------------------------------------
            */

            'total_amount' => (float) $this->total_amount,

            'commission_rate' => (float) $this->commission_rate,

            'admin_earning' => (float) $this->admin_earning,

            'vendor_earning' => (float) $this->vendor_earning,

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