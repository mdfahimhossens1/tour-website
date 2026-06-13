<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorProfileController extends Controller
{
    public function index()
    {
        $vendor = auth()->user()->vendor;

        return view(
            'vendor.profile.index',
            compact('vendor')
        );
    }

    public function update(Request $request)
    {
        $vendor = auth()->user()->vendor;

        $request->validate([
            'business_name' => 'required|max:255',
            'phone' => 'nullable|max:50',
            'address' => 'nullable|max:500',
        ]);

        $vendor->update([
            'business_name' => $request->business_name,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return back()->with(
            'success',
            'Profile updated successfully'
        );
    }
}