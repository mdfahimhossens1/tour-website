<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\TrackVisitorSession;
use App\Http\Middleware\ApiKeyMiddleware;
use App\Http\Middleware\CheckVendorStatus;
use App\Http\Middleware\RedirectAuthenticated;
use App\Http\Middleware\NoCache;

return Application::configure(
    basePath: dirname(__DIR__)
)

    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware): void {

        /*
        |--------------------------------------------------------------------------
        | Middleware Aliases
        |--------------------------------------------------------------------------
        */

        $middleware->alias([

            'role' => RoleMiddleware::class,

            'apikey' => ApiKeyMiddleware::class,

            'vendor.status' => CheckVendorStatus::class,

            'auth.redirect' => RedirectAuthenticated::class,

            'nocache' => NoCache::class,

        ]);


        /*
        |--------------------------------------------------------------------------
        | Web Middleware
        |--------------------------------------------------------------------------
        */

        $middleware->appendToGroup(
            'web',
            TrackVisitorSession::class
        );


        /*
        |--------------------------------------------------------------------------
        | Guest Redirect
        |--------------------------------------------------------------------------
        */

        $middleware->redirectGuestsTo(function ($request) {

            /*
            |--------------------------------------------------------------------------
            | Admin
            |--------------------------------------------------------------------------
            */

            if ($request->is('admin/*')) {
                return route('admin.login');
            }


            /*
            |--------------------------------------------------------------------------
            | Vendor
            |--------------------------------------------------------------------------
            */

            if ($request->is('vendor/*')) {
                return route('vendor.login');
            }


            /*
            |--------------------------------------------------------------------------
            | User
            |--------------------------------------------------------------------------
            */

            if ($request->is('user/*')) {
                return route('login');
            }


            /*
            |--------------------------------------------------------------------------
            | Public
            |--------------------------------------------------------------------------
            */

            return route('home');
        });


        /*
        |--------------------------------------------------------------------------
        | Authenticated User Redirect
        |--------------------------------------------------------------------------
        |
        | Prevent authenticated users from visiting login pages.
        |
        */

        $middleware->redirectUsersTo(function ($request) {

            if (!auth()->check()) {
                return route('home');
            }

            $user = auth()->user();

            $role = strtolower(
                str_replace(
                    [' ', '-'],
                    '_',
                    optional($user->role)->role_name ?? 'user'
                )
            );

            return match ($role) {

                'super_admin',
                'admin',
                'manager'
                    => route('admin.dashboard'),

                'vendor'
                    => route('vendor.dashboard'),

                'user'
                    => route('user.dashboard'),

                default
                    => route('home'),
            };
        });
    })

    ->withExceptions(function (Exceptions $exceptions): void {

        //
    })

    ->create();