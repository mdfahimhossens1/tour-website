<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function adminProfile(): View
{
    $user = Auth::user();
    return view('admin.profile.profile', compact('user'));
}

public function adminProfileUpdate(Request $request): RedirectResponse
{
    $user = Auth::user();

    $request->validate([
        'name'  => 'required|string|max:255',
        'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    $user->name = $request->name;

    if ($request->hasFile('photo')) {
     if ($user->photo && file_exists(public_path('uploads/users/'.$user->photo))) {
            unlink(public_path('uploads/users/'.$user->photo));
        }

        $file = $request->photo;
        $extension = $file->getClientOriginalExtension();
        $photoName = 'user_'.time().'_'.uniqid(30).'.'.$extension;
        $file->move(public_path('uploads/users'), $photoName);

        $user->photo = $photoName;
    }

    $user->save();

    return back()->with('success', 'Profile updated!');
}

public function adminAccount(): View
{
    return view('admin.profile.manage_account');
}

public function adminPasswordUpdate(Request $request): RedirectResponse
{
    $request->validate([
        'current_password' => 'required',
        'password' => 'required|min:6|confirmed',
    ]);

    $user = Auth::user();

    if (!Hash::check($request->current_password, $user->password)) {
        return back()->with('error', 'Current password is incorrect!');
    }

    $user->password = Hash::make($request->password);
    $user->save();

    return back()->with('success', 'Password updated!');
}
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

}
