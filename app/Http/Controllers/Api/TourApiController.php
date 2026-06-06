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
}