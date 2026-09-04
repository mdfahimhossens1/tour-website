@extends('layouts.vendor')

@section('title', 'Edit Facility')

@section('page')

<div class="container-fluid">

    <div class="card shadow">

        <div class="card-header">

            <h5 class="mb-0">

                <i class="fas fa-edit me-2"></i>

                Edit Facility

            </h5>

        </div>


        <div class="card-body">

            <form
                action="{{ route(
                    'vendor.facilities.update',
                    $facility->id
                ) }}"
                method="POST"
            >

                @csrf

                @method('PUT')


                {{-- NAME --}}

                <div class="mb-3">

                    <label class="form-label">

                        Facility Name

                    </label>

                    <input
                        type="text"
                        name="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $facility->name) }}"
                        required
                    >

                    @error('name')

                        <div class="invalid-feedback">

                            {{ $message }}

                        </div>

                    @enderror

                </div>


                {{-- ICON --}}

                <div class="mb-3">

                    <label class="form-label">

                        Icon Class

                    </label>

                    <input
                        type="text"
                        name="icon"
                        id="facilityIcon"
                        class="form-control"
                        value="{{ old('icon', $facility->icon) }}"
                        placeholder="fas fa-wifi"
                    >

                </div>


                {{-- ICON PREVIEW --}}

                <div class="text-center mb-4">

                    <i
                        id="iconPreview"
                        class="{{ $facility->icon ?: 'fas fa-star' }} fa-3x text-primary"
                    ></i>

                </div>


                {{-- TYPE --}}

                <div class="mb-3">

                    <label class="form-label">

                        Type

                    </label>

                    <select
                        name="type"
                        class="form-select"
                        required
                    >

                        <option
                            value="resort"
                            {{ old('type', $facility->type) === 'resort' ? 'selected' : '' }}
                        >

                            Resort

                        </option>

                        <option
                            value="room"
                            {{ old('type', $facility->type) === 'room' ? 'selected' : '' }}
                        >

                            Room

                        </option>

                    </select>

                </div>


                {{-- STATUS --}}

                <div class="mb-4">

                    <label class="form-label">

                        Status

                    </label>

                    <select
                        name="status"
                        class="form-select"
                        required
                    >

                        <option
                            value="1"
                            {{ old('status', $facility->status) == 1 ? 'selected' : '' }}
                        >

                            Active

                        </option>

                        <option
                            value="0"
                            {{ old('status', $facility->status) == 0 ? 'selected' : '' }}
                        >

                            Inactive

                        </option>

                    </select>

                </div>


                {{-- BUTTONS --}}

                <div class="d-flex gap-2">

                    <a
                        href="{{ route('vendor.facilities.index') }}"
                        class="btn btn-secondary"
                    >

                        <i class="fas fa-arrow-left me-1"></i>

                        Back

                    </a>


                    <button
                        type="submit"
                        class="btn btn-primary"
                    >

                        <i class="fas fa-save me-1"></i>

                        Update Facility

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


@push('scripts')

<script>

$(function () {

    $('#facilityIcon').on(
        'keyup change',
        function () {

            let icon = $(this).val();

            if (!icon) {

                icon = 'fas fa-star';

            }

            $('#iconPreview').attr(
                'class',
                icon + ' fa-3x text-primary'
            );

        }
    );

});

</script>

@endpush

@endsection