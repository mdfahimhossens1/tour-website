@extends('layouts.admin')

@section('title', 'Promotion Details')

@section('page')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1 fw-bold">
                <i class="fas fa-bullhorn me-2"></i>
                Promotion Details
            </h4>

            <p class="text-muted mb-0">
                View complete information and usage details of this promotion.
            </p>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('admin.promotions.edit', $promotion->id) }}"
               class="btn btn-primary">

                <i class="fas fa-edit me-1"></i>
                Edit Promotion

            </a>

            <a href="{{ route('admin.promotions.index') }}"
               class="btn btn-light border">

                <i class="fas fa-arrow-left me-1"></i>
                Back

            </a>

        </div>

    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="fas fa-check-circle me-2"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    <div class="row g-4">


        {{-- ================================================= --}}
        {{-- LEFT COLUMN --}}
        {{-- ================================================= --}}

        <div class="col-lg-8">


            {{-- ========================= --}}
            {{-- Promotion Overview --}}
            {{-- ========================= --}}

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white py-3">

                    <div class="d-flex justify-content-between align-items-center">

                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            Promotion Overview
                        </h5>

                        <div class="d-flex gap-2">

                            @if($promotion->is_active)

                                <span class="badge bg-success">
                                    <i class="fas fa-check me-1"></i>
                                    Active
                                </span>

                            @else

                                <span class="badge bg-secondary">
                                    <i class="fas fa-pause me-1"></i>
                                    Inactive
                                </span>

                            @endif


                            @if($promotion->is_featured)

                                <span class="badge bg-warning text-dark">
                                    <i class="fas fa-star me-1"></i>
                                    Featured
                                </span>

                            @endif

                        </div>

                    </div>

                </div>


                <div class="card-body">

                    <div class="row g-4">


                        {{-- Name --}}
                        <div class="col-md-8">

                            <div class="text-muted small mb-1">
                                Promotion Name
                            </div>

                            <div class="fs-5 fw-bold">
                                {{ $promotion->name }}
                            </div>

                        </div>


                        {{-- Code --}}
                        <div class="col-md-4">

                            <div class="text-muted small mb-1">
                                Promotion Code
                            </div>

                            @if($promotion->code)

                                <span class="badge bg-light text-dark border fs-6">

                                    <i class="fas fa-ticket-alt me-1"></i>

                                    {{ strtoupper($promotion->code) }}

                                </span>

                            @else

                                <span class="text-muted">
                                    No code required
                                </span>

                            @endif

                        </div>


                        {{-- Description --}}
                        <div class="col-12">

                            <div class="text-muted small mb-1">
                                Description
                            </div>

                            @if($promotion->description)

                                <div class="border rounded p-3 bg-light">

                                    {!! nl2br(e($promotion->description)) !!}

                                </div>

                            @else

                                <div class="text-muted">
                                    No description provided.
                                </div>

                            @endif

                        </div>

                    </div>

                </div>

            </div>



            {{-- ========================= --}}
            {{-- Discount Details --}}
            {{-- ========================= --}}

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-0 fw-bold">

                        <i class="fas fa-percent text-success me-2"></i>

                        Discount Details

                    </h5>

                </div>


                <div class="card-body">

                    <div class="row g-4">


                        {{-- Type --}}
                        <div class="col-md-4">

                            <div class="text-muted small mb-1">
                                Discount Type
                            </div>

                            @if($promotion->type === 'percentage')

                                <span class="badge bg-primary-subtle text-primary fs-6">

                                    <i class="fas fa-percent me-1"></i>

                                    Percentage

                                </span>

                            @else

                                <span class="badge bg-success-subtle text-success fs-6">

                                    <i class="fas fa-money-bill-wave me-1"></i>

                                    Fixed Amount

                                </span>

                            @endif

                        </div>


                        {{-- Value --}}
                        <div class="col-md-4">

                            <div class="text-muted small mb-1">
                                Discount Value
                            </div>

                            <div class="fs-4 fw-bold text-primary">

                                @if($promotion->type === 'percentage')

                                    {{ number_format((float) $promotion->value, 2) }}%

                                @else

                                    ৳{{ number_format((float) $promotion->value, 2) }}

                                @endif

                            </div>

                        </div>


                        {{-- Minimum --}}
                        <div class="col-md-4">

                            <div class="text-muted small mb-1">
                                Minimum Booking Amount
                            </div>

                            <div class="fw-bold">

                                @if((float) $promotion->minimum_amount > 0)

                                    ৳{{ number_format((float) $promotion->minimum_amount, 2) }}

                                @else

                                    No minimum

                                @endif

                            </div>

                        </div>


                        {{-- Maximum --}}
                        <div class="col-md-4">

                            <div class="text-muted small mb-1">
                                Maximum Discount
                            </div>

                            <div class="fw-bold">

                                @if(!is_null($promotion->maximum_discount))

                                    ৳{{ number_format((float) $promotion->maximum_discount, 2) }}

                                @else

                                    No maximum

                                @endif

                            </div>

                        </div>


                        {{-- Example --}}
                        <div class="col-md-8">

                            <div class="text-muted small mb-1">
                                Discount Example
                            </div>

                            <div class="alert alert-light border mb-0">

                                @if($promotion->type === 'percentage')

                                    A
                                    <strong>
                                        {{ number_format((float) $promotion->value, 2) }}%
                                    </strong>
                                    discount is applied to eligible bookings.

                                @else

                                    A fixed discount of
                                    <strong>
                                        ৳{{ number_format((float) $promotion->value, 2) }}
                                    </strong>
                                    is applied to eligible bookings.

                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            </div>



            {{-- ========================= --}}
            {{-- Validity --}}
            {{-- ========================= --}}

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-0 fw-bold">

                        <i class="fas fa-calendar-alt text-info me-2"></i>

                        Promotion Validity

                    </h5>

                </div>


                <div class="card-body">

                    <div class="row g-4">


                        {{-- Status --}}
                        <div class="col-md-4">

                            <div class="text-muted small mb-1">
                                Current Validity
                            </div>


                            @if($promotion->isCurrentlyValid())

                                <span class="badge bg-success fs-6">

                                    <i class="fas fa-check-circle me-1"></i>

                                    Currently Valid

                                </span>


                            @elseif($promotion->starts_at && $promotion->starts_at->isFuture())

                                <span class="badge bg-info fs-6">

                                    <i class="fas fa-clock me-1"></i>

                                    Upcoming

                                </span>


                            @elseif($promotion->hasExpired())

                                <span class="badge bg-danger fs-6">

                                    <i class="fas fa-times-circle me-1"></i>

                                    Expired

                                </span>


                            @else

                                <span class="badge bg-secondary fs-6">

                                    <i class="fas fa-calendar me-1"></i>

                                    No Schedule

                                </span>

                            @endif

                        </div>


                        {{-- Start --}}
                        <div class="col-md-4">

                            <div class="text-muted small mb-1">
                                Starts At
                            </div>

                            <div class="fw-semibold">

                                @if($promotion->starts_at)

                                    {{ $promotion->starts_at->format('d M Y, h:i A') }}

                                @else

                                    Immediately

                                @endif

                            </div>

                        </div>


                        {{-- End --}}
                        <div class="col-md-4">

                            <div class="text-muted small mb-1">
                                Ends At
                            </div>

                            <div class="fw-semibold">

                                @if($promotion->ends_at)

                                    {{ $promotion->ends_at->format('d M Y, h:i A') }}

                                @else

                                    No expiration

                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            </div>



            {{-- ========================= --}}
            {{-- Usage Details --}}
            {{-- ========================= --}}

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-0 fw-bold">

                        <i class="fas fa-chart-line text-warning me-2"></i>

                        Usage Details

                    </h5>

                </div>


                <div class="card-body">

                    <div class="row g-4">


                        {{-- Used --}}
                        <div class="col-md-4">

                            <div class="text-muted small mb-1">
                                Used Count
                            </div>

                            <div class="fs-4 fw-bold">

                                {{ number_format($promotion->used_count) }}

                            </div>

                        </div>


                        {{-- Limit --}}
                        <div class="col-md-4">

                            <div class="text-muted small mb-1">
                                Usage Limit
                            </div>

                            <div class="fs-4 fw-bold">

                                @if($promotion->usage_limit)

                                    {{ number_format($promotion->usage_limit) }}

                                @else

                                    Unlimited

                                @endif

                            </div>

                        </div>


                        {{-- Remaining --}}
                        <div class="col-md-4">

                            <div class="text-muted small mb-1">
                                Remaining Usage
                            </div>

                            <div class="fs-4 fw-bold text-success">

                                @if(is_null($promotion->remainingUsage()))

                                    Unlimited

                                @else

                                    {{ number_format($promotion->remainingUsage()) }}

                                @endif

                            </div>

                        </div>


                        {{-- Progress --}}
                        @if($promotion->usage_limit)

                            @php

                                $usagePercentage = min(
                                    100,
                                    ($promotion->used_count / $promotion->usage_limit) * 100
                                );

                            @endphp

                            <div class="col-12">

                                <div class="d-flex justify-content-between mb-2">

                                    <span class="small text-muted">
                                        Usage Progress
                                    </span>

                                    <span class="small fw-semibold">
                                        {{ number_format($usagePercentage, 1) }}%
                                    </span>

                                </div>

                                <div class="progress"
                                     style="height: 10px;">

                                    <div class="progress-bar
                                        {{ $usagePercentage >= 100
                                            ? 'bg-danger'
                                            : ($usagePercentage >= 75
                                                ? 'bg-warning'
                                                : 'bg-success') }}"
                                         style="width: {{ $usagePercentage }}%;">
                                    </div>

                                </div>

                            </div>

                        @endif


                        {{-- Per User --}}
                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Usage Per User
                            </div>

                            <div class="fw-bold">

                                @if($promotion->usage_per_user)

                                    {{ number_format($promotion->usage_per_user) }}
                                    time(s)

                                @else

                                    Unlimited

                                @endif

                            </div>

                        </div>


                        {{-- Limit Status --}}
                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Usage Status
                            </div>


                            @if($promotion->isUsageLimitReached())

                                <span class="badge bg-danger">

                                    <i class="fas fa-ban me-1"></i>

                                    Limit Reached

                                </span>

                            @else

                                <span class="badge bg-success">

                                    <i class="fas fa-check-circle me-1"></i>

                                    Available

                                </span>

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </div>



        {{-- ================================================= --}}
        {{-- RIGHT COLUMN --}}
        {{-- ================================================= --}}

        <div class="col-lg-4">


            {{-- ========================= --}}
            {{-- Status Card --}}
            {{-- ========================= --}}

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-0 fw-bold">

                        <i class="fas fa-toggle-on text-primary me-2"></i>

                        Status

                    </h5>

                </div>


                <div class="card-body text-center">


                    <div class="mb-3">

                        @if($promotion->is_active)

                            <div class="rounded-circle
                                        bg-success
                                        bg-opacity-10
                                        text-success
                                        d-inline-flex
                                        align-items-center
                                        justify-content-center"
                                 style="width:70px;height:70px;">

                                <i class="fas fa-check-circle fa-2x"></i>

                            </div>

                        @else

                            <div class="rounded-circle
                                        bg-secondary
                                        bg-opacity-10
                                        text-secondary
                                        d-inline-flex
                                        align-items-center
                                        justify-content-center"
                                 style="width:70px;height:70px;">

                                <i class="fas fa-pause-circle fa-2x"></i>

                            </div>

                        @endif

                    </div>


                    <h5 class="fw-bold">

                        {{ $promotion->is_active ? 'Active Promotion' : 'Inactive Promotion' }}

                    </h5>


                    <p class="text-muted small mb-3">

                        {{ $promotion->is_active
                            ? 'This promotion is currently enabled.'
                            : 'This promotion is currently disabled.' }}

                    </p>


                    <form method="POST"
                          action="{{ route('admin.promotions.toggle-status', $promotion->id) }}">

                        @csrf

                        <button type="submit"
                                class="btn {{ $promotion->is_active ? 'btn-outline-secondary' : 'btn-success' }} w-100">

                            @if($promotion->is_active)

                                <i class="fas fa-pause me-1"></i>
                                Deactivate Promotion

                            @else

                                <i class="fas fa-check me-1"></i>
                                Activate Promotion

                            @endif

                        </button>

                    </form>

                </div>

            </div>



            {{-- ========================= --}}
            {{-- Featured --}}
            {{-- ========================= --}}

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="fw-bold">
                                Featured Promotion
                            </div>

                            <div class="small text-muted">
                                Show this promotion in featured sections.
                            </div>

                        </div>


                        @if($promotion->is_featured)

                            <i class="fas fa-star text-warning fa-2x"></i>

                        @else

                            <i class="far fa-star text-muted fa-2x"></i>

                        @endif

                    </div>


                    <form method="POST"
                          action="{{ route('admin.promotions.toggle-featured', $promotion->id) }}"
                          class="mt-3">

                        @csrf

                        <button type="submit"
                                class="btn btn-light border w-100">

                            @if($promotion->is_featured)

                                <i class="far fa-star me-1"></i>
                                Remove from Featured

                            @else

                                <i class="fas fa-star me-1"></i>
                                Mark as Featured

                            @endif

                        </button>

                    </form>

                </div>

            </div>



            {{-- ========================= --}}
            {{-- System Information --}}
            {{-- ========================= --}}

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-0 fw-bold">

                        <i class="fas fa-database text-info me-2"></i>

                        System Information

                    </h5>

                </div>


                <div class="card-body">


                    <div class="d-flex justify-content-between mb-3">

                        <span class="text-muted">
                            Promotion ID
                        </span>

                        <span class="fw-bold">
                            #{{ $promotion->id }}
                        </span>

                    </div>


                    <div class="d-flex justify-content-between mb-3">

                        <span class="text-muted">
                            Sort Order
                        </span>

                        <span class="fw-semibold">
                            {{ $promotion->sort_order }}
                        </span>

                    </div>


                    <div class="d-flex justify-content-between mb-3">

                        <span class="text-muted">
                            Created
                        </span>

                        <span class="fw-semibold text-end">
                            {{ $promotion->created_at?->format('d M Y') ?? 'N/A' }}
                        </span>

                    </div>


                    <div class="d-flex justify-content-between">

                        <span class="text-muted">
                            Last Updated
                        </span>

                        <span class="fw-semibold text-end">
                            {{ $promotion->updated_at?->format('d M Y') ?? 'N/A' }}
                        </span>

                    </div>

                </div>

            </div>



            {{-- ========================= --}}
            {{-- Actions --}}
            {{-- ========================= --}}

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-0 fw-bold">

                        <i class="fas fa-cogs text-secondary me-2"></i>

                        Actions

                    </h5>

                </div>


                <div class="card-body">


                    <a href="{{ route('admin.promotions.edit', $promotion->id) }}"
                       class="btn btn-primary w-100 mb-2">

                        <i class="fas fa-edit me-1"></i>
                        Edit Promotion

                    </a>


                    <form method="POST"
                          action="{{ route('admin.promotions.destroy', $promotion->id) }}"
                          id="deletePromotionForm">

                        @csrf
                        @method('DELETE')

                        <button type="submit"
                                class="btn btn-outline-danger w-100">

                            <i class="fas fa-trash me-1"></i>
                            Delete Promotion

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const deleteForm =
        document.getElementById('deletePromotionForm');

    if (deleteForm) {

        deleteForm.addEventListener('submit', function (event) {

            const confirmed = confirm(
                'Are you sure you want to delete this promotion?'
            );

            if (!confirmed) {
                event.preventDefault();
            }

        });

    }

});
</script>

@endsection