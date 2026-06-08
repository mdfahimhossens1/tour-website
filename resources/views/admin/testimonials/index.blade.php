@extends('layouts.admin')
@section('title', 'Testimonials')
@section('page')

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');
:root {
  --p-surface:   #1a1d27;
  --p-surface2:  #222636;
  --p-border:    rgba(255,255,255,.07);
  --p-success:   #22c55e;
  --p-danger:    #ef4444;
  --p-warning:   #f59e0b;
  --p-amber:     #f59e0b;
  --p-text:      #e2e8f0;
  --p-muted:     #64748b;
  --p-radius:    14px;
  --p-radius-sm: 8px;
  --p-shadow:    0 8px 32px rgba(0,0,0,.45);
  --p-gold:      #f59e0b;
  --p-rose:      #f43f5e;
}
.tm-wrap { font-family:'Plus Jakarta Sans',sans-serif; color:var(--p-text); }

/* ── HEADER ── */
.tm-header {
  background:linear-gradient(135deg,#1a0c0e 0%,#2e0c18 55%,#1a0c0e 100%);
  border-radius:var(--p-radius); padding:28px 32px; margin-bottom:24px;
  position:relative; overflow:hidden; box-shadow:var(--p-shadow);
}
.tm-header::before {
  content:''; position:absolute; inset:0;
  background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23f43f5e' fill-opacity='0.05'%3E%3Cpath d='M30 5L55 20v20L30 55 5 40V20Z'/%3E%3C/g%3E%3C/svg%3E");
}
.tm-header::after {
  content:''; position:absolute; right:-40px; top:-40px; width:200px; height:200px;
  border-radius:50%; background:radial-gradient(circle,rgba(244,63,94,.2) 0%,transparent 70%);
}
.tm-header .title {
  font-size:1.5rem; font-weight:700; position:relative; z-index:1;
  background:linear-gradient(90deg,#fff,#fda4af);
  -webkit-background-clip:text; -webkit-text-fill-color:transparent;
}
.tm-header .subtitle { color:rgba(255,255,255,.45); font-size:.85rem; margin-top:4px; position:relative; z-index:1; }
.stat-pill {
  display:inline-flex; align-items:center; gap:8px;
  background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.1);
  border-radius:40px; padding:6px 16px; font-size:.8rem; font-weight:600; color:#fff; position:relative; z-index:1;
}
.stat-pill .dot { width:8px; height:8px; border-radius:50%; }
.tm-add-btn {
  background:linear-gradient(135deg,var(--p-rose),#be123c); color:#fff; border:none;
  border-radius:10px; padding:10px 20px; font-family:'Plus Jakarta Sans',sans-serif;
  font-weight:700; font-size:.85rem; cursor:pointer;
  display:inline-flex; align-items:center; gap:8px;
  transition:all .2s; position:relative; z-index:1;
}
.tm-add-btn:hover { transform:translateY(-2px); box-shadow:0 6px 18px rgba(244,63,94,.4); }

/* ── CARD ── */
.tm-card {
  background:var(--p-surface); border:1px solid var(--p-border);
  border-radius:var(--p-radius); box-shadow:var(--p-shadow); overflow:hidden;
}

/* ── TABLE ── */
.tm-table { width:100%; border-collapse:collapse; }
.tm-table thead tr { background:var(--p-surface2); border-bottom:1px solid var(--p-border); }
.tm-table th {
  font-size:.7rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase;
  color:var(--p-muted); padding:13px 20px; text-align:left;
}
.tm-table td {
  padding:14px 20px; border-bottom:1px solid var(--p-border);
  font-size:.875rem; color:var(--p-text); vertical-align:middle;
}
.tm-table tbody tr:last-child td { border-bottom:none; }
.tm-table tbody tr { transition:background .15s; }
.tm-table tbody tr:hover { background:rgba(255,255,255,.02); }

.tm-serial { font-family:'JetBrains Mono',monospace; font-size:.78rem; color:var(--p-muted); }

/* avatar */
.tm-avatar-img { width:46px; height:46px; border-radius:50%; object-fit:cover; border:2px solid rgba(244,63,94,.3); }
.tm-avatar-txt {
  width:46px; height:46px; border-radius:50%;
  background:linear-gradient(135deg,var(--p-rose),#9f1239);
  display:flex; align-items:center; justify-content:center;
  font-size:.9rem; font-weight:700; color:#fff; text-transform:uppercase;
}

/* name + designation */
.tm-person { display:flex; align-items:center; gap:11px; }
.tm-person-name { font-weight:600; font-size:.88rem; }
.tm-person-desg { font-size:.75rem; color:var(--p-muted); margin-top:2px; }

/* stars */
.tm-stars { display:flex; gap:2px; }
.tm-star-fill { color:#fbbf24; font-size:.78rem; }
.tm-star-empty { color:#374151; font-size:.78rem; }

/* badges */
.tm-badge { display:inline-flex; align-items:center; gap:5px; padding:3px 10px; border-radius:20px; font-size:.72rem; font-weight:700; }
.tm-badge-active   { background:rgba(34,197,94,.12);  color:#86efac; border:1px solid rgba(34,197,94,.25); }
.tm-badge-inactive { background:rgba(239,68,68,.12);  color:#fca5a5; border:1px solid rgba(239,68,68,.25); }

/* actions */
.tm-actions { display:flex; gap:7px; }
.tm-btn-view {
  display:inline-flex; align-items:center; gap:5px;
  background:rgba(244,63,94,.1); color:#fda4af;
  border:1px solid rgba(244,63,94,.2); border-radius:6px;
  padding:5px 12px; font-size:.78rem; font-weight:600;
  cursor:pointer; transition:all .2s; font-family:'Plus Jakarta Sans',sans-serif;
}
.tm-btn-view:hover { background:rgba(244,63,94,.2); transform:translateY(-1px); }
.tm-btn-del {
  display:inline-flex; align-items:center; gap:5px;
  background:rgba(239,68,68,.1); color:#fca5a5;
  border:1px solid rgba(239,68,68,.2); border-radius:6px;
  padding:5px 12px; font-size:.78rem; font-weight:600;
  cursor:pointer; transition:all .2s; font-family:'Plus Jakarta Sans',sans-serif;
}
.tm-btn-del:hover { background:rgba(239,68,68,.2); transform:translateY(-1px); }

/* empty */
.tm-empty { text-align:center; padding:70px 20px; color:var(--p-muted); }
.tm-empty i { font-size:2.5rem; margin-bottom:14px; opacity:.3; display:block; }

/* pagination */
.pagination { padding:14px 20px; }
.pagination .page-item .page-link { background:var(--p-surface2); border:1px solid var(--p-border); color:var(--p-muted); font-size:.82rem; border-radius:6px !important; margin:0 2px; transition:all .2s; }
.pagination .page-item .page-link:hover { background:var(--p-border); color:var(--p-text); }
.pagination .page-item.active .page-link { background:var(--p-rose); border-color:var(--p-rose); color:#fff; font-weight:700; }

/* ── MODAL ── */
.tm-overlay {
  position:fixed; inset:0; z-index:9999;
  background:rgba(0,0,0,.76); backdrop-filter:blur(6px);
  display:flex; align-items:center; justify-content:center;
  opacity:0; pointer-events:none; transition:opacity .25s;
}
.tm-overlay.open { opacity:1; pointer-events:auto; }
.tm-modal {
  background:var(--p-surface); border:1px solid var(--p-border);
  border-radius:18px; width:min(560px,96vw); max-height:90vh; overflow-y:auto;
  box-shadow:0 24px 64px rgba(0,0,0,.7);
  transform:translateY(24px) scale(.97);
  transition:transform .3s cubic-bezier(.34,1.56,.64,1);
}
.tm-overlay.open .tm-modal { transform:translateY(0) scale(1); }
.tm-modal::-webkit-scrollbar { width:5px; }
.tm-modal::-webkit-scrollbar-thumb { background:var(--p-border); border-radius:10px; }
.tm-modal-header {
  padding:20px 26px 16px; border-bottom:1px solid var(--p-border);
  display:flex; align-items:center; justify-content:space-between;
  background:var(--p-surface2); position:sticky; top:0; z-index:2;
}
.tm-modal-title { font-size:1.05rem; font-weight:700; display:flex; align-items:center; gap:9px; }
.tm-modal-close { background:var(--p-surface); border:1px solid var(--p-border); color:var(--p-muted); width:30px; height:30px; border-radius:7px; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:all .2s; font-size:.85rem; }
.tm-modal-close:hover { color:var(--p-text); }
.tm-modal-body   { padding:24px 26px; }
.tm-modal-footer { padding:14px 26px 20px; border-top:1px solid var(--p-border); display:flex; gap:10px; justify-content:flex-end; background:rgba(0,0,0,.1); }

/* ADD modal form */
.tf-field { margin-bottom:17px; }
.tf-field label { display:block; font-size:.73rem; font-weight:700; letter-spacing:.07em; text-transform:uppercase; color:var(--p-muted); margin-bottom:7px; }
.tf-field label .req { color:var(--p-danger); }
.tf-field input,.tf-field select,.tf-field textarea {
  width:100%; background:var(--p-surface2); border:1px solid var(--p-border);
  border-radius:var(--p-radius-sm); padding:10px 14px; color:var(--p-text);
  font-family:'Plus Jakarta Sans',sans-serif; font-size:.875rem; outline:none;
  transition:border-color .2s,box-shadow .2s; box-sizing:border-box; resize:vertical;
}
.tf-field input:focus,.tf-field select:focus,.tf-field textarea:focus { border-color:var(--p-rose); box-shadow:0 0 0 3px rgba(244,63,94,.12); }
.tf-field select option { background:#1a1d27; }
.tf-field .err { color:#fca5a5; font-size:.76rem; margin-top:5px; display:block; }
.tf-row { display:grid; grid-template-columns:1fr 1fr; gap:14px; }

/* star rating input */
.tf-star-row { display:flex; gap:8px; }
.tf-star-opt input[type="radio"] { display:none; }
.tf-star-opt label { display:flex; align-items:center; justify-content:center; gap:5px; padding:8px 14px; border-radius:8px; border:1px solid var(--p-border); cursor:pointer; font-size:.83rem; font-weight:600; transition:all .2s; background:var(--p-surface2); color:var(--p-muted); white-space:nowrap; }
.tf-star-opt input:checked + label { background:rgba(251,191,36,.12); color:#fbbf24; border-color:rgba(251,191,36,.3); }

/* file upload */
.tf-upload { border:2px dashed var(--p-border); border-radius:10px; padding:20px; text-align:center; cursor:pointer; transition:all .2s; position:relative; background:var(--p-surface2); }
.tf-upload:hover { border-color:var(--p-rose); background:rgba(244,63,94,.05); }
.tf-upload input { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; }
.tf-upload i { font-size:1.4rem; color:var(--p-muted); display:block; margin-bottom:6px; }
.tf-upload span { font-size:.8rem; color:var(--p-muted); }
.tf-preview { display:none; width:80px; height:80px; object-fit:cover; border-radius:50%; border:2px solid rgba(244,63,94,.3); margin:10px auto 0; }

/* status toggle */
.tf-status-row { display:flex; gap:10px; }
.tf-status-opt { flex:1; }
.tf-status-opt input[type="radio"] { display:none; }
.tf-status-opt label { display:flex; align-items:center; justify-content:center; gap:7px; padding:9px; border-radius:8px; border:1px solid var(--p-border); cursor:pointer; font-size:.83rem; font-weight:600; transition:all .2s; background:var(--p-surface2); color:var(--p-muted); text-transform:none; letter-spacing:0; }
.tf-status-opt input:checked + label.lbl-on  { background:rgba(34,197,94,.12); color:#86efac; border-color:rgba(34,197,94,.3); }
.tf-status-opt input:checked + label.lbl-off { background:rgba(239,68,68,.12); color:#fca5a5; border-color:rgba(239,68,68,.3); }

.tf-submit { width:100%; padding:11px; border:none; border-radius:var(--p-radius-sm); background:linear-gradient(135deg,var(--p-rose),#be123c); color:#fff; font-family:'Plus Jakarta Sans',sans-serif; font-weight:700; font-size:.9rem; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; transition:all .2s; }
.tf-submit:hover { transform:translateY(-2px); box-shadow:0 6px 18px rgba(244,63,94,.35); }

/* VIEW modal */
.tm-view-profile { display:flex; align-items:center; gap:16px; background:var(--p-surface2); border:1px solid var(--p-border); border-radius:12px; padding:18px 20px; margin-bottom:20px; }
.tm-view-avatar { width:60px; height:60px; border-radius:50%; object-fit:cover; border:2px solid rgba(244,63,94,.3); flex-shrink:0; }
.tm-view-avatar-txt { width:60px; height:60px; border-radius:50%; background:linear-gradient(135deg,var(--p-rose),#9f1239); display:flex; align-items:center; justify-content:center; font-size:1.3rem; font-weight:700; color:#fff; flex-shrink:0; text-transform:uppercase; }
.tm-view-name { font-size:1rem; font-weight:700; }
.tm-view-desg { font-size:.78rem; color:var(--p-muted); margin-top:3px; }
.tm-view-stars { display:flex; gap:3px; margin-top:6px; }
.tm-view-stars i { color:#fbbf24; font-size:.82rem; }
.tm-view-stars i.empty { color:#374151; }
.tm-view-msg  { background:var(--p-surface2); border:1px solid var(--p-border); border-left:3px solid var(--p-rose); border-radius:0 10px 10px 0; padding:16px 18px; font-size:.9rem; line-height:1.75; color:var(--p-text); font-style:italic; }
.tm-sec { font-size:.69rem; font-weight:700; letter-spacing:.09em; text-transform:uppercase; color:var(--p-rose); margin:0 0 12px; display:flex; align-items:center; gap:8px; }
.tm-sec::after { content:''; flex:1; height:1px; background:var(--p-border); }

/* DELETE modal */
.tm-del-body   { text-align:center; padding:32px 28px 16px; }
.tm-del-icon   { width:64px; height:64px; border-radius:50%; background:rgba(239,68,68,.12); border:2px solid rgba(239,68,68,.25); display:flex; align-items:center; justify-content:center; font-size:1.5rem; color:#fca5a5; margin:0 auto 16px; }
.tm-del-body h5 { font-weight:700; margin-bottom:8px; color:var(--p-text); }
.tm-del-body p  { color:var(--p-muted); font-size:.875rem; line-height:1.6; margin:0; }
.tm-del-footer  { padding:14px 28px 24px; display:flex; gap:10px; justify-content:center; }

.tm-btn { display:inline-flex; align-items:center; gap:7px; border:none; cursor:pointer; font-family:'Plus Jakarta Sans',sans-serif; font-weight:600; border-radius:var(--p-radius-sm); transition:all .2s; font-size:.85rem; padding:9px 20px; }
.tm-btn-outline { background:transparent; color:var(--p-text); border:1px solid var(--p-border); }
.tm-btn-outline:hover { background:var(--p-surface2); }
.tm-btn-danger  { background:var(--p-danger); color:#fff; border:none; }
.tm-btn-danger:hover { background:#dc2626; transform:translateY(-1px); }

/* Toast */
#tm-toast { position:fixed; bottom:28px; right:28px; z-index:99999; display:flex; flex-direction:column; gap:10px; }
.tm-toast { display:flex; align-items:center; gap:12px; background:var(--p-surface); border:1px solid var(--p-border); border-radius:12px; padding:14px 18px; min-width:260px; box-shadow:0 8px 30px rgba(0,0,0,.5); transform:translateX(120%); transition:transform .35s cubic-bezier(.34,1.56,.64,1); font-family:'Plus Jakarta Sans',sans-serif; position:relative; overflow:hidden; }
.tm-toast.show { transform:translateX(0); }
.tm-toast-icon { width:34px; height:34px; border-radius:9px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.tm-toast-s .tm-toast-icon { background:rgba(34,197,94,.15); color:var(--p-success); }
.tm-toast-d .tm-toast-icon { background:rgba(239,68,68,.15); color:var(--p-danger); }
.tm-toast-title { font-size:.875rem; font-weight:700; color:var(--p-text); }
.tm-toast-msg   { font-size:.77rem; color:var(--p-muted); margin-top:1px; }
.tm-toast-bar   { position:absolute; bottom:0; left:0; height:3px; border-radius:0 0 12px 12px; animation:tmBar 3.2s linear forwards; }
.tm-toast-s .tm-toast-bar { background:var(--p-success); }
.tm-toast-d .tm-toast-bar { background:var(--p-danger); }
@keyframes tmBar { from{width:100%} to{width:0%} }
</style>

@if(session('success'))<div id="flash-s" data-msg="{{ session('success') }}"></div>@endif
@if(session('error'))<div id="flash-e"   data-msg="{{ session('error') }}"></div>@endif

<div class="tm-wrap">

  {{-- HEADER --}}
  <div class="tm-header">
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;">
      <div>
        <div class="title"><i class="fas fa-quote-left me-2"></i>Testimonials</div>
        <div class="subtitle">Manage customer reviews and feedback</div>
        <div style="display:flex;gap:8px;margin-top:14px;flex-wrap:wrap;">
          <span class="stat-pill"><span class="dot" style="background:var(--p-success)"></span>{{ $testimonials->where('status',1)->count() }} Active</span>
          <span class="stat-pill"><span class="dot" style="background:var(--p-danger)"></span>{{ $testimonials->where('status',0)->count() }} Inactive</span>
          <span class="stat-pill"><span class="dot" style="background:var(--p-rose)"></span>{{ $testimonials->total() }} Total</span>
        </div>
      </div>
      <button class="tm-add-btn" onclick="openAddModal()">
        <i class="fas fa-plus"></i> Add Testimonial
      </button>
    </div>
  </div>

  {{-- TABLE --}}
  <div class="tm-card">
    <div class="table-responsive">
      <table class="tm-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Person</th>
            <th>Rating</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($testimonials as $key => $t)
          <tr>
            <td><span class="tm-serial">{{ str_pad($testimonials->firstItem()+$key,2,'0',STR_PAD_LEFT) }}</span></td>

            <td>
              <div class="tm-person">
                @if($t->image)
                  <img src="{{ asset('uploads/testimonials/'.$t->image) }}" class="tm-avatar-img">
                @else
                  <div class="tm-avatar-txt">{{ strtoupper(substr($t->name,0,1)) }}</div>
                @endif
                <div>
                  <div class="tm-person-name">{{ $t->name }}</div>
                  <div class="tm-person-desg">{{ $t->designation ?? 'Customer' }}</div>
                </div>
              </div>
            </td>

            <td>
              <div class="tm-stars">
                @for($i=1;$i<=5;$i++)
                  <i class="fas fa-star {{ $i<=$t->rating ? 'tm-star-fill' : 'tm-star-empty' }}"></i>
                @endfor
              </div>
            </td>

            <td>
              @if($t->status)
                <span class="tm-badge tm-badge-active"><i class="fas fa-circle" style="font-size:.4rem;"></i>Active</span>
              @else
                <span class="tm-badge tm-badge-inactive"><i class="fas fa-circle" style="font-size:.4rem;"></i>Inactive</span>
              @endif
            </td>

            <td>
              <div class="tm-actions">
                <button class="tm-btn-view"
                  onclick="openViewModal({
                    name:   {{ json_encode($t->name) }},
                    desg:   {{ json_encode($t->designation ?? 'Customer') }},
                    msg:    {{ json_encode($t->message) }},
                    rating: {{ $t->rating }},
                    status: {{ $t->status }},
                    image:  '{{ $t->image ? asset('uploads/testimonials/'.$t->image) : '' }}'
                  })">
                  <i class="fas fa-eye"></i> View
                </button>
                <button class="tm-btn-del"
                  onclick="openDeleteModal('{{ route('admin.testimonials.delete',$t->id) }}', {{ json_encode($t->name) }})">
                  <i class="fas fa-trash-alt"></i>
                </button>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5">
              <div class="tm-empty">
                <i class="fas fa-comment-slash"></i>
                <p>No testimonials yet. Add your first one!</p>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($testimonials->hasPages())
      <div class="pagination">{{ $testimonials->links() }}</div>
    @endif
  </div>

</div>

{{-- ══════════════════════════
     ADD MODAL
══════════════════════════ --}}
<div class="tm-overlay" id="addModal">
  <div class="tm-modal">
    <div class="tm-modal-header">
      <div class="tm-modal-title"><i class="fas fa-plus-circle" style="color:var(--p-rose);"></i> Add Testimonial</div>
      <button class="tm-modal-close" onclick="closeModal('addModal')"><i class="fas fa-times"></i></button>
    </div>
    <form method="POST" action="{{ route('admin.testimonials.store') }}" enctype="multipart/form-data">
      @csrf
      <div class="tm-modal-body">

        <div class="tf-row">
          <div class="tf-field">
            <label>Full Name <span class="req">*</span></label>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. John Doe">
            @error('name')<span class="err">{{ $message }}</span>@enderror
          </div>
          <div class="tf-field">
            <label>Designation</label>
            <input type="text" name="designation" value="{{ old('designation') }}" placeholder="e.g. Travel Blogger">
          </div>
        </div>

        <div class="tf-field">
          <label>Rating <span class="req">*</span></label>
          <div class="tf-star-row">
            @for($i=5;$i>=1;$i--)
            <div class="tf-star-opt">
              <input type="radio" name="rating" id="r{{ $i }}" value="{{ $i }}" {{ old('rating',5)==$i?'checked':'' }}>
              <label for="r{{ $i }}">
                @for($s=1;$s<=$i;$s++)<i class="fas fa-star" style="color:#fbbf24;font-size:.7rem;"></i>@endfor
                {{ $i }}
              </label>
            </div>
            @endfor
          </div>
          @error('rating')<span class="err">{{ $message }}</span>@enderror
        </div>

        <div class="tf-field">
          <label>Message <span class="req">*</span></label>
          <textarea name="message" rows="4" placeholder="What did they say?">{{ old('message') }}</textarea>
          @error('message')<span class="err">{{ $message }}</span>@enderror
        </div>

        <div class="tf-row">
          <div class="tf-field">
            <label>Status</label>
            <div class="tf-status-row">
              <div class="tf-status-opt">
                <input type="radio" name="status" id="st1" value="1" {{ old('status','1')=='1'?'checked':'' }}>
                <label for="st1" class="lbl-on"><i class="fas fa-eye"></i> Active</label>
              </div>
              <div class="tf-status-opt">
                <input type="radio" name="status" id="st0" value="0" {{ old('status')=='0'?'checked':'' }}>
                <label for="st0" class="lbl-off"><i class="fas fa-eye-slash"></i> Inactive</label>
              </div>
            </div>
          </div>
          <div class="tf-field">
            <label>Photo</label>
            <div class="tf-upload" id="tfUploadZone">
              <input type="file" name="image" id="tfImgInput" accept="image/*" onchange="previewTfImg(this)">
              <i class="fas fa-user-circle"></i>
              <span id="tfUploadLabel">Upload photo</span>
            </div>
            <img id="tfPreview" class="tf-preview" src="" alt="">
            @error('image')<span class="err" style="display:block;margin-top:5px;">{{ $message }}</span>@enderror
          </div>
        </div>

      </div>
      <div class="tm-modal-footer" style="display:block;padding:14px 26px 20px;">
        <div style="display:flex;gap:10px;">
          <button type="button" class="tm-btn tm-btn-outline" style="flex:0 0 auto;" onclick="closeModal('addModal')">Cancel</button>
          <button type="submit" class="tf-submit"><i class="fas fa-save"></i> Save Testimonial</button>
        </div>
      </div>
    </form>
  </div>
</div>

{{-- ══════════════════════════
     VIEW MODAL
══════════════════════════ --}}
<div class="tm-overlay" id="viewModal">
  <div class="tm-modal">
    <div class="tm-modal-header">
      <div class="tm-modal-title"><i class="fas fa-quote-right" style="color:var(--p-rose);"></i> Testimonial Details</div>
      <button class="tm-modal-close" onclick="closeModal('viewModal')"><i class="fas fa-times"></i></button>
    </div>
    <div class="tm-modal-body">
      <div class="tm-view-profile">
        <div id="vt-avatar-wrap"></div>
        <div>
          <div class="tm-view-name" id="vt-name">—</div>
          <div class="tm-view-desg" id="vt-desg">—</div>
          <div class="tm-view-stars" id="vt-stars"></div>
        </div>
        <div style="margin-left:auto;" id="vt-status-wrap"></div>
      </div>
      <div class="tm-sec"><i class="fas fa-comment"></i> Message</div>
      <div class="tm-view-msg" id="vt-msg">—</div>
    </div>
    <div class="tm-modal-footer">
      <button class="tm-btn tm-btn-outline" onclick="closeModal('viewModal')">Close</button>
    </div>
  </div>
</div>

{{-- ══════════════════════════
     DELETE MODAL
══════════════════════════ --}}
<div class="tm-overlay" id="deleteModal">
  <div class="tm-modal" style="max-width:400px;">
    <div class="tm-del-body">
      <div class="tm-del-icon"><i class="fas fa-comment-slash"></i></div>
      <h5>Delete Testimonial?</h5>
      <p><strong id="del-tm-name" style="color:#fda4af;display:block;margin-bottom:6px;"></strong>This review will be permanently removed.</p>
    </div>
    <div class="tm-del-footer">
      <button class="tm-btn tm-btn-outline" onclick="closeModal('deleteModal')"><i class="fas fa-times"></i> Cancel</button>
      <form id="deleteForm" method="POST" style="display:inline;">
        @csrf
        <button type="submit" class="tm-btn tm-btn-danger"><i class="fas fa-trash-alt"></i> Yes, Delete</button>
      </form>
    </div>
  </div>
</div>

<div id="tm-toast"></div>

<script>
function openModal(id)  { document.getElementById(id).classList.add('open');    document.body.style.overflow='hidden'; }
function closeModal(id) { document.getElementById(id).classList.remove('open'); document.body.style.overflow=''; }
document.querySelectorAll('.tm-overlay').forEach(function(el){
  el.addEventListener('click',function(e){ if(e.target===el) closeModal(el.id); });
});
document.addEventListener('keydown',function(e){
  if(e.key==='Escape') document.querySelectorAll('.tm-overlay.open').forEach(function(el){ closeModal(el.id); });
});

function openAddModal() { openModal('addModal'); }

function previewTfImg(input) {
  if(!input.files||!input.files[0]) return;
  var img=document.getElementById('tfPreview');
  img.src=URL.createObjectURL(input.files[0]);
  img.style.display='block';
  document.getElementById('tfUploadLabel').textContent=input.files[0].name;
  document.getElementById('tfUploadZone').style.borderColor='var(--p-rose)';
}

function openViewModal(d) {
  document.getElementById('vt-name').textContent = d.name;
  document.getElementById('vt-desg').textContent = d.desg;
  document.getElementById('vt-msg').textContent  = d.msg;

  // avatar
  var aw = document.getElementById('vt-avatar-wrap');
  if(d.image){
    aw.innerHTML = '<img src="'+d.image+'" class="tm-view-avatar">';
  } else {
    aw.innerHTML = '<div class="tm-view-avatar-txt">'+d.name.charAt(0).toUpperCase()+'</div>';
  }

  // stars
  var starsHtml = '';
  for(var i=1;i<=5;i++){
    starsHtml += '<i class="fas fa-star'+(i<=d.rating?'':' empty')+'"></i>';
  }
  document.getElementById('vt-stars').innerHTML = starsHtml;

  // status
  var sw = document.getElementById('vt-status-wrap');
  sw.innerHTML = d.status
    ? '<span class="tm-badge tm-badge-active"><i class="fas fa-circle" style="font-size:.4rem;"></i>Active</span>'
    : '<span class="tm-badge tm-badge-inactive"><i class="fas fa-circle" style="font-size:.4rem;"></i>Inactive</span>';

  openModal('viewModal');
}

function openDeleteModal(action, name) {
  document.getElementById('del-tm-name').textContent = name;
  document.getElementById('deleteForm').action = action;
  openModal('deleteModal');
}

function showToast(type,title,msg) {
  var c=document.getElementById('tm-toast'), t=document.createElement('div');
  var icon=type==='s'?'fas fa-check-circle':'fas fa-exclamation-circle';
  t.className='tm-toast tm-toast-'+type;
  t.innerHTML='<div class="tm-toast-icon"><i class="'+icon+'"></i></div><div><div class="tm-toast-title">'+title+'</div><div class="tm-toast-msg">'+msg+'</div></div><span class="tm-toast-bar"></span>';
  c.appendChild(t);
  setTimeout(function(){ t.classList.add('show'); },20);
  setTimeout(function(){ t.classList.remove('show'); setTimeout(function(){ t.remove(); },400); },3500);
}
(function(){
  var s=document.getElementById('flash-s'), e=document.getElementById('flash-e');
  if(s) showToast('s','Success',s.dataset.msg);
  if(e) showToast('d','Error',e.dataset.msg);
  @if($errors->any()) openAddModal(); @endif
})();
</script>

@endsection