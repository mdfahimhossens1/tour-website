@extends('layouts.admin')
@section('title', 'Transactions')
@section('page')

@php
  $totalAmt    = $transactions->sum('amount');
  $successCnt  = $transactions->where('status','success')->count();
  $pendingCnt  = $transactions->where('status','pending')->count();
  $failedCnt   = $transactions->where('status','failed')->count();
@endphp

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');
:root {
  --t-surface:   #1a1d27;
  --t-surface2:  #222636;
  --t-border:    rgba(255,255,255,.07);
  --t-accent:    #0ea5e9;
  --t-accent2:   #38bdf8;
  --t-success:   #22c55e;
  --t-danger:    #ef4444;
  --t-warning:   #f59e0b;
  --t-purple:    #a78bfa;
  --t-text:      #e2e8f0;
  --t-muted:     #64748b;
  --t-radius:    14px;
  --t-radius-sm: 8px;
  --t-shadow:    0 8px 32px rgba(0,0,0,.45);
}
.tx-wrap { font-family:'Plus Jakarta Sans',sans-serif; color:var(--t-text); }

/* header */
.tx-header {
  background:linear-gradient(135deg,#0c1a2e 0%,#0c3558 55%,#0c4a72 100%);
  border-radius:var(--t-radius); padding:28px 32px;
  margin-bottom:24px; position:relative; overflow:hidden; box-shadow:var(--t-shadow);
}
.tx-header::before { content:''; position:absolute; inset:0; background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%230ea5e9' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='20'/%3E%3C/g%3E%3C/svg%3E"); }
.tx-header::after { content:''; position:absolute; right:-40px; top:-40px; width:180px; height:180px; border-radius:50%; background:radial-gradient(circle,rgba(14,165,233,.2) 0%,transparent 70%); }
.tx-header .title { font-size:1.5rem; font-weight:700; position:relative; z-index:1; background:linear-gradient(90deg,#fff,var(--t-accent2)); -webkit-background-clip:text; -webkit-text-fill-color:transparent; }
.tx-header .subtitle { color:rgba(255,255,255,.45); font-size:.85rem; margin-top:4px; position:relative; z-index:1; }

.stat-pill { display:inline-flex; align-items:center; gap:8px; background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.1); border-radius:40px; padding:6px 16px; font-size:.8rem; font-weight:600; color:#fff; position:relative; z-index:1; }
.stat-pill .dot { width:8px; height:8px; border-radius:50%; }

/* table card */
.tx-card { background:var(--t-surface); border:1px solid var(--t-border); border-radius:var(--t-radius); overflow:hidden; box-shadow:var(--t-shadow); }
.tx-search-bar { padding:16px 20px; border-bottom:1px solid var(--t-border); display:flex; align-items:center; gap:12px; flex-wrap:wrap; justify-content:space-between; }
.tx-search-wrap { position:relative; }
.tx-search-wrap .si { position:absolute; left:11px; top:50%; transform:translateY(-50%); color:var(--t-muted); font-size:.8rem; }
.tx-search-input { background:var(--t-surface2); border:1px solid var(--t-border); border-radius:var(--t-radius-sm); padding:8px 14px 8px 36px; color:var(--t-text); font-family:inherit; font-size:.875rem; width:250px; outline:none; transition:border-color .2s; }
.tx-search-input:focus { border-color:var(--t-accent); box-shadow:0 0 0 3px rgba(14,165,233,.12); }

.tx-filter-tabs { display:flex; gap:6px; }
.tx-filter-tab { background:var(--t-surface2); border:1px solid var(--t-border); border-radius:20px; padding:5px 14px; font-size:.78rem; font-weight:600; color:var(--t-muted); cursor:pointer; font-family:inherit; transition:all .2s; }
.tx-filter-tab:hover,.tx-filter-tab.active { background:var(--t-accent); color:#0c1a2e; border-color:var(--t-accent); }

/* table */
.tx-table { width:100%; border-collapse:collapse; }
.tx-table thead tr { background:var(--t-surface2); }
.tx-table th { padding:13px 18px; text-align:left; font-size:.72rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:var(--t-muted); white-space:nowrap; }
.tx-table td { padding:13px 18px; vertical-align:middle; border-bottom:1px solid var(--t-border); font-size:.875rem; }
.tx-table tbody tr { transition:background .15s; }
.tx-table tbody tr:hover { background:rgba(14,165,233,.04); }
.tx-table tbody tr:last-child td { border-bottom:none; }

/* txn id */
.tx-id { font-family:'JetBrains Mono',monospace; font-size:.78rem; background:var(--t-surface2); border:1px solid var(--t-border); padding:3px 8px; border-radius:6px; color:var(--t-accent2); display:inline-flex; align-items:center; gap:6px; cursor:pointer; transition:border-color .2s; }
.tx-id:hover { border-color:rgba(56,189,248,.3); }
.tx-id i { font-size:.65rem; opacity:.5; }

/* booking ref */
.tx-booking { font-family:'JetBrains Mono',monospace; font-size:.8rem; color:var(--t-purple); font-weight:600; }

/* user cell */
.tx-user { display:flex; align-items:center; gap:8px; }
.tx-user-av { width:30px; height:30px; border-radius:50%; background:var(--t-accent); display:flex; align-items:center; justify-content:center; font-size:.7rem; font-weight:700; flex-shrink:0; color:#0c1a2e; }

/* method badge */
.tx-method { display:inline-flex; align-items:center; gap:5px; padding:3px 10px; border-radius:20px; font-size:.72rem; font-weight:700; }
.tx-method-bkash   { background:rgba(244,63,142,.1); color:#f9a8d4; border:1px solid rgba(244,63,142,.2); }
.tx-method-nagad   { background:rgba(249,115,22,.1); color:#fdba74; border:1px solid rgba(249,115,22,.2); }
.tx-method-stripe  { background:rgba(99,91,255,.12); color:#c4b5fd; border:1px solid rgba(99,91,255,.25); }
.tx-method-paypal  { background:rgba(56,189,248,.12); color:#7dd3fc; border:1px solid rgba(56,189,248,.25); }
.tx-method-default { background:rgba(100,116,139,.1); color:#94a3b8; border:1px solid rgba(100,116,139,.2); }

/* amount */
.tx-amount { font-family:'JetBrains Mono',monospace; font-weight:700; color:var(--t-success); }

/* status badge */
.tx-badge { display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:20px; font-size:.72rem; font-weight:700; }
.tx-badge-success  { background:rgba(34,197,94,.12);  color:#86efac; border:1px solid rgba(34,197,94,.25); }
.tx-badge-pending  { background:rgba(245,158,11,.15); color:#fcd34d; border:1px solid rgba(245,158,11,.3); }
.tx-badge-failed   { background:rgba(239,68,68,.12);  color:#fca5a5; border:1px solid rgba(239,68,68,.25); }
.tx-badge-refunded { background:rgba(167,139,250,.12);color:#c4b5fd; border:1px solid rgba(167,139,250,.25); }

/* action buttons */
.tx-btn { display:inline-flex; align-items:center; gap:5px; border:none; cursor:pointer; font-family:inherit; font-weight:600; border-radius:var(--t-radius-sm); transition:all .2s; text-decoration:none; }
.tx-btn-primary { background:var(--t-accent); color:#0c1a2e; padding:6px 12px; font-size:.78rem; }
.tx-btn-primary:hover { background:var(--t-accent2); color:#0c1a2e; transform:translateY(-1px); }
.tx-btn-danger-ghost { background:rgba(239,68,68,.1); color:#fca5a5; border:1px solid rgba(239,68,68,.2); padding:6px 10px; font-size:.78rem; border-radius:6px; }
.tx-btn-danger-ghost:hover { background:rgba(239,68,68,.2); }
.tx-actions { display:flex; gap:6px; align-items:center; }

/* pagination override */
.pagination { display:flex; gap:4px; margin:0; padding:16px 20px; border-top:1px solid var(--t-border); flex-wrap:wrap; }
.page-item .page-link { background:var(--t-surface2); border:1px solid var(--t-border); color:var(--t-muted); border-radius:var(--t-radius-sm) !important; padding:6px 12px; font-size:.8rem; font-family:inherit; transition:all .2s; }
.page-item.active .page-link { background:var(--t-accent); border-color:var(--t-accent); color:#0c1a2e; font-weight:700; }
.page-item .page-link:hover { background:var(--t-surface); color:var(--t-text); border-color:rgba(255,255,255,.15); }

/* modal */
.tx-modal-overlay { position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,.72); backdrop-filter:blur(6px); display:flex; align-items:center; justify-content:center; opacity:0; pointer-events:none; transition:opacity .25s; }
.tx-modal-overlay.open { opacity:1; pointer-events:auto; }
.tx-modal { background:var(--t-surface); border:1px solid var(--t-border); border-radius:18px; width:min(420px,96vw); box-shadow:0 24px 64px rgba(0,0,0,.6); transform:translateY(24px) scale(.97); transition:transform .3s cubic-bezier(.34,1.56,.64,1); }
.tx-modal-overlay.open .tx-modal { transform:translateY(0) scale(1); }
.tx-modal-body   { padding:36px 28px 24px; text-align:center; }
.tx-modal-footer { padding:18px 28px; border-top:1px solid var(--t-border); display:flex; gap:10px; justify-content:center; }
.tx-del-icon { width:64px; height:64px; border-radius:50%; background:rgba(239,68,68,.12); border:2px solid rgba(239,68,68,.25); display:flex; align-items:center; justify-content:center; font-size:1.5rem; color:#fca5a5; margin:0 auto 18px; }
.tx-btn-outline { background:transparent; color:var(--t-text); border:1px solid var(--t-border); padding:9px 16px; font-size:.875rem; border-radius:var(--t-radius-sm); }
.tx-btn-outline:hover { background:var(--t-surface2); }

/* toast */
#tx-toast-container { position:fixed; bottom:28px; right:28px; z-index:99999; display:flex; flex-direction:column; gap:10px; }
.tx-toast { display:flex; align-items:center; gap:12px; background:var(--t-surface); border:1px solid var(--t-border); border-radius:12px; padding:14px 18px; min-width:280px; box-shadow:0 8px 30px rgba(0,0,0,.5); transform:translateX(120%); transition:transform .35s cubic-bezier(.34,1.56,.64,1); font-family:'Plus Jakarta Sans',sans-serif; position:relative; overflow:hidden; }
.tx-toast.show { transform:translateX(0); }
.tx-toast-icon { width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.tx-toast-success .tx-toast-icon { background:rgba(34,197,94,.15); color:var(--t-success); }
.tx-toast-danger  .tx-toast-icon { background:rgba(239,68,68,.15);  color:var(--t-danger); }
.tx-toast-info    .tx-toast-icon { background:rgba(56,189,248,.15);  color:var(--t-accent); }
.tx-toast-title { font-size:.875rem; font-weight:700; color:var(--t-text); }
.tx-toast-msg   { font-size:.78rem;  color:var(--t-muted); margin-top:2px; }
.tx-toast-bar   { position:absolute; bottom:0; left:0; height:3px; border-radius:0 0 12px 12px; animation:txBar 3.5s linear forwards; }
.tx-toast-success .tx-toast-bar { background:var(--t-success); }
.tx-toast-danger  .tx-toast-bar { background:var(--t-danger); }
.tx-toast-info    .tx-toast-bar { background:var(--t-accent); }
@keyframes txBar { from{width:100%} to{width:0%} }
.tx-empty { text-align:center; padding:60px 20px; color:var(--t-muted); }
.tx-empty i { font-size:2.5rem; margin-bottom:14px; opacity:.4; display:block; }
</style>

@if(session('success'))<div id="flash-success" data-msg="{{ session('success') }}"></div>@endif
@if(session('error'))<div id="flash-error"   data-msg="{{ session('error') }}"></div>@endif

<div class="tx-wrap">

  {{-- Header --}}
  <div class="tx-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
      <div>
        <div class="title"><i class="fas fa-exchange-alt me-2"></i>Transactions</div>
        <div class="subtitle">All payment records and transaction history</div>
        <div class="d-flex gap-2 mt-3 flex-wrap">
          <span class="stat-pill"><span class="dot" style="background:var(--t-success)"></span>{{ $successCnt }} Success</span>
          <span class="stat-pill"><span class="dot" style="background:var(--t-warning)"></span>{{ $pendingCnt }} Pending</span>
          <span class="stat-pill"><span class="dot" style="background:var(--t-danger)"></span>{{ $failedCnt }} Failed</span>
          <span class="stat-pill"><span class="dot" style="background:var(--t-accent2)"></span>৳{{ number_format($totalAmt, 0) }} Total</span>
        </div>
      </div>
    </div>
  </div>

  {{-- Table Card --}}
  <div class="tx-card">
    <div class="tx-search-bar">
      <div class="d-flex gap-3 align-items-center flex-wrap">
        <div class="tx-search-wrap">
          <i class="fas fa-search si"></i>
          <input type="text" class="tx-search-input" id="tx-search" placeholder="Search by ID, user, method...">
        </div>
        <div class="tx-filter-tabs">
          <button class="tx-filter-tab active" data-filter="all">All</button>
          <button class="tx-filter-tab" data-filter="success">Success</button>
          <button class="tx-filter-tab" data-filter="pending">Pending</button>
          <button class="tx-filter-tab" data-filter="failed">Failed</button>
          <button class="tx-filter-tab" data-filter="refunded">Refunded</button>
        </div>
      </div>
      <span style="font-size:.8rem;color:var(--t-muted);" id="tx-count"></span>
    </div>

    <div style="overflow-x:auto;">
      <table class="tx-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Transaction ID</th>
            <th>Customer</th>
            <th>Booking</th>
            <th>Method</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="tx-tbody">
          @forelse($transactions as $key => $trx)
          @php
            $method = strtolower($trx->payment_method ?? 'other');
            $methodClass = match(true) {
              str_contains($method,'bkash')  => 'tx-method-bkash',
              str_contains($method,'nagad')  => 'tx-method-nagad',
              str_contains($method,'stripe') => 'tx-method-stripe',
              str_contains($method,'paypal') => 'tx-method-paypal',
              default                        => 'tx-method-default',
            };
            $methodIcon = match(true) {
              str_contains($method,'bkash')  => 'fas fa-mobile-alt',
              str_contains($method,'nagad')  => 'fas fa-mobile-alt',
              str_contains($method,'stripe') => 'fab fa-stripe-s',
              str_contains($method,'paypal') => 'fab fa-paypal',
              default                        => 'fas fa-credit-card',
            };
            $status = strtolower($trx->status ?? 'pending');
          @endphp
          <tr
            data-search="{{ strtolower($trx->transaction_id.' '.($trx->user->name ?? '').' '.$method) }}"
            data-status="{{ $status }}">
            <td style="color:var(--t-muted);font-size:.8rem;">{{ $transactions->firstItem() + $key }}</td>
            <td>
              <div class="tx-id" onclick="copyTxId('{{ $trx->transaction_id }}')" title="Click to copy">
                <i class="fas fa-hashtag"></i>
                {{ Str::limit($trx->transaction_id, 16) }}
                <i class="fas fa-copy"></i>
              </div>
            </td>
            <td>
              <div class="tx-user">
                <div class="tx-user-av">{{ strtoupper(substr($trx->user->name ?? 'U', 0, 1)) }}</div>
                <div>
                  <div style="font-weight:600;font-size:.875rem;">{{ $trx->user->name ?? '—' }}</div>
                  <div style="font-size:.72rem;color:var(--t-muted);">{{ $trx->user->email ?? '' }}</div>
                </div>
              </div>
            </td>
            <td>
              @if($trx->booking_id)
                <span class="tx-booking">#{{ $trx->booking_id }}</span>
                @if($trx->booking)
                  <div style="font-size:.72rem;color:var(--t-muted);margin-top:2px;">{{ Str::limit($trx->booking->booking_code ?? '', 14) }}</div>
                @endif
              @else
                <span style="color:var(--t-muted);">—</span>
              @endif
            </td>
            <td>
              <span class="tx-method {{ $methodClass }}">
                <i class="{{ $methodIcon }}" style="font-size:.65rem;"></i>
                {{ ucfirst($trx->payment_method ?? '—') }}
              </span>
            </td>
            <td><span class="tx-amount">৳{{ number_format($trx->amount, 0) }}</span></td>
            <td>
              <span class="tx-badge tx-badge-{{ $status }}">
                <i class="fas fa-circle" style="font-size:.4rem;"></i>
                {{ ucfirst($status) }}
              </span>
            </td>
            <td style="font-size:.8rem;color:var(--t-muted);">
              {{ $trx->created_at->format('d M Y') }}<br>
              <span style="font-size:.72rem;">{{ $trx->created_at->format('h:i A') }}</span>
            </td>
            <td>
              <div class="tx-actions">
                <a href="{{ route('admin.transactions.invoice', $trx->id) }}" class="tx-btn tx-btn-primary" title="Invoice" target="_blank">
                  <i class="fas fa-file-invoice"></i> Invoice
                </a>
                <button class="tx-btn tx-btn-danger-ghost" title="Delete"
                  onclick="openDeleteModal('{{ $trx->id }}', '{{ $trx->transaction_id }}')">
                  <i class="fas fa-trash-alt"></i>
                </button>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="9">
            <div class="tx-empty">
              <i class="fas fa-exchange-alt"></i>
              <p>No transactions found.</p>
            </div>
          </td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Pagination --}}
    @if($transactions->hasPages())
      <div>{{ $transactions->links() }}</div>
    @endif

  </div>
</div>

{{-- Delete Modal --}}
<div class="tx-modal-overlay" id="deleteModal">
  <div class="tx-modal">
    <div class="tx-modal-body">
      <div class="tx-del-icon"><i class="fas fa-trash-alt"></i></div>
      <h5 style="font-weight:700;margin-bottom:8px;">Delete Transaction?</h5>
      <p style="color:var(--t-muted);font-size:.88rem;margin-bottom:4px;">
        Transaction <strong id="del-txn-id" style="color:var(--t-text);font-family:'JetBrains Mono',monospace;font-size:.85rem;"></strong> will be permanently removed.
      </p>
      <p style="color:var(--t-muted);font-size:.8rem;">This action <strong style="color:var(--t-danger)">cannot be undone</strong>.</p>
    </div>
    <div class="tx-modal-footer">
      <button class="tx-btn tx-btn-outline" onclick="closeDeleteModal()"><i class="fas fa-times"></i> Cancel</button>
      <form id="deleteForm" method="POST" style="display:inline;">
        @csrf
        <button type="submit" class="tx-btn tx-btn-primary" style="background:var(--t-danger);color:#fff;padding:9px 18px;font-size:.875rem;">
          <i class="fas fa-trash-alt"></i> Yes, Delete
        </button>
      </form>
    </div>
  </div>
</div>

<div id="tx-toast-container"></div>

<script>
function openDeleteModal(id, txnId) {
  document.getElementById('del-txn-id').textContent = txnId;
  document.getElementById('deleteForm').action = '/admin/transactions/delete/' + id;
  document.getElementById('deleteModal').classList.add('open');
  document.body.style.overflow = 'hidden';
}
function closeDeleteModal() {
  document.getElementById('deleteModal').classList.remove('open');
  document.body.style.overflow = '';
}
document.getElementById('deleteModal').addEventListener('click', function(e){
  if(e.target === this) closeDeleteModal();
});
document.addEventListener('keydown', e => { if(e.key==='Escape') closeDeleteModal(); });

function copyTxId(id) {
  navigator.clipboard.writeText(id).then(() => {
    showToast('info', 'Copied!', 'Transaction ID copied to clipboard.');
  });
}

// search + filter
(function(){
  var inp   = document.getElementById('tx-search');
  var rows  = Array.from(document.querySelectorAll('#tx-tbody tr[data-search]'));
  var cnt   = document.getElementById('tx-count');
  var tabs  = document.querySelectorAll('.tx-filter-tab');
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
      var matchF = active==='all' || r.dataset.status===active;
      r.style.display = (matchQ && matchF) ? '' : 'none';
      if(matchQ && matchF) v++;
    });
    cnt.textContent = v + ' of ' + rows.length + ' transactions';
  }
  update();
})();

function showToast(type, title, msg) {
  var icons = {success:'fas fa-check-circle', danger:'fas fa-exclamation-circle', info:'fas fa-copy'};
  var c = document.getElementById('tx-toast-container');
  var t = document.createElement('div');
  t.className = 'tx-toast tx-toast-' + type;
  t.innerHTML = `<div class="tx-toast-icon"><i class="${icons[type]||icons.info}"></i></div>
    <div><div class="tx-toast-title">${title}</div><div class="tx-toast-msg">${msg}</div></div>
    <span class="tx-toast-bar"></span>`;
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