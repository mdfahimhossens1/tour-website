@extends('layouts.vendor')

@section('page')

<div class="container-fluid py-4">

{{-- =========================================================
    HEADER
========================================================== --}}
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

    <div>
        <h4 class="fw-bold mb-1">
            Edit Resort
        </h4>

        <p class="text-muted mb-0">
            Update your resort information, location, images and settings.
        </p>
    </div>

    <a
        href="{{ route('vendor.resorts.index') }}"
        class="btn btn-light border"
    >
        <i class="bi bi-arrow-left me-1"></i>
        Back to Resorts
    </a>

</div>


{{-- =========================================================
    VALIDATION ERRORS
========================================================== --}}
@if($errors->any())

    <div class="alert alert-danger border-0 shadow-sm">

        <div class="fw-bold mb-2">
            <i class="bi bi-exclamation-triangle me-1"></i>
            Please fix the following errors:
        </div>

        <ul class="mb-0">

            @foreach($errors->all() as $error)

                <li>{{ $error }}</li>

            @endforeach

        </ul>

    </div>

@endif


{{-- =========================================================
    SUCCESS MESSAGE
========================================================== --}}
@if(session('success'))

    <div class="alert alert-success border-0 shadow-sm">

        <i class="bi bi-check-circle me-1"></i>

        {{ session('success') }}

    </div>

@endif


{{-- =========================================================
    MAIN UPDATE FORM
========================================================== --}}
<form
    action="{{ route('vendor.resorts.update', $resort->slug) }}"
    method="POST"
    enctype="multipart/form-data"
>

    @csrf


    <div class="row g-4">

        {{-- =================================================
            LEFT COLUMN
        ================================================== --}}
        <div class="col-xl-8">


            {{-- =================================================
                BASIC INFORMATION
            ================================================== --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="fw-bold mb-1">
                        Basic Information
                    </h5>

                    <small class="text-muted">
                        Update the basic information of your resort.
                    </small>

                </div>

                <div class="card-body">

                    <div class="row g-3">

                        {{-- Resort Name --}}
                        <div class="col-md-8">

                            <label class="form-label fw-semibold">
                                Resort Name
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                name="name"
                                value="{{ old('name', $resort->name) }}"
                                class="form-control @error('name') is-invalid @enderror"
                                placeholder="Enter resort name"
                                required
                            >

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Destination --}}
                        <div class="col-md-4">

                            <label class="form-label fw-semibold">
                                Destination
                            </label>

                            <select
                                name="destination_id"
                                class="form-select @error('destination_id') is-invalid @enderror"
                            >

                                <option value="">
                                    Select Destination
                                </option>

                                @foreach($destinations as $destination)

                                    <option
                                        value="{{ $destination->id }}"
                                        @selected(
                                            old(
                                                'destination_id',
                                                $resort->destination_id
                                            ) == $destination->id
                                        )
                                    >
                                        {{ $destination->name }}
                                    </option>

                                @endforeach

                            </select>

                            @error('destination_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Slug --}}
                        <div class="col-12">

                            <label class="form-label fw-semibold">
                                Slug
                            </label>

                            <input
                                type="text"
                                name="slug"
                                value="{{ old('slug', $resort->slug) }}"
                                class="form-control @error('slug') is-invalid @enderror"
                                placeholder="resort-slug"
                            >

                            <small class="text-muted">
                                Leave blank to generate automatically from the resort name.
                            </small>

                            @error('slug')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Short Description --}}
                        <div class="col-12">

                            <label class="form-label fw-semibold">
                                Short Description
                            </label>

                            <textarea
                                name="short_description"
                                rows="3"
                                class="form-control @error('short_description') is-invalid @enderror"
                                placeholder="Short description about the resort"
                            >{{ old('short_description', $resort->short_description) }}</textarea>

                            @error('short_description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Full Description --}}
                        <div class="col-12">

                            <label class="form-label fw-semibold">
                                Full Description
                            </label>

                            <textarea
                                name="description"
                                rows="7"
                                class="form-control @error('description') is-invalid @enderror"
                                placeholder="Write detailed resort description..."
                            >{{ old('description', $resort->description) }}</textarea>

                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                LOCATION INFORMATION
            ================================================== --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="fw-bold mb-1">
                        Location Information
                    </h5>

                    <small class="text-muted">
                        Update your resort's location and map information.
                    </small>

                </div>

                <div class="card-body">

                    <div class="row g-3">

                        {{-- Division --}}
                        <div class="col-md-4">

                            <label class="form-label fw-semibold">
                                Division
                            </label>

                            <input
                                type="text"
                                name="division"
                                value="{{ old('division', $resort->division) }}"
                                class="form-control @error('division') is-invalid @enderror"
                                placeholder="e.g. Khulna"
                            >

                            @error('division')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- District --}}
                        <div class="col-md-4">

                            <label class="form-label fw-semibold">
                                District
                            </label>

                            <input
                                type="text"
                                name="district"
                                value="{{ old('district', $resort->district) }}"
                                class="form-control @error('district') is-invalid @enderror"
                                placeholder="e.g. Khulna"
                            >

                            @error('district')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Area --}}
                        <div class="col-md-4">

                            <label class="form-label fw-semibold">
                                Area
                            </label>

                            <input
                                type="text"
                                name="area"
                                value="{{ old('area', $resort->area) }}"
                                class="form-control @error('area') is-invalid @enderror"
                                placeholder="Area / Upazila"
                            >

                            @error('area')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Address --}}
                        <div class="col-12">

                            <label class="form-label fw-semibold">
                                Full Address
                            </label>

                            <textarea
                                name="address"
                                rows="3"
                                class="form-control @error('address') is-invalid @enderror"
                                placeholder="Full resort address"
                            >{{ old('address', $resort->address) }}</textarea>

                            @error('address')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Google Map --}}
                        <div class="col-12">

                            <label class="form-label fw-semibold">
                                Google Map URL / Embed Code
                            </label>

                            <textarea
                                name="google_map"
                                rows="4"
                                class="form-control @error('google_map') is-invalid @enderror"
                                placeholder="Paste Google Maps URL or embed code"
                            >{{ old('google_map', $resort->google_map) }}</textarea>

                            @error('google_map')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Latitude --}}
                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Latitude
                            </label>

                            <input
                                type="number"
                                step="any"
                                name="latitude"
                                value="{{ old('latitude', $resort->latitude) }}"
                                class="form-control @error('latitude') is-invalid @enderror"
                                placeholder="22.8456"
                            >

                            @error('latitude')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Longitude --}}
                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Longitude
                            </label>

                            <input
                                type="number"
                                step="any"
                                name="longitude"
                                value="{{ old('longitude', $resort->longitude) }}"
                                class="form-control @error('longitude') is-invalid @enderror"
                                placeholder="89.5403"
                            >

                            @error('longitude')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                FACILITIES
            ================================================== --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="fw-bold mb-1">
                        Resort Facilities
                    </h5>

                    <small class="text-muted">
                        Select the facilities available at this resort.
                    </small>

                </div>

                <div class="card-body">

                    @php

                        $oldFacilities = old(
                            'facilities',
                            $resort->facilities
                                ->pluck('id')
                                ->toArray()
                        );

                    @endphp

                    @if($facilities->count())

                        <div class="row g-3">

                            @foreach($facilities as $facility)

                                <div class="col-md-6 col-lg-4">

                                    <div class="form-check border rounded p-3 h-100">

                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            name="facilities[]"
                                            value="{{ $facility->id }}"
                                            id="facility_{{ $facility->id }}"
                                            @checked(
                                                in_array(
                                                    $facility->id,
                                                    $oldFacilities
                                                )
                                            )
                                        >

                                        <label
                                            class="form-check-label ms-2"
                                            for="facility_{{ $facility->id }}"
                                        >

                                            <div class="d-flex align-items-center gap-2">

                                                @if($facility->icon)
                                                    <i class="{{ $facility->icon }}"></i>
                                                @else
                                                    <i class="bi bi-check-circle"></i>
                                                @endif

                                                <span class="fw-semibold">
                                                    {{ $facility->name }}
                                                </span>

                                            </div>

                                        </label>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    @else

                        <div class="alert alert-info mb-0">

                            <i class="bi bi-info-circle me-1"></i>

                            No active resort facilities are available yet.

                        </div>

                    @endif

                </div>

            </div>


            {{-- =================================================
                CHECK IN / CHECK OUT
            ================================================== --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="fw-bold mb-1">
                        Check-in / Check-out
                    </h5>

                    <small class="text-muted">
                        Configure your resort's standard check-in and check-out times.
                    </small>

                </div>

                <div class="card-body">

                    <div class="row g-3">

                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Check-in Time
                            </label>

                            <input
                                type="time"
                                name="check_in"
                                value="{{ old('check_in', $resort->check_in) }}"
                                class="form-control @error('check_in') is-invalid @enderror"
                            >

                            @error('check_in')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Check-out Time
                            </label>

                            <input
                                type="time"
                                name="check_out"
                                value="{{ old('check_out', $resort->check_out) }}"
                                class="form-control @error('check_out') is-invalid @enderror"
                            >

                            @error('check_out')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                IMAGES
            ================================================== --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="fw-bold mb-1">
                        Resort Images
                    </h5>

                    <small class="text-muted">
                        Replace featured/cover images or add new gallery images.
                    </small>

                </div>

                <div class="card-body">

                    <div class="row g-4">

                        {{-- Featured Image --}}
                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Featured Image
                            </label>

                            @if($resort->featured_image)

                                <div class="mb-3">

                                    <img
                                        src="{{ asset('storage/' . $resort->featured_image) }}"
                                        alt="{{ $resort->name }}"
                                        class="img-fluid rounded border"
                                        style="
                                            width:100%;
                                            height:220px;
                                            object-fit:cover;
                                        "
                                    >

                                </div>

                            @endif

                            <input
                                type="file"
                                name="featured_image"
                                class="form-control @error('featured_image') is-invalid @enderror"
                                accept="image/jpeg,image/png,image/webp"
                            >

                            <small class="text-muted">
                                JPG, JPEG, PNG or WEBP. Maximum 2MB.
                            </small>

                            @error('featured_image')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Cover Image --}}
                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Cover Image
                            </label>

                            @if($resort->cover_image)

                                <div class="mb-3">

                                    <img
                                        src="{{ asset('storage/' . $resort->cover_image) }}"
                                        alt="{{ $resort->name }}"
                                        class="img-fluid rounded border"
                                        style="
                                            width:100%;
                                            height:220px;
                                            object-fit:cover;
                                        "
                                    >

                                </div>

                            @endif

                            <input
                                type="file"
                                name="cover_image"
                                class="form-control @error('cover_image') is-invalid @enderror"
                                accept="image/jpeg,image/png,image/webp"
                            >

                            <small class="text-muted">
                                JPG, JPEG, PNG or WEBP. Maximum 4MB.
                            </small>

                            @error('cover_image')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- New Gallery Images --}}
                        <div class="col-12">

                            <hr>

                            <label class="form-label fw-semibold">
                                Add New Gallery Images
                            </label>

                            <input
                                type="file"
                                name="images[]"
                                class="form-control @error('images') is-invalid @enderror"
                                accept="image/jpeg,image/png,image/webp"
                                multiple
                            >

                            <small class="text-muted">
                                You can select multiple images. Maximum 4MB per image.
                            </small>

                            @error('images')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            @error('images.*')
                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                SEO
            ================================================== --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="fw-bold mb-1">
                        SEO Information
                    </h5>

                    <small class="text-muted">
                        Optional search engine optimization information.
                    </small>

                </div>

                <div class="card-body">

                    <div class="row g-3">

                        <div class="col-12">

                            <label class="form-label fw-semibold">
                                Meta Title
                            </label>

                            <input
                                type="text"
                                name="meta_title"
                                value="{{ old('meta_title', $resort->meta_title) }}"
                                maxlength="255"
                                class="form-control @error('meta_title') is-invalid @enderror"
                                placeholder="SEO title"
                            >

                            @error('meta_title')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        <div class="col-12">

                            <label class="form-label fw-semibold">
                                Meta Description
                            </label>

                            <textarea
                                name="meta_description"
                                rows="4"
                                maxlength="1000"
                                class="form-control @error('meta_description') is-invalid @enderror"
                                placeholder="SEO description"
                            >{{ old('meta_description', $resort->meta_description) }}</textarea>

                            @error('meta_description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =================================================
            RIGHT COLUMN
        ================================================== --}}
        <div class="col-xl-4">

            {{-- Resort Settings --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="fw-bold mb-1">
                        Resort Settings
                    </h5>

                    <small class="text-muted">
                        Manage resort visibility and status.
                    </small>

                </div>

                <div class="card-body">

                    {{-- Rating --}}
                    <div class="mb-4">

                        <label class="form-label fw-semibold">
                            Rating
                        </label>

                        <input
                            type="number"
                            step="0.1"
                            min="0"
                            max="5"
                            name="rating"
                            value="{{ old('rating', $resort->rating ?? 0) }}"
                            class="form-control @error('rating') is-invalid @enderror"
                            placeholder="0.0"
                        >

                        @error('rating')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Status --}}
                    <div class="mb-4">

                        <label class="form-label fw-semibold">
                            Resort Status
                        </label>

                        <select
                            name="status"
                            class="form-select @error('status') is-invalid @enderror"
                        >

                            <option
                                value="approved"
                                @selected(
                                    old(
                                        'status',
                                        $resort->status
                                    ) === 'approved'
                                )
                            >
                                Approved
                            </option>

                            <option
                                value="pending"
                                @selected(
                                    old(
                                        'status',
                                        $resort->status
                                    ) === 'pending'
                                )
                            >
                                Pending
                            </option>

                            <option
                                value="rejected"
                                @selected(
                                    old(
                                        'status',
                                        $resort->status
                                    ) === 'rejected'
                                )
                            >
                                Rejected
                            </option>

                        </select>

                        @error('status')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- Featured --}}
                    <div class="form-check form-switch mb-4">

                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="is_featured"
                            value="1"
                            id="isFeatured"
                            @checked(
                                old(
                                    'is_featured',
                                    $resort->is_featured
                                )
                            )
                        >

                        <label
                            class="form-check-label fw-semibold"
                            for="isFeatured"
                        >

                            <i class="bi bi-star me-1"></i>

                            Featured Resort

                        </label>

                    </div>


                    {{-- Verified --}}
                    <div class="form-check form-switch">

                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="is_verified"
                            value="1"
                            id="isVerified"
                            @checked(
                                old(
                                    'is_verified',
                                    $resort->is_verified
                                )
                            )
                        >

                        <label
                            class="form-check-label fw-semibold"
                            for="isVerified"
                        >

                            <i class="bi bi-patch-check me-1"></i>

                            Verified Resort

                        </label>

                    </div>

                </div>

            </div>


            {{-- Actions --}}
            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <button
                        type="submit"
                        class="btn btn-primary w-100 mb-2"
                    >

                        <i class="bi bi-check-circle me-1"></i>

                        Update Resort

                    </button>


                    <a
                        href="{{ route('vendor.resorts.index') }}"
                        class="btn btn-light border w-100"
                    >

                        Cancel

                    </a>

                </div>

            </div>

        </div>

    </div>

</form>


{{-- =========================================================
    EXISTING GALLERY
    IMPORTANT: OUTSIDE UPDATE FORM
========================================================== --}}
@if($resort->images->count())

    <div class="row mt-4">

        <div class="col-xl-8">

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white border-0 py-3">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h5 class="fw-bold mb-1">
                                Existing Gallery Images
                            </h5>

                            <small class="text-muted">
                                Remove images you no longer want to use.
                            </small>

                        </div>

                        <span class="badge bg-light text-dark border">
                            {{ $resort->images->count() }} Images
                        </span>

                    </div>

                </div>


                <div class="card-body">

                    <div class="row g-3">

                        @foreach($resort->images->sortBy('sort_order') as $image)

                            <div class="col-md-4 col-lg-3">

                                <div class="card border h-100">

                                    <img
                                        src="{{ asset('storage/' . $image->image) }}"
                                        alt="{{ $resort->name }}"
                                        class="card-img-top"
                                        style="
                                            height:160px;
                                            object-fit:cover;
                                        "
                                    >


                                    <div class="card-body p-2">

                                        @if($image->is_cover)

                                            <span class="badge bg-primary mb-2">
                                                Cover
                                            </span>

                                        @endif


                                        {{-- SEPARATE DELETE FORM --}}
                                        <form
                                            action="{{ route('vendor.resorts.images.destroy', $image->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this image?');"
                                        >

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-outline-danger w-100"
                                            >

                                                <i class="bi bi-trash me-1"></i>

                                                Delete

                                            </button>

                                        </form>

                                    </div>

                                </div>

                            </div>

                        @endforeach

                    </div>

                </div>

            </div>

        </div>

    </div>

@endif
</div>

@endsection
