@extends('layouts.admin')
@section('title', 'Confirmed Bookings')
@section('page')

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');

:root {
  --b-surface:   #1a1d27;
  --b-surface2:  #222636;
  --b-border:    rgba(255,255,255,.07);
  --b-accent:    #22c55e;
  --b-accent2:   #4ade80;
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

.b-header {
  background: linear-gradient(135deg,#052e16 0%,#14532d 50%,#166534 100%);
  border-radius:var(--b-radius); padding:28px 32px;
  margin-bottom:24px; position:relative; overflow:hidden; box-shadow:var(--b-shadow);
}
.b-header::before {
  content:''; position:absolute; inset:0;
  background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%2322c55e' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='20'/%3E%3C/g%3E%3C/svg%3E");
}
.b-header::after {
  content:''; position:absolute; right:-40px; top:-40px;
  width:180px; height:180px; border-radius:50%;
  background:radial-gradient(circle,rgba(34,197,94,.15) 0%,transparent 70%);
}
.b-header .title {
  font-size:1.5rem; font-weight:700; position:relative; z-index:1;
  background:linear-gradient(90deg,#fff,var(--b-accent2));
  -webkit-background-clip:text; -webkit-text-fill-color:transparent;
}
.b-header .subtitle { color:rgba(255,255,255,.45); font-size:.85rem; margin-top:4px; position:relative; z-index:1; }

.stat-pill {
  display:inline-flex; align-items:center; gap:8px;
  background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.1);
  border-radius:40px; padding:6px 16px;
  font-size:.8rem; font-weight:600; color:#fff; position:relative; z-index:1;
}
.stat-pill .dot { width:8px; height:8px; border-radius:50%; }

.b-table-card {
  background:var(--b-surface); border:1px solid var(--b-border);
  border-radius:var(--b-radius); overflow:hidden; box-shadow:var(--b-shadow);
}
.b-search-bar {
  padding:16px 20px; border-bottom:1px solid var(--b-border);
  display:flex; align-items:center; gap:12px; flex-wrap:wrap; justify-content:space-between;
}
.b-search-wrap { position:relative; }
.b-search-wrap .si { position:absolute; left:11px; top:50%; transform:translateY(-50%); color:var(--b-muted); font-size:.8rem; }
.b-search-input {
  background:var(--b-surface2); border:1px solid var(--b-border);
  border-radius:var(--b-radius-sm); padding:8px 14px 8px 36px;
  color:var(--b-text); font-family:inherit; font-size:.875rem;
  width:260px; outline:none; transition:border-color .2s;
}
.b-search-input:focus { border-color:var(--b-accent); box-shadow:0 0 0 3px rgba(34,197,94,.12); }

.b-filter-tabs { display:flex; gap:6px; }
.b-filter-tab {
  background:var(--b-surface2); border:1px solid var(--b-border);
  border-radius:20px; padding:5px 14px; font-size:.78rem;
  font-weight:600; color:var(--b-muted); cursor:pointer;
  font-family:inherit; transition:all .2s;
}
.b-filter-tab:hover,.b-filter-tab.active { background:var(--b-accent); color:#052e16; border-color:var(--b-accent); }

.b-table { width:100%; border-collapse:collapse; }
.b-table thead tr { background:var(--b-surface2); }
.b-table th { padding:13px 18px; text-align:left; font-size:.72rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:var(--b-muted); white-space:nowrap; }
.b-table td { padding:13px 18px; vertical-align:middle; border-bottom:1px solid var(--b-border); font-size:.875rem; }
.b-table tbody tr { transition:background .15s; }
.b-table tbody tr:hover { background:rgba(34,197,94,.04); }
.b-table tbody tr:last-child td { border-bottom:none; }

.b-code { font-family:'JetBrains Mono',monospace; font-size:.8rem; background:var(--b-surface2); border:1px solid var(--b-border); padding:3px 8px; border-radius:6px; color:var(--b-accent2); }
.b-user { display:flex; align-items:center; gap:10px; }
.b-user-avatar { width:34px; height:34px; border-radius:50%; object-fit:cover; border:2px solid var(--b-border); flex-shrink:0; }
.b-user-avatar-placeholder { width:34px; height:34px; border-radius:50%; background:var(--b-accent); display:flex; align-items:center; justify-content:center; font-size:.75rem; font-weight:700; color:#052e16; flex-shrink:0; }
.b-user-name  { font-weight:600; font-size:.875rem; }
.b-user-email { font-size:.75rem; color:var(--b-muted); }
.b-tour-name  { font-weight:600; max-width:180px; font-size:.875rem; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
.b-tour-dest  { font-size:.75rem; color:var(--b-muted); }
.b-amount { font-family:'JetBrains Mono',monospace; font-weight:600; color:var(--b-accent2); }

.b-badge { display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:20px; font-size:.72rem; font-weight:700; }
.b-badge-paid      { background:rgba(34,197,94,.12);  color:#86efac; border:1px solid rgba(34,197,94,.25); }
.b-badge-pending   { background:rgba(245,158,11,.15); color:#fcd34d; border:1px solid rgba(245,158,11,.3); }
.b-badge-failed    { background:rgba(239,68,68,.12);  color:#fca5a5; border:1px solid rgba(239,68,68,.25); }
.b-badge-refunded  { background:rgba(56,189,248,.12); color:#7dd3fc; border:1px solid rgba(56,189,248,.25); }
.b-badge-confirmed { background:rgba(167,139,250,.12);color:#c4b5fd; border:1px solid rgba(167,139,250,.25); }
.b-badge-completed { background:rgba(34,197,94,.12);  color:#86efac; border:1px solid rgba(34,197,94,.25); }

.b-btn { display:inline-flex; align-items:center; gap:6px; border:none; cursor:pointer; font-family:inherit; font-weight:600; border-radius:var(--b-radius-sm); transition:all .2s; text-decoration:none; }
.b-btn-primary { background:var(--b-accent); color:#052e16; padding:9px 18px; font-size:.85rem; }
.b-btn-primary:hover { background:var(--b-accent2); transform:translateY(-1px); color:#052e16; }
.b-btn-outline { background:transparent; color:var(--b-text); border:1px solid var(--b-border); padding:9px 14px; font-size:.82rem; }
.b-btn-outline:hover { background:var(--b-surface2); color:var(--b-text); }
.b-btn-icon { background:var(--b-surface2); color:var(--b-muted); border:1px solid var(--b-border); padding:6px 10px; font-size:.78rem; border-radius:6px; }
.b-btn-icon:hover { color:var(--b-info); border-color:rgba(56,189,248,.3); }
.b-btn-danger-ghost { background:rgba(239,68,68,.1); color:#fca5a5; border:1px solid rgba(239,68,68,.2); padding:6px 10px; font-size:.78rem; border-radius:6px; }
.b-btn-danger-ghost:hover { background:rgba(239,68,68,.2); }
.b-actions-cell { display:flex; gap:6px; align-items:center; }

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
.b-modal-icon-danger { background:rgba(239,68,68,.12); border:2px solid rgba(239,68,68,.25); color:#fca5a5; }

#b-toast-container { position:fixed; bottom:28px; right:28px; z-index:99999; display:flex; flex-direction:column; gap:10px; }
.b-toast { display:flex; align-items:center; gap:12px; background:var(--b-surface); border:1px solid var(--b-border); border-radius:12px; padding:14px 18px; min-width:280px; box-shadow:0 8px 30px rgba(0,0,0,.5); transform:translateX(120%); transition:transform .35s cubic-bezier(.34,1.56,.64,1); font-family:'Plus Jakarta Sans',sans-serif; position:relative; overflow:hidden; }
.b-toast.show { transform:translateX(0); }
.b-toast-icon { width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:.95rem; }
.b-toast-success .b-toast-icon { background:rgba(34,197,94,.15);  color:var(--b-success); }
.b-toast-danger  .b-toast-icon { background:rgba(239,68,68,.15);   color:var(--b-danger); }
.b-toast-title { font-size:.875rem; font-weight:700; color:var(--b-text); }
.b-toast-msg   { font-size:.78rem;  color:var(--b-muted); margin-top:2px; }
.b-toast-bar   { position:absolute; bottom:0; left:0; height:3px; border-radius:0 0 12px 12px; animation:bBar 3.5s linear forwards; }
.b-toast-success .b-toast-bar { background:var(--b-success); }
.b-toast-danger  .b-toast-bar { background:var(--b-danger); }
@keyframes bBar { from{width:100%} to{width:0%} }
.b-empty { text-align:center; padding:60px 20px; color:var(--b-muted); }
.b-empty i { font-size:2.5rem; margin-bottom:14px; opacity:.4; display:block; }
</style>

@if(session('success'))<div id="flash-success" data-msg="{{ session('success') }}"></div>@endif
@if(session('error'))<div id="flash-error"   data-msg="{{ session('error') }}"></div>@endif

<div class="b-wrap">

  <div class="b-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
      <div>
        <div class="title"><i class="fas fa-check-circle me-2"></i>Confirmed Bookings</div>
        <div class="subtitle">All approved and confirmed tour reservations</div>
        <div class="d-flex gap-2 mt-3 flex-wrap">
          <span class="stat-pill"><span class="dot" style="background:var(--b-accent)"></span>{{ $bookings->count() }} Confirmed</span>
          <span class="stat-pill"><span class="dot" style="background:var(--b-warning)"></span>{{ $bookings->where('payment_status','pending')->count() }} Payment Pending</span>
          <span class="stat-pill"><span class="dot" style="background:var(--b-info)"></span>৳{{ number_format($bookings->sum('total_amount'), 0) }} Total</span>
        </div>
      </div>
      <div style="position:relative;z-index:1;">
        <a href="{{ route('admin.bookings.pending') }}" class="b-btn b-btn-outline">
          <i class="fas fa-hourglass-half"></i> Pending Bookings
        </a>
      </div>
    </div>
  </div>

  <div class="b-table-card">
    <div class="b-search-bar">
      <div class="d-flex gap-3 align-items-center flex-wrap">
        <div class="b-search-wrap">
          <i class="fas fa-search si"></i>
          <input type="text" class="b-search-input" id="b-search" placeholder="Search bookings...">
        </div>
        <div class="b-filter-tabs">
          <button class="b-filter-tab active" data-filter="all">All</button>
          <button class="b-filter-tab" data-filter="paid">Paid</button>
          <button class="b-filter-tab" data-filter="pending">Unpaid</button>
        </div>
      </div>
      <span style="font-size:.8rem;color:var(--b-muted);" id="b-count"></span>
    </div>

    <div style="overflow-x:auto;">
      <table class="b-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Booking Code</th>
            <th>Customer</th>
            <th>Tour</th>
            <th>Persons</th>
            <th>Amount</th>
            <th>Payment</th>
            <th>Status</th>
            <th>Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="b-tbody">
          @forelse($bookings as $i => $booking)
          <tr
            data-search="{{ strtolower($booking->booking_code.' '.($booking->user->name ?? '').' '.($booking->tour->title ?? '')) }}"
            data-payment="{{ $booking->payment_status }}">
            <td style="color:var(--b-muted);font-size:.8rem;">{{ $i + 1 }}</td>
            <td><span class="b-code">{{ $booking->booking_code }}</span></td>
            <td>
              <div class="b-user">
                @if($booking->user->photo ?? false)
                  <img src="{{ asset('uploads/users/'.$booking->user->photo) }}" class="b-user-avatar">
                @else
                  <div class="b-user-avatar-placeholder">{{ strtoupper(substr($booking->user->name ?? 'U', 0, 1)) }}</div>
                @endif
                <div>
                  <div class="b-user-name">{{ $booking->user->name ?? 'N/A' }}</div>
                  <div class="b-user-email">{{ $booking->user->email ?? '' }}</div>
                </div>
              </div>
            </td>
            <td>
              <div class="b-tour-name" title="{{ $booking->tour->title ?? '' }}">{{ $booking->tour->title ?? 'N/A' }}</div>
              @if($booking->tour->destination ?? false)
                <div class="b-tour-dest"><i class="fas fa-map-marker-alt me-1"></i>{{ $booking->tour->destination->name }}</div>
              @endif
            </td>
            <td style="text-align:center;font-weight:600;">{{ $booking->person_count }}</td>
            <td><span class="b-amount">৳{{ number_format($booking->total_amount, 0) }}</span></td>
            <td>
              @php $ps = $booking->payment_status; @endphp
              <span class="b-badge b-badge-{{ $ps }}">
                <i class="fas fa-circle" style="font-size:.4rem;"></i>
                {{ ucfirst($ps) }}
              </span>
            </td>
            <td>
              <span class="b-badge b-badge-{{ $booking->booking_status }}">
                {{ ucfirst($booking->booking_status) }}
              </span>
            </td>
            <td style="font-size:.8rem;color:var(--b-muted);">
              {{ $booking->created_at->format('d M Y') }}<br>
              {{ $booking->created_at->format('h:i A') }}
            </td>
            <td>
              <div class="b-actions-cell">
                <a href="{{ route('admin.bookings.show', $booking->id) }}" class="b-btn b-btn-icon" title="View">
                  <i class="fas fa-eye"></i>
                </a>
                <button class="b-btn b-btn-danger-ghost" title="Cancel"
                  onclick="openCancelModal('{{ $booking->id }}', {{ json_encode($booking->booking_code) }})">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="10">
            <div class="b-empty">
              <i class="fas fa-check-double"></i>
              <p>No confirmed bookings found.</p>
            </div>
          </td></tr>
          @endforelse
        </tbody>
      </table>
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

function openCancelModal(id, code) {
  document.getElementById('cancel-code').textContent = code;
  document.getElementById('cancelForm').action = '/admin/bookings/' + id + '/cancel';
  openModal('cancelModal');
}

(function(){
  var inp  = document.getElementById('b-search');
  var rows = Array.from(document.querySelectorAll('#b-tbody tr[data-search]'));
  var cnt  = document.getElementById('b-count');
  var tabs = document.querySelectorAll('.b-filter-tab');
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
        || (active==='paid' && r.dataset.payment==='paid')
        || (active==='pending' && r.dataset.payment==='pending');
      r.style.display = (matchQ && matchF) ? '' : 'none';
      if(matchQ && matchF) v++;
    });
    cnt.textContent = v + ' of ' + rows.length + ' bookings';
  }
  update();
})();

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