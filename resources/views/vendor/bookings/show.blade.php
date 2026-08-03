@extends('layouts.vendor')

@section('page')

<div class="container-fluid py-4">

{{-- =========================================================
    HEADER
========================================================== --}}
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

    <div>
        <h4 class="fw-bold mb-1">
            Booking Details
        </h4>

        <p class="text-muted mb-0">
            View booking information and manage this booking.
        </p>
    </div>

    <a
        href="{{ route('vendor.bookings.index') }}"
        class="btn btn-light border"
    >
        <i class="bi bi-arrow-left me-1"></i>
        Back to Bookings
    </a>

</div>


{{-- =========================================================
    SUCCESS MESSAGE
========================================================== --}}
@if(session('success'))

    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">

        <i class="bi bi-check-circle me-1"></i>

        {{ session('success') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
        ></button>

    </div>

@endif


{{-- =========================================================
    ERROR MESSAGE
========================================================== --}}
@if(session('error'))

    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm">

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

    {{-- =====================================================
        LEFT COLUMN
    ====================================================== --}}
    <div class="col-xl-8">


        {{-- =================================================
            BOOKING SUMMARY
        ================================================== --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-0 py-3">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <h5 class="fw-bold mb-1">
                            Booking #{{ $booking->id }}
                        </h5>

                        <small class="text-muted">
                            Created
                            {{ $booking->created_at?->format('d M Y, h:i A') }}
                        </small>

                    </div>


                    @php

                        $bookingStatus =
                            $booking->booking_status ?? 'pending';

                        $statusClass = match($bookingStatus) {

                            'confirmed'
                                => 'bg-success',

                            'cancelled'
                                => 'bg-danger',

                            'completed'
                                => 'bg-primary',

                            'pending'
                                => 'bg-warning text-dark',

                            default
                                => 'bg-secondary',

                        };

                    @endphp


                    <span class="badge {{ $statusClass }} fs-6">

                        {{ ucfirst($bookingStatus) }}

                    </span>

                </div>

            </div>


            <div class="card-body">

                <div class="row g-4">


                    {{-- Tour --}}
                    <div class="col-md-6">

                        <div class="text-muted small mb-1">
                            Tour
                        </div>

                        @if($booking->tour)

                            <div class="fw-semibold fs-6">

                                {{ $booking->tour->title }}

                            </div>

                        @else

                            <span class="text-muted">
                                Tour unavailable
                            </span>

                        @endif

                    </div>


                    {{-- Guests --}}
                    <div class="col-md-3">

                        <div class="text-muted small mb-1">
                            Guests
                        </div>

                        <div class="fw-semibold">

                            <i class="bi bi-people me-1"></i>

                            {{ $booking->person_count ?? 0 }}

                        </div>

                    </div>


                    {{-- Total Amount --}}
                    <div class="col-md-3">

                        <div class="text-muted small mb-1">
                            Total Amount
                        </div>

                        <div class="fw-bold text-primary">

                            ৳{{ number_format($booking->total_amount ?? 0, 2) }}

                        </div>

                    </div>


                </div>

            </div>

        </div>


        {{-- =================================================
            CUSTOMER INFORMATION
        ================================================== --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-0 py-3">

                <h5 class="fw-bold mb-0">
                    Customer Information
                </h5>

            </div>


            <div class="card-body">

                @if($booking->user)

                    <div class="row g-4">

                        {{-- Name --}}
                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Customer Name
                            </div>

                            <div class="fw-semibold">

                                <i class="bi bi-person me-1"></i>

                                {{ $booking->user->name }}

                            </div>

                        </div>


                        {{-- Email --}}
                        <div class="col-md-6">

                            <div class="text-muted small mb-1">
                                Email
                            </div>

                            <div class="fw-semibold">

                                <i class="bi bi-envelope me-1"></i>

                                {{ $booking->user->email ?? '—' }}

                            </div>

                        </div>


                        {{-- Phone --}}
                        @if(isset($booking->user->phone))

                            <div class="col-md-6">

                                <div class="text-muted small mb-1">
                                    Phone
                                </div>

                                <div class="fw-semibold">

                                    <i class="bi bi-telephone me-1"></i>

                                    {{ $booking->user->phone }}

                                </div>

                            </div>

                        @endif

                    </div>

                @else

                    <div class="text-muted">
                        Customer information unavailable.
                    </div>

                @endif

            </div>

        </div>


        {{-- =================================================
            TOUR DATE INFORMATION
        ================================================== --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-0 py-3">

                <h5 class="fw-bold mb-0">
                    Tour Schedule
                </h5>

            </div>


            <div class="card-body">

                @if($booking->tourDate)

                    <div class="row g-4">


                        {{-- Date --}}
                        <div class="col-md-4">

                            <div class="text-muted small mb-1">
                                Tour Date
                            </div>

                            @php

                                $tourDateValue =
                                    $booking->tourDate->date
                                    ?? $booking->tourDate->tour_date
                                    ?? null;

                            @endphp


                            @if($tourDateValue)

                                <div class="fw-semibold">

                                    <i class="bi bi-calendar-event me-1"></i>

                                    {{ \Carbon\Carbon::parse($tourDateValue)->format('d M Y') }}

                                </div>

                            @else

                                <span class="text-muted">
                                    —
                                </span>

                            @endif

                        </div>


                        {{-- Available Seats --}}
                        <div class="col-md-4">

                            <div class="text-muted small mb-1">
                                Available Seats
                            </div>

                            <div class="fw-semibold">

                                <i class="bi bi-person-check me-1"></i>

                                {{ $booking->tourDate->available_seat ?? 0 }}

                            </div>

                        </div>


                        {{-- Requested Seats --}}
                        <div class="col-md-4">

                            <div class="text-muted small mb-1">
                                Requested Seats
                            </div>

                            <div class="fw-semibold">

                                <i class="bi bi-people me-1"></i>

                                {{ $booking->person_count ?? 0 }}

                            </div>

                        </div>

                    </div>

                @else

                    <div class="alert alert-warning mb-0">

                        <i class="bi bi-exclamation-triangle me-1"></i>

                        Tour date information is not available.

                    </div>

                @endif

            </div>

        </div>


        {{-- =================================================
            PAYMENT INFORMATION
        ================================================== --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-0 py-3">

                <h5 class="fw-bold mb-0">
                    Payment Information
                </h5>

            </div>


            <div class="card-body">

                <div class="row g-4">


                    {{-- Total --}}
                    <div class="col-md-4">

                        <div class="text-muted small mb-1">
                            Total Amount
                        </div>

                        <div class="fw-bold fs-5">

                            ৳{{ number_format($booking->total_amount ?? 0, 2) }}

                        </div>

                    </div>


                    {{-- Vendor Earning --}}
                    <div class="col-md-4">

                        <div class="text-muted small mb-1">
                            Vendor Earning
                        </div>

                        <div class="fw-bold text-success fs-5">

                            ৳{{ number_format($booking->vendor_earning ?? 0, 2) }}

                        </div>

                    </div>


                    {{-- Payment Status --}}
                    <div class="col-md-4">

                        <div class="text-muted small mb-1">
                            Payment Status
                        </div>

                        @php

                            $paymentStatus =
                                $booking->payment_status ?? 'pending';

                            $paymentClass = match($paymentStatus) {

                                'paid',
                                'completed',
                                'success'
                                    => 'bg-success',

                                'failed',
                                'cancelled'
                                    => 'bg-danger',

                                'refunded'
                                    => 'bg-warning text-dark',

                                default
                                    => 'bg-secondary',

                            };

                        @endphp


                        <span class="badge {{ $paymentClass }}">

                            {{ ucfirst($paymentStatus) }}

                        </span>

                    </div>

                </div>

            </div>

        </div>


        {{-- =================================================
            NOTES / SPECIAL REQUEST
        ================================================== --}}
        @if(isset($booking->special_request) && $booking->special_request)

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="fw-bold mb-0">
                        Customer Request
                    </h5>

                </div>


                <div class="card-body">

                    <p class="mb-0 text-muted">

                        {{ $booking->special_request }}

                    </p>

                </div>

            </div>

        @endif

    </div>


    {{-- =====================================================
        RIGHT COLUMN
    ====================================================== --}}
    <div class="col-xl-4">


        {{-- =================================================
            BOOKING ACTIONS
        ================================================== --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-0 py-3">

                <h5 class="fw-bold mb-0">
                    Booking Actions
                </h5>

            </div>


            <div class="card-body">


                {{-- Confirm --}}
                @if($booking->booking_status === 'pending')

                    <form
                        action="{{ route('vendor.bookings.confirm', $booking->id) }}"
                        method="POST"
                        class="mb-3"
                        onsubmit="return confirm('Are you sure you want to confirm this booking?');"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="btn btn-success w-100"
                        >

                            <i class="bi bi-check-circle me-1"></i>

                            Confirm Booking

                        </button>

                    </form>

                @endif


                {{-- Cancel --}}
                @if(
                    $booking->booking_status !== 'cancelled' &&
                    $booking->booking_status !== 'completed'
                )

                    <form
                        action="{{ route('vendor.bookings.cancel', $booking->id) }}"
                        method="POST"
                        onsubmit="return confirm('Are you sure you want to cancel this booking?');"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="btn btn-outline-danger w-100"
                        >

                            <i class="bi bi-x-circle me-1"></i>

                            Cancel Booking

                        </button>

                    </form>

                @endif


                {{-- Already Confirmed --}}
                @if($booking->booking_status === 'confirmed')

                    <div class="alert alert-success mb-3">

                        <i class="bi bi-check-circle me-1"></i>

                        This booking has been confirmed.

                    </div>

                @endif


                {{-- Already Cancelled --}}
                @if($booking->booking_status === 'cancelled')

                    <div class="alert alert-danger mb-0">

                        <i class="bi bi-x-circle me-1"></i>

                        This booking has been cancelled.

                    </div>

                @endif


                {{-- Completed --}}
                @if($booking->booking_status === 'completed')

                    <div class="alert alert-primary mb-0">

                        <i class="bi bi-check2-all me-1"></i>

                        This booking has been completed.

                    </div>

                @endif

            </div>

        </div>


        {{-- =================================================
            QUICK INFORMATION
        ================================================== --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-0 py-3">

                <h5 class="fw-bold mb-0">
                    Quick Information
                </h5>

            </div>


            <div class="card-body">


                <div class="d-flex justify-content-between mb-3">

                    <span class="text-muted">
                        Booking ID
                    </span>

                    <span class="fw-semibold">
                        #{{ $booking->id }}
                    </span>

                </div>


                <div class="d-flex justify-content-between mb-3">

                    <span class="text-muted">
                        Guests
                    </span>

                    <span class="fw-semibold">
                        {{ $booking->person_count ?? 0 }}
                    </span>

                </div>


                <div class="d-flex justify-content-between mb-3">

                    <span class="text-muted">
                        Booking Status
                    </span>

                    <span class="fw-semibold">
                        {{ ucfirst($booking->booking_status ?? 'pending') }}
                    </span>

                </div>


                <div class="d-flex justify-content-between">

                    <span class="text-muted">
                        Payment Status
                    </span>

                    <span class="fw-semibold">
                        {{ ucfirst($booking->payment_status ?? 'pending') }}
                    </span>

                </div>

            </div>

        </div>


        {{-- =================================================
            BACK BUTTON
        ================================================== --}}
        <a
            href="{{ route('vendor.bookings.index') }}"
            class="btn btn-light border w-100"
        >

            <i class="bi bi-arrow-left me-1"></i>

            Back to Bookings

        </a>

    </div>

</div>
</div>

@endsection
