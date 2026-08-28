<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class TransportApiController extends Controller
{
    /**
     * All Approved Transports
     */
    public function index(Request $request)
    {
        $query = Vehicle::approved()
            ->with([
                'vendor:id,business_name',
            ]);


        /*
        |--------------------------------------------------------------------------
        | Filters
        |--------------------------------------------------------------------------
        */

        if ($request->filled('type')) {
            $query->where(
                'vehicle_type',
                $request->type
            );
        }


        if ($request->filled('division')) {
            $query->where(
                'division',
                $request->division
            );
        }


        if ($request->filled('district')) {
            $query->where(
                'district',
                $request->district
            );
        }


        if ($request->filled('with_driver')) {
            $query->where(
                'with_driver',
                $request->boolean('with_driver')
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Featured First
        |--------------------------------------------------------------------------
        */

        $vehicles = $query
            ->orderByDesc('is_featured')
            ->latest()
            ->paginate(
                $request->get('per_page', 12)
            );


        /*
        |--------------------------------------------------------------------------
        | Transform Image URL
        |--------------------------------------------------------------------------
        */

        $vehicles->getCollection()->transform(function ($vehicle) {

            $vehicle->featured_image_url =
                $vehicle->featured_image
                    ? asset(
                        'storage/' . $vehicle->featured_image
                    )
                    : null;

            return $vehicle;
        });


        return response()->json([
            'success' => true,
            'message' => 'Transports retrieved successfully.',
            'data' => $vehicles,
        ]);
    }


    /**
     * Featured Transports
     */
    public function featured()
    {
        $vehicles = Vehicle::featured()
            ->with([
                'vendor:id,business_name',
            ])
            ->latest()
            ->take(8)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Transform Image URL
        |--------------------------------------------------------------------------
        */

        $vehicles->transform(function ($vehicle) {

            $vehicle->featured_image_url =
                $vehicle->featured_image
                    ? asset(
                        'storage/' . $vehicle->featured_image
                    )
                    : null;

            return $vehicle;
        });


        return response()->json([
            'success' => true,
            'message' => 'Featured transports retrieved successfully.',
            'data' => $vehicles,
        ]);
    }


    /**
     * Single Transport Details
     */
    public function show($slug)
    {
        $vehicle = Vehicle::approved()
            ->with([
                'vendor:id,business_name',
            ])
            ->where('slug', $slug)
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Transform Image URL
        |--------------------------------------------------------------------------
        */

        $vehicle->featured_image_url =
            $vehicle->featured_image
                ? asset(
                    'storage/' . $vehicle->featured_image
                )
                : null;


        return response()->json([
            'success' => true,
            'message' => 'Transport details retrieved successfully.',
            'data' => $vehicle,
        ]);
    }


    /**
     * Transport Types
     */
    public function types()
    {
        $types = Vehicle::approved()
            ->whereNotNull('vehicle_type')
            ->distinct()
            ->pluck('vehicle_type');


        return response()->json([
            'success' => true,
            'data' => $types,
        ]);
    }
}
