@extends('layouts.admin')

@section('title', 'Subscriptions')

@section('page')

<div class="container-fluid">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1 fw-bold">
                Subscriptions
            </h4>

            <p class="text-muted mb-0">
                Manage all customer subscriptions
            </p>
        </div>

    </div>


    {{-- =========================================================
         ALERTS
    ========================================================== --}}

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}

            <button
                type="button"
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
                                Total Subscriptions
                            </div>

                            <h3 class="mb-0 fw-bold">
                                {{ number_format($totalSubscriptions) }}
                            </h3>

                        </div>

                        <div class="fs-2 text-primary">
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
                                Active
                            </div>

                            <h3 class="mb-0 fw-bold text-success">
                                {{ number_format($activeSubscriptions) }}
                            </h3>

                        </div>

                        <div class="fs-2 text-success">
                            <i class="fas fa-check-circle"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Trial --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="text-muted small mb-1">
                                Trial
                            </div>

                            <h3 class="mb-0 fw-bold text-info">
                                {{ number_format($trialSubscriptions) }}
                            </h3>

                        </div>

                        <div class="fs-2 text-info">
                            <i class="fas fa-clock"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Cancelled --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="text-muted small mb-1">
                                Cancelled
                            </div>

                            <h3 class="mb-0 fw-bold text-danger">
                                {{ number_format($cancelledSubscriptions) }}
                            </h3>

                        </div>

                        <div class="fs-2 text-danger">
                            <i class="fas fa-times-circle"></i>
                        </div>

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

            <form
                method="GET"
                action="{{ route('admin.subscriptions.index') }}"
            >

                <div class="row g-3">

                    {{-- Search --}}
                    <div class="col-xl-4 col-lg-6">

                        <label class="form-label small fw-semibold">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Code, user, email, plan..."
                            value="{{ request('search') }}"
                        >

                    </div>


                    {{-- Status --}}
                    <div class="col-xl-2 col-lg-3 col-md-4">

                        <label class="form-label small fw-semibold">
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-select"
                        >

                            <option value="">
                                All Status
                            </option>

                            @foreach([
                                'pending',
                                'trialing',
                                'active',
                                'past_due',
                                'suspended',
                                'cancelled',
                                'expired'
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    @selected(request('status') === $status)
                                >
                                    {{ ucwords(str_replace('_', ' ', $status)) }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Billing --}}
                    <div class="col-xl-2 col-lg-3 col-md-4">

                        <label class="form-label small fw-semibold">
                            Billing
                        </label>

                        <select
                            name="billing_cycle"
                            class="form-select"
                        >

                            <option value="">
                                All Cycles
                            </option>

                            @foreach([
                                'monthly',
                                'yearly',
                                'lifetime'
                            ] as $cycle)

                                <option
                                    value="{{ $cycle }}"
                                    @selected(request('billing_cycle') === $cycle)
                                >
                                    {{ ucfirst($cycle) }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Trial --}}
                    <div class="col-xl-2 col-lg-3 col-md-4">

                        <label class="form-label small fw-semibold">
                            Trial
                        </label>

                        <select
                            name="trial"
                            class="form-select"
                        >

                            <option value="">
                                All
                            </option>

                            <option
                                value="yes"
                                @selected(request('trial') === 'yes')
                            >
                                Trial
                            </option>

                            <option
                                value="no"
                                @selected(request('trial') === 'no')
                            >
                                Non-Trial
                            </option>

                        </select>

                    </div>


                    {{-- Date From --}}
                    <div class="col-xl-2 col-lg-3 col-md-4">

                        <label class="form-label small fw-semibold">
                            From
                        </label>

                        <input
                            type="date"
                            name="date_from"
                            class="form-control"
                            value="{{ request('date_from') }}"
                        >

                    </div>


                    {{-- Date To --}}
                    <div class="col-xl-2 col-lg-3 col-md-4">

                        <label class="form-label small fw-semibold">
                            To
                        </label>

                        <input
                            type="date"
                            name="date_to"
                            class="form-control"
                            value="{{ request('date_to') }}"
                        >

                    </div>


                    {{-- Buttons --}}
                    <div class="col-xl-2 col-lg-3 col-md-4 d-flex align-items-end gap-2">

                        <button
                            type="submit"
                            class="btn btn-primary flex-grow-1"
                        >
                            <i class="fas fa-search me-1"></i>
                            Filter
                        </button>

                        <a
                            href="{{ route('admin.subscriptions.index') }}"
                            class="btn btn-light border"
                            title="Reset"
                        >
                            <i class="fas fa-redo"></i>
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
         SUBSCRIPTIONS TABLE
    ========================================================== --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center">

                <h6 class="mb-0 fw-bold">
                    All Subscriptions
                </h6>

                <span class="badge bg-primary">
                    {{ number_format($subscriptions->total()) }}
                    Total
                </span>

            </div>

        </div>


        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th class="ps-3">
                            #
                        </th>

                        <th>
                            Subscription
                        </th>

                        <th>
                            Customer
                        </th>

                        <th>
                            Plan
                        </th>

                        <th>
                            Billing
                        </th>

                        <th>
                            Amount
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Started
                        </th>

                        <th class="text-end pe-3">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($subscriptions as $subscription)

                        @php

                            $status = strtolower(
                                $subscription->status ?? 'pending'
                            );

                            $statusClass = match($status) {

                                'active' => 'success',

                                'trialing' => 'info',

                                'suspended' => 'warning',

                                'cancelled' => 'danger',

                                'expired' => 'secondary',

                                'past_due' => 'warning',

                                default => 'primary',

                            };

                        @endphp


                        <tr>

                            {{-- # --}}
                            <td class="ps-3">

                                {{
                                    $subscriptions->firstItem()
                                    + $loop->index
                                }}

                            </td>


                            {{-- Subscription --}}
                            <td>

                                <div class="fw-semibold">

                                    {{
                                        $subscription->subscription_code
                                        ?? 'N/A'
                                    }}

                                </div>

                                @if($subscription->is_trial)

                                    <span class="badge bg-info-subtle text-info mt-1">
                                        Trial
                                    </span>

                                @endif

                            </td>


                            {{-- Customer --}}
                            <td>

                                <div class="fw-semibold">

                                    {{
                                        $subscription->user?->name
                                        ?? 'Deleted User'
                                    }}

                                </div>

                                <small class="text-muted">

                                    {{
                                        $subscription->user?->email
                                        ?? ''
                                    }}

                                </small>

                            </td>


                            {{-- Plan --}}
                            <td>

                                <span class="fw-medium">

                                    {{
                                        $subscription
                                            ->subscriptionPlan
                                            ?->name
                                        ?? 'N/A'
                                    }}

                                </span>

                            </td>


                            {{-- Billing --}}
                            <td>

                                <span class="text-capitalize">

                                    {{
                                        $subscription->billing_cycle
                                        ?? 'N/A'
                                    }}

                                </span>

                            </td>


                            {{-- Amount --}}
                            <td>

                                <div class="fw-semibold">

                                    {{
                                        $subscription->currency
                                        ?? 'USD'
                                    }}

                                    {{

                                        number_format(
                                            (float) (
                                                $subscription->price
                                                ?? 0
                                            ),
                                            2
                                        )

                                    }}

                                </div>

                            </td>


                            {{-- Status --}}
                            <td>

                                <span class="badge bg-{{ $statusClass }}">

                                    {{
                                        ucwords(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $status
                                            )
                                        )
                                    }}

                                </span>

                            </td>


                            {{-- Started --}}
                            <td>

                                @if($subscription->starts_at)

                                    {{
                                        $subscription
                                            ->starts_at
                                            ->format('d M Y')
                                    }}

                                @else

                                    <span class="text-muted">
                                        Not started
                                    </span>

                                @endif

                            </td>


                            {{-- Actions --}}
                            <td class="text-end pe-3">

                                <div class="d-inline-flex gap-1">

                                    {{-- VIEW --}}
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#viewSubscriptionModal{{ $subscription->id }}"
                                        title="View"
                                    >
                                        <i class="fas fa-eye"></i>
                                    </button>


                                    {{-- ACTIVATE --}}
                                    @if(
                                        !in_array(
                                            $subscription->status,
                                            ['active', 'cancelled']
                                        )
                                    )

                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'admin.subscriptions.activate',
                                                $subscription
                                            ) }}"
                                            class="d-inline"
                                        >

                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-outline-success"
                                                title="Activate"
                                                onclick="return confirm('Are you sure you want to activate this subscription?')"
                                            >
                                                <i class="fas fa-check"></i>
                                            </button>

                                        </form>

                                    @endif


                                    {{-- SUSPEND --}}
                                    @if(
                                        in_array(
                                            $subscription->status,
                                            ['active', 'trialing']
                                        )
                                    )

                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'admin.subscriptions.suspend',
                                                $subscription
                                            ) }}"
                                            class="d-inline"
                                        >

                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-outline-warning"
                                                title="Suspend"
                                                onclick="return confirm('Are you sure you want to suspend this subscription?')"
                                            >
                                                <i class="fas fa-pause"></i>
                                            </button>

                                        </form>

                                    @endif


                                    {{-- CANCEL --}}
                                    @if(
                                        !in_array(
                                            $subscription->status,
                                            ['cancelled', 'expired']
                                        )
                                    )

                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'admin.subscriptions.cancel',
                                                $subscription
                                            ) }}"
                                            class="d-inline"
                                        >

                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                title="Cancel"
                                                onclick="return confirm('Are you sure you want to cancel this subscription?')"
                                            >
                                                <i class="fas fa-times"></i>
                                            </button>

                                        </form>

                                    @endif

                                </div>

                            </td>

                        </tr>


                        {{-- =====================================================
                             VIEW MODAL
                        ====================================================== --}}

                        <div
                            class="modal fade"
                            id="viewSubscriptionModal{{ $subscription->id }}"
                            tabindex="-1"
                            aria-hidden="true"
                        >

                            <div class="modal-dialog modal-lg modal-dialog-centered">

                                <div class="modal-content border-0 shadow">

                                    {{-- Header --}}
                                    <div class="modal-header">

                                        <div>

                                            <h5 class="modal-title fw-bold">

                                                Subscription Details

                                            </h5>

                                            <small class="text-muted">

                                                {{
                                                    $subscription->subscription_code
                                                    ?? 'N/A'
                                                }}

                                            </small>

                                        </div>

                                        <button
                                            type="button"
                                            class="btn-close"
                                            data-bs-dismiss="modal"
                                        ></button>

                                    </div>


                                    {{-- Body --}}
                                    <div class="modal-body">

                                        <div class="row g-3">


                                            {{-- Status --}}
                                            <div class="col-md-6">

                                                <div class="border rounded p-3 h-100">

                                                    <div class="small text-muted mb-1">
                                                        Status
                                                    </div>

                                                    <span class="badge bg-{{ $statusClass }}">

                                                        {{
                                                            ucwords(
                                                                str_replace(
                                                                    '_',
                                                                    ' ',
                                                                    $status
                                                                )
                                                            )
                                                        }}

                                                    </span>

                                                </div>

                                            </div>


                                            {{-- Plan --}}
                                            <div class="col-md-6">

                                                <div class="border rounded p-3 h-100">

                                                    <div class="small text-muted mb-1">
                                                        Plan
                                                    </div>

                                                    <div class="fw-semibold">

                                                        {{
                                                            $subscription
                                                                ->subscriptionPlan
                                                                ?->name
                                                            ?? 'N/A'
                                                        }}

                                                    </div>

                                                </div>

                                            </div>


                                            {{-- Customer --}}
                                            <div class="col-md-6">

                                                <div class="border rounded p-3 h-100">

                                                    <div class="small text-muted mb-1">
                                                        Customer
                                                    </div>

                                                    <div class="fw-semibold">

                                                        {{
                                                            $subscription
                                                                ->user
                                                                ?->name
                                                            ?? 'Deleted User'
                                                        }}

                                                    </div>

                                                    <small class="text-muted">

                                                        {{
                                                            $subscription
                                                                ->user
                                                                ?->email
                                                            ?? ''
                                                        }}

                                                    </small>

                                                </div>

                                            </div>


                                            {{-- Amount --}}
                                            <div class="col-md-6">

                                                <div class="border rounded p-3 h-100">

                                                    <div class="small text-muted mb-1">
                                                        Amount
                                                    </div>

                                                    <div class="fw-bold fs-5">

                                                        {{
                                                            $subscription->currency
                                                            ?? 'USD'
                                                        }}

                                                        {{
                                                            number_format(
                                                                (float) (
                                                                    $subscription->price
                                                                    ?? 0
                                                                ),
                                                                2
                                                            )
                                                        }}

                                                    </div>

                                                    <small class="text-muted">

                                                        {{
                                                            ucfirst(
                                                                $subscription->billing_cycle
                                                                ?? 'monthly'
                                                            )
                                                        }}

                                                    </small>

                                                </div>

                                            </div>


                                            {{-- Subscription Dates --}}
                                            <div class="col-12">

                                                <div class="border rounded p-3">

                                                    <h6 class="fw-bold mb-3">
                                                        Subscription Period
                                                    </h6>

                                                    <div class="row g-3">

                                                        <div class="col-md-4">

                                                            <small class="text-muted d-block">
                                                                Started
                                                            </small>

                                                            <strong>

                                                                {{
                                                                    $subscription->starts_at
                                                                    ? $subscription->starts_at->format('d M Y H:i')
                                                                    : 'Not started'
                                                                }}

                                                            </strong>

                                                        </div>


                                                        <div class="col-md-4">

                                                            <small class="text-muted d-block">
                                                                Ends
                                                            </small>

                                                            <strong>

                                                                {{
                                                                    $subscription->ends_at
                                                                    ? $subscription->ends_at->format('d M Y H:i')
                                                                    : '—'
                                                                }}

                                                            </strong>

                                                        </div>


                                                        <div class="col-md-4">

                                                            <small class="text-muted d-block">
                                                                Next Billing
                                                            </small>

                                                            <strong>

                                                                {{
                                                                    $subscription->next_billing_at
                                                                    ? $subscription->next_billing_at->format('d M Y H:i')
                                                                    : '—'
                                                                }}

                                                            </strong>

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>


                                            {{-- Trial --}}
                                            @if($subscription->is_trial)

                                                <div class="col-12">

                                                    <div class="alert alert-info mb-0">

                                                        <div class="fw-bold mb-2">

                                                            <i class="fas fa-clock me-1"></i>

                                                            Trial Subscription

                                                        </div>

                                                        <div class="row g-2">

                                                            <div class="col-md-6">

                                                                <small class="d-block">
                                                                    Trial Started
                                                                </small>

                                                                <strong>

                                                                    {{
                                                                        $subscription->trial_starts_at
                                                                        ? $subscription->trial_starts_at->format('d M Y H:i')
                                                                        : '—'
                                                                    }}

                                                                </strong>

                                                            </div>

                                                            <div class="col-md-6">

                                                                <small class="d-block">
                                                                    Trial Ends
                                                                </small>

                                                                <strong>

                                                                    {{
                                                                        $subscription->trial_ends_at
                                                                        ? $subscription->trial_ends_at->format('d M Y H:i')
                                                                        : '—'
                                                                    }}

                                                                </strong>

                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>

                                            @endif


                                            {{-- Payment --}}
                                            <div class="col-md-6">

                                                <div class="border rounded p-3 h-100">

                                                    <h6 class="fw-bold mb-3">
                                                        Payment
                                                    </h6>

                                                    <div class="mb-2">

                                                        <small class="text-muted d-block">
                                                            Gateway
                                                        </small>

                                                        <span>
                                                            {{
                                                                $subscription->payment_gateway
                                                                ?? '—'
                                                            }}
                                                        </span>

                                                    </div>

                                                    <div>

                                                        <small class="text-muted d-block">
                                                            Payment Method
                                                        </small>

                                                        <span>
                                                            {{
                                                                $subscription->payment_method
                                                                ?? '—'
                                                            }}
                                                        </span>

                                                    </div>

                                                </div>

                                            </div>


                                            {{-- Gateway --}}
                                            <div class="col-md-6">

                                                <div class="border rounded p-3 h-100">

                                                    <h6 class="fw-bold mb-3">
                                                        Gateway Information
                                                    </h6>

                                                    <div class="mb-2">

                                                        <small class="text-muted d-block">
                                                            Gateway Subscription ID
                                                        </small>

                                                        <span class="text-break">
                                                            {{
                                                                $subscription->gateway_subscription_id
                                                                ?? '—'
                                                            }}
                                                        </span>

                                                    </div>

                                                    <div>

                                                        <small class="text-muted d-block">
                                                            Gateway Customer ID
                                                        </small>

                                                        <span class="text-break">
                                                            {{
                                                                $subscription->gateway_customer_id
                                                                ?? '—'
                                                            }}
                                                        </span>

                                                    </div>

                                                </div>

                                            </div>


                                            {{-- Cancellation --}}
                                            @if(
                                                $subscription->cancelled_at
                                                || $subscription->cancellation_reason
                                            )

                                                <div class="col-12">

                                                    <div class="alert alert-danger mb-0">

                                                        <div class="fw-bold mb-2">

                                                            <i class="fas fa-ban me-1"></i>

                                                            Cancellation Information

                                                        </div>

                                                        @if($subscription->cancelled_at)

                                                            <div>

                                                                <strong>
                                                                    Cancelled At:
                                                                </strong>

                                                                {{
                                                                    $subscription
                                                                        ->cancelled_at
                                                                        ->format('d M Y H:i')
                                                                }}

                                                            </div>

                                                        @endif

                                                        @if($subscription->cancellation_reason)

                                                            <div class="mt-1">

                                                                <strong>
                                                                    Reason:
                                                                </strong>

                                                                {{
                                                                    $subscription
                                                                        ->cancellation_reason
                                                                }}

                                                            </div>

                                                        @endif

                                                    </div>

                                                </div>

                                            @endif

                                        </div>

                                    </div>


                                    {{-- Footer --}}
                                    <div class="modal-footer">

                                        @if(
                                            !in_array(
                                                $subscription->status,
                                                ['active', 'cancelled']
                                            )
                                        )

                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'admin.subscriptions.activate',
                                                    $subscription
                                                ) }}"
                                            >

                                                @csrf
                                                @method('PATCH')

                                                <button
                                                    type="submit"
                                                    class="btn btn-success"
                                                >
                                                    <i class="fas fa-check me-1"></i>
                                                    Activate
                                                </button>

                                            </form>

                                        @endif


                                        @if(
                                            in_array(
                                                $subscription->status,
                                                ['active', 'trialing']
                                            )
                                        )

                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'admin.subscriptions.suspend',
                                                    $subscription
                                                ) }}"
                                            >

                                                @csrf
                                                @method('PATCH')

                                                <button
                                                    type="submit"
                                                    class="btn btn-warning"
                                                >
                                                    <i class="fas fa-pause me-1"></i>
                                                    Suspend
                                                </button>

                                            </form>

                                        @endif


                                        @if(
                                            !in_array(
                                                $subscription->status,
                                                ['cancelled', 'expired']
                                            )
                                        )

                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'admin.subscriptions.cancel',
                                                    $subscription
                                                ) }}"
                                            >

                                                @csrf
                                                @method('PATCH')

                                                <button
                                                    type="submit"
                                                    class="btn btn-danger"
                                                    onclick="return confirm('Are you sure you want to cancel this subscription?')"
                                                >
                                                    <i class="fas fa-times me-1"></i>
                                                    Cancel Subscription
                                                </button>

                                            </form>

                                        @endif


                                        <button
                                            type="button"
                                            class="btn btn-light border"
                                            data-bs-dismiss="modal"
                                        >
                                            Close
                                        </button>

                                    </div>

                                </div>

                            </div>

                        </div>

                    @empty

                        <tr>

                            <td
                                colspan="9"
                                class="text-center py-5 text-muted"
                            >

                                <i class="fas fa-layer-group fa-2x mb-3 d-block"></i>

                                No subscriptions found.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- =====================================================
             PAGINATION
        ====================================================== --}}

        @if($subscriptions->hasPages())

            <div
                class="card-footer bg-white
                       d-flex flex-wrap
                       justify-content-between
                       align-items-center
                       gap-3"
            >

                <small class="text-muted">

                    Showing

                    {{ $subscriptions->firstItem() }}

                    to

                    {{ $subscriptions->lastItem() }}

                    of

                    {{ $subscriptions->total() }}

                    results

                </small>


                <div>

                    {{ $subscriptions->links() }}

                </div>

            </div>

        @endif

    </div>

</div>

@endsection