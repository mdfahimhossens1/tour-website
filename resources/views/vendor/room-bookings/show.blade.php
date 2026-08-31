@extends('layouts.vendor')

@section('title', 'Room Booking Details')

@section('page')

<div class="container-fluid py-4">

    {{-- ==========================================================
        Header
    =========================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>

            <h4 class="fw-bold mb-1">
                Room Booking Details
            </h4>

            <p class="text-muted mb-0">
                View and manage booking information.
            </p>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route('vendor.room-bookings.index') }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Back
            </a>


            @if(
                !in_array(
                    $booking->booking_status,
                    ['checked_out', 'cancelled']
                )
            )

                <a
                    href="{{ route('vendor.room-bookings.edit', $booking) }}"
                    class="btn btn-outline-primary"
                >
                    <i class="bi bi-pencil me-1"></i>
                    Edit
                </a>

            @endif

        </div>

    </div>


    {{-- ==========================================================
        Flash Messages
    =========================================================== --}}

    @if(session('success'))

        <div
            class="alert alert-success alert-dismissible fade show border-0 shadow-sm"
            role="alert"
        >

            <i class="bi bi-check-circle me-1"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    @if(session('error'))

        <div
            class="alert alert-danger alert-dismissible fade show border-0 shadow-sm"
            role="alert"
        >

            <i class="bi bi-exclamation-triangle me-1"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    <div class="row g-4">


        {{-- ======================================================
            MAIN CONTENT
        ======================================================= --}}
        <div class="col-xl-8">


            {{-- ==================================================
                Booking Overview
            =================================================== --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white border-0 py-3">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h5 class="fw-bold mb-1">
                                Booking Overview
                            </h5>

                            <small class="text-muted">
                                {{ $booking->booking_code }}
                            </small>

                        </div>


                        <div>

                            @switch($booking->booking_status)

                                @case('pending')

                                    <span class="badge bg-warning text-dark px-3 py-2">
                                        <i class="bi bi-clock me-1"></i>
                                        Pending
                                    </span>

                                    @break


                                @case('confirmed')

                                    <span class="badge bg-success px-3 py-2">
                                        <i class="bi bi-check-circle me-1"></i>
                                        Confirmed
                                    </span>

                                    @break


                                @case('checked_in')

                                    <span class="badge bg-primary px-3 py-2">
                                        <i class="bi bi-box-arrow-in-right me-1"></i>
                                        Checked In
                                    </span>

                                    @break


                                @case('checked_out')

                                    <span class="badge bg-secondary px-3 py-2">
                                        <i class="bi bi-box-arrow-right me-1"></i>
                                        Checked Out
                                    </span>

                                    @break


                                @case('cancelled')

                                    <span class="badge bg-danger px-3 py-2">
                                        <i class="bi bi-x-circle me-1"></i>
                                        Cancelled
                                    </span>

                                    @break


                                @default

                                    <span class="badge bg-secondary px-3 py-2">
                                        {{ ucfirst($booking->booking_status ?? 'Unknown') }}
                                    </span>

                            @endswitch

                        </div>

                    </div>

                </div>


                <div class="card-body">

                    <div class="row g-4">


                        {{-- Check In --}}
                        <div class="col-md-4">

                            <div class="border rounded p-3 h-100">

                                <small class="text-muted d-block mb-1">
                                    Check-in
                                </small>

                                <div class="fw-bold fs-5">
                                    {{ $booking->check_in?->format('d M Y') ?? 'N/A' }}
                                </div>

                            </div>

                        </div>


                        {{-- Check Out --}}
                        <div class="col-md-4">

                            <div class="border rounded p-3 h-100">

                                <small class="text-muted d-block mb-1">
                                    Check-out
                                </small>

                                <div class="fw-bold fs-5">
                                    {{ $booking->check_out?->format('d M Y') ?? 'N/A' }}
                                </div>

                            </div>

                        </div>


                        {{-- Nights --}}
                        <div class="col-md-4">

                            <div class="border rounded p-3 h-100">

                                <small class="text-muted d-block mb-1">
                                    Stay Duration
                                </small>

                                <div class="fw-bold fs-5">

                                    {{ $booking->total_nights ?? 0 }}

                                    {{ ($booking->total_nights ?? 0) == 1
                                        ? 'Night'
                                        : 'Nights'
                                    }}

                                </div>

                            </div>

                        </div>

                    </div>


                    <hr class="my-4">


                    <div class="row g-4">


                        {{-- Rooms --}}
                        <div class="col-md-3">

                            <small class="text-muted d-block">
                                Rooms
                            </small>

                            <div class="fw-semibold">
                                {{ $booking->room_count ?? 1 }}
                            </div>

                        </div>


                        {{-- Adults --}}
                        <div class="col-md-3">

                            <small class="text-muted d-block">
                                Adults
                            </small>

                            <div class="fw-semibold">
                                {{ $booking->adults ?? 0 }}
                            </div>

                        </div>


                        {{-- Children --}}
                        <div class="col-md-3">

                            <small class="text-muted d-block">
                                Children
                            </small>

                            <div class="fw-semibold">
                                {{ $booking->children ?? 0 }}
                            </div>

                        </div>


                        {{-- Created --}}
                        <div class="col-md-3">

                            <small class="text-muted d-block">
                                Booked At
                            </small>

                            <div class="fw-semibold">
                                {{ $booking->created_at?->format('d M Y, h:i A') ?? 'N/A' }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ==================================================
                Customer & Room
            =================================================== --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="fw-bold mb-0">
                        Customer & Room Information
                    </h5>

                </div>


                <div class="card-body">

                    <div class="row g-4">


                        {{-- Customer --}}
                        <div class="col-md-6">

                            <div class="border rounded p-3 h-100">

                                <div class="d-flex align-items-center mb-3">

                                    <div
                                        class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center me-3"
                                        style="width:45px;height:45px;"
                                    >

                                        <i class="bi bi-person fs-5 text-primary"></i>

                                    </div>


                                    <div>

                                        <small class="text-muted d-block">
                                            Customer
                                        </small>

                                        <div class="fw-bold">
                                            {{ $booking->user?->name ?? 'N/A' }}
                                        </div>

                                    </div>

                                </div>


                                @if($booking->user?->email)

                                    <div class="mb-2">

                                        <small class="text-muted">
                                            Email
                                        </small>

                                        <div>
                                            {{ $booking->user->email }}
                                        </div>

                                    </div>

                                @endif


                                @if($booking->user?->phone)

                                    <div>

                                        <small class="text-muted">
                                            Phone
                                        </small>

                                        <div>
                                            {{ $booking->user->phone }}
                                        </div>

                                    </div>

                                @endif

                            </div>

                        </div>


                        {{-- Resort / Room --}}
                        <div class="col-md-6">

                            <div class="border rounded p-3 h-100">

                                <div class="d-flex align-items-center mb-3">

                                    <div
                                        class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center me-3"
                                        style="width:45px;height:45px;"
                                    >

                                        <i class="bi bi-building fs-5 text-success"></i>

                                    </div>


                                    <div>

                                        <small class="text-muted d-block">
                                            Resort
                                        </small>

                                        <div class="fw-bold">
                                            {{ $booking->room?->name ?? 'N/A' }}
                                        </div>

                                    </div>

                                </div>


                                <div class="mb-2">

                                    <small class="text-muted">
                                        Room
                                    </small>

                                    <div class="fw-semibold">
                                        {{ $booking->room?->name ?? 'N/A' }}
                                    </div>

                                </div>


                                @if($booking->room?->room_no)

                                    <div>

                                        <small class="text-muted">
                                            Room Number
                                        </small>

                                        <div class="fw-semibold">
                                            {{ $booking->room->room_no }}
                                        </div>

                                    </div>

                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ==================================================
                Guest Information
            =================================================== --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white border-0 py-3">

                    <div class="d-flex justify-content-between align-items-center">

                        <h5 class="fw-bold mb-0">
                            Guest Information
                        </h5>

                        <span class="badge bg-light text-dark border">

                            {{ $booking->guests?->count() ?? 0 }}

                            {{ ($booking->guests?->count() ?? 0) == 1
                                ? 'Guest'
                                : 'Guests'
                            }}

                        </span>

                    </div>

                </div>


                <div class="card-body">

                    @if($booking->guests && $booking->guests->count())

                        <div class="row g-3">

                            @foreach($booking->guests as $guest)

                                <div class="col-md-6">

                                    <div class="border rounded p-3 h-100">

                                        <div class="fw-bold mb-2">

                                            <i class="bi bi-person-circle me-1"></i>

                                            {{ $guest->name }}

                                        </div>


                                        <div class="row g-2">

                                            @if($guest->age !== null)

                                                <div class="col-6">

                                                    <small class="text-muted d-block">
                                                        Age
                                                    </small>

                                                    <span>
                                                        {{ $guest->age }}
                                                    </span>

                                                </div>

                                            @endif


                                            @if($guest->gender)

                                                <div class="col-6">

                                                    <small class="text-muted d-block">
                                                        Gender
                                                    </small>

                                                    <span>
                                                        {{ ucfirst($guest->gender) }}
                                                    </span>

                                                </div>

                                            @endif


                                            @if($guest->phone)

                                                <div class="col-12">

                                                    <small class="text-muted d-block">
                                                        Phone
                                                    </small>

                                                    <span>
                                                        {{ $guest->phone }}
                                                    </span>

                                                </div>

                                            @endif


                                            @if($guest->nid)

                                                <div class="col-12">

                                                    <small class="text-muted d-block">
                                                        NID
                                                    </small>

                                                    <span>
                                                        {{ $guest->nid }}
                                                    </span>

                                                </div>

                                            @endif


                                            @if($guest->passport)

                                                <div class="col-12">

                                                    <small class="text-muted d-block">
                                                        Passport
                                                    </small>

                                                    <span>
                                                        {{ $guest->passport }}
                                                    </span>

                                                </div>

                                            @endif

                                        </div>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    @else

                        <div class="text-center text-muted py-4">

                            <i class="bi bi-people fs-1 d-block mb-2"></i>

                            No additional guest information available.

                        </div>

                    @endif

                </div>

            </div>


            {{-- ==================================================
                Special Request
            =================================================== --}}
            @if($booking->special_request)

                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white border-0 py-3">

                        <h5 class="fw-bold mb-0">
                            Special Request
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="bg-light rounded p-3">

                            {!! nl2br(e($booking->special_request)) !!}

                        </div>

                    </div>

                </div>

            @endif


            {{-- ==================================================
                Payment Information
            =================================================== --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white border-0 py-3">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h5 class="fw-bold mb-1">
                                Payment Information
                            </h5>

                            <small class="text-muted">
                                Customer payment records
                            </small>

                        </div>


                        {{-- Overall Payment Status --}}
                        @if($booking->payment_status === 'paid')

                            <span class="badge bg-success px-3 py-2">

                                <i class="bi bi-check-circle me-1"></i>

                                Payment Paid

                            </span>

                        @elseif($booking->payment_status === 'pending')

                            <span class="badge bg-warning text-dark px-3 py-2">

                                <i class="bi bi-clock me-1"></i>

                                Payment Pending

                            </span>

                        @elseif($booking->payment_status === 'failed')

                            <span class="badge bg-danger px-3 py-2">

                                <i class="bi bi-x-circle me-1"></i>

                                Payment Failed

                            </span>

                        @elseif($booking->payment_status === 'refunded')

                            <span class="badge bg-secondary px-3 py-2">

                                <i class="bi bi-arrow-counterclockwise me-1"></i>

                                Refunded

                            </span>

                        @else

                            <span class="badge bg-secondary px-3 py-2">
                                {{ ucfirst($booking->payment_status ?? 'Unknown') }}
                            </span>

                        @endif

                    </div>

                </div>


                <div class="card-body p-0">

                    @if($booking->payments && $booking->payments->count())

                        <div class="table-responsive">

                            <table class="table table-hover align-middle mb-0">

                                <thead class="table-light">

                                    <tr>

                                        <th class="ps-4">
                                            Transaction ID
                                        </th>

                                        <th>
                                            Method
                                        </th>

                                        <th>
                                            Amount
                                        </th>

                                        <th>
                                            Status
                                        </th>

                                        <th>
                                            Paid At
                                        </th>

                                        <th class="pe-4 text-end">
                                            Action
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @foreach($booking->payments as $payment)

                                        <tr>

                                            <td class="ps-4">

                                                <span class="fw-semibold">
                                                    {{ $payment->trx_id ?? 'N/A' }}
                                                </span>

                                            </td>


                                            <td>

                                                {{ ucfirst(
                                                    str_replace(
                                                        '_',
                                                        ' ',
                                                        $payment->payment_method ?? 'N/A'
                                                    )
                                                ) }}

                                            </td>


                                            <td>

                                                <span class="fw-semibold">

                                                    ৳{{ number_format(
                                                        $payment->amount ?? 0,
                                                        2
                                                    ) }}

                                                </span>

                                            </td>


                                            <td>

                                                @if($payment->status === 'paid')

                                                    <span class="badge bg-success">

                                                        <i class="bi bi-check-circle me-1"></i>

                                                        Paid

                                                    </span>

                                                @elseif($payment->status === 'pending')

                                                    <span class="badge bg-warning text-dark">

                                                        <i class="bi bi-clock me-1"></i>

                                                        Pending

                                                    </span>

                                                @elseif($payment->status === 'failed')

                                                    <span class="badge bg-danger">

                                                        <i class="bi bi-x-circle me-1"></i>

                                                        Failed

                                                    </span>

                                                @elseif($payment->status === 'refunded')

                                                    <span class="badge bg-secondary">

                                                        <i class="bi bi-arrow-counterclockwise me-1"></i>

                                                        Refunded

                                                    </span>

                                                @else

                                                    <span class="badge bg-secondary">

                                                        {{ ucfirst(
                                                            $payment->status ?? 'Unknown'
                                                        ) }}

                                                    </span>

                                                @endif

                                            </td>


                                            <td>

                                                {{ $payment->paid_at?->format(
                                                    'd M Y, h:i A'
                                                ) ?? 'N/A' }}

                                            </td>


                                            {{-- Payment Action --}}
                                            <td class="pe-4 text-end">

                                                @if(
                                                    $payment->status === 'pending'
                                                    && $booking->booking_status === 'pending'
                                                )

                                                    <div class="d-flex justify-content-end gap-2">

                                                        {{-- Approve --}}
                                                        <form
                                                            action="{{ route(
                                                                'vendor.room-bookings.payment.approve',
                                                                $payment
                                                            ) }}"
                                                            method="POST"
                                                        >

                                                            @csrf

                                                            <button
                                                                type="submit"
                                                                class="btn btn-sm btn-success"
                                                                onclick="return confirm('Are you sure you want to approve this payment?')"
                                                            >

                                                                <i class="bi bi-check-circle me-1"></i>

                                                                Approve

                                                            </button>

                                                        </form>


                                                        {{-- Reject --}}
                                                        <form
                                                            action="{{ route(
                                                                'vendor.room-bookings.payment.reject',
                                                                $payment
                                                            ) }}"
                                                            method="POST"
                                                        >

                                                            @csrf

                                                            <button
                                                                type="submit"
                                                                class="btn btn-sm btn-outline-danger"
                                                                onclick="return confirm('Are you sure you want to reject this payment?')"
                                                            >

                                                                <i class="bi bi-x-circle me-1"></i>

                                                                Reject

                                                            </button>

                                                        </form>

                                                    </div>

                                                @elseif($payment->status === 'paid')

                                                    <span class="badge bg-success">

                                                        <i class="bi bi-check-circle me-1"></i>

                                                        Verified

                                                    </span>

                                                @elseif($payment->status === 'failed')

                                                    <span class="badge bg-danger">

                                                        Rejected

                                                    </span>

                                                @else

                                                    <span class="text-muted">
                                                        —
                                                    </span>

                                                @endif

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="text-center text-muted py-5">

                            <i class="bi bi-credit-card fs-1 d-block mb-2"></i>

                            <div class="fw-semibold">
                                No payment submitted yet.
                            </div>

                            <small>
                                Waiting for customer payment.
                            </small>

                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- ======================================================
            SIDEBAR
        ======================================================= --}}
        <div class="col-xl-4">


            {{-- ==================================================
                Payment Summary
            =================================================== --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="fw-bold mb-0">
                        Payment Summary
                    </h5>

                </div>


                <div class="card-body">

                    <div class="d-flex justify-content-between mb-3">

                        <span class="text-muted">
                            Room Price
                        </span>

                        <span>
                            ৳{{ number_format(
                                $booking->room_price ?? 0,
                                2
                            ) }}
                        </span>

                    </div>


                    <div class="d-flex justify-content-between mb-3">

                        <span class="text-muted">
                            Subtotal
                        </span>

                        <span>
                            ৳{{ number_format(
                                $booking->subtotal ?? 0,
                                2
                            ) }}
                        </span>

                    </div>


                    <div class="d-flex justify-content-between mb-3">

                        <span class="text-muted">
                            Discount
                        </span>

                        <span class="text-success">

                            - ৳{{ number_format(
                                $booking->discount ?? 0,
                                2
                            ) }}

                        </span>

                    </div>


                    <div class="d-flex justify-content-between mb-3">

                        <span class="text-muted">
                            Tax
                        </span>

                        <span>
                            ৳{{ number_format(
                                $booking->tax ?? 0,
                                2
                            ) }}
                        </span>

                    </div>


                    <hr>


                    <div class="d-flex justify-content-between mb-3">

                        <span class="fw-bold">
                            Total Amount
                        </span>

                        <span class="fw-bold fs-5">

                            ৳{{ number_format(
                                $booking->total_amount ?? 0,
                                2
                            ) }}

                        </span>

                    </div>


                    {{-- Vendor Earning --}}
                    @if(
                        $booking->booking_status !== 'pending'
                        && $booking->vendor_earning > 0
                    )

                        <div class="d-flex justify-content-between">

                            <span class="text-muted">
                                Vendor Earning
                            </span>

                            <span class="fw-bold text-success">

                                ৳{{ number_format(
                                    $booking->vendor_earning,
                                    2
                                ) }}

                            </span>

                        </div>

                    @else

                        <div class="d-flex justify-content-between">

                            <span class="text-muted">
                                Vendor Earning
                            </span>

                            <span class="text-muted">
                                Not calculated yet
                            </span>

                        </div>

                    @endif

                </div>

            </div>


            {{-- ==================================================
                Payment Status Card
            =================================================== --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="fw-bold mb-0">
                        Payment Status
                    </h5>

                </div>


                <div class="card-body">

                    @if($booking->payment_status === 'paid')

                        <div class="alert alert-success mb-0">

                            <div class="d-flex">

                                <i class="bi bi-check-circle-fill fs-4 me-2"></i>

                                <div>

                                    <div class="fw-bold">
                                        Payment Confirmed
                                    </div>

                                    <small>
                                        Payment has been verified by the vendor.
                                        You can now confirm the booking.
                                    </small>

                                </div>

                            </div>

                        </div>


                    @elseif($booking->payment_status === 'pending')

                        <div class="alert alert-warning mb-0">

                            <div class="d-flex">

                                <i class="bi bi-clock-fill fs-4 me-2"></i>

                                <div>

                                    <div class="fw-bold">
                                        Payment Pending
                                    </div>

                                    <small>
                                        Customer has submitted a payment.
                                        Please verify the payment before confirming the booking.
                                    </small>

                                </div>

                            </div>

                        </div>


                    @elseif($booking->payment_status === 'failed')

                        <div class="alert alert-danger mb-0">

                            <div class="d-flex">

                                <i class="bi bi-x-circle-fill fs-4 me-2"></i>

                                <div>

                                    <div class="fw-bold">
                                        Payment Failed
                                    </div>

                                    <small>
                                        The submitted payment was rejected or failed.
                                        Customer needs to submit a valid payment.
                                    </small>

                                </div>

                            </div>

                        </div>


                    @elseif($booking->payment_status === 'refunded')

                        <div class="alert alert-secondary mb-0">

                            <div class="d-flex">

                                <i class="bi bi-arrow-counterclockwise fs-4 me-2"></i>

                                <div>

                                    <div class="fw-bold">
                                        Payment Refunded
                                    </div>

                                    <small>
                                        This payment has been refunded.
                                    </small>

                                </div>

                            </div>

                        </div>


                    @else

                        <div class="alert alert-secondary mb-0">
                            Unknown payment status.
                        </div>

                    @endif

                </div>

            </div>


            {{-- ==================================================
                Booking Actions
            =================================================== --}}
            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="fw-bold mb-0">
                        Booking Actions
                    </h5>

                </div>


                <div class="card-body">


                    {{-- ==================================================
                        PAYMENT VERIFICATION REQUIRED
                    =================================================== --}}

                    @if(
                        $booking->booking_status === 'pending'
                        && $booking->payment_status === 'pending'
                    )

                        <div class="alert alert-warning">

                            <div class="d-flex">

                                <i class="bi bi-shield-exclamation fs-4 me-2"></i>

                                <div>

                                    <div class="fw-bold">
                                        Payment Verification Required
                                    </div>

                                    <small>
                                        Please verify the customer's payment
                                        from the Payment Information section
                                        before confirming this booking.
                                    </small>

                                </div>

                            </div>

                        </div>

                    @endif


                    {{-- ==================================================
                        PAYMENT FAILED
                    =================================================== --}}

                    @if(
                        $booking->booking_status === 'pending'
                        && $booking->payment_status === 'failed'
                    )

                        <div class="alert alert-danger">

                            <div class="d-flex">

                                <i class="bi bi-x-circle-fill fs-4 me-2"></i>

                                <div>

                                    <div class="fw-bold">
                                        Payment Failed
                                    </div>

                                    <small>
                                        This booking cannot be confirmed
                                        until a valid payment is submitted.
                                    </small>

                                </div>

                            </div>

                        </div>

                    @endif


                    {{-- ==================================================
                        CONFIRM BOOKING
                    =================================================== --}}

                    @if(
                        $booking->booking_status === 'pending'
                        && $booking->payment_status === 'paid'
                    )

                        <div class="alert alert-success">

                            <div class="d-flex">

                                <i class="bi bi-check-circle-fill fs-4 me-2"></i>

                                <div>

                                    <div class="fw-bold">
                                        Payment Verified
                                    </div>

                                    <small>
                                        Payment has been verified successfully.
                                        You can now confirm this booking.
                                    </small>

                                </div>

                            </div>

                        </div>


                        <form
                            action="{{ route(
                                'vendor.room-bookings.confirm',
                                $booking
                            ) }}"
                            method="POST"
                            class="mb-3"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="btn btn-success w-100"
                                onclick="return confirm('Are you sure you want to confirm this booking?')"
                            >

                                <i class="bi bi-check-circle me-1"></i>

                                Confirm Booking

                            </button>

                        </form>

                    @endif


                    {{-- ==================================================
                        CHECK IN
                    =================================================== --}}

                    @if(
                        $booking->booking_status === 'confirmed'
                    )

                        <div class="alert alert-success">

                            <div class="d-flex">

                                <i class="bi bi-check-circle-fill fs-4 me-2"></i>

                                <div>

                                    <div class="fw-bold">
                                        Booking Confirmed
                                    </div>

                                    <small>
                                        Payment is paid and booking is confirmed.
                                    </small>

                                </div>

                            </div>

                        </div>


                        <form
                            action="{{ route(
                                'vendor.room-bookings.check-in',
                                $booking
                            ) }}"
                            method="POST"
                            class="mb-2"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="btn btn-primary w-100"
                                onclick="return confirm('Check in this guest?')"
                            >

                                <i class="bi bi-box-arrow-in-right me-1"></i>

                                Check In Guest

                            </button>

                        </form>

                    @endif


                    {{-- ==================================================
                        CHECK OUT
                    =================================================== --}}

                    @if(
                        $booking->booking_status === 'checked_in'
                    )

                        <div class="alert alert-primary">

                            <div class="d-flex">

                                <i class="bi bi-person-check-fill fs-4 me-2"></i>

                                <div>

                                    <div class="fw-bold">
                                        Guest Checked In
                                    </div>

                                    <small>
                                        Guest is currently staying at the resort.
                                    </small>

                                </div>

                            </div>

                        </div>


                        <form
                            action="{{ route(
                                'vendor.room-bookings.check-out',
                                $booking
                            ) }}"
                            method="POST"
                            class="mb-2"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="btn btn-secondary w-100"
                                onclick="return confirm('Check out this guest?')"
                            >

                                <i class="bi bi-box-arrow-right me-1"></i>

                                Check Out Guest

                            </button>

                        </form>

                    @endif


                    {{-- ==================================================
                        CANCEL BOOKING
                    =================================================== --}}

                    @if(
                        !in_array(
                            $booking->booking_status,
                            [
                                'cancelled',
                                'checked_out'
                            ]
                        )
                    )

                        <form
                            action="{{ route(
                                'vendor.room-bookings.cancel',
                                $booking
                            ) }}"
                            method="POST"
                            class="mt-3"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="btn btn-outline-danger w-100"
                                onclick="return confirm('Are you sure you want to cancel this booking? This action cannot be undone.')"
                            >

                                <i class="bi bi-x-circle me-1"></i>

                                Cancel Booking

                            </button>

                        </form>

                    @endif


                    {{-- ==================================================
                        FINAL STATES
                    =================================================== --}}

                    @if(
                        $booking->booking_status === 'checked_out'
                    )

                        <div class="alert alert-secondary mb-0 mt-3">

                            <i class="bi bi-check2-all me-1"></i>

                            This booking has been checked out successfully.

                        </div>

                    @elseif(
                        $booking->booking_status === 'cancelled'
                    )

                        <div class="alert alert-danger mb-0 mt-3">

                            <i class="bi bi-x-circle me-1"></i>

                            This booking has been cancelled.

                        </div>

                    @endif

                </div>

            </div>


            {{-- ==================================================
                COMMISSION INFORMATION
            =================================================== --}}
            @if(
                $booking->booking_status !== 'pending'
                && $booking->vendor_earning !== null
            )

                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-header bg-white border-0 py-3">

                        <h5 class="fw-bold mb-0">
                            Commission Information
                        </h5>

                    </div>


                    <div class="card-body">

                        <div class="d-flex justify-content-between mb-3">

                            <span class="text-muted">
                                Commission Rate
                            </span>

                            <span class="fw-semibold">

                                {{ number_format(
                                    $booking->commission_rate ?? 0,
                                    2
                                ) }}%

                            </span>

                        </div>


                        <div class="d-flex justify-content-between mb-3">

                            <span class="text-muted">
                                Admin Commission
                            </span>

                            <span class="fw-semibold text-danger">

                                ৳{{ number_format(
                                    $booking->admin_commission ?? 0,
                                    2
                                ) }}

                            </span>

                        </div>


                        <div class="d-flex justify-content-between">

                            <span class="fw-bold">
                                Vendor Earning
                            </span>

                            <span class="fw-bold text-success">

                                ৳{{ number_format(
                                    $booking->vendor_earning ?? 0,
                                    2
                                ) }}

                            </span>

                        </div>

                    </div>

                </div>

            @endif


        </div>

    </div>

</div>

@endsection