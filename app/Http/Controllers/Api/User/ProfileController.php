<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Current User Profile
     */
    public function index(Request $request)
    {
        $user = $request->user()->load('role');

        return response()->json([
            'success' => true,

            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'username' => $user->username,

                'photo' => $user->photo
                    ? asset('storage/' . $user->photo)
                    : null,

                'role' => $user->role?->name,
            ],
        ]);
    }

    /**
     * Update Profile
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email,' . $user->id,
            ],

            'phone' => ['nullable', 'string', 'max:20'],

            'username' => [
                'nullable',
                'string',
                'max:50',
                'unique:users,username,' . $user->id,
            ],

            'photo' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png,webp',
                'max:2048'
            ],

            'current_password' => ['nullable', 'string'],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed'
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Password Change
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['password'])) {

            if (empty($validated['current_password'])) {

                return response()->json([
                    'success' => false,
                    'message' => 'Current password is required to change your password.',
                ], 422);

            }

            if (!Hash::check(
                $validated['current_password'],
                $user->password
            )) {

                return response()->json([
                    'success' => false,
                    'message' => 'Current password is incorrect.',
                ], 422);

            }

            $validated['password'] = Hash::make(
                $validated['password']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Photo Upload
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('photo')) {

            $photo = $request->file('photo');

            if ($photo && $photo->isValid()) {

                // পুরোনো ছবি delete
                if (
                    $user->photo &&
                    Storage::disk('public')->exists($user->photo)
                ) {

                    Storage::disk('public')->delete(
                        $user->photo
                    );

                }

                // নতুন ছবি upload
                $validated['photo'] = $photo->store(
                    'profile',
                    'public'
                );
            }
        }

        unset(
            $validated['current_password'],
            $validated['password_confirmation']
        );

        $user->update($validated);

        // নতুন করে role load করার জন্য
        $user->load('role');

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',

            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'username' => $user->username,

                'photo' => $user->photo
                    ? asset('uploads/' . $user->photo)
                    : null,

                'role' => $user->role?->name,
            ],
        ]);
    }
}