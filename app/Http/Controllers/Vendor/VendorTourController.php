<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VendorTourController extends Controller
{
public function index()
{
    $vendor = auth()->user()->vendor;

    $tours = Tour::where(
            'vendor_id',
            $vendor->id
        )
        ->latest()
        ->paginate(20);

    return view(
        'vendor.tours.index',
        compact('tours')
    );
}

    public function create()
    {
        $destinations = Destination::where('status', 1)->get();

        return view(
            'vendor.tours.create',
            compact('destinations')
        );
    }

    public function store(Request $request)
    {
        $vendor = auth()->user()->vendor;

        $request->validate([
            'destination_id' => 'required|exists:destinations,id',
            'title' => 'required|max:255',
            'price' => 'required|numeric',
            'featured_image' => 'nullable|image',
        ]);

        $tour = new Tour();

        $tour->vendor_id = $vendor->id;
        $tour->destination_id = $request->destination_id;

        $tour->title = $request->title;
        $tour->slug = Str::slug($request->title . '-' . time());

        $tour->short_description = $request->short_description;
        $tour->description = $request->description;

        $tour->price = $request->price;
        $tour->discount_price = $request->discount_price;

        $tour->duration = $request->duration;
        $tour->location = $request->location;

        $tour->included = $request->included;
        $tour->excluded = $request->excluded;

        $tour->tour_plan = $request->tour_plan;

        $tour->max_seat = $request->max_seat ?? 0;

        $tour->map_iframe = $request->map_iframe;

        $tour->status = 0;
        $tour->approval_status = 'pending';

        if ($request->hasFile('featured_image')) {

            $image = $request->file('featured_image');

            $name = time() . '.' . $image->getClientOriginalExtension();

            $image->move(
                public_path('uploads/tours'),
                $name
            );

            $tour->featured_image =
                'uploads/tours/' . $name;
        }

        $tour->save();

\Log::info('Vendor Tour Created', [
    'vendor_id' => $vendor->id,
    'tour_id' => $tour->id,
    'title' => $tour->title,
]);

        return redirect()
            ->route('vendor.tours.index')
            ->with(
                'success',
                'Tour created successfully'
            );
    }

    public function edit($slug)
    {
        $vendor = auth()->user()->vendor;

        $tour = Tour::where(
            'vendor_id',
            $vendor->id
        )
        ->where(
            'slug',
            $slug
        )
        ->firstOrFail();

        $destinations = Destination::all();

        return view(
            'vendor.tours.edit',
            compact(
                'tour',
                'destinations'
            )
        );
    }

    public function update(
        Request $request,
        $slug
    )
    {
        $vendor = auth()->user()->vendor;

        $tour = Tour::where(
            'vendor_id',
            $vendor->id
        )
        ->where(
            'slug',
            $slug
        )
        ->firstOrFail();

        $request->validate([
            'title' => 'required|max:255',
            'price' => 'required|numeric',
        ]);

        $tour->update([
            'title' => $request->title,
            'destination_id' => $request->destination_id,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'duration' => $request->duration,
            'location' => $request->location,
            'included' => $request->included,
            'excluded' => $request->excluded,
            'tour_plan' => $request->tour_plan,
            'max_seat' => $request->max_seat,
            'map_iframe' => $request->map_iframe,
        ]);

        activityLog(
            'Tour',
            'UPDATE',
            'Vendor updated tour: ' . $tour->title
        );

        return redirect()
            ->route('vendor.tours.index')
            ->with(
                'success',
                'Tour updated successfully'
            );
    }

    public function destroy($id)
    {
        $vendor = auth()->user()->vendor;

        $tour = Tour::where(
            'vendor_id',
            $vendor->id
        )
        ->findOrFail($id);

        $tourTitle = $tour->title;

        $tour->delete();

\Log::info('Vendor Tour Deleted', [
    'vendor_id' => $vendor->id,
    'tour_id' => $tour->id,
    'title' => $tourTitle,
]);

        return back()->with(
            'success',
            'Tour deleted successfully'
        );
    }
}