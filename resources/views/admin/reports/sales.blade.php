@extends('layouts.admin')
@section('page')

<div class="card mb-3">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Sales Report</h5>
  </div>

  <div class="card-body">
    <form class="row g-2" method="GET" action="{{ route('dashboard.reports.sales') }}">
      <div class="col-md-3">
        <label class="form-label">From</label>
        <input type="date" name="from" value="{{ request('from', \Carbon\Carbon::parse($from)->toDateString()) }}" class="form-control">
      </div>
      <div class="col-md-3">
        <label class="form-label">To</label>
        <input type="date" name="to" value="{{ request('to', \Carbon\Carbon::parse($to)->toDateString()) }}" class="form-control">
      </div>

      <div class="col-md-2">
        <label class="form-label">Status</label>
        <select name="status" class="form-control">
          <option value="">Paid/Completed (default)</option>
          @foreach(['paid','completed','pending','cancelled'] as $st)
            <option value="{{ $st }}" {{ request('status')===$st?'selected':'' }}>{{ ucfirst($st) }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-md-2">
        <label class="form-label">Payment Status</label>
        <select name="payment_status" class="form-control">
          <option value="">All</option>
          @foreach(['paid','unpaid','pending','failed'] as $ps)
            <option value="{{ $ps }}" {{ request('payment_status')===$ps?'selected':'' }}>{{ ucfirst($ps) }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-md-2">
        <label class="form-label">Payment Method</label>
        <select name="payment_method" class="form-control">
          <option value="">All</option>
          @foreach($methods as $m)
            <option value="{{ $m }}" {{ request('payment_method')===$m?'selected':'' }}>{{ $m }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-md-12 d-flex gap-2 mt-2">
        <button class="btn btn-dark">Apply Filter</button>
        <a href="{{ route('dashboard.reports.sales') }}" class="btn btn-outline-secondary">Reset</a>
      </div>
    </form>
  </div>
</div>

<div class="row g-3 mb-3">
  <div class="col-md-3">
    <div class="card p-3">
      <div class="small text-muted">Revenue</div>
      <div class="h4 mb-0">{{ number_format($kpi->revenue, 2) }}</div>
      <div class="small text-muted mt-1">Subtotal: {{ number_format($kpi->subtotal_total, 2) }}</div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card p-3">
      <div class="small text-muted">Total Orders</div>
      <div class="h4 mb-0">{{ $kpi->total_orders }}</div>
      <div class="small text-muted mt-1">Avg: {{ number_format($kpi->avg_order_value, 2) }}</div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card p-3">
      <div class="small text-muted">Items Sold</div>
      <div class="h4 mb-0">{{ $totalItemsSold }}</div>
      <div class="small text-muted mt-1">Tax: {{ number_format($kpi->tax_total, 2) }}</div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card p-3">
      <div class="small text-muted">Discount / Shipping</div>
      <div class="h6 mb-1">Discount: {{ number_format($kpi->discount_total, 2) }}</div>
      <div class="h6 mb-0">Shipping: {{ number_format($kpi->shipping_total, 2) }}</div>
    </div>
  </div>
</div>

<div class="card mb-3">
  <div class="card-header"><b>Sales Overview Chart</b></div>
  <div class="card-body">
    <canvas id="salesChart" height="100"></canvas>
  </div>
</div>


<div class="row g-3">
  <div class="col-lg-7">
    <div class="card">
      <div class="card-header"><b>Daily Sales</b></div>
      <div class="card-body table-responsive">
        <table class="table table-bordered table-sm mb-0">
          <thead class="table-dark">
            <tr>
              <th>Date</th>
              <th>Orders</th>
              <th>Revenue</th>
            </tr>
          </thead>
          <tbody>
            @forelse($daily as $d)
              <tr>
                <td>{{ \Carbon\Carbon::parse($d->day)->format('d M Y') }}</td>
                <td>{{ $d->orders }}</td>
                <td>{{ number_format($d->revenue, 2) }}</td>
              </tr>
            @empty
              <tr><td colspan="3" class="text-center">No data</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="col-lg-5">
    <div class="card">
      <div class="card-header"><b>Top Selling Products</b></div>
      <div class="card-body table-responsive">
        <table class="table table-bordered table-sm mb-0">
          <thead class="table-dark">
            <tr>
              <th>Product</th>
              <th>Sold</th>
              <th>Sales</th>
            </tr>
          </thead>
          <tbody>
            @forelse($topProducts as $p)
              <tr>
                <td>
                  <div class="fw-semibold">{{ $p->product_name }}</div>
                  <div class="small text-muted">{{ $p->sku }}</div>
                </td>
                <td>{{ $p->sold_qty }}</td>
                <td>{{ number_format($p->sales_total, 2) }}</td>
              </tr>
            @empty
              <tr><td colspan="3" class="text-center">No data</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="card mt-3">
  <div class="card-header"><b>Recent Orders</b></div>
  <div class="card-body table-responsive">
    <table class="table table-bordered table-sm mb-0">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Order</th>
          <th>Customer</th>
          <th>Total</th>
          <th>Status</th>
          <th>Payment</th>
          <th>Method</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
        @forelse($recentOrders as $o)
          <tr>
            <td>{{ $o->id }}</td>
            <td>{{ $o->order_number }}</td>
            <td>{{ $o->customer_name }}</td>
            <td>{{ number_format($o->grand_total, 2) }}</td>
            <td><span class="badge bg-secondary">{{ $o->status }}</span></td>
            <td><span class="badge bg-info text-dark">{{ $o->payment_status }}</span></td>
            <td>{{ $o->payment_method }}</td>
            <td>{{ \Carbon\Carbon::parse($o->created_at)->format('d M Y') }}</td>
          </tr>
        @empty
          <tr><td colspan="8" class="text-center">No orders</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('salesChart').getContext('2d');

    const labels = @json($daily->pluck('day')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M')));
    const revenueData = @json($daily->pluck('revenue'));
    const ordersData = @json($daily->pluck('orders'));

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Revenue',
                    data: revenueData,
                    borderColor: '#111827',
                    backgroundColor: 'rgba(17,24,39,0.1)',
                    tension: 0.3,
                    fill: true
                },
                {
                    label: 'Orders',
                    data: ordersData,
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13,110,253,0.1)',
                    tension: 0.3,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

@endsection
