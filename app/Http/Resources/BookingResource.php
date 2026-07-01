<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,

            'booking_code' => $this->booking_code,

            'person_count' => $this->person_count,

            'total_amount' => (float) $this->total_amount,

            'payment_status' => $this->payment_status,

            'booking_status' => $this->booking_status,

            'special_request' => $this->special_request,

            'tour' => [
                'id' => $this->tour?->id,
                'title' => $this->tour?->title,
                'slug' => $this->tour?->slug,
                'featured_image' => $this->tour?->featured_image
                    ? asset('uploads/tours/'.$this->tour->featured_image)
                    : null,
            ],

            'tour_date' => [
                'id' => $this->tourDate?->id,
                'start_date' => $this->tourDate?->start_date,
                'end_date' => $this->tourDate?->end_date,
            ],

            'created_at' => $this->created_at?->format('d M Y h:i A'),

        ];
    }
}