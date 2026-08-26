<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        try {

            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated'
                ], 401);
            }

            /*
            |--------------------------------------------------------------------------
            | Booking Statistics
            |--------------------------------------------------------------------------
            */

            $totalBookings = Booking::where(
                'user_id',
                $user->id
            )->count();

            $pendingBookings = Booking::where(
                'user_id',
                $user->id
            )
                ->where('booking_status', 'pending')
                ->count();

            $completedTours = Booking::where(
                'user_id',
                $user->id
            )
                ->where('booking_status', 'completed')
                ->count();

            $upcomingTours = Booking::where(
                'user_id',
                $user->id
            )
                ->whereHas('tourDate', function ($query) {
                    $query->whereDate(
                        'start_date',
                        '>=',
                        Carbon::today()
                    );
                })
                ->count();

            /*
            |--------------------------------------------------------------------------
            | Wishlist
            |--------------------------------------------------------------------------
            */

            $wishlistCount = $user->wishlists()->count();

            /*
            |--------------------------------------------------------------------------
            | Wallet
            |--------------------------------------------------------------------------
            */

            $wallet = $user->wallet;

            /*
            |--------------------------------------------------------------------------
            | Recent Bookings
            |--------------------------------------------------------------------------
            */

            $recentBookings = Booking::with([
                'tour',
                'tourDate',
                'payments',
            ])
                ->where('user_id', $user->id)
                ->latest()
                ->take(5)
                ->get();

            /*
            |--------------------------------------------------------------------------
            | Response
            |--------------------------------------------------------------------------
            */

            return response()->json([
                'success' => true,

                'stats' => [
                    'totalBookings' => $totalBookings,
                    'pendingBookings' => $pendingBookings,
                    'completedTours' => $completedTours,
                    'upcomingTours' => $upcomingTours,
                    'wishlist' => $wishlistCount,
                    'rewardPoints' => 0,
                ],

                'wallet' => [
                    'balance' => $wallet?->balance ?? 0,
                ],

                'recent_bookings' => $recentBookings,

                'notifications' => [],
            ]);

        } catch (\Throwable $e) {

            \Log::error('Dashboard Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to load dashboard. Please try again later.'
            ], 500);
        }
    }
}