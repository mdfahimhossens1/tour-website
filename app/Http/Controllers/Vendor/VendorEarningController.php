<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Commission;

class VendorEarningController extends Controller
{
    public function index()
    {
        $vendor = auth()->user()->vendor;

        $commissions = Commission::with([
            'booking.tour'
        ])
        ->whereHas('booking.tour', function ($q) use ($vendor) {
            $q->where('vendor_id', $vendor->id);
        })
        ->latest()
        ->paginate(20);

        $totalEarned = $commissions->sum('vendor_earning');

        return view(
            'vendor.earnings.index',
            compact(
                'commissions',
                'totalEarned'
            )
        );
    }
}