@extends('layouts.admin')
@section('title','Booking Reports')
@section('page')

<div class="card">

    {{-- HEADER --}}
    <div class="card-header d-flex justify-content-between align-items-center">

        <h5 class="mb-0">
            Booking Reports
        </h5>

    </div>

    <div class="card-body">

        {{-- FILTER SECTION --}}
        <form method="GET" class="row mb-3">

            {{-- Status --}}
            <div class="col-md-3">
                <label>Status</label>

                <select name="status" class="form-control">
                    <option value="">All</option>
                    <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
                    <option value="confirmed" {{ request('status')=='confirmed'?'selected':'' }}>Confirmed</option>
                    <option value="cancelled" {{ request('status')=='cancelled'?'selected':'' }}>Cancelled</option>
                    <option value="completed" {{ request('status')=='completed'?'selected':'' }}>Completed</option>
                </select>
            </div>

            {{-- FROM DATE --}}
            <div class="col-md-3">
                <label>From</label>
                <input type="date" name="from" class="form-control"
                       value="{{ request('from') }}">
            </div>

            {{-- TO DATE --}}
            <div class="col-md-3">
                <label>To</label>
                <input type="date" name="to" class="form-control"
                       value="{{ request('to') }}">
            </div>

            {{-- BUTTON --}}
            <div class="col-md-3 d-flex align-items-end">
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
                        <th>Status</th>
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
                                @if($booking->booking_status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($booking->booking_status == 'confirmed')
                                    <span class="badge bg-success">Confirmed</span>
                                @elseif($booking->booking_status == 'cancelled')
                                    <span class="badge bg-danger">Cancelled</span>
                                @else
                                    <span class="badge bg-secondary">
                                        {{ ucfirst($booking->booking_status) }}
                                    </span>
                                @endif
                            </td>

                            <td>
                                {{ $booking->created_at->format('d M Y') }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="8" class="text-center text-muted">
                                No Booking Found
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection