@extends('layouts.vendor')

@section('page')

<div class="container-fluid py-4">

    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h4 class="fw-bold mb-1">
                Create New Resort
            </h4>

            <p class="text-muted mb-0">
                Add your resort information and publish it on the platform.
            </p>
        </div>

        <a href="{{ route('vendor.resorts.index') }}"
           class="btn btn-light border">

            <i class="bi bi-arrow-left me-1"></i>

            Back to Resorts

        </a>

    </div>


    {{-- =========================================================
        VALIDATION ERRORS
    ========================================================== --}}

    @if($errors->any())

        <div class="alert alert-danger">

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

        <div class="alert alert-success">

            <i class="bi bi-check-circle me-1"></i>

            {{ session('success') }}

        </div>

    @endif


    {{-- =========================================================
        FORM
    ========================================================== --}}

    <form
        action="{{ route('vendor.resorts.store') }}"
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
                            Enter the basic details of your resort.
                        </small>

                    </div>


                    <div class="card-body">


                        {{-- Destination --}}

                        <div class="mb-3">

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

                                @foreach($destinations ?? [] as $destination)

                                    <option
                                        value="{{ $destination->id }}"
                                        @selected(old('destination_id') == $destination->id)
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


                        {{-- Resort Name --}}

                        <div class="mb-3">

                            <label class="form-label fw-semibold">

                                Resort Name

                                <span class="text-danger">*</span>

                            </label>

                            <input
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                class="form-control @error('name') is-invalid @enderror"
                                placeholder="Example: Sea Pearl Beach Resort"
                                required
                            >

                            @error('name')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Slug --}}

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Slug
                            </label>

                            <input
                                type="text"
                                name="slug"
                                value="{{ old('slug') }}"
                                class="form-control @error('slug') is-invalid @enderror"
                                placeholder="sea-pearl-beach-resort"
                            >

                            <small class="text-muted">

                                Leave empty if you want the system
                                to generate it automatically.

                            </small>

                            @error('slug')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Short Description --}}

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Short Description
                            </label>

                            <textarea
                                name="short_description"
                                rows="3"
                                class="form-control @error('short_description') is-invalid @enderror"
                                placeholder="Write a short description about your resort..."
                            >{{ old('short_description') }}</textarea>

                            @error('short_description')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- Full Description --}}

                        <div>

                            <label class="form-label fw-semibold">
                                Full Description
                            </label>

                            <textarea
                                name="description"
                                rows="7"
                                class="form-control @error('description') is-invalid @enderror"
                                placeholder="Describe your resort in detail..."
                            >{{ old('description') }}</textarea>

                            @error('description')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    LOCATION
                ================================================== --}}

                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white border-0 py-3">

                        <h5 class="fw-bold mb-1">
                            Location Information
                        </h5>

                        <small class="text-muted">
                            Add the resort's location and map information.
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
                                    value="{{ old('division') }}"
                                    class="form-control @error('division') is-invalid @enderror"
                                    placeholder="Khulna"
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
                                    value="{{ old('district') }}"
                                    class="form-control @error('district') is-invalid @enderror"
                                    placeholder="Khulna"
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
                                    value="{{ old('area') }}"
                                    class="form-control @error('area') is-invalid @enderror"
                                    placeholder="Dacope"
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
                                    placeholder="Enter complete resort address..."
                                >{{ old('address') }}</textarea>

                                @error('address')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Google Map --}}

                            <div class="col-12">

                                <label class="form-label fw-semibold">
                                    Google Map URL
                                </label>

                                <input
                                    type="url"
                                    name="google_map"
                                    value="{{ old('google_map') }}"
                                    class="form-control @error('google_map') is-invalid @enderror"
                                    placeholder="https://maps.google.com/..."
                                >

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
                                    value="{{ old('latitude') }}"
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
                                    value="{{ old('longitude') }}"
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
                    CHECK IN / CHECK OUT
                ================================================== --}}

                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white border-0 py-3">

                        <h5 class="fw-bold mb-1">
                            Check-in & Check-out
                        </h5>

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
                                    value="{{ old('check_in') }}"
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
                                    value="{{ old('check_out') }}"
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
                    RESORT FACILITIES
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
                                                        old('facilities', [])
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
                    SEO
                ================================================== --}}

                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white border-0 py-3">

                        <h5 class="fw-bold mb-1">
                            SEO Information
                        </h5>

                        <small class="text-muted">
                            Optional information for search engines.
                        </small>

                    </div>


                    <div class="card-body">


                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Meta Title
                            </label>

                            <input
                                type="text"
                                name="meta_title"
                                value="{{ old('meta_title') }}"
                                class="form-control @error('meta_title') is-invalid @enderror"
                                maxlength="255"
                                placeholder="Resort SEO title"
                            >

                            @error('meta_title')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <div>

                            <label class="form-label fw-semibold">
                                Meta Description
                            </label>

                            <textarea
                                name="meta_description"
                                rows="4"
                                class="form-control @error('meta_description') is-invalid @enderror"
                                placeholder="SEO description..."
                            >{{ old('meta_description') }}</textarea>

                            @error('meta_description')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>

                </div>

            </div>


            {{-- =================================================
                RIGHT COLUMN
            ================================================== --}}

            <div class="col-xl-4">


                {{-- =================================================
                    PUBLISH SETTINGS
                ================================================== --}}

                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white border-0 py-3">

                        <h5 class="fw-bold mb-0">
                            Publish Settings
                        </h5>

                    </div>


                    <div class="card-body">


                        {{-- Featured --}}

                        <div class="form-check mb-3">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="is_featured"
                                value="1"
                                id="is_featured"
                                @checked(old('is_featured'))
                            >

                            <label
                                class="form-check-label fw-semibold"
                                for="is_featured"
                            >

                                <i class="bi bi-star me-1"></i>

                                Mark as Featured

                            </label>

                        </div>


                        {{-- Verified --}}

                        <div class="form-check">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="is_verified"
                                value="1"
                                id="is_verified"
                                @checked(old('is_verified'))
                            >

                            <label
                                class="form-check-label fw-semibold"
                                for="is_verified"
                            >

                                <i class="bi bi-patch-check me-1"></i>

                                Mark as Verified

                            </label>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    FEATURED IMAGE
                ================================================== --}}

                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white border-0 py-3">

                        <h5 class="fw-bold mb-1">
                            Featured Image
                        </h5>

                        <small class="text-muted">
                            Main image of your resort.
                        </small>

                    </div>


                    <div class="card-body">

                        <input
                            type="file"
                            name="featured_image"
                            class="form-control @error('featured_image') is-invalid @enderror"
                            accept="image/*"
                        >

                        @error('featured_image')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                        <div class="form-text">
                            Recommended: JPG, PNG or WebP. Maximum 2MB.
                        </div>

                    </div>

                </div>


                {{-- =================================================
                    RESORT GALLERY
                ================================================== --}}

                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white border-0 py-3">

                        <h5 class="fw-bold mb-1">
                            Resort Gallery
                        </h5>

                        <small class="text-muted">
                            Upload multiple images of your resort.
                        </small>

                    </div>


                    <div class="card-body">

                        <input
                            type="file"
                            name="images[]"
                            class="form-control @error('images') is-invalid @enderror"
                            accept="image/*"
                            multiple
                        >

                        @error('images')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror


                        @error('images.*')

                            <div class="text-danger small mt-2">
                                {{ $message }}
                            </div>

                        @enderror


                        <div class="form-text mt-2">

                            <i class="bi bi-images me-1"></i>

                            You can select multiple images at once.

                            The first gallery image will be marked as
                            the gallery cover automatically.

                            Maximum 4MB per image.

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    SUBMIT
                ================================================== --}}

                <div class="card border-0 shadow-sm">

                    <div class="card-body">

                        <button
                            type="submit"
                            class="btn btn-primary w-100 mb-2"
                        >

                            <i class="bi bi-check-circle me-1"></i>

                            Create Resort

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

</div>

@endsection