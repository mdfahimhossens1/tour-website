<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class VendorProfileController extends Controller
{
    /**
     * Show vendor profile.
     */
    public function index()
    {
        $vendor = auth()->user()->vendor;

        abort_if(
            !$vendor,
            403,
            'Vendor profile not found.'
        );

        $user = auth()->user();

        return view(
            'vendor.profile.index',
            compact('vendor', 'user')
        );
    }


    /**
     * Show edit profile page.
     */
    public function edit()
    {
        $vendor = auth()->user()->vendor;

        abort_if(
            !$vendor,
            403,
            'Vendor profile not found.'
        );

        $user = auth()->user();

        return view(
            'vendor.profile.edit',
            compact('vendor', 'user')
        );
    }


    /**
     * Update vendor profile.
     */
    public function update(Request $request)
    {
        $vendor = auth()->user()->vendor;

        abort_if(
            !$vendor,
            403,
            'Vendor profile not found.'
        );

        $user = auth()->user();


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($user->id),
            ],

            'business_name' => [
                'required',
                'string',
                'max:255',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:50',
            ],

            'address' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'trade_license' => [
                'nullable',
                'string',
                'max:255',
            ],

            'logo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'website' => [
                'nullable',
                'url',
                'max:255',
            ],

            'bkash' => [
                'nullable',
                'string',
                'max:50',
            ],

            'nagad' => [
                'nullable',
                'string',
                'max:50',
            ],

            'bank_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'bank_account' => [
                'nullable',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
                'max:5000',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Update User Information
        |--------------------------------------------------------------------------
        */

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        $user->save();


        /*
        |--------------------------------------------------------------------------
        | Update Vendor Information
        |--------------------------------------------------------------------------
        */

        $vendor->business_name =
            $validated['business_name'];

        $vendor->phone =
            $validated['phone'] ?? null;

        $vendor->address =
            $validated['address'] ?? null;

        $vendor->trade_license =
            $validated['trade_license'] ?? null;

        $vendor->website =
            $validated['website'] ?? null;

        $vendor->bkash =
            $validated['bkash'] ?? null;

        $vendor->nagad =
            $validated['nagad'] ?? null;

        $vendor->bank_name =
            $validated['bank_name'] ?? null;

        $vendor->bank_account =
            $validated['bank_account'] ?? null;

        $vendor->description =
            $validated['description'] ?? null;


        /*
        |--------------------------------------------------------------------------
        | Logo Upload
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('logo')) {

            if (
                $vendor->logo &&
                Storage::disk('public')->exists(
                    $vendor->logo
                )
            ) {

                Storage::disk('public')->delete(
                    $vendor->logo
                );
            }


            $vendor->logo = $request
                ->file('logo')
                ->store('vendors/logos', 'public');
        }


        $vendor->save();


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('vendor.profile.index')
            ->with(
                'success',
                'Vendor profile updated successfully.'
            );
    }


    /**
     * Update vendor password.
     */
    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $request->validate([

            'current_password' => [
                'required',
                'current_password',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],

        ]);


        $user->password = Hash::make(
            $request->password
        );

        $user->save();


        return back()->with(
            'success',
            'Password changed successfully.'
        );
    }
}