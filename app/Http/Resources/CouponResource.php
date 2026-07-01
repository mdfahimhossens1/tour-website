<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,

            'code' => strtoupper($this->code),

            'type' => $this->type,

            'value' => (float) $this->value,

            'max_usage' => (int) $this->max_usage,

            'used_count' => (int) $this->used_count,

            'remaining_usage' => max(
                0,
                (int) $this->max_usage - (int) $this->used_count
            ),

            'start_date' => optional($this->start_date)->format('Y-m-d'),

            'end_date' => optional($this->end_date)->format('Y-m-d'),

            'is_active' => (bool) $this->status,

            /*
            |--------------------------------------------------------------------------
            | Runtime Information
            |--------------------------------------------------------------------------
            */

            'is_valid' => $this->status
                && (
                    is_null($this->start_date)
                    || now()->gte($this->start_date)
                )
                && (
                    is_null($this->end_date)
                    || now()->lte($this->end_date)
                )
                && (
                    $this->used_count < $this->max_usage
                ),

        ];
    }
}