<?php

namespace App\Http\Controllers\Vendor;

use App\Models\Tour;
use App\Models\Gallery;
use Illuminate\Http\Request;

class VendorGalleryController extends Controller
{
    public function index($slug)
    {
        $vendor = auth()->user()->vendor;

        $tour = Tour::where('vendor_id', $vendor->id)
            ->where('slug', $slug)
            ->firstOrFail();

        $galleries = Gallery::where('tour_id', $tour->id)
            ->latest()
            ->get();

        return view('vendor.tours.gallery.index', compact('tour', 'galleries'));
    }

    public function store(Request $request, $slug)
    {
        $vendor = auth()->user()->vendor;

        $tour = Tour::where('vendor_id', $vendor->id)
            ->where('slug', $slug)
            ->firstOrFail();

        $request->validate([
            'images.*' => 'required|image|mimes:jpg,png,jpeg,webp|max:2048',
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {

                $name = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();

                $image->move(public_path('uploads/gallery'), $name);

                Gallery::create([
                    'tour_id' => $tour->id,
                    'image' => 'uploads/gallery/'.$name,
                    'type' => 'image',
                ]);
            }
        }

        return back()->with('success', 'Gallery uploaded successfully');
    }

    public function destroy($id)
    {
        $vendor = auth()->user()->vendor;

        $gallery = Gallery::with('tour')->findOrFail($id);

        if ($gallery->tour->vendor_id != $vendor->id) {
            abort(403);
        }

        if (file_exists(public_path($gallery->image))) {
            unlink(public_path($gallery->image));
        }

        $gallery->delete();

        return back()->with('success', 'Image deleted');
    }
}