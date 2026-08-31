<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
        /*
        |--------------------------------------------------------------------------
        | Rate Limit Check
        |--------------------------------------------------------------------------
        */

        $this->ensureIsNotRateLimited();

        /*
        |--------------------------------------------------------------------------
        | Find User
        |--------------------------------------------------------------------------
        */

        $user = User::with([
            'role',
            'vendor',
        ])
            ->where('email', $this->email)
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Check Email & Password
        |--------------------------------------------------------------------------
        */

        if (
            !$user ||
            !Hash::check(
                $this->password,
                $user->password
            )
        ) {

            RateLimiter::hit(
                $this->throttleKey()
            );

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Get Role
        |--------------------------------------------------------------------------
        */

        $roleName = strtolower(
            str_replace(
                [' ', '-'],
                '_',
                trim(
                    optional($user->role)->role_name ?? ''
                )
            )
        );

        /*
        |--------------------------------------------------------------------------
        | Vendor Approval Check
        |--------------------------------------------------------------------------
        |
        | Vendor status:
        |
        | pending  = Waiting for approval
        | approved = Approved
        | rejected = Rejected
        |
        |--------------------------------------------------------------------------
        */

        if ($roleName === 'vendor') {

            /*
            |------------------------------------------------------------------
            | Vendor Profile Must Exist
            |------------------------------------------------------------------
            */

            if (!$user->vendor) {

                RateLimiter::clear(
                    $this->throttleKey()
                );

                throw ValidationException::withMessages([
                    'email' => 'Vendor profile not found.',
                ]);
            }

            /*
            |------------------------------------------------------------------
            | Get Vendor Status
            |------------------------------------------------------------------
            */

            $vendorStatus = strtolower(
                trim(
                    (string) $user->vendor->status
                )
            );

            /*
            |------------------------------------------------------------------
            | Pending / Rejected / Unknown
            |------------------------------------------------------------------
            */

            if ($vendorStatus !== 'approved') {

                RateLimiter::clear(
                    $this->throttleKey()
                );

                $message = match ($vendorStatus) {

                    'pending' =>
                        'Your vendor account is pending approval. Please wait for administrator approval.',

                    'rejected' =>
                        'Your vendor account has been rejected. Please contact the administrator.',

                    default =>
                        'Your vendor account is not approved yet. Please contact the administrator.',
                };

                throw ValidationException::withMessages([
                    'email' => $message,
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Check User Account Status
        |--------------------------------------------------------------------------
        |
        | 0 = Inactive
        | 1 = Active
        |
        |--------------------------------------------------------------------------
        */

        if ((int) $user->status !== 1) {

            RateLimiter::clear(
                $this->throttleKey()
            );

            throw ValidationException::withMessages([
                'email' =>
                    'Your account is currently inactive. Please contact the administrator.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Login User
        |--------------------------------------------------------------------------
        */

        Auth::login(
            $user,
            $this->boolean('remember')
        );

        /*
        |--------------------------------------------------------------------------
        | Clear Rate Limiter
        |--------------------------------------------------------------------------
        */

        RateLimiter::clear(
            $this->throttleKey()
        );
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (
            !RateLimiter::tooManyAttempts(
                $this->throttleKey(),
                5
            )
        ) {
            return;
        }

        event(
            new Lockout($this)
        );

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
            Str::lower(
                $this->string('email')
            )
            . '|'
            . $this->ip()
        );
    }
}
