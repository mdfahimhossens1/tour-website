<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;

class TestimonialApiController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::where('status', 1)
            ->latest()
            ->get()
            ->map(function ($item) {

                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'designation' => $item->designation,
                    'message' => $item->message,
                    'rating' => $item->rating,
                    'image' => $item->image
                        ? asset('uploads/testimonials/' . $item->image)
                        : null,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $testimonials,
        ]);
    }
}