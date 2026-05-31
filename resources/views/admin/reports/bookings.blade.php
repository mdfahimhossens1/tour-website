@extends('layouts.admin')
@section('title', 'Booking Reports')
@section('page')

@php
  $total     = $bookings->count();
  $pending   = $bookings->where('booking_status','pending')->count();
  $confirmed = $bookings->where('booking_status','confirmed')->count();
  $cancelled = $bookings->where('booking_status','cancelled')->count();
  $completed = $bookings->where('booking_status','completed')->count();
  $totalAmt  = $bookings->sum('total_amount');
@endphp

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');
:root {
  --r-surface:   #1a1d27;
  --r-surface2:  #222636;
  --r-border:    rgba(255,255,255,.07);
  --r-accent:    #6366f1;
  --r-accent2:   #818cf8;
  --r-success:   #22c55e;
  --r-danger:    #ef4444;
  --r-warning:   #f59e0b;
  --r-info:      #38bdf8;
  --r-purple:    #a78bfa;
  --r-text:      #e2e8f0;
  --r-muted:     #64748b;
  --r-radius:    14px;
  --r-radius-sm: 8px;
  --r-shadow:    0 8px 32px rgba(0,0,0,.45);
}
.r-wrap { font-family:'Plus Jakarta Sans',sans-serif; color:var(--r-text); }

/* header */
.r-header {
  background:linear-gradient(135deg,#1e1b4b 0%,#312e81 55%,#3730a3 100%);
  border-radius:var(--r-radius); padding:28px 32px;
  margin-bottom:24px; position:relative; overflow:hidden; box-shadow:var(--r-shadow);
}
.r-header::before {
  content:''; position:absolute; inset:0;
  background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%236366f1' fill-opacity='0.06'%3E%3Ccircle cx='30' cy='30' r='20'/%3E%3C/g%3E%3C/svg%3E");
}
.r-header::after { content:''; position:absolute; right:-40px; top:-40px; width:200px; height:200px; border-radius:50%; background:radial-gradient(circle,rgba(99,102,241,.2) 0%,transparent 70%); }
.r-header .title { font-size:1.5rem; font-weight:700; position:relative; z-index:1; background:linear-gradient(90deg,#fff,var(--r-accent2)); -webkit-background-clip:text; -webkit-text-fill-color:transparent; }
.r-header .subtitle { color:rgba(255,255,255,.45); font-size:.85rem; margin-top:4px; position:relative; z-index:1; }

/* stat cards */
.r-stats { display:grid; grid-template-columns:repeat(auto-fit,minmax(140px,1fr)); gap:14px; margin-bottom:24px; }
.r-stat-card {
  background:var(--r-surface); border:1px solid var(--r-border);
  border-radius:var(--r-radius-sm); padding:16px 18px;
  position:relative; overflow:hidden; transition:transform .2s;
}
.r-stat-card:hover { transform:translateY(-2px); }
.r-stat-card::before { content:''; position:absolute; left:0; top:0; bottom:0; width:3px; border-radius:3px 0 0 3px; }
.r-stat-card.total::before    { background:var(--r-accent2); }
.r-stat-card.pending::before  { background:var(--r-warning); }
.r-stat-card.confirmed::before{ background:var(--r-success); }
.r-stat-card.cancelled::before{ background:var(--r-danger); }
.r-stat-card.completed::before{ background:var(--r-purple); }
.r-stat-card.revenue::before  { background:var(--r-info); }
.r-stat-num   { font-size:1.5rem; font-weight:700; line-height:1; margin-bottom:4px; }
.r-stat-label { font-size:.75rem; color:var(--r-muted); text-transform:uppercase; letter-spacing:.06em; }

/* filter card */
.r-filter-card {
  background:var(--r-surface); border:1px solid var(--r-border);
  border-radius:var(--r-radius); padding:20px 24px;
  margin-bottom:20px; box-shadow:var(--r-shadow);
}
.r-filter-title { font-size:.75rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:var(--r-muted); margin-bottom:16px; display:flex; align-items:center; gap:8px; }
.r-filter-title::after { content:''; flex:1; height:1px; background:var(--r-border); }
.r-filter-row { display:flex; gap:12px; align-items:flex-end; flex-wrap:wrap; }
.r-filter-group { display:flex; flex-direction:column; gap:6px; }
.r-filter-group label { font-size:.75rem; font-weight:600; color:var(--r-muted); text-transform:uppercase; letter-spacing:.06em; }
.r-filter-group select,
.r-filter-group input {
  background:var(--r-surface2); border:1px solid var(--r-border);
  border-radius:var(--r-radius-sm); padding:9px 14px; color:var(--r-text);
  font-family:inherit; font-size:.875rem; outline:none; transition:border-color .2s;
  min-width:160px;
}
.r-filter-group input[type="date"]::-webkit-calendar-picker-indicator { filter:invert(1); opacity:.5; cursor:pointer; }
.r-filter-group select:focus,
.r-filter-group input:focus { border-color:var(--r-accent); box-shadow:0 0 0 3px rgba(99,102,241,.12); }
.r-btn { display:inline-flex; align-items:center; gap:6px; border:none; cursor:pointer; font-family:inherit; font-weight:600; border-radius:var(--r-radius-sm); transition:all .2s; }
.r-btn-primary { background:var(--r-accent); color:#fff; padding:9px 20px; font-size:.875rem; }
.r-btn-primary:hover { background:var(--r-accent2); transform:translateY(-1px); }
.r-btn-ghost   { background:var(--r-surface2); color:var(--r-muted); border:1px solid var(--r-border); padding:9px 14px; font-size:.875rem; text-decoration:none; }
.r-btn-ghost:hover { color:var(--r-text); }

/* table card */
.r-table-card { background:var(--r-surface); border:1px solid var(--r-border); border-radius:var(--r-radius); overflow:hidden; box-shadow:var(--r-shadow); }
.r-table-top { padding:14px 20px; border-bottom:1px solid var(--r-border); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; }
.r-table-title { font-size:.85rem; font-weight:700; }
.r-table { width:100%; border-collapse:collapse; }
.r-table thead tr { background:var(--r-surface2); }
.r-table th { padding:12px 18px; text-align:left; font-size:.72rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:var(--r-muted); white-space:nowrap; }
.r-table td { padding:13px 18px; vertical-align:middle; border-bottom:1px solid var(--r-border); font-size:.875rem; }
.r-table tbody tr:hover { background:rgba(99,102,241,.04); }
.r-table tbody tr:last-child td { border-bottom:none; }

.r-code { font-family:'JetBrains Mono',monospace; font-size:.8rem; background:var(--r-surface2); border:1px solid var(--r-border); padding:3px 8px; border-radius:6px; color:var(--r-accent2); }
.r-amount { font-family:'JetBrains Mono',monospace; font-weight:600; color:var(--r-success); }
.r-user { display:flex; align-items:center; gap:8px; }
.r-user-av { width:30px; height:30px; border-radius:50%; background:var(--r-accent); display:flex; align-items:center; justify-content:center; font-size:.7rem; font-weight:700; flex-shrink:0; color:#fff; }

.r-badge { display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:20px; font-size:.72rem; font-weight:700; }
.r-badge-pending   { background:rgba(245,158,11,.15); color:#fcd34d; border:1px solid rgba(245,158,11,.3); }
.r-badge-confirmed { background:rgba(34,197,94,.12);  color:#86efac; border:1px solid rgba(34,197,94,.25); }
.r-badge-cancelled { background:rgba(239,68,68,.12);  color:#fca5a5; border:1px solid rgba(239,68,68,.25); }
.r-badge-completed { background:rgba(167,139,250,.12);color:#c4b5fd; border:1px solid rgba(167,139,250,.25); }

.r-empty { text-align:center; padding:60px 20px; color:var(--r-muted); }
.r-empty i { font-size:2.5rem; margin-bottom:14px; opacity:.4; display:block; }

/* active filter tags */
.r-active-filters { display:flex; gap:8px; flex-wrap:wrap; margin-top:12px; }
.r-filter-tag { display:inline-flex; align-items:center; gap:6px; background:rgba(99,102,241,.12); border:1px solid rgba(99,102,241,.25); color:var(--r-accent2); border-radius:20px; padding:3px 10px; font-size:.75rem; font-weight:600; }
</style>

<div class="r-wrap">

  {{-- Header --}}
  <div class="r-header">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
      <div>
        <div class="title"><i class="fas fa-chart-bar me-2"></i>Booking Reports</div>
        <div class="subtitle">Analyze and filter all booking records</div>
      </div>
      <div style="position:relative;z-index:1;text-align:right;">
        <div style="font-size:.78rem;color:rgba(255,255,255,.45);">Report Generated</div>
        <div style="font-size:.85rem;color:#fff;font-weight:600;">{{ now()->format('d M Y, h:i A') }}</div>
      </div>
    </div>
  </div>

  {{-- Stat Cards --}}
  <div class="r-stats">
    <div class="r-stat-card total">
      <div class="r-stat-num" style="color:var(--r-accent2);">{{ $total }}</div>
      <div class="r-stat-label">Total Bookings</div>
    </div>
    <div class="r-stat-card pending">
      <div class="r-stat-num" style="color:var(--r-warning);">{{ $pending }}</div>
      <div class="r-stat-label">Pending</div>
    </div>
    <div class="r-stat-card confirmed">
      <div class="r-stat-num" style="color:var(--r-success);">{{ $confirmed }}</div>
      <div class="r-stat-label">Confirmed</div>
    </div>
    <div class="r-stat-card cancelled">
      <div class="r-stat-num" style="color:var(--r-danger);">{{ $cancelled }}</div>
      <div class="r-stat-label">Cancelled</div>
    </div>
    <div class="r-stat-card completed">
      <div class="r-stat-num" style="color:var(--r-purple);">{{ $completed }}</div>
      <div class="r-stat-label">Completed</div>
    </div>
    <div class="r-stat-card revenue">
      <div class="r-stat-num" style="color:var(--r-info);font-size:1.2rem;">৳{{ number_format($totalAmt, 0) }}</div>
      <div class="r-stat-label">Total Value</div>
    </div>
  </div>

  {{-- Filter --}}
  <div class="r-filter-card">
    <div class="r-filter-title"><i class="fas fa-filter"></i> Filter Bookings</div>
    <form method="GET">
      <div class="r-filter-row">
        <div class="r-filter-group">
          <label>Booking Status</label>
          <select name="status">
            <option value="">All Status</option>
            <option value="pending"   {{ request('status')=='pending'   ?'selected':'' }}>Pending</option>
            <option value="confirmed" {{ request('status')=='confirmed' ?'selected':'' }}>Confirmed</option>
            <option value="cancelled" {{ request('status')=='cancelled' ?'selected':'' }}>Cancelled</option>
            <option value="completed" {{ request('status')=='completed' ?'selected':'' }}>Completed</option>
          </select>
        </div>
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
        @if(request('status') || request('from') || request('to'))
          <a href="{{ url()->current() }}" class="r-btn r-btn-ghost">
            <i class="fas fa-times"></i> Clear
          </a>
        @endif
      </div>

      {{-- Active filter tags --}}
      @if(request('status') || request('from') || request('to'))
        <div class="r-active-filters">
          @if(request('status'))
            <span class="r-filter-tag"><i class="fas fa-tag" style="font-size:.6rem;"></i>Status: {{ ucfirst(request('status')) }}</span>
          @endif
          @if(request('from'))
            <span class="r-filter-tag"><i class="fas fa-calendar" style="font-size:.6rem;"></i>From: {{ \Carbon\Carbon::parse(request('from'))->format('d M Y') }}</span>
          @endif
          @if(request('to'))
            <span class="r-filter-tag"><i class="fas fa-calendar" style="font-size:.6rem;"></i>To: {{ \Carbon\Carbon::parse(request('to'))->format('d M Y') }}</span>
          @endif
          <span class="r-filter-tag" style="background:rgba(34,197,94,.1);border-color:rgba(34,197,94,.25);color:#86efac;">
            <i class="fas fa-list" style="font-size:.6rem;"></i>{{ $total }} results
          </span>
        </div>
      @endif
    </form>
  </div>

  {{-- Table --}}
  <div class="r-table-card">
    <div class="r-table-top">
      <div class="r-table-title"><i class="fas fa-list me-2" style="color:var(--r-accent2);"></i>Booking Records</div>
      <div style="font-size:.8rem;color:var(--r-muted);">{{ $total }} booking{{ $total != 1 ? 's' : '' }} found</div>
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
            <th>Status</th>
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
              @if($booking->tour->destination ?? false)
                <div style="font-size:.75rem;color:var(--r-muted);"><i class="fas fa-map-marker-alt me-1"></i>{{ $booking->tour->destination->name }}</div>
              @endif
            </td>
            <td style="text-align:center;font-weight:600;">{{ $booking->person_count }}</td>
            <td><span class="r-amount">৳{{ number_format($booking->total_amount, 0) }}</span></td>
            <td>
              @php $bs = $booking->booking_status; @endphp
              <span class="r-badge r-badge-{{ $bs }}">
                <i class="fas fa-circle" style="font-size:.4rem;"></i>{{ ucfirst($bs) }}
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
              <i class="fas fa-chart-bar"></i>
              <p>No booking records found for the selected filters.</p>
            </div>
          </td></tr>
          @endforelse
        </tbody>
        @if($total > 0)
        <tfoot>
          <tr style="background:var(--r-surface2);">
            <td colspan="5" style="padding:12px 18px;font-size:.82rem;color:var(--r-muted);font-weight:600;">TOTAL ({{ $total }} bookings)</td>
            <td style="padding:12px 18px;"><span class="r-amount" style="font-size:1rem;">৳{{ number_format($totalAmt, 0) }}</span></td>
            <td colspan="2"></td>
          </tr>
        </tfoot>
        @endif
      </table>
    </div>
  </div>

</div>
@endsection