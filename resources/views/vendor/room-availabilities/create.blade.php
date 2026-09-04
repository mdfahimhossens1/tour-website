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
                Set date-specific pricing and availability status for
                <strong>{{ $room->name }}</strong>.
            </p>
        </div>

        <a
            href="{{ route('vendor.room-availabilities.index', ['room' => $room->id]) }}"
            class="btn btn-light border mt-3 mt-md-0"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back to Availability
        </a>

    </div>


    {{-- Errors --}}
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
                        Configure the room for a specific date.
                    </small>

                </div>


                <div class="card-body">

                    <form
                        action="{{ route(
                            'vendor.room-availabilities.store',
                            ['room' => $room->id]
                        ) }}"
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
                                min="{{ now()->format('Y-m-d') }}"
                                required
                            >

                            @error('date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            <small class="text-muted">
                                Select the date when this availability applies.
                            </small>

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
                                        value="{{ old('price') }}"
                                        placeholder="Leave empty for default price"
                                    >

                                    @error('price')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                <small class="text-muted">
                                    Leave empty to use the room's default price.
                                </small>

                            </div>


                            {{-- Room Capacity --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Room Capacity
                                </label>

                                <div class="form-control bg-light">

                                    <strong>
                                        {{ $room->total_rooms ?? 0 }}
                                    </strong>

                                    <span class="text-muted">
                                        rooms
                                    </span>

                                </div>

                                <small class="text-muted">
                                    Managed from room inventory.
                                </small>

                            </div>

                        </div>


                        {{-- Status --}}
                        <div class="mt-4">

                            <label class="form-label fw-semibold">
                                Availability Status
                            </label>

                            <div class="border rounded p-3">

                                <div class="form-check">

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
                                            Guests will not be able to book this date.
                                        </small>

                                    </label>

                                </div>

                            </div>

                        </div>


                        {{-- Information --}}
                        <div class="alert alert-info mt-4">

                            <div class="d-flex gap-2">

                                <i class="bi bi-info-circle fs-5"></i>

                                <div>

                                    <div class="fw-semibold">
                                        How availability works
                                    </div>

                                    <small>
                                        Total rooms are taken from your room inventory.
                                        Available rooms are automatically calculated
                                        based on active bookings.
                                    </small>

                                </div>

                            </div>

                        </div>


                        {{-- Buttons --}}
                        <div class="d-flex justify-content-end gap-2 mt-4">

                            <a
                                href="{{ route(
                                    'vendor.room-availabilities.index',
                                    ['room' => $room->id]
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
                    @if($room->featured_image)

                        <img
                            src="{{ asset('storage/' . $room->featured_image) }}"
                            alt="{{ $room->name }}"
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
                        {{ $room->name }}
                    </h5>


                    @if($room->resort)

                        <div class="text-muted small mb-2">

                            <i class="bi bi-building me-1"></i>

                            {{ $room->resort->name }}

                        </div>

                    @endif


                    @if($room->roomType)

                        <div class="mb-3">

                            <span class="badge bg-light text-dark">

                                <i class="bi bi-grid me-1"></i>

                                {{ $room->roomType->name }}

                            </span>

                        </div>

                    @endif


                    <hr>


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


            {{-- Tips --}}
            <div class="card border-0 shadow-sm mt-3">

                <div class="card-body">

                    <h6 class="fw-bold mb-3">

                        <i class="bi bi-lightbulb me-1 text-warning"></i>

                        Quick Tips

                    </h6>

                    <ul class="small text-muted mb-0 ps-3">

                        <li class="mb-2">
                            Add special prices for holidays or peak seasons.
                        </li>

                        <li class="mb-2">
                            Close dates when rooms are temporarily unavailable.
                        </li>

                        <li>
                            Available rooms are automatically calculated from bookings.
                        </li>

                    </ul>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection