@extends('layouts.vendor')

@section('title', 'Edit Room')

@section('page')

<div class="container-fluid">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>

            <h4 class="fw-bold mb-1">
                Edit Room
            </h4>

            <p class="text-muted mb-0">
                Update room information, capacity, image and settings.
            </p>

        </div>

        <div>

            <a
                href="{{ route('vendor.rooms.index') }}"
                class="btn btn-outline-secondary"
            >

                <i class="fas fa-arrow-left me-1"></i>

                Back to Rooms

            </a>

        </div>

    </div>


    {{-- =========================================================
         VALIDATION ERRORS
    ========================================================== --}}

    @if ($errors->any())

        <div class="alert alert-danger">

            <div class="fw-bold mb-2">
                Please fix the following errors:
            </div>

            <ul class="mb-0">

                @foreach ($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =========================================================
         SUCCESS MESSAGE
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- =========================================================
         ERROR MESSAGE
    ========================================================== --}}

    @if(session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif



    {{-- =========================================================
         ROOM FORM
    ========================================================== --}}

    <form
        action="{{ route('vendor.rooms.update', $room) }}"
        method="POST"
        enctype="multipart/form-data"
    >

        @csrf

        @method('PUT')


        <div class="row g-4">


            {{-- =================================================
                 LEFT COLUMN
            ================================================== --}}

            <div class="col-lg-8">


                {{-- =================================================
                     BASIC INFORMATION
                ================================================== --}}

                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-transparent py-3">

                        <h5 class="mb-0 fw-bold">

                            <i class="fas fa-bed me-2"></i>

                            Basic Information

                        </h5>

                    </div>


                    <div class="card-body">


                        {{-- RESORT --}}

                        <div class="mb-4">

                            <label class="form-label fw-semibold">

                                Resort

                            </label>


                            <input
                                type="text"
                                class="form-control"
                                value="{{ $resort->name }}"
                                readonly
                            >


                            <div class="form-text">

                                Resort cannot be changed from room edit.
                                The room remains attached to this resort.

                            </div>

                        </div>


                        {{-- ROOM TYPE + ROOM NAME --}}

                        <div class="row">


                            {{-- ROOM TYPE --}}

                            <div class="col-md-6 mb-3">

                                <label
                                    for="room_type_id"
                                    class="form-label fw-semibold"
                                >

                                    Room Type

                                </label>


                                <select
                                    name="room_type_id"
                                    id="room_type_id"
                                    class="form-select @error('room_type_id') is-invalid @enderror"
                                >

                                    <option value="">
                                        Select Room Type
                                    </option>


                                    @foreach($roomTypes as $roomType)

                                        <option
                                            value="{{ $roomType->id }}"
                                            {{ old('room_type_id', $room->room_type_id) == $roomType->id ? 'selected' : '' }}
                                        >

                                            {{ $roomType->name }}

                                        </option>

                                    @endforeach

                                </select>


                                @error('room_type_id')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>



                            {{-- ROOM NAME --}}

                            <div class="col-md-6 mb-3">

                                <label
                                    for="name"
                                    class="form-label fw-semibold"
                                >

                                    Room Name

                                    <span class="text-danger">*</span>

                                </label>


                                <input
                                    type="text"
                                    name="name"
                                    id="name"
                                    value="{{ old('name', $room->name) }}"
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="e.g. Deluxe Sea View Room"
                                    required
                                >


                                @error('name')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                        </div>



                        {{-- ROOM NUMBER + SLUG --}}

                        <div class="row">


                            {{-- ROOM NUMBER --}}

                            <div class="col-md-6 mb-3">

                                <label
                                    for="room_no"
                                    class="form-label fw-semibold"
                                >

                                    Room Number

                                </label>


                                <input
                                    type="text"
                                    name="room_no"
                                    id="room_no"
                                    value="{{ old('room_no', $room->room_no) }}"
                                    class="form-control @error('room_no') is-invalid @enderror"
                                    placeholder="e.g. 101"
                                >


                                @error('room_no')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>



                            {{-- SLUG --}}

                            <div class="col-md-6 mb-3">

                                <label
                                    for="slug"
                                    class="form-label fw-semibold"
                                >

                                    Slug

                                </label>


                                <input
                                    type="text"
                                    name="slug"
                                    id="slug"
                                    value="{{ old('slug', $room->slug) }}"
                                    class="form-control @error('slug') is-invalid @enderror"
                                    placeholder="e.g. deluxe-sea-view-room"
                                >


                                @error('slug')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror


                                <div class="form-text">

                                    Leave empty to generate automatically from room name.

                                </div>

                            </div>

                        </div>



                        {{-- EXTRA BED PRICE --}}

                        <div class="mb-3">

                            <label
                                for="extra_bed_price"
                                class="form-label fw-semibold"
                            >

                                Extra Bed Price

                            </label>


                            <div class="input-group">

                                <span class="input-group-text">
                                    ৳
                                </span>


                                <input
                                    type="number"
                                    name="extra_bed_price"
                                    id="extra_bed_price"
                                    value="{{ old('extra_bed_price', $room->extra_bed_price) }}"
                                    class="form-control @error('extra_bed_price') is-invalid @enderror"
                                    placeholder="0.00"
                                    min="0"
                                    step="0.01"
                                >

                            </div>


                            @error('extra_bed_price')

                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>



                        {{-- DESCRIPTION --}}

                        <div class="mb-3">

                            <label
                                for="description"
                                class="form-label fw-semibold"
                            >

                                Description

                            </label>


                            <textarea
                                name="description"
                                id="description"
                                rows="5"
                                class="form-control @error('description') is-invalid @enderror"
                                placeholder="Describe the room, amenities, view, sleeping arrangement etc."
                            >{{ old('description', $room->description) }}</textarea>


                            @error('description')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>

                </div>



                {{-- =================================================
                     CAPACITY & ROOM DETAILS
                ================================================== --}}

                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-transparent py-3">

                        <h5 class="mb-0 fw-bold">

                            <i class="fas fa-users me-2"></i>

                            Capacity & Room Details

                        </h5>

                    </div>


                    <div class="card-body">


                        {{-- TOTAL ROOMS + ADULT + CHILD --}}

                        <div class="row">


                            {{-- TOTAL ROOMS --}}

                            <div class="col-md-4 mb-3">

                                <label
                                    for="total_rooms"
                                    class="form-label fw-semibold"
                                >

                                    Total Rooms

                                    <span class="text-danger">*</span>

                                </label>


                                <input
                                    type="number"
                                    name="total_rooms"
                                    id="total_rooms"
                                    value="{{ old('total_rooms', $room->total_rooms) }}"
                                    class="form-control @error('total_rooms') is-invalid @enderror"
                                    min="1"
                                    required
                                >


                                @error('total_rooms')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>



                            {{-- MAX ADULT --}}

                            <div class="col-md-4 mb-3">

                                <label
                                    for="max_adult"
                                    class="form-label fw-semibold"
                                >

                                    Maximum Adults

                                    <span class="text-danger">*</span>

                                </label>


                                <input
                                    type="number"
                                    name="max_adult"
                                    id="max_adult"
                                    value="{{ old('max_adult', $room->max_adult) }}"
                                    class="form-control @error('max_adult') is-invalid @enderror"
                                    min="1"
                                    required
                                >


                                @error('max_adult')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>



                            {{-- MAX CHILD --}}

                            <div class="col-md-4 mb-3">

                                <label
                                    for="max_child"
                                    class="form-label fw-semibold"
                                >

                                    Maximum Children

                                </label>


                                <input
                                    type="number"
                                    name="max_child"
                                    id="max_child"
                                    value="{{ old('max_child', $room->max_child ?? 0) }}"
                                    class="form-control @error('max_child') is-invalid @enderror"
                                    min="0"
                                >


                                @error('max_child')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                        </div>



                        {{-- BEDS + BATHROOMS + VIEW TYPE --}}

                        <div class="row">


                            {{-- BEDS --}}

                            <div class="col-md-4 mb-3">

                                <label
                                    for="beds"
                                    class="form-label fw-semibold"
                                >

                                    Number of Beds

                                    <span class="text-danger">*</span>

                                </label>


                                <input
                                    type="number"
                                    name="beds"
                                    id="beds"
                                    value="{{ old('beds', $room->beds) }}"
                                    class="form-control @error('beds') is-invalid @enderror"
                                    min="1"
                                    required
                                >


                                @error('beds')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>



                            {{-- BATHROOMS --}}

                            <div class="col-md-4 mb-3">

                                <label
                                    for="bathrooms"
                                    class="form-label fw-semibold"
                                >

                                    Bathrooms

                                    <span class="text-danger">*</span>

                                </label>


                                <input
                                    type="number"
                                    name="bathrooms"
                                    id="bathrooms"
                                    value="{{ old('bathrooms', $room->bathrooms) }}"
                                    class="form-control @error('bathrooms') is-invalid @enderror"
                                    min="1"
                                    required
                                >


                                @error('bathrooms')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>



                            {{-- VIEW TYPE --}}

                            <div class="col-md-4 mb-3">

                                <label
                                    for="view_type"
                                    class="form-label fw-semibold"
                                >

                                    View Type

                                </label>


                                <select
                                    name="view_type"
                                    id="view_type"
                                    class="form-select @error('view_type') is-invalid @enderror"
                                >

                                    <option value="">
                                        Select View
                                    </option>


                                    <option
                                        value="sea"
                                        {{ old('view_type', $room->view_type) === 'sea' ? 'selected' : '' }}
                                    >
                                        Sea View
                                    </option>


                                    <option
                                        value="mountain"
                                        {{ old('view_type', $room->view_type) === 'mountain' ? 'selected' : '' }}
                                    >
                                        Mountain View
                                    </option>


                                    <option
                                        value="garden"
                                        {{ old('view_type', $room->view_type) === 'garden' ? 'selected' : '' }}
                                    >
                                        Garden View
                                    </option>


                                    <option
                                        value="pool"
                                        {{ old('view_type', $room->view_type) === 'pool' ? 'selected' : '' }}
                                    >
                                        Pool View
                                    </option>


                                    <option
                                        value="city"
                                        {{ old('view_type', $room->view_type) === 'city' ? 'selected' : '' }}
                                    >
                                        City View
                                    </option>


                                    <option
                                        value="other"
                                        {{ old('view_type', $room->view_type) === 'other' ? 'selected' : '' }}
                                    >
                                        Other
                                    </option>

                                </select>


                                @error('view_type')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                        </div>



                        {{-- SIZE + SIZE UNIT --}}

                        <div class="row">


                            {{-- SIZE --}}

                            <div class="col-md-6 mb-3">

                                <label
                                    for="size"
                                    class="form-label fw-semibold"
                                >

                                    Room Size

                                </label>


                                <input
                                    type="number"
                                    name="size"
                                    id="size"
                                    value="{{ old('size', $room->size) }}"
                                    class="form-control @error('size') is-invalid @enderror"
                                    placeholder="e.g. 350"
                                    min="0"
                                    step="0.01"
                                >


                                @error('size')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>



                            {{-- SIZE UNIT --}}

                            <div class="col-md-6 mb-3">

                                <label
                                    for="size_unit"
                                    class="form-label fw-semibold"
                                >

                                    Size Unit

                                </label>


                                <select
                                    name="size_unit"
                                    id="size_unit"
                                    class="form-select @error('size_unit') is-invalid @enderror"
                                >

                                    <option
                                        value="sqft"
                                        {{ old('size_unit', $room->size_unit ?? 'sqft') === 'sqft' ? 'selected' : '' }}
                                    >
                                        Square Feet (sqft)
                                    </option>


                                    <option
                                        value="sqm"
                                        {{ old('size_unit', $room->size_unit) === 'sqm' ? 'selected' : '' }}
                                    >
                                        Square Meter (sqm)
                                    </option>

                                </select>


                                @error('size_unit')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                        </div>

                    </div>

                </div>



                {{-- =================================================
                     ROOM IMAGE
                ================================================== --}}

                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-transparent py-3">

                        <h5 class="mb-0 fw-bold">

                            <i class="fas fa-image me-2"></i>

                            Room Image

                        </h5>

                    </div>


                    <div class="card-body">


                        {{-- CURRENT IMAGE --}}

                        @if($room->featured_image)

                            <div class="mb-3">

                                <label class="form-label fw-semibold">

                                    Current Featured Image

                                </label>


                                <div>

                                    <img
                                        src="{{ asset('storage/' . $room->featured_image) }}"
                                        alt="{{ $room->name }}"
                                        style="
                                            width:220px;
                                            height:145px;
                                            object-fit:cover;
                                            border-radius:10px;
                                            border:1px solid #ddd;
                                        "
                                    >

                                </div>

                            </div>

                        @endif



                        {{-- NEW IMAGE --}}

                        <label
                            for="featured_image"
                            class="form-label fw-semibold"
                        >

                            Replace Featured Image

                        </label>


                        <input
                            type="file"
                            name="featured_image"
                            id="featured_image"
                            class="form-control @error('featured_image') is-invalid @enderror"
                            accept=".jpg,.jpeg,.png,.webp"
                        >


                        <div class="form-text">

                            Upload a new image only if you want to replace
                            the current image. JPG, JPEG, PNG or WEBP.
                            Maximum size 4MB.

                        </div>


                        @error('featured_image')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror



                        {{-- NEW IMAGE PREVIEW --}}

                        <div
                            id="imagePreviewWrapper"
                            class="mt-3 d-none"
                        >

                            <label class="form-label fw-semibold">

                                New Image Preview

                            </label>


                            <div>

                                <img
                                    id="imagePreview"
                                    src=""
                                    alt="Preview"
                                    style="
                                        width:220px;
                                        height:145px;
                                        object-fit:cover;
                                        border-radius:10px;
                                        border:1px solid #ddd;
                                    "
                                >

                            </div>

                        </div>

                    </div>

                </div>

            </div>



            {{-- =================================================
                 RIGHT COLUMN
            ================================================== --}}

            <div class="col-lg-4">


                {{-- =================================================
                     SETTINGS
                ================================================== --}}

                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-transparent py-3">

                        <h5 class="mb-0 fw-bold">

                            <i class="fas fa-cog me-2"></i>

                            Settings

                        </h5>

                    </div>


                    <div class="card-body">


                        {{-- FEATURED ROOM --}}

                        <div class="form-check form-switch mb-3">

                            <input
                                type="checkbox"
                                name="is_featured"
                                value="1"
                                class="form-check-input"
                                id="is_featured"
                                {{ old('is_featured', $room->is_featured) ? 'checked' : '' }}
                            >


                            <label
                                class="form-check-label fw-semibold"
                                for="is_featured"
                            >

                                Featured Room

                            </label>


                            <div class="form-text">

                                Show this room as a featured room.

                            </div>

                        </div>



                        {{-- STATUS --}}

                        <div class="form-check form-switch">

                            <input
                                type="checkbox"
                                name="status"
                                value="1"
                                class="form-check-input"
                                id="status"
                                {{ old('status', $room->status) ? 'checked' : '' }}
                            >


                            <label
                                class="form-check-label fw-semibold"
                                for="status"
                            >

                                Active

                            </label>


                            <div class="form-text">

                                Active rooms can be shown for booking.

                            </div>

                        </div>

                    </div>

                </div>



                {{-- =================================================
                     ROOM INFORMATION
                ================================================== --}}

                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-transparent py-3">

                        <h5 class="mb-0 fw-bold">

                            <i class="fas fa-info-circle me-2"></i>

                            Room Information

                        </h5>

                    </div>


                    <div class="card-body">


                        <div class="mb-3">

                            <small class="text-muted d-block">
                                Resort
                            </small>

                            <strong>
                                {{ $resort->name }}
                            </strong>

                        </div>


                        <div class="mb-3">

                            <small class="text-muted d-block">
                                Room
                            </small>

                            <strong>
                                {{ $room->name }}
                            </strong>

                        </div>


                        @if($room->room_no)

                            <div class="mb-3">

                                <small class="text-muted d-block">
                                    Room Number
                                </small>

                                <strong>
                                    {{ $room->room_no }}
                                </strong>

                            </div>

                        @endif


                        <div>

                            <small class="text-muted d-block">
                                Room ID
                            </small>

                            <strong>
                                #{{ $room->id }}
                            </strong>

                        </div>

                    </div>

                </div>



                {{-- =================================================
                     ACTIONS
                ================================================== --}}

                <div class="card border-0 shadow-sm">

                    <div class="card-body">


                        <button
                            type="submit"
                            class="btn btn-primary w-100 mb-2"
                        >

                            <i class="fas fa-save me-1"></i>

                            Update Room

                        </button>


                        <a
                            href="{{ route('vendor.rooms.index') }}"
                            class="btn btn-outline-secondary w-100"
                        >

                            Cancel

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>



{{-- =========================================================
     IMAGE PREVIEW
========================================================= --}}

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const input = document.getElementById('featured_image');

    const preview = document.getElementById('imagePreview');

    const wrapper = document.getElementById('imagePreviewWrapper');


    input?.addEventListener('change', function (event) {

        const file = event.target.files[0];


        if (!file) {

            wrapper.classList.add('d-none');

            preview.src = '';

            return;

        }


        if (!file.type.startsWith('image/')) {

            wrapper.classList.add('d-none');

            preview.src = '';

            return;

        }


        const reader = new FileReader();


        reader.onload = function (e) {

            preview.src = e.target.result;

            wrapper.classList.remove('d-none');

        };


        reader.readAsDataURL(file);

    });

});

</script>

@endpush

@endsection