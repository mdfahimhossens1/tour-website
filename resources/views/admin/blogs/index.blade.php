@extends('layouts.admin')
@section('title', 'Blog Management')
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
  --p-teal:      #14b8a6;
  --p-text:      #e2e8f0;
  --p-muted:     #64748b;
  --p-radius:    14px;
  --p-radius-sm: 8px;
  --p-shadow:    0 8px 32px rgba(0,0,0,.45);
}
.bl-wrap { font-family:'Plus Jakarta Sans',sans-serif; color:var(--p-text); }

.bl-header {
  background:linear-gradient(135deg,#0c1a2e 0%,#0c2218 50%,#0c1a2e 100%);
  border-radius:var(--p-radius); padding:28px 32px; margin-bottom:24px;
  position:relative; overflow:hidden; box-shadow:var(--p-shadow);
}
.bl-header::before {
  content:''; position:absolute; inset:0;
  background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%2314b8a6' fill-opacity='0.05'%3E%3Cpath d='M30 5L55 20v20L30 55 5 40V20Z'/%3E%3C/g%3E%3C/svg%3E");
}
.bl-header::after {
  content:''; position:absolute; right:-40px; top:-40px; width:200px; height:200px;
  border-radius:50%; background:radial-gradient(circle,rgba(20,184,166,.18) 0%,transparent 70%);
}
.bl-header .title {
  font-size:1.5rem; font-weight:700; position:relative; z-index:1;
  background:linear-gradient(90deg,#fff,#5eead4);
  -webkit-background-clip:text; -webkit-text-fill-color:transparent;
}
.bl-header .subtitle { color:rgba(255,255,255,.45); font-size:.85rem; margin-top:4px; position:relative; z-index:1; }
.stat-pill {
  display:inline-flex; align-items:center; gap:8px;
  background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.1);
  border-radius:40px; padding:6px 16px; font-size:.8rem; font-weight:600; color:#fff; position:relative; z-index:1;
}
.stat-pill .dot { width:8px; height:8px; border-radius:50%; }
.bl-add-btn {
  background:linear-gradient(135deg,var(--p-teal),#0d9488); color:#fff; border:none;
  border-radius:10px; padding:10px 20px; font-family:'Plus Jakarta Sans',sans-serif;
  font-weight:700; font-size:.85rem; cursor:pointer; display:inline-flex; align-items:center; gap:8px;
  transition:all .2s; text-decoration:none; position:relative; z-index:1;
}
.bl-add-btn:hover { transform:translateY(-2px); box-shadow:0 6px 18px rgba(20,184,166,.35); color:#fff; }

.bl-card {
  background:var(--p-surface); border:1px solid var(--p-border);
  border-radius:var(--p-radius); box-shadow:var(--p-shadow); overflow:hidden;
}

.bl-table { width:100%; border-collapse:collapse; }
.bl-table thead tr { background:var(--p-surface2); border-bottom:1px solid var(--p-border); }
.bl-table th {
  font-size:.7rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase;
  color:var(--p-muted); padding:13px 18px; text-align:left;
}
.bl-table td {
  padding:14px 18px; border-bottom:1px solid var(--p-border);
  font-size:.875rem; color:var(--p-text); vertical-align:middle;
}
.bl-table tbody tr:last-child td { border-bottom:none; }
.bl-table tbody tr { transition:background .15s; }
.bl-table tbody tr:hover { background:rgba(255,255,255,.02); }

.bl-serial { font-family:'JetBrains Mono',monospace; font-size:.78rem; color:var(--p-muted); }
.bl-thumb  { width:64px; height:50px; object-fit:cover; border-radius:8px; border:1px solid var(--p-border); }
.bl-no-img { width:64px; height:50px; background:var(--p-surface2); border:1px solid var(--p-border); border-radius:8px; display:flex; align-items:center; justify-content:center; color:var(--p-muted); font-size:.7rem; }
.bl-title-text { font-weight:600; font-size:.875rem; line-height:1.4; max-width:200px; }
.bl-slug-text  { font-family:'JetBrains Mono',monospace; font-size:.74rem; color:var(--p-muted); max-width:160px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block; }
.bl-cat-badge  { display:inline-flex; align-items:center; gap:5px; background:rgba(20,184,166,.12); color:#5eead4; border:1px solid rgba(20,184,166,.25); border-radius:20px; padding:3px 10px; font-size:.72rem; font-weight:600; }
.bl-badge { display:inline-flex; align-items:center; gap:5px; padding:3px 10px; border-radius:20px; font-size:.72rem; font-weight:700; }
.bl-badge-active   { background:rgba(34,197,94,.12);  color:#86efac; border:1px solid rgba(34,197,94,.25); }
.bl-badge-inactive { background:rgba(239,68,68,.12);  color:#fca5a5; border:1px solid rgba(239,68,68,.25); }
.bl-date { font-size:.78rem; color:var(--p-muted); display:flex; align-items:center; gap:5px; }
.bl-date i { color:var(--p-teal); font-size:.7rem; }

.bl-actions { display:flex; gap:6px; }
.bl-btn-action {
  display:inline-flex; align-items:center; gap:5px; border-radius:6px;
  padding:5px 11px; font-size:.77rem; font-weight:600; cursor:pointer;
  border:1px solid var(--p-border); font-family:'Plus Jakarta Sans',sans-serif;
  transition:all .2s; text-decoration:none;
}
.bl-btn-view { background:rgba(14,165,233,.1); color:var(--p-accent2); border-color:rgba(14,165,233,.2); }
.bl-btn-view:hover { background:rgba(14,165,233,.2); color:var(--p-accent2); transform:translateY(-1px); }
.bl-btn-edit { background:rgba(245,158,11,.1); color:#fcd34d; border-color:rgba(245,158,11,.2); }
.bl-btn-edit:hover { background:rgba(245,158,11,.2); color:#fcd34d; transform:translateY(-1px); }
.bl-btn-del  { background:rgba(239,68,68,.1); color:#fca5a5; border-color:rgba(239,68,68,.2); }
.bl-btn-del:hover { background:rgba(239,68,68,.2); transform:translateY(-1px); }

.bl-empty { text-align:center; padding:70px 20px; color:var(--p-muted); }
.bl-empty i { font-size:2.5rem; margin-bottom:14px; opacity:.3; display:block; }

.pagination { padding:14px 18px; }
.pagination .page-item .page-link {
  background:var(--p-surface2); border:1px solid var(--p-border);
  color:var(--p-muted); font-size:.82rem; border-radius:6px !important; margin:0 2px; transition:all .2s;
}
.pagination .page-item .page-link:hover { background:var(--p-border); color:var(--p-text); }
.pagination .page-item.active .page-link { background:var(--p-teal); border-color:var(--p-teal); color:#fff; font-weight:700; }

/* ── MODALS ── */
.bl-overlay {
  position:fixed; inset:0; z-index:9999;
  background:rgba(0,0,0,.75); backdrop-filter:blur(6px);
  display:flex; align-items:center; justify-content:center;
  opacity:0; pointer-events:none; transition:opacity .25s;
}
.bl-overlay.open { opacity:1; pointer-events:auto; }
.bl-modal {
  background:var(--p-surface); border:1px solid var(--p-border);
  border-radius:18px; width:min(720px,96vw); max-height:90vh;
  overflow-y:auto; box-shadow:0 24px 64px rgba(0,0,0,.7);
  transform:translateY(24px) scale(.97);
  transition:transform .3s cubic-bezier(.34,1.56,.64,1);
}
.bl-overlay.open .bl-modal { transform:translateY(0) scale(1); }
.bl-modal::-webkit-scrollbar { width:5px; }
.bl-modal::-webkit-scrollbar-thumb { background:var(--p-border); border-radius:10px; }

.bl-modal-header {
  padding:20px 26px 16px; border-bottom:1px solid var(--p-border);
  display:flex; align-items:center; justify-content:space-between;
  background:var(--p-surface2); position:sticky; top:0; z-index:2;
}
.bl-modal-title { font-size:1.05rem; font-weight:700; display:flex; align-items:center; gap:9px; }
.bl-modal-close {
  background:var(--p-surface); border:1px solid var(--p-border); color:var(--p-muted);
  width:30px; height:30px; border-radius:7px; cursor:pointer;
  display:flex; align-items:center; justify-content:center; transition:all .2s; font-size:.85rem;
}
.bl-modal-close:hover { color:var(--p-text); }
.bl-modal-body   { padding:24px 26px; }
.bl-modal-footer { padding:16px 26px 22px; border-top:1px solid var(--p-border); display:flex; gap:10px; justify-content:flex-end; background:rgba(0,0,0,.1); }

/* View modal internals */
.bl-view-cover { width:100%; max-height:260px; object-fit:cover; border-radius:12px; margin-bottom:20px; border:1px solid var(--p-border); }
.bl-view-grid  { display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:18px; }
.bl-view-item label { font-size:.7rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:var(--p-muted); display:block; margin-bottom:4px; }
.bl-view-item p { font-size:.875rem; color:var(--p-text); margin:0; font-weight:500; }
.bl-view-item p.mono { font-family:'JetBrains Mono',monospace; font-size:.8rem; color:var(--p-accent2); }
.bl-sec { font-size:.7rem; font-weight:700; letter-spacing:.09em; text-transform:uppercase; color:var(--p-teal); margin-bottom:10px; display:flex; align-items:center; gap:8px; }
.bl-sec::after { content:''; flex:1; height:1px; background:var(--p-border); }
.bl-desc-box {
  background:var(--p-surface2); border:1px solid var(--p-border); border-radius:10px;
  padding:16px 18px; font-size:.875rem; line-height:1.75; color:var(--p-text);
  max-height:220px; overflow-y:auto;
}
.bl-desc-box::-webkit-scrollbar { width:4px; }
.bl-desc-box::-webkit-scrollbar-thumb { background:var(--p-border); border-radius:10px; }
.bl-meta-box {
  background:rgba(14,165,233,.05); border:1px solid rgba(14,165,233,.15);
  border-radius:10px; padding:16px 18px; margin-top:16px; display:grid; grid-template-columns:1fr 1fr; gap:14px;
}
.bl-meta-box .mi label { font-size:.69rem; font-weight:700; letter-spacing:.07em; text-transform:uppercase; color:var(--p-muted); display:block; margin-bottom:4px; }
.bl-meta-box .mi p { font-size:.82rem; color:var(--p-text); margin:0; line-height:1.5; }

/* Delete modal */
.bl-del-modal { max-width:400px; }
.bl-del-body  { text-align:center; padding:34px 28px 16px; }
.bl-del-icon  { width:64px; height:64px; border-radius:50%; background:rgba(239,68,68,.12); border:2px solid rgba(239,68,68,.25); display:flex; align-items:center; justify-content:center; font-size:1.5rem; color:#fca5a5; margin:0 auto 16px; }
.bl-del-body h5 { font-weight:700; margin-bottom:8px; color:var(--p-text); }
.bl-del-body p  { color:var(--p-muted); font-size:.875rem; line-height:1.6; margin:0; }
.bl-del-footer  { padding:14px 28px 24px; display:flex; gap:10px; justify-content:center; }

.bl-btn { display:inline-flex; align-items:center; gap:7px; border:none; cursor:pointer; font-family:'Plus Jakarta Sans',sans-serif; font-weight:600; border-radius:var(--p-radius-sm); transition:all .2s; font-size:.85rem; padding:9px 20px; }
.bl-btn-outline { background:transparent; color:var(--p-text); border:1px solid var(--p-border); }
.bl-btn-outline:hover { background:var(--p-surface2); }
.bl-btn-danger  { background:var(--p-danger); color:#fff; border:none; }
.bl-btn-danger:hover { background:#dc2626; transform:translateY(-1px); }
.bl-btn-warn    { background:rgba(245,158,11,.15); color:#fcd34d; border:1px solid rgba(245,158,11,.25); }
.bl-btn-warn:hover { background:rgba(245,158,11,.25); }

/* Toast */
#bl-toast { position:fixed; bottom:28px; right:28px; z-index:99999; display:flex; flex-direction:column; gap:10px; }
.bl-toast {
  display:flex; align-items:center; gap:12px; background:var(--p-surface); border:1px solid var(--p-border);
  border-radius:12px; padding:14px 18px; min-width:260px; box-shadow:0 8px 30px rgba(0,0,0,.5);
  transform:translateX(120%); transition:transform .35s cubic-bezier(.34,1.56,.64,1);
  font-family:'Plus Jakarta Sans',sans-serif; position:relative; overflow:hidden;
}
.bl-toast.show { transform:translateX(0); }
.bl-toast-icon { width:34px; height:34px; border-radius:9px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.bl-toast-s .bl-toast-icon { background:rgba(34,197,94,.15); color:var(--p-success); }
.bl-toast-d .bl-toast-icon { background:rgba(239,68,68,.15); color:var(--p-danger); }
.bl-toast-title { font-size:.875rem; font-weight:700; color:var(--p-text); }
.bl-toast-msg   { font-size:.77rem; color:var(--p-muted); margin-top:1px; }
.bl-toast-bar   { position:absolute; bottom:0; left:0; height:3px; border-radius:0 0 12px 12px; animation:blBar 3.2s linear forwards; }
.bl-toast-s .bl-toast-bar { background:var(--p-success); }
.bl-toast-d .bl-toast-bar { background:var(--p-danger); }
@keyframes blBar { from{width:100%} to{width:0%} }
</style>

@if(session('success'))<div id="flash-s" data-msg="{{ session('success') }}"></div>@endif
@if(session('error'))<div id="flash-e"   data-msg="{{ session('error') }}"></div>@endif

<div class="bl-wrap">

  {{-- HEADER --}}
  <div class="bl-header">
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;">
      <div>
        <div class="title"><i class="fas fa-newspaper me-2"></i>Blog Management</div>
        <div class="subtitle">Create, manage and publish your blog posts</div>
        <div style="display:flex;gap:8px;margin-top:14px;flex-wrap:wrap;">
          <span class="stat-pill"><span class="dot" style="background:var(--p-success)"></span>{{ $blogs->where('status',1)->count() }} Published</span>
          <span class="stat-pill"><span class="dot" style="background:var(--p-danger)"></span>{{ $blogs->where('status',0)->count() }} Draft</span>
          <span class="stat-pill"><span class="dot" style="background:var(--p-teal)"></span>{{ $blogs->total() }} Total</span>
        </div>
      </div>
      <a href="{{ route('admin.blogs.create') }}" class="bl-add-btn">
        <i class="fas fa-plus"></i> New Blog Post
      </a>
    </div>
  </div>

  {{-- TABLE --}}
  <div class="bl-card">
    <div class="table-responsive">
      <table class="bl-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Image</th>
            <th>Title</th>
            <th>Category</th>
            <th>Status</th>
            <th>Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($blogs as $key => $blog)
          <tr>
            <td><span class="bl-serial">{{ str_pad($blogs->firstItem()+$key,2,'0',STR_PAD_LEFT) }}</span></td>
            <td>
              @if($blog->image)
                <img src="{{ asset('uploads/blogs/'.$blog->image) }}" class="bl-thumb">
              @else
                <div class="bl-no-img"><i class="fas fa-image"></i></div>
              @endif
            </td>
            <td>
              <div class="bl-title-text">{{ Str::limit($blog->title,45) }}</div>
              <span class="bl-slug-text">{{ $blog->slug }}</span>
            </td>
            <td>
              <span class="bl-cat-badge">
                <i class="fas fa-folder" style="font-size:.55rem;"></i>
                {{ $blog->category->name ?? 'Uncategorized' }}
              </span>
            </td>
            <td>
              @if($blog->status)
                <span class="bl-badge bl-badge-active"><i class="fas fa-circle" style="font-size:.4rem;"></i>Published</span>
              @else
                <span class="bl-badge bl-badge-inactive"><i class="fas fa-circle" style="font-size:.4rem;"></i>Draft</span>
              @endif
            </td>
            <td><span class="bl-date"><i class="fas fa-calendar-alt"></i>{{ $blog->created_at->format('d M Y') }}</span></td>
            <td>
              <div class="bl-actions">
                <button class="bl-btn-action bl-btn-view"
                  onclick="openViewModal({
                    title:    {{ json_encode($blog->title) }},
                    slug:     {{ json_encode($blog->slug) }},
                    category: {{ json_encode($blog->category->name ?? 'Uncategorized') }},
                    status:   '{{ $blog->status }}',
                    date:     '{{ $blog->created_at->format('d M Y, h:i A') }}',
                    desc:     {{ json_encode($blog->description) }},
                    meta_title: {{ json_encode($blog->meta_title ?? '') }},
                    meta_desc:  {{ json_encode($blog->meta_description ?? '') }},
                    image:    '{{ $blog->image ? asset('uploads/blogs/'.$blog->image) : '' }}',
                    edit_url: '{{ route('admin.blogs.edit',$blog->slug) }}'
                  })">
                  <i class="fas fa-eye"></i> View
                </button>
                <a href="{{ route('admin.blogs.edit',$blog->slug) }}" class="bl-btn-action bl-btn-edit">
                  <i class="fas fa-pen"></i> Edit
                </a>
                <button class="bl-btn-action bl-btn-del"
                  onclick="openDeleteModal('{{ route('admin.blogs.delete',$blog->slug) }}', {{ json_encode($blog->title) }})">
                  <i class="fas fa-trash-alt"></i>
                </button>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7">
              <div class="bl-empty">
                <i class="fas fa-newspaper"></i>
                <p>No blog posts yet. <a href="{{ route('admin.blogs.create') }}" style="color:var(--p-teal);">Create your first post</a>.</p>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($blogs->hasPages())
      <div class="pagination">{{ $blogs->links() }}</div>
    @endif
  </div>
</div>

{{-- VIEW MODAL --}}
<div class="bl-overlay" id="viewModal">
  <div class="bl-modal">
    <div class="bl-modal-header">
      <div class="bl-modal-title"><i class="fas fa-eye" style="color:var(--p-teal);"></i> Blog Details</div>
      <button class="bl-modal-close" onclick="closeModal('viewModal')"><i class="fas fa-times"></i></button>
    </div>
    <div class="bl-modal-body">
      <img id="view-cover" src="" alt="" class="bl-view-cover" style="display:none;">
      <div class="bl-view-grid">
        <div class="bl-view-item" style="grid-column:1/-1;">
          <label>Title</label>
          <p id="view-title" style="font-size:1rem;font-weight:700;"></p>
        </div>
        <div class="bl-view-item">
          <label>Category</label>
          <p id="view-category"></p>
        </div>
        <div class="bl-view-item">
          <label>Status</label>
          <p id="view-status"></p>
        </div>
        <div class="bl-view-item">
          <label>Slug</label>
          <p id="view-slug" class="mono"></p>
        </div>
        <div class="bl-view-item">
          <label>Published</label>
          <p id="view-date"></p>
        </div>
      </div>
      <div class="bl-sec"><i class="fas fa-align-left"></i> Description</div>
      <div class="bl-desc-box" id="view-desc"></div>
      <div class="bl-meta-box" id="view-meta-box">
        <div class="mi">
          <label>Meta Title</label>
          <p id="view-meta-title"></p>
        </div>
        <div class="mi">
          <label>Meta Description</label>
          <p id="view-meta-desc"></p>
        </div>
      </div>
    </div>
    <div class="bl-modal-footer">
      <button class="bl-btn bl-btn-outline" onclick="closeModal('viewModal')">Close</button>
      <a id="view-edit-link" href="#" class="bl-btn bl-btn-warn"><i class="fas fa-pen"></i> Edit Post</a>
    </div>
  </div>
</div>

{{-- DELETE MODAL --}}
<div class="bl-overlay" id="deleteModal">
  <div class="bl-modal bl-del-modal">
    <div class="bl-del-body">
      <div class="bl-del-icon"><i class="fas fa-newspaper"></i></div>
      <h5>Delete Blog Post?</h5>
      <p><strong id="del-title" style="color:var(--p-teal);display:block;margin-bottom:6px;"></strong>This post will be permanently deleted and cannot be recovered.</p>
    </div>
    <div class="bl-del-footer">
      <button class="bl-btn bl-btn-outline" onclick="closeModal('deleteModal')"><i class="fas fa-times"></i> Cancel</button>
      <form id="deleteForm" method="POST" style="display:inline;">
        @csrf
        <button type="submit" class="bl-btn bl-btn-danger"><i class="fas fa-trash-alt"></i> Yes, Delete</button>
      </form>
    </div>
  </div>
</div>

<div id="bl-toast"></div>

<script>
function openModal(id)  { document.getElementById(id).classList.add('open');    document.body.style.overflow='hidden'; }
function closeModal(id) { document.getElementById(id).classList.remove('open'); document.body.style.overflow=''; }
document.querySelectorAll('.bl-overlay').forEach(function(el){
  el.addEventListener('click',function(e){ if(e.target===el) closeModal(el.id); });
});
document.addEventListener('keydown',function(e){ if(e.key==='Escape'){ closeModal('viewModal'); closeModal('deleteModal'); } });

function openViewModal(d) {
  document.getElementById('view-title').textContent    = d.title;
  document.getElementById('view-slug').textContent     = d.slug;
  document.getElementById('view-category').textContent = d.category;
  document.getElementById('view-date').textContent     = d.date;
  document.getElementById('view-desc').innerHTML       = d.desc || '—';
  document.getElementById('view-meta-title').textContent = d.meta_title || '—';
  document.getElementById('view-meta-desc').textContent  = d.meta_desc  || '—';
  document.getElementById('view-edit-link').href = d.edit_url;
  var cover = document.getElementById('view-cover');
  if(d.image){ cover.src=d.image; cover.style.display='block'; } else { cover.style.display='none'; }
  var s = document.getElementById('view-status');
  s.innerHTML = d.status==1
    ? '<span class="bl-badge bl-badge-active"><i class="fas fa-circle" style="font-size:.4rem;"></i>Published</span>'
    : '<span class="bl-badge bl-badge-inactive"><i class="fas fa-circle" style="font-size:.4rem;"></i>Draft</span>';
  document.getElementById('view-meta-box').style.display = (d.meta_title||d.meta_desc) ? 'grid' : 'none';
  openModal('viewModal');
}

function openDeleteModal(action, title) {
  document.getElementById('del-title').textContent = title;
  document.getElementById('deleteForm').action = action;
  openModal('deleteModal');
}

function showToast(type,title,msg) {
  var c=document.getElementById('bl-toast'), t=document.createElement('div');
  var icon=type==='s'?'fas fa-check-circle':'fas fa-exclamation-circle';
  t.className='bl-toast bl-toast-'+type;
  t.innerHTML='<div class="bl-toast-icon"><i class="'+icon+'"></i></div><div><div class="bl-toast-title">'+title+'</div><div class="bl-toast-msg">'+msg+'</div></div><span class="bl-toast-bar"></span>';
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