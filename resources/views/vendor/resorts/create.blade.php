@extends('layouts.vendor')

@section('title', 'Add New Resort')

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
    --rs-danger: #ef4444;

    --rs-radius: 14px;
    --rs-shadow: 0 8px 32px rgba(0,0,0,.45);
}


.rs-wrap { font-family: 'Plus Jakarta Sans', sans-serif; color: var(--rs-text); }


/* HEADER (shared style) */

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

.rs-title {
    font-size: 1.5rem; font-weight: 700;
    background: linear-gradient(90deg, #fff, #a5b4fc);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
}

.rs-subtitle { color: rgba(255,255,255,.45); font-size: .82rem; margin-top: 5px; }

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
    display: inline-flex; align-items: center; gap: 8px;
    border: none;
    background: linear-gradient(135deg, var(--rs-indigo), var(--rs-purple));
    color: #fff;
    border-radius: 10px;
    padding: 11px 20px;
    font-size: .84rem; font-weight: 600;
    box-shadow: 0 8px 22px rgba(99,102,241,.28);
    transition: transform .2s ease, box-shadow .2s ease;
}

.rs-btn-primary:hover { transform: translateY(-1px); box-shadow: 0 10px 26px rgba(99,102,241,.4); color: #fff; }


/* ALERTS */

.rs-wrap .alert {
    background: var(--rs-surface);
    border: 1px solid var(--rs-border);
    color: var(--rs-text);
    border-radius: 12px;
    font-size: .84rem;
    box-shadow: var(--rs-shadow);
}
.rs-wrap .alert-danger { border-left: 3px solid var(--rs-danger); }
.rs-wrap .btn-close { filter: invert(1) grayscale(1) opacity(.6); }
.rs-wrap .alert ul { padding-left: 18px; margin: 6px 0 0; }
.rs-wrap .alert li { margin-bottom: 3px; }


/* LAYOUT */

.rs-form-grid { display: grid; grid-template-columns: 1fr 320px; gap: 20px; align-items: start; }

@media (max-width: 991.98px) {
    .rs-form-grid { grid-template-columns: 1fr; }
}


/* CARD / SECTIONS */

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
    display: flex; align-items: center; gap: 10px;
}

.rs-card-head i { color: #a5b4fc; font-size: 1rem; }

.rs-card-head h2 {
    font-size: .88rem; font-weight: 700; margin: 0; color: var(--rs-text);
}

.rs-card-head span {
    display: block; font-size: .7rem; color: var(--rs-muted); margin-top: 2px; font-weight: 400;
}

.rs-card-body { padding: 22px; }


/* FORM CONTROLS */

.rs-field { margin-bottom: 18px; }
.rs-field:last-child { margin-bottom: 0; }

.rs-label {
    display: block;
    font-size: .78rem;
    font-weight: 600;
    color: #cbd5e1;
    margin-bottom: 7px;
}

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

.rs-textarea { resize: vertical; min-height: 100px; }

.rs-input::placeholder, .rs-textarea::placeholder { color: var(--rs-muted); }

.rs-input:focus, .rs-select:focus, .rs-textarea:focus {
    border-color: rgba(99,102,241,.5);
    box-shadow: 0 0 0 3px rgba(99,102,241,.1);
}

.rs-input.is-invalid, .rs-select.is-invalid, .rs-textarea.is-invalid {
    border-color: rgba(239,68,68,.5);
}

.rs-error {
    color: #fca5a5;
    font-size: .72rem;
    margin-top: 5px;
}

.rs-help {
    color: var(--rs-muted);
    font-size: .72rem;
    margin-top: 5px;
}

.rs-row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.rs-row-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }

@media (max-width: 575.98px) {
    .rs-row-2, .rs-row-3 { grid-template-columns: 1fr; }
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
    font-size: .8rem;
}
.rs-prefix-group .rs-input { border-radius: 0 9px 9px 0; }


/* STATUS TOGGLE */

.rs-toggle-card {
    display: flex; align-items: center; justify-content: space-between;
    background: var(--rs-surface2);
    border: 1px solid var(--rs-border);
    border-radius: 10px;
    padding: 14px 16px;
}

.rs-toggle-card div strong { display: block; font-size: .82rem; color: var(--rs-text); }
.rs-toggle-card div small { color: var(--rs-muted); font-size: .72rem; }

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

.rs-facility-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 10px;
}

.rs-facility-item {
    display: flex; align-items: center; gap: 9px;
    background: var(--rs-surface2);
    border: 1px solid var(--rs-border);
    border-radius: 9px;
    padding: 10px 12px;
    cursor: pointer;
    transition: all .15s;
}

.rs-facility-item:hover { border-color: rgba(99,102,241,.3); }

.rs-facility-item input { accent-color: var(--rs-indigo); width: 15px; height: 15px; flex-shrink: 0; }

.rs-facility-item span { font-size: .78rem; color: #cbd5e1; }

.rs-facility-empty { color: var(--rs-muted); font-size: .8rem; }


/* IMAGE UPLOAD */

.rs-upload {
    border: 1.5px dashed var(--rs-border);
    border-radius: 12px;
    background: var(--rs-surface2);
    text-align: center;
    padding: 26px 16px;
    cursor: pointer;
    transition: border-color .15s;
    position: relative;
    overflow: hidden;
}

.rs-upload:hover { border-color: rgba(99,102,241,.4); }

.rs-upload i { font-size: 1.6rem; color: #a5b4fc; margin-bottom: 8px; display: block; }
.rs-upload p { font-size: .8rem; color: #cbd5e1; margin: 0 0 3px; font-weight: 600; }
.rs-upload small { color: var(--rs-muted); font-size: .68rem; }

.rs-upload input[type="file"] {
    position: absolute; inset: 0; opacity: 0; cursor: pointer;
}

.rs-upload-preview {
    width: 100%; height: 150px; object-fit: cover;
    border-radius: 10px; display: none; margin-bottom: 12px;
}


/* SUBMIT BAR */

.rs-submit-bar {
    display: flex; justify-content: flex-end; gap: 10px;
    padding-top: 4px;
}

@media (max-width: 575.98px) {
    .rs-submit-bar { flex-direction: column-reverse; }
    .rs-submit-bar a, .rs-submit-bar button { width: 100%; justify-content: center; }
}

</style>


<div class="rs-wrap">


    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="rs-header">

        <div class="rs-header-content">

            <div>
                <div class="rs-title"><i class="bi bi-building-add me-2"></i> Add New Resort</div>
                <div class="rs-subtitle">Fill in the details below to list a new resort on the marketplace.</div>
            </div>

            <a href="{{ route('vendor.resorts.index') }}" class="rs-btn-ghost">
                <i class="bi bi-arrow-left"></i>
                Back to Resorts
            </a>

        </div>

    </div>


    {{-- =====================================================
         ERRORS
    ====================================================== --}}

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4">
            <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle me-1"></i> Please fix the following errors:</div>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif


    <form action="{{ route('vendor.resorts.store') }}" method="POST" enctype="multipart/form-data" id="rsResortForm">

        @csrf

        <div class="rs-form-grid">

            {{-- =============================================
                 LEFT COLUMN
            ============================================== --}}

            <div>

                {{-- BASIC INFORMATION --}}
                <div class="rs-card">

                    <div class="rs-card-head">
                        <i class="bi bi-info-circle"></i>
                        <div>
                            <h2>Basic Information</h2>
                            <span>The name and description guests will see</span>
                        </div>
                    </div>

                    <div class="rs-card-body">

                        <div class="rs-field">
                            <label class="rs-label">Resort Name <span class="req">*</span></label>
                            <input type="text" name="name" class="rs-input @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}" placeholder="e.g. Sundarban Riverside Resort" required>
                            @error('name')<div class="rs-error">{{ $message }}</div>@enderror
                        </div>

                        <div class="rs-field">
                            <label class="rs-label">Short Description</label>
                            <textarea name="description" class="rs-textarea @error('description') is-invalid @enderror"
                                      placeholder="A short, appealing description of your resort...">{{ old('description') }}</textarea>
                            @error('description')<div class="rs-error">{{ $message }}</div>@enderror
                            <div class="rs-help">This appears on your resort's listing card and detail page.</div>
                        </div>

                        <div class="rs-field">
                            <label class="rs-label">Destination</label>
                            <select name="destination_id" class="rs-select @error('destination_id') is-invalid @enderror">
                                <option value="">Select a destination</option>
                                @isset($destinations)
                                    @foreach($destinations as $destination)
                                        <option value="{{ $destination->id }}" {{ old('destination_id') == $destination->id ? 'selected' : '' }}>
                                            {{ $destination->name }}
                                        </option>
                                    @endforeach
                                @endisset
                            </select>
                            @error('destination_id')<div class="rs-error">{{ $message }}</div>@enderror
                        </div>

                    </div>

                </div>


                {{-- LOCATION DETAILS --}}
                <div class="rs-card">

                    <div class="rs-card-head">
                        <i class="bi bi-geo-alt"></i>
                        <div>
                            <h2>Location Details</h2>
                            <span>Where guests will find your resort</span>
                        </div>
                    </div>

                    <div class="rs-card-body">

                        <div class="rs-row-3">

                            <div class="rs-field">
                                <label class="rs-label">Division</label>
                                <input type="text" name="division" class="rs-input @error('division') is-invalid @enderror"
                                       value="{{ old('division') }}" placeholder="e.g. Khulna">
                                @error('division')<div class="rs-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="rs-field">
                                <label class="rs-label">District</label>
                                <input type="text" name="district" class="rs-input @error('district') is-invalid @enderror"
                                       value="{{ old('district') }}" placeholder="e.g. Khulna">
                                @error('district')<div class="rs-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="rs-field">
                                <label class="rs-label">Area</label>
                                <input type="text" name="area" class="rs-input @error('area') is-invalid @enderror"
                                       value="{{ old('area') }}" placeholder="e.g. Sonadanga">
                                @error('area')<div class="rs-error">{{ $message }}</div>@enderror
                            </div>

                        </div>

                        <div class="rs-field mt-3">
                            <label class="rs-label">Full Address</label>
                            <input type="text" name="address" class="rs-input @error('address') is-invalid @enderror"
                                   value="{{ old('address') }}" placeholder="Street, landmark, postal code...">
                            @error('address')<div class="rs-error">{{ $message }}</div>@enderror
                        </div>

                    </div>

                </div>


                {{-- FACILITIES --}}
                @isset($facilities)
                    <div class="rs-card">

                        <div class="rs-card-head">
                            <i class="bi bi-concierge-bell"></i>
                            <div>
                                <h2>Resort Facilities</h2>
                                <span>Select the facilities available at this resort</span>
                            </div>
                        </div>

                        <div class="rs-card-body">

                            @if($facilities->count())
                                <div class="rs-facility-grid">
                                    @foreach($facilities as $facility)
                                        <label class="rs-facility-item">
                                            <input type="checkbox" name="facilities[]" value="{{ $facility->id }}"
                                                   {{ collect(old('facilities'))->contains($facility->id) ? 'checked' : '' }}>
                                            <span>{{ $facility->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <div class="rs-facility-empty">No facilities are available to select yet.</div>
                            @endif

                        </div>

                    </div>
                @endisset

            </div>


            {{-- =============================================
                 RIGHT COLUMN
            ============================================== --}}

            <div>

                {{-- FEATURED IMAGE --}}
                <div class="rs-card">

                    <div class="rs-card-head">
                        <i class="bi bi-image"></i>
                        <div>
                            <h2>Featured Image</h2>
                            <span>Main photo shown on your listing</span>
                        </div>
                    </div>

                    <div class="rs-card-body">

                        <img id="rsImagePreview" class="rs-upload-preview" alt="Preview">

                        <label class="rs-upload" for="rsFeaturedImage">
                            <i class="bi bi-cloud-arrow-up"></i>
                            <p>Click to upload a photo</p>
                            <small>PNG or JPG, up to 4MB</small>
                            <input type="file" name="featured_image" id="rsFeaturedImage" accept="image/*">
                        </label>

                        @error('featured_image')<div class="rs-error">{{ $message }}</div>@enderror

                    </div>

                </div>


                {{-- STATUS --}}
                <div class="rs-card">

                    <div class="rs-card-head">
                        <i class="bi bi-toggle-on"></i>
                        <div>
                            <h2>Visibility</h2>
                            <span>Control whether guests can see this resort</span>
                        </div>
                    </div>

                    <div class="rs-card-body">

                        <div class="rs-toggle-card">
                            <div>
                                <strong>Active Status</strong>
                                <small>Make this resort visible in search results</small>
                            </div>
                            <label class="rs-switch">
                                <input type="checkbox" name="status" value="1" {{ old('status', 1) ? 'checked' : '' }}>
                                <span class="rs-switch-slider"></span>
                            </label>
                        </div>

                        <div class="rs-help mt-3">
                            <i class="bi bi-shield-check me-1"></i>
                            Featured and Verified badges are assigned by our team after review.
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =============================================
             SUBMIT
        ============================================== --}}

        <div class="rs-submit-bar">
            <a href="{{ route('vendor.resorts.index') }}" class="rs-btn-ghost">Cancel</a>
            <button type="submit" class="rs-btn-primary">
                <i class="bi bi-check-lg"></i>
                Save Resort
            </button>
        </div>

    </form>

</div>


<script>
    (function () {
        var input = document.getElementById('rsFeaturedImage');
        var preview = document.getElementById('rsImagePreview');

        if (input) {
            input.addEventListener('change', function (e) {
                var file = e.target.files[0];
                if (!file) return;
                var reader = new FileReader();
                reader.onload = function (ev) {
                    preview.src = ev.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            });
        }
    })();
</script>

@endsection