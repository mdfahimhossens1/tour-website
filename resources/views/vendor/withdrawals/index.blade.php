@extends('layouts.admin')

@section('page')

<h3>Withdraw Money</h3>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

{{-- WITHDRAW FORM --}}
<div class="card mb-4">
    <div class="card-body">

        <form method="POST" action="{{ route('vendor.withdrawals.store') }}">
            @csrf

            <div class="mb-3">
                <label>Amount</label>
                <input type="number" name="amount" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Method</label>
                <select name="method" class="form-control">
                    <option value="bkash">bKash</option>
                    <option value="bank">Bank</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Account Number</label>
                <input type="text" name="account_number" class="form-control">
            </div>

            <button class="btn btn-primary">Request Withdraw</button>
        </form>

    </div>
</div>

{{-- HISTORY --}}
<div class="card">
    <div class="card-header">
        <h5>Withdrawal History</h5>
    </div>

    <div class="card-body">

        <table class="table">
            <thead>
                <tr>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>

            <tbody>
                @forelse($withdrawals as $w)
                <tr>
                    <td>৳ {{ $w->amount }}</td>
                    <td>{{ $w->method }}</td>
                    <td>
                        <span class="badge bg-warning">{{ $w->status }}</span>
                    </td>
                    <td>{{ $w->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4">No withdrawals yet</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{ $withdrawals->links() }}

    </div>
</div>

@endsection