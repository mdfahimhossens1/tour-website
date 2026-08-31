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

            'admin_commission' => (float) ($this->admin_commission ?? 0),

            'vendor_earning' => (float) ($this->vendor_earning ?? 0),

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

            'tour' => $this->tour ? [

                'id' => $this->tour->id,

                'title' => $this->tour->title,

                'slug' => $this->tour->slug,

                'featured_image' => $this->tour->featured_image
                    ? asset('uploads/tours/' . $this->tour->featured_image)
                    : null,

            ] : null,

            /*
            |--------------------------------------------------------------------------
            | Tour Date
            |--------------------------------------------------------------------------
            */

            'tour_date' => $this->tourDate ? [

                'id' => $this->tourDate->id,

                'start_date' => $this->tourDate->start_date,

                'end_date' => $this->tourDate->end_date,

            ] : null,

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

                'paid_at' => $this->transaction->paid_at,

            ] : null,

            /*
            |--------------------------------------------------------------------------
            | Latest Payment
            |--------------------------------------------------------------------------
            |
            | The Booking model's payment() relationship returns
            | a single latest Payment model.
            |
            */

            'payment' => $this->payment ? [

                'id' => $this->payment->id,

                'trx_id' => $this->payment->trx_id,

                'payment_method' => $this->payment->payment_method,

                'amount' => (float) $this->payment->amount,

                'status' => $this->payment->status,

                'paid_at' => $this->payment->paid_at
                    ? $this->payment->paid_at->toDateTimeString()
                    : null,

            ] : null,

            /*
            |--------------------------------------------------------------------------
            | Dates
            |--------------------------------------------------------------------------
            */

            'created_at' => $this->created_at
                ? $this->created_at->toDateTimeString()
                : null,

            'updated_at' => $this->updated_at
                ? $this->updated_at->toDateTimeString()
                : null,

        ];
    }
}
