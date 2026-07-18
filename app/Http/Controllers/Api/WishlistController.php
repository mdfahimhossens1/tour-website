<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Wishlist List
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $wishlists = Wishlist::with('tour')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $wishlists,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Add Wishlist
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tour_id' => ['required', 'exists:tours,id'],
        ]);

        $wishlist = Wishlist::firstOrCreate([
            'user_id' => $request->user()->id,
            'tour_id' => $validated['tour_id'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tour added to wishlist.',
            'data' => $wishlist,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Remove Wishlist
    |--------------------------------------------------------------------------
    */

    public function destroy(Request $request, $tourId)
    {
        Wishlist::where('user_id', $request->user()->id)
            ->where('tour_id', $tourId)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tour removed from wishlist.',
        ]);
    }
}