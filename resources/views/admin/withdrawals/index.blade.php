@extends('layouts.admin')

@section('page')

<h3>Withdrawal Requests</h3>

<table class="table">
    <thead>
        <tr>
            <th>Vendor</th>
            <th>Amount</th>
            <th>Method</th>
            <th>Account</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>
        @foreach($withdrawals as $w)
        <tr>
            <td>{{ $w->vendor->user->name ?? '' }}</td>
            <td>৳ {{ $w->amount }}</td>
            <td>{{ $w->method }}</td>
            <td>{{ $w->account_number }}</td>
            <td>
                <span class="badge bg-warning">{{ $w->status }}</span>
            </td>
            <td>

                @if($w->status == 'pending')

                <form action="{{ route('admin.withdrawals.approve', $w->id) }}" method="POST" style="display:inline;">
                    @csrf
                    <button class="btn btn-success btn-sm">Approve</button>
                </form>

                <form action="{{ route('admin.withdrawals.reject', $w->id) }}" method="POST" style="display:inline;">
                    @csrf
                    <button class="btn btn-danger btn-sm">Reject</button>
                </form>

                @endif

            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $withdrawals->links() }}

@endsection