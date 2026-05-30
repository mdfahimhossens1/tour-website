@extends('layouts.admin')
@section('page')

@php
  $authRole = strtolower(optional(Auth::user()->role)->role_name ?? 'user');
  $isManager    = $authRole === 'manager';
  $isSuperAdmin = $authRole === 'super_admin';
  $roles = App\Models\Role::all();
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
    --um-teal:      #2dd4bf;
    --um-text:      #e2e8f0;
    --um-muted:     #64748b;
    --um-radius:    14px;
    --um-radius-sm: 8px;
    --um-shadow:    0 8px 32px rgba(0,0,0,.45);
  }

  .um-wrap { font-family: 'Plus Jakarta Sans', sans-serif; color: var(--um-text); }

  /* ── header card ── */
  .um-header-card {
    background: linear-gradient(135deg, #0f2027 0%, #1a3a4a 50%, #0d3b50 100%);
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
    background: url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%232dd4bf' fill-opacity='0.04'%3E%3Cpath d='M50 50c0-5.523 4.477-10 10-10s10 4.477 10 10-4.477 10-10 10c0 5.523-4.477 10-10 10s-10-4.477-10-10 4.477-10 10-10zM10 10c0-5.523 4.477-10 10-10s10 4.477 10 10-4.477 10-10 10c0 5.523-4.477 10-10 10S0 25.523 0 20s4.477-10 10-10z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
  }
  .um-header-card::after {
    content: '';
    position: absolute;
    right: -60px; top: -60px;
    width: 220px; height: 220px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(45,212,191,.12) 0%, transparent 70%);
  }
  .um-header-card .title {
    font-size: 1.5rem; font-weight: 700;
    background: linear-gradient(90deg, #fff, var(--um-teal));
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    position: relative; z-index: 1;
  }
  .um-header-card .subtitle { color: rgba(255,255,255,.45); font-size: .85rem; margin-top: 4px; position: relative; z-index: 1; }
  .um-header-card .actions  { position: relative; z-index: 1; }

  /* ── stat pills ── */
  .stat-pill {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(255,255,255,.07); backdrop-filter: blur(8px);
    border: 1px solid rgba(255,255,255,.1);
    border-radius: 40px; padding: 6px 16px;
    font-size: .8rem; font-weight: 600; color: #fff;
    position: relative; z-index: 1;
  }
  .stat-pill .dot { width: 8px; height: 8px; border-radius: 50%; }

  /* ── role summary cards ── */
  .role-cards { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 20px; }
  .role-card {
    flex: 1; min-width: 110px;
    background: var(--um-surface);
    border: 1px solid var(--um-border);
    border-radius: var(--um-radius-sm);
    padding: 14px 16px;
    text-align: center;
  }
  .role-card .rc-count { font-size: 1.6rem; font-weight: 700; line-height: 1; }
  .role-card .rc-label { font-size: .72rem; color: var(--um-muted); margin-top: 4px; text-transform: uppercase; letter-spacing: .06em; }

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
    display: flex; align-items: center; gap: 12px; flex-wrap: wrap;
  }
  .um-search-input {
    background: var(--um-surface2); border: 1px solid var(--um-border);
    border-radius: var(--um-radius-sm); padding: 8px 14px 8px 38px;
    color: var(--um-text); font-family: inherit; font-size: .875rem;
    width: 260px; outline: none; transition: border-color .2s;
  }
  .um-search-input:focus { border-color: var(--um-teal); box-shadow: 0 0 0 3px rgba(45,212,191,.1); }
  .um-search-wrap { position: relative; }
  .um-search-wrap .si { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: var(--um-muted); font-size: .8rem; }

  /* ── filter tabs ── */
  .um-filter-tabs { display: flex; gap: 6px; }
  .um-filter-tab {
    background: var(--um-surface2); border: 1px solid var(--um-border);
    border-radius: 20px; padding: 5px 14px; font-size: .78rem;
    font-weight: 600; color: var(--um-muted); cursor: pointer;
    font-family: inherit; transition: all .2s;
  }
  .um-filter-tab:hover, .um-filter-tab.active { background: var(--um-teal); color: #0f2027; border-color: var(--um-teal); }

  /* ── table ── */
  .um-table { width: 100%; border-collapse: collapse; }
  .um-table thead tr { background: var(--um-surface2); }
  .um-table th {
    padding: 13px 18px; text-align: left;
    font-size: .72rem; font-weight: 700; letter-spacing: .08em;
    text-transform: uppercase; color: var(--um-muted); white-space: nowrap;
  }
  .um-table td {
    padding: 14px 18px; vertical-align: middle;
    border-bottom: 1px solid var(--um-border); font-size: .875rem;
  }
  .um-table tbody tr { transition: background .15s; }
  .um-table tbody tr:hover { background: rgba(45,212,191,.04); }
  .um-table tbody tr:last-child td { border-bottom: none; }

  /* ── avatar + name cell ── */
  .um-avatar {
    width: 42px; height: 42px; border-radius: 50%;
    object-fit: cover; border: 2px solid var(--um-border);
    transition: transform .2s, border-color .2s;
  }
  .um-table tbody tr:hover .um-avatar { transform: scale(1.1); border-color: var(--um-teal); }
  .um-user-name { font-weight: 600; }
  .um-user-email { font-size: .78rem; color: var(--um-muted); }

  /* ── badges ── */
  .um-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 3px 10px; border-radius: 20px;
    font-size: .72rem; font-weight: 700; letter-spacing: .04em;
  }
  .um-badge-super   { background: rgba(239,68,68,.15);  color: #fca5a5; border: 1px solid rgba(239,68,68,.3); }
  .um-badge-admin   { background: rgba(99,102,241,.15); color: #a5b4fc; border: 1px solid rgba(99,102,241,.3); }
  .um-badge-manager { background: rgba(245,158,11,.15); color: #fcd34d; border: 1px solid rgba(245,158,11,.3); }
  .um-badge-viewer  { background: rgba(100,116,139,.15);color: #94a3b8; border: 1px solid rgba(100,116,139,.3); }
  .um-badge-default { background: rgba(45,212,191,.12); color: #5eead4; border: 1px solid rgba(45,212,191,.25); }
  .um-status-active   { background: rgba(34,197,94,.12); color: #86efac; border: 1px solid rgba(34,197,94,.25); }
  .um-status-inactive { background: rgba(239,68,68,.12); color: #fca5a5; border: 1px solid rgba(239,68,68,.25); }

  /* ── action buttons ── */
  .um-btn { display: inline-flex; align-items: center; gap: 6px; border: none; cursor: pointer; font-family: inherit; font-weight: 600; border-radius: var(--um-radius-sm); transition: all .2s; text-decoration: none; }
  .um-btn-primary { background: var(--um-teal); color: #0f2027; padding: 9px 18px; font-size: .85rem; }
  .um-btn-primary:hover { background: #5eead4; transform: translateY(-1px); box-shadow: 0 4px 14px rgba(45,212,191,.35); color: #0f2027; }
  .um-btn-outline { background: transparent; color: var(--um-text); border: 1px solid var(--um-border); padding: 9px 14px; font-size: .82rem; }
  .um-btn-outline:hover { background: var(--um-surface2); border-color: rgba(255,255,255,.15); color: var(--um-text); }
  .um-btn-icon { background: var(--um-surface2); color: var(--um-muted); border: 1px solid var(--um-border); padding: 6px 10px; font-size: .78rem; border-radius: 6px; }
  .um-btn-icon:hover { color: var(--um-teal); border-color: rgba(45,212,191,.3); }
  .um-btn-icon-edit:hover { color: var(--um-info); border-color: rgba(56,189,248,.3); }
  .um-btn-danger-ghost { background: rgba(239,68,68,.1); color: #fca5a5; border: 1px solid rgba(239,68,68,.2); padding: 6px 10px; font-size: .78rem; border-radius: 6px; }
  .um-btn-danger-ghost:hover { background: rgba(239,68,68,.2); }
  .um-actions-cell { display: flex; gap: 6px; align-items: center; }

  /* ── mono ── */
  .um-mono { font-family: 'JetBrains Mono', monospace; font-size: .8rem; color: var(--um-muted); }

  /* ── MODAL ── */
  .um-modal-overlay {
    position: fixed; inset: 0; z-index: 9999;
    background: rgba(0,0,0,.72); backdrop-filter: blur(6px);
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
  .um-modal-close:hover { color: var(--um-text); }
  .um-modal-body   { padding: 24px 28px; }
  .um-modal-footer { padding: 18px 28px; border-top: 1px solid var(--um-border); display: flex; gap: 10px; justify-content: flex-end; }

  /* ── form fields ── */
  .um-field { margin-bottom: 18px; }
  .um-field label { display: block; font-size: .8rem; font-weight: 600; color: var(--um-muted); margin-bottom: 7px; text-transform: uppercase; letter-spacing: .06em; }
  .um-field input, .um-field select {
    width: 100%; background: var(--um-surface2);
    border: 1px solid var(--um-border); border-radius: var(--um-radius-sm);
    padding: 10px 14px; color: var(--um-text);
    font-family: inherit; font-size: .875rem; outline: none;
    transition: border-color .2s, box-shadow .2s;
  }
  .um-field input:focus, .um-field select:focus { border-color: var(--um-teal); box-shadow: 0 0 0 3px rgba(45,212,191,.12); }
  .um-field .error-msg { color: #fca5a5; font-size: .78rem; margin-top: 5px; }
  .um-field input[type="file"] { padding: 8px 12px; cursor: pointer; }
  .um-field .preview-wrap { margin-top: 10px; }
  .um-field .preview-wrap img { width: 60px; height: 60px; border-radius: 50%; object-fit: cover; border: 2px solid var(--um-border); }
  .um-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 0 18px; }

  /* ── delete modal ── */
  .um-delete-icon { width: 64px; height: 64px; border-radius: 50%; background: rgba(239,68,68,.12); border: 2px solid rgba(239,68,68,.25); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: #fca5a5; margin: 0 auto 18px; }

  /* ── TOAST ── */
  #um-toast-container { position: fixed; bottom: 28px; right: 28px; z-index: 99999; display: flex; flex-direction: column; gap: 10px; }
  .um-toast {
    display: flex; align-items: center; gap: 12px;
    background: var(--um-surface); border: 1px solid var(--um-border);
    border-radius: 12px; padding: 14px 18px; min-width: 280px;
    box-shadow: 0 8px 30px rgba(0,0,0,.5);
    transform: translateX(120%); transition: transform .35s cubic-bezier(.34,1.56,.64,1);
    font-family: 'Plus Jakarta Sans', sans-serif; position: relative; overflow: hidden;
  }
  .um-toast.show { transform: translateX(0); }
  .um-toast-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: .95rem; }
  .um-toast-success .um-toast-icon { background: rgba(34,197,94,.15);  color: var(--um-success); }
  .um-toast-danger  .um-toast-icon { background: rgba(239,68,68,.15);   color: var(--um-danger); }
  .um-toast-info    .um-toast-icon { background: rgba(56,189,248,.15);  color: var(--um-info); }
  .um-toast-title { font-size: .875rem; font-weight: 700; color: var(--um-text); }
  .um-toast-msg   { font-size: .78rem;  color: var(--um-muted); margin-top: 2px; }
  .um-toast-bar   { position: absolute; bottom: 0; left: 0; height: 3px; border-radius: 0 0 12px 12px; animation: toastBar 3.5s linear forwards; }
  .um-toast-success .um-toast-bar { background: var(--um-success); }
  .um-toast-danger  .um-toast-bar { background: var(--um-danger); }
  .um-toast-info    .um-toast-bar { background: var(--um-info); }
  @keyframes toastBar { from { width: 100%; } to { width: 0%; } }

  /* ── scrollbar ── */
  .um-modal::-webkit-scrollbar { width: 5px; }
  .um-modal::-webkit-scrollbar-thumb { background: var(--um-border); border-radius: 10px; }

  /* ── empty ── */
  .um-empty { text-align: center; padding: 60px 20px; color: var(--um-muted); }
  .um-empty i { font-size: 2.5rem; margin-bottom: 14px; opacity: .4; display: block; }
</style>

{{-- Flash --}}
@if(session('success'))<div id="flash-success" data-msg="{{ session('success') }}"></div>@endif
@if(session('error'))<div id="flash-error"   data-msg="{{ session('error') }}"></div>@endif

{{-- ═══════════════════════ PAGE ═══════════════════════ --}}
<div class="um-wrap">

  {{-- Header --}}
  <div class="um-header-card">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
      <div>
        <div class="title"><i class="fas fa-id-badge me-2"></i>Staff Directory</div>
        <div class="subtitle">Manage your team members and their access levels</div>
        <div class="d-flex gap-2 mt-3 flex-wrap">
          <span class="stat-pill"><span class="dot" style="background:var(--um-success)"></span>{{ $staff->where('status',1)->count() }} Active</span>
          <span class="stat-pill"><span class="dot" style="background:var(--um-danger)"></span>{{ $staff->where('status',0)->count() }} Inactive</span>
          <span class="stat-pill"><span class="dot" style="background:var(--um-teal)"></span>{{ $staff->count() }} Total Staff</span>
        </div>
      </div>
      <div class="actions d-flex gap-2 flex-wrap">
        <a href="{{ route('admin.users.index') }}" class="um-btn um-btn-outline">
          <i class="fas fa-arrow-left"></i> All Users
        </a>
        @if(!$isManager)
          <button class="um-btn um-btn-primary" onclick="openAddModal()">
            <i class="fas fa-plus"></i> Add Staff
          </button>
        @endif
      </div>
    </div>
  </div>

  {{-- Role summary cards --}}
  @php
    $roleCounts = $staff->groupBy(fn($s) => $s->role->role_name ?? 'Unknown');
  @endphp
  <div class="role-cards">
    @foreach($roleCounts as $roleName => $group)
    @php
      $rk2 = str_replace([' ','-'],'_',strtolower(trim($roleName)));
      $rc  = match($rk2) {
        'super_admin' => ['color'=>'#fca5a5','bg'=>'rgba(239,68,68,.12)','icon'=>'fas fa-crown'],
        'admin'       => ['color'=>'#a5b4fc','bg'=>'rgba(99,102,241,.12)','icon'=>'fas fa-shield-alt'],
        'manager'     => ['color'=>'#fcd34d','bg'=>'rgba(245,158,11,.12)','icon'=>'fas fa-user-tie'],
        default       => ['color'=>'#5eead4','bg'=>'rgba(45,212,191,.12)','icon'=>'fas fa-user'],
      };
    @endphp
    <div class="role-card">
      <div style="font-size:1.2rem;margin-bottom:6px;color:{{ $rc['color'] }}"><i class="{{ $rc['icon'] }}"></i></div>
      <div class="rc-count" style="color:{{ $rc['color'] }}">{{ $group->count() }}</div>
      <div class="rc-label">{{ $roleName }}</div>
    </div>
    @endforeach
  </div>

  {{-- Table Card --}}
  <div class="um-table-card">
    <div class="search-bar justify-content-between">
      <div class="d-flex gap-3 align-items-center flex-wrap">
        <div class="um-search-wrap">
          <i class="fas fa-search si"></i>
          <input type="text" class="um-search-input" id="um-search" placeholder="Search staff...">
        </div>
        <div class="um-filter-tabs">
          <button class="um-filter-tab active" data-filter="all">All</button>
          <button class="um-filter-tab" data-filter="active">Active</button>
          <button class="um-filter-tab" data-filter="inactive">Inactive</button>
        </div>
      </div>
      <span style="font-size:.8rem;color:var(--um-muted);" id="um-count"></span>
    </div>

    <div style="overflow-x:auto;">
      <table class="um-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Staff Member</th>
            <th>Username</th>
            <th>Role</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="um-tbody">
          @forelse($staff as $i => $s)
          @php
            $rk = str_replace([' ','-'],'_',strtolower(trim(optional($s->role)->role_name ?? 'user')));
            $badgeClass = match($rk) {
              'super_admin' => 'um-badge-super',
              'admin'       => 'um-badge-admin',
              'manager'     => 'um-badge-manager',
              'viewer'      => 'um-badge-viewer',
              default       => 'um-badge-default',
            };
            $roleIcon = match($rk) {
              'super_admin' => 'fas fa-crown',
              'admin'       => 'fas fa-shield-alt',
              'manager'     => 'fas fa-user-tie',
              default       => 'fas fa-user',
            };
            $statusStr = $s->status ? 'active' : 'inactive';
          @endphp
          <tr data-search="{{ strtolower($s->name.' '.$s->email.' '.$s->username.' '.($s->role->role_name ?? '')) }}"
              data-status="{{ $statusStr }}">
            <td style="color:var(--um-muted);font-size:.8rem;">{{ $i + 1 }}</td>
            <td>
              <div class="d-flex align-items-center gap-3">
                @if($s->photo)
                  <img src="{{ asset('uploads/users/'.$s->photo) }}" class="um-avatar" alt="{{ $s->name }}">
                @else
                  <img src="{{ asset('contents/admin/images/avatar.jpg') }}" class="um-avatar" alt="{{ $s->name }}">
                @endif
                <div>
                  <div class="um-user-name">{{ $s->name }}</div>
                  <div class="um-user-email">{{ $s->email }}</div>
                </div>
              </div>
            </td>
            <td><span class="um-mono">{{ $s->username }}</span></td>
            <td>
              <span class="um-badge {{ $badgeClass }}">
                <i class="{{ $roleIcon }}" style="font-size:.6rem;"></i>
                {{ $s->role->role_name ?? 'N/A' }}
              </span>
            </td>
            <td>
              @if($s->status)
                <span class="um-badge um-status-active"><i class="fas fa-circle" style="font-size:.4rem;"></i> Active</span>
              @else
                <span class="um-badge um-status-inactive"><i class="fas fa-circle" style="font-size:.4rem;"></i> Inactive</span>
              @endif
            </td>
            <td>
              <div class="um-actions-cell">
                <a href="{{ route('admin.users.show', $s->slug) }}" class="um-btn um-btn-icon" title="View">
                  <i class="fas fa-eye"></i>
                </a>
                <button class="um-btn um-btn-icon um-btn-icon-edit" title="Edit"
                  onclick="openEditModal({
                    id: '{{ $s->id }}',
                    slug: '{{ $s->slug }}',
                    name: {{ json_encode($s->name) }},
                    phone: {{ json_encode($s->phone ?? '') }},
                    email: {{ json_encode($s->email) }},
                    username: {{ json_encode($s->username) }},
                    role_id: '{{ $s->role_id }}',
                    role_name: {{ json_encode($s->role->role_name ?? '') }},
                    status: '{{ $s->status }}',
                    photo: '{{ $s->photo ? asset("uploads/users/".$s->photo) : asset("contents/admin/images/avatar.jpg") }}'
                  })">
                  <i class="fas fa-pen"></i>
                </button>
                @if(!$isManager)
                <button class="um-btn um-btn-danger-ghost" title="Delete"
                  onclick="openDeleteModal('{{ $s->id }}', {{ json_encode($s->name) }})">
                  <i class="fas fa-trash-alt"></i>
                </button>
                @endif
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6">
              <div class="um-empty">
                <i class="fas fa-id-badge"></i>
                <p>No staff members found.</p>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

</div>

{{-- ═══════════════════════ ADD STAFF MODAL ═══════════════════════ --}}
<div class="um-modal-overlay" id="addModal">
  <div class="um-modal">
    <div class="um-modal-header">
      <div class="um-modal-title"><i class="fas fa-user-plus me-2" style="color:var(--um-teal)"></i>Add New Staff Member</div>
      <button class="um-modal-close" onclick="closeModal('addModal')"><i class="fas fa-times"></i></button>
    </div>
    <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
      @csrf
      <div class="um-modal-body">
        <div class="um-grid-2">
          <div class="um-field">
            <label>Full Name <span style="color:var(--um-danger)">*</span></label>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Jane Smith">
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
            <input type="email" name="email" value="{{ old('email') }}" placeholder="staff@example.com">
            @error('email')<div class="error-msg">{{ $message }}</div>@enderror
          </div>
          <div class="um-field">
            <label>Username <span style="color:var(--um-danger)">*</span></label>
            <input type="text" name="username" value="{{ old('username') }}" placeholder="jane_smith">
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
            <label>Role <span style="color:var(--um-danger)">*</span></label>
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
        <button type="submit" class="um-btn um-btn-primary"><i class="fas fa-save"></i> Create Staff</button>
      </div>
    </form>
  </div>
</div>

{{-- ═══════════════════════ EDIT STAFF MODAL ═══════════════════════ --}}
<div class="um-modal-overlay" id="editModal">
  <div class="um-modal">
    <div class="um-modal-header">
      <div class="um-modal-title"><i class="fas fa-user-edit me-2" style="color:var(--um-info)"></i>Edit Staff Member</div>
      <button class="um-modal-close" onclick="closeModal('editModal')"><i class="fas fa-times"></i></button>
    </div>
    <form method="POST" id="editUserForm" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <input type="hidden" name="id"   id="edit_id">
      <input type="hidden" name="slug" id="edit_slug">
      <div class="um-modal-body">
        <div class="um-grid-2">
          <div class="um-field">
            <label>Full Name <span style="color:var(--um-danger)">*</span></label>
            <input type="text" name="name" id="edit_name" placeholder="Jane Smith">
          </div>
          <div class="um-field">
            <label>Phone</label>
            <input type="text" name="phone" id="edit_phone" placeholder="+880 1xxx-xxxxxx">
          </div>
        </div>
        <div class="um-grid-2">
          <div class="um-field">
            <label>Email <span style="color:var(--um-danger)">*</span></label>
            <input type="email" name="email" id="edit_email">
          </div>
          <div class="um-field">
            <label>Username</label>
            <input type="text" id="edit_username_display" disabled style="opacity:.55;">
          </div>
        </div>
        <div class="um-grid-2">
          <div class="um-field">
            <label>Role <span style="color:var(--um-danger)">*</span></label>
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
        <button type="submit" class="um-btn um-btn-primary" style="background:var(--um-info);color:#fff;">
          <i class="fas fa-save"></i> Update Staff
        </button>
      </div>
    </form>
  </div>
</div>

{{-- ═══════════════════════ DELETE MODAL ═══════════════════════ --}}
<div class="um-modal-overlay" id="deleteModal">
  <div class="um-modal" style="width:min(420px,96vw);">
    <div class="um-modal-body" style="text-align:center;padding:40px 28px 28px;">
      <div class="um-delete-icon"><i class="fas fa-user-minus"></i></div>
      <h5 style="font-weight:700;margin-bottom:8px;">Remove Staff Member?</h5>
      <p style="color:var(--um-muted);font-size:.88rem;margin-bottom:4px;">
        You are about to delete <strong id="delete-name" style="color:var(--um-text)"></strong>.
      </p>
      <p style="color:var(--um-muted);font-size:.8rem;">This action <strong style="color:var(--um-danger)">cannot be undone</strong>.</p>
    </div>
    <div class="um-modal-footer" style="justify-content:center;gap:14px;">
      <button class="um-btn um-btn-outline" onclick="closeModal('deleteModal')"><i class="fas fa-times"></i> Cancel</button>
      <form id="deleteUserForm" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" class="um-btn um-btn-primary" style="background:var(--um-danger);">
          <i class="fas fa-trash-alt"></i> Yes, Remove
        </button>
      </form>
    </div>
  </div>
</div>

{{-- ═══════════════════════ TOAST ═══════════════════════ --}}
<div id="um-toast-container"></div>

{{-- ═══════════════════════ SCRIPTS ═══════════════════════ --}}
<script>
// ── Modal ───────────────────────────────────────────
function openModal(id)  { document.getElementById(id).classList.add('open'); document.body.style.overflow='hidden'; }
function closeModal(id) { document.getElementById(id).classList.remove('open'); document.body.style.overflow=''; }

document.querySelectorAll('.um-modal-overlay').forEach(el => {
  el.addEventListener('click', e => { if(e.target===el) closeModal(el.id); });
});
document.addEventListener('keydown', e => {
  if(e.key==='Escape') document.querySelectorAll('.um-modal-overlay.open').forEach(el=>closeModal(el.id));
});

// ── Add ─────────────────────────────────────────────
function openAddModal() { openModal('addModal'); }

@if($errors->any() && old('_method') !== 'PUT')
  openAddModal();
@endif

// ── Edit ────────────────────────────────────────────
function openEditModal(u) {
  document.getElementById('edit_id').value              = u.id;
  document.getElementById('edit_slug').value            = u.slug;
  document.getElementById('edit_name').value            = u.name;
  document.getElementById('edit_phone').value           = u.phone;
  document.getElementById('edit_email').value           = u.email;
  document.getElementById('edit_username_display').value= u.username;
  document.getElementById('edit_status').value          = u.status;
  document.getElementById('edit-preview').src           = u.photo;
  document.getElementById('editUserForm').action        = '/dashboard/user/edit/' + u.slug;

  @if($isSuperAdmin)
    var sel = document.getElementById('edit_role_id');
    if(sel) sel.value = u.role_id;
  @else
    var disp = document.getElementById('edit_role_display');
    if(disp) disp.textContent = u.role_name || '—';
  @endif

  openModal('editModal');
}

// ── Delete ──────────────────────────────────────────
function openDeleteModal(id, name) {
  document.getElementById('delete-name').textContent    = name;
  document.getElementById('deleteUserForm').action      = '/dashboard/user/delete/' + id;
  openModal('deleteModal');
}

// ── Photo preview ───────────────────────────────────
function previewImg(input, previewId) {
  var p = document.getElementById(previewId);
  if(input.files && input.files[0]) {
    var r = new FileReader();
    r.onload = e => { p.src = e.target.result; p.style.display = 'block'; };
    r.readAsDataURL(input.files[0]);
  }
}

// ── Live search + status filter ─────────────────────
(function() {
  var inp    = document.getElementById('um-search');
  var rows   = Array.from(document.querySelectorAll('#um-tbody tr[data-search]'));
  var count  = document.getElementById('um-count');
  var tabs   = document.querySelectorAll('.um-filter-tab');
  var active = 'all';

  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      tabs.forEach(t=>t.classList.remove('active'));
      tab.classList.add('active');
      active = tab.dataset.filter;
      update();
    });
  });

  inp.addEventListener('input', update);

  function update() {
    var q = inp.value.toLowerCase().trim();
    var vis = 0;
    rows.forEach(r => {
      var matchQ = !q || r.dataset.search.includes(q);
      var matchF = active==='all' || r.dataset.status===active;
      var show = matchQ && matchF;
      r.style.display = show ? '' : 'none';
      if(show) vis++;
    });
    count.textContent = vis + ' of ' + rows.length + ' staff';
  }
  update();
})();

// ── Toast ───────────────────────────────────────────
function showToast(type, title, msg) {
  var icons = { success:'fas fa-check-circle', danger:'fas fa-exclamation-circle', info:'fas fa-info-circle' };
  var c = document.getElementById('um-toast-container');
  var t = document.createElement('div');
  t.className = 'um-toast um-toast-' + type;
  t.innerHTML = `
    <div class="um-toast-icon"><i class="${icons[type]||icons.info}"></i></div>
    <div><div class="um-toast-title">${title}</div><div class="um-toast-msg">${msg}</div></div>
    <span class="um-toast-bar"></span>`;
  c.appendChild(t);
  setTimeout(()=>t.classList.add('show'), 20);
  setTimeout(()=>{ t.classList.remove('show'); setTimeout(()=>t.remove(),400); }, 3500);
}

// flash
(function(){
  var s=document.getElementById('flash-success');
  var e=document.getElementById('flash-error');
  if(s) showToast('success','Success',s.dataset.msg);
  if(e) showToast('danger','Error',e.dataset.msg);
})();
</script>

@endsection