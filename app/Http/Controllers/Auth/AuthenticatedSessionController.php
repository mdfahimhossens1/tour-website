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
     * Display Admin Login Page
     */
    public function adminCreate(): View
    {
        return view('auth.admin-login');
    }

    /**
     * Handle Admin Login
     */
    public function adminStore(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = Auth::user();

        $role = str(
            optional($user->role)->role_name ?? 'user'
        )
            ->lower()
            ->replace([' ', '-'], '_')
            ->toString();

        /*
        |--------------------------------------------------------------------------
        | Only Admin Roles Can Login
        |--------------------------------------------------------------------------
        */

        if (! in_array($role, [
            'super_admin',
            'admin',
            'manager',
        ])) {
            Auth::guard('web')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'This account does not have admin access.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Regenerate Session
        |--------------------------------------------------------------------------
        */

        $request->session()->regenerate();

        /*
        |--------------------------------------------------------------------------
        | Redirect To Admin Dashboard
        |--------------------------------------------------------------------------
        */

        return redirect()->route('admin.dashboard');
    }

    /**
     * Display Vendor Login Page
     */
    public function vendorCreate(): View
    {
        return view('auth.vendor-login');
    }

    /**
     * Handle Vendor Login
     */
    public function vendorStore(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = Auth::user();

        $role = str(
            optional($user->role)->role_name ?? 'user'
        )
            ->lower()
            ->replace([' ', '-'], '_')
            ->toString();

        /*
        |--------------------------------------------------------------------------
        | Only Vendor Can Login
        |--------------------------------------------------------------------------
        */

        if ($role !== 'vendor') {
            Auth::guard('web')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'This account does not have vendor access.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Regenerate Session
        |--------------------------------------------------------------------------
        */

        $request->session()->regenerate();

        /*
        |--------------------------------------------------------------------------
        | Redirect To Vendor Dashboard
        |--------------------------------------------------------------------------
        */

        return redirect()->route('vendor.dashboard');
    }

    /**
     * Logout
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}