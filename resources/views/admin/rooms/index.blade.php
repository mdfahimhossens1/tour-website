@extends('layouts.admin')

@section('title', 'Rooms')

@section('page')

<div class="container-fluid py-4">
{{-- =========================================================
    HEADER
========================================================== --}}

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

    <div>
        <h4 class="fw-bold mb-1">
            <i class="fas fa-bed text-primary me-2"></i>
            Room Management
        </h4>

        <p class="text-muted mb-0">
            Manage your rooms, pricing, availability and gallery.
        </p>
    </div>



</div>


{{-- =========================================================
    SUCCESS MESSAGE
========================================================== --}}

@if(session('success'))

    <div class="alert alert-success alert-dismissible fade show">

        <i class="fas fa-check-circle me-2"></i>

        {{ session('success') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
        ></button>

    </div>

@endif


{{-- =========================================================
    ERROR MESSAGE
========================================================== --}}

@if(session('error'))

    <div class="alert alert-danger alert-dismissible fade show">

        <i class="fas fa-exclamation-circle me-2"></i>

        {{ session('error') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
        ></button>

    </div>

@endif


{{-- =========================================================
    VALIDATION ERRORS
========================================================== --}}

@if($errors->any())

    <div class="alert alert-danger alert-dismissible fade show">

        <strong>
            Please fix the following errors:
        </strong>

        <ul class="mb-0 mt-2">

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


{{-- =========================================================
    SUMMARY CARDS
========================================================== --}}

<div class="row g-3 mb-4">

    {{-- Total Rooms --}}

    <div class="col-xl-3 col-md-6">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <small class="text-muted">
                            Total Rooms
                        </small>

                        <h4 class="fw-bold mb-0 mt-1">
                            {{ $rooms->total() }}
                        </h4>

                    </div>

                    <div
                        class="rounded-3 bg-primary bg-opacity-10 p-3"
                    >

                        <i class="fas fa-bed text-primary fs-4"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Active Rooms --}}

    <div class="col-xl-3 col-md-6">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <small class="text-muted">
                            Active Rooms
                        </small>

                        <h4 class="fw-bold mb-0 mt-1">

                            {{ $rooms->where('status', 1)->count() }}

                        </h4>

                    </div>

                    <div
                        class="rounded-3 bg-success bg-opacity-10 p-3"
                    >

                        <i class="fas fa-check-circle text-success fs-4"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Inactive Rooms --}}

    <div class="col-xl-3 col-md-6">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <small class="text-muted">
                            Inactive Rooms
                        </small>

                        <h4 class="fw-bold mb-0 mt-1">

                            {{ $rooms->where('status', 0)->count() }}

                        </h4>

                    </div>

                    <div
                        class="rounded-3 bg-danger bg-opacity-10 p-3"
                    >

                        <i class="fas fa-times-circle text-danger fs-4"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Resorts --}}

    <div class="col-xl-3 col-md-6">

        <div class="card border-0 shadow-sm h-100">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <small class="text-muted">
                            My Resorts
                        </small>

                        <h4 class="fw-bold mb-0 mt-1">

                            {{ $resorts->count() }}

                        </h4>

                    </div>

                    <div
                        class="rounded-3 bg-info bg-opacity-10 p-3"
                    >

                        <i class="fas fa-hotel text-info fs-4"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
    ROOM LIST
========================================================== --}}

<div class="card border-0 shadow-sm">

    {{-- Card Header --}}

    <div class="card-header bg-white border-0 py-3">

        <div class="d-flex flex-wrap justify-content-between align-items-center">

            <div>

                <h5 class="fw-bold mb-1">
                    All Rooms
                </h5>

                <small class="text-muted">
                    Rooms added to your resorts
                </small>

            </div>


            <div class="text-muted small mt-2 mt-md-0">

                Showing
                <strong>{{ $rooms->count() }}</strong>
                of
                <strong>{{ $rooms->total() }}</strong>
                rooms

            </div>

        </div>

    </div>


    {{-- Card Body --}}

    <div class="card-body p-0">

        @if($rooms->count())

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="ps-4">
                                #
                            </th>

                            <th>
                                Room
                            </th>

                            <th>
                                Resort
                            </th>

                            <th>
                                Type
                            </th>

                            <th>
                                Price
                            </th>

                            <th>
                                Capacity
                            </th>

                            <th>
                                Status
                            </th>

                            <th class="text-end pe-4">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($rooms as $room)

                            <tr>

                                {{-- =================================================
                                    NUMBER
                                ================================================== --}}

                                <td class="ps-4">

                                    <span class="text-muted">

                                        {{ $rooms->firstItem() + $loop->index }}

                                    </span>

                                </td>


                                {{-- =================================================
                                    ROOM
                                ================================================== --}}

                                <td>

                                    <div class="d-flex align-items-center gap-3">

                                        {{-- Image --}}

                                        <div
                                            class="rounded-3 bg-light overflow-hidden d-flex align-items-center justify-content-center"
                                            style="width:65px;height:55px;min-width:65px;"
                                        >

                                            @if($room->featured_image)

                                                <img
                                                    src="{{ asset('storage/' . $room->featured_image) }}"
                                                    alt="{{ $room->name }}"
                                                    class="w-100 h-100"
                                                    style="object-fit:cover;"
                                                >

                                            @else

                                                <i class="fas fa-bed text-muted fs-4"></i>

                                            @endif

                                        </div>


                                        {{-- Name --}}

                                        <div>

                                            <div class="fw-semibold">

                                                {{ $room->name }}

                                            </div>

                                            <small class="text-muted">

                                                @if($room->beds)
                                                    {{ $room->beds }} Bed{{ $room->beds > 1 ? 's' : '' }}
                                                @endif

                                                @if($room->bathrooms)
                                                    · {{ $room->bathrooms }} Bath{{ $room->bathrooms > 1 ? 's' : '' }}
                                                @endif

                                            </small>

                                        </div>

                                    </div>

                                </td>


                                {{-- =================================================
                                    RESORT
                                ================================================== --}}

                                <td>

                                    @if($room->resort)

                                        <div class="fw-semibold">

                                            {{ $room->resort->name }}

                                        </div>

                                        @if($room->resort->district)

                                            <small class="text-muted">

                                                {{ $room->resort->district }}

                                            </small>

                                        @endif

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- =================================================
                                    ROOM TYPE
                                ================================================== --}}

                                <td>

                                    @if($room->roomType)

                                        <span class="badge bg-light text-dark border">

                                            {{ $room->roomType->name }}

                                        </span>

                                    @else

                                        <span class="text-muted">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- =================================================
                                    PRICE
                                ================================================== --}}

                                <td>

                                    <div class="fw-semibold">

                                        ৳{{ number_format($room->price ?? 0, 2) }}

                                    </div>

                                    <small class="text-muted">
                                        per night
                                    </small>

                                </td>


                                {{-- =================================================
                                    CAPACITY
                                ================================================== --}}

                                <td>

                                    <span>

                                        <i class="fas fa-users text-muted me-1"></i>

                                        {{ $room->capacity ?? 0 }}

                                        {{ ($room->capacity ?? 0) == 1 ? 'Person' : 'Persons' }}

                                    </span>

                                </td>


                                {{-- =================================================
                                    STATUS
                                ================================================== --}}

                                <td>

                                    @if($room->status)

                                        <span class="badge bg-success-subtle text-success">

                                            <i class="fas fa-check-circle me-1"></i>
                                            Active

                                        </span>

                                    @else

                                        <span class="badge bg-danger-subtle text-danger">

                                            <i class="fas fa-times-circle me-1"></i>
                                            Inactive

                                        </span>

                                    @endif

                                </td>


                                {{-- =================================================
                                    ACTIONS
                                ================================================== --}}

                                <td class="text-end pe-4">

                                    <div class="d-flex justify-content-end gap-1 flex-wrap">


                                        {{-- =====================================
                                            EDIT
                                        ====================================== --}}

                                        <a
                                            href="{{ route('vendor.rooms.edit', $room) }}"
                                            class="btn btn-sm btn-outline-primary"
                                            title="Edit Room"
                                        >

                                            <i class="fas fa-edit"></i>

                                        </a>


                                        {{-- =====================================
                                            PRICES
                                        ====================================== --}}

                                        <a
                                            href="{{ route('vendor.room-prices.index', $room) }}"
                                            class="btn btn-sm btn-outline-success"
                                            title="Manage Room Prices"
                                        >

                                            <i class="fas fa-tags"></i>

                                        </a>


                                        {{-- =====================================
                                            AVAILABILITY
                                        ====================================== --}}

                                        <a
                                            href="{{ route('vendor.room-availabilities.index', $room) }}"
                                            class="btn btn-sm btn-outline-warning"
                                            title="Manage Availability"
                                        >

                                            <i class="fas fa-calendar-alt"></i>

                                        </a>


                                        {{-- =====================================
                                            GALLERY
                                        ====================================== --}}

                                        <a
                                            href="{{ route('vendor.room-images.index', $room) }}"
                                            class="btn btn-sm btn-outline-info"
                                            title="Room Gallery"
                                        >

                                            <i class="fas fa-images"></i>

                                        </a>


                                        {{-- =====================================
                                            DELETE
                                        ====================================== --}}

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteRoomModal{{ $room->id }}"
                                            title="Delete Room"
                                        >

                                            <i class="fas fa-trash"></i>

                                        </button>

                                    </div>

                                </td>

                            </tr>


                            {{-- =================================================
                                DELETE MODAL
                            ================================================== --}}

                            <div
                                class="modal fade"
                                id="deleteRoomModal{{ $room->id }}"
                                tabindex="-1"
                                aria-hidden="true"
                            >

                                <div class="modal-dialog modal-dialog-centered">

                                    <div class="modal-content border-0 shadow">

                                        <div class="modal-header bg-danger text-white">

                                            <h5 class="modal-title">

                                                <i class="fas fa-trash me-2"></i>

                                                Delete Room

                                            </h5>

                                            <button
                                                type="button"
                                                class="btn-close btn-close-white"
                                                data-bs-dismiss="modal"
                                            ></button>

                                        </div>


                                        <div class="modal-body text-center py-4">

                                            <div
                                                class="rounded-circle bg-danger bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3"
                                                style="width:70px;height:70px;"
                                            >

                                                <i class="fas fa-trash text-danger fs-3"></i>

                                            </div>


                                            <h5 class="fw-bold">
                                                Delete this room?
                                            </h5>


                                            <p class="text-muted mb-2">

                                                You are about to delete:

                                            </p>


                                            <div class="fw-semibold">

                                                {{ $room->name }}

                                            </div>


                                            <p class="text-danger small mt-3 mb-0">

                                                This action cannot be undone.

                                            </p>

                                        </div>


                                        <div class="modal-footer justify-content-center">

                                            <button
                                                type="button"
                                                class="btn btn-light border"
                                                data-bs-dismiss="modal"
                                            >

                                                Cancel

                                            </button>


                                            <form
                                                action="{{ route('vendor.rooms.destroy', $room) }}"
                                                method="POST"
                                            >

                                                @csrf

                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn btn-danger"
                                                >

                                                    <i class="fas fa-trash me-1"></i>

                                                    Delete Room

                                                </button>

                                            </form>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            {{-- =================================================
                EMPTY STATE
            ================================================== --}}

            <div class="text-center py-5">

                <div
                    class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3"
                    style="width:80px;height:80px;"
                >

                    <i class="fas fa-bed text-primary fs-2"></i>

                </div>


                <h5 class="fw-bold">
                    No Rooms Found
                </h5>


                <p class="text-muted mb-4">

                    You haven't added any rooms yet.
                    Start by adding your first room.

                </p>


            </div>

        @endif

    </div>


    {{-- =========================================================
        PAGINATION
    ========================================================== --}}

    @if($rooms->hasPages())

        <div class="card-footer bg-white border-0 py-3">

            <div class="d-flex justify-content-center">

                {{ $rooms->links() }}

            </div>

        </div>

    @endif

</div>
</div>

@endsection
