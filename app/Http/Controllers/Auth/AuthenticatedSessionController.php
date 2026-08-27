<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Vendor;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display login page
     */
    public function create(): View
    {
        return view('auth.login');
    }

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
    )->lower()->replace([' ', '-'], '_')->toString();

    // Only admin roles can login here
    if (!in_array($role, ['super_admin', 'admin', 'manager'])) {

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        throw ValidationException::withMessages([
            'email' => 'This account does not have admin access.',
        ]);
    }

    $request->session()->regenerate();

    return redirect()->route('admin.dashboard');
}

    /**
     * Handle login request
     */
public function store(LoginRequest $request): RedirectResponse
{
    $request->authenticate();

    $request->session()->regenerate();

    $user = Auth::user();

    $role = strtolower(
        str_replace(
            [' ', '-'],
            '_',
            optional($user->role)->role_name ?? 'user'
        )
    );

    if (in_array($role, ['super_admin', 'admin', 'manager'])) {
        return redirect()->route('admin.dashboard');
    }

    if ($role === 'vendor') {
        Vendor::firstOrCreate(
            ['user_id' => $user->id],
            [
                'business_name' => $user->name,
                'status' => 1,
                'commission_rate' => 10,
            ]
        );

        return redirect()->route('vendor.dashboard');
    }

    return redirect()->route('user.dashboard');
}

    /**
     * Logout user
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}