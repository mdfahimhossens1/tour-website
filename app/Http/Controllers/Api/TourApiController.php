<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tour;

class TourApiController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Tour::latest()->get()
        ]);
    }

    public function show($id)
    {
        return response()->json([
            'success' => true,
            'data' => Tour::findOrFail($id)
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