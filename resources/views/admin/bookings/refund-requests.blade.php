@extends('layouts.admin')

@section('title', 'Refund Requests')

@section('page')

<div class="container-fluid px-0">

    {{-- =====================================================
         PAGE HEADER
    ====================================================== --}}

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>

            <h4 class="fw-bold mb-1">
                Refund Requests
            </h4>

            <p class="text-muted mb-0">
                Review and manage customer refund requests.
            </p>

        </div>

        <div class="mt-2 mt-md-0">

            <span class="badge bg-primary px-3 py-2">
                Total: {{ $refundRequests->total() }}
            </span>

        </div>

    </div>


    {{-- =====================================================
         FILTER CARD
    ====================================================== --}}

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-transparent py-3">

            <div class="d-flex align-items-center">

                <i class="fas fa-filter me-2"></i>

                <strong>
                    Filter Refund Requests
                </strong>

            </div>

        </div>


        <div class="card-body">

            <form
                method="GET"
                action="{{ route('admin.bookings.refund-requests') }}"
            >

                <div class="row g-3">

                    {{-- Search --}}

                    <div class="col-lg-5 col-md-6">

                        <label class="form-label">
                            Search
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>

                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                placeholder="Booking code, customer name, email..."
                                value="{{ request('search') }}"
                            >

                        </div>

                    </div>


                    {{-- Status --}}

                    <div class="col-lg-3 col-md-6">

                        <label class="form-label">
                            Refund Status
                        </label>

                        <select
                            name="status"
                            class="form-select"
                        >

                            <option value="">
                                All Status
                            </option>

                            <option
                                value="pending"
                                {{ request('status') === 'pending' ? 'selected' : '' }}
                            >
                                Pending
                            </option>

                            <option
                                value="approved"
                                {{ request('status') === 'approved' ? 'selected' : '' }}
                            >
                                Approved
                            </option>

                            <option
                                value="rejected"
                                {{ request('status') === 'rejected' ? 'selected' : '' }}
                            >
                                Rejected
                            </option>

                            <option
                                value="completed"
                                {{ request('status') === 'completed' ? 'selected' : '' }}
                            >
                                Completed
                            </option>

                        </select>

                    </div>


                    {{-- Buttons --}}

                    <div class="col-lg-4 col-md-12 d-flex align-items-end gap-2">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >

                            <i class="fas fa-search me-1"></i>

                            Filter

                        </button>


                        <a
                            href="{{ route('admin.bookings.refund-requests') }}"
                            class="btn btn-light border"
                        >

                            <i class="fas fa-redo me-1"></i>

                            Reset

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =====================================================
         REFUND REQUEST TABLE
    ====================================================== --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-transparent py-3">

            <div class="d-flex justify-content-between align-items-center">

                <strong>
                    Refund Request List
                </strong>

                <span class="text-muted small">

                    Showing
                    {{ $refundRequests->firstItem() ?? 0 }}

                    -

                    {{ $refundRequests->lastItem() ?? 0 }}

                    of

                    {{ $refundRequests->total() }}

                </span>

            </div>

        </div>


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
                                Amount
                            </th>

                            <th>
                                Payment
                            </th>

                            <th>
                                Reason
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Requested
                            </th>

                            <th class="text-end px-3">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse($refundRequests as $refund)

                        <tr>

                            {{-- Number --}}

                            <td class="px-3">

                                {{ $refundRequests->firstItem() + $loop->index }}

                            </td>


                            {{-- Booking --}}

                            <td>

                                @if($refund->booking)

                                    <div class="fw-bold">

                                        {{ $refund->booking->booking_code }}

                                    </div>

                                    @if($refund->booking->tour)

                                        <small class="text-muted">

                                            {{ $refund->booking->tour->title }}

                                        </small>

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

                                    @if($refund->user->email)

                                        <small class="text-muted">

                                            {{ $refund->user->email }}

                                        </small>

                                    @endif

                                @else

                                    <span class="text-muted">
                                        Guest
                                    </span>

                                @endif

                            </td>


                            {{-- Amount --}}

                            <td>

                                <div class="fw-bold">

                                    {{ number_format((float) $refund->refund_amount, 2) }}

                                </div>

                                @if($refund->booking)

                                    <small class="text-muted">

                                        Booking:
                                        {{ number_format((float) $refund->booking->total_amount, 2) }}

                                    </small>

                                @endif

                            </td>


                            {{-- Payment --}}

                            <td>

                                @if($refund->payment)

                                    <div class="fw-semibold">

                                        {{ $refund->payment->payment_method ?? 'N/A' }}

                                    </div>

                                    <small class="text-muted">

                                        {{ $refund->payment->trx_id }}

                                    </small>

                                @else

                                    <span class="text-muted">
                                        N/A
                                    </span>

                                @endif

                            </td>


                            {{-- Reason --}}

                            <td style="min-width: 180px; max-width: 260px;">

                                @if($refund->reason)

                                    <span
                                        title="{{ $refund->reason }}"
                                        class="d-inline-block text-truncate"
                                        style="max-width: 240px;"
                                    >

                                        {{ $refund->reason }}

                                    </span>

                                @else

                                    <span class="text-muted">
                                        No reason provided
                                    </span>

                                @endif

                            </td>


                            {{-- Status --}}

                            <td>

                                @php

                                    $statusClass = match($refund->status) {

                                        'pending' =>
                                            'bg-warning text-dark',

                                        'approved' =>
                                            'bg-info text-dark',

                                        'rejected' =>
                                            'bg-danger',

                                        'completed' =>
                                            'bg-success',

                                        default =>
                                            'bg-secondary',

                                    };

                                @endphp


                                <span class="badge {{ $statusClass }}">

                                    {{ ucfirst($refund->status) }}

                                </span>

                            </td>


                            {{-- Requested Date --}}

                            <td>

                                @if($refund->requested_at)

                                    <div>

                                        {{ $refund->requested_at->format('d M Y') }}

                                    </div>

                                    <small class="text-muted">

                                        {{ $refund->requested_at->format('h:i A') }}

                                    </small>

                                @else

                                    <div>

                                        {{ $refund->created_at->format('d M Y') }}

                                    </div>

                                    <small class="text-muted">

                                        {{ $refund->created_at->format('h:i A') }}

                                    </small>

                                @endif

                            </td>


                            {{-- Action --}}

                            <td class="text-end px-3">

                                <div class="dropdown">

                                    <button
                                        class="btn btn-sm btn-light border"
                                        type="button"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false"
                                    >

                                        <i class="fas fa-ellipsis-v"></i>

                                    </button>


                                    <ul class="dropdown-menu dropdown-menu-end">

                                        {{-- View --}}

                                        <li>

                                            <a
                                                class="dropdown-item"
                                                href="#"
                                            >

                                                <i class="fas fa-eye me-2"></i>

                                                View Request

                                            </a>

                                        </li>


                                        {{-- Approve --}}

                                        @if($refund->status === 'pending')

                                            <li>

                                                <button
                                                    type="button"
                                                    class="dropdown-item text-success"
                                                >

                                                    <i class="fas fa-check me-2"></i>

                                                    Approve Refund

                                                </button>

                                            </li>


                                            {{-- Reject --}}

                                            <li>

                                                <button
                                                    type="button"
                                                    class="dropdown-item text-danger"
                                                >

                                                    <i class="fas fa-times me-2"></i>

                                                    Reject Refund

                                                </button>

                                            </li>

                                        @endif


                                        {{-- Complete --}}

                                        @if($refund->status === 'approved')

                                            <li>

                                                <button
                                                    type="button"
                                                    class="dropdown-item text-primary"
                                                >

                                                    <i class="fas fa-money-bill-wave me-2"></i>

                                                    Complete Refund

                                                </button>

                                            </li>

                                        @endif

                                    </ul>

                                </div>

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
                                        class="fas fa-receipt fa-3x text-muted"
                                    ></i>

                                </div>

                                <h6 class="fw-bold">

                                    No refund requests found

                                </h6>

                                <p class="text-muted mb-0">

                                    There are no refund requests matching your filters.

                                </p>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Pagination --}}

        @if($refundRequests->hasPages())

            <div class="card-footer bg-transparent">

                {{ $refundRequests->links() }}

            </div>

        @endif

    </div>

</div>

@endsection