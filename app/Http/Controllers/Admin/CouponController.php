<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::latest()->get();
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'  => 'required|unique:coupons,code',
            'type'  => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'max_usage' => 'required|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        Coupon::create([
            'code' => strtoupper($request->code),
            'type' => $request->type,
            'value' => $request->value,
            'max_usage' => $request->max_usage,
            'used_count' => 0,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'active',
        ]);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon created successfully');
    }

    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);

        $request->validate([
            'code'  => 'required|unique:coupons,code,' . $coupon->id,
            'type'  => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
        ]);

        $coupon->update([
            'code' => strtoupper($request->code),
            'type' => $request->type,
            'value' => $request->value,
        ]);

        return back()->with('success', 'Coupon updated successfully');
    }

    public function destroy($id)
    {
        Coupon::findOrFail($id)->delete();
        return back()->with('success', 'Coupon deleted successfully');
    }
}
