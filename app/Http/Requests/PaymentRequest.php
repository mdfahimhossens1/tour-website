<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation Rules
     */
    public function rules(): array
    {
        return [

            /*
            |--------------------------------------------------------------
            | Booking
            |--------------------------------------------------------------
            */

            'booking_id' => [
                'required',
                'integer',
                'min:1',
            ],

            'booking_type' => [
                'required',
                Rule::in([
                    'booking',
                    'room_booking',
                    'transport_booking',
                ]),
            ],


            /*
            |--------------------------------------------------------------
            | Payment Method
            |--------------------------------------------------------------
            */

            'payment_method' => [
                'required',
                Rule::in([
                    'bkash',
                    'nagad',
                    'bank',
                    'stripe',
                    'paypal',
                    'manual',
                ]),
            ],

            'trx_id' => [
                'required',
                'string',
                'max:255',
            ],


            /*
            |--------------------------------------------------------------
            | Optional Information
            |--------------------------------------------------------------
            */

            'note' => [
                'nullable',
                'string',
                'max:1000',
            ],

        ];
    }

    /**
     * Custom Messages
     */
    public function messages(): array
    {
        return [
            'booking_id.required' =>
                'Booking is required.',

            'booking_type.required' =>
                'Booking type is required.',

            'booking_type.in' =>
                'Invalid booking type.',

            'payment_method.required' =>
                'Please select a payment method.',

            'payment_method.in' =>
                'Invalid payment method.',

            'trx_id.required' =>
                'Transaction ID is required.',
        ];
    }
}