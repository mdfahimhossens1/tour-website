<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Auto-generate username from first name
        $firstName = strtolower(explode(' ', trim($request->name))[0]);
        $username = $firstName . '_' . rand(10, 99);

        // Ensure username is unique
        while (User::where('username', $username)->exists()) {
            $username = $firstName . '_' . rand(10, 99);
        }

        $slug = 'user_' . uniqid('', true);
        
        $userRoleId = Role::whereRaw('LOWER(role_name) = ?', ['user'])->value('id');

        $user = User::create([
            'role_id'  => $userRoleId,
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password), // (তোমার cast hashed থাকলে চাইলে এটাও বাদ দিতে পারো)
            'username' => $username,
            'slug'     => $slug,
        ]);

        event(new Registered($user));

        Auth::login($user);

        // ✅ Frontend user redirect (admin dashboard নয়)
        return redirect('/');
    }
}
