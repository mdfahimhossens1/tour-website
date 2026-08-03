<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Facility;
use App\Models\Resort;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VendorResortController extends Controller
{
    /**
     * Display vendor's resorts.
     */
    public function index()
    {
        $vendor = Auth::user()->vendor;

        abort_if(
            !$vendor,
            403,
            'Vendor profile not found.'
        );

        $resorts = Resort::where('vendor_id', $vendor->id)
            ->with('destination')
            ->withCount('facilities')
            ->latest()
            ->paginate(10);

        return view(
            'vendor.resorts.index',
            compact('resorts')
        );
    }


    /**
     * Show create resort form.
     */
    public function create()
    {
        $vendor = Auth::user()->vendor;

        abort_if(
            !$vendor,
            403,
            'Vendor profile not found.'
        );

        $destinations = Destination::orderBy('name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Resort Facilities
        |--------------------------------------------------------------------------
        */

        $facilities = Facility::where('type', 'resort')
            ->where('status', true)
            ->orderBy('name')
            ->get();

        return view(
            'vendor.resorts.create',
            compact(
                'destinations',
                'facilities'
            )
        );
    }


    /**
     * Store new resort.
     */
    public function store(Request $request)
    {
        $vendor = Auth::user()->vendor;

        abort_if(
            !$vendor,
            403,
            'Vendor profile not found.'
        );

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'destination_id' => [
                'nullable',
                'exists:destinations,id',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'slug' => [
                'nullable',
                'string',
                'max:255',
            ],

            'short_description' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'division' => [
                'nullable',
                'string',
                'max:255',
            ],

            'district' => [
                'nullable',
                'string',
                'max:255',
            ],

            'area' => [
                'nullable',
                'string',
                'max:255',
            ],

            'address' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'google_map' => [
                'nullable',
                'string',
            ],

            'latitude' => [
                'nullable',
                'numeric',
            ],

            'longitude' => [
                'nullable',
                'numeric',
            ],

            'featured_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'cover_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
            ],

            'check_in' => [
                'nullable',
                'date_format:H:i',
            ],

            'check_out' => [
                'nullable',
                'date_format:H:i',
            ],

            'rating' => [
                'nullable',
                'numeric',
                'min:0',
                'max:5',
            ],

            'is_featured' => [
                'nullable',
                'boolean',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],

            'meta_title' => [
                'nullable',
                'string',
                'max:255',
            ],

            'meta_description' => [
                'nullable',
                'string',
                'max:1000',
            ],

            /*
            |--------------------------------------------------------------------------
            | Facilities
            |--------------------------------------------------------------------------
            */

            'facilities' => [
                'nullable',
                'array',
            ],

            'facilities.*' => [
                'integer',
                'exists:facilities,id',
            ],

            /*
            |--------------------------------------------------------------------------
            | Verified
            |--------------------------------------------------------------------------
            |
            | IMPORTANT:
            | এই field database-এ থাকলে তবেই রাখবে।
            |
            */

            'is_verified' => [
                'nullable',
                'boolean',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Vendor ID
        |--------------------------------------------------------------------------
        */

        $validated['vendor_id'] = $vendor->id;


        /*
        |--------------------------------------------------------------------------
        | Generate Slug
        |--------------------------------------------------------------------------
        */

        if (empty($validated['slug'])) {

            $validated['slug'] = Str::slug(
                $validated['name']
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Unique Slug
        |--------------------------------------------------------------------------
        */

        $validated['slug'] = $this->generateUniqueSlug(
            $validated['slug']
        );


        /*
        |--------------------------------------------------------------------------
        | Checkbox Values
        |--------------------------------------------------------------------------
        */

        $validated['is_featured'] =
            $request->boolean('is_featured');

        $validated['status'] =
            $request->boolean('status', true);

        /*
        |--------------------------------------------------------------------------
        | Is Verified
        |--------------------------------------------------------------------------
        */

        $validated['is_verified'] =
            $request->boolean('is_verified');


        /*
        |--------------------------------------------------------------------------
        | Remove Facilities Before Resort::create()
        |--------------------------------------------------------------------------
        |
        | facilities[] database column নয়।
        |
        */

        $facilityIds = $validated['facilities'] ?? [];

        unset($validated['facilities']);


        /*
        |--------------------------------------------------------------------------
        | Featured Image
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('featured_image')) {

            $validated['featured_image'] = $request
                ->file('featured_image')
                ->store(
                    'resorts',
                    'public'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Cover Image
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('cover_image')) {

            $validated['cover_image'] = $request
                ->file('cover_image')
                ->store(
                    'resorts/covers',
                    'public'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Create Resort + Attach Facilities
        |--------------------------------------------------------------------------
        */

DB::transaction(function () use (
    $validated,
    $facilityIds,
    $vendor
)  {

            $resort = Resort::create(
                $validated
            );


            /*
            |--------------------------------------------------------------------------
            | Attach Resort Facilities
            |--------------------------------------------------------------------------
            */

            if (!empty($facilityIds)) {

$validFacilityIds = Facility::where(
        'vendor_id',
        $vendor->id
    )
    ->where('type', 'resort')
    ->where('status', true)
    ->whereIn('id', $facilityIds)
    ->pluck('id')
    ->toArray();

$resort->facilities()->sync($validFacilityIds);
            }
        });


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('vendor.resorts.index')
            ->with(
                'success',
                'Resort created successfully.'
            );
    }


    /**
     * Show edit resort form.
     */
    public function edit($slug)
    {
        $vendor = Auth::user()->vendor;

        abort_if(
            !$vendor,
            403,
            'Vendor profile not found.'
        );


        /*
        |--------------------------------------------------------------------------
        | Vendor's Own Resort
        |--------------------------------------------------------------------------
        */

        $resort = Resort::where(
                'vendor_id',
                $vendor->id
            )
            ->where(
                'slug',
                $slug
            )
            ->with('facilities')
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Destinations
        |--------------------------------------------------------------------------
        */

        $destinations = Destination::orderBy('name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Resort Facilities
        |--------------------------------------------------------------------------
        */

        $facilities = Facility::where('type', 'resort')
            ->where('status', true)
            ->orderBy('name')
            ->get();


        return view(
            'vendor.resorts.edit',
            compact(
                'resort',
                'destinations',
                'facilities'
            )
        );
    }


    /**
     * Update resort.
     */
    public function update(
        Request $request,
        $slug
    ) {

        $vendor = Auth::user()->vendor;

        abort_if(
            !$vendor,
            403,
            'Vendor profile not found.'
        );


        /*
        |--------------------------------------------------------------------------
        | Find Vendor's Own Resort
        |--------------------------------------------------------------------------
        */

        $resort = Resort::where(
                'vendor_id',
                $vendor->id
            )
            ->where(
                'slug',
                $slug
            )
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'destination_id' => [
                'nullable',
                'exists:destinations,id',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'slug' => [
                'nullable',
                'string',
                'max:255',
            ],

            'short_description' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'division' => [
                'nullable',
                'string',
                'max:255',
            ],

            'district' => [
                'nullable',
                'string',
                'max:255',
            ],

            'area' => [
                'nullable',
                'string',
                'max:255',
            ],

            'address' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'google_map' => [
                'nullable',
                'string',
            ],

            'latitude' => [
                'nullable',
                'numeric',
            ],

            'longitude' => [
                'nullable',
                'numeric',
            ],

            'featured_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'cover_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
            ],

            'check_in' => [
                'nullable',
                'date_format:H:i',
            ],

            'check_out' => [
                'nullable',
                'date_format:H:i',
            ],

            'rating' => [
                'nullable',
                'numeric',
                'min:0',
                'max:5',
            ],

            'is_featured' => [
                'nullable',
                'boolean',
            ],

            'status' => [
                'nullable',
                'boolean',
            ],

            'meta_title' => [
                'nullable',
                'string',
                'max:255',
            ],

            'meta_description' => [
                'nullable',
                'string',
                'max:1000',
            ],

            /*
            |--------------------------------------------------------------------------
            | Facilities
            |--------------------------------------------------------------------------
            */

            'facilities' => [
                'nullable',
                'array',
            ],

            'facilities.*' => [
                'integer',
                'exists:facilities,id',
            ],

            /*
            |--------------------------------------------------------------------------
            | Verified
            |--------------------------------------------------------------------------
            */

            'is_verified' => [
                'nullable',
                'boolean',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Generate Slug
        |--------------------------------------------------------------------------
        */

        if (empty($validated['slug'])) {

            $validated['slug'] = Str::slug(
                $validated['name']
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Unique Slug
        |--------------------------------------------------------------------------
        */

        $validated['slug'] = $this->generateUniqueSlug(
            $validated['slug'],
            $resort->id
        );


        /*
        |--------------------------------------------------------------------------
        | Checkbox Values
        |--------------------------------------------------------------------------
        */

        $validated['is_featured'] =
            $request->boolean('is_featured');

        $validated['status'] =
            $request->boolean('status');

        $validated['is_verified'] =
            $request->boolean('is_verified');


        /*
        |--------------------------------------------------------------------------
        | Facility IDs
        |--------------------------------------------------------------------------
        */

        $facilityIds = $validated['facilities'] ?? [];

        unset($validated['facilities']);


        /*
        |--------------------------------------------------------------------------
        | Featured Image
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('featured_image')) {

            if (
                $resort->featured_image &&
                Storage::disk('public')->exists(
                    $resort->featured_image
                )
            ) {

                Storage::disk('public')->delete(
                    $resort->featured_image
                );
            }


            $validated['featured_image'] = $request
                ->file('featured_image')
                ->store(
                    'resorts',
                    'public'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Cover Image
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('cover_image')) {

            if (
                $resort->cover_image &&
                Storage::disk('public')->exists(
                    $resort->cover_image
                )
            ) {

                Storage::disk('public')->delete(
                    $resort->cover_image
                );
            }


            $validated['cover_image'] = $request
                ->file('cover_image')
                ->store(
                    'resorts/covers',
                    'public'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Update Resort + Sync Facilities
        |--------------------------------------------------------------------------
        */

DB::transaction(function () use (
    $validated,
    $facilityIds,
    $vendor
) {

            $resort->update(
                $validated
            );


            /*
            |--------------------------------------------------------------------------
            | Sync Facilities
            |--------------------------------------------------------------------------
            */

$validFacilityIds = Facility::where(
        'vendor_id',
        $vendor->id
    )
    ->where('type', 'resort')
    ->where('status', true)
    ->whereIn('id', $facilityIds)
    ->pluck('id')
    ->toArray();

$resort->facilities()->sync($validFacilityIds);
        });


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('vendor.resorts.index')
            ->with(
                'success',
                'Resort updated successfully.'
            );
    }


    /**
     * Delete resort.
     */
    public function destroy($id)
    {
        $vendor = Auth::user()->vendor;

        abort_if(
            !$vendor,
            403,
            'Vendor profile not found.'
        );


        /*
        |--------------------------------------------------------------------------
        | Vendor's Own Resort
        |--------------------------------------------------------------------------
        */

        $resort = Resort::where(
                'vendor_id',
                $vendor->id
            )
            ->findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | Delete Featured Image
        |--------------------------------------------------------------------------
        */

        if (
            $resort->featured_image &&
            Storage::disk('public')->exists(
                $resort->featured_image
            )
        ) {

            Storage::disk('public')->delete(
                $resort->featured_image
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Delete Cover Image
        |--------------------------------------------------------------------------
        */

        if (
            $resort->cover_image &&
            Storage::disk('public')->exists(
                $resort->cover_image
            )
        ) {

            Storage::disk('public')->delete(
                $resort->cover_image
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Delete Resort
        |--------------------------------------------------------------------------
        |
        | facility_resort pivot records will be removed
        | automatically if cascadeOnDelete() is configured.
        |
        */

        $resort->delete();


        return redirect()
            ->route('vendor.resorts.index')
            ->with(
                'success',
                'Resort deleted successfully.'
            );
    }


    /**
     * Generate unique slug.
     */
    private function generateUniqueSlug(
        string $slug,
        ?int $ignoreId = null
    ): string {

        $originalSlug = Str::slug($slug);

        if (empty($originalSlug)) {

            $originalSlug = 'resort';
        }


        $uniqueSlug = $originalSlug;

        $counter = 1;


        while (
            Resort::where(
                'slug',
                $uniqueSlug
            )
            ->when(
                $ignoreId,
                function ($query) use ($ignoreId) {

                    $query->where(
                        'id',
                        '!=',
                        $ignoreId
                    );
                }
            )
            ->exists()
        ) {

            $uniqueSlug =
                $originalSlug . '-' . $counter;

            $counter++;
        }


        return $uniqueSlug;
    }
}