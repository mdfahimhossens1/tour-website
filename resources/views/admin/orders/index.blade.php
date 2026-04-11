@extends('layouts.admin')
@section('page')

@php
  $type = $type ?? 'all';
  $btn = fn($key) => $type === $key ? 'btn btn-dark btn-sm' : 'btn btn-outline-dark btn-sm';
@endphp

<div class="row">
  <div class="col-12">
    <div class="card mb-3">

      <div class="card-header">
        <div class="row align-items-center g-2">
          <div class="col-md-6 col-12 card_title_part">
            <i class="fas fa-shopping-bag me-2"></i>
            @if($type==='pending') Pending Orders
            @elseif($type==='completed') Completed Orders
            @else All Orders
            @endif
          </div>

          <div class="col-md-6 col-12 card_button_part text-md-end">
            <div class="d-flex flex-wrap gap-2 justify-content-md-end">
              <a href="{{ route('dashboard.orders.index') }}" class="{{ $btn('all') }}">All Orders</a>
              <a href="{{ route('dashboard.orders.pending') }}" class="{{ $btn('pending') }}">Pending</a>
              <a href="{{ route('dashboard.orders.completed') }}" class="{{ $btn('completed') }}">Completed</a>
            </div>
          </div>
        </div>
      </div>

      <div class="card-body">
        @if(session('success'))
          <div class="alert alert-success mb-3">{{ session('success') }}</div>
        @endif
        @if(session('error'))
          <div class="alert alert-danger mb-3">{{ session('error') }}</div>
        @endif

        <div class="">
          <table id="myTable" class="table table-bordered table-striped table-hover align-middle mb-0 custom_table">
            <thead class="table-dark">
              <tr>
                <th>Order No</th>
                <th>Customer</th>
                <th>Grand Total</th>
                <th>Payment</th>
                <th>Status</th>
                <th>Date</th>
                <th>Action</th>
              </tr>
            </thead>

            <tbody>
              @foreach($orders as $o)
                <tr>
                  <td>
                    <div class="fw-semibold">{{ $o->order_number }}</div>
                    <div class="text-muted small">ID: {{ $o->id }}</div>
                  </td>

                  <td>
                    <div class="fw-semibold">{{ $o->customer_name ?? '—' }}</div>
                    <div class="text-muted small">
                      {{ $o->customer_phone ?? '' }} {{ $o->customer_email ? '• '.$o->customer_email : '' }}
                    </div>
                  </td>

                  <td>৳ {{ number_format((float)$o->grand_total, 2) }}</td>

                  <td>
                    <div class="small text-muted">{{ $o->payment_method ?? '—' }}</div>
                    @php $pay = strtolower($o->payment_status ?? ''); @endphp
                    @if($pay==='paid')
                      <span class="badge bg-success">Paid</span>
                    @elseif($pay==='unpaid')
                      <span class="badge bg-warning text-dark">Unpaid</span>
                    @else
                      <span class="badge bg-secondary">{{ $o->payment_status ?? 'N/A' }}</span>
                    @endif
                  </td>

                  <td>
                    @php $st = strtolower($o->status ?? ''); @endphp
                    @if($st==='pending')
                      <span class="badge bg-warning text-dark">Pending</span>
                    @elseif($st==='completed')
                      <span class="badge bg-success">Completed</span>
                    @elseif($st==='processing')
                      <span class="badge bg-primary">Processing</span>
                    @elseif($st==='cancelled')
                      <span class="badge bg-danger">Cancelled</span>
                    @else
                      <span class="badge bg-secondary">{{ $o->status ?? 'N/A' }}</span>
                    @endif
                  </td>

                  <td>{{ optional($o->created_at)->format('d M Y, h:i A') }}</td>

                  <td>
                    <a href="{{ route('dashboard.orders.show', $o->order_number) }}" class="btn btn-sm btn-outline-primary">
                      View
                    </a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <div class="mt-3">
          {{ $orders->links() }}
        </div>
      </div>

    </div>
  </div>
</div>

@endsection
