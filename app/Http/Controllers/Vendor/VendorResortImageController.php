<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Resort;
use App\Models\ResortImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VendorResortImageController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Show Resort Images
    |--------------------------------------------------------------------------
    */
    public function index(Resort $resort)
    {
        $this->authorizeResort($resort);

        $images = $resort->images()
            ->orderBy('sort_order')
            ->latest()
            ->get();

        return view('vendor.resort-images.index', compact('resort', 'images'));
    }


    /*
    |--------------------------------------------------------------------------
    | Store New Image
    |--------------------------------------------------------------------------
    */
public function store(Request $request, Resort $resort)
{
    $this->authorizeResort($resort);

    $validated = $request->validate([
        'image' => [
            'required',
            'image',
            'mimes:jpg,jpeg,png,webp',
            'max:4096',
        ],

        'is_cover' => [
            'nullable',
            'boolean',
        ],
    ]);

    /*
    |--------------------------------------------------------------------------
    | Upload Image
    |--------------------------------------------------------------------------
    */

    if (!$request->hasFile('image')) {
        return back()->with('error', 'Image file was not uploaded.');
    }

    $path = $request
        ->file('image')
        ->store('resorts/gallery', 'public');


    /*
    |--------------------------------------------------------------------------
    | Sort Order
    |--------------------------------------------------------------------------
    */

    $sortOrder = ($resort->images()->max('sort_order') ?? 0) + 1;


    /*
    |--------------------------------------------------------------------------
    | Cover Image
    |--------------------------------------------------------------------------
    */

    $isCover = $request->boolean('is_cover');

    if ($isCover) {

        $resort->images()->update([
            'is_cover' => false,
        ]);

    }


    /*
    |--------------------------------------------------------------------------
    | Create Database Record
    |--------------------------------------------------------------------------
    */

    $resort->images()->create([
        'image' => $path,
        'is_cover' => $isCover,
        'sort_order' => $sortOrder,
    ]);


    return redirect()
        ->route('vendor.resort-images.index', $resort)
        ->with('success', 'Resort image uploaded successfully.');
}


    /*
    |--------------------------------------------------------------------------
    | Set Cover Image
    |--------------------------------------------------------------------------
    */
    public function setCover(ResortImage $resortImage)
    {
        $resortImage->load('resort');

        $this->authorizeResort($resortImage->resort);


        // Remove previous cover
        $resortImage->resort->images()->update([
            'is_cover' => false,
        ]);


        // Set new cover
        $resortImage->update([
            'is_cover' => true,
        ]);


        return back()->with('success', 'Cover image updated successfully.');
    }


    /*
    |--------------------------------------------------------------------------
    | Update Sort Order
    |--------------------------------------------------------------------------
    */
    public function updateOrder(Request $request, ResortImage $resortImage)
    {
        $resortImage->load('resort');

        $this->authorizeResort($resortImage->resort);


        $validated = $request->validate([
            'sort_order' => [
                'required',
                'integer',
                'min:0',
            ],
        ]);


        $resortImage->update([
            'sort_order' => $validated['sort_order'],
        ]);


        return back()->with('success', 'Image order updated successfully.');
    }


    /*
    |--------------------------------------------------------------------------
    | Delete Image
    |--------------------------------------------------------------------------
    */
    public function destroy(ResortImage $resortImage)
    {
        $resortImage->load('resort');

        $this->authorizeResort($resortImage->resort);


        // Delete file from storage
        if (
            $resortImage->image &&
            Storage::disk('public')->exists($resortImage->image)
        ) {
            Storage::disk('public')->delete($resortImage->image);
        }


        // Delete DB record
        $resortImage->delete();


        return back()->with('success', 'Resort image deleted successfully.');
    }


    /*
    |--------------------------------------------------------------------------
    | Authorization Check
    |--------------------------------------------------------------------------
    */
    private function authorizeResort(Resort $resort): void
    {
        $vendor = Auth::user()->vendor;

        abort_if(
            !$vendor,
            403,
            'Vendor profile not found.'
        );

        abort_unless(
            $resort->vendor_id === $vendor->id,
            403,
            'Unauthorized access.'
        );
    }
}