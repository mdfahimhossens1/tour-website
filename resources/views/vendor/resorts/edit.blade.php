@extends('layouts.vendor')

@section('page')

<style>

@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');

:root {

    --rs-surface: #1a1d27;
    --rs-surface2: #222636;
    --rs-surface3: #2a2f42;
    --rs-border: rgba(255,255,255,.07);

    --rs-text: #e2e8f0;
    --rs-muted: #64748b;

    --rs-indigo: #6366f1;
    --rs-purple: #8b5cf6;

    --rs-success: #22c55e;
    --rs-warning: #f59e0b;
    --rs-danger: #ef4444;

    --rs-radius: 14px;
    --rs-shadow: 0 8px 32px rgba(0,0,0,.45);
}


.rs-wrap { font-family: 'Plus Jakarta Sans', sans-serif; color: var(--rs-text); }


/* HEADER */

.rs-header {
    background: linear-gradient(135deg, #0c1a2e 0%, #0e0c2e 55%, #0c1a2e 100%);
    border-radius: var(--rs-radius);
    padding: 28px 30px;
    margin-bottom: 22px;
    box-shadow: var(--rs-shadow);
    position: relative;
    overflow: hidden;
}

.rs-header::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%236366f1' fill-opacity='0.05'%3E%3Cpath d='M30 5L55 20v20L30 55 5 40V20Z'/%3E%3C/g%3E%3C/svg%3E");
}

.rs-header-content {
    position: relative; z-index: 1;
    display: flex; align-items: center; justify-content: space-between;
    gap: 20px; flex-wrap: wrap;
}

.rs-title-row { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }

.rs-title {
    font-size: 1.5rem; font-weight: 700;
    background: linear-gradient(90deg, #fff, #a5b4fc);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
}

.rs-subtitle { color: rgba(255,255,255,.45); font-size: .82rem; margin-top: 5px; }

.rs-verified-pill {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(34,197,94,.12);
    border: 1px solid rgba(34,197,94,.25);
    color: #86efac;
    border-radius: 999px;
    padding: 5px 11px;
    font-size: .68rem;
    font-weight: 700;
}

.rs-btn-ghost {
    display: inline-flex; align-items: center; gap: 8px;
    border: 1px solid rgba(255,255,255,.12);
    background: rgba(255,255,255,.04);
    color: #e2e8f0;
    border-radius: 10px;
    padding: 10px 16px;
    font-size: .82rem; font-weight: 600;
    text-decoration: none;
    transition: all .2s ease;
    white-space: nowrap;
}

.rs-btn-ghost:hover { background: rgba(255,255,255,.09); color: #fff; }

.rs-btn-primary {
    display: inline-flex; align-items: center; justify-content: center; gap: 8px;
    border: none;
    background: linear-gradient(135deg, var(--rs-indigo), var(--rs-purple));
    color: #fff;
    border-radius: 10px;
    padding: 12px 20px;
    font-size: .86rem; font-weight: 600;
    box-shadow: 0 8px 22px rgba(99,102,241,.28);
    transition: transform .2s ease, box-shadow .2s ease, opacity .2s ease;
    width: 100%;
}

.rs-btn-primary:hover { transform: translateY(-1px); box-shadow: 0 10px 26px rgba(99,102,241,.4); color: #fff; }
.rs-btn-primary:disabled { opacity: .7; transform: none; cursor: not-allowed; }

.rs-btn-block-ghost {
    display: flex; align-items: center; justify-content: center; gap: 8px;
    border: 1px solid rgba(255,255,255,.12);
    background: rgba(255,255,255,.04);
    color: #e2e8f0;
    border-radius: 10px;
    padding: 11px 16px;
    font-size: .84rem; font-weight: 600;
    text-decoration: none;
    width: 100%;
    transition: all .2s ease;
}

.rs-btn-block-ghost:hover { background: rgba(255,255,255,.09); color: #fff; }


/* ALERTS */

.rs-wrap .alert {
    background: var(--rs-surface);
    border: 1px solid var(--rs-border);
    color: var(--rs-text);
    border-radius: 12px;
    font-size: .84rem;
    box-shadow: var(--rs-shadow);
}
.rs-wrap .alert-success { border-left: 3px solid var(--rs-success); }
.rs-wrap .alert-danger { border-left: 3px solid var(--rs-danger); }
.rs-wrap .btn-close { filter: invert(1) grayscale(1) opacity(.6); }
.rs-wrap .alert ul { padding-left: 18px; margin: 6px 0 0; }
.rs-wrap .alert li { margin-bottom: 3px; }


/* LAYOUT */

.rs-form-grid { display: grid; grid-template-columns: 1fr 340px; gap: 20px; align-items: start; }

@media (max-width: 991.98px) {
    .rs-form-grid { grid-template-columns: 1fr; }
}


/* CARD */

.rs-card {
    background: var(--rs-surface);
    border: 1px solid var(--rs-border);
    border-radius: var(--rs-radius);
    box-shadow: var(--rs-shadow);
    overflow: hidden;
    margin-bottom: 20px;
}

.rs-card-head {
    padding: 17px 22px;
    border-bottom: 1px solid var(--rs-border);
    display: flex; align-items: center; gap: 12px;
}

.rs-card-icon {
    width: 38px; height: 38px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; font-size: .95rem;
}

.rs-card-head h2 { font-size: .88rem; font-weight: 700; margin: 0; color: var(--rs-text); }
.rs-card-head span { display: block; font-size: .7rem; color: var(--rs-muted); margin-top: 2px; font-weight: 400; }

.rs-card-body { padding: 22px; }

.rs-divider { border-top: 1px solid var(--rs-border); margin: 22px 0; padding-top: 22px; }


/* FORM CONTROLS */

.rs-field { margin-bottom: 18px; }
.rs-field:last-child { margin-bottom: 0; }

.rs-label { display: block; font-size: .78rem; font-weight: 600; color: #cbd5e1; margin-bottom: 7px; }
.rs-label .req { color: var(--rs-danger); }

.rs-input, .rs-select, .rs-textarea {
    width: 100%;
    background: var(--rs-surface2);
    border: 1px solid var(--rs-border);
    color: var(--rs-text);
    border-radius: 9px;
    padding: 11px 13px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: .84rem;
    outline: none;
    transition: border-color .15s, box-shadow .15s;
}

.rs-textarea { resize: vertical; min-height: 90px; }
.rs-input::placeholder, .rs-textarea::placeholder { color: var(--rs-muted); }

.rs-input:focus, .rs-select:focus, .rs-textarea:focus {
    border-color: rgba(99,102,241,.5);
    box-shadow: 0 0 0 3px rgba(99,102,241,.1);
}

.rs-input.is-invalid, .rs-select.is-invalid, .rs-textarea.is-invalid { border-color: rgba(239,68,68,.5); }

.rs-error { color: #fca5a5; font-size: .72rem; margin-top: 5px; }
.rs-help { color: var(--rs-muted); font-size: .72rem; margin-top: 5px; }

.rs-row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.rs-row-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }
.rs-row-82 { display: grid; grid-template-columns: 2fr 1fr; gap: 16px; }

@media (max-width: 575.98px) {
    .rs-row-2, .rs-row-3, .rs-row-82 { grid-template-columns: 1fr; }
}

.rs-prefix-group { display: flex; }
.rs-prefix {
    display: flex; align-items: center;
    background: var(--rs-surface3);
    border: 1px solid var(--rs-border);
    border-right: none;
    color: var(--rs-muted);
    padding: 0 12px;
    border-radius: 9px 0 0 9px;
    font-size: .85rem;
}
.rs-prefix-group .rs-input { border-radius: 0 9px 9px 0; }

.rs-number-suffix { display: flex; align-items: stretch; }
.rs-number-suffix .rs-input { border-radius: 9px 0 0 9px; border-right: none; }
.rs-number-suffix .rs-suffix {
    display: flex; align-items: center;
    background: var(--rs-surface3);
    border: 1px solid var(--rs-border);
    border-radius: 0 9px 9px 0;
    padding: 0 13px; color: var(--rs-warning);
}


/* TOGGLE SWITCH */

.rs-toggle-card {
    display: flex; align-items: center; justify-content: space-between; gap: 12px;
    background: var(--rs-surface2);
    border: 1px solid var(--rs-border);
    border-radius: 10px;
    padding: 14px 16px;
    margin-bottom: 14px;
}

.rs-toggle-card:last-child { margin-bottom: 0; }
.rs-toggle-card div strong { display: flex; align-items: center; gap: 6px; font-size: .82rem; color: var(--rs-text); }
.rs-toggle-card div small { color: var(--rs-muted); font-size: .72rem; display: block; margin-top: 3px; }

.rs-switch { position: relative; width: 44px; height: 24px; flex-shrink: 0; }
.rs-switch input { opacity: 0; width: 0; height: 0; }
.rs-switch-slider {
    position: absolute; inset: 0; cursor: pointer;
    background: var(--rs-surface3); border: 1px solid var(--rs-border);
    border-radius: 999px; transition: .2s;
}
.rs-switch-slider::before {
    content: ''; position: absolute; width: 18px; height: 18px;
    left: 2px; top: 2px; background: #fff; border-radius: 50%; transition: .2s;
}
.rs-switch input:checked + .rs-switch-slider { background: var(--rs-success); border-color: var(--rs-success); }
.rs-switch input:checked + .rs-switch-slider::before { transform: translateX(20px); }


/* FACILITIES */

.rs-facility-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(190px, 1fr)); gap: 10px; }

.rs-facility-item {
    display: flex; align-items: center; gap: 10px;
    background: var(--rs-surface2);
    border: 1px solid var(--rs-border);
    border-radius: 10px;
    padding: 11px 12px;
    cursor: pointer;
    transition: all .15s;
}

.rs-facility-item:hover { border-color: rgba(99,102,241,.3); }
.rs-facility-item.is-checked { border-color: rgba(99,102,241,.5); background: rgba(99,102,241,.08); }

.rs-facility-item input { accent-color: var(--rs-indigo); width: 15px; height: 15px; flex-shrink: 0; }

.rs-facility-icon {
    width: 28px; height: 28px; border-radius: 7px;
    background: var(--rs-surface3);
    display: flex; align-items: center; justify-content: center;
    font-size: .78rem; color: #a5b4fc; flex-shrink: 0;
}

.rs-facility-item span.rs-facility-name { font-size: .78rem; color: #cbd5e1; }


/* IMAGE SLOTS */

.rs-image-slot {
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid var(--rs-border);
    background: var(--rs-surface2);
    position: relative;
    height: 210px;
    margin-bottom: 12px;
    display: flex; align-items: center; justify-content: center;
}

.rs-image-slot img { width: 100%; height: 100%; object-fit: cover; }

.rs-image-slot .rs-placeholder { text-align: center; color: var(--rs-muted); }
.rs-image-slot .rs-placeholder i { font-size: 1.8rem; display: block; margin-bottom: 6px; }
.rs-image-slot .rs-placeholder span { font-size: .78rem; }

.rs-image-tag {
    position: absolute; top: 10px; left: 10px;
    background: rgba(10,10,16,.75);
    color: #fff;
    font-size: .66rem; font-weight: 700;
    padding: 4px 9px; border-radius: 999px;
    display: inline-flex; align-items: center; gap: 4px;
}

.rs-file-btn {
    display: inline-flex; align-items: center; gap: 7px;
    background: var(--rs-surface2);
    border: 1px solid var(--rs-border);
    color: #cbd5e1;
    border-radius: 9px;
    padding: 9px 13px;
    font-size: .78rem;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    width: 100%;
}

.rs-file-btn:hover { border-color: rgba(99,102,241,.4); }
.rs-file-btn input[type="file"] { position: absolute; inset: 0; opacity: 0; cursor: pointer; }


/* GALLERY */

.rs-gallery-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 10px; margin-top: 14px; }

.rs-gallery-item { border-radius: 10px; overflow: hidden; border: 1px solid var(--rs-border); background: var(--rs-surface2); }
.rs-gallery-item img { width: 100%; height: 100px; object-fit: cover; display: block; }
.rs-gallery-item .rs-gallery-cap { padding: 7px 9px; font-size: .68rem; color: var(--rs-muted); }

.rs-existing-gallery-item {
    border-radius: 12px; overflow: hidden;
    border: 1px solid var(--rs-border);
    background: var(--rs-surface2);
}

.rs-existing-gallery-item .rs-eg-image { position: relative; }
.rs-existing-gallery-item img { width: 100%; height: 150px; object-fit: cover; display: block; }

.rs-eg-cover-tag {
    position: absolute; top: 8px; left: 8px;
    background: linear-gradient(135deg, var(--rs-indigo), var(--rs-purple));
    color: #fff; font-size: .62rem; font-weight: 700;
    padding: 4px 8px; border-radius: 999px;
}

.rs-eg-footer { padding: 9px 11px; display: flex; align-items: center; justify-content: space-between; }
.rs-eg-footer small { color: var(--rs-muted); font-size: .68rem; }

.rs-delete-btn {
    border: 1px solid rgba(239,68,68,.25);
    background: rgba(239,68,68,.1);
    color: #fca5a5;
    border-radius: 7px;
    padding: 5px 9px;
    font-size: .68rem;
    font-weight: 600;
    display: inline-flex; align-items: center; gap: 4px;
}

.rs-delete-btn:hover { background: rgba(239,68,68,.18); color: #fecaca; }


/* SIDEBAR INFO ROWS */

.rs-info-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: 10px 0;
    border-bottom: 1px dashed var(--rs-border);
    font-size: .8rem;
}
.rs-info-row:last-child { border-bottom: none; }
.rs-info-row span:first-child { color: var(--rs-muted); }
.rs-info-row span:last-child { color: var(--rs-text); font-family: 'JetBrains Mono', monospace; font-weight: 600; }


/* SUBMIT NOTE */

.rs-submit-note { text-align: center; margin-top: 12px; color: var(--rs-muted); font-size: .72rem; }

</style>


<div class="rs-wrap">


    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="rs-header">

        <div class="rs-header-content">

            <div>
                <div class="rs-title-row">
                    <div class="rs-title"><i class="bi bi-pencil-square me-2"></i> Edit Resort</div>

                    @if($resort->is_verified)
                        <span class="rs-verified-pill">
                            <i class="bi bi-patch-check-fill"></i> Verified
                        </span>
                    @endif
                </div>
                <div class="rs-subtitle">Update your resort information, facilities, location, images and settings.</div>
            </div>

            <a href="{{ route('vendor.resorts.index') }}" class="rs-btn-ghost">
                <i class="bi bi-arrow-left"></i>
                Back to Resorts
            </a>

        </div>

    </div>


    {{-- =====================================================
         MESSAGES
    ====================================================== --}}

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger mb-4">
            <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i> Please fix the following errors:</div>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- =====================================================
         MAIN UPDATE FORM
    ====================================================== --}}

    <form id="resortUpdateForm" action="{{ route('vendor.resorts.update', $resort->slug) }}" method="POST" enctype="multipart/form-data">

        @csrf

        <div class="rs-form-grid">

            {{-- =============================================
                 LEFT COLUMN
            ============================================== --}}

            <div>

                {{-- BASIC INFORMATION --}}
                <div class="rs-card">

                    <div class="rs-card-head">
                        <div class="rs-card-icon" style="background:rgba(99,102,241,.12);color:#a5b4fc;"><i class="bi bi-building"></i></div>
                        <div>
                            <h2>Basic Information</h2>
                            <span>Keep your resort information clear and attractive for customers</span>
                        </div>
                    </div>

                    <div class="rs-card-body">

                        <div class="rs-row-82">

                            <div class="rs-field">
                                <label class="rs-label">Resort Name <span class="req">*</span></label>
                                <input type="text" name="name" id="resortName" value="{{ old('name', $resort->name) }}"
                                       class="rs-input @error('name') is-invalid @enderror" placeholder="Enter resort name" required>
                                @error('name')<div class="rs-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="rs-field">
                                <label class="rs-label">Destination</label>
                                <select name="destination_id" class="rs-select @error('destination_id') is-invalid @enderror">
                                    <option value="">Select Destination</option>
                                    @foreach($destinations as $destination)
                                        <option value="{{ $destination->id }}" @selected(old('destination_id', $resort->destination_id) == $destination->id)>
                                            {{ $destination->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('destination_id')<div class="rs-error">{{ $message }}</div>@enderror
                            </div>

                        </div>

                        <div class="rs-field">
                            <label class="rs-label">Resort Slug</label>
                            <div class="rs-prefix-group">
                                <span class="rs-prefix"><i class="bi bi-link-45deg"></i></span>
                                <input type="text" name="slug" id="resortSlug" value="{{ old('slug', $resort->slug) }}"
                                       class="rs-input @error('slug') is-invalid @enderror" placeholder="resort-name">
                            </div>
                            <div class="rs-help">Used in your public resort URL. Leave blank to generate automatically.</div>
                            @error('slug')<div class="rs-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="rs-field">
                            <label class="rs-label">Short Description</label>
                            <textarea name="short_description" rows="3" maxlength="1000"
                                      class="rs-textarea @error('short_description') is-invalid @enderror"
                                      placeholder="Write a short and attractive description...">{{ old('short_description', $resort->short_description) }}</textarea>
                            <div class="rs-help">A short description helps customers understand your resort quickly.</div>
                            @error('short_description')<div class="rs-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="rs-field">
                            <label class="rs-label">Full Description</label>
                            <textarea name="description" rows="7"
                                      class="rs-textarea @error('description') is-invalid @enderror"
                                      placeholder="Write detailed information about your resort...">{{ old('description', $resort->description) }}</textarea>
                            <div class="rs-help">Mention rooms, facilities, nearby attractions, policies and other useful information.</div>
                            @error('description')<div class="rs-error">{{ $message }}</div>@enderror
                        </div>

                    </div>

                </div>


                {{-- LOCATION --}}
                <div class="rs-card">

                    <div class="rs-card-head">
                        <div class="rs-card-icon" style="background:rgba(239,68,68,.12);color:#fca5a5;"><i class="bi bi-geo-alt"></i></div>
                        <div>
                            <h2>Location Information</h2>
                            <span>Help travelers find your resort easily</span>
                        </div>
                    </div>

                    <div class="rs-card-body">

                        <div class="rs-row-3">

                            <div class="rs-field">
                                <label class="rs-label">Division</label>
                                <input type="text" name="division" value="{{ old('division', $resort->division) }}"
                                       class="rs-input @error('division') is-invalid @enderror" placeholder="e.g. Khulna">
                                @error('division')<div class="rs-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="rs-field">
                                <label class="rs-label">District</label>
                                <input type="text" name="district" value="{{ old('district', $resort->district) }}"
                                       class="rs-input @error('district') is-invalid @enderror" placeholder="e.g. Khulna">
                                @error('district')<div class="rs-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="rs-field">
                                <label class="rs-label">Area / Upazila</label>
                                <input type="text" name="area" value="{{ old('area', $resort->area) }}"
                                       class="rs-input @error('area') is-invalid @enderror" placeholder="e.g. Dacope">
                                @error('area')<div class="rs-error">{{ $message }}</div>@enderror
                            </div>

                        </div>

                        <div class="rs-field mt-3">
                            <label class="rs-label">Full Address</label>
                            <textarea name="address" rows="3" class="rs-textarea @error('address') is-invalid @enderror"
                                      placeholder="Enter complete resort address...">{{ old('address', $resort->address) }}</textarea>
                            @error('address')<div class="rs-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="rs-field">
                            <label class="rs-label">Google Maps URL / Embed Code</label>
                            <textarea name="google_map" rows="3" class="rs-textarea @error('google_map') is-invalid @enderror"
                                      placeholder="Paste Google Maps URL or embed code">{{ old('google_map', $resort->google_map) }}</textarea>
                            <div class="rs-help">Adding a map helps customers locate your resort.</div>
                            @error('google_map')<div class="rs-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="rs-row-2">

                            <div class="rs-field">
                                <label class="rs-label">Latitude</label>
                                <input type="number" step="any" name="latitude" value="{{ old('latitude', $resort->latitude) }}"
                                       class="rs-input @error('latitude') is-invalid @enderror" placeholder="22.8456">
                                @error('latitude')<div class="rs-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="rs-field">
                                <label class="rs-label">Longitude</label>
                                <input type="number" step="any" name="longitude" value="{{ old('longitude', $resort->longitude) }}"
                                       class="rs-input @error('longitude') is-invalid @enderror" placeholder="89.5403">
                                @error('longitude')<div class="rs-error">{{ $message }}</div>@enderror
                            </div>

                        </div>

                    </div>

                </div>


                {{-- FACILITIES --}}
                <div class="rs-card">

                    <div class="rs-card-head">
                        <div class="rs-card-icon" style="background:rgba(34,197,94,.12);color:#86efac;"><i class="bi bi-stars"></i></div>
                        <div>
                            <h2>Resort Facilities</h2>
                            <span>Select all facilities available for your guests</span>
                        </div>
                    </div>

                    <div class="rs-card-body">

                        @php
                            $oldFacilities = old('facilities', $resort->facilities->pluck('id')->toArray());
                        @endphp

                        @if($facilities->count())

                            <div class="rs-facility-grid">

                                @foreach($facilities as $facility)

                                    <label class="rs-facility-item {{ in_array($facility->id, $oldFacilities) ? 'is-checked' : '' }}">

                                        <input type="checkbox" name="facilities[]" value="{{ $facility->id }}"
                                               @checked(in_array($facility->id, $oldFacilities))>

                                        <span class="rs-facility-icon">
                                            @if($facility->icon)
                                                <i class="{{ $facility->icon }}"></i>
                                            @else
                                                <i class="bi bi-check-circle"></i>
                                            @endif
                                        </span>

                                        <span class="rs-facility-name">{{ $facility->name }}</span>

                                    </label>

                                @endforeach

                            </div>

                        @else

                            <div class="rs-help" style="font-size:.8rem;">
                                <i class="bi bi-info-circle me-1"></i>
                                No active resort facilities are available yet.
                            </div>

                        @endif

                    </div>

                </div>


                {{-- CHECK-IN / CHECK-OUT --}}
                <div class="rs-card">

                    <div class="rs-card-head">
                        <div class="rs-card-icon" style="background:rgba(14,165,233,.12);color:#7dd3fc;"><i class="bi bi-clock"></i></div>
                        <div>
                            <h2>Check-in &amp; Check-out</h2>
                            <span>Configure standard guest arrival and departure times</span>
                        </div>
                    </div>

                    <div class="rs-card-body">

                        <div class="rs-row-2">

                            <div class="rs-field">
                                <label class="rs-label">Check-in Time</label>
                                <input type="time" name="check_in" value="{{ old('check_in', $resort->check_in) }}"
                                       class="rs-input @error('check_in') is-invalid @enderror">
                                @error('check_in')<div class="rs-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="rs-field">
                                <label class="rs-label">Check-out Time</label>
                                <input type="time" name="check_out" value="{{ old('check_out', $resort->check_out) }}"
                                       class="rs-input @error('check_out') is-invalid @enderror">
                                @error('check_out')<div class="rs-error">{{ $message }}</div>@enderror
                            </div>

                        </div>

                    </div>

                </div>


                {{-- IMAGES --}}
                <div class="rs-card">

                    <div class="rs-card-head">
                        <div class="rs-card-icon" style="background:rgba(245,158,11,.12);color:#fcd34d;"><i class="bi bi-images"></i></div>
                        <div>
                            <h2>Resort Images</h2>
                            <span>High-quality images make your marketplace listing more attractive</span>
                        </div>
                    </div>

                    <div class="rs-card-body">

                        <div class="rs-row-2">

                            {{-- Featured Image --}}
                            <div>
                                <label class="rs-label">Featured Image</label>

                                <div class="rs-image-slot">
                                    @if($resort->featured_image)
                                        <img src="{{ asset('storage/' . $resort->featured_image) }}" alt="{{ $resort->name }}" id="featuredPreview">
                                        <span class="rs-image-tag"><i class="bi bi-star-fill"></i> Featured</span>
                                    @else
                                        <div class="rs-placeholder" id="featuredPreviewBox">
                                            <i class="bi bi-image"></i>
                                            <span>No featured image</span>
                                        </div>
                                    @endif
                                </div>

                                <label class="rs-file-btn">
                                    <i class="bi bi-upload"></i> Choose featured image
                                    <input type="file" name="featured_image" id="featuredImageInput" accept="image/jpeg,image/png,image/webp">
                                </label>

                                <div class="rs-help">JPG, JPEG, PNG or WEBP. Maximum 2MB.</div>
                                @error('featured_image')<div class="rs-error">{{ $message }}</div>@enderror
                            </div>


                            {{-- Cover Image --}}
                            <div>
                                <label class="rs-label">Cover Image</label>

                                <div class="rs-image-slot">
                                    @if($resort->cover_image)
                                        <img src="{{ asset('storage/' . $resort->cover_image) }}" alt="{{ $resort->name }}" id="coverPreview">
                                        <span class="rs-image-tag"><i class="bi bi-image"></i> Cover</span>
                                    @else
                                        <div class="rs-placeholder" id="coverPreviewBox">
                                            <i class="bi bi-image"></i>
                                            <span>No cover image</span>
                                        </div>
                                    @endif
                                </div>

                                <label class="rs-file-btn">
                                    <i class="bi bi-upload"></i> Choose cover image
                                    <input type="file" name="cover_image" id="coverImageInput" accept="image/jpeg,image/png,image/webp">
                                </label>

                                <div class="rs-help">JPG, JPEG, PNG or WEBP. Maximum 4MB.</div>
                                @error('cover_image')<div class="rs-error">{{ $message }}</div>@enderror
                            </div>

                        </div>


                        <div class="rs-divider">

                            <label class="rs-label">Add New Gallery Images</label>

                            <label class="rs-file-btn">
                                <i class="bi bi-images"></i> Choose gallery images (multiple)
                                <input type="file" name="images[]" id="galleryInput" accept="image/jpeg,image/png,image/webp" multiple>
                            </label>

                            <div class="rs-help">You can select multiple images. Maximum 4MB per image.</div>
                            @error('images')<div class="rs-error">{{ $message }}</div>@enderror
                            @error('images.*')<div class="rs-error">{{ $message }}</div>@enderror

                            <div id="galleryPreview" class="rs-gallery-grid"></div>

                        </div>

                    </div>

                </div>


                {{-- SEO --}}
                <div class="rs-card">

                    <div class="rs-card-head">
                        <div class="rs-card-icon" style="background:rgba(148,163,184,.12);color:#cbd5e1;"><i class="bi bi-search"></i></div>
                        <div>
                            <h2>SEO Information</h2>
                            <span>Improve how your resort appears in search engines</span>
                        </div>
                    </div>

                    <div class="rs-card-body">

                        <div class="rs-field">
                            <label class="rs-label">Meta Title</label>
                            <input type="text" name="meta_title" value="{{ old('meta_title', $resort->meta_title) }}" maxlength="255"
                                   class="rs-input @error('meta_title') is-invalid @enderror" placeholder="SEO title">
                            @error('meta_title')<div class="rs-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="rs-field">
                            <label class="rs-label">Meta Description</label>
                            <textarea name="meta_description" rows="4" maxlength="1000"
                                      class="rs-textarea @error('meta_description') is-invalid @enderror"
                                      placeholder="SEO description...">{{ old('meta_description', $resort->meta_description) }}</textarea>
                            @error('meta_description')<div class="rs-error">{{ $message }}</div>@enderror
                        </div>

                    </div>

                </div>

            </div>


            {{-- =============================================
                 RIGHT COLUMN
            ============================================== --}}

            <div>

                {{-- SETTINGS --}}
                <div class="rs-card">

                    <div class="rs-card-head">
                        <div class="rs-card-icon" style="background:rgba(99,102,241,.12);color:#a5b4fc;"><i class="bi bi-sliders"></i></div>
                        <div>
                            <h2>Resort Settings</h2>
                            <span>Manage listing settings</span>
                        </div>
                    </div>

                    <div class="rs-card-body">

                        <div class="rs-field">
                            <label class="rs-label">Rating</label>
                            <div class="rs-number-suffix">
                                <input type="number" step="0.1" min="0" max="5" name="rating"
                                       value="{{ old('rating', $resort->rating ?? 0) }}"
                                       class="rs-input @error('rating') is-invalid @enderror" placeholder="0.0">
                                <span class="rs-suffix"><i class="bi bi-star-fill"></i></span>
                            </div>
                            <div class="rs-help">Rating should be between 0 and 5.</div>
                            @error('rating')<div class="rs-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="rs-field">
                            <label class="rs-label">Resort Status</label>
                            <select name="status" class="rs-select @error('status') is-invalid @enderror">
                                <option value="approved" @selected(old('status', $resort->status) === 'approved')>Approved</option>
                                <option value="pending" @selected(old('status', $resort->status) === 'pending')>Pending</option>
                                <option value="rejected" @selected(old('status', $resort->status) === 'rejected')>Rejected</option>
                            </select>
                            @error('status')<div class="rs-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="rs-toggle-card">
                            <div>
                                <strong><i class="bi bi-star-fill" style="color:#fcd34d;"></i> Featured Resort</strong>
                                <small>Featured resorts may receive better marketplace visibility.</small>
                            </div>
                            <label class="rs-switch">
                                <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $resort->is_featured))>
                                <span class="rs-switch-slider"></span>
                            </label>
                        </div>

                        <div class="rs-toggle-card">
                            <div>
                                <strong><i class="bi bi-patch-check-fill" style="color:#86efac;"></i> Verified Resort</strong>
                                <small>Verification should normally be controlled by marketplace administration.</small>
                            </div>
                            <label class="rs-switch">
                                <input type="checkbox" name="is_verified" value="1" @checked(old('is_verified', $resort->is_verified))>
                                <span class="rs-switch-slider"></span>
                            </label>
                        </div>

                    </div>

                </div>


                {{-- QUICK INFO --}}
                <div class="rs-card">

                    <div class="rs-card-head">
                        <div class="rs-card-icon" style="background:rgba(148,163,184,.12);color:#cbd5e1;"><i class="bi bi-info-circle"></i></div>
                        <div><h2>Resort Information</h2></div>
                    </div>

                    <div class="rs-card-body">

                        <div class="rs-info-row"><span>Resort ID</span><span>#{{ $resort->id }}</span></div>
                        <div class="rs-info-row"><span>Gallery Images</span><span>{{ $resort->images->count() }}</span></div>
                        <div class="rs-info-row"><span>Facilities</span><span>{{ $resort->facilities->count() }}</span></div>
                        <div class="rs-info-row"><span>Reviews</span><span>{{ $resort->total_reviews ?? 0 }}</span></div>

                    </div>

                </div>


                {{-- ACTIONS --}}
                <div class="rs-card">

                    <div class="rs-card-body">

                        <button type="submit" id="updateButton" class="rs-btn-primary">
                            <i class="bi bi-check-circle-fill"></i>
                            Update Resort
                        </button>

                        <div class="mt-2"></div>

                        <a href="{{ route('vendor.resorts.index') }}" class="rs-btn-block-ghost">
                            <i class="bi bi-x-circle"></i>
                            Cancel
                        </a>

                        <div class="rs-submit-note">
                            <i class="bi bi-shield-check me-1"></i>
                            Your resort data is securely managed.
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </form>


    {{-- =====================================================
         EXISTING GALLERY (outside main form)
    ====================================================== --}}

    @if($resort->images->count())

        <div class="rs-form-grid mt-1">

            <div>

                <div class="rs-card">

                    <div class="rs-card-head">
                        <div class="rs-card-icon" style="background:rgba(245,158,11,.12);color:#fcd34d;"><i class="bi bi-images"></i></div>
                        <div style="flex:1;">
                            <h2>Existing Gallery</h2>
                            <span>Manage images already uploaded to this resort</span>
                        </div>
                        <span class="rs-total-badge" style="display:inline-flex;align-items:center;gap:6px;background:rgba(99,102,241,.12);border:1px solid rgba(99,102,241,.22);color:#c7d2fe;border-radius:8px;padding:6px 11px;font-size:.7rem;font-weight:600;">
                            {{ $resort->images->count() }} {{ $resort->images->count() == 1 ? 'Image' : 'Images' }}
                        </span>
                    </div>

                    <div class="rs-card-body">

                        <div class="rs-gallery-grid" style="grid-template-columns:repeat(auto-fill, minmax(180px, 1fr));">

                            @foreach($resort->images->sortBy('sort_order') as $image)

                                <div class="rs-existing-gallery-item">

                                    <div class="rs-eg-image">
                                        <img src="{{ asset('storage/' . $image->image) }}" alt="{{ $resort->name }}">
                                        @if($image->is_cover)
                                            <span class="rs-eg-cover-tag"><i class="bi bi-star-fill me-1"></i>Cover</span>
                                        @endif
                                    </div>

                                    <div class="rs-eg-footer">
                                        <small>Image #{{ $loop->iteration }}</small>

                                        <form action="{{ route('vendor.resorts.images.destroy', $image->id) }}" method="POST"
                                              onsubmit="return confirm('Are you sure you want to delete this image?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rs-delete-btn">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>

                                </div>

                            @endforeach

                        </div>

                    </div>

                </div>

            </div>

            <div></div>

        </div>

    @endif

</div>


{{-- =========================================================
    PAGE JS
========================================================== --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    /* Slug field — left as manual entry, matching current backend behaviour */
    const nameInput = document.getElementById('resortName');
    const slugInput = document.getElementById('resortSlug');
    if (nameInput && slugInput) {
        nameInput.addEventListener('input', function () {
            // Slug is not auto-generated on edit; the user controls it manually.
        });
    }

    /* Featured image preview */
    const featuredInput = document.getElementById('featuredImageInput');
    if (featuredInput) {
        featuredInput.addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function (e) {
                let preview = document.getElementById('featuredPreview');
                if (preview) {
                    preview.src = e.target.result;
                } else {
                    const box = document.getElementById('featuredPreviewBox');
                    if (box) {
                        box.parentElement.innerHTML = `<img src="${e.target.result}" id="featuredPreview" alt="Featured preview">`;
                    }
                }
            };
            reader.readAsDataURL(file);
        });
    }

    /* Cover image preview */
    const coverInput = document.getElementById('coverImageInput');
    if (coverInput) {
        coverInput.addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function (e) {
                let preview = document.getElementById('coverPreview');
                if (preview) {
                    preview.src = e.target.result;
                } else {
                    const box = document.getElementById('coverPreviewBox');
                    if (box) {
                        box.parentElement.innerHTML = `<img src="${e.target.result}" id="coverPreview" alt="Cover preview">`;
                    }
                }
            };
            reader.readAsDataURL(file);
        });
    }

    /* Gallery preview */
    const galleryInput = document.getElementById('galleryInput');
    const galleryPreview = document.getElementById('galleryPreview');
    if (galleryInput && galleryPreview) {
        galleryInput.addEventListener('change', function () {
            galleryPreview.innerHTML = '';
            Array.from(galleryInput.files).forEach(function (file, index) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const item = document.createElement('div');
                    item.className = 'rs-gallery-item';
                    item.innerHTML = `
                        <img src="${e.target.result}" alt="Gallery preview">
                        <div class="rs-gallery-cap">New image ${index + 1}</div>
                    `;
                    galleryPreview.appendChild(item);
                };
                reader.readAsDataURL(file);
            });
        });
    }

    /* Submit loading state */
    const form = document.getElementById('resortUpdateForm');
    const updateButton = document.getElementById('updateButton');
    if (form && updateButton) {
        form.addEventListener('submit', function () {
            updateButton.disabled = true;
            updateButton.innerHTML = `
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Updating Resort...
            `;
        });
    }

    /* Facility card click + checked-state styling */
    document.querySelectorAll('.rs-facility-item').forEach(function (card) {
        const checkbox = card.querySelector('input[type="checkbox"]');
        if (!checkbox) return;
        checkbox.addEventListener('change', function () {
            card.classList.toggle('is-checked', checkbox.checked);
        });
    });

});

</script>

@endsection