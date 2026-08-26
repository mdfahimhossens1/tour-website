@extends('layouts.vendor')

@section('title', 'Vendor Reports')

@section('page')

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h4 class="fw-bold mb-1">
                Reports
            </h4>

            <p class="text-muted mb-0">
                Monitor your bookings, revenue and earnings.
            </p>
        </div>

    </div>


    {{-- ==========================================================
        Statistics
    =========================================================== --}}

    <div class="row g-4 mb-4">

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">
                                Total Bookings
                            </small>

                            <h3 class="fw-bold mb-0">
                                {{ number_format($totalBookings) }}
                            </h3>

                        </div>

                        <div class="bg-primary bg-opacity-10 rounded p-3">
                            <i class="bi bi-calendar-check fs-4 text-primary"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">
                                Total Revenue
                            </small>

                            <h3 class="fw-bold mb-0">
                                ৳{{ number_format($totalRevenue, 2) }}
                            </h3>

                        </div>

                        <div class="bg-info bg-opacity-10 rounded p-3">
                            <i class="bi bi-cash-stack fs-4 text-info"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">
                                Admin Commission
                            </small>

                            <h3 class="fw-bold mb-0">
                                ৳{{ number_format($totalCommission, 2) }}
                            </h3>

                        </div>

                        <div class="bg-danger bg-opacity-10 rounded p-3">
                            <i class="bi bi-percent fs-4 text-danger"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">
                                Vendor Earnings
                            </small>

                            <h3 class="fw-bold text-success mb-0">
                                ৳{{ number_format($totalVendorEarning, 2) }}
                            </h3>

                        </div>

                        <div class="bg-success bg-opacity-10 rounded p-3">
                            <i class="bi bi-wallet2 fs-4 text-success"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ==========================================================
        Earnings Summary
    =========================================================== --}}

    <div class="row g-4 mb-4">

        <div class="col-md-4">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <small class="text-muted">
                        Released Earnings
                    </small>

                    <h4 class="fw-bold text-success mb-0">
                        ৳{{ number_format($releasedEarning, 2) }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <small class="text-muted">
                        Pending Earnings
                    </small>

                    <h4 class="fw-bold text-warning mb-0">
                        ৳{{ number_format($pendingEarning, 2) }}
                    </h4>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <small class="text-muted">
                        Completed Check-outs
                    </small>

                    <h4 class="fw-bold mb-0">
                        {{ number_format($totalCheckedOut) }}
                    </h4>

                </div>

            </div>

        </div>

    </div>


    {{-- ==========================================================
        Filters
    =========================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white border-0 py-3">

            <h5 class="fw-bold mb-1">
                Filter Report
            </h5>

            <small class="text-muted">
                Filter booking records by date and status.
            </small>

        </div>


        <div class="card-body">

            <form
                action="{{ route('vendor.reports.index') }}"
                method="GET"
            >

                <div class="row g-3">

                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            From Date
                        </label>

                        <input
                            type="date"
                            name="date_from"
                            class="form-control"
                            value="{{ request('date_from') }}"
                        >

                    </div>


                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            To Date
                        </label>

                        <input
                            type="date"
                            name="date_to"
                            class="form-control"
                            value="{{ request('date_to') }}"
                        >

                    </div>


                    <div class="col-md-3">

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

                            @foreach([
                                'pending',
                                'confirmed',
                                'checked_in',
                                'checked_out',
                                'cancelled'
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    @selected(request('booking_status') === $status)
                                >
                                    {{ ucwords(str_replace('_', ' ', $status)) }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Payment Status
                        </label>

                        <select
                            name="payment_status"
                            class="form-select"
                        >

                            <option value="">
                                All Payments
                            </option>

                            @foreach([
                                'pending',
                                'paid',
                                'failed',
                                'refunded'
                            ] as $status)

                                <option
                                    value="{{ $status }}"
                                    @selected(request('payment_status') === $status)
                                >
                                    {{ ucfirst($status) }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="col-12">

                        <div class="d-flex gap-2">

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                <i class="bi bi-funnel me-1"></i>
                                Apply Filter
                            </button>


                            <a
                                href="{{ route('vendor.reports.index') }}"
                                class="btn btn-light border"
                            >
                                Reset
                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- ==========================================================
        Booking Report
    =========================================================== --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="fw-bold mb-1">
                        Booking Report
                    </h5>

                    <small class="text-muted">
                        Booking-wise revenue and earning details.
                    </small>

                </div>

            </div>

        </div>


        <div class="card-body p-0">

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
                                Amount
                            </th>

                            <th>
                                Commission
                            </th>

                            <th>
                                Earning
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($bookings as $booking)

                            <tr>

                                <td class="ps-4">

                                    <strong>
                                        #{{ $booking->booking_code }}
                                    </strong>

                                    <small class="text-muted d-block">
                                        {{ $booking->created_at?->format('d M Y') }}
                                    </small>

                                </td>


                                <td>

                                    {{ $booking->user?->name ?? 'N/A' }}

                                </td>


                                <td>

                                    <strong>
                                        {{ $booking->resort?->name ?? 'N/A' }}
                                    </strong>

                                    <small class="text-muted d-block">
                                        {{ $booking->room?->name ?? 'N/A' }}
                                    </small>

                                </td>


                                <td>

                                    <strong>
                                        {{ $booking->total_nights }}
                                    </strong>

                                    nights

                                    <small class="text-muted d-block">
                                        {{ $booking->check_in?->format('d M Y') }}
                                        -
                                        {{ $booking->check_out?->format('d M Y') }}
                                    </small>

                                </td>


                                <td>

                                    <strong>
                                        ৳{{ number_format($booking->total_amount, 2) }}
                                    </strong>

                                </td>


                                <td>

                                    <span class="text-danger">
                                        ৳{{ number_format($booking->admin_commission ?? 0, 2) }}
                                    </span>

                                </td>


                                <td>

                                    <strong class="text-success">
                                        ৳{{ number_format($booking->vendor_earning ?? 0, 2) }}
                                    </strong>

                                </td>


                                <td>

                                    @if($booking->booking_status === 'confirmed')

                                        <span class="badge bg-success">
                                            Confirmed
                                        </span>

                                    @elseif($booking->booking_status === 'checked_in')

                                        <span class="badge bg-primary">
                                            Checked In
                                        </span>

                                    @elseif($booking->booking_status === 'checked_out')

                                        <span class="badge bg-secondary">
                                            Checked Out
                                        </span>

                                    @elseif($booking->booking_status === 'cancelled')

                                        <span class="badge bg-danger">
                                            Cancelled
                                        </span>

                                    @else

                                        <span class="badge bg-warning text-dark">
                                            Pending
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    <div class="d-flex gap-1">

                                        <a
                                            href="{{ route('vendor.resort-bookings.show', $booking) }}"
                                            class="btn btn-sm btn-light border"
                                            title="View Booking"
                                        >
                                            <i class="bi bi-eye"></i>
                                        </a>


                                        <a
                                            href="{{ route('vendor.invoices.show', $booking) }}"
                                            class="btn btn-sm btn-primary"
                                            title="Invoice"
                                        >
                                            <i class="bi bi-receipt"></i>
                                        </a>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="9"
                                    class="text-center py-5"
                                >

                                    <i class="bi bi-bar-chart fs-1 text-muted"></i>

                                    <h6 class="fw-bold mt-3">
                                        No report data found
                                    </h6>

                                    <p class="text-muted mb-0">
                                        Try changing your filters.
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($bookings->hasPages())

            <div class="card-footer bg-white border-0 py-3">

                {{ $bookings->links() }}

            </div>

        @endif

    </div>

</div>

@endsection