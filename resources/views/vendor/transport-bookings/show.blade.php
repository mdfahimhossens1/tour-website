@extends('layouts.vendor')

@section('title', 'Transport Booking Details')

@section('page')

@php
    /*
    |--------------------------------------------------------------------------
    | Pending Payment
    |--------------------------------------------------------------------------
    |
    | পুরো পেজে একই pending payment ব্যবহার করার জন্য
    | শুরুতেই বের করে রাখা হয়েছে।
    |
    */

    $pendingPayment = $booking->payments
        ->where('status', 'pending')
        ->sortByDesc('id')
        ->first();
@endphp


<div class="container-fluid py-3">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>

            <div class="d-flex align-items-center gap-2 mb-1">

                <a
                    href="{{ route('vendor.transport-bookings.index') }}"
                    class="btn btn-sm btn-light border"
                >
                    <i class="fas fa-arrow-left"></i>
                </a>

                <h4 class="mb-0">
                    <i class="fas fa-car me-2"></i>
                    Transport Booking Details
                </h4>

            </div>

            <p class="text-muted mb-0">
                Booking #{{ $booking->booking_code }}
            </p>

        </div>


        {{-- =====================================================
            HEADER ACTIONS
        ====================================================== --}}
        <div class="d-flex flex-wrap gap-2 mt-3 mt-md-0">

            {{-- EDIT --}}
            @if(!in_array($booking->booking_status, ['completed', 'cancelled'], true))

                <a
                    href="{{ route('vendor.transport-bookings.edit', $booking) }}"
                    class="btn btn-warning"
                >
                    <i class="fas fa-edit me-1"></i>
                    Edit
                </a>

            @endif


            {{-- APPROVE / REJECT PAYMENT --}}
            @if(
                $booking->payment_status === 'pending' &&
                $pendingPayment
            )

                {{-- APPROVE --}}
                <form
                    action="{{ route('vendor.transport-bookings.payments.approve', [
                        'booking' => $booking->id,
                        'payment' => $pendingPayment->id,
                    ]) }}"
                    method="POST"
                    onsubmit="return confirm('Are you sure you want to approve this payment?')"
                >

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-success"
                    >
                        <i class="fas fa-check-circle me-1"></i>
                        Approve Payment
                    </button>

                </form>


                {{-- REJECT --}}
                <form
                    action="{{ route('vendor.transport-bookings.payments.reject', [
                        'booking' => $booking->id,
                        'payment' => $pendingPayment->id,
                    ]) }}"
                    method="POST"
                    onsubmit="return confirm('Are you sure you want to reject this payment?')"
                >

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-outline-danger"
                    >
                        <i class="fas fa-times-circle me-1"></i>
                        Reject Payment
                    </button>

                </form>

            @endif


            {{-- CONFIRM BOOKING --}}
            @if(
                $booking->booking_status === 'pending' &&
                $booking->payment_status === 'paid'
            )

                <form
                    action="{{ route('vendor.transport-bookings.confirm', $booking) }}"
                    method="POST"
                    onsubmit="return confirm('Are you sure you want to confirm this booking?')"
                >

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="fas fa-calendar-check me-1"></i>
                        Confirm Booking
                    </button>

                </form>

            @endif


            {{-- CANCEL --}}
            @if(
                !in_array($booking->booking_status, ['completed', 'cancelled'], true)
            )

                <form
                    action="{{ route('vendor.transport-bookings.cancel', $booking) }}"
                    method="POST"
                    onsubmit="return confirm('Are you sure you want to cancel this booking?')"
                >

                    @csrf

                    <button
                        type="submit"
                        class="btn btn-outline-danger"
                    >
                        <i class="fas fa-ban me-1"></i>
                        Cancel
                    </button>

                </form>

            @endif

        </div>

    </div>


    {{-- =========================================================
        ALERTS
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="fas fa-check-circle me-2"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="fas fa-exclamation-circle me-2"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    @if($errors->any())

        <div class="alert alert-danger">

            <strong>Please fix the following errors:</strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =========================================================
        STATUS CARDS
    ========================================================== --}}
    <div class="row g-3 mb-4">

        {{-- BOOKING STATUS --}}
        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted d-block mb-2">
                                Booking Status
                            </small>

                            @php
                                $statusClass = match($booking->booking_status) {
                                    'confirmed' => 'bg-success',
                                    'completed' => 'bg-primary',
                                    'cancelled' => 'bg-danger',
                                    default => 'bg-warning text-dark',
                                };
                            @endphp

                            <span class="badge {{ $statusClass }} fs-6">
                                {{ ucfirst($booking->booking_status) }}
                            </span>

                        </div>

                        <div class="text-muted">
                            <i class="fas fa-calendar-check fa-2x"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- PAYMENT STATUS --}}
        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted d-block mb-2">
                                Payment Status
                            </small>

                            @php
                                $paymentClass = match($booking->payment_status) {
                                    'paid' => 'bg-success',
                                    'failed' => 'bg-danger',
                                    'refunded' => 'bg-secondary',
                                    default => 'bg-warning text-dark',
                                };
                            @endphp

                            <span class="badge {{ $paymentClass }} fs-6">
                                {{ ucfirst($booking->payment_status) }}
                            </span>

                        </div>

                        <div class="text-muted">
                            <i class="fas fa-credit-card fa-2x"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- TOTAL --}}
        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted d-block mb-2">
                                Total Amount
                            </small>

                            <h4 class="mb-0 fw-bold">
                                {{ number_format((float) $booking->total_amount, 2) }}
                            </h4>

                        </div>

                        <div class="text-muted">
                            <i class="fas fa-money-bill-wave fa-2x"></i>
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
        <div class="col-lg-8">


            {{-- BOOKING INFORMATION --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="fas fa-file-alt me-2 text-primary"></i>
                        Booking Information
                    </h5>
                </div>

                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-6">
                            <small class="text-muted d-block">Booking Code</small>
                            <div class="fw-semibold mt-1">
                                {{ $booking->booking_code }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted d-block">Booking Date</small>
                            <div class="fw-semibold mt-1">
                                {{ optional($booking->created_at)->format('d M Y, h:i A') }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted d-block">Start Date</small>
                            <div class="fw-semibold mt-1">
                                {{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted d-block">End Date</small>
                            <div class="fw-semibold mt-1">
                                {{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted d-block">Total Days</small>
                            <div class="fw-semibold mt-1">
                                {{ $booking->total_days }}
                                {{ $booking->total_days == 1 ? 'Day' : 'Days' }}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted d-block">Passengers</small>
                            <div class="fw-semibold mt-1">
                                <i class="fas fa-users me-1 text-muted"></i>
                                {{ $booking->passengers }}
                            </div>
                        </div>

                    </div>

                </div>

            </div>


            {{-- JOURNEY INFORMATION --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="fas fa-route me-2 text-primary"></i>
                        Journey Information
                    </h5>
                </div>

                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-6">

                            <div class="border rounded p-3 h-100">

                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge bg-success me-2">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </span>
                                    <strong>Pickup Location</strong>
                                </div>

                                <div class="text-muted">
                                    {{ $booking->pickup_location ?: 'Not specified' }}
                                </div>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="border rounded p-3 h-100">

                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge bg-danger me-2">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </span>
                                    <strong>Dropoff Location</strong>
                                </div>

                                <div class="text-muted">
                                    {{ $booking->dropoff_location ?: 'Not specified' }}
                                </div>

                            </div>

                        </div>

                        @if($booking->special_request)

                            <div class="col-12">

                                <div class="border rounded p-3">

                                    <strong class="d-block mb-2">
                                        <i class="fas fa-comment-alt me-2 text-primary"></i>
                                        Special Request
                                    </strong>

                                    <div class="text-muted">
                                        {{ $booking->special_request }}
                                    </div>

                                </div>

                            </div>

                        @endif

                    </div>

                </div>

            </div>


            {{-- VEHICLE INFORMATION --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="fas fa-car me-2 text-primary"></i>
                        Vehicle Information
                    </h5>
                </div>

                <div class="card-body">

                    @if($booking->vehicle)

                        <div class="row g-4">

                            <div class="col-md-6">
                                <small class="text-muted d-block">Vehicle Name</small>
                                <div class="fw-semibold mt-1">
                                    {{ $booking->vehicle->name }}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <small class="text-muted d-block">
                                    Registration Number
                                </small>
                                <div class="fw-semibold mt-1">
                                    {{ $booking->vehicle->registration_number }}
                                </div>
                            </div>

                            @if($booking->vehicle->brand)
                                <div class="col-md-4">
                                    <small class="text-muted d-block">Brand</small>
                                    <div class="fw-semibold mt-1">
                                        {{ $booking->vehicle->brand }}
                                    </div>
                                </div>
                            @endif

                            @if($booking->vehicle->model)
                                <div class="col-md-4">
                                    <small class="text-muted d-block">Model</small>
                                    <div class="fw-semibold mt-1">
                                        {{ $booking->vehicle->model }}
                                    </div>
                                </div>
                            @endif

                        </div>

                    @else

                        <div class="alert alert-warning mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Vehicle information is unavailable.
                        </div>

                    @endif

                </div>

            </div>


            {{-- CUSTOMER INFORMATION --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="fas fa-user me-2 text-primary"></i>
                        Customer Information
                    </h5>
                </div>

                <div class="card-body">

                    @if($booking->user)

                        <div class="row g-4">

                            <div class="col-md-6">
                                <small class="text-muted d-block">Name</small>
                                <div class="fw-semibold mt-1">
                                    {{ $booking->user->name }}
                                </div>
                            </div>

                            @if($booking->user->email)
                                <div class="col-md-6">
                                    <small class="text-muted d-block">Email</small>
                                    <div class="fw-semibold mt-1">
                                        {{ $booking->user->email }}
                                    </div>
                                </div>
                            @endif

                            @if($booking->user->phone)
                                <div class="col-md-6">
                                    <small class="text-muted d-block">Phone</small>
                                    <div class="fw-semibold mt-1">
                                        {{ $booking->user->phone }}
                                    </div>
                                </div>
                            @endif

                        </div>

                    @else

                        <div class="text-muted">
                            Customer information is unavailable.
                        </div>

                    @endif

                </div>

            </div>


            {{-- =================================================
                PAYMENT INFORMATION
            ================================================== --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white border-bottom">

                    <div class="d-flex justify-content-between align-items-center">

                        <h5 class="mb-0">
                            <i class="fas fa-credit-card me-2 text-primary"></i>
                            Payment Information
                        </h5>

                        @php
                            $bookingPaymentClass = match($booking->payment_status) {
                                'paid' => 'bg-success',
                                'failed' => 'bg-danger',
                                'refunded' => 'bg-secondary',
                                default => 'bg-warning text-dark',
                            };
                        @endphp

                        <span class="badge {{ $bookingPaymentClass }}">
                            {{ ucfirst($booking->payment_status) }}
                        </span>

                    </div>

                </div>


                <div class="card-body">

                    @if($booking->payments && $booking->payments->count())

                        <div class="table-responsive">

                            <table class="table table-hover table-sm align-middle mb-0">

                                <thead class="table-light">

                                    <tr>
                                        <th>#</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th class="text-end">Action</th>
                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach($booking->payments as $payment)

                                        <tr>

                                            <td>{{ $loop->iteration }}</td>

                                            <td>
                                                <span class="fw-semibold">
                                                    {{ number_format((float) $payment->amount, 2) }}
                                                </span>
                                            </td>

                                            <td>
                                                {{ $payment->method
                                                    ?? $payment->payment_method
                                                    ?? 'N/A' }}
                                            </td>

                                            <td>

                                                @php
                                                    $paymentStatusClass = match($payment->status) {
                                                        'paid' => 'bg-success',
                                                        'failed' => 'bg-danger',
                                                        'refunded' => 'bg-secondary',
                                                        default => 'bg-warning text-dark',
                                                    };
                                                @endphp

                                                <span class="badge {{ $paymentStatusClass }}">
                                                    {{ ucfirst($payment->status) }}
                                                </span>

                                            </td>

                                            <td>
                                                {{ optional($payment->created_at)->format('d M Y, h:i A') }}
                                            </td>

                                            <td class="text-end">

                                                @if($payment->status === 'pending')

                                                    <div class="d-flex justify-content-end gap-1">

                                                        {{-- APPROVE --}}
                                                        <form
                                                            action="{{ route('vendor.transport-bookings.payments.approve', [
                                                                'booking' => $booking->id,
                                                                'payment' => $payment->id,
                                                            ]) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Are you sure you want to approve this payment?')"
                                                        >

                                                            @csrf

                                                            <button
                                                                type="submit"
                                                                class="btn btn-sm btn-success"
                                                            >
                                                                <i class="fas fa-check me-1"></i>
                                                                Approve
                                                            </button>

                                                        </form>


                                                        {{-- REJECT --}}
                                                        <form
                                                            action="{{ route('vendor.transport-bookings.payments.reject', [
                                                                'booking' => $booking->id,
                                                                'payment' => $payment->id,
                                                            ]) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Are you sure you want to reject this payment?')"
                                                        >

                                                            @csrf

                                                            <button
                                                                type="submit"
                                                                class="btn btn-sm btn-danger"
                                                            >
                                                                <i class="fas fa-times me-1"></i>
                                                                Reject
                                                            </button>

                                                        </form>

                                                    </div>

                                                @elseif($payment->status === 'paid')

                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check-circle me-1"></i>
                                                        Approved
                                                    </span>

                                                @elseif($payment->status === 'failed')

                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-times-circle me-1"></i>
                                                        Rejected
                                                    </span>

                                                @else

                                                    <span class="text-muted small">
                                                        No action
                                                    </span>

                                                @endif

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="text-center py-5 text-muted">

                            <i class="fas fa-credit-card fa-2x mb-3 opacity-50"></i>

                            <p class="mb-0">
                                No payment records found.
                            </p>

                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- =====================================================
            RIGHT COLUMN
        ====================================================== --}}
        <div class="col-lg-4">


            {{-- PAYMENT APPROVAL CARD --}}
            @if(
                $booking->payment_status === 'pending' &&
                $pendingPayment
            )

                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-warning-subtle border-bottom">

                        <h5 class="mb-0">
                            <i class="fas fa-credit-card me-2"></i>
                            Payment Approval Required
                        </h5>

                    </div>

                    <div class="card-body">

                        <p class="text-muted">
                            A payment is waiting for vendor approval.
                        </p>

                        <div class="border rounded p-3 mb-3">

                            <div class="d-flex justify-content-between mb-2">

                                <span class="text-muted">Payment Amount</span>

                                <strong>
                                    {{ number_format((float) $pendingPayment->amount, 2) }}
                                </strong>

                            </div>

                            <div class="d-flex justify-content-between">

                                <span class="text-muted">Payment Method</span>

                                <strong>
                                    {{ $pendingPayment->method
                                        ?? $pendingPayment->payment_method
                                        ?? 'N/A' }}
                                </strong>

                            </div>

                        </div>

                        <div class="d-grid gap-2">

                            {{-- APPROVE --}}
                            <form
                                action="{{ route('vendor.transport-bookings.payments.approve', [
                                    'booking' => $booking->id,
                                    'payment' => $pendingPayment->id,
                                ]) }}"
                                method="POST"
                                onsubmit="return confirm('Are you sure you want to approve this payment?')"
                            >

                                @csrf

                                <button
                                    type="submit"
                                    class="btn btn-success w-100"
                                >
                                    <i class="fas fa-check-circle me-1"></i>
                                    Approve Payment
                                </button>

                            </form>


                            {{-- REJECT --}}
                            <form
                                action="{{ route('vendor.transport-bookings.payments.reject', [
                                    'booking' => $booking->id,
                                    'payment' => $pendingPayment->id,
                                ]) }}"
                                method="POST"
                                onsubmit="return confirm('Are you sure you want to reject this payment?')"
                            >

                                @csrf

                                <button
                                    type="submit"
                                    class="btn btn-danger w-100"
                                >
                                    <i class="fas fa-times-circle me-1"></i>
                                    Reject Payment
                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            @endif


            {{-- CONFIRM BOOKING CARD --}}
            @if(
                $booking->booking_status === 'pending' &&
                $booking->payment_status === 'paid'
            )

                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-success-subtle border-bottom">

                        <h5 class="mb-0">
                            <i class="fas fa-calendar-check me-2"></i>
                            Booking Ready
                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="alert alert-success">

                            <i class="fas fa-check-circle me-2"></i>

                            Payment has been approved. This booking is ready for confirmation.

                        </div>

                        <form
                            action="{{ route('vendor.transport-bookings.confirm', $booking) }}"
                            method="POST"
                            onsubmit="return confirm('Are you sure you want to confirm this booking?')"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="btn btn-primary w-100 btn-lg"
                            >
                                <i class="fas fa-calendar-check me-1"></i>
                                Confirm Booking
                            </button>

                        </form>

                    </div>

                </div>

            @endif


            {{-- PRICE BREAKDOWN --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="fas fa-calculator me-2 text-primary"></i>
                        Price Breakdown
                    </h5>
                </div>

                <div class="card-body">

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Price / Day</span>
                        <span class="fw-semibold">
                            {{ number_format((float) $booking->price_per_day, 2) }}
                        </span>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Total Days</span>
                        <span class="fw-semibold">
                            {{ $booking->total_days }}
                        </span>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Subtotal</span>
                        <span class="fw-semibold">
                            {{ number_format((float) $booking->subtotal, 2) }}
                        </span>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Discount</span>
                        <span class="text-danger fw-semibold">
                            - {{ number_format((float) ($booking->discount ?? 0), 2) }}
                        </span>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Tax</span>
                        <span class="fw-semibold">
                            {{ number_format((float) ($booking->tax ?? 0), 2) }}
                        </span>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">

                        <span class="fw-bold">Total</span>

                        <span class="fw-bold fs-5">
                            {{ number_format((float) $booking->total_amount, 2) }}
                        </span>

                    </div>

                </div>

            </div>


            {{-- COMMISSION --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-pie me-2 text-primary"></i>
                        Commission
                    </h5>
                </div>

                <div class="card-body">

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Commission Rate</span>
                        <span class="fw-semibold">
                            {{ number_format((float) ($booking->commission_rate ?? 0), 2) }}%
                        </span>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Admin Earning</span>
                        <span class="fw-semibold text-primary">
                            {{ number_format((float) ($booking->admin_commission ?? 0), 2) }}
                        </span>
                    </div>

                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Vendor Earning</span>
                        <span class="fw-semibold text-success">
                            {{ number_format((float) ($booking->vendor_earning ?? 0), 2) }}
                        </span>
                    </div>

                </div>

            </div>


            {{-- VENDOR INFORMATION --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="fas fa-store me-2 text-primary"></i>
                        Vendor Information
                    </h5>
                </div>

                <div class="card-body">

                    @if($booking->vendor)

                        <div class="mb-3">

                            <small class="text-muted d-block">Vendor</small>

                            <div class="fw-semibold mt-1">
                                {{ $booking->vendor->name
                                    ?? $booking->vendor->business_name
                                    ?? 'N/A' }}
                            </div>

                        </div>

                        @if($booking->vendor->email)

                            <div class="mb-3">

                                <small class="text-muted d-block">Email</small>

                                <div class="mt-1">
                                    {{ $booking->vendor->email }}
                                </div>

                            </div>

                        @endif

                        @if($booking->vendor->phone)

                            <div>

                                <small class="text-muted d-block">Phone</small>

                                <div class="mt-1">
                                    {{ $booking->vendor->phone }}
                                </div>

                            </div>

                        @endif

                    @else

                        <div class="text-muted">
                            Vendor information unavailable.
                        </div>

                    @endif

                </div>

            </div>


            {{-- QUICK ACTIONS --}}
            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt me-2 text-primary"></i>
                        Quick Actions
                    </h5>
                </div>

                <div class="card-body">

                    <div class="d-grid gap-2">


                        {{-- APPROVE / REJECT --}}
                        @if(
                            $booking->payment_status === 'pending' &&
                            $pendingPayment
                        )

                            {{-- APPROVE --}}
                            <form
                                action="{{ route('vendor.transport-bookings.payments.approve', [
                                    'booking' => $booking->id,
                                    'payment' => $pendingPayment->id,
                                ]) }}"
                                method="POST"
                                onsubmit="return confirm('Approve this payment?')"
                            >

                                @csrf

                                <button
                                    type="submit"
                                    class="btn btn-success w-100"
                                >
                                    <i class="fas fa-check-circle me-1"></i>
                                    Approve Payment
                                </button>

                            </form>


                            {{-- REJECT --}}
                            <form
                                action="{{ route('vendor.transport-bookings.payments.reject', [
                                    'booking' => $booking->id,
                                    'payment' => $pendingPayment->id,
                                ]) }}"
                                method="POST"
                                onsubmit="return confirm('Reject this payment?')"
                            >

                                @csrf

                                <button
                                    type="submit"
                                    class="btn btn-danger w-100"
                                >
                                    <i class="fas fa-times-circle me-1"></i>
                                    Reject Payment
                                </button>

                            </form>

                        @endif


                        {{-- CONFIRM BOOKING --}}
                        @if(
                            $booking->booking_status === 'pending' &&
                            $booking->payment_status === 'paid'
                        )

                            <form
                                action="{{ route('vendor.transport-bookings.confirm', $booking) }}"
                                method="POST"
                                onsubmit="return confirm('Confirm this booking?')"
                            >

                                @csrf

                                <button
                                    type="submit"
                                    class="btn btn-primary w-100"
                                >
                                    <i class="fas fa-calendar-check me-1"></i>
                                    Confirm Booking
                                </button>

                            </form>

                        @endif


                        {{-- EDIT --}}
                        @if(
                            !in_array(
                                $booking->booking_status,
                                ['completed', 'cancelled'],
                                true
                            )
                        )

                            <a
                                href="{{ route('vendor.transport-bookings.edit', $booking) }}"
                                class="btn btn-warning w-100"
                            >
                                <i class="fas fa-edit me-1"></i>
                                Edit Booking
                            </a>

                        @endif


                        {{-- CANCEL --}}
                        @if(
                            !in_array(
                                $booking->booking_status,
                                ['completed', 'cancelled'],
                                true
                            )
                        )

                            <form
                                action="{{ route('vendor.transport-bookings.cancel', $booking) }}"
                                method="POST"
                                onsubmit="return confirm('Are you sure you want to cancel this booking?')"
                            >

                                @csrf

                                <button
                                    type="submit"
                                    class="btn btn-outline-danger w-100"
                                >
                                    <i class="fas fa-ban me-1"></i>
                                    Cancel Booking
                                </button>

                            </form>

                        @endif


                        {{-- DELETE --}}
                        <form
                            action="{{ route('vendor.transport-bookings.destroy', $booking) }}"
                            method="POST"
                            onsubmit="return confirm('Are you sure you want to permanently delete this booking?')"
                        >

                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="btn btn-outline-danger w-100"
                            >
                                <i class="fas fa-trash me-1"></i>
                                Delete Booking
                            </button>

                        </form>


                        {{-- BACK --}}
                        <a
                            href="{{ route('vendor.transport-bookings.index') }}"
                            class="btn btn-light border w-100"
                        >
                            <i class="fas fa-arrow-left me-1"></i>
                            Back to Bookings
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection