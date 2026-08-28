@extends('layouts.admin')

@section('title', 'Transport Bookings')

@section('page')

<div class="container-fluid">

{{-- ==========================================================
     PAGE HEADER
=========================================================== --}}
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

    <div>
        <h4 class="fw-bold mb-1">
            Transport Bookings
        </h4>

        <p class="text-muted mb-0">
            Manage transport bookings, payments and booking status.
        </p>
    </div>

</div>


{{-- ==========================================================
     FLASH MESSAGE
=========================================================== --}}

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
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
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle me-2"></i>

        {{ session('error') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert">
        </button>
    </div>
@endif


{{-- ==========================================================
     STATISTICS
=========================================================== --}}

<div class="row g-3 mb-4">

    {{-- Total --}}
    <div class="col-xl-3 col-md-6">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <p class="text-muted mb-1">
                            Total Bookings
                        </p>

                        <h3 class="fw-bold mb-0">
                            {{ $totalBookings ?? $bookings->total() }}
                        </h3>

                    </div>

                    <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                        <i class="fas fa-car text-primary fs-4"></i>
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

                        <p class="text-muted mb-1">
                            Pending
                        </p>

                        <h3 class="fw-bold mb-0">
                            {{ $pendingBookings ?? 0 }}
                        </h3>

                    </div>

                    <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                        <i class="fas fa-clock text-warning fs-4"></i>
                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Confirmed --}}
    <div class="col-xl-3 col-md-6">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <p class="text-muted mb-1">
                            Confirmed
                        </p>

                        <h3 class="fw-bold mb-0">
                            {{ $confirmedBookings ?? 0 }}
                        </h3>

                    </div>

                    <div class="rounded-circle bg-success bg-opacity-10 p-3">
                        <i class="fas fa-check-circle text-success fs-4"></i>
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

                        <p class="text-muted mb-1">
                            Total Revenue
                        </p>

                        <h3 class="fw-bold mb-0">
                            ৳{{ number_format($totalRevenue ?? 0, 2) }}
                        </h3>

                    </div>

                    <div class="rounded-circle bg-info bg-opacity-10 p-3">
                        <i class="fas fa-money-bill-wave text-info fs-4"></i>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- ==========================================================
     FILTER / SEARCH
=========================================================== --}}

<div class="card border-0 shadow-sm mb-4">

    <div class="card-body">

        <form
            method="GET"
            action="{{ route('admin.transport-bookings.index') }}"
        >

            <div class="row g-3">

                {{-- Search --}}
                <div class="col-lg-4">

                    <label class="form-label fw-semibold">
                        Search
                    </label>

                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        value="{{ request('search') }}"
                        placeholder="Booking code, customer, email..."
                    >

                </div>


                {{-- Booking Status --}}
                <div class="col-lg-2">

                    <label class="form-label fw-semibold">
                        Booking Status
                    </label>

                    <select
                        name="booking_status"
                        class="form-select"
                    >

                        <option value="">
                            All Status
                        </option>

                        <option
                            value="pending"
                            {{ request('booking_status') == 'pending' ? 'selected' : '' }}
                        >
                            Pending
                        </option>

                        <option
                            value="confirmed"
                            {{ request('booking_status') == 'confirmed' ? 'selected' : '' }}
                        >
                            Confirmed
                        </option>

                        <option
                            value="cancelled"
                            {{ request('booking_status') == 'cancelled' ? 'selected' : '' }}
                        >
                            Cancelled
                        </option>

                        <option
                            value="completed"
                            {{ request('booking_status') == 'completed' ? 'selected' : '' }}
                        >
                            Completed
                        </option>

                    </select>

                </div>


                {{-- Payment Status --}}
                <div class="col-lg-2">

                    <label class="form-label fw-semibold">
                        Payment
                    </label>

                    <select
                        name="payment_status"
                        class="form-select"
                    >

                        <option value="">
                            All Payments
                        </option>

                        <option
                            value="pending"
                            {{ request('payment_status') == 'pending' ? 'selected' : '' }}
                        >
                            Pending
                        </option>

                        <option
                            value="paid"
                            {{ request('payment_status') == 'paid' ? 'selected' : '' }}
                        >
                            Paid
                        </option>

                        <option
                            value="failed"
                            {{ request('payment_status') == 'failed' ? 'selected' : '' }}
                        >
                            Failed
                        </option>

                        <option
                            value="refunded"
                            {{ request('payment_status') == 'refunded' ? 'selected' : '' }}
                        >
                            Refunded
                        </option>

                    </select>

                </div>


                {{-- Search button --}}
                <div class="col-lg-2 d-flex align-items-end">

                    <button
                        type="submit"
                        class="btn btn-primary w-100"
                    >

                        <i class="fas fa-search me-1"></i>

                        Filter

                    </button>

                </div>


                {{-- Reset --}}
                <div class="col-lg-2 d-flex align-items-end">

                    <a
                        href="{{ route('admin.transport-bookings.index') }}"
                        class="btn btn-light border w-100"
                    >

                        <i class="fas fa-sync-alt me-1"></i>

                        Reset

                    </a>

                </div>

            </div>

        </form>

    </div>

</div>


{{-- ==========================================================
     BOOKING TABLE
=========================================================== --}}

<div class="card border-0 shadow-sm">

    <div class="card-header bg-transparent py-3">

        <div class="d-flex justify-content-between align-items-center">

            <div>

                <h5 class="fw-bold mb-0">
                    All Transport Bookings
                </h5>

                <small class="text-muted">
                    {{ $bookings->total() }} booking(s)
                </small>

            </div>

        </div>

    </div>


    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead>

                    <tr>

                        <th class="px-3">
                            Booking
                        </th>

                        <th>
                            Customer
                        </th>

                        <th>
                            Vehicle
                        </th>

                        <th>
                            Journey
                        </th>

                        <th>
                            Amount
                        </th>

                        <th>
                            Payment
                        </th>

                        <th>
                            Status
                        </th>

                        <th class="text-end px-3">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                @forelse($bookings as $booking)

                    <tr>

                        {{-- ==================================================
                             BOOKING
                        =================================================== --}}

                        <td class="px-3">

                            <div class="fw-bold">

                                {{ $booking->booking_code }}

                            </div>

                            <small class="text-muted">

                                {{ $booking->created_at?->format('d M Y, h:i A') }}

                            </small>

                        </td>


                        {{-- ==================================================
                             CUSTOMER
                        =================================================== --}}

                        <td>

                            @if($booking->user)

                                <div class="fw-semibold">

                                    {{ $booking->user->name }}

                                </div>

                                <small class="text-muted">

                                    {{ $booking->user->email }}

                                </small>

                            @else

                                <span class="text-muted">
                                    N/A
                                </span>

                            @endif

                        </td>


                        {{-- ==================================================
                             VEHICLE
                        =================================================== --}}

                        <td>

                            @if($booking->vehicle)

                                <div class="fw-semibold">

                                    {{ $booking->vehicle->name }}

                                </div>

                                @if($booking->vendor)

                                    <small class="text-muted">

                                        Vendor:
                                        {{ $booking->vendor->name }}

                                    </small>

                                @endif

                            @else

                                <span class="text-muted">
                                    Vehicle unavailable
                                </span>

                            @endif

                        </td>


                        {{-- ==================================================
                             JOURNEY
                        =================================================== --}}

                        <td>

                            <div>

                                {{ $booking->start_date?->format('d M Y') }}

                            </div>

                            <small class="text-muted">

                                to
                                {{ $booking->end_date?->format('d M Y') }}

                                <br>

                                {{ $booking->total_days }} day(s)

                            </small>

                        </td>


                        {{-- ==================================================
                             AMOUNT
                        =================================================== --}}

                        <td>

                            <strong>

                                ৳{{ number_format($booking->total_amount, 2) }}

                            </strong>

                            <br>

                            <small class="text-muted">

                                {{ $booking->passengers }}
                                passenger(s)

                            </small>

                        </td>


                        {{-- ==================================================
                             PAYMENT STATUS
                        =================================================== --}}

                        <td>

                            @php

                                $paymentClass = match($booking->payment_status) {

                                    'paid' => 'success',

                                    'failed' => 'danger',

                                    'refunded' => 'secondary',

                                    default => 'warning',

                                };

                            @endphp

                            <span class="badge bg-{{ $paymentClass }}">

                                {{ ucfirst($booking->payment_status) }}

                            </span>

                        </td>


                        {{-- ==================================================
                             BOOKING STATUS
                        =================================================== --}}

                        <td>

                            @php

                                $statusClass = match($booking->booking_status) {

                                    'confirmed' => 'success',

                                    'completed' => 'primary',

                                    'cancelled' => 'danger',

                                    default => 'warning',

                                };

                            @endphp

                            <span class="badge bg-{{ $statusClass }}">

                                {{ ucfirst($booking->booking_status) }}

                            </span>

                        </td>


                        {{-- ==================================================
                             ACTIONS
                        =================================================== --}}

                        <td class="text-end px-3">

                            {{-- VIEW --}}
                            <button
                                type="button"
                                class="btn btn-sm btn-outline-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#viewBookingModal{{ $booking->id }}"
                                title="View Booking"
                            >

                                <i class="fas fa-eye"></i>

                            </button>


                            {{-- EDIT --}}
                            <button
                                type="button"
                                class="btn btn-sm btn-outline-secondary"
                                data-bs-toggle="modal"
                                data-bs-target="#editBookingModal{{ $booking->id }}"
                                title="Edit Booking"
                            >

                                <i class="fas fa-edit"></i>

                            </button>


                            {{-- DELETE --}}
                            <button
                                type="button"
                                class="btn btn-sm btn-outline-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#deleteBookingModal{{ $booking->id }}"
                                title="Delete Booking"
                            >

                                <i class="fas fa-trash"></i>

                            </button>

                        </td>

                    </tr>


                    {{-- ======================================================
                         VIEW MODAL
                    ======================================================= --}}

                    <div
                        class="modal fade"
                        id="viewBookingModal{{ $booking->id }}"
                        tabindex="-1"
                        aria-hidden="true"
                    >

                        <div class="modal-dialog modal-lg modal-dialog-centered">

                            <div class="modal-content">

                                <div class="modal-header">

                                    <div>

                                        <h5 class="modal-title fw-bold">

                                            Transport Booking Details

                                        </h5>

                                        <small class="text-muted">

                                            {{ $booking->booking_code }}

                                        </small>

                                    </div>

                                    <button
                                        type="button"
                                        class="btn-close"
                                        data-bs-dismiss="modal"
                                    ></button>

                                </div>


                                <div class="modal-body">

                                    <div class="row g-3">


                                        {{-- Booking Information --}}
                                        <div class="col-md-6">

                                            <div class="card border h-100">

                                                <div class="card-header bg-transparent">

                                                    <strong>
                                                        Booking Information
                                                    </strong>

                                                </div>

                                                <div class="card-body">

                                                    <div class="mb-2">
                                                        <small class="text-muted">
                                                            Booking Code
                                                        </small>

                                                        <div class="fw-semibold">
                                                            {{ $booking->booking_code }}
                                                        </div>
                                                    </div>

                                                    <div class="mb-2">
                                                        <small class="text-muted">
                                                            Created
                                                        </small>

                                                        <div>
                                                            {{ $booking->created_at?->format('d M Y, h:i A') }}
                                                        </div>
                                                    </div>

                                                    <div>
                                                        <small class="text-muted">
                                                            Passengers
                                                        </small>

                                                        <div>
                                                            {{ $booking->passengers }}
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>

                                        </div>


                                        {{-- Customer --}}
                                        <div class="col-md-6">

                                            <div class="card border h-100">

                                                <div class="card-header bg-transparent">

                                                    <strong>
                                                        Customer
                                                    </strong>

                                                </div>

                                                <div class="card-body">

                                                    <div class="mb-2">

                                                        <small class="text-muted">
                                                            Name
                                                        </small>

                                                        <div class="fw-semibold">

                                                            {{ $booking->user?->name ?? 'N/A' }}

                                                        </div>

                                                    </div>

                                                    <div>

                                                        <small class="text-muted">
                                                            Email
                                                        </small>

                                                        <div>

                                                            {{ $booking->user?->email ?? 'N/A' }}

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>


                                        {{-- Vehicle --}}
                                        <div class="col-md-6">

                                            <div class="card border h-100">

                                                <div class="card-header bg-transparent">

                                                    <strong>
                                                        Vehicle
                                                    </strong>

                                                </div>

                                                <div class="card-body">

                                                    <div class="mb-2">

                                                        <small class="text-muted">
                                                            Vehicle
                                                        </small>

                                                        <div class="fw-semibold">

                                                            {{ $booking->vehicle?->name ?? 'N/A' }}

                                                        </div>

                                                    </div>

                                                    <div>

                                                        <small class="text-muted">
                                                            Vendor
                                                        </small>

                                                        <div>

                                                            {{ $booking->vendor?->name ?? 'N/A' }}

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>


                                        {{-- Journey --}}
                                        <div class="col-md-6">

                                            <div class="card border h-100">

                                                <div class="card-header bg-transparent">

                                                    <strong>
                                                        Journey
                                                    </strong>

                                                </div>

                                                <div class="card-body">

                                                    <div class="mb-2">

                                                        <small class="text-muted">
                                                            Start Date
                                                        </small>

                                                        <div>
                                                            {{ $booking->start_date?->format('d M Y') }}
                                                        </div>

                                                    </div>

                                                    <div class="mb-2">

                                                        <small class="text-muted">
                                                            End Date
                                                        </small>

                                                        <div>
                                                            {{ $booking->end_date?->format('d M Y') }}
                                                        </div>

                                                    </div>

                                                    <div>

                                                        <small class="text-muted">
                                                            Total Days
                                                        </small>

                                                        <div>
                                                            {{ $booking->total_days }}
                                                        </div>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>


                                        {{-- Locations --}}
                                        <div class="col-12">

                                            <div class="card border">

                                                <div class="card-header bg-transparent">

                                                    <strong>
                                                        Locations & Request
                                                    </strong>

                                                </div>

                                                <div class="card-body">

                                                    <div class="row">

                                                        <div class="col-md-6 mb-3">

                                                            <small class="text-muted">
                                                                Pickup Location
                                                            </small>

                                                            <div>
                                                                {{ $booking->pickup_location ?? 'N/A' }}
                                                            </div>

                                                        </div>

                                                        <div class="col-md-6 mb-3">

                                                            <small class="text-muted">
                                                                Dropoff Location
                                                            </small>

                                                            <div>
                                                                {{ $booking->dropoff_location ?? 'N/A' }}
                                                            </div>

                                                        </div>

                                                        <div class="col-12">

                                                            <small class="text-muted">
                                                                Special Request
                                                            </small>

                                                            <div>
                                                                {{ $booking->special_request ?? 'N/A' }}
                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>


                                        {{-- Pricing --}}
                                        <div class="col-12">

                                            <div class="card border">

                                                <div class="card-header bg-transparent">

                                                    <strong>
                                                        Pricing & Commission
                                                    </strong>

                                                </div>

                                                <div class="card-body">

                                                    <div class="row g-3">

                                                        <div class="col-md-3">

                                                            <small class="text-muted">
                                                                Price / Day
                                                            </small>

                                                            <div class="fw-semibold">
                                                                ৳{{ number_format($booking->price_per_day, 2) }}
                                                            </div>

                                                        </div>

                                                        <div class="col-md-3">

                                                            <small class="text-muted">
                                                                Subtotal
                                                            </small>

                                                            <div class="fw-semibold">
                                                                ৳{{ number_format($booking->subtotal, 2) }}
                                                            </div>

                                                        </div>

                                                        <div class="col-md-3">

                                                            <small class="text-muted">
                                                                Discount
                                                            </small>

                                                            <div class="fw-semibold">
                                                                ৳{{ number_format($booking->discount, 2) }}
                                                            </div>

                                                        </div>

                                                        <div class="col-md-3">

                                                            <small class="text-muted">
                                                                Tax
                                                            </small>

                                                            <div class="fw-semibold">
                                                                ৳{{ number_format($booking->tax, 2) }}
                                                            </div>

                                                        </div>

                                                        <div class="col-md-4">

                                                            <small class="text-muted">
                                                                Total Amount
                                                            </small>

                                                            <div class="fw-bold fs-5">
                                                                ৳{{ number_format($booking->total_amount, 2) }}
                                                            </div>

                                                        </div>

                                                        <div class="col-md-4">

                                                            <small class="text-muted">
                                                                Commission
                                                            </small>

                                                            <div>
                                                                {{ number_format($booking->commission_rate, 2) }}%
                                                            </div>

                                                        </div>

                                                        <div class="col-md-4">

                                                            <small class="text-muted">
                                                                Vendor Earning
                                                            </small>

                                                            <div class="fw-semibold">
                                                                ৳{{ number_format($booking->vendor_earning, 2) }}
                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>


                                <div class="modal-footer">

                                    <button
                                        type="button"
                                        class="btn btn-light border"
                                        data-bs-dismiss="modal"
                                    >

                                        Close

                                    </button>

                                    <button
                                        type="button"
                                        class="btn btn-primary"
                                        data-bs-dismiss="modal"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editBookingModal{{ $booking->id }}"
                                    >

                                        <i class="fas fa-edit me-1"></i>

                                        Edit Booking

                                    </button>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- ======================================================
                         EDIT MODAL
                    ======================================================= --}}

                    <div
                        class="modal fade"
                        id="editBookingModal{{ $booking->id }}"
                        tabindex="-1"
                        aria-hidden="true"
                    >

                        <div class="modal-dialog modal-lg modal-dialog-centered">

                            <div class="modal-content">

                                <form
                                    method="POST"
                                    action="{{ route('admin.transport-bookings.update', $booking->id) }}"
                                >

                                    @csrf

                                    @method('PUT')


                                    <div class="modal-header">

                                        <div>

                                            <h5 class="modal-title fw-bold">
                                                Edit Transport Booking
                                            </h5>

                                            <small class="text-muted">
                                                {{ $booking->booking_code }}
                                            </small>

                                        </div>

                                        <button
                                            type="button"
                                            class="btn-close"
                                            data-bs-dismiss="modal"
                                        ></button>

                                    </div>


                                    <div class="modal-body">

                                        <div class="row g-3">


                                            {{-- Booking Status --}}
                                            <div class="col-md-6">

                                                <label class="form-label fw-semibold">
                                                    Booking Status
                                                </label>

                                                <select
                                                    name="booking_status"
                                                    class="form-select"
                                                    required
                                                >

                                                    <option
                                                        value="pending"
                                                        {{ $booking->booking_status === 'pending' ? 'selected' : '' }}
                                                    >
                                                        Pending
                                                    </option>

                                                    <option
                                                        value="confirmed"
                                                        {{ $booking->booking_status === 'confirmed' ? 'selected' : '' }}
                                                    >
                                                        Confirmed
                                                    </option>

                                                    <option
                                                        value="cancelled"
                                                        {{ $booking->booking_status === 'cancelled' ? 'selected' : '' }}
                                                    >
                                                        Cancelled
                                                    </option>

                                                    <option
                                                        value="completed"
                                                        {{ $booking->booking_status === 'completed' ? 'selected' : '' }}
                                                    >
                                                        Completed
                                                    </option>

                                                </select>

                                            </div>


                                            {{-- Payment Status --}}
                                            <div class="col-md-6">

                                                <label class="form-label fw-semibold">
                                                    Payment Status
                                                </label>

                                                <select
                                                    name="payment_status"
                                                    class="form-select"
                                                    required
                                                >

                                                    <option
                                                        value="pending"
                                                        {{ $booking->payment_status === 'pending' ? 'selected' : '' }}
                                                    >
                                                        Pending
                                                    </option>

                                                    <option
                                                        value="paid"
                                                        {{ $booking->payment_status === 'paid' ? 'selected' : '' }}
                                                    >
                                                        Paid
                                                    </option>

                                                    <option
                                                        value="failed"
                                                        {{ $booking->payment_status === 'failed' ? 'selected' : '' }}
                                                    >
                                                        Failed
                                                    </option>

                                                    <option
                                                        value="refunded"
                                                        {{ $booking->payment_status === 'refunded' ? 'selected' : '' }}
                                                    >
                                                        Refunded
                                                    </option>

                                                </select>

                                            </div>


                                            {{-- Start Date --}}
                                            <div class="col-md-6">

                                                <label class="form-label fw-semibold">
                                                    Start Date
                                                </label>

                                                <input
                                                    type="date"
                                                    name="start_date"
                                                    class="form-control"
                                                    value="{{ $booking->start_date?->format('Y-m-d') }}"
                                                    required
                                                >

                                            </div>


                                            {{-- End Date --}}
                                            <div class="col-md-6">

                                                <label class="form-label fw-semibold">
                                                    End Date
                                                </label>

                                                <input
                                                    type="date"
                                                    name="end_date"
                                                    class="form-control"
                                                    value="{{ $booking->end_date?->format('Y-m-d') }}"
                                                    required
                                                >

                                            </div>


                                            {{-- Passengers --}}
                                            <div class="col-md-6">

                                                <label class="form-label fw-semibold">
                                                    Passengers
                                                </label>

                                                <input
                                                    type="number"
                                                    name="passengers"
                                                    class="form-control"
                                                    min="1"
                                                    value="{{ $booking->passengers }}"
                                                    required
                                                >

                                            </div>


                                            {{-- Pickup --}}
                                            <div class="col-md-6">

                                                <label class="form-label fw-semibold">
                                                    Pickup Location
                                                </label>

                                                <input
                                                    type="text"
                                                    name="pickup_location"
                                                    class="form-control"
                                                    value="{{ $booking->pickup_location }}"
                                                >

                                            </div>


                                            {{-- Dropoff --}}
                                            <div class="col-md-6">

                                                <label class="form-label fw-semibold">
                                                    Dropoff Location
                                                </label>

                                                <input
                                                    type="text"
                                                    name="dropoff_location"
                                                    class="form-control"
                                                    value="{{ $booking->dropoff_location }}"
                                                >

                                            </div>


                                            {{-- Special Request --}}
                                            <div class="col-12">

                                                <label class="form-label fw-semibold">
                                                    Special Request
                                                </label>

                                                <textarea
                                                    name="special_request"
                                                    class="form-control"
                                                    rows="3"
                                                >{{ $booking->special_request }}</textarea>

                                            </div>

                                        </div>

                                    </div>


                                    <div class="modal-footer">

                                        <button
                                            type="button"
                                            class="btn btn-light border"
                                            data-bs-dismiss="modal"
                                        >
                                            Cancel
                                        </button>

                                        <button
                                            type="submit"
                                            class="btn btn-primary"
                                        >

                                            <i class="fas fa-save me-1"></i>

                                            Save Changes

                                        </button>

                                    </div>

                                </form>

                            </div>

                        </div>

                    </div>


                    {{-- ======================================================
                         DELETE MODAL
                    ======================================================= --}}

                    <div
                        class="modal fade"
                        id="deleteBookingModal{{ $booking->id }}"
                        tabindex="-1"
                        aria-hidden="true"
                    >

                        <div class="modal-dialog modal-dialog-centered">

                            <div class="modal-content">

                                <div class="modal-header">

                                    <h5 class="modal-title fw-bold">
                                        Delete Booking
                                    </h5>

                                    <button
                                        type="button"
                                        class="btn-close"
                                        data-bs-dismiss="modal"
                                    ></button>

                                </div>


                                <div class="modal-body text-center py-4">

                                    <div class="mb-3">

                                        <i class="fas fa-exclamation-triangle text-danger fs-1"></i>

                                    </div>

                                    <h5>
                                        Are you sure?
                                    </h5>

                                    <p class="text-muted mb-0">

                                        You are about to delete booking

                                        <strong>
                                            {{ $booking->booking_code }}
                                        </strong>.

                                        This action cannot be undone.

                                    </p>

                                </div>


                                <div class="modal-footer justify-content-center">

                                    <button
                                        type="button"
                                        class="btn btn-light border"
                                        data-bs-dismiss="modal"
                                    >

                                        Cancel

                                    </button>


                                    <form
                                        method="POST"
                                        action="{{ route('admin.transport-bookings.destroy', $booking->id) }}"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-danger"
                                        >

                                            <i class="fas fa-trash me-1"></i>

                                            Yes, Delete

                                        </button>

                                    </form>

                                </div>

                            </div>

                        </div>

                    </div>


                @empty

                    <tr>

                        <td
                            colspan="8"
                            class="text-center py-5"
                        >

                            <div class="mb-3">

                                <i class="fas fa-car text-muted fs-1"></i>

                            </div>

                            <h6 class="fw-bold">
                                No Transport Bookings Found
                            </h6>

                            <p class="text-muted mb-0">
                                There are no transport bookings available.
                            </p>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- ==========================================================
         PAGINATION
    =========================================================== --}}

    @if($bookings->hasPages())

        <div class="card-footer bg-transparent">

            {{ $bookings->withQueryString()->links() }}

        </div>

    @endif

</div>

</div>

@endsection
