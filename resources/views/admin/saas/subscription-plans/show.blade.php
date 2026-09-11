@extends('layouts.admin')

@section('title', 'Subscription Plan Details')

@section('page')

<div class="container-fluid">

    {{-- ============================================================
         PAGE HEADER
    ============================================================ --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="fas fa-layer-group me-2"></i>
                Subscription Plan Details
            </h4>

            <p class="text-muted mb-0">
                View complete information about this subscription plan.
            </p>
        </div>

        <div class="d-flex gap-2 mt-2 mt-md-0">

            <a href="{{ route('admin.subscription-plans.index') }}"
               class="btn btn-light border">
                <i class="fas fa-arrow-left me-1"></i>
                Back
            </a>

            <a href="{{ route('admin.subscription-plans.edit', $subscriptionPlan) }}"
               class="btn btn-primary">
                <i class="fas fa-edit me-1"></i>
                Edit Plan
            </a>

        </div>

    </div>


    {{-- ============================================================
         PLAN HEADER CARD
    ============================================================ --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body p-4">

            <div class="row align-items-center">

                {{-- PLAN INFO --}}
                <div class="col-lg-8">

                    <div class="d-flex align-items-center flex-wrap gap-2 mb-2">

                        <h3 class="mb-0 fw-bold">
                            {{ $subscriptionPlan->name }}
                        </h3>

                        @if($subscriptionPlan->is_active)
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle me-1"></i>
                                Active
                            </span>
                        @else
                            <span class="badge bg-secondary">
                                <i class="fas fa-ban me-1"></i>
                                Inactive
                            </span>
                        @endif

                        @if($subscriptionPlan->is_featured)
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-star me-1"></i>
                                Featured
                            </span>
                        @endif

                    </div>

                    <div class="text-muted mb-3">
                        <i class="fas fa-link me-1"></i>
                        {{ $subscriptionPlan->slug }}
                    </div>

                    @if($subscriptionPlan->description)
                        <p class="text-muted mb-0">
                            {{ $subscriptionPlan->description }}
                        </p>
                    @else
                        <p class="text-muted fst-italic mb-0">
                            No description available.
                        </p>
                    @endif

                </div>


                {{-- PRICE --}}
                <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">

                    <div class="text-muted small mb-1">
                        Subscription Price
                    </div>

                    <div class="display-6 fw-bold">
                        {{ $subscriptionPlan->currency }}
                        {{ number_format((float) $subscriptionPlan->price, 2) }}
                    </div>

                    <div class="text-muted mt-1">

                        @if($subscriptionPlan->billing_cycle === 'monthly')
                            <i class="fas fa-calendar-alt me-1"></i>
                            Monthly
                        @elseif($subscriptionPlan->billing_cycle === 'yearly')
                            <i class="fas fa-calendar-check me-1"></i>
                            Yearly
                        @else
                            <i class="fas fa-infinity me-1"></i>
                            Lifetime
                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    <div class="row">

        {{-- ========================================================
             PRICING INFORMATION
        ========================================================= --}}
        <div class="col-lg-6 mb-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="mb-0">
                        <i class="fas fa-dollar-sign text-success me-2"></i>
                        Pricing Information
                    </h5>

                </div>

                <div class="card-body">

                    <div class="row g-3">

                        <div class="col-sm-6">

                            <div class="border rounded p-3 h-100">

                                <div class="text-muted small mb-1">
                                    Price
                                </div>

                                <div class="fw-bold fs-5">
                                    {{ $subscriptionPlan->currency }}
                                    {{ number_format((float) $subscriptionPlan->price, 2) }}
                                </div>

                            </div>

                        </div>


                        <div class="col-sm-6">

                            <div class="border rounded p-3 h-100">

                                <div class="text-muted small mb-1">
                                    Currency
                                </div>

                                <div class="fw-bold fs-5">
                                    {{ $subscriptionPlan->currency }}
                                </div>

                            </div>

                        </div>


                        <div class="col-sm-6">

                            <div class="border rounded p-3 h-100">

                                <div class="text-muted small mb-1">
                                    Billing Cycle
                                </div>

                                <div class="fw-bold">

                                    @if($subscriptionPlan->billing_cycle === 'monthly')
                                        Monthly
                                    @elseif($subscriptionPlan->billing_cycle === 'yearly')
                                        Yearly
                                    @else
                                        Lifetime
                                    @endif

                                </div>

                            </div>

                        </div>


                        <div class="col-sm-6">

                            <div class="border rounded p-3 h-100">

                                <div class="text-muted small mb-1">
                                    Sort Order
                                </div>

                                <div class="fw-bold">
                                    {{ $subscriptionPlan->sort_order }}
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================
             PLAN STATUS
        ========================================================= --}}
        <div class="col-lg-6 mb-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="mb-0">
                        <i class="fas fa-toggle-on text-primary me-2"></i>
                        Plan Status
                    </h5>

                </div>

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center border-bottom py-3">

                        <span class="text-muted">
                            Plan Status
                        </span>

                        @if($subscriptionPlan->is_active)
                            <span class="badge bg-success">
                                Active
                            </span>
                        @else
                            <span class="badge bg-secondary">
                                Inactive
                            </span>
                        @endif

                    </div>


                    <div class="d-flex justify-content-between align-items-center border-bottom py-3">

                        <span class="text-muted">
                            Featured Plan
                        </span>

                        @if($subscriptionPlan->is_featured)
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-star me-1"></i>
                                Yes
                            </span>
                        @else
                            <span class="badge bg-light text-dark border">
                                No
                            </span>
                        @endif

                    </div>


                    <div class="d-flex justify-content-between align-items-center py-3">

                        <span class="text-muted">
                            Plan ID
                        </span>

                        <strong>
                            #{{ $subscriptionPlan->id }}
                        </strong>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================
             USAGE LIMITS
        ========================================================= --}}
        <div class="col-12 mb-4">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white border-0 py-3">

                    <div class="d-flex justify-content-between align-items-center">

                        <h5 class="mb-0">
                            <i class="fas fa-sliders-h text-info me-2"></i>
                            Usage Limits
                        </h5>

                        <span class="badge bg-light text-dark border">
                            Plan Limits
                        </span>

                    </div>

                </div>


                <div class="card-body">

                    <div class="row g-3">

                        {{-- VENDORS --}}
                        <div class="col-md-6 col-xl-3">

                            <div class="border rounded p-4 text-center h-100">

                                <div class="mb-3">
                                    <i class="fas fa-store fa-2x text-primary"></i>
                                </div>

                                <div class="text-muted small">
                                    Maximum Vendors
                                </div>

                                <div class="fs-4 fw-bold mt-1">

                                    @if(is_null($subscriptionPlan->max_vendors))
                                        Unlimited
                                    @else
                                        {{ number_format($subscriptionPlan->max_vendors) }}
                                    @endif

                                </div>

                            </div>

                        </div>


                        {{-- TOURS --}}
                        <div class="col-md-6 col-xl-3">

                            <div class="border rounded p-4 text-center h-100">

                                <div class="mb-3">
                                    <i class="fas fa-route fa-2x text-success"></i>
                                </div>

                                <div class="text-muted small">
                                    Maximum Tours
                                </div>

                                <div class="fs-4 fw-bold mt-1">

                                    @if(is_null($subscriptionPlan->max_tours))
                                        Unlimited
                                    @else
                                        {{ number_format($subscriptionPlan->max_tours) }}
                                    @endif

                                </div>

                            </div>

                        </div>


                        {{-- BOOKINGS --}}
                        <div class="col-md-6 col-xl-3">

                            <div class="border rounded p-4 text-center h-100">

                                <div class="mb-3">
                                    <i class="fas fa-calendar-check fa-2x text-warning"></i>
                                </div>

                                <div class="text-muted small">
                                    Maximum Bookings
                                </div>

                                <div class="fs-4 fw-bold mt-1">

                                    @if(is_null($subscriptionPlan->max_bookings))
                                        Unlimited
                                    @else
                                        {{ number_format($subscriptionPlan->max_bookings) }}
                                    @endif

                                </div>

                            </div>

                        </div>


                        {{-- STORAGE --}}
                        <div class="col-md-6 col-xl-3">

                            <div class="border rounded p-4 text-center h-100">

                                <div class="mb-3">
                                    <i class="fas fa-hdd fa-2x text-danger"></i>
                                </div>

                                <div class="text-muted small">
                                    Maximum Storage
                                </div>

                                <div class="fs-4 fw-bold mt-1">

                                    @if(is_null($subscriptionPlan->max_storage_mb))
                                        Unlimited
                                    @else
                                        {{ number_format($subscriptionPlan->max_storage_mb) }}
                                        <small class="text-muted">MB</small>
                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================
             TIMELINE / RECORD INFORMATION
        ========================================================= --}}
        <div class="col-lg-6 mb-4">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="mb-0">
                        <i class="fas fa-clock text-secondary me-2"></i>
                        Record Information
                    </h5>

                </div>

                <div class="card-body">

                    <div class="d-flex justify-content-between border-bottom py-3">

                        <span class="text-muted">
                            Created At
                        </span>

                        <strong>
                            {{ $subscriptionPlan->created_at?->format('d M Y, h:i A') ?? '-' }}
                        </strong>

                    </div>


                    <div class="d-flex justify-content-between py-3">

                        <span class="text-muted">
                            Last Updated
                        </span>

                        <strong>
                            {{ $subscriptionPlan->updated_at?->format('d M Y, h:i A') ?? '-' }}
                        </strong>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================
             FUTURE SUBSCRIPTION SUMMARY
        ========================================================= --}}
        <div class="col-lg-6 mb-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="mb-0">
                        <i class="fas fa-users text-primary me-2"></i>
                        Subscription Summary
                    </h5>

                </div>

                <div class="card-body">

                    <div class="text-center py-3">

                        <div class="mb-3">
                            <i class="fas fa-users fa-3x text-muted"></i>
                        </div>

                        <h6 class="fw-bold">
                            Subscription Tracking
                        </h6>

                        <p class="text-muted small mb-0">
                            Active subscription statistics will appear
                            here once the Subscription module is connected.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
         BOTTOM ACTIONS
    ============================================================ --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <a href="{{ route('admin.subscription-plans.index') }}"
           class="btn btn-light border">

            <i class="fas fa-arrow-left me-1"></i>
            Back to Plans

        </a>


        <a href="{{ route('admin.subscription-plans.edit', $subscriptionPlan) }}"
           class="btn btn-primary">

            <i class="fas fa-edit me-1"></i>
            Edit This Plan

        </a>

    </div>

</div>

@endsection