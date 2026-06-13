<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    private function normalize(?string $role): string
    {
        return str($role ?? 'user')
            ->lower()
            ->replace([' ', '-'], '_')
            ->toString();
    }

   public function handle(Request $request, Closure $next, ...$roles): Response
{
    if (!Auth::check()) {
        return redirect('/login');
    }

    $user = Auth::user();

    $roleName = $this->normalize(optional($user->role)->role_name ?? 'user');

    $levels = [
        'user' => 1,
        'vendor' => 2,
        'manager' => 3,
        'admin' => 4,
        'super_admin' => 5,
    ];

    $userLevel = $levels[$roleName] ?? 0;

    foreach ($roles as $role) {

        if ($userLevel >= ($levels[$this->normalize($role)] ?? 0)) {
            return $next($request);
        }
    }

    abort(403, 'Unauthorized access.');
}
}