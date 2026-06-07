@extends('layouts.admin')
@section('title', 'Blog Categories')
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
  --p-orange:    #f97316;
  --p-text:      #e2e8f0;
  --p-muted:     #64748b;
  --p-radius:    14px;
  --p-radius-sm: 8px;
  --p-shadow:    0 8px 32px rgba(0,0,0,.45);
}
.bc-wrap { font-family:'Plus Jakarta Sans',sans-serif; color:var(--p-text); }

/* ── HEADER ── */
.bc-header {
  background:linear-gradient(135deg,#0c1a2e 0%,#1a160c 50%,#0c1a2e 100%);
  border-radius:var(--p-radius); padding:28px 32px;
  margin-bottom:24px; position:relative; overflow:hidden; box-shadow:var(--p-shadow);
}
.bc-header::before {
  content:''; position:absolute; inset:0;
  background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23f97316' fill-opacity='0.05'%3E%3Cpath d='M30 5L55 20v20L30 55 5 40V20Z'/%3E%3C/g%3E%3C/svg%3E");
}
.bc-header::after {
  content:''; position:absolute; right:-40px; top:-40px; width:200px; height:200px;
  border-radius:50%; background:radial-gradient(circle,rgba(249,115,22,.18) 0%,transparent 70%);
}
.bc-header .title {
  font-size:1.5rem; font-weight:700; position:relative; z-index:1;
  background:linear-gradient(90deg,#fff,#fdba74);
  -webkit-background-clip:text; -webkit-text-fill-color:transparent;
}
.bc-header .subtitle { color:rgba(255,255,255,.45); font-size:.85rem; margin-top:4px; position:relative; z-index:1; }
.stat-pill {
  display:inline-flex; align-items:center; gap:8px;
  background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.1);
  border-radius:40px; padding:6px 16px; font-size:.8rem; font-weight:600; color:#fff;
  position:relative; z-index:1;
}
.stat-pill .dot { width:8px; height:8px; border-radius:50%; }

/* ── LAYOUT ── */
.bc-layout { display:grid; grid-template-columns:340px 1fr; gap:20px; align-items:start; }
@media(max-width:900px){ .bc-layout{ grid-template-columns:1fr; } }

/* ── FORM CARD ── */
.bc-form-card {
  background:var(--p-surface); border:1px solid var(--p-border);
  border-radius:var(--p-radius); box-shadow:var(--p-shadow); overflow:hidden;
  position:sticky; top:20px;
}
.bc-form-card-head {
  padding:18px 22px 16px; border-bottom:1px solid var(--p-border);
  background:var(--p-surface2); display:flex; align-items:center; gap:10px;
}
.bc-form-card-head .icon {
  width:36px; height:36px; border-radius:9px;
  background:rgba(249,115,22,.15); color:#fdba74;
  display:flex; align-items:center; justify-content:center; font-size:.85rem;
}
.bc-form-card-head h5 { font-size:.95rem; font-weight:700; margin:0; }
.bc-form-card-head p  { font-size:.75rem; color:var(--p-muted); margin:2px 0 0; }

.bc-form-body { padding:22px; }
.bc-field { margin-bottom:16px; }
.bc-field label {
  display:block; font-size:.75rem; font-weight:700; letter-spacing:.07em;
  text-transform:uppercase; color:var(--p-muted); margin-bottom:7px;
}
.bc-field input, .bc-field select {
  width:100%; background:var(--p-surface2); border:1px solid var(--p-border);
  border-radius:var(--p-radius-sm); padding:10px 14px; color:var(--p-text);
  font-family:'Plus Jakarta Sans',sans-serif; font-size:.875rem; outline:none;
  transition:border-color .2s, box-shadow .2s; box-sizing:border-box;
}
.bc-field input:focus, .bc-field select:focus {
  border-color:var(--p-orange); box-shadow:0 0 0 3px rgba(249,115,22,.12);
}
.bc-field .err { color:#fca5a5; font-size:.76rem; margin-top:5px; display:block; }
.bc-field select option { background:#1a1d27; }

.bc-submit {
  width:100%; padding:11px; border:none; border-radius:var(--p-radius-sm);
  background:linear-gradient(135deg,var(--p-orange),#ea580c);
  color:#fff; font-family:'Plus Jakarta Sans',sans-serif; font-weight:700;
  font-size:.88rem; cursor:pointer; display:flex; align-items:center;
  justify-content:center; gap:8px; transition:all .2s; margin-top:4px;
}
.bc-submit:hover { transform:translateY(-2px); box-shadow:0 6px 18px rgba(249,115,22,.35); }

/* ── TABLE CARD ── */
.bc-table-card {
  background:var(--p-surface); border:1px solid var(--p-border);
  border-radius:var(--p-radius); box-shadow:var(--p-shadow); overflow:hidden;
}
.bc-table-head {
  padding:18px 22px 16px; border-bottom:1px solid var(--p-border);
  background:var(--p-surface2); display:flex; align-items:center; justify-content:space-between;
}
.bc-table-head h5 { font-size:.95rem; font-weight:700; margin:0; display:flex; align-items:center; gap:8px; }
.bc-count-badge {
  background:rgba(249,115,22,.15); color:#fdba74; border:1px solid rgba(249,115,22,.25);
  border-radius:20px; padding:2px 10px; font-size:.72rem; font-weight:700;
}

.bc-table { width:100%; border-collapse:collapse; }
.bc-table thead tr { background:rgba(255,255,255,.02); border-bottom:1px solid var(--p-border); }
.bc-table th {
  font-size:.7rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase;
  color:var(--p-muted); padding:12px 18px; text-align:left;
}
.bc-table td {
  padding:14px 18px; border-bottom:1px solid var(--p-border);
  font-size:.875rem; color:var(--p-text); vertical-align:middle;
}
.bc-table tbody tr:last-child td { border-bottom:none; }
.bc-table tbody tr { transition:background .15s; }
.bc-table tbody tr:hover { background:rgba(255,255,255,.02); }

.bc-serial { font-family:'JetBrains Mono',monospace; font-size:.78rem; color:var(--p-muted); }
.bc-name   { font-weight:600; font-size:.88rem; }
.bc-slug   { font-family:'JetBrains Mono',monospace; font-size:.76rem; color:var(--p-muted); }

.bc-badge { display:inline-flex; align-items:center; gap:5px; padding:3px 10px; border-radius:20px; font-size:.72rem; font-weight:700; }
.bc-badge-active   { background:rgba(34,197,94,.12); color:#86efac; border:1px solid rgba(34,197,94,.25); }
.bc-badge-inactive { background:rgba(239,68,68,.12); color:#fca5a5; border:1px solid rgba(239,68,68,.25); }

.bc-actions { display:flex; gap:7px; }
.bc-edit-btn {
  display:inline-flex; align-items:center; gap:5px;
  background:rgba(245,158,11,.1); color:#fcd34d;
  border:1px solid rgba(245,158,11,.2); border-radius:6px;
  padding:5px 12px; font-size:.78rem; font-weight:600;
  cursor:pointer; transition:all .2s; font-family:'Plus Jakarta Sans',sans-serif; text-decoration:none;
}
.bc-edit-btn:hover { background:rgba(245,158,11,.2); color:#fcd34d; transform:translateY(-1px); }
.bc-del-btn {
  display:inline-flex; align-items:center; gap:5px;
  background:rgba(239,68,68,.1); color:#fca5a5;
  border:1px solid rgba(239,68,68,.2); border-radius:6px;
  padding:5px 12px; font-size:.78rem; font-weight:600;
  cursor:pointer; transition:all .2s; font-family:'Plus Jakarta Sans',sans-serif;
}
.bc-del-btn:hover { background:rgba(239,68,68,.2); transform:translateY(-1px); }

.bc-empty { text-align:center; padding:60px 20px; color:var(--p-muted); }
.bc-empty i { font-size:2.2rem; margin-bottom:12px; opacity:.3; display:block; }

/* pagination */
.pagination { padding:14px 18px 0; }
.pagination .page-item .page-link {
  background:var(--p-surface2); border:1px solid var(--p-border);
  color:var(--p-muted); font-size:.82rem; border-radius:6px !important; margin:0 2px; transition:all .2s;
}
.pagination .page-item .page-link:hover { background:var(--p-border); color:var(--p-text); }
.pagination .page-item.active .page-link { background:var(--p-orange); border-color:var(--p-orange); color:#fff; font-weight:700; }

/* ── EDIT MODAL ── */
.bc-modal-overlay {
  position:fixed; inset:0; z-index:9999;
  background:rgba(0,0,0,.75); backdrop-filter:blur(6px);
  display:flex; align-items:center; justify-content:center;
  opacity:0; pointer-events:none; transition:opacity .25s;
}
.bc-modal-overlay.open { opacity:1; pointer-events:auto; }
.bc-modal {
  background:var(--p-surface); border:1px solid var(--p-border);
  border-radius:18px; width:min(480px,94vw);
  box-shadow:0 24px 64px rgba(0,0,0,.7);
  transform:translateY(20px) scale(.97);
  transition:transform .28s cubic-bezier(.34,1.56,.64,1); overflow:hidden;
}
.bc-modal-overlay.open .bc-modal { transform:translateY(0) scale(1); }
.bc-modal-header {
  padding:20px 24px 16px; border-bottom:1px solid var(--p-border);
  display:flex; align-items:center; justify-content:space-between;
  background:var(--p-surface2);
}
.bc-modal-title { font-size:1rem; font-weight:700; display:flex; align-items:center; gap:9px; }
.bc-modal-close {
  background:var(--p-surface); border:1px solid var(--p-border); color:var(--p-muted);
  width:30px; height:30px; border-radius:7px; cursor:pointer;
  display:flex; align-items:center; justify-content:center; transition:all .2s; font-size:.85rem;
}
.bc-modal-close:hover { color:var(--p-text); }
.bc-modal-body   { padding:22px 24px; }
.bc-modal-footer { padding:14px 24px 20px; border-top:1px solid var(--p-border); display:flex; gap:10px; justify-content:flex-end; }
.bc-btn { display:inline-flex; align-items:center; gap:7px; border:none; cursor:pointer; font-family:'Plus Jakarta Sans',sans-serif; font-weight:600; border-radius:var(--p-radius-sm); transition:all .2s; font-size:.85rem; padding:9px 20px; }
.bc-btn-outline { background:transparent; color:var(--p-text); border:1px solid var(--p-border); }
.bc-btn-outline:hover { background:var(--p-surface2); }
.bc-btn-warn { background:var(--p-warning); color:#1a1d27; border:none; }
.bc-btn-warn:hover { background:#d97706; transform:translateY(-1px); }

/* ── DELETE MODAL ── */
.bc-del-overlay {
  position:fixed; inset:0; z-index:10000;
  background:rgba(0,0,0,.8); backdrop-filter:blur(6px);
  display:flex; align-items:center; justify-content:center;
  opacity:0; pointer-events:none; transition:opacity .25s;
}
.bc-del-overlay.open { opacity:1; pointer-events:auto; }
.bc-del-box {
  background:var(--p-surface); border:1px solid var(--p-border);
  border-radius:18px; width:min(390px,94vw);
  box-shadow:0 24px 64px rgba(0,0,0,.7);
  transform:translateY(20px) scale(.97);
  transition:transform .28s cubic-bezier(.34,1.56,.64,1); overflow:hidden;
}
.bc-del-overlay.open .bc-del-box { transform:translateY(0) scale(1); }
.bc-del-icon {
  width:60px; height:60px; border-radius:50%;
  background:rgba(239,68,68,.12); border:2px solid rgba(239,68,68,.25);
  display:flex; align-items:center; justify-content:center;
  font-size:1.4rem; color:#fca5a5; margin:0 auto 14px;
}
.bc-del-body { text-align:center; padding:32px 28px 16px; }
.bc-del-body h5 { font-weight:700; margin-bottom:8px; color:var(--p-text); }
.bc-del-body p  { color:var(--p-muted); font-size:.875rem; line-height:1.6; margin:0; }
.bc-del-footer  { padding:14px 28px 22px; display:flex; gap:10px; justify-content:center; }
.bc-btn-danger  { background:var(--p-danger); color:#fff; border:none; }
.bc-btn-danger:hover { background:#dc2626; transform:translateY(-1px); }

/* ── TOAST ── */
#bc-toast { position:fixed; bottom:28px; right:28px; z-index:99999; display:flex; flex-direction:column; gap:10px; }
.bc-toast {
  display:flex; align-items:center; gap:12px;
  background:var(--p-surface); border:1px solid var(--p-border);
  border-radius:12px; padding:14px 18px; min-width:260px;
  box-shadow:0 8px 30px rgba(0,0,0,.5);
  transform:translateX(120%); transition:transform .35s cubic-bezier(.34,1.56,.64,1);
  font-family:'Plus Jakarta Sans',sans-serif; position:relative; overflow:hidden;
}
.bc-toast.show { transform:translateX(0); }
.bc-toast-icon { width:34px; height:34px; border-radius:9px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.bc-toast-s .bc-toast-icon { background:rgba(34,197,94,.15); color:var(--p-success); }
.bc-toast-d .bc-toast-icon { background:rgba(239,68,68,.15); color:var(--p-danger); }
.bc-toast-title { font-size:.875rem; font-weight:700; color:var(--p-text); }
.bc-toast-msg   { font-size:.77rem; color:var(--p-muted); margin-top:1px; }
.bc-toast-bar   { position:absolute; bottom:0; left:0; height:3px; border-radius:0 0 12px 12px; animation:bcBar 3.2s linear forwards; }
.bc-toast-s .bc-toast-bar { background:var(--p-success); }
.bc-toast-d .bc-toast-bar { background:var(--p-danger); }
@keyframes bcBar { from{width:100%} to{width:0%} }
</style>

@if(session('success'))<div id="flash-s" data-msg="{{ session('success') }}"></div>@endif
@if(session('error'))<div id="flash-e"   data-msg="{{ session('error') }}"></div>@endif

<div class="bc-wrap">

  {{-- ── HEADER ── --}}
  <div class="bc-header">
    <div>
      <div class="title"><i class="fas fa-folder-open me-2"></i>Blog Categories</div>
      <div class="subtitle">Create and manage your blog category structure</div>
      <div style="display:flex;gap:8px;margin-top:14px;flex-wrap:wrap;">
        <span class="stat-pill"><span class="dot" style="background:var(--p-success)"></span>{{ $categories->where('status',1)->count() }} Active</span>
        <span class="stat-pill"><span class="dot" style="background:var(--p-danger)"></span>{{ $categories->where('status',0)->count() }} Inactive</span>
        <span class="stat-pill"><span class="dot" style="background:var(--p-orange)"></span>{{ $categories->total() }} Total</span>
      </div>
    </div>
  </div>

  {{-- ── MAIN LAYOUT ── --}}
  <div class="bc-layout">

    {{-- ── CREATE FORM ── --}}
    <div class="bc-form-card">
      <div class="bc-form-card-head">
        <div class="icon"><i class="fas fa-plus"></i></div>
        <div>
          <h5>New Category</h5>
          <p>Add a new blog category</p>
        </div>
      </div>
      <div class="bc-form-body">
        <form method="POST" action="{{ route('admin.blog.categories.store') }}">
          @csrf
          <div class="bc-field">
            <label>Category Name <span style="color:var(--p-danger)">*</span></label>
            <input type="text" name="name" placeholder="e.g. Travel Tips" value="{{ old('name') }}">
            @error('name')<span class="err">{{ $message }}</span>@enderror
          </div>
          <div class="bc-field">
            <label>Status</label>
            <select name="status">
              <option value="1" {{ old('status','1')=='1'?'selected':'' }}>Active</option>
              <option value="0" {{ old('status')=='0'?'selected':'' }}>Inactive</option>
            </select>
          </div>
          <button type="submit" class="bc-submit">
            <i class="fas fa-save"></i> Save Category
          </button>
        </form>
      </div>
    </div>

    {{-- ── TABLE ── --}}
    <div class="bc-table-card">
      <div class="bc-table-head">
        <h5>
          <i class="fas fa-list" style="color:var(--p-orange);"></i>
          All Categories
          <span class="bc-count-badge">{{ $categories->total() }}</span>
        </h5>
      </div>

      <div class="table-responsive">
        <table class="bc-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Slug</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($categories as $key => $cat)
            <tr>
              <td><span class="bc-serial">{{ str_pad($categories->firstItem() + $key, 2, '0', STR_PAD_LEFT) }}</span></td>
              <td><span class="bc-name">{{ $cat->name }}</span></td>
              <td><span class="bc-slug">{{ $cat->slug }}</span></td>
              <td>
                @if($cat->status)
                  <span class="bc-badge bc-badge-active"><i class="fas fa-circle" style="font-size:.4rem;"></i>Active</span>
                @else
                  <span class="bc-badge bc-badge-inactive"><i class="fas fa-circle" style="font-size:.4rem;"></i>Inactive</span>
                @endif
              </td>
              <td>
                <div class="bc-actions">
                  <button class="bc-edit-btn"
                    onclick="openEditModal('{{ $cat->id }}', {{ json_encode($cat->name) }}, '{{ $cat->status }}')">
                    <i class="fas fa-pen"></i> Edit
                  </button>
                  <button class="bc-del-btn"
                    onclick="openDeleteModal('{{ $cat->id }}', {{ json_encode($cat->name) }})">
                    <i class="fas fa-trash-alt"></i> Delete
                  </button>
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="5">
                <div class="bc-empty">
                  <i class="fas fa-folder-open"></i>
                  <p>No categories yet. Create your first one!</p>
                </div>
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if($categories->hasPages())
        <div class="pagination">{{ $categories->links() }}</div>
        <div style="height:14px;"></div>
      @endif
    </div>

  </div>
</div>

{{-- ══════════════════════════════
     EDIT MODAL
══════════════════════════════ --}}
<div class="bc-modal-overlay" id="editModal">
  <div class="bc-modal">
    <div class="bc-modal-header">
      <div class="bc-modal-title">
        <i class="fas fa-pen" style="color:var(--p-warning);"></i> Edit Category
      </div>
      <button class="bc-modal-close" onclick="closeModal('editModal')"><i class="fas fa-times"></i></button>
    </div>
    <form method="POST" id="editForm">
      @csrf
      <div class="bc-modal-body">
        <div class="bc-field">
          <label>Category Name <span style="color:var(--p-danger)">*</span></label>
          <input type="text" name="name" id="edit_name" placeholder="Category name">
        </div>
        <div class="bc-field">
          <label>Status</label>
          <select name="status" id="edit_status">
            <option value="1">Active</option>
            <option value="0">Inactive</option>
          </select>
        </div>
      </div>
      <div class="bc-modal-footer">
        <button type="button" class="bc-btn bc-btn-outline" onclick="closeModal('editModal')">Cancel</button>
        <button type="submit" class="bc-btn bc-btn-warn">
          <i class="fas fa-save"></i> Update
        </button>
      </div>
    </form>
  </div>
</div>

{{-- ══════════════════════════════
     DELETE CONFIRM MODAL
══════════════════════════════ --}}
<div class="bc-del-overlay" id="deleteModal">
  <div class="bc-del-box">
    <div class="bc-del-body">
      <div class="bc-del-icon"><i class="fas fa-folder"></i></div>
      <h5>Delete Category?</h5>
      <p>
        <strong id="del-cat-name" style="color:var(--p-orange);"></strong> will be permanently deleted.
        <br>This action <strong style="color:var(--p-danger)">cannot be undone</strong>.
      </p>
    </div>
    <div class="bc-del-footer">
      <button class="bc-btn bc-btn-outline" onclick="closeModal('deleteModal')">
        <i class="fas fa-times"></i> Cancel
      </button>
      <form id="deleteForm" method="POST" style="display:inline;">
        @csrf
        <button type="submit" class="bc-btn bc-btn-danger">
          <i class="fas fa-trash-alt"></i> Yes, Delete
        </button>
      </form>
    </div>
  </div>
</div>

<div id="bc-toast"></div>

<script>
  /* ── Modal helpers ── */
  function openModal(id)  { document.getElementById(id).classList.add('open');    document.body.style.overflow='hidden'; }
  function closeModal(id) { document.getElementById(id).classList.remove('open'); document.body.style.overflow=''; }

  document.querySelectorAll('.bc-modal-overlay, .bc-del-overlay').forEach(function(el) {
    el.addEventListener('click', function(e){ if(e.target===el) closeModal(el.id); });
  });
  document.addEventListener('keydown', function(e){ if(e.key==='Escape'){ closeModal('editModal'); closeModal('deleteModal'); } });

  /* ── Edit ── */
  function openEditModal(id, name, status) {
    document.getElementById('edit_name').value   = name;
    document.getElementById('edit_status').value = status;
    document.getElementById('editForm').action   = '/admin/blog/categories/update/' + id;
    openModal('editModal');
  }

  /* ── Delete ── */
  function openDeleteModal(id, name) {
    document.getElementById('del-cat-name').textContent = name;
    document.getElementById('deleteForm').action = '/admin/blog/categories/delete/' + id;
    openModal('deleteModal');
  }

  /* ── Toast ── */
  function showToast(type, title, msg) {
    var c = document.getElementById('bc-toast');
    var t = document.createElement('div');
    var icon = type==='s' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
    t.className = 'bc-toast bc-toast-' + type;
    t.innerHTML = '<div class="bc-toast-icon"><i class="'+icon+'"></i></div>' +
      '<div><div class="bc-toast-title">'+title+'</div><div class="bc-toast-msg">'+msg+'</div></div>' +
      '<span class="bc-toast-bar"></span>';
    c.appendChild(t);
    setTimeout(function(){ t.classList.add('show'); }, 20);
    setTimeout(function(){ t.classList.remove('show'); setTimeout(function(){ t.remove(); },400); }, 3500);
  }
  (function(){
    var s = document.getElementById('flash-s');
    var e = document.getElementById('flash-e');
    if(s) showToast('s','Success', s.dataset.msg);
    if(e) showToast('d','Error',   e.dataset.msg);
  })();

  /* Auto-open edit modal on validation error */
  @if($errors->any())
    document.addEventListener('DOMContentLoaded', function(){ openModal('editModal'); });
  @endif
</script>

@endsection