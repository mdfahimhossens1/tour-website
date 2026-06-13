@extends('layouts.admin')

@section('page')

<div class="row mb-3">

    <div class="col-md-3">
        <div class="card p-3">
            <h6>Balance</h6>
            <h3>৳ {{ $wallet->balance }}</h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card p-3">
            <h6>Pending</h6>
            <h3>৳ {{ $wallet->pending_balance }}</h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card p-3">
            <h6>Total Earned</h6>
            <h3>৳ {{ $wallet->total_earned }}</h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card p-3">
            <h6>Withdrawn</h6>
            <h3>৳ {{ $wallet->total_withdrawn }}</h3>
        </div>
    </div>

</div>

<div class="card">
    <div class="card-header">
        <h5>Wallet Transactions</h5>
    </div>

    <div class="card-body">

        <table class="table table-bordered">

            <thead>
                <tr>
                    <th>Date</th>
                    <th>Booking ID</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Note</th>
                </tr>
            </thead>

            <tbody>
                @forelse($transactions as $t)
                <tr>
                    <td>{{ $t->created_at->format('d M Y') }}</td>
                    <td>#{{ $t->booking_id }}</td>
                    <td>
                        <span class="badge bg-{{ $t->type == 'credit' ? 'success' : 'danger' }}">
                            {{ $t->type }}
                        </span>
                    </td>
                    <td>৳ {{ $t->amount }}</td>
                    <td>{{ $t->status }}</td>
                    <td>{{ $t->note }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">No transactions found</td>
                </tr>
                @endforelse
            </tbody>

        </table>

        {{ $transactions->links() }}

    </div>
</div>

@endsection