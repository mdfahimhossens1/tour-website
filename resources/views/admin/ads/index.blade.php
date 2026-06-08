@extends('layouts.admin')
@section('title', 'Ads Management')
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
  --p-text:      #e2e8f0;
  --p-muted:     #64748b;
  --p-radius:    14px;
  --p-radius-sm: 8px;
  --p-shadow:    0 8px 32px rgba(0,0,0,.45);
  --p-cyan:      #06b6d4;
  --p-cyan2:     #22d3ee;
}
.ad-wrap { font-family:'Plus Jakarta Sans',sans-serif; color:var(--p-text); }

/* HEADER */
.ad-header {
  background:linear-gradient(135deg,#0c1a2e 0%,#0c2a2e 55%,#0c1a2e 100%);
  border-radius:var(--p-radius); padding:28px 32px; margin-bottom:24px;
  position:relative; overflow:hidden; box-shadow:var(--p-shadow);
}
.ad-header::before {
  content:''; position:absolute; inset:0;
  background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%2306b6d4' fill-opacity='0.05'%3E%3Cpath d='M30 5L55 20v20L30 55 5 40V20Z'/%3E%3C/g%3E%3C/svg%3E");
}
.ad-header::after {
  content:''; position:absolute; right:-40px; top:-40px; width:200px; height:200px;
  border-radius:50%; background:radial-gradient(circle,rgba(6,182,212,.2) 0%,transparent 70%);
}
.ad-header .title {
  font-size:1.5rem; font-weight:700; position:relative; z-index:1;
  background:linear-gradient(90deg,#fff,#67e8f9);
  -webkit-background-clip:text; -webkit-text-fill-color:transparent;
}
.ad-header .subtitle { color:rgba(255,255,255,.45); font-size:.85rem; margin-top:4px; position:relative; z-index:1; }
.stat-pill {
  display:inline-flex; align-items:center; gap:8px;
  background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.1);
  border-radius:40px; padding:6px 16px; font-size:.8rem; font-weight:600; color:#fff; position:relative; z-index:1;
}
.stat-pill .dot { width:8px; height:8px; border-radius:50%; }
.ad-add-btn {
  background:linear-gradient(135deg,var(--p-cyan),#0891b2); color:#fff; border:none;
  border-radius:10px; padding:10px 20px; font-family:'Plus Jakarta Sans',sans-serif;
  font-weight:700; font-size:.85rem; cursor:pointer;
  display:inline-flex; align-items:center; gap:8px;
  transition:all .2s; position:relative; z-index:1;
}
.ad-add-btn:hover { transform:translateY(-2px); box-shadow:0 6px 18px rgba(6,182,212,.4); }

/* CARD */
.ad-card {
  background:var(--p-surface); border:1px solid var(--p-border);
  border-radius:var(--p-radius); box-shadow:var(--p-shadow); overflow:hidden;
}

/* TABLE */
.ad-table { width:100%; border-collapse:collapse; }
.ad-table thead tr { background:var(--p-surface2); border-bottom:1px solid var(--p-border); }
.ad-table th {
  font-size:.7rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase;
  color:var(--p-muted); padding:13px 18px; text-align:left;
}
.ad-table td {
  padding:13px 18px; border-bottom:1px solid var(--p-border);
  font-size:.875rem; color:var(--p-text); vertical-align:middle;
}
.ad-table tbody tr:last-child td { border-bottom:none; }
.ad-table tbody tr { transition:background .15s; }
.ad-table tbody tr:hover { background:rgba(255,255,255,.02); }

.ad-serial { font-family:'JetBrains Mono',monospace; font-size:.78rem; color:var(--p-muted); }
.ad-thumb  { width:72px; height:46px; object-fit:cover; border-radius:7px; border:1px solid var(--p-border); }
.ad-no-img { width:72px; height:46px; background:var(--p-surface2); border:1px solid var(--p-border); border-radius:7px; display:flex; align-items:center; justify-content:center; color:var(--p-muted); font-size:.7rem; }

.ad-title { font-weight:600; font-size:.88rem; }

.ad-pos-badge {
  display:inline-flex; align-items:center; gap:5px;
  background:rgba(6,182,212,.1); color:var(--p-cyan2);
  border:1px solid rgba(6,182,212,.2); border-radius:20px;
  padding:3px 10px; font-size:.72rem; font-weight:600;
}

.ad-stat { display:flex; align-items:center; gap:6px; font-size:.82rem; }
.ad-stat i { font-size:.68rem; }

.ad-badge { display:inline-flex; align-items:center; gap:5px; padding:3px 10px; border-radius:20px; font-size:.72rem; font-weight:700; }
.ad-badge-active   { background:rgba(34,197,94,.12); color:#86efac; border:1px solid rgba(34,197,94,.25); }
.ad-badge-inactive { background:rgba(239,68,68,.12); color:#fca5a5; border:1px solid rgba(239,68,68,.25); }

.ad-actions { display:flex; gap:6px; }
.ad-btn-view { display:inline-flex; align-items:center; gap:5px; background:rgba(6,182,212,.1); color:var(--p-cyan2); border:1px solid rgba(6,182,212,.2); border-radius:6px; padding:5px 11px; font-size:.78rem; font-weight:600; cursor:pointer; transition:all .2s; font-family:'Plus Jakarta Sans',sans-serif; }
.ad-btn-view:hover { background:rgba(6,182,212,.2); transform:translateY(-1px); }
.ad-btn-del  { display:inline-flex; align-items:center; gap:5px; background:rgba(239,68,68,.1); color:#fca5a5; border:1px solid rgba(239,68,68,.2); border-radius:6px; padding:5px 11px; font-size:.78rem; font-weight:600; cursor:pointer; transition:all .2s; font-family:'Plus Jakarta Sans',sans-serif; }
.ad-btn-del:hover  { background:rgba(239,68,68,.2); transform:translateY(-1px); }

.ad-empty { text-align:center; padding:70px 20px; color:var(--p-muted); }
.ad-empty i { font-size:2.5rem; margin-bottom:14px; opacity:.3; display:block; }

.pagination { padding:14px 18px; }
.pagination .page-item .page-link { background:var(--p-surface2); border:1px solid var(--p-border); color:var(--p-muted); font-size:.82rem; border-radius:6px !important; margin:0 2px; transition:all .2s; }
.pagination .page-item .page-link:hover { background:var(--p-border); color:var(--p-text); }
.pagination .page-item.active .page-link { background:var(--p-cyan); border-color:var(--p-cyan); color:#fff; font-weight:700; }

/* MODAL */
.ad-overlay {
  position:fixed; inset:0; z-index:9999;
  background:rgba(0,0,0,.76); backdrop-filter:blur(6px);
  display:flex; align-items:center; justify-content:center;
  opacity:0; pointer-events:none; transition:opacity .25s;
}
.ad-overlay.open { opacity:1; pointer-events:auto; }
.ad-modal {
  background:var(--p-surface); border:1px solid var(--p-border);
  border-radius:18px; width:min(620px,96vw); max-height:90vh; overflow-y:auto;
  box-shadow:0 24px 64px rgba(0,0,0,.7);
  transform:translateY(24px) scale(.97);
  transition:transform .3s cubic-bezier(.34,1.56,.64,1);
}
.ad-overlay.open .ad-modal { transform:translateY(0) scale(1); }
.ad-modal::-webkit-scrollbar { width:5px; }
.ad-modal::-webkit-scrollbar-thumb { background:var(--p-border); border-radius:10px; }
.ad-modal-header {
  padding:20px 26px 16px; border-bottom:1px solid var(--p-border);
  display:flex; align-items:center; justify-content:space-between;
  background:var(--p-surface2); position:sticky; top:0; z-index:2;
}
.ad-modal-title { font-size:1.05rem; font-weight:700; display:flex; align-items:center; gap:9px; }
.ad-modal-close { background:var(--p-surface); border:1px solid var(--p-border); color:var(--p-muted); width:30px; height:30px; border-radius:7px; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:all .2s; font-size:.85rem; }
.ad-modal-close:hover { color:var(--p-text); }
.ad-modal-body   { padding:24px 26px; }
.ad-modal-footer { padding:14px 26px 20px; border-top:1px solid var(--p-border); display:flex; gap:10px; justify-content:flex-end; background:rgba(0,0,0,.1); }

/* FORM */
.af-field { margin-bottom:16px; }
.af-field label { display:block; font-size:.73rem; font-weight:700; letter-spacing:.07em; text-transform:uppercase; color:var(--p-muted); margin-bottom:7px; }
.af-field label .req { color:var(--p-danger); }
.af-field input,.af-field select {
  width:100%; background:var(--p-surface2); border:1px solid var(--p-border);
  border-radius:var(--p-radius-sm); padding:10px 14px; color:var(--p-text);
  font-family:'Plus Jakarta Sans',sans-serif; font-size:.875rem; outline:none;
  transition:border-color .2s,box-shadow .2s; box-sizing:border-box;
}
.af-field input:focus,.af-field select:focus { border-color:var(--p-cyan); box-shadow:0 0 0 3px rgba(6,182,212,.12); }
.af-field select option { background:#1a1d27; }
.af-field .err { color:#fca5a5; font-size:.76rem; margin-top:5px; display:block; }
.af-row { display:grid; grid-template-columns:1fr 1fr; gap:14px; }

/* upload */
.af-upload { border:2px dashed var(--p-border); border-radius:10px; padding:20px; text-align:center; cursor:pointer; transition:all .2s; position:relative; background:var(--p-surface2); }
.af-upload:hover { border-color:var(--p-cyan); background:rgba(6,182,212,.05); }
.af-upload input { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; }
.af-upload i { font-size:1.4rem; color:var(--p-muted); display:block; margin-bottom:6px; }
.af-upload span { font-size:.8rem; color:var(--p-muted); }
.af-preview { display:none; width:100%; height:120px; object-fit:cover; border-radius:9px; border:1px solid var(--p-border); margin-top:10px; }

/* status toggle */
.af-status-row { display:flex; gap:10px; }
.af-status-opt { flex:1; }
.af-status-opt input[type="radio"] { display:none; }
.af-status-opt label { display:flex; align-items:center; justify-content:center; gap:7px; padding:9px; border-radius:8px; border:1px solid var(--p-border); cursor:pointer; font-size:.83rem; font-weight:600; transition:all .2s; background:var(--p-surface2); color:var(--p-muted); text-transform:none; letter-spacing:0; }
.af-status-opt input:checked + label.lbl-on  { background:rgba(34,197,94,.12); color:#86efac; border-color:rgba(34,197,94,.3); }
.af-status-opt input:checked + label.lbl-off { background:rgba(239,68,68,.12); color:#fca5a5; border-color:rgba(239,68,68,.3); }

.af-submit { width:100%; padding:11px; border:none; border-radius:var(--p-radius-sm); background:linear-gradient(135deg,var(--p-cyan),#0891b2); color:#fff; font-family:'Plus Jakarta Sans',sans-serif; font-weight:700; font-size:.9rem; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; transition:all .2s; }
.af-submit:hover { transform:translateY(-2px); box-shadow:0 6px 18px rgba(6,182,212,.35); }

/* VIEW modal */
.av-cover { width:100%; max-height:200px; object-fit:cover; border-radius:12px; border:1px solid var(--p-border); margin-bottom:18px; }
.av-grid  { display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:4px; }
.av-item label { font-size:.69rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:var(--p-muted); display:block; margin-bottom:4px; }
.av-item .val  { font-size:.875rem; color:var(--p-text); font-weight:500; }
.av-item .mono { font-family:'JetBrains Mono',monospace; font-size:.8rem; color:var(--p-cyan2); word-break:break-all; }
.av-stat-box { display:flex; gap:12px; background:var(--p-surface2); border:1px solid var(--p-border); border-radius:10px; padding:14px 18px; margin-top:14px; }
.av-stat-box .sv { flex:1; text-align:center; }
.av-stat-box .sv .num { font-size:1.4rem; font-weight:700; color:var(--p-cyan2); font-family:'JetBrains Mono',monospace; }
.av-stat-box .sv .lbl { font-size:.72rem; color:var(--p-muted); text-transform:uppercase; letter-spacing:.06em; margin-top:2px; }
.av-sec { font-size:.69rem; font-weight:700; letter-spacing:.09em; text-transform:uppercase; color:var(--p-cyan); margin:16px 0 10px; display:flex; align-items:center; gap:8px; }
.av-sec::after { content:''; flex:1; height:1px; background:var(--p-border); }

/* DELETE modal */
.ad-del-body   { text-align:center; padding:32px 28px 16px; }
.ad-del-icon   { width:64px; height:64px; border-radius:50%; background:rgba(239,68,68,.12); border:2px solid rgba(239,68,68,.25); display:flex; align-items:center; justify-content:center; font-size:1.5rem; color:#fca5a5; margin:0 auto 16px; }
.ad-del-body h5 { font-weight:700; margin-bottom:8px; color:var(--p-text); }
.ad-del-body p  { color:var(--p-muted); font-size:.875rem; line-height:1.6; margin:0; }
.ad-del-footer  { padding:14px 28px 24px; display:flex; gap:10px; justify-content:center; }

.ad-btn { display:inline-flex; align-items:center; gap:7px; border:none; cursor:pointer; font-family:'Plus Jakarta Sans',sans-serif; font-weight:600; border-radius:var(--p-radius-sm); transition:all .2s; font-size:.85rem; padding:9px 20px; }
.ad-btn-outline { background:transparent; color:var(--p-text); border:1px solid var(--p-border); }
.ad-btn-outline:hover { background:var(--p-surface2); }
.ad-btn-danger  { background:var(--p-danger); color:#fff; border:none; }
.ad-btn-danger:hover { background:#dc2626; transform:translateY(-1px); }

/* TOAST */
#ad-toast { position:fixed; bottom:28px; right:28px; z-index:99999; display:flex; flex-direction:column; gap:10px; }
.ad-toast { display:flex; align-items:center; gap:12px; background:var(--p-surface); border:1px solid var(--p-border); border-radius:12px; padding:14px 18px; min-width:260px; box-shadow:0 8px 30px rgba(0,0,0,.5); transform:translateX(120%); transition:transform .35s cubic-bezier(.34,1.56,.64,1); font-family:'Plus Jakarta Sans',sans-serif; position:relative; overflow:hidden; }
.ad-toast.show { transform:translateX(0); }
.ad-toast-icon { width:34px; height:34px; border-radius:9px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.ad-toast-s .ad-toast-icon { background:rgba(34,197,94,.15); color:var(--p-success); }
.ad-toast-d .ad-toast-icon { background:rgba(239,68,68,.15); color:var(--p-danger); }
.ad-toast-title { font-size:.875rem; font-weight:700; color:var(--p-text); }
.ad-toast-msg   { font-size:.77rem; color:var(--p-muted); margin-top:1px; }
.ad-toast-bar   { position:absolute; bottom:0; left:0; height:3px; border-radius:0 0 12px 12px; animation:adBar 3.2s linear forwards; }
.ad-toast-s .ad-toast-bar { background:var(--p-success); }
.ad-toast-d .ad-toast-bar { background:var(--p-danger); }
@keyframes adBar { from{width:100%} to{width:0%} }
</style>

@if(session('success'))<div id="flash-s" data-msg="{{ session('success') }}"></div>@endif
@if(session('error'))<div id="flash-e"   data-msg="{{ session('error') }}"></div>@endif

<div class="ad-wrap">

  {{-- HEADER --}}
  <div class="ad-header">
    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;">
      <div>
        <div class="title"><i class="fas fa-ad me-2"></i>Advertisements</div>
        <div class="subtitle">Manage banners, positions and ad campaigns</div>
        <div style="display:flex;gap:8px;margin-top:14px;flex-wrap:wrap;">
          <span class="stat-pill"><span class="dot" style="background:var(--p-success)"></span>{{ $ads->where('status',1)->count() }} Active</span>
          <span class="stat-pill"><span class="dot" style="background:var(--p-danger)"></span>{{ $ads->where('status',0)->count() }} Inactive</span>
          <span class="stat-pill"><span class="dot" style="background:var(--p-cyan)"></span>{{ $ads->total() }} Total</span>
        </div>
      </div>
      <button class="ad-add-btn" onclick="openAddModal()">
        <i class="fas fa-plus"></i> Add Advertisement
      </button>
    </div>
  </div>

  {{-- TABLE --}}
  <div class="ad-card">
    <div class="table-responsive">
      <table class="ad-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Image</th>
            <th>Title</th>
            <th>Position</th>
            <th>Views</th>
            <th>Clicks</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($ads as $key => $ad)
          <tr>
            <td><span class="ad-serial">{{ str_pad($ads->firstItem()+$key,2,'0',STR_PAD_LEFT) }}</span></td>
            <td>
              @if($ad->image)
                <img src="{{ asset('uploads/ads/'.$ad->image) }}" class="ad-thumb">
              @else
                <div class="ad-no-img"><i class="fas fa-image"></i></div>
              @endif
            </td>
            <td><span class="ad-title">{{ $ad->title }}</span></td>
            <td>
              <span class="ad-pos-badge">
                <i class="fas fa-map-pin" style="font-size:.55rem;"></i>
                {{ str_replace('_',' ',ucwords($ad->position,'_')) }}
              </span>
            </td>
            <td>
              <span class="ad-stat"><i class="fas fa-eye" style="color:var(--p-cyan);"></i>{{ number_format($ad->views) }}</span>
            </td>
            <td>
              <span class="ad-stat"><i class="fas fa-mouse-pointer" style="color:var(--p-warning);"></i>{{ number_format($ad->clicks) }}</span>
            </td>
            <td>
              @if($ad->status)
                <span class="ad-badge ad-badge-active"><i class="fas fa-circle" style="font-size:.4rem;"></i>Active</span>
              @else
                <span class="ad-badge ad-badge-inactive"><i class="fas fa-circle" style="font-size:.4rem;"></i>Inactive</span>
              @endif
            </td>
            <td>
              <div class="ad-actions">
                <button class="ad-btn-view"
                  onclick="openViewModal({
                    title:    {{ json_encode($ad->title) }},
                    position: '{{ $ad->position }}',
                    link:     {{ json_encode($ad->link ?? '') }},
                    views:    '{{ number_format($ad->views) }}',
                    clicks:   '{{ number_format($ad->clicks) }}',
                    status:   {{ $ad->status }},
                    start:    '{{ $ad->start_date ?? '' }}',
                    end:      '{{ $ad->end_date ?? '' }}',
                    image:    '{{ $ad->image ? asset('uploads/ads/'.$ad->image) : '' }}'
                  })">
                  <i class="fas fa-eye"></i> View
                </button>
                <button class="ad-btn-del"
                  onclick="openDeleteModal('{{ route('admin.ads.delete',$ad->id) }}', {{ json_encode($ad->title) }})">
                  <i class="fas fa-trash-alt"></i>
                </button>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="8">
              <div class="ad-empty">
                <i class="fas fa-ad"></i>
                <p>No advertisements yet. Click <strong>Add Advertisement</strong> to get started.</p>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($ads->hasPages())
      <div class="pagination">{{ $ads->links() }}</div>
    @endif
  </div>

</div>

{{-- ══════════════════════════════
     ADD MODAL
══════════════════════════════ --}}
<div class="ad-overlay" id="addModal">
  <div class="ad-modal">
    <div class="ad-modal-header">
      <div class="ad-modal-title"><i class="fas fa-plus-circle" style="color:var(--p-cyan);"></i> Create Advertisement</div>
      <button class="ad-modal-close" onclick="closeModal('addModal')"><i class="fas fa-times"></i></button>
    </div>
    <form method="POST" action="{{ route('admin.ads.store') }}" enctype="multipart/form-data">
      @csrf
      <div class="ad-modal-body">

        <div class="af-row">
          <div class="af-field" style="grid-column:1/-1;">
            <label>Advertisement Title <span class="req">*</span></label>
            <input type="text" name="title" value="{{ old('title') }}" placeholder="e.g. Summer Sale Banner">
            @error('title')<span class="err">{{ $message }}</span>@enderror
          </div>
        </div>

        <div class="af-row">
          <div class="af-field">
            <label>Position <span class="req">*</span></label>
            <select name="position">
              <option value="homepage_banner" {{ old('position')=='homepage_banner'?'selected':'' }}>Homepage Banner</option>
              <option value="sidebar"         {{ old('position')=='sidebar'?'selected':'' }}>Sidebar</option>
              <option value="package_page"    {{ old('position')=='package_page'?'selected':'' }}>Package Page</option>
              <option value="footer"          {{ old('position')=='footer'?'selected':'' }}>Footer</option>
            </select>
          </div>
          <div class="af-field">
            <label>Target Link</label>
            <input type="url" name="link" value="{{ old('link') }}" placeholder="https://example.com">
          </div>
        </div>

        <div class="af-row">
          <div class="af-field">
            <label>Start Date</label>
            <input type="date" name="start_date" value="{{ old('start_date') }}">
          </div>
          <div class="af-field">
            <label>End Date</label>
            <input type="date" name="end_date" value="{{ old('end_date') }}">
          </div>
        </div>

        <div class="af-field">
          <label>Status</label>
          <div class="af-status-row">
            <div class="af-status-opt">
              <input type="radio" name="status" id="ads1" value="1" {{ old('status','1')=='1'?'checked':'' }}>
              <label for="ads1" class="lbl-on"><i class="fas fa-eye"></i> Active</label>
            </div>
            <div class="af-status-opt">
              <input type="radio" name="status" id="ads0" value="0" {{ old('status')=='0'?'checked':'' }}>
              <label for="ads0" class="lbl-off"><i class="fas fa-eye-slash"></i> Inactive</label>
            </div>
          </div>
        </div>

        <div class="af-field" style="margin-bottom:0;">
          <label>Advertisement Image <span class="req">*</span></label>
          <div class="af-upload" id="afUploadZone">
            <input type="file" name="image" id="afImgInput" accept="image/*" onchange="previewAdImg(this)">
            <i class="fas fa-cloud-upload-alt"></i>
            <span id="afUploadLabel">Click to upload banner image</span>
          </div>
          <img id="afPreview" class="af-preview" src="" alt="">
          @error('image')<span class="err" style="display:block;margin-top:5px;">{{ $message }}</span>@enderror
        </div>

      </div>
      <div class="ad-modal-footer" style="display:block;padding:14px 26px 20px;">
        <div style="display:flex;gap:10px;">
          <button type="button" class="ad-btn ad-btn-outline" style="flex:0 0 auto;" onclick="closeModal('addModal')">Cancel</button>
          <button type="submit" class="af-submit"><i class="fas fa-save"></i> Save Advertisement</button>
        </div>
      </div>
    </form>
  </div>
</div>

{{-- ══════════════════════════════
     VIEW MODAL
══════════════════════════════ --}}
<div class="ad-overlay" id="viewModal">
  <div class="ad-modal">
    <div class="ad-modal-header">
      <div class="ad-modal-title"><i class="fas fa-eye" style="color:var(--p-cyan);"></i> Ad Details</div>
      <button class="ad-modal-close" onclick="closeModal('viewModal')"><i class="fas fa-times"></i></button>
    </div>
    <div class="ad-modal-body">
      <img id="av-cover" src="" alt="" class="av-cover" style="display:none;">
      <div class="av-grid">
        <div class="av-item" style="grid-column:1/-1;">
          <label>Title</label>
          <div class="val" id="av-title" style="font-size:1rem;font-weight:700;"></div>
        </div>
        <div class="av-item">
          <label>Position</label>
          <div class="val" id="av-position"></div>
        </div>
        <div class="av-item">
          <label>Status</label>
          <div class="val" id="av-status"></div>
        </div>
        <div class="av-item" id="av-link-wrap">
          <label>Target Link</label>
          <div class="val mono" id="av-link"></div>
        </div>
        <div class="av-item" id="av-date-wrap">
          <label>Campaign Period</label>
          <div class="val" id="av-dates"></div>
        </div>
      </div>
      <div class="av-stat-box">
        <div class="sv">
          <div class="num" id="av-views">0</div>
          <div class="lbl"><i class="fas fa-eye" style="margin-right:4px;color:var(--p-cyan);"></i>Total Views</div>
        </div>
        <div style="width:1px;background:var(--p-border);"></div>
        <div class="sv">
          <div class="num" id="av-clicks">0</div>
          <div class="lbl"><i class="fas fa-mouse-pointer" style="margin-right:4px;color:var(--p-warning);"></i>Total Clicks</div>
        </div>
        <div style="width:1px;background:var(--p-border);"></div>
        <div class="sv">
          <div class="num" id="av-ctr">0%</div>
          <div class="lbl">CTR</div>
        </div>
      </div>
    </div>
    <div class="ad-modal-footer">
      <button class="ad-btn ad-btn-outline" onclick="closeModal('viewModal')">Close</button>
    </div>
  </div>
</div>

{{-- ══════════════════════════════
     DELETE MODAL
══════════════════════════════ --}}
<div class="ad-overlay" id="deleteModal">
  <div class="ad-modal" style="max-width:400px;">
    <div class="ad-del-body">
      <div class="ad-del-icon"><i class="fas fa-ad"></i></div>
      <h5>Delete Advertisement?</h5>
      <p><strong id="del-ad-title" style="color:var(--p-cyan2);display:block;margin-bottom:6px;"></strong>This ad will be permanently deleted and cannot be recovered.</p>
    </div>
    <div class="ad-del-footer">
      <button class="ad-btn ad-btn-outline" onclick="closeModal('deleteModal')"><i class="fas fa-times"></i> Cancel</button>
      <form id="deleteForm" method="POST" style="display:inline;">
        @csrf
        <button type="submit" class="ad-btn ad-btn-danger"><i class="fas fa-trash-alt"></i> Yes, Delete</button>
      </form>
    </div>
  </div>
</div>

<div id="ad-toast"></div>

<script>
function openModal(id)  { document.getElementById(id).classList.add('open');    document.body.style.overflow='hidden'; }
function closeModal(id) { document.getElementById(id).classList.remove('open'); document.body.style.overflow=''; }
document.querySelectorAll('.ad-overlay').forEach(function(el){
  el.addEventListener('click',function(e){ if(e.target===el) closeModal(el.id); });
});
document.addEventListener('keydown',function(e){
  if(e.key==='Escape') document.querySelectorAll('.ad-overlay.open').forEach(function(el){ closeModal(el.id); });
});

function openAddModal() { openModal('addModal'); }

function previewAdImg(input) {
  if(!input.files||!input.files[0]) return;
  var img=document.getElementById('afPreview');
  img.src=URL.createObjectURL(input.files[0]);
  img.style.display='block';
  document.getElementById('afUploadLabel').textContent=input.files[0].name;
  document.getElementById('afUploadZone').style.borderColor='var(--p-cyan)';
}

function openViewModal(d) {
  document.getElementById('av-title').textContent    = d.title;
  document.getElementById('av-views').textContent    = d.views;
  document.getElementById('av-clicks').textContent   = d.clicks;

  // CTR
  var v=parseInt(d.views.replace(/,/g,''))||0, c=parseInt(d.clicks.replace(/,/g,''))||0;
  document.getElementById('av-ctr').textContent = v>0 ? ((c/v)*100).toFixed(1)+'%' : '0%';

  // position
  document.getElementById('av-position').innerHTML =
    '<span class="ad-pos-badge"><i class="fas fa-map-pin" style="font-size:.55rem;"></i> '+
    d.position.replace(/_/g,' ').replace(/\b\w/g,function(l){return l.toUpperCase()})+'</span>';

  // status
  document.getElementById('av-status').innerHTML = d.status
    ? '<span class="ad-badge ad-badge-active"><i class="fas fa-circle" style="font-size:.4rem;"></i>Active</span>'
    : '<span class="ad-badge ad-badge-inactive"><i class="fas fa-circle" style="font-size:.4rem;"></i>Inactive</span>';

  // link
  var linkWrap=document.getElementById('av-link-wrap');
  if(d.link){ document.getElementById('av-link').textContent=d.link; linkWrap.style.display='block'; } else { linkWrap.style.display='none'; }

  // dates
  var dateWrap=document.getElementById('av-date-wrap');
  if(d.start||d.end){ document.getElementById('av-dates').textContent=(d.start||'—')+' → '+(d.end||'—'); dateWrap.style.display='block'; } else { dateWrap.style.display='none'; }

  // cover
  var cover=document.getElementById('av-cover');
  if(d.image){ cover.src=d.image; cover.style.display='block'; } else { cover.style.display='none'; }

  openModal('viewModal');
}

function openDeleteModal(action, title) {
  document.getElementById('del-ad-title').textContent = title;
  document.getElementById('deleteForm').action = action;
  openModal('deleteModal');
}

function showToast(type,title,msg) {
  var c=document.getElementById('ad-toast'), t=document.createElement('div');
  var icon=type==='s'?'fas fa-check-circle':'fas fa-exclamation-circle';
  t.className='ad-toast ad-toast-'+type;
  t.innerHTML='<div class="ad-toast-icon"><i class="'+icon+'"></i></div><div><div class="ad-toast-title">'+title+'</div><div class="ad-toast-msg">'+msg+'</div></div><span class="ad-toast-bar"></span>';
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