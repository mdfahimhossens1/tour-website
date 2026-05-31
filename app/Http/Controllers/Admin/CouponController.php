<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;

class CouponController extends Controller
{
    // ALL
    public function index()
    {
        $coupons = Coupon::latest()->get();
        return view('admin.coupons.index', compact('coupons'));
    }

    // CREATE
    public function create()
    {
        return view('admin.coupons.create');
    }

    // STORE
    public function store(Request $request)
    {
        $request->validate([
            'code'  => 'required|unique:coupons,code',
            'type'  => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
        ]);

        Coupon::create($request->all());

        return redirect()
            ->route('admin.coupons.index')
            ->with('success', 'Coupon created successfully');
    }

    public function edit($slug)
    {
        $coupon = Coupon::where('slug', $slug)->firstOrFail();
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, $slug)
    {
        $coupon = Coupon::where('slug', $slug)->firstOrFail();

        $request->validate([
            'code'  => 'required|unique:coupons,code,' . $coupon->id,
            'type'  => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
        ]);

        $coupon->update($request->all());

        return redirect()
            ->route('admin.coupons.index')
            ->with('success', 'Coupon updated successfully');
    }

    public function destroy($id)
    {
        Coupon::findOrFail($id)->delete();

        return back()->with('success', 'Coupon deleted successfully');
    }
}