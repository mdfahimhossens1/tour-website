@extends('layouts.vendor')

@section('title', 'Commissions')

@section('page')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1 fw-bold">
                Commissions
            </h4>

            <p class="text-muted mb-0">
                View your booking sales, commission rate and earnings.
            </p>
        </div>

    </div>


    {{-- =========================================================
        STATISTICS
    ========================================================== --}}
    <div class="row g-3 mb-4">

        {{-- Total Bookings --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>
                            <p class="text-muted mb-1">
                                Total Bookings
                            </p>

                            <h3 class="fw-bold mb-0">
                                {{ number_format($stats['total_bookings']) }}
                            </h3>
                        </div>

                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3">
                            <i class="bi bi-calendar-check fs-5"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Total Sales --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>
                            <p class="text-muted mb-1">
                                Total Sales
                            </p>

                            <h3 class="fw-bold mb-0">
                                ৳{{ number_format($stats['total_sales'], 2) }}
                            </h3>
                        </div>

                        <div class="bg-success bg-opacity-10 text-success rounded-circle p-3">
                            <i class="bi bi-graph-up-arrow fs-5"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Vendor Earnings --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>
                            <p class="text-muted mb-1">
                                Your Earnings
                            </p>

                            <h3 class="fw-bold mb-0">
                                ৳{{ number_format($stats['vendor_earning'], 2) }}
                            </h3>
                        </div>

                        <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-3">
                            <i class="bi bi-wallet2 fs-5"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Commission Rate --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>
                            <p class="text-muted mb-1">
                                Commission Rate
                            </p>

                            <h3 class="fw-bold mb-0">
                                {{ number_format($stats['commission_rate'], 2) }}%
                            </h3>
                        </div>

                        <div class="bg-info bg-opacity-10 text-info rounded-circle p-3">
                            <i class="bi bi-percent fs-5"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        COMMISSION TABLE
    ========================================================== --}}
    <div class="card border-0 shadow-sm">

        {{-- Card Header --}}
        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                <div>
                    <h5 class="mb-1 fw-bold">
                        Commission History
                    </h5>

                    <small class="text-muted">
                        Your booking commission records
                    </small>
                </div>


                {{-- Search --}}
                <form
                    action="{{ route('vendor.commissions.index') }}"
                    method="GET"
                    class="d-flex"
                >

                    <div class="input-group">

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Search booking, customer or tour..."
                            value="{{ request('search') }}"
                        >

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >
                            <i class="bi bi-search"></i>
                            Search
                        </button>

                    </div>

                </form>

            </div>

        </div>


        {{-- Table --}}
        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="px-3">
                                #
                            </th>

                            <th>
                                Booking
                            </th>

                            <th>
                                Customer
                            </th>

                            <th>
                                Tour
                            </th>

                            <th>
                                Tour Date
                            </th>

                            <th>
                                Total Amount
                            </th>

                            <th>
                                Commission
                            </th>

                            <th>
                                Your Earning
                            </th>

                            <th class="text-end px-3">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($commissions as $commission)

                            @php
                                $booking = $commission->booking;
                            @endphp

                            <tr>

                                {{-- Serial --}}
                                <td class="px-3">

                                    {{ $commissions->firstItem() + $loop->index }}

                                </td>


                                {{-- Booking --}}
                                <td>

                                    @if($booking)

                                        <a
                                            href="{{ route('vendor.commissions.show', $commission->id) }}"
                                            class="fw-semibold text-decoration-none"
                                        >
                                            {{ $booking->booking_code ?? 'N/A' }}
                                        </a>

                                    @else

                                        <span class="text-muted">
                                            N/A
                                        </span>

                                    @endif

                                </td>


                                {{-- Customer --}}
                                <td>

                                    @if($booking?->user)

                                        <div class="d-flex align-items-center">

                                            <div
                                                class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-2"
                                                style="width: 36px; height: 36px;"
                                            >
                                                <i class="bi bi-person"></i>
                                            </div>

                                            <div>

                                                <div class="fw-semibold">
                                                    {{ $booking->user->name }}
                                                </div>

                                                <small class="text-muted">
                                                    {{ $booking->user->email }}
                                                </small>

                                            </div>

                                        </div>

                                    @else

                                        <span class="text-muted">
                                            N/A
                                        </span>

                                    @endif

                                </td>


                                {{-- Tour --}}
                                <td>

                                    @if($booking?->tour)

                                        <span class="fw-semibold">
                                            {{ $booking->tour->title }}
                                        </span>

                                    @else

                                        <span class="text-muted">
                                            N/A
                                        </span>

                                    @endif

                                </td>


                                {{-- Tour Date --}}
                                <td>

                                    @if($booking?->tourDate)

                                        {{ optional($booking->tourDate->date)->format('d M, Y') ?? 'N/A' }}

                                    @else

                                        <span class="text-muted">
                                            N/A
                                        </span>

                                    @endif

                                </td>


                                {{-- Total Amount --}}
                                <td>

                                    <span class="fw-semibold">
                                        ৳{{ number_format($commission->total_amount ?? 0, 2) }}
                                    </span>

                                </td>


                                {{-- Commission --}}
                                <td>

                                    <span class="badge bg-danger bg-opacity-10 text-danger">

                                        {{ number_format($commission->commission_rate ?? $stats['commission_rate'], 2) }}%

                                    </span>

                                </td>


                                {{-- Vendor Earning --}}
                                <td>

                                    <span class="fw-bold text-success">

                                        ৳{{ number_format($commission->vendor_earning ?? 0, 2) }}

                                    </span>

                                </td>


                                {{-- Action --}}
                                <td class="text-end px-3">

                                    <a
                                        href="{{ route('vendor.commissions.show', $commission->id) }}"
                                        class="btn btn-sm btn-outline-primary"
                                        title="View Details"
                                    >

                                        <i class="bi bi-eye"></i>

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="9"
                                    class="text-center py-5"
                                >

                                    <div class="mb-3">

                                        <i
                                            class="bi bi-receipt text-muted"
                                            style="font-size: 3rem;"
                                        ></i>

                                    </div>

                                    <h6 class="fw-bold">
                                        No Commission Records Found
                                    </h6>

                                    <p class="text-muted mb-0">

                                        @if(request('search'))

                                            No commission records matched
                                            your search.

                                        @else

                                            You don't have any commission
                                            records yet.

                                        @endif

                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Pagination --}}
        @if($commissions->hasPages())

            <div class="card-footer bg-white border-0 py-3">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                    <div class="text-muted small">

                        Showing
                        <strong>{{ $commissions->firstItem() }}</strong>
                        to
                        <strong>{{ $commissions->lastItem() }}</strong>
                        of
                        <strong>{{ $commissions->total() }}</strong>
                        results

                    </div>

                    <div>

                        {{ $commissions->links() }}

                    </div>

                </div>

            </div>

        @endif

    </div>

</div>

@endsection
