@extends('layouts.vendor')

@section('title', 'Room Bookings')

@section('page')

<div class="container-fluid py-4">

    {{-- ==========================================================
        Header
    =========================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h4 class="fw-bold mb-1">
                Room Bookings
            </h4>

            <p class="text-muted mb-0">
                Manage and monitor all room bookings.
            </p>
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


    {{-- ==========================================================
        Booking Statistics
    =========================================================== --}}
    <div class="row g-3 mb-4">

        {{-- Total --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted d-block mb-1">
                                Total Bookings
                            </small>

                            <h4 class="fw-bold mb-0">
                                {{ $bookings->total() }}
                            </h4>

                        </div>

                        <div
                            class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center"
                            style="width:50px;height:50px;"
                        >
                            <i class="bi bi-calendar-check fs-4 text-primary"></i>
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

                            <small class="text-muted d-block mb-1">
                                Pending
                            </small>

                            <h4 class="fw-bold mb-0">
                                {{ $bookings->where('booking_status', 'pending')->count() }}
                            </h4>

                        </div>

                        <div
                            class="rounded-circle bg-warning bg-opacity-10 d-flex align-items-center justify-content-center"
                            style="width:50px;height:50px;"
                        >
                            <i class="bi bi-clock fs-4 text-warning"></i>
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

                            <small class="text-muted d-block mb-1">
                                Confirmed
                            </small>

                            <h4 class="fw-bold mb-0">
                                {{ $bookings->where('booking_status', 'confirmed')->count() }}
                            </h4>

                        </div>

                        <div
                            class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center"
                            style="width:50px;height:50px;"
                        >
                            <i class="bi bi-check-circle fs-4 text-success"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Earnings --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted d-block mb-1">
                                Current Page Earnings
                            </small>

                            <h5 class="fw-bold mb-0">

                                ৳{{ number_format(
                                    $bookings->sum('vendor_earning'),
                                    2
                                ) }}

                            </h5>

                        </div>

                        <div
                            class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center"
                            style="width:50px;height:50px;"
                        >
                            <i class="bi bi-wallet2 fs-4 text-success"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ==========================================================
        Bookings Table
    =========================================================== --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">

                <div>

                    <h5 class="fw-bold mb-1">
                        Room Booking List
                    </h5>

                    <small class="text-muted">
                        All room bookings associated with your resorts.
                    </small>

                </div>

            </div>

        </div>


        <div class="card-body p-0">

            @if($bookings->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th class="ps-4">
                                    Booking
                                </th>

                                <th>
                                    Customer
                                </th>

                                <th>
                                    Resort / Room
                                </th>

                                <th>
                                    Stay
                                </th>

                                <th>
                                    Guests
                                </th>

                                <th>
                                    Amount
                                </th>

                                <th>
                                    Payment
                                </th>

                                <th>
                                    Booking Status
                                </th>

                                <th class="text-end pe-4">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($bookings as $booking)

                                <tr>

                                    {{-- ==================================================
                                        Booking
                                    =================================================== --}}
                                    <td class="ps-4">

                                        <div class="fw-bold">

                                            {{ $booking->booking_code }}

                                        </div>

                                        <small class="text-muted">

                                            {{ $booking->created_at?->format('d M Y, h:i A') ?? 'N/A' }}

                                        </small>

                                    </td>


                                    {{-- ==================================================
                                        Customer
                                    =================================================== --}}
                                    <td>

                                        <div class="fw-semibold">

                                            {{ $booking->user?->name ?? 'N/A' }}

                                        </div>

                                        @if($booking->user?->email)

                                            <small class="text-muted">

                                                {{ $booking->user->email }}

                                            </small>

                                        @endif

                                    </td>


                                    {{-- ==================================================
                                        Resort / Room
                                    =================================================== --}}
                                    <td>

                                        <div class="fw-semibold">

                                            {{ $booking->room?->name ?? 'N/A' }}

                                        </div>

                                        <small class="text-muted">

                                            {{ $booking->room?->name ?? 'N/A' }}

                                            @if($booking->room?->room_no)

                                                · Room {{ $booking->room->room_no }}

                                            @endif

                                        </small>

                                    </td>


                                    {{-- ==================================================
                                        Stay
                                    =================================================== --}}
                                    <td>

                                        <div class="fw-semibold">

                                            {{ $booking->check_in?->format('d M Y') ?? 'N/A' }}

                                        </div>

                                        <small class="text-muted">

                                            to

                                            {{ $booking->check_out?->format('d M Y') ?? 'N/A' }}

                                        </small>

                                        <div class="mt-1">

                                            <span class="badge bg-light text-dark border">

                                                {{ $booking->total_nights }}

                                                {{ $booking->total_nights == 1 ? 'Night' : 'Nights' }}

                                            </span>

                                        </div>

                                    </td>


                                    {{-- ==================================================
                                        Guests
                                    =================================================== --}}
                                    <td>

                                        <div class="fw-semibold">

                                            <i class="bi bi-people me-1"></i>

                                            {{ $booking->adults ?? 0 }}

                                            {{ ($booking->adults ?? 0) == 1 ? 'Adult' : 'Adults' }}

                                        </div>

                                        @if(($booking->children ?? 0) > 0)

                                            <small class="text-muted">

                                                {{ $booking->children }}

                                                {{ $booking->children == 1 ? 'Child' : 'Children' }}

                                            </small>

                                        @endif

                                        <div class="mt-1">

                                            <span class="badge bg-light text-dark border">

                                                {{ $booking->room_count ?? 1 }}

                                                {{ ($booking->room_count ?? 1) == 1 ? 'Room' : 'Rooms' }}

                                            </span>

                                        </div>

                                    </td>


                                    {{-- ==================================================
                                        Amount
                                    =================================================== --}}
                                    <td>

                                        <div class="fw-bold">

                                            ৳{{ number_format(
                                                $booking->total_amount ?? 0,
                                                2
                                            ) }}

                                        </div>

                                        <small class="text-success">

                                            Earning:

                                            ৳{{ number_format(
                                                $booking->vendor_earning ?? 0,
                                                2
                                            ) }}

                                        </small>

                                    </td>


                                    {{-- ==================================================
                                        Payment
                                    =================================================== --}}
                                    <td>

                                        @if($booking->payment_status === 'paid')

                                            <span class="badge bg-success">

                                                <i class="bi bi-check-circle me-1"></i>

                                                Paid

                                            </span>

                                        @elseif($booking->payment_status === 'pending')

                                            <span class="badge bg-warning text-dark">

                                                <i class="bi bi-clock me-1"></i>

                                                Pending

                                            </span>

                                        @elseif($booking->payment_status === 'failed')

                                            <span class="badge bg-danger">

                                                <i class="bi bi-x-circle me-1"></i>

                                                Failed

                                            </span>

                                        @elseif($booking->payment_status === 'refunded')

                                            <span class="badge bg-secondary">

                                                <i class="bi bi-arrow-counterclockwise me-1"></i>

                                                Refunded

                                            </span>

                                        @else

                                            <span class="badge bg-secondary">

                                                {{ ucfirst($booking->payment_status ?? 'Unknown') }}

                                            </span>

                                        @endif

                                    </td>


                                    {{-- ==================================================
                                        Booking Status
                                    =================================================== --}}
                                    <td>

                                        @switch($booking->booking_status)

                                            @case('pending')

                                                <span class="badge bg-warning text-dark">

                                                    <i class="bi bi-clock me-1"></i>

                                                    Pending

                                                </span>

                                                @break


                                            @case('confirmed')

                                                <span class="badge bg-success">

                                                    <i class="bi bi-check-circle me-1"></i>

                                                    Confirmed

                                                </span>

                                                @break


                                            @case('checked_in')

                                                <span class="badge bg-primary">

                                                    <i class="bi bi-box-arrow-in-right me-1"></i>

                                                    Checked In

                                                </span>

                                                @break


                                            @case('checked_out')

                                                <span class="badge bg-secondary">

                                                    <i class="bi bi-box-arrow-right me-1"></i>

                                                    Checked Out

                                                </span>

                                                @break


                                            @case('cancelled')

                                                <span class="badge bg-danger">

                                                    <i class="bi bi-x-circle me-1"></i>

                                                    Cancelled

                                                </span>

                                                @break


                                            @default

                                                <span class="badge bg-secondary">

                                                    {{ ucfirst($booking->booking_status ?? 'Unknown') }}

                                                </span>

                                        @endswitch

                                    </td>


                                    {{-- ==================================================
                                        Action
                                    =================================================== --}}
                                    <td class="text-end pe-4">

                                        <a
                                            href="{{ route(
                                                'vendor.room-bookings.show',
                                                $booking
                                            ) }}"
                                            class="btn btn-sm btn-outline-primary"
                                        >

                                            <i class="bi bi-eye me-1"></i>

                                            View

                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- ==========================================================
                    Pagination
                =========================================================== --}}
                @if($bookings->hasPages())

                    <div class="card-footer bg-white border-0 py-3">

                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                            <small class="text-muted">

                                Showing

                                {{ $bookings->firstItem() }}

                                to

                                {{ $bookings->lastItem() }}

                                of

                                {{ $bookings->total() }}

                                bookings

                            </small>


                            <div>

                                {{ $bookings->links() }}

                            </div>

                        </div>

                    </div>

                @endif

            @else

                {{-- ==========================================================
                    Empty State
                =========================================================== --}}
                <div class="text-center py-5">

                    <div
                        class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3"
                        style="width:80px;height:80px;"
                    >

                        <i class="bi bi-calendar-x fs-1 text-muted"></i>

                    </div>


                    <h5 class="fw-bold">

                        No Room Bookings Found

                    </h5>


                    <p class="text-muted mb-0">

                        There are currently no room bookings associated with your resorts.

                    </p>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection
