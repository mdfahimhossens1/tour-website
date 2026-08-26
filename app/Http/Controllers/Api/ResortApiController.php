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
            'coverImage',
            'facilities',
            'rooms.roomType',
            'rooms.prices',
            'rooms.facilities',
            'rooms.images',
        ])
            ->where('status', 'approved')
            ->latest()
            ->take(8)
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
            'coverImage',
            'facilities',
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
            'coverImage',
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
            'coverImage',
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

            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {

                $q->where(
                    'name',
                    'like',
                    '%' . $keyword . '%'
                )

                ->orWhere(
                    'district',
                    'like',
                    '%' . $keyword . '%'
                )

                ->orWhere(
                    'area',
                    'like',
                    '%' . $keyword . '%'
                )

                ->orWhere(
                    'division',
                    'like',
                    '%' . $keyword . '%'
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
