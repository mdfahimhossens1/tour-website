<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    private function myRole(): string
    {
        return strtolower(optional(auth()->user()->role)->role_name ?? 'user');
    }

    private function abortIfNotStaff(): void
    {
        if (!in_array($this->myRole(), ['super admin','admin','manager'])) {
            abort(403, 'Unauthorized');
        }
    }

    public function index()
    {
        $this->abortIfNotStaff();

        $coupons = Coupon::orderByDesc('id')->paginate(20);
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        $this->abortIfNotStaff();
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        $this->abortIfNotStaff();

        $request->validate([
            'code'         => 'required|string|max:50|unique:coupons,code',
            'type'         => 'required|in:percent,fixed',
            'value'        => 'required|numeric|min:0',
            'min_order'    => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit'  => 'nullable|integer|min:1',
            'starts_at'    => 'nullable|date',
            'ends_at'      => 'nullable|date|after_or_equal:starts_at',
            'is_active'    => 'required|in:0,1',
        ]);

        // Normalize CODE uppercase
        $code = strtoupper(trim($request->code));

        // percent validation
        if ($request->type === 'percent' && (float)$request->value > 100) {
            return back()->with('error', 'Percent coupon value cannot exceed 100.')->withInput();
        }

        Coupon::create([
            'code'         => $code,
            'type'         => $request->type,
            'value'        => (float)$request->value,
            'min_order'    => $request->min_order !== null ? (float)$request->min_order : null,
            'max_discount' => $request->max_discount !== null ? (float)$request->max_discount : null,
            'usage_limit'  => $request->usage_limit !== null ? (int)$request->usage_limit : null,
            'used_count'   => 0,
            'starts_at'    => $request->starts_at ?: null,
            'ends_at'      => $request->ends_at ?: null,
            'is_active'    => (int)$request->is_active,
        ]);

        return redirect()->route('dashboard.coupons.index')->with('success', 'Coupon created successfully.');
    }

    public function edit($id)
    {
        $this->abortIfNotStaff();

        $coupon = Coupon::findOrFail($id);
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, $id)
    {
        $this->abortIfNotStaff();

        $coupon = Coupon::findOrFail($id);

        $request->validate([
            'code'         => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'type'         => 'required|in:percent,fixed',
            'value'        => 'required|numeric|min:0',
            'min_order'    => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit'  => 'nullable|integer|min:1',
            'starts_at'    => 'nullable|date',
            'ends_at'      => 'nullable|date|after_or_equal:starts_at',
            'is_active'    => 'required|in:0,1',
        ]);

        $code = strtoupper(trim($request->code));

        if ($request->type === 'percent' && (float)$request->value > 100) {
            return back()->with('error', 'Percent coupon value cannot exceed 100.')->withInput();
        }

        $coupon->code         = $code;
        $coupon->type         = $request->type;
        $coupon->value        = (float)$request->value;
        $coupon->min_order    = $request->min_order !== null ? (float)$request->min_order : null;
        $coupon->max_discount = $request->max_discount !== null ? (float)$request->max_discount : null;
        $coupon->usage_limit  = $request->usage_limit !== null ? (int)$request->usage_limit : null;
        $coupon->starts_at    = $request->starts_at ?: null;
        $coupon->ends_at      = $request->ends_at ?: null;
        $coupon->is_active    = (int)$request->is_active;
        $coupon->save();

        return redirect()->route('dashboard.coupons.index')->with('success', 'Coupon updated successfully.');
    }

    public function toggle($id)
    {
        $this->abortIfNotStaff();

        $coupon = Coupon::findOrFail($id);
        $coupon->is_active = $coupon->is_active ? 0 : 1;
        $coupon->save();

        return back()->with('success', 'Coupon status updated.');
    }

    public function destroy($id)
    {
        $this->abortIfNotStaff();

        // optional: manager cannot delete
        if ($this->myRole() === 'manager') {
            return back()->with('error', 'Manager cannot delete coupons.');
        }

        Coupon::findOrFail($id)->delete();
        return redirect()->route('dashboard.coupons.index')->with('success', 'Coupon deleted successfully.');
    }
}
