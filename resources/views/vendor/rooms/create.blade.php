@extends('layouts.vendor')

@section('title', 'Add Room')

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

.rs-btn-ghost {
    display: inline-flex; align-items: center; gap: 8px;
    border: 1px solid rgba(255,255,255,.12); background: rgba(255,255,255,.04); color: #e2e8f0;
    border-radius: 10px; padding: 10px 16px; font-size: .82rem; font-weight: 600;
    text-decoration: none; transition: all .2s ease; white-space: nowrap;
}
.rs-btn-ghost:hover { background: rgba(255,255,255,.09); color: #fff; }

.rs-btn-primary {
    display: inline-flex; align-items: center; justify-content: center; gap: 8px;
    border: none; background: linear-gradient(135deg, var(--rs-indigo), var(--rs-purple)); color: #fff;
    border-radius: 10px; padding: 12px 20px; font-size: .86rem; font-weight: 600;
    box-shadow: 0 8px 22px rgba(99,102,241,.28);
    transition: transform .2s ease, box-shadow .2s ease, opacity .2s ease; width: 100%;
    text-decoration: none;
}
.rs-btn-primary:hover { transform: translateY(-1px); box-shadow: 0 10px 26px rgba(99,102,241,.4); color: #fff; }
.rs-btn-primary:disabled { opacity: .7; transform: none; cursor: not-allowed; }

.rs-btn-block-ghost {
    display: flex; align-items: center; justify-content: center; gap: 8px;
    border: 1px solid rgba(255,255,255,.12); background: rgba(255,255,255,.04); color: #e2e8f0;
    border-radius: 10px; padding: 11px 16px; font-size: .84rem; font-weight: 600;
    text-decoration: none; width: 100%; transition: all .2s ease;
}
.rs-btn-block-ghost:hover { background: rgba(255,255,255,.09); color: #fff; }


/* ALERTS */

.rs-wrap .alert { background: var(--rs-surface); border: 1px solid var(--rs-border); color: var(--rs-text); border-radius: 12px; font-size: .84rem; box-shadow: var(--rs-shadow); }
.rs-wrap .alert-success { border-left: 3px solid var(--rs-success); }
.rs-wrap .alert-danger { border-left: 3px solid var(--rs-danger); }
.rs-wrap .alert-warning { border-left: 3px solid var(--rs-warning); background: rgba(245,158,11,.08); }
.rs-wrap .alert ul { padding-left: 18px; margin: 6px 0 0; }
.rs-wrap .alert li { margin-bottom: 3px; }


/* LAYOUT */

.rs-form-grid { display: grid; grid-template-columns: 1fr 320px; gap: 20px; align-items: start; }
@media (max-width: 991.98px) { .rs-form-grid { grid-template-columns: 1fr; } }


/* CARD */

.rs-card { background: var(--rs-surface); border: 1px solid var(--rs-border); border-radius: var(--rs-radius); box-shadow: var(--rs-shadow); overflow: hidden; margin-bottom: 20px; }
.rs-card-head { padding: 17px 22px; border-bottom: 1px solid var(--rs-border); display: flex; align-items: center; gap: 12px; }
.rs-card-icon { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: .95rem; }
.rs-card-head h2 { font-size: .88rem; font-weight: 700; margin: 0; color: var(--rs-text); }
.rs-card-head span { display: block; font-size: .7rem; color: var(--rs-muted); margin-top: 2px; font-weight: 400; }
.rs-card-body { padding: 22px; }


/* FORM CONTROLS */

.rs-field { margin-bottom: 18px; }
.rs-field:last-child { margin-bottom: 0; }
.rs-label { display: block; font-size: .78rem; font-weight: 600; color: #cbd5e1; margin-bottom: 7px; }
.rs-label .req { color: var(--rs-danger); }

.rs-input, .rs-select, .rs-textarea {
    width: 100%; background: var(--rs-surface2); border: 1px solid var(--rs-border); color: var(--rs-text);
    border-radius: 9px; padding: 11px 13px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: .84rem;
    outline: none; transition: border-color .15s, box-shadow .15s;
}
.rs-textarea { resize: vertical; min-height: 90px; }
.rs-input::placeholder, .rs-textarea::placeholder { color: var(--rs-muted); }
.rs-input:focus, .rs-select:focus, .rs-textarea:focus { border-color: rgba(99,102,241,.5); box-shadow: 0 0 0 3px rgba(99,102,241,.1); }
.rs-input.is-invalid, .rs-select.is-invalid, .rs-textarea.is-invalid { border-color: rgba(239,68,68,.5); }

.rs-error { color: #fca5a5; font-size: .72rem; margin-top: 5px; }
.rs-help { color: var(--rs-muted); font-size: .72rem; margin-top: 5px; }

.rs-row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.rs-row-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }
@media (max-width: 575.98px) { .rs-row-2, .rs-row-3 { grid-template-columns: 1fr; } }

.rs-prefix-group { display: flex; }
.rs-prefix {
    display: flex; align-items: center; background: var(--rs-surface3);
    border: 1px solid var(--rs-border); border-right: none; color: var(--rs-muted);
    padding: 0 12px; border-radius: 9px 0 0 9px; font-size: .85rem;
}
.rs-prefix-group .rs-input { border-radius: 0 9px 9px 0; }


/* TOGGLE */

.rs-toggle-card {
    display: flex; align-items: center; justify-content: space-between; gap: 12px;
    background: var(--rs-surface2); border: 1px solid var(--rs-border); border-radius: 10px;
    padding: 14px 16px; margin-bottom: 14px;
}
.rs-toggle-card:last-child { margin-bottom: 0; }
.rs-toggle-card div strong { font-size: .82rem; color: var(--rs-text); }
.rs-toggle-card div small { color: var(--rs-muted); font-size: .72rem; display: block; margin-top: 3px; }

.rs-switch { position: relative; width: 44px; height: 24px; flex-shrink: 0; }
.rs-switch input { opacity: 0; width: 0; height: 0; }
.rs-switch-slider { position: absolute; inset: 0; cursor: pointer; background: var(--rs-surface3); border: 1px solid var(--rs-border); border-radius: 999px; transition: .2s; }
.rs-switch-slider::before { content: ''; position: absolute; width: 18px; height: 18px; left: 2px; top: 2px; background: #fff; border-radius: 50%; transition: .2s; }
.rs-switch input:checked + .rs-switch-slider { background: var(--rs-success); border-color: var(--rs-success); }
.rs-switch input:checked + .rs-switch-slider::before { transform: translateX(20px); }


/* IMAGE PICKER */

.rs-file-btn {
    display: inline-flex; align-items: center; gap: 8px;
    background: var(--rs-surface2); border: 1.5px dashed var(--rs-border); color: #cbd5e1;
    border-radius: 10px; padding: 13px 16px; font-size: .8rem; cursor: pointer; position: relative;
    overflow: hidden; width: 100%; justify-content: center; transition: border-color .15s;
}
.rs-file-btn:hover { border-color: rgba(99,102,241,.4); }
.rs-file-btn input[type="file"] { position: absolute; inset: 0; opacity: 0; cursor: pointer; }

.rs-image-count { display: none; align-items: center; gap: 6px; color: #a5b4fc; font-size: .76rem; margin-top: 12px; }
.rs-image-count.is-visible { display: flex; }

.rs-image-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 12px; margin-top: 14px; }

.rs-image-card { border-radius: 10px; overflow: hidden; border: 1px solid var(--rs-border); background: var(--rs-surface2); position: relative; }
.rs-image-card img { width: 100%; height: 120px; object-fit: cover; display: block; }
.rs-image-card-top { position: absolute; top: 0; left: 0; right: 0; padding: 8px; display: flex; justify-content: space-between; align-items: flex-start; }
.rs-image-tag { background: rgba(10,10,16,.75); color: #fff; font-size: .62rem; font-weight: 700; padding: 3px 8px; border-radius: 999px; }
.rs-image-remove {
    width: 22px; height: 22px; border-radius: 6px; border: none;
    background: rgba(239,68,68,.85); color: #fff; display: inline-flex; align-items: center; justify-content: center;
    font-size: .7rem; cursor: pointer;
}
.rs-image-card-name { padding: 6px 9px; font-size: .66rem; color: var(--rs-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

</style>


<div class="rs-wrap">


    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="rs-header">

        <div class="rs-header-content">

            <div>
                <div class="rs-title"><i class="bi bi-door-open me-2"></i> Add Room</div>
                <div class="rs-subtitle">Add a new room to one of your resorts.</div>
            </div>

            <a href="{{ route('vendor.rooms.index') }}" class="rs-btn-ghost">
                <i class="bi bi-arrow-left"></i>
                Back to Rooms
            </a>

        </div>

    </div>


    {{-- =====================================================
         MESSAGES
    ====================================================== --}}

    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle me-1"></i> Please fix the following errors:</div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success mb-4"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger mb-4"><i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}</div>
    @endif


    {{-- =====================================================
         ROOM FORM
    ====================================================== --}}

    <form action="{{ route('vendor.rooms.store') }}" method="POST" enctype="multipart/form-data" id="roomCreateForm">

        @csrf

        <div class="rs-form-grid">

            {{-- =============================================
                 LEFT COLUMN
            ============================================== --}}

            <div>

                {{-- BASIC INFORMATION --}}
                <div class="rs-card">

                    <div class="rs-card-head">
                        <div class="rs-card-icon" style="background:rgba(99,102,241,.12);color:#a5b4fc;"><i class="bi bi-door-closed"></i></div>
                        <div><h2>Basic Information</h2></div>
                    </div>

                    <div class="rs-card-body">

                        <div class="rs-field">
                            <label class="rs-label">Resort <span class="req">*</span></label>

                            @if($resorts->count())
                                <select name="resort_id" id="resort_id" class="rs-select @error('resort_id') is-invalid @enderror" required>
                                    <option value="">Select Resort</option>
                                    @foreach($resorts as $resort)
                                        <option value="{{ $resort->id }}" {{ old('resort_id') == $resort->id ? 'selected' : '' }}>
                                            {{ $resort->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('resort_id')<div class="rs-error">{{ $message }}</div>@enderror
                                <div class="rs-help">Select the resort where this room will be added.</div>
                            @else
                                <div class="alert alert-warning mb-0">
                                    <div class="fw-semibold mb-1">No active resort found.</div>
                                    <div class="small mb-2" style="color:var(--rs-muted);">Please create a resort before adding rooms.</div>
                                    <a href="{{ route('vendor.resorts.create') }}" class="rs-btn-ghost">
                                        <i class="bi bi-plus-lg"></i> Create Resort
                                    </a>
                                </div>
                            @endif
                        </div>

                        <div class="rs-row-2">

                            <div class="rs-field">
                                <label class="rs-label">Room Type</label>
                                <select name="room_type_id" id="room_type_id" class="rs-select @error('room_type_id') is-invalid @enderror">
                                    <option value="">Select Room Type</option>
                                    @foreach($roomTypes as $roomType)
                                        <option value="{{ $roomType->id }}" {{ old('room_type_id') == $roomType->id ? 'selected' : '' }}>
                                            {{ $roomType->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('room_type_id')<div class="rs-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="rs-field">
                                <label class="rs-label">Room Name <span class="req">*</span></label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}"
                                       class="rs-input @error('name') is-invalid @enderror" placeholder="e.g. Deluxe Sea View Room" required>
                                @error('name')<div class="rs-error">{{ $message }}</div>@enderror
                            </div>

                        </div>

                        <div class="rs-row-2">

                            <div class="rs-field">
                                <label class="rs-label">Room Number</label>
                                <input type="text" name="room_no" id="room_no" value="{{ old('room_no') }}"
                                       class="rs-input @error('room_no') is-invalid @enderror" placeholder="e.g. 101">
                                @error('room_no')<div class="rs-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="rs-field">
                                <label class="rs-label">Extra Bed Price</label>
                                <div class="rs-prefix-group">
                                    <span class="rs-prefix">৳</span>
                                    <input type="number" name="extra_bed_price" id="extra_bed_price" value="{{ old('extra_bed_price') }}"
                                           class="rs-input @error('extra_bed_price') is-invalid @enderror" placeholder="0.00" min="0" step="0.01">
                                </div>
                                @error('extra_bed_price')<div class="rs-error">{{ $message }}</div>@enderror
                            </div>

                        </div>

                        <div class="rs-field">
                            <label class="rs-label">Description</label>
                            <textarea name="description" id="description" rows="5"
                                      class="rs-textarea @error('description') is-invalid @enderror"
                                      placeholder="Describe the room, amenities, view, sleeping arrangement etc.">{{ old('description') }}</textarea>
                            @error('description')<div class="rs-error">{{ $message }}</div>@enderror
                        </div>

                    </div>

                </div>


                {{-- CAPACITY --}}
                <div class="rs-card">

                    <div class="rs-card-head">
                        <div class="rs-card-icon" style="background:rgba(14,165,233,.12);color:#7dd3fc;"><i class="bi bi-people"></i></div>
                        <div><h2>Capacity &amp; Room Details</h2></div>
                    </div>

                    <div class="rs-card-body">

                        <div class="rs-row-3">

                            <div class="rs-field">
                                <label class="rs-label">Total Rooms <span class="req">*</span></label>
                                <input type="number" name="total_rooms" id="total_rooms" value="{{ old('total_rooms', 1) }}"
                                       class="rs-input @error('total_rooms') is-invalid @enderror" min="1" required>
                                @error('total_rooms')<div class="rs-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="rs-field">
                                <label class="rs-label">Maximum Adults <span class="req">*</span></label>
                                <input type="number" name="max_adult" id="max_adult" value="{{ old('max_adult', 2) }}"
                                       class="rs-input @error('max_adult') is-invalid @enderror" min="1" required>
                                @error('max_adult')<div class="rs-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="rs-field">
                                <label class="rs-label">Maximum Children</label>
                                <input type="number" name="max_child" id="max_child" value="{{ old('max_child', 0) }}"
                                       class="rs-input @error('max_child') is-invalid @enderror" min="0">
                                @error('max_child')<div class="rs-error">{{ $message }}</div>@enderror
                            </div>

                        </div>

                        <div class="rs-row-3">

                            <div class="rs-field">
                                <label class="rs-label">Number of Beds <span class="req">*</span></label>
                                <input type="number" name="beds" id="beds" value="{{ old('beds', 1) }}"
                                       class="rs-input @error('beds') is-invalid @enderror" min="1" required>
                                @error('beds')<div class="rs-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="rs-field">
                                <label class="rs-label">Bathrooms <span class="req">*</span></label>
                                <input type="number" name="bathrooms" id="bathrooms" value="{{ old('bathrooms', 1) }}"
                                       class="rs-input @error('bathrooms') is-invalid @enderror" min="1" required>
                                @error('bathrooms')<div class="rs-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="rs-field">
                                <label class="rs-label">View Type</label>
                                <select name="view_type" id="view_type" class="rs-select @error('view_type') is-invalid @enderror">
                                    <option value="">Select View</option>
                                    <option value="sea" {{ old('view_type') === 'sea' ? 'selected' : '' }}>Sea View</option>
                                    <option value="mountain" {{ old('view_type') === 'mountain' ? 'selected' : '' }}>Mountain View</option>
                                    <option value="garden" {{ old('view_type') === 'garden' ? 'selected' : '' }}>Garden View</option>
                                    <option value="pool" {{ old('view_type') === 'pool' ? 'selected' : '' }}>Pool View</option>
                                    <option value="city" {{ old('view_type') === 'city' ? 'selected' : '' }}>City View</option>
                                    <option value="other" {{ old('view_type') === 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('view_type')<div class="rs-error">{{ $message }}</div>@enderror
                            </div>

                        </div>

                        <div class="rs-row-2">

                            <div class="rs-field">
                                <label class="rs-label">Room Size</label>
                                <input type="number" name="size" id="size" value="{{ old('size') }}"
                                       class="rs-input @error('size') is-invalid @enderror" placeholder="e.g. 350" min="0" step="0.01">
                                @error('size')<div class="rs-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="rs-field">
                                <label class="rs-label">Size Unit</label>
                                <select name="size_unit" id="size_unit" class="rs-select @error('size_unit') is-invalid @enderror">
                                    <option value="sqft" {{ old('size_unit', 'sqft') === 'sqft' ? 'selected' : '' }}>Square Feet (sqft)</option>
                                    <option value="sqm" {{ old('size_unit') === 'sqm' ? 'selected' : '' }}>Square Meter (sqm)</option>
                                </select>
                                @error('size_unit')<div class="rs-error">{{ $message }}</div>@enderror
                            </div>

                        </div>

                    </div>

                </div>


                {{-- ROOM IMAGES --}}
                <div class="rs-card">

                    <div class="rs-card-head">
                        <div class="rs-card-icon" style="background:rgba(245,158,11,.12);color:#fcd34d;"><i class="bi bi-images"></i></div>
                        <div><h2>Room Images</h2></div>
                    </div>

                    <div class="rs-card-body">

                        <label class="rs-label">Add Room Images</label>
                        <div class="rs-help mb-2">Select one image at a time. After selecting one image, select another one. All selected images will be uploaded together.</div>

                        <div id="roomImageInputs"></div>

                        <label class="rs-file-btn">
                            <i class="bi bi-cloud-arrow-up"></i> Choose an image
                            <input type="file" id="roomImagePicker" accept=".jpg,.jpeg,.png,.webp">
                        </label>

                        <div class="rs-help">JPG, JPEG, PNG or WEBP. Maximum size 4MB per image.</div>

                        @error('images')<div class="rs-error">{{ $message }}</div>@enderror
                        @error('images.*')<div class="rs-error">{{ $message }}</div>@enderror

                        <div id="imageCount" class="rs-image-count">
                            <i class="bi bi-images"></i>
                            <span id="imageCountText">0 images selected</span>
                        </div>

                        <div id="roomImagesPreview" class="rs-image-grid"></div>

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
                        <div><h2>Settings</h2></div>
                    </div>

                    <div class="rs-card-body">

                        <div class="rs-toggle-card">
                            <div>
                                <strong><i class="bi bi-star-fill me-1" style="color:#fcd34d;"></i> Featured Room</strong>
                                <small>Show this room as a featured room.</small>
                            </div>
                            <label class="rs-switch">
                                <input type="checkbox" name="is_featured" value="1" id="is_featured" {{ old('is_featured') ? 'checked' : '' }}>
                                <span class="rs-switch-slider"></span>
                            </label>
                        </div>

                        <div class="rs-toggle-card">
                            <div>
                                <strong>Active</strong>
                                <small>Active rooms can be shown for booking.</small>
                            </div>
                            <label class="rs-switch">
                                <input type="checkbox" name="status" value="1" id="status" {{ old('status', true) ? 'checked' : '' }}>
                                <span class="rs-switch-slider"></span>
                            </label>
                        </div>

                    </div>

                </div>


                {{-- ROOM TYPE INFO --}}
                <div class="rs-card">

                    <div class="rs-card-head">
                        <div class="rs-card-icon" style="background:rgba(148,163,184,.12);color:#cbd5e1;"><i class="bi bi-info-circle"></i></div>
                        <div><h2>Room Type</h2></div>
                    </div>

                    <div class="rs-card-body">
                        <p class="rs-help mb-0" style="font-size:.8rem;">
                            Room Types are managed separately. Select the appropriate room type when creating this room.
                        </p>
                    </div>

                </div>


                {{-- ACTIONS --}}
                <div class="rs-card">

                    <div class="rs-card-body">

                        @if($resorts->count())
                            <button type="submit" class="rs-btn-primary" id="createRoomBtn">
                                <i class="bi bi-check-lg"></i> Create Room
                            </button>
                        @else
                            <a href="{{ route('vendor.resorts.create') }}" class="rs-btn-primary">
                                <i class="bi bi-plus-lg"></i> Create Resort First
                            </a>
                        @endif

                        <div class="mt-2"></div>

                        <a href="{{ route('vendor.rooms.index') }}" class="rs-btn-block-ghost">
                            Cancel
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>


{{-- =========================================================
    IMAGE SCRIPT
========================================================= --}}

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const picker = document.getElementById('roomImagePicker');
    const preview = document.getElementById('roomImagesPreview');
    const inputsContainer = document.getElementById('roomImageInputs');
    const imageCount = document.getElementById('imageCount');
    const imageCountText = document.getElementById('imageCountText');

    let selectedFiles = [];

    picker.addEventListener('change', function () {

        const file = this.files[0];
        if (!file) return;

        const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];

        if (!allowedTypes.includes(file.type)) {
            alert('Please select a JPG, JPEG, PNG or WEBP image.');
            this.value = '';
            return;
        }

        if (file.size > 4 * 1024 * 1024) {
            alert('Image size must be less than 4MB.');
            this.value = '';
            return;
        }

        selectedFiles.push(file);
        renderImages();
        this.value = '';

    });

    function renderImages() {

        preview.innerHTML = '';
        inputsContainer.innerHTML = '';

        if (selectedFiles.length > 0) {
            imageCount.classList.add('is-visible');
            imageCountText.textContent = selectedFiles.length === 1 ? '1 image selected' : selectedFiles.length + ' images selected';
        } else {
            imageCount.classList.remove('is-visible');
        }

        selectedFiles.forEach(function (file, index) {

            const reader = new FileReader();

            reader.onload = function (event) {

                const card = document.createElement('div');
                card.className = 'rs-image-card';

                card.innerHTML = `
                    <img src="${event.target.result}" alt="Room Image ${index + 1}">
                    <div class="rs-image-card-top">
                        <span class="rs-image-tag">Image ${index + 1}</span>
                        <button type="button" class="rs-image-remove remove-room-image" data-index="${index}" title="Remove image">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                    <div class="rs-image-card-name" title="${escapeHtml(file.name)}">${escapeHtml(file.name)}</div>
                `;

                preview.appendChild(card);

            };

            reader.readAsDataURL(file);

            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);

            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'file';
            hiddenInput.name = 'images[]';
            hiddenInput.className = 'd-none';
            hiddenInput.files = dataTransfer.files;

            inputsContainer.appendChild(hiddenInput);

        });

    }

    preview.addEventListener('click', function (event) {
        const button = event.target.closest('.remove-room-image');
        if (!button) return;
        const index = Number(button.dataset.index);
        selectedFiles.splice(index, 1);
        renderImages();
    });

    function escapeHtml(value) {
        const div = document.createElement('div');
        div.textContent = value;
        return div.innerHTML;
    }

    const form = document.getElementById('roomCreateForm');
    const submitButton = document.getElementById('createRoomBtn');

    form?.addEventListener('submit', function () {
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.innerHTML = `
                <span class="spinner-border spinner-border-sm" role="status"></span>
                Creating Room...
            `;
        }
    });

});

</script>

@endpush

@endsection