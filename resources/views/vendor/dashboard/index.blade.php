@extends('layouts.vendor')

@section('page')

<div class="container-fluid py-4">

    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h4 class="fw-bold mb-1">
                Vendor Dashboard
            </h4>

            <p class="text-muted mb-0">
                Welcome back,
                {{ $vendor->business_name ?? auth()->user()->name }}.
                Here's your business overview.
            </p>
        </div>

    </div>


    {{-- =========================================================
        SUCCESS / ERROR MESSAGE
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

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

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="bi bi-exclamation-triangle me-1"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- =========================================================
        STATISTICS
    ========================================================== --}}

    <div class="row g-4 mb-4">

        {{-- Total Resorts --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <p class="text-muted mb-1">
                                Total Resorts
                            </p>

                            <h3 class="fw-bold mb-0">
                                {{ $totalResorts ?? 0 }}
                            </h3>

                        </div>

                        <div class="bg-primary bg-opacity-10 rounded-3 p-3">

                            <i class="bi bi-building text-primary fs-4"></i>

                        </div>

                    </div>

                    <div class="mt-3">

                        <a
                            href="{{ route('vendor.resorts.index') }}"
                            class="small text-decoration-none"
                        >
                            Manage Resorts
                            <i class="bi bi-arrow-right"></i>
                        </a>

                    </div>

                </div>

            </div>

        </div>


        {{-- Total Rooms --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <p class="text-muted mb-1">
                                Total Rooms
                            </p>

                            <h3 class="fw-bold mb-0">
                                {{ $totalRooms ?? 0 }}
                            </h3>

                        </div>

                        <div class="bg-success bg-opacity-10 rounded-3 p-3">

                            <i class="bi bi-door-open text-success fs-4"></i>

                        </div>

                    </div>

                    <div class="mt-3">

                        <a
                            href="{{ route('vendor.rooms.index') }}"
                            class="small text-decoration-none"
                        >
                            Manage Rooms
                            <i class="bi bi-arrow-right"></i>
                        </a>

                    </div>

                </div>

            </div>

        </div>


        {{-- Total Bookings --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <p class="text-muted mb-1">
                                Total Bookings
                            </p>

                            <h3 class="fw-bold mb-0">
                                {{ $totalBookings ?? 0 }}
                            </h3>

                        </div>

                        <div class="bg-warning bg-opacity-10 rounded-3 p-3">

                            <i class="bi bi-calendar-check text-warning fs-4"></i>

                        </div>

                    </div>

                    <div class="mt-3">

                        <a
                            href="{{ route('vendor.resorts.index') }}"
                            class="small text-decoration-none"
                        >
                            View Bookings
                            <i class="bi bi-arrow-right"></i>
                        </a>

                    </div>

                </div>

            </div>

        </div>


        {{-- Total Earnings --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <p class="text-muted mb-1">
                                Total Earnings
                            </p>

                            <h3 class="fw-bold mb-0">
                                ৳{{ number_format($totalEarnings ?? 0, 2) }}
                            </h3>

                        </div>

                        <div class="bg-info bg-opacity-10 rounded-3 p-3">

                            <i class="bi bi-cash-stack text-info fs-4"></i>

                        </div>

                    </div>

                    <div class="mt-3">

                        <a
                            href="{{ route('vendor.earnings.index') }}"
                            class="small text-decoration-none"
                        >
                            View Earnings
                            <i class="bi bi-arrow-right"></i>
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        MAIN CONTENT
    ========================================================== --}}

    <div class="row g-4 mb-4">


        {{-- =====================================================
            RECENT BOOKINGS
        ====================================================== --}}

        <div class="col-xl-8">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white border-0 py-3">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h5 class="fw-bold mb-1">
                                Recent Bookings
                            </h5>

                            <small class="text-muted">
                                Latest bookings from your resorts
                            </small>

                        </div>

                        <a
                            href="{{ route('vendor.resorts.index') }}"
                            class="btn btn-sm btn-light border"
                        >
                            View All
                        </a>

                    </div>

                </div>


                <div class="card-body p-0">

                    @if(isset($recentBookings) && $recentBookings->count())

                        <div class="table-responsive">

                            <table class="table align-middle mb-0">

                                <thead class="table-light">

                                    <tr>

                                        <th class="ps-3">
                                            Booking
                                        </th>

                                        <th>
                                            Guest
                                        </th>

                                        <th>
                                            Resort
                                        </th>

                                        <th>
                                            Amount
                                        </th>

                                        <th>
                                            Status
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @foreach($recentBookings as $booking)

                                        <tr>

                                            <td class="ps-3">

                                                <div class="fw-semibold">
                                                    #{{ $booking->booking_code ?? $booking->id }}
                                                </div>

                                                <small class="text-muted">

                                                    {{ optional($booking->created_at)->format('d M Y') }}

                                                </small>

                                            </td>


                                            <td>

                                                {{ $booking->user->name ?? 'Guest' }}

                                            </td>


                                            <td>

                                                {{ $booking->resort->name ?? 'N/A' }}

                                                @if($booking->room)

                                                    <br>

                                                    <small class="text-muted">

                                                        {{ $booking->room->name ?? 'Room' }}

                                                    </small>

                                                @endif

                                            </td>


                                            <td>

                                                <span class="fw-semibold">

                                                    ৳{{ number_format($booking->total_amount ?? 0, 2) }}

                                                </span>

                                            </td>


                                            <td>

                                                @php

                                                    $status = $booking->booking_status ?? 'pending';

                                                    $badge = match($status) {

                                                        'confirmed' => 'bg-success',

                                                        'completed' => 'bg-primary',

                                                        'cancelled' => 'bg-danger',

                                                        default => 'bg-warning text-dark',

                                                    };

                                                @endphp

                                                <span class="badge {{ $badge }}">

                                                    {{ ucfirst($status) }}

                                                </span>

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="text-center py-5">

                            <i class="bi bi-calendar-x fs-1 text-muted"></i>

                            <p class="text-muted mt-3 mb-0">
                                No bookings found yet.
                            </p>

                        </div>

                    @endif

                </div>

            </div>

        </div>


        {{-- =====================================================
            QUICK ACTIONS
        ====================================================== --}}

        <div class="col-xl-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="fw-bold mb-0">
                        Quick Actions
                    </h5>

                </div>


                <div class="card-body">

                    <div class="d-grid gap-3">

                        <a
                            href="{{ route('vendor.resorts.create') }}"
                            class="btn btn-primary text-start"
                        >

                            <i class="bi bi-building-add me-2"></i>

                            Add New Resort

                            <i class="bi bi-arrow-right float-end"></i>

                        </a>


                        <a
                            href="{{ route('vendor.rooms.index') }}"
                            class="btn btn-light border text-start"
                        >

                            <i class="bi bi-door-open me-2"></i>

                            Manage Rooms

                            <i class="bi bi-arrow-right float-end"></i>

                        </a>


                        <a
                            href="{{ route('vendor.resorts.index') }}"
                            class="btn btn-light border text-start"
                        >

                            <i class="bi bi-calendar-check me-2"></i>

                            Manage Bookings

                            <i class="bi bi-arrow-right float-end"></i>

                        </a>


                        <a
                            href="{{ route('vendor.wallet.index') }}"
                            class="btn btn-light border text-start"
                        >

                            <i class="bi bi-wallet2 me-2"></i>

                            My Wallet

                            <i class="bi bi-arrow-right float-end"></i>

                        </a>


                        <a
                            href="{{ route('vendor.withdrawals.index') }}"
                            class="btn btn-light border text-start"
                        >

                            <i class="bi bi-bank me-2"></i>

                            Withdraw Money

                            <i class="bi bi-arrow-right float-end"></i>

                        </a>


                        <a
                            href="{{ route('vendor.profile.index') }}"
                            class="btn btn-light border text-start"
                        >

                            <i class="bi bi-person-circle me-2"></i>

                            My Profile

                            <i class="bi bi-arrow-right float-end"></i>

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        SECOND ROW
    ========================================================== --}}

    <div class="row g-4 mb-4">


        {{-- =====================================================
            WALLET
        ====================================================== --}}

        <div class="col-xl-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white border-0 py-3">

                    <div class="d-flex justify-content-between">

                        <h5 class="fw-bold mb-0">
                            Wallet
                        </h5>

                        <a
                            href="{{ route('vendor.wallet.index') }}"
                            class="small text-decoration-none"
                        >
                            View
                        </a>

                    </div>

                </div>


                <div class="card-body">

                    <div class="mb-4">

                        <small class="text-muted">
                            Available Balance
                        </small>

                        <h3 class="fw-bold mt-1 mb-0">

                            ৳{{ number_format($wallet->balance ?? 0, 2) }}

                        </h3>

                    </div>


                    <div class="row g-3">

                        <div class="col-6">

                            <div class="bg-light rounded-3 p-3">

                                <small class="text-muted">
                                    Pending
                                </small>

                                <div class="fw-bold mt-1">

                                    ৳{{ number_format($wallet->pending_balance ?? 0, 2) }}

                                </div>

                            </div>

                        </div>


                        <div class="col-6">

                            <div class="bg-light rounded-3 p-3">

                                <small class="text-muted">
                                    Withdrawn
                                </small>

                                <div class="fw-bold mt-1">

                                    ৳{{ number_format($wallet->total_withdrawn ?? 0, 2) }}

                                </div>

                            </div>

                        </div>

                    </div>


                    <a
                        href="{{ route('vendor.withdrawals.index') }}"
                        class="btn btn-primary w-100 mt-4"
                    >

                        <i class="bi bi-cash-coin me-1"></i>

                        Request Withdrawal

                    </a>

                </div>

            </div>

        </div>


        {{-- =====================================================
            BOOKING SUMMARY
        ====================================================== --}}

        <div class="col-xl-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="fw-bold mb-0">
                        Booking Summary
                    </h5>

                </div>


                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <span class="text-muted">
                            Pending
                        </span>

                        <span class="badge bg-warning text-dark">

                            {{ $pendingBookings ?? 0 }}

                        </span>

                    </div>


                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <span class="text-muted">
                            Confirmed
                        </span>

                        <span class="badge bg-success">

                            {{ $confirmedBookings ?? 0 }}

                        </span>

                    </div>


                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <span class="text-muted">
                            Completed
                        </span>

                        <span class="badge bg-primary">

                            {{ $completedBookings ?? 0 }}

                        </span>

                    </div>


                    <div class="d-flex justify-content-between align-items-center">

                        <span class="text-muted">
                            Cancelled
                        </span>

                        <span class="badge bg-danger">

                            {{ $cancelledBookings ?? 0 }}

                        </span>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
            RESORT SUMMARY
        ====================================================== --}}

        <div class="col-xl-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="fw-bold mb-0">
                        Resort Overview
                    </h5>

                </div>


                <div class="card-body">

                    <div class="d-flex justify-content-between mb-3">

                        <span class="text-muted">
                            Total Resorts
                        </span>

                        <strong>
                            {{ $totalResorts ?? 0 }}
                        </strong>

                    </div>


                    <div class="d-flex justify-content-between mb-3">

                        <span class="text-muted">
                            Total Rooms
                        </span>

                        <strong>
                            {{ $totalRooms ?? 0 }}
                        </strong>

                    </div>


                    <div class="d-flex justify-content-between mb-3">

                        <span class="text-muted">
                            Total Bookings
                        </span>

                        <strong>
                            {{ $totalBookings ?? 0 }}
                        </strong>

                    </div>


                    <div class="d-flex justify-content-between">

                        <span class="text-muted">
                            Total Earnings
                        </span>

                        <strong class="text-success">

                            ৳{{ number_format($totalEarnings ?? 0, 2) }}

                        </strong>

                    </div>

                </div>

            </div>

        </div>

    </div>


{{-- =========================================================
    RECENT RESORTS
========================================================== --}}

<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-white border-0 py-3">

        <div class="d-flex justify-content-between align-items-center">

            <div>

                <h5 class="fw-bold mb-1">
                    My Resorts
                </h5>

                <small class="text-muted">
                    Recently added resorts
                </small>

            </div>

            <a
                href="{{ route('vendor.resorts.index') }}"
                class="btn btn-sm btn-light border"
            >
                View All
            </a>

        </div>

    </div>


    <div class="card-body p-0">

        @if(isset($resorts) && $resorts->count())

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="ps-3">
                                Resort
                            </th>

                            <th>
                                Added By
                            </th>

                            <th>
                                Date
                            </th>

                            <th>
                                Time
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($resorts->take(5) as $resort)

                            <tr>

                                {{-- Resort --}}
                                <td class="ps-3">

                                    <div class="d-flex align-items-center gap-3">

                                        {{-- Resort Image --}}
                                        <div
                                            class="rounded-3 bg-light d-flex align-items-center justify-content-center overflow-hidden"
                                            style="width:50px;height:50px;"
                                        >

                                            @if($resort->featured_image)

                                                <img
                                                    src="{{ asset('storage/' . $resort->featured_image) }}"
                                                    alt="{{ $resort->name }}"
                                                    class="w-100 h-100"
                                                    style="object-fit:cover;"
                                                >

                                            @else

                                                <i class="bi bi-building text-muted fs-4"></i>

                                            @endif

                                        </div>


                                        {{-- Name + Location --}}
                                        <div>

                                            <div class="fw-semibold">

                                                {{ $resort->name }}

                                            </div>

                                            <small class="text-muted">

                                                {{ $resort->district ?? 'Location not set' }}

                                            </small>

                                        </div>

                                    </div>

                                </td>


                                {{-- Added By --}}
                                <td>

                                    <div class="d-flex align-items-center gap-2">

                                        <div
                                            class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center"
                                            style="width:34px;height:34px;"
                                        >

                                            <i class="bi bi-person"></i>

                                        </div>


                                        <span class="fw-semibold">

                                            {{ $vendor->business_name ?? auth()->user()->name }}

                                        </span>

                                    </div>

                                </td>


                                {{-- Date --}}
                                <td>

                                    <span class="text-muted">

                                        {{ $resort->created_at?->format('d M Y') }}

                                    </span>

                                </td>


                                {{-- Time --}}
                                <td>

                                    <span class="text-muted">

                                        {{ $resort->created_at?->format('h:i A') }}

                                    </span>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            {{-- Empty State --}}

            <div class="text-center py-5">

                <i class="bi bi-building fs-1 text-muted"></i>

                <h6 class="fw-bold mt-3">
                    No Resorts Yet
                </h6>

                <p class="text-muted mb-3">
                    Start by adding your first resort.
                </p>

            </div>

        @endif

    </div>

</div>

    {{-- =========================================================
        RECENT WALLET TRANSACTIONS
    ========================================================== --}}

    @if(isset($recentTransactions))

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white border-0 py-3">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <h5 class="fw-bold mb-1">
                            Recent Wallet Activity
                        </h5>

                        <small class="text-muted">
                            Latest wallet transactions
                        </small>

                    </div>

                    <a
                        href="{{ route('vendor.wallet.index') }}"
                        class="btn btn-sm btn-light border"
                    >
                        View Wallet
                    </a>

                </div>

            </div>


            <div class="card-body p-0">

                @if($recentTransactions->count())

                    <div class="table-responsive">

                        <table class="table align-middle mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th class="ps-3">
                                        Date
                                    </th>

                                    <th>
                                        Type
                                    </th>

                                    <th>
                                        Booking
                                    </th>

                                    <th>
                                        Note
                                    </th>

                                    <th class="text-end pe-3">
                                        Amount
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach($recentTransactions as $transaction)

                                    <tr>

                                        <td class="ps-3">

                                            {{ optional($transaction->created_at)->format('d M Y') }}

                                        </td>


                                        <td>

                                            @if($transaction->type === 'credit')

                                                <span class="badge bg-success">
                                                    Credit
                                                </span>

                                            @else

                                                <span class="badge bg-danger">
                                                    Debit
                                                </span>

                                            @endif

                                        </td>


                                        <td>

                                            @if($transaction->booking_id)

                                                #{{ $transaction->booking_id }}

                                            @else

                                                —

                                            @endif

                                        </td>


                                        <td>

                                            {{ $transaction->note ?? 'Wallet transaction' }}

                                        </td>


                                        <td class="text-end pe-3">

                                            <span
                                                class="fw-semibold
                                                {{ $transaction->type === 'credit'
                                                    ? 'text-success'
                                                    : 'text-danger'
                                                }}"
                                            >

                                                {{ $transaction->type === 'credit' ? '+' : '-' }}

                                                ৳{{ number_format($transaction->amount ?? 0, 2) }}

                                            </span>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @else

                    <div class="text-center py-5">

                        <i class="bi bi-wallet2 fs-1 text-muted"></i>

                        <p class="text-muted mt-3 mb-0">
                            No wallet transactions yet.
                        </p>

                    </div>

                @endif

            </div>

        </div>

    @endif

</div>

@endsection
