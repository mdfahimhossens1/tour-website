@extends('layouts.admin')

@section('title', 'All Bookings')

@section('page')

<style>
    .booking-page {
        padding: 10px 0 30px;
    }

    .booking-header {
        background: linear-gradient(135deg, #ffffff 0%, #f8faff 100%);
        border: 1px solid #e9edf5;
        border-radius: 14px;
        padding: 22px 24px;
        margin-bottom: 20px;
    }

    .booking-header h4 {
        font-size: 21px;
        color: #1f2937;
    }

    .booking-header p {
        font-size: 13px;
    }

    .total-badge {
        background: #eef4ff;
        color: #0d6efd;
        border: 1px solid #d9e6ff;
        border-radius: 8px;
        font-weight: 600;
    }

    .filter-card,
    .booking-card {
        border: 1px solid #e9edf5 !important;
        border-radius: 14px !important;
        overflow: hidden;
        background: #fff;
    }

    .filter-card .card-header,
    .booking-card .card-header {
        background: #fff;
        border-bottom: 1px solid #edf0f5;
        padding: 16px 20px;
    }

    .filter-title {
        font-size: 14px;
        font-weight: 700;
        color: #374151;
    }

    .filter-title i {
        color: #0d6efd;
    }

    .form-label {
        font-size: 12px;
        font-weight: 600;
        color: #6b7280;
        margin-bottom: 6px;
    }

    .form-control,
    .form-select {
        min-height: 40px;
        border-radius: 8px;
        border: 1px solid #dfe4ec;
        font-size: 13px;
        box-shadow: none !important;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #86b7fe;
    }

    .input-group-text {
        background: #f8f9fb;
        border-color: #dfe4ec;
        color: #6b7280;
    }

    .btn-filter {
        min-height: 40px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
    }

    .reset-btn {
        width: 42px;
        min-height: 40px;
        border-radius: 8px;
    }

    .booking-card .card-header strong {
        font-size: 14px;
        color: #374151;
    }

    .booking-table {
        margin-bottom: 0;
        white-space: nowrap;
    }

    .booking-table thead th {
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
        color: #6b7280;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .3px;
        padding: 13px 12px;
    }

    .booking-table tbody td {
        padding: 14px 12px;
        border-bottom: 1px solid #f0f2f5;
        font-size: 13px;
        color: #374151;
        vertical-align: middle;
    }

    .booking-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .booking-table tbody tr:hover {
        background: #fafcff;
    }

    .booking-code {
        color: #0d6efd;
        font-weight: 700;
        font-size: 13px;
    }

    .customer-name,
    .tour-name {
        font-weight: 600;
        color: #374151;
    }

    .customer-email {
        display: block;
        color: #9ca3af;
        font-size: 11px;
        margin-top: 2px;
    }

    .vendor-name {
        display: block;
        color: #9ca3af;
        font-size: 11px;
        margin-top: 4px;
    }

    .vendor-name i {
        color: #6b7280;
    }

    .person-badge {
        background: #f3f4f6;
        border: 1px solid #e5e7eb;
        color: #374151;
        border-radius: 6px;
        padding: 5px 8px;
        font-size: 11px;
        font-weight: 600;
    }

    .amount-main {
        font-weight: 700;
        color: #111827;
    }

    .discount-text {
        color: #198754;
        font-size: 10px;
        margin-top: 3px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 9px;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 700;
        text-transform: capitalize;
    }

    .status-badge i {
        font-size: 7px;
    }

    .payment-paid {
        background: #eaf8f0;
        color: #198754;
    }

    .payment-failed {
        background: #fdecec;
        color: #dc3545;
    }

    .payment-refunded {
        background: #fff5d9;
        color: #996c00;
    }

    .payment-pending {
        background: #f1f3f5;
        color: #6c757d;
    }

    .booking-confirmed {
        background: #eaf8f0;
        color: #198754;
    }

    .booking-completed {
        background: #eaf1ff;
        color: #0d6efd;
    }

    .booking-cancelled {
        background: #fdecec;
        color: #dc3545;
    }

    .booking-processing {
        background: #fff5d9;
        color: #996c00;
    }

    .booking-pending {
        background: #fff5d9;
        color: #996c00;
    }

    .date-main {
        color: #374151;
        font-size: 12px;
        font-weight: 600;
    }

    .date-time {
        display: block;
        color: #9ca3af;
        font-size: 10px;
        margin-top: 2px;
    }

    .action-btn {
        width: 34px;
        height: 34px;
        border-radius: 7px;
        border: 1px solid #e1e5eb;
        background: #fff;
        color: #6b7280;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .action-btn:hover {
        background: #f8f9fa;
        color: #0d6efd;
    }

    .dropdown-menu {
        border: 1px solid #e5e7eb;
        border-radius: 9px;
        box-shadow: 0 8px 25px rgba(0,0,0,.08);
        padding: 6px;
    }

    .dropdown-item {
        border-radius: 6px;
        font-size: 12px;
        padding: 8px 10px;
    }

    .dropdown-item:hover {
        background: #f5f7fa;
    }

    .empty-state {
        padding: 60px 20px !important;
    }

    .empty-state i {
        color: #cbd5e1;
    }

    .empty-state h6 {
        margin-top: 15px;
        color: #374151;
    }

    .empty-state p {
        font-size: 12px;
    }

    .pagination {
        margin-bottom: 0;
    }

    .card-footer {
        border-top: 1px solid #edf0f5;
        padding: 14px 20px;
    }

    @media (max-width: 768px) {

        .booking-header {
            padding: 18px;
        }

        .booking-header h4 {
            font-size: 18px;
        }

        .filter-card .card-body {
            padding: 15px;
        }

        .booking-table thead th,
        .booking-table tbody td {
            padding: 11px 10px;
        }
    }
</style>


<div class="container-fluid px-0 booking-page">

    {{-- =====================================================
        PAGE HEADER
    ====================================================== --}}

    <div class="booking-header">

        <div class="d-flex flex-wrap justify-content-between align-items-center">

            <div>

                <div class="d-flex align-items-center gap-2 mb-1">

                    <h4 class="fw-bold mb-0">
                        All Bookings
                    </h4>

                </div>

                <p class="text-muted mb-0">
                    Manage and monitor all tour bookings from one place.
                </p>

            </div>

            <div class="mt-3 mt-md-0">

                <span class="badge total-badge px-3 py-2">

                    <i class="fas fa-calendar-check me-1"></i>

                    Total:
                    {{ $bookings->total() }}

                </span>

            </div>

        </div>

    </div>


    {{-- =====================================================
        FILTER CARD
    ====================================================== --}}

    <div class="card filter-card shadow-sm mb-4">

        <div class="card-header">

            <div class="filter-title">

                <i class="fas fa-sliders-h me-2"></i>

                Filter Bookings

            </div>

        </div>


        <div class="card-body p-4">

            <form method="GET"
                  action="{{ route('admin.bookings.index') }}">

                <div class="row g-3">

                    {{-- Search --}}

                    <div class="col-xl-4 col-lg-4 col-md-6">

                        <label class="form-label">
                            Search Booking
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>

                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                placeholder="Booking code, customer, tour..."
                                value="{{ request('search') }}"
                            >

                        </div>

                    </div>


                    {{-- Booking Status --}}

                    <div class="col-xl-2 col-lg-2 col-md-6">

                        <label class="form-label">
                            Booking Status
                        </label>

                        <select
                            name="booking_status"
                            class="form-select"
                        >

                            <option value="">
                                All Status
                            </option>

                            <option value="pending"
                                {{ request('booking_status') === 'pending' ? 'selected' : '' }}>
                                Pending
                            </option>

                            <option value="processing"
                                {{ request('booking_status') === 'processing' ? 'selected' : '' }}>
                                Processing
                            </option>

                            <option value="confirmed"
                                {{ request('booking_status') === 'confirmed' ? 'selected' : '' }}>
                                Confirmed
                            </option>

                            <option value="completed"
                                {{ request('booking_status') === 'completed' ? 'selected' : '' }}>
                                Completed
                            </option>

                            <option value="cancelled"
                                {{ request('booking_status') === 'cancelled' ? 'selected' : '' }}>
                                Cancelled
                            </option>

                        </select>

                    </div>


                    {{-- Payment Status --}}

                    <div class="col-xl-2 col-lg-2 col-md-6">

                        <label class="form-label">
                            Payment Status
                        </label>

                        <select
                            name="payment_status"
                            class="form-select"
                        >

                            <option value="">
                                All Payments
                            </option>

                            <option value="pending"
                                {{ request('payment_status') === 'pending' ? 'selected' : '' }}>
                                Pending
                            </option>

                            <option value="paid"
                                {{ request('payment_status') === 'paid' ? 'selected' : '' }}>
                                Paid
                            </option>

                            <option value="failed"
                                {{ request('payment_status') === 'failed' ? 'selected' : '' }}>
                                Failed
                            </option>

                            <option value="refunded"
                                {{ request('payment_status') === 'refunded' ? 'selected' : '' }}>
                                Refunded
                            </option>

                        </select>

                    </div>


                    {{-- Vendor --}}

                    <div class="col-xl-2 col-lg-2 col-md-6">

                        <label class="form-label">
                            Vendor
                        </label>

                        <select
                            name="vendor_id"
                            class="form-select"
                        >

                            <option value="">
                                All Vendors
                            </option>

                            @foreach($vendors as $vendor)

                                <option
                                    value="{{ $vendor->id }}"
                                    {{ (string) request('vendor_id') === (string) $vendor->id ? 'selected' : '' }}
                                >
                                    {{ $vendor->business_name ?? $vendor->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Buttons --}}

                    <div class="col-xl-2 col-lg-2 col-md-12">

                        <label class="form-label d-none d-md-block">
                            &nbsp;
                        </label>

                        <div class="d-flex gap-2">

                            <button
                                type="submit"
                                class="btn btn-primary btn-filter flex-grow-1"
                            >

                                <i class="fas fa-search me-1"></i>

                                Filter

                            </button>

                            <a
                                href="{{ route('admin.bookings.index') }}"
                                class="btn btn-light border reset-btn"
                                title="Reset Filter"
                            >

                                <i class="fas fa-redo"></i>

                            </a>

                        </div>

                    </div>


                    {{-- Date From --}}

                    <div class="col-xl-3 col-lg-3 col-md-6">

                        <label class="form-label">
                            Booking From
                        </label>

                        <input
                            type="date"
                            name="date_from"
                            class="form-control"
                            value="{{ request('date_from') }}"
                        >

                    </div>


                    {{-- Date To --}}

                    <div class="col-xl-3 col-lg-3 col-md-6">

                        <label class="form-label">
                            Booking To
                        </label>

                        <input
                            type="date"
                            name="date_to"
                            class="form-control"
                            value="{{ request('date_to') }}"
                        >

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =====================================================
        BOOKINGS TABLE
    ====================================================== --}}

    <div class="card booking-card shadow-sm">

        <div class="card-header">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">

                <div class="d-flex align-items-center gap-2">

                    <i class="fas fa-list text-primary"></i>

                    <strong>
                        Booking List
                    </strong>

                </div>

                <span class="text-muted small">

                    Showing
                    {{ $bookings->firstItem() ?? 0 }}

                    -

                    {{ $bookings->lastItem() ?? 0 }}

                    of

                    {{ $bookings->total() }}

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table booking-table table-hover align-middle">

                    <thead>

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
                                Persons
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

                            <th>
                                Date
                            </th>

                            <th class="text-end px-3">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    @forelse($bookings as $booking)

                        <tr>

                            {{-- Number --}}

                            <td class="px-3">

                                <span class="text-muted">
                                    {{ $bookings->firstItem() + $loop->index }}
                                </span>

                            </td>


                            {{-- Booking --}}

                            <td>

                                <div class="booking-code">

                                    {{ $booking->booking_code }}

                                </div>


                                @if($booking->vendor)

                                    <span class="vendor-name">

                                        <i class="fas fa-store me-1"></i>

                                        {{ $booking->vendor->business_name ?? $booking->vendor->name }}

                                    </span>

                                @endif

                            </td>


                            {{-- Customer --}}

                            <td>

                                @if($booking->user)

                                    <div class="customer-name">

                                        {{ $booking->user->name }}

                                    </div>

                                    @if($booking->user->email)

                                        <span class="customer-email">

                                            {{ $booking->user->email }}

                                        </span>

                                    @endif

                                @else

                                    <span class="text-muted">
                                        Guest
                                    </span>

                                @endif

                            </td>


                            {{-- Tour --}}

                            <td>

                                @if($booking->tour)

                                    <div class="tour-name">

                                        {{ $booking->tour->title }}

                                    </div>

                                @else

                                    <span class="text-muted">
                                        N/A
                                    </span>

                                @endif

                            </td>


                            {{-- Tour Date --}}

                            <td>

                                @if($booking->tourDate)

                                    <div class="date-main">

                                        {{ \Carbon\Carbon::parse($booking->tourDate->date)->format('d M Y') }}

                                    </div>

                                @else

                                    <span class="text-muted">
                                        N/A
                                    </span>

                                @endif

                            </td>


                            {{-- Persons --}}

                            <td>

                                <span class="person-badge">

                                    <i class="fas fa-users me-1"></i>

                                    {{ $booking->person_count }}

                                </span>

                            </td>


                            {{-- Amount --}}

                            <td>

                                <div class="amount-main">

                                    {{ number_format((float) $booking->total_amount, 2) }}

                                </div>


                                @if((float) $booking->discount > 0)

                                    <div class="discount-text">

                                        Discount:
                                        {{ number_format((float) $booking->discount, 2) }}

                                    </div>

                                @endif

                            </td>


                            {{-- Payment --}}

                            <td>

                                @php

                                    $paymentClass = match($booking->payment_status) {

                                        'paid' => 'payment-paid',

                                        'failed' => 'payment-failed',

                                        'refunded' => 'payment-refunded',

                                        default => 'payment-pending',

                                    };

                                @endphp


                                <span class="status-badge {{ $paymentClass }}">

                                    <i class="fas fa-circle"></i>

                                    {{ ucfirst($booking->payment_status) }}

                                </span>

                            </td>


                            {{-- Booking Status --}}

                            <td>

                                @php

                                    $statusClass = match($booking->booking_status) {

                                        'confirmed' => 'booking-confirmed',

                                        'completed' => 'booking-completed',

                                        'cancelled' => 'booking-cancelled',

                                        'processing' => 'booking-processing',

                                        default => 'booking-pending',

                                    };

                                @endphp


                                <span class="status-badge {{ $statusClass }}">

                                    <i class="fas fa-circle"></i>

                                    {{ ucfirst($booking->booking_status) }}

                                </span>

                            </td>


                            {{-- Created Date --}}

                            <td>

                                <div class="date-main">

                                    {{ $booking->created_at->format('d M Y') }}

                                </div>

                                <span class="date-time">

                                    {{ $booking->created_at->format('h:i A') }}

                                </span>

                            </td>


                            {{-- Action --}}

                            <td class="text-end px-3">

                                <div class="dropdown">

                                    <button
                                        class="action-btn"
                                        type="button"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false"
                                        title="Actions"
                                    >

                                        <i class="fas fa-ellipsis-v"></i>

                                    </button>


                                    <ul class="dropdown-menu dropdown-menu-end">

                                        {{-- View --}}

                                        <li>

                                            <a
                                                class="dropdown-item"
                                                href="{{ route('admin.bookings.show', $booking->id) }}"
                                            >

                                                <i class="fas fa-eye me-2 text-primary"></i>

                                                View Booking

                                            </a>

                                        </li>


                                        {{-- Confirm --}}

                                        @if($booking->booking_status === 'pending')

                                            <li>

                                                <form
                                                    method="POST"
                                                    action="{{ route('admin.bookings.confirm', $booking->id) }}"
                                                >

                                                    @csrf

                                                    <button
                                                        type="submit"
                                                        class="dropdown-item text-success"
                                                        onclick="return confirm('Are you sure you want to confirm this booking?')"
                                                    >

                                                        <i class="fas fa-check me-2"></i>

                                                        Confirm Booking

                                                    </button>

                                                </form>

                                            </li>

                                        @endif


                                        {{-- Move To Processing --}}

                                        @if($booking->booking_status === 'pending')

                                            <li>

                                                <form
                                                    method="POST"
                                                    action="{{ route('admin.bookings.processing.move', $booking->id) }}"
                                                >

                                                    @csrf

                                                    <button
                                                        type="submit"
                                                        class="dropdown-item text-warning"
                                                        onclick="return confirm('Move this booking to processing?')"
                                                    >

                                                        <i class="fas fa-spinner me-2"></i>

                                                        Move To Processing

                                                    </button>

                                                </form>

                                            </li>

                                        @endif


                                        {{-- Cancel --}}

                                        @if(
                                            in_array(
                                                $booking->booking_status,
                                                ['pending', 'processing', 'confirmed']
                                            )
                                        )

                                            <li>

                                                <form
                                                    method="POST"
                                                    action="{{ route('admin.bookings.cancel', $booking->id) }}"
                                                >

                                                    @csrf

                                                    <button
                                                        type="submit"
                                                        class="dropdown-item text-danger"
                                                        onclick="return confirm('Are you sure you want to cancel this booking?')"
                                                    >

                                                        <i class="fas fa-times me-2"></i>

                                                        Cancel Booking

                                                    </button>

                                                </form>

                                            </li>

                                        @endif

                                    </ul>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="11"
                                class="text-center empty-state"
                            >

                                <div>

                                    <i class="fas fa-calendar-times fa-3x"></i>

                                    <h6 class="fw-bold">
                                        No bookings found
                                    </h6>

                                    <p class="text-muted mb-0">
                                        There are no bookings matching your current filters.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- Pagination --}}

        @if($bookings->hasPages())

            <div class="card-footer bg-white">

                {{ $bookings->links() }}

            </div>

        @endif

    </div>

</div>

@endsection