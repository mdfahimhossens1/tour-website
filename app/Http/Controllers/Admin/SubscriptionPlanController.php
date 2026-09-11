<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SubscriptionPlanController extends Controller
{
    /**
     * ---------------------------------------------------------
     * Display all subscription plans
     * ---------------------------------------------------------
     */
    public function index(Request $request): View
    {
        $query = SubscriptionPlan::query();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('currency', 'like', "%{$search}%");
            });
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
        | Status Filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('status')) {

            if ($request->status === 'active') {
                $query->where('is_active', true);
            }

            if ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Featured Filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('featured')) {

            if ($request->featured === 'yes') {
                $query->where('is_featured', true);
            }

            if ($request->featured === 'no') {
                $query->where('is_featured', false);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Plans
        |--------------------------------------------------------------------------
        */
        $plans = $query
            ->ordered()
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */
        $stats = [
            'total' => SubscriptionPlan::count(),

            'active' => SubscriptionPlan::where(
                'is_active',
                true
            )->count(),

            'inactive' => SubscriptionPlan::where(
                'is_active',
                false
            )->count(),

            'featured' => SubscriptionPlan::where(
                'is_featured',
                true
            )->count(),

            'monthly' => SubscriptionPlan::where(
                'billing_cycle',
                'monthly'
            )->count(),

            'yearly' => SubscriptionPlan::where(
                'billing_cycle',
                'yearly'
            )->count(),

            'lifetime' => SubscriptionPlan::where(
                'billing_cycle',
                'lifetime'
            )->count(),
        ];

        return view(
            'admin.saas.subscription-plans.index',
            compact('plans', 'stats')
        );
    }

    /**
     * ---------------------------------------------------------
     * Show create form
     * ---------------------------------------------------------
     */
    public function create(): View
    {
        return view(
            'admin.saas.subscription-plans.create'
        );
    }

    /**
     * ---------------------------------------------------------
     * Store new subscription plan
     * ---------------------------------------------------------
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'slug' => [
                'nullable',
                'string',
                'max:255',
                'unique:subscription_plans,slug',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'billing_cycle' => [
                'required',
                'in:monthly,yearly,lifetime',
            ],

            'currency' => [
                'required',
                'string',
                'max:10',
            ],

            'max_vendors' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'max_tours' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'max_bookings' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'max_storage_mb' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'is_featured' => [
                'nullable',
                'boolean',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Generate Slug
        |--------------------------------------------------------------------------
        */
        $slug = $validated['slug']
            ?? Str::slug($validated['name']);

        /*
        |--------------------------------------------------------------------------
        | Ensure Unique Slug
        |--------------------------------------------------------------------------
        */
        $originalSlug = $slug;
        $counter = 1;

        while (
            SubscriptionPlan::where('slug', $slug)->exists()
        ) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        /*
        |--------------------------------------------------------------------------
        | Create Plan
        |--------------------------------------------------------------------------
        */
        SubscriptionPlan::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,

            'price' => $validated['price'],

            'billing_cycle' => $validated['billing_cycle'],

            'currency' => strtoupper(
                $validated['currency']
            ),

            'max_vendors' => $validated['max_vendors'] ?? null,
            'max_tours' => $validated['max_tours'] ?? null,
            'max_bookings' => $validated['max_bookings'] ?? null,
            'max_storage_mb' => $validated['max_storage_mb'] ?? null,

            'is_featured' => $request->boolean(
                'is_featured'
            ),

            'is_active' => $request->boolean(
                'is_active',
                true
            ),

            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()
            ->route('admin.subscription-plans.index')
            ->with(
                'success',
                'Subscription plan created successfully.'
            );
    }

    /**
     * ---------------------------------------------------------
     * Display a single plan
     * ---------------------------------------------------------
     */
    public function show(
        SubscriptionPlan $subscriptionPlan
    ): View {
        return view(
            'admin.saas.subscription-plans.show',
            compact('subscriptionPlan')
        );
    }

    /**
     * ---------------------------------------------------------
     * Show edit form
     * ---------------------------------------------------------
     */
    public function edit(
        SubscriptionPlan $subscriptionPlan
    ): View {
        return view(
            'admin.saas.subscription-plans.edit',
            compact('subscriptionPlan')
        );
    }

    /**
     * ---------------------------------------------------------
     * Update subscription plan
     * ---------------------------------------------------------
     */
    public function update(
        Request $request,
        SubscriptionPlan $subscriptionPlan
    ): RedirectResponse {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'slug' => [
                'nullable',
                'string',
                'max:255',
                'unique:subscription_plans,slug,'
                    . $subscriptionPlan->id,
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'billing_cycle' => [
                'required',
                'in:monthly,yearly,lifetime',
            ],

            'currency' => [
                'required',
                'string',
                'max:10',
            ],

            'max_vendors' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'max_tours' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'max_bookings' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'max_storage_mb' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'is_featured' => [
                'nullable',
                'boolean',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Generate / Update Slug
        |--------------------------------------------------------------------------
        */
        $slug = $validated['slug']
            ?? Str::slug($validated['name']);

        $originalSlug = $slug;
        $counter = 1;

        while (
            SubscriptionPlan::where('slug', $slug)
                ->where(
                    'id',
                    '!=',
                    $subscriptionPlan->id
                )
                ->exists()
        ) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */
        $subscriptionPlan->update([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,

            'price' => $validated['price'],

            'billing_cycle' => $validated['billing_cycle'],

            'currency' => strtoupper(
                $validated['currency']
            ),

            'max_vendors' => $validated['max_vendors'] ?? null,
            'max_tours' => $validated['max_tours'] ?? null,
            'max_bookings' => $validated['max_bookings'] ?? null,
            'max_storage_mb' => $validated['max_storage_mb'] ?? null,

            'is_featured' => $request->boolean(
                'is_featured'
            ),

            'is_active' => $request->boolean(
                'is_active'
            ),

            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()
            ->route('admin.subscription-plans.index')
            ->with(
                'success',
                'Subscription plan updated successfully.'
            );
    }

    /**
     * ---------------------------------------------------------
     * Delete subscription plan
     * ---------------------------------------------------------
     */
    public function destroy(
        SubscriptionPlan $subscriptionPlan
    ): RedirectResponse {
        /*
        |--------------------------------------------------------------------------
        | Prevent deletion if subscriptions exist
        |--------------------------------------------------------------------------
        */
        if (
            $subscriptionPlan
                ->subscriptions()
                ->exists()
        ) {
            return back()->with(
                'error',
                'This plan cannot be deleted because it has existing subscriptions.'
            );
        }

        $subscriptionPlan->delete();

        return redirect()
            ->route('admin.subscription-plans.index')
            ->with(
                'success',
                'Subscription plan deleted successfully.'
            );
    }

    /**
     * ---------------------------------------------------------
     * Toggle Active Status
     * ---------------------------------------------------------
     */
    public function toggleStatus(
        SubscriptionPlan $subscriptionPlan
    ): RedirectResponse {
        $subscriptionPlan->update([
            'is_active' => ! $subscriptionPlan->is_active,
        ]);

        return back()->with(
            'success',
            'Subscription plan status updated successfully.'
        );
    }

    /**
     * ---------------------------------------------------------
     * Toggle Featured Status
     * ---------------------------------------------------------
     */
    public function toggleFeatured(
        SubscriptionPlan $subscriptionPlan
    ): RedirectResponse {
        $subscriptionPlan->update([
            'is_featured' => ! $subscriptionPlan->is_featured,
        ]);

        return back()->with(
            'success',
            'Subscription plan featured status updated successfully.'
        );
    }
}