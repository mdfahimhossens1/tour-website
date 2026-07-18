<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Current User Profile
     */
    public function index(Request $request)
    {
        $user = $request->user();

        return response()->json([

            'id' => $user->id,

            'name' => $user->name,

            'email' => $user->email,

            'phone' => $user->phone,

            'username' => $user->username,

            'photo' => $user->photo
                ? asset('storage/' . $user->photo)
                : null,

            'role' => optional($user->role)->name,

        ]);
    }

    /**
     * Update Profile
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([

            'name' => 'required|string|max:255',

            'phone' => 'nullable|string|max:20',

            'username' => 'nullable|string|max:50|unique:users,username,' . $user->id,

            'photo' => 'nullable|image|max:2048',

        ]);

        if ($request->hasFile('photo')) {

            if (
                $user->photo &&
                Storage::disk('public')->exists($user->photo)
            ) {
                Storage::disk('public')->delete($user->photo);
            }

            $validated['photo'] = $request
                ->file('photo')
                ->store('profile', 'public');
        }

        $user->update($validated);

        return response()->json([

            'message' => 'Profile updated successfully.',

            'user' => [

                'id' => $user->id,

                'name' => $user->name,

                'email' => $user->email,

                'phone' => $user->phone,

                'username' => $user->username,

                'photo' => $user->photo
                    ? asset('storage/' . $user->photo)
                    : null,

                'role' => optional($user->role)->name,

            ]

        ]);
    }
}