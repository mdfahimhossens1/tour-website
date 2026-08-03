<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use Illuminate\Http\Request;

class CommissionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | COMMISSION LIST
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Get Commissions
        |--------------------------------------------------------------------------
        */

        $query = Commission::with([
            'booking.user',
            'booking.vendor',
            'booking.tour',
        ])->latest();


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->whereHas('booking', function ($bookingQuery) use ($search) {

                $bookingQuery
                    ->where('booking_code', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {

                        $userQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");

                    })
                    ->orWhereHas('vendor', function ($vendorQuery) use ($search) {

                        $vendorQuery->where(
                            'business_name',
                            'like',
                            "%{$search}%"
                        );

                    });

            });
        }


        /*
        |--------------------------------------------------------------------------
        | Commission List
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

            'total_sales' => Commission::sum('total_amount'),

            'admin_earning' => Commission::sum('admin_earning'),

            'vendor_earning' => Commission::sum('vendor_earning'),

            'total_commissions' => Commission::count(),

        ];


        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.commissions.index',
            compact(
                'commissions',
                'stats'
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
        $commission = Commission::with([
            'booking.user',
            'booking.vendor',
            'booking.tour',
            'booking.tourDate',
            'booking.transaction',
        ])->findOrFail($id);


        return view(
            'admin.commissions.show',
            compact('commission')
        );
    }
}
