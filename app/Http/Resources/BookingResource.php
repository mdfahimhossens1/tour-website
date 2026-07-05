<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Booking Information
            |--------------------------------------------------------------------------
            */

            'id' => $this->id,

            'booking_code' => $this->booking_code,

            'person_count' => $this->person_count,

            /*
            |--------------------------------------------------------------------------
            | Pricing
            |--------------------------------------------------------------------------
            */

            'unit_price' => (float) $this->unit_price,

            'subtotal' => (float) $this->subtotal,

            'coupon_code' => $this->coupon_code,

            'discount' => (float) $this->discount,

            'total_amount' => (float) $this->total_amount,

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            'payment_status' => $this->payment_status,

            'booking_status' => $this->booking_status,

            /*
            |--------------------------------------------------------------------------
            | Commission
            |--------------------------------------------------------------------------
            */

            'admin_commission' => (float) $this->admin_commission,

            'vendor_earning' => (float) $this->vendor_earning,

            /*
            |--------------------------------------------------------------------------
            | Special Request
            |--------------------------------------------------------------------------
            */

            'special_request' => $this->special_request,

            /*
            |--------------------------------------------------------------------------
            | Tour
            |--------------------------------------------------------------------------
            */

            'tour' => [

                'id' => $this->tour?->id,

                'title' => $this->tour?->title,

                'slug' => $this->tour?->slug,

                'featured_image' => $this->tour?->featured_image
                    ? asset('uploads/tours/' . $this->tour->featured_image)
                    : null,

            ],

            /*
            |--------------------------------------------------------------------------
            | Tour Date
            |--------------------------------------------------------------------------
            */

            'tour_date' => [

                'id' => $this->tourDate?->id,

                'start_date' => $this->tourDate?->start_date,

                'end_date' => $this->tourDate?->end_date,

            ],

            /*
            |--------------------------------------------------------------------------
            | Transaction
            |--------------------------------------------------------------------------
            */

            'transaction' => $this->transaction ? [

                'id' => $this->transaction->id,

                'transaction_id' => $this->transaction->transaction_id,

                'payment_method' => $this->transaction->payment_method,

                'amount' => (float) $this->transaction->amount,

                'status' => $this->transaction->status,

            ] : null,

            /*
            |--------------------------------------------------------------------------
            | Payment
            |--------------------------------------------------------------------------
            */

            'payment' => $this->payment ? [

                'id' => $this->payment->id,

                'payment_method' => $this->payment->payment_method,

                'amount' => (float) $this->payment->amount,

                'status' => $this->payment->status,

                'paid_at' => $this->payment->paid_at,

            ] : null,

            /*
            |--------------------------------------------------------------------------
            | Dates
            |--------------------------------------------------------------------------
            */

            'created_at' => $this->created_at?->toDateTimeString(),

            'updated_at' => $this->updated_at?->toDateTimeString(),

        ];
    }
}