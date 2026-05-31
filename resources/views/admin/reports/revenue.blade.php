@extends('layouts.admin')
@section('title', 'Revenue Reports')
@section('page')

@php
  $total        = $bookings->count();
  $totalRevenue = $bookings->sum('total_amount');
  $paidRevenue  = $bookings->where('payment_status','paid')->sum('total_amount');
  $avgOrder     = $total > 0 ? $totalRevenue / $total : 0;

  // group by month for chart
  $monthly = $bookings->groupBy(fn($b) => $b->created_at->format('M Y'))
    ->map(fn($g) => $g->sum('total_amount'));
@endphp

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');
:root {
  --r-surface:   #1a1d27;
  --r-surface2:  #222636;
  --r-border:    rgba(255,255,255,.07);
  --r-accent:    #0ea5e9;
  --r-accent2:   #38bdf8;
  --r-success:   #22c55e;
  --r-danger:    #ef4444;
  --r-warning:   #f59e0b;
  --r-teal:      #2dd4bf;
  --r-text:      #e2e8f0;
  --r-muted:     #64748b;
  --r-radius:    14px;
  --r-radius-sm: 8px;
  --r-shadow:    0 8px 32px rgba(0,0,0,.45);
}
.r-wrap { font-family:'Plus Jakarta Sans',sans-serif; color:var(--r-text); }

/* header */
.r-header {
  background:linear-gradient(135deg,#0c1a2e 0%,#0c3558 50%,#0c4a72 100%);
  border-radius:var(--r-radius); padding:28px 32px;
  margin-bottom:24px; position:relative; overflow:hidden; box-shadow:var(--r-shadow);
}
.r-header::before { content:''; position:absolute; inset:0; background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%230ea5e9' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='20'/%3E%3C/g%3E%3C/svg%3E"); }
.r-header::after { content:''; position:absolute; right:-40px; top:-40px; width:200px; height:200px; border-radius:50%; background:radial-gradient(circle,rgba(14,165,233,.2) 0%,transparent 70%); }
.r-header .title { font-size:1.5rem; font-weight:700; position:relative; z-index:1; background:linear-gradient(90deg,#fff,var(--r-accent2)); -webkit-background-clip:text; -webkit-text-fill-color:transparent; }
.r-header .subtitle { color:rgba(255,255,255,.45); font-size:.85rem; margin-top:4px; position:relative; z-index:1; }

/* revenue highlight */
.r-total-pill {
  position:relative; z-index:1;
  background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.15);
  border-radius:12px; padding:14px 20px; text-align:right;
}
.r-total-label { font-size:.75rem; color:rgba(255,255,255,.5); margin-bottom:4px; }
.r-total-value { font-size:1.6rem; font-weight:700; color:var(--r-accent2); font-family:'JetBrains Mono',monospace; }

/* stat cards */
.r-stats { display:grid; grid-template-columns:repeat(auto-fit,minmax(160px,1fr)); gap:14px; margin-bottom:24px; }
.r-stat-card { background:var(--r-surface); border:1px solid var(--r-border); border-radius:var(--r-radius-sm); padding:18px; position:relative; overflow:hidden; transition:transform .2s; }
.r-stat-card:hover { transform:translateY(-2px); }
.r-stat-card::before { content:''; position:absolute; left:0; top:0; bottom:0; width:3px; border-radius:3px 0 0 3px; }
.r-stat-card.s1::before { background:var(--r-accent2); }
.r-stat-card.s2::before { background:var(--r-success); }
.r-stat-card.s3::before { background:var(--r-warning); }
.r-stat-card.s4::before { background:var(--r-teal); }
.r-stat-icon { width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:.9rem; margin-bottom:10px; }
.r-stat-num   { font-size:1.3rem; font-weight:700; line-height:1; margin-bottom:4px; font-family:'JetBrains Mono',monospace; }
.r-stat-label { font-size:.72rem; color:var(--r-muted); text-transform:uppercase; letter-spacing:.06em; }

/* chart card */
.r-chart-card { background:var(--r-surface); border:1px solid var(--r-border); border-radius:var(--r-radius); padding:24px; margin-bottom:20px; box-shadow:var(--r-shadow); }
.r-chart-title { font-size:.8rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:var(--r-muted); margin-bottom:20px; display:flex; align-items:center; gap:8px; }
.r-chart-title::after { content:''; flex:1; height:1px; background:var(--r-border); }

/* bar chart */
.r-bar-chart { display:flex; align-items:flex-end; gap:8px; height:160px; }
.r-bar-col   { display:flex; flex-direction:column; align-items:center; gap:6px; flex:1; }
.r-bar       { width:100%; border-radius:4px 4px 0 0; background:linear-gradient(180deg,var(--r-accent2),var(--r-accent)); min-height:4px; transition:all .3s; position:relative; cursor:pointer; }
.r-bar:hover { opacity:.8; }
.r-bar-label { font-size:.68rem; color:var(--r-muted); white-space:nowrap; }
.r-bar-val   { font-size:.65rem; font-family:'JetBrains Mono',monospace; color:var(--r-accent2); }

/* filter */
.r-filter-card { background:var(--r-surface); border:1px solid var(--r-border); border-radius:var(--r-radius); padding:20px 24px; margin-bottom:20px; box-shadow:var(--r-shadow); }
.r-filter-title { font-size:.75rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:var(--r-muted); margin-bottom:16px; display:flex; align-items:center; gap:8px; }
.r-filter-title::after { content:''; flex:1; height:1px; background:var(--r-border); }
.r-filter-row { display:flex; gap:12px; align-items:flex-end; flex-wrap:wrap; }
.r-filter-group { display:flex; flex-direction:column; gap:6px; }
.r-filter-group label { font-size:.75rem; font-weight:600; color:var(--r-muted); text-transform:uppercase; letter-spacing:.06em; }
.r-filter-group input {
  background:var(--r-surface2); border:1px solid var(--r-border);
  border-radius:var(--r-radius-sm); padding:9px 14px; color:var(--r-text);
  font-family:inherit; font-size:.875rem; outline:none; min-width:160px;
}
.r-filter-group input[type="date"]::-webkit-calendar-picker-indicator { filter:invert(1); opacity:.5; cursor:pointer; }
.r-filter-group input:focus { border-color:var(--r-accent); box-shadow:0 0 0 3px rgba(14,165,233,.12); }
.r-btn { display:inline-flex; align-items:center; gap:6px; border:none; cursor:pointer; font-family:inherit; font-weight:600; border-radius:var(--r-radius-sm); transition:all .2s; }
.r-btn-primary { background:var(--r-accent); color:#fff; padding:9px 20px; font-size:.875rem; }
.r-btn-primary:hover { background:var(--r-accent2); transform:translateY(-1px); color:#0c1a2e; }
.r-btn-ghost { background:var(--r-surface2); color:var(--r-muted); border:1px solid var(--r-border); padding:9px 14px; font-size:.875rem; text-decoration:none; }
.r-btn-ghost:hover { color:var(--r-text); }

/* active filter tags */
.r-active-filters { display:flex; gap:8px; flex-wrap:wrap; margin-top:12px; }
.r-filter-tag { display:inline-flex; align-items:center; gap:6px; background:rgba(14,165,233,.1); border:1px solid rgba(14,165,233,.25); color:var(--r-accent2); border-radius:20px; padding:3px 10px; font-size:.75rem; font-weight:600; }

/* table */
.r-table-card { background:var(--r-surface); border:1px solid var(--r-border); border-radius:var(--r-radius); overflow:hidden; box-shadow:var(--r-shadow); }
.r-table-top { padding:14px 20px; border-bottom:1px solid var(--r-border); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; }
.r-table-title { font-size:.85rem; font-weight:700; }
.r-table { width:100%; border-collapse:collapse; }
.r-table thead tr { background:var(--r-surface2); }
.r-table th { padding:12px 18px; text-align:left; font-size:.72rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:var(--r-muted); white-space:nowrap; }
.r-table td { padding:13px 18px; vertical-align:middle; border-bottom:1px solid var(--r-border); font-size:.875rem; }
.r-table tbody tr:hover { background:rgba(14,165,233,.04); }
.r-table tbody tr:last-child td { border-bottom:none; }

.r-code   { font-family:'JetBrains Mono',monospace; font-size:.8rem; background:var(--r-surface2); border:1px solid var(--r-border); padding:3px 8px; border-radius:6px; color:var(--r-accent2); }
.r-amount { font-family:'JetBrains Mono',monospace; font-weight:600; color:var(--r-success); }
.r-user   { display:flex; align-items:center; gap:8px; }
.r-user-av{ width:30px; height:30px; border-radius:50%; background:var(--r-accent); display:flex; align-items:center; justify-content:center; font-size:.7rem; font-weight:700; flex-shrink:0; color:#0c1a2e; }

.r-badge { display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:20px; font-size:.72rem; font-weight:700; }
.r-badge-paid     { background:rgba(34,197,94,.12);  color:#86efac; border:1px solid rgba(34,197,94,.25); }
.r-badge-pending  { background:rgba(245,158,11,.15); color:#fcd34d; border:1px solid rgba(245,158,11,.3); }
.r-badge-failed   { background:rgba(239,68,68,.12);  color:#fca5a5; border:1px solid rgba(239,68,68,.25); }
.r-badge-refunded { background:rgba(56,189,248,.12); color:#7dd3fc; border:1px solid rgba(56,189,248,.25); }

.r-empty { text-align:center; padding:60px 20px; color:var(--r-muted); }
.r-empty i { font-size:2.5rem; margin-bottom:14px; opacity:.4; display:block; }
</style>

<div class="r-wrap">

  {{-- Header --}}
  <div class="r-header">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
      <div>
        <div class="title"><i class="fas fa-chart-line me-2"></i>Revenue Reports</div>
        <div class="subtitle">Track income, payment trends and financial performance</div>
      </div>
      <div class="r-total-pill">
        <div class="r-total-label"><i class="fas fa-money-bill-wave me-1"></i>Total Revenue</div>
        <div class="r-total-value">৳{{ number_format($totalRevenue, 0) }}</div>
      </div>
    </div>
  </div>

  {{-- Stat Cards --}}
  <div class="r-stats">
    <div class="r-stat-card s1">
      <div class="r-stat-icon" style="background:rgba(56,189,248,.12);color:var(--r-accent2);"><i class="fas fa-money-bill-wave"></i></div>
      <div class="r-stat-num" style="color:var(--r-accent2);">৳{{ number_format($totalRevenue, 0) }}</div>
      <div class="r-stat-label">Total Revenue</div>
    </div>
    <div class="r-stat-card s2">
      <div class="r-stat-icon" style="background:rgba(34,197,94,.12);color:var(--r-success);"><i class="fas fa-check-circle"></i></div>
      <div class="r-stat-num" style="color:var(--r-success);">৳{{ number_format($paidRevenue, 0) }}</div>
      <div class="r-stat-label">Paid Revenue</div>
    </div>
    <div class="r-stat-card s3">
      <div class="r-stat-icon" style="background:rgba(245,158,11,.12);color:var(--r-warning);"><i class="fas fa-receipt"></i></div>
      <div class="r-stat-num" style="color:var(--r-warning);">{{ $total }}</div>
      <div class="r-stat-label">Total Orders</div>
    </div>
    <div class="r-stat-card s4">
      <div class="r-stat-icon" style="background:rgba(45,212,191,.12);color:var(--r-teal);"><i class="fas fa-calculator"></i></div>
      <div class="r-stat-num" style="color:var(--r-teal);font-size:1.1rem;">৳{{ number_format($avgOrder, 0) }}</div>
      <div class="r-stat-label">Avg. Order Value</div>
    </div>
  </div>

  {{-- Mini Bar Chart --}}
  @if($monthly->count() > 0)
  <div class="r-chart-card">
    <div class="r-chart-title"><i class="fas fa-chart-bar"></i> Monthly Revenue Overview</div>
    @php $maxVal = $monthly->max() ?: 1; @endphp
    <div class="r-bar-chart">
      @foreach($monthly as $month => $amount)
        @php $height = max(4, ($amount / $maxVal) * 140); @endphp
        <div class="r-bar-col">
          <div class="r-bar-val">৳{{ number_format($amount/1000, 0) }}k</div>
          <div class="r-bar" style="height:{{ $height }}px;" title="{{ $month }}: ৳{{ number_format($amount, 0) }}"></div>
          <div class="r-bar-label">{{ $month }}</div>
        </div>
      @endforeach
    </div>
  </div>
  @endif

  {{-- Filter --}}
  <div class="r-filter-card">
    <div class="r-filter-title"><i class="fas fa-filter"></i> Filter Revenue</div>
    <form method="GET">
      <div class="r-filter-row">
        <div class="r-filter-group">
          <label>From Date</label>
          <input type="date" name="from" value="{{ request('from') }}">
        </div>
        <div class="r-filter-group">
          <label>To Date</label>
          <input type="date" name="to" value="{{ request('to') }}">
        </div>
        <button type="submit" class="r-btn r-btn-primary">
          <i class="fas fa-search"></i> Apply Filter
        </button>
        @if(request('from') || request('to'))
          <a href="{{ url()->current() }}" class="r-btn r-btn-ghost">
            <i class="fas fa-times"></i> Clear
          </a>
        @endif
      </div>

      @if(request('from') || request('to'))
        <div class="r-active-filters">
          @if(request('from'))
            <span class="r-filter-tag"><i class="fas fa-calendar" style="font-size:.6rem;"></i>From: {{ \Carbon\Carbon::parse(request('from'))->format('d M Y') }}</span>
          @endif
          @if(request('to'))
            <span class="r-filter-tag"><i class="fas fa-calendar" style="font-size:.6rem;"></i>To: {{ \Carbon\Carbon::parse(request('to'))->format('d M Y') }}</span>
          @endif
          <span class="r-filter-tag" style="background:rgba(34,197,94,.1);border-color:rgba(34,197,94,.25);color:#86efac;">
            <i class="fas fa-coins" style="font-size:.6rem;"></i>৳{{ number_format($totalRevenue, 0) }} total
          </span>
        </div>
      @endif
    </form>
  </div>

  {{-- Table --}}
  <div class="r-table-card">
    <div class="r-table-top">
      <div class="r-table-title"><i class="fas fa-table me-2" style="color:var(--r-accent2);"></i>Revenue Records</div>
      <div style="font-size:.8rem;color:var(--r-muted);">{{ $total }} transaction{{ $total != 1 ? 's' : '' }}</div>
    </div>
    <div style="overflow-x:auto;">
      <table class="r-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Booking Code</th>
            <th>Customer</th>
            <th>Tour</th>
            <th>Persons</th>
            <th>Amount</th>
            <th>Payment</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          @forelse($bookings as $i => $booking)
          <tr>
            <td style="color:var(--r-muted);font-size:.8rem;">{{ $i + 1 }}</td>
            <td><span class="r-code">{{ $booking->booking_code }}</span></td>
            <td>
              <div class="r-user">
                <div class="r-user-av">{{ strtoupper(substr($booking->user->name ?? 'U', 0, 1)) }}</div>
                <div>
                  <div style="font-weight:600;font-size:.875rem;">{{ $booking->user->name ?? 'N/A' }}</div>
                  <div style="font-size:.75rem;color:var(--r-muted);">{{ $booking->user->email ?? '' }}</div>
                </div>
              </div>
            </td>
            <td>
              <div style="font-weight:600;font-size:.875rem;max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                {{ $booking->tour->title ?? 'N/A' }}
              </div>
            </td>
            <td style="text-align:center;font-weight:600;">{{ $booking->person_count }}</td>
            <td><span class="r-amount">৳{{ number_format($booking->total_amount, 0) }}</span></td>
            <td>
              @php $ps = $booking->payment_status ?? 'pending'; @endphp
              <span class="r-badge r-badge-{{ $ps }}">
                <i class="fas fa-circle" style="font-size:.4rem;"></i>{{ ucfirst($ps) }}
              </span>
            </td>
            <td style="font-size:.82rem;color:var(--r-muted);">
              {{ $booking->created_at->format('d M Y') }}<br>
              <span style="font-size:.75rem;">{{ $booking->created_at->format('h:i A') }}</span>
            </td>
          </tr>
          @empty
          <tr><td colspan="8">
            <div class="r-empty">
              <i class="fas fa-chart-line"></i>
              <p>No revenue data found for the selected period.</p>
            </div>
          </td></tr>
          @endforelse
        </tbody>
        @if($total > 0)
        <tfoot>
          <tr style="background:var(--r-surface2);">
            <td colspan="5" style="padding:12px 18px;font-size:.82rem;color:var(--r-muted);font-weight:600;">TOTAL ({{ $total }} transactions)</td>
            <td style="padding:12px 18px;"><span class="r-amount" style="font-size:1rem;">৳{{ number_format($totalRevenue, 0) }}</span></td>
            <td colspan="2"></td>
          </tr>
        </tfoot>
        @endif
      </table>
    </div>
  </div>

</div>
@endsection