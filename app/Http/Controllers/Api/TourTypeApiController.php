<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TourTypeResource;
use App\Services\TourTypeService;

class TourTypeApiController extends Controller
{
    protected TourTypeService $tourTypeService;

    public function __construct(TourTypeService $tourTypeService)
    {
        $this->tourTypeService = $tourTypeService;
    }

    /**
     * Get All Active Tour Types
     */
    public function index()
    {
        $tourTypes = $this->tourTypeService->getAll();

        return TourTypeResource::collection($tourTypes);
    }

    /**
     * Get Single Tour Type
     */
    public function show($slug)
    {
        $tourType = $this->tourTypeService->getBySlug($slug);

        return new TourTypeResource($tourType);
    }
}