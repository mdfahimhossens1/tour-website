<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Facility;
use App\Models\Resort;
use App\Models\ResortImage;
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
            ->with([
                'destination',
                'images',
                'facilities',
            ])
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

            /*
            |--------------------------------------------------------------------------
            | Images
            |--------------------------------------------------------------------------
            */

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

            'images' => [
                'nullable',
                'array',
            ],

            'images.*' => [
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
            ],

            /*
            |--------------------------------------------------------------------------
            | Check In / Check Out
            |--------------------------------------------------------------------------
            */

            'check_in' => [
                'nullable',
                'date_format:H:i',
            ],

            'check_out' => [
                'nullable',
                'date_format:H:i',
            ],

            /*
            |--------------------------------------------------------------------------
            | Rating
            |--------------------------------------------------------------------------
            */

            'rating' => [
                'nullable',
                'numeric',
                'min:0',
                'max:5',
            ],

            /*
            |--------------------------------------------------------------------------
            | Featured
            |--------------------------------------------------------------------------
            */

            'is_featured' => [
                'nullable',
                'boolean',
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

            /*
            |--------------------------------------------------------------------------
            | SEO
            |--------------------------------------------------------------------------
            */

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

        if (empty($validated['slug'])) {

            $validated['slug'] = Str::slug(
                $validated['name']
            );
        }

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

        $validated['is_verified'] =
            $request->boolean('is_verified');


        /*
        |--------------------------------------------------------------------------
        | Default Status
        |--------------------------------------------------------------------------
        */

        $validated['status'] = 'approved';


        /*
        |--------------------------------------------------------------------------
        | Facilities
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
        | Gallery Images
        |--------------------------------------------------------------------------
        */

        $galleryImages = $request->file('images', []);


        /*
        |--------------------------------------------------------------------------
        | Create Resort
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $validated,
            $facilityIds,
            $galleryImages,
            $vendor
        ) {

            $resort = Resort::create(
                $validated
            );


            /*
            |--------------------------------------------------------------------------
            | Attach Vendor Facilities
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

                $resort->facilities()->sync(
                    $validFacilityIds
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Gallery
            |--------------------------------------------------------------------------
            */

            if (!empty($galleryImages)) {

                foreach ($galleryImages as $index => $image) {

                    $path = $image->store(
                        'resorts/gallery',
                        'public'
                    );

                    ResortImage::create([
                        'resort_id' => $resort->id,
                        'image' => $path,
                        'is_cover' => $index === 0,
                        'sort_order' => $index,
                    ]);
                }
            }
        });


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


        $resort = Resort::where(
                'vendor_id',
                $vendor->id
            )
            ->where(
                'slug',
                $slug
            )
            ->with([
                'facilities',
                'images',
                'destination',
            ])
            ->firstOrFail();


        $destinations = Destination::orderBy('name')
            ->get();


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

            /*
            |--------------------------------------------------------------------------
            | Images
            |--------------------------------------------------------------------------
            */

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

            'images' => [
                'nullable',
                'array',
            ],

            'images.*' => [
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
            ],

            /*
            |--------------------------------------------------------------------------
            | Check In / Check Out
            |--------------------------------------------------------------------------
            */

            'check_in' => [
                'nullable',
                'date_format:H:i',
            ],

            'check_out' => [
                'nullable',
                'date_format:H:i',
            ],

            /*
            |--------------------------------------------------------------------------
            | Rating
            |--------------------------------------------------------------------------
            */

            'rating' => [
                'nullable',
                'numeric',
                'min:0',
                'max:5',
            ],

            /*
            |--------------------------------------------------------------------------
            | Featured
            |--------------------------------------------------------------------------
            */

            'is_featured' => [
                'nullable',
                'boolean',
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

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            'status' => [
                'nullable',
                'in:approved,pending,rejected',
            ],

            /*
            |--------------------------------------------------------------------------
            | SEO
            |--------------------------------------------------------------------------
            */

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
        ]);


        /*
        |--------------------------------------------------------------------------
        | Slug
        |--------------------------------------------------------------------------
        */

        if (empty($validated['slug'])) {

            $validated['slug'] = Str::slug(
                $validated['name']
            );
        }


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

        $validated['is_verified'] =
            $request->boolean('is_verified');


        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        $validated['status'] =
            $request->input(
                'status',
                $resort->status ?? 'approved'
            );


        /*
        |--------------------------------------------------------------------------
        | Facilities
        |--------------------------------------------------------------------------
        */

        $facilityIds = $validated['facilities'] ?? [];

        unset($validated['facilities']);


        /*
        |--------------------------------------------------------------------------
        | Gallery Images
        |--------------------------------------------------------------------------
        */

        $galleryImages = $request->file(
            'images',
            []
        );


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


            $validated['featured_image'] =
                $request
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


            $validated['cover_image'] =
                $request
                    ->file('cover_image')
                    ->store(
                        'resorts/covers',
                        'public'
                    );
        }


        /*
        |--------------------------------------------------------------------------
        | Update Resort
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $validated,
            $facilityIds,
            $galleryImages,
            $vendor,
            $resort
        ) {

            /*
            |--------------------------------------------------------------------------
            | Update Main Resort
            |--------------------------------------------------------------------------
            */

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


            $resort->facilities()->sync(
                $validFacilityIds
            );


            /*
            |--------------------------------------------------------------------------
            | Add New Gallery Images
            |--------------------------------------------------------------------------
            */

            if (!empty($galleryImages)) {

                $lastSortOrder = ResortImage::where(
                    'resort_id',
                    $resort->id
                )->max('sort_order');


                $lastSortOrder =
                    $lastSortOrder ?? -1;


                foreach (
                    $galleryImages as $index => $image
                ) {

                    $path = $image->store(
                        'resorts/gallery',
                        'public'
                    );


                    ResortImage::create([
                        'resort_id' => $resort->id,
                        'image' => $path,
                        'is_cover' => false,
                        'sort_order' =>
                            $lastSortOrder + $index + 1,
                    ]);
                }
            }
        });


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


        $resort = Resort::where(
                'vendor_id',
                $vendor->id
            )
            ->with([
                'images',
                'facilities',
            ])
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
        | Delete Gallery Images
        |--------------------------------------------------------------------------
        */

        foreach ($resort->images as $image) {

            if (
                $image->image &&
                Storage::disk('public')->exists(
                    $image->image
                )
            ) {

                Storage::disk('public')->delete(
                    $image->image
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Delete Resort
        |--------------------------------------------------------------------------
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
     * Delete single resort gallery image.
     */
    public function destroyImage($id)
    {
        $vendor = Auth::user()->vendor;

        abort_if(
            !$vendor,
            403,
            'Vendor profile not found.'
        );


        /*
        |--------------------------------------------------------------------------
        | Find Image
        |--------------------------------------------------------------------------
        */

        $image = ResortImage::whereHas(
            'resort',
            function ($query) use ($vendor) {

                $query->where(
                    'vendor_id',
                    $vendor->id
                );
            }
        )->findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | Delete Physical Image
        |--------------------------------------------------------------------------
        */

        if (
            $image->image &&
            Storage::disk('public')->exists(
                $image->image
            )
        ) {

            Storage::disk('public')->delete(
                $image->image
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Delete Database Record
        |--------------------------------------------------------------------------
        */

        $image->delete();


        return back()->with(
            'success',
            'Resort image deleted successfully.'
        );
    }


    /**
     * Generate unique slug.
     */
    private function generateUniqueSlug(
        string $slug,
        ?int $ignoreId = null
    ): string {

        $originalSlug = Str::slug(
            $slug
        );


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