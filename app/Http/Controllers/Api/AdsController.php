<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ads;
use Illuminate\Http\Request;

class AdsController extends Controller
{
    /**
     * Active Advertisement List
     */
    public function index(Request $request)
    {
        $query = $this->activeAdsQuery();

        // Position Filter
        if ($request->filled('position')) {
            $query->where('position', $request->string('position')->toString());
        }

        // Title Search
        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where('title', 'like', "%{$search}%");
        }

        $query->latest();

        // Optional Pagination
        if ($request->boolean('paginate')) {
            $perPage = min(
                max((int) $request->input('per_page', 10), 1),
                50
            );

            $ads = $query
                ->paginate($perPage)
                ->through(fn ($ad) => $this->formatAd($ad));

            return response()->json([
                'success' => true,
                'data' => $ads->items(),
                'meta' => [
                    'current_page' => $ads->currentPage(),
                    'last_page' => $ads->lastPage(),
                    'per_page' => $ads->perPage(),
                    'total' => $ads->total(),
                ],
            ]);
        }

        $ads = $query
            ->get()
            ->map(fn ($ad) => $this->formatAd($ad))
            ->values();

        return response()->json([
            'success' => true,
            'data' => $ads,
        ]);
    }

    /**
     * Advertisement Details
     */
    public function show($id)
    {
        $ad = $this->activeAdsQuery()->find($id);

        if (!$ad) {
            return response()->json([
                'success' => false,
                'message' => 'Advertisement not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $this->formatAd($ad),
        ]);
    }

    /**
     * Record Advertisement View
     */
    public function view($id)
    {
        $ad = $this->activeAdsQuery()->find($id);

        if (!$ad) {
            return response()->json([
                'success' => false,
                'message' => 'Advertisement not found.',
            ], 404);
        }

        $ad->increment('views');

        return response()->json([
            'success' => true,
            'message' => 'Advertisement view recorded successfully.',
        ]);
    }

    /**
     * Record Advertisement Click
     */
    public function click($id)
    {
        $ad = $this->activeAdsQuery()->find($id);

        if (!$ad) {
            return response()->json([
                'success' => false,
                'message' => 'Advertisement not found.',
            ], 404);
        }

        $ad->increment('clicks');

        return response()->json([
            'success' => true,
            'message' => 'Advertisement click recorded successfully.',
        ]);
    }

    /**
     * Active and Currently Valid Advertisements Query
     */
    private function activeAdsQuery()
    {
        return Ads::query()
            ->where('status', true)
            ->where(function ($query) {
                $query->whereNull('start_date')
                    ->orWhereDate('start_date', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('end_date')
                    ->orWhereDate('end_date', '>=', now());
            });
    }

    /**
     * Format Advertisement Data
     */
    private function formatAd(Ads $ad): array
    {
        return [
            'id' => $ad->id,
            'title' => $ad->title,
            'image' => $ad->image
                ? asset('uploads/ads/' . $ad->image)
                : null,
            'link' => $ad->link,
            'position' => $ad->position,
        ];
    }
}