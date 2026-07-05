<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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

            'booking_id' => [
                'required',
                'integer',
                'exists:bookings,id',
            ],

            'trx_id' => [
                'required',
                'string',
                'max:255',
            ],

            'payment_method' => [
                'required',
                'string',
                'max:100',
            ],

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

            'booking_id.required' => 'Booking is required.',

            'booking_id.exists' => 'Invalid booking.',

            'trx_id.required' => 'Transaction ID is required.',

            'payment_method.required' => 'Please select a payment method.',

        ];
    }
}