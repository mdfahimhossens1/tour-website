<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TourResource;
use App\Models\Tour;
use App\Models\TourDate;

class TourApiController extends Controller
{
    /**
     * All Approved Tours
     */
    public function index()
    {
        $tours = Tour::where('status', 1)
            ->where('approval_status', 'approved')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => TourResource::collection($tours),
        ]);
    }

    /**
     * Single Tour
     */
    public function show($id)
    {
        $tour = Tour::where('status', 1)
            ->where('approval_status', 'approved')
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => new TourResource($tour),
        ]);
    }

    /**
     * Tour Available Dates
     */
    public function dates($id)
    {
        $dates = TourDate::where('tour_id', $id)
            ->where('status', 1)
            ->orderBy('start_date')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $dates,
        ]);
    }
}
