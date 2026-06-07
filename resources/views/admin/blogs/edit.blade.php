@extends('layouts.admin')
@section('title', 'Edit Blog')
@section('page')

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');
:root {
  --p-surface:#1a1d27; --p-surface2:#222636; --p-border:rgba(255,255,255,.07);
  --p-accent:#0ea5e9; --p-accent2:#38bdf8; --p-success:#22c55e; --p-danger:#ef4444;
  --p-warning:#f59e0b; --p-teal:#14b8a6; --p-text:#e2e8f0; --p-muted:#64748b;
  --p-radius:14px; --p-radius-sm:8px; --p-shadow:0 8px 32px rgba(0,0,0,.45);
}
.be-wrap { font-family:'Plus Jakarta Sans',sans-serif; color:var(--p-text); }
.be-header {
  background:linear-gradient(135deg,#0c1a2e 0%,#1a120c 60%,#0c1a2e 100%);
  border-radius:var(--p-radius); padding:26px 32px; margin-bottom:24px;
  position:relative; overflow:hidden; box-shadow:var(--p-shadow);
  display:flex; align-items:center; justify-content:space-between;
}
.be-header::after { content:''; position:absolute; right:-40px; top:-40px; width:180px; height:180px; border-radius:50%; background:radial-gradient(circle,rgba(245,158,11,.15) 0%,transparent 70%); }
.be-header .title { font-size:1.35rem; font-weight:700; position:relative; z-index:1; background:linear-gradient(90deg,#fff,#fcd34d); -webkit-background-clip:text; -webkit-text-fill-color:transparent; }
.be-header .subtitle { color:rgba(255,255,255,.45); font-size:.82rem; margin-top:3px; position:relative; z-index:1; }
.be-header-btns { display:flex; gap:10px; position:relative; z-index:1; }
.be-back { display:inline-flex; align-items:center; gap:7px; background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.12); color:#fff; border-radius:9px; padding:8px 16px; font-size:.83rem; font-weight:600; text-decoration:none; transition:background .2s; }
.be-back:hover { background:rgba(255,255,255,.14); color:#fff; }
.be-layout { display:grid; grid-template-columns:1fr 340px; gap:20px; align-items:start; }
@media(max-width:960px){ .be-layout{ grid-template-columns:1fr; } }
.be-card { background:var(--p-surface); border:1px solid var(--p-border); border-radius:var(--p-radius); box-shadow:var(--p-shadow); overflow:hidden; }
.be-card-head { padding:15px 22px; border-bottom:1px solid var(--p-border); background:var(--p-surface2); display:flex; align-items:center; gap:10px; }
.be-card-head .ic { width:34px; height:34px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:.82rem; }
.be-card-head h5 { font-size:.9rem; font-weight:700; margin:0; }
.be-card-head p  { font-size:.73rem; color:var(--p-muted); margin:2px 0 0; }
.be-body { padding:22px; }
.be-field { margin-bottom:17px; }
.be-field label { display:block; font-size:.73rem; font-weight:700; letter-spacing:.07em; text-transform:uppercase; color:var(--p-muted); margin-bottom:7px; }
.be-field label .req { color:var(--p-danger); }
.be-field input,.be-field select,.be-field textarea {
  width:100%; background:var(--p-surface2); border:1px solid var(--p-border);
  border-radius:var(--p-radius-sm); padding:10px 14px; color:var(--p-text);
  font-family:'Plus Jakarta Sans',sans-serif; font-size:.875rem; outline:none;
  transition:border-color .2s,box-shadow .2s; box-sizing:border-box; resize:vertical;
}
.be-field input:focus,.be-field select:focus,.be-field textarea:focus { border-color:var(--p-warning); box-shadow:0 0 0 3px rgba(245,158,11,.12); }
.be-field select option { background:#1a1d27; }
.be-field .err { color:#fca5a5; font-size:.76rem; margin-top:5px; display:block; }

/* current image */
.be-current-img { display:flex; align-items:center; gap:14px; background:var(--p-surface2); border:1px solid var(--p-border); border-radius:10px; padding:12px 16px; margin-bottom:12px; }
.be-current-img img { width:80px; height:60px; object-fit:cover; border-radius:8px; border:1px solid var(--p-border); }
.be-current-img .info strong { font-size:.85rem; font-weight:600; color:var(--p-text); display:block; }
.be-current-img .info span   { font-size:.76rem; color:var(--p-muted); }
.be-no-img { background:var(--p-surface2); border:1px solid var(--p-border); border-radius:10px; padding:14px 16px; margin-bottom:12px; display:flex; align-items:center; gap:10px; color:var(--p-muted); font-size:.82rem; }

.be-upload { border:2px dashed var(--p-border); border-radius:10px; padding:18px; text-align:center; cursor:pointer; transition:all .2s; position:relative; background:var(--p-surface2); }
.be-upload:hover { border-color:var(--p-warning); background:rgba(245,158,11,.05); }
.be-upload input { position:absolute; inset:0; opacity:0; cursor:pointer; width:100%; height:100%; }
.be-upload i { font-size:1.3rem; color:var(--p-muted); display:block; margin-bottom:5px; }
.be-upload span { font-size:.78rem; color:var(--p-muted); }
.be-preview { display:none; width:100%; height:150px; object-fit:cover; border-radius:9px; border:1px solid var(--p-border); margin-top:10px; }

.be-status-row { display:flex; gap:10px; }
.be-status-opt { flex:1; }
.be-status-opt input[type="radio"] { display:none; }
.be-status-opt label { display:flex; align-items:center; justify-content:center; gap:7px; padding:10px; border-radius:8px; border:1px solid var(--p-border); cursor:pointer; font-size:.83rem; font-weight:600; transition:all .2s; background:var(--p-surface2); color:var(--p-muted); text-transform:none; letter-spacing:0; }
.be-status-opt input:checked + label.lbl-pub { background:rgba(34,197,94,.12); color:#86efac; border-color:rgba(34,197,94,.3); }
.be-status-opt input:checked + label.lbl-dft { background:rgba(239,68,68,.12); color:#fca5a5; border-color:rgba(239,68,68,.3); }

.be-submit { width:100%; padding:12px; border:none; border-radius:var(--p-radius-sm); background:linear-gradient(135deg,var(--p-warning),#d97706); color:#1a1d27; font-family:'Plus Jakarta Sans',sans-serif; font-weight:700; font-size:.9rem; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; transition:all .2s; margin-top:4px; }
.be-submit:hover { transform:translateY(-2px); box-shadow:0 6px 18px rgba(245,158,11,.35); }
.be-err-box { background:rgba(239,68,68,.08); border:1px solid rgba(239,68,68,.2); border-radius:10px; padding:14px 18px; margin-bottom:18px; }
.be-err-box ul { margin:0; padding-left:18px; }
.be-err-box li { font-size:.83rem; color:#fca5a5; margin-bottom:3px; }

/* slug preview */
.be-slug-box { background:var(--p-surface2); border:1px solid var(--p-border); border-radius:8px; padding:10px 14px; font-family:'JetBrains Mono',monospace; font-size:.78rem; color:var(--p-accent2); word-break:break-all; }
</style>

<div class="be-wrap">
  <div class="be-header">
    <div>
      <div class="title"><i class="fas fa-pen me-2"></i>Edit Blog Post</div>
      <div class="subtitle">Update and republish — {{ Str::limit($blog->title, 50) }}</div>
    </div>
    <div class="be-header-btns">
      <a href="{{ route('admin.blogs.index') }}" class="be-back"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
  </div>

  @if($errors->any())
    <div class="be-err-box"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
  @endif

  <form action="{{ route('admin.blogs.update',$blog->slug) }}" method="POST" enctype="multipart/form-data">
  @csrf
  <div class="be-layout">

    {{-- LEFT --}}
    <div style="display:flex;flex-direction:column;gap:18px;">
      <div class="be-card">
        <div class="be-card-head">
          <div class="ic" style="background:rgba(245,158,11,.15);color:#fcd34d;"><i class="fas fa-heading"></i></div>
          <div><h5>Post Content</h5><p>Title, category and main body</p></div>
        </div>
        <div class="be-body">
          <div class="be-field">
            <label>Blog Title <span class="req">*</span></label>
            <input type="text" name="title" value="{{ old('title',$blog->title) }}" placeholder="Blog title...">
            @error('title')<span class="err">{{ $message }}</span>@enderror
          </div>
          <div class="be-field">
            <label>Category <span class="req">*</span></label>
            <select name="blog_category_id">
              <option value="">— Select Category —</option>
              @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ old('blog_category_id',$blog->blog_category_id)==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
              @endforeach
            </select>
            @error('blog_category_id')<span class="err">{{ $message }}</span>@enderror
          </div>
          <div class="be-field">
            <label>Current Slug</label>
            <div class="be-slug-box">{{ $blog->slug }}</div>
          </div>
          <div class="be-field" style="margin-bottom:0;">
            <label>Description <span class="req">*</span></label>
            <textarea name="description" rows="12">{{ old('description',$blog->description) }}</textarea>
            @error('description')<span class="err">{{ $message }}</span>@enderror
          </div>
        </div>
      </div>

      <div class="be-card">
        <div class="be-card-head">
          <div class="ic" style="background:rgba(14,165,233,.15);color:var(--p-accent2);"><i class="fas fa-search"></i></div>
          <div><h5>SEO Meta</h5><p>Optional — helps search engines</p></div>
        </div>
        <div class="be-body">
          <div class="be-field">
            <label>Meta Title</label>
            <input type="text" name="meta_title" value="{{ old('meta_title',$blog->meta_title) }}" placeholder="SEO title...">
          </div>
          <div class="be-field" style="margin-bottom:0;">
            <label>Meta Description</label>
            <textarea name="meta_description" rows="3">{{ old('meta_description',$blog->meta_description) }}</textarea>
          </div>
        </div>
      </div>
    </div>

    {{-- RIGHT --}}
    <div style="display:flex;flex-direction:column;gap:18px;position:sticky;top:20px;">
      <div class="be-card">
        <div class="be-card-head">
          <div class="ic" style="background:rgba(34,197,94,.15);color:#86efac;"><i class="fas fa-save"></i></div>
          <div><h5>Update Post</h5><p>Save your changes</p></div>
        </div>
        <div class="be-body">
          <div class="be-field" style="margin-bottom:14px;">
            <label>Visibility</label>
            <div class="be-status-row">
              <div class="be-status-opt">
                <input type="radio" name="status" id="s1" value="1" {{ old('status',$blog->status)==1?'checked':'' }}>
                <label for="s1" class="lbl-pub"><i class="fas fa-globe"></i> Published</label>
              </div>
              <div class="be-status-opt">
                <input type="radio" name="status" id="s0" value="0" {{ old('status',$blog->status)==0?'checked':'' }}>
                <label for="s0" class="lbl-dft"><i class="fas fa-lock"></i> Draft</label>
              </div>
            </div>
          </div>
          <button type="submit" class="be-submit"><i class="fas fa-save"></i> Update Post</button>
        </div>
      </div>

      <div class="be-card">
        <div class="be-card-head">
          <div class="ic" style="background:rgba(139,92,246,.15);color:#c4b5fd;"><i class="fas fa-image"></i></div>
          <div><h5>Cover Image</h5><p>Upload to replace current</p></div>
        </div>
        <div class="be-body">
          @if($blog->image)
            <div class="be-current-img">
              <img src="{{ asset('uploads/blogs/'.$blog->image) }}" alt="Current">
              <div class="info">
                <strong>Current Image</strong>
                <span>Upload below to replace</span>
              </div>
            </div>
          @else
            <div class="be-no-img"><i class="fas fa-image-slash"></i> No image set</div>
          @endif
          <div class="be-upload" id="uploadZone">
            <input type="file" name="image" id="imgInput" accept="image/*" onchange="previewImg(this)">
            <i class="fas fa-cloud-upload-alt"></i>
            <span id="uploadLabel">Click to upload new image</span>
          </div>
          <img id="imgPreview" class="be-preview" src="" alt="">
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
  document.getElementById('uploadZone').style.borderColor='var(--p-warning)';
}
</script>
@endsection