<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Models\TourDate;

class TourApiController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Tour::where('status', 'active')
                ->where('approval_status', 'approved')
                ->latest()
                ->get()
        ]);
    }

    public function show($id)
    {
        return response()->json([
            'success' => true,
            'data' => Tour::where('status', 'active')
                ->where('approval_status', 'approved')
                ->findOrFail($id)
        ]);
    }

    public function dates($id)
    {
        $dates = TourDate::where('tour_id', $id)
            ->where('status', 'active')
            ->orderBy('start_date')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $dates
        ]);
    }
}