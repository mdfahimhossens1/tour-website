@extends('layouts.admin')

@section('page')

<div class="container-fluid py-4">

    {{-- Header --}}

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>

            <h4 class="fw-bold mb-1">
                Withdrawal Requests
            </h4>

            <p class="text-muted mb-0">
                Manage vendor withdrawal requests and payment approvals.
            </p>

        </div>

    </div>


    {{-- Success --}}

    @if(session('success'))

        <div class="alert alert-success border-0 shadow-sm">

            <i class="bi bi-check-circle me-1"></i>

            {{ session('success') }}

        </div>

    @endif


    {{-- Error --}}

    @if(session('error'))

        <div class="alert alert-danger border-0 shadow-sm">

            <i class="bi bi-exclamation-triangle me-1"></i>

            {{ session('error') }}

        </div>

    @endif


    {{-- Withdrawal Table --}}

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="fw-bold mb-1">
                        All Withdrawal Requests
                    </h5>

                    <small class="text-muted">
                        Review and process vendor withdrawal requests.
                    </small>

                </div>

                <span class="badge bg-light text-dark border">

                    {{ $withdrawals->total() }}

                    Requests

                </span>

            </div>

        </div>


        <div class="card-body p-0">

            @if($withdrawals->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th class="px-4">
                                    ID
                                </th>

                                <th>
                                    Vendor
                                </th>

                                <th>
                                    Amount
                                </th>

                                <th>
                                    Method
                                </th>

                                <th>
                                    Account Details
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Date
                                </th>

                                <th class="text-end px-4">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($withdrawals as $withdrawal)

                                <tr>

                                    {{-- ID --}}

                                    <td class="px-4">

                                        <span class="fw-semibold">

                                            #{{ $withdrawal->id }}

                                        </span>

                                    </td>


                                    {{-- Vendor --}}

                                    <td>

                                        @if($withdrawal->vendor)

                                            <div class="fw-semibold">

                                                {{ $withdrawal->vendor->name ?? 'Vendor' }}

                                            </div>

                                            @if(
                                                isset($withdrawal->vendor->user) &&
                                                $withdrawal->vendor->user
                                            )

                                                <small class="text-muted">

                                                    {{ $withdrawal->vendor->user->name }}

                                                </small>

                                            @endif

                                        @else

                                            <span class="text-muted">
                                                Vendor not found
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Amount --}}

                                    <td>

                                        <span class="fw-bold">

                                            ৳{{ number_format($withdrawal->amount, 2) }}

                                        </span>

                                    </td>


                                    {{-- Method --}}

                                    <td>

                                        @if($withdrawal->method)

                                            <span class="badge bg-light text-dark border">

                                                {{ ucfirst($withdrawal->method) }}

                                            </span>

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Account --}}

                                    <td>

                                        <span class="text-muted">

                                            {{ $withdrawal->account_details ?? '—' }}

                                        </span>

                                    </td>


                                    {{-- Status --}}

                                    <td>

                                        @if($withdrawal->status === 'pending')

                                            <span class="badge bg-warning-subtle text-warning">

                                                <i class="bi bi-clock me-1"></i>

                                                Pending

                                            </span>

                                        @elseif($withdrawal->status === 'approved')

                                            <span class="badge bg-success-subtle text-success">

                                                <i class="bi bi-check-circle me-1"></i>

                                                Approved

                                            </span>

                                        @elseif($withdrawal->status === 'rejected')

                                            <span class="badge bg-danger-subtle text-danger">

                                                <i class="bi bi-x-circle me-1"></i>

                                                Rejected

                                            </span>

                                        @else

                                            <span class="badge bg-secondary">

                                                {{ ucfirst($withdrawal->status) }}

                                            </span>

                                        @endif

                                    </td>


                                    {{-- Date --}}

                                    <td>

                                        <div class="fw-semibold">

                                            {{ $withdrawal->created_at->format('d M Y') }}

                                        </div>

                                        <small class="text-muted">

                                            {{ $withdrawal->created_at->format('h:i A') }}

                                        </small>

                                    </td>


                                    {{-- Action --}}

                                    <td class="text-end px-4">

                                        <a
                                            href="{{ route('admin.withdrawals.show', $withdrawal->id) }}"
                                            class="btn btn-sm btn-outline-primary"
                                        >

                                            <i class="bi bi-eye me-1"></i>

                                            View

                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <i class="bi bi-wallet2 fs-1 text-muted"></i>

                    <h6 class="fw-bold mt-3">
                        No withdrawal requests
                    </h6>

                    <p class="text-muted mb-0">
                        Vendor withdrawal requests will appear here.
                    </p>

                </div>

            @endif

        </div>


        {{-- Pagination --}}

        @if($withdrawals->hasPages())

            <div class="card-footer bg-white border-0 py-3">

                {{ $withdrawals->links() }}

            </div>

        @endif

    </div>

</div>

@endsection