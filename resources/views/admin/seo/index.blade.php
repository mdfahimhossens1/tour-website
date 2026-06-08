@extends('layouts.admin')
@section('title', 'SEO Settings')
@section('page')

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');
:root {
  --s-surface:   #1a1d27;
  --s-surface2:  #222636;
  --s-border:    rgba(255,255,255,.07);
  --s-accent:    #10b981;
  --s-accent2:   #34d399;
  --s-success:   #22c55e;
  --s-danger:    #ef4444;
  --s-warning:   #f59e0b;
  --s-info:      #38bdf8;
  --s-text:      #e2e8f0;
  --s-muted:     #64748b;
  --s-radius:    14px;
  --s-radius-sm: 8px;
  --s-shadow:    0 8px 32px rgba(0,0,0,.45);
}
.seo-wrap { font-family:'Plus Jakarta Sans',sans-serif; color:var(--s-text); }

/* header */
.seo-header {
  background:linear-gradient(135deg,#022c22 0%,#064e3b 50%,#065f46 100%);
  border-radius:var(--s-radius); padding:28px 32px;
  margin-bottom:24px; position:relative; overflow:hidden; box-shadow:var(--s-shadow);
}
.seo-header::before { content:''; position:absolute; inset:0; background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%2310b981' fill-opacity='0.05'%3E%3Cpath d='M30 5L55 20v20L30 55 5 40V20Z'/%3E%3C/g%3E%3C/svg%3E"); }
.seo-header::after { content:''; position:absolute; right:-40px; top:-40px; width:200px; height:200px; border-radius:50%; background:radial-gradient(circle,rgba(16,185,129,.18) 0%,transparent 70%); }
.seo-header .title { font-size:1.5rem; font-weight:700; position:relative; z-index:1; background:linear-gradient(90deg,#fff,var(--s-accent2)); -webkit-background-clip:text; -webkit-text-fill-color:transparent; }
.seo-header .subtitle { color:rgba(255,255,255,.45); font-size:.85rem; margin-top:4px; position:relative; z-index:1; }

.stat-pill { display:inline-flex; align-items:center; gap:8px; background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.1); border-radius:40px; padding:6px 16px; font-size:.8rem; font-weight:600; color:#fff; position:relative; z-index:1; }
.stat-pill .dot { width:8px; height:8px; border-radius:50%; }

/* table card */
.seo-card { background:var(--s-surface); border:1px solid var(--s-border); border-radius:var(--s-radius); overflow:hidden; box-shadow:var(--s-shadow); }
.seo-search-bar { padding:16px 20px; border-bottom:1px solid var(--s-border); display:flex; align-items:center; gap:12px; flex-wrap:wrap; justify-content:space-between; }
.seo-search-wrap { position:relative; }
.seo-search-wrap .si { position:absolute; left:11px; top:50%; transform:translateY(-50%); color:var(--s-muted); font-size:.8rem; }
.seo-search-input { background:var(--s-surface2); border:1px solid var(--s-border); border-radius:var(--s-radius-sm); padding:8px 14px 8px 36px; color:var(--s-text); font-family:inherit; font-size:.875rem; width:240px; outline:none; transition:border-color .2s; }
.seo-search-input:focus { border-color:var(--s-accent); box-shadow:0 0 0 3px rgba(16,185,129,.12); }

/* table */
.seo-table { width:100%; border-collapse:collapse; }
.seo-table thead tr { background:var(--s-surface2); }
.seo-table th { padding:13px 18px; text-align:left; font-size:.72rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:var(--s-muted); white-space:nowrap; }
.seo-table td { padding:13px 18px; vertical-align:middle; border-bottom:1px solid var(--s-border); font-size:.875rem; }
.seo-table tbody tr { transition:background .15s; }
.seo-table tbody tr:hover { background:rgba(16,185,129,.04); }
.seo-table tbody tr:last-child td { border-bottom:none; }

/* page badge */
.seo-page-badge {
  display:inline-flex; align-items:center; gap:6px;
  background:var(--s-surface2); border:1px solid var(--s-border);
  border-radius:8px; padding:5px 12px;
  font-family:'JetBrains Mono',monospace; font-size:.8rem;
  font-weight:700; color:var(--s-accent2);
}
.seo-page-badge i { font-size:.65rem; opacity:.6; }

/* meta title */
.seo-meta-title { font-weight:600; font-size:.875rem; max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
.seo-meta-desc  { font-size:.78rem; color:var(--s-muted); max-width:240px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; margin-top:3px; }

/* keyword tags */
.seo-kw-wrap { display:flex; gap:4px; flex-wrap:wrap; max-width:180px; }
.seo-kw { display:inline-block; background:rgba(16,185,129,.08); border:1px solid rgba(16,185,129,.18); color:var(--s-accent2); border-radius:20px; padding:2px 8px; font-size:.68rem; font-weight:600; }

/* canonical */
.seo-canonical { font-family:'JetBrains Mono',monospace; font-size:.72rem; color:var(--s-muted); max-width:160px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }

/* buttons */
.seo-btn { display:inline-flex; align-items:center; gap:6px; border:none; cursor:pointer; font-family:inherit; font-weight:600; border-radius:var(--s-radius-sm); transition:all .2s; text-decoration:none; }
.seo-btn-primary { background:var(--s-accent); color:#022c22; padding:9px 18px; font-size:.85rem; }
.seo-btn-primary:hover { background:var(--s-accent2); transform:translateY(-1px); box-shadow:0 4px 14px rgba(16,185,129,.35); color:#022c22; }
.seo-btn-outline { background:transparent; color:var(--s-text); border:1px solid var(--s-border); padding:9px 14px; font-size:.82rem; }
.seo-btn-outline:hover { background:var(--s-surface2); color:var(--s-text); }
.seo-btn-icon { background:var(--s-surface2); color:var(--s-muted); border:1px solid var(--s-border); padding:6px 10px; font-size:.78rem; border-radius:6px; }
.seo-btn-icon-edit:hover { color:var(--s-warning); border-color:rgba(245,158,11,.3); }
.seo-btn-danger-ghost { background:rgba(239,68,68,.1); color:#fca5a5; border:1px solid rgba(239,68,68,.2); padding:6px 10px; font-size:.78rem; border-radius:6px; }
.seo-btn-danger-ghost:hover { background:rgba(239,68,68,.2); }
.seo-actions { display:flex; gap:6px; align-items:center; }

/* pagination */
.pagination { display:flex; gap:4px; margin:0; padding:16px 20px; border-top:1px solid var(--s-border); flex-wrap:wrap; }
.page-item .page-link { background:var(--s-surface2); border:1px solid var(--s-border); color:var(--s-muted); border-radius:var(--s-radius-sm) !important; padding:6px 12px; font-size:.8rem; font-family:inherit; transition:all .2s; }
.page-item.active .page-link { background:var(--s-accent); border-color:var(--s-accent); color:#022c22; font-weight:700; }
.page-item .page-link:hover { background:var(--s-surface); color:var(--s-text); }

/* MODAL */
.seo-modal-overlay { position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,.72); backdrop-filter:blur(6px); display:flex; align-items:center; justify-content:center; opacity:0; pointer-events:none; transition:opacity .25s; }
.seo-modal-overlay.open { opacity:1; pointer-events:auto; }
.seo-modal { background:var(--s-surface); border:1px solid var(--s-border); border-radius:18px; width:min(640px,96vw); max-height:90vh; overflow-y:auto; box-shadow:0 24px 64px rgba(0,0,0,.6); transform:translateY(24px) scale(.97); transition:transform .3s cubic-bezier(.34,1.56,.64,1); }
.seo-modal-overlay.open .seo-modal { transform:translateY(0) scale(1); }
.seo-modal-header { padding:22px 28px 18px; border-bottom:1px solid var(--s-border); display:flex; align-items:center; justify-content:space-between; }
.seo-modal-title  { font-size:1.1rem; font-weight:700; }
.seo-modal-close  { background:var(--s-surface2); border:1px solid var(--s-border); color:var(--s-muted); width:32px; height:32px; border-radius:8px; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:all .2s; }
.seo-modal-close:hover { color:var(--s-text); }
.seo-modal-body   { padding:24px 28px; }
.seo-modal-footer { padding:18px 28px; border-top:1px solid var(--s-border); display:flex; gap:10px; justify-content:flex-end; }

/* form fields */
.seo-field { margin-bottom:18px; }
.seo-field label { display:block; font-size:.8rem; font-weight:600; color:var(--s-muted); margin-bottom:7px; text-transform:uppercase; letter-spacing:.06em; }
.seo-field input,.seo-field textarea {
  width:100%; background:var(--s-surface2); border:1px solid var(--s-border);
  border-radius:var(--s-radius-sm); padding:10px 14px; color:var(--s-text);
  font-family:inherit; font-size:.875rem; outline:none; resize:vertical;
  transition:border-color .2s,box-shadow .2s;
}
.seo-field input:focus,.seo-field textarea:focus { border-color:var(--s-accent); box-shadow:0 0 0 3px rgba(16,185,129,.12); }
.seo-field .err { color:#fca5a5; font-size:.78rem; margin-top:5px; }
.seo-field .hint { font-size:.72rem; color:var(--s-muted); margin-top:5px; }
.seo-field .char-count { font-size:.72rem; text-align:right; margin-top:4px; }
.seo-section { font-size:.7rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:var(--s-accent2); margin:4px 0 16px; display:flex; align-items:center; gap:8px; }
.seo-section::after { content:''; flex:1; height:1px; background:var(--s-border); }

/* page quick chips */
.seo-page-chips { display:flex; gap:6px; flex-wrap:wrap; margin-top:8px; }
.seo-page-chip { padding:4px 12px; border-radius:20px; font-size:.75rem; font-weight:600; cursor:pointer; border:1px solid var(--s-border); background:var(--s-surface2); color:var(--s-muted); transition:all .2s; font-family:'JetBrains Mono',monospace; }
.seo-page-chip:hover { background:var(--s-accent); color:#022c22; border-color:var(--s-accent); }

/* char limit bars */
.seo-limit-bar { height:3px; background:var(--s-surface2); border-radius:2px; margin-top:5px; overflow:hidden; }
.seo-limit-fill { height:100%; border-radius:2px; transition:width .2s, background .2s; }

/* delete modal */
.seo-del-icon { width:64px; height:64px; border-radius:50%; background:rgba(239,68,68,.12); border:2px solid rgba(239,68,68,.25); display:flex; align-items:center; justify-content:center; font-size:1.5rem; color:#fca5a5; margin:0 auto 18px; }

/* toast */
#seo-toast-container { position:fixed; bottom:28px; right:28px; z-index:99999; display:flex; flex-direction:column; gap:10px; }
.seo-toast { display:flex; align-items:center; gap:12px; background:var(--s-surface); border:1px solid var(--s-border); border-radius:12px; padding:14px 18px; min-width:280px; box-shadow:0 8px 30px rgba(0,0,0,.5); transform:translateX(120%); transition:transform .35s cubic-bezier(.34,1.56,.64,1); font-family:'Plus Jakarta Sans',sans-serif; position:relative; overflow:hidden; }
.seo-toast.show { transform:translateX(0); }
.seo-toast-icon { width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.seo-toast-success .seo-toast-icon { background:rgba(34,197,94,.15);  color:var(--s-success); }
.seo-toast-danger  .seo-toast-icon { background:rgba(239,68,68,.15);   color:var(--s-danger); }
.seo-toast-title { font-size:.875rem; font-weight:700; color:var(--s-text); }
.seo-toast-msg   { font-size:.78rem;  color:var(--s-muted); margin-top:2px; }
.seo-toast-bar   { position:absolute; bottom:0; left:0; height:3px; border-radius:0 0 12px 12px; animation:seoBar 3.5s linear forwards; }
.seo-toast-success .seo-toast-bar { background:var(--s-success); }
.seo-toast-danger  .seo-toast-bar { background:var(--s-danger); }
@keyframes seoBar { from{width:100%} to{width:0%} }
.seo-modal::-webkit-scrollbar { width:5px; }
.seo-modal::-webkit-scrollbar-thumb { background:var(--s-border); border-radius:10px; }
.seo-empty { text-align:center; padding:60px 20px; color:var(--s-muted); }
.seo-empty i { font-size:2.5rem; margin-bottom:14px; opacity:.4; display:block; }
</style>

@if(session('success'))<div id="flash-success" data-msg="{{ session('success') }}"></div>@endif
@if(session('error'))<div id="flash-error"   data-msg="{{ session('error') }}"></div>@endif

<div class="seo-wrap">

  {{-- Header --}}
  <div class="seo-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
      <div>
        <div class="title"><i class="fas fa-search me-2"></i>SEO Settings</div>
        <div class="subtitle">Manage meta tags, titles and keywords for each page</div>
        <div class="d-flex gap-2 mt-3 flex-wrap">
          <span class="stat-pill"><span class="dot" style="background:var(--s-accent)"></span>{{ $seo->total() }} Pages</span>
          <span class="stat-pill"><span class="dot" style="background:var(--s-info)"></span>{{ $seo->where('canonical_url','!=',null)->count() }} With Canonical</span>
        </div>
      </div>
      <div style="position:relative;z-index:1;">
        <button class="seo-btn seo-btn-primary" onclick="openAddModal()">
          <i class="fas fa-plus"></i> Add SEO
        </button>
      </div>
    </div>
  </div>

  {{-- Table Card --}}
  <div class="seo-card">
    <div class="seo-search-bar">
      <div class="seo-search-wrap">
        <i class="fas fa-search si"></i>
        <input type="text" class="seo-search-input" id="seo-search" placeholder="Search by page or title...">
      </div>
      <span style="font-size:.8rem;color:var(--s-muted);" id="seo-count"></span>
    </div>

    <div style="overflow-x:auto;">
      <table class="seo-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Page</th>
            <th>Meta Title & Description</th>
            <th>Keywords</th>
            <th>Canonical URL</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="seo-tbody">
          @forelse($seo as $key => $s)
          <tr data-search="{{ strtolower($s->page.' '.($s->meta_title ?? '')) }}">
            <td style="color:var(--s-muted);font-size:.8rem;">{{ $seo->firstItem() + $key }}</td>
            <td>
              <div class="seo-page-badge">
                <i class="fas fa-file-alt"></i>
                {{ ucfirst($s->page) }}
              </div>
            </td>
            <td>
              <div class="seo-meta-title">{{ $s->meta_title ?? '—' }}</div>
              @if($s->meta_description)
                <div class="seo-meta-desc">{{ Str::limit($s->meta_description, 60) }}</div>
              @endif
            </td>
            <td>
              @if($s->meta_keywords)
                <div class="seo-kw-wrap">
                  @foreach(array_slice(explode(',', $s->meta_keywords), 0, 3) as $kw)
                    @if(trim($kw))
                      <span class="seo-kw">{{ trim($kw) }}</span>
                    @endif
                  @endforeach
                  @if(count(explode(',', $s->meta_keywords)) > 3)
                    <span class="seo-kw" style="background:rgba(100,116,139,.1);color:var(--s-muted);">+{{ count(explode(',', $s->meta_keywords)) - 3 }}</span>
                  @endif
                </div>
              @else
                <span style="color:var(--s-muted);">—</span>
              @endif
            </td>
            <td>
              @if($s->canonical_url)
                <div class="seo-canonical" title="{{ $s->canonical_url }}">{{ $s->canonical_url }}</div>
              @else
                <span style="color:var(--s-muted);font-size:.8rem;">—</span>
              @endif
            </td>
            <td>
              <div class="seo-actions">
                <button class="seo-btn seo-btn-icon seo-btn-icon-edit" title="Edit"
                  onclick="openEditModal({
                    id: '{{ $s->id }}',
                    page: {{ json_encode($s->page) }},
                    meta_title: {{ json_encode($s->meta_title ?? '') }},
                    meta_description: {{ json_encode($s->meta_description ?? '') }},
                    meta_keywords: {{ json_encode($s->meta_keywords ?? '') }},
                    canonical_url: {{ json_encode($s->canonical_url ?? '') }}
                  })">
                  <i class="fas fa-pen"></i>
                </button>
                <button class="seo-btn seo-btn-danger-ghost" title="Delete"
                  onclick="openDeleteModal('{{ $s->id }}', {{ json_encode($s->page) }})">
                  <i class="fas fa-trash-alt"></i>
                </button>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="6">
            <div class="seo-empty">
              <i class="fas fa-search"></i>
              <p>No SEO settings found. Add your first page.</p>
            </div>
          </td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($seo->hasPages())
      <div>{{ $seo->links() }}</div>
    @endif
  </div>
</div>

{{-- ═══ ADD MODAL ═══ --}}
<div class="seo-modal-overlay" id="addModal">
  <div class="seo-modal">
    <div class="seo-modal-header">
      <div class="seo-modal-title"><i class="fas fa-plus-circle me-2" style="color:var(--s-accent2)"></i>Add SEO Settings</div>
      <button class="seo-modal-close" onclick="closeModal('addModal')"><i class="fas fa-times"></i></button>
    </div>
    <form method="POST" action="{{ route('admin.seo.store') }}">
      @csrf
      <div class="seo-modal-body">

        <div class="seo-section"><i class="fas fa-file-alt"></i> Page</div>
        <div class="seo-field">
          <label>Page Name <span style="color:var(--s-danger)">*</span></label>
          <input type="text" name="page" id="add_page" value="{{ old('page') }}" placeholder="home / about / tours / contact">
          <div class="seo-page-chips">
            @foreach(['home','about','tours','blog','contact','faq'] as $pg)
              <span class="seo-page-chip" onclick="setPage('add','{{ $pg }}')">{{ $pg }}</span>
            @endforeach
          </div>
          @error('page')<div class="err">{{ $message }}</div>@enderror
        </div>

        <div class="seo-section"><i class="fas fa-tags"></i> Meta Tags</div>
        <div class="seo-field">
          <label>Meta Title <span style="color:var(--s-muted);font-size:.7rem;font-weight:400;text-transform:none;">(recommended: 50–60 chars)</span></label>
          <input type="text" name="meta_title" id="add_meta_title" value="{{ old('meta_title') }}" placeholder="Page Title for Search Engines" oninput="updateChar('add_meta_title','add_title_count',60)">
          <div class="seo-limit-bar"><div class="seo-limit-fill" id="add_title_bar" style="width:0%;background:var(--s-accent);"></div></div>
          <div class="char-count seo-field" style="margin:4px 0 0;"><span id="add_title_count" style="font-size:.72rem;color:var(--s-muted);">0 / 60</span></div>
        </div>
        <div class="seo-field">
          <label>Meta Description <span style="color:var(--s-muted);font-size:.7rem;font-weight:400;text-transform:none;">(recommended: 150–160 chars)</span></label>
          <textarea name="meta_description" id="add_meta_desc" rows="3" placeholder="Brief description for search result snippets..." oninput="updateChar('add_meta_desc','add_desc_count',160)">{{ old('meta_description') }}</textarea>
          <div class="seo-limit-bar"><div class="seo-limit-fill" id="add_desc_bar" style="width:0%;background:var(--s-accent);"></div></div>
          <div><span id="add_desc_count" style="font-size:.72rem;color:var(--s-muted);">0 / 160</span></div>
        </div>
        <div class="seo-field">
          <label>Meta Keywords</label>
          <input type="text" name="meta_keywords" value="{{ old('meta_keywords') }}" placeholder="tour, travel, bangladesh, cox's bazar">
          <div style="font-size:.72rem;color:var(--s-muted);margin-top:4px;"><i class="fas fa-info-circle me-1"></i>Separate keywords with commas</div>
        </div>

        <div class="seo-section"><i class="fas fa-link"></i> Canonical URL</div>
        <div class="seo-field">
          <label>Canonical URL <span style="color:var(--s-muted);font-size:.7rem;font-weight:400;text-transform:none;">(optional)</span></label>
          <input type="text" name="canonical_url" value="{{ old('canonical_url') }}" placeholder="https://yoursite.com/page" style="font-family:'JetBrains Mono',monospace;font-size:.82rem;">
        </div>

      </div>
      <div class="seo-modal-footer">
        <button type="button" class="seo-btn seo-btn-outline" onclick="closeModal('addModal')">Cancel</button>
        <button type="submit" class="seo-btn seo-btn-primary"><i class="fas fa-save"></i> Save SEO</button>
      </div>
    </form>
  </div>
</div>

{{-- ═══ EDIT MODAL ═══ --}}
<div class="seo-modal-overlay" id="editModal">
  <div class="seo-modal">
    <div class="seo-modal-header">
      <div class="seo-modal-title"><i class="fas fa-pen me-2" style="color:var(--s-warning)"></i>Edit SEO Settings</div>
      <button class="seo-modal-close" onclick="closeModal('editModal')"><i class="fas fa-times"></i></button>
    </div>
    <form method="POST" id="editForm">
      @csrf
      @method('PUT')
      <input type="hidden" name="id" id="edit_id">
      <div class="seo-modal-body">

        <div class="seo-section"><i class="fas fa-file-alt"></i> Page</div>
        <div class="seo-field">
          <label>Page Name <span style="color:var(--s-danger)">*</span></label>
          <input type="text" name="page" id="edit_page" placeholder="home / about / tours">
          <div class="seo-page-chips">
            @foreach(['home','about','tours','blog','contact','faq'] as $pg)
              <span class="seo-page-chip" onclick="setPage('edit','{{ $pg }}')">{{ $pg }}</span>
            @endforeach
          </div>
        </div>

        <div class="seo-section"><i class="fas fa-tags"></i> Meta Tags</div>
        <div class="seo-field">
          <label>Meta Title <span style="color:var(--s-muted);font-size:.7rem;font-weight:400;text-transform:none;">(recommended: 50–60 chars)</span></label>
          <input type="text" name="meta_title" id="edit_meta_title" placeholder="Page Title for Search Engines" oninput="updateChar('edit_meta_title','edit_title_count',60)">
          <div class="seo-limit-bar"><div class="seo-limit-fill" id="edit_title_bar" style="width:0%;background:var(--s-warning);"></div></div>
          <div><span id="edit_title_count" style="font-size:.72rem;color:var(--s-muted);">0 / 60</span></div>
        </div>
        <div class="seo-field">
          <label>Meta Description <span style="color:var(--s-muted);font-size:.7rem;font-weight:400;text-transform:none;">(recommended: 150–160 chars)</span></label>
          <textarea name="meta_description" id="edit_meta_desc" rows="3" placeholder="Brief description..." oninput="updateChar('edit_meta_desc','edit_desc_count',160)"></textarea>
          <div class="seo-limit-bar"><div class="seo-limit-fill" id="edit_desc_bar" style="width:0%;background:var(--s-warning);"></div></div>
          <div><span id="edit_desc_count" style="font-size:.72rem;color:var(--s-muted);">0 / 160</span></div>
        </div>
        <div class="seo-field">
          <label>Meta Keywords</label>
          <input type="text" name="meta_keywords" id="edit_meta_keywords" placeholder="keyword1, keyword2, keyword3">
          <div style="font-size:.72rem;color:var(--s-muted);margin-top:4px;"><i class="fas fa-info-circle me-1"></i>Separate keywords with commas</div>
        </div>

        <div class="seo-section"><i class="fas fa-link"></i> Canonical URL</div>
        <div class="seo-field">
          <label>Canonical URL</label>
          <input type="text" name="canonical_url" id="edit_canonical_url" placeholder="https://yoursite.com/page" style="font-family:'JetBrains Mono',monospace;font-size:.82rem;">
        </div>

      </div>
      <div class="seo-modal-footer">
        <button type="button" class="seo-btn seo-btn-outline" onclick="closeModal('editModal')">Cancel</button>
        <button type="submit" class="seo-btn seo-btn-primary" style="background:var(--s-warning);color:#1a1d27;">
          <i class="fas fa-save"></i> Update SEO
        </button>
      </div>
    </form>
  </div>
</div>

{{-- ═══ DELETE MODAL ═══ --}}
<div class="seo-modal-overlay" id="deleteModal">
  <div class="seo-modal" style="width:min(420px,96vw);">
    <div class="seo-modal-body" style="text-align:center;padding:40px 28px 28px;">
      <div class="seo-del-icon"><i class="fas fa-search"></i></div>
      <h5 style="font-weight:700;margin-bottom:8px;">Delete SEO Settings?</h5>
      <p style="color:var(--s-muted);font-size:.88rem;margin-bottom:4px;">
        SEO for page <strong id="del-page-name" style="color:var(--s-text);font-family:'JetBrains Mono',monospace;"></strong> will be permanently deleted.
      </p>
      <p style="color:var(--s-muted);font-size:.8rem;">This action <strong style="color:var(--s-danger)">cannot be undone</strong>.</p>
    </div>
    <div class="seo-modal-footer" style="justify-content:center;gap:14px;">
      <button class="seo-btn seo-btn-outline" onclick="closeModal('deleteModal')"><i class="fas fa-times"></i> Cancel</button>
      <form id="deleteForm" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="seo-btn seo-btn-primary" style="background:var(--s-danger);color:#fff;padding:9px 18px;font-size:.875rem;">
          <i class="fas fa-trash-alt"></i> Yes, Delete
        </button>
      </form>
    </div>
  </div>
</div>

<div id="seo-toast-container"></div>

<script>
// ── Modal ──────────────────────────────────────────
function openModal(id)  { document.getElementById(id).classList.add('open'); document.body.style.overflow='hidden'; }
function closeModal(id) { document.getElementById(id).classList.remove('open'); document.body.style.overflow=''; }
document.querySelectorAll('.seo-modal-overlay').forEach(el => {
  el.addEventListener('click', e => { if(e.target===el) closeModal(el.id); });
});
document.addEventListener('keydown', e => {
  if(e.key==='Escape') document.querySelectorAll('.seo-modal-overlay.open').forEach(el=>closeModal(el.id));
});

// ── Add ────────────────────────────────────────────
function openAddModal() { openModal('addModal'); }
@if($errors->any() && old('_method') !== 'PUT') openAddModal(); @endif

// ── Page chip ──────────────────────────────────────
function setPage(prefix, page) {
  document.getElementById(prefix + '_page').value = page;
}

// ── Edit ───────────────────────────────────────────
function openEditModal(d) {
  document.getElementById('edit_id').value             = d.id;
  document.getElementById('edit_page').value           = d.page;
  document.getElementById('edit_meta_title').value     = d.meta_title;
  document.getElementById('edit_meta_desc').value      = d.meta_description;
  document.getElementById('edit_meta_keywords').value  = d.meta_keywords;
  document.getElementById('edit_canonical_url').value  = d.canonical_url;
  document.getElementById('editForm').action           = '/admin/seo/' + d.id;

  // update char bars
  updateChar('edit_meta_title','edit_title_count',60);
  updateChar('edit_meta_desc','edit_desc_count',160);

  openModal('editModal');
}

// ── Delete ─────────────────────────────────────────
function openDeleteModal(id, page) {
  document.getElementById('del-page-name').textContent = page;
  document.getElementById('deleteForm').action = '/admin/seo/' + id;
  openModal('deleteModal');
}

// ── Char counter + bar ─────────────────────────────
function updateChar(inputId, countId, max) {
  var el  = document.getElementById(inputId);
  var cnt = document.getElementById(countId);
  var barId = inputId.replace('meta_title','title_bar').replace('meta_desc','desc_bar');
  var bar = document.getElementById(barId);
  var len = el.value.length;
  cnt.textContent = len + ' / ' + max;
  var pct = Math.min(100, (len / max) * 100);
  if(bar) {
    bar.style.width = pct + '%';
    bar.style.background = pct > 100 ? 'var(--s-danger)' : pct > 80 ? 'var(--s-warning)' : 'var(--s-accent)';
  }
  cnt.style.color = pct > 100 ? 'var(--s-danger)' : pct > 80 ? 'var(--s-warning)' : 'var(--s-muted)';
}

// ── Live search ────────────────────────────────────
(function(){
  var inp  = document.getElementById('seo-search');
  var rows = Array.from(document.querySelectorAll('#seo-tbody tr[data-search]'));
  var cnt  = document.getElementById('seo-count');
  inp.addEventListener('input', function() {
    var q = this.value.toLowerCase().trim();
    var v = 0;
    rows.forEach(r => {
      var show = !q || r.dataset.search.includes(q);
      r.style.display = show ? '' : 'none';
      if(show) v++;
    });
    cnt.textContent = v + ' of ' + rows.length + ' pages';
  });
  cnt.textContent = rows.length + ' of ' + rows.length + ' pages';
})();

// ── Toast ──────────────────────────────────────────
function showToast(type, title, msg) {
  var icons = {success:'fas fa-check-circle', danger:'fas fa-exclamation-circle'};
  var c = document.getElementById('seo-toast-container');
  var t = document.createElement('div');
  t.className = 'seo-toast seo-toast-' + type;
  t.innerHTML = `<div class="seo-toast-icon"><i class="${icons[type]}"></i></div>
    <div><div class="seo-toast-title">${title}</div><div class="seo-toast-msg">${msg}</div></div>
    <span class="seo-toast-bar"></span>`;
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