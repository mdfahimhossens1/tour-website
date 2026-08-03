@extends('layouts.vendor')

@section('page')

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h4 class="fw-bold mb-1">
                Add Room Availability
            </h4>

            <p class="text-muted mb-0">
                Add availability information for
                <strong>{{ $room->name }}</strong>.
            </p>
        </div>

        <a
            href="{{ route('vendor.room-availabilities.index', ['room' => $room->id]) }}"
            class="btn btn-light border"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back to Availability
        </a>

    </div>


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

        {{-- Main Form --}}
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white border-0 py-3">

                    <h5 class="fw-bold mb-1">
                        Availability Details
                    </h5>

                    <small class="text-muted">
                        Set room availability and pricing for a specific date.
                    </small>

                </div>


                <div class="card-body">

                    <form
                        action="{{ route('vendor.room-availabilities.store', ['room' => $room->id]) }}"
                        method="POST"
                    >

                        @csrf


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
                                value="{{ old('date') }}"
                                required
                            >

                            @error('date')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                            <small class="text-muted">
                                Select the date for this availability record.
                            </small>

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
                                        value="{{ old('price') }}"
                                        placeholder="0.00"
                                    >

                                    @error('price')

                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>

                                    @enderror

                                </div>

                                <small class="text-muted">
                                    Leave empty if the default room price should be used.
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
                                    value="{{ old('total_rooms', $room->total_rooms ?? 0) }}"
                                    required
                                >

                                @error('total_rooms')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                                <small class="text-muted">
                                    Total rooms available for this date.
                                </small>

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
                                    value="{{ old('available_rooms', $room->total_rooms ?? 0) }}"
                                    required
                                >

                                @error('available_rooms')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                                <small class="text-muted">
                                    Number of rooms currently available for booking.
                                </small>

                            </div>


                            {{-- Status --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Availability Status
                                </label>

                                <div class="border rounded p-3">

                                    <div class="form-check mb-2">

                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            name="is_closed"
                                            value="1"
                                            id="isClosed"
                                            {{ old('is_closed') ? 'checked' : '' }}
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


                                    <div class="form-check">

                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            name="is_sold_out"
                                            value="1"
                                            id="isSoldOut"
                                            {{ old('is_sold_out') ? 'checked' : '' }}
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


                        {{-- Info --}}
                        <div class="alert alert-info mt-4 mb-4">

                            <div class="d-flex gap-2">

                                <i class="bi bi-info-circle fs-5"></i>

                                <div>

                                    <div class="fw-semibold">
                                        Availability Information
                                    </div>

                                    <small>
                                        Available rooms should not be greater than total rooms.
                                        A closed or sold-out date will not be available for booking.
                                    </small>

                                </div>

                            </div>

                        </div>


                        {{-- Buttons --}}
                        <div class="d-flex justify-content-end gap-2">

                            <a
                                href="{{ route('vendor.room-availabilities.index', ['room' => $room->id]) }}"
                                class="btn btn-light border"
                            >
                                Cancel
                            </a>

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >
                                <i class="bi bi-check-lg me-1"></i>
                                Save Availability
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

                    {{-- Image --}}
                    <div class="mb-3">

                        @if($room->featured_image)

                            <img
                                src="{{ asset('storage/' . $room->featured_image) }}"
                                alt="{{ $room->name }}"
                                class="w-100"
                                style="
                                    height:180px;
                                    object-fit:cover;
                                    border-radius:12px;
                                "
                            >

                        @else

                            <div
                                class="bg-light d-flex align-items-center justify-content-center"
                                style="
                                    height:180px;
                                    border-radius:12px;
                                "
                            >

                                <i class="bi bi-door-open fs-1 text-muted"></i>

                            </div>

                        @endif

                    </div>


                    {{-- Room Name --}}
                    <h5 class="fw-bold mb-1">
                        {{ $room->name }}
                    </h5>


                    {{-- Resort --}}
                    @if($room->resort)

                        <div class="text-muted small mb-2">

                            <i class="bi bi-building me-1"></i>

                            {{ $room->resort->name }}

                        </div>

                    @endif


                    {{-- Room Type --}}
                    @if($room->roomType)

                        <div class="mb-3">

                            <span class="badge bg-light text-dark">

                                <i class="bi bi-grid me-1"></i>

                                {{ $room->roomType->name }}

                            </span>

                        </div>

                    @endif


                    <hr>


                    {{-- Room Details --}}
                    <div class="row g-3">

                        <div class="col-6">

                            <small class="text-muted d-block">
                                Room No
                            </small>

                            <strong>
                                {{ $room->room_no ?? 'N/A' }}
                            </strong>

                        </div>


                        <div class="col-6">

                            <small class="text-muted d-block">
                                Total Rooms
                            </small>

                            <strong>
                                {{ $room->total_rooms ?? 0 }}
                            </strong>

                        </div>


                        <div class="col-6">

                            <small class="text-muted d-block">
                                Max Adults
                            </small>

                            <strong>
                                {{ $room->max_adult ?? 0 }}
                            </strong>

                        </div>


                        <div class="col-6">

                            <small class="text-muted d-block">
                                Max Children
                            </small>

                            <strong>
                                {{ $room->max_child ?? 0 }}
                            </strong>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Helpful Tips --}}
            <div class="card border-0 shadow-sm mt-3">

                <div class="card-body">

                    <h6 class="fw-bold mb-3">

                        <i class="bi bi-lightbulb me-1 text-warning"></i>

                        Quick Tips

                    </h6>

                    <ul class="small text-muted mb-0 ps-3">

                        <li class="mb-2">
                            Keep available rooms synchronized with actual inventory.
                        </li>

                        <li class="mb-2">
                            Use closed status when the resort is unavailable on a specific date.
                        </li>

                        <li>
                            Use sold out when all rooms are already booked.
                        </li>

                    </ul>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection