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
    $currentRole = strtolower(
        str_replace(
            [' ', '-'],
            '_',
            auth()->user()->role->role_name ?? ''
        )
    );

    if (!in_array($currentRole, [
        'super_admin',
        'admin'
    ])) {
        abort(403);
    }

    $vendor = Vendor::findOrFail($id);

    $vendor->status = 1;
    $vendor->save();

    return back()->with('success', 'Vendor approved successfully');
}

public function destroy($id)
{
    $currentRole = strtolower(
        str_replace(
            [' ', '-'],
            '_',
            auth()->user()->role->role_name ?? ''
        )
    );

    if (!in_array($currentRole, [
        'super_admin',
        'admin'
    ])) {
        abort(403);
    }

    Vendor::findOrFail($id)->delete();

    return back()->with('success', 'Vendor deleted successfully');
}
}
