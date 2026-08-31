<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectAuthenticated
{
    /**
     * Redirect authenticated users away from login pages.
     */
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        /*
        |--------------------------------------------------------------------------
        | User is not logged in
        |--------------------------------------------------------------------------
        */

        if (!Auth::check()) {
            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | Get authenticated user
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Get role
        |--------------------------------------------------------------------------
        */

        $role = strtolower(
            str_replace(
                [' ', '-'],
                '_',
                trim(optional($user->role)->role_name ?? 'user')
            )
        );

        /*
        |--------------------------------------------------------------------------
        | Redirect according to role
        |--------------------------------------------------------------------------
        */

        return match ($role) {

            'super_admin',
            'admin',
            'manager'
                => redirect()->route('admin.dashboard'),

            'vendor'
                => redirect()->route('vendor.dashboard'),

            'user'
                => redirect()->route('user.dashboard'),

            default
                => redirect()->route('home'),
        };
    }
}