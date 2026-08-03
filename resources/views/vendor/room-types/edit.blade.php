@extends('layouts.vendor')

@section('page')

<div class="container-fluid py-4">

{{-- Header --}}
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

    <div>
        <h4 class="fw-bold mb-1">
            Edit Room Type
        </h4>

        <p class="text-muted mb-0">
            Update the information of this room type.
        </p>
    </div>

    <a
        href="{{ route('vendor.room-types.index') }}"
        class="btn btn-light mt-3 mt-md-0"
    >
        <i class="bi bi-arrow-left me-1"></i>
        Back to Room Types
    </a>

</div>


{{-- Success Message --}}
@if(session('success'))

    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm">

        <i class="bi bi-check-circle me-1"></i>

        {{ session('success') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
        ></button>

    </div>

@endif


{{-- Validation Errors --}}
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


{{-- Form --}}
<div class="row justify-content-center">

    <div class="col-xl-8 col-lg-9">

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white border-0 py-3">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <h5 class="fw-bold mb-1">
                            Room Type Information
                        </h5>

                        <small class="text-muted">
                            Update the room type details below.
                        </small>

                    </div>

                    <span class="badge bg-light text-dark">
                        #{{ $roomType->id }}
                    </span>

                </div>

            </div>


            <div class="card-body">

                <form
                    action="{{ route('vendor.room-types.update', $roomType) }}"
                    method="POST"
                >

                    @csrf
                    @method('PUT')


                    {{-- Room Type Name --}}
                    <div class="mb-4">

                        <label
                            for="name"
                            class="form-label fw-semibold"
                        >
                            Room Type Name
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $roomType->name) }}"
                            placeholder="e.g. Deluxe Room"
                            maxlength="255"
                            required
                        >

                        @error('name')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                        <small class="text-muted">
                            Example: Standard Room, Deluxe Room,
                            Family Room or Suite.
                        </small>

                    </div>


                    {{-- Icon --}}
                    <div class="mb-4">

                        <label
                            for="icon"
                            class="form-label fw-semibold"
                        >
                            Icon Class
                        </label>

                        <input
                            type="text"
                            id="icon"
                            name="icon"
                            class="form-control @error('icon') is-invalid @enderror"
                            value="{{ old('icon', $roomType->icon) }}"
                            placeholder="e.g. bi bi-house-door"
                            maxlength="255"
                        >

                        @error('icon')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                        <small class="text-muted">
                            Enter a Bootstrap Icons or Font Awesome icon class.
                        </small>

                    </div>


                    {{-- Icon Preview --}}
                    <div class="mb-4">

                        <label class="form-label fw-semibold">
                            Icon Preview
                        </label>

                        <div
                            class="border rounded p-4 text-center bg-light"
                        >

                            <div
                                id="iconPreview"
                                class="rounded-circle bg-primary bg-opacity-10
                                       text-primary d-flex align-items-center
                                       justify-content-center mx-auto"
                                style="width:70px;height:70px;"
                            >

                                @if($roomType->icon)

                                    <i class="{{ $roomType->icon }} fs-2"></i>

                                @else

                                    <i class="bi bi-grid fs-2"></i>

                                @endif

                            </div>

                            <small class="text-muted d-block mt-2">
                                Current icon preview
                            </small>

                        </div>

                    </div>


                    {{-- Usage Information --}}
                    <div class="alert alert-info border-0">

                        <div class="d-flex gap-2">

                            <i class="bi bi-info-circle fs-5"></i>

                            <div>

                                <div class="fw-semibold">
                                    Room Usage
                                </div>

                                <small>
                                    This room type is currently assigned to
                                    <strong>
                                        {{ $roomType->rooms()->count() }}
                                    </strong>
                                    room(s).
                                </small>

                            </div>

                        </div>

                    </div>


                    {{-- Actions --}}
                    <div
                        class="d-flex flex-wrap justify-content-between
                               align-items-center gap-2 pt-3 border-top"
                    >

                        <a
                            href="{{ route('vendor.room-types.index') }}"
                            class="btn btn-light px-4"
                        >
                            <i class="bi bi-arrow-left me-1"></i>
                            Cancel
                        </a>


                        <button
                            type="submit"
                            class="btn btn-primary px-4"
                        >
                            <i class="bi bi-check-lg me-1"></i>
                            Update Room Type
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

</div>

{{-- Icon Preview Script --}}
@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const iconInput = document.getElementById('icon');
    const iconPreview = document.getElementById('iconPreview');

    if (!iconInput || !iconPreview) {
        return;
    }

    iconInput.addEventListener('input', function () {

        const iconClass = this.value.trim();

        if (iconClass) {

            iconPreview.innerHTML =
                '<i class="' + iconClass + ' fs-2"></i>';

        } else {

            iconPreview.innerHTML =
                '<i class="bi bi-grid fs-2"></i>';

        }

    });

});

</script>

@endpush

@endsection
