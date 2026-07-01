<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TourListResource;
use App\Http\Resources\TourDetailResource;
use App\Http\Resources\TourDateResource;
use App\Models\Tour;

class TourApiController extends Controller
{
    /**
     * All Tours
     */
    public function index()
    {
        $tours = Tour::with([
                'destination',
                'galleries',
                'reviews',
                'bookings'
            ])
            ->where('status', 1)
            ->where('approval_status', 'approved')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => TourListResource::collection($tours),
        ]);
    }

    /**
     * Single Tour
     */
    public function show(string $slug)
    {
        $tour = Tour::with([
                'destination',
                'galleries',
                'reviews',
                'bookings',
                'tourDates'
            ])
            ->where('slug', $slug)
            ->where('status', 1)
            ->where('approval_status', 'approved')
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => new TourDetailResource($tour),
        ]);
    }

    /**
     * Tour Dates
     */
    public function dates(string $slug)
    {
        $tour = Tour::where('slug', $slug)
            ->where('status', 1)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => TourDateResource::collection(
                $tour->tourDates()
                    ->where('status', 1)
                    ->orderBy('start_date')
                    ->get()
            ),
        ]);
    }
}