@extends('layouts.admin')

@section('title', 'Billing History')

@section('page')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold">
                <i class="fas fa-file-invoice-dollar me-2 text-primary"></i>
                Billing History
            </h4>

            <p class="text-muted mb-0">
                Manage and monitor all subscription billing records.
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

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif


    {{-- =========================================================
        STATISTICS
    ========================================================== --}}
    <div class="row g-3 mb-4">

        {{-- Total Records --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <div class="text-muted small mb-1">
                                Total Billing Records
                            </div>

                            <h3 class="fw-bold mb-0">
                                {{ number_format($totalBillingRecords) }}
                            </h3>
                        </div>

                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3">
                            <i class="fas fa-file-invoice fa-lg"></i>
                        </div>

                    </div>
                </div>
            </div>
        </div>


        {{-- Paid --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <div class="text-muted small mb-1">
                                Paid Records
                            </div>

                            <h3 class="fw-bold text-success mb-0">
                                {{ number_format($paidBillingRecords) }}
                            </h3>
                        </div>

                        <div class="bg-success bg-opacity-10 text-success rounded-circle p-3">
                            <i class="fas fa-check-circle fa-lg"></i>
                        </div>

                    </div>
                </div>
            </div>
        </div>


        {{-- Pending --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <div class="text-muted small mb-1">
                                Pending Records
                            </div>

                            <h3 class="fw-bold text-warning mb-0">
                                {{ number_format($pendingBillingRecords) }}
                            </h3>
                        </div>

                        <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-3">
                            <i class="fas fa-clock fa-lg"></i>
                        </div>

                    </div>
                </div>
            </div>
        </div>


        {{-- Revenue --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <div class="text-muted small mb-1">
                                Total Paid Revenue
                            </div>

                            <h3 class="fw-bold text-success mb-0">
                                ${{ number_format((float) $totalRevenue, 2) }}
                            </h3>
                        </div>

                        <div class="bg-success bg-opacity-10 text-success rounded-circle p-3">
                            <i class="fas fa-dollar-sign fa-lg"></i>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>


    {{-- =========================================================
        REFUND SUMMARY
    ========================================================== --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">

            <div class="row align-items-center">

                <div class="col-md-8">
                    <div class="d-flex align-items-center">

                        <div class="bg-danger bg-opacity-10 text-danger rounded-circle p-3 me-3">
                            <i class="fas fa-undo-alt fa-lg"></i>
                        </div>

                        <div>
                            <h6 class="mb-1 fw-bold">
                                Total Refunded Amount
                            </h6>

                            <p class="text-muted small mb-0">
                                Total amount refunded from paid billing records.
                            </p>
                        </div>

                    </div>
                </div>

                <div class="col-md-4 text-md-end mt-3 mt-md-0">

                    <h4 class="fw-bold text-danger mb-0">
                        ${{ number_format((float) $totalRefunded, 2) }}
                    </h4>

                </div>

            </div>

        </div>
    </div>


    {{-- =========================================================
        FILTERS
    ========================================================== --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white border-0 py-3">
            <h6 class="mb-0 fw-bold">
                <i class="fas fa-filter me-2 text-primary"></i>
                Filter Billing Records
            </h6>
        </div>

        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.billing-history.index') }}">

                <div class="row g-3">

                    {{-- Search --}}
                    <div class="col-xl-4 col-md-6">

                        <label class="form-label fw-semibold">
                            Search
                        </label>

                        <div class="input-group">

                            <span class="input-group-text bg-white">
                                <i class="fas fa-search text-muted"></i>
                            </span>

                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                value="{{ request('search') }}"
                                placeholder="Invoice, transaction, user, plan..."
                            >

                        </div>

                    </div>


                    {{-- Status --}}
                    <div class="col-xl-2 col-md-6">

                        <label class="form-label fw-semibold">
                            Status
                        </label>

                        <select name="status" class="form-select">

                            <option value="">
                                All Status
                            </option>

                            <option value="pending"
                                @selected(request('status') === 'pending')}>
                                Pending
                            </option>

                            <option value="paid"
                                @selected(request('status') === 'paid')}>
                                Paid
                            </option>

                            <option value="failed"
                                @selected(request('status') === 'failed')}>
                                Failed
                            </option>

                            <option value="refunded"
                                @selected(request('status') === 'refunded')}>
                                Refunded
                            </option>

                            <option value="partially_refunded"
                                @selected(request('status') === 'partially_refunded')}>
                                Partially Refunded
                            </option>

                            <option value="cancelled"
                                @selected(request('status') === 'cancelled')}>
                                Cancelled
                            </option>

                        </select>

                    </div>


                    {{-- Billing Cycle --}}
                    <div class="col-xl-2 col-md-6">

                        <label class="form-label fw-semibold">
                            Billing Cycle
                        </label>

                        <select name="billing_cycle" class="form-select">

                            <option value="">
                                All Cycles
                            </option>

                            <option value="monthly"
                                @selected(request('billing_cycle') === 'monthly')}>
                                Monthly
                            </option>

                            <option value="yearly"
                                @selected(request('billing_cycle') === 'yearly')}>
                                Yearly
                            </option>

                            <option value="lifetime"
                                @selected(request('billing_cycle') === 'lifetime')}>
                                Lifetime
                            </option>

                        </select>

                    </div>


                    {{-- Gateway --}}
                    <div class="col-xl-2 col-md-6">

                        <label class="form-label fw-semibold">
                            Payment Gateway
                        </label>

                        <select name="payment_gateway" class="form-select">

                            <option value="">
                                All Gateways
                            </option>

                            @foreach($paymentGateways as $gateway)

                                <option value="{{ $gateway }}"
                                    @selected(request('payment_gateway') === $gateway)>
                                    {{ ucfirst($gateway) }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- From Date --}}
                    <div class="col-xl-1 col-md-6">

                        <label class="form-label fw-semibold">
                            From
                        </label>

                        <input
                            type="date"
                            name="date_from"
                            class="form-control"
                            value="{{ request('date_from') }}"
                        >

                    </div>


                    {{-- To Date --}}
                    <div class="col-xl-1 col-md-6">

                        <label class="form-label fw-semibold">
                            To
                        </label>

                        <input
                            type="date"
                            name="date_to"
                            class="form-control"
                            value="{{ request('date_to') }}"
                        >

                    </div>

                </div>


                {{-- Filter Buttons --}}
                <div class="d-flex flex-wrap gap-2 mt-4">

                    <button type="submit"
                            class="btn btn-primary">

                        <i class="fas fa-filter me-1"></i>
                        Apply Filters

                    </button>

                    <a href="{{ route('admin.billing-history.index') }}"
                       class="btn btn-light border">

                        <i class="fas fa-redo me-1"></i>
                        Reset

                    </a>

                </div>

            </form>

        </div>
    </div>


    {{-- =========================================================
        BILLING TABLE
    ========================================================== --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center">

                <div>
                    <h6 class="mb-1 fw-bold">
                        Billing Records
                    </h6>

                    <small class="text-muted">
                        Showing
                        {{ $billingHistories->firstItem() ?? 0 }}
                        -
                        {{ $billingHistories->lastItem() ?? 0 }}
                        of
                        {{ $billingHistories->total() }}
                        records
                    </small>
                </div>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="px-3">
                                Invoice
                            </th>

                            <th>
                                Customer
                            </th>

                            <th>
                                Plan
                            </th>

                            <th>
                                Amount
                            </th>

                            <th>
                                Cycle
                            </th>

                            <th>
                                Gateway
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Billing Date
                            </th>

                            <th class="text-end px-3">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse($billingHistories as $billing)

                        <tr>

                            {{-- Invoice --}}
                            <td class="px-3">

                                <div class="fw-semibold">
                                    {{ $billing->invoice_number }}
                                </div>

                                @if($billing->transaction_id)

                                    <small class="text-muted">
                                        TXN:
                                        {{ $billing->transaction_id }}
                                    </small>

                                @endif

                            </td>


                            {{-- Customer --}}
                            <td>

                                @if($billing->user)

                                    <div class="fw-semibold">
                                        {{ $billing->user->name }}
                                    </div>

                                    <small class="text-muted">
                                        {{ $billing->user->email }}
                                    </small>

                                @else

                                    <span class="text-muted">
                                        Deleted User
                                    </span>

                                @endif

                            </td>


                            {{-- Plan --}}
                            <td>

                                @if($billing->subscriptionPlan)

                                    <span class="fw-semibold">
                                        {{ $billing->subscriptionPlan->name }}
                                    </span>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- Amount --}}
                            <td>

                                <div class="fw-bold">
                                    {{ $billing->currency ?? 'USD' }}
                                    {{ number_format((float) $billing->total_amount, 2) }}
                                </div>

                                @if((float) $billing->discount > 0)

                                    <small class="text-success">
                                        Discount:
                                        {{ number_format((float) $billing->discount, 2) }}
                                    </small>

                                @endif

                            </td>


                            {{-- Billing Cycle --}}
                            <td>

                                @php
                                    $cycleClass = match($billing->billing_cycle) {
                                        'monthly' => 'primary',
                                        'yearly' => 'info',
                                        'lifetime' => 'dark',
                                        default => 'secondary',
                                    };
                                @endphp

                                <span class="badge bg-{{ $cycleClass }}">

                                    {{ $billing->getBillingCycleLabel() }}

                                </span>

                            </td>


                            {{-- Gateway --}}
                            <td>

                                @if($billing->payment_gateway)

                                    <span class="text-capitalize">
                                        {{ $billing->payment_gateway }}
                                    </span>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- Status --}}
                            <td>

                                @php

                                    $statusClass = match($billing->status) {

                                        'paid'
                                            => 'success',

                                        'pending'
                                            => 'warning',

                                        'failed'
                                            => 'danger',

                                        'refunded'
                                            => 'dark',

                                        'partially_refunded'
                                            => 'info',

                                        'cancelled'
                                            => 'secondary',

                                        default
                                            => 'secondary',

                                    };

                                @endphp


                                <span class="badge bg-{{ $statusClass }}">

                                    {{ $billing->getStatusLabel() }}

                                </span>


                                @if($billing->hasRefund())

                                    <div class="mt-1">

                                        <small class="text-danger">

                                            Refunded:
                                            {{ $billing->currency ?? 'USD' }}
                                            {{ number_format((float) $billing->refunded_amount, 2) }}

                                        </small>

                                    </div>

                                @endif

                            </td>


                            {{-- Billing Date --}}
                            <td>

                                @if($billing->billing_date)

                                    <div>
                                        {{ $billing->billing_date->format('d M Y') }}
                                    </div>

                                    <small class="text-muted">
                                        {{ $billing->billing_date->format('h:i A') }}
                                    </small>

                                @else

                                    <span class="text-muted">
                                        —
                                    </span>

                                @endif

                            </td>


                            {{-- Action --}}
                            <td class="text-end px-3">

                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#billingViewModal{{ $billing->id }}"
                                >
                                    <i class="fas fa-eye me-1"></i>
                                    View
                                </button>

                            </td>

                        </tr>


                        {{-- =================================================
                            VIEW MODAL
                        ================================================== --}}
                        <div
                            class="modal fade"
                            id="billingViewModal{{ $billing->id }}"
                            tabindex="-1"
                            aria-hidden="true"
                        >

                            <div class="modal-dialog modal-xl modal-dialog-centered">

                                <div class="modal-content border-0 shadow">

                                    <div class="modal-header">

                                        <div>

                                            <h5 class="modal-title fw-bold mb-1">

                                                <i class="fas fa-file-invoice-dollar text-primary me-2"></i>

                                                Billing Details

                                            </h5>

                                            <small class="text-muted">

                                                {{ $billing->invoice_number }}

                                            </small>

                                        </div>

                                        <button
                                            type="button"
                                            class="btn-close"
                                            data-bs-dismiss="modal"
                                        ></button>

                                    </div>


                                    <div class="modal-body">

                                        <div class="row g-4">

                                            {{-- Customer --}}
                                            <div class="col-md-6">

                                                <div class="border rounded p-3 h-100">

                                                    <h6 class="fw-bold mb-3">
                                                        <i class="fas fa-user me-2 text-primary"></i>
                                                        Customer Information
                                                    </h6>

                                                    @if($billing->user)

                                                        <div class="mb-2">
                                                            <strong>Name:</strong>
                                                            {{ $billing->user->name }}
                                                        </div>

                                                        <div class="mb-2">
                                                            <strong>Email:</strong>
                                                            {{ $billing->user->email }}
                                                        </div>

                                                        @if($billing->user->phone)

                                                            <div>
                                                                <strong>Phone:</strong>
                                                                {{ $billing->user->phone }}
                                                            </div>

                                                        @endif

                                                    @else

                                                        <span class="text-muted">
                                                            User no longer exists.
                                                        </span>

                                                    @endif

                                                </div>

                                            </div>


                                            {{-- Subscription --}}
                                            <div class="col-md-6">

                                                <div class="border rounded p-3 h-100">

                                                    <h6 class="fw-bold mb-3">
                                                        <i class="fas fa-sync-alt me-2 text-primary"></i>
                                                        Subscription Information
                                                    </h6>

                                                    @if($billing->subscription)

                                                        <div class="mb-2">
                                                            <strong>Code:</strong>
                                                            {{ $billing->subscription->subscription_code }}
                                                        </div>

                                                        <div class="mb-2">
                                                            <strong>Status:</strong>

                                                            <span class="badge bg-secondary">
                                                                {{ $billing->subscription->getStatusLabel() }}
                                                            </span>
                                                        </div>

                                                    @else

                                                        <span class="text-muted">
                                                            Subscription no longer exists.
                                                        </span>

                                                    @endif

                                                    @if($billing->subscriptionPlan)

                                                        <div>
                                                            <strong>Plan:</strong>
                                                            {{ $billing->subscriptionPlan->name }}
                                                        </div>

                                                    @endif

                                                </div>

                                            </div>


                                            {{-- Invoice Information --}}
                                            <div class="col-md-6">

                                                <div class="border rounded p-3 h-100">

                                                    <h6 class="fw-bold mb-3">
                                                        <i class="fas fa-receipt me-2 text-primary"></i>
                                                        Invoice Information
                                                    </h6>

                                                    <div class="mb-2">
                                                        <strong>Invoice:</strong>
                                                        {{ $billing->invoice_number }}
                                                    </div>

                                                    @if($billing->transaction_id)

                                                        <div class="mb-2">
                                                            <strong>Transaction ID:</strong>
                                                            {{ $billing->transaction_id }}
                                                        </div>

                                                    @endif

                                                    @if($billing->gateway_invoice_id)

                                                        <div class="mb-2">
                                                            <strong>Gateway Invoice:</strong>
                                                            {{ $billing->gateway_invoice_id }}
                                                        </div>

                                                    @endif

                                                    @if($billing->gateway_transaction_id)

                                                        <div>
                                                            <strong>Gateway Transaction:</strong>
                                                            {{ $billing->gateway_transaction_id }}
                                                        </div>

                                                    @endif

                                                </div>

                                            </div>


                                            {{-- Payment Information --}}
                                            <div class="col-md-6">

                                                <div class="border rounded p-3 h-100">

                                                    <h6 class="fw-bold mb-3">
                                                        <i class="fas fa-credit-card me-2 text-primary"></i>
                                                        Payment Information
                                                    </h6>

                                                    <div class="mb-2">
                                                        <strong>Gateway:</strong>

                                                        {{ $billing->payment_gateway
                                                            ? ucfirst($billing->payment_gateway)
                                                            : '—'
                                                        }}

                                                    </div>

                                                    <div class="mb-2">
                                                        <strong>Method:</strong>

                                                        {{ $billing->payment_method
                                                            ? ucfirst($billing->payment_method)
                                                            : '—'
                                                        }}

                                                    </div>

                                                    <div>
                                                        <strong>Status:</strong>

                                                        <span class="badge bg-{{ $statusClass }}">
                                                            {{ $billing->getStatusLabel() }}
                                                        </span>

                                                    </div>

                                                </div>

                                            </div>


                                            {{-- Amount Breakdown --}}
                                            <div class="col-12">

                                                <div class="border rounded p-3">

                                                    <h6 class="fw-bold mb-3">
                                                        <i class="fas fa-calculator me-2 text-primary"></i>
                                                        Amount Breakdown
                                                    </h6>

                                                    <div class="row g-3">

                                                        <div class="col-md-3">

                                                            <div class="text-muted small">
                                                                Base Amount
                                                            </div>

                                                            <div class="fw-bold">
                                                                {{ $billing->currency ?? 'USD' }}
                                                                {{ number_format((float) $billing->amount, 2) }}
                                                            </div>

                                                        </div>


                                                        <div class="col-md-3">

                                                            <div class="text-muted small">
                                                                Discount
                                                            </div>

                                                            <div class="fw-bold text-success">
                                                                -
                                                                {{ $billing->currency ?? 'USD' }}
                                                                {{ number_format((float) $billing->discount, 2) }}
                                                            </div>

                                                        </div>


                                                        <div class="col-md-3">

                                                            <div class="text-muted small">
                                                                Tax
                                                            </div>

                                                            <div class="fw-bold">
                                                                {{ $billing->currency ?? 'USD' }}
                                                                {{ number_format((float) $billing->tax, 2) }}
                                                            </div>

                                                        </div>


                                                        <div class="col-md-3">

                                                            <div class="text-muted small">
                                                                Total
                                                            </div>

                                                            <div class="fw-bold fs-5 text-primary">
                                                                {{ $billing->currency ?? 'USD' }}
                                                                {{ number_format((float) $billing->total_amount, 2) }}
                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>


                                            {{-- Refund Information --}}
                                            @if($billing->hasRefund())

                                                <div class="col-12">

                                                    <div class="alert alert-danger mb-0">

                                                        <h6 class="fw-bold">
                                                            <i class="fas fa-undo-alt me-2"></i>
                                                            Refund Information
                                                        </h6>

                                                        <div class="row mt-2">

                                                            <div class="col-md-4">

                                                                <small class="text-muted">
                                                                    Refunded Amount
                                                                </small>

                                                                <div class="fw-bold">
                                                                    {{ $billing->currency ?? 'USD' }}
                                                                    {{ number_format((float) $billing->refunded_amount, 2) }}
                                                                </div>

                                                            </div>


                                                            <div class="col-md-4">

                                                                <small class="text-muted">
                                                                    Refunded At
                                                                </small>

                                                                <div class="fw-bold">

                                                                    {{ $billing->refunded_at
                                                                        ? $billing->refunded_at->format('d M Y, h:i A')
                                                                        : '—'
                                                                    }}

                                                                </div>

                                                            </div>


                                                            <div class="col-md-4">

                                                                <small class="text-muted">
                                                                    Reason
                                                                </small>

                                                                <div>
                                                                    {{ $billing->refund_reason ?: '—' }}
                                                                </div>

                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>

                                            @endif


                                            {{-- Dates --}}
                                            <div class="col-12">

                                                <div class="border rounded p-3">

                                                    <h6 class="fw-bold mb-3">
                                                        <i class="fas fa-calendar-alt me-2 text-primary"></i>
                                                        Billing Dates
                                                    </h6>

                                                    <div class="row g-3">

                                                        <div class="col-md-4">

                                                            <small class="text-muted">
                                                                Billing Date
                                                            </small>

                                                            <div class="fw-semibold">

                                                                {{ $billing->billing_date
                                                                    ? $billing->billing_date->format('d M Y, h:i A')
                                                                    : '—'
                                                                }}

                                                            </div>

                                                        </div>


                                                        <div class="col-md-4">

                                                            <small class="text-muted">
                                                                Paid At
                                                            </small>

                                                            <div class="fw-semibold">

                                                                {{ $billing->paid_at
                                                                    ? $billing->paid_at->format('d M Y, h:i A')
                                                                    : '—'
                                                                }}

                                                            </div>

                                                        </div>


                                                        <div class="col-md-4">

                                                            <small class="text-muted">
                                                                Due At
                                                            </small>

                                                            <div class="fw-semibold">

                                                                {{ $billing->due_at
                                                                    ? $billing->due_at->format('d M Y, h:i A')
                                                                    : '—'
                                                                }}

                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>


                                            {{-- Description --}}
                                            @if($billing->description)

                                                <div class="col-12">

                                                    <div class="border rounded p-3">

                                                        <h6 class="fw-bold mb-2">
                                                            <i class="fas fa-align-left me-2 text-primary"></i>
                                                            Description
                                                        </h6>

                                                        <p class="mb-0 text-muted">
                                                            {{ $billing->description }}
                                                        </p>

                                                    </div>

                                                </div>

                                            @endif

                                        </div>

                                    </div>


                                    <div class="modal-footer">

                                        <button
                                            type="button"
                                            class="btn btn-light border"
                                            data-bs-dismiss="modal"
                                        >
                                            <i class="fas fa-times me-1"></i>
                                            Close
                                        </button>

                                    </div>

                                </div>

                            </div>

                        </div>

                    @empty

                        <tr>

                            <td colspan="9" class="text-center py-5">

                                <div class="text-muted">

                                    <i class="fas fa-file-invoice-dollar fa-3x mb-3 opacity-50"></i>

                                    <h6 class="fw-bold">
                                        No Billing Records Found
                                    </h6>

                                    <p class="mb-0">
                                        There are no billing records matching your filters.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- =====================================================
            PAGINATION
        ====================================================== --}}
        @if($billingHistories->hasPages())

            <div class="card-footer bg-white border-0 py-3">

                {{ $billingHistories->links() }}

            </div>

        @endif

    </div>

</div>

@endsection