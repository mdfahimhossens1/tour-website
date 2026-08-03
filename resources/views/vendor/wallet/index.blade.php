@extends('layouts.vendor')

@section('page')

<div class="container-fluid py-4">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h4 class="fw-bold mb-1">
                My Wallet
            </h4>

            <p class="text-muted mb-0">
                View your earnings, wallet balance and transaction history.
            </p>
        </div>

    </div>


    {{-- =========================================================
        SUCCESS MESSAGE
    ========================================================== --}}
    @if(session('success'))

        <div class="alert alert-success border-0 shadow-sm">
            <i class="bi bi-check-circle me-1"></i>
            {{ session('success') }}
        </div>

    @endif


    {{-- =========================================================
        ERROR MESSAGE
    ========================================================== --}}
    @if(session('error'))

        <div class="alert alert-danger border-0 shadow-sm">
            <i class="bi bi-exclamation-triangle me-1"></i>
            {{ session('error') }}
        </div>

    @endif


    {{-- =========================================================
        WALLET SUMMARY
    ========================================================== --}}
    <div class="row g-4 mb-4">

        {{-- Available Balance --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>

                            <p class="text-muted mb-2">
                                Available Balance
                            </p>

                            <h3 class="fw-bold mb-0">
                                ৳{{ number_format($wallet->balance ?? 0, 2) }}
                            </h3>

                        </div>

                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">

                            <i class="bi bi-wallet2 text-primary fs-4"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Pending Balance --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>

                            <p class="text-muted mb-2">
                                Pending Balance
                            </p>

                            <h3 class="fw-bold mb-0">
                                ৳{{ number_format($wallet->pending_balance ?? 0, 2) }}
                            </h3>

                        </div>

                        <div class="bg-warning bg-opacity-10 rounded-circle p-3">

                            <i class="bi bi-hourglass-split text-warning fs-4"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Total Earned --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>

                            <p class="text-muted mb-2">
                                Total Earned
                            </p>

                            <h3 class="fw-bold mb-0">
                                ৳{{ number_format($wallet->total_earned ?? 0, 2) }}
                            </h3>

                        </div>

                        <div class="bg-success bg-opacity-10 rounded-circle p-3">

                            <i class="bi bi-graph-up-arrow text-success fs-4"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Total Withdrawn --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>

                            <p class="text-muted mb-2">
                                Total Withdrawn
                            </p>

                            <h3 class="fw-bold mb-0">
                                ৳{{ number_format($wallet->total_withdrawn ?? 0, 2) }}
                            </h3>

                        </div>

                        <div class="bg-danger bg-opacity-10 rounded-circle p-3">

                            <i class="bi bi-arrow-up-right-circle text-danger fs-4"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        CREDIT / DEBIT SUMMARY
    ========================================================== --}}
    <div class="row g-4 mb-4">

        {{-- Total Credits --}}
        <div class="col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="d-flex align-items-center gap-3">

                        <div class="bg-success bg-opacity-10 rounded-circle p-3">

                            <i class="bi bi-arrow-down-left text-success fs-4"></i>

                        </div>

                        <div>

                            <div class="text-muted small">
                                Completed Credits
                            </div>

                            <h5 class="fw-bold mb-0 text-success">
                                +৳{{ number_format($totalCredits ?? 0, 2) }}
                            </h5>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Total Debits --}}
        <div class="col-md-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="d-flex align-items-center gap-3">

                        <div class="bg-danger bg-opacity-10 rounded-circle p-3">

                            <i class="bi bi-arrow-up-right text-danger fs-4"></i>

                        </div>

                        <div>

                            <div class="text-muted small">
                                Completed Debits
                            </div>

                            <h5 class="fw-bold mb-0 text-danger">
                                -৳{{ number_format($totalDebits ?? 0, 2) }}
                            </h5>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        TRANSACTION HISTORY
    ========================================================== --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center">

                <div>

                    <h5 class="fw-bold mb-1">
                        Transaction History
                    </h5>

                    <small class="text-muted">
                        Your latest wallet transactions.
                    </small>

                </div>

            </div>

        </div>


        <div class="card-body p-0">

            @if($transactions->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th class="px-4">
                                    Date
                                </th>

                                <th>
                                    Type
                                </th>

                                <th>
                                    Booking
                                </th>

                                <th>
                                    Amount
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Note
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($transactions as $transaction)

                                <tr>

                                    {{-- Date --}}
                                    <td class="px-4">

                                        <div class="fw-semibold">

                                            {{ $transaction->created_at?->format('d M Y') }}

                                        </div>

                                        <small class="text-muted">

                                            {{ $transaction->created_at?->format('h:i A') }}

                                        </small>

                                    </td>


                                    {{-- Type --}}
                                    <td>

                                        @if($transaction->type === 'credit')

                                            <span class="badge bg-success-subtle text-success">

                                                <i class="bi bi-arrow-down-left me-1"></i>

                                                Credit

                                            </span>

                                        @else

                                            <span class="badge bg-danger-subtle text-danger">

                                                <i class="bi bi-arrow-up-right me-1"></i>

                                                Debit

                                            </span>

                                        @endif

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


                                    {{-- Amount --}}
                                    <td>

                                        @if($transaction->type === 'credit')

                                            <span class="fw-bold text-success">

                                                +৳{{ number_format($transaction->amount ?? 0, 2) }}

                                            </span>

                                        @else

                                            <span class="fw-bold text-danger">

                                                -৳{{ number_format($transaction->amount ?? 0, 2) }}

                                            </span>

                                        @endif

                                    </td>


                                    {{-- Status --}}
                                    <td>

                                        @switch($transaction->status)

                                            @case('completed')

                                                <span class="badge bg-success-subtle text-success">
                                                    Completed
                                                </span>

                                                @break

                                            @case('pending')

                                                <span class="badge bg-warning-subtle text-warning">
                                                    Pending
                                                </span>

                                                @break

                                            @case('failed')

                                                <span class="badge bg-danger-subtle text-danger">
                                                    Failed
                                                </span>

                                                @break

                                            @default

                                                <span class="badge bg-secondary-subtle text-secondary">
                                                    {{ ucfirst($transaction->status ?? 'Unknown') }}
                                                </span>

                                        @endswitch

                                    </td>


                                    {{-- Note --}}
                                    <td>

                                        <span
                                            class="text-muted"
                                            title="{{ $transaction->note }}"
                                        >
                                            {{ \Illuminate\Support\Str::limit($transaction->note, 45) }}
                                        </span>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- Pagination --}}
                @if($transactions->hasPages())

                    <div class="px-4 py-3 border-top">

                        {{ $transactions->links() }}

                    </div>

                @endif


            @else

                {{-- Empty State --}}
                <div class="text-center py-5">

                    <div class="mb-3">

                        <i class="bi bi-wallet2 text-muted"
                           style="font-size: 48px;">
                        </i>

                    </div>

                    <h5 class="fw-bold">
                        No Transactions Yet
                    </h5>

                    <p class="text-muted mb-0">
                        Your wallet transactions will appear here.
                    </p>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection
