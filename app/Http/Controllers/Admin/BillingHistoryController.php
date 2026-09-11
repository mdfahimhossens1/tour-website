<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BillingHistory;
use Illuminate\Http\Request;

class BillingHistoryController extends Controller
{
    /**
     * Display billing history.
     */
    public function index(Request $request)
    {
        $query = BillingHistory::query()
            ->with([
                'user',
                'subscription',
                'subscriptionPlan',
            ])
            ->latest('billing_date')
            ->latest('id');


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('invoice_number', 'like', "%{$search}%")

                    ->orWhere('transaction_id', 'like', "%{$search}%")

                    ->orWhere(
                        'gateway_invoice_id',
                        'like',
                        "%{$search}%"
                    )

                    ->orWhere(
                        'gateway_transaction_id',
                        'like',
                        "%{$search}%"
                    )

                    ->orWhere(
                        'payment_gateway',
                        'like',
                        "%{$search}%"
                    )

                    ->orWhereHas('user', function ($userQuery) use ($search) {

                        $userQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");

                    })

                    ->orWhereHas('subscriptionPlan', function ($planQuery) use ($search) {

                        $planQuery->where(
                            'name',
                            'like',
                            "%{$search}%"
                        );

                    })

                    ->orWhereHas('subscription', function ($subscriptionQuery) use ($search) {

                        $subscriptionQuery->where(
                            'subscription_code',
                            'like',
                            "%{$search}%"
                        );

                    });

            });

        }


        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Billing Cycle Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('billing_cycle')) {

            $query->where(
                'billing_cycle',
                $request->billing_cycle
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Payment Gateway Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('payment_gateway')) {

            $query->where(
                'payment_gateway',
                $request->payment_gateway
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Date From
        |--------------------------------------------------------------------------
        */

        if ($request->filled('date_from')) {

            $query->whereDate(
                'billing_date',
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
                'billing_date',
                '<=',
                $request->date_to
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $billingHistories = $query
            ->paginate(20)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $totalBillingRecords = BillingHistory::count();

        $paidBillingRecords = BillingHistory::where(
            'status',
            'paid'
        )->count();

        $pendingBillingRecords = BillingHistory::where(
            'status',
            'pending'
        )->count();

        $failedBillingRecords = BillingHistory::where(
            'status',
            'failed'
        )->count();

        /*
        |--------------------------------------------------------------------------
        | Revenue Statistics
        |--------------------------------------------------------------------------
        */

        $totalRevenue = BillingHistory::where(
            'status',
            'paid'
        )->sum('total_amount');

        $totalRefunded = BillingHistory::whereIn(
            'status',
            [
                'refunded',
                'partially_refunded',
            ]
        )->sum('refunded_amount');


        /*
        |--------------------------------------------------------------------------
        | Payment Gateways
        |--------------------------------------------------------------------------
        */

        $paymentGateways = BillingHistory::query()
            ->whereNotNull('payment_gateway')
            ->where('payment_gateway', '!=', '')
            ->distinct()
            ->orderBy('payment_gateway')
            ->pluck('payment_gateway');


        return view(
            'admin.billing-history.index',
            compact(
                'billingHistories',
                'totalBillingRecords',
                'paidBillingRecords',
                'pendingBillingRecords',
                'failedBillingRecords',
                'totalRevenue',
                'totalRefunded',
                'paymentGateways'
            )
        );
    }


    /**
     * Display billing details.
     */
    public function show(BillingHistory $billingHistory)
    {
        $billingHistory->load([
            'user',
            'subscription',
            'subscriptionPlan',
        ]);

        return view(
            'admin.billing-history.show',
            compact('billingHistory')
        );
    }
}