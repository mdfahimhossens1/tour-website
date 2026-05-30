@extends('layouts.admin')
@section('title', 'All Tour Dates')
@section('page')

@php
  $totalActive   = $dates->where('status', 1)->count();
  $totalInactive = $dates->where('status', 0)->count();
@endphp

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');

:root {
  --t-surface:   #1a1d27;
  --t-surface2:  #222636;
  --t-border:    rgba(255,255,255,.07);
  --t-accent:    #06b6d4;
  --t-accent2:   #22d3ee;
  --t-success:   #22c55e;
  --t-danger:    #ef4444;
  --t-warning:   #f59e0b;
  --t-text:      #e2e8f0;
  --t-muted:     #64748b;
  --t-radius:    14px;
  --t-radius-sm: 8px;
  --t-shadow:    0 8px 32px rgba(0,0,0,.45);
}

.t-wrap { font-family:'Plus Jakarta Sans',sans-serif; color:var(--t-text); }

/* header */
.t-header {
  background: linear-gradient(135deg,#0c1445 0%,#0e3460 50%,#0a4a6e 100%);
  border-radius:var(--t-radius); padding:28px 32px;
  margin-bottom:24px; position:relative; overflow:hidden;
  box-shadow:var(--t-shadow);
}
.t-header::before {
  content:''; position:absolute; inset:0;
  background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%2306b6d4' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='20'/%3E%3Ccircle cx='0' cy='0' r='10'/%3E%3Ccircle cx='60' cy='60' r='10'/%3E%3C/g%3E%3C/svg%3E");
}
.t-header::after {
  content:''; position:absolute; right:-40px; top:-40px;
  width:200px; height:200px; border-radius:50%;
  background:radial-gradient(circle,rgba(6,182,212,.15) 0%,transparent 70%);
}
.t-header .title {
  font-size:1.5rem; font-weight:700; position:relative; z-index:1;
  background:linear-gradient(90deg,#fff,var(--t-accent2));
  -webkit-background-clip:text; -webkit-text-fill-color:transparent;
}
.t-header .subtitle { color:rgba(255,255,255,.45); font-size:.85rem; margin-top:4px; position:relative; z-index:1; }

.stat-pill {
  display:inline-flex; align-items:center; gap:8px;
  background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.1);
  border-radius:40px; padding:6px 16px;
  font-size:.8rem; font-weight:600; color:#fff; position:relative; z-index:1;
}
.stat-pill .dot { width:8px; height:8px; border-radius:50%; }

/* table card */
.t-table-card {
  background:var(--t-surface); border:1px solid var(--t-border);
  border-radius:var(--t-radius); overflow:hidden; box-shadow:var(--t-shadow);
}
.t-search-bar {
  padding:16px 20px; border-bottom:1px solid var(--t-border);
  display:flex; align-items:center; gap:12px; flex-wrap:wrap;
}
.t-search-wrap { position:relative; }
.t-search-wrap .si { position:absolute; left:11px; top:50%; transform:translateY(-50%); color:var(--t-muted); font-size:.8rem; }
.t-search-input {
  background:var(--t-surface2); border:1px solid var(--t-border);
  border-radius:var(--t-radius-sm); padding:8px 14px 8px 36px;
  color:var(--t-text); font-family:inherit; font-size:.875rem;
  width:260px; outline:none; transition:border-color .2s;
}
.t-search-input:focus { border-color:var(--t-accent); box-shadow:0 0 0 3px rgba(6,182,212,.12); }

.t-filter-tabs { display:flex; gap:6px; }
.t-filter-tab {
  background:var(--t-surface2); border:1px solid var(--t-border);
  border-radius:20px; padding:5px 14px; font-size:.78rem;
  font-weight:600; color:var(--t-muted); cursor:pointer;
  font-family:inherit; transition:all .2s;
}
.t-filter-tab:hover,.t-filter-tab.active { background:var(--t-accent); color:#0c1445; border-color:var(--t-accent); }

.t-table { width:100%; border-collapse:collapse; }
.t-table thead tr { background:var(--t-surface2); }
.t-table th { padding:13px 18px; text-align:left; font-size:.72rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:var(--t-muted); white-space:nowrap; }
.t-table td { padding:14px 18px; vertical-align:middle; border-bottom:1px solid var(--t-border); font-size:.875rem; }
.t-table tbody tr { transition:background .15s; }
.t-table tbody tr:hover { background:rgba(6,182,212,.04); }
.t-table tbody tr:last-child td { border-bottom:none; }

/* tour title cell */
.t-tour-name { font-weight:600; }
.t-tour-sub  { font-size:.75rem; color:var(--t-muted); margin-top:2px; }

/* date range pill */
.t-date-range {
  display:inline-flex; align-items:center; gap:6px;
  background:var(--t-surface2); border:1px solid var(--t-border);
  border-radius:20px; padding:4px 12px; font-size:.8rem;
  font-family:'JetBrains Mono',monospace; color:var(--t-text);
}
.t-date-range i { color:var(--t-accent); font-size:.65rem; }

/* price */
.t-price { font-family:'JetBrains Mono',monospace; font-weight:600; color:var(--t-accent2); }

/* seat bar */
.t-seat-wrap { display:flex; align-items:center; gap:8px; min-width:80px; }
.t-seat-bar  { flex:1; height:4px; background:var(--t-surface2); border-radius:2px; overflow:hidden; }
.t-seat-fill { height:100%; border-radius:2px; background:var(--t-accent); }
.t-seat-num  { font-size:.8rem; font-family:'JetBrains Mono',monospace; color:var(--t-muted); white-space:nowrap; }

/* badges */
.t-badge { display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:20px; font-size:.72rem; font-weight:700; }
.t-badge-active   { background:rgba(34,197,94,.12);  color:#86efac; border:1px solid rgba(34,197,94,.25); }
.t-badge-inactive { background:rgba(239,68,68,.12);  color:#fca5a5; border:1px solid rgba(239,68,68,.25); }
.t-badge-upcoming { background:rgba(6,182,212,.12);  color:#67e8f9; border:1px solid rgba(6,182,212,.25); }
.t-badge-past     { background:rgba(100,116,139,.1); color:#94a3b8; border:1px solid rgba(100,116,139,.2); }

/* action buttons */
.t-btn { display:inline-flex; align-items:center; gap:6px; border:none; cursor:pointer; font-family:inherit; font-weight:600; border-radius:var(--t-radius-sm); transition:all .2s; text-decoration:none; }
.t-btn-primary { background:var(--t-accent); color:#0c1445; padding:9px 18px; font-size:.85rem; }
.t-btn-primary:hover { background:var(--t-accent2); transform:translateY(-1px); box-shadow:0 4px 14px rgba(6,182,212,.35); color:#0c1445; }
.t-btn-outline { background:transparent; color:var(--t-text); border:1px solid var(--t-border); padding:9px 14px; font-size:.82rem; }
.t-btn-outline:hover { background:var(--t-surface2); color:var(--t-text); }
.t-btn-icon { background:var(--t-surface2); color:var(--t-muted); border:1px solid var(--t-border); padding:6px 10px; font-size:.78rem; border-radius:6px; }
.t-btn-icon:hover { color:var(--t-accent); border-color:rgba(6,182,212,.3); }
.t-btn-icon-edit:hover { color:var(--t-warning); border-color:rgba(245,158,11,.3); }
.t-btn-danger-ghost { background:rgba(239,68,68,.1); color:#fca5a5; border:1px solid rgba(239,68,68,.2); padding:6px 10px; font-size:.78rem; border-radius:6px; }
.t-btn-danger-ghost:hover { background:rgba(239,68,68,.2); }
.t-actions-cell { display:flex; gap:6px; align-items:center; }

/* MODAL */
.t-modal-overlay {
  position:fixed; inset:0; z-index:9999;
  background:rgba(0,0,0,.72); backdrop-filter:blur(6px);
  display:flex; align-items:center; justify-content:center;
  opacity:0; pointer-events:none; transition:opacity .25s;
}
.t-modal-overlay.open { opacity:1; pointer-events:auto; }
.t-modal {
  background:var(--t-surface); border:1px solid var(--t-border);
  border-radius:18px; width:min(640px,96vw); max-height:90vh; overflow-y:auto;
  box-shadow:0 24px 64px rgba(0,0,0,.6);
  transform:translateY(24px) scale(.97);
  transition:transform .3s cubic-bezier(.34,1.56,.64,1);
}
.t-modal-overlay.open .t-modal { transform:translateY(0) scale(1); }
.t-modal-header { padding:22px 28px 18px; border-bottom:1px solid var(--t-border); display:flex; align-items:center; justify-content:space-between; }
.t-modal-title  { font-size:1.1rem; font-weight:700; }
.t-modal-close  { background:var(--t-surface2); border:1px solid var(--t-border); color:var(--t-muted); width:32px; height:32px; border-radius:8px; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:all .2s; }
.t-modal-close:hover { color:var(--t-text); }
.t-modal-body   { padding:24px 28px; }
.t-modal-footer { padding:18px 28px; border-top:1px solid var(--t-border); display:flex; gap:10px; justify-content:flex-end; }

/* form fields */
.t-field { margin-bottom:18px; }
.t-field label { display:block; font-size:.8rem; font-weight:600; color:var(--t-muted); margin-bottom:7px; text-transform:uppercase; letter-spacing:.06em; }
.t-field input,.t-field select {
  width:100%; background:var(--t-surface2); border:1px solid var(--t-border);
  border-radius:var(--t-radius-sm); padding:10px 14px; color:var(--t-text);
  font-family:inherit; font-size:.875rem; outline:none;
  transition:border-color .2s,box-shadow .2s;
}
.t-field input:focus,.t-field select:focus { border-color:var(--t-accent); box-shadow:0 0 0 3px rgba(6,182,212,.12); }
/* date input fix dark */
.t-field input[type="date"]::-webkit-calendar-picker-indicator { filter:invert(1); opacity:.5; cursor:pointer; }
.t-field .err { color:#fca5a5; font-size:.78rem; margin-top:5px; }
.t-grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:0 18px; }
.t-grid-3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:0 18px; }
.t-section-title { font-size:.7rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:var(--t-accent); margin:4px 0 16px; display:flex; align-items:center; gap:8px; }
.t-section-title::after { content:''; flex:1; height:1px; background:var(--t-border); }

/* delete modal */
.t-delete-icon { width:64px; height:64px; border-radius:50%; background:rgba(239,68,68,.12); border:2px solid rgba(239,68,68,.25); display:flex; align-items:center; justify-content:center; font-size:1.5rem; color:#fca5a5; margin:0 auto 18px; }

/* toast */
#t-toast-container { position:fixed; bottom:28px; right:28px; z-index:99999; display:flex; flex-direction:column; gap:10px; }
.t-toast { display:flex; align-items:center; gap:12px; background:var(--t-surface); border:1px solid var(--t-border); border-radius:12px; padding:14px 18px; min-width:280px; box-shadow:0 8px 30px rgba(0,0,0,.5); transform:translateX(120%); transition:transform .35s cubic-bezier(.34,1.56,.64,1); font-family:'Plus Jakarta Sans',sans-serif; position:relative; overflow:hidden; }
.t-toast.show { transform:translateX(0); }
.t-toast-icon { width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:.95rem; }
.t-toast-success .t-toast-icon { background:rgba(34,197,94,.15);  color:var(--t-success); }
.t-toast-danger  .t-toast-icon { background:rgba(239,68,68,.15);   color:var(--t-danger); }
.t-toast-title { font-size:.875rem; font-weight:700; color:var(--t-text); }
.t-toast-msg   { font-size:.78rem;  color:var(--t-muted); margin-top:2px; }
.t-toast-bar   { position:absolute; bottom:0; left:0; height:3px; border-radius:0 0 12px 12px; animation:tBar 3.5s linear forwards; }
.t-toast-success .t-toast-bar { background:var(--t-success); }
.t-toast-danger  .t-toast-bar { background:var(--t-danger); }
@keyframes tBar { from{width:100%} to{width:0%} }
.t-modal::-webkit-scrollbar { width:5px; }
.t-modal::-webkit-scrollbar-thumb { background:var(--t-border); border-radius:10px; }
.t-empty { text-align:center; padding:60px 20px; color:var(--t-muted); }
.t-empty i { font-size:2.5rem; margin-bottom:14px; opacity:.4; display:block; }
</style>

@if(session('success'))<div id="flash-success" data-msg="{{ session('success') }}"></div>@endif
@if(session('error'))<div id="flash-error"   data-msg="{{ session('error') }}"></div>@endif

<div class="t-wrap">

  {{-- Header --}}
  <div class="t-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
      <div>
        <div class="title"><i class="fas fa-calendar-alt me-2"></i>Tour Dates</div>
        <div class="subtitle">Schedule and manage departure dates for all tour packages</div>
        <div class="d-flex gap-2 mt-3 flex-wrap">
          <span class="stat-pill"><span class="dot" style="background:var(--t-success)"></span>{{ $totalActive }} Active</span>
          <span class="stat-pill"><span class="dot" style="background:var(--t-danger)"></span>{{ $totalInactive }} Inactive</span>
          <span class="stat-pill"><span class="dot" style="background:var(--t-accent)"></span>{{ $dates->count() }} Total</span>
        </div>
      </div>
      <div style="position:relative;z-index:1;">
        <button class="t-btn t-btn-primary" onclick="openAddModal()">
          <i class="fas fa-plus"></i> Add Date
        </button>
      </div>
    </div>
  </div>

  {{-- Table Card --}}
  <div class="t-table-card">
    <div class="t-search-bar justify-content-between">
      <div class="d-flex gap-3 align-items-center flex-wrap">
        <div class="t-search-wrap">
          <i class="fas fa-search si"></i>
          <input type="text" class="t-search-input" id="t-search" placeholder="Search by tour name...">
        </div>
        <div class="t-filter-tabs">
          <button class="t-filter-tab active" data-filter="all">All</button>
          <button class="t-filter-tab" data-filter="active">Active</button>
          <button class="t-filter-tab" data-filter="inactive">Inactive</button>
        </div>
      </div>
      <span style="font-size:.8rem;color:var(--t-muted);" id="t-count"></span>
    </div>

    <div style="overflow-x:auto;">
      <table class="t-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Tour Package</th>
            <th>Date Range</th>
            <th>Available Seats</th>
            <th>Special Price</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="t-tbody">
          @forelse($dates as $i => $date)
          @php
            $statusStr = $date->status ? 'active' : 'inactive';
            $now       = now();
            $start     = \Carbon\Carbon::parse($date->start_date);
            $end       = \Carbon\Carbon::parse($date->end_date);
            $days      = $start->diffInDays($end);
            $isPast    = $end->isPast();
            $isUpcoming= $start->isFuture();
          @endphp
          <tr
            data-search="{{ strtolower($date->tour->title ?? '') }}"
            data-status="{{ $statusStr }}">
            <td style="color:var(--t-muted);font-size:.8rem;">{{ $i + 1 }}</td>

            <td>
              <div class="t-tour-name">{{ $date->tour->title ?? 'N/A' }}</div>
              @if($days > 0)
                <div class="t-tour-sub"><i class="fas fa-clock me-1"></i>{{ $days }} day{{ $days > 1 ? 's' : '' }}</div>
              @endif
            </td>

            <td>
              <div class="t-date-range">
                <i class="fas fa-calendar-day"></i>
                {{ \Carbon\Carbon::parse($date->start_date)->format('d M Y') }}
                <i class="fas fa-arrow-right"></i>
                {{ \Carbon\Carbon::parse($date->end_date)->format('d M Y') }}
              </div>
              @if($isPast)
                <div style="margin-top:5px;"><span class="t-badge t-badge-past"><i class="fas fa-history" style="font-size:.55rem;"></i>Ended</span></div>
              @elseif($isUpcoming)
                <div style="margin-top:5px;"><span class="t-badge t-badge-upcoming"><i class="fas fa-hourglass-start" style="font-size:.55rem;"></i>Upcoming</span></div>
              @endif
            </td>

            <td>
              <div class="t-seat-wrap">
                <div class="t-seat-bar">
                  @php $pct = min(100, ($date->available_seat / max(1,50)) * 100); @endphp
                  <div class="t-seat-fill" style="width:{{ $pct }}%;background:{{ $pct > 60 ? 'var(--t-success)' : ($pct > 30 ? 'var(--t-warning)' : 'var(--t-danger)') }};"></div>
                </div>
                <span class="t-seat-num">{{ $date->available_seat }}</span>
              </div>
            </td>

            <td>
              @if($date->price)
                <span class="t-price">৳{{ number_format($date->price, 0) }}</span>
              @else
                <span style="color:var(--t-muted);font-size:.8rem;">—</span>
              @endif
            </td>

            <td>
              @if($date->status)
                <span class="t-badge t-badge-active"><i class="fas fa-circle" style="font-size:.4rem;"></i>Active</span>
              @else
                <span class="t-badge t-badge-inactive"><i class="fas fa-circle" style="font-size:.4rem;"></i>Inactive</span>
              @endif
            </td>

            <td>
              <div class="t-actions-cell">
                <button class="t-btn t-btn-icon t-btn-icon-edit" title="Edit"
                  onclick="openEditModal({
                    id: '{{ $date->id }}',
                    tour_id: '{{ $date->tour_id }}',
                    start_date: '{{ $date->start_date }}',
                    end_date: '{{ $date->end_date }}',
                    available_seat: '{{ $date->available_seat }}',
                    price: '{{ $date->price }}',
                    status: '{{ $date->status }}'
                  })">
                  <i class="fas fa-pen"></i>
                </button>
                <button class="t-btn t-btn-danger-ghost" title="Delete"
                  onclick="openDeleteModal('{{ $date->id }}', {{ json_encode($date->tour->title ?? 'this date') }})">
                  <i class="fas fa-trash-alt"></i>
                </button>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7">
              <div class="t-empty">
                <i class="fas fa-calendar-times"></i>
                <p>No tour dates found. Add your first date.</p>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

</div>

{{-- ═══ ADD DATE MODAL ═══ --}}
<div class="t-modal-overlay" id="addModal">
  <div class="t-modal">
    <div class="t-modal-header">
      <div class="t-modal-title">
        <i class="fas fa-calendar-plus me-2" style="color:var(--t-accent)"></i>Add New Tour Date
      </div>
      <button class="t-modal-close" onclick="closeModal('addModal')"><i class="fas fa-times"></i></button>
    </div>

    <form method="POST" action="{{ route('admin.tour.dates.store') }}">
      @csrf
      <div class="t-modal-body">

        <div class="t-section-title"><i class="fas fa-map-marked-alt"></i> Tour Package</div>
        <div class="t-field">
          <label>Select Tour <span style="color:var(--t-danger)">*</span></label>
          <select name="tour_id">
            <option value="">— Choose a Tour Package —</option>
            @foreach($tours as $tour)
              <option value="{{ $tour->id }}" {{ old('tour_id') == $tour->id ? 'selected' : '' }}>
                {{ $tour->title }}
              </option>
            @endforeach
          </select>
          @error('tour_id')<div class="err">{{ $message }}</div>@enderror
        </div>

        <div class="t-section-title"><i class="fas fa-calendar-alt"></i> Schedule</div>
        <div class="t-grid-2">
          <div class="t-field">
            <label>Start Date <span style="color:var(--t-danger)">*</span></label>
            <input type="date" name="start_date" value="{{ old('start_date') }}">
            @error('start_date')<div class="err">{{ $message }}</div>@enderror
          </div>
          <div class="t-field">
            <label>End Date <span style="color:var(--t-danger)">*</span></label>
            <input type="date" name="end_date" value="{{ old('end_date') }}">
            @error('end_date')<div class="err">{{ $message }}</div>@enderror
          </div>
        </div>

        <div class="t-section-title"><i class="fas fa-chair"></i> Pricing & Availability</div>
        <div class="t-grid-3">
          <div class="t-field">
            <label>Available Seats <span style="color:var(--t-danger)">*</span></label>
            <input type="number" name="available_seat" value="{{ old('available_seat') }}" placeholder="30" min="1">
            @error('available_seat')<div class="err">{{ $message }}</div>@enderror
          </div>
          <div class="t-field">
            <label>Special Price (৳)</label>
            <input type="number" step="0.01" name="price" value="{{ old('price') }}" placeholder="Optional">
          </div>
          <div class="t-field">
            <label>Status</label>
            <select name="status">
              <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Active</option>
              <option value="0" {{ old('status') == 0   ? 'selected' : '' }}>Inactive</option>
            </select>
          </div>
        </div>

        {{-- Duration preview --}}
        <div id="duration-preview" style="display:none;background:var(--t-surface2);border:1px solid var(--t-border);border-radius:var(--t-radius-sm);padding:12px 16px;font-size:.85rem;color:var(--t-accent);">
          <i class="fas fa-info-circle me-2"></i>
          <span id="duration-text"></span>
        </div>

      </div>
      <div class="t-modal-footer">
        <button type="button" class="t-btn t-btn-outline" onclick="closeModal('addModal')">Cancel</button>
        <button type="submit" class="t-btn t-btn-primary"><i class="fas fa-save"></i> Save Date</button>
      </div>
    </form>
  </div>
</div>

{{-- ═══ EDIT DATE MODAL ═══ --}}
<div class="t-modal-overlay" id="editModal">
  <div class="t-modal">
    <div class="t-modal-header">
      <div class="t-modal-title">
        <i class="fas fa-calendar-edit me-2" style="color:var(--t-warning)"></i>Edit Tour Date
      </div>
      <button class="t-modal-close" onclick="closeModal('editModal')"><i class="fas fa-times"></i></button>
    </div>

    <form method="POST" id="editDateForm">
      @csrf
      @method('PUT')
      <input type="hidden" name="id" id="edit_id">
      <div class="t-modal-body">

        <div class="t-section-title"><i class="fas fa-map-marked-alt"></i> Tour Package</div>
        <div class="t-field">
          <label>Select Tour <span style="color:var(--t-danger)">*</span></label>
          <select name="tour_id" id="edit_tour_id">
            <option value="">— Choose a Tour Package —</option>
            @foreach($tours as $tour)
              <option value="{{ $tour->id }}">{{ $tour->title }}</option>
            @endforeach
          </select>
        </div>

        <div class="t-section-title"><i class="fas fa-calendar-alt"></i> Schedule</div>
        <div class="t-grid-2">
          <div class="t-field">
            <label>Start Date <span style="color:var(--t-danger)">*</span></label>
            <input type="date" name="start_date" id="edit_start_date">
          </div>
          <div class="t-field">
            <label>End Date <span style="color:var(--t-danger)">*</span></label>
            <input type="date" name="end_date" id="edit_end_date">
          </div>
        </div>

        <div class="t-section-title"><i class="fas fa-chair"></i> Pricing & Availability</div>
        <div class="t-grid-3">
          <div class="t-field">
            <label>Available Seats <span style="color:var(--t-danger)">*</span></label>
            <input type="number" name="available_seat" id="edit_available_seat" min="1">
          </div>
          <div class="t-field">
            <label>Special Price (৳)</label>
            <input type="number" step="0.01" name="price" id="edit_price" placeholder="Optional">
          </div>
          <div class="t-field">
            <label>Status</label>
            <select name="status" id="edit_status">
              <option value="1">Active</option>
              <option value="0">Inactive</option>
            </select>
          </div>
        </div>

      </div>
      <div class="t-modal-footer">
        <button type="button" class="t-btn t-btn-outline" onclick="closeModal('editModal')">Cancel</button>
        <button type="submit" class="t-btn t-btn-primary" style="background:var(--t-warning);color:#0c1445;">
          <i class="fas fa-save"></i> Update Date
        </button>
      </div>
    </form>
  </div>
</div>

{{-- ═══ DELETE MODAL ═══ --}}
<div class="t-modal-overlay" id="deleteModal">
  <div class="t-modal" style="width:min(420px,96vw);">
    <div class="t-modal-body" style="text-align:center;padding:40px 28px 28px;">
      <div class="t-delete-icon"><i class="fas fa-calendar-times"></i></div>
      <h5 style="font-weight:700;margin-bottom:8px;">Delete Tour Date?</h5>
      <p style="color:var(--t-muted);font-size:.88rem;margin-bottom:4px;">
        Tour: <strong id="delete-tour-name" style="color:var(--t-text)"></strong>
      </p>
      <p style="color:var(--t-muted);font-size:.8rem;">This action <strong style="color:var(--t-danger)">cannot be undone</strong>.</p>
    </div>
    <div class="t-modal-footer" style="justify-content:center;gap:14px;">
      <button class="t-btn t-btn-outline" onclick="closeModal('deleteModal')"><i class="fas fa-times"></i> Cancel</button>
      <form id="deleteDateForm" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" class="t-btn t-btn-primary" style="background:var(--t-danger);">
          <i class="fas fa-trash-alt"></i> Yes, Delete
        </button>
      </form>
    </div>
  </div>
</div>

<div id="t-toast-container"></div>

<script>
// ── Modal helpers ───────────────────────────────────
function openModal(id)  { document.getElementById(id).classList.add('open'); document.body.style.overflow='hidden'; }
function closeModal(id) { document.getElementById(id).classList.remove('open'); document.body.style.overflow=''; }

document.querySelectorAll('.t-modal-overlay').forEach(el => {
  el.addEventListener('click', e => { if(e.target===el) closeModal(el.id); });
});
document.addEventListener('keydown', e => {
  if(e.key==='Escape') document.querySelectorAll('.t-modal-overlay.open').forEach(el=>closeModal(el.id));
});

// ── Add modal ───────────────────────────────────────
function openAddModal() { openModal('addModal'); }

@if($errors->any() && old('_method') !== 'PUT')
  openAddModal();
@endif

// ── Duration preview in add modal ──────────────────
['start_date','end_date'].forEach(id => {
  var el = document.querySelector('[name="'+id+'"]');
  if(el) el.addEventListener('change', updateDuration);
});
function updateDuration() {
  var s = document.querySelector('#addModal [name="start_date"]').value;
  var e = document.querySelector('#addModal [name="end_date"]').value;
  var wrap = document.getElementById('duration-preview');
  var txt  = document.getElementById('duration-text');
  if(s && e) {
    var diff = Math.round((new Date(e)-new Date(s))/(1000*60*60*24));
    if(diff > 0) {
      txt.textContent = 'Duration: ' + diff + ' day' + (diff>1?'s':'') + ' (' + diff + ' nights)';
      wrap.style.display = 'block';
    } else if(diff <= 0) {
      txt.textContent = 'End date must be after start date.';
      txt.style.color = 'var(--t-danger)';
      wrap.style.display = 'block';
    }
  } else {
    wrap.style.display = 'none';
  }
}

// ── Edit modal ──────────────────────────────────────
function openEditModal(d) {
  document.getElementById('edit_id').value             = d.id;
  document.getElementById('edit_tour_id').value        = d.tour_id;
  document.getElementById('edit_start_date').value     = d.start_date;
  document.getElementById('edit_end_date').value       = d.end_date;
  document.getElementById('edit_available_seat').value = d.available_seat;
  document.getElementById('edit_price').value          = d.price;
  document.getElementById('edit_status').value         = d.status;
  document.getElementById('editDateForm').action       = '/admin/tour-dates/' + d.id;
  openModal('editModal');
}

// ── Delete modal ────────────────────────────────────
function openDeleteModal(id, tourName) {
  document.getElementById('delete-tour-name').textContent = tourName;
  document.getElementById('deleteDateForm').action = '/admin/tour-dates/' + id;
  openModal('deleteModal');
}

// ── Live search + filter ────────────────────────────
(function(){
  var inp   = document.getElementById('t-search');
  var rows  = Array.from(document.querySelectorAll('#t-tbody tr[data-search]'));
  var count = document.getElementById('t-count');
  var tabs  = document.querySelectorAll('.t-filter-tab');
  var active = 'all';

  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      tabs.forEach(t=>t.classList.remove('active'));
      tab.classList.add('active');
      active = tab.dataset.filter;
      update();
    });
  });
  inp.addEventListener('input', update);

  function update() {
    var q = inp.value.toLowerCase().trim();
    var vis = 0;
    rows.forEach(r => {
      var matchQ = !q || r.dataset.search.includes(q);
      var matchF = active==='all' || r.dataset.status===active;
      r.style.display = (matchQ && matchF) ? '' : 'none';
      if(matchQ && matchF) vis++;
    });
    count.textContent = vis + ' of ' + rows.length + ' dates';
  }
  update();
})();

// ── Toast ───────────────────────────────────────────
function showToast(type, title, msg) {
  var icons = {success:'fas fa-check-circle', danger:'fas fa-exclamation-circle'};
  var c = document.getElementById('t-toast-container');
  var t = document.createElement('div');
  t.className = 't-toast t-toast-' + type;
  t.innerHTML = `<div class="t-toast-icon"><i class="${icons[type]}"></i></div>
    <div><div class="t-toast-title">${title}</div><div class="t-toast-msg">${msg}</div></div>
    <span class="t-toast-bar"></span>`;
  c.appendChild(t);
  setTimeout(()=>t.classList.add('show'),20);
  setTimeout(()=>{ t.classList.remove('show'); setTimeout(()=>t.remove(),400); },3500);
}
(function(){
  var s=document.getElementById('flash-success');
  var e=document.getElementById('flash-error');
  if(s) showToast('success','Success',s.dataset.msg);
  if(e) showToast('danger','Error',e.dataset.msg);
})();
</script>

@endsection