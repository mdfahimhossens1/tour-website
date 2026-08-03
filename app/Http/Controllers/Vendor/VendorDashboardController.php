<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Resort;
use App\Models\Room;
use App\Models\ResortBooking;
use Illuminate\Support\Facades\Auth;

class VendorDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Logged-in user's vendor profile
        $vendor = $user->vendor;

        // If user has no vendor profile
        if (!$vendor) {
            abort(403, 'Vendor profile not found.');
        }

        /*
        |--------------------------------------------------------------------------
        | Vendor Resorts
        |--------------------------------------------------------------------------
        */

        $resorts = Resort::where('vendor_id', $vendor->id)->get();

        $totalResorts = $resorts->count();


        /*
        |--------------------------------------------------------------------------
        | Total Rooms
        |--------------------------------------------------------------------------
        */

        $totalRooms = Room::whereIn(
            'resort_id',
            $resorts->pluck('id')
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Resort Bookings
        |--------------------------------------------------------------------------
        */

        $totalBookings = ResortBooking::whereIn(
            'resort_id',
            $resorts->pluck('id')
        )->count();


        /*
        |--------------------------------------------------------------------------
        | Earnings
        |--------------------------------------------------------------------------
        */

        $totalEarnings = ResortBooking::whereIn(
            'resort_id',
            $resorts->pluck('id')
        )
        ->whereIn('booking_status', [
            'confirmed',
            'completed',
        ])
        ->sum('total_amount');


        /*
        |--------------------------------------------------------------------------
        | Recent Bookings
        |--------------------------------------------------------------------------
        */

        $recentBookings = ResortBooking::whereIn(
            'resort_id',
            $resorts->pluck('id')
        )
        ->latest()
        ->take(5)
        ->get();


        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        return view('vendor.dashboard.index', compact(
            'vendor',
            'resorts',
            'totalResorts',
            'totalRooms',
            'totalBookings',
            'totalEarnings',
            'recentBookings'
        ));
    }
}