<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Models\Booking;

class VendorDashboardController extends Controller
{
    public function index()
    {
        $vendor = auth()->user()->vendor;

        if (!$vendor) {
            abort(403, 'Vendor profile not found.');
        }
        
        $totalTours = Tour::where(
            'vendor_id',
            $vendor->id
        )->count();

        $totalBookings = Booking::whereHas('tour', function ($q) use ($vendor) {

            $q->where('vendor_id', $vendor->id);

        })->count();

        return view(
            'vendor.dashboard.index',
            compact(
                'totalTours',
                'totalBookings'
            )
        );
    }
}