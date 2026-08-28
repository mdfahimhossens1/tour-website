<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\TrackVisitorSession;
use App\Http\Middleware\ApiKeyMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware): void {

        /*
        |--------------------------------------------------------------------------
        | Sanctum Stateful API
        |--------------------------------------------------------------------------
        */

        // $middleware->statefulApi();


        /*
        |--------------------------------------------------------------------------
        | Custom Middleware Aliases
        |--------------------------------------------------------------------------
        */

        $middleware->alias([
            'role'   => RoleMiddleware::class,
            'apikey' => ApiKeyMiddleware::class,
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
        |
        | We do not use a general /login route.
        |
        | Admin users are redirected to:
        |     /admin/login
        |
        | Vendors are redirected to:
        |     /vendor/login
        |
        | Other unauthenticated requests go to:
        |     /
        |
        */

        $middleware->redirectGuestsTo(function ($request) {

            /*
            |--------------------------------------------------------------------------
            | Admin Area
            |--------------------------------------------------------------------------
            */

            if ($request->is('admin/*')) {
                return route('admin.login');
            }


            /*
            |--------------------------------------------------------------------------
            | Vendor Area
            |--------------------------------------------------------------------------
            */

            if ($request->is('vendor/*')) {
                return route('vendor.login');
            }


            /*
            |--------------------------------------------------------------------------
            | Public Website
            |--------------------------------------------------------------------------
            */

            return url('/');
        });
    })

    ->withExceptions(function (Exceptions $exceptions): void {

        //
    })

    ->create();
