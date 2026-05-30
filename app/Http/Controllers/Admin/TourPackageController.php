<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Destination;
use App\Models\Tour;

class TourPackageController extends Controller
{
    /**
     * All Packages
     */
public function index()
{
    $tours = Tour::with('destination')
        ->latest()
        ->get();

    $destinations = Destination::latest()->get(); 

    return view('admin.tour.index', compact('tours', 'destinations'));
}

    /**
     * Add Package Form
     */
    public function create()
    {
        $destinations = Destination::latest()->get();

        return view('admin.tour.create', compact('destinations'));
    }

    /**
     * Store Package
     */
public function store(Request $request)
{
    // =========================
    // VALIDATION
    // =========================

    $request->validate([

        'destination_id'   => 'required|exists:destinations,id',
        'title'            => 'required|max:255',
        'price'            => 'required|numeric',
        'discount_price'   => 'nullable|numeric',
        'duration'         => 'nullable|max:255',
        'location'         => 'nullable|max:255',
        'max_seat'         => 'nullable|integer',
        'featured_image'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

    ]);

    // =========================
    // IMAGE UPLOAD
    // =========================

    $imageName = null;

    if ($request->hasFile('featured_image')) {

        $image = $request->file('featured_image');

        $imageName = 'tour_' . time() . '.' . $image->getClientOriginalExtension();

        $image->move(
            public_path('uploads/tours'),
            $imageName
        );
    }

    // =========================
    // CREATE TOUR
    // =========================

    Tour::create([

        'destination_id'   => $request->destination_id,
        'title'            => $request->title,
        'slug'             => Str::slug($request->title),
        'short_description'=> $request->short_description,
        'description'      => $request->description,
        'price'            => $request->price,
        'discount_price'   => $request->discount_price,
        'duration'         => $request->duration,
        'location'         => $request->location,
        'featured_image'   => $imageName,
        'included'         => $request->included,
        'excluded'         => $request->excluded,
        'tour_plan'        => $request->tour_plan,
        'max_seat'         => $request->max_seat ?? 0,
        'map_iframe'       => $request->map_iframe,
        'is_featured'      => $request->is_featured ?? 0,
        'status'           => $request->status ?? 1,

    ]);

    // =========================
    // REDIRECT
    // =========================

    return redirect()
        ->route('admin.tours.index')
        ->with('success', 'Tour Package Added Successfully');

}

/**
 * VIEW TOUR
 */
public function show($slug)
{
    $tour = Tour::with('destination')
        ->where('slug' ,$slug)->firstOrFail();

    return view('admin.tour.view', compact('tour'));
}

/**
 * EDIT PAGE
 */
public function edit($slug)
{
    $tour = Tour::where('slug', $slug)->firstOrFail();

    $destinations = Destination::latest()->get();

    return view('admin.tour.edit', compact(
        'tour',
        'destinations'
    ));
}

/**
 * UPDATE TOUR
 */
public function update(Request $request, $slug)
{
    $tour = Tour::where('slug', $slug)->firstOrFail();

    $request->validate([

        'destination_id' => 'required',
        'title'          => 'required|max:255',
        'price'          => 'required|numeric',

    ]);

    // IMAGE UPDATE
    $imageName = $tour->featured_image;

    if ($request->hasFile('featured_image')) {

        // old delete
        if (
            $tour->featured_image &&
            file_exists(public_path('uploads/tours/'.$tour->featured_image))
        ) {

            unlink(public_path('uploads/tours/'.$tour->featured_image));
        }

        $image = $request->file('featured_image');

        $imageName = 'tour_'.time().'.'.$image->getClientOriginalExtension();

        $image->move(
            public_path('uploads/tours'),
            $imageName
        );
    }

    // UPDATE
    $tour->update([

        'destination_id'   => $request->destination_id,
        'title'            => $request->title,
        'slug'             => Str::slug($request->title).'-'.uniqid(),
        'short_description'=> $request->short_description,
        'description'      => $request->description,
        'price'            => $request->price,
        'discount_price'   => $request->discount_price,
        'duration'         => $request->duration,
        'location'         => $request->location,
        'featured_image'   => $imageName,
        'included'         => $request->included,
        'excluded'         => $request->excluded,
        'tour_plan'        => $request->tour_plan,
        'max_seat'         => $request->max_seat,
        'map_iframe'       => $request->map_iframe,
        'is_featured'      => $request->is_featured,
        'status'           => $request->status,

    ]);

    return redirect()
        ->route('admin.tours.index')
        ->with('success', 'Tour Updated Successfully');
}

/**
 * DELETE TOUR
 */
public function destroy($id)
{
    $tour = Tour::findOrFail($id);

    // DELETE IMAGE
    if (
        $tour->featured_image &&
        file_exists(public_path('uploads/tours/'.$tour->featured_image))
    ) {

        unlink(public_path('uploads/tours/'.$tour->featured_image));
    }

    $tour->delete();

    return redirect()
        ->route('admin.tours.index')
        ->with('success', 'Tour Deleted Successfully');
}

public function modalData($id)
{
    $tour = Tour::with('destination')->findOrFail($id);
    $destinations = Destination::orderBy('name')->get();
 
    return response()->json([
        'tour' => [
            'id'               => $tour->id,
            'title'            => $tour->title,
            'destination_id'   => $tour->destination_id,
            'destination_name' => $tour->destination->name ?? 'N/A',
            'price'            => $tour->price,
            'discount_price'   => $tour->discount_price,
            'duration'         => $tour->duration,
            'location'         => $tour->location,
            'max_seat'         => $tour->max_seat,
            'is_featured'      => $tour->is_featured,
            'status'           => $tour->status,
            'featured_image'   => $tour->featured_image,
            'short_description'=> $tour->short_description,
            'description'      => $tour->description,
            'included'         => $tour->included,
            'excluded'         => $tour->excluded,
            'tour_plan'        => $tour->tour_plan,
            'map_iframe'       => $tour->map_iframe,
        ],
        'destinations' => $destinations->map(fn($d) => [
            'id'   => $d->id,
            'name' => $d->name,
        ]),
    ]);
}
}