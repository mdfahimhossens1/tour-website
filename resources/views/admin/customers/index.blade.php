@extends('layouts.admin')
@section('page')

<div class="row">
  <div class="col-12">
    <div class="card mb-3">

      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-8 col-8 card_title_part">
            <i class="fas fa-users me-2"></i> Customers
          </div>
          <div class="col-md-4 col-4 card_button_part text-end">
            {{-- No button needed here --}}
          </div>
        </div>
      </div>

      <div class="card-body">

        <div class="">
          <table id="myTable" class="table table-bordered table-striped table-hover align-middle mb-0 custom_table">
            <thead class="table-dark">
              <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Orders</th>
                <th>Paid Total</th>
                <th>Action</th>
              </tr>
            </thead>

            <tbody>
              @forelse($customers as $c)
                <tr>
                  <td>{{ $c->id }}</td>

                  <td>
                    <div class="fw-semibold">{{ $c->name ?? '—' }}</div>
                    <div class="text-muted small">
                      {{ $c->email ?? '' }} {{ $c->phone ? '• '.$c->phone : '' }}
                    </div>
                  </td>

                  <td>
                    <span class="badge bg-primary">{{ $c->orders_count ?? 0 }}</span>
                  </td>

                  <td>
                    ৳ {{ number_format((float)($c->orders_paid_sum ?? 0), 2) }}
                  </td>

                  <td>
                    <a href="{{ route('dashboard.customers.show', $c->id) }}" class="btn btn-sm btn-outline-primary">
                      View
                    </a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center">No customers found.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <div class="mt-3">
          {{ $customers->links() }}
        </div>

      </div>
    </div>
  </div>
</div>

@endsection
