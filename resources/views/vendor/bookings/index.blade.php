@extends('layouts.vendor')

@section('page')

<div class="container-fluid py-4">

{{-- =========================================================
    HEADER
========================================================== --}}
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

    <div>
        <h4 class="fw-bold mb-1">
            My Bookings
        </h4>

        <p class="text-muted mb-0">
            Manage and monitor all bookings related to your tours.
        </p>
    </div>

</div>


{{-- =========================================================
    SUCCESS MESSAGE
========================================================== --}}
@if(session('success'))

    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">

        <i class="bi bi-check-circle me-1"></i>

        {{ session('success') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
        ></button>

    </div>

@endif


{{-- =========================================================
    ERROR MESSAGE
========================================================== --}}
@if(session('error'))

    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm">

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
    BOOKING TABLE
========================================================== --}}
<div class="card border-0 shadow-sm">

    <div class="card-header bg-white border-0 py-3">

        <div class="d-flex flex-wrap justify-content-between align-items-center">

            <div>

                <h5 class="fw-bold mb-1">
                    Booking List
                </h5>

                <small class="text-muted">
                    View and manage your customer bookings.
                </small>

            </div>


            <div>

                <span class="badge bg-light text-dark border">

                    Total:
                    {{ $bookings->total() }}

                </span>

            </div>

        </div>

    </div>


    <div class="card-body p-0">

        @if($bookings->count())

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="px-3">
                                Booking
                            </th>

                            <th>
                                Tour
                            </th>

                            <th>
                                Customer
                            </th>

                            <th>
                                Date
                            </th>

                            <th>
                                Guests
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

                            <th class="text-end px-3">
                                Action
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($bookings as $booking)

                            <tr>

                                {{-- Booking --}}
                                <td class="px-3">

                                    <div class="fw-semibold">
                                        #{{ $booking->id }}
                                    </div>

                                    <small class="text-muted">
                                        {{ $booking->created_at?->format('d M Y') }}
                                    </small>

                                </td>


                                {{-- Tour --}}
                                <td>

                                    @if($booking->tour)

                                        <div class="fw-semibold">
                                            {{ $booking->tour->title }}
                                        </div>

                                        @if($booking->tour->slug ?? false)

                                            <small class="text-muted">
                                                {{ $booking->tour->slug }}
                                            </small>

                                        @endif

                                    @else

                                        <span class="text-muted">
                                            Tour unavailable
                                        </span>

                                    @endif

                                </td>


                                {{-- Customer --}}
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
                                            Customer unavailable
                                        </span>

                                    @endif

                                </td>


                                {{-- Booking Date --}}
                                <td>

                                    @if($booking->tourDate)

                                        <span>
                                            {{ \Carbon\Carbon::parse($booking->tourDate->date ?? $booking->tourDate->tour_date ?? $booking->tourDate->created_at)->format('d M Y') }}
                                        </span>

                                    @elseif($booking->booking_date ?? false)

                                        <span>
                                            {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}
                                        </span>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- Guests --}}
                                <td>

                                    <span class="badge bg-light text-dark border">

                                        <i class="bi bi-people me-1"></i>

                                        {{ $booking->person_count ?? 0 }}

                                    </span>

                                </td>


                                {{-- Amount --}}
                                <td>

                                    @if(isset($booking->total_amount))

                                        <span class="fw-semibold">

                                            ৳{{ number_format($booking->total_amount, 2) }}

                                        </span>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- Payment Status --}}
                                <td>

                                    @php

                                        $paymentStatus = $booking->payment_status ?? 'pending';

                                        $paymentClass = match($paymentStatus) {

                                            'paid',
                                            'completed',
                                            'success'
                                                => 'bg-success',

                                            'failed',
                                            'cancelled'
                                                => 'bg-danger',

                                            'refunded'
                                                => 'bg-warning text-dark',

                                            default
                                                => 'bg-secondary',

                                        };

                                    @endphp


                                    <span class="badge {{ $paymentClass }}">

                                        {{ ucfirst($paymentStatus) }}

                                    </span>

                                </td>


                                {{-- Booking Status --}}
                                <td>

                                    @php

                                        $bookingStatus = $booking->booking_status ?? 'pending';

                                        $statusClass = match($bookingStatus) {

                                            'confirmed'
                                                => 'bg-success',

                                            'cancelled'
                                                => 'bg-danger',

                                            'completed'
                                                => 'bg-primary',

                                            'pending'
                                                => 'bg-warning text-dark',

                                            default
                                                => 'bg-secondary',

                                        };

                                    @endphp


                                    <span class="badge {{ $statusClass }}">

                                        {{ ucfirst($bookingStatus) }}

                                    </span>

                                </td>


                                {{-- Action --}}
                                <td class="text-end px-3">

                                    <a
                                        href="{{ route('vendor.bookings.show', $booking->id) }}"
                                        class="btn btn-sm btn-outline-primary"
                                        title="View Booking"
                                    >

                                        <i class="bi bi-eye"></i>

                                        View

                                    </a>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>


            {{-- =================================================
                PAGINATION
            ================================================== --}}
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 px-3 py-3">

                <div class="text-muted small">

                    Showing
                    <strong>{{ $bookings->firstItem() }}</strong>
                    to
                    <strong>{{ $bookings->lastItem() }}</strong>
                    of
                    <strong>{{ $bookings->total() }}</strong>
                    bookings

                </div>


                <div>

                    {{ $bookings->links() }}

                </div>

            </div>

        @else

            {{-- =================================================
                EMPTY STATE
            ================================================== --}}
            <div class="text-center py-5">

                <div class="mb-3">

                    <i
                        class="bi bi-calendar-x text-muted"
                        style="font-size: 3rem;"
                    ></i>

                </div>


                <h5 class="fw-bold">
                    No Bookings Found
                </h5>


                <p class="text-muted mb-0">

                    You don't have any bookings for your tours yet.

                </p>

            </div>

        @endif

    </div>

</div>
</div>

@endsection
