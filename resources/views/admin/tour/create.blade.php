@extends('layouts.admin')
@section('title', 'Add Tour Package')
@section('page')

<style>
    .page-header {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 60%, #0f3460 100%);
        border-radius: 16px;
        padding: 1.6rem 2rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .page-header-left h4 {
        color: #fff;
        font-weight: 700;
        margin: 0 0 .2rem;
        font-size: 1.2rem;
    }
    .page-header-left p { color: rgba(255,255,255,.55); margin: 0; font-size: .85rem; }
    .btn-back {
        background: rgba(255,255,255,.12);
        color: #fff;
        border: 1.5px solid rgba(255,255,255,.2);
        border-radius: 10px;
        padding: .45rem 1.1rem;
        font-size: .85rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        text-decoration: none;
        transition: background .2s;
    }
    .btn-back:hover { background: rgba(255,255,255,.22); color: #fff; }

    /* ── Form Card ── */
    .form-card {
        background: #fff;
        border: 1.5px solid #e3e5ef;
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 1.2rem;
    }
    .form-card-header {
        background: #f6f7fb;
        border-bottom: 1.5px solid #e3e5ef;
        padding: .9rem 1.4rem;
        display: flex;
        align-items: center;
        gap: .5rem;
    }
    .form-card-header-icon {
        width: 30px; height: 30px;
        border-radius: 8px;
        background: linear-gradient(135deg, #0f3460, #1a73e8);
        display: flex; align-items: center; justify-content: center;
        color: #fff;
        font-size: .75rem;
        flex-shrink: 0;
    }
    .form-card-header span {
        font-size: .8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .7px;
        color: #2d3050;
    }
    .form-card-body { padding: 1.4rem; }

    /* ── Labels & Inputs ── */
    .form-label-custom {
        font-size: .8rem;
        font-weight: 600;
        color: #4a4f6a;
        text-transform: uppercase;
        letter-spacing: .6px;
        margin-bottom: .35rem;
        display: block;
    }
    .form-control-custom {
        border: 1.5px solid #dde0ef;
        border-radius: 10px;
        font-size: .9rem;
        padding: .6rem .9rem;
        color: #2d3050;
        background: #fff;
        width: 100%;
        transition: border-color .2s, box-shadow .2s;
    }
    .form-control-custom:focus {
        border-color: #0f3460;
        box-shadow: 0 0 0 3px rgba(15,52,96,.1);
        outline: none;
    }
    textarea.form-control-custom { resize: vertical; }

    /* ── Required star ── */
    .req { color: #e53935; margin-left: 2px; }

    /* ── Image upload box ── */
    .upload-box {
        border: 2px dashed #c8ccdf;
        border-radius: 12px;
        padding: 1.4rem;
        text-align: center;
        background: #f8f9fd;
        cursor: pointer;
        transition: border-color .2s, background .2s;
        position: relative;
    }
    .upload-box:hover { border-color: #0f3460; background: #eef1fb; }
    .upload-box input[type="file"] {
        position: absolute; inset: 0;
        opacity: 0; cursor: pointer; width: 100%; height: 100%;
    }
    .upload-box i { font-size: 1.6rem; color: #a0a4bf; margin-bottom: .4rem; display: block; }
    .upload-box p { margin: 0; font-size: .85rem; color: #7a7f9a; }
    .upload-box strong { color: #0f3460; }
    #imagePreview {
        margin-top: 1rem;
        display: none;
        border-radius: 10px;
        overflow: hidden;
        border: 2px solid #e3e5ef;
    }
    #imagePreview img { width: 100%; max-height: 180px; object-fit: cover; display: block; }

    /* ── Toggle chips (Featured / Status) ── */
    .chip-group { display: flex; gap: .5rem; }
    .chip-group input[type="radio"] { display: none; }
    .chip-group label {
        padding: .45rem 1.1rem;
        border-radius: 20px;
        font-size: .82rem;
        font-weight: 600;
        cursor: pointer;
        border: 1.5px solid #dde0ef;
        color: #7a7f9a;
        background: #f6f7fb;
        transition: all .2s;
        user-select: none;
    }
    .chip-group input[type="radio"]:checked + label {
        background: linear-gradient(135deg, #0f3460, #1a73e8);
        color: #fff;
        border-color: transparent;
    }

    /* ── Sticky footer bar ── */
    .form-footer-bar {
        position: sticky;
        bottom: 0;
        z-index: 50;
        background: #fff;
        border-top: 1.5px solid #e3e5ef;
        padding: 1rem 0;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: .7rem;
        margin-top: 1.5rem;
    }
    .btn-save {
        background: linear-gradient(135deg, #0f3460, #1a73e8);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: .6rem 1.8rem;
        font-weight: 700;
        font-size: .95rem;
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        transition: opacity .2s, transform .2s;
        cursor: pointer;
    }
    .btn-save:hover { opacity: .88; transform: translateY(-1px); }
    .btn-reset {
        background: #f0f1f7;
        color: #4a4f6a;
        border: none;
        border-radius: 10px;
        padding: .6rem 1.4rem;
        font-weight: 600;
        font-size: .9rem;
        cursor: pointer;
        transition: background .2s;
    }
    .btn-reset:hover { background: #e0e2ef; }
</style>

{{-- Page Header --}}
<div class="page-header">
    <div class="page-header-left">
        <h4><i class="fas fa-map-marked-alt me-2"></i>Add New Tour Package</h4>
        <p>Fill in the details to create a new tour listing</p>
    </div>
    <a href="{{ route('admin.tours.index') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> All Packages
    </a>
</div>

<form method="POST" action="{{ request()->routeIs('vendor.*')
            ? route('vendor.tours.store')
            : route('admin.tours.store') }}"
      enctype="multipart/form-data">
    @csrf

    <div class="row g-4">

        {{-- ── LEFT COLUMN ── --}}
        <div class="col-lg-8">

            {{-- Basic Info --}}
            <div class="form-card">
                <div class="form-card-header">
                    <div class="form-card-header-icon"><i class="fas fa-info"></i></div>
                    <span>Basic Information</span>
                </div>
                <div class="form-card-body">
                    <div class="row g-3">

                        <div class="col-md-12">
                            <label class="form-label-custom">Tour Title <span class="req">*</span></label>
                            <input type="text" name="title" class="form-control-custom"
                                   value="{{ old('title') }}"
                                   placeholder="e.g. Cox's Bazar Beach Tour">
                            @error('title')<small class="text-danger d-block mt-1">{{ $message }}</small>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Destination <span class="req">*</span></label>
                            <select name="destination_id" class="form-control-custom">
                                <option value="">— Select Destination —</option>
                                @foreach($destinations as $destination)
                                    <option value="{{ $destination->id }}"
                                        {{ old('destination_id') == $destination->id ? 'selected' : '' }}>
                                        {{ $destination->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('destination_id')<small class="text-danger d-block mt-1">{{ $message }}</small>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Location</label>
                            <input type="text" name="location" class="form-control-custom"
                                   value="{{ old('location') }}"
                                   placeholder="e.g. Chittagong, Bangladesh">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Duration</label>
                            <input type="text" name="duration" class="form-control-custom"
                                   value="{{ old('duration') }}"
                                   placeholder="3 Days 2 Nights">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label-custom">Maximum Seat</label>
                            <input type="number" name="max_seat" class="form-control-custom"
                                   value="{{ old('max_seat') }}"
                                   placeholder="e.g. 20">
                        </div>

                    </div>
                </div>
            </div>

            {{-- Descriptions --}}
            <div class="form-card">
                <div class="form-card-header">
                    <div class="form-card-header-icon"><i class="fas fa-align-left"></i></div>
                    <span>Descriptions</span>
                </div>
                <div class="form-card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label-custom">Short Description</label>
                            <textarea name="short_description" rows="2" class="form-control-custom"
                                      placeholder="A brief summary shown in listing cards...">{{ old('short_description') }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label-custom">Full Description</label>
                            <textarea name="description" rows="5" class="form-control-custom"
                                      placeholder="Complete details about this tour...">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Included & Excluded --}}
            <div class="form-card">
                <div class="form-card-header">
                    <div class="form-card-header-icon"><i class="fas fa-list-check"></i></div>
                    <span>Included & Excluded</span>
                </div>
                <div class="form-card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">✅ Included</label>
                            <textarea name="included" rows="5" class="form-control-custom"
                                      placeholder="• Hotel accommodation&#10;• Breakfast & dinner&#10;• Transport...">{{ old('included') }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">❌ Excluded</label>
                            <textarea name="excluded" rows="5" class="form-control-custom"
                                      placeholder="• Airfare&#10;• Personal expenses&#10;• Visa fees...">{{ old('excluded') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tour Plan --}}
            <div class="form-card">
                <div class="form-card-header">
                    <div class="form-card-header-icon"><i class="fas fa-route"></i></div>
                    <span>Tour Plan / Itinerary</span>
                </div>
                <div class="form-card-body">
                    <textarea name="tour_plan" rows="6" class="form-control-custom"
                              placeholder="Day 1: Arrival & hotel check-in...&#10;Day 2: Sightseeing...">{{ old('tour_plan') }}</textarea>
                </div>
            </div>

            {{-- Google Map --}}
            <div class="form-card">
                <div class="form-card-header">
                    <div class="form-card-header-icon"><i class="fas fa-map-pin"></i></div>
                    <span>Google Map</span>
                </div>
                <div class="form-card-body">
                    <label class="form-label-custom">Embed iframe Code</label>
                    <textarea name="map_iframe" rows="3" class="form-control-custom"
                              placeholder='<iframe src="https://maps.google.com/..." ...></iframe>'>{{ old('map_iframe') }}</textarea>
                </div>
            </div>

        </div>

        {{-- ── RIGHT COLUMN ── --}}
        <div class="col-lg-4">

            {{-- Pricing --}}
            <div class="form-card">
                <div class="form-card-header">
                    <div class="form-card-header-icon"><i class="fas fa-tag"></i></div>
                    <span>Pricing</span>
                </div>
                <div class="form-card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label-custom">Regular Price <span class="req">*</span></label>
                            <div style="position:relative;">
                                <span style="position:absolute;left:.85rem;top:50%;transform:translateY(-50%);color:#7a7f9a;font-weight:700;">৳</span>
                                <input type="number" step="0.01" name="price"
                                       class="form-control-custom"
                                       style="padding-left:2rem;"
                                       value="{{ old('price') }}"
                                       placeholder="0.00">
                            </div>
                            @error('price')<small class="text-danger d-block mt-1">{{ $message }}</small>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label-custom">Discount Price</label>
                            <div style="position:relative;">
                                <span style="position:absolute;left:.85rem;top:50%;transform:translateY(-50%);color:#7a7f9a;font-weight:700;">৳</span>
                                <input type="number" step="0.01" name="discount_price"
                                       class="form-control-custom"
                                       style="padding-left:2rem;"
                                       value="{{ old('discount_price') }}"
                                       placeholder="0.00">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Featured Image --}}
            <div class="form-card">
                <div class="form-card-header">
                    <div class="form-card-header-icon"><i class="fas fa-image"></i></div>
                    <span>Featured Image</span>
                </div>
                <div class="form-card-body">
                    <div class="upload-box" id="uploadBox">
                        <input type="file" name="featured_image" accept="image/*"
                               onchange="previewImage(event)">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p><strong>Click to upload</strong> or drag & drop</p>
                        <p style="font-size:.78rem; margin-top:.25rem;">JPG, PNG, WEBP — max 2MB</p>
                    </div>
                    <div id="imagePreview">
                        <img id="previewImg" src="" alt="Preview">
                    </div>
                    @error('featured_image')<small class="text-danger d-block mt-1">{{ $message }}</small>@enderror
                </div>
            </div>

{{-- AI Planner Settings --}}
<div class="form-card mt-4">
    <div class="form-card-header">
        <div class="form-card-header-icon">
            <i class="fas fa-robot"></i>
        </div>
        <span>AI Planner Settings</span>
    </div>

    <div class="form-card-body">

        <div class="row g-3">

            {{-- Hotel Name --}}
            <div class="col-12">
                <label class="form-label-custom">
                    Hotel Name
                </label>

                <input
                    type="text"
                    name="hotel_name"
                    class="form-control-custom"
                    value="{{ old('hotel_name') }}"
                    placeholder="e.g. Grand Sultan Resort"
                >
            </div>

            {{-- Food Menu --}}
            <div class="col-12">
                <label class="form-label-custom">
                    Food Menu
                </label>

                <textarea
                    name="food_menu"
                    rows="3"
                    class="form-control-custom"
                    placeholder="Breakfast&#10;Lunch&#10;Dinner"
                >{{ old('food_menu') }}</textarea>
            </div>

            {{-- Backpack Price --}}
            <div class="col-12">
                <label class="form-label-custom">
                    Backpack Price
                </label>

                <div style="position:relative;">
                    <span style="position:absolute;left:.85rem;top:50%;transform:translateY(-50%);color:#7a7f9a;font-weight:700;">
                        ৳
                    </span>

                    <input
                        type="number"
                        step="0.01"
                        name="backpack_price"
                        class="form-control-custom"
                        style="padding-left:2rem;"
                        value="{{ old('backpack_price',0) }}"
                        placeholder="0.00"
                    >
                </div>
            </div>

            {{-- Moderate Price --}}
            <div class="col-12">
                <label class="form-label-custom">
                    Moderate Price
                </label>

                <div style="position:relative;">
                    <span style="position:absolute;left:.85rem;top:50%;transform:translateY(-50%);color:#7a7f9a;font-weight:700;">
                        ৳
                    </span>

                    <input
                        type="number"
                        step="0.01"
                        name="moderate_price"
                        class="form-control-custom"
                        style="padding-left:2rem;"
                        value="{{ old('moderate_price',0) }}"
                        placeholder="0.00"
                    >
                </div>
            </div>

            {{-- Luxury Price --}}
            <div class="col-12">
                <label class="form-label-custom">
                    Luxury Price
                </label>

                <div style="position:relative;">
                    <span style="position:absolute;left:.85rem;top:50%;transform:translateY(-50%);color:#7a7f9a;font-weight:700;">
                        ৳
                    </span>

                    <input
                        type="number"
                        step="0.01"
                        name="luxury_price"
                        class="form-control-custom"
                        style="padding-left:2rem;"
                        value="{{ old('luxury_price',0) }}"
                        placeholder="0.00"
                    >
                </div>
            </div>

            {{-- AI Highlights --}}
            <div class="col-12">
                <label class="form-label-custom">
                    AI Highlights
                </label>

                <textarea
                    name="ai_highlights"
                    rows="5"
                    class="form-control-custom"
                    placeholder="Luxury Resort&#10;Sea View Room&#10;Private Transport&#10;Local Guide&#10;Free Breakfast"
                >{{ old('ai_highlights') }}</textarea>

                <small class="text-muted">
                    One highlight per line.
                </small>
            </div>

        </div>

    </div>
</div>
            {{-- Visibility --}}
            <div class="form-card">
                <div class="form-card-header">
                    <div class="form-card-header-icon"><i class="fas fa-sliders-h"></i></div>
                    <span>Visibility Settings</span>
                </div>
                <div class="form-card-body">
                    <div class="row g-3">

                        <div class="col-12">
                            <label class="form-label-custom">Status</label>
                            <div class="chip-group">
                                <input type="radio" name="status" id="statusActive" value="1"
                                       {{ old('status', '1') == '1' ? 'checked' : '' }}>
                                <label for="statusActive">Active</label>

                                <input type="radio" name="status" id="statusInactive" value="0"
                                       {{ old('status') == '0' ? 'checked' : '' }}>
                                <label for="statusInactive">Inactive</label>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label-custom">Featured?</label>
                            <div class="chip-group">
                                <input type="radio" name="is_featured" id="featuredYes" value="1"
                                       {{ old('is_featured') == '1' ? 'checked' : '' }}>
                                <label for="featuredYes">⭐ Yes</label>

                                <input type="radio" name="is_featured" id="featuredNo" value="0"
                                       {{ old('is_featured', '0') == '0' ? 'checked' : '' }}>
                                <label for="featuredNo">No</label>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

    </div>

    {{-- Sticky Footer --}}
    <div class="form-footer-bar">
        <button type="reset" class="btn-reset">
            <i class="fas fa-undo me-1"></i> Reset
        </button>
        <button type="submit" class="btn-save">
            <i class="fas fa-save"></i> Save Tour Package
        </button>
    </div>

</form>

@endsection

@push('scripts')
<script>
function previewImage(event) {
    const file = event.target.files[0];
    if (!file) return;

    const preview = document.getElementById('imagePreview');
    const img     = document.getElementById('previewImg');

    const reader = new FileReader();
    reader.onload = e => {
        img.src = e.target.result;
        preview.style.display = 'block';
    };
    reader.readAsDataURL(file);
}
</script>
@endpush