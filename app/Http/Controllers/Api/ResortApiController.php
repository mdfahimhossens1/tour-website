<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ResortResource;
use App\Models\Resort;
use Illuminate\Http\Request;

class ResortApiController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Featured Resorts
    |--------------------------------------------------------------------------
    */

    public function featured()
    {
        $resorts = Resort::with([
                'destination',
                'vendor',
                'images',
                'facilities',
                'rooms.roomType',
                'rooms.prices',
                'rooms.facilities',
                'rooms.images',
            ])
            ->where('status', 'approved')
            ->where('is_featured', true)
            ->latest()
            ->get();

        return response()->json([

            'success' => true,

            'data' => ResortResource::collection($resorts),

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | All Resorts
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $resorts = Resort::with([
                'destination',
                'vendor',
                'images',
                'rooms.roomType',
                'rooms.prices',
            ])
            ->where('status', 'approved')
            ->latest()
            ->paginate(12);

        return response()->json([

            'success' => true,

            'data' => ResortResource::collection($resorts),

            'pagination' => [

                'current_page' => $resorts->currentPage(),

                'last_page' => $resorts->lastPage(),

                'per_page' => $resorts->perPage(),

                'total' => $resorts->total(),

            ],

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Resort Details
    |--------------------------------------------------------------------------
    */

    public function show($slug)
    {
        $resort = Resort::with([
                'destination',
                'vendor',
                'images',
                'facilities',
                'rooms.roomType',
                'rooms.prices',
                'rooms.facilities',
                'rooms.images',
            ])
            ->where('slug', $slug)
            ->where('status', 'approved')
            ->firstOrFail();

        return response()->json([

            'success' => true,

            'data' => new ResortResource($resort),

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Search Resorts
    |--------------------------------------------------------------------------
    */

    public function search(Request $request)
    {
        $query = Resort::with([
                'destination',
                'vendor',
                'images',
                'rooms.roomType',
                'rooms.prices',
            ])
            ->where('status', 'approved');

        /*
        |--------------------------------------------------------------------------
        | Destination
        |--------------------------------------------------------------------------
        */

        if ($request->filled('destination')) {

            $query->where(
                'destination_id',
                $request->destination
            );

        }

        /*
        |--------------------------------------------------------------------------
        | Division
        |--------------------------------------------------------------------------
        */

        if ($request->filled('division')) {

            $query->where(
                'division',
                $request->division
            );

        }

        /*
        |--------------------------------------------------------------------------
        | District
        |--------------------------------------------------------------------------
        */

        if ($request->filled('district')) {

            $query->where(
                'district',
                $request->district
            );

        }

        /*
        |--------------------------------------------------------------------------
        | Keyword
        |--------------------------------------------------------------------------
        */

        if ($request->filled('keyword')) {

            $query->where(function ($q) use ($request) {

                $q->where(
                    'name',
                    'like',
                    '%' . $request->keyword . '%'
                )

                ->orWhere(
                    'district',
                    'like',
                    '%' . $request->keyword . '%'
                )

                ->orWhere(
                    'area',
                    'like',
                    '%' . $request->keyword . '%'
                );

            });

        }

        $resorts = $query
            ->latest()
            ->get();

        return response()->json([

            'success' => true,

            'data' => ResortResource::collection($resorts),

        ]);
    }
}