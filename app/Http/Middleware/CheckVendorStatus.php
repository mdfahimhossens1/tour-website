<?php

namespace App\Http\Middleware;

use App\Models\Vendor;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckVendorStatus
{
    /**
     * Handle an incoming request.
     */
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        /*
        |--------------------------------------------------------------------------
        | User Must Be Logged In
        |--------------------------------------------------------------------------
        */

        if (!Auth::check()) {
            return redirect()->route('vendor.login');
        }

        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Check Vendor Role
        |--------------------------------------------------------------------------
        */

        $role = strtolower(
            str_replace(
                [' ', '-'],
                '_',
                trim($user->role->role_name ?? '')
            )
        );

        if ($role !== 'vendor') {

            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('vendor.login')
                ->withErrors([
                    'email' => 'This account does not have vendor access.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Find Vendor Profile
        |--------------------------------------------------------------------------
        */

        $vendor = Vendor::where(
            'user_id',
            $user->id
        )->first();

        /*
        |--------------------------------------------------------------------------
        | Vendor Profile Not Found
        |--------------------------------------------------------------------------
        */

        if (!$vendor) {

            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('vendor.login')
                ->withErrors([
                    'email' => 'Vendor profile not found.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Vendor Approval Status
        |--------------------------------------------------------------------------
        |
        | approved = Vendor can access dashboard
        | pending  = Vendor cannot access
        | rejected = Vendor cannot access
        |
        |--------------------------------------------------------------------------
        */

        if ($vendor->status !== 'approved') {

            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $message = match ($vendor->status) {

                'rejected' =>
                    'Your vendor account has been rejected. Please contact the administrator.',

                'pending' =>
                    'Your vendor account is pending approval. Please wait for administrator approval.',

                default =>
                    'Your vendor account is not approved yet. Please contact the administrator.',
            };

            return redirect()
                ->route('vendor.login')
                ->withErrors([
                    'email' => $message,
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | User Account Status
        |--------------------------------------------------------------------------
        |
        | 1 = Active
        | 0 = Inactive
        |
        |--------------------------------------------------------------------------
        */

        if ((int) $user->status !== 1) {

            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('vendor.login')
                ->withErrors([
                    'email' => 'Your vendor account is currently inactive.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Everything OK
        |--------------------------------------------------------------------------
        */

        return $next($request);
    }
}