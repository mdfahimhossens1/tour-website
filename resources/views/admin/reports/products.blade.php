@extends('layouts.admin')
@section('page')

<div class="card mb-3">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Product Report</h5>
  </div>

  <div class="card-body">
    <form class="row g-2" method="GET" action="{{ route('dashboard.reports.products') }}">
      <div class="col-md-3">
        <label class="form-label">From</label>
        <input type="date" name="from" value="{{ request('from', \Carbon\Carbon::parse($from)->toDateString()) }}" class="form-control">
      </div>
      <div class="col-md-3">
        <label class="form-label">To</label>
        <input type="date" name="to" value="{{ request('to', \Carbon\Carbon::parse($to)->toDateString()) }}" class="form-control">
      </div>

      <div class="col-md-3">
        <label class="form-label">Order Status</label>
        <select name="status" class="form-control">
          <option value="">Paid/Completed (default)</option>
          @foreach(['paid','completed','pending','cancelled'] as $st)
            <option value="{{ $st }}" {{ request('status')===$st?'selected':'' }}>{{ ucfirst($st) }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-md-3">
        <label class="form-label">Payment Status</label>
        <select name="payment_status" class="form-control">
          <option value="">All</option>
          @foreach(['paid','unpaid','pending','failed'] as $ps)
            <option value="{{ $ps }}" {{ request('payment_status')===$ps?'selected':'' }}>{{ ucfirst($ps) }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-md-12 d-flex gap-2 mt-2">
        <button class="btn btn-dark">Apply Filter</button>
        <a href="{{ route('dashboard.reports.products') }}" class="btn btn-outline-secondary">Reset</a>
      </div>
    </form>
  </div>
</div>

<div class="row g-3 mb-3">
  <div class="col-md-3">
    <div class="card p-3">
      <div class="small text-muted">Total Products</div>
      <div class="h4 mb-0">{{ $totalProducts }}</div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card p-3">
      <div class="small text-muted">Active Products</div>
      <div class="h4 mb-0">{{ $activeProducts }}</div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card p-3">
      <div class="small text-muted">Out of Stock</div>
      <div class="h4 mb-0">{{ $outOfStock }}</div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="card p-3">
      <div class="small text-muted">Low Stock</div>
      <div class="h4 mb-0">{{ $lowStock }}</div>
    </div>
  </div>
</div>

<div class="card mb-3">
  <div class="card-header"><b>Top Products Chart</b></div>
  <div class="card-body">
    <canvas id="topProductsChart" height="110"></canvas>
  </div>
</div>


<div class="row g-3">
  <div class="col-lg-7">
    <div class="card">
      <div class="card-header"><b>Top Selling Products</b></div>
      <div class="card-body table-responsive">
        <table class="table table-bordered table-sm mb-0">
          <thead class="table-dark">
            <tr>
              <th>Product</th>
              <th>SKU</th>
              <th>Sold</th>
              <th>Avg Price</th>
              <th>Sales</th>
            </tr>
          </thead>
          <tbody>
            @forelse($topProducts as $p)
              <tr>
                <td class="fw-semibold">{{ $p->product_name }}</td>
                <td>{{ $p->sku }}</td>
                <td>{{ $p->sold_qty }}</td>
                <td>{{ number_format($p->avg_price, 2) }}</td>
                <td>{{ number_format($p->sales_total, 2) }}</td>
              </tr>
            @empty
              <tr><td colspan="5" class="text-center">No data</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="col-lg-5">
    <div class="card">
      <div class="card-header"><b>Low Stock / Attention</b></div>
      <div class="card-body table-responsive">
        <table class="table table-bordered table-sm mb-0">
          <thead class="table-dark">
            <tr>
              <th>Product</th>
              <th>SKU</th>
              <th>Stock</th>
              <th>Limit</th>
            </tr>
          </thead>
          <tbody>
            @forelse($lowStockProducts as $p)
              <tr>
                <td class="fw-semibold">{{ $p->name }}</td>
                <td>{{ $p->sku }}</td>
                <td>
                  @if($p->stock <= 0)
                    <span class="badge bg-danger">0</span>
                  @else
                    <span class="badge bg-warning text-dark">{{ $p->stock }}</span>
                  @endif
                </td>
                <td>{{ $p->low_stock_threshold }}</td>
              </tr>
            @empty
              <tr><td colspan="4" class="text-center">No low stock products</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="card mt-3">
  <div class="card-header"><b>Product Performance (All Products)</b></div>
  <div class="card-body table-responsive">
    <table class="table table-bordered table-sm mb-0 align-middle">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Product</th>
          <th>SKU</th>
          <th>Active</th>
          <th>Stock</th>
          <th>Sold (Range)</th>
          <th>Sales (Range)</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        @forelse($productPerformance as $p)
          @php
            $isLow = $p->stock <= $p->low_stock_threshold;
          @endphp
          <tr>
            <td>{{ $p->id }}</td>
            <td class="fw-semibold">{{ $p->name }}</td>
            <td>{{ $p->sku }}</td>
            <td>{!! $p->is_active ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-secondary">No</span>' !!}</td>
            <td>{{ $p->stock }}</td>
            <td>{{ $p->sold_qty }}</td>
            <td>{{ number_format($p->sales_total, 2) }}</td>
            <td>
              @if($p->stock <= 0)
                <span class="badge bg-danger">Out of Stock</span>
              @elseif($isLow)
                <span class="badge bg-warning text-dark">Low Stock</span>
              @else
                <span class="badge bg-success">Healthy</span>
              @endif
            </td>
          </tr>
        @empty
          <tr><td colspan="8" class="text-center">No products</td></tr>
        @endforelse
      </tbody>
    </table>

    <div class="mt-3">
      {{ $productPerformance->links() }}
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const topLabels = @json($topProducts->pluck('product_name'));
  const topSold   = @json($topProducts->pluck('sold_qty'));
  const topSales  = @json($topProducts->pluck('sales_total'));

  const ctxTop = document.getElementById('topProductsChart').getContext('2d');

  new Chart(ctxTop, {
    type: 'bar',
    data: {
      labels: topLabels,
      datasets: [
        {
          label: 'Sold Qty',
          data: topSold,
        },
        {
          label: 'Sales Total',
          data: topSales,
        }
      ]
    },
    options: {
      responsive: true,
      interaction: { mode: 'index', intersect: false },
      scales: {
        x: { ticks: { maxRotation: 45, minRotation: 45 } },
        y: { beginAtZero: true }
      },
      plugins: {
        tooltip: {
          callbacks: {
            label: function(context) {
              const label = context.dataset.label || '';
              const val = context.parsed.y ?? 0;
              // show 2 decimals for sales
              if(label.toLowerCase().includes('sales')) return `${label}: ${val.toFixed(2)}`;
              return `${label}: ${val}`;
            }
          }
        }
      }
    }
  });
</script>
@endsection
