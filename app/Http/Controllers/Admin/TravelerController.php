<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Traveler;
use App\Models\Booking;
use Illuminate\Http\Request;

class TravelerController extends Controller
{
    // =========================
    // ALL TRAVELERS
    // =========================
    public function index() {
        $travelers = Traveler::with('booking.tour')->latest()->get();
        $bookings  = Booking::with('tour')->latest()->get();
        return view('admin.travelers.index', compact('travelers', 'bookings'));
    }

    // =========================
    // CREATE FORM (optional future use)
    // =========================
    public function create()
    {
        $bookings = Booking::latest()->get();

        return view('admin.travelers.create', compact('bookings'));
    }

    // =========================
    // STORE
    // =========================
    public function store(Request $request)
    {
        $request->validate([

            'booking_id' => 'required|exists:bookings,id',
            'name'       => 'required|max:255',
            'phone'      => 'nullable',
            'email'      => 'nullable|email',
            'age'        => 'nullable|integer',
            'gender'     => 'nullable',
            'address'    => 'nullable',

        ]);

        Traveler::create($request->all());

        return back()->with('success', 'Traveler Added Successfully');
    }
}