@extends('layouts.admin')

@section('title', 'Vendor Payouts')

@section('page')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h4 class="fw-bold mb-1">
                <i class="fas fa-money-check-alt me-2"></i>
                Vendor Payouts
            </h4>

            <p class="text-muted mb-0">
                Manage vendor earnings, tax deductions, payout processing and settlements.
            </p>
        </div>

        <div class="mt-3 mt-md-0">

            <a href="{{ route('admin.vendor-payouts.pending') }}"
               class="btn btn-warning">

                <i class="fas fa-clock me-1"></i>
                Pending Payouts

            </a>

        </div>

    </div>


    {{-- =========================================================
        SUCCESS / ERROR MESSAGE
    ========================================================== --}}

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


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="fas fa-exclamation-circle me-2"></i>

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}

    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show">

            <div class="fw-bold mb-2">

                <i class="fas fa-exclamation-triangle me-2"></i>

                Please fix the following errors:

            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

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


        {{-- =====================================================
            PENDING
        ====================================================== --}}

        <div class="col-xl col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Pending Amount
                            </p>

                            <h4 class="fw-bold mb-0">

                                {{ number_format((float) $pendingAmount, 2) }}

                            </h4>

                            <small class="text-muted">
                                Net payout payable
                            </small>

                        </div>

                        <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-3">

                            <i class="fas fa-clock fa-lg"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            PROCESSING
        ====================================================== --}}

        <div class="col-xl col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Processing Amount
                            </p>

                            <h4 class="fw-bold mb-0">

                                {{ number_format((float) $processingAmount, 2) }}

                            </h4>

                            <small class="text-muted">
                                Net payout processing
                            </small>

                        </div>

                        <div class="bg-info bg-opacity-10 text-info rounded-circle p-3">

                            <i class="fas fa-spinner fa-lg"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            COMPLETED
        ====================================================== --}}

        <div class="col-xl col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Completed Payouts
                            </p>

                            <h4 class="fw-bold mb-0">

                                {{ number_format((float) $completedAmount, 2) }}

                            </h4>

                            <small class="text-muted">
                                Net amount paid
                            </small>

                        </div>

                        <div class="bg-success bg-opacity-10 text-success rounded-circle p-3">

                            <i class="fas fa-check-circle fa-lg"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            THIS MONTH
        ====================================================== --}}

        <div class="col-xl col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Paid This Month
                            </p>

                            <h4 class="fw-bold mb-0">

                                {{ number_format((float) $thisMonthAmount, 2) }}

                            </h4>

                            <small class="text-muted">
                                Net payout paid
                            </small>

                        </div>

                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3">

                            <i class="fas fa-calendar-check fa-lg"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            VENDORS
        ====================================================== --}}

        <div class="col-xl col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <p class="text-muted mb-1">
                                Vendors Paid
                            </p>

                            <h4 class="fw-bold mb-0">

                                {{ number_format($totalVendorsPaid) }}

                            </h4>

                            <small class="text-muted">
                                Unique vendors
                            </small>

                        </div>

                        <div class="bg-secondary bg-opacity-10 text-secondary rounded-circle p-3">

                            <i class="fas fa-users fa-lg"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        TAX INFORMATION
    ========================================================== --}}

    <div class="alert alert-info border-0 shadow-sm mb-4">

        <div class="d-flex align-items-start">

            <div class="me-3">

                <i class="fas fa-info-circle fa-lg"></i>

            </div>

            <div>

                <h6 class="fw-bold mb-1">
                    Vendor Payout Tax
                </h6>

                <p class="mb-0 small">

                    Vendor earning is calculated before payout tax.
                    The applicable tax is deducted from the vendor earning,
                    and the remaining amount is paid as the net payout.

                </p>

            </div>

        </div>

    </div>


    {{-- =========================================================
        FILTER CARD
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex align-items-center">

                <i class="fas fa-filter me-2 text-primary"></i>

                <h6 class="fw-bold mb-0">
                    Filter Payouts
                </h6>

            </div>

        </div>


        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.vendor-payouts.index') }}">

                <div class="row g-3">


                    {{-- =================================================
                        SEARCH
                    ================================================== --}}

                    <div class="col-lg-3 col-md-6">

                        <label class="form-label fw-semibold">
                            Search
                        </label>

                        <input type="text"
                               name="search"
                               class="form-control"
                               value="{{ request('search') }}"
                               placeholder="Payout code, vendor, booking...">

                    </div>


                    {{-- =================================================
                        STATUS
                    ================================================== --}}

                    <div class="col-lg-2 col-md-6">

                        <label class="form-label fw-semibold">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="">
                                All Status
                            </option>

                            <option value="pending"
                                @selected(request('status') === 'pending')>
                                Pending
                            </option>

                            <option value="processing"
                                @selected(request('status') === 'processing')>
                                Processing
                            </option>

                            <option value="completed"
                                @selected(request('status') === 'completed')>
                                Completed
                            </option>

                            <option value="rejected"
                                @selected(request('status') === 'rejected')>
                                Rejected
                            </option>

                            <option value="failed"
                                @selected(request('status') === 'failed')>
                                Failed
                            </option>

                        </select>

                    </div>


                    {{-- =================================================
                        PAYMENT METHOD
                    ================================================== --}}

                    <div class="col-lg-2 col-md-6">

                        <label class="form-label fw-semibold">
                            Payment Method
                        </label>

                        <select name="payment_method"
                                class="form-select">

                            <option value="">
                                All Methods
                            </option>

                            <option value="bank_transfer"
                                @selected(request('payment_method') === 'bank_transfer')>
                                Bank Transfer
                            </option>

                            <option value="mobile_banking"
                                @selected(request('payment_method') === 'mobile_banking')>
                                Mobile Banking
                            </option>

                            <option value="cash"
                                @selected(request('payment_method') === 'cash')>
                                Cash
                            </option>

                            <option value="other"
                                @selected(request('payment_method') === 'other')>
                                Other
                            </option>

                        </select>

                    </div>


                    {{-- =================================================
                        DATE FROM
                    ================================================== --}}

                    <div class="col-lg-2 col-md-6">

                        <label class="form-label fw-semibold">
                            From Date
                        </label>

                        <input type="date"
                               name="date_from"
                               class="form-control"
                               value="{{ request('date_from') }}">

                    </div>


                    {{-- =================================================
                        DATE TO
                    ================================================== --}}

                    <div class="col-lg-2 col-md-6">

                        <label class="form-label fw-semibold">
                            To Date
                        </label>

                        <input type="date"
                               name="date_to"
                               class="form-control"
                               value="{{ request('date_to') }}">

                    </div>


                    {{-- =================================================
                        BUTTONS
                    ================================================== --}}

                    <div class="col-lg-1 col-md-6 d-flex align-items-end">

                        <div class="d-flex gap-2 w-100">

                            <button type="submit"
                                    class="btn btn-primary w-100"
                                    title="Search">

                                <i class="fas fa-search"></i>

                            </button>


                            <a href="{{ route('admin.vendor-payouts.index') }}"
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
        PAYOUT TABLE
    ========================================================== --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center">

                <div>

                    <h6 class="fw-bold mb-1">
                        Vendor Payout List
                    </h6>

                    <small class="text-muted">
                        Manage vendor settlement records and tax deductions.
                    </small>

                </div>


                <div class="mt-2 mt-md-0">

                    <span class="badge bg-light text-dark border">

                        Total:
                        {{ $payouts->total() }}

                    </span>

                </div>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="ps-4">
                                Payout
                            </th>

                            <th>
                                Vendor
                            </th>

                            <th>
                                Booking
                            </th>

                            <th>
                                Vendor Earning
                            </th>

                            <th>
                                Tax
                            </th>

                            <th>
                                Net Payout
                            </th>

                            <th>
                                Method
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Paid Date
                            </th>

                            <th class="text-end pe-4">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($payouts as $payout)

                            @php

                                /*
                                |--------------------------------------------------------------------------
                                | Tax-aware payout calculations
                                |--------------------------------------------------------------------------
                                */

                                $vendorEarning = (float) (
                                    $payout->commission?->vendor_earning ?? 0
                                );

                                $taxAmount = (float) (
                                    $payout->tax_amount ?? 0
                                );

                                $netPayout = (float) (
                                    $payout->amount ?? 0
                                );

                            @endphp


                            <tr>


                                {{-- =================================================
                                    PAYOUT
                                ================================================== --}}

                                <td class="ps-4">

                                    <div class="fw-semibold">

                                        {{ $payout->payout_code }}

                                    </div>

                                    <small class="text-muted">

                                        {{ $payout->created_at?->format('d M Y, h:i A') }}

                                    </small>

                                </td>


                                {{-- =================================================
                                    VENDOR
                                ================================================== --}}

                                <td>

                                    @if($payout->vendor)

                                        <div class="fw-semibold">

                                            {{ $payout->vendor->business_name }}

                                        </div>

                                    @else

                                        <span class="text-muted">
                                            N/A
                                        </span>

                                    @endif

                                </td>


                                {{-- =================================================
                                    BOOKING
                                ================================================== --}}

                                <td>

                                    @if($payout->booking)

                                        <a href="{{ route('admin.bookings.show', $payout->booking->id) }}"
                                           class="text-decoration-none fw-semibold">

                                            {{ $payout->booking->booking_code }}

                                        </a>

                                    @else

                                        <span class="text-muted">
                                            N/A
                                        </span>

                                    @endif

                                </td>


                                {{-- =================================================
                                    VENDOR EARNING
                                ================================================== --}}

                                <td>

                                    <div class="fw-bold">

                                        {{ number_format($vendorEarning, 2) }}

                                    </div>

                                    <small class="text-muted">

                                        Gross earning

                                    </small>

                                </td>


                                {{-- =================================================
                                    TAX
                                ================================================== --}}

                                <td>

                                    @if($taxAmount > 0)

                                        <div class="fw-semibold text-danger">

                                            - {{ number_format($taxAmount, 2) }}

                                        </div>

                                        <small class="text-danger">

                                            Tax deducted

                                        </small>

                                    @else

                                        <span class="text-muted">

                                            0.00

                                        </span>

                                        <br>

                                        <small class="text-muted">

                                            No tax

                                        </small>

                                    @endif

                                </td>


                                {{-- =================================================
                                    NET PAYOUT
                                ================================================== --}}

                                <td>

                                    <div class="fw-bold text-success">

                                        {{ number_format($netPayout, 2) }}

                                    </div>

                                    <small class="text-muted">

                                        Vendor receives

                                    </small>

                                </td>


                                {{-- =================================================
                                    PAYMENT METHOD
                                ================================================== --}}

                                <td>

                                    @if($payout->payment_method)

                                        @php

                                            $methodLabels = [

                                                'bank_transfer' =>
                                                    'Bank Transfer',

                                                'mobile_banking' =>
                                                    'Mobile Banking',

                                                'cash' =>
                                                    'Cash',

                                                'other' =>
                                                    'Other',

                                            ];

                                        @endphp


                                        <span>

                                            {{ $methodLabels[$payout->payment_method]
                                                ?? ucfirst(
                                                    str_replace(
                                                        '_',
                                                        ' ',
                                                        $payout->payment_method
                                                    )
                                                )
                                            }}

                                        </span>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- =================================================
                                    STATUS
                                ================================================== --}}

                                <td>

                                    @switch($payout->status)


                                        @case('pending')

                                            <span class="badge bg-warning text-dark">

                                                <i class="fas fa-clock me-1"></i>

                                                Pending

                                            </span>

                                            @break


                                        @case('processing')

                                            <span class="badge bg-info">

                                                <i class="fas fa-spinner me-1"></i>

                                                Processing

                                            </span>

                                            @break


                                        @case('completed')

                                            <span class="badge bg-success">

                                                <i class="fas fa-check-circle me-1"></i>

                                                Completed

                                            </span>

                                            @break


                                        @case('rejected')

                                            <span class="badge bg-danger">

                                                <i class="fas fa-ban me-1"></i>

                                                Rejected

                                            </span>

                                            @break


                                        @case('failed')

                                            <span class="badge bg-secondary">

                                                <i class="fas fa-times-circle me-1"></i>

                                                Failed

                                            </span>

                                            @break


                                        @default

                                            <span class="badge bg-light text-dark">

                                                {{ ucfirst($payout->status) }}

                                            </span>

                                    @endswitch

                                </td>


                                {{-- =================================================
                                    PAID DATE
                                ================================================== --}}

                                <td>

                                    @if($payout->paid_at)

                                        <div>

                                            {{ $payout->paid_at->format('d M Y') }}

                                        </div>

                                        <small class="text-muted">

                                            {{ $payout->paid_at->format('h:i A') }}

                                        </small>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- =================================================
                                    ACTIONS
                                ================================================== --}}

                                <td class="text-end pe-4">

                                    <div class="dropdown">

                                        <button class="btn btn-sm btn-light border"
                                                type="button"
                                                data-bs-toggle="dropdown"
                                                aria-expanded="false">

                                            <i class="fas fa-ellipsis-v"></i>

                                        </button>


                                        <ul class="dropdown-menu dropdown-menu-end">


                                            {{-- =================================================
                                                VIEW
                                            ================================================== --}}

                                            <li>

                                                <a class="dropdown-item"
                                                   href="{{ route('admin.vendor-payouts.show', $payout->id) }}">

                                                    <i class="fas fa-eye text-primary me-2"></i>

                                                    View Details

                                                </a>

                                            </li>


                                            {{-- =================================================
                                                PROCESS
                                            ================================================== --}}

                                            @if($payout->status === 'pending')

                                                <li>

                                                    <form method="POST"
                                                          action="{{ route('admin.vendor-payouts.process', $payout->id) }}">

                                                        @csrf

                                                        <button type="submit"
                                                                class="dropdown-item">

                                                            <i class="fas fa-spinner text-info me-2"></i>

                                                            Process Payout

                                                        </button>

                                                    </form>

                                                </li>

                                            @endif


                                            {{-- =================================================
                                                COMPLETE
                                            ================================================== --}}

                                            @if(in_array($payout->status, ['pending', 'processing']))

                                                <li>

                                                    <a href="{{ route('admin.vendor-payouts.show', $payout->id) }}"
                                                       class="dropdown-item">

                                                        <i class="fas fa-check-circle text-success me-2"></i>

                                                        Complete Payout

                                                    </a>

                                                </li>

                                            @endif


                                            {{-- =================================================
                                                REJECT
                                            ================================================== --}}

                                            @if(in_array($payout->status, ['pending', 'processing']))

                                                <li>

                                                    <hr class="dropdown-divider">

                                                </li>


                                                <li>

                                                    <button type="button"
                                                            class="dropdown-item text-danger"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#rejectModal{{ $payout->id }}">

                                                        <i class="fas fa-ban me-2"></i>

                                                        Reject Payout

                                                    </button>

                                                </li>

                                            @endif


                                            {{-- =================================================
                                                FAILED
                                            ================================================== --}}

                                            @if($payout->status === 'processing')

                                                <li>

                                                    <button type="button"
                                                            class="dropdown-item text-danger"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#failedModal{{ $payout->id }}">

                                                        <i class="fas fa-times-circle me-2"></i>

                                                        Mark as Failed

                                                    </button>

                                                </li>

                                            @endif


                                        </ul>

                                    </div>

                                </td>

                            </tr>


                            {{-- =========================================================
                                REJECT MODAL
                            ========================================================== --}}

                            @if(in_array($payout->status, ['pending', 'processing']))

                                <div class="modal fade"
                                     id="rejectModal{{ $payout->id }}"
                                     tabindex="-1"
                                     aria-hidden="true">

                                    <div class="modal-dialog">

                                        <div class="modal-content">


                                            {{-- Header --}}

                                            <div class="modal-header">

                                                <h5 class="modal-title">

                                                    <i class="fas fa-ban text-danger me-2"></i>

                                                    Reject Vendor Payout

                                                </h5>

                                                <button type="button"
                                                        class="btn-close"
                                                        data-bs-dismiss="modal">
                                                </button>

                                            </div>


                                            <form method="POST"
                                                  action="{{ route('admin.vendor-payouts.reject', $payout->id) }}">

                                                @csrf


                                                <div class="modal-body">


                                                    {{-- Payout Summary --}}

                                                    <div class="alert alert-warning">


                                                        <div class="fw-bold mb-2">

                                                            {{ $payout->payout_code }}

                                                        </div>


                                                        <div class="row g-2 small">


                                                            <div class="col-6">

                                                                <span class="text-muted">
                                                                    Vendor Earning
                                                                </span>

                                                                <div class="fw-bold">

                                                                    {{ number_format($vendorEarning, 2) }}

                                                                </div>

                                                            </div>


                                                            <div class="col-6">

                                                                <span class="text-muted">
                                                                    Tax Deducted
                                                                </span>

                                                                <div class="fw-bold text-danger">

                                                                    {{ number_format($taxAmount, 2) }}

                                                                </div>

                                                            </div>


                                                            <div class="col-12">

                                                                <hr class="my-2">

                                                            </div>


                                                            <div class="col-12">

                                                                <span class="text-muted">
                                                                    Net Payout
                                                                </span>

                                                                <div class="fw-bold text-success fs-5">

                                                                    {{ number_format($netPayout, 2) }}

                                                                </div>

                                                            </div>


                                                        </div>

                                                    </div>


                                                    {{-- Rejection Reason --}}

                                                    <label class="form-label fw-semibold">

                                                        Rejection Reason

                                                        <span class="text-danger">
                                                            *
                                                        </span>

                                                    </label>


                                                    <textarea name="admin_note"
                                                              class="form-control"
                                                              rows="4"
                                                              required
                                                              placeholder="Enter the reason for rejecting this payout..."></textarea>

                                                </div>


                                                <div class="modal-footer">

                                                    <button type="button"
                                                            class="btn btn-light"
                                                            data-bs-dismiss="modal">

                                                        Cancel

                                                    </button>


                                                    <button type="submit"
                                                            class="btn btn-danger">

                                                        <i class="fas fa-ban me-1"></i>

                                                        Reject Payout

                                                    </button>

                                                </div>

                                            </form>

                                        </div>

                                    </div>

                                </div>

                            @endif


                            {{-- =========================================================
                                FAILED MODAL
                            ========================================================== --}}

                            @if($payout->status === 'processing')

                                <div class="modal fade"
                                     id="failedModal{{ $payout->id }}"
                                     tabindex="-1"
                                     aria-hidden="true">

                                    <div class="modal-dialog">

                                        <div class="modal-content">


                                            {{-- Header --}}

                                            <div class="modal-header">

                                                <h5 class="modal-title">

                                                    <i class="fas fa-times-circle text-danger me-2"></i>

                                                    Mark Payout As Failed

                                                </h5>

                                                <button type="button"
                                                        class="btn-close"
                                                        data-bs-dismiss="modal">
                                                </button>

                                            </div>


                                            <form method="POST"
                                                  action="{{ route('admin.vendor-payouts.fail', $payout->id) }}">

                                                @csrf


                                                <div class="modal-body">


                                                    {{-- Payout Summary --}}

                                                    <div class="alert alert-danger">


                                                        <div class="fw-bold mb-2">

                                                            Payout:
                                                            {{ $payout->payout_code }}

                                                        </div>


                                                        <div class="row g-2 small">


                                                            <div class="col-6">

                                                                <span class="text-muted">
                                                                    Vendor Earning
                                                                </span>

                                                                <div class="fw-bold">

                                                                    {{ number_format($vendorEarning, 2) }}

                                                                </div>

                                                            </div>


                                                            <div class="col-6">

                                                                <span class="text-muted">
                                                                    Tax Deducted
                                                                </span>

                                                                <div class="fw-bold">

                                                                    {{ number_format($taxAmount, 2) }}

                                                                </div>

                                                            </div>


                                                            <div class="col-12">

                                                                <hr class="my-2">

                                                            </div>


                                                            <div class="col-12">

                                                                <span class="text-muted">
                                                                    Net Payout
                                                                </span>

                                                                <div class="fw-bold fs-5">

                                                                    {{ number_format($netPayout, 2) }}

                                                                </div>

                                                            </div>


                                                        </div>

                                                    </div>


                                                    {{-- Failure Reason --}}

                                                    <label class="form-label fw-semibold">

                                                        Failure Reason

                                                        <span class="text-danger">
                                                            *
                                                        </span>

                                                    </label>


                                                    <textarea name="admin_note"
                                                              class="form-control"
                                                              rows="4"
                                                              required
                                                              placeholder="Enter the reason why this payout failed..."></textarea>

                                                </div>


                                                <div class="modal-footer">

                                                    <button type="button"
                                                            class="btn btn-light"
                                                            data-bs-dismiss="modal">

                                                        Cancel

                                                    </button>


                                                    <button type="submit"
                                                            class="btn btn-danger">

                                                        <i class="fas fa-times-circle me-1"></i>

                                                        Mark Failed

                                                    </button>

                                                </div>

                                            </form>

                                        </div>

                                    </div>

                                </div>

                            @endif


                        @empty

                            {{-- =================================================
                                EMPTY STATE
                            ================================================== --}}

                            <tr>

                                <td colspan="10"
                                    class="text-center py-5">

                                    <div class="text-muted">

                                        <i class="fas fa-money-check-alt fa-3x mb-3 opacity-50"></i>


                                        <h6 class="fw-bold">

                                            No Vendor Payouts Found

                                        </h6>


                                        <p class="mb-0">

                                            There are no payout records matching your filters.

                                        </p>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- =========================================================
            PAGINATION
        ========================================================== --}}

        @if($payouts->hasPages())

            <div class="card-footer bg-white border-0 py-3">

                <div class="d-flex justify-content-between align-items-center flex-wrap">


                    <div class="text-muted small">

                        Showing

                        <strong>
                            {{ $payouts->firstItem() ?? 0 }}
                        </strong>

                        to

                        <strong>
                            {{ $payouts->lastItem() ?? 0 }}
                        </strong>

                        of

                        <strong>
                            {{ $payouts->total() }}
                        </strong>

                        payouts

                    </div>


                    <div class="mt-2 mt-md-0">

                        {{ $payouts->links() }}

                    </div>


                </div>

            </div>

        @endif

    </div>

</div>

@endsection