<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ads;
use Illuminate\Http\Request;

class AdsController extends Controller
{
    /**
     * Get active advertisements
     *
     * Optional:
     * /api/ads?position=home_top
     */
    public function index(Request $request)
    {
        $query = Ads::query()
            ->where('status', 1)
            ->where(function ($query) {
                $query->whereNull('start_date')
                    ->orWhereDate('start_date', '<=', now()->toDateString());
            })
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhereDate('end_date', '>=', now()->toDateString());
            });

        // নির্দিষ্ট position চাইলে শুধু ওই position-এর ads return করবে
        if ($request->filled('position')) {
            $query->where('position', $request->position);
        }

        $ads = $query
            ->latest()
            ->get()
            ->map(function ($ad) {
                return [
                    'id' => $ad->id,
                    'title' => $ad->title,

                    // Frontend-এর জন্য সম্পূর্ণ Image URL
                    'image' => $ad->image
                        ? asset('uploads/ads/' . $ad->image)
                        : null,

                    'link' => $ad->link,
                    'position' => $ad->position,
                ];
            })
            ->values();

        return response()->json([
            'success' => true,
            'data' => $ads,
        ]);
    }

    /**
     * Record Advertisement View
     */
    public function view($id)
    {
        $ad = Ads::find($id);

        if (!$ad) {
            return response()->json([
                'success' => false,
                'message' => 'Advertisement not found.',
            ], 404);
        }

        $ad->increment('views');

        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * Record Advertisement Click
     */
    public function click($id)
    {
        $ad = Ads::find($id);

        if (!$ad) {
            return response()->json([
                'success' => false,
                'message' => 'Advertisement not found.',
            ], 404);
        }

        $ad->increment('clicks');

        return response()->json([
            'success' => true,
        ]);
    }
}