@extends('layouts.vendor')

@section('page')

<style>

@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');

:root {
    --rs-surface: #1a1d27; --rs-surface2: #222636; --rs-surface3: #2a2f42; --rs-border: rgba(255,255,255,.07);
    --rs-text: #e2e8f0; --rs-muted: #64748b;
    --rs-indigo: #6366f1; --rs-purple: #8b5cf6;
    --rs-success: #22c55e; --rs-warning: #f59e0b; --rs-danger: #ef4444; --rs-cyan: #0ea5e9;
    --rs-radius: 14px; --rs-shadow: 0 8px 32px rgba(0,0,0,.45);
}

.rs-wrap { font-family: 'Plus Jakarta Sans', sans-serif; color: var(--rs-text); }

.rs-header {
    background: linear-gradient(135deg, #0c1a2e 0%, #0e0c2e 55%, #0c1a2e 100%);
    border-radius: var(--rs-radius); padding: 28px 30px; margin-bottom: 22px;
    box-shadow: var(--rs-shadow); position: relative; overflow: hidden;
}
.rs-header::before {
    content: ''; position: absolute; inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%236366f1' fill-opacity='0.05'%3E%3Cpath d='M30 5L55 20v20L30 55 5 40V20Z'/%3E%3C/g%3E%3C/svg%3E");
}
.rs-header-content { position: relative; z-index: 1; display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap; }
.rs-title { font-size: 1.5rem; font-weight: 700; background: linear-gradient(90deg, #fff, #a5b4fc); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
.rs-subtitle { color: rgba(255,255,255,.45); font-size: .82rem; margin-top: 5px; }

.rs-btn-primary {
    display: inline-flex; align-items: center; gap: 8px; border: none;
    background: linear-gradient(135deg, var(--rs-indigo), var(--rs-purple)); color: #fff;
    border-radius: 10px; padding: 11px 18px; font-size: .82rem; font-weight: 600;
    box-shadow: 0 8px 22px rgba(99,102,241,.28); transition: transform .2s ease, box-shadow .2s ease; white-space: nowrap;
}
.rs-btn-primary:hover { transform: translateY(-1px); color: #fff; box-shadow: 0 10px 26px rgba(99,102,241,.4); }

.rs-wrap .alert { background: var(--rs-surface); border: 1px solid var(--rs-border); color: var(--rs-text); border-radius: 12px; font-size: .84rem; box-shadow: var(--rs-shadow); }
.rs-wrap .alert-success { border-left: 3px solid var(--rs-success); }
.rs-wrap .alert-danger { border-left: 3px solid var(--rs-danger); }
.rs-wrap .btn-close { filter: invert(1) grayscale(1) opacity(.6); }
.rs-wrap .alert ul { padding-left: 18px; margin: 6px 0 0; }

.rs-stats { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 14px; margin-bottom: 22px; }
.rs-stat { background: var(--rs-surface); border: 1px solid var(--rs-border); border-radius: var(--rs-radius); padding: 18px 20px; box-shadow: var(--rs-shadow); display: flex; align-items: center; gap: 14px; }
.rs-stat-icon { width: 44px; height: 44px; border-radius: 11px; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; }
.rs-stat-total .rs-stat-icon    { background: rgba(99,102,241,.12); color: #a5b4fc; }
.rs-stat-approved .rs-stat-icon { background: rgba(34,197,94,.12);  color: #86efac; }
.rs-stat-pending .rs-stat-icon  { background: rgba(245,158,11,.12); color: #fcd34d; }
.rs-stat-rejected .rs-stat-icon { background: rgba(239,68,68,.12);  color: #fca5a5; }
.rs-stat-label { color: var(--rs-muted); font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .06em; }
.rs-stat-value { color: var(--rs-text); font-size: 1.15rem; font-family: 'JetBrains Mono', monospace; font-weight: 700; margin-top: 3px; }

.rs-card { background: var(--rs-surface); border: 1px solid var(--rs-border); border-radius: var(--rs-radius); box-shadow: var(--rs-shadow); overflow: hidden; }
.rs-toolbar { padding: 17px 20px; border-bottom: 1px solid var(--rs-border); }
.rs-toolbar h2 { font-size: .95rem; font-weight: 700; margin: 0 0 3px; color: var(--rs-text); }
.rs-toolbar span { font-size: .74rem; color: var(--rs-muted); }

.rs-table { width: 100%; min-width: 1050px; border-collapse: collapse; }
.rs-table thead tr { background: var(--rs-surface2); border-bottom: 1px solid var(--rs-border); }
.rs-table th { padding: 13px 17px; text-align: left; color: var(--rs-muted); font-size: .66rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; white-space: nowrap; }
.rs-table td { padding: 15px 17px; border-bottom: 1px solid var(--rs-border); font-size: .79rem; vertical-align: middle; color: var(--rs-text); }
.rs-table tbody tr:hover { background: rgba(255,255,255,.02); }
.rs-table tbody tr:last-child td { border-bottom: none; }

.rs-vehicle-thumb { width: 58px; height: 46px; border-radius: 9px; background: var(--rs-surface2); border: 1px solid var(--rs-border); display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0; }
.rs-vehicle-thumb img { width: 100%; height: 100%; object-fit: cover; }
.rs-vehicle-name { font-weight: 600; font-size: .8rem; }
.rs-vehicle-sub { color: var(--rs-muted); font-size: .68rem; margin-top: 2px; }

.rs-type-pill { background: var(--rs-surface2); border: 1px solid var(--rs-border); color: #cbd5e1; padding: 4px 9px; border-radius: 7px; font-size: .7rem; font-weight: 600; }

.rs-badge { display: inline-flex; align-items: center; gap: 5px; padding: 5px 9px; border-radius: 7px; font-size: .68rem; font-weight: 700; }
.rs-badge-success { background: rgba(34,197,94,.1); color: #86efac; border: 1px solid rgba(34,197,94,.18); }
.rs-badge-warning { background: rgba(245,158,11,.1); color: #fcd34d; border: 1px solid rgba(245,158,11,.18); }
.rs-badge-danger  { background: rgba(239,68,68,.1);  color: #fca5a5; border: 1px solid rgba(239,68,68,.18); }
.rs-badge-muted   { background: rgba(255,255,255,.04); color: var(--rs-muted); border: 1px solid var(--rs-border); }

.rs-action-btn { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; border: 1px solid var(--rs-border); background: rgba(255,255,255,.02); color: var(--rs-muted); transition: all .2s; }
.rs-action-btn:hover, .rs-action-btn:focus { background: rgba(99,102,241,.14); color: #c7d2fe; border-color: rgba(99,102,241,.3); box-shadow: none; }
.rs-wrap .dropdown-menu { background: var(--rs-surface2); border: 1px solid var(--rs-border); border-radius: 10px; padding: 6px; box-shadow: 0 20px 45px rgba(0,0,0,.5); min-width: 190px; }
.rs-wrap .dropdown-item { border-radius: 7px; font-size: .8rem; padding: 8px 10px; color: #cbd5e1; }
.rs-wrap .dropdown-item:hover { background: rgba(255,255,255,.05); color: #fff; }
.rs-wrap .dropdown-item i { width: 18px; color: #93a2b8; }
.rs-wrap .dropdown-item.text-success { color: #86efac !important; }
.rs-wrap .dropdown-item.text-success i { color: #86efac !important; }
.rs-wrap .dropdown-item.text-danger { color: #fca5a5 !important; }
.rs-wrap .dropdown-item.text-danger i { color: #fca5a5 !important; }
.rs-wrap .dropdown-divider { border-color: var(--rs-border); }

.rs-empty { text-align: center; padding: 70px 20px; color: var(--rs-muted); }
.rs-empty i { font-size: 2.4rem; opacity: .25; margin-bottom: 12px; display: block; }
.rs-empty-title { color: var(--rs-text); font-size: .95rem; font-weight: 700; margin-bottom: 6px; }
.rs-empty-text { font-size: .78rem; margin-bottom: 20px; }

.rs-pagination { padding: 14px 18px; border-top: 1px solid var(--rs-border); }
.rs-pagination .pagination { margin: 0; }
.rs-pagination .page-link { background: var(--rs-surface2); border: 1px solid var(--rs-border); color: var(--rs-muted); font-size: .75rem; }
.rs-pagination .page-link:hover { background: rgba(255,255,255,.07); color: var(--rs-text); }
.rs-pagination .page-item.active .page-link { background: var(--rs-indigo); border-color: var(--rs-indigo); color: #fff; }


/* ===== MODAL ===== */

.rs-wrap .modal-content { background: var(--rs-surface); border: 1px solid var(--rs-border); border-radius: 16px; color: var(--rs-text); box-shadow: 0 24px 60px rgba(0,0,0,.55); }
.rs-wrap .modal-header { border-bottom: 1px solid var(--rs-border); padding: 20px 24px; }
.rs-wrap .modal-header h5 { font-size: 1.05rem; font-weight: 700; }
.rs-wrap .modal-header small { color: var(--rs-muted); font-size: .78rem; }
.rs-wrap .modal-body { padding: 24px; }
.rs-wrap .modal-footer { border-top: 1px solid var(--rs-border); padding: 16px 24px; }

.rs-field { margin-bottom: 16px; }
.rs-label { display: block; font-size: .78rem; font-weight: 600; color: #cbd5e1; margin-bottom: 7px; }
.rs-label .req { color: var(--rs-danger); }

.rs-input, .rs-select, .rs-textarea {
    width: 100%; background: var(--rs-surface2); border: 1px solid var(--rs-border); color: var(--rs-text);
    border-radius: 9px; padding: 10px 13px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: .84rem;
    outline: none; transition: border-color .15s, box-shadow .15s;
}
.rs-textarea { resize: vertical; min-height: 80px; }
.rs-input::placeholder, .rs-textarea::placeholder { color: var(--rs-muted); }
.rs-input:focus, .rs-select:focus, .rs-textarea:focus { border-color: rgba(99,102,241,.5); box-shadow: 0 0 0 3px rgba(99,102,241,.1); }
.rs-help { color: var(--rs-muted); font-size: .72rem; margin-top: 5px; }

.rs-file {
    display: inline-flex; align-items: center; gap: 8px; width: 100%; justify-content: center;
    background: var(--rs-surface2); border: 1.5px dashed var(--rs-border); color: #cbd5e1;
    border-radius: 9px; padding: 10px 13px; font-size: .8rem; cursor: pointer; position: relative; overflow: hidden;
}
.rs-file input[type="file"] { position: absolute; inset: 0; opacity: 0; cursor: pointer; }

.rs-toggle-inline { display: flex; align-items: center; gap: 10px; background: var(--rs-surface2); border: 1px solid var(--rs-border); border-radius: 9px; padding: 10px 13px; }
.rs-switch { position: relative; width: 40px; height: 22px; flex-shrink: 0; }
.rs-switch input { opacity: 0; width: 0; height: 0; }
.rs-switch-slider { position: absolute; inset: 0; cursor: pointer; background: var(--rs-surface3); border: 1px solid var(--rs-border); border-radius: 999px; transition: .2s; }
.rs-switch-slider::before { content: ''; position: absolute; width: 16px; height: 16px; left: 2px; top: 2px; background: #fff; border-radius: 50%; transition: .2s; }
.rs-switch input:checked + .rs-switch-slider { background: var(--rs-success); border-color: var(--rs-success); }
.rs-switch input:checked + .rs-switch-slider::before { transform: translateX(18px); }
.rs-toggle-inline span { font-size: .82rem; color: #cbd5e1; }

.rs-btn-ghost {
    display: inline-flex; align-items: center; gap: 6px; border: 1px solid rgba(255,255,255,.12);
    background: rgba(255,255,255,.04); color: #e2e8f0; border-radius: 9px; padding: 9px 16px;
    font-size: .82rem; font-weight: 600;
}
.rs-btn-ghost:hover { background: rgba(255,255,255,.09); color: #fff; }

@media (max-width: 900px) { .rs-stats { grid-template-columns: 1fr 1fr; } }
@media (max-width: 600px) { .rs-stats { grid-template-columns: 1fr; } .rs-btn-primary { width: 100%; justify-content: center; } }

</style>


<div class="rs-wrap">


    {{-- HEADER --}}

    <div class="rs-header">

        <div class="rs-header-content">

            <div>
                <div class="rs-title"><i class="bi bi-car-front me-2"></i> Transport Vehicles</div>
                <div class="rs-subtitle">Manage your vehicles and transport services.</div>
            </div>

            <button type="button" class="rs-btn-primary" data-bs-toggle="modal" data-bs-target="#addVehicleModal">
                <i class="bi bi-plus-lg"></i> Add Vehicle
            </button>

        </div>

    </div>


    {{-- MESSAGES --}}

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4">
            <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4">
            <i class="bi bi-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4">
            <div class="fw-semibold mb-1">Please fix the following errors:</div>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif


    {{-- STATS --}}

    <div class="rs-stats">

        <div class="rs-stat rs-stat-total">
            <div class="rs-stat-icon"><i class="bi bi-car-front"></i></div>
            <div>
                <div class="rs-stat-label">Total Vehicles</div>
                <div class="rs-stat-value">{{ $vehicles->total() }}</div>
            </div>
        </div>

        <div class="rs-stat rs-stat-approved">
            <div class="rs-stat-icon"><i class="bi bi-check-circle"></i></div>
            <div>
                <div class="rs-stat-label">Approved Vehicles</div>
                <div class="rs-stat-value">{{ $vehicles->where('status', 'approved')->count() }}</div>
            </div>
        </div>

        <div class="rs-stat rs-stat-pending">
            <div class="rs-stat-icon"><i class="bi bi-clock"></i></div>
            <div>
                <div class="rs-stat-label">Pending Approval</div>
                <div class="rs-stat-value">{{ $vehicles->where('status', 'pending')->count() }}</div>
            </div>
        </div>

        <div class="rs-stat rs-stat-rejected">
            <div class="rs-stat-icon"><i class="bi bi-x-circle"></i></div>
            <div>
                <div class="rs-stat-label">Rejected Vehicles</div>
                <div class="rs-stat-value">{{ $vehicles->where('status', 'rejected')->count() }}</div>
            </div>
        </div>

    </div>


    {{-- TABLE --}}

    <div class="rs-card">

        <div class="rs-toolbar">
            <h2>My Vehicles</h2>
            <span>Vehicles added by your business.</span>
        </div>

        @if($vehicles->count())

            <div class="table-responsive">

                <table class="rs-table">

                    <thead>
                        <tr>
                            <th class="ps-4">Vehicle</th>
                            <th>Type</th>
                            <th>Registration</th>
                            <th>Capacity</th>
                            <th>Price / Day</th>
                            <th>Driver</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                    @foreach($vehicles as $vehicle)

                        <tr>

                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rs-vehicle-thumb">
                                        @if($vehicle->featured_image)
                                            <img src="{{ asset('storage/' . $vehicle->featured_image) }}" alt="{{ $vehicle->name }}">
                                        @else
                                            <i class="bi bi-car-front" style="color:var(--rs-muted);"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="rs-vehicle-name">{{ $vehicle->name }}</div>
                                        @if($vehicle->brand || $vehicle->model)
                                            <div class="rs-vehicle-sub">{{ $vehicle->brand }}{{ $vehicle->brand && $vehicle->model ? ' - ' : '' }}{{ $vehicle->model }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td><span class="rs-type-pill">{{ ucfirst($vehicle->vehicle_type) }}</span></td>

                            <td>
                                @if($vehicle->registration_number)
                                    <span class="fw-medium">{{ $vehicle->registration_number }}</span>
                                @else
                                    <span style="color:var(--rs-muted);">N/A</span>
                                @endif
                            </td>

                            <td><i class="bi bi-people me-1" style="color:var(--rs-muted);"></i>{{ $vehicle->passenger_capacity }} <small style="color:var(--rs-muted);">seats</small></td>

                            <td>
                                <div class="fw-bold">৳{{ number_format($vehicle->price_per_day, 2) }}</div>
                                <small style="color:var(--rs-muted);">per day</small>
                            </td>

                            <td>
                                @if($vehicle->with_driver)
                                    <span class="rs-badge rs-badge-success"><i class="bi bi-person-check"></i> Included</span>
                                @else
                                    <span class="rs-badge rs-badge-muted">Without Driver</span>
                                @endif
                            </td>

                            <td>
                                @switch($vehicle->status)
                                    @case('approved')
                                        <span class="rs-badge rs-badge-success"><i class="bi bi-check-circle"></i> Approved</span>
                                        @break
                                    @case('pending')
                                        <span class="rs-badge rs-badge-warning"><i class="bi bi-clock"></i> Pending</span>
                                        @break
                                    @case('rejected')
                                        <span class="rs-badge rs-badge-danger"><i class="bi bi-x-circle"></i> Rejected</span>
                                        @break
                                    @case('inactive')
                                        <span class="rs-badge rs-badge-muted"><i class="bi bi-pause-circle"></i> Inactive</span>
                                        @break
                                    @default
                                        <span class="rs-badge rs-badge-muted">{{ ucfirst($vehicle->status) }}</span>
                                @endswitch
                            </td>

                            <td class="text-end pe-4">

                                <div class="dropdown">

                                    <button class="rs-action-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-end">

                                        <li>
                                            <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editVehicleModal{{ $vehicle->id }}">
                                                <i class="bi bi-pencil me-2"></i> Edit Vehicle
                                            </button>
                                        </li>

                                        @if($vehicle->status === 'approved')
                                            <li>
                                                <form method="POST" action="{{ route('vendor.vehicles.toggle-status', $vehicle) }}">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="bi bi-pause-circle me-2"></i> Set Inactive
                                                    </button>
                                                </form>
                                            </li>
                                        @elseif($vehicle->status === 'inactive')
                                            <li>
                                                <form method="POST" action="{{ route('vendor.vehicles.toggle-status', $vehicle) }}">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="bi bi-check-circle me-2"></i> Activate Vehicle
                                                    </button>
                                                </form>
                                            </li>
                                        @endif

                                        <li><hr class="dropdown-divider"></li>

                                        <li>
                                            <form method="POST" action="{{ route('vendor.vehicles.destroy', $vehicle) }}"
                                                  onsubmit="return confirm('Are you sure you want to delete this vehicle?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="bi bi-trash me-2"></i> Delete Vehicle
                                                </button>
                                            </form>
                                        </li>

                                    </ul>

                                </div>

                            </td>

                        </tr>


                        {{-- EDIT VEHICLE MODAL --}}

                        <div class="modal fade" id="editVehicleModal{{ $vehicle->id }}" tabindex="-1" aria-hidden="true">

                            <div class="modal-dialog modal-xl modal-dialog-centered">

                                <div class="modal-content">

                                    <form method="POST" action="{{ route('vendor.vehicles.update', $vehicle) }}" enctype="multipart/form-data">

                                        @csrf @method('PUT')

                                        <div class="modal-header">
                                            <div>
                                                <h5>Edit Vehicle</h5>
                                                <small>Update your vehicle information.</small>
                                            </div>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">

                                            <div class="row g-3">

                                                <div class="col-md-6 rs-field">
                                                    <label class="rs-label">Vehicle Name <span class="req">*</span></label>
                                                    <input type="text" name="name" class="rs-input" value="{{ $vehicle->name }}" placeholder="Toyota Axio" required>
                                                </div>

                                                <div class="col-md-6 rs-field">
                                                    <label class="rs-label">Vehicle Type <span class="req">*</span></label>
                                                    <select name="vehicle_type" class="rs-select" required>
                                                        <option value="">Select vehicle type</option>
                                                        @foreach(['car'=>'Car','microbus'=>'Microbus','bus'=>'Bus','motorcycle'=>'Motorcycle','bike'=>'Bike','cng'=>'CNG / Auto','pickup'=>'Pickup','hiace'=>'Hiace','other'=>'Other'] as $value => $label)
                                                            <option value="{{ $value }}" @selected($vehicle->vehicle_type === $value)>{{ $label }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-6 rs-field">
                                                    <label class="rs-label">Brand</label>
                                                    <input type="text" name="brand" class="rs-input" value="{{ $vehicle->brand }}" placeholder="Toyota, Honda, Yamaha...">
                                                </div>

                                                <div class="col-md-6 rs-field">
                                                    <label class="rs-label">Model</label>
                                                    <input type="text" name="model" class="rs-input" value="{{ $vehicle->model }}" placeholder="Axio, Noah, Civic...">
                                                </div>

                                                <div class="col-md-6 rs-field">
                                                    <label class="rs-label">Registration Number</label>
                                                    <input type="text" name="registration_number" class="rs-input" value="{{ $vehicle->registration_number }}" placeholder="Dhaka Metro-GA...">
                                                </div>

                                                <div class="col-md-6 rs-field">
                                                    <label class="rs-label">Passenger Capacity <span class="req">*</span></label>
                                                    <input type="number" name="passenger_capacity" class="rs-input" min="1" value="{{ $vehicle->passenger_capacity }}" required>
                                                </div>

                                                <div class="col-md-6 rs-field">
                                                    <label class="rs-label">Price Per Day (৳) <span class="req">*</span></label>
                                                    <input type="number" name="price_per_day" class="rs-input" min="0" step="0.01" value="{{ $vehicle->price_per_day }}" required>
                                                </div>

                                                <div class="col-md-6 rs-field">
                                                    <label class="rs-label">Price Per Hour (৳)</label>
                                                    <input type="number" name="price_per_hour" class="rs-input" min="0" step="0.01" value="{{ $vehicle->price_per_hour }}" placeholder="Optional">
                                                </div>

                                                <div class="col-md-4 rs-field">
                                                    <label class="rs-label">Division</label>
                                                    <input type="text" name="division" class="rs-input" value="{{ $vehicle->division }}" placeholder="Dhaka">
                                                </div>

                                                <div class="col-md-4 rs-field">
                                                    <label class="rs-label">District</label>
                                                    <input type="text" name="district" class="rs-input" value="{{ $vehicle->district }}" placeholder="Dhaka">
                                                </div>

                                                <div class="col-md-4 rs-field">
                                                    <label class="rs-label">Area</label>
                                                    <input type="text" name="area" class="rs-input" value="{{ $vehicle->area }}" placeholder="Gulshan">
                                                </div>

                                                <div class="col-12 rs-field">
                                                    <label class="rs-label">Address</label>
                                                    <textarea name="address" rows="2" class="rs-textarea" placeholder="Vehicle service address...">{{ $vehicle->address }}</textarea>
                                                </div>

                                                <div class="col-md-4 rs-field">
                                                    <label class="rs-label">Driver Service</label>
                                                    <div class="rs-toggle-inline">
                                                        <label class="rs-switch">
                                                            <input type="checkbox" name="with_driver" value="1" @checked($vehicle->with_driver)>
                                                            <span class="rs-switch-slider"></span>
                                                        </label>
                                                        <span>With Driver</span>
                                                    </div>
                                                </div>

                                                <div class="col-md-4 rs-field">
                                                    <label class="rs-label">Featured Vehicle</label>
                                                    <div class="rs-toggle-inline">
                                                        <label class="rs-switch">
                                                            <input type="checkbox" name="is_featured" value="1" @checked($vehicle->is_featured)>
                                                            <span class="rs-switch-slider"></span>
                                                        </label>
                                                        <span>Mark as Featured</span>
                                                    </div>
                                                </div>

                                                <div class="col-md-4 rs-field">
                                                    <label class="rs-label">Vehicle Image</label>
                                                    <label class="rs-file">
                                                        <i class="bi bi-upload"></i> Choose file
                                                        <input type="file" name="featured_image" accept="image/jpeg,image/png,image/webp">
                                                    </label>
                                                    @if($vehicle->featured_image)
                                                        <div class="rs-help">Existing image available. Upload only to replace it.</div>
                                                    @else
                                                        <div class="rs-help">JPG, PNG or WEBP. Maximum 2MB.</div>
                                                    @endif
                                                </div>

                                                <div class="col-12 rs-field">
                                                    <label class="rs-label">Description</label>
                                                    <textarea name="description" rows="4" class="rs-textarea" placeholder="Describe your vehicle, features, condition, service details...">{{ $vehicle->description }}</textarea>
                                                </div>

                                            </div>

                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="rs-btn-ghost" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="rs-btn-primary"><i class="bi bi-check-lg"></i> Update Vehicle</button>
                                        </div>

                                    </form>

                                </div>

                            </div>

                        </div>

                    @endforeach

                    </tbody>

                </table>

            </div>

            <div class="rs-pagination">
                {{ $vehicles->links() }}
            </div>

        @else

            <div class="rs-empty">
                <i class="bi bi-car-front"></i>
                <div class="rs-empty-title">No vehicles added yet</div>
                <div class="rs-empty-text">Add your first vehicle to start offering transport services.</div>
                <button type="button" class="rs-btn-primary" data-bs-toggle="modal" data-bs-target="#addVehicleModal">
                    <i class="bi bi-plus-lg"></i> Add Your First Vehicle
                </button>
            </div>

        @endif

    </div>


    {{-- ADD VEHICLE MODAL --}}

    <div class="modal fade" id="addVehicleModal" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-xl modal-dialog-centered">

            <div class="modal-content">

                <form method="POST" action="{{ route('vendor.vehicles.store') }}" enctype="multipart/form-data">

                    @csrf

                    <div class="modal-header">
                        <div>
                            <h5>Add New Vehicle</h5>
                            <small>Add a vehicle to your transport service.</small>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="row g-3">

                            <div class="col-md-6 rs-field">
                                <label class="rs-label">Vehicle Name <span class="req">*</span></label>
                                <input type="text" name="name" class="rs-input" value="{{ old('name') }}" placeholder="Toyota Axio" required>
                            </div>

                            <div class="col-md-6 rs-field">
                                <label class="rs-label">Vehicle Type <span class="req">*</span></label>
                                <select name="vehicle_type" class="rs-select" required>
                                    <option value="">Select vehicle type</option>
                                    @foreach(['car'=>'Car','microbus'=>'Microbus','bus'=>'Bus','motorcycle'=>'Motorcycle','bike'=>'Bike','cng'=>'CNG / Auto','pickup'=>'Pickup','hiace'=>'Hiace','other'=>'Other'] as $value => $label)
                                        <option value="{{ $value }}" @selected(old('vehicle_type') === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 rs-field">
                                <label class="rs-label">Brand</label>
                                <input type="text" name="brand" class="rs-input" value="{{ old('brand') }}" placeholder="Toyota, Honda, Yamaha...">
                            </div>

                            <div class="col-md-6 rs-field">
                                <label class="rs-label">Model</label>
                                <input type="text" name="model" class="rs-input" value="{{ old('model') }}" placeholder="Axio, Noah, Civic...">
                            </div>

                            <div class="col-md-6 rs-field">
                                <label class="rs-label">Registration Number</label>
                                <input type="text" name="registration_number" class="rs-input" value="{{ old('registration_number') }}" placeholder="Dhaka Metro-GA...">
                            </div>

                            <div class="col-md-6 rs-field">
                                <label class="rs-label">Passenger Capacity <span class="req">*</span></label>
                                <input type="number" name="passenger_capacity" class="rs-input" min="1" value="{{ old('passenger_capacity', 1) }}" required>
                            </div>

                            <div class="col-md-6 rs-field">
                                <label class="rs-label">Price Per Day (৳) <span class="req">*</span></label>
                                <input type="number" name="price_per_day" class="rs-input" min="0" step="0.01" value="{{ old('price_per_day') }}" placeholder="2500" required>
                            </div>

                            <div class="col-md-6 rs-field">
                                <label class="rs-label">Price Per Hour (৳)</label>
                                <input type="number" name="price_per_hour" class="rs-input" min="0" step="0.01" value="{{ old('price_per_hour') }}" placeholder="Optional">
                            </div>

                            <div class="col-md-4 rs-field">
                                <label class="rs-label">Division</label>
                                <input type="text" name="division" class="rs-input" value="{{ old('division') }}" placeholder="Dhaka">
                            </div>

                            <div class="col-md-4 rs-field">
                                <label class="rs-label">District</label>
                                <input type="text" name="district" class="rs-input" value="{{ old('district') }}" placeholder="Dhaka">
                            </div>

                            <div class="col-md-4 rs-field">
                                <label class="rs-label">Area</label>
                                <input type="text" name="area" class="rs-input" value="{{ old('area') }}" placeholder="Gulshan">
                            </div>

                            <div class="col-12 rs-field">
                                <label class="rs-label">Address</label>
                                <textarea name="address" rows="2" class="rs-textarea" placeholder="Vehicle service address...">{{ old('address') }}</textarea>
                            </div>

                            <div class="col-md-4 rs-field">
                                <label class="rs-label">Driver Service</label>
                                <div class="rs-toggle-inline">
                                    <label class="rs-switch">
                                        <input type="checkbox" name="with_driver" value="1" @checked(old('with_driver'))>
                                        <span class="rs-switch-slider"></span>
                                    </label>
                                    <span>With Driver</span>
                                </div>
                            </div>

                            <div class="col-md-4 rs-field">
                                <label class="rs-label">Featured Vehicle</label>
                                <div class="rs-toggle-inline">
                                    <label class="rs-switch">
                                        <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured'))>
                                        <span class="rs-switch-slider"></span>
                                    </label>
                                    <span>Mark as Featured</span>
                                </div>
                            </div>

                            <div class="col-md-4 rs-field">
                                <label class="rs-label">Vehicle Image</label>
                                <label class="rs-file">
                                    <i class="bi bi-upload"></i> Choose file
                                    <input type="file" name="featured_image" accept="image/jpeg,image/png,image/webp">
                                </label>
                                <div class="rs-help">JPG, PNG or WEBP. Maximum 2MB.</div>
                            </div>

                            <div class="col-12 rs-field">
                                <label class="rs-label">Description</label>
                                <textarea name="description" rows="4" class="rs-textarea" placeholder="Describe your vehicle, features, condition, service details...">{{ old('description') }}</textarea>
                            </div>

                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="rs-btn-ghost" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="rs-btn-primary"><i class="bi bi-plus-lg"></i> Add Vehicle</button>
                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection