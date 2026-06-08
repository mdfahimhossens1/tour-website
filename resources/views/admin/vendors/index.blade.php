@extends('layouts.admin')
@section('title', 'Vendors')
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
  --p-indigo:    #6366f1;
  --p-text:      #e2e8f0;
  --p-muted:     #64748b;
  --p-radius:    14px;
  --p-radius-sm: 8px;
  --p-shadow:    0 8px 32px rgba(0,0,0,.45);
}
.vn-wrap { font-family:'Plus Jakarta Sans',sans-serif; color:var(--p-text); }

/* ── HEADER ── */
.vn-header {
  background:linear-gradient(135deg,#0c1a2e 0%,#0e0c2e 55%,#0c1a2e 100%);
  border-radius:var(--p-radius); padding:28px 32px; margin-bottom:24px;
  position:relative; overflow:hidden; box-shadow:var(--p-shadow);
}
.vn-header::before {
  content:''; position:absolute; inset:0;
  background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%236366f1' fill-opacity='0.05'%3E%3Cpath d='M30 5L55 20v20L30 55 5 40V20Z'/%3E%3C/g%3E%3C/svg%3E");
}
.vn-header::after {
  content:''; position:absolute; right:-40px; top:-40px; width:200px; height:200px;
  border-radius:50%; background:radial-gradient(circle,rgba(99,102,241,.2) 0%,transparent 70%);
}
.vn-header .title {
  font-size:1.5rem; font-weight:700; position:relative; z-index:1;
  background:linear-gradient(90deg,#fff,#a5b4fc);
  -webkit-background-clip:text; -webkit-text-fill-color:transparent;
}
.vn-header .subtitle { color:rgba(255,255,255,.45); font-size:.85rem; margin-top:4px; position:relative; z-index:1; }
.stat-pill {
  display:inline-flex; align-items:center; gap:8px;
  background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.1);
  border-radius:40px; padding:6px 16px; font-size:.8rem; font-weight:600; color:#fff; position:relative; z-index:1;
}
.stat-pill .dot { width:8px; height:8px; border-radius:50%; }

/* ── CARD ── */
.vn-card {
  background:var(--p-surface); border:1px solid var(--p-border);
  border-radius:var(--p-radius); box-shadow:var(--p-shadow); overflow:hidden;
}

/* ── TABLE ── */
.vn-table { width:100%; border-collapse:collapse; }
.vn-table thead tr { background:var(--p-surface2); border-bottom:1px solid var(--p-border); }
.vn-table th {
  font-size:.7rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase;
  color:var(--p-muted); padding:13px 20px; text-align:left;
}
.vn-table td {
  padding:15px 20px; border-bottom:1px solid var(--p-border);
  font-size:.875rem; color:var(--p-text); vertical-align:middle;
}
.vn-table tbody tr:last-child td { border-bottom:none; }
.vn-table tbody tr { transition:background .15s; }
.vn-table tbody tr:hover { background:rgba(255,255,255,.02); }

/* vendor avatar + name */
.vn-user-wrap { display:flex; align-items:center; gap:11px; }
.vn-avatar {
  width:38px; height:38px; border-radius:50%;
  background:linear-gradient(135deg,var(--p-indigo),var(--p-purple));
  display:flex; align-items:center; justify-content:center;
  font-size:.85rem; font-weight:700; color:#fff; flex-shrink:0; text-transform:uppercase;
}
.vn-user-name  { font-weight:600; font-size:.88rem; }
.vn-user-email { font-size:.75rem; color:var(--p-muted); font-family:'JetBrains Mono',monospace; margin-top:2px; }

/* business */
.vn-biz { font-size:.85rem; color:var(--p-text); display:flex; align-items:center; gap:7px; }
.vn-biz i { color:var(--p-indigo); font-size:.72rem; }

/* serial */
.vn-serial { font-family:'JetBrains Mono',monospace; font-size:.78rem; color:var(--p-muted); }

/* status badges */
.vn-badge { display:inline-flex; align-items:center; gap:5px; padding:4px 12px; border-radius:20px; font-size:.72rem; font-weight:700; text-transform:capitalize; }
.vn-badge-approved { background:rgba(34,197,94,.12);  color:#86efac; border:1px solid rgba(34,197,94,.25); }
.vn-badge-pending  { background:rgba(245,158,11,.12); color:#fcd34d; border:1px solid rgba(245,158,11,.25); }
.vn-badge-rejected { background:rgba(239,68,68,.12);  color:#fca5a5; border:1px solid rgba(239,68,68,.25); }

/* action btns */
.vn-actions { display:flex; gap:7px; align-items:center; }
.vn-btn-view {
  display:inline-flex; align-items:center; gap:5px;
  background:rgba(99,102,241,.1); color:#a5b4fc;
  border:1px solid rgba(99,102,241,.2); border-radius:6px;
  padding:5px 12px; font-size:.78rem; font-weight:600;
  cursor:pointer; transition:all .2s; font-family:'Plus Jakarta Sans',sans-serif;
}
.vn-btn-view:hover { background:rgba(99,102,241,.2); transform:translateY(-1px); }
.vn-btn-approve {
  display:inline-flex; align-items:center; gap:5px;
  background:rgba(34,197,94,.12); color:#86efac;
  border:1px solid rgba(34,197,94,.25); border-radius:6px;
  padding:5px 12px; font-size:.78rem; font-weight:600;
  cursor:pointer; transition:all .2s; font-family:'Plus Jakarta Sans',sans-serif;
}
.vn-btn-approve:hover { background:rgba(34,197,94,.22); transform:translateY(-1px); }

/* empty */
.vn-empty { text-align:center; padding:70px 20px; color:var(--p-muted); }
.vn-empty i { font-size:2.5rem; margin-bottom:14px; opacity:.3; display:block; }

/* pagination */
.pagination { padding:14px 20px; }
.pagination .page-item .page-link {
  background:var(--p-surface2); border:1px solid var(--p-border);
  color:var(--p-muted); font-size:.82rem; border-radius:6px !important; margin:0 2px; transition:all .2s;
}
.pagination .page-item .page-link:hover { background:var(--p-border); color:var(--p-text); }
.pagination .page-item.active .page-link { background:var(--p-indigo); border-color:var(--p-indigo); color:#fff; font-weight:700; }

/* ── MODAL BASE ── */
.vn-overlay {
  position:fixed; inset:0; z-index:9999;
  background:rgba(0,0,0,.76); backdrop-filter:blur(6px);
  display:flex; align-items:center; justify-content:center;
  opacity:0; pointer-events:none; transition:opacity .25s;
}
.vn-overlay.open { opacity:1; pointer-events:auto; }
.vn-modal {
  background:var(--p-surface); border:1px solid var(--p-border);
  border-radius:18px; width:min(520px,96vw); max-height:90vh; overflow-y:auto;
  box-shadow:0 24px 64px rgba(0,0,0,.7);
  transform:translateY(24px) scale(.97);
  transition:transform .3s cubic-bezier(.34,1.56,.64,1);
}
.vn-overlay.open .vn-modal { transform:translateY(0) scale(1); }
.vn-modal::-webkit-scrollbar { width:5px; }
.vn-modal::-webkit-scrollbar-thumb { background:var(--p-border); border-radius:10px; }
.vn-modal-header {
  padding:20px 26px 16px; border-bottom:1px solid var(--p-border);
  display:flex; align-items:center; justify-content:space-between;
  background:var(--p-surface2); position:sticky; top:0; z-index:2;
}
.vn-modal-title { font-size:1.05rem; font-weight:700; display:flex; align-items:center; gap:9px; }
.vn-modal-close {
  background:var(--p-surface); border:1px solid var(--p-border); color:var(--p-muted);
  width:30px; height:30px; border-radius:7px; cursor:pointer;
  display:flex; align-items:center; justify-content:center; transition:all .2s; font-size:.85rem;
}
.vn-modal-close:hover { color:var(--p-text); }
.vn-modal-body   { padding:24px 26px; }
.vn-modal-footer { padding:14px 26px 20px; border-top:1px solid var(--p-border); display:flex; gap:10px; justify-content:flex-end; background:rgba(0,0,0,.1); }

/* view modal internals */
.vn-profile-card {
  background:var(--p-surface2); border:1px solid var(--p-border);
  border-radius:12px; padding:18px 20px; margin-bottom:20px;
  display:flex; align-items:center; gap:16px;
}
.vn-profile-avatar {
  width:56px; height:56px; border-radius:50%;
  background:linear-gradient(135deg,var(--p-indigo),var(--p-purple));
  display:flex; align-items:center; justify-content:center;
  font-size:1.3rem; font-weight:700; color:#fff; flex-shrink:0; text-transform:uppercase;
}
.vn-profile-name  { font-size:1rem; font-weight:700; color:var(--p-text); }
.vn-profile-email { font-size:.78rem; color:var(--p-muted); font-family:'JetBrains Mono',monospace; margin-top:3px; }

.vn-detail-grid { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
.vn-detail-item label { font-size:.69rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:var(--p-muted); display:block; margin-bottom:4px; }
.vn-detail-item .val { font-size:.875rem; color:var(--p-text); font-weight:500; }

.vn-sec { font-size:.69rem; font-weight:700; letter-spacing:.09em; text-transform:uppercase; color:var(--p-indigo); margin:0 0 14px; display:flex; align-items:center; gap:8px; }
.vn-sec::after { content:''; flex:1; height:1px; background:var(--p-border); }

/* approve confirm modal */
.vn-approve-body { text-align:center; padding:32px 28px 16px; }
.vn-approve-icon { width:64px; height:64px; border-radius:50%; background:rgba(34,197,94,.12); border:2px solid rgba(34,197,94,.25); display:flex; align-items:center; justify-content:center; font-size:1.5rem; color:#86efac; margin:0 auto 16px; }
.vn-approve-body h5 { font-weight:700; margin-bottom:8px; color:var(--p-text); }
.vn-approve-body p  { color:var(--p-muted); font-size:.875rem; line-height:1.6; margin:0; }
.vn-approve-footer  { padding:14px 28px 24px; display:flex; gap:10px; justify-content:center; }

.vn-btn { display:inline-flex; align-items:center; gap:7px; border:none; cursor:pointer; font-family:'Plus Jakarta Sans',sans-serif; font-weight:600; border-radius:var(--p-radius-sm); transition:all .2s; font-size:.85rem; padding:9px 20px; }
.vn-btn-outline  { background:transparent; color:var(--p-text); border:1px solid var(--p-border); }
.vn-btn-outline:hover { background:var(--p-surface2); }
.vn-btn-success  { background:var(--p-success); color:#fff; border:none; }
.vn-btn-success:hover { background:#16a34a; transform:translateY(-1px); box-shadow:0 4px 14px rgba(34,197,94,.3); }

/* Toast */
#vn-toast { position:fixed; bottom:28px; right:28px; z-index:99999; display:flex; flex-direction:column; gap:10px; }
.vn-toast { display:flex; align-items:center; gap:12px; background:var(--p-surface); border:1px solid var(--p-border); border-radius:12px; padding:14px 18px; min-width:260px; box-shadow:0 8px 30px rgba(0,0,0,.5); transform:translateX(120%); transition:transform .35s cubic-bezier(.34,1.56,.64,1); font-family:'Plus Jakarta Sans',sans-serif; position:relative; overflow:hidden; }
.vn-toast.show { transform:translateX(0); }
.vn-toast-icon { width:34px; height:34px; border-radius:9px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.vn-toast-s .vn-toast-icon { background:rgba(34,197,94,.15); color:var(--p-success); }
.vn-toast-d .vn-toast-icon { background:rgba(239,68,68,.15); color:var(--p-danger); }
.vn-toast-title { font-size:.875rem; font-weight:700; color:var(--p-text); }
.vn-toast-msg   { font-size:.77rem; color:var(--p-muted); margin-top:1px; }
.vn-toast-bar   { position:absolute; bottom:0; left:0; height:3px; border-radius:0 0 12px 12px; animation:vnBar 3.2s linear forwards; }
.vn-toast-s .vn-toast-bar { background:var(--p-success); }
.vn-toast-d .vn-toast-bar { background:var(--p-danger); }
@keyframes vnBar { from{width:100%} to{width:0%} }
</style>

@if(session('success'))<div id="flash-s" data-msg="{{ session('success') }}"></div>@endif
@if(session('error'))<div id="flash-e"   data-msg="{{ session('error') }}"></div>@endif

<div class="vn-wrap">

  {{-- HEADER --}}
  <div class="vn-header">
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;">
      <div>
        <div class="title"><i class="fas fa-store me-2"></i>Vendors</div>
        <div class="subtitle">Review and manage vendor applications</div>
        <div style="display:flex;gap:8px;margin-top:14px;flex-wrap:wrap;">
          <span class="stat-pill"><span class="dot" style="background:var(--p-success)"></span>{{ $vendors->where('status','approved')->count() }} Approved</span>
          <span class="stat-pill"><span class="dot" style="background:var(--p-warning)"></span>{{ $vendors->where('status','pending')->count() }} Pending</span>
          <span class="stat-pill"><span class="dot" style="background:var(--p-indigo)"></span>{{ $vendors->total() }} Total</span>
        </div>
      </div>
    </div>
  </div>

  {{-- TABLE --}}
  <div class="vn-card">
    <div class="table-responsive">
      <table class="vn-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Vendor</th>
            <th>Business</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($vendors as $key => $vendor)
          <tr>
            <td><span class="vn-serial">{{ str_pad($vendors->firstItem()+$key,2,'0',STR_PAD_LEFT) }}</span></td>

            <td>
              <div class="vn-user-wrap">
                <div class="vn-avatar">{{ strtoupper(substr($vendor->user->name ?? 'V',0,1)) }}</div>
                <div>
                  <div class="vn-user-name">{{ $vendor->user->name ?? '—' }}</div>
                  <div class="vn-user-email">{{ $vendor->user->email ?? '—' }}</div>
                </div>
              </div>
            </td>

            <td>
              <span class="vn-biz">
                <i class="fas fa-building"></i>
                {{ $vendor->business_name }}
              </span>
            </td>

            <td>
              @php $st = $vendor->status; @endphp
              <span class="vn-badge vn-badge-{{ $st }}">
                <i class="fas fa-circle" style="font-size:.4rem;"></i>
                {{ ucfirst($st) }}
              </span>
            </td>

            <td>
              <div class="vn-actions">
                {{-- VIEW --}}
                <button class="vn-btn-view"
                  onclick="openViewModal({
                    name:     {{ json_encode($vendor->user->name ?? '—') }},
                    email:    {{ json_encode($vendor->user->email ?? '—') }},
                    business: {{ json_encode($vendor->business_name) }},
                    status:   '{{ $vendor->status }}',
                    phone:    {{ json_encode($vendor->phone ?? '') }},
                    address:  {{ json_encode($vendor->address ?? '') }},
                    desc:     {{ json_encode($vendor->description ?? '') }},
                    joined:   '{{ $vendor->created_at->format('d M Y') }}'
                  })">
                  <i class="fas fa-eye"></i> View
                </button>

                {{-- APPROVE --}}
                @if($vendor->status == 'pending')
                <button class="vn-btn-approve"
                  onclick="openApproveModal('{{ route('admin.vendors.approve',$vendor->id) }}', {{ json_encode($vendor->business_name) }})">
                  <i class="fas fa-check"></i> Approve
                </button>
                @endif
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5">
              <div class="vn-empty">
                <i class="fas fa-store-slash"></i>
                <p>No vendor applications yet.</p>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($vendors->hasPages())
      <div class="pagination">{{ $vendors->links() }}</div>
    @endif
  </div>

</div>

{{-- ══════════════════════════
     VIEW MODAL
══════════════════════════ --}}
<div class="vn-overlay" id="viewModal">
  <div class="vn-modal">
    <div class="vn-modal-header">
      <div class="vn-modal-title"><i class="fas fa-store" style="color:var(--p-indigo);"></i> Vendor Details</div>
      <button class="vn-modal-close" onclick="closeModal('viewModal')"><i class="fas fa-times"></i></button>
    </div>
    <div class="vn-modal-body">

      <div class="vn-profile-card">
        <div class="vn-profile-avatar" id="v-avatar">?</div>
        <div>
          <div class="vn-profile-name"  id="v-name">—</div>
          <div class="vn-profile-email" id="v-email">—</div>
        </div>
      </div>

      <div class="vn-sec"><i class="fas fa-info-circle"></i> Business Info</div>
      <div class="vn-detail-grid">
        <div class="vn-detail-item">
          <label>Business Name</label>
          <div class="val" id="v-business">—</div>
        </div>
        <div class="vn-detail-item">
          <label>Status</label>
          <div class="val" id="v-status">—</div>
        </div>
        <div class="vn-detail-item" id="v-phone-wrap" style="display:none;">
          <label>Phone</label>
          <div class="val" id="v-phone">—</div>
        </div>
        <div class="vn-detail-item">
          <label>Joined</label>
          <div class="val" id="v-joined">—</div>
        </div>
        <div class="vn-detail-item" id="v-address-wrap" style="display:none;grid-column:1/-1;">
          <label>Address</label>
          <div class="val" id="v-address">—</div>
        </div>
      </div>

      <div id="v-desc-wrap" style="display:none;margin-top:16px;">
        <div class="vn-sec" style="margin-top:0;"><i class="fas fa-align-left"></i> Description</div>
        <div style="background:var(--p-surface2);border:1px solid var(--p-border);border-radius:9px;padding:14px 16px;font-size:.86rem;line-height:1.7;color:var(--p-text);" id="v-desc"></div>
      </div>

    </div>
    <div class="vn-modal-footer">
      <button class="vn-btn vn-btn-outline" onclick="closeModal('viewModal')">Close</button>
    </div>
  </div>
</div>

{{-- ══════════════════════════
     APPROVE CONFIRM MODAL
══════════════════════════ --}}
<div class="vn-overlay" id="approveModal">
  <div class="vn-modal" style="max-width:420px;">
    <div class="vn-approve-body">
      <div class="vn-approve-icon"><i class="fas fa-check-circle"></i></div>
      <h5>Approve Vendor?</h5>
      <p>
        <strong id="approve-biz-name" style="color:#a5b4fc;display:block;margin-bottom:6px;"></strong>
        This vendor will be approved and gain access to list their services.
      </p>
    </div>
    <div class="vn-approve-footer">
      <button class="vn-btn vn-btn-outline" onclick="closeModal('approveModal')">
        <i class="fas fa-times"></i> Cancel
      </button>
      <form id="approveForm" method="POST" style="display:inline;">
        @csrf
        <button type="submit" class="vn-btn vn-btn-success">
          <i class="fas fa-check"></i> Yes, Approve
        </button>
      </form>
    </div>
  </div>
</div>

<div id="vn-toast"></div>

<script>
function openModal(id)  { document.getElementById(id).classList.add('open');    document.body.style.overflow='hidden'; }
function closeModal(id) { document.getElementById(id).classList.remove('open'); document.body.style.overflow=''; }
document.querySelectorAll('.vn-overlay').forEach(function(el){
  el.addEventListener('click',function(e){ if(e.target===el) closeModal(el.id); });
});
document.addEventListener('keydown',function(e){
  if(e.key==='Escape'){ closeModal('viewModal'); closeModal('approveModal'); }
});

function openViewModal(d) {
  document.getElementById('v-avatar').textContent   = d.name.charAt(0).toUpperCase();
  document.getElementById('v-name').textContent     = d.name;
  document.getElementById('v-email').textContent    = d.email;
  document.getElementById('v-business').textContent = d.business;
  document.getElementById('v-joined').textContent   = d.joined;

  // status badge
  var stEl = document.getElementById('v-status');
  var stMap = {
    approved: 'vn-badge-approved',
    pending:  'vn-badge-pending',
    rejected: 'vn-badge-rejected'
  };
  stEl.innerHTML = '<span class="vn-badge '+(stMap[d.status]||'vn-badge-pending')+'"><i class="fas fa-circle" style="font-size:.4rem;"></i> '+d.status.charAt(0).toUpperCase()+d.status.slice(1)+'</span>';

  // optional fields
  var phoneWrap = document.getElementById('v-phone-wrap');
  if(d.phone){ document.getElementById('v-phone').textContent=d.phone; phoneWrap.style.display='block'; } else { phoneWrap.style.display='none'; }

  var addrWrap = document.getElementById('v-address-wrap');
  if(d.address){ document.getElementById('v-address').textContent=d.address; addrWrap.style.display='block'; } else { addrWrap.style.display='none'; }

  var descWrap = document.getElementById('v-desc-wrap');
  if(d.desc){ document.getElementById('v-desc').textContent=d.desc; descWrap.style.display='block'; } else { descWrap.style.display='none'; }

  openModal('viewModal');
}

function openApproveModal(action, bizName) {
  document.getElementById('approve-biz-name').textContent = bizName;
  document.getElementById('approveForm').action = action;
  openModal('approveModal');
}

function showToast(type,title,msg) {
  var c=document.getElementById('vn-toast'), t=document.createElement('div');
  var icon=type==='s'?'fas fa-check-circle':'fas fa-exclamation-circle';
  t.className='vn-toast vn-toast-'+type;
  t.innerHTML='<div class="vn-toast-icon"><i class="'+icon+'"></i></div><div><div class="vn-toast-title">'+title+'</div><div class="vn-toast-msg">'+msg+'</div></div><span class="vn-toast-bar"></span>';
  c.appendChild(t);
  setTimeout(function(){ t.classList.add('show'); },20);
  setTimeout(function(){ t.classList.remove('show'); setTimeout(function(){ t.remove(); },400); },3500);
}
(function(){
  var s=document.getElementById('flash-s'), e=document.getElementById('flash-e');
  if(s) showToast('s','Success',s.dataset.msg);
  if(e) showToast('d','Error',e.dataset.msg);
})();
</script>

@endsection