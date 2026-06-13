<?php

namespace App\Http\Controllers\Vendor;

use App\Models\Tour;
use App\Models\TourDate;
use Illuminate\Http\Request;

class VendorTourDateController extends Controller
{
    public function index($slug)
    {
        $vendor = auth()->user()->vendor;

        $tour = Tour::where('vendor_id', $vendor->id)
            ->where('slug', $slug)
            ->firstOrFail();

        $dates = TourDate::where('tour_id', $tour->id)
            ->latest()
            ->get();

        return view('vendor.dates.index', compact('tour', 'dates'));
    }

    public function store(Request $request, $slug)
    {
        $vendor = auth()->user()->vendor;

        $tour = Tour::where('vendor_id', $vendor->id)
            ->where('slug', $slug)
            ->firstOrFail();

        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'available_seat' => 'required|integer|min:0',
        ]);

        TourDate::create([
            'tour_id' => $tour->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'available_seat' => $request->available_seat,
            'status' => 1,
        ]);

        return back()->with('success', 'Tour date added successfully');
    }

    public function destroy($id)
    {
        $vendor = auth()->user()->vendor;

        $date = TourDate::with('tour')->findOrFail($id);

        if ($date->tour->vendor_id != $vendor->id) {
            abort(403);
        }

        $date->delete();

        return back()->with('success', 'Date deleted');
    }
}