<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VehicleController extends Controller
{
    /**
     * Transport Vehicles List
     */
    public function index(Request $request)
    {
        $query = Vehicle::with('vendor');

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('vehicle_type', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    ->orWhere(
                        'registration_number',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhereHas('vendor', function ($vendorQuery) use ($search) {

                        $vendorQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");

                    });

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Vehicle Type Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('vehicle_type')) {

            $query->where(
                'vehicle_type',
                $request->vehicle_type
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $vehicles = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.transport-vehicles.index',
            compact('vehicles')
        );
    }


    /**
     * Update Vehicle
     */
    public function update(Request $request, Vehicle $vehicle)
    {
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
            ],

            'passenger_capacity' => [
                'required',
                'integer',
                'min:1',
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

            'description' => [
                'nullable',
                'string',
            ],

            'featured_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'with_driver' => [
                'nullable',
                'boolean',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Driver Option
        |--------------------------------------------------------------------------
        |
        | Checkbox unchecked হলে request-এ with_driver আসবে না।
        | তাই false সেট করে দিচ্ছি।
        |
        */

        $validated['with_driver'] = $request->boolean('with_driver');

        /*
        |--------------------------------------------------------------------------
        | New Image Upload
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('featured_image')) {

            /*
            | Delete Old Image
            */

            if (
                $vehicle->featured_image &&
                Storage::disk('public')->exists(
                    $vehicle->featured_image
                )
            ) {

                Storage::disk('public')->delete(
                    $vehicle->featured_image
                );
            }

            /*
            | Store New Image
            */

            $validated['featured_image'] = $request
                ->file('featured_image')
                ->store('vehicles', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | Update Vehicle
        |--------------------------------------------------------------------------
        */

        $vehicle->update($validated);

        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.transport-vehicles.index')
            ->with(
                'success',
                'Vehicle updated successfully.'
            );
    }


    /**
     * Approve Vehicle
     */
    public function approve(Vehicle $vehicle)
    {
        $vehicle->update([
            'status' => 'approved',
            'is_verified' => true,
        ]);

        return redirect()
            ->route('admin.transport-vehicles.index')
            ->with(
                'success',
                'Vehicle approved successfully.'
            );
    }


    /**
     * Reject Vehicle
     */
    public function reject(Vehicle $vehicle)
    {
        $vehicle->update([
            'status' => 'rejected',
            'is_verified' => false,
        ]);

        return redirect()
            ->route('admin.transport-vehicles.index')
            ->with(
                'success',
                'Vehicle rejected successfully.'
            );
    }


    /**
     * Activate Vehicle
     */
    public function activate(Vehicle $vehicle)
    {
        $vehicle->update([
            'status' => 'approved',
        ]);

        return redirect()
            ->route('admin.transport-vehicles.index')
            ->with(
                'success',
                'Vehicle activated successfully.'
            );
    }


    /**
     * Deactivate Vehicle
     */
    public function deactivate(Vehicle $vehicle)
    {
        $vehicle->update([
            'status' => 'inactive',
        ]);

        return redirect()
            ->route('admin.transport-vehicles.index')
            ->with(
                'success',
                'Vehicle deactivated successfully.'
            );
    }


    /**
     * Delete Vehicle
     */
    public function destroy(Vehicle $vehicle)
    {
        /*
        |--------------------------------------------------------------------------
        | Delete Featured Image
        |--------------------------------------------------------------------------
        */

        if (
            $vehicle->featured_image &&
            Storage::disk('public')->exists(
                $vehicle->featured_image
            )
        ) {

            Storage::disk('public')->delete(
                $vehicle->featured_image
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Delete Vehicle
        |--------------------------------------------------------------------------
        */

        $vehicle->delete();

        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.transport-vehicles.index')
            ->with(
                'success',
                'Vehicle deleted successfully.'
            );
    }
}

