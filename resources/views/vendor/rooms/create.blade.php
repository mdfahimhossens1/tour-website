@extends('layouts.vendor')

@section('title', 'Add Room')

@section('page')

<div class="container-fluid">

{{-- =========================================================
     PAGE HEADER
========================================================== --}}

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

    <div>

        <h4 class="fw-bold mb-1">
            Add Room
        </h4>

        <p class="text-muted mb-0">
            Add a new room to one of your resorts.
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
    action="{{ route('vendor.rooms.store') }}"
    method="POST"
    enctype="multipart/form-data"
    id="roomCreateForm"
>

    @csrf


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

                        <label
                            for="resort_id"
                            class="form-label fw-semibold"
                        >

                            Resort

                            <span class="text-danger">*</span>

                        </label>


                        @if($resorts->count())

                            <select
                                name="resort_id"
                                id="resort_id"
                                class="form-select @error('resort_id') is-invalid @enderror"
                                required
                            >

                                <option value="">
                                    Select Resort
                                </option>


                                @foreach($resorts as $resort)

                                    <option
                                        value="{{ $resort->id }}"
                                        {{ old('resort_id') == $resort->id ? 'selected' : '' }}
                                    >

                                        {{ $resort->name }}

                                    </option>

                                @endforeach

                            </select>


                            @error('resort_id')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror


                            <div class="form-text">

                                Select the resort where this room will be added.

                            </div>

                        @else

                            <div class="alert alert-warning mb-0">

                                <div class="fw-semibold mb-1">

                                    No active resort found.

                                </div>

                                <div class="small mb-2">

                                    Please create a resort before adding rooms.

                                </div>

                                <a
                                    href="{{ route('vendor.resorts.create') }}"
                                    class="btn btn-sm btn-warning"
                                >

                                    <i class="fas fa-plus me-1"></i>

                                    Create Resort

                                </a>

                            </div>

                        @endif

                    </div>


                    {{-- ROOM TYPE + NAME --}}

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
                                        {{ old('room_type_id') == $roomType->id ? 'selected' : '' }}
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
                                value="{{ old('name') }}"
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



                    {{-- ROOM NUMBER + EXTRA BED PRICE --}}

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
                                value="{{ old('room_no') }}"
                                class="form-control @error('room_no') is-invalid @enderror"
                                placeholder="e.g. 101"
                            >


                            @error('room_no')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>



                        {{-- EXTRA BED PRICE --}}

                        <div class="col-md-6 mb-3">

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
                                    value="{{ old('extra_bed_price') }}"
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
                                value="{{ old('total_rooms', 1) }}"
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
                                value="{{ old('max_adult', 2) }}"
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
                                value="{{ old('max_child', 0) }}"
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
                                value="{{ old('beds', 1) }}"
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
                                value="{{ old('bathrooms', 1) }}"
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
                                    {{ old('view_type') === 'sea' ? 'selected' : '' }}
                                >
                                    Sea View
                                </option>


                                <option
                                    value="mountain"
                                    {{ old('view_type') === 'mountain' ? 'selected' : '' }}
                                >
                                    Mountain View
                                </option>


                                <option
                                    value="garden"
                                    {{ old('view_type') === 'garden' ? 'selected' : '' }}
                                >
                                    Garden View
                                </option>


                                <option
                                    value="pool"
                                    {{ old('view_type') === 'pool' ? 'selected' : '' }}
                                >
                                    Pool View
                                </option>


                                <option
                                    value="city"
                                    {{ old('view_type') === 'city' ? 'selected' : '' }}
                                >
                                    City View
                                </option>


                                <option
                                    value="other"
                                    {{ old('view_type') === 'other' ? 'selected' : '' }}
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
                                value="{{ old('size') }}"
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
                                    {{ old('size_unit', 'sqft') === 'sqft' ? 'selected' : '' }}
                                >
                                    Square Feet (sqft)
                                </option>


                                <option
                                    value="sqm"
                                    {{ old('size_unit') === 'sqm' ? 'selected' : '' }}
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
                 ROOM IMAGES
            ================================================== --}}

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-transparent py-3">

                    <h5 class="mb-0 fw-bold">

                        <i class="fas fa-images me-2"></i>

                        Room Images

                    </h5>

                </div>


                <div class="card-body">


                    <label
                        for="roomImagePicker"
                        class="form-label fw-semibold"
                    >

                        Add Room Images

                    </label>


                    <div class="form-text mb-3">

                        Select one image at a time.
                        After selecting one image, select another one.
                        All selected images will be uploaded together.

                    </div>


                    {{-- =================================================
                         IMPORTANT
                         These hidden file inputs will be generated
                         dynamically by JavaScript.
                    ================================================== --}}

                    <div id="roomImageInputs"></div>


                    {{-- IMAGE PICKER --}}

                    <input
                        type="file"
                        id="roomImagePicker"
                        class="form-control"
                        accept=".jpg,.jpeg,.png,.webp"
                    >


                    <div class="form-text">

                        JPG, JPEG, PNG or WEBP.
                        Maximum size 4MB per image.

                    </div>


                    @error('images')

                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>

                    @enderror


                    @error('images.*')

                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>

                    @enderror



                    {{-- =================================================
                         SELECTED IMAGE COUNT
                    ================================================== --}}

                    <div
                        id="imageCount"
                        class="small text-muted mt-3 d-none"
                    >

                        <i class="fas fa-images me-1"></i>

                        <span id="imageCountText">
                            0 images selected
                        </span>

                    </div>



                    {{-- =================================================
                         IMAGE PREVIEW
                    ================================================== --}}

                    <div
                        id="roomImagesPreview"
                        class="row g-3 mt-2"
                    ></div>

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


                    {{-- FEATURED --}}

                    <div class="form-check form-switch mb-3">

                        <input
                            type="checkbox"
                            name="is_featured"
                            value="1"
                            class="form-check-input"
                            id="is_featured"
                            {{ old('is_featured') ? 'checked' : '' }}
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
                            {{ old('status', true) ? 'checked' : '' }}
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
                 ROOM TYPE INFORMATION
            ================================================== --}}

            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-transparent py-3">

                    <h5 class="mb-0 fw-bold">

                        <i class="fas fa-info-circle me-2"></i>

                        Room Type

                    </h5>

                </div>


                <div class="card-body">

                    <p class="text-muted small mb-0">

                        Room Types are managed separately.
                        Select the appropriate room type when creating
                        this room.

                    </p>

                </div>

            </div>



            {{-- =================================================
                 ACTIONS
            ================================================== --}}

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    @if($resorts->count())

                        <button
                            type="submit"
                            class="btn btn-primary w-100 mb-2"
                            id="createRoomBtn"
                        >

                            <i class="fas fa-save me-1"></i>

                            Create Room

                        </button>

                    @else

                        <a
                            href="{{ route('vendor.resorts.create') }}"
                            class="btn btn-primary w-100 mb-2"
                        >

                            <i class="fas fa-plus me-1"></i>

                            Create Resort First

                        </a>

                    @endif


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
IMAGE SCRIPT
========================================================= --}}

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const picker =
        document.getElementById('roomImagePicker');

    const preview =
        document.getElementById('roomImagesPreview');

    const inputsContainer =
        document.getElementById('roomImageInputs');

    const imageCount =
        document.getElementById('imageCount');

    const imageCountText =
        document.getElementById('imageCountText');


    /*
    |--------------------------------------------------------------------------
    | Store selected files
    |--------------------------------------------------------------------------
    */

    let selectedFiles = [];



    /*
    |--------------------------------------------------------------------------
    | Image Picker Change
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    |
    | User selects ONLY ONE image.
    |
    | Then we store that image in selectedFiles.
    |
    | After that picker is reset so the user can select
    | another image.
    |
    |--------------------------------------------------------------------------
    */

    picker.addEventListener('change', function () {

        const file = this.files[0];


        if (!file) {

            return;

        }



        /*
        |--------------------------------------------------------------------------
        | Validate File Type
        |--------------------------------------------------------------------------
        */

        const allowedTypes = [

            'image/jpeg',
            'image/png',
            'image/webp'

        ];


        if (!allowedTypes.includes(file.type)) {

            alert(
                'Please select a JPG, JPEG, PNG or WEBP image.'
            );

            this.value = '';

            return;

        }



        /*
        |--------------------------------------------------------------------------
        | Validate File Size
        |--------------------------------------------------------------------------
        */

        if (file.size > 4 * 1024 * 1024) {

            alert(
                'Image size must be less than 4MB.'
            );

            this.value = '';

            return;

        }



        /*
        |--------------------------------------------------------------------------
        | Add File To Array
        |--------------------------------------------------------------------------
        */

        selectedFiles.push(file);


        /*
        |--------------------------------------------------------------------------
        | Render Preview
        |--------------------------------------------------------------------------
        */

        renderImages();


        /*
        |--------------------------------------------------------------------------
        | IMPORTANT
        |--------------------------------------------------------------------------
        |
        | Reset input after every selection.
        |
        | This allows user to select another image
        | using the SAME file picker.
        |
        |--------------------------------------------------------------------------
        */

        this.value = '';

    });



    /*
    |--------------------------------------------------------------------------
    | Render Images
    |--------------------------------------------------------------------------
    */

    function renderImages() {

        preview.innerHTML = '';

        inputsContainer.innerHTML = '';


        /*
        |--------------------------------------------------------------------------
        | Image Count
        |--------------------------------------------------------------------------
        */

        if (selectedFiles.length > 0) {

            imageCount.classList.remove('d-none');


            imageCountText.textContent =
                selectedFiles.length === 1
                    ? '1 image selected'
                    : selectedFiles.length + ' images selected';

        } else {

            imageCount.classList.add('d-none');

        }



        /*
        |--------------------------------------------------------------------------
        | Loop Through Selected Images
        |--------------------------------------------------------------------------
        */

        selectedFiles.forEach(function (file, index) {


            /*
            |--------------------------------------------------------------------------
            | Preview Reader
            |--------------------------------------------------------------------------
            */

            const reader =
                new FileReader();


            reader.onload = function (event) {


                const col =
                    document.createElement('div');


                col.className =
                    'col-md-4 col-sm-6';


                col.innerHTML = `

                    <div
                        class="border rounded-3 overflow-hidden position-relative bg-light"
                    >

                        <img
                            src="${event.target.result}"
                            class="w-100"
                            alt="Room Image ${index + 1}"
                            style="
                                height:160px;
                                object-fit:cover;
                            "
                        >


                        <div
                            class="
                                position-absolute
                                top-0
                                start-0
                                end-0
                                p-2
                                d-flex
                                justify-content-between
                                align-items-start
                            "
                        >

                            <span class="badge bg-dark">

                                Image ${index + 1}

                            </span>


                            <button
                                type="button"
                                class="btn btn-sm btn-danger remove-room-image"
                                data-index="${index}"
                                title="Remove image"
                            >

                                <i class="fas fa-times"></i>

                            </button>

                        </div>


                        <div class="p-2">

                            <div
                                class="small text-muted text-truncate"
                                title="${escapeHtml(file.name)}"
                            >

                                ${escapeHtml(file.name)}

                            </div>

                        </div>

                    </div>

                `;


                preview.appendChild(col);

            };


            reader.readAsDataURL(file);



            /*
            |--------------------------------------------------------------------------
            | Create Hidden File Input
            |--------------------------------------------------------------------------
            |
            | VERY IMPORTANT:
            |
            | Controller expects:
            |
            | images[]
            |
            | NOT:
            |
            | room_images[]
            |
            |--------------------------------------------------------------------------
            */

            const dataTransfer =
                new DataTransfer();


            dataTransfer.items.add(file);


            const hiddenInput =
                document.createElement('input');


            hiddenInput.type =
                'file';


            hiddenInput.name =
                'images[]';


            hiddenInput.className =
                'd-none';


            hiddenInput.files =
                dataTransfer.files;


            inputsContainer.appendChild(
                hiddenInput
            );

        });

    }



    /*
    |--------------------------------------------------------------------------
    | Remove Selected Image
    |--------------------------------------------------------------------------
    */

    preview.addEventListener(
        'click',
        function (event) {

            const button =
                event.target.closest(
                    '.remove-room-image'
                );


            if (!button) {

                return;

            }


            const index =
                Number(
                    button.dataset.index
                );


            selectedFiles.splice(
                index,
                1
            );


            renderImages();

        }
    );



    /*
    |--------------------------------------------------------------------------
    | Escape HTML
    |--------------------------------------------------------------------------
    |
    | Prevent filename from breaking preview HTML.
    |--------------------------------------------------------------------------
    */

    function escapeHtml(value) {

        const div =
            document.createElement('div');


        div.textContent =
            value;


        return div.innerHTML;

    }



    /*
    |--------------------------------------------------------------------------
    | Form Submit Protection
    |--------------------------------------------------------------------------
    */

    const form =
        document.getElementById(
            'roomCreateForm'
        );


    const submitButton =
        document.getElementById(
            'createRoomBtn'
        );


    form?.addEventListener(
        'submit',
        function () {

            if (submitButton) {

                submitButton.disabled =
                    true;


                submitButton.innerHTML = `

                    <span
                        class="spinner-border spinner-border-sm me-1"
                        role="status"
                    ></span>

                    Creating Room...

                `;

            }

        }
    );

});

</script>

@endpush

@endsection
