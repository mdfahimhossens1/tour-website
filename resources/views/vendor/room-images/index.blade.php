@extends('layouts.vendor')

@section('page')

<div class="container-fluid py-4">

{{-- Header --}}
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

    <div>
        <h4 class="fw-bold mb-1">
            Room Images
        </h4>

        <p class="text-muted mb-0">
            Manage gallery images for
            <strong>{{ $room->name }}</strong>.
        </p>
    </div>

    <a
        href="{{ route('vendor.rooms.index') }}"
        class="btn btn-outline-secondary mt-3 mt-md-0"
    >
        <i class="bi bi-arrow-left me-1"></i>
        Back to Rooms
    </a>

</div>


{{-- Success Message --}}
@if(session('success'))

    <div class="alert alert-success alert-dismissible fade show">

        <i class="bi bi-check-circle me-1"></i>

        {{ session('success') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
        ></button>

    </div>

@endif


{{-- Error Message --}}
@if(session('error'))

    <div class="alert alert-danger alert-dismissible fade show">

        <i class="bi bi-exclamation-triangle me-1"></i>

        {{ session('error') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
        ></button>

    </div>

@endif


{{-- Validation Errors --}}
@if($errors->any())

    <div class="alert alert-danger">

        <div class="fw-bold mb-2">
            Please fix the following errors:
        </div>

        <ul class="mb-0">

            @foreach($errors->all() as $error)

                <li>{{ $error }}</li>

            @endforeach

        </ul>

    </div>

@endif


{{-- Room Information --}}
<div class="card border-0 shadow-sm mb-4">

    <div class="card-body">

        <div class="row g-3 align-items-center">

            <div class="col-md-8">

                <div class="d-flex align-items-center gap-3">

                    @if($room->featured_image)

                        <img
                            src="{{ asset('storage/' . $room->featured_image) }}"
                            alt="{{ $room->name }}"
                            style="
                                width:80px;
                                height:65px;
                                object-fit:cover;
                                border-radius:10px;
                            "
                        >

                    @else

                        <div
                            class="bg-light d-flex align-items-center
                                   justify-content-center"
                            style="
                                width:80px;
                                height:65px;
                                border-radius:10px;
                            "
                        >
                            <i class="bi bi-door-open fs-3 text-muted"></i>
                        </div>

                    @endif


                    <div>

                        <h5 class="fw-bold mb-1">
                            {{ $room->name }}
                        </h5>

                        <div class="text-muted small">

                            @if($room->resort)
                                <i class="bi bi-building me-1"></i>
                                {{ $room->resort->name }}
                            @endif

                            @if($room->roomType)
                                <span class="mx-2">•</span>
                                <i class="bi bi-grid me-1"></i>
                                {{ $room->roomType->name }}
                            @endif

                        </div>

                    </div>

                </div>

            </div>


            <div class="col-md-4 text-md-end">

                <span class="badge bg-primary fs-6">
                    {{ $images->count() }}
                    {{ $images->count() == 1 ? 'Image' : 'Images' }}
                </span>

            </div>

        </div>

    </div>

</div>


<div class="row g-4">

    {{-- Upload Image --}}
    <div class="col-lg-4">

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white border-0 py-3">

                <h5 class="fw-bold mb-1">
                    Upload Image
                </h5>

                <small class="text-muted">
                    Add a new image to this room's gallery.
                </small>

            </div>


            <div class="card-body">

                <form
                    action="{{ route('vendor.room-images.store', $room) }}"
                    method="POST"
                    enctype="multipart/form-data"
                >

                    @csrf


                    {{-- Image --}}
                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Image
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="file"
                            name="image"
                            class="form-control"
                            accept="image/jpeg,image/png,image/webp"
                            required
                        >

                        <small class="text-muted">
                            JPG, JPEG, PNG or WEBP. Maximum 4MB.
                        </small>

                    </div>


                    {{-- Cover --}}
                    <div class="form-check mb-3">

                        <input
                            type="checkbox"
                            name="is_cover"
                            value="1"
                            class="form-check-input"
                            id="is_cover"
                        >

                        <label
                            class="form-check-label"
                            for="is_cover"
                        >
                            Set as cover image
                        </label>

                    </div>


                    <button
                        type="submit"
                        class="btn btn-primary w-100"
                    >

                        <i class="bi bi-cloud-upload me-1"></i>
                        Upload Image

                    </button>

                </form>

            </div>

        </div>


        {{-- Helpful Information --}}
        <div class="card border-0 shadow-sm mt-4">

            <div class="card-body">

                <h6 class="fw-bold mb-3">
                    <i class="bi bi-info-circle me-1"></i>
                    Image Guidelines
                </h6>

                <ul class="text-muted small mb-0 ps-3">

                    <li class="mb-2">
                        Use clear, high-quality room photos.
                    </li>

                    <li class="mb-2">
                        The cover image represents the main room photo.
                    </li>

                    <li class="mb-2">
                        Only one image can be the cover at a time.
                    </li>

                    <li>
                        Recommended formats: JPG, PNG and WEBP.
                    </li>

                </ul>

            </div>

        </div>

    </div>


    {{-- Gallery --}}
    <div class="col-lg-8">

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white border-0 py-3">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <h5 class="fw-bold mb-1">
                            Room Gallery
                        </h5>

                        <small class="text-muted">
                            Manage your room images.
                        </small>

                    </div>

                </div>

            </div>


            <div class="card-body">

                @if($images->count())

                    <div class="row g-3">

                        @foreach($images as $image)

                            <div class="col-md-6">

                                <div class="card border h-100 overflow-hidden">

                                    {{-- Image --}}
                                    <div
                                        class="position-relative"
                                        style="
                                            height:220px;
                                            background:#f8f9fa;
                                        "
                                    >

                                        <img
                                            src="{{ asset('storage/' . $image->image) }}"
                                            alt="{{ $room->name }}"
                                            class="w-100 h-100"
                                            style="object-fit:cover;"
                                        >


                                        {{-- Cover Badge --}}
                                        @if($image->is_cover)

                                            <span
                                                class="position-absolute top-0 start-0
                                                       badge bg-success m-2"
                                            >
                                                <i class="bi bi-star-fill me-1"></i>
                                                Cover
                                            </span>

                                        @endif


                                        {{-- Order Badge --}}
                                        <span
                                            class="position-absolute top-0 end-0
                                                   badge bg-dark m-2"
                                        >
                                            #{{ $image->sort_order }}
                                        </span>

                                    </div>


                                    <div class="card-body">

                                        {{-- Sort Order --}}
                                        <form
                                            action="{{ route('vendor.room-images.order', $image) }}"
                                            method="POST"
                                            class="mb-3"
                                        >

                                            @csrf
                                            @method('PUT')

                                            <label class="form-label small fw-semibold">
                                                Sort Order
                                            </label>

                                            <div class="input-group">

                                                <input
                                                    type="number"
                                                    name="sort_order"
                                                    value="{{ $image->sort_order }}"
                                                    min="0"
                                                    class="form-control form-control-sm"
                                                >

                                                <button
                                                    type="submit"
                                                    class="btn btn-outline-primary btn-sm"
                                                >
                                                    Save
                                                </button>

                                            </div>

                                        </form>


                                        {{-- Actions --}}
                                        <div class="d-flex gap-2">

                                            @if(!$image->is_cover)

                                                <form
                                                    action="{{ route('vendor.room-images.cover', $image) }}"
                                                    method="POST"
                                                    class="flex-grow-1"
                                                >

                                                    @csrf

                                                    <button
                                                        type="submit"
                                                        class="btn btn-outline-success btn-sm w-100"
                                                    >
                                                        <i class="bi bi-star me-1"></i>
                                                        Set Cover
                                                    </button>

                                                </form>

                                            @else

                                                <button
                                                    type="button"
                                                    class="btn btn-success btn-sm flex-grow-1"
                                                    disabled
                                                >
                                                    <i class="bi bi-star-fill me-1"></i>
                                                    Current Cover
                                                </button>

                                            @endif


                                            {{-- Delete --}}
                                            <form
                                                action="{{ route('vendor.room-images.destroy', $image) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this image?')"
                                            >

                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn btn-outline-danger btn-sm"
                                                >
                                                    <i class="bi bi-trash"></i>
                                                </button>

                                            </form>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        @endforeach

                    </div>

                @else

                    {{-- Empty Gallery --}}
                    <div class="text-center py-5">

                        <div
                            class="rounded-circle bg-light d-flex align-items-center
                                   justify-content-center mx-auto mb-3"
                            style="width:80px;height:80px;"
                        >

                            <i class="bi bi-images fs-2 text-muted"></i>

                        </div>

                        <h5 class="fw-bold">
                            No Images Yet
                        </h5>

                        <p class="text-muted mb-0">
                            Upload your first room image using the form.
                        </p>

                    </div>

                @endif

            </div>

        </div>

    </div>

</div>
</div>

@endsection
