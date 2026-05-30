@extends('layouts.admin')

@section('title','Revenue Reports')

@section('page')

<div class="card">

    {{-- HEADER --}}
    <div class="card-header d-flex justify-content-between align-items-center">

        <h5 class="mb-0">
            Revenue Reports
        </h5>

        <h6>
            Total Revenue:
            <span class="text-success">
                ৳ {{ number_format($totalRevenue, 2) }}
            </span>
        </h6>

    </div>

    <div class="card-body">

        {{-- FILTER --}}
        <form method="GET" class="row mb-3">

            <div class="col-md-4">
                <label>From</label>
                <input type="date"
                       name="from"
                       class="form-control"
                       value="{{ request('from') }}">
            </div>

            <div class="col-md-4">
                <label>To</label>
                <input type="date"
                       name="to"
                       class="form-control"
                       value="{{ request('to') }}">
            </div>

            <div class="col-md-4 d-flex align-items-end">
                <button class="btn btn-dark w-100">
                    Filter
                </button>
            </div>

        </form>

        {{-- TABLE --}}
        <div class="table-responsive">

            <table class="table table-bordered table-hover">

                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Booking Code</th>
                        <th>User</th>
                        <th>Tour</th>
                        <th>Persons</th>
                        <th>Amount</th>
                        <th>Date</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($bookings as $booking)

                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>
                                {{ $booking->booking_code }}
                            </td>

                            <td>
                                {{ $booking->user->name ?? 'N/A' }}
                            </td>

                            <td>
                                {{ $booking->tour->title ?? 'N/A' }}
                            </td>

                            <td>
                                {{ $booking->person_count }}
                            </td>

                            <td>
                                ৳ {{ number_format($booking->total_amount, 2) }}
                            </td>

                            <td>
                                {{ $booking->created_at->format('d M Y') }}
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                No Revenue Data Found
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection