@extends('layouts.admin')
@section('title', 'Coupons')
@section('page')

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');

:root {
  --c-surface:   #1a1d27;
  --c-surface2:  #222636;
  --c-border:    rgba(255,255,255,.07);
  --c-accent:    #10b981;
  --c-accent2:   #34d399;
  --c-success:   #22c55e;
  --c-danger:    #ef4444;
  --c-warning:   #f59e0b;
  --c-info:      #38bdf8;
  --c-fixed:     #f97316;
  --c-percent:   #8b5cf6;
  --c-text:      #e2e8f0;
  --c-muted:     #64748b;
  --c-radius:    14px;
  --c-radius-sm: 8px;
  --c-shadow:    0 8px 32px rgba(0,0,0,.45);
}

.c-wrap { font-family:'Plus Jakarta Sans',sans-serif; color:var(--c-text); }

/* header */
.c-header {
  background: linear-gradient(135deg,#022c22 0%,#064e3b 50%,#065f46 100%);
  border-radius:var(--c-radius); padding:28px 32px;
  margin-bottom:24px; position:relative; overflow:hidden; box-shadow:var(--c-shadow);
}
.c-header::before {
  content:''; position:absolute; inset:0;
  background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%2310b981' fill-opacity='0.06'%3E%3Cpath d='M30 5 L55 20 L55 40 L30 55 L5 40 L5 20 Z'/%3E%3C/g%3E%3C/svg%3E");
}
.c-header::after {
  content:''; position:absolute; right:-40px; top:-40px;
  width:200px; height:200px; border-radius:50%;
  background:radial-gradient(circle,rgba(16,185,129,.18) 0%,transparent 70%);
}
.c-header .title {
  font-size:1.5rem; font-weight:700; position:relative; z-index:1;
  background:linear-gradient(90deg,#fff,var(--c-accent2));
  -webkit-background-clip:text; -webkit-text-fill-color:transparent;
}
.c-header .subtitle { color:rgba(255,255,255,.45); font-size:.85rem; margin-top:4px; position:relative; z-index:1; }

.stat-pill {
  display:inline-flex; align-items:center; gap:8px;
  background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.1);
  border-radius:40px; padding:6px 16px;
  font-size:.8rem; font-weight:600; color:#fff; position:relative; z-index:1;
}
.stat-pill .dot { width:8px; height:8px; border-radius:50%; }

/* table card */
.c-card {
  background:var(--c-surface); border:1px solid var(--c-border);
  border-radius:var(--c-radius); overflow:hidden; box-shadow:var(--c-shadow);
}
.c-search-bar {
  padding:16px 20px; border-bottom:1px solid var(--c-border);
  display:flex; align-items:center; gap:12px; flex-wrap:wrap; justify-content:space-between;
}
.c-search-wrap { position:relative; }
.c-search-wrap .si { position:absolute; left:11px; top:50%; transform:translateY(-50%); color:var(--c-muted); font-size:.8rem; }
.c-search-input {
  background:var(--c-surface2); border:1px solid var(--c-border);
  border-radius:var(--c-radius-sm); padding:8px 14px 8px 36px;
  color:var(--c-text); font-family:inherit; font-size:.875rem;
  width:240px; outline:none; transition:border-color .2s;
}
.c-search-input:focus { border-color:var(--c-accent); box-shadow:0 0 0 3px rgba(16,185,129,.12); }

.c-filter-tabs { display:flex; gap:6px; }
.c-filter-tab {
  background:var(--c-surface2); border:1px solid var(--c-border);
  border-radius:20px; padding:5px 14px; font-size:.78rem;
  font-weight:600; color:var(--c-muted); cursor:pointer;
  font-family:inherit; transition:all .2s;
}
.c-filter-tab:hover,.c-filter-tab.active { background:var(--c-accent); color:#022c22; border-color:var(--c-accent); }

/* table */
.c-table { width:100%; border-collapse:collapse; }
.c-table thead tr { background:var(--c-surface2); }
.c-table th { padding:13px 18px; text-align:left; font-size:.72rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:var(--c-muted); white-space:nowrap; }
.c-table td { padding:13px 18px; vertical-align:middle; border-bottom:1px solid var(--c-border); font-size:.875rem; }
.c-table tbody tr { transition:background .15s; }
.c-table tbody tr:hover { background:rgba(16,185,129,.04); }
.c-table tbody tr:last-child td { border-bottom:none; }

/* coupon code */
.c-code-pill {
  display:inline-flex; align-items:center; gap:8px;
  background:var(--c-surface2); border:1px solid var(--c-border);
  border-radius:8px; padding:6px 12px;
  font-family:'JetBrains Mono',monospace; font-size:.85rem;
  font-weight:700; color:var(--c-accent2); letter-spacing:.05em;
}
.c-code-copy {
  background:none; border:none; color:var(--c-muted); cursor:pointer;
  padding:0; font-size:.75rem; transition:color .2s;
}
.c-code-copy:hover { color:var(--c-accent); }

/* type badge */
.c-badge { display:inline-flex; align-items:center; gap:5px; padding:4px 12px; border-radius:20px; font-size:.75rem; font-weight:700; }
.c-badge-fixed   { background:rgba(249,115,22,.12); color:#fdba74; border:1px solid rgba(249,115,22,.25); }
.c-badge-percent { background:rgba(139,92,246,.12); color:#c4b5fd; border:1px solid rgba(139,92,246,.25); }
.c-badge-active   { background:rgba(34,197,94,.12);  color:#86efac; border:1px solid rgba(34,197,94,.25); }
.c-badge-inactive { background:rgba(239,68,68,.12);  color:#fca5a5; border:1px solid rgba(239,68,68,.25); }
.c-badge-expired  { background:rgba(100,116,139,.1); color:#94a3b8; border:1px solid rgba(100,116,139,.2); }

/* value display */
.c-value { font-family:'JetBrains Mono',monospace; font-weight:700; font-size:.9rem; }
.c-value-fixed   { color:#fdba74; }
.c-value-percent { color:#c4b5fd; }

/* usage bar */
.c-usage-wrap { display:flex; align-items:center; gap:8px; min-width:100px; }
.c-usage-bar  { flex:1; height:4px; background:var(--c-surface2); border-radius:2px; overflow:hidden; }
.c-usage-fill { height:100%; border-radius:2px; }
.c-usage-num  { font-size:.78rem; font-family:'JetBrains Mono',monospace; color:var(--c-muted); white-space:nowrap; }

/* date range */
.c-date-range { font-size:.78rem; color:var(--c-muted); }
.c-date-range i { color:var(--c-accent); font-size:.65rem; margin-right:3px; }
.c-date-expired { color:var(--c-danger) !important; }

/* buttons */
.c-btn { display:inline-flex; align-items:center; gap:6px; border:none; cursor:pointer; font-family:inherit; font-weight:600; border-radius:var(--c-radius-sm); transition:all .2s; text-decoration:none; }
.c-btn-primary { background:var(--c-accent); color:#022c22; padding:9px 18px; font-size:.85rem; }
.c-btn-primary:hover { background:var(--c-accent2); transform:translateY(-1px); box-shadow:0 4px 14px rgba(16,185,129,.35); color:#022c22; }
.c-btn-outline { background:transparent; color:var(--c-text); border:1px solid var(--c-border); padding:9px 14px; font-size:.82rem; }
.c-btn-outline:hover { background:var(--c-surface2); color:var(--c-text); }
.c-btn-icon { background:var(--c-surface2); color:var(--c-muted); border:1px solid var(--c-border); padding:6px 10px; font-size:.78rem; border-radius:6px; }
.c-btn-icon-edit:hover { color:var(--c-warning); border-color:rgba(245,158,11,.3); }
.c-btn-danger-ghost { background:rgba(239,68,68,.1); color:#fca5a5; border:1px solid rgba(239,68,68,.2); padding:6px 10px; font-size:.78rem; border-radius:6px; }
.c-btn-danger-ghost:hover { background:rgba(239,68,68,.2); }
.c-actions-cell { display:flex; gap:6px; align-items:center; }

/* MODAL */
.c-modal-overlay {
  position:fixed; inset:0; z-index:9999;
  background:rgba(0,0,0,.72); backdrop-filter:blur(6px);
  display:flex; align-items:center; justify-content:center;
  opacity:0; pointer-events:none; transition:opacity .25s;
}
.c-modal-overlay.open { opacity:1; pointer-events:auto; }
.c-modal {
  background:var(--c-surface); border:1px solid var(--c-border);
  border-radius:18px; width:min(620px,96vw); max-height:90vh; overflow-y:auto;
  box-shadow:0 24px 64px rgba(0,0,0,.6);
  transform:translateY(24px) scale(.97);
  transition:transform .3s cubic-bezier(.34,1.56,.64,1);
}
.c-modal-overlay.open .c-modal { transform:translateY(0) scale(1); }
.c-modal-header { padding:22px 28px 18px; border-bottom:1px solid var(--c-border); display:flex; align-items:center; justify-content:space-between; }
.c-modal-title  { font-size:1.1rem; font-weight:700; }
.c-modal-close  { background:var(--c-surface2); border:1px solid var(--c-border); color:var(--c-muted); width:32px; height:32px; border-radius:8px; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:all .2s; }
.c-modal-close:hover { color:var(--c-text); }
.c-modal-body   { padding:24px 28px; }
.c-modal-footer { padding:18px 28px; border-top:1px solid var(--c-border); display:flex; gap:10px; justify-content:flex-end; }

/* form fields */
.c-field { margin-bottom:18px; }
.c-field label { display:block; font-size:.8rem; font-weight:600; color:var(--c-muted); margin-bottom:7px; text-transform:uppercase; letter-spacing:.06em; }
.c-field input,.c-field select {
  width:100%; background:var(--c-surface2); border:1px solid var(--c-border);
  border-radius:var(--c-radius-sm); padding:10px 14px; color:var(--c-text);
  font-family:inherit; font-size:.875rem; outline:none;
  transition:border-color .2s,box-shadow .2s;
}
.c-field input[type="text"].mono-input { font-family:'JetBrains Mono',monospace; text-transform:uppercase; letter-spacing:.06em; }
.c-field input:focus,.c-field select:focus { border-color:var(--c-accent); box-shadow:0 0 0 3px rgba(16,185,129,.12); }
.c-field input[type="date"]::-webkit-calendar-picker-indicator { filter:invert(1); opacity:.5; cursor:pointer; }
.c-field .err { color:#fca5a5; font-size:.78rem; margin-top:5px; }
.c-grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:0 18px; }
.c-grid-3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:0 18px; }
.c-section-title { font-size:.7rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:var(--c-accent); margin:4px 0 16px; display:flex; align-items:center; gap:8px; }
.c-section-title::after { content:''; flex:1; height:1px; background:var(--c-border); }

/* type toggle hint */
.c-type-hint { display:flex; gap:10px; margin-top:8px; }
.c-type-chip { padding:4px 12px; border-radius:20px; font-size:.75rem; font-weight:600; cursor:pointer; border:1px solid var(--c-border); background:var(--c-surface2); color:var(--c-muted); transition:all .2s; }
.c-type-chip.active-fixed   { background:rgba(249,115,22,.15); color:#fdba74; border-color:rgba(249,115,22,.3); }
.c-type-chip.active-percent { background:rgba(139,92,246,.15); color:#c4b5fd; border-color:rgba(139,92,246,.3); }

/* delete modal */
.c-delete-icon { width:64px; height:64px; border-radius:50%; background:rgba(239,68,68,.12); border:2px solid rgba(239,68,68,.25); display:flex; align-items:center; justify-content:center; font-size:1.5rem; color:#fca5a5; margin:0 auto 18px; }

/* toast */
#c-toast-container { position:fixed; bottom:28px; right:28px; z-index:99999; display:flex; flex-direction:column; gap:10px; }
.c-toast { display:flex; align-items:center; gap:12px; background:var(--c-surface); border:1px solid var(--c-border); border-radius:12px; padding:14px 18px; min-width:280px; box-shadow:0 8px 30px rgba(0,0,0,.5); transform:translateX(120%); transition:transform .35s cubic-bezier(.34,1.56,.64,1); font-family:'Plus Jakarta Sans',sans-serif; position:relative; overflow:hidden; }
.c-toast.show { transform:translateX(0); }
.c-toast-icon { width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:.95rem; }
.c-toast-success .c-toast-icon { background:rgba(34,197,94,.15);  color:var(--c-success); }
.c-toast-danger  .c-toast-icon { background:rgba(239,68,68,.15);   color:var(--c-danger); }
.c-toast-title { font-size:.875rem; font-weight:700; color:var(--c-text); }
.c-toast-msg   { font-size:.78rem;  color:var(--c-muted); margin-top:2px; }
.c-toast-bar   { position:absolute; bottom:0; left:0; height:3px; border-radius:0 0 12px 12px; animation:cBar 3.5s linear forwards; }
.c-toast-success .c-toast-bar { background:var(--c-success); }
.c-toast-danger  .c-toast-bar { background:var(--c-danger); }
@keyframes cBar { from{width:100%} to{width:0%} }
.c-modal::-webkit-scrollbar { width:5px; }
.c-modal::-webkit-scrollbar-thumb { background:var(--c-border); border-radius:10px; }
.c-empty { text-align:center; padding:60px 20px; color:var(--c-muted); }
.c-empty i { font-size:2.5rem; margin-bottom:14px; opacity:.4; display:block; }
</style>

@if(session('success'))<div id="flash-success" data-msg="{{ session('success') }}"></div>@endif
@if(session('error'))<div id="flash-error"   data-msg="{{ session('error') }}"></div>@endif

<div class="c-wrap">

  {{-- Header --}}
  <div class="c-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
      <div>
        <div class="title"><i class="fas fa-ticket-alt me-2"></i>Coupon Manager</div>
        <div class="subtitle">Create and manage discount coupons for tour bookings</div>
        <div class="d-flex gap-2 mt-3 flex-wrap">
          <span class="stat-pill"><span class="dot" style="background:var(--c-success)"></span>{{ $coupons->where('status',1)->count() }} Active</span>
          <span class="stat-pill"><span class="dot" style="background:var(--c-fixed)"></span>{{ $coupons->where('type','fixed')->count() }} Fixed</span>
          <span class="stat-pill"><span class="dot" style="background:var(--c-percent)"></span>{{ $coupons->where('type','percent')->count() }} Percent</span>
          <span class="stat-pill"><span class="dot" style="background:var(--c-accent)"></span>{{ $coupons->count() }} Total</span>
        </div>
      </div>
      <div style="position:relative;z-index:1;">
        <button class="c-btn c-btn-primary" onclick="openAddModal()">
          <i class="fas fa-plus"></i> Add Coupon
        </button>
      </div>
    </div>
  </div>

  {{-- Table Card --}}
  <div class="c-card">
    <div class="c-search-bar">
      <div class="d-flex gap-3 align-items-center flex-wrap">
        <div class="c-search-wrap">
          <i class="fas fa-search si"></i>
          <input type="text" class="c-search-input" id="c-search" placeholder="Search coupons...">
        </div>
        <div class="c-filter-tabs">
          <button class="c-filter-tab active" data-filter="all">All</button>
          <button class="c-filter-tab" data-filter="active">Active</button>
          <button class="c-filter-tab" data-filter="inactive">Inactive</button>
          <button class="c-filter-tab" data-filter="fixed">Fixed ৳</button>
          <button class="c-filter-tab" data-filter="percent">Percent %</button>
        </div>
      </div>
      <span style="font-size:.8rem;color:var(--c-muted);" id="c-count"></span>
    </div>

    <div style="overflow-x:auto;">
      <table class="c-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Coupon Code</th>
            <th>Type & Value</th>
            <th>Usage</th>
            <th>Valid Period</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="c-tbody">
          @forelse($coupons as $i => $coupon)
          @php
            $now      = now();
            $isExpired= $coupon->end_date && \Carbon\Carbon::parse($coupon->end_date)->isPast();
            $statusStr= $coupon->status ? 'active' : 'inactive';
            $usagePct = $coupon->max_usage ? min(100, ($coupon->used_count ?? 0) / $coupon->max_usage * 100) : 0;
          @endphp
          <tr
            data-search="{{ strtolower($coupon->code) }}"
            data-status="{{ $statusStr }}"
            data-type="{{ $coupon->type }}">
            <td style="color:var(--c-muted);font-size:.8rem;">{{ $i + 1 }}</td>

            <td>
              <div class="c-code-pill">
                <i class="fas fa-tag" style="font-size:.7rem;opacity:.6;"></i>
                {{ strtoupper($coupon->code) }}
                <button class="c-code-copy" onclick="copyCode('{{ strtoupper($coupon->code) }}')" title="Copy code">
                  <i class="fas fa-copy"></i>
                </button>
              </div>
            </td>

            <td>
              <span class="c-badge {{ $coupon->type === 'fixed' ? 'c-badge-fixed' : 'c-badge-percent' }}">
                <i class="fas {{ $coupon->type === 'fixed' ? 'fa-taka-sign' : 'fa-percent' }}" style="font-size:.65rem;"></i>
                {{ ucfirst($coupon->type) }}
              </span>
              <div class="c-value {{ $coupon->type === 'fixed' ? 'c-value-fixed' : 'c-value-percent' }}" style="margin-top:5px;">
                @if($coupon->type === 'fixed')
                  ৳{{ number_format($coupon->value, 0) }}
                @else
                  {{ $coupon->value }}% off
                @endif
              </div>
            </td>

            <td>
              @if($coupon->max_usage)
                <div class="c-usage-wrap">
                  <div class="c-usage-bar">
                    <div class="c-usage-fill" style="width:{{ $usagePct }}%;background:{{ $usagePct >= 90 ? 'var(--c-danger)' : ($usagePct >= 60 ? 'var(--c-warning)' : 'var(--c-accent)') }};"></div>
                  </div>
                  <span class="c-usage-num">{{ $coupon->used_count ?? 0 }}/{{ $coupon->max_usage }}</span>
                </div>
              @else
                <span style="color:var(--c-muted);font-size:.8rem;"><i class="fas fa-infinity me-1"></i>Unlimited</span>
              @endif
            </td>

            <td>
              @if($coupon->start_date || $coupon->end_date)
                @if($coupon->start_date)
                  <div class="c-date-range"><i class="fas fa-play"></i>{{ \Carbon\Carbon::parse($coupon->start_date)->format('d M Y') }}</div>
                @endif
                @if($coupon->end_date)
                  <div class="c-date-range {{ $isExpired ? 'c-date-expired' : '' }}">
                    <i class="fas fa-stop"></i>{{ \Carbon\Carbon::parse($coupon->end_date)->format('d M Y') }}
                    @if($isExpired) <span style="font-size:.7rem;">(Expired)</span> @endif
                  </div>
                @endif
              @else
                <span style="color:var(--c-muted);font-size:.8rem;"><i class="fas fa-infinity me-1"></i>No Expiry</span>
              @endif
            </td>

            <td>
              @if($isExpired)
                <span class="c-badge c-badge-expired"><i class="fas fa-clock" style="font-size:.5rem;"></i>Expired</span>
              @elseif($coupon->status)
                <span class="c-badge c-badge-active"><i class="fas fa-circle" style="font-size:.4rem;"></i>Active</span>
              @else
                <span class="c-badge c-badge-inactive"><i class="fas fa-circle" style="font-size:.4rem;"></i>Inactive</span>
              @endif
            </td>

            <td>
              <div class="c-actions-cell">
                <button class="c-btn c-btn-icon c-btn-icon-edit" title="Edit"
                  onclick="openEditModal({
                    id: '{{ $coupon->id }}',
                    code: {{ json_encode($coupon->code) }},
                    type: '{{ $coupon->type }}',
                    value: '{{ $coupon->value }}',
                    max_usage: '{{ $coupon->max_usage ?? '' }}',
                    start_date: '{{ $coupon->start_date ?? '' }}',
                    end_date: '{{ $coupon->end_date ?? '' }}',
                    status: '{{ $coupon->status }}'
                  })">
                  <i class="fas fa-pen"></i>
                </button>
                <button class="c-btn c-btn-danger-ghost" title="Delete"
                  onclick="openDeleteModal('{{ $coupon->id }}', {{ json_encode($coupon->code) }})">
                  <i class="fas fa-trash-alt"></i>
                </button>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="7">
            <div class="c-empty">
              <i class="fas fa-ticket-alt"></i>
              <p>No coupons found. Create your first coupon.</p>
            </div>
          </td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

</div>

{{-- ═══ ADD MODAL ═══ --}}
<div class="c-modal-overlay" id="addModal">
  <div class="c-modal">
    <div class="c-modal-header">
      <div class="c-modal-title"><i class="fas fa-ticket-alt me-2" style="color:var(--c-accent)"></i>Create New Coupon</div>
      <button class="c-modal-close" onclick="closeModal('addModal')"><i class="fas fa-times"></i></button>
    </div>
    <form method="POST" action="{{ route('admin.coupons.store') }}">
      @csrf
      <div class="c-modal-body">

        <div class="c-section-title"><i class="fas fa-tag"></i> Coupon Details</div>
        <div class="c-grid-2">
          <div class="c-field">
            <label>Coupon Code <span style="color:var(--c-danger)">*</span></label>
            <input type="text" name="code" value="{{ old('code') }}"
              placeholder="e.g. SAVE20" class="mono-input"
              style="font-family:'JetBrains Mono',monospace;text-transform:uppercase;letter-spacing:.06em;">
            @error('code')<div class="err">{{ $message }}</div>@enderror
          </div>
          <div class="c-field">
            <label>Discount Type <span style="color:var(--c-danger)">*</span></label>
            <select name="type" id="add_type" onchange="updateTypeChip('add')">
              <option value="">— Select Type —</option>
              <option value="fixed"   {{ old('type') == 'fixed'   ? 'selected' : '' }}>Fixed Amount (৳)</option>
              <option value="percent" {{ old('type') == 'percent' ? 'selected' : '' }}>Percentage (%)</option>
            </select>
            <div class="c-type-hint">
              <span class="c-type-chip" id="add-chip-fixed">৳ Fixed</span>
              <span class="c-type-chip" id="add-chip-percent">% Percent</span>
            </div>
            @error('type')<div class="err">{{ $message }}</div>@enderror
          </div>
        </div>

        <div class="c-grid-2">
          <div class="c-field">
            <label>Discount Value <span style="color:var(--c-danger)">*</span></label>
            <input type="number" name="value" value="{{ old('value') }}" placeholder="e.g. 500 or 15" step="0.01" min="0">
            @error('value')<div class="err">{{ $message }}</div>@enderror
          </div>
          <div class="c-field">
            <label>Max Usage <span style="color:var(--c-muted);font-weight:400;">(optional)</span></label>
            <input type="number" name="max_usage" value="{{ old('max_usage') }}" placeholder="Unlimited if empty" min="1">
          </div>
        </div>

        <div class="c-section-title"><i class="fas fa-calendar-alt"></i> Validity Period</div>
        <div class="c-grid-2">
          <div class="c-field">
            <label>Start Date</label>
            <input type="date" name="start_date" value="{{ old('start_date') }}">
          </div>
          <div class="c-field">
            <label>End Date</label>
            <input type="date" name="end_date" value="{{ old('end_date') }}">
          </div>
        </div>

        <div class="c-field">
          <label>Status</label>
          <select name="status">
            <option value="1" {{ old('status', 1) == 1 ? 'selected' : '' }}>Active</option>
            <option value="0" {{ old('status') == 0   ? 'selected' : '' }}>Inactive</option>
          </select>
        </div>

      </div>
      <div class="c-modal-footer">
        <button type="button" class="c-btn c-btn-outline" onclick="closeModal('addModal')">Cancel</button>
        <button type="submit" class="c-btn c-btn-primary"><i class="fas fa-save"></i> Create Coupon</button>
      </div>
    </form>
  </div>
</div>

{{-- ═══ EDIT MODAL ═══ --}}
<div class="c-modal-overlay" id="editModal">
  <div class="c-modal">
    <div class="c-modal-header">
      <div class="c-modal-title"><i class="fas fa-pen me-2" style="color:var(--c-warning)"></i>Edit Coupon</div>
      <button class="c-modal-close" onclick="closeModal('editModal')"><i class="fas fa-times"></i></button>
    </div>
    <form method="POST" id="editForm">
      @csrf
      @method('PUT')
      <input type="hidden" name="id" id="edit_id">
      <div class="c-modal-body">

        <div class="c-section-title"><i class="fas fa-tag"></i> Coupon Details</div>
        <div class="c-grid-2">
          <div class="c-field">
            <label>Coupon Code <span style="color:var(--c-danger)">*</span></label>
            <input type="text" name="code" id="edit_code"
              style="font-family:'JetBrains Mono',monospace;text-transform:uppercase;letter-spacing:.06em;">
          </div>
          <div class="c-field">
            <label>Discount Type <span style="color:var(--c-danger)">*</span></label>
            <select name="type" id="edit_type" onchange="updateTypeChip('edit')">
              <option value="fixed">Fixed Amount (৳)</option>
              <option value="percent">Percentage (%)</option>
            </select>
            <div class="c-type-hint">
              <span class="c-type-chip" id="edit-chip-fixed">৳ Fixed</span>
              <span class="c-type-chip" id="edit-chip-percent">% Percent</span>
            </div>
          </div>
        </div>

        <div class="c-grid-2">
          <div class="c-field">
            <label>Discount Value <span style="color:var(--c-danger)">*</span></label>
            <input type="number" name="value" id="edit_value" step="0.01" min="0">
          </div>
          <div class="c-field">
            <label>Max Usage</label>
            <input type="number" name="max_usage" id="edit_max_usage" placeholder="Unlimited if empty" min="1">
          </div>
        </div>

        <div class="c-section-title"><i class="fas fa-calendar-alt"></i> Validity Period</div>
        <div class="c-grid-2">
          <div class="c-field">
            <label>Start Date</label>
            <input type="date" name="start_date" id="edit_start_date">
          </div>
          <div class="c-field">
            <label>End Date</label>
            <input type="date" name="end_date" id="edit_end_date">
          </div>
        </div>

        <div class="c-field">
          <label>Status</label>
          <select name="status" id="edit_status">
            <option value="1">Active</option>
            <option value="0">Inactive</option>
          </select>
        </div>

      </div>
      <div class="c-modal-footer">
        <button type="button" class="c-btn c-btn-outline" onclick="closeModal('editModal')">Cancel</button>
        <button type="submit" class="c-btn c-btn-primary" style="background:var(--c-warning);color:#1a1d27;">
          <i class="fas fa-save"></i> Update Coupon
        </button>
      </div>
    </form>
  </div>
</div>

{{-- ═══ DELETE MODAL ═══ --}}
<div class="c-modal-overlay" id="deleteModal">
  <div class="c-modal" style="width:min(420px,96vw);">
    <div class="c-modal-body" style="text-align:center;padding:40px 28px 28px;">
      <div class="c-delete-icon"><i class="fas fa-ticket-alt"></i></div>
      <h5 style="font-weight:700;margin-bottom:8px;">Delete Coupon?</h5>
      <p style="color:var(--c-muted);font-size:.88rem;margin-bottom:4px;">
        Coupon <strong id="delete-code" style="color:var(--c-text);font-family:'JetBrains Mono',monospace;"></strong> will be permanently deleted.
      </p>
      <p style="color:var(--c-muted);font-size:.8rem;">This action <strong style="color:var(--c-danger)">cannot be undone</strong>.</p>
    </div>
    <div class="c-modal-footer" style="justify-content:center;gap:14px;">
      <button class="c-btn c-btn-outline" onclick="closeModal('deleteModal')"><i class="fas fa-times"></i> Cancel</button>
      <form id="deleteForm" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="c-btn c-btn-primary" style="background:var(--c-danger);">
          <i class="fas fa-trash-alt"></i> Yes, Delete
        </button>
      </form>
    </div>
  </div>
</div>

<div id="c-toast-container"></div>

<script>
// ── Modal helpers ──────────────────────────────────
function openModal(id)  { document.getElementById(id).classList.add('open'); document.body.style.overflow='hidden'; }
function closeModal(id) { document.getElementById(id).classList.remove('open'); document.body.style.overflow=''; }
document.querySelectorAll('.c-modal-overlay').forEach(el => {
  el.addEventListener('click', e => { if(e.target===el) closeModal(el.id); });
});
document.addEventListener('keydown', e => {
  if(e.key==='Escape') document.querySelectorAll('.c-modal-overlay.open').forEach(el=>closeModal(el.id));
});

// ── Type chip visual ──────────────────────────────
function updateTypeChip(prefix) {
  var val = document.getElementById(prefix+'_type').value;
  var cf  = document.getElementById(prefix+'-chip-fixed');
  var cp  = document.getElementById(prefix+'-chip-percent');
  cf.className = 'c-type-chip' + (val==='fixed'   ? ' active-fixed'   : '');
  cp.className = 'c-type-chip' + (val==='percent' ? ' active-percent' : '');
}

// ── Add modal ─────────────────────────────────────
function openAddModal() { openModal('addModal'); updateTypeChip('add'); }

@if($errors->any() && old('_method') !== 'PUT')
  openAddModal();
@endif

// ── Edit modal ────────────────────────────────────
function openEditModal(d) {
  document.getElementById('edit_id').value          = d.id;
  document.getElementById('edit_code').value        = d.code.toUpperCase();
  document.getElementById('edit_type').value        = d.type;
  document.getElementById('edit_value').value       = d.value;
  document.getElementById('edit_max_usage').value   = d.max_usage;
  document.getElementById('edit_start_date').value  = d.start_date;
  document.getElementById('edit_end_date').value    = d.end_date;
  document.getElementById('edit_status').value      = d.status;
  document.getElementById('editForm').action        = '/admin/coupons/' + d.id;
  updateTypeChip('edit');
  openModal('editModal');
}

// ── Delete modal ──────────────────────────────────
function openDeleteModal(id, code) {
  document.getElementById('delete-code').textContent = code.toUpperCase();
  document.getElementById('deleteForm').action = '/admin/coupons/' + id;
  openModal('deleteModal');
}

// ── Copy code ─────────────────────────────────────
function copyCode(code) {
  navigator.clipboard.writeText(code).then(() => {
    showToast('success', 'Copied!', code + ' copied to clipboard.');
  });
}

// uppercase code input
document.querySelectorAll('input[name="code"]').forEach(el => {
  el.addEventListener('input', function(){ this.value = this.value.toUpperCase(); });
});

// ── Search + filter ───────────────────────────────
(function(){
  var inp   = document.getElementById('c-search');
  var rows  = Array.from(document.querySelectorAll('#c-tbody tr[data-search]'));
  var cnt   = document.getElementById('c-count');
  var tabs  = document.querySelectorAll('.c-filter-tab');
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
    var v = 0;
    rows.forEach(r => {
      var matchQ = !q || r.dataset.search.includes(q);
      var matchF = active==='all'
        || (active==='fixed'   && r.dataset.type==='fixed')
        || (active==='percent' && r.dataset.type==='percent')
        || (active==='active'  && r.dataset.status==='active')
        || (active==='inactive'&& r.dataset.status==='inactive');
      r.style.display = (matchQ && matchF) ? '' : 'none';
      if(matchQ && matchF) v++;
    });
    cnt.textContent = v + ' of ' + rows.length + ' coupons';
  }
  update();
})();

// ── Toast ─────────────────────────────────────────
function showToast(type, title, msg) {
  var icons = {success:'fas fa-check-circle', danger:'fas fa-exclamation-circle'};
  var c = document.getElementById('c-toast-container');
  var t = document.createElement('div');
  t.className = 'c-toast c-toast-' + type;
  t.innerHTML = `<div class="c-toast-icon"><i class="${icons[type]}"></i></div>
    <div><div class="c-toast-title">${title}</div><div class="c-toast-msg">${msg}</div></div>
    <span class="c-toast-bar"></span>`;
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