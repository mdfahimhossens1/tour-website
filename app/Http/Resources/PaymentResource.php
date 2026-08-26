<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Payment
            |--------------------------------------------------------------------------
            */

            'id' => $this->id,

            /*
            |--------------------------------------------------------------------------
            | Paymentable
            |--------------------------------------------------------------------------
            |
            | Payment কোন model-এর সাথে সম্পর্কিত
            | যেমন:
            | RoomBooking
            | Booking
            |
            |--------------------------------------------------------------------------
            */

            'paymentable_id' => $this->paymentable_id,

            'paymentable_type' => $this->paymentable_type,

            /*
            |--------------------------------------------------------------------------
            | Booking Information
            |--------------------------------------------------------------------------
            */

            'booking_code' => $this->paymentable?->booking_code,

            /*
            |--------------------------------------------------------------------------
            | Transaction
            |--------------------------------------------------------------------------
            */

            'trx_id' => $this->trx_id,

            /*
            |--------------------------------------------------------------------------
            | Payment Information
            |--------------------------------------------------------------------------
            */

            'payment_method' => $this->payment_method,

            'amount' => (float) $this->amount,

            'status' => $this->status,

            /*
            |--------------------------------------------------------------------------
            | Payment Data
            |--------------------------------------------------------------------------
            */

            'payment_data' => $this->payment_data ?? [],

            /*
            |--------------------------------------------------------------------------
            | Payment Date
            |--------------------------------------------------------------------------
            */

            'paid_at' => $this->paid_at
                ? $this->paid_at->format('Y-m-d H:i:s')
                : null,

            'created_at' => $this->created_at
                ? $this->created_at->format('Y-m-d H:i:s')
                : null,

            'updated_at' => $this->updated_at
                ? $this->updated_at->format('Y-m-d H:i:s')
                : null,
        ];
    }
}