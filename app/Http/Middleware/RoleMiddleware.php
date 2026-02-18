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
        $role = strtolower(trim($role ?? 'user'));
        // "Super Admin" => "super_admin"
        $role = str_replace([' ', '-'], '_', $role);
        return $role;
    }

    public function handle(Request $request, Closure $next, ...$roles)
    {
        
        $user = Auth::user();
        if (!$user) abort(403);

        $dbRole = optional($user->role)->role_name ?? 'User';
        $roleName = $this->normalize($dbRole);

        // hierarchy
        $levels = [
            'user' => 1,
            'manager' => 2,
            'admin' => 3,
            'super_admin' => 4,
        ];

        $userLevel = $levels[$roleName] ?? 0;

        foreach ($roles as $r) {
            $need = $levels[$this->normalize($r)] ?? 0;
            if ($userLevel >= $need) {
                return $next($request);
            }
        }

        abort(403, 'You are not authorized to access this page.');
    }
}
