@extends('layouts.admin')
@section('title', 'Subscribers')
@section('page')

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');
:root {
  --p-surface:   #1a1d27;
  --p-surface2:  #222636;
  --p-border:    rgba(255,255,255,.07);
  --p-accent:    #0ea5e9;
  --p-accent2:   #38bdf8;
  --p-success:   #22c55e;
  --p-danger:    #ef4444;
  --p-warning:   #f59e0b;
  --p-purple:    #8b5cf6;
  --p-text:      #e2e8f0;
  --p-muted:     #64748b;
  --p-radius:    14px;
  --p-radius-sm: 8px;
  --p-shadow:    0 8px 32px rgba(0,0,0,.45);
}

.sb-wrap { font-family:'Plus Jakarta Sans',sans-serif; color:var(--p-text); }

/* ── HEADER ── */
.sb-header {
  background:linear-gradient(135deg,#0c1a2e 0%,#1a0c2e 50%,#0c1a2e 100%);
  border-radius:var(--p-radius); padding:28px 32px;
  margin-bottom:24px; position:relative; overflow:hidden; box-shadow:var(--p-shadow);
}
.sb-header::before {
  content:''; position:absolute; inset:0;
  background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%238b5cf6' fill-opacity='0.05'%3E%3Cpath d='M30 5L55 20v20L30 55 5 40V20Z'/%3E%3C/g%3E%3C/svg%3E");
}
.sb-header::after {
  content:''; position:absolute; right:-40px; top:-40px;
  width:200px; height:200px; border-radius:50%;
  background:radial-gradient(circle,rgba(139,92,246,.18) 0%,transparent 70%);
}
.sb-header .title {
  font-size:1.5rem; font-weight:700; position:relative; z-index:1;
  background:linear-gradient(90deg,#fff,#c4b5fd);
  -webkit-background-clip:text; -webkit-text-fill-color:transparent;
}
.sb-header .subtitle { color:rgba(255,255,255,.45); font-size:.85rem; margin-top:4px; position:relative; z-index:1; }
.stat-pill {
  display:inline-flex; align-items:center; gap:8px;
  background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.1);
  border-radius:40px; padding:6px 16px; font-size:.8rem; font-weight:600; color:#fff;
  position:relative; z-index:1;
}
.stat-pill .dot { width:8px; height:8px; border-radius:50%; }

/* ── CARD ── */
.sb-card {
  background:var(--p-surface); border:1px solid var(--p-border);
  border-radius:var(--p-radius); box-shadow:var(--p-shadow); overflow:hidden;
}

/* ── TABLE ── */
.sb-table { width:100%; border-collapse:collapse; }
.sb-table thead tr { background:var(--p-surface2); border-bottom:1px solid var(--p-border); }
.sb-table th {
  font-size:.72rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase;
  color:var(--p-muted); padding:14px 20px; text-align:left;
}
.sb-table td {
  padding:15px 20px; border-bottom:1px solid var(--p-border);
  font-size:.875rem; color:var(--p-text); vertical-align:middle;
}
.sb-table tbody tr:last-child td { border-bottom:none; }
.sb-table tbody tr { transition:background .15s; }
.sb-table tbody tr:hover { background:rgba(255,255,255,.02); }

.sb-serial { font-family:'JetBrains Mono',monospace; font-size:.78rem; color:var(--p-muted); font-weight:500; }

/* email cell */
.sb-email-wrap { display:flex; align-items:center; gap:12px; }
.sb-avatar {
  width:36px; height:36px; border-radius:50%;
  background:linear-gradient(135deg,var(--p-purple),var(--p-accent));
  display:flex; align-items:center; justify-content:center;
  font-size:.8rem; font-weight:700; color:#fff; flex-shrink:0; text-transform:uppercase;
}
.sb-email-text { font-family:'JetBrains Mono',monospace; font-size:.82rem; color:var(--p-accent2); }

/* date */
.sb-date { font-size:.8rem; color:var(--p-muted); display:flex; align-items:center; gap:6px; }
.sb-date i { color:var(--p-purple); font-size:.72rem; }

/* delete btn */
.sb-del-btn {
  display:inline-flex; align-items:center; gap:6px;
  background:rgba(239,68,68,.1); color:#fca5a5;
  border:1px solid rgba(239,68,68,.2); border-radius:var(--p-radius-sm);
  padding:6px 14px; font-size:.8rem; font-weight:600;
  cursor:pointer; transition:all .2s; font-family:'Plus Jakarta Sans',sans-serif;
}
.sb-del-btn:hover { background:rgba(239,68,68,.2); border-color:rgba(239,68,68,.35); transform:translateY(-1px); }

/* empty */
.sb-empty { text-align:center; padding:70px 20px; color:var(--p-muted); }
.sb-empty i { font-size:2.5rem; margin-bottom:14px; opacity:.3; display:block; }

/* pagination */
.pagination { margin-top:0; padding:16px 20px; }
.pagination .page-item .page-link {
  background:var(--p-surface2); border:1px solid var(--p-border);
  color:var(--p-muted); font-size:.82rem; border-radius:6px !important; margin:0 2px; transition:all .2s;
}
.pagination .page-item .page-link:hover { background:var(--p-border); color:var(--p-text); }
.pagination .page-item.active .page-link { background:var(--p-purple); border-color:var(--p-purple); color:#fff; font-weight:700; }

/* ── DELETE MODAL ── */
.sb-del-overlay {
  position:fixed; inset:0; z-index:9999;
  background:rgba(0,0,0,.75); backdrop-filter:blur(6px);
  display:flex; align-items:center; justify-content:center;
  opacity:0; pointer-events:none; transition:opacity .25s;
}
.sb-del-overlay.open { opacity:1; pointer-events:auto; }
.sb-del-box {
  background:var(--p-surface); border:1px solid var(--p-border);
  border-radius:18px; width:min(400px,94vw);
  box-shadow:0 24px 64px rgba(0,0,0,.7);
  transform:translateY(20px) scale(.97);
  transition:transform .28s cubic-bezier(.34,1.56,.64,1); overflow:hidden;
}
.sb-del-overlay.open .sb-del-box { transform:translateY(0) scale(1); }
.sb-del-icon {
  width:64px; height:64px; border-radius:50%;
  background:rgba(239,68,68,.12); border:2px solid rgba(239,68,68,.25);
  display:flex; align-items:center; justify-content:center;
  font-size:1.5rem; color:#fca5a5; margin:0 auto 16px;
}
.sb-del-body { text-align:center; padding:36px 28px 20px; }
.sb-del-body h5 { font-weight:700; margin-bottom:8px; color:var(--p-text); font-size:1.05rem; }
.sb-del-body p  { color:var(--p-muted); font-size:.875rem; margin:0; line-height:1.6; }
.sb-del-footer  { padding:16px 28px 24px; display:flex; gap:10px; justify-content:center; }

.sb-btn { display:inline-flex; align-items:center; gap:7px; border:none; cursor:pointer; font-family:'Plus Jakarta Sans',sans-serif; font-weight:600; border-radius:var(--p-radius-sm); transition:all .2s; font-size:.85rem; padding:9px 20px; }
.sb-btn-outline { background:transparent; color:var(--p-text); border:1px solid var(--p-border); }
.sb-btn-outline:hover { background:var(--p-surface2); }
.sb-btn-danger  { background:var(--p-danger); color:#fff; border:none; }
.sb-btn-danger:hover { background:#dc2626; transform:translateY(-1px); box-shadow:0 4px 14px rgba(239,68,68,.35); }

/* ── TOAST ── */
#sb-toast { position:fixed; bottom:28px; right:28px; z-index:99999; display:flex; flex-direction:column; gap:10px; }
.sb-toast {
  display:flex; align-items:center; gap:12px;
  background:var(--p-surface); border:1px solid var(--p-border);
  border-radius:12px; padding:14px 18px; min-width:260px;
  box-shadow:0 8px 30px rgba(0,0,0,.5);
  transform:translateX(120%); transition:transform .35s cubic-bezier(.34,1.56,.64,1);
  font-family:'Plus Jakarta Sans',sans-serif; position:relative; overflow:hidden;
}
.sb-toast.show { transform:translateX(0); }
.sb-toast-icon { width:34px; height:34px; border-radius:9px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.sb-toast-s .sb-toast-icon { background:rgba(34,197,94,.15); color:var(--p-success); }
.sb-toast-d .sb-toast-icon { background:rgba(239,68,68,.15); color:var(--p-danger); }
.sb-toast-title { font-size:.875rem; font-weight:700; color:var(--p-text); }
.sb-toast-msg   { font-size:.77rem; color:var(--p-muted); margin-top:1px; }
.sb-toast-bar   { position:absolute; bottom:0; left:0; height:3px; border-radius:0 0 12px 12px; animation:sbBar 3.2s linear forwards; }
.sb-toast-s .sb-toast-bar { background:var(--p-success); }
.sb-toast-d .sb-toast-bar { background:var(--p-danger); }
@keyframes sbBar { from{width:100%} to{width:0%} }
</style>

@if(session('success'))<div id="flash-s" data-msg="{{ session('success') }}"></div>@endif
@if(session('error'))<div id="flash-e"   data-msg="{{ session('error') }}"></div>@endif

<div class="sb-wrap">

  {{-- ── HEADER ── --}}
  <div class="sb-header">
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;">
      <div>
        <div class="title"><i class="fas fa-bell me-2"></i>Subscribers</div>
        <div class="subtitle">Everyone who subscribed to your newsletter</div>
        <div style="display:flex;gap:8px;margin-top:14px;flex-wrap:wrap;">
          <span class="stat-pill">
            <span class="dot" style="background:var(--p-purple)"></span>
            {{ $subscribers->total() }} Total Subscribers
          </span>
        </div>
      </div>
    </div>
  </div>

  {{-- ── TABLE CARD ── --}}
  <div class="sb-card">
    <div class="table-responsive">
      <table class="sb-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Email Address</th>
            <th>Subscribed On</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($subscribers as $key => $subscriber)
          <tr>
            <td><span class="sb-serial">{{ str_pad($subscribers->firstItem() + $key, 2, '0', STR_PAD_LEFT) }}</span></td>
            <td>
              <div class="sb-email-wrap">
                <div class="sb-avatar">{{ strtoupper(substr($subscriber->email, 0, 1)) }}</div>
                <span class="sb-email-text">{{ $subscriber->email }}</span>
              </div>
            </td>
            <td>
              <span class="sb-date">
                <i class="fas fa-calendar-alt"></i>
                {{ $subscriber->created_at->format('d M Y') }}
              </span>
            </td>
            <td>
              <button class="sb-del-btn"
                onclick="openDeleteModal('{{ $subscriber->id }}', '{{ $subscriber->email }}')">
                <i class="fas fa-trash-alt"></i> Delete
              </button>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="4">
              <div class="sb-empty">
                <i class="fas fa-bell-slash"></i>
                <p>No subscribers yet.</p>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($subscribers->hasPages())
      <div class="pagination">{{ $subscribers->links() }}</div>
    @endif
  </div>

</div>

{{-- ── DELETE CONFIRM MODAL ── --}}
<div class="sb-del-overlay" id="deleteModal">
  <div class="sb-del-box">
    <div class="sb-del-body">
      <div class="sb-del-icon"><i class="fas fa-user-slash"></i></div>
      <h5>Remove Subscriber?</h5>
      <p>
        <strong id="del-email" style="color:var(--p-accent2);font-family:'JetBrains Mono',monospace;font-size:.82rem;display:block;margin-bottom:6px;"></strong>
        will be permanently removed from your subscriber list.
      </p>
    </div>
    <div class="sb-del-footer">
      <button class="sb-btn sb-btn-outline" onclick="closeDeleteModal()">
        <i class="fas fa-times"></i> Cancel
      </button>
      <form id="deleteForm" method="POST" style="display:inline;">
        @csrf
        <button type="submit" class="sb-btn sb-btn-danger">
          <i class="fas fa-trash-alt"></i> Yes, Remove
        </button>
      </form>
    </div>
  </div>
</div>

<div id="sb-toast"></div>

<script>
  function openDeleteModal(id, email) {
    document.getElementById('del-email').textContent = email;
    document.getElementById('deleteForm').action = '/admin/subscribers/delete/' + id;
    document.getElementById('deleteModal').classList.add('open');
    document.body.style.overflow = 'hidden';
  }
  function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('open');
    document.body.style.overflow = '';
  }
  document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
  });
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeDeleteModal();
  });

  function showToast(type, title, msg) {
    var c = document.getElementById('sb-toast');
    var t = document.createElement('div');
    var icon = type === 's' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
    t.className = 'sb-toast sb-toast-' + type;
    t.innerHTML = '<div class="sb-toast-icon"><i class="' + icon + '"></i></div>' +
      '<div><div class="sb-toast-title">' + title + '</div><div class="sb-toast-msg">' + msg + '</div></div>' +
      '<span class="sb-toast-bar"></span>';
    c.appendChild(t);
    setTimeout(function(){ t.classList.add('show'); }, 20);
    setTimeout(function(){ t.classList.remove('show'); setTimeout(function(){ t.remove(); }, 400); }, 3500);
  }
  (function() {
    var s = document.getElementById('flash-s');
    var e = document.getElementById('flash-e');
    if (s) showToast('s', 'Success', s.dataset.msg);
    if (e) showToast('d', 'Error',   e.dataset.msg);
  })();
</script>

@endsection