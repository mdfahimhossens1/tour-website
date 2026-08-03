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
                Update availability information for
                <strong>{{ $availability->room->name }}</strong>.
            </p>
        </div>

        <a
            href="{{ route('vendor.room-availabilities.index', ['room' => $availability->room_id]) }}"
            class="btn btn-light border"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back to Availability
        </a>

    </div>


    {{-- Success --}}
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


    {{-- Validation Errors --}}
    @if($errors->any())

        <div class="alert alert-danger alert-dismissible fade show">

            <div class="fw-bold mb-2">
                <i class="bi bi-exclamation-triangle me-1"></i>
                Please fix the following errors:
            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

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
                        Update the daily room availability and pricing.
                    </small>

                </div>


                <div class="card-body">

                    <form
                        action="{{ route('vendor.room-availabilities.update', $availability) }}"
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
                                value="{{ old('date', $availability->date?->format('Y-m-d')) }}"
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
                                    Price
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
                                        value="{{ old('price', $availability->price) }}"
                                        placeholder="0.00"
                                    >

                                    @error('price')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>

                                <small class="text-muted">
                                    Leave empty to use the default room price.
                                </small>

                            </div>


                            {{-- Total Rooms --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">

                                    Total Rooms

                                    <span class="text-danger">*</span>

                                </label>

                                <input
                                    type="number"
                                    name="total_rooms"
                                    min="0"
                                    class="form-control @error('total_rooms') is-invalid @enderror"
                                    value="{{ old('total_rooms', $availability->total_rooms) }}"
                                    required
                                >

                                @error('total_rooms')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Available Rooms --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">

                                    Available Rooms

                                    <span class="text-danger">*</span>

                                </label>

                                <input
                                    type="number"
                                    name="available_rooms"
                                    min="0"
                                    class="form-control @error('available_rooms') is-invalid @enderror"
                                    value="{{ old('available_rooms', $availability->available_rooms) }}"
                                    required
                                >

                                @error('available_rooms')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- Current Status --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Availability Status
                                </label>

                                <div class="border rounded p-3">

                                    {{-- Closed --}}
                                    <div class="form-check mb-2">

                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            name="is_closed"
                                            value="1"
                                            id="isClosed"
                                            {{ old('is_closed', $availability->is_closed) ? 'checked' : '' }}
                                        >

                                        <label
                                            class="form-check-label"
                                            for="isClosed"
                                        >

                                            <span class="fw-semibold">
                                                Close this date
                                            </span>

                                            <small class="text-muted d-block">
                                                Guests cannot book this date.
                                            </small>

                                        </label>

                                    </div>


                                    {{-- Sold Out --}}
                                    <div class="form-check">

                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            name="is_sold_out"
                                            value="1"
                                            id="isSoldOut"
                                            {{ old('is_sold_out', $availability->is_sold_out) ? 'checked' : '' }}
                                        >

                                        <label
                                            class="form-check-label"
                                            for="isSoldOut"
                                        >

                                            <span class="fw-semibold">
                                                Mark as Sold Out
                                            </span>

                                            <small class="text-muted d-block">
                                                Show this date as unavailable.
                                            </small>

                                        </label>

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- Current Availability --}}
                        @php

                            $total = (int) ($availability->total_rooms ?? 0);
                            $available = (int) ($availability->available_rooms ?? 0);

                            $percentage = $total > 0
                                ? round(($available / $total) * 100)
                                : 0;

                        @endphp


                        <div class="card bg-light border-0 mt-4">

                            <div class="card-body">

                                <div class="d-flex justify-content-between align-items-center mb-2">

                                    <span class="fw-semibold">
                                        Current Availability
                                    </span>

                                    <span class="fw-bold">
                                        {{ $available }} / {{ $total }}
                                    </span>

                                </div>

                                <div
                                    class="progress"
                                    style="height:8px;"
                                >

                                    <div
                                        class="progress-bar
                                        @if($percentage >= 50)
                                            bg-success
                                        @elseif($percentage > 0)
                                            bg-warning
                                        @else
                                            bg-danger
                                        @endif"
                                        style="width: {{ min($percentage, 100) }}%;"
                                    ></div>

                                </div>

                                <small class="text-muted mt-2 d-block">
                                    {{ $percentage }}% rooms currently available.
                                </small>

                            </div>

                        </div>


                        {{-- Warning --}}
                        <div class="alert alert-info mt-4">

                            <i class="bi bi-info-circle me-1"></i>

                            Available rooms should not be greater than total rooms.

                        </div>


                        {{-- Buttons --}}
                        <div class="d-flex justify-content-end gap-2 mt-4">

                            <a
                                href="{{ route('vendor.room-availabilities.index', ['room' => $availability->room_id]) }}"
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


        {{-- Room Information --}}
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
                            src="{{ asset('storage/' . $availability->room->featured_image) }}"
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


                    <hr>


                    <div class="row g-3">

                        <div class="col-6">

                            <small class="text-muted d-block">
                                Room No
                            </small>

                            <strong>
                                {{ $availability->room->room_no ?? 'N/A' }}
                            </strong>

                        </div>


                        <div class="col-6">

                            <small class="text-muted d-block">
                                Capacity
                            </small>

                            <strong>
                                {{ $availability->room->total_rooms ?? 0 }}
                            </strong>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Danger Zone --}}
            <div class="card border-0 shadow-sm mt-3">

                <div class="card-body">

                    <h6 class="fw-bold text-danger mb-2">

                        <i class="bi bi-exclamation-triangle me-1"></i>

                        Danger Zone

                    </h6>

                    <p class="small text-muted mb-3">
                        Deleting this record cannot be undone.
                    </p>

                    <form
                        action="{{ route('vendor.room-availabilities.destroy', $availability) }}"
                        method="POST"
                        onsubmit="return confirm('Are you sure you want to delete this availability record?')"
                    >

                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="btn btn-outline-danger btn-sm"
                        >

                            <i class="bi bi-trash me-1"></i>

                            Delete Availability

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection