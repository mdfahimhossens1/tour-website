@extends('layouts.vendor')

@section('page')

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h4 class="fw-bold mb-1">
                Rooms
            </h4>

            <p class="text-muted mb-0">
                Manage your resort rooms, room types and availability.
            </p>
        </div>

        <div class="d-flex gap-2 mt-3 mt-md-0">

@if($resorts->count())

    <a
        href="{{ route('vendor.rooms.create') }}"
        class="btn btn-primary"
    >
        <i class="bi bi-plus-lg me-1"></i>
        Add Room
    </a>

@else

    <a
        href="{{ route('vendor.resorts.create') }}"
        class="btn btn-primary"
    >
        <i class="bi bi-plus-lg me-1"></i>
        Create Resort First
    </a>

@endif

        </div>

    </div>


    {{-- Statistics --}}
    <div class="row g-3 mb-4">

        <div class="col-md-4">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <small class="text-muted">
                                Total Rooms
                            </small>

                            <h3 class="fw-bold mb-0">
                                {{ $rooms->total() }}
                            </h3>
                        </div>

                        <div
                            class="rounded-circle bg-primary bg-opacity-10
                                   text-primary d-flex align-items-center
                                   justify-content-center"
                            style="width:50px;height:50px;"
                        >
                            <i class="bi bi-door-open fs-4"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <small class="text-muted">
                                My Resorts
                            </small>

                            <h3 class="fw-bold mb-0">
                                {{ $resorts->count() }}
                            </h3>
                        </div>

                        <div
                            class="rounded-circle bg-success bg-opacity-10
                                   text-success d-flex align-items-center
                                   justify-content-center"
                            style="width:50px;height:50px;"
                        >
                            <i class="bi bi-building fs-4"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>
                            <small class="text-muted">
                                Active Rooms
                            </small>

                            <h3 class="fw-bold mb-0">
                                {{ $rooms->where('status', 1)->count() }}
                            </h3>
                        </div>

                        <div
                            class="rounded-circle bg-info bg-opacity-10
                                   text-info d-flex align-items-center
                                   justify-content-center"
                            style="width:50px;height:50px;"
                        >
                            <i class="bi bi-check-circle fs-4"></i>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Rooms Table --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center">

                <div>
                    <h5 class="fw-bold mb-1">
                        All Rooms
                    </h5>

                    <small class="text-muted">
                        Manage all rooms belonging to your resorts.
                    </small>
                </div>

            </div>

        </div>


        <div class="card-body p-0">

            @if($rooms->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th class="ps-4">
                                    Room
                                </th>

                                <th>
                                    Resort
                                </th>

                                <th>
                                    Room Type
                                </th>

                                <th>
                                    Room No
                                </th>

                                <th>
                                    Total Rooms
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="text-end pe-4">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($rooms as $room)

                                <tr>

                                    {{-- Room --}}
                                    <td class="ps-4">

                                        <div class="d-flex align-items-center gap-3">

                                            @if($room->featured_image)

                                                <img
                                                    src="{{ asset('storage/' . $room->featured_image) }}"
                                                    alt="{{ $room->name }}"
                                                    style="
                                                        width:55px;
                                                        height:45px;
                                                        object-fit:cover;
                                                        border-radius:8px;
                                                    "
                                                >

                                            @else

                                                <div
                                                    class="bg-light d-flex align-items-center
                                                           justify-content-center"
                                                    style="
                                                        width:55px;
                                                        height:45px;
                                                        border-radius:8px;
                                                    "
                                                >
                                                    <i class="bi bi-door-open text-muted"></i>
                                                </div>

                                            @endif


                                            <div>

                                                <div class="fw-bold">
                                                    {{ $room->name }}
                                                </div>

                                                @if($room->slug)
                                                    <small class="text-muted">
                                                        {{ $room->slug }}
                                                    </small>
                                                @endif

                                            </div>

                                        </div>

                                    </td>


                                    {{-- Resort --}}
                                    <td>

                                        @if($room->resort)

                                            <span class="fw-semibold">
                                                {{ $room->resort->name }}
                                            </span>

                                        @else

                                            <span class="text-muted">
                                                N/A
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Room Type --}}
                                    <td>

                                        @if($room->roomType)

                                            <span class="badge bg-light text-dark">
                                                {{ $room->roomType->name }}
                                            </span>

                                        @else

                                            <span class="text-muted">
                                                N/A
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Room No --}}
                                    <td>

                                        {{ $room->room_no ?? 'N/A' }}

                                    </td>


                                    {{-- Total Rooms --}}
                                    <td>

                                        {{ $room->total_rooms ?? 0 }}

                                    </td>


                                    {{-- Status --}}
                                    <td>

                                        @if($room->status)

                                            <span class="badge bg-success">
                                                Active
                                            </span>

                                        @else

                                            <span class="badge bg-danger">
                                                Inactive
                                            </span>

                                        @endif

                                    </td>


{{-- Actions --}}
<td class="text-end pe-4">

    <div class="dropdown">

        <button
            class="btn btn-sm btn-light"
            type="button"
            data-bs-toggle="dropdown"
            aria-expanded="false"
        >

            <i class="bi bi-three-dots-vertical"></i>

        </button>


        <ul class="dropdown-menu dropdown-menu-end">

            {{-- Edit --}}
            <li>

                <a
                    class="dropdown-item"
                    href="{{ route('vendor.rooms.edit', $room) }}"
                >

                    <i class="bi bi-pencil me-2"></i>

                    Edit

                </a>

            </li>


            {{-- Manage Prices --}}
            <li>

                <a
                    class="dropdown-item"
                    href="{{ route('vendor.room-prices.index', ['room' => $room->id]) }}"
                >

                    <i class="bi bi-currency-dollar me-2"></i>

                    Manage Prices

                </a>

            </li>


            {{-- Availability --}}
            <li>

                <a
                    class="dropdown-item"
                    href="{{ route('vendor.room-availabilities.index', ['room' => $room->id]) }}"
                >

                    <i class="bi bi-calendar-check me-2"></i>

                    Availability

                </a>

            </li>


            <li>
                <hr class="dropdown-divider">
            </li>


            {{-- Delete --}}
            <li>

                <form
                    action="{{ route('vendor.rooms.destroy', $room) }}"
                    method="POST"
                    onsubmit="return confirm('Are you sure you want to delete this room?')"
                >

                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="dropdown-item text-danger"
                    >

                        <i class="bi bi-trash me-2"></i>

                        Delete

                    </button>

                </form>

            </li>

        </ul>

    </div>

</td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- Pagination --}}
                <div class="p-3">

                    {{ $rooms->links() }}

                </div>


            @else

                {{-- Empty --}}
                <div class="text-center py-5">

                    <div
                        class="rounded-circle bg-light d-flex align-items-center
                               justify-content-center mx-auto mb-3"
                        style="width:75px;height:75px;"
                    >
                        <i class="bi bi-door-open fs-2 text-muted"></i>
                    </div>

                    <h5 class="fw-bold">
                        No rooms found
                    </h5>

                    <p class="text-muted mb-3">
                        You haven't added any rooms to your resorts yet.
                    </p>


                 @if($resorts->count())

    <a
        href="{{ route('vendor.rooms.create') }}"
        class="btn btn-primary"
    >
        <i class="bi bi-plus-lg me-1"></i>
        Add Your First Room
    </a>

@else

    <a
        href="{{ route('vendor.resorts.create') }}"
        class="btn btn-primary"
    >
        Create Resort First
    </a>

@endif

                </div>

            @endif

        </div>

    </div>

</div>

@endsection