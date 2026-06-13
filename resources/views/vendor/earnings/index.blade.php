@extends('layouts.admin')

@section('page')

<div class="container-fluid">

    <div class="row">

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h6>Total Earned</h6>
                    <h3>
                        ৳ {{ number_format($totalEarned ?? 0, 2) }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h6>Pending Balance</h6>
                    <h3>
                        ৳ {{ number_format($pendingBalance ?? 0, 2) }}
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h6>Available Balance</h6>
                    <h3>
                        ৳ {{ number_format($availableBalance ?? 0, 2) }}
                    </h3>
                </div>
            </div>
        </div>

    </div>

    <div class="card mt-4">

        <div class="card-header">
            <h5>Earning History</h5>
        </div>

        <div class="card-body">

            <table class="table table-bordered">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Booking</th>
                        <th>Tour</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($commissions ?? [] as $commission)

                    <tr>

                        <td>{{ $commission->id }}</td>

                        <td>
                            #{{ $commission->booking_id }}
                        </td>

                        <td>
                            {{ optional($commission->booking->tour)->title }}
                        </td>

                        <td>
                            ৳ {{ number_format($commission->vendor_earning, 2) }}
                        </td>

                        <td>
                            <span class="badge bg-success">
                                Paid
                            </span>
                        </td>

                        <td>
                            {{ $commission->created_at->format('d M Y') }}
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="text-center">
                            No earnings found
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection