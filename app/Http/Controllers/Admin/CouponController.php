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
            'code' => 'required|unique:coupons',
            'type' => 'required',
            'value' => 'required|numeric',
        ]);

        Coupon::create($request->all());

        return redirect()
            ->route('admin.coupons.index')
            ->with('success', 'Coupon created successfully');
    }

    // EDIT
    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('admin.coupons.edit', compact('coupon'));
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);

        $coupon->update($request->all());

        return redirect()
            ->route('admin.coupons.index')
            ->with('success', 'Coupon updated successfully');
    }

    // DELETE
    public function destroy($id)
    {
        Coupon::findOrFail($id)->delete();

        return back()->with('success', 'Coupon deleted');
    }
}