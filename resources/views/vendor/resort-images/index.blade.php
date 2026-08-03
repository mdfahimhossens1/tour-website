@extends('layouts.vendor')

@section('page')

<div class="container-fluid py-4">

{{-- Header --}}
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

    <div>
        <h4 class="fw-bold mb-1">
            Resort Images
        </h4>

        <p class="text-muted mb-0">
            Manage gallery images for
            <strong>{{ $resort->name }}</strong>
        </p>
    </div>

    <a
        href="{{ route('vendor.resorts.index') }}"
        class="btn btn-light mt-3 mt-md-0"
    >
        <i class="bi bi-arrow-left me-1"></i>
        Back to Resorts
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

        <strong>
            Please fix the following errors:
        </strong>

        <ul class="mb-0 mt-2">

            @foreach($errors->all() as $error)

                <li>
                    {{ $error }}
                </li>

            @endforeach

        </ul>

    </div>

@endif


<div class="row g-4">


    {{-- Upload Form --}}
    <div class="col-lg-4">

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white border-0 py-3">

                <h5 class="fw-bold mb-1">
                    Upload Image
                </h5>

                <small class="text-muted">
                    Add a new image to your resort gallery.
                </small>

            </div>


            <div class="card-body">

                <form
                    action="{{ route('vendor.resort-images.store', $resort) }}"
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
                            class="form-control @error('image') is-invalid @enderror"
                            accept="image/jpeg,image/png,image/webp"
                            required
                        >

                        @error('image')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                        <small class="text-muted">
                            JPG, JPEG, PNG or WEBP. Maximum 4MB.
                        </small>

                    </div>


                    {{-- Cover --}}
                    <div class="form-check mb-4">

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

                        <i class="bi bi-cloud-arrow-up me-1"></i>

                        Upload Image

                    </button>

                </form>

            </div>

        </div>


        {{-- Resort Information --}}
        <div class="card border-0 shadow-sm mt-4">

            <div class="card-body">

                <h6 class="fw-bold mb-3">
                    Resort Information
                </h6>

                <div class="mb-2">

                    <small class="text-muted d-block">
                        Resort
                    </small>

                    <span class="fw-semibold">
                        {{ $resort->name }}
                    </span>

                </div>

                <div class="mb-2">

                    <small class="text-muted d-block">
                        Total Images
                    </small>

                    <span class="fw-semibold">
                        {{ $images->count() }}
                    </span>

                </div>

                <div>

                    <small class="text-muted d-block">
                        Cover Image
                    </small>

                    @if($images->where('is_cover', true)->count())

                        <span class="badge bg-success">
                            Set
                        </span>

                    @else

                        <span class="badge bg-secondary">
                            Not Set
                        </span>

                    @endif

                </div>

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
                            Gallery
                        </h5>

                        <small class="text-muted">
                            Manage your resort images.
                        </small>

                    </div>

                    <span class="badge bg-primary">
                        {{ $images->count() }}
                        {{ $images->count() == 1 ? 'Image' : 'Images' }}
                    </span>

                </div>

            </div>


            <div class="card-body">

                @if($images->count())

                    <div class="row g-4">

                        @foreach($images as $image)

                            <div class="col-md-6 col-xl-4">

                                <div class="card border shadow-sm h-100">

                                    {{-- Image --}}
                                    <div
                                        class="position-relative"
                                        style="height:190px;"
                                    >

                                        <img
                                            src="{{ asset('storage/' . $image->image) }}"
                                            alt="{{ $resort->name }}"
                                            class="w-100 h-100"
                                            style="
                                                object-fit:cover;
                                                border-radius:6px 6px 0 0;
                                            "
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

                                    </div>


                                    <div class="card-body">

                                        {{-- Sort Order --}}
                                        <form
                                            action="{{ route('vendor.resort-images.order', $image) }}"
                                            method="POST"
                                            class="mb-3"
                                        >

                                            @csrf

                                            @method('PUT')

                                            <label class="form-label small fw-semibold">
                                                Sort Order
                                            </label>

                                            <div class="input-group input-group-sm">

                                                <input
                                                    type="number"
                                                    name="sort_order"
                                                    value="{{ $image->sort_order }}"
                                                    min="0"
                                                    class="form-control"
                                                >

                                                <button
                                                    type="submit"
                                                    class="btn btn-outline-primary"
                                                    title="Update order"
                                                >

                                                    <i class="bi bi-check-lg"></i>

                                                </button>

                                            </div>

                                        </form>


                                        {{-- Actions --}}
                                        <div class="d-flex gap-2">


                                            {{-- Set Cover --}}
                                            @if(!$image->is_cover)

                                                <form
                                                    action="{{ route('vendor.resort-images.cover', $image) }}"
                                                    method="POST"
                                                    class="flex-grow-1"
                                                >

                                                    @csrf

                                                    <button
                                                        type="submit"
                                                        class="btn btn-sm btn-outline-success w-100"
                                                    >

                                                        <i class="bi bi-star me-1"></i>

                                                        Cover

                                                    </button>

                                                </form>

                                            @else

                                                <button
                                                    type="button"
                                                    class="btn btn-sm btn-success flex-grow-1"
                                                    disabled
                                                >

                                                    <i class="bi bi-star-fill me-1"></i>

                                                    Cover

                                                </button>

                                            @endif


                                            {{-- Delete --}}
                                            <form
                                                action="{{ route('vendor.resort-images.destroy', $image) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this image?')"
                                            >

                                                @csrf

                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Delete"
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

                    {{-- Empty State --}}
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
                            Upload your first resort image using the form.
                        </p>

                    </div>

                @endif

            </div>

        </div>

    </div>

</div>

</div>

@endsection
