<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;
class AuthController extends Controller
{

public function register(Request $request)
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'unique:users,email'],
        'phone' => ['nullable', 'string', 'max:20'],
        'password' => ['required', 'confirmed', Password::min(6)],
    ]);

$username = Str::slug($request->name) . rand(1000, 9999);

$originalUsername = $username;
$count = 1;

while (User::where('username', $username)->exists()) {
    $username = $originalUsername . $count;
    $count++;
}
    $user = User::create([
        'role_id' => 4,
        'name' => $request->name,
        'username' => $username,
        'email' => $request->email,
        'phone' => $request->phone,
        'password' => Hash::make($request->password),
        'status' => 1,
        'slug' => 'user_' . uniqid(),
    ]);

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'success' => true,
        'message' => 'Registration successful.',
        'token' => $token,
        'user' => $user,
    ], 201);
}

public function login(Request $request)
{
    $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (!Auth::attempt([
        'email' => $request->email,
        'password' => $request->password,
    ])) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid email or password.',
        ], 401);
    }

    $user = Auth::user()->load('role');

    // Inactive user check
    if ($user->status != 1) {
        return response()->json([
            'success' => false,
            'message' => 'Your account has been deactivated.',
        ], 403);
    }

    // Remove old tokens (optional but recommended)
    $user->tokens()->delete();

    $token = $user->createToken('auth_token')->plainTextToken;

return response()->json([
    'success' => true,
    'message' => 'Login successful.',
    'token' => $token,
    'user' => $user,
]);
}

public function logout(Request $request)
{
    $request->user()->currentAccessToken()->delete();

    return response()->json([
        'success' => true,
        'message' => 'Logged out successfully.',
    ]);
}

public function me(Request $request)
{
    return response()->json([
        'success' => true,
        'user' => $request->user()->load('role'),
    ]);
}
}
