@extends('layouts.admin')
@section('title', 'Contact Messages')
@section('page')

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');
:root {
  --p-surface:   #1a1d27;
  --p-surface2:  #222636;
  --p-border:    rgba(255,255,255,.07);
  --p-accent:    #0ea5e9;
  --p-accent2:   #38bdf8;
  --p-green:     #10b981;
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

.cm-wrap { font-family:'Plus Jakarta Sans',sans-serif; color:var(--p-text); }

/* ── HEADER ── */
.cm-header {
  background:linear-gradient(135deg,#0c1a2e 0%,#0d2d1f 50%,#091a2c 100%);
  border-radius:var(--p-radius); padding:28px 32px;
  margin-bottom:24px; position:relative; overflow:hidden; box-shadow:var(--p-shadow);
}
.cm-header::before {
  content:''; position:absolute; inset:0;
  background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%2310b981' fill-opacity='0.05'%3E%3Cpath d='M30 5L55 20v20L30 55 5 40V20Z'/%3E%3C/g%3E%3C/svg%3E");
}
.cm-header::after {
  content:''; position:absolute; right:-40px; top:-40px;
  width:200px; height:200px; border-radius:50%;
  background:radial-gradient(circle,rgba(16,185,129,.18) 0%,transparent 70%);
}
.cm-header .title {
  font-size:1.5rem; font-weight:700; position:relative; z-index:1;
  background:linear-gradient(90deg,#fff,#6ee7b7);
  -webkit-background-clip:text; -webkit-text-fill-color:transparent;
}
.cm-header .subtitle { color:rgba(255,255,255,.45); font-size:.85rem; margin-top:4px; position:relative; z-index:1; }

.stat-pill {
  display:inline-flex; align-items:center; gap:8px;
  background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.1);
  border-radius:40px; padding:6px 16px; font-size:.8rem; font-weight:600; color:#fff;
  position:relative; z-index:1;
}
.stat-pill .dot { width:8px; height:8px; border-radius:50%; }

/* ── CARD ── */
.cm-card {
  background:var(--p-surface); border:1px solid var(--p-border);
  border-radius:var(--p-radius); box-shadow:var(--p-shadow); overflow:hidden;
}

/* ── TABLE ── */
.cm-table { width:100%; border-collapse:collapse; }
.cm-table thead tr { background:var(--p-surface2); border-bottom:1px solid var(--p-border); }
.cm-table th {
  font-family:'Plus Jakarta Sans',sans-serif; font-size:.72rem; font-weight:700;
  letter-spacing:.08em; text-transform:uppercase; color:var(--p-muted);
  padding:14px 20px; text-align:left;
}
.cm-table td {
  padding:15px 20px; border-bottom:1px solid var(--p-border);
  font-size:.875rem; color:var(--p-text); vertical-align:middle;
}
.cm-table tbody tr:last-child td { border-bottom:none; }
.cm-table tbody tr { transition:background .15s; }
.cm-table tbody tr:hover { background:rgba(255,255,255,.02); }

/* unread row highlight */
.cm-table tbody tr.unread-row { border-left:3px solid var(--p-danger); }
.cm-table tbody tr.read-row   { border-left:3px solid transparent; }

/* avatar */
.cm-avatar {
  width:38px; height:38px; border-radius:50%;
  background:linear-gradient(135deg,var(--p-accent),var(--p-purple));
  display:flex; align-items:center; justify-content:center;
  font-size:.85rem; font-weight:700; color:#fff; flex-shrink:0;
}

.cm-name-wrap { display:flex; align-items:center; gap:10px; }
.cm-name { font-weight:600; font-size:.9rem; }
.cm-email { font-size:.76rem; color:var(--p-muted); margin-top:2px; font-family:'JetBrains Mono',monospace; }

.cm-subject { font-size:.85rem; color:var(--p-text); max-width:200px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }

/* badges */
.cm-badge { display:inline-flex; align-items:center; gap:5px; padding:4px 12px; border-radius:20px; font-size:.72rem; font-weight:700; }
.cm-badge-read   { background:rgba(34,197,94,.12); color:#86efac; border:1px solid rgba(34,197,94,.25); }
.cm-badge-unread { background:rgba(239,68,68,.12); color:#fca5a5; border:1px solid rgba(239,68,68,.25); }

/* serial */
.cm-serial { font-family:'JetBrains Mono',monospace; font-size:.78rem; color:var(--p-muted); font-weight:500; }

/* action btn */
.cm-view-btn {
  display:inline-flex; align-items:center; gap:6px;
  background:rgba(14,165,233,.12); color:var(--p-accent2);
  border:1px solid rgba(14,165,233,.25); border-radius:var(--p-radius-sm);
  padding:6px 14px; font-size:.8rem; font-weight:600;
  cursor:pointer; transition:all .2s; font-family:'Plus Jakarta Sans',sans-serif;
}
.cm-view-btn:hover {
  background:rgba(14,165,233,.22); border-color:rgba(56,189,248,.4);
  transform:translateY(-1px); box-shadow:0 4px 12px rgba(14,165,233,.2);
}

/* empty state */
.cm-empty { text-align:center; padding:70px 20px; color:var(--p-muted); }
.cm-empty i { font-size:2.5rem; margin-bottom:14px; opacity:.3; display:block; }
.cm-empty p { font-size:.9rem; }

/* pagination override */
.pagination { margin-top:20px; padding:0 20px 20px; }
.pagination .page-item .page-link {
  background:var(--p-surface2); border:1px solid var(--p-border);
  color:var(--p-muted); font-size:.82rem; border-radius:6px !important; margin:0 2px;
  transition:all .2s;
}
.pagination .page-item .page-link:hover { background:var(--p-border); color:var(--p-text); }
.pagination .page-item.active .page-link { background:var(--p-accent); border-color:var(--p-accent); color:#0c1a2e; font-weight:700; }

/* ── MODAL ── */
.cm-modal-overlay {
  position:fixed; inset:0; z-index:9999;
  background:rgba(0,0,0,.72); backdrop-filter:blur(6px);
  display:flex; align-items:center; justify-content:center;
  opacity:0; pointer-events:none; transition:opacity .25s;
}
.cm-modal-overlay.open { opacity:1; pointer-events:auto; }
.cm-modal {
  background:var(--p-surface); border:1px solid var(--p-border);
  border-radius:18px; width:min(620px,96vw); max-height:90vh;
  overflow-y:auto; box-shadow:0 24px 64px rgba(0,0,0,.6);
  transform:translateY(24px) scale(.97);
  transition:transform .3s cubic-bezier(.34,1.56,.64,1);
}
.cm-modal-overlay.open .cm-modal { transform:translateY(0) scale(1); }
.cm-modal::-webkit-scrollbar { width:5px; }
.cm-modal::-webkit-scrollbar-thumb { background:var(--p-border); border-radius:10px; }

.cm-modal-header {
  padding:22px 28px 18px; border-bottom:1px solid var(--p-border);
  display:flex; align-items:center; justify-content:space-between; position:sticky; top:0;
  background:var(--p-surface); z-index:2;
}
.cm-modal-title { font-size:1.05rem; font-weight:700; display:flex; align-items:center; gap:10px; }
.cm-modal-close {
  background:var(--p-surface2); border:1px solid var(--p-border); color:var(--p-muted);
  width:32px; height:32px; border-radius:8px; cursor:pointer;
  display:flex; align-items:center; justify-content:center; transition:all .2s;
}
.cm-modal-close:hover { color:var(--p-text); border-color:rgba(255,255,255,.15); }

.cm-modal-body { padding:26px 28px; }

/* sender card */
.cm-sender-card {
  background:var(--p-surface2); border:1px solid var(--p-border);
  border-radius:12px; padding:18px 20px; margin-bottom:22px;
  display:grid; grid-template-columns:auto 1fr; gap:16px; align-items:center;
}
.cm-sender-avatar {
  width:52px; height:52px; border-radius:50%;
  background:linear-gradient(135deg,var(--p-accent),var(--p-purple));
  display:flex; align-items:center; justify-content:center;
  font-size:1.2rem; font-weight:700; color:#fff;
}
.cm-sender-name  { font-size:1rem; font-weight:700; color:var(--p-text); }
.cm-sender-email { font-size:.8rem; color:var(--p-muted); font-family:'JetBrains Mono',monospace; margin-top:3px; }
.cm-sender-phone { display:inline-flex; align-items:center; gap:5px; font-size:.78rem; color:var(--p-muted); margin-top:5px; }

/* meta pills */
.cm-meta-row { display:flex; gap:10px; flex-wrap:wrap; margin-bottom:20px; }
.cm-meta-pill {
  display:inline-flex; align-items:center; gap:7px;
  background:var(--p-surface2); border:1px solid var(--p-border);
  border-radius:8px; padding:8px 14px; font-size:.8rem; color:var(--p-muted);
}
.cm-meta-pill i { color:var(--p-accent2); font-size:.75rem; }
.cm-meta-pill strong { color:var(--p-text); font-weight:600; }

/* subject */
.cm-subject-label {
  font-size:.7rem; font-weight:700; letter-spacing:.09em; text-transform:uppercase;
  color:var(--p-accent2); margin-bottom:8px; display:flex; align-items:center; gap:7px;
}
.cm-subject-label::after { content:''; flex:1; height:1px; background:var(--p-border); }
.cm-subject-text {
  font-size:.95rem; font-weight:600; color:var(--p-text);
  background:var(--p-surface2); border:1px solid var(--p-border);
  border-radius:9px; padding:12px 16px; margin-bottom:18px;
}

/* message body */
.cm-msg-label {
  font-size:.7rem; font-weight:700; letter-spacing:.09em; text-transform:uppercase;
  color:var(--p-accent2); margin-bottom:8px; display:flex; align-items:center; gap:7px;
}
.cm-msg-label::after { content:''; flex:1; height:1px; background:var(--p-border); }
.cm-msg-body {
  background:var(--p-surface2); border:1px solid var(--p-border);
  border-radius:12px; padding:18px 20px; font-size:.88rem;
  line-height:1.75; color:var(--p-text); white-space:pre-wrap;
  min-height:100px;
}

.cm-modal-footer {
  padding:18px 28px; border-top:1px solid var(--p-border);
  display:flex; gap:10px; justify-content:space-between; align-items:center;
  background:rgba(0,0,0,.15);
}
.cm-footer-status { font-size:.78rem; color:var(--p-muted); display:flex; align-items:center; gap:6px; }

.cm-btn { display:inline-flex; align-items:center; gap:7px; border:none; cursor:pointer; font-family:inherit; font-weight:600; border-radius:var(--p-radius-sm); transition:all .2s; text-decoration:none; font-size:.85rem; }
.cm-btn-outline { background:transparent; color:var(--p-text); border:1px solid var(--p-border); padding:9px 18px; }
.cm-btn-outline:hover { background:var(--p-surface2); color:var(--p-text); }
.cm-btn-danger  { background:rgba(239,68,68,.15); color:#fca5a5; border:1px solid rgba(239,68,68,.25); padding:9px 18px; }
.cm-btn-danger:hover { background:rgba(239,68,68,.25); }

/* delete confirm modal */
.cm-del-overlay {
  position:fixed; inset:0; z-index:10000;
  background:rgba(0,0,0,.8); backdrop-filter:blur(6px);
  display:flex; align-items:center; justify-content:center;
  opacity:0; pointer-events:none; transition:opacity .25s;
}
.cm-del-overlay.open { opacity:1; pointer-events:auto; }
.cm-del-box {
  background:var(--p-surface); border:1px solid var(--p-border);
  border-radius:18px; width:min(400px,94vw);
  box-shadow:0 24px 64px rgba(0,0,0,.7);
  transform:translateY(20px) scale(.97);
  transition:transform .28s cubic-bezier(.34,1.56,.64,1);
  overflow:hidden;
}
.cm-del-overlay.open .cm-del-box { transform:translateY(0) scale(1); }
.cm-del-icon {
  width:64px; height:64px; border-radius:50%;
  background:rgba(239,68,68,.12); border:2px solid rgba(239,68,68,.25);
  display:flex; align-items:center; justify-content:center;
  font-size:1.5rem; color:#fca5a5; margin:0 auto 18px;
}

/* toast */
#cm-toast { position:fixed; bottom:28px; right:28px; z-index:99999; display:flex; flex-direction:column; gap:10px; }
.cm-toast {
  display:flex; align-items:center; gap:12px;
  background:var(--p-surface); border:1px solid var(--p-border);
  border-radius:12px; padding:14px 18px; min-width:270px;
  box-shadow:0 8px 30px rgba(0,0,0,.5);
  transform:translateX(120%); transition:transform .35s cubic-bezier(.34,1.56,.64,1);
  font-family:'Plus Jakarta Sans',sans-serif; position:relative; overflow:hidden;
}
.cm-toast.show { transform:translateX(0); }
.cm-toast-icon { width:34px; height:34px; border-radius:9px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.cm-toast-s .cm-toast-icon { background:rgba(34,197,94,.15); color:var(--p-success); }
.cm-toast-d .cm-toast-icon { background:rgba(239,68,68,.15); color:var(--p-danger); }
.cm-toast-title { font-size:.875rem; font-weight:700; color:var(--p-text); }
.cm-toast-msg   { font-size:.77rem; color:var(--p-muted); margin-top:1px; }
.cm-toast-bar { position:absolute; bottom:0; left:0; height:3px; border-radius:0 0 12px 12px; animation:cmBar 3.2s linear forwards; }
.cm-toast-s .cm-toast-bar { background:var(--p-success); }
.cm-toast-d .cm-toast-bar { background:var(--p-danger); }
@keyframes cmBar { from{width:100%} to{width:0%} }
</style>

@if(session('success'))<div id="flash-s" data-msg="{{ session('success') }}"></div>@endif
@if(session('error'))<div id="flash-e"   data-msg="{{ session('error') }}"></div>@endif

<div class="cm-wrap">

  {{-- ── HEADER ── --}}
  <div class="cm-header">
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;">
      <div>
        <div class="title"><i class="fas fa-envelope-open-text me-2"></i>Contact Messages</div>
        <div class="subtitle">All incoming messages from the contact form</div>
        <div style="display:flex;gap:8px;margin-top:14px;flex-wrap:wrap;">
          <span class="stat-pill"><span class="dot" style="background:var(--p-danger)"></span>{{ $messages->where('is_read',0)->count() }} Unread</span>
          <span class="stat-pill"><span class="dot" style="background:var(--p-success)"></span>{{ $messages->where('is_read',1)->count() }} Read</span>
          <span class="stat-pill"><span class="dot" style="background:var(--p-accent2)"></span>{{ $messages->total() }} Total</span>
        </div>
      </div>
    </div>
  </div>

  {{-- ── TABLE CARD ── --}}
  <div class="cm-card">
    <div class="table-responsive">
      <table class="cm-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Sender</th>
            <th>Subject</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($messages as $key => $message)
          <tr class="{{ $message->is_read ? 'read-row' : 'unread-row' }}">

            <td><span class="cm-serial">{{ str_pad($messages->firstItem() + $key, 2, '0', STR_PAD_LEFT) }}</span></td>

            <td>
              <div class="cm-name-wrap">
                <div class="cm-avatar">{{ strtoupper(substr($message->name,0,1)) }}</div>
                <div>
                  <div class="cm-name">
                    {{ $message->name }}
                    @if(!$message->is_read)
                      <span style="display:inline-block;width:7px;height:7px;background:var(--p-danger);border-radius:50%;margin-left:6px;vertical-align:middle;"></span>
                    @endif
                  </div>
                  <div class="cm-email">{{ $message->email }}</div>
                </div>
              </div>
            </td>

            <td>
              <span class="cm-subject">{{ $message->subject }}</span>
            </td>

            <td>
              @if($message->is_read)
                <span class="cm-badge cm-badge-read"><i class="fas fa-circle" style="font-size:.4rem;"></i>Read</span>
              @else
                <span class="cm-badge cm-badge-unread"><i class="fas fa-circle" style="font-size:.4rem;"></i>Unread</span>
              @endif
            </td>

            <td>
              <button class="cm-view-btn"
                onclick="openViewModal({
                  id:      '{{ $message->id }}',
                  name:    {{ json_encode($message->name) }},
                  email:   {{ json_encode($message->email) }},
                  phone:   {{ json_encode($message->phone ?? '') }},
                  subject: {{ json_encode($message->subject) }},
                  body:    {{ json_encode($message->message) }},
                  is_read: {{ $message->is_read ? 'true' : 'false' }},
                  delete_url: '{{ route('admin.contact.delete', $message->id) }}'
                })">
                <i class="fas fa-eye"></i> View
              </button>
            </td>

          </tr>
          @empty
          <tr>
            <td colspan="5">
              <div class="cm-empty">
                <i class="fas fa-inbox"></i>
                <p>No messages yet. Your inbox is empty.</p>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($messages->hasPages())
      <div class="pagination">{{ $messages->links() }}</div>
    @endif
  </div>

</div>

{{-- ══════════════════════════════════
     VIEW MESSAGE MODAL
══════════════════════════════════ --}}
<div class="cm-modal-overlay" id="viewModal">
  <div class="cm-modal">

    <div class="cm-modal-header">
      <div class="cm-modal-title">
        <i class="fas fa-envelope-open" style="color:var(--p-accent2);"></i>
        Message Details
      </div>
      <button class="cm-modal-close" onclick="closeViewModal()"><i class="fas fa-times"></i></button>
    </div>

    <div class="cm-modal-body">

      {{-- Sender Card --}}
      <div class="cm-sender-card">
        <div class="cm-sender-avatar" id="modal-avatar">?</div>
        <div>
          <div class="cm-sender-name" id="modal-name">—</div>
          <div class="cm-sender-email" id="modal-email">—</div>
          <div class="cm-sender-phone" id="modal-phone-wrap" style="display:none;">
            <i class="fas fa-phone" style="color:var(--p-green);"></i>
            <span id="modal-phone">—</span>
          </div>
        </div>
      </div>

      {{-- Meta --}}
      <div class="cm-meta-row">
        <div class="cm-meta-pill">
          <i class="fas fa-circle-dot"></i>
          Status: <strong id="modal-status-text">—</strong>
        </div>
        <div class="cm-meta-pill" id="modal-phone-pill" style="display:none;">
          <i class="fas fa-phone"></i>
          Phone: <strong id="modal-phone-meta">—</strong>
        </div>
      </div>

      {{-- Subject --}}
      <div class="cm-subject-label"><i class="fas fa-tag"></i> Subject</div>
      <div class="cm-subject-text" id="modal-subject">—</div>

      {{-- Message --}}
      <div class="cm-msg-label"><i class="fas fa-comment-lines"></i> Message</div>
      <div class="cm-msg-body" id="modal-body">—</div>

    </div>

    <div class="cm-modal-footer">
      <div class="cm-footer-status" id="modal-badge-wrap"></div>
      <div style="display:flex;gap:10px;">
        <button class="cm-btn cm-btn-outline" onclick="closeViewModal()">
          <i class="fas fa-times"></i> Close
        </button>
        <button class="cm-btn cm-btn-danger" id="modal-delete-btn" onclick="openDeleteConfirm()">
          <i class="fas fa-trash-alt"></i> Delete
        </button>
      </div>
    </div>

  </div>
</div>

{{-- ══════════════════════════════════
     DELETE CONFIRM MODAL
══════════════════════════════════ --}}
<div class="cm-del-overlay" id="deleteConfirm">
  <div class="cm-del-box">
    <div style="text-align:center;padding:36px 28px 20px;">
      <div class="cm-del-icon"><i class="fas fa-envelope"></i></div>
      <h5 style="font-weight:700;margin-bottom:8px;color:var(--p-text);">Delete Message?</h5>
      <p style="color:var(--p-muted);font-size:.875rem;margin-bottom:4px;">
        Message from <strong id="del-sender-name" style="color:var(--p-text)"></strong> will be permanently deleted.
      </p>
      <p style="color:var(--p-muted);font-size:.8rem;">This action <strong style="color:var(--p-danger)">cannot be undone</strong>.</p>
    </div>
    <div style="padding:16px 28px 24px;display:flex;gap:10px;justify-content:center;">
      <button class="cm-btn cm-btn-outline" onclick="closeDeleteConfirm()">
        <i class="fas fa-times"></i> Cancel
      </button>
      <form id="deleteForm" method="POST" style="display:inline;">
        @csrf
        <button type="submit" class="cm-btn" style="background:var(--p-danger);color:#fff;padding:9px 20px;border:none;border-radius:var(--p-radius-sm);">
          <i class="fas fa-trash-alt"></i> Yes, Delete
        </button>
      </form>
    </div>
  </div>
</div>

<div id="cm-toast"></div>

<script>
  var _currentDeleteUrl = '';
  var _currentSenderName = '';

  /* ── View Modal ── */
  function openViewModal(d) {
    _currentDeleteUrl  = d.delete_url;
    _currentSenderName = d.name;

    // Avatar
    document.getElementById('modal-avatar').textContent = d.name.charAt(0).toUpperCase();

    // Sender info
    document.getElementById('modal-name').textContent  = d.name;
    document.getElementById('modal-email').textContent = d.email;

    // Phone
    if (d.phone) {
      document.getElementById('modal-phone').textContent      = d.phone;
      document.getElementById('modal-phone-meta').textContent = d.phone;
      document.getElementById('modal-phone-wrap').style.display = 'flex';
      document.getElementById('modal-phone-pill').style.display = 'flex';
    } else {
      document.getElementById('modal-phone-wrap').style.display = 'none';
      document.getElementById('modal-phone-pill').style.display = 'none';
    }

    // Subject & body
    document.getElementById('modal-subject').textContent = d.subject;
    document.getElementById('modal-body').textContent    = d.body;

    // Status
    var statusText = d.is_read ? 'Read' : 'Unread';
    document.getElementById('modal-status-text').textContent = statusText;
    document.getElementById('modal-badge-wrap').innerHTML = d.is_read
      ? '<span class="cm-badge cm-badge-read"><i class="fas fa-circle" style="font-size:.4rem;"></i> Read</span>'
      : '<span class="cm-badge cm-badge-unread"><i class="fas fa-circle" style="font-size:.4rem;"></i> Unread</span>';

    document.getElementById('viewModal').classList.add('open');
    document.body.style.overflow = 'hidden';

    // Mark as read via AJAX if unread
    if (!d.is_read) {
      fetch('/admin/contact/read/' + d.id, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
      }).then(() => {
        // update badge in table row silently
        var btns = document.querySelectorAll('.cm-view-btn');
        btns.forEach(function(btn) {
          if (btn.getAttribute('onclick') && btn.getAttribute('onclick').includes("'" + d.id + "'")) {
            var row = btn.closest('tr');
            if (row) {
              row.classList.remove('unread-row');
              row.classList.add('read-row');
              var badge = row.querySelector('.cm-badge');
              if (badge) {
                badge.className = 'cm-badge cm-badge-read';
                badge.innerHTML = '<i class="fas fa-circle" style="font-size:.4rem;"></i>Read';
              }
            }
          }
        });
      }).catch(function(){});
    }
  }

  function closeViewModal() {
    document.getElementById('viewModal').classList.remove('open');
    document.body.style.overflow = '';
  }

  /* ── Delete Confirm ── */
  function openDeleteConfirm() {
    document.getElementById('del-sender-name').textContent = _currentSenderName;
    document.getElementById('deleteForm').action = _currentDeleteUrl;
    document.getElementById('deleteConfirm').classList.add('open');
  }
  function closeDeleteConfirm() {
    document.getElementById('deleteConfirm').classList.remove('open');
  }

  /* Close on backdrop click */
  document.getElementById('viewModal').addEventListener('click', function(e) {
    if (e.target === this) closeViewModal();
  });
  document.getElementById('deleteConfirm').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteConfirm();
  });

  /* Escape key */
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      closeDeleteConfirm();
      closeViewModal();
    }
  });

  /* ── Toast ── */
  function showToast(type, title, msg) {
    var c = document.getElementById('cm-toast');
    var t = document.createElement('div');
    var icon = type === 's' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
    t.className = 'cm-toast cm-toast-' + type;
    t.innerHTML = '<div class="cm-toast-icon"><i class="' + icon + '"></i></div>' +
      '<div><div class="cm-toast-title">' + title + '</div><div class="cm-toast-msg">' + msg + '</div></div>' +
      '<span class="cm-toast-bar"></span>';
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