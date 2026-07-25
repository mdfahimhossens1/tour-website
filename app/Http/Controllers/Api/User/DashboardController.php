<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;
use Carbon\Carbon;

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
            | Dashboard Stats
            |--------------------------------------------------------------------------
            */

            $totalBookings = Booking::where('user_id', $user->id)->count();

            $pendingBookings = Booking::where('user_id', $user->id)
                ->where('booking_status', 'pending')
                ->count();

            $completedTours = Booking::where('user_id', $user->id)
                ->where('booking_status', 'completed')
                ->count();

            $upcomingTours = Booking::where('user_id', $user->id)
                ->whereHas('tourDate', function ($query) {
                    $query->whereDate('start_date', '>=', Carbon::today());
                })
                ->count();

            /*
            |--------------------------------------------------------------------------
            | Recent Bookings
            |--------------------------------------------------------------------------
            */

            $recentBookings = Booking::with([
                'tour',
                'tourDate',
                'payment',
            ])
                ->where('user_id', $user->id)
                ->latest()
                ->take(5)
                ->get();

            /*
            |--------------------------------------------------------------------------
            | Notifications
            |--------------------------------------------------------------------------
            */

            $notifications = DatabaseNotification::where(
                'notifiable_id',
                $user->id
            )
                ->latest()
                ->take(5)
                ->get();

            return response()->json([

                'success' => true,

                'stats' => [

                    'totalBookings'   => $totalBookings,
                    'pendingBookings' => $pendingBookings,
                    'completedTours'  => $completedTours,
                    'upcomingTours'   => $upcomingTours,

                    // frontend এর জন্য
                    'wishlist' => 0,
                    'rewardPoints' => 0,

                ],

                // frontend এর জন্য
                'wallet' => [
                    'balance' => 0,
                ],

                'recent_bookings' => $recentBookings,

                'notifications' => $notifications,

            ]);
        } catch (\Throwable $e) {

            return response()->json([

                'success' => false,

                'message' => $e->getMessage(),

                'file' => $e->getFile(),

                'line' => $e->getLine(),

                'trace' => collect($e->getTrace())
                    ->take(5),

            ], 500);
        }
    }
}