<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'string',
                'email',
            ],

            'password' => [
                'required',
                'string',
            ],

            'remember' => [
                'nullable',
                'boolean',
            ],
        ];
    }


    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();


        /*
        |--------------------------------------------------------------------------
        | Attempt Login
        |--------------------------------------------------------------------------
        */

        if (!Auth::attempt(
            $this->only('email', 'password'),
            $this->boolean('remember')
        )) {

            RateLimiter::hit($this->throttleKey());


            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Clear Rate Limiter
        |--------------------------------------------------------------------------
        */

        RateLimiter::clear($this->throttleKey());
    }


    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Allow Login If Not Rate Limited
        |--------------------------------------------------------------------------
        */

        if (!RateLimiter::tooManyAttempts(
            $this->throttleKey(),
            5
        )) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Lockout Event
        |--------------------------------------------------------------------------
        */

        event(new Lockout($this));


        /*
        |--------------------------------------------------------------------------
        | Remaining Lockout Time
        |--------------------------------------------------------------------------
        */

        $seconds = RateLimiter::availableIn(
            $this->throttleKey()
        );


        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }


    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(
            Str::lower($this->string('email'))
            . '|'
            . $this->ip()
        );
    }
}
