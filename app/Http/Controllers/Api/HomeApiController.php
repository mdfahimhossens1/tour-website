<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Tour;
use App\Models\Destination;
use App\Models\Testimonial;

class HomeApiController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------
        | 1. FEATURED / POPULAR TOURS
        |--------------------------------------
        */

$packages = Tour::with(['destination', 'galleries'])
    ->where('status', 1)
    ->where('approval_status', 'approved')
    ->latest()
    ->take(6)
    ->get()
    ->map(function ($tour) {

    return [
        'id' => (string)$tour->id,

        'title' => $tour->title,

        'tagline' => $tour->short_description ?? '',

        'category' => $tour->destination?->name ?? '',

        'location' => $tour->location ?? '',

        'duration' => $tour->duration ?? '',

        'priceBDT' => (float)$tour->price,

        'discountPriceBDT' => (float)($tour->discount_price ?? 0),

        'rating' => round($tour->reviews()->avg('rating') ?? 0, 1),

        'reviewCount' => $tour->reviews()->count(),

        'images' => $tour->featured_image
            ? [asset('uploads/tours/' . $tour->featured_image)]
            : [],

        'description' => $tour->description ?? '',

        'included' => $tour->included
            ? preg_split('/\r\n|\r|\n/', $tour->included)
            : [],

        'excluded' => $tour->excluded
            ? preg_split('/\r\n|\r|\n/', $tour->excluded)
            : [],

        'tourPlan' => $tour->tour_plan ?? '',

        'totalSeats' => (int)$tour->max_seat,

        'availableSeats' => max(
            0,
            (int)$tour->max_seat -
            $tour->bookings()
                ->where('booking_status', 'Confirmed')
                ->sum('person_count')
        ),

        'mapIframe' => $tour->map_iframe,

        'featured' => (bool)$tour->is_featured,
    ];
});



        /*
        |--------------------------------------
        | 2. DESTINATIONS GRID
        |--------------------------------------
        */

        $destinations = Destination::withCount('tours')
            ->where('status', 'active')
            ->orderBy('id', 'desc')
            ->take(8)
            ->get()
            ->map(function ($dest) {

                return [
                    'id' => $dest->id,
                    'name' => $dest->name,
                    'locator' => $dest->slug,


        'image' => $dest->image
            ? asset('storage/' . $dest->image)
            : 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e',


        'package_count' => $dest->tours_count,
                ];
            });


        /*
        |--------------------------------------
        | 3. TESTIMONIALS
        |--------------------------------------
        */

        $testimonials = Testimonial::where('status', 'active')
            ->latest()
            ->take(6)
            ->get()
            ->map(function ($t) {

                return [
                    'id' => $t->id,
                    'name' => $t->name,
                    'text' => $t->message,
                    'rating' => (float) $t->rating,

                    'location' => $t->designation ?? 'Traveler',

                    'image' => $t->image
                        ? asset('storage/' . $t->image)
                        : null,
                ];
            });


        /*
        |--------------------------------------
        | RESPONSE
        |--------------------------------------
        */

        return response()->json([
            'success' => true,
            'data' => [
                'packages' => $packages,
                'destinations' => $destinations,
                'testimonials' => $testimonials,
            ]
        ]);
    }
}