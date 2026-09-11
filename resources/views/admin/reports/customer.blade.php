@extends('layouts.admin')
@section('title', 'Customer Reports')
@section('page')

<div class="container-fluid">

    {{-- ============================================================
         PAGE HEADER
    ============================================================ --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="fas fa-users me-2"></i>
                Customer Reports
            </h4>

            <p class="text-muted mb-0">
                Analyze customer bookings, spending and booking activity.
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
                  action="{{ route('admin.reports.customer') }}">

                <div class="row g-3">

                    {{-- Customer --}}
                    <div class="col-xl-4 col-md-6">

                        <label class="form-label fw-semibold">
                            Customer
                        </label>

                        <select name="customer_id"
                                class="form-select">

                            <option value="">
                                All Customers
                            </option>

                            @foreach($customerOptions as $customer)

                                <option value="{{ $customer->id }}"
                                    {{ (string) $customerId === (string) $customer->id ? 'selected' : '' }}>

                                    {{ $customer->name }}

                                    @if(!empty($customer->email))
                                        — {{ $customer->email }}
                                    @endif

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


                            <a href="{{ route('admin.reports.customer') }}"
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
         SELECTED CUSTOMER
    ============================================================ --}}
    @if($selectedCustomer && $selectedReport)

        {{-- Customer Profile --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-body">

                <div class="d-flex flex-wrap justify-content-between align-items-center">

                    <div class="d-flex align-items-center">

                        {{-- Avatar --}}
                        <div class="me-3">

                            @if(!empty($selectedCustomer->photo))

                                <img src="{{ asset('storage/' . $selectedCustomer->photo) }}"
                                     alt="{{ $selectedCustomer->name }}"
                                     width="65"
                                     height="65"
                                     class="rounded-circle object-fit-cover">

                            @else

                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                     style="width:65px;height:65px;">

                                    <i class="fas fa-user fa-2x text-muted"></i>

                                </div>

                            @endif

                        </div>


                        {{-- Customer Info --}}
                        <div>

                            <h5 class="mb-1">
                                {{ $selectedCustomer->name }}
                            </h5>


                            @if(!empty($selectedCustomer->email))

                                <div class="text-muted small">

                                    <i class="fas fa-envelope me-1"></i>

                                    {{ $selectedCustomer->email }}

                                </div>

                            @endif


                            @if(!empty($selectedCustomer->phone))

                                <div class="text-muted small">

                                    <i class="fas fa-phone me-1"></i>

                                    {{ $selectedCustomer->phone }}

                                </div>

                            @endif

                        </div>

                    </div>


                    {{-- Back --}}
                    <div class="mt-3 mt-md-0">

                        <a href="{{ route('admin.reports.customer') }}"
                           class="btn btn-outline-secondary">

                            <i class="fas fa-arrow-left me-1"></i>

                            All Customers

                        </a>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================
             MAIN CUSTOMER STATS
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


            {{-- Total Spending --}}
            <div class="col-xl-3 col-md-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-start">

                            <div>

                                <p class="text-muted mb-1">
                                    Total Spending
                                </p>

                                <h3 class="mb-0 text-info">

                                    ৳{{ number_format($selectedReport['total_spending'], 2) }}

                                </h3>

                            </div>

                            <div class="text-info fs-3">

                                <i class="fas fa-wallet"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Average Booking --}}
            <div class="col-xl-3 col-md-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-start">

                            <div>

                                <p class="text-muted mb-1">
                                    Average Booking
                                </p>

                                <h3 class="mb-0 text-primary">

                                    ৳{{ number_format($selectedReport['average_booking_value'], 2) }}

                                </h3>

                            </div>

                            <div class="text-primary fs-3">

                                <i class="fas fa-chart-line"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================
             SECONDARY STATS
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


            {{-- Cancelled --}}
            <div class="col-xl-3 col-md-6">

                <div class="card border-0 shadow-sm">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <small class="text-muted">
                                    Cancelled
                                </small>

                                <h5 class="mb-0 mt-1 text-danger">

                                    {{ number_format($selectedReport['cancelled_bookings']) }}

                                </h5>

                            </div>

                            <i class="fas fa-times-circle text-danger fs-4"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================
             FINANCIAL SUMMARY
        ========================================================= --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white py-3">

                <h6 class="mb-0">

                    <i class="fas fa-chart-pie me-2 text-primary"></i>

                    Financial Summary

                </h6>

            </div>


            <div class="card-body">

                <div class="row g-4">

                    {{-- Spending --}}
                    <div class="col-lg-3 col-md-6">

                        <div class="border rounded p-3 h-100">

                            <small class="text-muted">
                                Total Spending
                            </small>

                            <h5 class="mb-0 mt-2 text-success">

                                ৳{{ number_format($selectedReport['total_spending'], 2) }}

                            </h5>

                        </div>

                    </div>


                    {{-- Refund --}}
                    <div class="col-lg-3 col-md-6">

                        <div class="border rounded p-3 h-100">

                            <small class="text-muted">
                                Refunded Amount
                            </small>

                            <h5 class="mb-0 mt-2 text-danger">

                                ৳{{ number_format($selectedReport['refunded_amount'], 2) }}

                            </h5>

                        </div>

                    </div>


                    {{-- Discount --}}
                    <div class="col-lg-3 col-md-6">

                        <div class="border rounded p-3 h-100">

                            <small class="text-muted">
                                Total Discount
                            </small>

                            <h5 class="mb-0 mt-2 text-warning">

                                ৳{{ number_format($selectedReport['total_discount'], 2) }}

                            </h5>

                        </div>

                    </div>


                    {{-- Tax --}}
                    <div class="col-lg-3 col-md-6">

                        <div class="border rounded p-3 h-100">

                            <small class="text-muted">
                                Total Tax
                            </small>

                            <h5 class="mb-0 mt-2 text-info">

                                ৳{{ number_format($selectedReport['total_tax'], 2) }}

                            </h5>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================
             CUSTOMER BOOKING DETAILS
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
                                    Tour
                                </th>

                                <th>
                                    Vendor
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


                                    {{-- Vendor --}}
                                    <td>

                                        @if($booking->vendor)

                                            <div class="fw-semibold">

                                                {{ $booking->vendor->business_name }}

                                            </div>

                                            @if(!empty($booking->vendor->email))

                                                <small class="text-muted">

                                                    {{ $booking->vendor->email }}

                                                </small>

                                            @endif

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


                                    {{-- Payment --}}
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

                                    <td colspan="7"
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


            @if($bookings->hasPages())

                <div class="card-footer bg-white">

                    {{ $bookings->links() }}

                </div>

            @endif

        </div>


    @else

        {{-- ========================================================
             ALL CUSTOMERS REPORT
        ========================================================= --}}

        {{-- Overall Stats --}}
        <div class="row g-3 mb-4">

            {{-- Customers --}}
            <div class="col-xl-4 col-md-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-start">

                            <div>

                                <p class="text-muted mb-1">
                                    Total Customers
                                </p>

                                <h3 class="mb-0">

                                    {{ number_format($overallTotalCustomers) }}

                                </h3>

                            </div>

                            <div class="text-primary fs-3">

                                <i class="fas fa-users"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Bookings --}}
            <div class="col-xl-4 col-md-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-start">

                            <div>

                                <p class="text-muted mb-1">
                                    Total Bookings
                                </p>

                                <h3 class="mb-0 text-info">

                                    {{ number_format($overallTotalBookings) }}

                                </h3>

                            </div>

                            <div class="text-info fs-3">

                                <i class="fas fa-calendar-check"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Spending --}}
            <div class="col-xl-4 col-md-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-start">

                            <div>

                                <p class="text-muted mb-1">
                                    Total Customer Spending
                                </p>

                                <h3 class="mb-0 text-success">

                                    ৳{{ number_format($overallTotalSpending, 2) }}

                                </h3>

                            </div>

                            <div class="text-success fs-3">

                                <i class="fas fa-money-bill-wave"></i>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Customer Performance --}}
        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white py-3">

                <div class="d-flex flex-wrap justify-content-between align-items-center">

                    <div>

                        <h6 class="mb-1">
                            Customer Performance
                        </h6>

                        <small class="text-muted">

                            {{ $reports->count() }}

                            customer(s) found

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
                                    Customer
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
                                    Total Spending
                                </th>

                                <th>
                                    Average Booking
                                </th>

                                <th class="text-end pe-4">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($reports as $index => $report)

                                @php
                                    $customer = $report['customer'];
                                @endphp


                                <tr>

                                    {{-- Number --}}
                                    <td class="ps-4">

                                        <span class="fw-semibold">

                                            {{ $index + 1 }}

                                        </span>

                                    </td>


                                    {{-- Customer --}}
                                    <td>

                                        <div class="d-flex align-items-center">

                                            <div class="me-3">

                                                @if(!empty($customer->photo))

                                                    <img src="{{ asset('storage/' . $customer->photo) }}"
                                                         alt="{{ $customer->name }}"
                                                         width="45"
                                                         height="45"
                                                         class="rounded-circle object-fit-cover">

                                                @else

                                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                                         style="width:45px;height:45px;">

                                                        <i class="fas fa-user text-muted"></i>

                                                    </div>

                                                @endif

                                            </div>


                                            <div>

                                                <div class="fw-semibold">

                                                    {{ $customer->name }}

                                                </div>


                                                @if(!empty($customer->email))

                                                    <small class="text-muted">

                                                        {{ $customer->email }}

                                                    </small>

                                                @endif


                                                @if(!empty($customer->phone))

                                                    <small class="text-muted d-block">

                                                        {{ $customer->phone }}

                                                    </small>

                                                @endif

                                            </div>

                                        </div>

                                    </td>


                                    {{-- Bookings --}}
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


                                    {{-- Spending --}}
                                    <td>

                                        <span class="fw-semibold text-success">

                                            ৳{{ number_format((float) $report['total_spending'], 2) }}

                                        </span>

                                    </td>


                                    {{-- Average --}}
                                    <td>

                                        <span class="fw-semibold text-primary">

                                            ৳{{ number_format((float) $report['average_booking_value'], 2) }}

                                        </span>

                                    </td>


                                    {{-- Action --}}
                                    <td class="text-end pe-4">

                                        <a href="{{ route('admin.reports.customer', [
                                            'customer_id' => $customer->id,
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

                                    <td colspan="8"
                                        class="text-center py-5">

                                        <div class="mb-3">

                                            <i class="fas fa-users-slash fa-3x text-muted"></i>

                                        </div>

                                        <h6 class="mb-1">

                                            No Customer Reports Found

                                        </h6>

                                        <p class="text-muted mb-0">

                                            No customer data matches your selected filters.

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