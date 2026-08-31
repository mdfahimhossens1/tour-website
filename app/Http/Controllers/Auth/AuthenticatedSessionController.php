<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Normalize role name.
     */
    private function normalizeRole(?string $role): string
    {
        return strtolower(
            str_replace(
                [' ', '-'],
                '_',
                trim($role ?? '')
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ADMIN LOGIN PAGE
    |--------------------------------------------------------------------------
    */

    public function adminCreate(): View
    {
        return view('auth.admin-login');
    }


    /*
    |--------------------------------------------------------------------------
    | ADMIN LOGIN
    |--------------------------------------------------------------------------
    */

    public function adminStore(
        LoginRequest $request
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Authenticate
        |--------------------------------------------------------------------------
        */

        $request->authenticate();

        $user = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | Get Role
        |--------------------------------------------------------------------------
        */

        $role = $this->normalizeRole(
            optional($user->role)->role_name
        );


        /*
        |--------------------------------------------------------------------------
        | Admin Role Check
        |--------------------------------------------------------------------------
        */

        if (!in_array($role, [
            'super_admin',
            'admin',
            'manager',
        ], true)) {

            Auth::guard('web')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'এই অ্যাকাউন্ট দিয়ে Admin Panel-এ প্রবেশের অনুমতি নেই।',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Account Status
        |--------------------------------------------------------------------------
        */

        if ((int) $user->status !== 1) {

            Auth::guard('web')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'আপনার অ্যাকাউন্ট বর্তমানে নিষ্ক্রিয়।',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Prevent Session Fixation
        |--------------------------------------------------------------------------
        */

        $request->session()->regenerate();


        /*
        |--------------------------------------------------------------------------
        | Admin Dashboard
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->intended(route('admin.dashboard'));
    }


    /*
    |--------------------------------------------------------------------------
    | VENDOR LOGIN PAGE
    |--------------------------------------------------------------------------
    */

    public function vendorCreate(): View
    {
        return view('auth.vendor-login');
    }


    /*
    |--------------------------------------------------------------------------
    | VENDOR LOGIN
    |--------------------------------------------------------------------------
    */

    public function vendorStore(
        LoginRequest $request
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Authenticate
        |--------------------------------------------------------------------------
        */

        $request->authenticate();

        $user = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | Get Role
        |--------------------------------------------------------------------------
        */

        $role = $this->normalizeRole(
            optional($user->role)->role_name
        );


        /*
        |--------------------------------------------------------------------------
        | Vendor Role Check
        |--------------------------------------------------------------------------
        */

        if ($role !== 'vendor') {

            Auth::guard('web')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'এই অ্যাকাউন্ট দিয়ে Vendor Panel-এ প্রবেশের অনুমতি নেই।',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Account Status
        |--------------------------------------------------------------------------
        */

        if ((int) $user->status !== 1) {

            Auth::guard('web')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'আপনার অ্যাকাউন্ট বর্তমানে নিষ্ক্রিয়।',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Session Regeneration
        |--------------------------------------------------------------------------
        */

        $request->session()->regenerate();


        /*
        |--------------------------------------------------------------------------
        | Vendor Dashboard
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->intended(route('vendor.dashboard'));
    }


    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Request $request
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Logout User
        |--------------------------------------------------------------------------
        */

        Auth::guard('web')->logout();


        /*
        |--------------------------------------------------------------------------
        | Destroy Session
        |--------------------------------------------------------------------------
        */

        $request->session()->invalidate();


        /*
        |--------------------------------------------------------------------------
        | Regenerate CSRF Token
        |--------------------------------------------------------------------------
        */

        $request->session()->regenerateToken();


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('home')
            ->with('success', 'আপনি সফলভাবে লগআউট করেছেন।');
    }
}