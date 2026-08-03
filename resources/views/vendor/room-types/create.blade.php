@extends('layouts.vendor')

@section('title', 'Add Room Type')

@section('page')

<div class="container-fluid py-4">

```
{{-- Header --}}
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

    <div>
        <h4 class="fw-bold mb-1">
            Add Room Type
        </h4>

        <p class="text-muted mb-0">
            Create a new room type for your resort rooms.
        </p>
    </div>

    <a
        href="{{ route('vendor.room-types.index') }}"
        class="btn btn-light mt-3 mt-md-0"
    >
        <i class="fas fa-arrow-left me-1"></i>
        Back to Room Types
    </a>

</div>


{{-- Validation Errors --}}
@if($errors->any())

    <div class="alert alert-danger border-0 shadow-sm">

        <div class="fw-bold mb-2">
            <i class="fas fa-exclamation-triangle me-1"></i>
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

            <div class="card-header bg-transparent border-0 py-3">

                <h5 class="fw-bold mb-1">
                    Room Type Information
                </h5>

                <small class="text-muted">
                    Enter the basic information for the new room type.
                </small>

            </div>


            <div class="card-body">

                <form
                    action="{{ route('vendor.room-types.store') }}"
                    method="POST"
                >

                    @csrf


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
                            value="{{ old('name') }}"
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
                            Family Room, Suite etc.
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
                            value="{{ old('icon') }}"
                            placeholder="e.g. fas fa-bed"
                            maxlength="255"
                        >

                        @error('icon')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                        <small class="text-muted">
                            Example:
                            <code>fas fa-bed</code>,
                            <code>fas fa-hotel</code>,
                            <code>fas fa-crown</code>
                        </small>

                    </div>


                    {{-- Icon Preview --}}
                    <div class="mb-4">

                        <label class="form-label fw-semibold">
                            Icon Preview
                        </label>

                        <div class="border rounded p-4 text-center">

                            <div
                                id="iconPreview"
                                class="rounded-circle bg-primary bg-opacity-10
                                       text-primary d-flex align-items-center
                                       justify-content-center mx-auto"
                                style="width:70px;height:70px;"
                            >

                                <i class="fas fa-bed fs-2"></i>

                            </div>

                            <small class="text-muted d-block mt-2">
                                Icon preview
                            </small>

                        </div>

                    </div>


                    {{-- Actions --}}
                    <div
                        class="d-flex flex-wrap justify-content-end gap-2
                               pt-3 border-top"
                    >

                        <a
                            href="{{ route('vendor.room-types.index') }}"
                            class="btn btn-light px-4"
                        >
                            Cancel
                        </a>

                        <button
                            type="submit"
                            class="btn btn-primary px-4"
                        >

                            <i class="fas fa-check me-1"></i>

                            Create Room Type

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>
```

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
                '<i class="fas fa-bed fs-2"></i>';

        }

    });

});

</script>

@endpush

@endsection
