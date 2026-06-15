<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
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
     * Handle login request
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        $role = str(
            optional($user->role)->role_name ?? 'user'
        )->lower()->replace([' ', '-'], '_')->toString();

        // =========================
        // ROLE BASED REDIRECT
        // =========================

        if ($role === 'super_admin' || $role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($role === 'manager') {
            return redirect()->route('admin.dashboard');
        }

    if ($role === 'vendor') {

        Vendor::firstOrCreate([
            'user_id' => $user->id
        ], [
            'business_name' => $user->name,
            'status' => 1,
            'commission_rate' => 10,
        ]);

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