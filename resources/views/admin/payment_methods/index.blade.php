@extends('layouts.admin')
@section('title', 'Payment Methods')
@section('page')

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');
:root {
  --p-surface:   #1a1d27;
  --p-surface2:  #222636;
  --p-border:    rgba(255,255,255,.07);
  --p-accent:    #0ea5e9;
  --p-accent2:   #38bdf8;
  --p-green:     #10b981;
  --p-green2:    #34d399;
  --p-success:   #22c55e;
  --p-danger:    #ef4444;
  --p-warning:   #f59e0b;
  --p-purple:    #8b5cf6;
  --p-text:      #e2e8f0;
  --p-muted:     #64748b;
  --p-radius:    14px;
  --p-radius-sm: 8px;
  --p-shadow:    0 8px 32px rgba(0,0,0,.45);
}
.pm-wrap { font-family:'Plus Jakarta Sans',sans-serif; color:var(--p-text); }

/* header */
.pm-header {
  background:linear-gradient(135deg,#0c1a2e 0%,#0c3558 50%,#083344 100%);
  border-radius:var(--p-radius); padding:28px 32px;
  margin-bottom:24px; position:relative; overflow:hidden; box-shadow:var(--p-shadow);
}
.pm-header::before { content:''; position:absolute; inset:0; background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%230ea5e9' fill-opacity='0.05'%3E%3Cpath d='M30 5L55 20v20L30 55 5 40V20Z'/%3E%3C/g%3E%3C/svg%3E"); }
.pm-header::after { content:''; position:absolute; right:-40px; top:-40px; width:200px; height:200px; border-radius:50%; background:radial-gradient(circle,rgba(14,165,233,.18) 0%,transparent 70%); }
.pm-header .title { font-size:1.5rem; font-weight:700; position:relative; z-index:1; background:linear-gradient(90deg,#fff,var(--p-accent2)); -webkit-background-clip:text; -webkit-text-fill-color:transparent; }
.pm-header .subtitle { color:rgba(255,255,255,.45); font-size:.85rem; margin-top:4px; position:relative; z-index:1; }

.stat-pill { display:inline-flex; align-items:center; gap:8px; background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.1); border-radius:40px; padding:6px 16px; font-size:.8rem; font-weight:600; color:#fff; position:relative; z-index:1; }
.stat-pill .dot { width:8px; height:8px; border-radius:50%; }

/* method type icon mapping */
.pm-type-icon { width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1rem; flex-shrink:0; }
.pm-type-bkash   { background:rgba(244,63,142,.12); color:#f9a8d4; }
.pm-type-nagad   { background:rgba(249,115,22,.12);  color:#fdba74; }
.pm-type-rocket  { background:rgba(139,92,246,.12);  color:#c4b5fd; }
.pm-type-stripe  { background:rgba(99,91,255,.12);   color:#a5b4fc; }
.pm-type-paypal  { background:rgba(56,189,248,.12);  color:#7dd3fc; }
.pm-type-bank    { background:rgba(16,185,129,.12);  color:#6ee7b7; }
.pm-type-default { background:rgba(100,116,139,.1);  color:#94a3b8; }

/* card grid */
.pm-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(300px,1fr)); gap:16px; margin-bottom:24px; }
.pm-method-card {
  background:var(--p-surface); border:1px solid var(--p-border);
  border-radius:var(--p-radius); padding:20px;
  box-shadow:var(--p-shadow); transition:transform .2s,border-color .2s;
  position:relative; overflow:hidden;
}
.pm-method-card:hover { transform:translateY(-2px); border-color:rgba(255,255,255,.12); }
.pm-method-card::before { content:''; position:absolute; left:0; top:0; bottom:0; width:3px; border-radius:3px 0 0 3px; }
.pm-method-card.active-card::before  { background:var(--p-green); }
.pm-method-card.inactive-card::before { background:var(--p-danger); }

.pm-card-top { display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:14px; }
.pm-card-left { display:flex; align-items:center; gap:12px; }
.pm-card-name  { font-weight:700; font-size:.95rem; }
.pm-card-type  { font-size:.72rem; color:var(--p-muted); text-transform:uppercase; letter-spacing:.06em; margin-top:2px; }
.pm-card-actions { display:flex; gap:6px; }

.pm-account { font-family:'JetBrains Mono',monospace; font-size:.8rem; background:var(--p-surface2); border:1px solid var(--p-border); padding:6px 12px; border-radius:var(--p-radius-sm); color:var(--p-accent2); margin-bottom:12px; display:flex; align-items:center; justify-content:space-between; }
.pm-account-copy { background:none; border:none; color:var(--p-muted); cursor:pointer; font-size:.7rem; transition:color .2s; }
.pm-account-copy:hover { color:var(--p-accent); }

.pm-desc { font-size:.78rem; color:var(--p-muted); line-height:1.5; margin-bottom:12px; }

.pm-footer-row { display:flex; align-items:center; justify-content:space-between; }

/* badges */
.pm-badge { display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:20px; font-size:.72rem; font-weight:700; }
.pm-badge-active   { background:rgba(34,197,94,.12); color:#86efac; border:1px solid rgba(34,197,94,.25); }
.pm-badge-inactive { background:rgba(239,68,68,.12); color:#fca5a5; border:1px solid rgba(239,68,68,.25); }

/* has api key indicator */
.pm-api-dot { display:inline-flex; align-items:center; gap:5px; font-size:.72rem; color:var(--p-muted); }
.pm-api-dot i { font-size:.55rem; }

/* buttons */
.pm-btn { display:inline-flex; align-items:center; gap:6px; border:none; cursor:pointer; font-family:inherit; font-weight:600; border-radius:var(--p-radius-sm); transition:all .2s; text-decoration:none; }
.pm-btn-primary { background:var(--p-accent); color:#0c1a2e; padding:9px 18px; font-size:.85rem; }
.pm-btn-primary:hover { background:var(--p-accent2); transform:translateY(-1px); box-shadow:0 4px 14px rgba(14,165,233,.35); color:#0c1a2e; }
.pm-btn-outline { background:transparent; color:var(--p-text); border:1px solid var(--p-border); padding:9px 14px; font-size:.82rem; }
.pm-btn-outline:hover { background:var(--p-surface2); color:var(--p-text); }
.pm-btn-icon { background:var(--p-surface2); color:var(--p-muted); border:1px solid var(--p-border); padding:6px 10px; font-size:.78rem; border-radius:6px; }
.pm-btn-icon-edit:hover { color:var(--p-warning); border-color:rgba(245,158,11,.3); }
.pm-btn-danger-ghost { background:rgba(239,68,68,.1); color:#fca5a5; border:1px solid rgba(239,68,68,.2); padding:6px 10px; font-size:.78rem; border-radius:6px; }
.pm-btn-danger-ghost:hover { background:rgba(239,68,68,.2); }

/* MODAL */
.pm-modal-overlay { position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,.72); backdrop-filter:blur(6px); display:flex; align-items:center; justify-content:center; opacity:0; pointer-events:none; transition:opacity .25s; }
.pm-modal-overlay.open { opacity:1; pointer-events:auto; }
.pm-modal { background:var(--p-surface); border:1px solid var(--p-border); border-radius:18px; width:min(600px,96vw); max-height:90vh; overflow-y:auto; box-shadow:0 24px 64px rgba(0,0,0,.6); transform:translateY(24px) scale(.97); transition:transform .3s cubic-bezier(.34,1.56,.64,1); }
.pm-modal-overlay.open .pm-modal { transform:translateY(0) scale(1); }
.pm-modal-header { padding:22px 28px 18px; border-bottom:1px solid var(--p-border); display:flex; align-items:center; justify-content:space-between; }
.pm-modal-title  { font-size:1.1rem; font-weight:700; }
.pm-modal-close  { background:var(--p-surface2); border:1px solid var(--p-border); color:var(--p-muted); width:32px; height:32px; border-radius:8px; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:all .2s; }
.pm-modal-close:hover { color:var(--p-text); }
.pm-modal-body   { padding:24px 28px; }
.pm-modal-footer { padding:18px 28px; border-top:1px solid var(--p-border); display:flex; gap:10px; justify-content:flex-end; }

/* form fields */
.pm-field { margin-bottom:18px; }
.pm-field label { display:block; font-size:.8rem; font-weight:600; color:var(--p-muted); margin-bottom:7px; text-transform:uppercase; letter-spacing:.06em; }
.pm-field input,.pm-field select,.pm-field textarea {
  width:100%; background:var(--p-surface2); border:1px solid var(--p-border);
  border-radius:var(--p-radius-sm); padding:10px 14px; color:var(--p-text);
  font-family:inherit; font-size:.875rem; outline:none; resize:vertical;
  transition:border-color .2s,box-shadow .2s;
}
.pm-field input:focus,.pm-field select:focus,.pm-field textarea:focus { border-color:var(--p-accent); box-shadow:0 0 0 3px rgba(14,165,233,.12); }
.pm-field .mono { font-family:'JetBrains Mono',monospace; letter-spacing:.04em; }
.pm-field .err   { color:#fca5a5; font-size:.78rem; margin-top:5px; }
.pm-grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:0 18px; }
.pm-section { font-size:.7rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:var(--p-accent2); margin:4px 0 16px; display:flex; align-items:center; gap:8px; }
.pm-section::after { content:''; flex:1; height:1px; background:var(--p-border); }

/* api key field — password style toggle */
.pm-key-wrap { position:relative; }
.pm-key-wrap input { padding-right:44px; }
.pm-key-toggle { position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; color:var(--p-muted); cursor:pointer; font-size:.8rem; transition:color .2s; }
.pm-key-toggle:hover { color:var(--p-text); }

/* type selector chips */
.pm-type-chips { display:flex; gap:8px; flex-wrap:wrap; margin-top:8px; }
.pm-type-chip { padding:5px 14px; border-radius:20px; font-size:.75rem; font-weight:600; cursor:pointer; border:1px solid var(--p-border); background:var(--p-surface2); color:var(--p-muted); transition:all .2s; }
.pm-type-chip.sel-bkash   { background:rgba(244,63,142,.15); color:#f9a8d4; border-color:rgba(244,63,142,.3); }
.pm-type-chip.sel-nagad   { background:rgba(249,115,22,.15);  color:#fdba74; border-color:rgba(249,115,22,.3); }
.pm-type-chip.sel-stripe  { background:rgba(99,91,255,.15);   color:#a5b4fc; border-color:rgba(99,91,255,.3); }
.pm-type-chip.sel-paypal  { background:rgba(56,189,248,.15);  color:#7dd3fc; border-color:rgba(56,189,248,.3); }
.pm-type-chip.sel-bank    { background:rgba(16,185,129,.15);  color:#6ee7b7; border-color:rgba(16,185,129,.3); }

/* delete modal */
.pm-del-icon { width:64px; height:64px; border-radius:50%; background:rgba(239,68,68,.12); border:2px solid rgba(239,68,68,.25); display:flex; align-items:center; justify-content:center; font-size:1.5rem; color:#fca5a5; margin:0 auto 18px; }

/* toast */
#pm-toast-container { position:fixed; bottom:28px; right:28px; z-index:99999; display:flex; flex-direction:column; gap:10px; }
.pm-toast { display:flex; align-items:center; gap:12px; background:var(--p-surface); border:1px solid var(--p-border); border-radius:12px; padding:14px 18px; min-width:280px; box-shadow:0 8px 30px rgba(0,0,0,.5); transform:translateX(120%); transition:transform .35s cubic-bezier(.34,1.56,.64,1); font-family:'Plus Jakarta Sans',sans-serif; position:relative; overflow:hidden; }
.pm-toast.show { transform:translateX(0); }
.pm-toast-icon { width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.pm-toast-success .pm-toast-icon { background:rgba(34,197,94,.15);  color:var(--p-success); }
.pm-toast-danger  .pm-toast-icon { background:rgba(239,68,68,.15);   color:var(--p-danger); }
.pm-toast-info    .pm-toast-icon { background:rgba(56,189,248,.15);  color:var(--p-accent); }
.pm-toast-title { font-size:.875rem; font-weight:700; color:var(--p-text); }
.pm-toast-msg   { font-size:.78rem;  color:var(--p-muted); margin-top:2px; }
.pm-toast-bar   { position:absolute; bottom:0; left:0; height:3px; border-radius:0 0 12px 12px; animation:pmBar 3.5s linear forwards; }
.pm-toast-success .pm-toast-bar { background:var(--p-success); }
.pm-toast-danger  .pm-toast-bar { background:var(--p-danger); }
.pm-toast-info    .pm-toast-bar { background:var(--p-accent); }
@keyframes pmBar { from{width:100%} to{width:0%} }
.pm-modal::-webkit-scrollbar { width:5px; }
.pm-modal::-webkit-scrollbar-thumb { background:var(--p-border); border-radius:10px; }
.pm-empty { text-align:center; padding:60px 20px; color:var(--p-muted); }
.pm-empty i { font-size:2.5rem; margin-bottom:14px; opacity:.4; display:block; }
</style>

@if(session('success'))<div id="flash-success" data-msg="{{ session('success') }}"></div>@endif
@if(session('error'))<div id="flash-error"   data-msg="{{ session('error') }}"></div>@endif

<div class="pm-wrap">

  {{-- Header --}}
  <div class="pm-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
      <div>
        <div class="title"><i class="fas fa-credit-card me-2"></i>Payment Methods</div>
        <div class="subtitle">Manage all active and inactive payment gateways</div>
        <div class="d-flex gap-2 mt-3 flex-wrap">
          <span class="stat-pill"><span class="dot" style="background:var(--p-success)"></span>{{ collect($methods)->where('status',1)->count() }} Active</span>
          <span class="stat-pill"><span class="dot" style="background:var(--p-danger)"></span>{{ collect($methods)->where('status',0)->count() }} Inactive</span>
          <span class="stat-pill"><span class="dot" style="background:var(--p-accent2)"></span>{{ collect($methods)->count() }} Total</span>
        </div>
      </div>
      <div style="position:relative;z-index:1;">
        <button class="pm-btn pm-btn-primary" onclick="openAddModal()">
          <i class="fas fa-plus"></i> Add Method
        </button>
      </div>
    </div>
  </div>

  {{-- Cards Grid --}}
  @if(count($methods) > 0)
  <div class="pm-grid">
    @foreach($methods as $key => $method)
    @php
      $type = strtolower($method->type ?? 'other');
      $typeClass = match(true) {
        str_contains($type,'bkash')  => 'pm-type-bkash',
        str_contains($type,'nagad')  => 'pm-type-nagad',
        str_contains($type,'rocket') => 'pm-type-rocket',
        str_contains($type,'stripe') => 'pm-type-stripe',
        str_contains($type,'paypal') => 'pm-type-paypal',
        str_contains($type,'bank')   => 'pm-type-bank',
        default                      => 'pm-type-default',
      };
      $typeIcon = match(true) {
        str_contains($type,'bkash')  => 'fas fa-mobile-alt',
        str_contains($type,'nagad')  => 'fas fa-mobile-alt',
        str_contains($type,'rocket') => 'fas fa-rocket',
        str_contains($type,'stripe') => 'fab fa-stripe-s',
        str_contains($type,'paypal') => 'fab fa-paypal',
        str_contains($type,'bank')   => 'fas fa-university',
        default                      => 'fas fa-credit-card',
      };
    @endphp
    <div class="pm-method-card {{ $method->status ? 'active-card' : 'inactive-card' }}">
      <div class="pm-card-top">
        <div class="pm-card-left">
          <div class="pm-type-icon {{ $typeClass }}">
            <i class="{{ $typeIcon }}"></i>
          </div>
          <div>
            <div class="pm-card-name">{{ $method->name }}</div>
            <div class="pm-card-type">{{ ucfirst($method->type) }}</div>
          </div>
        </div>
        <div class="pm-card-actions">
          <button class="pm-btn pm-btn-icon pm-btn-icon-edit" title="Edit"
            onclick="openEditModal({
              id: '{{ $method->id }}',
              name: {{ json_encode($method->name) }},
              type: {{ json_encode($method->type) }},
              account_number: {{ json_encode($method->account_number ?? '') }},
              api_key: {{ json_encode($method->api_key ?? '') }},
              secret_key: {{ json_encode($method->secret_key ?? '') }},
              description: {{ json_encode($method->description ?? '') }},
              status: '{{ $method->status }}'
            })">
            <i class="fas fa-pen"></i>
          </button>
          <button class="pm-btn pm-btn-danger-ghost" title="Delete"
            onclick="openDeleteModal('{{ $method->id }}', {{ json_encode($method->name) }})">
            <i class="fas fa-trash-alt"></i>
          </button>
        </div>
      </div>

      @if($method->account_number)
      <div class="pm-account">
        <span>{{ $method->account_number }}</span>
        <button class="pm-account-copy" onclick="copyText('{{ $method->account_number }}')" title="Copy">
          <i class="fas fa-copy"></i>
        </button>
      </div>
      @endif

      @if($method->description)
        <div class="pm-desc">{{ Str::limit($method->description, 80) }}</div>
      @endif

      <div class="pm-footer-row">
        <span class="pm-badge {{ $method->status ? 'pm-badge-active' : 'pm-badge-inactive' }}">
          <i class="fas fa-circle" style="font-size:.4rem;"></i>
          {{ $method->status ? 'Active' : 'Inactive' }}
        </span>
        @if($method->api_key)
          <span class="pm-api-dot"><i class="fas fa-key" style="color:var(--p-warning);"></i> API Key set</span>
        @endif
      </div>
    </div>
    @endforeach
  </div>
  @else
  <div class="pm-empty" style="background:var(--p-surface);border:1px solid var(--p-border);border-radius:var(--p-radius);box-shadow:var(--p-shadow);">
    <i class="fas fa-credit-card"></i>
    <p>No payment methods added yet. Click <strong>Add Method</strong> to get started.</p>
  </div>
  @endif

</div>

{{-- ═══ ADD MODAL ═══ --}}
<div class="pm-modal-overlay" id="addModal">
  <div class="pm-modal">
    <div class="pm-modal-header">
      <div class="pm-modal-title"><i class="fas fa-plus-circle me-2" style="color:var(--p-accent2)"></i>Add Payment Method</div>
      <button class="pm-modal-close" onclick="closeModal('addModal')"><i class="fas fa-times"></i></button>
    </div>
    <form method="POST" action="{{ route('admin.payment_methods.store') }}">
      @csrf
      <div class="pm-modal-body">

        <div class="pm-section"><i class="fas fa-info-circle"></i> Basic Info</div>
        <div class="pm-grid-2">
          <div class="pm-field">
            <label>Method Name <span style="color:var(--p-danger)">*</span></label>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. bKash">
            @error('name')<div class="err">{{ $message }}</div>@enderror
          </div>
          <div class="pm-field">
            <label>Type <span style="color:var(--p-danger)">*</span></label>
            <input type="text" name="type" id="add_type" value="{{ old('type') }}" placeholder="bkash / bank / stripe">
            <div class="pm-type-chips">
              @foreach(['bkash','nagad','stripe','paypal','bank'] as $t)
                <span class="pm-type-chip" onclick="setType('add','{{ $t }}')">{{ ucfirst($t) }}</span>
              @endforeach
            </div>
            @error('type')<div class="err">{{ $message }}</div>@enderror
          </div>
        </div>

        <div class="pm-grid-2">
          <div class="pm-field">
            <label>Account / Number</label>
            <input type="text" name="account_number" value="{{ old('account_number') }}" placeholder="01XXXXXXXXX or IBAN" class="mono">
          </div>
          <div class="pm-field">
            <label>Status</label>
            <select name="status">
              <option value="1" {{ old('status',1)==1?'selected':'' }}>Active</option>
              <option value="0" {{ old('status')==0?'selected':'' }}>Inactive</option>
            </select>
          </div>
        </div>

        <div class="pm-section"><i class="fas fa-key"></i> API Credentials <span style="font-size:.65rem;color:var(--p-muted);font-weight:400;text-transform:none;letter-spacing:0;">(optional)</span></div>
        <div class="pm-grid-2">
          <div class="pm-field">
            <label>API Key</label>
            <div class="pm-key-wrap">
              <input type="password" name="api_key" id="add_api_key" value="{{ old('api_key') }}" placeholder="sk_live_...">
              <button type="button" class="pm-key-toggle" onclick="toggleKey('add_api_key',this)"><i class="fas fa-eye"></i></button>
            </div>
          </div>
          <div class="pm-field">
            <label>Secret Key</label>
            <div class="pm-key-wrap">
              <input type="password" name="secret_key" id="add_secret_key" value="{{ old('secret_key') }}" placeholder="sk_secret_...">
              <button type="button" class="pm-key-toggle" onclick="toggleKey('add_secret_key',this)"><i class="fas fa-eye"></i></button>
            </div>
          </div>
        </div>

        <div class="pm-field">
          <label>Description</label>
          <textarea name="description" rows="2" placeholder="Short note about this payment method...">{{ old('description') }}</textarea>
        </div>

      </div>
      <div class="pm-modal-footer">
        <button type="button" class="pm-btn pm-btn-outline" onclick="closeModal('addModal')">Cancel</button>
        <button type="submit" class="pm-btn pm-btn-primary"><i class="fas fa-save"></i> Save Method</button>
      </div>
    </form>
  </div>
</div>

{{-- ═══ EDIT MODAL ═══ --}}
<div class="pm-modal-overlay" id="editModal">
  <div class="pm-modal">
    <div class="pm-modal-header">
      <div class="pm-modal-title"><i class="fas fa-pen me-2" style="color:var(--p-warning)"></i>Edit Payment Method</div>
      <button class="pm-modal-close" onclick="closeModal('editModal')"><i class="fas fa-times"></i></button>
    </div>
    <form method="POST" id="editForm">
      @csrf
      @method('PUT')
      <input type="hidden" name="id" id="edit_id">
      <div class="pm-modal-body">

        <div class="pm-section"><i class="fas fa-info-circle"></i> Basic Info</div>
        <div class="pm-grid-2">
          <div class="pm-field">
            <label>Method Name <span style="color:var(--p-danger)">*</span></label>
            <input type="text" name="name" id="edit_name" placeholder="e.g. bKash">
          </div>
          <div class="pm-field">
            <label>Type <span style="color:var(--p-danger)">*</span></label>
            <input type="text" name="type" id="edit_type" placeholder="bkash / bank / stripe">
            <div class="pm-type-chips">
              @foreach(['bkash','nagad','stripe','paypal','bank'] as $t)
                <span class="pm-type-chip" onclick="setType('edit','{{ $t }}')">{{ ucfirst($t) }}</span>
              @endforeach
            </div>
          </div>
        </div>

        <div class="pm-grid-2">
          <div class="pm-field">
            <label>Account / Number</label>
            <input type="text" name="account_number" id="edit_account_number" placeholder="01XXXXXXXXX" class="mono">
          </div>
          <div class="pm-field">
            <label>Status</label>
            <select name="status" id="edit_status">
              <option value="1">Active</option>
              <option value="0">Inactive</option>
            </select>
          </div>
        </div>

        <div class="pm-section"><i class="fas fa-key"></i> API Credentials <span style="font-size:.65rem;color:var(--p-muted);font-weight:400;text-transform:none;letter-spacing:0;">(leave blank to keep existing)</span></div>
        <div class="pm-grid-2">
          <div class="pm-field">
            <label>API Key</label>
            <div class="pm-key-wrap">
              <input type="password" name="api_key" id="edit_api_key" placeholder="Leave blank to keep">
              <button type="button" class="pm-key-toggle" onclick="toggleKey('edit_api_key',this)"><i class="fas fa-eye"></i></button>
            </div>
          </div>
          <div class="pm-field">
            <label>Secret Key</label>
            <div class="pm-key-wrap">
              <input type="password" name="secret_key" id="edit_secret_key" placeholder="Leave blank to keep">
              <button type="button" class="pm-key-toggle" onclick="toggleKey('edit_secret_key',this)"><i class="fas fa-eye"></i></button>
            </div>
          </div>
        </div>

        <div class="pm-field">
          <label>Description</label>
          <textarea name="description" id="edit_description" rows="2" placeholder="Short note..."></textarea>
        </div>

      </div>
      <div class="pm-modal-footer">
        <button type="button" class="pm-btn pm-btn-outline" onclick="closeModal('editModal')">Cancel</button>
        <button type="submit" class="pm-btn pm-btn-primary" style="background:var(--p-warning);color:#1a1d27;">
          <i class="fas fa-save"></i> Update Method
        </button>
      </div>
    </form>
  </div>
</div>

{{-- ═══ DELETE MODAL ═══ --}}
<div class="pm-modal-overlay" id="deleteModal">
  <div class="pm-modal" style="width:min(420px,96vw);">
    <div class="pm-modal-body" style="text-align:center;padding:40px 28px 28px;">
      <div class="pm-del-icon"><i class="fas fa-credit-card"></i></div>
      <h5 style="font-weight:700;margin-bottom:8px;">Delete Payment Method?</h5>
      <p style="color:var(--p-muted);font-size:.88rem;margin-bottom:4px;">
        <strong id="del-method-name" style="color:var(--p-text)"></strong> will be permanently deleted.
      </p>
      <p style="color:var(--p-muted);font-size:.8rem;">This action <strong style="color:var(--p-danger)">cannot be undone</strong>.</p>
    </div>
    <div class="pm-modal-footer" style="justify-content:center;gap:14px;">
      <button class="pm-btn pm-btn-outline" onclick="closeModal('deleteModal')"><i class="fas fa-times"></i> Cancel</button>
      <a id="deleteLink" href="#" class="pm-btn pm-btn-primary" style="background:var(--p-danger);color:#fff;padding:9px 18px;font-size:.875rem;">
        <i class="fas fa-trash-alt"></i> Yes, Delete
      </a>
    </div>
  </div>
</div>

<div id="pm-toast-container"></div>

<script>
// ── Modal helpers ──────────────────────────────────
function openModal(id)  { document.getElementById(id).classList.add('open'); document.body.style.overflow='hidden'; }
function closeModal(id) { document.getElementById(id).classList.remove('open'); document.body.style.overflow=''; }
document.querySelectorAll('.pm-modal-overlay').forEach(el => {
  el.addEventListener('click', e => { if(e.target===el) closeModal(el.id); });
});
document.addEventListener('keydown', e => {
  if(e.key==='Escape') document.querySelectorAll('.pm-modal-overlay.open').forEach(el=>closeModal(el.id));
});

// ── Add ────────────────────────────────────────────
function openAddModal() { openModal('addModal'); }
@if($errors->any() && old('_method') !== 'PUT') openAddModal(); @endif

// ── Type chip ──────────────────────────────────────
function setType(prefix, type) {
  document.getElementById(prefix + '_type').value = type;
  // update chip highlight
  var chips = document.querySelectorAll('#' + (prefix==='add'?'addModal':'editModal') + ' .pm-type-chip');
  chips.forEach(c => {
    c.className = 'pm-type-chip';
    if(c.textContent.toLowerCase().trim() === type) c.classList.add('sel-' + type);
  });
}

// ── Edit ───────────────────────────────────────────
function openEditModal(d) {
  document.getElementById('edit_id').value             = d.id;
  document.getElementById('edit_name').value           = d.name;
  document.getElementById('edit_type').value           = d.type;
  document.getElementById('edit_account_number').value = d.account_number;
  document.getElementById('edit_api_key').value        = '';
  document.getElementById('edit_secret_key').value     = '';
  document.getElementById('edit_description').value    = d.description;
  document.getElementById('edit_status').value         = d.status;
  document.getElementById('editForm').action           = '/admin/payment-methods/' + d.id;

  // highlight type chip
  var chips = document.querySelectorAll('#editModal .pm-type-chip');
  chips.forEach(c => {
    c.className = 'pm-type-chip';
    if(c.textContent.toLowerCase().trim() === d.type.toLowerCase()) c.classList.add('sel-' + d.type.toLowerCase());
  });

  openModal('editModal');
}

// ── Delete ─────────────────────────────────────────
function openDeleteModal(id, name) {
  document.getElementById('del-method-name').textContent = name;
  document.getElementById('deleteLink').href = '/admin/payment-methods/delete/' + id;
  openModal('deleteModal');
}

// ── API key toggle ─────────────────────────────────
function toggleKey(inputId, btn) {
  var inp = document.getElementById(inputId);
  var isPass = inp.type === 'password';
  inp.type = isPass ? 'text' : 'password';
  btn.querySelector('i').className = isPass ? 'fas fa-eye-slash' : 'fas fa-eye';
}

// ── Copy ───────────────────────────────────────────
function copyText(text) {
  navigator.clipboard.writeText(text).then(() => {
    showToast('info', 'Copied!', text + ' copied to clipboard.');
  });
}

// ── Toast ──────────────────────────────────────────
function showToast(type, title, msg) {
  var icons = {success:'fas fa-check-circle', danger:'fas fa-exclamation-circle', info:'fas fa-copy'};
  var c = document.getElementById('pm-toast-container');
  var t = document.createElement('div');
  t.className = 'pm-toast pm-toast-' + type;
  t.innerHTML = `<div class="pm-toast-icon"><i class="${icons[type]||icons.info}"></i></div>
    <div><div class="pm-toast-title">${title}</div><div class="pm-toast-msg">${msg}</div></div>
    <span class="pm-toast-bar"></span>`;
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