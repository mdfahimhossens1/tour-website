<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DestinationResource;
use App\Models\Destination;

class DestinationApiController extends Controller
{
    public function index()
    {
        $destinations = Destination::query()
            ->where('status', 1)
            ->withCount('tours')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => DestinationResource::collection($destinations),
        ]);
    }
}