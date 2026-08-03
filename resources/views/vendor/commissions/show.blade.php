@extends('layouts.vendor')

@section('title', 'Commission Details')

@section('page')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>

            <div class="d-flex align-items-center gap-2 mb-1">

                <a
                    href="{{ route('vendor.commissions.index') }}"
                    class="btn btn-sm btn-light"
                >
                    <i class="bi bi-arrow-left"></i>
                </a>

                <h4 class="mb-0 fw-bold">
                    Commission Details
                </h4>

            </div>

            <p class="text-muted mb-0">
                View complete information about this commission.
            </p>

        </div>


        {{-- Booking Code --}}
        @if($commission->booking)

            <div>

                <span class="text-muted me-2">
                    Booking:
                </span>

                <span class="badge bg-primary fs-6">
                    {{ $commission->booking->booking_code ?? 'N/A' }}
                </span>

            </div>

        @endif

    </div>


    {{-- =========================================================
        MAIN CONTENT
    ========================================================== --}}
    <div class="row g-4">

        {{-- =====================================================
            LEFT SIDE
        ====================================================== --}}
        <div class="col-xl-8">


            {{-- =================================================
                COMMISSION SUMMARY
            ================================================== --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-receipt me-2 text-primary"></i>
                        Commission Summary
                    </h5>

                </div>


                <div class="card-body">

                    <div class="row g-4">


                        {{-- Total Amount --}}
                        <div class="col-md-4">

                            <div class="border rounded p-3 h-100">

                                <small class="text-muted d-block mb-1">
                                    Total Booking Amount
                                </small>

                                <h4 class="fw-bold mb-0">
                                    ৳{{ number_format($commission->total_amount ?? 0, 2) }}
                                </h4>

                            </div>

                        </div>


                        {{-- Commission Rate --}}
                        <div class="col-md-4">

                            <div class="border rounded p-3 h-100">

                                <small class="text-muted d-block mb-1">
                                    Commission Rate
                                </small>

                                <h4 class="fw-bold text-danger mb-0">

                                    {{ number_format($commission->commission_rate ?? $vendor->commission_rate ?? 0, 2) }}%

                                </h4>

                            </div>

                        </div>


                        {{-- Vendor Earning --}}
                        <div class="col-md-4">

                            <div class="border rounded p-3 h-100">

                                <small class="text-muted d-block mb-1">
                                    Your Earning
                                </small>

                                <h4 class="fw-bold text-success mb-0">
                                    ৳{{ number_format($commission->vendor_earning ?? 0, 2) }}
                                </h4>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                BOOKING INFORMATION
            ================================================== --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="mb-0 fw-bold">

                        <i class="bi bi-calendar-check me-2 text-primary"></i>

                        Booking Information

                    </h5>

                </div>


                <div class="card-body">

                    @if($commission->booking)

                        <div class="row g-4">


                            {{-- Booking Code --}}
                            <div class="col-md-6">

                                <div class="d-flex">

                                    <div class="text-primary me-3">

                                        <i class="bi bi-upc-scan fs-4"></i>

                                    </div>

                                    <div>

                                        <small class="text-muted d-block">
                                            Booking Code
                                        </small>

                                        <strong>
                                            {{ $commission->booking->booking_code ?? 'N/A' }}
                                        </strong>

                                    </div>

                                </div>

                            </div>


                            {{-- Booking Date --}}
                            <div class="col-md-6">

                                <div class="d-flex">

                                    <div class="text-primary me-3">

                                        <i class="bi bi-calendar-event fs-4"></i>

                                    </div>

                                    <div>

                                        <small class="text-muted d-block">
                                            Booking Date
                                        </small>

                                        <strong>

                                            @if($commission->booking->created_at)

                                                {{ $commission->booking->created_at->format('d M, Y h:i A') }}

                                            @else

                                                N/A

                                            @endif

                                        </strong>

                                    </div>

                                </div>

                            </div>


                            {{-- Tour --}}
                            <div class="col-md-6">

                                <div class="d-flex">

                                    <div class="text-primary me-3">

                                        <i class="bi bi-map fs-4"></i>

                                    </div>

                                    <div>

                                        <small class="text-muted d-block">
                                            Tour
                                        </small>

                                        <strong>

                                            {{ $commission->booking->tour->title ?? 'N/A' }}

                                        </strong>

                                    </div>

                                </div>

                            </div>


                            {{-- Tour Date --}}
                            <div class="col-md-6">

                                <div class="d-flex">

                                    <div class="text-primary me-3">

                                        <i class="bi bi-calendar3 fs-4"></i>

                                    </div>

                                    <div>

                                        <small class="text-muted d-block">
                                            Tour Date
                                        </small>

                                        <strong>

                                            @if($commission->booking->tourDate?->date)

                                                {{ \Carbon\Carbon::parse($commission->booking->tourDate->date)->format('d M, Y') }}

                                            @else

                                                N/A

                                            @endif

                                        </strong>

                                    </div>

                                </div>

                            </div>


                            {{-- Number of Travelers --}}
                            <div class="col-md-6">

                                <div class="d-flex">

                                    <div class="text-primary me-3">

                                        <i class="bi bi-people fs-4"></i>

                                    </div>

                                    <div>

                                        <small class="text-muted d-block">
                                            Travelers
                                        </small>

                                        <strong>

                                            {{ $commission->booking->person_count ?? $commission->booking->travelers_count ?? 'N/A' }}

                                        </strong>

                                    </div>

                                </div>

                            </div>


                            {{-- Booking Status --}}
                            <div class="col-md-6">

                                <div class="d-flex">

                                    <div class="text-primary me-3">

                                        <i class="bi bi-info-circle fs-4"></i>

                                    </div>

                                    <div>

                                        <small class="text-muted d-block">
                                            Booking Status
                                        </small>

                                        @php
                                            $status = strtolower($commission->booking->status ?? 'pending');
                                        @endphp

                                        @if($status === 'confirmed')

                                            <span class="badge bg-success">
                                                Confirmed
                                            </span>

                                        @elseif($status === 'completed')

                                            <span class="badge bg-primary">
                                                Completed
                                            </span>

                                        @elseif($status === 'cancelled')

                                            <span class="badge bg-danger">
                                                Cancelled
                                            </span>

                                        @else

                                            <span class="badge bg-warning text-dark">
                                                {{ ucfirst($status) }}
                                            </span>

                                        @endif

                                    </div>

                                </div>

                            </div>

                        </div>

                    @else

                        <div class="text-center text-muted py-4">

                            <i class="bi bi-calendar-x fs-1"></i>

                            <p class="mb-0 mt-2">
                                Booking information is not available.
                            </p>

                        </div>

                    @endif

                </div>

            </div>


            {{-- =================================================
                CUSTOMER INFORMATION
            ================================================== --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="mb-0 fw-bold">

                        <i class="bi bi-person-circle me-2 text-primary"></i>

                        Customer Information

                    </h5>

                </div>


                <div class="card-body">

                    @if($commission->booking?->user)

                        <div class="d-flex align-items-center">

                            <div
                                class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-3"
                                style="width: 60px; height: 60px;"
                            >

                                <i class="bi bi-person fs-4"></i>

                            </div>


                            <div>

                                <h5 class="mb-1 fw-bold">

                                    {{ $commission->booking->user->name }}

                                </h5>

                                <div class="text-muted">

                                    <i class="bi bi-envelope me-1"></i>

                                    {{ $commission->booking->user->email }}

                                </div>

                                @if($commission->booking->user->phone)

                                    <div class="text-muted mt-1">

                                        <i class="bi bi-telephone me-1"></i>

                                        {{ $commission->booking->user->phone }}

                                    </div>

                                @endif

                            </div>

                        </div>

                    @else

                        <p class="text-muted mb-0">
                            Customer information is not available.
                        </p>

                    @endif

                </div>

            </div>


            {{-- =================================================
                TRANSACTION INFORMATION
            ================================================== --}}
            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="mb-0 fw-bold">

                        <i class="bi bi-credit-card me-2 text-primary"></i>

                        Payment / Transaction Information

                    </h5>

                </div>


                <div class="card-body">

                    @if($commission->booking?->transaction)

                        @php
                            $transaction = $commission->booking->transaction;
                        @endphp

                        <div class="row g-4">


                            {{-- Transaction ID --}}
                            <div class="col-md-6">

                                <small class="text-muted d-block mb-1">
                                    Transaction ID
                                </small>

                                <strong>
                                    {{ $transaction->transaction_id ?? $transaction->trx_id ?? 'N/A' }}
                                </strong>

                            </div>


                            {{-- Payment Method --}}
                            <div class="col-md-6">

                                <small class="text-muted d-block mb-1">
                                    Payment Method
                                </small>

                                <strong>
                                    {{ ucfirst($transaction->payment_method ?? 'N/A') }}
                                </strong>

                            </div>


                            {{-- Transaction Amount --}}
                            <div class="col-md-6">

                                <small class="text-muted d-block mb-1">
                                    Transaction Amount
                                </small>

                                <strong>
                                    ৳{{ number_format($transaction->amount ?? $commission->total_amount ?? 0, 2) }}
                                </strong>

                            </div>


                            {{-- Transaction Status --}}
                            <div class="col-md-6">

                                <small class="text-muted d-block mb-1">
                                    Transaction Status
                                </small>

                                @php
                                    $transactionStatus = strtolower($transaction->status ?? 'pending');
                                @endphp

                                @if($transactionStatus === 'paid' || $transactionStatus === 'completed' || $transactionStatus === 'success')

                                    <span class="badge bg-success">
                                        {{ ucfirst($transactionStatus) }}
                                    </span>

                                @elseif($transactionStatus === 'failed' || $transactionStatus === 'cancelled')

                                    <span class="badge bg-danger">
                                        {{ ucfirst($transactionStatus) }}
                                    </span>

                                @else

                                    <span class="badge bg-warning text-dark">
                                        {{ ucfirst($transactionStatus) }}
                                    </span>

                                @endif

                            </div>


                            {{-- Transaction Date --}}
                            <div class="col-md-6">

                                <small class="text-muted d-block mb-1">
                                    Transaction Date
                                </small>

                                <strong>

                                    @if($transaction->created_at)

                                        {{ $transaction->created_at->format('d M, Y h:i A') }}

                                    @else

                                        N/A

                                    @endif

                                </strong>

                            </div>

                        </div>

                    @else

                        <div class="text-center text-muted py-4">

                            <i class="bi bi-credit-card-2-front fs-1"></i>

                            <p class="mb-0 mt-2">
                                No transaction information available.
                            </p>

                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- =====================================================
            RIGHT SIDE
        ====================================================== --}}
        <div class="col-xl-4">


            {{-- =================================================
                EARNING CARD
            ================================================== --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="mb-0 fw-bold">

                        <i class="bi bi-wallet2 me-2 text-success"></i>

                        Earnings Breakdown

                    </h5>

                </div>


                <div class="card-body">


                    {{-- Total --}}
                    <div class="d-flex justify-content-between mb-3">

                        <span class="text-muted">
                            Total Amount
                        </span>

                        <strong>
                            ৳{{ number_format($commission->total_amount ?? 0, 2) }}
                        </strong>

                    </div>


                    {{-- Commission Rate --}}
                    <div class="d-flex justify-content-between mb-3">

                        <span class="text-muted">
                            Commission Rate
                        </span>

                        <strong class="text-danger">

                            {{ number_format($commission->commission_rate ?? $vendor->commission_rate ?? 0, 2) }}%

                        </strong>

                    </div>


                    <hr>


                    {{-- Vendor Earning --}}
                    <div class="d-flex justify-content-between align-items-center">

                        <span class="fw-semibold">
                            Your Earning
                        </span>

                        <span class="fs-4 fw-bold text-success">

                            ৳{{ number_format($commission->vendor_earning ?? 0, 2) }}

                        </span>

                    </div>

                </div>

            </div>


            {{-- =================================================
                VENDOR INFORMATION
            ================================================== --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="mb-0 fw-bold">

                        <i class="bi bi-shop me-2 text-primary"></i>

                        Your Vendor Account

                    </h5>

                </div>


                <div class="card-body">


                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Vendor Name
                        </small>

                        <strong>
                            {{ $vendor->name ?? 'N/A' }}
                        </strong>

                    </div>


                    <div class="mb-3">

                        <small class="text-muted d-block">
                            Commission Rate
                        </small>

                        <strong class="text-primary">

                            {{ number_format($vendor->commission_rate ?? 0, 2) }}%

                        </strong>

                    </div>


                    @if(isset($vendor->email))

                        <div class="mb-3">

                            <small class="text-muted d-block">
                                Email
                            </small>

                            <span>
                                {{ $vendor->email }}
                            </span>

                        </div>

                    @endif


                    @if(isset($vendor->phone))

                        <div>

                            <small class="text-muted d-block">
                                Phone
                            </small>

                            <span>
                                {{ $vendor->phone }}
                            </span>

                        </div>

                    @endif

                </div>

            </div>


            {{-- =================================================
                BACK BUTTON
            ================================================== --}}
            <div class="d-grid">

                <a
                    href="{{ route('vendor.commissions.index') }}"
                    class="btn btn-outline-secondary"
                >

                    <i class="bi bi-arrow-left me-1"></i>

                    Back to Commissions

                </a>

            </div>

        </div>

    </div>

</div>

@endsection
