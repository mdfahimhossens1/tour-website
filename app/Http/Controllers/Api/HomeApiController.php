<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TourCardResource;
use App\Http\Resources\DestinationResource;
use App\Http\Resources\TestimonialResource;
use App\Http\Resources\TourDateResource;
use App\Models\Tour;
use App\Models\Destination;
use App\Models\Testimonial;
use App\Models\TourDate;
use Illuminate\Http\Request;

class HomeApiController extends Controller
{
    /**
     * Homepage API
     */
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Featured Tours
        |--------------------------------------------------------------------------
        */

        $packages = Tour::with([
                'destination',
                'galleries',
                'reviews',
                'bookings',
            ])
            ->where('status', 1)
            ->where('approval_status', 'approved')
            ->latest()
            ->take(6)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Destinations
        |--------------------------------------------------------------------------
        */

        $destinations = Destination::withCount('tours')
            ->where('status', 1)
            ->latest()
            ->take(8)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Testimonials
        |--------------------------------------------------------------------------
        */

        $testimonials = Testimonial::where('status', 'active')
            ->latest()
            ->take(6)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Upcoming Tour Dates
        |--------------------------------------------------------------------------
        */

        $tourDates = TourDate::where('status', 1)
            ->orderBy('start_date')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'success' => true,

            'data' => [

                'packages' => TourCardResource::collection($packages),

                'destinations' => DestinationResource::collection($destinations),

                'tourDates' => TourDateResource::collection($tourDates),

                'testimonials' => TestimonialResource::collection($testimonials),

            ],
        ]);
    }

    /**
     * Get Tour Dates by Tour ID
     */
    public function getTourDates($tourId)
    {
        $dates = TourDate::where('tour_id', $tourId)
            ->where('status', 1)
            ->orderBy('start_date')
            ->get();

        return response()->json([
            'success' => true,

            'data' => TourDateResource::collection($dates),
        ]);
    }
}