@extends('layouts.vendor')

@section('page')

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>

            <h4 class="fw-bold mb-1">
                Edit Room Availability
            </h4>

            <p class="text-muted mb-0">
                Update availability settings for
                <strong>{{ $availability->room->name }}</strong>.
            </p>

        </div>

        <a
            href="{{ route(
                'vendor.room-availabilities.index',
                ['room' => $availability->room_id]
            ) }}"
            class="btn btn-light border mt-3 mt-md-0"
        >

            <i class="bi bi-arrow-left me-1"></i>

            Back to Availability

        </a>

    </div>


    {{-- Errors --}}
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

        {{-- Form --}}
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="fw-bold mb-1">
                        Availability Details
                    </h5>

                    <small class="text-muted">
                        Update date-specific pricing and status.
                    </small>

                </div>


                <div class="card-body">

                    <form
                        action="{{ route(
                            'vendor.room-availabilities.update',
                            $availability
                        ) }}"
                        method="POST"
                    >

                        @csrf

                        @method('PUT')


                        {{-- Date --}}
                        <div class="mb-4">

                            <label class="form-label fw-semibold">

                                Date

                                <span class="text-danger">*</span>

                            </label>

                            <input
                                type="date"
                                name="date"
                                class="form-control @error('date') is-invalid @enderror"
                                value="{{ old(
                                    'date',
                                    optional($availability->date)->format('Y-m-d')
                                ) }}"
                                min="{{ now()->format('Y-m-d') }}"
                                required
                            >

                            @error('date')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <div class="row g-3">

                            {{-- Price --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Special Price
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        ৳
                                    </span>

                                    <input
                                        type="number"
                                        name="price"
                                        step="0.01"
                                        min="0"
                                        class="form-control @error('price') is-invalid @enderror"
                                        value="{{ old(
                                            'price',
                                            $availability->price
                                        ) }}"
                                        placeholder="Default price"
                                    >

                                </div>

                                @error('price')

                                    <div class="text-danger small mt-1">
                                        {{ $message }}
                                    </div>

                                @enderror

                                <small class="text-muted">
                                    Leave empty to use the default room price.
                                </small>

                            </div>


                            {{-- Total --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Total Rooms
                                </label>

                                <div class="form-control bg-light">

                                    <strong>
                                        {{ $availability->room->total_rooms ?? 0 }}
                                    </strong>

                                    <span class="text-muted">
                                        rooms
                                    </span>

                                </div>

                                <small class="text-muted">
                                    Managed automatically from room inventory.
                                </small>

                            </div>

                        </div>


                        {{-- Current Availability --}}
                        <div class="row g-3 mt-1">

                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Currently Available
                                </label>

                                <div class="form-control bg-light">

                                    <strong>
                                        {{ $availability->available_rooms }}
                                    </strong>

                                    <span class="text-muted">
                                        rooms
                                    </span>

                                </div>

                            </div>


                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Current Status
                                </label>

                                <div class="form-control bg-light">

                                    @if($availability->is_closed)

                                        <span class="badge bg-danger">
                                            Closed
                                        </span>

                                    @elseif($availability->is_sold_out)

                                        <span class="badge bg-warning text-dark">
                                            Sold Out
                                        </span>

                                    @else

                                        <span class="badge bg-success">
                                            Open
                                        </span>

                                    @endif

                                </div>

                            </div>

                        </div>


                        {{-- Closed --}}
                        <div class="mt-4">

                            <div class="border rounded p-3">

                                <div class="form-check">

                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="is_closed"
                                        value="1"
                                        id="isClosed"
                                        {{ old(
                                            'is_closed',
                                            $availability->is_closed
                                        ) ? 'checked' : '' }}
                                    >

                                    <label
                                        class="form-check-label"
                                        for="isClosed"
                                    >

                                        <span class="fw-semibold">
                                            Close this date
                                        </span>

                                        <small class="text-muted d-block">
                                            Guests will not be able to book this date.
                                        </small>

                                    </label>

                                </div>

                            </div>

                        </div>


                        {{-- Info --}}
                        <div class="alert alert-info mt-4">

                            <i class="bi bi-info-circle me-1"></i>

                            Available rooms and sold-out status are calculated
                            automatically from room inventory and bookings.

                        </div>


                        {{-- Buttons --}}
                        <div class="d-flex justify-content-end gap-2 mt-4">

                            <a
                                href="{{ route(
                                    'vendor.room-availabilities.index',
                                    ['room' => $availability->room_id]
                                ) }}"
                                class="btn btn-light border"
                            >
                                Cancel
                            </a>

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >

                                <i class="bi bi-check-lg me-1"></i>

                                Update Availability

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>


        {{-- Room Info --}}
        <div class="col-lg-4">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="fw-bold mb-0">
                        Room Information
                    </h5>

                </div>

                <div class="card-body">

                    @if($availability->room->featured_image)

                        <img
                            src="{{ asset(
                                'storage/' .
                                $availability->room->featured_image
                            ) }}"
                            alt="{{ $availability->room->name }}"
                            class="w-100 mb-3"
                            style="
                                height:180px;
                                object-fit:cover;
                                border-radius:12px;
                            "
                        >

                    @else

                        <div
                            class="bg-light d-flex align-items-center justify-content-center mb-3"
                            style="
                                height:180px;
                                border-radius:12px;
                            "
                        >

                            <i class="bi bi-door-open fs-1 text-muted"></i>

                        </div>

                    @endif


                    <h5 class="fw-bold mb-1">
                        {{ $availability->room->name }}
                    </h5>


                    @if($availability->room->resort)

                        <div class="text-muted small mb-2">

                            <i class="bi bi-building me-1"></i>

                            {{ $availability->room->resort->name }}

                        </div>

                    @endif


                    @if($availability->room->roomType)

                        <span class="badge bg-light text-dark">

                            <i class="bi bi-grid me-1"></i>

                            {{ $availability->room->roomType->name }}

                        </span>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@endsection