<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class VendorVehicleController extends Controller
{
    /**
     * Vendor Vehicle List
     */
    public function index()
    {
        $vendor = Auth::user()->vendor;

        abort_unless($vendor, 403);

        $vehicles = Vehicle::where('vendor_id', $vendor->id)
            ->latest()
            ->paginate(10);

        return view(
            'vendor.vehicles.index',
            compact('vehicles')
        );
    }


    /**
     * Store Vehicle
     */
    public function store(Request $request)
    {
        $vendor = Auth::user()->vendor;

        abort_unless($vendor, 403);

        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'vehicle_type' => [
                'required',
                'string',
                'max:100',
            ],

            'brand' => [
                'nullable',
                'string',
                'max:100',
            ],

            'model' => [
                'nullable',
                'string',
                'max:100',
            ],

            'registration_number' => [
                'nullable',
                'string',
                'max:100',
                'unique:vehicles,registration_number',
            ],

            'passenger_capacity' => [
                'required',
                'integer',
                'min:1',
            ],

            'division' => [
                'nullable',
                'string',
                'max:100',
            ],

            'district' => [
                'nullable',
                'string',
                'max:100',
            ],

            'area' => [
                'nullable',
                'string',
                'max:100',
            ],

            'address' => [
                'nullable',
                'string',
            ],

            'price_per_day' => [
                'required',
                'numeric',
                'min:0',
            ],

            'price_per_hour' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'featured_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'with_driver' => [
                'nullable',
                'boolean',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Vendor
        |--------------------------------------------------------------------------
        */

        $validated['vendor_id'] = $vendor->id;


        /*
        |--------------------------------------------------------------------------
        | Slug
        |--------------------------------------------------------------------------
        */

        $baseSlug = Str::slug($validated['name']);

        $slug = $baseSlug;

        $counter = 1;

        while (
            Vehicle::where('slug', $slug)->exists()
        ) {
            $slug = $baseSlug . '-' . $counter++;
        }

        $validated['slug'] = $slug;


        /*
        |--------------------------------------------------------------------------
        | Image
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('featured_image')) {

            $validated['featured_image'] =
                $request
                    ->file('featured_image')
                    ->store('vehicles', 'public');
        }


        /*
        |--------------------------------------------------------------------------
        | Default Values
        |--------------------------------------------------------------------------
        */

        $validated['with_driver'] =
            $request->boolean('with_driver');

        $validated['is_featured'] = false;

        $validated['is_verified'] = false;

        /*
        | Vendor vehicle must be approved by admin
        */

        $validated['status'] = 'pending';


        /*
        |--------------------------------------------------------------------------
        | Create Vehicle
        |--------------------------------------------------------------------------
        */

        Vehicle::create($validated);


        return redirect()
            ->route('vendor.vehicles.index')
            ->with(
                'success',
                'Vehicle submitted successfully. It is now waiting for admin approval.'
            );
    }


    /**
     * Update Vehicle
     */
    public function update(
        Request $request,
        Vehicle $vehicle
    ) {
        $vendor = Auth::user()->vendor;

        abort_unless($vendor, 403);

        abort_unless(
            $vehicle->vendor_id === $vendor->id,
            403
        );


        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'vehicle_type' => [
                'required',
                'string',
                'max:100',
            ],

            'brand' => [
                'nullable',
                'string',
                'max:100',
            ],

            'model' => [
                'nullable',
                'string',
                'max:100',
            ],

            'registration_number' => [
                'nullable',
                'string',
                'max:100',
                'unique:vehicles,registration_number,' . $vehicle->id,
            ],

            'passenger_capacity' => [
                'required',
                'integer',
                'min:1',
            ],

            'division' => [
                'nullable',
                'string',
                'max:100',
            ],

            'district' => [
                'nullable',
                'string',
                'max:100',
            ],

            'area' => [
                'nullable',
                'string',
                'max:100',
            ],

            'address' => [
                'nullable',
                'string',
            ],

            'price_per_day' => [
                'required',
                'numeric',
                'min:0',
            ],

            'price_per_hour' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'featured_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'with_driver' => [
                'nullable',
                'boolean',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Slug
        |--------------------------------------------------------------------------
        */

        if ($vehicle->name !== $validated['name']) {

            $baseSlug = Str::slug($validated['name']);

            $slug = $baseSlug;

            $counter = 1;

            while (
                Vehicle::where('slug', $slug)
                    ->where('id', '!=', $vehicle->id)
                    ->exists()
            ) {
                $slug = $baseSlug . '-' . $counter++;
            }

            $validated['slug'] = $slug;
        }


        /*
        |--------------------------------------------------------------------------
        | Image
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('featured_image')) {

            $validated['featured_image'] =
                $request
                    ->file('featured_image')
                    ->store('vehicles', 'public');
        }


        /*
        |--------------------------------------------------------------------------
        | Driver
        |--------------------------------------------------------------------------
        */

        $validated['with_driver'] =
            $request->boolean('with_driver');


        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $vehicle->update($validated);


        return redirect()
            ->route('vendor.vehicles.index')
            ->with(
                'success',
                'Vehicle updated successfully.'
            );
    }


    /**
     * Delete Vehicle
     */
    public function destroy(Vehicle $vehicle)
    {
        $vendor = Auth::user()->vendor;

        abort_unless($vendor, 403);

        abort_unless(
            $vehicle->vendor_id === $vendor->id,
            403
        );


        $vehicle->delete();


        return redirect()
            ->route('vendor.vehicles.index')
            ->with(
                'success',
                'Vehicle deleted successfully.'
            );
    }
}
