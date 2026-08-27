<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Normalize role name for consistent comparison.
     */
    private function normalize(?string $role): string
    {
        return str($role ?? 'user')
            ->lower()
            ->replace([' ', '-'], '_')
            ->toString();
    }

    /**
     * Handle role-based authorization.
     */
    public function handle(
        Request $request,
        Closure $next,
        ...$roles
    ): Response {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        $userRole = $this->normalize(
            optional($user->role)->role_name ?? 'user'
        );

        $allowedRoles = array_map(
            fn ($role) => $this->normalize($role),
            $roles
        );

        if (!in_array($userRole, $allowedRoles, true)) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}