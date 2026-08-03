@extends('layouts.vendor')

@section('page')

<div class="container-fluid py-4">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h4 class="fw-bold mb-1">
                Withdrawals
            </h4>

            <p class="text-muted mb-0">
                Request withdrawals and track your withdrawal history.
            </p>
        </div>

        <a
            href="{{ route('vendor.wallet.index') }}"
            class="btn btn-light border"
        >
            <i class="bi bi-wallet2 me-1"></i>
            My Wallet
        </a>

    </div>


    {{-- =========================================================
        SUCCESS
    ========================================================== --}}
    @if(session('success'))

        <div class="alert alert-success border-0 shadow-sm">

            <i class="bi bi-check-circle me-1"></i>

            {{ session('success') }}

        </div>

    @endif


    {{-- =========================================================
        ERROR
    ========================================================== --}}
    @if(session('error'))

        <div class="alert alert-danger border-0 shadow-sm">

            <i class="bi bi-exclamation-triangle me-1"></i>

            {{ session('error') }}

        </div>

    @endif


    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}
    @if($errors->any())

        <div class="alert alert-danger border-0 shadow-sm">

            <div class="fw-bold mb-2">
                Please fix the following errors:
            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =========================================================
        BALANCE CARD
    ========================================================== --}}
    <div class="row g-4 mb-4">

        <div class="col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center gap-3">

                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">

                            <i class="bi bi-wallet2 text-primary fs-4"></i>

                        </div>

                        <div>

                            <div class="text-muted small">
                                Available Balance
                            </div>

                            <h3 class="fw-bold mb-0">

                                ৳{{ number_format($wallet->balance ?? 0, 2) }}

                            </h3>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center gap-3">

                        <div class="bg-warning bg-opacity-10 rounded-circle p-3">

                            <i class="bi bi-hourglass-split text-warning fs-4"></i>

                        </div>

                        <div>

                            <div class="text-muted small">
                                Pending Balance
                            </div>

                            <h3 class="fw-bold mb-0">

                                ৳{{ number_format($wallet->pending_balance ?? 0, 2) }}

                            </h3>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        WITHDRAW REQUEST
    ========================================================== --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white border-0 py-3">

            <h5 class="fw-bold mb-1">
                Request Withdrawal
            </h5>

            <small class="text-muted">
                Submit a withdrawal request from your available wallet balance.
            </small>

        </div>


        <div class="card-body">

            <form
                action="{{ route('vendor.withdrawals.store') }}"
                method="POST"
            >

                @csrf


                <div class="row g-3">

                    {{-- Amount --}}
                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Withdrawal Amount
                            <span class="text-danger">*</span>
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                ৳
                            </span>

                            <input
                                type="number"
                                name="amount"
                                min="1"
                                step="0.01"
                                value="{{ old('amount') }}"
                                class="form-control @error('amount') is-invalid @enderror"
                                placeholder="Enter amount"
                                required
                            >

                            @error('amount')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                        <small class="text-muted">
                            Available:
                            ৳{{ number_format($wallet->balance ?? 0, 2) }}
                        </small>

                    </div>


                    {{-- Method --}}
                    <div class="col-md-4">

                        <label class="form-label fw-semibold">
                            Withdrawal Method
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="method"
                            class="form-select @error('method') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                Select Method
                            </option>

                            <option
                                value="bkash"
                                @selected(old('method') === 'bkash')
                            >
                                bKash
                            </option>

                            <option
                                value="nagad"
                                @selected(old('method') === 'nagad')
                            >
                                Nagad
                            </option>

                            <option
                                value="bank"
                                @selected(old('method') === 'bank')
                            >
                                Bank Transfer
                            </option>

                        </select>

                        @error('method')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Account Details --}}
                    <div class="col-md-4">

                        <label class="form-label fw-semibold">

                            Account Details

                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="text"
                            name="account_details"
                            value="{{ old('account_details') }}"
                            class="form-control @error('account_details') is-invalid @enderror"
                            placeholder="Phone / Bank account details"
                            required
                        >

                        @error('account_details')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Submit --}}
                    <div class="col-12">

                        <button
                            type="submit"
                            class="btn btn-primary px-4"
                            @disabled(($wallet->balance ?? 0) <= 0)
                        >

                            <i class="bi bi-send me-1"></i>

                            Submit Withdrawal Request

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
        WITHDRAWAL HISTORY
    ========================================================== --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-0 py-3">

            <h5 class="fw-bold mb-1">
                Withdrawal History
            </h5>

            <small class="text-muted">
                Track your withdrawal requests and their current status.
            </small>

        </div>


        <div class="card-body p-0">

            @if($withdrawals->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th class="px-4">
                                    Date
                                </th>

                                <th>
                                    Amount
                                </th>

                                <th>
                                    Method
                                </th>

                                <th>
                                    Account
                                </th>

                                <th>
                                    Status
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($withdrawals as $withdrawal)

                                <tr>

                                    {{-- Date --}}
                                    <td class="px-4">

                                        <div class="fw-semibold">

                                            {{ $withdrawal->created_at?->format('d M Y') }}

                                        </div>

                                        <small class="text-muted">

                                            {{ $withdrawal->created_at?->format('h:i A') }}

                                        </small>

                                    </td>


                                    {{-- Amount --}}
                                    <td>

                                        <span class="fw-bold">

                                            ৳{{ number_format($withdrawal->amount, 2) }}

                                        </span>

                                    </td>


                                    {{-- Method --}}
                                    <td>

                                        @switch($withdrawal->method)

                                            @case('bkash')

                                                <span class="badge bg-danger-subtle text-danger">
                                                    bKash
                                                </span>

                                                @break

                                            @case('nagad')

                                                <span class="badge bg-warning-subtle text-warning">
                                                    Nagad
                                                </span>

                                                @break

                                            @case('bank')

                                                <span class="badge bg-primary-subtle text-primary">
                                                    Bank
                                                </span>

                                                @break

                                            @default

                                                <span class="badge bg-secondary-subtle text-secondary">
                                                    {{ ucfirst($withdrawal->method ?? 'Unknown') }}
                                                </span>

                                        @endswitch

                                    </td>


                                    {{-- Account --}}
                                    <td>

                                        <span
                                            class="text-muted"
                                            title="{{ $withdrawal->account_details }}"
                                        >

                                            {{ \Illuminate\Support\Str::limit(
                                                $withdrawal->account_details,
                                                30
                                            ) }}

                                        </span>

                                    </td>


                                    {{-- Status --}}
                                    <td>

                                        @switch($withdrawal->status)

                                            @case('pending')

                                                <span class="badge bg-warning-subtle text-warning">
                                                    <i class="bi bi-clock me-1"></i>
                                                    Pending
                                                </span>

                                                @break

                                            @case('approved')

                                                <span class="badge bg-info-subtle text-info">
                                                    <i class="bi bi-check-circle me-1"></i>
                                                    Approved
                                                </span>

                                                @break

                                            @case('completed')

                                                <span class="badge bg-success-subtle text-success">
                                                    <i class="bi bi-check2-all me-1"></i>
                                                    Completed
                                                </span>

                                                @break

                                            @case('rejected')

                                                <span class="badge bg-danger-subtle text-danger">
                                                    <i class="bi bi-x-circle me-1"></i>
                                                    Rejected
                                                </span>

                                                @break

                                            @default

                                                <span class="badge bg-secondary-subtle text-secondary">

                                                    {{ ucfirst($withdrawal->status ?? 'Unknown') }}

                                                </span>

                                        @endswitch

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- Pagination --}}
                @if($withdrawals->hasPages())

                    <div class="px-4 py-3 border-top">

                        {{ $withdrawals->links() }}

                    </div>

                @endif


            @else

                <div class="text-center py-5">

                    <div class="mb-3">

                        <i
                            class="bi bi-cash-stack text-muted"
                            style="font-size: 48px;"
                        ></i>

                    </div>

                    <h5 class="fw-bold">
                        No Withdrawal Requests
                    </h5>

                    <p class="text-muted mb-0">
                        Your withdrawal history will appear here.
                    </p>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection

