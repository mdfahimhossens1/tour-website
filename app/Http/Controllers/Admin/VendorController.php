<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\User;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index()
    {
        $vendors = Vendor::with('user')->latest()->paginate(20);
        return view('admin.vendors.index', compact('vendors'));
    }

public function approve($id)
{
    $vendor = Vendor::with('user')->findOrFail($id);

    $vendor->update([
        'status' => 'approved'
    ]);

    $vendorRole = \App\Models\Role::where(
        'role_name',
        'Vendor'
    )->first();

    if ($vendorRole) {

        $vendor->user->update([
            'role_id' => $vendorRole->id
        ]);

    }

    return back()->with(
        'success',
        'Vendor Approved Successfully'
    );
}

    public function destroy($id)
    {
        Vendor::findOrFail($id)->delete();
        return back()->with('success','Deleted');
    }
}
