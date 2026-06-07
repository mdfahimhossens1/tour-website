@extends('layouts.admin')
@section('title', 'Create Blog')
@section('page')

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');
:root {
  --p-surface:#1a1d27; --p-surface2:#222636; --p-border:rgba(255,255,255,.07);
  --p-accent:#0ea5e9; --p-accent2:#38bdf8; --p-success:#22c55e; --p-danger:#ef4444;
  --p-warning:#f59e0b; --p-teal:#14b8a6; --p-text:#e2e8f0; --p-muted:#64748b;
  --p-radius:14px; --p-radius-sm:8px; --p-shadow:0 8px 32px rgba(0,0,0,.45);
}
.bc-wrap { font-family:'Plus Jakarta Sans',sans-serif; color:var(--p-text); }
.bc-header {
  background:linear-gradient(135deg,#0c1a2e 0%,#0c2218 60%,#0c1a2e 100%);
  border-radius:var(--p-radius); padding:26px 32px; margin-bottom:24px;
  position:relative; overflow:hidden; box-shadow:var(--p-shadow);
  display:flex; align-items:center; justify-content:space-between;
}
.bc-header::after { content:''; position:absolute; right:-40px; top:-40px; width:180px; height:180px; border-radius:50%; background:radial-gradient(circle,rgba(20,184,166,.15) 0%,transparent 70%); }
.bc-header .title { font-size:1.35rem; font-weight:700; position:relative; z-index:1; background:linear-gradient(90deg,#fff,#5eead4); -webkit-background-clip:text; -webkit-text-fill-color:transparent; }
.bc-header .subtitle { color:rgba(255,255,255,.45); font-size:.82rem; margin-top:3px; position:relative; z-index:1; }
.bc-back { display:inline-flex; align-items:center; gap:7px; position:relative; z-index:1; background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.12); color:#fff; border-radius:9px; padding:8px 16px; font-size:.83rem; font-weight:600; text-decoration:none; transition:background .2s; }
.bc-back:hover { background:rgba(255,255,255,.14); color:#fff; }
.bc-layout { display:grid; grid-template-columns:1fr 340px; gap:20px; align-items:start; }
@media(max-width:960px){ .bc-layout{ grid-template-columns:1fr; } }
.bc-card { background:var(--p-surface); border:1px solid var(--p-border); border-radius:var(--p-radius); box-shadow:var(--p-shadow); overflow:hidden; }
.bc-card-head { padding:15px 22px; border-bottom:1px solid var(--p-border); background:var(--p-surface2); display:flex; align-items:center; gap:10px; }
.bc-card-head .ic { width:34px; height:34px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:.82rem; }
.bc-card-head h5 { font-size:.9rem; font-weight:700; margin:0; }
.bc-card-head p  { font-size:.73rem; color:var(--p-muted); margin:2px 0 0; }
.bc-body { padding:22px; }
.bc-field { margin-bottom:17px; }
.bc-field label { display:block; font-size:.73rem; font-weight:700; letter-spacing:.07em; text-transform:uppercase; color:var(--p-muted); margin-bottom:7px; }
.bc-field label .req { color:var(--p-danger); }
.bc-field input,.bc-field select,.bc-field textarea {
  width:100%; background:var(--p-surface2); border:1px solid var(--p-border);
  border-radius:var(--p-radius-sm); padding:10px 14px; color:var(--p-text);
  font-family:'Plus Jakarta Sans',sans-serif; font-size:.875rem; outline:none;
  transition:border-color .2s,box-shadow .2s; box-sizing:border-box; resize:vertical;
}
.bc-field input:focus,.bc-field select:focus,.bc-field textarea:focus { border-color:var(--p-teal); box-shadow:0 0 0 3px rgba(20,184,166,.12); }
.bc-field select option { background:#1a1d27; }
.bc-field .err { color:#fca5a5; font-size:.76rem; margin-top:5px; display:block; }
.bc-upload { border:2px dashed var(--p-border); border-radius:10px; padding:22px; text-align:center; cursor:pointer; transition:all .2s; position:relative; background:var(--p-surface2); }
.bc-upload:hover { border-color:var(--p-teal); background:rgba(20,184,166,.05); }
.bc-upload input { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; }
.bc-upload i { font-size:1.4rem; color:var(--p-muted); display:block; margin-bottom:6px; }
.bc-upload span { font-size:.8rem; color:var(--p-muted); }
.bc-preview { display:none; width:100%; height:160px; object-fit:cover; border-radius:9px; border:1px solid var(--p-border); margin-top:12px; }
.bc-status-row { display:flex; gap:10px; }
.bc-status-opt { flex:1; }
.bc-status-opt input[type="radio"] { display:none; }
.bc-status-opt label { display:flex; align-items:center; justify-content:center; gap:7px; padding:10px; border-radius:8px; border:1px solid var(--p-border); cursor:pointer; font-size:.83rem; font-weight:600; transition:all .2s; background:var(--p-surface2); color:var(--p-muted); text-transform:none; letter-spacing:0; }
.bc-status-opt input:checked + label.lbl-pub { background:rgba(34,197,94,.12); color:#86efac; border-color:rgba(34,197,94,.3); }
.bc-status-opt input:checked + label.lbl-dft { background:rgba(239,68,68,.12); color:#fca5a5; border-color:rgba(239,68,68,.3); }
.bc-submit { width:100%; padding:12px; border:none; border-radius:var(--p-radius-sm); background:linear-gradient(135deg,var(--p-teal),#0d9488); color:#fff; font-family:'Plus Jakarta Sans',sans-serif; font-weight:700; font-size:.9rem; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; transition:all .2s; margin-top:4px; }
.bc-submit:hover { transform:translateY(-2px); box-shadow:0 6px 18px rgba(20,184,166,.35); }
.bc-err-box { background:rgba(239,68,68,.08); border:1px solid rgba(239,68,68,.2); border-radius:10px; padding:14px 18px; margin-bottom:18px; }
.bc-err-box ul { margin:0; padding-left:18px; }
.bc-err-box li { font-size:.83rem; color:#fca5a5; margin-bottom:3px; }
</style>

<div class="bc-wrap">
  <div class="bc-header">
    <div>
      <div class="title"><i class="fas fa-pen-nib me-2"></i>Create Blog Post</div>
      <div class="subtitle">Write and publish a new blog article</div>
    </div>
    <a href="{{ route('admin.blogs.index') }}" class="bc-back"><i class="fas fa-arrow-left"></i> Back</a>
  </div>

  @if($errors->any())
    <div class="bc-err-box"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
  @endif

  <form action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data">
  @csrf
  <div class="bc-layout">

    {{-- LEFT --}}
    <div style="display:flex;flex-direction:column;gap:18px;">
      <div class="bc-card">
        <div class="bc-card-head">
          <div class="ic" style="background:rgba(20,184,166,.15);color:#5eead4;"><i class="fas fa-heading"></i></div>
          <div><h5>Post Content</h5><p>Title, category and main content</p></div>
        </div>
        <div class="bc-body">
          <div class="bc-field">
            <label>Blog Title <span class="req">*</span></label>
            <input type="text" name="title" value="{{ old('title') }}" placeholder="Enter an engaging blog title...">
            @error('title')<span class="err">{{ $message }}</span>@enderror
          </div>
          <div class="bc-field">
            <label>Category <span class="req">*</span></label>
            <select name="blog_category_id">
              <option value="">— Select Category —</option>
              @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ old('blog_category_id')==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
              @endforeach
            </select>
            @error('blog_category_id')<span class="err">{{ $message }}</span>@enderror
          </div>
          <div class="bc-field" style="margin-bottom:0;">
            <label>Description <span class="req">*</span></label>
            <textarea name="description" rows="12" placeholder="Write your blog content here...">{{ old('description') }}</textarea>
            @error('description')<span class="err">{{ $message }}</span>@enderror
          </div>
        </div>
      </div>

      <div class="bc-card">
        <div class="bc-card-head">
          <div class="ic" style="background:rgba(14,165,233,.15);color:var(--p-accent2);"><i class="fas fa-search"></i></div>
          <div><h5>SEO Meta</h5><p>Optional — for search engines</p></div>
        </div>
        <div class="bc-body">
          <div class="bc-field">
            <label>Meta Title</label>
            <input type="text" name="meta_title" value="{{ old('meta_title') }}" placeholder="SEO title for search results">
          </div>
          <div class="bc-field" style="margin-bottom:0;">
            <label>Meta Description</label>
            <textarea name="meta_description" rows="3" placeholder="Brief description shown in search results...">{{ old('meta_description') }}</textarea>
          </div>
        </div>
      </div>
    </div>

    {{-- RIGHT --}}
    <div style="display:flex;flex-direction:column;gap:18px;position:sticky;top:20px;">
      <div class="bc-card">
        <div class="bc-card-head">
          <div class="ic" style="background:rgba(34,197,94,.15);color:#86efac;"><i class="fas fa-paper-plane"></i></div>
          <div><h5>Publish</h5><p>Set visibility and save</p></div>
        </div>
        <div class="bc-body">
          <div class="bc-field" style="margin-bottom:14px;">
            <label>Visibility</label>
            <div class="bc-status-row">
              <div class="bc-status-opt">
                <input type="radio" name="status" id="s1" value="1" {{ old('status','1')=='1'?'checked':'' }}>
                <label for="s1" class="lbl-pub"><i class="fas fa-globe"></i> Published</label>
              </div>
              <div class="bc-status-opt">
                <input type="radio" name="status" id="s0" value="0" {{ old('status')=='0'?'checked':'' }}>
                <label for="s0" class="lbl-dft"><i class="fas fa-lock"></i> Draft</label>
              </div>
            </div>
          </div>
          <button type="submit" class="bc-submit"><i class="fas fa-save"></i> Publish Post</button>
        </div>
      </div>

      <div class="bc-card">
        <div class="bc-card-head">
          <div class="ic" style="background:rgba(139,92,246,.15);color:#c4b5fd;"><i class="fas fa-image"></i></div>
          <div><h5>Cover Image</h5><p>Featured image for the post</p></div>
        </div>
        <div class="bc-body">
          <div class="bc-upload" id="uploadZone">
            <input type="file" name="image" id="imgInput" accept="image/*" onchange="previewImg(this)">
            <i class="fas fa-cloud-upload-alt"></i>
            <span id="uploadLabel">Click to upload cover image</span>
          </div>
          <img id="imgPreview" class="bc-preview" src="" alt="">
          @error('image')<span class="err" style="margin-top:6px;display:block;">{{ $message }}</span>@enderror
        </div>
      </div>
    </div>

  </div>
  </form>
</div>

<script>
function previewImg(input) {
  if(!input.files||!input.files[0]) return;
  var img=document.getElementById('imgPreview');
  img.src=URL.createObjectURL(input.files[0]);
  img.style.display='block';
  document.getElementById('uploadLabel').textContent=input.files[0].name;
  document.getElementById('uploadZone').style.borderColor='var(--p-teal)';
}
</script>
@endsection