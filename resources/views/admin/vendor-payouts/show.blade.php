@extends('layouts.admin')

@section('title', 'Vendor Payout Details')

@section('page')

@php

    /*
    |--------------------------------------------------------------------------
    | Financial Calculations
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

    /*
    |--------------------------------------------------------------------------
    | Tax Percentage
    |--------------------------------------------------------------------------
    */

    $effectiveTaxRate = 0;

    if ($vendorEarning > 0 && $taxAmount > 0) {

        $effectiveTaxRate = round(
            ($taxAmount / $vendorEarning) * 100,
            2
        );

    }

    /*
    |--------------------------------------------------------------------------
    | Status Configuration
    |--------------------------------------------------------------------------
    */

    $statusConfig = [

        'pending' => [
            'label' => 'Pending',
            'class' => 'warning',
            'icon' => 'clock',
        ],

        'processing' => [
            'label' => 'Processing',
            'class' => 'info',
            'icon' => 'spinner',
        ],

        'completed' => [
            'label' => 'Completed',
            'class' => 'success',
            'icon' => 'check-circle',
        ],

        'rejected' => [
            'label' => 'Rejected',
            'class' => 'danger',
            'icon' => 'ban',
        ],

        'failed' => [
            'label' => 'Failed',
            'class' => 'secondary',
            'icon' => 'times-circle',
        ],

    ];

    $currentStatus = $statusConfig[$payout->status] ?? [
        'label' => ucfirst($payout->status),
        'class' => 'light',
        'icon' => 'question-circle',
    ];


    /*
    |--------------------------------------------------------------------------
    | Payment Method
    |--------------------------------------------------------------------------
    */

    $methodLabels = [

        'bank_transfer' => 'Bank Transfer',

        'mobile_banking' => 'Mobile Banking',

        'cash' => 'Cash',

        'other' => 'Other',

    ];

    $paymentMethod = $payout->payment_method
        ? (
            $methodLabels[$payout->payment_method]
            ?? ucfirst(
                str_replace(
                    '_',
                    ' ',
                    $payout->payment_method
                )
            )
        )
        : 'Not Selected';


    /*
    |--------------------------------------------------------------------------
    | Dates
    |--------------------------------------------------------------------------
    */

    $createdAt = $payout->created_at;

    $processedAt = $payout->processed_at;

    $paidAt = $payout->paid_at;

@endphp


<div class="container-fluid">


    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>

            <div class="d-flex align-items-center gap-2 mb-1">

                <h4 class="fw-bold mb-0">

                    <i class="fas fa-money-check-alt me-2"></i>

                    Vendor Payout Details

                </h4>


                <span class="badge bg-{{ $currentStatus['class'] }}
                    {{ $currentStatus['class'] === 'warning' ? 'text-dark' : '' }}">

                    <i class="fas fa-{{ $currentStatus['icon'] }} me-1"></i>

                    {{ $currentStatus['label'] }}

                </span>

            </div>


            <p class="text-muted mb-0">

                Review vendor earning, tax deduction and payout settlement details.

            </p>

        </div>


        <div class="mt-3 mt-md-0 d-flex gap-2">

            <a href="{{ route('admin.vendor-payouts.index') }}"
               class="btn btn-light border">

                <i class="fas fa-arrow-left me-1"></i>

                Back to Payouts

            </a>

        </div>

    </div>


    {{-- =========================================================
        SUCCESS / ERROR MESSAGES
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
        PAYOUT SUMMARY
    ========================================================== --}}

    <div class="row g-3 mb-4">


        {{-- =====================================================
            GROSS VENDOR EARNING
        ====================================================== --}}

        <div class="col-xl-4 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>

                            <p class="text-muted mb-1">
                                Vendor Earning
                            </p>

                            <h3 class="fw-bold mb-1">

                                {{ number_format($vendorEarning, 2) }}

                            </h3>

                            <small class="text-muted">

                                Gross vendor earning

                            </small>

                        </div>


                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3">

                            <i class="fas fa-coins fa-lg"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            TAX
        ====================================================== --}}

        <div class="col-xl-4 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>

                            <p class="text-muted mb-1">
                                Tax Deducted
                            </p>

                            <h3 class="fw-bold text-danger mb-1">

                                - {{ number_format($taxAmount, 2) }}

                            </h3>


                            @if($effectiveTaxRate > 0)

                                <small class="text-danger">

                                    {{ number_format($effectiveTaxRate, 2) }}% effective tax

                                </small>

                            @else

                                <small class="text-muted">

                                    No tax deducted

                                </small>

                            @endif

                        </div>


                        <div class="bg-danger bg-opacity-10 text-danger rounded-circle p-3">

                            <i class="fas fa-file-invoice-dollar fa-lg"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            NET PAYOUT
        ====================================================== --}}

        <div class="col-xl-4 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>

                            <p class="text-muted mb-1">
                                Net Payout
                            </p>

                            <h3 class="fw-bold text-success mb-1">

                                {{ number_format($netPayout, 2) }}

                            </h3>

                            <small class="text-success">

                                Final amount vendor receives

                            </small>

                        </div>


                        <div class="bg-success bg-opacity-10 text-success rounded-circle p-3">

                            <i class="fas fa-hand-holding-usd fa-lg"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        FINANCIAL BREAKDOWN
    ========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex align-items-center">

                <i class="fas fa-calculator text-primary me-2"></i>

                <h6 class="fw-bold mb-0">

                    Payout Financial Breakdown

                </h6>

            </div>

        </div>


        <div class="card-body">


            <div class="row g-4">


                {{-- Gross --}}

                <div class="col-md-4">

                    <div class="border rounded p-3 h-100">

                        <div class="d-flex justify-content-between">

                            <span class="text-muted">

                                Gross Vendor Earning

                            </span>

                            <i class="fas fa-coins text-primary"></i>

                        </div>


                        <h4 class="fw-bold mt-2 mb-0">

                            {{ number_format($vendorEarning, 2) }}

                        </h4>


                        <small class="text-muted">

                            Commission vendor earning

                        </small>

                    </div>

                </div>


                {{-- Tax --}}

                <div class="col-md-4">

                    <div class="border rounded p-3 h-100">

                        <div class="d-flex justify-content-between">

                            <span class="text-muted">

                                Less: Tax

                            </span>

                            <i class="fas fa-minus-circle text-danger"></i>

                        </div>


                        <h4 class="fw-bold text-danger mt-2 mb-0">

                            - {{ number_format($taxAmount, 2) }}

                        </h4>


                        @if($effectiveTaxRate > 0)

                            <small class="text-danger">

                                {{ number_format($effectiveTaxRate, 2) }}%

                            </small>

                        @else

                            <small class="text-muted">

                                0% tax

                            </small>

                        @endif

                    </div>

                </div>


                {{-- Net --}}

                <div class="col-md-4">

                    <div class="border rounded p-3 h-100">

                        <div class="d-flex justify-content-between">

                            <span class="text-muted">

                                Net Vendor Payout

                            </span>

                            <i class="fas fa-check-circle text-success"></i>

                        </div>


                        <h4 class="fw-bold text-success mt-2 mb-0">

                            {{ number_format($netPayout, 2) }}

                        </h4>


                        <small class="text-success">

                            Gross earning − tax

                        </small>

                    </div>

                </div>


            </div>


            {{-- Calculation Formula --}}

            <div class="alert alert-light border mt-4 mb-0">

                <div class="d-flex align-items-start">

                    <i class="fas fa-info-circle text-primary mt-1 me-2"></i>

                    <div>

                        <div class="fw-semibold mb-1">

                            Payout Calculation

                        </div>

                        <div class="small text-muted">

                            {{ number_format($vendorEarning, 2) }}

                            −

                            {{ number_format($taxAmount, 2) }}

                            =

                            <strong class="text-success">

                                {{ number_format($netPayout, 2) }}

                            </strong>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        MAIN CONTENT
    ========================================================== --}}

    <div class="row g-4">


        {{-- =====================================================
            LEFT COLUMN
        ====================================================== --}}

        <div class="col-xl-8">


            {{-- =================================================
                PAYOUT INFORMATION
            ================================================== --}}

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white border-0 py-3">

                    <div class="d-flex align-items-center">

                        <i class="fas fa-money-check-alt text-primary me-2"></i>

                        <h6 class="fw-bold mb-0">

                            Payout Information

                        </h6>

                    </div>

                </div>


                <div class="card-body">


                    <div class="row g-4">


                        {{-- Payout Code --}}

                        <div class="col-md-6">

                            <label class="text-muted small mb-1">

                                Payout Code

                            </label>

                            <div class="fw-bold">

                                {{ $payout->payout_code }}

                            </div>

                        </div>


                        {{-- Status --}}

                        <div class="col-md-6">

                            <label class="text-muted small mb-1">

                                Status

                            </label>

                            <div>

                                <span class="badge bg-{{ $currentStatus['class'] }}
                                    {{ $currentStatus['class'] === 'warning' ? 'text-dark' : '' }}">

                                    <i class="fas fa-{{ $currentStatus['icon'] }} me-1"></i>

                                    {{ $currentStatus['label'] }}

                                </span>

                            </div>

                        </div>


                        {{-- Payment Method --}}

                        <div class="col-md-6">

                            <label class="text-muted small mb-1">

                                Payment Method

                            </label>

                            <div class="fw-semibold">

                                <i class="fas fa-credit-card text-muted me-1"></i>

                                {{ $paymentMethod }}

                            </div>

                        </div>


                        {{-- Reference ID --}}

                        <div class="col-md-6">

                            <label class="text-muted small mb-1">

                                Payment Reference

                            </label>

                            <div class="fw-semibold">

                                @if($payout->reference_id)

                                    {{ $payout->reference_id }}

                                @else

                                    <span class="text-muted">
                                        Not Available
                                    </span>

                                @endif

                            </div>

                        </div>


                        {{-- Created At --}}

                        <div class="col-md-6">

                            <label class="text-muted small mb-1">

                                Created At

                            </label>

                            <div class="fw-semibold">

                                @if($createdAt)

                                    {{ $createdAt->format('d M Y, h:i A') }}

                                @else

                                    —

                                @endif

                            </div>

                        </div>


                        {{-- Processed At --}}

                        <div class="col-md-6">

                            <label class="text-muted small mb-1">

                                Processed At

                            </label>

                            <div class="fw-semibold">

                                @if($processedAt)

                                    {{ $processedAt->format('d M Y, h:i A') }}

                                @else

                                    <span class="text-muted">
                                        Not Processed
                                    </span>

                                @endif

                            </div>

                        </div>


                        {{-- Paid At --}}

                        <div class="col-md-6">

                            <label class="text-muted small mb-1">

                                Paid At

                            </label>

                            <div class="fw-semibold">

                                @if($paidAt)

                                    {{ $paidAt->format('d M Y, h:i A') }}

                                @else

                                    <span class="text-muted">
                                        Not Paid
                                    </span>

                                @endif

                            </div>

                        </div>


                        {{-- Admin Note --}}

                        <div class="col-12">

                            <label class="text-muted small mb-1">

                                Admin Note

                            </label>


                            @if($payout->admin_note)

                                <div class="bg-light border rounded p-3">

                                    {{ $payout->admin_note }}

                                </div>

                            @else

                                <div class="text-muted">

                                    No admin note available.

                                </div>

                            @endif

                        </div>


                    </div>

                </div>

            </div>


            {{-- =================================================
                VENDOR INFORMATION
            ================================================== --}}

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white border-0 py-3">

                    <div class="d-flex align-items-center">

                        <i class="fas fa-store text-primary me-2"></i>

                        <h6 class="fw-bold mb-0">

                            Vendor Information

                        </h6>

                    </div>

                </div>


                <div class="card-body">


                    @if($payout->vendor)

                        <div class="d-flex align-items-center">


                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 me-3">

                                <i class="fas fa-store fa-lg"></i>

                            </div>


                            <div>

                                <h6 class="fw-bold mb-1">

                                    {{ $payout->vendor->business_name }}

                                </h6>


                                @if(isset($payout->vendor->email))

                                    <div class="text-muted small">

                                        <i class="fas fa-envelope me-1"></i>

                                        {{ $payout->vendor->email }}

                                    </div>

                                @endif


                                @if(isset($payout->vendor->phone))

                                    <div class="text-muted small">

                                        <i class="fas fa-phone me-1"></i>

                                        {{ $payout->vendor->phone }}

                                    </div>

                                @endif

                            </div>

                        </div>

                    @else

                        <div class="text-muted">

                            <i class="fas fa-store-slash me-2"></i>

                            Vendor information is not available.

                        </div>

                    @endif

                </div>

            </div>


            {{-- =================================================
                BOOKING INFORMATION
            ================================================== --}}

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white border-0 py-3">

                    <div class="d-flex align-items-center">

                        <i class="fas fa-ticket-alt text-primary me-2"></i>

                        <h6 class="fw-bold mb-0">

                            Booking Information

                        </h6>

                    </div>

                </div>


                <div class="card-body">


                    @if($payout->booking)

                        <div class="row g-4">


                            {{-- Booking Code --}}

                            <div class="col-md-6">

                                <label class="text-muted small mb-1">

                                    Booking Code

                                </label>

                                <div>

                                    <a href="{{ route('admin.bookings.show', $payout->booking->id) }}"
                                       class="fw-bold text-decoration-none">

                                        {{ $payout->booking->booking_code }}

                                    </a>

                                </div>

                            </div>


                            {{-- Booking Status --}}

                            <div class="col-md-6">

                                <label class="text-muted small mb-1">

                                    Booking Status

                                </label>

                                <div>

                                    @switch($payout->booking->booking_status)

                                        @case('pending')

                                            <span class="badge bg-warning text-dark">
                                                Pending
                                            </span>

                                            @break

                                        @case('processing')

                                            <span class="badge bg-info">
                                                Processing
                                            </span>

                                            @break

                                        @case('confirmed')

                                            <span class="badge bg-primary">
                                                Confirmed
                                            </span>

                                            @break

                                        @case('completed')

                                            <span class="badge bg-success">
                                                Completed
                                            </span>

                                            @break

                                        @case('cancelled')

                                            <span class="badge bg-danger">
                                                Cancelled
                                            </span>

                                            @break

                                        @default

                                            <span class="badge bg-light text-dark">

                                                {{ ucfirst($payout->booking->booking_status) }}

                                            </span>

                                    @endswitch

                                </div>

                            </div>


                            {{-- Person Count --}}

                            <div class="col-md-6">

                                <label class="text-muted small mb-1">

                                    Travelers

                                </label>

                                <div class="fw-semibold">

                                    <i class="fas fa-users text-muted me-1"></i>

                                    {{ $payout->booking->person_count }}

                                </div>

                            </div>


                            {{-- Booking Total --}}

                            <div class="col-md-6">

                                <label class="text-muted small mb-1">

                                    Booking Total

                                </label>

                                <div class="fw-bold">

                                    {{ number_format((float) $payout->booking->total_amount, 2) }}

                                </div>

                            </div>


                        </div>

                    @else

                        <div class="text-muted">

                            <i class="fas fa-ticket-alt me-2"></i>

                            Booking information is not available.

                        </div>

                    @endif

                </div>

            </div>


            {{-- =================================================
                COMMISSION INFORMATION
            ================================================== --}}

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white border-0 py-3">

                    <div class="d-flex align-items-center">

                        <i class="fas fa-chart-pie text-primary me-2"></i>

                        <h6 class="fw-bold mb-0">

                            Commission Information

                        </h6>

                    </div>

                </div>


                <div class="card-body">


                    @if($payout->commission)

                        <div class="row g-4">


                            {{-- Commission ID --}}

                            <div class="col-md-6">

                                <label class="text-muted small mb-1">

                                    Commission ID

                                </label>

                                <div class="fw-semibold">

                                    #{{ $payout->commission->id }}

                                </div>

                            </div>


                            {{-- Commission Rate --}}

                            <div class="col-md-6">

                                <label class="text-muted small mb-1">

                                    Commission Rate

                                </label>

                                <div class="fw-semibold">

                                    {{ number_format((float) $payout->commission->commission_rate, 2) }}%

                                </div>

                            </div>


                            {{-- Commission Base --}}

                            <div class="col-md-6">

                                <label class="text-muted small mb-1">

                                    Commission Base Amount

                                </label>

                                <div class="fw-semibold">

                                    {{ number_format((float) $payout->commission->total_amount, 2) }}

                                </div>

                            </div>


                            {{-- Admin Earning --}}

                            <div class="col-md-6">

                                <label class="text-muted small mb-1">

                                    Admin Earning

                                </label>

                                <div class="fw-bold text-primary">

                                    {{ number_format((float) $payout->commission->admin_earning, 2) }}

                                </div>

                            </div>


                            {{-- Vendor Earning --}}

                            <div class="col-md-6">

                                <label class="text-muted small mb-1">

                                    Gross Vendor Earning

                                </label>

                                <div class="fw-bold">

                                    {{ number_format((float) $payout->commission->vendor_earning, 2) }}

                                </div>

                            </div>


                            {{-- Tax --}}

                            <div class="col-md-6">

                                <label class="text-muted small mb-1">

                                    Payout Tax

                                </label>

                                <div class="fw-bold text-danger">

                                    {{ number_format($taxAmount, 2) }}

                                </div>

                            </div>


                        </div>

                    @else

                        <div class="text-muted">

                            <i class="fas fa-chart-pie me-2"></i>

                            Commission information is not available.

                        </div>

                    @endif

                </div>

            </div>


        </div>


        {{-- =====================================================
            RIGHT COLUMN
        ====================================================== --}}

        <div class="col-xl-4">


            {{-- =================================================
                CURRENT STATUS
            ================================================== --}}

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white border-0 py-3">

                    <h6 class="fw-bold mb-0">

                        <i class="fas fa-info-circle text-primary me-2"></i>

                        Current Status

                    </h6>

                </div>


                <div class="card-body">


                    <div class="text-center py-3">

                        <div class="bg-{{ $currentStatus['class'] }}
                            bg-opacity-10
                            text-{{ $currentStatus['class'] }}
                            rounded-circle
                            d-inline-flex
                            align-items-center
                            justify-content-center
                            p-4
                            mb-3">

                            <i class="fas fa-{{ $currentStatus['icon'] }} fa-2x"></i>

                        </div>


                        <h5 class="fw-bold mb-1">

                            {{ $currentStatus['label'] }}

                        </h5>


                        <p class="text-muted small mb-0">

                            Payout status

                        </p>

                    </div>


                    <hr>


                    {{-- Process --}}

                    @if($payout->status === 'pending')

                        <form method="POST"
                              action="{{ route('admin.vendor-payouts.process', $payout->id) }}"
                              class="mb-2">

                            @csrf

                            <button type="submit"
                                    class="btn btn-info text-white w-100">

                                <i class="fas fa-spinner me-1"></i>

                                Process Payout

                            </button>

                        </form>

                    @endif


                    {{-- Complete --}}

                    @if(in_array($payout->status, ['pending', 'processing']))

                        <button type="button"
                                class="btn btn-success w-100 mb-2"
                                data-bs-toggle="modal"
                                data-bs-target="#completeModal">

                            <i class="fas fa-check-circle me-1"></i>

                            Complete Payout

                        </button>

                    @endif


                    {{-- Reject --}}

                    @if(in_array($payout->status, ['pending', 'processing']))

                        <button type="button"
                                class="btn btn-outline-danger w-100 mb-2"
                                data-bs-toggle="modal"
                                data-bs-target="#rejectModal">

                            <i class="fas fa-ban me-1"></i>

                            Reject Payout

                        </button>

                    @endif


                    {{-- Failed --}}

                    @if($payout->status === 'processing')

                        <button type="button"
                                class="btn btn-outline-danger w-100"
                                data-bs-toggle="modal"
                                data-bs-target="#failedModal">

                            <i class="fas fa-times-circle me-1"></i>

                            Mark as Failed

                        </button>

                    @endif


                    @if(
                        !in_array(
                            $payout->status,
                            ['pending', 'processing']
                        )
                    )

                        <div class="alert alert-light border text-center mb-0">

                            <i class="fas fa-lock me-1"></i>

                            No further action available.

                        </div>

                    @endif

                </div>

            </div>


            {{-- =================================================
                TAX SUMMARY
            ================================================== --}}

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white border-0 py-3">

                    <h6 class="fw-bold mb-0">

                        <i class="fas fa-file-invoice-dollar text-danger me-2"></i>

                        Tax Summary

                    </h6>

                </div>


                <div class="card-body">


                    <div class="d-flex justify-content-between mb-3">

                        <span class="text-muted">
                            Gross Earning
                        </span>

                        <strong>

                            {{ number_format($vendorEarning, 2) }}

                        </strong>

                    </div>


                    <div class="d-flex justify-content-between mb-3">

                        <span class="text-muted">
                            Tax Deducted
                        </span>

                        <strong class="text-danger">

                            - {{ number_format($taxAmount, 2) }}

                        </strong>

                    </div>


                    <hr>


                    <div class="d-flex justify-content-between">

                        <span class="fw-bold">
                            Net Payout
                        </span>

                        <strong class="text-success fs-5">

                            {{ number_format($netPayout, 2) }}

                        </strong>

                    </div>


                    @if($effectiveTaxRate > 0)

                        <div class="alert alert-danger bg-opacity-10 mt-3 mb-0 small">

                            <i class="fas fa-percentage me-1"></i>

                            Effective tax rate:

                            <strong>

                                {{ number_format($effectiveTaxRate, 2) }}%

                            </strong>

                        </div>

                    @endif

                </div>

            </div>


            {{-- =================================================
                QUICK SUMMARY
            ================================================== --}}

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white border-0 py-3">

                    <h6 class="fw-bold mb-0">

                        <i class="fas fa-list-alt text-primary me-2"></i>

                        Quick Summary

                    </h6>

                </div>


                <div class="card-body">


                    <div class="mb-3">

                        <small class="text-muted d-block">

                            Payout Code

                        </small>

                        <strong>

                            {{ $payout->payout_code }}

                        </strong>

                    </div>


                    <div class="mb-3">

                        <small class="text-muted d-block">

                            Vendor

                        </small>

                        <strong>

                            {{ $payout->vendor?->business_name ?? 'N/A' }}

                        </strong>

                    </div>


                    <div class="mb-3">

                        <small class="text-muted d-block">

                            Booking

                        </small>

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

                    </div>


                    <div>

                        <small class="text-muted d-block">

                            Payment Method

                        </small>

                        <strong>

                            {{ $paymentMethod }}

                        </strong>

                    </div>

                </div>

            </div>


        </div>

    </div>


</div>


{{-- =============================================================
    COMPLETE PAYOUT MODAL
============================================================== --}}

@if(in_array($payout->status, ['pending', 'processing']))

    <div class="modal fade"
         id="completeModal"
         tabindex="-1"
         aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content">


                <div class="modal-header">

                    <h5 class="modal-title">

                        <i class="fas fa-check-circle text-success me-2"></i>

                        Complete Vendor Payout

                    </h5>


                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>

                </div>


                <form method="POST"
                      action="{{ route('admin.vendor-payouts.pay', $payout->id) }}">

                    @csrf


                    <div class="modal-body">


                        <div class="alert alert-success">

                            <div class="fw-bold mb-2">

                                {{ $payout->payout_code }}

                            </div>


                            <div class="small">

                                The following net amount will be marked as paid:

                            </div>


                            <div class="fs-4 fw-bold mt-2">

                                {{ number_format($netPayout, 2) }}

                            </div>


                            <small class="text-muted">

                                Vendor receives after tax deduction.

                            </small>

                        </div>


                        <div class="border rounded p-3">

                            <div class="d-flex justify-content-between mb-2">

                                <span class="text-muted">

                                    Gross Earning

                                </span>

                                <strong>

                                    {{ number_format($vendorEarning, 2) }}

                                </strong>

                            </div>


                            <div class="d-flex justify-content-between mb-2">

                                <span class="text-muted">

                                    Tax

                                </span>

                                <strong class="text-danger">

                                    - {{ number_format($taxAmount, 2) }}

                                </strong>

                            </div>


                            <hr>


                            <div class="d-flex justify-content-between">

                                <span class="fw-bold">

                                    Net Payout

                                </span>

                                <strong class="text-success">

                                    {{ number_format($netPayout, 2) }}

                                </strong>

                            </div>

                        </div>


                    </div>


                    <div class="modal-footer">

                        <button type="button"
                                class="btn btn-light"
                                data-bs-dismiss="modal">

                            Cancel

                        </button>


                        <button type="submit"
                                class="btn btn-success">

                            <i class="fas fa-check-circle me-1"></i>

                            Confirm Payment

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endif


{{-- =============================================================
    REJECT PAYOUT MODAL
============================================================== --}}

@if(in_array($payout->status, ['pending', 'processing']))

    <div class="modal fade"
         id="rejectModal"
         tabindex="-1"
         aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content">


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


                        <div class="alert alert-warning">

                            <strong>

                                {{ $payout->payout_code }}

                            </strong>


                            <div class="mt-2 small">

                                Net payout:

                                <strong>

                                    {{ number_format($netPayout, 2) }}

                                </strong>

                            </div>

                        </div>


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


{{-- =============================================================
    FAILED PAYOUT MODAL
============================================================== --}}

@if($payout->status === 'processing')

    <div class="modal fade"
         id="failedModal"
         tabindex="-1"
         aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content">


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


                        <div class="alert alert-danger">

                            <strong>

                                {{ $payout->payout_code }}

                            </strong>


                            <div class="mt-2">

                                Net Payout:

                                <strong>

                                    {{ number_format($netPayout, 2) }}

                                </strong>

                            </div>

                        </div>


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


@endsection