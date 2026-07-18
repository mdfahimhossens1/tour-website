<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;

class UserBookingController extends Controller
{
    /**
     * Logged User Bookings
     */
    public function index(Request $request)
    {
        $bookings = Booking::with([
            'tour',
            'tourDate',
            'payment',
        ])
        ->where('user_id', $request->user()->id)
        ->latest()
        ->get();

        return response()->json([
            'success' => true,
            'data' => $bookings,
        ]);
    }
}