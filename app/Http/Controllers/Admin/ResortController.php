<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Resort;
use App\Models\Vendor;
use App\Models\Destination;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ResortController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Resort List
    |--------------------------------------------------------------------------
    */

public function index()
{
    $resorts = Resort::with(['vendor', 'destination'])
        ->latest()
        ->paginate(15);
    
    $vendors = Vendor::where('status', 'approved')
        ->orderBy('business_name')
        ->get();
    
    $destinations = Destination::orderBy('name')
        ->get();

    return view('admin.resorts.index', compact('resorts', 'vendors', 'destinations'));
}

    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $vendors = Vendor::where('status', 'approved')
            ->orderBy('business_name')
            ->get();

        $destinations = Destination::orderBy('name')
            ->get();

        return view(
            'admin.resorts.create',
            compact(
                'vendors',
                'destinations'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {

        $request->validate([

            'vendor_id' => 'required|exists:vendors,id',

            'destination_id' => 'required|exists:destinations,id',

            'name' => 'required|max:255',

            'short_description' => 'nullable',

            'description' => 'nullable',

            'division' => 'required|max:100',

            'district' => 'required|max:100',

            'area' => 'nullable|max:100',

            'address' => 'required',

            'google_map' => 'nullable',

            'latitude' => 'nullable',

            'longitude' => 'nullable',

            'check_in' => 'nullable',

            'check_out' => 'nullable',

            'featured_image' =>
            'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',

            'cover_image' =>
            'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',

            'meta_title' => 'nullable|max:255',

            'meta_description' => 'nullable',

        ]);

        /*
        |--------------------------------------------------------------------------
        | Upload Images
        |--------------------------------------------------------------------------
        */

        $featuredImage = null;

        if ($request->hasFile('featured_image')) {

            $featuredImage = $request
                ->file('featured_image')
                ->store('resorts/featured', 'public');
        }

        $coverImage = null;

        if ($request->hasFile('cover_image')) {

            $coverImage = $request
                ->file('cover_image')
                ->store('resorts/covers', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | Create Resort
        |--------------------------------------------------------------------------
        */

        Resort::create([

            'vendor_id' => $request->vendor_id,

            'destination_id' => $request->destination_id,

            'name' => $request->name,

            'slug' => Str::slug($request->name)
                . '-'
                . rand(1000,9999),

            'short_description'
                => $request->short_description,

            'description'
                => $request->description,

            'division'
                => $request->division,

            'district'
                => $request->district,

            'area'
                => $request->area,

            'address'
                => $request->address,

            'google_map'
                => $request->google_map,

            'latitude'
                => $request->latitude,

            'longitude'
                => $request->longitude,

            'featured_image'
                => $featuredImage,

            'cover_image'
                => $coverImage,

            'check_in'
                => $request->check_in,

            'check_out'
                => $request->check_out,

            'rating'
                => 0,

            'total_reviews'
                => 0,

            'is_featured'
                => 0,

            'is_verified'
                => 0,

            'status'
                => 1,

            'meta_title'
                => $request->meta_title,

            'meta_description'
                => $request->meta_description,

        ]);

        return redirect()

            ->route('admin.resorts.index')

            ->with(
                'success',
                'Resort Created Successfully.'
            );

    }

    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit($slug)
    {
        $resort = Resort::where('slug', $slug)->firstOrFail();

        $vendors = Vendor::where('status', 'approved')
            ->orderBy('business_name')
            ->get();

        $destinations = Destination::orderBy('name')
            ->get();

        return view(
            'admin.resorts.edit',
            compact(
                'resort',
                'vendors',
                'destinations'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, $slug)
    {
        $resort = Resort::where('slug', $slug)->firstOrFail();

        $request->validate([

            'vendor_id' => 'required|exists:vendors,id',

            'destination_id' => 'required|exists:destinations,id',

            'name' => 'required|max:255',

            'division' => 'required|max:100',

            'district' => 'required|max:100',

            'area' => 'nullable|max:100',

            'address' => 'required',

            'featured_image' =>
                'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',

            'cover_image' =>
                'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',

        ]);

        /*
        |--------------------------------------------------------------------------
        | Replace Featured Image
        |--------------------------------------------------------------------------
        */

        $featuredImage = $resort->featured_image;

        if ($request->hasFile('featured_image')) {

            if (
                $featuredImage &&
                Storage::disk('public')->exists($featuredImage)
            ) {
                Storage::disk('public')->delete($featuredImage);
            }

            $featuredImage = $request
                ->file('featured_image')
                ->store('resorts/featured', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | Replace Cover Image
        |--------------------------------------------------------------------------
        */

        $coverImage = $resort->cover_image;

        if ($request->hasFile('cover_image')) {

            if (
                $coverImage &&
                Storage::disk('public')->exists($coverImage)
            ) {
                Storage::disk('public')->delete($coverImage);
            }

            $coverImage = $request
                ->file('cover_image')
                ->store('resorts/covers', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | Update Resort
        |--------------------------------------------------------------------------
        */

        $resort->update([

            'vendor_id' => $request->vendor_id,

            'destination_id' => $request->destination_id,

            'name' => $request->name,

            'slug' => Str::slug($request->name) . '-' . $resort->id,

            'short_description' => $request->short_description,

            'description' => $request->description,

            'division' => $request->division,

            'district' => $request->district,

            'area' => $request->area,

            'address' => $request->address,

            'google_map' => $request->google_map,

            'latitude' => $request->latitude,

            'longitude' => $request->longitude,

            'featured_image' => $featuredImage,

            'cover_image' => $coverImage,

            'check_in' => $request->check_in,

            'check_out' => $request->check_out,

            'is_featured' => $request->boolean('is_featured'),

            'is_verified' => $request->boolean('is_verified'),

            'status' => $request->status,

            'meta_title' => $request->meta_title,

            'meta_description' => $request->meta_description,

        ]);

        return redirect()
            ->route('admin.resorts.index')
            ->with(
                'success',
                'Resort Updated Successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Resort
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $resort = Resort::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Delete Featured Image
        |--------------------------------------------------------------------------
        */

        if (
            $resort->featured_image &&
            Storage::disk('public')->exists($resort->featured_image)
        ) {
            Storage::disk('public')->delete($resort->featured_image);
        }

        /*
        |--------------------------------------------------------------------------
        | Delete Cover Image
        |--------------------------------------------------------------------------
        */

        if (
            $resort->cover_image &&
            Storage::disk('public')->exists($resort->cover_image)
        ) {
            Storage::disk('public')->delete($resort->cover_image);
        }

        /*
        |--------------------------------------------------------------------------
        | Delete Resort
        |--------------------------------------------------------------------------
        */

        $resort->delete();

        return redirect()
            ->route('admin.resorts.index')
            ->with(
                'success',
                'Resort Deleted Successfully.'
            );
    }

}