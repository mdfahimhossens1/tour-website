<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | SUBSCRIPTIONS INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = Subscription::query()
            ->with([
                'user',
                'subscriptionPlan',
            ])
            ->latest();


        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim(
                $request->search
            );

            $query->where(function ($q) use ($search) {

                $q->where(
                    'subscription_code',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'payment_gateway',
                    'like',
                    "%{$search}%"
                )

                ->orWhere(
                    'gateway_subscription_id',
                    'like',
                    "%{$search}%"
                )

                ->orWhereHas(
                    'user',
                    function ($userQuery) use ($search) {

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
                    }
                )

                ->orWhereHas(
                    'subscriptionPlan',
                    function ($planQuery) use ($search) {

                        $planQuery
                            ->where(
                                'name',
                                'like',
                                "%{$search}%"
                            );
                    }
                );
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
        | Trial Filter
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled('trial')
            &&
            in_array(
                $request->trial,
                ['yes', 'no']
            )
        ) {

            $query->where(
                'is_trial',
                $request->trial === 'yes'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Date From
        |--------------------------------------------------------------------------
        */

        if ($request->filled('date_from')) {

            $query->whereDate(
                'created_at',
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
                'created_at',
                '<=',
                $request->date_to
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $subscriptions = $query
            ->paginate(20)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $totalSubscriptions =
            Subscription::count();


        $activeSubscriptions =
            Subscription::where(
                'status',
                'active'
            )->count();


        $trialSubscriptions =
            Subscription::where(
                'status',
                'trialing'
            )->count();


        $cancelledSubscriptions =
            Subscription::where(
                'status',
                'cancelled'
            )->count();


        /*
        |--------------------------------------------------------------------------
        | View
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.subscriptions.index',
            compact(
                'subscriptions',
                'totalSubscriptions',
                'activeSubscriptions',
                'trialSubscriptions',
                'cancelledSubscriptions'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW SUBSCRIPTION
    |--------------------------------------------------------------------------
    */

    public function show(
        Subscription $subscription
    ) {
        $subscription->load([

            'user',

            'subscriptionPlan',

        ]);


        return view(
            'admin.subscriptions.show',
            compact(
                'subscription'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ACTIVATE SUBSCRIPTION
    |--------------------------------------------------------------------------
    */

    public function activate(
        Subscription $subscription
    ) {
        return DB::transaction(
            function () use ($subscription) {


                /*
                |--------------------------------------------------------------------------
                | Prevent activating cancelled subscription
                |--------------------------------------------------------------------------
                */

                if (
                    $subscription->status === 'cancelled'
                ) {

                    return back()->with(
                        'error',
                        'Cancelled subscription cannot be activated.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Activate Subscription
                |--------------------------------------------------------------------------
                */

                $subscription->update([

                    'status' =>
                        'active',

                    'starts_at' =>
                        $subscription->starts_at
                        ?? now(),

                    'cancel_at_period_end' =>
                        false,

                    'cancelled_at' =>
                        null,

                    'cancellation_reason' =>
                        null,

                ]);


                return back()->with(
                    'success',
                    'Subscription activated successfully.'
                );
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SUSPEND SUBSCRIPTION
    |--------------------------------------------------------------------------
    */

    public function suspend(
        Subscription $subscription
    ) {
        return DB::transaction(
            function () use ($subscription) {


                /*
                |--------------------------------------------------------------------------
                | Cancelled subscription cannot be suspended
                |--------------------------------------------------------------------------
                */

                if (
                    $subscription->status === 'cancelled'
                ) {

                    return back()->with(
                        'error',
                        'Cancelled subscription cannot be suspended.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Suspend
                |--------------------------------------------------------------------------
                */

                $subscription->update([

                    'status' =>
                        'suspended',

                ]);


                return back()->with(
                    'success',
                    'Subscription suspended successfully.'
                );
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CANCEL SUBSCRIPTION
    |--------------------------------------------------------------------------
    */

    public function cancel(
        Subscription $subscription
    ) {
        return DB::transaction(
            function () use ($subscription) {


                /*
                |--------------------------------------------------------------------------
                | Already Cancelled
                |--------------------------------------------------------------------------
                */

                if (
                    $subscription->status === 'cancelled'
                ) {

                    return back()->with(
                        'error',
                        'Subscription is already cancelled.'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | Cancel Subscription
                |--------------------------------------------------------------------------
                */

                $subscription->update([

                    'status' =>
                        'cancelled',

                    'cancelled_at' =>
                        now(),

                    'cancel_at_period_end' =>
                        false,

                ]);


                return back()->with(
                    'success',
                    'Subscription cancelled successfully.'
                );
            }
        );
    }
}