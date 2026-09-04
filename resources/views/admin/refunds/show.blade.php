@extends('layouts.admin')

@section('title', 'Refund Details')

@section('page')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>

            <h4 class="mb-1">
                <i class="fas fa-undo-alt me-2"></i>
                Refund Details
            </h4>

            <p class="text-muted mb-0">
                View completed refund information.
            </p>

        </div>


        <div>

            <a
                href="{{ route('admin.refunds.index') }}"
                class="btn btn-light border"
            >

                <i class="fas fa-arrow-left me-1"></i>

                Back to Refunds

            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- SUCCESS ALERT --}}
    {{-- ========================================================= --}}

    <div class="alert alert-success border-0 shadow-sm">

        <div class="d-flex align-items-center">

            <i class="fas fa-check-circle fa-lg me-3"></i>

            <div>

                <strong>
                    Refund Completed
                </strong>

                <div class="small">
                    This refund has been successfully processed.
                </div>

            </div>

        </div>

    </div>


    <div class="row g-4">

        {{-- ===================================================== --}}
        {{-- LEFT --}}
        {{-- ===================================================== --}}

        <div class="col-lg-8">


            {{-- Refund Summary --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-0">
                        Refund Summary
                    </h5>

                </div>

                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Refund ID
                            </div>

                            <div class="fw-semibold">
                                #REF-{{ str_pad(
                                    $refund->id,
                                    6,
                                    '0',
                                    STR_PAD_LEFT
                                ) }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Status
                            </div>

                            <span class="badge bg-success">

                                <i class="fas fa-check-circle me-1"></i>

                                Completed

                            </span>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Refund Amount
                            </div>

                            <div class="fs-4 fw-bold">

                                {{ number_format(
                                    $refund->refund_amount,
                                    2
                                ) }}

                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Processed At
                            </div>

                            <div class="fw-semibold">

                                @if($refund->processed_at)

                                    {{ $refund->processed_at->format(
                                        'd M Y, h:i A'
                                    ) }}

                                @else

                                    N/A

                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Booking --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-0">
                        Booking Information
                    </h5>

                </div>

                <div class="card-body">

                    @if($refund->booking)

                        <div class="row g-4">

                            <div class="col-md-6">

                                <div class="text-muted small mb-1">
                                    Booking Code
                                </div>

                                <a
                                    href="{{ route(
                                        'admin.bookings.show',
                                        $refund->booking->id
                                    ) }}"
                                    class="fw-semibold text-decoration-none"
                                >

                                    {{ $refund->booking->booking_code }}

                                </a>

                            </div>


                            <div class="col-md-6">

                                <div class="text-muted small mb-1">
                                    Booking Status
                                </div>

                                <span class="badge bg-secondary">
                                    {{ ucfirst(
                                        $refund->booking->booking_status
                                    ) }}
                                </span>

                            </div>


                            <div class="col-md-6">

                                <div class="text-muted small mb-1">
                                    Payment Status
                                </div>

                                <span class="badge bg-info">

                                    {{ ucfirst(
                                        $refund->booking->payment_status
                                    ) }}

                                </span>

                            </div>


                            <div class="col-md-6">

                                <div class="text-muted small mb-1">
                                    Booking Amount
                                </div>

                                <strong>

                                    {{ number_format(
                                        $refund->booking->total_amount,
                                        2
                                    ) }}

                                </strong>

                            </div>


                            @if($refund->booking->tour)

                                <div class="col-12">

                                    <div class="text-muted small mb-1">
                                        Tour
                                    </div>

                                    <div class="fw-semibold">
                                        {{ $refund->booking->tour->title }}
                                    </div>

                                </div>

                            @endif

                        </div>

                    @else

                        <div class="text-muted">
                            Booking information is unavailable.
                        </div>

                    @endif

                </div>

            </div>


            {{-- Customer --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-0">
                        Customer Information
                    </h5>

                </div>

                <div class="card-body">

                    @if($refund->user)

                        <div class="row g-4">

                            <div class="col-md-4">

                                <div class="text-muted small mb-1">
                                    Name
                                </div>

                                <div class="fw-semibold">
                                    {{ $refund->user->name }}
                                </div>

                            </div>


                            <div class="col-md-4">

                                <div class="text-muted small mb-1">
                                    Email
                                </div>

                                <div>
                                    {{ $refund->user->email }}
                                </div>

                            </div>


                            <div class="col-md-4">

                                <div class="text-muted small mb-1">
                                    Phone
                                </div>

                                <div>
                                    {{ $refund->user->phone ?? 'N/A' }}
                                </div>

                            </div>

                        </div>

                    @else

                        <div class="text-muted">
                            Customer information is unavailable.
                        </div>

                    @endif

                </div>

            </div>


            {{-- Reason --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-0">
                        Refund Reason
                    </h5>

                </div>

                <div class="card-body">

                    @if($refund->reason)

                        <div class="bg-light rounded p-3">
                            {!! nl2br(e($refund->reason)) !!}
                        </div>

                    @else

                        <span class="text-muted">
                            No refund reason provided.
                        </span>

                    @endif

                </div>

            </div>


            {{-- Admin Note --}}
            @if($refund->admin_note)

                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white py-3">

                        <h5 class="mb-0">
                            Admin Note
                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="bg-light rounded p-3">

                            {!! nl2br(e($refund->admin_note)) !!}

                        </div>

                    </div>

                </div>

            @endif

        </div>


        {{-- ===================================================== --}}
        {{-- RIGHT --}}
        {{-- ===================================================== --}}

        <div class="col-lg-4">


            {{-- Payment --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-0">
                        Payment Information
                    </h5>

                </div>

                <div class="card-body">

                    @if($refund->payment)

                        <div class="mb-3">

                            <div class="text-muted small">
                                Transaction ID
                            </div>

                            <div class="fw-semibold">
                                {{ $refund->payment->trx_id }}
                            </div>

                        </div>


                        <div class="mb-3">

                            <div class="text-muted small">
                                Payment Method
                            </div>

                            <div>
                                {{ $refund->payment->payment_method ?? 'N/A' }}
                            </div>

                        </div>


                        <div class="mb-3">

                            <div class="text-muted small">
                                Payment Amount
                            </div>

                            <div class="fw-semibold">

                                {{ number_format(
                                    $refund->payment->amount,
                                    2
                                ) }}

                            </div>

                        </div>


                        <div>

                            <div class="text-muted small">
                                Payment Status
                            </div>

                            <span class="badge bg-success">

                                {{ ucfirst(
                                    $refund->payment->status
                                ) }}

                            </span>

                        </div>

                    @else

                        <div class="text-muted">
                            Payment information unavailable.
                        </div>

                    @endif

                </div>

            </div>


            {{-- Refund Timeline --}}
            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">

                    <h5 class="mb-0">
                        Refund Timeline
                    </h5>

                </div>

                <div class="card-body">

                    <div class="d-flex mb-4">

                        <div class="me-3">

                            <span class="badge bg-success rounded-circle p-2">
                                <i class="fas fa-check"></i>
                            </span>

                        </div>

                        <div>

                            <div class="fw-semibold">
                                Refund Completed
                            </div>

                            <div class="small text-muted">

                                @if($refund->processed_at)

                                    {{ $refund->processed_at->format(
                                        'd M Y, h:i A'
                                    ) }}

                                @else

                                    N/A

                                @endif

                            </div>

                        </div>

                    </div>


                    <div class="d-flex">

                        <div class="me-3">

                            <span class="badge bg-success rounded-circle p-2">
                                <i class="fas fa-check"></i>
                            </span>

                        </div>

                        <div>

                            <div class="fw-semibold">
                                Refund Request Created
                            </div>

                            <div class="small text-muted">

                                @if($refund->requested_at)

                                    {{ $refund->requested_at->format(
                                        'd M Y, h:i A'
                                    ) }}

                                @else

                                    N/A

                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection