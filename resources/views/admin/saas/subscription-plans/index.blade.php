@extends('layouts.admin')

@section('title', 'Subscription Plans')

@section('page')

<div class="container-fluid">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1 fw-bold">
                <i class="fas fa-layer-group me-2"></i>
                Subscription Plans
            </h4>

            <p class="text-muted mb-0">
                Manage your SaaS subscription plans and usage limits.
            </p>
        </div>

        <div class="mt-2 mt-md-0">
            <a href="{{ route('admin.subscription-plans.create') }}"
               class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>
                Add New Plan
            </a>
        </div>

    </div>


    {{-- =========================================================
         FLASH MESSAGES
    ========================================================== --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>
        </div>
    @endif


    {{-- =========================================================
         STATISTICS
    ========================================================== --}}
    <div class="row g-3 mb-4">

        {{-- Total --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <div class="text-muted small mb-1">
                                Total Plans
                            </div>

                            <h3 class="mb-0 fw-bold">
                                {{ $stats['total'] }}
                            </h3>
                        </div>

                        <div class="rounded-circle bg-primary bg-opacity-10
                                    text-primary d-flex align-items-center
                                    justify-content-center"
                             style="width:50px;height:50px;">

                            <i class="fas fa-layer-group"></i>

                        </div>

                    </div>

                </div>
            </div>
        </div>


        {{-- Active --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <div class="text-muted small mb-1">
                                Active Plans
                            </div>

                            <h3 class="mb-0 fw-bold text-success">
                                {{ $stats['active'] }}
                            </h3>
                        </div>

                        <div class="rounded-circle bg-success bg-opacity-10
                                    text-success d-flex align-items-center
                                    justify-content-center"
                             style="width:50px;height:50px;">

                            <i class="fas fa-check-circle"></i>

                        </div>

                    </div>

                </div>
            </div>
        </div>


        {{-- Inactive --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <div class="text-muted small mb-1">
                                Inactive Plans
                            </div>

                            <h3 class="mb-0 fw-bold text-danger">
                                {{ $stats['inactive'] }}
                            </h3>
                        </div>

                        <div class="rounded-circle bg-danger bg-opacity-10
                                    text-danger d-flex align-items-center
                                    justify-content-center"
                             style="width:50px;height:50px;">

                            <i class="fas fa-ban"></i>

                        </div>

                    </div>

                </div>
            </div>
        </div>


        {{-- Featured --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <div class="text-muted small mb-1">
                                Featured Plans
                            </div>

                            <h3 class="mb-0 fw-bold text-warning">
                                {{ $stats['featured'] }}
                            </h3>
                        </div>

                        <div class="rounded-circle bg-warning bg-opacity-10
                                    text-warning d-flex align-items-center
                                    justify-content-center"
                             style="width:50px;height:50px;">

                            <i class="fas fa-star"></i>

                        </div>

                    </div>

                </div>
            </div>
        </div>

    </div>


    {{-- =========================================================
         BILLING SUMMARY
    ========================================================== --}}
    <div class="row g-3 mb-4">

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center">

                    <div class="rounded-circle bg-info bg-opacity-10
                                text-info d-flex align-items-center
                                justify-content-center me-3"
                         style="width:45px;height:45px;">

                        <i class="fas fa-calendar-alt"></i>

                    </div>

                    <div>
                        <div class="text-muted small">
                            Monthly Plans
                        </div>

                        <h5 class="mb-0 fw-bold">
                            {{ $stats['monthly'] }}
                        </h5>
                    </div>

                </div>
            </div>
        </div>


        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center">

                    <div class="rounded-circle bg-primary bg-opacity-10
                                text-primary d-flex align-items-center
                                justify-content-center me-3"
                         style="width:45px;height:45px;">

                        <i class="fas fa-calendar-check"></i>

                    </div>

                    <div>
                        <div class="text-muted small">
                            Yearly Plans
                        </div>

                        <h5 class="mb-0 fw-bold">
                            {{ $stats['yearly'] }}
                        </h5>
                    </div>

                </div>
            </div>
        </div>


        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center">

                    <div class="rounded-circle bg-secondary bg-opacity-10
                                text-secondary d-flex align-items-center
                                justify-content-center me-3"
                         style="width:45px;height:45px;">

                        <i class="fas fa-infinity"></i>

                    </div>

                    <div>
                        <div class="text-muted small">
                            Lifetime Plans
                        </div>

                        <h5 class="mb-0 fw-bold">
                            {{ $stats['lifetime'] }}
                        </h5>
                    </div>

                </div>
            </div>
        </div>

    </div>


    {{-- =========================================================
         FILTER CARD
    ========================================================== --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.subscription-plans.index') }}">

                <div class="row g-3 align-items-end">

                    {{-- Search --}}
                    <div class="col-lg-4">

                        <label class="form-label fw-semibold">
                            Search
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>

                            <input type="text"
                                   name="search"
                                   class="form-control"
                                   placeholder="Search plan name, slug..."
                                   value="{{ request('search') }}">

                        </div>

                    </div>


                    {{-- Billing Cycle --}}
                    <div class="col-lg-2">

                        <label class="form-label fw-semibold">
                            Billing Cycle
                        </label>

                        <select name="billing_cycle"
                                class="form-select">

                            <option value="">
                                All Cycles
                            </option>

                            <option value="monthly"
                                {{ request('billing_cycle') === 'monthly' ? 'selected' : '' }}>
                                Monthly
                            </option>

                            <option value="yearly"
                                {{ request('billing_cycle') === 'yearly' ? 'selected' : '' }}>
                                Yearly
                            </option>

                            <option value="lifetime"
                                {{ request('billing_cycle') === 'lifetime' ? 'selected' : '' }}>
                                Lifetime
                            </option>

                        </select>

                    </div>


                    {{-- Status --}}
                    <div class="col-lg-2">

                        <label class="form-label fw-semibold">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="">
                                All Status
                            </option>

                            <option value="active"
                                {{ request('status') === 'active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option value="inactive"
                                {{ request('status') === 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                    </div>


                    {{-- Featured --}}
                    <div class="col-lg-2">

                        <label class="form-label fw-semibold">
                            Featured
                        </label>

                        <select name="featured"
                                class="form-select">

                            <option value="">
                                All
                            </option>

                            <option value="yes"
                                {{ request('featured') === 'yes' ? 'selected' : '' }}>
                                Featured
                            </option>

                            <option value="no"
                                {{ request('featured') === 'no' ? 'selected' : '' }}>
                                Not Featured
                            </option>

                        </select>

                    </div>


                    {{-- Buttons --}}
                    <div class="col-lg-2">

                        <div class="d-flex gap-2">

                            <button type="submit"
                                    class="btn btn-primary flex-grow-1">

                                <i class="fas fa-filter me-1"></i>
                                Filter

                            </button>

                            <a href="{{ route('admin.subscription-plans.index') }}"
                               class="btn btn-light border"
                               title="Reset">

                                <i class="fas fa-redo"></i>

                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
         PLANS HEADER ROW
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>
            <h5 class="mb-0 fw-bold">
                All Plans
            </h5>
            <small class="text-muted">
                Manage all available SaaS plans.
            </small>
        </div>

        <span class="badge bg-light text-dark border px-3 py-2">
            {{ $plans->total() }} Plans
        </span>

    </div>


    {{-- =========================================================
         PLANS — CARD GRID
    ========================================================== --}}
    @if($plans->count())

        <div class="row g-4 mb-4">

            @foreach($plans as $plan)

                <div class="col-xl-4 col-lg-6 col-md-6">

                    <div class="card h-100 border-0 shadow-sm plan-card
                                {{ $plan->is_featured ? 'plan-card-featured' : '' }}
                                {{ !$plan->is_active ? 'plan-card-inactive' : '' }}">

                        {{-- Featured ribbon --}}
                        @if($plan->is_featured)
                            <div class="plan-ribbon">
                                <i class="fas fa-star me-1"></i> Featured
                            </div>
                        @endif

                        {{-- Actions dropdown --}}
                        <div class="plan-card-menu">

                            <div class="dropdown">

                                <button class="btn btn-sm btn-light border rounded-circle
                                               plan-menu-btn"
                                        type="button"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false">

                                    <i class="fas fa-ellipsis-v"></i>

                                </button>


                                <ul class="dropdown-menu dropdown-menu-end">

                                    <li>
                                        <a class="dropdown-item"
                                           href="{{ route('admin.subscription-plans.show', $plan) }}">
                                            <i class="fas fa-eye me-2 text-info"></i>
                                            View
                                        </a>
                                    </li>

                                    <li>
                                        <a class="dropdown-item"
                                           href="{{ route('admin.subscription-plans.edit', $plan) }}">
                                            <i class="fas fa-edit me-2 text-primary"></i>
                                            Edit
                                        </a>
                                    </li>

                                    <li><hr class="dropdown-divider"></li>

                                    <li>
                                        <form method="POST"
                                              action="{{ route('admin.subscription-plans.toggle-status', $plan) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="dropdown-item">
                                                @if($plan->is_active)
                                                    <i class="fas fa-ban me-2 text-danger"></i>
                                                    Deactivate
                                                @else
                                                    <i class="fas fa-check-circle me-2 text-success"></i>
                                                    Activate
                                                @endif
                                            </button>
                                        </form>
                                    </li>

                                    <li>
                                        <form method="POST"
                                              action="{{ route('admin.subscription-plans.toggle-featured', $plan) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="dropdown-item">
                                                @if($plan->is_featured)
                                                    <i class="fas fa-star-half-alt me-2 text-secondary"></i>
                                                    Remove Featured
                                                @else
                                                    <i class="fas fa-star me-2 text-warning"></i>
                                                    Make Featured
                                                @endif
                                            </button>
                                        </form>
                                    </li>

                                    <li><hr class="dropdown-divider"></li>

                                    <li>
                                        <form method="POST"
                                              action="{{ route('admin.subscription-plans.destroy', $plan) }}"
                                              onsubmit="return confirm('Are you sure you want to delete this subscription plan?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="fas fa-trash me-2"></i>
                                                Delete
                                            </button>
                                        </form>
                                    </li>

                                </ul>

                            </div>

                        </div>


                        <div class="card-body d-flex flex-column pt-4">

                            {{-- Icon + Name --}}
                            <div class="text-center mb-3">

                                <div class="plan-icon mx-auto mb-3
                                            {{ $plan->is_featured ? 'plan-icon-featured' : '' }}">
                                    <i class="fas fa-crown"></i>
                                </div>

                                <h5 class="fw-bold mb-1">
                                    {{ $plan->name }}
                                </h5>

                                <div class="text-muted small">
                                    {{ $plan->slug }}
                                </div>

                            </div>


                            {{-- Price --}}
                            <div class="text-center py-3 mb-3 plan-price-box">

                                <span class="plan-price">
                                    {{ $plan->currency }}{{ number_format((float) $plan->price, 2) }}
                                </span>

                                <div class="mt-1">

                                    @if($plan->billing_cycle === 'monthly')

                                        <span class="badge bg-info bg-opacity-10 text-info">
                                            <i class="fas fa-calendar-alt me-1"></i>
                                            Billed Monthly
                                        </span>

                                    @elseif($plan->billing_cycle === 'yearly')

                                        <span class="badge bg-primary bg-opacity-10 text-primary">
                                            <i class="fas fa-calendar-check me-1"></i>
                                            Billed Yearly
                                        </span>

                                    @else

                                        <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                            <i class="fas fa-infinity me-1"></i>
                                            One-time / Lifetime
                                        </span>

                                    @endif

                                </div>

                            </div>


                            {{-- Usage limits --}}
                            <ul class="list-unstyled plan-feature-list mb-3 flex-grow-1">

                                <li>
                                    <span class="feature-icon bg-primary bg-opacity-10 text-primary">
                                        <i class="fas fa-store"></i>
                                    </span>
                                    <span class="feature-text">
                                        Vendors:
                                        <strong>
                                            {{ is_null($plan->max_vendors) ? 'Unlimited' : number_format($plan->max_vendors) }}
                                        </strong>
                                    </span>
                                </li>

                                <li>
                                    <span class="feature-icon bg-success bg-opacity-10 text-success">
                                        <i class="fas fa-route"></i>
                                    </span>
                                    <span class="feature-text">
                                        Tours:
                                        <strong>
                                            {{ is_null($plan->max_tours) ? 'Unlimited' : number_format($plan->max_tours) }}
                                        </strong>
                                    </span>
                                </li>

                                <li>
                                    <span class="feature-icon bg-warning bg-opacity-10 text-warning">
                                        <i class="fas fa-calendar-check"></i>
                                    </span>
                                    <span class="feature-text">
                                        Bookings:
                                        <strong>
                                            {{ is_null($plan->max_bookings) ? 'Unlimited' : number_format($plan->max_bookings) }}
                                        </strong>
                                    </span>
                                </li>

                            </ul>


                            {{-- Status footer --}}
                            <div class="d-flex justify-content-between align-items-center
                                        pt-3 border-top">

                                @if($plan->is_active)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i>
                                        Active
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="fas fa-ban me-1"></i>
                                        Inactive
                                    </span>
                                @endif

                                <a href="{{ route('admin.subscription-plans.show', $plan) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    Details
                                    <i class="fas fa-arrow-right ms-1"></i>
                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>


        {{-- =================================================
             PAGINATION
        ================================================== --}}
        @if($plans->hasPages())

            <div class="card border-0 shadow-sm">
                <div class="card-body py-3">

                    <div class="d-flex flex-wrap justify-content-between
                                align-items-center">

                        <div class="text-muted small mb-2 mb-md-0">

                            Showing
                            <strong>{{ $plans->firstItem() }}</strong>
                            to
                            <strong>{{ $plans->lastItem() }}</strong>
                            of
                            <strong>{{ $plans->total() }}</strong>
                            plans

                        </div>

                        <div>

                            {{ $plans->links() }}

                        </div>

                    </div>

                </div>
            </div>

        @endif

    @else

        {{-- =================================================
             EMPTY STATE
        ================================================== --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body">

                <div class="text-center py-5">

                    <div class="mb-3">

                        <div class="rounded-circle
                                    bg-light
                                    text-muted
                                    d-inline-flex
                                    align-items-center
                                    justify-content-center"
                             style="width:70px;height:70px;">

                            <i class="fas fa-layer-group fa-2x"></i>

                        </div>

                    </div>

                    <h5 class="fw-bold">
                        No Subscription Plans Found
                    </h5>

                    <p class="text-muted mb-4">

                        @if(
                            request()->filled('search') ||
                            request()->filled('billing_cycle') ||
                            request()->filled('status') ||
                            request()->filled('featured')
                        )

                            No plans match your current filters.

                        @else

                            You haven't created any subscription plans yet.

                        @endif

                    </p>


                    @if(
                        request()->filled('search') ||
                        request()->filled('billing_cycle') ||
                        request()->filled('status') ||
                        request()->filled('featured')
                    )

                        <a href="{{ route('admin.subscription-plans.index') }}"
                           class="btn btn-light border me-2">

                            <i class="fas fa-redo me-1"></i>
                            Reset Filters

                        </a>

                    @endif


                    <a href="{{ route('admin.subscription-plans.create') }}"
                       class="btn btn-primary">

                        <i class="fas fa-plus me-1"></i>
                        Create First Plan

                    </a>

                </div>

            </div>
        </div>

    @endif

</div>


{{-- =========================================================
     CARD STYLES
========================================================== --}}
@push('styles')
<style>

    .plan-card {
        position: relative;
        border-radius: 16px;
        overflow: hidden;
        transition: transform .2s ease, box-shadow .2s ease;
        border: 1px solid rgba(0,0,0,.06);
    }

    .plan-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 .75rem 1.5rem rgba(0,0,0,.08) !important;
    }

    .plan-card-featured {
        border: 1px solid #ffc107;
        background: linear-gradient(180deg, rgba(255,193,7,.06) 0%, rgba(255,255,255,1) 18%);
    }

    .plan-card-inactive {
        opacity: .7;
    }

    .plan-ribbon {
        position: absolute;
        top: 14px;
        left: -34px;
        transform: rotate(-45deg);
        background: linear-gradient(90deg, #ffc107, #ff9f1c);
        color: #212529;
        font-size: .72rem;
        font-weight: 700;
        padding: 4px 40px;
        box-shadow: 0 2px 6px rgba(0,0,0,.15);
        z-index: 2;
    }

    .plan-card-menu {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 3;
    }

    .plan-menu-btn {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .plan-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(13,110,253,.1);
        color: #0d6efd;
        font-size: 1.4rem;
    }

    .plan-icon-featured {
        background: rgba(255,193,7,.15);
        color: #d39e00;
    }

    .plan-price-box {
        background: rgba(0,0,0,.02);
        border-radius: 12px;
    }

    .plan-price {
        font-size: 2rem;
        font-weight: 800;
        color: #212529;
    }

    .plan-feature-list li {
        display: flex;
        align-items: center;
        padding: 8px 0;
        font-size: .9rem;
    }

    .feature-icon {
        width: 30px;
        height: 30px;
        min-width: 30px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 10px;
        font-size: .8rem;
    }

    .feature-text {
        color: #495057;
    }

</style>
@endpush

@endsection