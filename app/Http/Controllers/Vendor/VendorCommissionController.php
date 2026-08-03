<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorCommissionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | COMMISSION DASHBOARD
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $vendor = Auth::user()->vendor;

        /*
        |--------------------------------------------------------------------------
        | Vendor Profile Check
        |--------------------------------------------------------------------------
        */

        abort_if(
            !$vendor,
            403,
            'Vendor profile not found.'
        );


        /*
        |--------------------------------------------------------------------------
        | Commission Query
        |--------------------------------------------------------------------------
        */

        $query = Commission::with([
            'booking.user',
            'booking.tour',
            'booking.tourDate',
        ])
        ->whereHas('booking', function ($bookingQuery) use ($vendor) {

            $bookingQuery->where(
                'vendor_id',
                $vendor->id
            );

        })
        ->latest();


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->whereHas('booking', function ($bookingQuery) use ($search) {

                $bookingQuery
                    ->where(
                        'booking_code',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhereHas('user', function ($userQuery) use ($search) {

                        $userQuery
                            ->where(
                                'name',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'email',
                                'like',
                                "%{$search}%"
                            );

                    })
                    ->orWhereHas('tour', function ($tourQuery) use ($search) {

                        $tourQuery->where(
                            'title',
                            'like',
                            "%{$search}%"
                        );

                    });

            });
        }


        /*
        |--------------------------------------------------------------------------
        | Paginated Commissions
        |--------------------------------------------------------------------------
        */

        $commissions = $query
            ->paginate(20)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $stats = [

            /*
            | Total Booking Count
            */

            'total_bookings' => Commission::whereHas(
                'booking',
                function ($bookingQuery) use ($vendor) {

                    $bookingQuery->where(
                        'vendor_id',
                        $vendor->id
                    );

                }
            )->count(),


            /*
            | Total Sales
            */

            'total_sales' => Commission::whereHas(
                'booking',
                function ($bookingQuery) use ($vendor) {

                    $bookingQuery->where(
                        'vendor_id',
                        $vendor->id
                    );

                }
            )->sum('total_amount'),


            /*
            | Vendor Earning
            */

            'vendor_earning' => Commission::whereHas(
                'booking',
                function ($bookingQuery) use ($vendor) {

                    $bookingQuery->where(
                        'vendor_id',
                        $vendor->id
                    );

                }
            )->sum('vendor_earning'),


            /*
            | Current Commission Rate
            */

            'commission_rate' => $vendor->commission_rate ?? 0,

        ];


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'vendor.commissions.index',
            compact(
                'commissions',
                'stats',
                'vendor'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW COMMISSION
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $vendor = Auth::user()->vendor;

        /*
        |--------------------------------------------------------------------------
        | Vendor Check
        |--------------------------------------------------------------------------
        */

        abort_if(
            !$vendor,
            403,
            'Vendor profile not found.'
        );


        /*
        |--------------------------------------------------------------------------
        | Get Only Own Commission
        |--------------------------------------------------------------------------
        */

        $commission = Commission::with([
            'booking.user',
            'booking.tour',
            'booking.tourDate',
            'booking.transaction',
        ])
        ->whereHas('booking', function ($bookingQuery) use ($vendor) {

            $bookingQuery->where(
                'vendor_id',
                $vendor->id
            );

        })
        ->findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'vendor.commissions.show',
            compact(
                'commission',
                'vendor'
            )
        );
    }
}
