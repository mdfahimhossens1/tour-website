<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\TrialSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SubscribeController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | SUBSCRIPTION PLANS
    |--------------------------------------------------------------------------
    */

    public function plans()
    {
        $plans = SubscriptionPlan::query()
            ->where('status', 1)
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->get();

        return view(
            'frontend.subscription.plans',
            compact('plans')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CHECKOUT PAGE
    |--------------------------------------------------------------------------
    */

    public function checkout(
        SubscriptionPlan $subscriptionPlan,
        Request $request
    ) {
        /*
        |--------------------------------------------------------------------------
        | Billing Cycle
        |--------------------------------------------------------------------------
        */

        $billingCycle = $request->get(
            'billing_cycle',
            'monthly'
        );

        /*
        |--------------------------------------------------------------------------
        | Validate Cycle
        |--------------------------------------------------------------------------
        */

        if (
            !in_array(
                $billingCycle,
                [
                    'monthly',
                    'yearly',
                    'lifetime',
                ]
            )
        ) {
            abort(404);
        }

        return view(
            'frontend.subscription.checkout',
            compact(
                'subscriptionPlan',
                'billingCycle'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE SUBSCRIPTION
    |--------------------------------------------------------------------------
    */

    public function subscribe(Request $request)
    {
        $request->validate([
            'subscription_plan_id' =>
                'required|exists:subscription_plans,id',

            'billing_cycle' =>
                'required|in:monthly,yearly,lifetime',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Logged In User
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | Plan
        |--------------------------------------------------------------------------
        */

        $plan = SubscriptionPlan::findOrFail(
            $request->subscription_plan_id
        );


        /*
        |--------------------------------------------------------------------------
        | Plan Status
        |--------------------------------------------------------------------------
        */

        if (!$plan->status) {

            return back()->with(
                'error',
                'This subscription plan is currently unavailable.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Prevent Multiple Active Subscription
        |--------------------------------------------------------------------------
        */

        $existingSubscription = Subscription::where(
            'user_id',
            $user->id
        )
            ->whereIn(
                'status',
                [
                    'trialing',
                    'active',
                    'pending',
                ]
            )
            ->first();


        if ($existingSubscription) {

            return redirect()
                ->route(
                    'subscription.current'
                )
                ->with(
                    'error',
                    'You already have an active subscription.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Get Price
        |--------------------------------------------------------------------------
        */

        $price = match (
            $request->billing_cycle
        ) {

            'monthly' =>
                $plan->monthly_price ?? 0,

            'yearly' =>
                $plan->yearly_price ?? 0,

            'lifetime' =>
                $plan->lifetime_price ?? 0,

        };


        /*
        |--------------------------------------------------------------------------
        | Trial Settings
        |--------------------------------------------------------------------------
        */

        $trialSetting = TrialSetting::query()
            ->first();


        $trialDays = 0;


        if (
            $trialSetting &&
            $trialSetting->is_enabled
        ) {

            $trialDays =
                (int) (
                    $trialSetting->trial_days
                    ?? 0
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Create Subscription
        |--------------------------------------------------------------------------
        */

        $subscription = Subscription::create([

            'user_id' =>
                $user->id,

            'subscription_plan_id' =>
                $plan->id,

            'subscription_code' =>
                'SUB-' .
                strtoupper(
                    Str::random(10)
                ),

            'billing_cycle' =>
                $request->billing_cycle,

            'price' =>
                $price,

            'currency' =>
                $plan->currency
                ?? 'USD',

            'status' =>
                $trialDays > 0
                    ? 'trialing'
                    : 'pending',

            'is_trial' =>
                $trialDays > 0,

            'trial_starts_at' =>
                $trialDays > 0
                    ? now()
                    : null,

            'trial_ends_at' =>
                $trialDays > 0
                    ? now()->addDays(
                        $trialDays
                    )
                    : null,

            'starts_at' =>
                $trialDays > 0
                    ? now()
                    : null,

            'ends_at' =>
                null,

            'cancel_at_period_end' =>
                false,

        ]);


        /*
        |--------------------------------------------------------------------------
        | Trial Subscription
        |--------------------------------------------------------------------------
        */

        if ($trialDays > 0) {

            return redirect()
                ->route(
                    'subscription.current'
                )
                ->with(
                    'success',
                    'Your free trial has started successfully.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Payment
        |--------------------------------------------------------------------------
        |
        | আমরা পরবর্তী step-এ
        | Billing History + Payment Gateway
        | connect করব।
        |
        */

        return redirect()
            ->route(
                'subscription.current'
            )
            ->with(
                'success',
                'Subscription created successfully. Please complete payment.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | CURRENT SUBSCRIPTION
    |--------------------------------------------------------------------------
    */

    public function current()
    {
        $subscription = Subscription::with(
            'subscriptionPlan'
        )
            ->where(
                'user_id',
                Auth::id()
            )
            ->latest()
            ->first();

        return view(
            'frontend.subscription.current',
            compact('subscription')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SUBSCRIPTION HISTORY
    |--------------------------------------------------------------------------
    */

    public function history()
    {
        $subscriptions = Subscription::with(
            'subscriptionPlan'
        )
            ->where(
                'user_id',
                Auth::id()
            )
            ->latest()
            ->paginate(10);

        return view(
            'frontend.subscription.history',
            compact('subscriptions')
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
        /*
        |--------------------------------------------------------------------------
        | Security Check
        |--------------------------------------------------------------------------
        */

        if (
            $subscription->user_id
            !== Auth::id()
        ) {

            abort(403);
        }


        /*
        |--------------------------------------------------------------------------
        | Already Cancelled
        |--------------------------------------------------------------------------
        */

        if (
            $subscription->status
            === 'cancelled'
        ) {

            return back()->with(
                'error',
                'Subscription is already cancelled.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Cancel
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


        return redirect()
            ->route(
                'subscription.current'
            )
            ->with(
                'success',
                'Subscription cancelled successfully.'
            );
    }
}