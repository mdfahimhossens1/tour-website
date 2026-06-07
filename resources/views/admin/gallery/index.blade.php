@extends('layouts.admin')
@section('title', 'Gallery')
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
  --p-pink:      #ec4899;
  --p-text:      #e2e8f0;
  --p-muted:     #64748b;
  --p-radius:    14px;
  --p-radius-sm: 8px;
  --p-shadow:    0 8px 32px rgba(0,0,0,.45);
}
.gl-wrap { font-family:'Plus Jakarta Sans',sans-serif; color:var(--p-text); }

/* ── HEADER ── */
.gl-header {
  background:linear-gradient(135deg,#0c1a2e 0%,#1a0c2e 55%,#0c1a2e 100%);
  border-radius:var(--p-radius); padding:28px 32px; margin-bottom:24px;
  position:relative; overflow:hidden; box-shadow:var(--p-shadow);
}
.gl-header::before {
  content:''; position:absolute; inset:0;
  background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ec4899' fill-opacity='0.05'%3E%3Cpath d='M30 5L55 20v20L30 55 5 40V20Z'/%3E%3C/g%3E%3C/svg%3E");
}
.gl-header::after {
  content:''; position:absolute; right:-40px; top:-40px; width:200px; height:200px;
  border-radius:50%; background:radial-gradient(circle,rgba(236,72,153,.18) 0%,transparent 70%);
}
.gl-header .title {
  font-size:1.5rem; font-weight:700; position:relative; z-index:1;
  background:linear-gradient(90deg,#fff,#f9a8d4);
  -webkit-background-clip:text; -webkit-text-fill-color:transparent;
}
.gl-header .subtitle { color:rgba(255,255,255,.45); font-size:.85rem; margin-top:4px; position:relative; z-index:1; }
.stat-pill {
  display:inline-flex; align-items:center; gap:8px;
  background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.1);
  border-radius:40px; padding:6px 16px; font-size:.8rem; font-weight:600; color:#fff; position:relative; z-index:1;
}
.stat-pill .dot { width:8px; height:8px; border-radius:50%; }
.gl-upload-btn {
  background:linear-gradient(135deg,var(--p-pink),#db2777); color:#fff; border:none;
  border-radius:10px; padding:10px 20px; font-family:'Plus Jakarta Sans',sans-serif;
  font-weight:700; font-size:.85rem; cursor:pointer;
  display:inline-flex; align-items:center; gap:8px;
  transition:all .2s; position:relative; z-index:1;
}
.gl-upload-btn:hover { transform:translateY(-2px); box-shadow:0 6px 18px rgba(236,72,153,.4); }

/* ── GRID ── */
.gl-card-wrap {
  background:var(--p-surface); border:1px solid var(--p-border);
  border-radius:var(--p-radius); box-shadow:var(--p-shadow); padding:24px;
}
.gl-grid {
  display:grid;
  grid-template-columns:repeat(auto-fill,minmax(220px,1fr));
  gap:16px;
}

/* ── GALLERY ITEM ── */
.gl-item {
  background:var(--p-surface2); border:1px solid var(--p-border);
  border-radius:12px; overflow:hidden; transition:transform .2s,border-color .2s,box-shadow .2s;
  position:relative;
}
.gl-item:hover { transform:translateY(-3px); border-color:rgba(255,255,255,.14); box-shadow:0 12px 32px rgba(0,0,0,.4); }
.gl-item:hover .gl-overlay { opacity:1; }

.gl-img-wrap { position:relative; height:175px; overflow:hidden; }
.gl-img-wrap img { width:100%; height:100%; object-fit:cover; display:block; transition:transform .3s; }
.gl-item:hover .gl-img-wrap img { transform:scale(1.04); }

/* hover overlay */
.gl-overlay {
  position:absolute; inset:0;
  background:rgba(0,0,0,.55);
  display:flex; align-items:center; justify-content:center; gap:10px;
  opacity:0; transition:opacity .2s;
}
.gl-overlay-btn {
  width:38px; height:38px; border-radius:50%; border:none; cursor:pointer;
  display:flex; align-items:center; justify-content:center; font-size:.85rem;
  transition:transform .15s;
}
.gl-overlay-btn:hover { transform:scale(1.12); }
.gl-obtn-view { background:rgba(14,165,233,.85); color:#fff; }
.gl-obtn-del  { background:rgba(239,68,68,.85);  color:#fff; }

/* type badge on image */
.gl-type-badge {
  position:absolute; top:8px; left:8px;
  padding:3px 9px; border-radius:20px; font-size:.68rem; font-weight:700;
  backdrop-filter:blur(6px);
}
.gl-type-image { background:rgba(34,197,94,.7);  color:#fff; }
.gl-type-video { background:rgba(239,68,68,.7);  color:#fff; }

/* footer */
.gl-item-footer { padding:10px 12px; }
.gl-tour-name { font-size:.78rem; color:var(--p-muted); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:flex; align-items:center; gap:5px; }
.gl-tour-name i { color:var(--p-pink); font-size:.65rem; }

/* empty */
.gl-empty { text-align:center; padding:70px 20px; color:var(--p-muted); }
.gl-empty i { font-size:2.5rem; margin-bottom:14px; opacity:.3; display:block; }

/* pagination */
.pagination { margin-top:20px; }
.pagination .page-item .page-link {
  background:var(--p-surface2); border:1px solid var(--p-border);
  color:var(--p-muted); font-size:.82rem; border-radius:6px !important; margin:0 2px; transition:all .2s;
}
.pagination .page-item .page-link:hover { background:var(--p-border); color:var(--p-text); }
.pagination .page-item.active .page-link { background:var(--p-pink); border-color:var(--p-pink); color:#fff; font-weight:700; }

/* ── MODAL BASE ── */
.gl-overlay-modal {
  position:fixed; inset:0; z-index:9999;
  background:rgba(0,0,0,.78); backdrop-filter:blur(7px);
  display:flex; align-items:center; justify-content:center;
  opacity:0; pointer-events:none; transition:opacity .25s;
}
.gl-overlay-modal.open { opacity:1; pointer-events:auto; }
.gl-modal {
  background:var(--p-surface); border:1px solid var(--p-border);
  border-radius:18px; width:min(540px,96vw); max-height:92vh; overflow-y:auto;
  box-shadow:0 24px 64px rgba(0,0,0,.7);
  transform:translateY(24px) scale(.97);
  transition:transform .3s cubic-bezier(.34,1.56,.64,1);
}
.gl-overlay-modal.open .gl-modal { transform:translateY(0) scale(1); }
.gl-modal::-webkit-scrollbar { width:5px; }
.gl-modal::-webkit-scrollbar-thumb { background:var(--p-border); border-radius:10px; }
.gl-modal-header {
  padding:20px 26px 16px; border-bottom:1px solid var(--p-border);
  display:flex; align-items:center; justify-content:space-between;
  background:var(--p-surface2); position:sticky; top:0; z-index:2;
}
.gl-modal-title { font-size:1.05rem; font-weight:700; display:flex; align-items:center; gap:9px; }
.gl-modal-close {
  background:var(--p-surface); border:1px solid var(--p-border); color:var(--p-muted);
  width:30px; height:30px; border-radius:7px; cursor:pointer;
  display:flex; align-items:center; justify-content:center; transition:all .2s; font-size:.85rem;
}
.gl-modal-close:hover { color:var(--p-text); }
.gl-modal-body   { padding:24px 26px; }
.gl-modal-footer { padding:14px 26px 20px; border-top:1px solid var(--p-border); display:flex; gap:10px; justify-content:flex-end; background:rgba(0,0,0,.1); }

/* upload form inside modal */
.gf-field { margin-bottom:17px; }
.gf-field label { display:block; font-size:.73rem; font-weight:700; letter-spacing:.07em; text-transform:uppercase; color:var(--p-muted); margin-bottom:7px; }
.gf-field label .req { color:var(--p-danger); }
.gf-field input,.gf-field select {
  width:100%; background:var(--p-surface2); border:1px solid var(--p-border);
  border-radius:var(--p-radius-sm); padding:10px 14px; color:var(--p-text);
  font-family:'Plus Jakarta Sans',sans-serif; font-size:.875rem; outline:none;
  transition:border-color .2s,box-shadow .2s; box-sizing:border-box;
}
.gf-field input:focus,.gf-field select:focus { border-color:var(--p-pink); box-shadow:0 0 0 3px rgba(236,72,153,.12); }
.gf-field select option { background:#1a1d27; }
.gf-field .err { color:#fca5a5; font-size:.76rem; margin-top:5px; display:block; }

/* type chips */
.gf-type-row { display:flex; gap:10px; }
.gf-type-opt { flex:1; }
.gf-type-opt input[type="radio"] { display:none; }
.gf-type-opt label { display:flex; align-items:center; justify-content:center; gap:7px; padding:10px; border-radius:8px; border:1px solid var(--p-border); cursor:pointer; font-size:.83rem; font-weight:600; transition:all .2s; background:var(--p-surface2); color:var(--p-muted); text-transform:none; letter-spacing:0; }
.gf-type-opt input:checked + label.lbl-img { background:rgba(34,197,94,.12); color:#86efac; border-color:rgba(34,197,94,.3); }
.gf-type-opt input:checked + label.lbl-vid { background:rgba(239,68,68,.12); color:#fca5a5; border-color:rgba(239,68,68,.3); }

/* file upload zone */
.gf-upload-zone {
  border:2px dashed var(--p-border); border-radius:10px; padding:22px;
  text-align:center; cursor:pointer; transition:all .2s; position:relative; background:var(--p-surface2);
}
.gf-upload-zone:hover { border-color:var(--p-pink); background:rgba(236,72,153,.05); }
.gf-upload-zone input { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; }
.gf-upload-zone i { font-size:1.5rem; color:var(--p-muted); display:block; margin-bottom:6px; }
.gf-upload-zone span { font-size:.8rem; color:var(--p-muted); }
.gf-preview { display:none; width:100%; height:140px; object-fit:cover; border-radius:9px; border:1px solid var(--p-border); margin-top:12px; }

.gf-submit {
  flex:1; padding:10px; border:none; border-radius:var(--p-radius-sm);
  background:linear-gradient(135deg,var(--p-pink),#db2777); color:#fff;
  font-family:'Plus Jakarta Sans',sans-serif; font-weight:700; font-size:.88rem;
  cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px;
  transition:all .2s;
}
.gf-submit:hover { transform:translateY(-1px); box-shadow:0 5px 16px rgba(236,72,153,.35); }
.gl-btn-outline {
  padding:10px 18px; border-radius:var(--p-radius-sm); border:1px solid var(--p-border);
  background:transparent; color:var(--p-text); font-family:'Plus Jakarta Sans',sans-serif;
  font-weight:600; font-size:.85rem; cursor:pointer; display:inline-flex; align-items:center; gap:7px; transition:background .2s;
}
.gl-btn-outline:hover { background:var(--p-surface2); }

/* ── VIEW MODAL ── */
.gl-view-img { width:100%; max-height:360px; object-fit:contain; border-radius:12px; background:#000; border:1px solid var(--p-border); margin-bottom:16px; display:block; }
.gl-view-meta { display:flex; gap:10px; flex-wrap:wrap; }
.gl-view-pill { display:inline-flex; align-items:center; gap:7px; background:var(--p-surface2); border:1px solid var(--p-border); border-radius:8px; padding:7px 14px; font-size:.8rem; color:var(--p-muted); }
.gl-view-pill i { color:var(--p-pink); font-size:.72rem; }
.gl-view-pill strong { color:var(--p-text); font-weight:600; }

/* ── DELETE MODAL ── */
.gl-del-modal { max-width:400px; }
.gl-del-body  { text-align:center; padding:34px 28px 16px; }
.gl-del-icon  { width:64px; height:64px; border-radius:50%; background:rgba(239,68,68,.12); border:2px solid rgba(239,68,68,.25); display:flex; align-items:center; justify-content:center; font-size:1.5rem; color:#fca5a5; margin:0 auto 16px; }
.gl-del-body h5 { font-weight:700; margin-bottom:8px; color:var(--p-text); }
.gl-del-body p  { color:var(--p-muted); font-size:.875rem; line-height:1.6; margin:0; }
.gl-del-footer  { padding:14px 28px 24px; display:flex; gap:10px; justify-content:center; }
.gl-btn-danger { background:var(--p-danger); color:#fff; border:none; padding:9px 20px; border-radius:var(--p-radius-sm); font-family:'Plus Jakarta Sans',sans-serif; font-weight:600; font-size:.85rem; cursor:pointer; display:inline-flex; align-items:center; gap:7px; transition:all .2s; }
.gl-btn-danger:hover { background:#dc2626; transform:translateY(-1px); }

/* Toast */
#gl-toast { position:fixed; bottom:28px; right:28px; z-index:99999; display:flex; flex-direction:column; gap:10px; }
.gl-toast { display:flex; align-items:center; gap:12px; background:var(--p-surface); border:1px solid var(--p-border); border-radius:12px; padding:14px 18px; min-width:260px; box-shadow:0 8px 30px rgba(0,0,0,.5); transform:translateX(120%); transition:transform .35s cubic-bezier(.34,1.56,.64,1); font-family:'Plus Jakarta Sans',sans-serif; position:relative; overflow:hidden; }
.gl-toast.show { transform:translateX(0); }
.gl-toast-icon { width:34px; height:34px; border-radius:9px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.gl-toast-s .gl-toast-icon { background:rgba(34,197,94,.15); color:var(--p-success); }
.gl-toast-d .gl-toast-icon { background:rgba(239,68,68,.15); color:var(--p-danger); }
.gl-toast-title { font-size:.875rem; font-weight:700; color:var(--p-text); }
.gl-toast-msg   { font-size:.77rem; color:var(--p-muted); margin-top:1px; }
.gl-toast-bar   { position:absolute; bottom:0; left:0; height:3px; border-radius:0 0 12px 12px; animation:glBar 3.2s linear forwards; }
.gl-toast-s .gl-toast-bar { background:var(--p-success); }
.gl-toast-d .gl-toast-bar { background:var(--p-danger); }
@keyframes glBar { from{width:100%} to{width:0%} }
</style>

@if(session('success'))<div id="flash-s" data-msg="{{ session('success') }}"></div>@endif
@if(session('error'))<div id="flash-e"   data-msg="{{ session('error') }}"></div>@endif

<div class="gl-wrap">

  {{-- HEADER --}}
  <div class="gl-header">
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;">
      <div>
        <div class="title"><i class="fas fa-images me-2"></i>Gallery</div>
        <div class="subtitle">Manage your photos and media library</div>
        <div style="display:flex;gap:8px;margin-top:14px;flex-wrap:wrap;">
          <span class="stat-pill"><span class="dot" style="background:var(--p-success)"></span>{{ $galleries->where('type','image')->count() }} Images</span>
          <span class="stat-pill"><span class="dot" style="background:var(--p-danger)"></span>{{ $galleries->where('type','video')->count() }} Videos</span>
          <span class="stat-pill"><span class="dot" style="background:var(--p-pink)"></span>{{ $galleries->total() }} Total</span>
        </div>
      </div>
      <button class="gl-upload-btn" onclick="openUploadModal()">
        <i class="fas fa-cloud-upload-alt"></i> Upload Image
      </button>
    </div>
  </div>

  {{-- GALLERY GRID --}}
  <div class="gl-card-wrap">

    <div class="gl-grid">
      @forelse($galleries as $gallery)
      <div class="gl-item">
        <div class="gl-img-wrap">
          <img src="{{ asset('uploads/gallery/'.$gallery->image) }}" alt="">
          <span class="gl-type-badge {{ $gallery->type == 'video' ? 'gl-type-video' : 'gl-type-image' }}">
            <i class="fas {{ $gallery->type == 'video' ? 'fa-video' : 'fa-image' }}" style="font-size:.6rem;margin-right:3px;"></i>
            {{ ucfirst($gallery->type) }}
          </span>
          <div class="gl-overlay">
            <button class="gl-overlay-btn gl-obtn-view" title="View"
              onclick="openViewModal(
                '{{ asset('uploads/gallery/'.$gallery->image) }}',
                {{ json_encode($gallery->tour->title ?? 'No Tour') }},
                '{{ $gallery->type }}'
              )">
              <i class="fas fa-eye"></i>
            </button>
            <button class="gl-overlay-btn gl-obtn-del" title="Delete"
              onclick="openDeleteModal('{{ route('admin.gallery.delete',$gallery->id) }}')">
              <i class="fas fa-trash-alt"></i>
            </button>
          </div>
        </div>
        <div class="gl-item-footer">
          <div class="gl-tour-name">
            <i class="fas fa-map-marker-alt"></i>
            {{ Str::limit($gallery->tour->title ?? 'No Tour', 30) }}
          </div>
        </div>
      </div>
      @empty
      <div class="gl-empty" style="grid-column:1/-1;">
        <i class="fas fa-images"></i>
        <p>No images uploaded yet. Click <strong>Upload Image</strong> to get started.</p>
      </div>
      @endforelse
    </div>

    @if($galleries->hasPages())
      <div class="pagination">{{ $galleries->links() }}</div>
    @endif
  </div>

</div>

{{-- ══════════════════════════════
     UPLOAD MODAL
══════════════════════════════ --}}
<div class="gl-overlay-modal" id="uploadModal">
  <div class="gl-modal">
    <div class="gl-modal-header">
      <div class="gl-modal-title">
        <i class="fas fa-cloud-upload-alt" style="color:var(--p-pink);"></i> Upload to Gallery
      </div>
      <button class="gl-modal-close" onclick="closeModal('uploadModal')"><i class="fas fa-times"></i></button>
    </div>
    <form method="POST" action="{{ route('admin.gallery.store') }}" enctype="multipart/form-data">
      @csrf
      <div class="gl-modal-body">

        <div class="gf-field">
          <label>Select Tour <span class="req">*</span></label>
          <select name="tour_id">
            <option value="">— Choose a Tour —</option>
            @foreach($tours as $tour)
              <option value="{{ $tour->id }}" {{ old('tour_id')==$tour->id?'selected':'' }}>{{ $tour->title }}</option>
            @endforeach
          </select>
          @error('tour_id')<span class="err">{{ $message }}</span>@enderror
        </div>

        <div class="gf-field">
          <label>Media Type</label>
          <div class="gf-type-row">
            <div class="gf-type-opt">
              <input type="radio" name="type" id="t_img" value="image" {{ old('type','image')=='image'?'checked':'' }}>
              <label for="t_img" class="lbl-img"><i class="fas fa-image"></i> Image</label>
            </div>
            <div class="gf-type-opt">
              <input type="radio" name="type" id="t_vid" value="video" {{ old('type')=='video'?'checked':'' }}>
              <label for="t_vid" class="lbl-vid"><i class="fas fa-video"></i> Video</label>
            </div>
          </div>
        </div>

        <div class="gf-field" style="margin-bottom:0;">
          <label>Upload File <span class="req">*</span></label>
          <div class="gf-upload-zone" id="uploadZone">
            <input type="file" name="image" id="fileInput" accept="image/*,video/*" onchange="previewFile(this)">
            <i class="fas fa-cloud-upload-alt"></i>
            <span id="uploadLabel">Click to browse or drag & drop</span>
          </div>
          <img id="filePreview" class="gf-preview" src="" alt="">
          @error('image')<span class="err" style="margin-top:6px;display:block;">{{ $message }}</span>@enderror
        </div>

      </div>
      <div class="gl-modal-footer">
        <button type="button" class="gl-btn-outline" onclick="closeModal('uploadModal')">
          <i class="fas fa-times"></i> Cancel
        </button>
        <button type="submit" class="gf-submit">
          <i class="fas fa-upload"></i> Upload Now
        </button>
      </div>
    </form>
  </div>
</div>

{{-- ══════════════════════════════
     VIEW MODAL
══════════════════════════════ --}}
<div class="gl-overlay-modal" id="viewModal">
  <div class="gl-modal">
    <div class="gl-modal-header">
      <div class="gl-modal-title"><i class="fas fa-eye" style="color:var(--p-accent2);"></i> Preview</div>
      <button class="gl-modal-close" onclick="closeModal('viewModal')"><i class="fas fa-times"></i></button>
    </div>
    <div class="gl-modal-body">
      <img id="view-img" src="" alt="" class="gl-view-img">
      <div class="gl-view-meta">
        <div class="gl-view-pill">
          <i class="fas fa-map-marker-alt"></i>
          Tour: <strong id="view-tour">—</strong>
        </div>
        <div class="gl-view-pill" id="view-type-pill">
          <i class="fas fa-tag"></i>
          Type: <strong id="view-type">—</strong>
        </div>
      </div>
    </div>
    <div class="gl-modal-footer">
      <button class="gl-btn-outline" onclick="closeModal('viewModal')">Close</button>
    </div>
  </div>
</div>

{{-- ══════════════════════════════
     DELETE CONFIRM MODAL
══════════════════════════════ --}}
<div class="gl-overlay-modal" id="deleteModal">
  <div class="gl-modal gl-del-modal">
    <div class="gl-del-body">
      <div class="gl-del-icon"><i class="fas fa-image"></i></div>
      <h5>Delete this image?</h5>
      <p>This media file will be permanently removed from the gallery and cannot be recovered.</p>
    </div>
    <div class="gl-del-footer">
      <button class="gl-btn-outline" onclick="closeModal('deleteModal')"><i class="fas fa-times"></i> Cancel</button>
      <form id="deleteForm" method="POST" style="display:inline;">
        @csrf
        <button type="submit" class="gl-btn-danger"><i class="fas fa-trash-alt"></i> Yes, Delete</button>
      </form>
    </div>
  </div>
</div>

<div id="gl-toast"></div>

<script>
/* ── Modals ── */
function openModal(id)  { document.getElementById(id).classList.add('open');    document.body.style.overflow='hidden'; }
function closeModal(id) { document.getElementById(id).classList.remove('open'); document.body.style.overflow=''; }
document.querySelectorAll('.gl-overlay-modal').forEach(function(el){
  el.addEventListener('click',function(e){ if(e.target===el) closeModal(el.id); });
});
document.addEventListener('keydown',function(e){
  if(e.key==='Escape') document.querySelectorAll('.gl-overlay-modal.open').forEach(function(el){ closeModal(el.id); });
});

/* ── Upload ── */
function openUploadModal() { openModal('uploadModal'); }

function previewFile(input) {
  if(!input.files||!input.files[0]) return;
  var file  = input.files[0];
  var img   = document.getElementById('filePreview');
  var label = document.getElementById('uploadLabel');
  label.textContent = file.name;
  document.getElementById('uploadZone').style.borderColor = 'var(--p-pink)';
  if(file.type.startsWith('image/')) {
    img.src = URL.createObjectURL(file);
    img.style.display = 'block';
  } else {
    img.style.display = 'none';
  }
}

/* ── View ── */
function openViewModal(imgSrc, tour, type) {
  document.getElementById('view-img').src           = imgSrc;
  document.getElementById('view-tour').textContent  = tour;
  document.getElementById('view-type').textContent  = type.charAt(0).toUpperCase() + type.slice(1);
  openModal('viewModal');
}

/* ── Delete ── */
function openDeleteModal(action) {
  document.getElementById('deleteForm').action = action;
  openModal('deleteModal');
}

/* ── Toast ── */
function showToast(type,title,msg) {
  var c=document.getElementById('gl-toast'), t=document.createElement('div');
  var icon=type==='s'?'fas fa-check-circle':'fas fa-exclamation-circle';
  t.className='gl-toast gl-toast-'+type;
  t.innerHTML='<div class="gl-toast-icon"><i class="'+icon+'"></i></div><div><div class="gl-toast-title">'+title+'</div><div class="gl-toast-msg">'+msg+'</div></div><span class="gl-toast-bar"></span>';
  c.appendChild(t);
  setTimeout(function(){ t.classList.add('show'); },20);
  setTimeout(function(){ t.classList.remove('show'); setTimeout(function(){ t.remove(); },400); },3500);
}
(function(){
  var s=document.getElementById('flash-s'), e=document.getElementById('flash-e');
  if(s) showToast('s','Success',s.dataset.msg);
  if(e) showToast('d','Error',e.dataset.msg);
  /* auto open upload modal on validation error */
  @if($errors->any()) openUploadModal(); @endif
})();
</script>

@endsection