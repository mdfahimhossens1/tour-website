<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Validation Rules
     */
    public function rules(): array
    {
        return [

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')
                    ->ignore(auth()->id()),
            ],

            'phone' => [
                'nullable',
                'string',
                'max:20',
            ],

            'username' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('users', 'username')
                    ->ignore(auth()->id()),
            ],

            'photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

        ];
    }

    /**
     * Custom Messages
     */
    public function messages(): array
    {
        return [

            'name.required' => 'Full name is required.',

            'email.required' => 'Email is required.',

            'email.email' => 'Enter a valid email.',

            'email.unique' => 'This email is already taken.',

            'username.unique' => 'Username already exists.',

            'photo.image' => 'Photo must be an image.',

            'photo.max' => 'Maximum image size is 2MB.',

        ];
    }
}