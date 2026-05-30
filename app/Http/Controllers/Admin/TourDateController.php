<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Models\TourDate;
use Illuminate\Http\Request;

class TourDateController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | ALL DATES
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $dates = TourDate::with('tour')
            ->latest()
            ->get();

        $tours = Tour::latest()->get();

        return view('admin.tour_dates.index', compact('dates', 'tours'));
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE PAGE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $tours = Tour::latest()->get();

        return view('admin.tour_dates.create', compact('tours'));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([

            'tour_id'        => 'required|exists:tours,id',
            'start_date'     => 'required|date',
            'end_date'       => 'required|date|after_or_equal:start_date',
            'available_seat' => 'required|integer|min:1',
            'price'          => 'nullable|numeric',

        ]);

        TourDate::create([

            'tour_id'        => $request->tour_id,
            'start_date'     => $request->start_date,
            'end_date'       => $request->end_date,
            'available_seat' => $request->available_seat,
            'price'          => $request->price,
            'status'         => $request->status ?? 1,

        ]);

        return redirect()
            ->route('admin.tour.dates.index')
            ->with('success', 'Tour Date Added Successfully');
    }
}