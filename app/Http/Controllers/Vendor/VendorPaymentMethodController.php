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
        )
            ->latest()
            ->get();

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

            /*
            |--------------------------------------------------------------------------
            | Basic Information
            |--------------------------------------------------------------------------
            */

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'type' => [
                'required',
                'in:bkash,nagad,stripe,paypal,bank,manual',
            ],

            /*
            |--------------------------------------------------------------------------
            | Service Type
            |--------------------------------------------------------------------------
            |
            | all       = Resort + Transport + other supported services
            | resort    = Only Resort bookings
            | transport = Only Transport bookings
            |
            */

            'service_type' => [
                'required',
                'in:all,resort,transport',
            ],

            /*
            |--------------------------------------------------------------------------
            | Account Information
            |--------------------------------------------------------------------------
            */

            'account_number' => [
                'nullable',
                'string',
                'max:255',
            ],

            /*
            |--------------------------------------------------------------------------
            | API Credentials
            |--------------------------------------------------------------------------
            */

            'api_key' => [
                'nullable',
                'string',
                'max:255',
            ],

            'secret_key' => [
                'nullable',
                'string',
                'max:255',
            ],

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            'status' => [
                'required',
                'boolean',
            ],

            /*
            |--------------------------------------------------------------------------
            | Description
            |--------------------------------------------------------------------------
            */

            'description' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Get Vendor
        |--------------------------------------------------------------------------
        */

        $vendor = $this->getVendor();


        /*
        |--------------------------------------------------------------------------
        | Attach Vendor ID
        |--------------------------------------------------------------------------
        */

        $validated['vendor_id'] = $vendor->id;


        /*
        |--------------------------------------------------------------------------
        | Create Payment Method
        |--------------------------------------------------------------------------
        */

        VendorPaymentMethod::create($validated);


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('vendor.payment-methods.index')
            ->with(
                'success',
                'Payment method added successfully.'
            );
    }


    /**
     * Update Payment Method
     */
    public function update(
        Request $request,
        $id
    ) {
        /*
        |--------------------------------------------------------------------------
        | Get Vendor
        |--------------------------------------------------------------------------
        */

        $vendor = $this->getVendor();


        /*
        |--------------------------------------------------------------------------
        | Get Payment Method
        |--------------------------------------------------------------------------
        |
        | Vendor can only update their own payment methods.
        |
        */

        $method = VendorPaymentMethod::where(
            'vendor_id',
            $vendor->id
        )->findOrFail($id);


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

            'type' => [
                'required',
                'in:bkash,nagad,stripe,paypal,bank,manual',
            ],

            'service_type' => [
                'required',
                'in:all,resort,transport',
            ],

            'account_number' => [
                'nullable',
                'string',
                'max:255',
            ],

            'api_key' => [
                'nullable',
                'string',
                'max:255',
            ],

            'secret_key' => [
                'nullable',
                'string',
                'max:255',
            ],

            'status' => [
                'required',
                'boolean',
            ],

            'description' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $method->update($validated);


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

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
        /*
        |--------------------------------------------------------------------------
        | Get Vendor
        |--------------------------------------------------------------------------
        */

        $vendor = $this->getVendor();


        /*
        |--------------------------------------------------------------------------
        | Get Payment Method
        |--------------------------------------------------------------------------
        |
        | Make sure vendor can only deactivate
        | their own payment method.
        |
        */

        $method = VendorPaymentMethod::where(
            'vendor_id',
            $vendor->id
        )->findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | Deactivate
        |--------------------------------------------------------------------------
        |
        | Do not delete payment methods because
        | previous payment records may depend on them.
        |
        */

        $method->update([
            'status' => false,
        ]);


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return back()->with(
            'success',
            'Payment method deactivated successfully.'
        );
    }


    /**
     * Get Currently Logged-in Vendor
     */
    private function getVendor(): Vendor
    {
        /*
        |--------------------------------------------------------------------------
        | Vendor Authentication
        |--------------------------------------------------------------------------
        |
        | Current project structure:
        |
        | users
        |    ↓
        | vendors
        |    ↓
        | user_id
        |
        */

        return Vendor::where(
            'user_id',
            auth()->id()
        )->firstOrFail();
    }
}