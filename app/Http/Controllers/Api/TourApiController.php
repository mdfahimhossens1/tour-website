<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TourListResource;
use App\Http\Resources\TourDetailResource;
use App\Http\Resources\TourDateResource;
use Illuminate\Http\Request;
use App\Models\Tour;

class TourApiController extends Controller
{
    /**
     * All Tours + Search
     */
    public function index(Request $request)
    {
        $query = Tour::with([
            'destination',
            'galleries',
            'reviews',
            'bookings',
            'dates'
        ])
        ->where('status', 1)
        ->where('approval_status', 'approved');

        /*
        |--------------------------------------------------------------------------
        | Destination Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('destination')) {

            $keyword = $request->destination;

            $query->where(function ($q) use ($keyword) {

                $q->where('title', 'LIKE', "%{$keyword}%")
                  ->orWhere('location', 'LIKE', "%{$keyword}%");

            });

        }

        /*
        |--------------------------------------------------------------------------
        | Travel Date Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('travel_date')) {

            $query->whereHas('dates', function ($q) use ($request) {

                $q->whereDate('start_date', '<=', $request->travel_date)
                  ->whereDate('end_date', '>=', $request->travel_date)
                  ->where('status', 1);

            });

        }

        /*
        |--------------------------------------------------------------------------
        | Traveller Filter
        |--------------------------------------------------------------------------
        */

        $totalTraveller =
            (int) $request->adults +
            (int) $request->children;

        if ($totalTraveller > 0) {

            $query->where('max_seat', '>=', $totalTraveller);

        }

        /*
        |--------------------------------------------------------------------------
        | Result
        |--------------------------------------------------------------------------
        */

        $tours = $query
            ->latest()
            ->paginate(12);

        return response()->json([
            'success' => true,
            'data' => TourListResource::collection($tours),
            'meta' => [
                'current_page' => $tours->currentPage(),
                'last_page' => $tours->lastPage(),
                'per_page' => $tours->perPage(),
                'total' => $tours->total(),
            ]
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
                'dates'
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
                $tour->dates()
                    ->where('status', 1)
                    ->orderBy('start_date')
                    ->get()
            ),
        ]);
    }
}