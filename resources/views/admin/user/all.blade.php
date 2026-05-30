@extends('layouts.admin')
@section('page')

@php
  $authUser = Auth::user();
  $roles = App\Models\Role::all();
  $role = strtolower(optional(Auth::user()->role)->role_name ?? 'user');
  $isSuperAdmin = $role === 'super_admin';
  $isAdmin      = $role === 'admin';
  $isManager    = $role === 'manager';
  $myRole       = strtolower(Auth::user()->role->role_name ?? '');
@endphp

{{-- ═══════════════════════ STYLES ═══════════════════════ --}}
<style>
  @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');

  :root {
    --um-bg:        #0f1117;
    --um-surface:   #1a1d27;
    --um-surface2:  #222636;
    --um-border:    rgba(255,255,255,.07);
    --um-accent:    #6c63ff;
    --um-accent2:   #a78bfa;
    --um-success:   #22c55e;
    --um-danger:    #ef4444;
    --um-warning:   #f59e0b;
    --um-info:      #38bdf8;
    --um-text:      #e2e8f0;
    --um-muted:     #64748b;
    --um-radius:    14px;
    --um-radius-sm: 8px;
    --um-shadow:    0 8px 32px rgba(0,0,0,.45);
  }

  /* ── wrapper ── */
  .um-wrap { font-family: 'Plus Jakarta Sans', sans-serif; color: var(--um-text); }

  /* ── header card ── */
  .um-header-card {
    background: linear-gradient(135deg, #1e1b4b 0%, #312e81 60%, #4c1d95 100%);
    border-radius: var(--um-radius);
    padding: 28px 32px;
    margin-bottom: 24px;
    position: relative;
    overflow: hidden;
    box-shadow: var(--um-shadow);
  }
  .um-header-card::before {
    content: '';
    position: absolute; inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
  }
  .um-header-card .title {
    font-size: 1.5rem; font-weight: 700;
    background: linear-gradient(90deg, #fff, var(--um-accent2));
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    position: relative;
  }
  .um-header-card .subtitle { color: rgba(255,255,255,.5); font-size: .85rem; margin-top: 4px; position: relative; }
  .um-header-card .actions { position: relative; }

  /* ── stat pills ── */
  .stat-pill {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(255,255,255,.08); backdrop-filter: blur(8px);
    border: 1px solid rgba(255,255,255,.12);
    border-radius: 40px; padding: 6px 16px;
    font-size: .8rem; font-weight: 600; color: #fff;
    position: relative;
  }
  .stat-pill .dot { width:8px; height:8px; border-radius:50%; }

  /* ── table card ── */
  .um-table-card {
    background: var(--um-surface);
    border: 1px solid var(--um-border);
    border-radius: var(--um-radius);
    overflow: hidden;
    box-shadow: var(--um-shadow);
  }
  .um-table-card .search-bar {
    padding: 16px 20px;
    border-bottom: 1px solid var(--um-border);
    display: flex; align-items: center; gap: 12px;
  }
  .um-search-input {
    background: var(--um-surface2); border: 1px solid var(--um-border);
    border-radius: var(--um-radius-sm); padding: 8px 14px 8px 38px;
    color: var(--um-text); font-family: inherit; font-size: .875rem;
    width: 260px; outline: none; transition: border-color .2s;
  }
  .um-search-input:focus { border-color: var(--um-accent); }
  .um-search-wrap { position: relative; }
  .um-search-wrap .si { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: var(--um-muted); font-size: .8rem; }

  /* ── custom table ── */
  .um-table { width: 100%; border-collapse: collapse; }
  .um-table thead tr { background: var(--um-surface2); }
  .um-table th {
    padding: 13px 18px; text-align: left;
    font-size: .72rem; font-weight: 700; letter-spacing: .08em;
    text-transform: uppercase; color: var(--um-muted);
    white-space: nowrap;
  }
  .um-table td {
    padding: 14px 18px; vertical-align: middle;
    border-bottom: 1px solid var(--um-border);
    font-size: .875rem;
  }
  .um-table tbody tr { transition: background .15s; }
  .um-table tbody tr:hover { background: rgba(108,99,255,.06); }
  .um-table tbody tr:last-child td { border-bottom: none; }

  /* ── avatar ── */
  .um-avatar {
    width: 40px; height: 40px; border-radius: 50%;
    object-fit: cover;
    border: 2px solid var(--um-border);
    transition: transform .2s;
  }
  .um-table tbody tr:hover .um-avatar { transform: scale(1.1); }

  /* ── role badges ── */
  .um-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 3px 10px; border-radius: 20px;
    font-size: .72rem; font-weight: 700; letter-spacing: .04em;
  }
  .um-badge-super  { background: rgba(239,68,68,.15);  color: #fca5a5; border: 1px solid rgba(239,68,68,.3); }
  .um-badge-admin  { background: rgba(99,102,241,.15); color: #a5b4fc; border: 1px solid rgba(99,102,241,.3); }
  .um-badge-manager{ background: rgba(245,158,11,.15); color: #fcd34d; border: 1px solid rgba(245,158,11,.3); }
  .um-badge-viewer { background: rgba(100,116,139,.15);color: #94a3b8; border: 1px solid rgba(100,116,139,.3); }
  .um-badge-default{ background: rgba(56,189,248,.15); color: #7dd3fc; border: 1px solid rgba(56,189,248,.3); }
  .um-status-active  { background: rgba(34,197,94,.12); color: #86efac; border: 1px solid rgba(34,197,94,.25); }
  .um-status-inactive{ background: rgba(239,68,68,.12); color: #fca5a5; border: 1px solid rgba(239,68,68,.25); }

  /* ── action buttons ── */
  .um-btn { display: inline-flex; align-items: center; gap: 6px; border: none; cursor: pointer; font-family: inherit; font-weight: 600; border-radius: var(--um-radius-sm); transition: all .2s; }
  .um-btn-primary { background: var(--um-accent); color: #fff; padding: 9px 18px; font-size: .85rem; }
  .um-btn-primary:hover { background: #7c74ff; transform: translateY(-1px); box-shadow: 0 4px 14px rgba(108,99,255,.4); }
  .um-btn-outline { background: transparent; color: var(--um-text); border: 1px solid var(--um-border); padding: 9px 14px; font-size: .82rem; }
  .um-btn-outline:hover { background: var(--um-surface2); border-color: rgba(255,255,255,.15); }
  .um-btn-icon { background: var(--um-surface2); color: var(--um-muted); border: 1px solid var(--um-border); padding: 6px 10px; font-size: .78rem; border-radius: 6px; }
  .um-btn-icon:hover { color: var(--um-text); border-color: rgba(255,255,255,.15); }
  .um-btn-danger-ghost { background: rgba(239,68,68,.1); color: #fca5a5; border: 1px solid rgba(239,68,68,.2); padding: 6px 10px; font-size: .78rem; border-radius: 6px; }
  .um-btn-danger-ghost:hover { background: rgba(239,68,68,.2); }

  /* ── action row ── */
  .um-actions-cell { display: flex; gap: 6px; align-items: center; }

  /* ── MODAL ── */
  .um-modal-overlay {
    position: fixed; inset: 0; z-index: 9999;
    background: rgba(0,0,0,.7); backdrop-filter: blur(6px);
    display: flex; align-items: center; justify-content: center;
    opacity: 0; pointer-events: none; transition: opacity .25s;
  }
  .um-modal-overlay.open { opacity: 1; pointer-events: auto; }
  .um-modal {
    background: var(--um-surface);
    border: 1px solid var(--um-border);
    border-radius: 18px;
    width: min(640px, 96vw);
    max-height: 90vh; overflow-y: auto;
    box-shadow: 0 24px 64px rgba(0,0,0,.6);
    transform: translateY(24px) scale(.97);
    transition: transform .3s cubic-bezier(.34,1.56,.64,1);
  }
  .um-modal-overlay.open .um-modal { transform: translateY(0) scale(1); }
  .um-modal-header {
    padding: 22px 28px 18px;
    border-bottom: 1px solid var(--um-border);
    display: flex; align-items: center; justify-content: space-between;
  }
  .um-modal-title { font-size: 1.1rem; font-weight: 700; }
  .um-modal-close {
    background: var(--um-surface2); border: 1px solid var(--um-border);
    color: var(--um-muted); width: 32px; height: 32px; border-radius: 8px;
    cursor: pointer; display: flex; align-items: center; justify-content: center;
    transition: all .2s;
  }
  .um-modal-close:hover { color: var(--um-text); background: var(--um-border); }
  .um-modal-body { padding: 24px 28px; }
  .um-modal-footer { padding: 18px 28px; border-top: 1px solid var(--um-border); display: flex; gap: 10px; justify-content: flex-end; }

  /* ── form fields inside modal ── */
  .um-field { margin-bottom: 18px; }
  .um-field label { display: block; font-size: .8rem; font-weight: 600; color: var(--um-muted); margin-bottom: 7px; text-transform: uppercase; letter-spacing: .06em; }
  .um-field input, .um-field select {
    width: 100%; background: var(--um-surface2);
    border: 1px solid var(--um-border); border-radius: var(--um-radius-sm);
    padding: 10px 14px; color: var(--um-text);
    font-family: inherit; font-size: .875rem; outline: none;
    transition: border-color .2s, box-shadow .2s;
  }
  .um-field input:focus, .um-field select:focus { border-color: var(--um-accent); box-shadow: 0 0 0 3px rgba(108,99,255,.15); }
  .um-field .error-msg { color: #fca5a5; font-size: .78rem; margin-top: 5px; }
  .um-field input[type="file"] { padding: 8px 12px; cursor: pointer; }
  .um-field .preview-wrap { margin-top: 10px; }
  .um-field .preview-wrap img { width: 60px; height: 60px; border-radius: 50%; object-fit: cover; border: 2px solid var(--um-border); }
  .um-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 0 18px; }

  /* ── TOAST ── */
  #um-toast-container { position: fixed; bottom: 28px; right: 28px; z-index: 99999; display: flex; flex-direction: column; gap: 10px; }
  .um-toast {
    display: flex; align-items: center; gap: 12px;
    background: var(--um-surface); border: 1px solid var(--um-border);
    border-radius: 12px; padding: 14px 18px; min-width: 280px;
    box-shadow: 0 8px 30px rgba(0,0,0,.5);
    transform: translateX(120%); transition: transform .35s cubic-bezier(.34,1.56,.64,1);
    font-family: 'Plus Jakarta Sans', sans-serif;
  }
  .um-toast.show { transform: translateX(0); }
  .um-toast-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: .95rem; }
  .um-toast-success .um-toast-icon { background: rgba(34,197,94,.15); color: var(--um-success); }
  .um-toast-danger  .um-toast-icon { background: rgba(239,68,68,.15);  color: var(--um-danger); }
  .um-toast-info    .um-toast-icon { background: rgba(56,189,248,.15);  color: var(--um-info); }
  .um-toast-title { font-size: .875rem; font-weight: 700; color: var(--um-text); }
  .um-toast-msg   { font-size: .78rem; color: var(--um-muted); margin-top: 2px; }
  .um-toast-bar   { position: absolute; bottom: 0; left: 0; height: 3px; border-radius: 0 0 12px 12px; background: currentColor; animation: toastBar 3.5s linear forwards; }
  @keyframes toastBar { from { width: 100%; } to { width: 0%; } }

  /* ── delete modal specifics ── */
  .um-delete-icon { width: 60px; height: 60px; border-radius: 50%; background: rgba(239,68,68,.12); border: 2px solid rgba(239,68,68,.25); display: flex; align-items: center; justify-content: center; font-size: 1.4rem; color: #fca5a5; margin: 0 auto 18px; }

  /* ── scrollbar ── */
  .um-modal::-webkit-scrollbar { width: 5px; }
  .um-modal::-webkit-scrollbar-track { background: transparent; }
  .um-modal::-webkit-scrollbar-thumb { background: var(--um-border); border-radius: 10px; }

  /* ── mono font for username ── */
  .um-mono { font-family: 'JetBrains Mono', monospace; font-size: .8rem; color: var(--um-muted); }

  /* ── empty state ── */
  .um-empty { text-align: center; padding: 60px 20px; color: var(--um-muted); }
  .um-empty i { font-size: 2.5rem; margin-bottom: 14px; opacity: .4; }
  .um-empty p { font-size: .9rem; }
</style>

{{-- ═══════════════════════ FLASH MESSAGES ═══════════════════════ --}}
@if(session('success'))
<div id="flash-success" data-msg="{{ session('success') }}"></div>
@endif
@if(session('error'))
<div id="flash-error" data-msg="{{ session('error') }}"></div>
@endif

{{-- ═══════════════════════ PAGE ═══════════════════════ --}}
<div class="um-wrap">

  {{-- Header --}}
  <div class="um-header-card">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
      <div>
        <div class="title"><i class="fas fa-users-cog me-2"></i>User Management</div>
        <div class="subtitle">Manage all system users, roles and permissions</div>
        <div class="d-flex gap-2 mt-3 flex-wrap">
          <span class="stat-pill"><span class="dot" style="background:var(--um-success)"></span>{{ $users->where('status',1)->count() }} Active</span>
          <span class="stat-pill"><span class="dot" style="background:var(--um-danger)"></span>{{ $users->where('status',0)->count() }} Inactive</span>
          <span class="stat-pill"><span class="dot" style="background:var(--um-accent2)"></span>{{ $users->count() }} Total</span>
        </div>
      </div>
      <div class="actions d-flex gap-2 flex-wrap">
        <a href="{{ route('admin.users.staff') }}" class="um-btn um-btn-outline">
          <i class="fas fa-id-badge"></i> Staff
        </a>
        @if(!$isManager)
          <button class="um-btn um-btn-primary" onclick="openAddModal()">
            <i class="fas fa-plus"></i> Add User
          </button>
        @endif
      </div>
    </div>
  </div>

  {{-- Table Card --}}
  <div class="um-table-card">
    <div class="search-bar justify-content-between flex-wrap gap-2">
      <div class="um-search-wrap">
        <i class="fas fa-search si"></i>
        <input type="text" class="um-search-input" id="um-search" placeholder="Search users...">
      </div>
      <span style="font-size:.8rem;color:var(--um-muted);" id="um-count"></span>
    </div>

    <div style="overflow-x:auto;">
      <table class="um-table" id="um-table">
        <thead>
          <tr>
            <th>#</th>
            <th>User</th>
            <th>Contact</th>
            <th>Username</th>
            @if($myRole !== 'viewer')
            <th>Role</th>
            @endif
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="um-tbody">
          @forelse($users as $i => $user)
          @php
            $rk = str_replace([' ','-'],'_', strtolower(trim(optional($user->role)->role_name ?? 'user')));
            $badgeClass = match($rk) {
              'super_admin' => 'um-badge-super',
              'admin'       => 'um-badge-admin',
              'manager'     => 'um-badge-manager',
              'viewer'      => 'um-badge-viewer',
              default       => 'um-badge-default',
            };
          @endphp
          <tr data-search="{{ strtolower($user->name . ' ' . $user->email . ' ' . $user->username . ' ' . ($user->role->role_name ?? '')) }}">
            <td style="color:var(--um-muted);font-size:.8rem;">{{ $i+1 }}</td>
            <td>
              <div class="d-flex align-items-center gap-3">
                @if($user->photo)
                  <img src="{{ asset('uploads/users/'.$user->photo) }}" class="um-avatar" alt="{{ $user->name }}">
                @else
                  <img src="{{ asset('contents/admin/images/avatar.jpg') }}" class="um-avatar" alt="{{ $user->name }}">
                @endif
                <div>
                  <div style="font-weight:600;">{{ $user->name }}</div>
                  <div style="font-size:.78rem;color:var(--um-muted);">{{ $user->email }}</div>
                </div>
              </div>
            </td>
            <td style="color:var(--um-muted);font-size:.85rem;">{{ $user->phone ?? '—' }}</td>
            <td><span class="um-mono">{{ $user->username }}</span></td>
            @if($myRole !== 'viewer')
            <td>
              <span class="um-badge {{ $badgeClass }}">
                <i class="fas fa-circle" style="font-size:.4rem;"></i>
                {{ $user->role->role_name ?? 'N/A' }}
              </span>
            </td>
            @endif
            <td>
              @if($user->status == 1)
                <span class="um-badge um-status-active"><i class="fas fa-circle" style="font-size:.4rem;"></i>Active</span>
              @else
                <span class="um-badge um-status-inactive"><i class="fas fa-circle" style="font-size:.4rem;"></i>Inactive</span>
              @endif
            </td>
            <td>
              <div class="um-actions-cell">
                <a href="{{ url('dashboard/user/view/'.$user->slug) }}" class="um-btn um-btn-icon" title="View">
                  <i class="fas fa-eye"></i>
                </a>
                <button class="um-btn um-btn-icon" title="Edit"
                  onclick="openEditModal({
                    id: '{{ $user->id }}',
                    slug: '{{ $user->slug }}',
                    name: {{ json_encode($user->name) }},
                    phone: {{ json_encode($user->phone ?? '') }},
                    email: {{ json_encode($user->email) }},
                    username: {{ json_encode($user->username) }},
                    role_id: '{{ $user->role_id }}',
                    status: '{{ $user->status }}',
                    photo: '{{ $user->photo ? asset("uploads/users/".$user->photo) : asset("contents/admin/images/avatar.jpg") }}'
                  })">
                  <i class="fas fa-pen"></i>
                </button>
                @if(!$isManager && $user->id != 1)
                <button class="um-btn um-btn-danger-ghost" title="Delete"
                  onclick="openDeleteModal('{{ $user->id }}', {{ json_encode($user->name) }})">
                  <i class="fas fa-trash-alt"></i>
                </button>
                @endif
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7">
              <div class="um-empty">
                <i class="fas fa-users-slash d-block"></i>
                <p>No users found.</p>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

{{-- ═══════════════════════ ADD USER MODAL ═══════════════════════ --}}
<div class="um-modal-overlay" id="addModal">
  <div class="um-modal">
    <div class="um-modal-header">
      <div class="um-modal-title"><i class="fas fa-user-plus me-2" style="color:var(--um-accent2)"></i>Add New User</div>
      <button class="um-modal-close" onclick="closeModal('addModal')"><i class="fas fa-times"></i></button>
    </div>

    <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data" id="addUserForm">
      @csrf
      <div class="um-modal-body">

        <div class="um-grid-2">
          <div class="um-field">
            <label>Full Name <span style="color:var(--um-danger)">*</span></label>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="John Doe">
            @error('name')<div class="error-msg">{{ $message }}</div>@enderror
          </div>
          <div class="um-field">
            <label>Phone</label>
            <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+880 1xxx-xxxxxx">
            @error('phone')<div class="error-msg">{{ $message }}</div>@enderror
          </div>
        </div>

        <div class="um-grid-2">
          <div class="um-field">
            <label>Email <span style="color:var(--um-danger)">*</span></label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="user@example.com">
            @error('email')<div class="error-msg">{{ $message }}</div>@enderror
          </div>
          <div class="um-field">
            <label>Username <span style="color:var(--um-danger)">*</span></label>
            <input type="text" name="username" value="{{ old('username') }}" placeholder="john_doe">
            @error('username')<div class="error-msg">{{ $message }}</div>@enderror
          </div>
        </div>

        <div class="um-grid-2">
          <div class="um-field">
            <label>Password <span style="color:var(--um-danger)">*</span></label>
            <input type="password" name="password" placeholder="••••••••">
            @error('password')<div class="error-msg">{{ $message }}</div>@enderror
          </div>
          <div class="um-field">
            <label>Confirm Password <span style="color:var(--um-danger)">*</span></label>
            <input type="password" name="password_confirmation" placeholder="••••••••">
          </div>
        </div>

        <div class="um-grid-2">
          <div class="um-field">
            <label>User Role <span style="color:var(--um-danger)">*</span></label>
            <select name="role_id">
              <option value="">Select Role</option>
              @foreach($roles as $r)
                <option value="{{ $r->id }}" {{ old('role_id') == $r->id ? 'selected' : '' }}>{{ ucfirst($r->role_name) }}</option>
              @endforeach
            </select>
            @error('role_id')<div class="error-msg">{{ $message }}</div>@enderror
          </div>
          <div class="um-field">
            <label>Photo</label>
            <input type="file" name="photo" accept="image/*" onchange="previewImg(this,'add-preview')">
            @error('photo')<div class="error-msg">{{ $message }}</div>@enderror
            <div class="preview-wrap"><img id="add-preview" src="{{ asset('contents/admin/images/avatar.jpg') }}" style="display:none;"></div>
          </div>
        </div>

      </div>
      <div class="um-modal-footer">
        <button type="button" class="um-btn um-btn-outline" onclick="closeModal('addModal')">Cancel</button>
        <button type="submit" class="um-btn um-btn-primary"><i class="fas fa-save"></i> Create User</button>
      </div>
    </form>
  </div>
</div>

{{-- ═══════════════════════ EDIT USER MODAL ═══════════════════════ --}}
<div class="um-modal-overlay" id="editModal">
  <div class="um-modal">
    <div class="um-modal-header">
      <div class="um-modal-title"><i class="fas fa-user-edit me-2" style="color:var(--um-info)"></i>Edit User</div>
      <button class="um-modal-close" onclick="closeModal('editModal')"><i class="fas fa-times"></i></button>
    </div>

    <form method="POST" id="editUserForm" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <input type="hidden" name="id" id="edit_id">
      <input type="hidden" name="slug" id="edit_slug">

      <div class="um-modal-body">

        <div class="um-grid-2">
          <div class="um-field">
            <label>Full Name <span style="color:var(--um-danger)">*</span></label>
            <input type="text" name="name" id="edit_name" placeholder="John Doe">
          </div>
          <div class="um-field">
            <label>Phone</label>
            <input type="text" name="phone" id="edit_phone" placeholder="+880 1xxx-xxxxxx">
          </div>
        </div>

        <div class="um-grid-2">
          <div class="um-field">
            <label>Email <span style="color:var(--um-danger)">*</span></label>
            <input type="email" name="email" id="edit_email" placeholder="user@example.com">
          </div>
          <div class="um-field">
            <label>Username</label>
            <input type="text" id="edit_username_display" disabled style="opacity:.6;">
            {{-- username not submitted (disabled), just shown --}}
          </div>
        </div>

        <div class="um-grid-2">
          <div class="um-field">
            <label>User Role <span style="color:var(--um-danger)">*</span></label>
            @if($isSuperAdmin)
              <select name="role_id" id="edit_role_id">
                <option value="">Select Role</option>
                @foreach($roles as $r)
                  <option value="{{ $r->id }}">{{ ucfirst($r->role_name) }}</option>
                @endforeach
              </select>
            @else
              <div id="edit_role_display" style="padding:10px 14px;background:var(--um-surface2);border:1px solid var(--um-border);border-radius:var(--um-radius-sm);font-size:.875rem;color:var(--um-muted);"></div>
            @endif
          </div>
          <div class="um-field">
            <label>Status <span style="color:var(--um-danger)">*</span></label>
            <select name="status" id="edit_status">
              <option value="1">Active</option>
              <option value="0">Inactive</option>
            </select>
          </div>
        </div>

        <div class="um-field">
          <label>Photo</label>
          <input type="file" name="photo" accept="image/*" onchange="previewImg(this,'edit-preview')">
          <div class="preview-wrap"><img id="edit-preview" src="{{ asset('contents/admin/images/avatar.jpg') }}"></div>
        </div>

      </div>
      <div class="um-modal-footer">
        <button type="button" class="um-btn um-btn-outline" onclick="closeModal('editModal')">Cancel</button>
        <button type="submit" class="um-btn um-btn-primary" style="background:var(--um-info);"><i class="fas fa-save"></i> Update User</button>
      </div>
    </form>
  </div>
</div>

{{-- ═══════════════════════ DELETE MODAL ═══════════════════════ --}}
<div class="um-modal-overlay" id="deleteModal">
  <div class="um-modal" style="width:min(420px,96vw);">
    <div class="um-modal-body" style="text-align:center;padding:36px 28px;">
      <div class="um-delete-icon"><i class="fas fa-trash-alt"></i></div>
      <h5 style="font-weight:700;margin-bottom:8px;">Delete User?</h5>
      <p style="color:var(--um-muted);font-size:.88rem;margin-bottom:4px;">You are about to delete <strong id="delete-name" style="color:var(--um-text)"></strong>.</p>
      <p style="color:var(--um-muted);font-size:.8rem;">This action <strong style="color:var(--um-danger)">cannot be undone</strong>.</p>
    </div>
    <div class="um-modal-footer" style="justify-content:center;gap:14px;">
      <button class="um-btn um-btn-outline" onclick="closeModal('deleteModal')"><i class="fas fa-times"></i> Cancel</button>
      <form id="deleteUserForm" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" class="um-btn um-btn-primary" style="background:var(--um-danger);"><i class="fas fa-trash-alt"></i> Yes, Delete</button>
      </form>
    </div>
  </div>
</div>

{{-- ═══════════════════════ TOAST CONTAINER ═══════════════════════ --}}
<div id="um-toast-container"></div>

{{-- ═══════════════════════ SCRIPTS ═══════════════════════ --}}
<script>
// ── Modal helpers ──────────────────────────────────
function openModal(id)  { document.getElementById(id).classList.add('open'); document.body.style.overflow='hidden'; }
function closeModal(id) { document.getElementById(id).classList.remove('open'); document.body.style.overflow=''; }

document.querySelectorAll('.um-modal-overlay').forEach(el => {
  el.addEventListener('click', e => { if(e.target === el) closeModal(el.id); });
});
document.addEventListener('keydown', e => { if(e.key==='Escape') document.querySelectorAll('.um-modal-overlay.open').forEach(el=>closeModal(el.id)); });

// ── Add modal ──────────────────────────────────────
function openAddModal() { openModal('addModal'); }

@if($errors->any() && old('_method') !== 'PUT')
  openAddModal();
@endif

// ── Edit modal ─────────────────────────────────────
function openEditModal(u) {
  document.getElementById('edit_id').value   = u.id;
  document.getElementById('edit_slug').value = u.slug;
  document.getElementById('edit_name').value  = u.name;
  document.getElementById('edit_phone').value = u.phone;
  document.getElementById('edit_email').value = u.email;
  document.getElementById('edit_username_display').value = u.username;
  document.getElementById('edit_status').value = u.status;
  document.getElementById('edit-preview').src = u.photo;

  var form = document.getElementById('editUserForm');
  form.action = '/dashboard/user/edit/' + u.slug;

  @if($isSuperAdmin)
    var sel = document.getElementById('edit_role_id');
    if(sel) sel.value = u.role_id;
  @else
    var disp = document.getElementById('edit_role_display');
    if(disp) disp.textContent = u.role_name || '—';
  @endif

  openModal('editModal');
}

// ── Delete modal ───────────────────────────────────
function openDeleteModal(id, name) {
  document.getElementById('delete-name').textContent = name;
  document.getElementById('deleteUserForm').action = '/dashboard/user/delete/' + id;
  openModal('deleteModal');
}

// ── Photo preview ──────────────────────────────────
function previewImg(input, previewId) {
  var preview = document.getElementById(previewId);
  if(input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = e => { preview.src = e.target.result; preview.style.display='block'; };
    reader.readAsDataURL(input.files[0]);
  }
}

// ── Live search ────────────────────────────────────
(function() {
  var inp   = document.getElementById('um-search');
  var rows  = document.querySelectorAll('#um-tbody tr[data-search]');
  var count = document.getElementById('um-count');
  function update() {
    var q = inp.value.toLowerCase().trim();
    var vis = 0;
    rows.forEach(r => {
      var match = !q || r.dataset.search.includes(q);
      r.style.display = match ? '' : 'none';
      if(match) vis++;
    });
    count.textContent = vis + ' of ' + rows.length + ' users';
  }
  inp.addEventListener('input', update);
  update();
})();

// ── Toast ──────────────────────────────────────────
function showToast(type, title, msg) {
  var icons = { success:'fas fa-check-circle', danger:'fas fa-exclamation-circle', info:'fas fa-info-circle' };
  var container = document.getElementById('um-toast-container');
  var t = document.createElement('div');
  t.className = 'um-toast um-toast-' + type;
  t.style.position = 'relative';
  t.innerHTML = `
    <div class="um-toast-icon"><i class="${icons[type]||icons.info}"></i></div>
    <div>
      <div class="um-toast-title">${title}</div>
      <div class="um-toast-msg">${msg}</div>
    </div>
    <span class="um-toast-bar" style="color:${type==='success'?'#22c55e':type==='danger'?'#ef4444':'#38bdf8'}"></span>
  `;
  container.appendChild(t);
  setTimeout(()=>t.classList.add('show'), 20);
  setTimeout(()=>{ t.classList.remove('show'); setTimeout(()=>t.remove(), 400); }, 3500);
}

// ── Flash session toasts ───────────────────────────
(function() {
  var s = document.getElementById('flash-success');
  var e = document.getElementById('flash-error');
  if(s) showToast('success','Success', s.dataset.msg);
  if(e) showToast('danger','Error', e.dataset.msg);
})();
</script>

@endsection