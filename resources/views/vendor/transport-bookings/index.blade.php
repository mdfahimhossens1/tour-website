@extends('layouts.vendor')

@section('title', 'Transport Bookings')

@section('page')

<div class="container-fluid">

{{-- =========================================================
    HEADER
========================================================== --}}
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

    <div>
        <h4 class="mb-1">
            <i class="fas fa-car me-2"></i>
            Transport Bookings
        </h4>

        <p class="text-muted mb-0">
            Manage your vehicle transport bookings.
        </p>
    </div>

</div>


{{-- =========================================================
    SUCCESS MESSAGE
========================================================== --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert">
        </button>
    </div>
@endif


{{-- =========================================================
    ERROR MESSAGE
========================================================== --}}
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle me-2"></i>
        {{ session('error') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert">
        </button>
    </div>
@endif


{{-- =========================================================
    VALIDATION ERRORS
========================================================== --}}
@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


{{-- =========================================================
    BOOKINGS CARD
========================================================== --}}
<div class="card border-0 shadow-sm">

    {{-- Card Header --}}
    <div class="card-header bg-white border-bottom">

        <div class="row align-items-center">

            <div class="col-md-6">
                <h5 class="mb-0">
                    All Transport Bookings
                </h5>
            </div>

            <div class="col-md-6">

                {{-- Search --}}
                <form
                    action="{{ route('vendor.transport-bookings.index') }}"
                    method="GET"
                    class="d-flex justify-content-md-end mt-3 mt-md-0">

                    <div class="input-group" style="max-width: 350px;">

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Search booking..."
                            value="{{ request('search') }}">

                        <button
                            type="submit"
                            class="btn btn-primary">

                            <i class="fas fa-search"></i>

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>


    {{-- =====================================================
        TABLE
    ====================================================== --}}
    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>
                        <th class="px-3">#</th>
                        <th>Booking</th>
                        <th>Customer</th>
                        <th>Vehicle</th>
                        <th>Journey</th>
                        <th>Passengers</th>
                        <th>Amount</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th class="text-end px-3">Action</th>
                    </tr>

                </thead>


                <tbody>

                @forelse($bookings as $booking)

                    {{-- =================================================
                        GET PENDING PAYMENT FOR THIS BOOKING
                        গুরুত্বপূর্ণ: Undefined variable fix
                    ================================================== --}}
                    @php
                        $pendingPayment = null;

                        if ($booking->relationLoaded('payments')) {
                            $pendingPayment = $booking->payments
                                ->where('status', 'pending')
                                ->sortByDesc('id')
                                ->first();
                        }
                    @endphp

                    <tr>

                        {{-- ID --}}
                        <td class="px-3">
                            <span class="fw-semibold">
                                {{ $bookings->firstItem() + $loop->index }}
                            </span>
                        </td>


                        {{-- BOOKING --}}
                        <td>
                            <div class="fw-semibold">
                                {{ $booking->booking_code }}
                            </div>

                            <small class="text-muted">
                                {{ optional($booking->created_at)->format('d M Y') }}
                            </small>
                        </td>


                        {{-- CUSTOMER --}}
                        <td>

                            @if($booking->user)

                                <div class="fw-semibold">
                                    {{ $booking->user->name }}
                                </div>

                                @if($booking->user->email)
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


                        {{-- VEHICLE --}}
                        <td>

                            @if($booking->vehicle)

                                <div class="fw-semibold">
                                    {{ $booking->vehicle->name }}
                                </div>

                                <small class="text-muted">
                                    {{ $booking->vehicle->registration_number }}
                                </small>

                            @else

                                <span class="text-muted">
                                    Vehicle unavailable
                                </span>

                            @endif

                        </td>


                        {{-- JOURNEY --}}
                        <td>

                            <div>

                                <span class="fw-semibold">
                                    {{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }}
                                </span>

                                <span class="text-muted">
                                    →
                                </span>

                                <span class="fw-semibold">
                                    {{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}
                                </span>

                            </div>

                            <small class="text-muted">
                                {{ $booking->total_days }}
                                {{ $booking->total_days == 1 ? 'day' : 'days' }}
                            </small>

                        </td>


                        {{-- PASSENGERS --}}
                        <td>

                            <i class="fas fa-users me-1 text-muted"></i>
                            {{ $booking->passengers }}

                        </td>


                        {{-- AMOUNT --}}
                        <td>

                            <div class="fw-bold">
                                {{ number_format((float) $booking->total_amount, 2) }}
                            </div>

                            <small class="text-muted">
                                / day
                                {{ number_format((float) $booking->price_per_day, 2) }}
                            </small>

                        </td>


                        {{-- PAYMENT STATUS --}}
                        <td>

                            @php
                                $paymentClass = match($booking->payment_status) {
                                    'paid' => 'bg-success',
                                    'failed' => 'bg-danger',
                                    'refunded' => 'bg-secondary',
                                    default => 'bg-warning text-dark',
                                };
                            @endphp

                            <span class="badge {{ $paymentClass }}">
                                {{ ucfirst($booking->payment_status) }}
                            </span>

                        </td>


                        {{-- BOOKING STATUS --}}
                        <td>

                            @php
                                $statusClass = match($booking->booking_status) {
                                    'confirmed' => 'bg-success',
                                    'completed' => 'bg-primary',
                                    'cancelled' => 'bg-danger',
                                    default => 'bg-warning text-dark',
                                };
                            @endphp

                            <span class="badge {{ $statusClass }}">
                                {{ ucfirst($booking->booking_status) }}
                            </span>

                        </td>


                        {{-- =================================================
                            ACTIONS
                        ================================================== --}}
                        <td class="text-end px-3">

                            <div class="dropdown">

                                <button
                                    class="btn btn-sm btn-light border dropdown-toggle"
                                    type="button"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false">

                                    <i class="fas fa-ellipsis-v"></i>

                                </button>


                                <ul class="dropdown-menu dropdown-menu-end">


                                    {{-- SHOW --}}
                                    <li>
                                        <a
                                            href="{{ route('vendor.transport-bookings.show', $booking) }}"
                                            class="dropdown-item">

                                            <i class="fas fa-eye me-2 text-primary"></i>
                                            View

                                        </a>
                                    </li>



                                    {{-- =================================================
                                        PAYMENT ACTIONS
                                    ================================================== --}}
                                    @if(
                                        $booking->payment_status === 'pending'
                                        && $pendingPayment
                                    )

                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>


                                        {{-- APPROVE PAYMENT --}}
                                        <li>

                                            <form
                                                action="{{ route('vendor.transport-bookings.payments.approve', [
                                                    'booking' => $booking->id,
                                                    'payment' => $pendingPayment->id,
                                                ]) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to approve this payment?')">

                                                @csrf

                                                <button
                                                    type="submit"
                                                    class="dropdown-item text-success">

                                                    <i class="fas fa-check-circle me-2"></i>
                                                    Approve Payment

                                                </button>

                                            </form>

                                        </li>


                                        {{-- REJECT PAYMENT --}}
                                        <li>

                                            <form
                                                action="{{ route('vendor.transport-bookings.payments.reject', [
                                                    'booking' => $booking->id,
                                                    'payment' => $pendingPayment->id,
                                                ]) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to reject this payment?')">

                                                @csrf

                                                <button
                                                    type="submit"
                                                    class="dropdown-item text-danger">

                                                    <i class="fas fa-times-circle me-2"></i>
                                                    Reject Payment

                                                </button>

                                            </form>

                                        </li>

                                    @endif


                                    {{-- CONFIRM BOOKING --}}
                                    @if(
                                        $booking->booking_status === 'pending' &&
                                        $booking->payment_status === 'paid'
                                    )

                                        <li>

                                            <form
                                                action="{{ route('vendor.transport-bookings.confirm', $booking) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to confirm this booking?')">

                                                @csrf

                                                <button
                                                    type="submit"
                                                    class="dropdown-item text-success">

                                                    <i class="fas fa-calendar-check me-2"></i>
                                                    Confirm Booking

                                                </button>

                                            </form>

                                        </li>

                                    @endif


                                    {{-- CANCEL BOOKING --}}
                                    @if(
                                        !in_array(
                                            $booking->booking_status,
                                            ['cancelled', 'completed']
                                        )
                                    )

                                        <li>

                                            <form
                                                action="{{ route('vendor.transport-bookings.cancel', $booking) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to cancel this booking?')">

                                                @csrf

                                                <button
                                                    type="submit"
                                                    class="dropdown-item text-danger">

                                                    <i class="fas fa-ban me-2"></i>
                                                    Cancel Booking

                                                </button>

                                            </form>

                                        </li>

                                    @endif


                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>


                                    {{-- DELETE --}}
                                    <li>

                                        <form
                                            action="{{ route('vendor.transport-bookings.destroy', $booking) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this transport booking?')">

                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="dropdown-item text-danger">

                                                <i class="fas fa-trash me-2"></i>
                                                Delete

                                            </button>

                                        </form>

                                    </li>

                                </ul>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="10" class="text-center py-5">

                            <div class="text-muted">

                                <i class="fas fa-car fa-3x mb-3 opacity-50"></i>

                                <h6>
                                    No transport bookings found
                                </h6>

                                <p class="mb-0">
                                    There are no transport bookings for your vehicles yet.
                                </p>

                            </div>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- =====================================================
        PAGINATION
    ====================================================== --}}
    @if($bookings->hasPages())

        <div class="card-footer bg-white">

            <div class="d-flex justify-content-between align-items-center flex-wrap">

                <div class="text-muted small">

                    Showing
                    {{ $bookings->firstItem() }}
                    to
                    {{ $bookings->lastItem() }}
                    of
                    {{ $bookings->total() }}
                    bookings

                </div>

                <div>
                    {{ $bookings->withQueryString()->links() }}
                </div>

            </div>

        </div>

    @endif

</div>

</div>

@endsection
