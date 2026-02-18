@extends('layouts.admin')
@section('page')

<div id="dashWrap">

<style>
  #dashWrap{
    --radius:18px;
    --shadow: 0 12px 34px rgba(17, 24, 39, .08);
    --shadow-hover: 0 18px 50px rgba(17, 24, 39, .12);
    --muted:#6b7280;
    --text:#111827;
    --bg:#f5f7fb;
    --border: rgba(17, 24, 39, .06);
    --card:#fff;
  }

  body{ background: var(--bg); }

  /* Base cards */
  #dashWrap .card{
    border: 1px solid var(--border);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    overflow: hidden;
    background: var(--card);
  }
  #dashWrap .card-header{
    background: transparent;
    border-bottom: 1px solid var(--border);
  }

  /* KPI premium */
  #dashWrap .kpi{
    position: relative;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
    background: var(--card);
    transition: .25s ease;
    overflow: hidden;
    min-height: 130px;
  }
  #dashWrap .kpi:hover{ transform: translateY(-3px); box-shadow: var(--shadow-hover); }

  /* soft gradient ribbon */
  #dashWrap .kpi::before{
    content:"";
    position:absolute; inset:-2px -2px auto -2px;
    height:72px;
    background: linear-gradient(90deg, rgba(99,102,241,.22), rgba(99,102,241,0));
    opacity:.9;
  }
  /* per-card gradients */
  #dashWrap .kpi.kpi-green::before{ background: linear-gradient(90deg, rgba(34,197,94,.22), rgba(34,197,94,0)); }
  #dashWrap .kpi.kpi-orange::before{ background: linear-gradient(90deg, rgba(249,115,22,.22), rgba(249,115,22,0)); }
  #dashWrap .kpi.kpi-purple::before{ background: linear-gradient(90deg, rgba(168,85,247,.22), rgba(168,85,247,0)); }
  #dashWrap .kpi.kpi-blue::before{ background: linear-gradient(90deg, rgba(59,130,246,.22), rgba(59,130,246,0)); }

  #dashWrap .kpi-body{ position:relative; padding:18px 18px 14px; height: 180px}
  #dashWrap .kpi-top{ display:flex; align-items:flex-start; justify-content:space-between; gap:14px; }
  #dashWrap .kpi-left{ min-width: 0; }
  #dashWrap .kpi-title{
    font-size:12px; letter-spacing:.2px; color: var(--muted);
    margin:0; font-weight:800; text-transform: uppercase;
  }
  #dashWrap .kpi-value{
    font-size:28px; font-weight:900; margin:8px 0 0; color: var(--text);
    line-height:1.05;
  }
  #dashWrap .kpi-meta{
    font-size:12px; margin-top:8px; color: var(--muted);
    display:flex; align-items:center; gap:10px; flex-wrap:wrap;
  }

  /* icon chip */
  #dashWrap .kpi-ico{
    width:44px; height:44px; border-radius:14px;
    display:grid; place-items:center;
    background: rgba(17,24,39,.04);
    border: 1px solid rgba(17,24,39,.06);
    flex: 0 0 auto;
  }
  #dashWrap .kpi-ico svg{ width:20px; height:20px; }

  #dashWrap .chip{
    display:inline-flex; align-items:center; gap:6px;
    padding:4px 10px;
    border-radius:999px;
    font-size:12px; font-weight:900;
    border: 1px solid rgba(17,24,39,.08);
    background: rgba(255,255,255,.7);
    color:#111827;
  }
  #dashWrap .chip.up{ color:#16a34a; border-color: rgba(34,197,94,.25); background: rgba(34,197,94,.08); }
  #dashWrap .chip.down{ color:#dc2626; border-color: rgba(220,38,38,.22); background: rgba(220,38,38,.08); }

  /* sparkline */
  #dashWrap .spark{ height:46px; width: 140px; }
  #dashWrap .spark canvas{ width:100% !important; height:100% !important; }

  /* compact KPI cards */
  #dashWrap .mini{
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
    background: var(--card);
    transition:.25s ease;
    overflow:hidden;
    min-height:110px;
  }
  #dashWrap .mini:hover{ transform: translateY(-2px); box-shadow: var(--shadow-hover); }
  #dashWrap .mini .mini-body{    
    padding: 25px 18px;
    height: 125px; 
  }
  #dashWrap .mini .kpi-value{ font-size:26px; font-weight:900; margin-top:6px; }

  /* tables */
  #dashWrap .table-sm td, #dashWrap .table-sm th { padding: .60rem; }
  #dashWrap .table thead th{
    color:#374151; font-weight:900; font-size:12px;
    text-transform: uppercase; letter-spacing:.3px;
    border-bottom:1px solid var(--border) !important;
  }
  #dashWrap .table td{ border-color: var(--border) !important; vertical-align: middle; }

  /* scroll */
  #dashWrap .activity-box{ max-height:190px; overflow-y:auto; }
  #dashWrap .fixed-box{ max-height:190px; overflow:auto; }

  #dashWrap .activity-item{ padding:10px 0; border-bottom:1px solid rgba(17,24,39,.06); }
  #dashWrap .activity-title{ font-weight:900; font-size:13px; color:#111827; }
  #dashWrap .activity-meta{ font-size:12px; color: var(--muted); }

  #dashWrap a.small.text-decoration-none{ color:#4f46e5; font-weight:800; }
  #dashWrap a.small.text-decoration-none:hover{ text-decoration: underline !important; }

  #dashWrap .activity-box::-webkit-scrollbar,
  #dashWrap .fixed-box::-webkit-scrollbar{ width:8px; height:8px; }
  #dashWrap .activity-box::-webkit-scrollbar-thumb,
  #dashWrap .fixed-box::-webkit-scrollbar-thumb{
    background: rgba(17,24,39,.18);
    border-radius: 999px;
  }

  #dashWrap .copyright p{ margin:0; }
</style>

@php
  $salesChartLabelsSafe = $salesChartLabels ?? [];
  $salesChartValuesSafe = $salesChartValues ?? [];

  $orderStatusSafe = $orderStatus ?? [
    'pending'    => 0,
    'processing' => 0,
    'completed'  => 0,
    'cancelled'  => 0,
  ];

  // optional growth numbers (controller theke dile better)
  $salesGrowth     = $salesGrowth ?? 0;
  $ordersGrowth    = $ordersGrowth ?? 0;
  $customersGrowth = $customersGrowth ?? 0;
  $productsGrowth  = $productsGrowth ?? 0;

  // optional spark arrays (controller theke dile better)
  $sparkOrders    = $sparkOrders ?? [];
  $sparkCustomers = $sparkCustomers ?? [];
  $sparkProducts  = $sparkProducts ?? [];
@endphp

{{-- =======================
  KPI ROW (Premium)
======================= --}}
<div class="row g-3">

  {{-- Total Sales --}}
  <div class="col-md-3 col-6">
    <div class="kpi kpi-green">
      <div class="kpi-body">
        <div class="kpi-top">
          <div class="kpi-left">
            <p class="kpi-title">Total Earnings</p>
            <div class="kpi-value">৳ {{ number_format($totalSales ?? 0) }}</div>
            <div class="kpi-meta">
              <span>Today: ৳ {{ number_format($todaySales ?? 0) }}</span>
              <span class="chip up">▲ {{ $salesGrowth }}%</span>
            </div>
          </div>
          <div class="text-end">
            <div class="kpi-ico">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 12c0 4.418-4.03 8-9 8s-9-3.582-9-8 4.03-8 9-8 9 3.582 9 8Z"/>
                <path d="M9.5 9.5c0-1 1-2 2.5-2s2.5.7 2.5 2-1 1.7-2.5 2-2.5 1-2.5 2 1 2 2.5 2 2.5-1 2.5-2"/>
              </svg>
            </div>
            <div class="spark mt-2"><canvas id="sparkSales"></canvas></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Orders --}}
  <div class="col-md-3 col-6">
    <div class="kpi kpi-orange">
      <div class="kpi-body">
        <div class="kpi-top">
          <div class="kpi-left">
            <p class="kpi-title">Total Orders</p>
            <div class="kpi-value">{{ $totalOrders ?? 0 }}</div>
            <div class="kpi-meta">
              <span>Today: {{ $todayOrders ?? 0 }}</span>
              <span class="chip up">▲ {{ $ordersGrowth }}%</span>
            </div>
          </div>
          <div class="text-end">
            <div class="kpi-ico">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M6 7h12l-1 14H7L6 7Z"/>
                <path d="M9 7a3 3 0 0 1 6 0"/>
              </svg>
            </div>
            <div class="spark mt-2"><canvas id="sparkOrders"></canvas></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Customers --}}
  <div class="col-md-3 col-6">
    <div class="kpi kpi-purple">
      <div class="kpi-body">
        <div class="kpi-top">
          <div class="kpi-left">
            <p class="kpi-title">Customers</p>
            <div class="kpi-value">{{ $totalCustomers ?? 0 }}</div>
            <div class="kpi-meta">
              <span>Today: {{ $todayCustomers ?? 0 }}</span>
              <span class="chip up">▲ {{ $customersGrowth }}%</span>
            </div>
          </div>
          <div class="text-end">
            <div class="kpi-ico">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20 21a8 8 0 1 0-16 0"/>
                <path d="M12 11a4 4 0 1 0-4-4 4 4 0 0 0 4 4Z"/>
              </svg>
            </div>
            <div class="spark mt-2"><canvas id="sparkCustomers"></canvas></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Products --}}
  <div class="col-md-3 col-6">
    <div class="kpi kpi-blue">
      <div class="kpi-body">
        <div class="kpi-top">
          <div class="kpi-left">
            <p class="kpi-title">Products</p>
            <div class="kpi-value">{{ $totalProducts ?? 0 }}</div>
            <div class="kpi-meta">
              <span>Active: {{ $activeProducts ?? 0 }}</span>
              <span class="chip up">▲ {{ $productsGrowth }}%</span>
            </div>
          </div>
          <div class="text-end">
            <div class="kpi-ico">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 8l-9-5-9 5 9 5 9-5Z"/>
                <path d="M3 8v10l9 5 9-5V8"/>
                <path d="M12 13v10"/>
              </svg>
            </div>
            <div class="spark mt-2"><canvas id="sparkProducts"></canvas></div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>

{{-- =======================
  TRAFFIC ROW (compact)
======================= --}}
<div class="row g-3 mt-2">

  <div class="col-md-4 col-6">
    <div class="mini">
      <div class="mini-body">
        <p class="kpi-title">Unique Visitors (Today)</p>
        <div class="kpi-value" id="uniqueVisitorsToday">{{ $uniqueVisitorsToday ?? 0 }}</div>
        <div class="kpi-meta">Based on sessions</div>
      </div>
    </div>
  </div>

  <div class="col-md-4 col-6">
    <div class="mini">
      <div class="mini-body">
        <p class="kpi-title">Page Views (Today)</p>
        <div class="kpi-value" id="pageViewsToday">{{ $pageViewsToday ?? 0 }}</div>
        <div class="kpi-meta">Total page hits</div>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="mini">
      <div class="mini-body">
        <p class="kpi-title">Online Logged-in Users (Now)</p>
        <div class="kpi-value">{{ $onlineLoggedInCount ?? 0 }}</div>
        <div class="kpi-meta">Active in last 2 minutes</div>
      </div>
    </div>
  </div>

</div>

{{-- =======================
  CHARTS (Dataflow-like)
======================= --}}
<div class="row mt-3 g-3">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header py-2 d-flex justify-content-between align-items-center">
        <strong>Revenue ({{ now()->year }})</strong>
        <span class="chip up">Yearly</span>
      </div>
      <div class="card-body" style="height:320px;">
        <canvas id="revenueComboChart"></canvas>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card">
      <div class="card-header py-2"><strong>Order Status</strong></div>
      <div class="card-body" style="height:320px;">
        <canvas id="orderStatusChart"></canvas>
      </div>
    </div>
  </div>
</div>

{{-- =======================
  LEVEL-3 WIDGETS
======================= --}}
<div class="row mt-3 g-3">

  {{-- Top Products --}}
  <div class="col-md-4">
    <div class="card">
      <div class="card-header py-2"><strong>Top 5 Products</strong></div>
      <div class="card-body fixed-box">
        <div class="table-responsive">
          <table class="table table-sm mb-0">
            <thead>
              <tr>
                <th>Product</th>
                <th class="text-end">Sold</th>
                <th class="text-end">Revenue</th>
              </tr>
            </thead>
            <tbody>
              @forelse($topProducts ?? [] as $p)
                <tr>
                  <td>
                    <div style="font-weight:800">{{ $p->name ?? $p->title ?? 'N/A' }}</div>
                    <small class="text-muted">SKU: {{ $p->sku ?? '—' }}</small>
                  </td>
                  <td class="text-end">{{ $p->sold_qty ?? 0 }}</td>
                  <td class="text-end">৳ {{ number_format($p->revenue ?? 0) }}</td>
                </tr>
              @empty
                <tr><td colspan="3" class="text-muted">No data found.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  {{-- Recent Orders --}}
  <div class="col-md-4">
    <div class="card">
      <div class="card-header py-2 d-flex justify-content-between align-items-center">
        <strong>Recent Orders</strong>
        <a href="{{ route('dashboard.orders.index') }}" class="small text-decoration-none">View all</a>
      </div>
      <div class="card-body fixed-box">
        <div class="table-responsive">
          <table class="table table-sm mb-0">
            <thead>
              <tr>
                <th>#</th>
                <th>Status</th>
                <th class="text-end">Total</th>
              </tr>
            </thead>
            <tbody>
              @forelse($recentOrders ?? [] as $o)
                <tr>
                  <td>
                    <div style="font-weight:800">#{{ $o->id }}</div>
                    <small class="text-muted">{{ \Carbon\Carbon::parse($o->created_at)->diffForHumans() }}</small>
                  </td>
                  <td>
                    <span class="chip">{{ ucfirst($o->status ?? 'pending') }}</span>
                  </td>
                  <td class="text-end">৳ {{ number_format($o->grand_total ?? $o->total ?? 0) }}</td>
                </tr>
              @empty
                <tr><td colspan="3" class="text-muted">No recent orders found.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  {{-- Low Stock --}}
  <div class="col-md-4">
    <div class="card">
      <div class="card-header py-2 d-flex justify-content-between align-items-center">
        <strong>Low Stock</strong>
        <a href="{{ route('dashboard.inventory.index') }}" class="small text-decoration-none">Inventory</a>
      </div>
      <div class="card-body activity-box">
        @forelse($lowStockProducts ?? [] as $lp)
          <div class="activity-item">
            <div class="activity-title">⚠️ {{ $lp->name ?? $lp->title ?? 'N/A' }}</div>
            <div class="activity-meta">Stock: <strong>{{ $lp->stock ?? 0 }}</strong> • SKU: {{ $lp->sku ?? '—' }}</div>
          </div>
        @empty
          <div class="text-muted">No low stock items.</div>
        @endforelse
      </div>
    </div>
  </div>

</div>

{{-- =======================
  RECENT ACTIVITY
======================= --}}
<div class="row mt-3 g-3">
  <div class="col-md-6">
    <div class="card">
      <div class="card-header py-2"><strong>Activity Feed</strong></div>
      <div class="card-body activity-box">
        @forelse($activityFeed ?? [] as $a)
          <div class="activity-item">
            <div class="activity-title">
              @if(($a->type ?? '') === 'order') 🛒 Order:
              @elseif(($a->type ?? '') === 'product') 📦 Product:
              @elseif(($a->type ?? '') === 'customer') 👤 Customer:
              @else 🔔
              @endif
              {{ $a->title ?? '—' }}
            </div>
            <div class="activity-meta">
              {{ $a->meta ?? '' }}
              @if(!empty($a->at)) • {{ \Carbon\Carbon::parse($a->at)->diffForHumans() }} @endif
            </div>
          </div>
        @empty
          <div class="text-muted">No recent activity found.</div>
        @endforelse
      </div>
    </div>
  </div>
  <div class="col-md-6">
  <div class="card">
  <div class="card-header py-2 d-flex justify-content-between align-items-center">
    <strong>User Location</strong>
    <span class="small text-muted">Last 30 days</span>
  </div>

  <div class="card-body">
    <div id="worldMap" style="height:230px;border-radius:14px;overflow:hidden;"></div>

    <div class="mt-3">
      @php
        $vals = array_values($worldMapData ?? []);
        $max = (count($vals) ? max($vals) : 1);
        if($max == 0) $max = 1;
      @endphp

      @forelse($topCountries ?? [] as $c)
        @php $pct = round(($c->total / $max) * 100, 1); @endphp
        <div class="d-flex align-items-center justify-content-between mb-2">
          <div class="d-flex align-items-center gap-2">
            <span style="width:8px;height:8px;border-radius:999px;background:#fb7a29;display:inline-block"></span>
            <span class="small fw-semibold">{{ $c->name }}</span>
          </div>
          <span class="small text-muted">{{ $pct }}%</span>
        </div>
      @empty
        <div class="text-muted">No location data yet.</div>
      @endforelse
    </div>
  </div>
  </div>
</div>

</div>

<div class="copyright">
  <p style="color:#111827;">© {{ date('Y') }} - Made with ❤️ by
    <a href="https://www.facebook.com/mdfahim.hossensujon" target="_blank">Fahim</a>
  </p>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

  // ✅ safety: Chart.js loaded?
  if (typeof Chart === 'undefined') {
    console.error('Chart.js not loaded. Check: contents/admin/js/chart.js');
    return;
  }

  const salesLabels = @json($salesChartLabelsSafe);
  const salesValues = @json($salesChartValuesSafe);
  const orderStatus = @json($orderStatusSafe);

  const lastN  = (arr, n=12) => (Array.isArray(arr) ? arr.slice(Math.max(arr.length - n, 0)) : []);
  const zeros  = (n=12) => Array.from({length:n}, () => 0);
  const fixSpark = (a)=> (Array.isArray(a) && a.length ? lastN(a,12) : zeros(12));

  const sparkSales     = lastN(salesValues, 12).length ? lastN(salesValues, 12) : zeros(12);
  const sparkOrders    = @json($sparkOrders ?? []);
  const sparkCustomers = @json($sparkCustomers ?? []);
  const sparkProducts  = @json($sparkProducts ?? []);

  // -----------------------------
  // Spark charts (already)
  // -----------------------------
  function sparkChart(canvasId, data){
    const el = document.getElementById(canvasId);
    if(!el) return;

    new Chart(el, {
      type:'line',
      data:{
        labels: data.map((_,i)=> i+1),
        datasets:[{
          data,
          tension:.45,
          borderWidth:2,
          pointRadius:0,
          fill:true,
          backgroundColor:'rgba(17,24,39,.06)',
          borderColor:'rgba(17,24,39,.55)'
        }]
      },
      options:{
        responsive:true,
        maintainAspectRatio:false,
        plugins:{ legend:{display:false}, tooltip:{enabled:false}},
        scales:{ x:{display:false}, y:{display:false} }
      }
    });
  }

  sparkChart('sparkSales', fixSpark(sparkSales));
  sparkChart('sparkOrders', fixSpark(sparkOrders));
  sparkChart('sparkCustomers', fixSpark(sparkCustomers));
  sparkChart('sparkProducts', fixSpark(sparkProducts));

  // -----------------------------
  // ✅ BIG Chart 1: Revenue Combo
  // -----------------------------
  const revEl = document.getElementById('revenueComboChart');
  if (revEl) {
    new Chart(revEl, {
      data: {
        labels: salesLabels,
        datasets: [
          {
            type: 'bar',
            label: 'Revenue',
            data: salesValues,
            borderWidth: 0
          },
          {
            type: 'line',
            label: 'Trend',
            data: salesValues,
            tension: .35,
            borderWidth: 2,
            pointRadius: 2
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: true } },
        scales: {
          x: { grid: { display: false } },
          y: { beginAtZero: true }
        }
      }
    });
  }

  // -----------------------------
  // ✅ BIG Chart 2: Order Status
  // -----------------------------
  const statusEl = document.getElementById('orderStatusChart');
  if (statusEl) {
    const statusLabels = Object.keys(orderStatus);
    const statusValues = Object.values(orderStatus);

    new Chart(statusEl, {
      type: 'doughnut',
      data: {
        labels: statusLabels.map(s => s.charAt(0).toUpperCase() + s.slice(1)),
        datasets: [{
          data: statusValues,
          borderWidth: 0
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom' } },
        cutout: '65%'
      }
    });
  }

  // -----------------------------
  // Map
  // -----------------------------
  const worldMapData = @json($worldMapData ?? []);
  if (document.getElementById('worldMap') && typeof jsVectorMap !== 'undefined') {
    new jsVectorMap({
      selector: '#worldMap',
      map: 'world_merc',
      zoomButtons: false,
      regionStyle: {
        initial: { fill: '#9ca3af', stroke:'#ffffff', strokeWidth:1 }
      },
      series: {
        regions: [{
          values: worldMapData,
          scale: ['#fed7aa', '#fb7a29'],
          normalizeFunction: 'polynomial'
        }]
      }
    });
  }

  // -----------------------------
  // Live traffic (optional)
  // -----------------------------
  @if(\Illuminate\Support\Facades\Route::has('dashboard.traffic.today'))
  setInterval(async () => {
    try {
      const res = await fetch(@json(route('dashboard.traffic.today')));
      const data = await res.json();
      const u = document.getElementById('uniqueVisitorsToday');
      const v = document.getElementById('pageViewsToday');
      if(u) u.innerText = data.unique ?? 0;
      if(v) v.innerText = data.views ?? 0;
    } catch (e) {}
  }, 5000);
  @endif

});
</script>
@endpush

</div>
@endsection
