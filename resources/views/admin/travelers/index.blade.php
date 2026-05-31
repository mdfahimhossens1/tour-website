@extends('layouts.admin')
@section('title', 'Travelers')
@section('page')

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');

:root {
  --t-surface:   #1a1d27;
  --t-surface2:  #222636;
  --t-border:    rgba(255,255,255,.07);
  --t-accent:    #8b5cf6;
  --t-accent2:   #a78bfa;
  --t-success:   #22c55e;
  --t-danger:    #ef4444;
  --t-warning:   #f59e0b;
  --t-info:      #38bdf8;
  --t-teal:      #2dd4bf;
  --t-text:      #e2e8f0;
  --t-muted:     #64748b;
  --t-radius:    14px;
  --t-radius-sm: 8px;
  --t-shadow:    0 8px 32px rgba(0,0,0,.45);
}

.tv-wrap { font-family:'Plus Jakarta Sans',sans-serif; color:var(--t-text); }

/* header */
.tv-header {
  background: linear-gradient(135deg,#1e1035 0%,#2d1b69 50%,#3b1f8c 100%);
  border-radius:var(--t-radius); padding:28px 32px;
  margin-bottom:24px; position:relative; overflow:hidden; box-shadow:var(--t-shadow);
}
.tv-header::before {
  content:''; position:absolute; inset:0;
  background:url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%238b5cf6' fill-opacity='0.05'%3E%3Cpath d='M40 0L80 40L40 80L0 40Z'/%3E%3C/g%3E%3C/svg%3E");
}
.tv-header::after {
  content:''; position:absolute; right:-50px; top:-50px;
  width:200px; height:200px; border-radius:50%;
  background:radial-gradient(circle,rgba(139,92,246,.18) 0%,transparent 70%);
}
.tv-header .title {
  font-size:1.5rem; font-weight:700; position:relative; z-index:1;
  background:linear-gradient(90deg,#fff,var(--t-accent2));
  -webkit-background-clip:text; -webkit-text-fill-color:transparent;
}
.tv-header .subtitle { color:rgba(255,255,255,.45); font-size:.85rem; margin-top:4px; position:relative; z-index:1; }

.stat-pill {
  display:inline-flex; align-items:center; gap:8px;
  background:rgba(255,255,255,.07); border:1px solid rgba(255,255,255,.1);
  border-radius:40px; padding:6px 16px;
  font-size:.8rem; font-weight:600; color:#fff; position:relative; z-index:1;
}
.stat-pill .dot { width:8px; height:8px; border-radius:50%; }

/* table card */
.tv-card {
  background:var(--t-surface); border:1px solid var(--t-border);
  border-radius:var(--t-radius); overflow:hidden; box-shadow:var(--t-shadow);
}
.tv-search-bar {
  padding:16px 20px; border-bottom:1px solid var(--t-border);
  display:flex; align-items:center; gap:12px; flex-wrap:wrap; justify-content:space-between;
}
.tv-search-wrap { position:relative; }
.tv-search-wrap .si { position:absolute; left:11px; top:50%; transform:translateY(-50%); color:var(--t-muted); font-size:.8rem; }
.tv-search-input {
  background:var(--t-surface2); border:1px solid var(--t-border);
  border-radius:var(--t-radius-sm); padding:8px 14px 8px 36px;
  color:var(--t-text); font-family:inherit; font-size:.875rem;
  width:260px; outline:none; transition:border-color .2s;
}
.tv-search-input:focus { border-color:var(--t-accent); box-shadow:0 0 0 3px rgba(139,92,246,.12); }

/* filter tabs */
.tv-filter-tabs { display:flex; gap:6px; }
.tv-filter-tab {
  background:var(--t-surface2); border:1px solid var(--t-border);
  border-radius:20px; padding:5px 14px; font-size:.78rem;
  font-weight:600; color:var(--t-muted); cursor:pointer;
  font-family:inherit; transition:all .2s;
}
.tv-filter-tab:hover,.tv-filter-tab.active { background:var(--t-accent); color:#fff; border-color:var(--t-accent); }

/* table */
.tv-table { width:100%; border-collapse:collapse; }
.tv-table thead tr { background:var(--t-surface2); }
.tv-table th { padding:13px 18px; text-align:left; font-size:.72rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:var(--t-muted); white-space:nowrap; }
.tv-table td { padding:13px 18px; vertical-align:middle; border-bottom:1px solid var(--t-border); font-size:.875rem; }
.tv-table tbody tr { transition:background .15s; }
.tv-table tbody tr:hover { background:rgba(139,92,246,.04); }
.tv-table tbody tr:last-child td { border-bottom:none; }

/* traveler cell */
.tv-person { display:flex; align-items:center; gap:10px; }
.tv-avatar {
  width:36px; height:36px; border-radius:50%; flex-shrink:0;
  display:flex; align-items:center; justify-content:center;
  font-size:.8rem; font-weight:700; border:2px solid var(--t-border);
}
.tv-avatar-male   { background:rgba(56,189,248,.15); color:var(--t-info); }
.tv-avatar-female { background:rgba(244,114,182,.15); color:#f472b6; }
.tv-avatar-other  { background:rgba(139,92,246,.15); color:var(--t-accent2); }
.tv-person-name   { font-weight:600; }
.tv-person-age    { font-size:.75rem; color:var(--t-muted); margin-top:2px; }

.tv-code { font-family:'JetBrains Mono',monospace; font-size:.78rem; background:var(--t-surface2); border:1px solid var(--t-border); padding:3px 8px; border-radius:6px; color:var(--t-accent2); }
.tv-mono { font-family:'JetBrains Mono',monospace; font-size:.82rem; color:var(--t-muted); }

/* gender badge */
.tv-badge { display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:20px; font-size:.72rem; font-weight:700; }
.tv-badge-male   { background:rgba(56,189,248,.12); color:#7dd3fc; border:1px solid rgba(56,189,248,.25); }
.tv-badge-female { background:rgba(244,114,182,.12); color:#f9a8d4; border:1px solid rgba(244,114,182,.25); }
.tv-badge-other  { background:rgba(139,92,246,.12); color:#c4b5fd; border:1px solid rgba(139,92,246,.25); }

/* buttons */
.tv-btn { display:inline-flex; align-items:center; gap:6px; border:none; cursor:pointer; font-family:inherit; font-weight:600; border-radius:var(--t-radius-sm); transition:all .2s; text-decoration:none; }
.tv-btn-primary { background:var(--t-accent); color:#fff; padding:9px 18px; font-size:.85rem; }
.tv-btn-primary:hover { background:var(--t-accent2); transform:translateY(-1px); box-shadow:0 4px 14px rgba(139,92,246,.4); color:#fff; }
.tv-btn-outline { background:transparent; color:var(--t-text); border:1px solid var(--t-border); padding:9px 14px; font-size:.82rem; }
.tv-btn-outline:hover { background:var(--t-surface2); color:var(--t-text); }
.tv-btn-icon { background:var(--t-surface2); color:var(--t-muted); border:1px solid var(--t-border); padding:6px 10px; font-size:.78rem; border-radius:6px; }
.tv-btn-icon:hover { color:var(--t-info); border-color:rgba(56,189,248,.3); }
.tv-btn-icon-edit:hover { color:var(--t-warning); border-color:rgba(245,158,11,.3); }
.tv-btn-danger-ghost { background:rgba(239,68,68,.1); color:#fca5a5; border:1px solid rgba(239,68,68,.2); padding:6px 10px; font-size:.78rem; border-radius:6px; }
.tv-btn-danger-ghost:hover { background:rgba(239,68,68,.2); }
.tv-actions-cell { display:flex; gap:6px; align-items:center; }

/* MODAL */
.tv-modal-overlay {
  position:fixed; inset:0; z-index:9999;
  background:rgba(0,0,0,.72); backdrop-filter:blur(6px);
  display:flex; align-items:center; justify-content:center;
  opacity:0; pointer-events:none; transition:opacity .25s;
}
.tv-modal-overlay.open { opacity:1; pointer-events:auto; }
.tv-modal {
  background:var(--t-surface); border:1px solid var(--t-border);
  border-radius:18px; width:min(640px,96vw); max-height:90vh; overflow-y:auto;
  box-shadow:0 24px 64px rgba(0,0,0,.6);
  transform:translateY(24px) scale(.97);
  transition:transform .3s cubic-bezier(.34,1.56,.64,1);
}
.tv-modal-overlay.open .tv-modal { transform:translateY(0) scale(1); }
.tv-modal-header { padding:22px 28px 18px; border-bottom:1px solid var(--t-border); display:flex; align-items:center; justify-content:space-between; }
.tv-modal-title  { font-size:1.1rem; font-weight:700; }
.tv-modal-close  { background:var(--t-surface2); border:1px solid var(--t-border); color:var(--t-muted); width:32px; height:32px; border-radius:8px; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:all .2s; }
.tv-modal-close:hover { color:var(--t-text); }
.tv-modal-body   { padding:24px 28px; }
.tv-modal-footer { padding:18px 28px; border-top:1px solid var(--t-border); display:flex; gap:10px; justify-content:flex-end; }

/* form fields */
.tv-field { margin-bottom:18px; }
.tv-field label { display:block; font-size:.8rem; font-weight:600; color:var(--t-muted); margin-bottom:7px; text-transform:uppercase; letter-spacing:.06em; }
.tv-field input,.tv-field select,.tv-field textarea {
  width:100%; background:var(--t-surface2); border:1px solid var(--t-border);
  border-radius:var(--t-radius-sm); padding:10px 14px; color:var(--t-text);
  font-family:inherit; font-size:.875rem; outline:none;
  transition:border-color .2s,box-shadow .2s; resize:vertical;
}
.tv-field input:focus,.tv-field select:focus,.tv-field textarea:focus {
  border-color:var(--t-accent); box-shadow:0 0 0 3px rgba(139,92,246,.12);
}
.tv-field .err { color:#fca5a5; font-size:.78rem; margin-top:5px; }
.tv-grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:0 18px; }
.tv-grid-3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:0 18px; }
.tv-section-title { font-size:.7rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:var(--t-accent2); margin:4px 0 16px; display:flex; align-items:center; gap:8px; }
.tv-section-title::after { content:''; flex:1; height:1px; background:var(--t-border); }

/* delete modal */
.tv-delete-icon { width:64px; height:64px; border-radius:50%; background:rgba(239,68,68,.12); border:2px solid rgba(239,68,68,.25); display:flex; align-items:center; justify-content:center; font-size:1.5rem; color:#fca5a5; margin:0 auto 18px; }

/* toast */
#tv-toast-container { position:fixed; bottom:28px; right:28px; z-index:99999; display:flex; flex-direction:column; gap:10px; }
.tv-toast { display:flex; align-items:center; gap:12px; background:var(--t-surface); border:1px solid var(--t-border); border-radius:12px; padding:14px 18px; min-width:280px; box-shadow:0 8px 30px rgba(0,0,0,.5); transform:translateX(120%); transition:transform .35s cubic-bezier(.34,1.56,.64,1); font-family:'Plus Jakarta Sans',sans-serif; position:relative; overflow:hidden; }
.tv-toast.show { transform:translateX(0); }
.tv-toast-icon { width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:.95rem; }
.tv-toast-success .tv-toast-icon { background:rgba(34,197,94,.15);  color:var(--t-success); }
.tv-toast-danger  .tv-toast-icon { background:rgba(239,68,68,.15);   color:var(--t-danger); }
.tv-toast-title { font-size:.875rem; font-weight:700; color:var(--t-text); }
.tv-toast-msg   { font-size:.78rem;  color:var(--t-muted); margin-top:2px; }
.tv-toast-bar   { position:absolute; bottom:0; left:0; height:3px; border-radius:0 0 12px 12px; animation:tvBar 3.5s linear forwards; }
.tv-toast-success .tv-toast-bar { background:var(--t-success); }
.tv-toast-danger  .tv-toast-bar { background:var(--t-danger); }
@keyframes tvBar { from{width:100%} to{width:0%} }
.tv-modal::-webkit-scrollbar { width:5px; }
.tv-modal::-webkit-scrollbar-thumb { background:var(--t-border); border-radius:10px; }
.tv-empty { text-align:center; padding:60px 20px; color:var(--t-muted); }
.tv-empty i { font-size:2.5rem; margin-bottom:14px; opacity:.4; display:block; }
</style>

@if(session('success'))<div id="flash-success" data-msg="{{ session('success') }}"></div>@endif
@if(session('error'))<div id="flash-error"   data-msg="{{ session('error') }}"></div>@endif

<div class="tv-wrap">

  {{-- Header --}}
  <div class="tv-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
      <div>
        <div class="title"><i class="fas fa-hiking me-2"></i>Travelers</div>
        <div class="subtitle">Manage all registered travelers across bookings</div>
        <div class="d-flex gap-2 mt-3 flex-wrap">
          <span class="stat-pill"><span class="dot" style="background:var(--t-info)"></span>{{ $travelers->where('gender','male')->count() }} Male</span>
          <span class="stat-pill"><span class="dot" style="background:#f472b6"></span>{{ $travelers->where('gender','female')->count() }} Female</span>
          <span class="stat-pill"><span class="dot" style="background:var(--t-accent2)"></span>{{ $travelers->count() }} Total</span>
        </div>
      </div>
      <div style="position:relative;z-index:1;">
        <button class="tv-btn tv-btn-primary" onclick="openAddModal()">
          <i class="fas fa-plus"></i> Add Traveler
        </button>
      </div>
    </div>
  </div>

  {{-- Table Card --}}
  <div class="tv-card">
    <div class="tv-search-bar">
      <div class="d-flex gap-3 align-items-center flex-wrap">
        <div class="tv-search-wrap">
          <i class="fas fa-search si"></i>
          <input type="text" class="tv-search-input" id="tv-search" placeholder="Search by name, email, booking...">
        </div>
        <div class="tv-filter-tabs">
          <button class="tv-filter-tab active" data-filter="all">All</button>
          <button class="tv-filter-tab" data-filter="male">Male</button>
          <button class="tv-filter-tab" data-filter="female">Female</button>
          <button class="tv-filter-tab" data-filter="other">Other</button>
        </div>
      </div>
      <span style="font-size:.8rem;color:var(--t-muted);" id="tv-count"></span>
    </div>

    <div style="overflow-x:auto;">
      <table class="tv-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Traveler</th>
            <th>Booking</th>
            <th>Contact</th>
            <th>Gender</th>
            <th>Address</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="tv-tbody">
          @forelse($travelers as $i => $traveler)
          @php
            $gender = strtolower($traveler->gender ?? 'other');
            $genderIcon = match($gender) {
              'male'   => 'fas fa-mars',
              'female' => 'fas fa-venus',
              default  => 'fas fa-genderless',
            };
            $avatarClass = match($gender) {
              'male'   => 'tv-avatar-male',
              'female' => 'tv-avatar-female',
              default  => 'tv-avatar-other',
            };
            $badgeClass = match($gender) {
              'male'   => 'tv-badge-male',
              'female' => 'tv-badge-female',
              default  => 'tv-badge-other',
            };
          @endphp
          <tr
            data-search="{{ strtolower($traveler->name.' '.($traveler->email ?? '').' '.($traveler->booking->booking_code ?? '')) }}"
            data-gender="{{ $gender }}">
            <td style="color:var(--t-muted);font-size:.8rem;">{{ $i + 1 }}</td>
            <td>
              <div class="tv-person">
                <div class="tv-avatar {{ $avatarClass }}">
                  <i class="{{ $genderIcon }}"></i>
                </div>
                <div>
                  <div class="tv-person-name">{{ $traveler->name }}</div>
                  @if($traveler->age)
                    <div class="tv-person-age"><i class="fas fa-birthday-cake me-1" style="font-size:.65rem;"></i>Age {{ $traveler->age }}</div>
                  @endif
                </div>
              </div>
            </td>
            <td>
              @if($traveler->booking)
                <span class="tv-code">{{ $traveler->booking->booking_code }}</span>
              @else
                <span style="color:var(--t-muted);">—</span>
              @endif
            </td>
            <td>
              @if($traveler->phone)
                <div class="tv-mono"><i class="fas fa-phone me-1" style="font-size:.7rem;"></i>{{ $traveler->phone }}</div>
              @endif
              @if($traveler->email)
                <div style="font-size:.78rem;color:var(--t-muted);margin-top:3px;"><i class="fas fa-envelope me-1" style="font-size:.65rem;"></i>{{ $traveler->email }}</div>
              @endif
              @if(!$traveler->phone && !$traveler->email)
                <span style="color:var(--t-muted);">—</span>
              @endif
            </td>
            <td>
              <span class="tv-badge {{ $badgeClass }}">
                <i class="{{ $genderIcon }}" style="font-size:.65rem;"></i>
                {{ ucfirst($gender) }}
              </span>
            </td>
            <td style="font-size:.82rem;color:var(--t-muted);max-width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
              {{ $traveler->address ?? '—' }}
            </td>
            <td>
              <div class="tv-actions-cell">
                <button class="tv-btn tv-btn-icon tv-btn-icon-edit" title="Edit"
                  onclick="openEditModal({
                    id: '{{ $traveler->id }}',
                    booking_id: '{{ $traveler->booking_id }}',
                    name: {{ json_encode($traveler->name) }},
                    phone: {{ json_encode($traveler->phone ?? '') }},
                    email: {{ json_encode($traveler->email ?? '') }},
                    age: '{{ $traveler->age ?? '' }}',
                    gender: '{{ $traveler->gender ?? '' }}',
                    address: {{ json_encode($traveler->address ?? '') }}
                  })">
                  <i class="fas fa-pen"></i>
                </button>
                <button class="tv-btn tv-btn-danger-ghost" title="Delete"
                  onclick="openDeleteModal('{{ $traveler->id }}', {{ json_encode($traveler->name) }})">
                  <i class="fas fa-trash-alt"></i>
                </button>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="7">
            <div class="tv-empty">
              <i class="fas fa-hiking"></i>
              <p>No travelers found.</p>
            </div>
          </td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

</div>

{{-- ═══ ADD MODAL ═══ --}}
<div class="tv-modal-overlay" id="addModal">
  <div class="tv-modal">
    <div class="tv-modal-header">
      <div class="tv-modal-title"><i class="fas fa-user-plus me-2" style="color:var(--t-accent2)"></i>Add New Traveler</div>
      <button class="tv-modal-close" onclick="closeModal('addModal')"><i class="fas fa-times"></i></button>
    </div>
    <form method="POST" action="{{ route('admin.travelers.store') }}">
      @csrf
      <div class="tv-modal-body">

        <div class="tv-section-title"><i class="fas fa-ticket-alt"></i> Booking</div>
        <div class="tv-field">
          <label>Booking Code <span style="color:var(--t-danger)">*</span></label>
          <select name="booking_id">
            <option value="">— Select Booking —</option>
            @foreach($bookings as $booking)
              <option value="{{ $booking->id }}" {{ old('booking_id') == $booking->id ? 'selected' : '' }}>
                {{ $booking->booking_code }}
                @if($booking->tour) — {{ Str::limit($booking->tour->title, 30) }} @endif
              </option>
            @endforeach
          </select>
          @error('booking_id')<div class="err">{{ $message }}</div>@enderror
        </div>

        <div class="tv-section-title"><i class="fas fa-user"></i> Personal Info</div>
        <div class="tv-grid-2">
          <div class="tv-field">
            <label>Full Name <span style="color:var(--t-danger)">*</span></label>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="John Doe">
            @error('name')<div class="err">{{ $message }}</div>@enderror
          </div>
          <div class="tv-field">
            <label>Age</label>
            <input type="number" name="age" value="{{ old('age') }}" placeholder="25" min="1" max="120">
          </div>
        </div>

        <div class="tv-grid-2">
          <div class="tv-field">
            <label>Phone</label>
            <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+880 1xxx-xxxxxx">
          </div>
          <div class="tv-field">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="traveler@example.com">
          </div>
        </div>

        <div class="tv-field">
          <label>Gender</label>
          <select name="gender">
            <option value="">— Select Gender —</option>
            <option value="male"   {{ old('gender') == 'male'   ? 'selected' : '' }}>Male</option>
            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
            <option value="other"  {{ old('gender') == 'other'  ? 'selected' : '' }}>Other</option>
          </select>
        </div>

        <div class="tv-field">
          <label>Address</label>
          <textarea name="address" rows="2" placeholder="Full address...">{{ old('address') }}</textarea>
        </div>

      </div>
      <div class="tv-modal-footer">
        <button type="button" class="tv-btn tv-btn-outline" onclick="closeModal('addModal')">Cancel</button>
        <button type="submit" class="tv-btn tv-btn-primary"><i class="fas fa-save"></i> Save Traveler</button>
      </div>
    </form>
  </div>
</div>

{{-- ═══ EDIT MODAL ═══ --}}
<div class="tv-modal-overlay" id="editModal">
  <div class="tv-modal">
    <div class="tv-modal-header">
      <div class="tv-modal-title"><i class="fas fa-user-edit me-2" style="color:var(--t-warning)"></i>Edit Traveler</div>
      <button class="tv-modal-close" onclick="closeModal('editModal')"><i class="fas fa-times"></i></button>
    </div>
    <form method="POST" id="editForm">
      @csrf
      @method('PUT')
      <input type="hidden" name="id" id="edit_id">
      <div class="tv-modal-body">

        <div class="tv-section-title"><i class="fas fa-ticket-alt"></i> Booking</div>
        <div class="tv-field">
          <label>Booking Code <span style="color:var(--t-danger)">*</span></label>
          <select name="booking_id" id="edit_booking_id">
            <option value="">— Select Booking —</option>
            @foreach($bookings as $booking)
              <option value="{{ $booking->id }}">
                {{ $booking->booking_code }}
                @if($booking->tour) — {{ Str::limit($booking->tour->title, 30) }} @endif
              </option>
            @endforeach
          </select>
        </div>

        <div class="tv-section-title"><i class="fas fa-user"></i> Personal Info</div>
        <div class="tv-grid-2">
          <div class="tv-field">
            <label>Full Name <span style="color:var(--t-danger)">*</span></label>
            <input type="text" name="name" id="edit_name" placeholder="John Doe">
          </div>
          <div class="tv-field">
            <label>Age</label>
            <input type="number" name="age" id="edit_age" placeholder="25" min="1" max="120">
          </div>
        </div>

        <div class="tv-grid-2">
          <div class="tv-field">
            <label>Phone</label>
            <input type="text" name="phone" id="edit_phone" placeholder="+880 1xxx-xxxxxx">
          </div>
          <div class="tv-field">
            <label>Email</label>
            <input type="email" name="email" id="edit_email" placeholder="traveler@example.com">
          </div>
        </div>

        <div class="tv-field">
          <label>Gender</label>
          <select name="gender" id="edit_gender">
            <option value="">— Select Gender —</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="other">Other</option>
          </select>
        </div>

        <div class="tv-field">
          <label>Address</label>
          <textarea name="address" id="edit_address" rows="2" placeholder="Full address..."></textarea>
        </div>

      </div>
      <div class="tv-modal-footer">
        <button type="button" class="tv-btn tv-btn-outline" onclick="closeModal('editModal')">Cancel</button>
        <button type="submit" class="tv-btn tv-btn-primary" style="background:var(--t-warning);color:#1a1d27;">
          <i class="fas fa-save"></i> Update Traveler
        </button>
      </div>
    </form>
  </div>
</div>

{{-- ═══ DELETE MODAL ═══ --}}
<div class="tv-modal-overlay" id="deleteModal">
  <div class="tv-modal" style="width:min(420px,96vw);">
    <div class="tv-modal-body" style="text-align:center;padding:40px 28px 28px;">
      <div class="tv-delete-icon"><i class="fas fa-user-minus"></i></div>
      <h5 style="font-weight:700;margin-bottom:8px;">Delete Traveler?</h5>
      <p style="color:var(--t-muted);font-size:.88rem;margin-bottom:4px;">
        You are about to delete <strong id="delete-name" style="color:var(--t-text)"></strong>.
      </p>
      <p style="color:var(--t-muted);font-size:.8rem;">This action <strong style="color:var(--t-danger)">cannot be undone</strong>.</p>
    </div>
    <div class="tv-modal-footer" style="justify-content:center;gap:14px;">
      <button class="tv-btn tv-btn-outline" onclick="closeModal('deleteModal')"><i class="fas fa-times"></i> Cancel</button>
      <form id="deleteForm" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="tv-btn tv-btn-primary" style="background:var(--t-danger);">
          <i class="fas fa-trash-alt"></i> Yes, Delete
        </button>
      </form>
    </div>
  </div>
</div>

<div id="tv-toast-container"></div>

<script>
// ── Modal helpers ──────────────────────────────────────
function openModal(id)  { document.getElementById(id).classList.add('open'); document.body.style.overflow='hidden'; }
function closeModal(id) { document.getElementById(id).classList.remove('open'); document.body.style.overflow=''; }
document.querySelectorAll('.tv-modal-overlay').forEach(el => {
  el.addEventListener('click', e => { if(e.target===el) closeModal(el.id); });
});
document.addEventListener('keydown', e => {
  if(e.key==='Escape') document.querySelectorAll('.tv-modal-overlay.open').forEach(el=>closeModal(el.id));
});

// ── Add ────────────────────────────────────────────────
function openAddModal() { openModal('addModal'); }

@if($errors->any() && old('_method') !== 'PUT')
  openAddModal();
@endif

// ── Edit ───────────────────────────────────────────────
function openEditModal(d) {
  document.getElementById('edit_id').value         = d.id;
  document.getElementById('edit_booking_id').value = d.booking_id;
  document.getElementById('edit_name').value       = d.name;
  document.getElementById('edit_age').value        = d.age;
  document.getElementById('edit_phone').value      = d.phone;
  document.getElementById('edit_email').value      = d.email;
  document.getElementById('edit_gender').value     = d.gender;
  document.getElementById('edit_address').value    = d.address;
  document.getElementById('editForm').action       = '/admin/travelers/' + d.id;
  openModal('editModal');
}

@if($errors->any() && old('_method') === 'PUT')
  // reopen edit modal on validation error - not easy without session, skip
@endif

// ── Delete ─────────────────────────────────────────────
function openDeleteModal(id, name) {
  document.getElementById('delete-name').textContent = name;
  document.getElementById('deleteForm').action = '/admin/travelers/' + id;
  openModal('deleteModal');
}

// ── Search + Gender filter ─────────────────────────────
(function(){
  var inp   = document.getElementById('tv-search');
  var rows  = Array.from(document.querySelectorAll('#tv-tbody tr[data-search]'));
  var cnt   = document.getElementById('tv-count');
  var tabs  = document.querySelectorAll('.tv-filter-tab');
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
    var v = 0;
    rows.forEach(r => {
      var matchQ = !q || r.dataset.search.includes(q);
      var matchF = active==='all' || r.dataset.gender===active;
      r.style.display = (matchQ && matchF) ? '' : 'none';
      if(matchQ && matchF) v++;
    });
    cnt.textContent = v + ' of ' + rows.length + ' travelers';
  }
  update();
})();

// ── Toast ──────────────────────────────────────────────
function showToast(type, title, msg) {
  var icons = {success:'fas fa-check-circle', danger:'fas fa-exclamation-circle'};
  var c = document.getElementById('tv-toast-container');
  var t = document.createElement('div');
  t.className = 'tv-toast tv-toast-' + type;
  t.innerHTML = `<div class="tv-toast-icon"><i class="${icons[type]}"></i></div>
    <div><div class="tv-toast-title">${title}</div><div class="tv-toast-msg">${msg}</div></div>
    <span class="tv-toast-bar"></span>`;
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