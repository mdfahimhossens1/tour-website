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
        $vendor = Vendor::findOrFail($id);
        $vendor->status = 'approved';
        $vendor->save();

        return back()->with('success','Vendor Approved');
    }

    public function destroy($id)
    {
        Vendor::findOrFail($id)->delete();
        return back()->with('success','Deleted');
    }
}
