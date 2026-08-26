<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\ResortBooking;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class VendorReportController extends Controller
{
    /**
     * Vendor booking & earning report.
     */
    public function index(Request $request)
    {
        $vendor = Auth::user()->vendor;

        abort_if(
            !$vendor,
            403,
            'Vendor profile not found.'
        );

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'date_from' => [
                'nullable',
                'date',
            ],

            'date_to' => [
                'nullable',
                'date',
                'after_or_equal:date_from',
            ],

            'booking_status' => [
                'nullable',
                Rule::in([
                    'pending',
                    'confirmed',
                    'checked_in',
                    'checked_out',
                    'cancelled',
                ]),
            ],

            'payment_status' => [
                'nullable',
                Rule::in([
                    'pending',
                    'paid',
                    'failed',
                    'refunded',
                ]),
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Base Query
        |--------------------------------------------------------------------------
        */

        $query = ResortBooking::query()
            ->with([
                'user',
                'resort',
                'room',
            ])
            ->where(
                'vendor_id',
                $vendor->id
            );


        /*
        |--------------------------------------------------------------------------
        | Date Filter
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['date_from'])) {

            $query->whereDate(
                'created_at',
                '>=',
                $validated['date_from']
            );
        }


        if (!empty($validated['date_to'])) {

            $query->whereDate(
                'created_at',
                '<=',
                $validated['date_to']
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Booking Status
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['booking_status'])) {

            $query->where(
                'booking_status',
                $validated['booking_status']
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Payment Status
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['payment_status'])) {

            $query->where(
                'payment_status',
                $validated['payment_status']
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Paginated Bookings
        |--------------------------------------------------------------------------
        */

        $bookings = $query
            ->latest()
            ->paginate(20)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Statistics Query
        |--------------------------------------------------------------------------
        */

        $statsQuery = ResortBooking::query()
            ->where(
                'vendor_id',
                $vendor->id
            );


        if (!empty($validated['date_from'])) {

            $statsQuery->whereDate(
                'created_at',
                '>=',
                $validated['date_from']
            );
        }


        if (!empty($validated['date_to'])) {

            $statsQuery->whereDate(
                'created_at',
                '<=',
                $validated['date_to']
            );
        }


        if (!empty($validated['booking_status'])) {

            $statsQuery->where(
                'booking_status',
                $validated['booking_status']
            );
        }


        if (!empty($validated['payment_status'])) {

            $statsQuery->where(
                'payment_status',
                $validated['payment_status']
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $totalBookings = (clone $statsQuery)
            ->count();


        $totalRevenue = (clone $statsQuery)
            ->sum('total_amount');


        $totalCommission = (clone $statsQuery)
            ->sum('admin_commission');


        $totalVendorEarning = (clone $statsQuery)
            ->sum('vendor_earning');


        $totalCancelled = (clone $statsQuery)
            ->where(
                'booking_status',
                'cancelled'
            )
            ->count();


        $totalConfirmed = (clone $statsQuery)
            ->where(
                'booking_status',
                'confirmed'
            )
            ->count();


        $totalCheckedOut = (clone $statsQuery)
            ->where(
                'booking_status',
                'checked_out'
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Completed Wallet Earnings
        |--------------------------------------------------------------------------
        */

        $releasedEarning = WalletTransaction::query()
            ->where(
                'vendor_id',
                $vendor->id
            )
            ->where(
                'type',
                'credit'
            )
            ->where(
                'status',
                'completed'
            )
            ->sum('amount');


        /*
        |--------------------------------------------------------------------------
        | Pending Wallet Earnings
        |--------------------------------------------------------------------------
        */

        $pendingEarning = WalletTransaction::query()
            ->where(
                'vendor_id',
                $vendor->id
            )
            ->where(
                'type',
                'credit'
            )
            ->where(
                'status',
                'pending'
            )
            ->sum('amount');


        return view(
            'vendor.reports.index',
            compact(
                'bookings',
                'totalBookings',
                'totalRevenue',
                'totalCommission',
                'totalVendorEarning',
                'totalCancelled',
                'totalConfirmed',
                'totalCheckedOut',
                'releasedEarning',
                'pendingEarning'
            )
        );
    }
}