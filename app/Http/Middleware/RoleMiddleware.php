<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Normalize role name.
     */
    private function normalize(?string $role): string
    {
        return strtolower(
            str_replace(
                [' ', '-'],
                '_',
                trim($role ?? 'user')
            )
        );
    }

    /**
     * Handle role based authorization.
     */
    public function handle(
        Request $request,
        Closure $next,
        ...$roles
    ): Response {

        /*
        |--------------------------------------------------------------------------
        | Authentication Check
        |--------------------------------------------------------------------------
        */

        if (!Auth::check()) {

            if ($request->is('admin/*')) {
                return redirect()->route('admin.login');
            }

            if ($request->is('vendor/*')) {
                return redirect()->route('vendor.login');
            }

            return redirect()->route('home');
        }


        /*
        |--------------------------------------------------------------------------
        | Get Authenticated User
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | User Role
        |--------------------------------------------------------------------------
        */

        $userRole = $this->normalize(
            optional($user->role)->role_name
        );


        /*
        |--------------------------------------------------------------------------
        | Allowed Roles
        |--------------------------------------------------------------------------
        */

        $allowedRoles = array_map(
            fn ($role) => $this->normalize($role),
            $roles
        );


        /*
        |--------------------------------------------------------------------------
        | Authorization
        |--------------------------------------------------------------------------
        */

        if (!in_array($userRole, $allowedRoles, true)) {

            /*
            |--------------------------------------------------------------------------
            | If Vendor tries Admin
            |--------------------------------------------------------------------------
            */

            if ($userRole === 'vendor') {
                return redirect()
                    ->route('vendor.dashboard')
                    ->with('error', 'আপনার এই অংশে প্রবেশের অনুমতি নেই।');
            }


            /*
            |--------------------------------------------------------------------------
            | If Admin tries Vendor
            |--------------------------------------------------------------------------
            */

            if (
                in_array(
                    $userRole,
                    ['admin', 'super_admin', 'manager'],
                    true
                )
            ) {
                return redirect()
                    ->route('admin.dashboard')
                    ->with('error', 'আপনার এই অংশে প্রবেশের অনুমতি নেই।');
            }


            /*
            |--------------------------------------------------------------------------
            | Other users
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route('home')
                ->with('error', 'আপনার এই অংশে প্রবেশের অনুমতি নেই।');
        }


        return $next($request);
    }
}