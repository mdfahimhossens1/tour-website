<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class VendorReviewController extends Controller
{
    /**
     * Display vendor reviews.
     */
    public function index(Request $request)
    {
        $vendor = auth()->user()->vendor;

        if (!$vendor) {
            abort(403, 'Vendor profile not found.');
        }

        /*
        |--------------------------------------------------------------------------
        | Base Query
        |--------------------------------------------------------------------------
        */

        $query = Review::query()
            ->with([
                'user',
                'tour',
                'booking',
            ])
            ->whereHas('tour', function ($q) use ($vendor) {
                $q->where('vendor_id', $vendor->id);
            });


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('review', 'like', "%{$search}%")

                    ->orWhereHas('user', function ($user) use ($search) {
                        $user->where('name', 'like', "%{$search}%");
                    })

                    ->orWhereHas('tour', function ($tour) use ($search) {
                        $tour->where('title', 'like', "%{$search}%");
                    });

            });
        }


        /*
        |--------------------------------------------------------------------------
        | Rating Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('rating')) {

            $rating = (int) $request->rating;

            if ($rating >= 1 && $rating <= 5) {
                $query->where('rating', $rating);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Approval Filter
        |--------------------------------------------------------------------------
        */

        if ($request->status === 'approved') {

            $query->where('is_approved', true);

        } elseif ($request->status === 'pending') {

            $query->where('is_approved', false);
        }


        /*
        |--------------------------------------------------------------------------
        | Reviews
        |--------------------------------------------------------------------------
        |
        | Pagination temporarily removed.
        | This keeps the page simple and prevents pagination-related loops.
        |
        */

        $reviews = $query
            ->orderByDesc('created_at')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $statsQuery = Review::query()
            ->whereHas('tour', function ($q) use ($vendor) {
                $q->where('vendor_id', $vendor->id);
            });


        $totalReviews = (clone $statsQuery)->count();

        $approvedReviews = (clone $statsQuery)
            ->where('is_approved', true)
            ->count();

        $pendingReviews = (clone $statsQuery)
            ->where('is_approved', false)
            ->count();

        $averageRating = (clone $statsQuery)->avg('rating');

        $averageRating = $averageRating
            ? round($averageRating, 1)
            : 0;


        /*
        |--------------------------------------------------------------------------
        | Rating Breakdown
        |--------------------------------------------------------------------------
        */

        $ratingCounts = [];

        for ($rating = 1; $rating <= 5; $rating++) {

            $ratingCounts[$rating] = (clone $statsQuery)
                ->where('rating', $rating)
                ->count();
        }


        return view('vendor.reviews.index', [
            'reviews' => $reviews,
            'totalReviews' => $totalReviews,
            'approvedReviews' => $approvedReviews,
            'pendingReviews' => $pendingReviews,
            'averageRating' => $averageRating,
            'ratingCounts' => $ratingCounts,
        ]);
    }


    /**
     * Show single review.
     */
    public function show($id)
    {
        $vendor = auth()->user()->vendor;

        if (!$vendor) {
            abort(403, 'Vendor profile not found.');
        }

        $review = Review::with([
                'user',
                'tour',
                'booking',
            ])
            ->whereHas('tour', function ($q) use ($vendor) {
                $q->where('vendor_id', $vendor->id);
            })
            ->findOrFail($id);

        return view(
            'vendor.reviews.show',
            compact('review')
        );
    }
}