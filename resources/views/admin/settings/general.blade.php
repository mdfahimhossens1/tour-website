@extends('layouts.admin')
@section('title', 'General Settings')
@section('page')

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');
:root {
  --s-surface:   #1a1d27;
  --s-surface2:  #222636;
  --s-border:    rgba(255,255,255,.07);
  --s-accent:    #6366f1;
  --s-accent2:   #818cf8;
  --s-success:   #22c55e;
  --s-danger:    #ef4444;
  --s-text:      #e2e8f0;
  --s-muted:     #64748b;
  --s-radius:    14px;
  --s-radius-sm: 8px;
  --s-shadow:    0 8px 32px rgba(0,0,0,.45);
}
.s-wrap { font-family:'Plus Jakarta Sans',sans-serif; color:var(--s-text); max-width:100%; margin:0 auto; }

/* hero */
.s-hero {
  background:linear-gradient(135deg,#1e1b4b 0%,#312e81 55%,#3730a3 100%);
  border-radius:var(--s-radius); padding:28px 32px 72px;
  position:relative; overflow:hidden; box-shadow:var(--s-shadow); margin-bottom:-52px;
}
.s-hero::before { content:''; position:absolute; inset:0; background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%236366f1' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='20'/%3E%3C/g%3E%3C/svg%3E"); }
.s-hero::after { content:''; position:absolute; right:-40px; top:-40px; width:180px; height:180px; border-radius:50%; background:radial-gradient(circle,rgba(99,102,241,.2) 0%,transparent 70%); }
.s-hero-nav { position:relative; z-index:1; display:flex; justify-content:space-between; align-items:center; }
.s-breadcrumb { font-size:.8rem; color:rgba(255,255,255,.45); }
.s-breadcrumb a { color:rgba(255,255,255,.45); text-decoration:none; }
.s-breadcrumb a:hover { color:rgba(255,255,255,.7); }
.s-breadcrumb span { color:rgba(255,255,255,.8); }

/* main card */
.s-main-card {
  background:var(--s-surface); border:1px solid var(--s-border);
  border-radius:var(--s-radius); box-shadow:var(--s-shadow);
  position:relative; z-index:2;
}
.s-card-header {
  padding:24px 28px 20px;
  border-bottom:1px solid var(--s-border);
  display:flex; align-items:center; gap:14px;
}
.s-card-icon { width:42px; height:42px; border-radius:12px; background:rgba(99,102,241,.15); border:1px solid rgba(99,102,241,.2); display:flex; align-items:center; justify-content:center; color:var(--s-accent2); font-size:1rem; flex-shrink:0; }
.s-card-title { font-size:1.1rem; font-weight:700; }
.s-card-sub   { font-size:.8rem; color:var(--s-muted); margin-top:2px; }
.s-card-body  { padding:28px; }
.s-card-footer{ padding:18px 28px; border-top:1px solid var(--s-border); display:flex; gap:10px; justify-content:flex-end; }

/* section title */
.s-section { font-size:.7rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:var(--s-accent2); margin:0 0 16px; display:flex; align-items:center; gap:8px; }
.s-section::after { content:''; flex:1; height:1px; background:var(--s-border); }
.s-divider { height:1px; background:var(--s-border); margin:24px 0; }

/* fields */
.s-field { margin-bottom:18px; }
.s-field label { display:block; font-size:.8rem; font-weight:600; color:var(--s-muted); margin-bottom:7px; text-transform:uppercase; letter-spacing:.06em; }
.s-field input,.s-field textarea {
  width:100%; background:var(--s-surface2); border:1px solid var(--s-border);
  border-radius:var(--s-radius-sm); padding:11px 14px; color:var(--s-text);
  font-family:inherit; font-size:.875rem; outline:none; resize:vertical;
  transition:border-color .2s,box-shadow .2s;
}
.s-field input:focus,.s-field textarea:focus { border-color:var(--s-accent); box-shadow:0 0 0 3px rgba(99,102,241,.12); }
.s-field input[type="file"] { padding:8px 12px; cursor:pointer; }
.s-field .err { color:#fca5a5; font-size:.78rem; margin-top:5px; }
.s-grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:0 18px; }
@media(max-width:560px){ .s-grid-2 { grid-template-columns:1fr; } }

/* logo preview */
.s-logo-preview {
  margin-top:12px; padding:14px 16px;
  background:var(--s-surface2); border:1px solid var(--s-border);
  border-radius:var(--s-radius-sm); display:flex; align-items:center; gap:14px;
}
.s-logo-preview img { height:44px; object-fit:contain; border-radius:6px; max-width:160px; }
.s-logo-placeholder { color:var(--s-muted); font-size:.82rem; font-style:italic; }

/* info card */
.s-info-card {
  background:var(--s-surface2); border:1px solid var(--s-border);
  border-radius:var(--s-radius-sm); padding:14px 16px;
  display:flex; align-items:flex-start; gap:10px; margin-bottom:20px;
  font-size:.82rem; color:var(--s-muted); line-height:1.5;
}
.s-info-card i { color:var(--s-accent2); margin-top:2px; flex-shrink:0; }

/* buttons */
.s-btn { display:inline-flex; align-items:center; gap:6px; border:none; cursor:pointer; font-family:inherit; font-weight:600; border-radius:var(--s-radius-sm); transition:all .2s; text-decoration:none; }
.s-btn-primary { background:var(--s-accent); color:#fff; padding:10px 22px; font-size:.875rem; }
.s-btn-primary:hover { background:var(--s-accent2); transform:translateY(-1px); box-shadow:0 4px 14px rgba(99,102,241,.4); }
.s-btn-outline { background:transparent; color:var(--s-text); border:1px solid var(--s-border); padding:10px 16px; font-size:.875rem; }
.s-btn-outline:hover { background:var(--s-surface2); color:var(--s-text); }

/* toast */
#s-toast-container { position:fixed; bottom:28px; right:28px; z-index:99999; display:flex; flex-direction:column; gap:10px; }
.s-toast { display:flex; align-items:center; gap:12px; background:var(--s-surface); border:1px solid var(--s-border); border-radius:12px; padding:14px 18px; min-width:280px; box-shadow:0 8px 30px rgba(0,0,0,.5); transform:translateX(120%); transition:transform .35s cubic-bezier(.34,1.56,.64,1); font-family:'Plus Jakarta Sans',sans-serif; position:relative; overflow:hidden; }
.s-toast.show { transform:translateX(0); }
.s-toast-icon { width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.s-toast-success .s-toast-icon { background:rgba(34,197,94,.15); color:var(--s-success); }
.s-toast-danger  .s-toast-icon { background:rgba(239,68,68,.15);  color:var(--s-danger); }
.s-toast-title { font-size:.875rem; font-weight:700; color:var(--s-text); }
.s-toast-msg   { font-size:.78rem;  color:var(--s-muted); margin-top:2px; }
.s-toast-bar   { position:absolute; bottom:0; left:0; height:3px; border-radius:0 0 12px 12px; animation:sBar 3.5s linear forwards; }
.s-toast-success .s-toast-bar { background:var(--s-success); }
.s-toast-danger  .s-toast-bar { background:var(--s-danger); }
@keyframes sBar { from{width:100%} to{width:0%} }
</style>

@if(session('success'))<div id="flash-success" data-msg="{{ session('success') }}"></div>@endif
@if(session('error'))<div id="flash-error"   data-msg="{{ session('error') }}"></div>@endif

<div class="s-wrap">

  {{-- Hero --}}
  <div class="s-hero">
    <div class="s-hero-nav">
      <div class="s-breadcrumb">
        <a href="{{ route('admin.settings.index') }}"><i class="fas fa-cogs me-1"></i>Settings</a>
        <i class="fas fa-chevron-right mx-2" style="font-size:.55rem;opacity:.5;"></i>
        <span>General Settings</span>
      </div>
      <a href="{{ route('admin.settings.index') }}" class="s-btn s-btn-outline" style="font-size:.78rem;padding:7px 14px;">
        <i class="fas fa-arrow-left"></i> Back
      </a>
    </div>
  </div>

  {{-- Form Card --}}
  <div class="s-main-card">
    <div class="s-card-header">
      <div class="s-card-icon"><i class="fas fa-globe"></i></div>
      <div>
        <div class="s-card-title">General Settings</div>
        <div class="s-card-sub">Configure your website identity and contact information</div>
      </div>
    </div>

    <form method="POST" action="{{ route('admin.settings.general.update') }}" enctype="multipart/form-data">
      @csrf
      <div class="s-card-body">

        <div class="s-section"><i class="fas fa-id-card"></i> Site Identity</div>
        <div class="s-field">
          <label>Site Name</label>
          <input type="text" name="site_name" value="{{ old('site_name', $settings['site_name'] ?? '') }}" placeholder="My Tour Website">
          @error('site_name')<div class="err">{{ $message }}</div>@enderror
        </div>

        <div class="s-divider"></div>
        <div class="s-section"><i class="fas fa-address-card"></i> Contact Information</div>

        <div class="s-grid-2">
          <div class="s-field">
            <label>Email Address</label>
            <input type="email" name="site_email" value="{{ old('site_email', $settings['site_email'] ?? '') }}" placeholder="info@yoursite.com">
            @error('site_email')<div class="err">{{ $message }}</div>@enderror
          </div>
          <div class="s-field">
            <label>Phone Number</label>
            <input type="text" name="phone" value="{{ old('phone', $settings['phone'] ?? '') }}" placeholder="+880 1xxx-xxxxxx">
            @error('phone')<div class="err">{{ $message }}</div>@enderror
          </div>
        </div>

        <div class="s-field">
          <label>Business Address</label>
          <textarea name="address" rows="3" placeholder="Full business address...">{{ old('address', $settings['address'] ?? '') }}</textarea>
        </div>

        <div class="s-divider"></div>
        <div class="s-section"><i class="fas fa-image"></i> Branding</div>

        <div class="s-info-card">
          <i class="fas fa-info-circle"></i>
          <span>Upload your site logo. Recommended size: 200×60px. Supported formats: PNG, JPG, SVG, WEBP.</span>
        </div>

        <div class="s-field">
          <label>Site Logo</label>
          <input type="file" name="logo" accept="image/*" onchange="previewLogo(this)">
          @error('logo')<div class="err">{{ $message }}</div>@enderror

          <div class="s-logo-preview" id="logo-preview-wrap">
            @if($settings['logo'] ?? false)
              <img id="logo-preview-img" src="{{ asset('uploads/settings/'.$settings['logo']) }}" alt="Current Logo">
              <div>
                <div style="font-size:.78rem;font-weight:600;color:var(--s-text);">Current Logo</div>
                <div style="font-size:.72rem;color:var(--s-muted);">Upload a new file to replace</div>
              </div>
            @else
              <i class="fas fa-image" style="color:var(--s-muted);font-size:1.2rem;"></i>
              <span class="s-logo-placeholder" id="logo-placeholder">No logo uploaded yet</span>
              <img id="logo-preview-img" style="display:none;" alt="Preview">
            @endif
          </div>
        </div>

      </div>

      <div class="s-card-footer">
        <a href="{{ route('admin.settings.index') }}" class="s-btn s-btn-outline">Cancel</a>
        <button type="submit" class="s-btn s-btn-primary"><i class="fas fa-save"></i> Save General Settings</button>
      </div>
    </form>
  </div>

</div>

<div id="s-toast-container"></div>

<script>
function previewLogo(input) {
  var img  = document.getElementById('logo-preview-img');
  var ph   = document.getElementById('logo-placeholder');
  if(input.files && input.files[0]) {
    var r = new FileReader();
    r.onload = e => {
      img.src = e.target.result;
      img.style.display = 'block';
      if(ph) ph.style.display = 'none';
    };
    r.readAsDataURL(input.files[0]);
  }
}

function showToast(type, title, msg) {
  var icons = {success:'fas fa-check-circle', danger:'fas fa-exclamation-circle'};
  var c = document.getElementById('s-toast-container');
  var t = document.createElement('div');
  t.className = 's-toast s-toast-' + type;
  t.innerHTML = `<div class="s-toast-icon"><i class="${icons[type]}"></i></div>
    <div><div class="s-toast-title">${title}</div><div class="s-toast-msg">${msg}</div></div>
    <span class="s-toast-bar"></span>`;
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