@extends('layouts.admin')
@section('title', 'Booking Details')
@section('page')

@php
  $bs = $booking->booking_status;
  $ps = $booking->payment_status;
  $bsConfig = match($bs) {
    'pending'   => ['cls'=>'bs-pending',   'color'=>'#fcd34d', 'bg'=>'rgba(245,158,11,.12)', 'icon'=>'fas fa-hourglass-half'],
    'confirmed' => ['cls'=>'bs-confirmed', 'color'=>'#c4b5fd', 'bg'=>'rgba(167,139,250,.12)','icon'=>'fas fa-check-circle'],
    'cancelled' => ['cls'=>'bs-cancelled', 'color'=>'#fca5a5', 'bg'=>'rgba(239,68,68,.12)',  'icon'=>'fas fa-times-circle'],
    default     => ['cls'=>'bs-completed', 'color'=>'#86efac', 'bg'=>'rgba(34,197,94,.12)',  'icon'=>'fas fa-flag-checkered'],
  };
  $psConfig = match($ps) {
    'paid'     => ['color'=>'#86efac', 'bg'=>'rgba(34,197,94,.12)',  'icon'=>'fas fa-check-circle'],
    'failed'   => ['color'=>'#fca5a5', 'bg'=>'rgba(239,68,68,.12)',  'icon'=>'fas fa-times-circle'],
    'refunded' => ['color'=>'#7dd3fc', 'bg'=>'rgba(56,189,248,.12)', 'icon'=>'fas fa-undo'],
    default    => ['color'=>'#fcd34d', 'bg'=>'rgba(245,158,11,.12)', 'icon'=>'fas fa-clock'],
  };
@endphp

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');

:root {
  --b-surface:   #1a1d27;
  --b-surface2:  #222636;
  --b-border:    rgba(255,255,255,.07);
  --b-success:   #22c55e;
  --b-danger:    #ef4444;
  --b-warning:   #f59e0b;
  --b-info:      #38bdf8;
  --b-purple:    #a78bfa;
  --b-text:      #e2e8f0;
  --b-muted:     #64748b;
  --b-radius:    14px;
  --b-radius-sm: 8px;
  --b-shadow:    0 8px 32px rgba(0,0,0,.45);
}

.b-wrap { font-family:'Plus Jakarta Sans',sans-serif; color:var(--b-text); }

/* hero */
.b-hero {
  background: linear-gradient(135deg,#1e1b4b 0%,#312e81 55%,#4338ca 100%);
  border-radius:var(--b-radius); padding:28px 32px 72px;
  position:relative; overflow:hidden; box-shadow:var(--b-shadow); margin-bottom:-52px;
}
.b-hero::before {
  content:''; position:absolute; inset:0;
  background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4z'/%3E%3C/g%3E%3C/svg%3E");
}
.b-hero-top { position:relative;z-index:1; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; }
.b-breadcrumb { font-size:.8rem; color:rgba(255,255,255,.45); }
.b-breadcrumb a { color:rgba(255,255,255,.45); text-decoration:none; }
.b-breadcrumb span { color:rgba(255,255,255,.8); }

/* main card */
.b-main-card {
  background:var(--b-surface); border:1px solid var(--b-border);
  border-radius:var(--b-radius); box-shadow:var(--b-shadow);
  position:relative; z-index:2; margin-bottom:20px;
}

/* booking code hero strip */
.b-code-strip {
  padding:24px 28px 20px;
  border-bottom:1px solid var(--b-border);
  display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:16px;
}
.b-booking-code {
  font-family:'JetBrains Mono',monospace; font-size:1.3rem; font-weight:700;
  color:#c4b5fd; letter-spacing:.05em;
}
.b-booking-date { font-size:.8rem; color:var(--b-muted); margin-top:3px; }
.b-status-group { display:flex; gap:10px; align-items:center; flex-wrap:wrap; }

/* badge */
.b-badge { display:inline-flex; align-items:center; gap:6px; padding:5px 14px; border-radius:20px; font-size:.78rem; font-weight:700; }

/* info grid */
.b-info-grid { display:grid; grid-template-columns:1fr 1fr; gap:1px; background:var(--b-border); }
.b-info-cell { background:var(--b-surface); padding:20px 24px; }
.b-info-cell:first-child { border-radius:0; }
.b-info-label { font-size:.72rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:var(--b-muted); margin-bottom:8px; display:flex; align-items:center; gap:6px; }
.b-info-value { font-size:.95rem; font-weight:500; }
.b-info-value.mono { font-family:'JetBrains Mono',monospace; font-size:.88rem; }
@media(max-width:580px){ .b-info-grid { grid-template-columns:1fr; } }

/* amount highlight */
.b-amount-big { font-family:'JetBrains Mono',monospace; font-size:1.4rem; font-weight:700; color:var(--b-success); }

/* special request */
.b-special-box {
  margin:0 28px 24px;
  background:var(--b-surface2); border:1px solid var(--b-border);
  border-radius:var(--b-radius-sm); padding:16px 18px;
}
.b-special-box .label { font-size:.72rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:var(--b-muted); margin-bottom:8px; }
.b-special-box .val { font-size:.88rem; color:var(--b-text); line-height:1.6; }

/* sidebar card */
.b-side-card {
  background:var(--b-surface); border:1px solid var(--b-border);
  border-radius:var(--b-radius); box-shadow:var(--b-shadow); overflow:hidden; margin-bottom:16px;
}
.b-side-header { padding:16px 20px; border-bottom:1px solid var(--b-border); font-size:.8rem; font-weight:700; letter-spacing:.06em; text-transform:uppercase; color:var(--b-muted); display:flex; align-items:center; gap:8px; }
.b-side-body { padding:20px; }

/* user card */
.b-user-big { text-align:center; }
.b-user-big img { width:80px; height:80px; border-radius:50%; object-fit:cover; border:3px solid var(--b-border); margin-bottom:12px; }
.b-user-big .placeholder { width:80px; height:80px; border-radius:50%; background:var(--b-purple); display:flex; align-items:center; justify-content:center; font-size:1.5rem; font-weight:700; color:#1e1b4b; margin:0 auto 12px; }
.b-user-big .uname { font-size:1.05rem; font-weight:700; }
.b-user-big .uemail { color:var(--b-muted); font-size:.83rem; margin-top:3px; }
.b-user-big .uphone { color:var(--b-muted); font-size:.83rem; }
.b-user-divider { height:1px; background:var(--b-border); margin:14px 0; }
.b-user-meta { display:flex; justify-content:center; gap:6px; flex-wrap:wrap; }

/* tour card */
.b-tour-img { width:100%; border-radius:var(--b-radius-sm); height:140px; object-fit:cover; margin-bottom:14px; }
.b-tour-title { font-size:1rem; font-weight:700; margin-bottom:10px; }
.b-tour-meta { display:flex; flex-direction:column; gap:7px; }
.b-tour-meta-row { display:flex; align-items:center; gap:8px; font-size:.83rem; color:var(--b-muted); }
.b-tour-meta-row i { width:14px; color:var(--b-info); font-size:.75rem; }

/* action card */
.b-action-btn {
  display:flex; align-items:center; justify-content:center; gap:8px;
  width:100%; padding:12px; border-radius:var(--b-radius-sm);
  font-family:inherit; font-size:.88rem; font-weight:700;
  cursor:pointer; border:none; transition:all .2s; margin-bottom:10px;
}
.b-action-btn:last-child { margin-bottom:0; }
.b-action-confirm { background:rgba(34,197,94,.15); color:#86efac; border:1px solid rgba(34,197,94,.3); }
.b-action-confirm:hover { background:rgba(34,197,94,.25); transform:translateY(-1px); }
.b-action-cancel  { background:rgba(239,68,68,.12); color:#fca5a5; border:1px solid rgba(239,68,68,.25); }
.b-action-cancel:hover  { background:rgba(239,68,68,.22); transform:translateY(-1px); }

/* buttons */
.b-btn { display:inline-flex; align-items:center; gap:6px; border:none; cursor:pointer; font-family:inherit; font-weight:600; border-radius:var(--b-radius-sm); transition:all .2s; text-decoration:none; }
.b-btn-outline { background:transparent; color:var(--b-text); border:1px solid var(--b-border); padding:8px 14px; font-size:.82rem; }
.b-btn-outline:hover { background:var(--b-surface2); color:var(--b-text); }

/* modal */
.b-modal-overlay {
  position:fixed; inset:0; z-index:9999;
  background:rgba(0,0,0,.72); backdrop-filter:blur(6px);
  display:flex; align-items:center; justify-content:center;
  opacity:0; pointer-events:none; transition:opacity .25s;
}
.b-modal-overlay.open { opacity:1; pointer-events:auto; }
.b-modal {
  background:var(--b-surface); border:1px solid var(--b-border);
  border-radius:18px; width:min(420px,96vw);
  box-shadow:0 24px 64px rgba(0,0,0,.6);
  transform:translateY(24px) scale(.97);
  transition:transform .3s cubic-bezier(.34,1.56,.64,1);
}
.b-modal-overlay.open .b-modal { transform:translateY(0) scale(1); }
.b-modal-body   { padding:36px 28px 24px; text-align:center; }
.b-modal-footer { padding:18px 28px; border-top:1px solid var(--b-border); display:flex; gap:10px; justify-content:center; }
.b-modal-icon { width:64px; height:64px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:1.5rem; margin:0 auto 18px; }
.b-modal-icon-success { background:rgba(34,197,94,.12); border:2px solid rgba(34,197,94,.25); color:#86efac; }
.b-modal-icon-danger  { background:rgba(239,68,68,.12);  border:2px solid rgba(239,68,68,.25);  color:#fca5a5; }
.b-btn-primary { background:#6c63ff; color:#fff; padding:9px 18px; font-size:.85rem; }
.b-btn-primary:hover { background:#7c74ff; transform:translateY(-1px); color:#fff; }

/* toast */
#b-toast-container { position:fixed; bottom:28px; right:28px; z-index:99999; display:flex; flex-direction:column; gap:10px; }
.b-toast { display:flex; align-items:center; gap:12px; background:var(--b-surface); border:1px solid var(--b-border); border-radius:12px; padding:14px 18px; min-width:280px; box-shadow:0 8px 30px rgba(0,0,0,.5); transform:translateX(120%); transition:transform .35s cubic-bezier(.34,1.56,.64,1); font-family:'Plus Jakarta Sans',sans-serif; position:relative; overflow:hidden; }
.b-toast.show { transform:translateX(0); }
.b-toast-icon { width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:.95rem; }
.b-toast-success .b-toast-icon { background:rgba(34,197,94,.15); color:var(--b-success); }
.b-toast-danger  .b-toast-icon { background:rgba(239,68,68,.15);  color:var(--b-danger); }
.b-toast-title { font-size:.875rem; font-weight:700; color:var(--b-text); }
.b-toast-msg   { font-size:.78rem;  color:var(--b-muted); margin-top:2px; }
.b-toast-bar   { position:absolute; bottom:0; left:0; height:3px; border-radius:0 0 12px 12px; animation:bBar 3.5s linear forwards; }
.b-toast-success .b-toast-bar { background:var(--b-success); }
.b-toast-danger  .b-toast-bar { background:var(--b-danger); }
@keyframes bBar { from{width:100%} to{width:0%} }
</style>

@if(session('success'))<div id="flash-success" data-msg="{{ session('success') }}"></div>@endif
@if(session('error'))<div id="flash-error"   data-msg="{{ session('error') }}"></div>@endif

<div class="b-wrap">

  {{-- Hero --}}
  <div class="b-hero">
    <div class="b-hero-top">
      <div class="b-breadcrumb">
        <a href="{{ route('admin.bookings.pending') }}"><i class="fas fa-ticket-alt me-1"></i>Bookings</a>
        <i class="fas fa-chevron-right mx-2" style="font-size:.55rem;opacity:.5;"></i>
        <span>{{ $booking->booking_code }}</span>
      </div>
      <a href="{{ route('admin.bookings.pending') }}" class="b-btn b-btn-outline" style="font-size:.78rem;padding:7px 14px;">
        <i class="fas fa-arrow-left"></i> Back
      </a>
    </div>
  </div>

  <div class="row" style="position:relative;z-index:2;">

    {{-- LEFT --}}
    <div class="col-lg-8 mb-4">

      <div class="b-main-card">

        {{-- Code + Status strip --}}
        <div class="b-code-strip">
          <div>
            <div class="b-booking-code"><i class="fas fa-hashtag me-1" style="font-size:.9rem;opacity:.6;"></i>{{ $booking->booking_code }}</div>
            <div class="b-booking-date"><i class="fas fa-calendar me-1"></i>{{ $booking->created_at->format('d M Y, h:i A') }}</div>
          </div>
          <div class="b-status-group">
            <span class="b-badge" style="background:{{ $bsConfig['bg'] }};color:{{ $bsConfig['color'] }};border:1px solid {{ $bsConfig['color'] }}44;">
              <i class="{{ $bsConfig['icon'] }}" style="font-size:.65rem;"></i>
              {{ ucfirst($bs) }}
            </span>
            <span class="b-badge" style="background:{{ $psConfig['bg'] }};color:{{ $psConfig['color'] }};border:1px solid {{ $psConfig['color'] }}44;">
              <i class="{{ $psConfig['icon'] }}" style="font-size:.65rem;"></i>
              {{ ucfirst($ps) }}
            </span>
          </div>
        </div>

        {{-- Info grid --}}
        <div class="b-info-grid">

          <div class="b-info-cell">
            <div class="b-info-label"><i class="fas fa-users"></i> Persons</div>
            <div class="b-info-value" style="font-size:1.2rem;font-weight:700;color:var(--b-info);">
              {{ $booking->person_count }}
              <span style="font-size:.8rem;font-weight:400;color:var(--b-muted);"> traveller{{ $booking->person_count > 1 ? 's' : '' }}</span>
            </div>
          </div>

          <div class="b-info-cell">
            <div class="b-info-label"><i class="fas fa-money-bill-wave"></i> Total Amount</div>
            <div class="b-amount-big">৳{{ number_format($booking->total_amount, 2) }}</div>
          </div>

          <div class="b-info-cell">
            <div class="b-info-label"><i class="fas fa-tag"></i> Booking Status</div>
            <div class="b-info-value">
              <span class="b-badge" style="background:{{ $bsConfig['bg'] }};color:{{ $bsConfig['color'] }};border:1px solid {{ $bsConfig['color'] }}44;font-size:.82rem;">
                <i class="{{ $bsConfig['icon'] }}" style="font-size:.7rem;"></i>
                {{ ucfirst($bs) }}
              </span>
            </div>
          </div>

          <div class="b-info-cell">
            <div class="b-info-label"><i class="fas fa-credit-card"></i> Payment Status</div>
            <div class="b-info-value">
              <span class="b-badge" style="background:{{ $psConfig['bg'] }};color:{{ $psConfig['color'] }};border:1px solid {{ $psConfig['color'] }}44;font-size:.82rem;">
                <i class="{{ $psConfig['icon'] }}" style="font-size:.7rem;"></i>
                {{ ucfirst($ps) }}
              </span>
            </div>
          </div>

          <div class="b-info-cell" style="grid-column:span 2;">
            <div class="b-info-label"><i class="fas fa-clock"></i> Booked On</div>
            <div class="b-info-value mono">{{ $booking->created_at->format('d M Y, h:i A') }} &mdash; {{ $booking->created_at->diffForHumans() }}</div>
          </div>

        </div>

        {{-- Special Request --}}
        @if($booking->special_request)
        <div class="b-special-box">
          <div class="label"><i class="fas fa-comment-alt me-1"></i>Special Request</div>
          <div class="val">{{ $booking->special_request }}</div>
        </div>
        @endif

      </div>
    </div>

    {{-- RIGHT --}}
    <div class="col-lg-4">

      {{-- User --}}
      <div class="b-side-card">
        <div class="b-side-header"><i class="fas fa-user"></i> Customer</div>
        <div class="b-side-body">
          <div class="b-user-big">
            @if($booking->user->photo ?? false)
              <img src="{{ asset('uploads/users/'.$booking->user->photo) }}" alt="{{ $booking->user->name }}">
            @else
              <div class="placeholder">{{ strtoupper(substr($booking->user->name ?? 'U', 0, 1)) }}</div>
            @endif
            <div class="uname">{{ $booking->user->name ?? 'N/A' }}</div>
            <div class="uemail"><i class="fas fa-envelope me-1" style="font-size:.7rem;"></i>{{ $booking->user->email ?? '' }}</div>
            @if($booking->user->phone ?? false)
              <div class="uphone"><i class="fas fa-phone me-1" style="font-size:.7rem;"></i>{{ $booking->user->phone }}</div>
            @endif
          </div>
        </div>
      </div>

      {{-- Tour --}}
      <div class="b-side-card">
        <div class="b-side-header"><i class="fas fa-map-marked-alt"></i> Tour Package</div>
        <div class="b-side-body">
          @if($booking->tour->featured_image ?? false)
            <img src="{{ asset('uploads/tours/'.$booking->tour->featured_image) }}" class="b-tour-img" alt="{{ $booking->tour->title }}">
          @endif
          <div class="b-tour-title">{{ $booking->tour->title ?? 'N/A' }}</div>
          <div class="b-tour-meta">
            @if($booking->tour->destination ?? false)
              <div class="b-tour-meta-row"><i class="fas fa-map-marker-alt"></i>{{ $booking->tour->destination->name }}</div>
            @endif
            @if($booking->tour->duration ?? false)
              <div class="b-tour-meta-row"><i class="fas fa-clock"></i>{{ $booking->tour->duration }}</div>
            @endif
            @if($booking->tour->location ?? false)
              <div class="b-tour-meta-row"><i class="fas fa-location-arrow"></i>{{ $booking->tour->location }}</div>
            @endif
          </div>
        </div>
      </div>

      {{-- Actions --}}
      <div class="b-side-card">
        <div class="b-side-header"><i class="fas fa-cog"></i> Actions</div>
        <div class="b-side-body">
          @if($bs === 'pending')
            <button class="b-action-btn b-action-confirm"
              onclick="openConfirmModal('{{ $booking->id }}', {{ json_encode($booking->booking_code) }})">
              <i class="fas fa-check-circle"></i> Confirm Booking
            </button>
          @endif
          @if($bs !== 'cancelled')
            <button class="b-action-btn b-action-cancel"
              onclick="openCancelModal('{{ $booking->id }}', {{ json_encode($booking->booking_code) }})">
              <i class="fas fa-times-circle"></i> Cancel Booking
            </button>
          @endif
          @if($bs === 'cancelled')
            <div style="text-align:center;color:var(--b-muted);font-size:.85rem;padding:10px 0;">
              <i class="fas fa-ban me-2"></i>This booking has been cancelled.
            </div>
          @endif
        </div>
      </div>

    </div>
  </div>
</div>

{{-- Confirm Modal --}}
<div class="b-modal-overlay" id="confirmModal">
  <div class="b-modal">
    <div class="b-modal-body">
      <div class="b-modal-icon b-modal-icon-success"><i class="fas fa-check-circle"></i></div>
      <h5 style="font-weight:700;margin-bottom:8px;">Confirm Booking?</h5>
      <p style="color:var(--b-muted);font-size:.88rem;">
        Booking <strong id="confirm-code" style="color:var(--b-text)"></strong> will be confirmed and the customer will be notified.
      </p>
    </div>
    <div class="b-modal-footer">
      <button class="b-btn b-btn-outline" onclick="closeModal('confirmModal')"><i class="fas fa-times"></i> Cancel</button>
      <form id="confirmForm" method="POST" style="display:inline;">
        @csrf
        <button type="submit" class="b-btn b-btn-primary" style="background:var(--b-success);color:#052e16;">
          <i class="fas fa-check"></i> Yes, Confirm
        </button>
      </form>
    </div>
  </div>
</div>

{{-- Cancel Modal --}}
<div class="b-modal-overlay" id="cancelModal">
  <div class="b-modal">
    <div class="b-modal-body">
      <div class="b-modal-icon b-modal-icon-danger"><i class="fas fa-times-circle"></i></div>
      <h5 style="font-weight:700;margin-bottom:8px;">Cancel Booking?</h5>
      <p style="color:var(--b-muted);font-size:.88rem;">
        Booking <strong id="cancel-code" style="color:var(--b-text)"></strong> will be cancelled.
      </p>
      <p style="color:var(--b-muted);font-size:.8rem;">This action <strong style="color:var(--b-danger)">cannot be undone</strong>.</p>
    </div>
    <div class="b-modal-footer">
      <button class="b-btn b-btn-outline" onclick="closeModal('cancelModal')"><i class="fas fa-times"></i> Back</button>
      <form id="cancelForm" method="POST" style="display:inline;">
        @csrf
        <button type="submit" class="b-btn b-btn-primary" style="background:var(--b-danger);">
          <i class="fas fa-times-circle"></i> Yes, Cancel
        </button>
      </form>
    </div>
  </div>
</div>

<div id="b-toast-container"></div>

<script>
function openModal(id)  { document.getElementById(id).classList.add('open'); document.body.style.overflow='hidden'; }
function closeModal(id) { document.getElementById(id).classList.remove('open'); document.body.style.overflow=''; }
document.querySelectorAll('.b-modal-overlay').forEach(el => {
  el.addEventListener('click', e => { if(e.target===el) closeModal(el.id); });
});
document.addEventListener('keydown', e => {
  if(e.key==='Escape') document.querySelectorAll('.b-modal-overlay.open').forEach(el=>closeModal(el.id));
});

function openConfirmModal(id, code) {
  document.getElementById('confirm-code').textContent = code;
  document.getElementById('confirmForm').action = '/admin/bookings/' + id + '/confirm';
  openModal('confirmModal');
}
function openCancelModal(id, code) {
  document.getElementById('cancel-code').textContent = code;
  document.getElementById('cancelForm').action = '/admin/bookings/' + id + '/cancel';
  openModal('cancelModal');
}

function showToast(type, title, msg) {
  var icons = {success:'fas fa-check-circle', danger:'fas fa-exclamation-circle'};
  var c = document.getElementById('b-toast-container');
  var t = document.createElement('div');
  t.className = 'b-toast b-toast-' + type;
  t.innerHTML = `<div class="b-toast-icon"><i class="${icons[type]}"></i></div>
    <div><div class="b-toast-title">${title}</div><div class="b-toast-msg">${msg}</div></div>
    <span class="b-toast-bar"></span>`;
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