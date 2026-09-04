<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RefundRequest;
use Illuminate\Http\Request;

class RefundController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | REFUNDS
    |--------------------------------------------------------------------------
    |
    | Finance refund history.
    |
    | Only completed refunds are displayed here.
    |
    */

    public function index(Request $request)
    {
        $query = RefundRequest::with([
            'booking.tour',
            'booking.tourDate',
            'booking.vendor',
            'user',
            'payment',
        ])
            ->where('status', 'completed')
            ->latest('processed_at');

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->whereHas('booking', function ($bookingQuery) use ($search) {

                    $bookingQuery
                        ->where(
                            'booking_code',
                            'like',
                            "%{$search}%"
                        );

                })
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
                        )
                        ->orWhere(
                            'phone',
                            'like',
                            "%{$search}%"
                        );

                })
                ->orWhereHas('payment', function ($paymentQuery) use ($search) {

                    $paymentQuery->where(
                        'trx_id',
                        'like',
                        "%{$search}%"
                    );

                });

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Date From
        |--------------------------------------------------------------------------
        */

        if ($request->filled('date_from')) {

            $query->whereDate(
                'processed_at',
                '>=',
                $request->date_from
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Date To
        |--------------------------------------------------------------------------
        */

        if ($request->filled('date_to')) {

            $query->whereDate(
                'processed_at',
                '<=',
                $request->date_to
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $refunds = $query
            ->paginate(20)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $totalRefundAmount = RefundRequest::where(
            'status',
            'completed'
        )->sum('refund_amount');

        $totalRefunds = RefundRequest::where(
            'status',
            'completed'
        )->count();

        $thisMonthRefunds = RefundRequest::where(
            'status',
            'completed'
        )
            ->whereMonth(
                'processed_at',
                now()->month
            )
            ->whereYear(
                'processed_at',
                now()->year
            )
            ->count();

        $thisMonthRefundAmount = RefundRequest::where(
            'status',
            'completed'
        )
            ->whereMonth(
                'processed_at',
                now()->month
            )
            ->whereYear(
                'processed_at',
                now()->year
            )
            ->sum('refund_amount');

        return view(
            'admin.refunds.index',
            compact(
                'refunds',
                'totalRefundAmount',
                'totalRefunds',
                'thisMonthRefunds',
                'thisMonthRefundAmount'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $refund = RefundRequest::with([
            'booking.tour',
            'booking.tourDate',
            'booking.vendor',
            'booking.travelers',
            'user',
            'payment',
        ])
            ->where('status', 'completed')
            ->findOrFail($id);

        return view(
            'admin.refunds.show',
            compact('refund')
        );
    }
}