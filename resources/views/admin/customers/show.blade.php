@extends('layouts.admin')
@section('page')

<div class="row">
  <div class="col-12">
    <div class="card mb-3">

      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-8 col-8 card_title_part">
            <i class="fas fa-user me-2"></i> Customer Details
          </div>
          <div class="col-md-4 col-4 card_button_part text-end">
            <a href="{{ route('dashboard.customers.index') }}" class="btn btn-sm btn-dark">
              Back
            </a>
          </div>
        </div>
      </div>

      <div class="card-body">
        <div class="row g-3">
          <div class="col-md-5">
            <div class="border rounded p-3 h-100">
              <div class="fw-bold mb-2">{{ $customer->name ?? '—' }}</div>
              <div><span class="text-muted">Email:</span> {{ $customer->email ?? '—' }}</div>
              <div><span class="text-muted">Phone:</span> {{ $customer->phone ?? '—' }}</div>
              <div><span class="text-muted">Role:</span> {{ $customer->role->role_name ?? '—' }}</div>
            </div>
          </div>

          <div class="col-md-7">
            <div class="border rounded p-3 h-100">
              <div class="fw-bold mb-2">Orders</div>

              <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle mb-0 custom_table">
                  <thead class="table-dark">
                    <tr>
                      <th>Order No</th>
                      <th>Total</th>
                      <th>Payment</th>
                      <th>Status</th>
                      <th>Date</th>
                    </tr>
                  </thead>

                  <tbody>
                    @forelse($orders as $o)
                      <tr>
                        <td>
                          <a href="{{ route('dashboard.orders.show', $o->order_number) }}" class="text-decoration-none">
                            {{ $o->order_number }}
                          </a>
                        </td>
                        <td>৳ {{ number_format((float)$o->grand_total, 2) }}</td>
                        <td>{{ $o->payment_status }}</td>
                        <td>{{ $o->status }}</td>
                        <td>{{ optional($o->created_at)->format('d M Y, h:i A') }}</td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="5" class="text-center">No orders found.</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>

              <div class="mt-2">
                {{ $orders->links() }}
              </div>

            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

@endsection
