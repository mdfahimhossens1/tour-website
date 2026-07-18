<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BlogCategoryResource;
use App\Models\BlogCategory;

class BlogCategoryApiController extends Controller
{
    /**
     * Blog Category List
     */
    public function index()
    {
        $categories = BlogCategory::where('status', 1)
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Blog categories fetched successfully.',
            'data' => BlogCategoryResource::collection($categories),
        ]);
    }
}