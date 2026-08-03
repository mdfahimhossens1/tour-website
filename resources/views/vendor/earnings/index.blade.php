@extends('layouts.vendor')

@section('page')

<div class="container-fluid py-4">

    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>

            <h4 class="fw-bold mb-1">
                Earnings
            </h4>

            <p class="text-muted mb-0">
                Track your resort and tour earnings, wallet balance and transactions.
            </p>

        </div>

        <div>

            <a
                href="{{ route('vendor.withdrawals.index') }}"
                class="btn btn-primary"
            >

                <i class="bi bi-wallet2 me-1"></i>

                Withdraw Money

            </a>

        </div>

    </div>


    {{-- =========================================================
        SUCCESS / ERROR MESSAGE
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success border-0 shadow-sm">

            <i class="bi bi-check-circle me-1"></i>

            {{ session('success') }}

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger border-0 shadow-sm">

            <i class="bi bi-exclamation-triangle me-1"></i>

            {{ session('error') }}

        </div>

    @endif


    {{-- =========================================================
        SUMMARY CARDS
    ========================================================== --}}

    <div class="row g-4 mb-4">


        {{-- Available Balance --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>

                            <p class="text-muted mb-1">
                                Available Balance
                            </p>

                            <h3 class="fw-bold mb-0">

                                ৳{{ number_format($availableBalance, 2) }}

                            </h3>

                        </div>

                        <div
                            class="rounded-circle bg-primary bg-opacity-10 p-3"
                        >

                            <i class="bi bi-wallet2 text-primary fs-4"></i>

                        </div>

                    </div>

                    <small class="text-muted">
                        Available for withdrawal
                    </small>

                </div>

            </div>

        </div>


        {{-- Total Earned --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>

                            <p class="text-muted mb-1">
                                Total Earned
                            </p>

                            <h3 class="fw-bold mb-0">

                                ৳{{ number_format($totalEarned, 2) }}

                            </h3>

                        </div>

                        <div
                            class="rounded-circle bg-success bg-opacity-10 p-3"
                        >

                            <i class="bi bi-graph-up-arrow text-success fs-4"></i>

                        </div>

                    </div>

                    <small class="text-muted">
                        Completed earnings
                    </small>

                </div>

            </div>

        </div>


        {{-- Monthly Earnings --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>

                            <p class="text-muted mb-1">
                                This Month
                            </p>

                            <h3 class="fw-bold mb-0">

                                ৳{{ number_format($monthlyEarning, 2) }}

                            </h3>

                        </div>

                        <div
                            class="rounded-circle bg-info bg-opacity-10 p-3"
                        >

                            <i class="bi bi-calendar3 text-info fs-4"></i>

                        </div>

                    </div>

                    <small class="text-muted">
                        Earnings this month
                    </small>

                </div>

            </div>

        </div>


        {{-- Withdrawn --}}

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>

                            <p class="text-muted mb-1">
                                Total Withdrawn
                            </p>

                            <h3 class="fw-bold mb-0">

                                ৳{{ number_format($totalWithdrawn, 2) }}

                            </h3>

                        </div>

                        <div
                            class="rounded-circle bg-warning bg-opacity-10 p-3"
                        >

                            <i class="bi bi-cash-stack text-warning fs-4"></i>

                        </div>

                    </div>

                    <small class="text-muted">
                        Successfully withdrawn
                    </small>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        SECONDARY SUMMARY
    ========================================================== --}}

    <div class="row g-4 mb-4">


        {{-- Today's Earnings --}}

        <div class="col-md-4">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="d-flex align-items-center gap-3">

                        <div
                            class="rounded-circle bg-success bg-opacity-10 p-3"
                        >

                            <i class="bi bi-calendar-day text-success fs-4"></i>

                        </div>

                        <div>

                            <div class="text-muted small">
                                Today's Earnings
                            </div>

                            <div class="fs-4 fw-bold">

                                ৳{{ number_format($todayEarning, 2) }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Pending Balance --}}

        <div class="col-md-4">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="d-flex align-items-center gap-3">

                        <div
                            class="rounded-circle bg-warning bg-opacity-10 p-3"
                        >

                            <i class="bi bi-hourglass-split text-warning fs-4"></i>

                        </div>

                        <div>

                            <div class="text-muted small">
                                Pending Balance
                            </div>

                            <div class="fs-4 fw-bold">

                                ৳{{ number_format($pendingBalance, 2) }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Wallet Total --}}

        <div class="col-md-4">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="d-flex align-items-center gap-3">

                        <div
                            class="rounded-circle bg-primary bg-opacity-10 p-3"
                        >

                            <i class="bi bi-bank text-primary fs-4"></i>

                        </div>

                        <div>

                            <div class="text-muted small">
                                Wallet Balance
                            </div>

                            <div class="fs-4 fw-bold">

                                ৳{{ number_format($wallet->balance ?? 0, 2) }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        TRANSACTIONS
    ========================================================== --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center">

                <div>

                    <h5 class="fw-bold mb-1">
                        Earning Transactions
                    </h5>

                    <small class="text-muted">
                        Your completed earning history.
                    </small>

                </div>

                <span class="badge bg-light text-dark border">

                    {{ $transactions->total() }}

                    Transactions

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($transactions->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th class="px-4">
                                    Transaction
                                </th>

                                <th>
                                    Booking
                                </th>

                                <th>
                                    Tour
                                </th>

                                <th>
                                    Amount
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

                            @foreach($transactions as $transaction)

                                <tr>

                                    {{-- Transaction --}}

                                    <td class="px-4">

                                        <div class="d-flex align-items-center gap-2">

                                            <div
                                                class="rounded-circle bg-success bg-opacity-10 p-2"
                                            >

                                                <i
                                                    class="bi bi-arrow-down-left text-success"
                                                ></i>

                                            </div>

                                            <div>

                                                <div class="fw-semibold">

                                                    #{{ $transaction->id }}

                                                </div>

                                                <small class="text-muted">

                                                    {{ $transaction->note ?? 'Earning' }}

                                                </small>

                                            </div>

                                        </div>

                                    </td>


                                    {{-- Booking --}}

                                    <td>

                                        @if($transaction->booking)

                                            <span class="fw-semibold">

                                                #{{ $transaction->booking->id }}

                                            </span>

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Tour --}}

                                    <td>

                                        @if(
                                            $transaction->booking &&
                                            $transaction->booking->tour
                                        )

                                            {{ $transaction->booking->tour->title }}

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Amount --}}

                                    <td>

                                        <span class="fw-bold text-success">

                                            +৳{{ number_format($transaction->amount, 2) }}

                                        </span>

                                    </td>


                                    {{-- Status --}}

                                    <td>

                                        @if($transaction->status === 'completed')

                                            <span class="badge bg-success-subtle text-success">

                                                <i class="bi bi-check-circle me-1"></i>

                                                Completed

                                            </span>

                                        @elseif($transaction->status === 'pending')

                                            <span class="badge bg-warning-subtle text-warning">

                                                <i class="bi bi-clock me-1"></i>

                                                Pending

                                            </span>

                                        @else

                                            <span class="badge bg-danger-subtle text-danger">

                                                {{ ucfirst($transaction->status) }}

                                            </span>

                                        @endif

                                    </td>


                                    {{-- Date --}}

                                    <td>

                                        <div class="fw-semibold">

                                            {{ $transaction->created_at->format('d M Y') }}

                                        </div>

                                        <small class="text-muted">

                                            {{ $transaction->created_at->format('h:i A') }}

                                        </small>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


            @else

                <div class="text-center py-5">

                    <div class="mb-3">

                        <i
                            class="bi bi-bar-chart-line fs-1 text-muted"
                        ></i>

                    </div>

                    <h6 class="fw-bold">
                        No earnings yet
                    </h6>

                    <p class="text-muted mb-0">

                        Your completed booking earnings will appear here.

                    </p>

                </div>

            @endif

        </div>


        {{-- Pagination --}}

        @if($transactions->hasPages())

            <div class="card-footer bg-white border-0 py-3">

                {{ $transactions->links() }}

            </div>

        @endif

    </div>

</div>

@endsection