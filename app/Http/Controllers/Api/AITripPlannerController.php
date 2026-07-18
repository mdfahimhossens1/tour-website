<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AITripPlannerRequest;
use App\Http\Resources\AITripPlanResource;
use App\Services\AITripPlannerService;

class AITripPlannerController extends Controller
{
    public function __construct(
        protected AITripPlannerService $service
    ) {}

    public function generate(AITripPlannerRequest $request)
    {
        $plan = $this->service->generate(
            $request->validated()
        );

        return response()->json([

            'success' => true,

            'message' => 'Trip plan generated successfully.',

            'data' => new AITripPlanResource($plan),

        ]);
    }
}