<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BlogResource;
use App\Models\Blog;

class BlogApiController extends Controller
{
    /**
     * Blog List
     */
    public function index()
    {
        $blogs = Blog::with('category')
            ->where('status', 1)
            ->latest()
            ->paginate(9);

        return BlogResource::collection($blogs);
    }

    /**
     * Blog Details
     */
    public function show(string $slug)
    {
        $blog = Blog::with('category')
            ->where('status', 1)
            ->where('slug', $slug)
            ->firstOrFail();

        return new BlogResource($blog);
    }
}