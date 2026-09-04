@extends('layouts.admin')

@section('title', 'Refunds')

@section('page')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- PAGE HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="fas fa-undo-alt me-2"></i>
                Refunds
            </h4>

            <p class="text-muted mb-0">
                View completed customer refund transactions.
            </p>
        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- STATISTICS --}}
    {{-- ========================================================= --}}

    <div class="row g-3 mb-4">

        {{-- Total Refund Amount --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <p class="text-muted mb-1">
                                Total Refunded
                            </p>

                            <h4 class="mb-0">
                                {{ number_format($totalRefundAmount, 2) }}
                            </h4>
                        </div>

                        <div class="bg-light rounded-circle p-3">
                            <i class="fas fa-money-bill-wave fa-lg"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Total Refunds --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <p class="text-muted mb-1">
                                Total Refunds
                            </p>

                            <h4 class="mb-0">
                                {{ number_format($totalRefunds) }}
                            </h4>
                        </div>

                        <div class="bg-light rounded-circle p-3">
                            <i class="fas fa-receipt fa-lg"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- This Month Refunds --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <p class="text-muted mb-1">
                                This Month
                            </p>

                            <h4 class="mb-0">
                                {{ number_format($thisMonthRefunds) }}
                            </h4>
                        </div>

                        <div class="bg-light rounded-circle p-3">
                            <i class="fas fa-calendar-alt fa-lg"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- This Month Amount --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <p class="text-muted mb-1">
                                This Month Amount
                            </p>

                            <h4 class="mb-0">
                                {{ number_format($thisMonthRefundAmount, 2) }}
                            </h4>
                        </div>

                        <div class="bg-light rounded-circle p-3">
                            <i class="fas fa-chart-line fa-lg"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- SEARCH / FILTER --}}
    {{-- ========================================================= --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.refunds.index') }}">

                <div class="row g-3 align-items-end">

                    {{-- Search --}}
                    <div class="col-lg-5">

                        <label class="form-label">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Booking code, customer, email or transaction ID"
                            value="{{ request('search') }}"
                        >

                    </div>


                    {{-- Date From --}}
                    <div class="col-lg-2">

                        <label class="form-label">
                            From Date
                        </label>

                        <input
                            type="date"
                            name="date_from"
                            class="form-control"
                            value="{{ request('date_from') }}"
                        >

                    </div>


                    {{-- Date To --}}
                    <div class="col-lg-2">

                        <label class="form-label">
                            To Date
                        </label>

                        <input
                            type="date"
                            name="date_to"
                            class="form-control"
                            value="{{ request('date_to') }}"
                        >

                    </div>


                    {{-- Search Button --}}
                    <div class="col-lg-1">

                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >
                            <i class="fas fa-search"></i>
                        </button>

                    </div>


                    {{-- Reset --}}
                    <div class="col-lg-2">

                        <a
                            href="{{ route('admin.refunds.index') }}"
                            class="btn btn-light border w-100"
                        >
                            <i class="fas fa-redo me-1"></i>
                            Reset
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- REFUND TABLE --}}
    {{-- ========================================================= --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex justify-content-between align-items-center">

                <h5 class="mb-0">
                    Refund History
                </h5>

                <span class="badge bg-success">
                    Completed Refunds
                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="px-3">
                                Refund
                            </th>

                            <th>
                                Booking
                            </th>

                            <th>
                                Customer
                            </th>

                            <th>
                                Payment
                            </th>

                            <th>
                                Amount
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Processed At
                            </th>

                            <th class="text-end px-3">
                                Action
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($refunds as $refund)

                            <tr>

                                {{-- Refund --}}
                                <td class="px-3">

                                    <strong>
                                        #REF-{{ str_pad($refund->id, 6, '0', STR_PAD_LEFT) }}
                                    </strong>

                                </td>


                                {{-- Booking --}}
                                <td>

                                    @if($refund->booking)

                                        <a
                                            href="{{ route('admin.bookings.show', $refund->booking->id) }}"
                                            class="text-decoration-none fw-semibold"
                                        >
                                            {{ $refund->booking->booking_code }}
                                        </a>

                                        @if($refund->booking->tour)

                                            <div class="small text-muted">

                                                {{ \Illuminate\Support\Str::limit(
                                                    $refund->booking->tour->title,
                                                    35
                                                ) }}

                                            </div>

                                        @endif

                                    @else

                                        <span class="text-muted">
                                            N/A
                                        </span>

                                    @endif

                                </td>


                                {{-- Customer --}}
                                <td>

                                    @if($refund->user)

                                        <div class="fw-semibold">
                                            {{ $refund->user->name }}
                                        </div>

                                        <div class="small text-muted">
                                            {{ $refund->user->email }}
                                        </div>

                                    @else

                                        <span class="text-muted">
                                            N/A
                                        </span>

                                    @endif

                                </td>


                                {{-- Payment --}}
                                <td>

                                    @if($refund->payment)

                                        <div class="fw-semibold">
                                            {{ $refund->payment->trx_id }}
                                        </div>

                                        <div class="small text-muted">

                                            {{ $refund->payment->payment_method
                                                ?? 'N/A'
                                            }}

                                        </div>

                                    @else

                                        <span class="text-muted">
                                            N/A
                                        </span>

                                    @endif

                                </td>


                                {{-- Amount --}}
                                <td>

                                    <strong>
                                        {{ number_format(
                                            $refund->refund_amount,
                                            2
                                        ) }}
                                    </strong>

                                </td>


                                {{-- Status --}}
                                <td>

                                    <span class="badge bg-success">

                                        <i class="fas fa-check-circle me-1"></i>

                                        Completed

                                    </span>

                                </td>


                                {{-- Processed --}}
                                <td>

                                    @if($refund->processed_at)

                                        <div>
                                            {{ $refund->processed_at->format('d M Y') }}
                                        </div>

                                        <div class="small text-muted">
                                            {{ $refund->processed_at->format('h:i A') }}
                                        </div>

                                    @else

                                        <span class="text-muted">
                                            N/A
                                        </span>

                                    @endif

                                </td>


                                {{-- Action --}}
                                <td class="text-end px-3">

                                    <a
                                        href="{{ route(
                                            'admin.refunds.show',
                                            $refund->id
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                    >

                                        <i class="fas fa-eye me-1"></i>

                                        View

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="8"
                                    class="text-center py-5"
                                >

                                    <div class="mb-3">

                                        <i
                                            class="fas fa-undo-alt fa-3x text-muted"
                                        ></i>

                                    </div>

                                    <h6 class="mb-1">
                                        No completed refunds found
                                    </h6>

                                    <p class="text-muted mb-0">
                                        Completed refunds will appear here.
                                    </p>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Pagination --}}
        @if($refunds->hasPages())

            <div class="card-footer bg-white border-0">

                {{ $refunds->links() }}

            </div>

        @endif

    </div>

</div>

@endsection