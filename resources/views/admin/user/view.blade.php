@extends('layouts.admin')
@section('page')

@php
  $isSuperAdmin = strtolower(optional(Auth::user()->role)->role_name ?? '') === 'super_admin';
  $roles = App\Models\Role::all();
  $rk = str_replace([' ','-'],'_', strtolower(trim(optional($user->role)->role_name ?? 'user')));
  $badgeClass = match($rk) {
    'super_admin' => ['cls'=>'um-badge-super',   'color'=>'#fca5a5', 'icon'=>'fas fa-crown'],
    'admin'       => ['cls'=>'um-badge-admin',   'color'=>'#a5b4fc', 'icon'=>'fas fa-shield-alt'],
    'manager'     => ['cls'=>'um-badge-manager', 'color'=>'#fcd34d', 'icon'=>'fas fa-user-tie'],
    'viewer'      => ['cls'=>'um-badge-viewer',  'color'=>'#94a3b8', 'icon'=>'fas fa-eye'],
    default       => ['cls'=>'um-badge-default', 'color'=>'#5eead4', 'icon'=>'fas fa-user'],
  };
@endphp

<style>
  @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');

  :root {
    --um-surface:  #1a1d27;
    --um-surface2: #222636;
    --um-border:   rgba(255,255,255,.07);
    --um-accent:   #6c63ff;
    --um-success:  #22c55e;
    --um-danger:   #ef4444;
    --um-info:     #38bdf8;
    --um-teal:     #2dd4bf;
    --um-text:     #e2e8f0;
    --um-muted:    #64748b;
    --um-radius:   14px;
    --um-radius-sm:8px;
    --um-shadow:   0 8px 32px rgba(0,0,0,.45);
  }

  .uv-wrap { font-family:'Plus Jakarta Sans',sans-serif; color:var(--um-text); max-width:900px; margin:0 auto; }

  /* hero */
  .uv-hero {
    background: linear-gradient(135deg,#1e1b4b 0%,#312e81 55%,#4c1d95 100%);
    border-radius:var(--um-radius); padding:40px 36px 80px;
    position:relative; overflow:hidden; box-shadow:var(--um-shadow); margin-bottom:-60px;
  }
  .uv-hero::before {
    content:''; position:absolute; inset:0;
    background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
  }
  .uv-hero-actions { position:relative; z-index:1; display:flex; justify-content:space-between; align-items:center; }
  .uv-breadcrumb { font-size:.8rem; color:rgba(255,255,255,.45); }
  .uv-breadcrumb span { color:rgba(255,255,255,.8); }

  /* profile card */
  .uv-profile-card {
    position:relative; z-index:2;
    background:var(--um-surface); border:1px solid var(--um-border);
    border-radius:var(--um-radius); padding:0 32px 32px;
    box-shadow:var(--um-shadow); margin-bottom:20px;
  }
  .uv-avatar-wrap { display:flex; align-items:flex-end; gap:24px; margin-top:-50px; margin-bottom:24px; }
  .uv-avatar {
    width:100px; height:100px; border-radius:50%; object-fit:cover;
    border:4px solid var(--um-surface);
    box-shadow:0 0 0 3px {{ $badgeClass['color'] }}55;
    flex-shrink:0;
  }
  .uv-profile-name  { font-size:1.5rem; font-weight:700; line-height:1.2; }
  .uv-profile-email { color:var(--um-muted); font-size:.875rem; margin-top:3px; }
  .uv-divider { height:1px; background:var(--um-border); margin:24px 0; }

  /* info grid */
  .uv-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
  @media(max-width:580px){ .uv-grid{grid-template-columns:1fr;} .uv-hero{padding-bottom:70px;} }
  .uv-info-item {
    background:var(--um-surface2); border:1px solid var(--um-border);
    border-radius:var(--um-radius-sm); padding:16px 18px; transition:border-color .2s;
  }
  .uv-info-item:hover { border-color:rgba(255,255,255,.14); }
  .uv-info-label {
    font-size:.72rem; font-weight:700; letter-spacing:.08em;
    text-transform:uppercase; color:var(--um-muted); margin-bottom:6px;
    display:flex; align-items:center; gap:6px;
  }
  .uv-info-value { font-size:.925rem; font-weight:500; color:var(--um-text); }
  .uv-info-value.mono { font-family:'JetBrains Mono',monospace; font-size:.85rem; color:var(--um-muted); }

  /* badges */
  .um-badge { display:inline-flex;align-items:center;gap:5px;padding:4px 12px;border-radius:20px;font-size:.75rem;font-weight:700;letter-spacing:.04em; }
  .um-badge-super   { background:rgba(239,68,68,.15); color:#fca5a5; border:1px solid rgba(239,68,68,.3); }
  .um-badge-admin   { background:rgba(99,102,241,.15);color:#a5b4fc; border:1px solid rgba(99,102,241,.3); }
  .um-badge-manager { background:rgba(245,158,11,.15);color:#fcd34d; border:1px solid rgba(245,158,11,.3); }
  .um-badge-viewer  { background:rgba(100,116,139,.15);color:#94a3b8;border:1px solid rgba(100,116,139,.3); }
  .um-badge-default { background:rgba(45,212,191,.12); color:#5eead4; border:1px solid rgba(45,212,191,.25); }
  .um-status-active   { background:rgba(34,197,94,.12); color:#86efac; border:1px solid rgba(34,197,94,.25); }
  .um-status-inactive { background:rgba(239,68,68,.12); color:#fca5a5; border:1px solid rgba(239,68,68,.25); }
  .uv-status-dot { width:10px;height:10px;border-radius:50%;display:inline-block;margin-right:4px; }

  /* buttons */
  .um-btn { display:inline-flex;align-items:center;gap:6px;border:none;cursor:pointer;font-family:inherit;font-weight:600;border-radius:var(--um-radius-sm);transition:all .2s;text-decoration:none; }
  .um-btn-primary { background:var(--um-accent);color:#fff;padding:9px 18px;font-size:.85rem; }
  .um-btn-primary:hover { background:#7c74ff;transform:translateY(-1px);box-shadow:0 4px 14px rgba(108,99,255,.4);color:#fff; }
  .um-btn-outline { background:transparent;color:var(--um-text);border:1px solid var(--um-border);padding:9px 14px;font-size:.82rem; }
  .um-btn-outline:hover { background:var(--um-surface2);border-color:rgba(255,255,255,.15);color:var(--um-text); }
  .um-btn-info { background:var(--um-info);color:#0f2027;padding:9px 18px;font-size:.85rem; }
  .um-btn-info:hover { background:#7dd3fc;transform:translateY(-1px);color:#0f2027; }

  /* MODAL */
  .um-modal-overlay {
    position:fixed;inset:0;z-index:9999;
    background:rgba(0,0,0,.72);backdrop-filter:blur(6px);
    display:flex;align-items:center;justify-content:center;
    opacity:0;pointer-events:none;transition:opacity .25s;
  }
  .um-modal-overlay.open { opacity:1;pointer-events:auto; }
  .um-modal {
    background:var(--um-surface);border:1px solid var(--um-border);
    border-radius:18px;width:min(640px,96vw);max-height:90vh;overflow-y:auto;
    box-shadow:0 24px 64px rgba(0,0,0,.6);
    transform:translateY(24px) scale(.97);
    transition:transform .3s cubic-bezier(.34,1.56,.64,1);
  }
  .um-modal-overlay.open .um-modal { transform:translateY(0) scale(1); }
  .um-modal-header { padding:22px 28px 18px;border-bottom:1px solid var(--um-border);display:flex;align-items:center;justify-content:space-between; }
  .um-modal-title  { font-size:1.1rem;font-weight:700; }
  .um-modal-close  {
    background:var(--um-surface2);border:1px solid var(--um-border);
    color:var(--um-muted);width:32px;height:32px;border-radius:8px;
    cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .2s;
  }
  .um-modal-close:hover { color:var(--um-text); }
  .um-modal-body   { padding:24px 28px; }
  .um-modal-footer { padding:18px 28px;border-top:1px solid var(--um-border);display:flex;gap:10px;justify-content:flex-end; }

  /* form fields */
  .um-field { margin-bottom:18px; }
  .um-field label { display:block;font-size:.8rem;font-weight:600;color:var(--um-muted);margin-bottom:7px;text-transform:uppercase;letter-spacing:.06em; }
  .um-field input,.um-field select {
    width:100%;background:var(--um-surface2);border:1px solid var(--um-border);
    border-radius:var(--um-radius-sm);padding:10px 14px;color:var(--um-text);
    font-family:inherit;font-size:.875rem;outline:none;transition:border-color .2s,box-shadow .2s;
  }
  .um-field input:focus,.um-field select:focus { border-color:var(--um-accent);box-shadow:0 0 0 3px rgba(108,99,255,.15); }
  .um-field input[type="file"] { padding:8px 12px;cursor:pointer; }
  .um-field .preview-wrap { margin-top:10px; }
  .um-field .preview-wrap img { width:60px;height:60px;border-radius:50%;object-fit:cover;border:2px solid var(--um-border); }
  .um-grid-2 { display:grid;grid-template-columns:1fr 1fr;gap:0 18px; }

  /* toast */
  #um-toast-container { position:fixed;bottom:28px;right:28px;z-index:99999;display:flex;flex-direction:column;gap:10px; }
  .um-toast {
    display:flex;align-items:center;gap:12px;
    background:var(--um-surface);border:1px solid var(--um-border);
    border-radius:12px;padding:14px 18px;min-width:280px;
    box-shadow:0 8px 30px rgba(0,0,0,.5);
    transform:translateX(120%);transition:transform .35s cubic-bezier(.34,1.56,.64,1);
    font-family:'Plus Jakarta Sans',sans-serif;position:relative;overflow:hidden;
  }
  .um-toast.show { transform:translateX(0); }
  .um-toast-icon { width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:.95rem; }
  .um-toast-success .um-toast-icon { background:rgba(34,197,94,.15); color:var(--um-success); }
  .um-toast-danger  .um-toast-icon { background:rgba(239,68,68,.15);  color:var(--um-danger); }
  .um-toast-title { font-size:.875rem;font-weight:700;color:var(--um-text); }
  .um-toast-msg   { font-size:.78rem;color:var(--um-muted);margin-top:2px; }
  .um-toast-bar   { position:absolute;bottom:0;left:0;height:3px;border-radius:0 0 12px 12px;animation:toastBar 3.5s linear forwards; }
  .um-toast-success .um-toast-bar { background:var(--um-success); }
  .um-toast-danger  .um-toast-bar { background:var(--um-danger); }
  @keyframes toastBar { from{width:100%} to{width:0%} }
  .um-modal::-webkit-scrollbar { width:5px; }
  .um-modal::-webkit-scrollbar-thumb { background:var(--um-border);border-radius:10px; }
</style>

{{-- Flash --}}
@if(session('success'))<div id="flash-success" data-msg="{{ session('success') }}"></div>@endif
@if(session('error'))<div id="flash-error" data-msg="{{ session('error') }}"></div>@endif

<div class="uv-wrap">

  {{-- Hero --}}
  <div class="uv-hero">
    <div class="uv-hero-actions">
      <div class="uv-breadcrumb">
        <a href="{{ route('admin.users.index') }}" style="color:rgba(255,255,255,.45);text-decoration:none;">
          <i class="fas fa-users me-1"></i>All Users
        </a>
        <i class="fas fa-chevron-right mx-2" style="font-size:.6rem;opacity:.5;"></i>
        <span>{{ $user->name }}</span>
      </div>
      <div class="d-flex gap-2">
        <a href="{{ route('admin.users.index') }}" class="um-btn um-btn-outline" style="font-size:.78rem;padding:7px 14px;">
          <i class="fas fa-arrow-left"></i> Back
        </a>
        <button onclick="openEditModal()" class="um-btn um-btn-info" style="font-size:.78rem;padding:7px 14px;color:#0f2027;">
          <i class="fas fa-pen"></i> Edit
        </button>
      </div>
    </div>
  </div>

  {{-- Profile Card --}}
  <div class="uv-profile-card">
    <div class="uv-avatar-wrap">
      @if($user->photo)
        <img src="{{ asset('uploads/users/'.$user->photo) }}" class="uv-avatar" alt="{{ $user->name }}">
      @else
        <img src="{{ asset('contents/admin/images/avatar.jpg') }}" class="uv-avatar" alt="Default">
      @endif
      <div style="padding-bottom:6px;">
        <div class="uv-profile-name">{{ $user->name }}</div>
        <div class="uv-profile-email"><i class="fas fa-envelope me-1" style="font-size:.75rem;"></i>{{ $user->email }}</div>
        <div class="d-flex gap-2 mt-2 flex-wrap">
          <span class="um-badge {{ $badgeClass['cls'] }}">
            <i class="{{ $badgeClass['icon'] }}" style="font-size:.6rem;"></i>
            {{ $user->role->role_name ?? 'N/A' }}
          </span>
          @if($user->status == 1)
            <span class="um-badge um-status-active">
              <span class="uv-status-dot" style="background:#22c55e;box-shadow:0 0 6px #22c55e88;"></span>Active
            </span>
          @else
            <span class="um-badge um-status-inactive">
              <span class="uv-status-dot" style="background:#ef4444;"></span>Inactive
            </span>
          @endif
        </div>
      </div>
    </div>

    <div class="uv-divider"></div>

    <div class="uv-grid">

      <div class="uv-info-item">
        <div class="uv-info-label"><i class="fas fa-user"></i> Full Name</div>
        <div class="uv-info-value">{{ $user->name }}</div>
      </div>

      <div class="uv-info-item">
        <div class="uv-info-label"><i class="fas fa-at"></i> Username</div>
        <div class="uv-info-value mono">{{ $user->username }}</div>
      </div>

      <div class="uv-info-item">
        <div class="uv-info-label"><i class="fas fa-envelope"></i> Email</div>
        <div class="uv-info-value">{{ $user->email }}</div>
      </div>

      <div class="uv-info-item">
        <div class="uv-info-label"><i class="fas fa-phone"></i> Phone</div>
        <div class="uv-info-value mono">{{ $user->phone ?? '—' }}</div>
      </div>

      <div class="uv-info-item">
        <div class="uv-info-label"><i class="fas fa-user-plus"></i> Created By</div>
        <div class="uv-info-value">
          @if($user->creatorUser)
            <div style="display:flex;align-items:center;gap:8px;">
              <span style="width:28px;height:28px;border-radius:50%;background:var(--um-accent);display:inline-flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:700;flex-shrink:0;">
                {{ strtoupper(substr($user->creatorUser->name,0,1)) }}
              </span>
              {{ $user->creatorUser->name }}
            </div>
          @else
            <span style="color:var(--um-muted);">Self-Registered</span>
          @endif
        </div>
      </div>

      <div class="uv-info-item">
        <div class="uv-info-label"><i class="fas fa-user-edit"></i> Last Edited By</div>
        <div class="uv-info-value">
          @if($user->editorInfo)
            <div style="display:flex;align-items:center;gap:8px;">
              <span style="width:28px;height:28px;border-radius:50%;background:var(--um-info);display:inline-flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:700;flex-shrink:0;color:#0f2027;">
                {{ strtoupper(substr($user->editorInfo->name,0,1)) }}
              </span>
              {{ $user->editorInfo->name }}
            </div>
          @else
            <span style="color:var(--um-muted);">N/A</span>
          @endif
        </div>
      </div>

      <div class="uv-info-item">
        <div class="uv-info-label"><i class="fas fa-shield-alt"></i> Role</div>
        <div class="uv-info-value">
          <span class="um-badge {{ $badgeClass['cls'] }}">
            <i class="{{ $badgeClass['icon'] }}" style="font-size:.6rem;"></i>
            {{ $user->role->role_name ?? 'N/A' }}
          </span>
        </div>
      </div>

      <div class="uv-info-item">
        <div class="uv-info-label"><i class="fas fa-calendar-alt"></i> Joined</div>
        <div class="uv-info-value">
          {{ date('d M Y', strtotime($user->created_at)) }}
          <span style="color:var(--um-muted);font-size:.8rem;margin-left:6px;">
            {{ $user->created_at->diffForHumans() }}
          </span>
        </div>
      </div>

    </div>

    <div class="uv-divider"></div>
    <div class="d-flex gap-2 justify-content-end flex-wrap">
      <a href="{{ route('admin.users.index') }}" class="um-btn um-btn-outline">
        <i class="fas fa-arrow-left"></i> Back to Users
      </a>
      <button onclick="openEditModal()" class="um-btn um-btn-primary">
        <i class="fas fa-pen"></i> Edit This User
      </button>
    </div>
  </div>

</div>

{{-- ═══ EDIT MODAL ═══ --}}
<div class="um-modal-overlay" id="editModal">
  <div class="um-modal">
    <div class="um-modal-header">
      <div class="um-modal-title"><i class="fas fa-user-edit me-2" style="color:var(--um-info)"></i>Edit User</div>
      <button class="um-modal-close" onclick="closeModal()"><i class="fas fa-times"></i></button>
    </div>

    <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <input type="hidden" name="id"   value="{{ $user->id }}">
      <input type="hidden" name="slug" value="{{ $user->slug }}">

      <div class="um-modal-body">

        <div class="um-grid-2">
          <div class="um-field">
            <label>Full Name <span style="color:var(--um-danger)">*</span></label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" placeholder="John Doe">
            @error('name')<div style="color:#fca5a5;font-size:.78rem;margin-top:5px;">{{ $message }}</div>@enderror
          </div>
          <div class="um-field">
            <label>Phone</label>
            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+880 1xxx-xxxxxx">
            @error('phone')<div style="color:#fca5a5;font-size:.78rem;margin-top:5px;">{{ $message }}</div>@enderror
          </div>
        </div>

        <div class="um-grid-2">
          <div class="um-field">
            <label>Email <span style="color:var(--um-danger)">*</span></label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}">
            @error('email')<div style="color:#fca5a5;font-size:.78rem;margin-top:5px;">{{ $message }}</div>@enderror
          </div>
          <div class="um-field">
            <label>Username</label>
            <input type="text" value="{{ $user->username }}" disabled style="opacity:.55;">
          </div>
        </div>

        <div class="um-grid-2">
          <div class="um-field">
            <label>Role <span style="color:var(--um-danger)">*</span></label>
            @if($isSuperAdmin)
              <select name="role_id">
                <option value="">Select Role</option>
                @foreach($roles as $r)
                  <option value="{{ $r->id }}" {{ old('role_id', $user->role_id) == $r->id ? 'selected' : '' }}>
                    {{ ucfirst($r->role_name) }}
                  </option>
                @endforeach
              </select>
            @else
              <div style="padding:10px 14px;background:var(--um-surface2);border:1px solid var(--um-border);border-radius:var(--um-radius-sm);font-size:.875rem;color:var(--um-muted);">
                {{ $user->role->role_name ?? '—' }}
              </div>
            @endif
            @error('role_id')<div style="color:#fca5a5;font-size:.78rem;margin-top:5px;">{{ $message }}</div>@enderror
          </div>
          <div class="um-field">
            <label>Status <span style="color:var(--um-danger)">*</span></label>
            <select name="status">
              <option value="1" {{ old('status', $user->status) == 1 ? 'selected' : '' }}>Active</option>
              <option value="0" {{ old('status', $user->status) == 0 ? 'selected' : '' }}>Inactive</option>
            </select>
            @error('status')<div style="color:#fca5a5;font-size:.78rem;margin-top:5px;">{{ $message }}</div>@enderror
          </div>
        </div>

        <div class="um-field">
          <label>Photo</label>
          <input type="file" name="photo" accept="image/*" onchange="previewImg(this)">
          @error('photo')<div style="color:#fca5a5;font-size:.78rem;margin-top:5px;">{{ $message }}</div>@enderror
          <div class="preview-wrap">
            <img id="edit-preview"
              src="{{ $user->photo ? asset('uploads/users/'.$user->photo) : asset('contents/admin/images/avatar.jpg') }}">
          </div>
        </div>

      </div>

      <div class="um-modal-footer">
        <button type="button" class="um-btn um-btn-outline" onclick="closeModal()">Cancel</button>
        <button type="submit" class="um-btn um-btn-primary" style="background:var(--um-info);color:#fff;">
          <i class="fas fa-save"></i> Update User
        </button>
      </div>
    </form>
  </div>
</div>

<div id="um-toast-container"></div>

<script>
function openEditModal()  { document.getElementById('editModal').classList.add('open'); document.body.style.overflow='hidden'; }
function closeModal()     { document.getElementById('editModal').classList.remove('open'); document.body.style.overflow=''; }

document.getElementById('editModal').addEventListener('click', function(e){
  if(e.target === this) closeModal();
});
document.addEventListener('keydown', e => { if(e.key==='Escape') closeModal(); });

function previewImg(input) {
  var p = document.getElementById('edit-preview');
  if(input.files && input.files[0]) {
    var r = new FileReader();
    r.onload = e => { p.src = e.target.result; };
    r.readAsDataURL(input.files[0]);
  }
}

// validation error হলে modal খুলে রাখো
@if($errors->any())
  openEditModal();
@endif

// Toast
function showToast(type, title, msg) {
  var icons = { success:'fas fa-check-circle', danger:'fas fa-exclamation-circle' };
  var c = document.getElementById('um-toast-container');
  var t = document.createElement('div');
  t.className = 'um-toast um-toast-' + type;
  t.innerHTML = `<div class="um-toast-icon"><i class="${icons[type]}"></i></div>
    <div><div class="um-toast-title">${title}</div><div class="um-toast-msg">${msg}</div></div>
    <span class="um-toast-bar"></span>`;
  c.appendChild(t);
  setTimeout(()=>t.classList.add('show'), 20);
  setTimeout(()=>{ t.classList.remove('show'); setTimeout(()=>t.remove(),400); }, 3500);
}
(function(){
  var s=document.getElementById('flash-success');
  var e=document.getElementById('flash-error');
  if(s) showToast('success','Success',s.dataset.msg);
  if(e) showToast('danger','Error',e.dataset.msg);
})();
</script>

@endsection