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

        $dbRole = optional($user->role)->role_name ?? 'User';

        $roleName = $this->normalize($dbRole);

        $levels = [
            'user' => 1,
            'manager' => 2,
            'admin' => 3,
            'super_admin' => 4,
        ];

        $userLevel = $levels[$roleName] ?? 0;

        foreach ($roles as $role) {

            $requiredLevel = $levels[$this->normalize($role)] ?? 0;

            if ($userLevel >= $requiredLevel) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized access.');
    }
}