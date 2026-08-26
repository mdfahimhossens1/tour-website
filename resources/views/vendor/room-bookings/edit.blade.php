@extends('layouts.vendor')

@section('title', 'Edit Room Booking')

@section('page')

<div class="container-fluid py-4">

    {{-- ==========================================================
        Header
    =========================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>

            <h4 class="fw-bold mb-1">
                Edit Room Booking
            </h4>

            <p class="text-muted mb-0">
                Update booking details and manage booking status.
            </p>

        </div>


        <div>

            <a
                href="{{ route('vendor.room-bookings.show', $booking) }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Back to Booking
            </a>

        </div>

    </div>


    {{-- ==========================================================
        Validation Errors
    =========================================================== --}}
    @if($errors->any())

        <div class="alert alert-danger border-0 shadow-sm">

            <div class="fw-bold mb-2">
                <i class="bi bi-exclamation-triangle me-1"></i>
                Please fix the following errors:
            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- ==========================================================
        Booking Summary
    =========================================================== --}}
    <div class="row g-4">

        <div class="col-lg-8">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="fw-bold mb-0">
                        Booking Information
                    </h5>

                </div>


                <div class="card-body">

                    <form
                        action="{{ route('vendor.room-bookings.update', $booking) }}"
                        method="POST"
                    >

                        @csrf

                        @method('PUT')


                        {{-- ==================================================
                            Booking Code
                        =================================================== --}}
                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Booking Code
                            </label>

                            <input
                                type="text"
                                class="form-control bg-light"
                                value="{{ $booking->booking_code }}"
                                readonly
                            >

                            <small class="text-muted">
                                Booking code cannot be changed.
                            </small>

                        </div>


                        {{-- ==================================================
                            Customer / Resort / Room
                        =================================================== --}}
                        <div class="row g-3 mb-4">

                            <div class="col-md-4">

                                <label class="form-label fw-semibold">
                                    Customer
                                </label>

                                <input
                                    type="text"
                                    class="form-control bg-light"
                                    value="{{ $booking->user?->name ?? 'N/A' }}"
                                    readonly
                                >

                            </div>


                            <div class="col-md-4">

                                <label class="form-label fw-semibold">
                                    Resort
                                </label>

                                <input
                                    type="text"
                                    class="form-control bg-light"
                                    value="{{ $booking->resort?->name ?? 'N/A' }}"
                                    readonly
                                >

                            </div>


                            <div class="col-md-4">

                                <label class="form-label fw-semibold">
                                    Room
                                </label>

                                <input
                                    type="text"
                                    class="form-control bg-light"
                                    value="{{ $booking->room?->name ?? 'N/A' }}"
                                    readonly
                                >

                            </div>

                        </div>


                        {{-- ==================================================
                            Stay Dates
                        =================================================== --}}
                        <div class="row g-3 mb-4">

                            <div class="col-md-6">

                                <label
                                    for="check_in"
                                    class="form-label fw-semibold"
                                >
                                    Check-in Date
                                </label>

                                <input
                                    type="date"
                                    name="check_in"
                                    id="check_in"
                                    class="form-control @error('check_in') is-invalid @enderror"
                                    value="{{ old(
                                        'check_in',
                                        optional($booking->check_in)->format('Y-m-d')
                                    ) }}"
                                    required
                                >

                                @error('check_in')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            <div class="col-md-6">

                                <label
                                    for="check_out"
                                    class="form-label fw-semibold"
                                >
                                    Check-out Date
                                </label>

                                <input
                                    type="date"
                                    name="check_out"
                                    id="check_out"
                                    class="form-control @error('check_out') is-invalid @enderror"
                                    value="{{ old(
                                        'check_out',
                                        optional($booking->check_out)->format('Y-m-d')
                                    ) }}"
                                    required
                                >

                                @error('check_out')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                        </div>


                        {{-- ==================================================
                            Guest Information
                        =================================================== --}}
                        <div class="row g-3 mb-4">

                            <div class="col-md-4">

                                <label
                                    for="room_count"
                                    class="form-label fw-semibold"
                                >
                                    Room Count
                                </label>

                                <input
                                    type="number"
                                    id="room_count"
                                    class="form-control bg-light"
                                    value="{{ $booking->room_count ?? 1 }}"
                                    readonly
                                >

                                <small class="text-muted">
                                    Room count cannot be changed here.
                                </small>

                            </div>


                            <div class="col-md-4">

                                <label
                                    for="adults"
                                    class="form-label fw-semibold"
                                >
                                    Adults
                                </label>

                                <input
                                    type="number"
                                    name="adults"
                                    id="adults"
                                    min="1"
                                    class="form-control @error('adults') is-invalid @enderror"
                                    value="{{ old(
                                        'adults',
                                        $booking->adults ?? 1
                                    ) }}"
                                    required
                                >

                                @error('adults')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            <div class="col-md-4">

                                <label
                                    for="children"
                                    class="form-label fw-semibold"
                                >
                                    Children
                                </label>

                                <input
                                    type="number"
                                    name="children"
                                    id="children"
                                    min="0"
                                    class="form-control @error('children') is-invalid @enderror"
                                    value="{{ old(
                                        'children',
                                        $booking->children ?? 0
                                    ) }}"
                                >

                                @error('children')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                        </div>


                        {{-- ==================================================
                            Booking Status
                        =================================================== --}}
                        <div class="mb-4">

                            <label
                                for="booking_status"
                                class="form-label fw-semibold"
                            >
                                Booking Status
                            </label>

                            <select
                                name="booking_status"
                                id="booking_status"
                                class="form-select @error('booking_status') is-invalid @enderror"
                                required
                            >

                                @php

                                    $currentStatus = old(
                                        'booking_status',
                                        $booking->booking_status
                                    );

                                @endphp


                                <option
                                    value="pending"
                                    {{ $currentStatus === 'pending' ? 'selected' : '' }}
                                >
                                    Pending
                                </option>


                                <option
                                    value="confirmed"
                                    {{ $currentStatus === 'confirmed' ? 'selected' : '' }}
                                >
                                    Confirmed
                                </option>


                                <option
                                    value="checked_in"
                                    {{ $currentStatus === 'checked_in' ? 'selected' : '' }}
                                >
                                    Checked In
                                </option>


                                <option
                                    value="checked_out"
                                    {{ $currentStatus === 'checked_out' ? 'selected' : '' }}
                                >
                                    Checked Out
                                </option>


                                <option
                                    value="cancelled"
                                    {{ $currentStatus === 'cancelled' ? 'selected' : '' }}
                                >
                                    Cancelled
                                </option>

                            </select>

                            @error('booking_status')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                            <small class="text-muted d-block mt-1">

                                Status transitions are protected by the booking controller.

                            </small>

                        </div>


                        {{-- ==================================================
                            Payment Status
                        =================================================== --}}
                        <div class="mb-4">

                            <label
                                for="payment_status"
                                class="form-label fw-semibold"
                            >
                                Payment Status
                            </label>

                            <select
                                name="payment_status"
                                id="payment_status"
                                class="form-select @error('payment_status') is-invalid @enderror"
                                required
                            >

                                @php

                                    $paymentStatus = old(
                                        'payment_status',
                                        $booking->payment_status
                                    );

                                @endphp


                                <option
                                    value="pending"
                                    {{ $paymentStatus === 'pending' ? 'selected' : '' }}
                                >
                                    Pending
                                </option>


                                <option
                                    value="paid"
                                    {{ $paymentStatus === 'paid' ? 'selected' : '' }}
                                >
                                    Paid
                                </option>


                                <option
                                    value="failed"
                                    {{ $paymentStatus === 'failed' ? 'selected' : '' }}
                                >
                                    Failed
                                </option>


                                <option
                                    value="refunded"
                                    {{ $paymentStatus === 'refunded' ? 'selected' : '' }}
                                >
                                    Refunded
                                </option>

                            </select>

                            @error('payment_status')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- ==================================================
                            Special Request
                        =================================================== --}}
                        <div class="mb-4">

                            <label
                                for="special_request"
                                class="form-label fw-semibold"
                            >
                                Special Request
                            </label>

                            <textarea
                                name="special_request"
                                id="special_request"
                                rows="5"
                                maxlength="5000"
                                class="form-control @error('special_request') is-invalid @enderror"
                                placeholder="Enter any special request..."
                            >{{ old(
                                'special_request',
                                $booking->special_request
                            ) }}</textarea>

                            @error('special_request')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- ==================================================
                            Submit
                        =================================================== --}}
                        <div class="d-flex justify-content-end gap-2">

                            <a
                                href="{{ route('vendor.room-bookings.show', $booking) }}"
                                class="btn btn-light border"
                            >
                                Cancel
                            </a>

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                <i class="bi bi-save me-1"></i>
                                Update Booking
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>


        {{-- ==========================================================
            Right Sidebar
        =========================================================== --}}
        <div class="col-lg-4">


            {{-- ==================================================
                Booking Summary
            =================================================== --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="fw-bold mb-0">
                        Booking Summary
                    </h5>

                </div>


                <div class="card-body">

                    <div class="d-flex justify-content-between mb-3">

                        <span class="text-muted">
                            Booking Code
                        </span>

                        <span class="fw-semibold">
                            {{ $booking->booking_code }}
                        </span>

                    </div>


                    <div class="d-flex justify-content-between mb-3">

                        <span class="text-muted">
                            Room Count
                        </span>

                        <span class="fw-semibold">

                            {{ $booking->room_count ?? 1 }}

                        </span>

                    </div>


                    <div class="d-flex justify-content-between mb-3">

                        <span class="text-muted">
                            Total Nights
                        </span>

                        <span class="fw-semibold">

                            {{ $booking->total_nights ?? 0 }}

                        </span>

                    </div>


                    <hr>


                    <div class="d-flex justify-content-between mb-3">

                        <span class="text-muted">
                            Subtotal
                        </span>

                        <span class="fw-semibold">

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


                    <div class="d-flex justify-content-between">

                        <span class="text-muted">
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


            {{-- ==================================================
                Guest List
            =================================================== --}}
            @if($booking->guests && $booking->guests->count())

                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white border-0 py-3">

                        <h5 class="fw-bold mb-0">
                            Guest Information
                        </h5>

                    </div>


                    <div class="card-body">

                        @foreach($booking->guests as $guest)

                            <div
                                class="border rounded p-3 mb-3"
                            >

                                <div class="fw-semibold mb-1">

                                    <i class="bi bi-person me-1"></i>

                                    {{ $guest->name }}

                                </div>


                                @if($guest->age !== null)

                                    <small class="text-muted d-block">

                                        Age:
                                        {{ $guest->age }}

                                    </small>

                                @endif


                                @if($guest->gender)

                                    <small class="text-muted d-block">

                                        Gender:
                                        {{ ucfirst($guest->gender) }}

                                    </small>

                                @endif


                                @if($guest->phone)

                                    <small class="text-muted d-block">

                                        Phone:
                                        {{ $guest->phone }}

                                    </small>

                                @endif

                            </div>

                        @endforeach

                    </div>

                </div>

            @endif


            {{-- ==================================================
                Warning
            =================================================== --}}
            <div class="alert alert-warning border-0 shadow-sm">

                <div class="fw-semibold mb-1">

                    <i class="bi bi-info-circle me-1"></i>

                    Important

                </div>

                <small>

                    Financial amounts, commission and vendor earnings
                    are not editable from this form.

                    Booking cancellation should be performed through
                    the dedicated Cancel Booking action.

                </small>

            </div>

        </div>

    </div>

</div>

@endsection
