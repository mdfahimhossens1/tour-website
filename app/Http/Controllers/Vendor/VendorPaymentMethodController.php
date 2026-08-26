<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\VendorPaymentMethod;
use Illuminate\Http\Request;

class VendorPaymentMethodController extends Controller
{
    /**
     * Vendor Payment Methods List
     */
    public function index()
    {
        $vendor = $this->getVendor();

        $methods = VendorPaymentMethod::where(
            'vendor_id',
            $vendor->id
        )->latest()->get();

        return view(
            'vendor.payment-methods.index',
            compact('methods')
        );
    }


    /**
     * Store New Payment Method
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',

            'type' => 'required|in:bkash,nagad,stripe,paypal,bank,manual',

            'account_number' => 'nullable|string|max:255',

            'api_key' => 'nullable|string|max:255',

            'secret_key' => 'nullable|string|max:255',

            'status' => 'required|boolean',

            'description' => 'nullable|string',
        ]);

        $vendor = $this->getVendor();

        $validated['vendor_id'] = $vendor->id;

        VendorPaymentMethod::create($validated);

        return redirect()
            ->route('vendor.payment-methods.index')
            ->with('success', 'Payment method added successfully.');
    }


    /**
     * Update Payment Method
     */
    public function update(Request $request, $id)
    {
        $vendor = $this->getVendor();

        $method = VendorPaymentMethod::where(
            'vendor_id',
            $vendor->id
        )->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',

            'type' => 'required|in:bkash,nagad,stripe,paypal,bank,manual',

            'account_number' => 'nullable|string|max:255',

            'api_key' => 'nullable|string|max:255',

            'secret_key' => 'nullable|string|max:255',

            'status' => 'required|boolean',

            'description' => 'nullable|string',
        ]);

        $method->update($validated);

        return back()->with(
            'success',
            'Payment method updated successfully.'
        );
    }


    /**
     * Deactivate Payment Method
     */
    public function destroy($id)
    {
        $vendor = $this->getVendor();

        $method = VendorPaymentMethod::where(
            'vendor_id',
            $vendor->id
        )->findOrFail($id);

        // Payment history নিরাপদ রাখার জন্য delete না করে inactive করছি
        $method->update([
            'status' => 0,
        ]);

        return back()->with(
            'success',
            'Payment method deactivated successfully.'
        );
    }


    /**
     * Get Currently Logged-in Vendor
     */
    private function getVendor()
    {
        /*
         * তোমার Vendor authentication অনুযায়ী
         * এখানে প্রয়োজন হলে পরিবর্তন করতে হবে।
         */

        return Vendor::where(
            'user_id',
            auth()->id()
        )->firstOrFail();
    }
}