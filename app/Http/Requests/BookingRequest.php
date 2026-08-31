<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized.
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
            |----------------------------------------------------------------------
            | Tour Information
            |----------------------------------------------------------------------
            */

            'tour_id' => [
                'required',
                'integer',
                'exists:tours,id',
            ],

            'tour_date_id' => [
                'required',
                'integer',
                'exists:tour_dates,id',
            ],

            /*
            |----------------------------------------------------------------------
            | Booking Information
            |----------------------------------------------------------------------
            */

            'person_count' => [
                'required',
                'integer',
                'min:1',
                'max:50',
            ],

            /*
            |----------------------------------------------------------------------
            | Coupon
            |----------------------------------------------------------------------
            */

            'coupon_code' => [
                'nullable',
                'string',
                'max:50',
            ],

            /*
            |----------------------------------------------------------------------
            | Special Request
            |----------------------------------------------------------------------
            */

            'special_request' => [
                'nullable',
                'string',
                'max:1000',
            ],

        ];
    }

    /**
     * Custom Error Messages
     */
    public function messages(): array
    {
        return [

            'tour_id.required' => 'Please select a tour.',
            'tour_id.integer' => 'Invalid tour selected.',
            'tour_id.exists' => 'Selected tour does not exist.',

            'tour_date_id.required' => 'Please select a tour date.',
            'tour_date_id.integer' => 'Invalid tour date selected.',
            'tour_date_id.exists' => 'Selected tour date is invalid.',

            'person_count.required' => 'Please enter person count.',
            'person_count.integer' => 'Person count must be a number.',
            'person_count.min' => 'Minimum booking is 1 person.',
            'person_count.max' => 'Maximum booking limit is 50 persons.',

        ];
    }

    /**
     * Prepare Data Before Validation
     */
    protected function prepareForValidation(): void
    {
        $data = [];

        /*
        |----------------------------------------------------------------------
        | Normalize Coupon Code
        |----------------------------------------------------------------------
        */

        if ($this->filled('coupon_code')) {

            $data['coupon_code'] = strtoupper(
                trim($this->input('coupon_code'))
            );

        } else {

            $data['coupon_code'] = null;
        }

        $this->merge($data);
    }
}