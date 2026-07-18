<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | My Reviews
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $reviews = Review::with([
            'tour',
            'booking',
        ])
        ->where('user_id', $request->user()->id)
        ->latest()
        ->get();

        return response()->json([

            'success' => true,

            'data' => $reviews,

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Store Review
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([

            'tour_id' => 'required|exists:tours,id',

            'booking_id' => 'nullable|exists:bookings,id',

            'rating' => 'required|integer|min:1|max:5',

            'review' => 'required|string|max:1000',

        ]);

        $review = Review::create([

            'user_id' => $request->user()->id,

            'tour_id' => $validated['tour_id'],

            'booking_id' => $validated['booking_id'] ?? null,

            'rating' => $validated['rating'],

            'review' => $validated['review'],

            'is_approved' => false,

        ]);

        return response()->json([

            'success' => true,

            'message' => 'Review submitted successfully.',

            'data' => $review,

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Update Review
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, Review $review)
    {
        abort_if(
            $review->user_id != $request->user()->id,
            403
        );

        $validated = $request->validate([

            'rating' => 'required|integer|min:1|max:5',

            'review' => 'required|string|max:1000',

        ]);

        $review->update([

            'rating' => $validated['rating'],

            'review' => $validated['review'],

            'is_approved' => false,

        ]);

        return response()->json([

            'success' => true,

            'message' => 'Review updated successfully.',

            'data' => $review,

        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Review
    |--------------------------------------------------------------------------
    */

    public function destroy(Request $request, Review $review)
    {
        abort_if(
            $review->user_id != $request->user()->id,
            403
        );

        $review->delete();

        return response()->json([

            'success' => true,

            'message' => 'Review deleted successfully.',

        ]);
    }
}