@extends('layouts.admin')
@section('title', 'View Blog')
@section('page')

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');
:root {
  --p-surface:#1a1d27; --p-surface2:#222636; --p-border:rgba(255,255,255,.07);
  --p-accent:#0ea5e9; --p-accent2:#38bdf8; --p-success:#22c55e; --p-danger:#ef4444;
  --p-warning:#f59e0b; --p-teal:#14b8a6; --p-text:#e2e8f0; --p-muted:#64748b;
  --p-radius:14px; --p-radius-sm:8px; --p-shadow:0 8px 32px rgba(0,0,0,.45);
}
.bv-wrap { font-family:'Plus Jakarta Sans',sans-serif; color:var(--p-text); }

.bv-header {
  background:linear-gradient(135deg,#0c1a2e 0%,#0c2218 50%,#0c1a2e 100%);
  border-radius:var(--p-radius); padding:26px 32px; margin-bottom:24px;
  position:relative; overflow:hidden; box-shadow:var(--p-shadow);
}
.bv-header::after { content:''; position:absolute; right:-40px; top:-40px; width:180px; height:180px; border-radius:50%; background:radial-gradient(circle,rgba(20,184,166,.15) 0%,transparent 70%); }
.bv-header-inner { display:flex; align-items:flex-start; justify-content:space-between; gap:16px; flex-wrap:wrap; position:relative; z-index:1; }
.bv-header .title { font-size:1.3rem; font-weight:700; background:linear-gradient(90deg,#fff,#5eead4); -webkit-background-clip:text; -webkit-text-fill-color:transparent; line-height:1.35; }
.bv-header .subtitle { color:rgba(255,255,255,.45); font-size:.8rem; margin-top:5px; }
.bv-header-btns { display:flex; gap:10px; flex-shrink:0; }
.bv-btn { display:inline-flex; align-items:center; gap:7px; border-radius:9px; padding:8px 16px; font-size:.83rem; font-weight:600; text-decoration:none; transition:all .2s; cursor:pointer; border:none; font-family:'Plus Jakarta Sans',sans-serif; }
.bv-btn-ghost { background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.12); color:#fff; }
.bv-btn-ghost:hover { background:rgba(255,255,255,.14); color:#fff; }
.bv-btn-edit  { background:rgba(245,158,11,.15); border:1px solid rgba(245,158,11,.25); color:#fcd34d; }
.bv-btn-edit:hover { background:rgba(245,158,11,.25); color:#fcd34d; }

.bv-layout { display:grid; grid-template-columns:1fr 300px; gap:20px; align-items:start; }
@media(max-width:900px){ .bv-layout{ grid-template-columns:1fr; } }

.bv-card { background:var(--p-surface); border:1px solid var(--p-border); border-radius:var(--p-radius); box-shadow:var(--p-shadow); overflow:hidden; }
.bv-card-head { padding:15px 22px; border-bottom:1px solid var(--p-border); background:var(--p-surface2); display:flex; align-items:center; gap:10px; }
.bv-card-head .ic { width:34px; height:34px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:.82rem; }
.bv-card-head h5 { font-size:.9rem; font-weight:700; margin:0; }
.bv-card-body { padding:22px; }

/* cover */
.bv-cover { width:100%; max-height:340px; object-fit:cover; border-radius:12px; border:1px solid var(--p-border); margin-bottom:0; display:block; }

/* meta grid */
.bv-meta-grid { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
.bv-meta-item label { font-size:.69rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:var(--p-muted); display:block; margin-bottom:4px; }
.bv-meta-item .val { font-size:.875rem; color:var(--p-text); font-weight:500; }
.bv-meta-item .mono { font-family:'JetBrains Mono',monospace; font-size:.8rem; color:var(--p-accent2); word-break:break-all; }

.bv-badge { display:inline-flex; align-items:center; gap:5px; padding:4px 12px; border-radius:20px; font-size:.72rem; font-weight:700; }
.bv-badge-active   { background:rgba(34,197,94,.12); color:#86efac; border:1px solid rgba(34,197,94,.25); }
.bv-badge-inactive { background:rgba(239,68,68,.12); color:#fca5a5; border:1px solid rgba(239,68,68,.25); }
.bv-cat-badge { display:inline-flex; align-items:center; gap:5px; background:rgba(20,184,166,.12); color:#5eead4; border:1px solid rgba(20,184,166,.25); border-radius:20px; padding:4px 12px; font-size:.75rem; font-weight:600; }

/* sec label */
.bv-sec { font-size:.69rem; font-weight:700; letter-spacing:.09em; text-transform:uppercase; color:var(--p-teal); margin:18px 0 12px; display:flex; align-items:center; gap:8px; }
.bv-sec::after { content:''; flex:1; height:1px; background:var(--p-border); }
.bv-sec:first-child { margin-top:0; }

/* description */
.bv-desc { font-size:.9rem; line-height:1.8; color:var(--p-text); }
.bv-desc img { max-width:100%; border-radius:8px; }
.bv-desc h1,.bv-desc h2,.bv-desc h3 { color:#fff; margin:16px 0 8px; }
.bv-desc p { margin-bottom:12px; }
.bv-desc a { color:var(--p-accent2); }

/* seo box */
.bv-seo-box { background:rgba(14,165,233,.05); border:1px solid rgba(14,165,233,.15); border-radius:10px; padding:14px 16px; }
.bv-seo-item { margin-bottom:12px; }
.bv-seo-item:last-child { margin-bottom:0; }
.bv-seo-item label { font-size:.69rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:var(--p-muted); display:block; margin-bottom:4px; }
.bv-seo-item p { font-size:.82rem; color:var(--p-text); margin:0; line-height:1.5; }
</style>

<div class="bv-wrap">

  {{-- HEADER --}}
  <div class="bv-header">
    <div class="bv-header-inner">
      <div>
        <div class="title">{{ $blog->title }}</div>
        <div class="subtitle">
          <i class="fas fa-calendar-alt me-1"></i>{{ $blog->created_at->format('d M Y, h:i A') }}
        </div>
      </div>
      <div class="bv-header-btns">
        <a href="{{ route('admin.blogs.index') }}" class="bv-btn bv-btn-ghost"><i class="fas fa-arrow-left"></i> Back</a>
        <a href="{{ route('admin.blogs.edit',$blog->slug) }}" class="bv-btn bv-btn-edit"><i class="fas fa-pen"></i> Edit</a>
      </div>
    </div>
  </div>

  <div class="bv-layout">

    {{-- LEFT: content --}}
    <div style="display:flex;flex-direction:column;gap:18px;">

      {{-- Cover --}}
      @if($blog->image)
      <div class="bv-card">
        <div class="bv-card-head">
          <div class="ic" style="background:rgba(139,92,246,.15);color:#c4b5fd;"><i class="fas fa-image"></i></div>
          <h5>Cover Image</h5>
        </div>
        <div class="bv-card-body" style="padding:0;">
          <img src="{{ asset('uploads/blogs/'.$blog->image) }}" class="bv-cover" alt="{{ $blog->title }}">
        </div>
      </div>
      @endif

      {{-- Description --}}
      <div class="bv-card">
        <div class="bv-card-head">
          <div class="ic" style="background:rgba(20,184,166,.15);color:#5eead4;"><i class="fas fa-align-left"></i></div>
          <h5>Blog Content</h5>
        </div>
        <div class="bv-card-body">
          <div class="bv-desc">{!! $blog->description !!}</div>
        </div>
      </div>

      {{-- SEO --}}
      @if($blog->meta_title || $blog->meta_description)
      <div class="bv-card">
        <div class="bv-card-head">
          <div class="ic" style="background:rgba(14,165,233,.15);color:var(--p-accent2);"><i class="fas fa-search"></i></div>
          <h5>SEO Meta</h5>
        </div>
        <div class="bv-card-body">
          <div class="bv-seo-box">
            @if($blog->meta_title)
            <div class="bv-seo-item">
              <label>Meta Title</label>
              <p>{{ $blog->meta_title }}</p>
            </div>
            @endif
            @if($blog->meta_description)
            <div class="bv-seo-item">
              <label>Meta Description</label>
              <p>{{ $blog->meta_description }}</p>
            </div>
            @endif
          </div>
        </div>
      </div>
      @endif

    </div>

    {{-- RIGHT: sidebar --}}
    <div style="display:flex;flex-direction:column;gap:18px;position:sticky;top:20px;">
      <div class="bv-card">
        <div class="bv-card-head">
          <div class="ic" style="background:rgba(245,158,11,.15);color:#fcd34d;"><i class="fas fa-info-circle"></i></div>
          <h5>Post Details</h5>
        </div>
        <div class="bv-card-body">
          <div class="bv-sec"><i class="fas fa-tag"></i> Info</div>
          <div style="display:flex;flex-direction:column;gap:14px;">
            <div class="bv-meta-item">
              <label>Status</label>
              <div class="val">
                @if($blog->status)
                  <span class="bv-badge bv-badge-active"><i class="fas fa-circle" style="font-size:.4rem;"></i>Published</span>
                @else
                  <span class="bv-badge bv-badge-inactive"><i class="fas fa-circle" style="font-size:.4rem;"></i>Draft</span>
                @endif
              </div>
            </div>
            <div class="bv-meta-item">
              <label>Category</label>
              <div class="val">
                <span class="bv-cat-badge"><i class="fas fa-folder" style="font-size:.55rem;"></i>{{ $blog->category->name ?? 'Uncategorized' }}</span>
              </div>
            </div>
            <div class="bv-meta-item">
              <label>Slug</label>
              <div class="val mono">{{ $blog->slug }}</div>
            </div>
            <div class="bv-meta-item">
              <label>Created</label>
              <div class="val" style="font-size:.82rem;">{{ $blog->created_at->format('d M Y, h:i A') }}</div>
            </div>
            <div class="bv-meta-item">
              <label>Last Updated</label>
              <div class="val" style="font-size:.82rem;">{{ $blog->updated_at->format('d M Y, h:i A') }}</div>
            </div>
          </div>

          <div style="margin-top:20px;padding-top:16px;border-top:1px solid var(--p-border);display:flex;flex-direction:column;gap:10px;">
            <a href="{{ route('admin.blogs.edit',$blog->slug) }}"
               style="display:flex;align-items:center;justify-content:center;gap:8px;padding:10px;border-radius:var(--p-radius-sm);background:rgba(245,158,11,.12);color:#fcd34d;border:1px solid rgba(245,158,11,.2);font-weight:600;font-size:.84rem;text-decoration:none;transition:all .2s;">
              <i class="fas fa-pen"></i> Edit This Post
            </a>
            <form method="POST" action="{{ route('admin.blogs.delete',$blog->slug) }}" id="delForm">
              @csrf
              <button type="button" onclick="confirmDelete()"
                style="width:100%;display:flex;align-items:center;justify-content:center;gap:8px;padding:10px;border-radius:var(--p-radius-sm);background:rgba(239,68,68,.1);color:#fca5a5;border:1px solid rgba(239,68,68,.2);font-weight:600;font-size:.84rem;cursor:pointer;font-family:'Plus Jakarta Sans',sans-serif;transition:all .2s;">
                <i class="fas fa-trash-alt"></i> Delete Post
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<script>
function confirmDelete() {
  if(confirm('Are you sure you want to delete this blog post? This cannot be undone.')) {
    document.getElementById('delForm').submit();
  }
}
</script>
@endsection