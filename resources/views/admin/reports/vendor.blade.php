@extends('layouts.admin')

@section('title', 'Vendor Reports')

@section('page')

<div class="container-fluid">

    {{-- ============================================================
         PAGE HEADER
    ============================================================ --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="fas fa-chart-bar me-2"></i>
                Vendor Reports
            </h4>

            <p class="text-muted mb-0">
                Analyze vendor bookings, sales, earnings and commissions.
            </p>
        </div>

    </div>


    {{-- ============================================================
         FILTER CARD
    ============================================================ --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white py-3">

            <div class="d-flex align-items-center">

                <div class="me-2 text-primary">
                    <i class="fas fa-filter"></i>
                </div>

                <h6 class="mb-0">
                    Report Filters
                </h6>

            </div>

        </div>


        <div class="card-body">

            <form method="GET"
                  action="{{ route('admin.reports.vendor') }}">

                <div class="row g-3">

                    {{-- Vendor --}}
                    <div class="col-xl-4 col-md-6">

                        <label class="form-label fw-semibold">
                            Vendor
                        </label>

                        <select name="vendor_id"
                                class="form-select">

                            <option value="">
                                All Vendors
                            </option>

                            @foreach($vendorOptions as $vendor)

                                <option value="{{ $vendor->id }}"
                                    {{ (string) $vendorId === (string) $vendor->id ? 'selected' : '' }}>

                                    {{ $vendor->business_name }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Date From --}}
                    <div class="col-xl-3 col-md-6">

                        <label class="form-label fw-semibold">
                            Date From
                        </label>

                        <input type="date"
                               name="date_from"
                               class="form-control"
                               value="{{ $dateFrom }}">

                    </div>


                    {{-- Date To --}}
                    <div class="col-xl-3 col-md-6">

                        <label class="form-label fw-semibold">
                            Date To
                        </label>

                        <input type="date"
                               name="date_to"
                               class="form-control"
                               value="{{ $dateTo }}">

                    </div>


                    {{-- Buttons --}}
                    <div class="col-xl-2 col-md-6">

                        <label class="form-label d-block">
                            &nbsp;
                        </label>

                        <div class="d-flex gap-2">

                            <button type="submit"
                                    class="btn btn-primary flex-grow-1">

                                <i class="fas fa-search me-1"></i>
                                Filter

                            </button>


                            <a href="{{ route('admin.reports.vendor') }}"
                               class="btn btn-outline-secondary"
                               title="Reset">

                                <i class="fas fa-redo"></i>

                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- ============================================================
         SELECTED VENDOR REPORT
    ============================================================ --}}
    @if($selectedVendor && $selectedReport)

        {{-- ========================================================
             VENDOR PROFILE / HEADER
        ========================================================= --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-body">

                <div class="d-flex flex-wrap justify-content-between align-items-center">

                    <div class="d-flex align-items-center">

                        {{-- Vendor Logo --}}
                        <div class="me-3">

                            @if(!empty($selectedVendor->logo))

                                <img src="{{ asset('storage/' . $selectedVendor->logo) }}"
                                     alt="{{ $selectedVendor->business_name }}"
                                     width="65"
                                     height="65"
                                     class="rounded-circle object-fit-cover">

                            @else

                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                     style="width:65px;height:65px;">

                                    <i class="fas fa-store fa-2x text-muted"></i>

                                </div>

                            @endif

                        </div>


                        {{-- Vendor Info --}}
                        <div>

                            <h5 class="mb-1">
                                {{ $selectedVendor->business_name }}
                            </h5>


                            @if(!empty($selectedVendor->email))

                                <div class="text-muted small">

                                    <i class="fas fa-envelope me-1"></i>

                                    {{ $selectedVendor->email }}

                                </div>

                            @endif


                            @if(!empty($selectedVendor->phone))

                                <div class="text-muted small">

                                    <i class="fas fa-phone me-1"></i>

                                    {{ $selectedVendor->phone }}

                                </div>

                            @endif

                        </div>

                    </div>


                    {{-- Back --}}
                    <div class="mt-3 mt-md-0">

                        <a href="{{ route('admin.reports.vendor') }}"
                           class="btn btn-outline-secondary">

                            <i class="fas fa-arrow-left me-1"></i>

                            All Vendors

                        </a>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================
             MAIN STATISTICS
        ========================================================= --}}
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

                                <h3 class="mb-0">

                                    {{ number_format($selectedReport['total_bookings']) }}

                                </h3>

                            </div>

                            <div class="text-primary fs-3">

                                <i class="fas fa-calendar-check"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Completed --}}
            <div class="col-xl-3 col-md-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-start">

                            <div>

                                <p class="text-muted mb-1">
                                    Completed
                                </p>

                                <h3 class="mb-0 text-success">

                                    {{ number_format($selectedReport['completed_bookings']) }}

                                </h3>

                            </div>

                            <div class="text-success fs-3">

                                <i class="fas fa-check-circle"></i>

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

                                <h3 class="mb-0">

                                    ৳{{ number_format($selectedReport['total_sales'], 2) }}

                                </h3>

                            </div>

                            <div class="text-info fs-3">

                                <i class="fas fa-money-bill-wave"></i>

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
                                    Vendor Earnings
                                </p>

                                <h3 class="mb-0 text-success">

                                    ৳{{ number_format($selectedReport['vendor_earnings'], 2) }}

                                </h3>

                            </div>

                            <div class="text-success fs-3">

                                <i class="fas fa-wallet"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================
             SECONDARY STATISTICS
        ========================================================= --}}
        <div class="row g-3 mb-4">

            {{-- Pending --}}
            <div class="col-xl-3 col-md-6">

                <div class="card border-0 shadow-sm">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <small class="text-muted">
                                    Pending
                                </small>

                                <h5 class="mb-0 mt-1 text-warning">

                                    {{ number_format($selectedReport['pending_bookings']) }}

                                </h5>

                            </div>

                            <i class="fas fa-clock text-warning fs-4"></i>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Processing --}}
            <div class="col-xl-3 col-md-6">

                <div class="card border-0 shadow-sm">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <small class="text-muted">
                                    Processing
                                </small>

                                <h5 class="mb-0 mt-1 text-info">

                                    {{ number_format($selectedReport['processing_bookings']) }}

                                </h5>

                            </div>

                            <i class="fas fa-spinner text-info fs-4"></i>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Confirmed --}}
            <div class="col-xl-3 col-md-6">

                <div class="card border-0 shadow-sm">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <small class="text-muted">
                                    Confirmed
                                </small>

                                <h5 class="mb-0 mt-1 text-primary">

                                    {{ number_format($selectedReport['confirmed_bookings']) }}

                                </h5>

                            </div>

                            <i class="fas fa-check text-primary fs-4"></i>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Admin Commission --}}
            <div class="col-xl-3 col-md-6">

                <div class="card border-0 shadow-sm">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <small class="text-muted">
                                    Admin Commission
                                </small>

                                <h5 class="mb-0 mt-1 text-primary">

                                    ৳{{ number_format($selectedReport['admin_commission'], 2) }}

                                </h5>

                            </div>

                            <i class="fas fa-percentage text-primary fs-4"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================
             BOOKING DETAILS
        ========================================================= --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white py-3">

                <div class="d-flex flex-wrap justify-content-between align-items-center">

                    <div>

                        <h6 class="mb-1">
                            Booking Details
                        </h6>

                        <small class="text-muted">

                            {{ number_format($bookings->total()) }}

                            total booking(s)

                        </small>

                    </div>


                    @if($dateFrom || $dateTo)

                        <div class="small text-muted mt-2 mt-md-0">

                            <i class="fas fa-calendar-alt me-1"></i>

                            {{ $dateFrom ? \Carbon\Carbon::parse($dateFrom)->format('d M Y') : 'Beginning' }}

                            -

                            {{ $dateTo ? \Carbon\Carbon::parse($dateTo)->format('d M Y') : 'Today' }}

                        </div>

                    @endif

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
                                    Tour
                                </th>

                                <th>
                                    Amount
                                </th>

                                <th>
                                    Commission
                                </th>

                                <th>
                                    Payment
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Date
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($bookings as $booking)

                                <tr>

                                    {{-- Booking --}}
                                    <td class="ps-4">

                                        <div class="fw-semibold">

                                            {{ $booking->booking_code }}

                                        </div>

                                        <small class="text-muted">

                                            {{ $booking->person_count }}

                                            {{ $booking->person_count == 1 ? 'Person' : 'People' }}

                                        </small>

                                    </td>


                                    {{-- Customer --}}
                                    <td>

                                        @if($booking->user)

                                            <div class="fw-semibold">

                                                {{ $booking->user->name }}

                                            </div>


                                            @if(!empty($booking->user->email))

                                                <small class="text-muted">

                                                    {{ $booking->user->email }}

                                                </small>

                                            @endif

                                        @else

                                            <span class="text-muted">
                                                N/A
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Tour --}}
                                    <td>

                                        @if($booking->tour)

                                            <div class="fw-semibold">

                                                {{ $booking->tour->title }}

                                            </div>

                                        @else

                                            <span class="text-muted">
                                                N/A
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Amount --}}
                                    <td>

                                        <div class="fw-semibold">

                                            ৳{{ number_format((float) $booking->total_amount, 2) }}

                                        </div>


                                        @if((float) $booking->discount > 0)

                                            <small class="text-success">

                                                Discount:
                                                ৳{{ number_format((float) $booking->discount, 2) }}

                                            </small>

                                            <br>

                                        @endif


                                        @if(isset($booking->tax_amount) && (float) $booking->tax_amount > 0)

                                            <small class="text-muted">

                                                Tax:
                                                ৳{{ number_format((float) $booking->tax_amount, 2) }}

                                            </small>

                                        @endif

                                    </td>


                                    {{-- Commission --}}
                                    <td>

                                        @if($booking->commission)

                                            <div class="text-success small">

                                                <strong>
                                                    Vendor:
                                                </strong>

                                                ৳{{ number_format((float) ($booking->commission->vendor_earning ?? 0), 2) }}

                                            </div>


                                            <div class="text-primary small">

                                                <strong>
                                                    Admin:
                                                </strong>

                                                ৳{{ number_format((float) ($booking->commission->admin_earning ?? 0), 2) }}

                                            </div>

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Payment Status --}}
                                    <td>

                                        @switch($booking->payment_status)

                                            @case('paid')

                                                <span class="badge bg-success-subtle text-success">

                                                    <i class="fas fa-check-circle me-1"></i>
                                                    Paid

                                                </span>

                                                @break


                                            @case('pending')

                                                <span class="badge bg-warning-subtle text-warning">

                                                    <i class="fas fa-clock me-1"></i>
                                                    Pending

                                                </span>

                                                @break


                                            @case('failed')

                                                <span class="badge bg-danger-subtle text-danger">

                                                    <i class="fas fa-times-circle me-1"></i>
                                                    Failed

                                                </span>

                                                @break


                                            @case('refunded')

                                                <span class="badge bg-info-subtle text-info">

                                                    <i class="fas fa-undo me-1"></i>
                                                    Refunded

                                                </span>

                                                @break


                                            @default

                                                <span class="badge bg-secondary-subtle text-secondary">

                                                    {{ ucfirst($booking->payment_status ?? 'Unknown') }}

                                                </span>

                                        @endswitch

                                    </td>


                                    {{-- Booking Status --}}
                                    <td>

                                        @switch($booking->booking_status)

                                            @case('pending')

                                                <span class="badge bg-warning-subtle text-warning">
                                                    Pending
                                                </span>

                                                @break


                                            @case('processing')

                                                <span class="badge bg-info-subtle text-info">
                                                    Processing
                                                </span>

                                                @break


                                            @case('confirmed')

                                                <span class="badge bg-primary-subtle text-primary">
                                                    Confirmed
                                                </span>

                                                @break


                                            @case('completed')

                                                <span class="badge bg-success-subtle text-success">
                                                    Completed
                                                </span>

                                                @break


                                            @case('cancelled')

                                                <span class="badge bg-danger-subtle text-danger">
                                                    Cancelled
                                                </span>

                                                @break


                                            @default

                                                <span class="badge bg-secondary-subtle text-secondary">

                                                    {{ ucfirst($booking->booking_status ?? 'Unknown') }}

                                                </span>

                                        @endswitch

                                    </td>


                                    {{-- Date --}}
                                    <td>

                                        <div class="fw-semibold">

                                            {{ $booking->created_at?->format('d M Y') }}

                                        </div>

                                        <small class="text-muted">

                                            {{ $booking->created_at?->format('h:i A') }}

                                        </small>

                                    </td>

                                </tr>


                            @empty

                                <tr>

                                    <td colspan="8"
                                        class="text-center py-5">

                                        <div class="mb-3">

                                            <i class="fas fa-calendar-times fa-3x text-muted"></i>

                                        </div>

                                        <h6 class="mb-1">
                                            No Bookings Found
                                        </h6>

                                        <p class="text-muted mb-0">

                                            No bookings match the selected filters.

                                        </p>

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

    @else

        {{-- ========================================================
             ALL VENDORS REPORT
        ========================================================= --}}

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white py-3">

                <div class="d-flex flex-wrap justify-content-between align-items-center">

                    <div>

                        <h6 class="mb-1">
                            Vendor Performance
                        </h6>

                        <small class="text-muted">

                            {{ $reports->count() }}
                            vendor(s) found

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
                                    #
                                </th>

                                <th>
                                    Vendor
                                </th>

                                <th>
                                    Bookings
                                </th>

                                <th>
                                    Completed
                                </th>

                                <th>
                                    Cancelled
                                </th>

                                <th>
                                    Total Sales
                                </th>

                                <th>
                                    Vendor Earnings
                                </th>

                                <th>
                                    Admin Commission
                                </th>

                                <th class="text-end pe-4">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($reports as $index => $report)

                                @php
                                    $vendor = $report['vendor'];
                                @endphp


                                <tr>

                                    {{-- Number --}}
                                    <td class="ps-4">

                                        <span class="fw-semibold">

                                            {{ $index + 1 }}

                                        </span>

                                    </td>


                                    {{-- Vendor --}}
                                    <td>

                                        <div class="d-flex align-items-center">

                                            {{-- Logo --}}
                                            <div class="me-3">

                                                @if(!empty($vendor->logo))

                                                    <img src="{{ asset('storage/' . $vendor->logo) }}"
                                                         alt="{{ $vendor->business_name }}"
                                                         width="45"
                                                         height="45"
                                                         class="rounded-circle object-fit-cover">

                                                @else

                                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                                         style="width:45px;height:45px;">

                                                        <i class="fas fa-store text-muted"></i>

                                                    </div>

                                                @endif

                                            </div>


                                            {{-- Name --}}
                                            <div>

                                                <div class="fw-semibold">

                                                    {{ $vendor->business_name }}

                                                </div>


                                                @if(!empty($vendor->email))

                                                    <small class="text-muted">

                                                        {{ $vendor->email }}

                                                    </small>

                                                @endif

                                            </div>

                                        </div>

                                    </td>


                                    {{-- Total Bookings --}}
                                    <td>

                                        <span class="badge bg-primary-subtle text-primary">

                                            {{ number_format($report['total_bookings']) }}

                                        </span>

                                    </td>


                                    {{-- Completed --}}
                                    <td>

                                        <span class="badge bg-success-subtle text-success">

                                            {{ number_format($report['completed_bookings']) }}

                                        </span>

                                    </td>


                                    {{-- Cancelled --}}
                                    <td>

                                        <span class="badge bg-danger-subtle text-danger">

                                            {{ number_format($report['cancelled_bookings']) }}

                                        </span>

                                    </td>


                                    {{-- Sales --}}
                                    <td>

                                        <span class="fw-semibold">

                                            ৳{{ number_format((float) $report['total_sales'], 2) }}

                                        </span>

                                    </td>


                                    {{-- Vendor Earnings --}}
                                    <td>

                                        <span class="fw-semibold text-success">

                                            ৳{{ number_format((float) $report['vendor_earnings'], 2) }}

                                        </span>

                                    </td>


                                    {{-- Admin Commission --}}
                                    <td>

                                        <span class="fw-semibold text-primary">

                                            ৳{{ number_format((float) $report['admin_commission'], 2) }}

                                        </span>

                                    </td>


                                    {{-- Action --}}
                                    <td class="text-end pe-4">

                                        <a href="{{ route('admin.reports.vendor', [
                                            'vendor_id' => $vendor->id,
                                            'date_from' => $dateFrom,
                                            'date_to' => $dateTo,
                                        ]) }}"
                                           class="btn btn-sm btn-outline-primary">

                                            <i class="fas fa-eye me-1"></i>

                                            View Report

                                        </a>

                                    </td>

                                </tr>


                            @empty

                                <tr>

                                    <td colspan="9"
                                        class="text-center py-5">

                                        <div class="mb-3">

                                            <i class="fas fa-chart-bar fa-3x text-muted"></i>

                                        </div>

                                        <h6 class="mb-1">
                                            No Vendor Reports Found
                                        </h6>

                                        <p class="text-muted mb-0">

                                            No vendor data matches your selected filters.

                                        </p>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    @endif

</div>

@endsection