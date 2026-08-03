@extends('layouts.vendor')

@section('page')

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h4 class="fw-bold mb-1">
                Room Availability
            </h4>

            <p class="text-muted mb-0">
                Manage availability, pricing and room status for
                <strong>{{ $room->name }}</strong>.
            </p>
        </div>

        <div class="d-flex gap-2 mt-3 mt-md-0">

            <a
                href="{{ route('vendor.rooms.index') }}"
                class="btn btn-light border"
            >
                <i class="bi bi-arrow-left me-1"></i>
                Back to Rooms
            </a>

            <a
                href="{{ route('vendor.room-availabilities.create', $room) }}"
                class="btn btn-primary"
            >
                <i class="bi bi-plus-lg me-1"></i>
                Add Availability
            </a>

        </div>

    </div>


    {{-- Success Message --}}
    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show" role="alert">

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

        <div class="alert alert-danger">

            <strong>
                Please fix the following errors:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Room Information --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="row align-items-center">

                <div class="col-md-8">

                    <div class="d-flex align-items-center gap-3">

                        @if($room->featured_image)

                            <img
                                src="{{ asset('storage/' . $room->featured_image) }}"
                                alt="{{ $room->name }}"
                                style="
                                    width:80px;
                                    height:65px;
                                    object-fit:cover;
                                    border-radius:10px;
                                "
                            >

                        @else

                            <div
                                class="bg-light d-flex align-items-center justify-content-center"
                                style="
                                    width:80px;
                                    height:65px;
                                    border-radius:10px;
                                "
                            >
                                <i class="bi bi-door-open fs-3 text-muted"></i>
                            </div>

                        @endif


                        <div>

                            <h5 class="fw-bold mb-1">
                                {{ $room->name }}
                            </h5>

                            <div class="text-muted small">

                                @if($room->resort)
                                    <i class="bi bi-building me-1"></i>
                                    {{ $room->resort->name }}
                                @endif

                                @if($room->roomType)
                                    <span class="mx-2">•</span>
                                    <i class="bi bi-grid me-1"></i>
                                    {{ $room->roomType->name }}
                                @endif

                                @if($room->room_no)
                                    <span class="mx-2">•</span>
                                    Room No: {{ $room->room_no }}
                                @endif

                            </div>

                        </div>

                    </div>

                </div>


                <div class="col-md-4 mt-3 mt-md-0">

                    <div class="row g-2">

                        <div class="col-6">

                            <div class="bg-light rounded p-2 text-center">

                                <small class="text-muted d-block">
                                    Total Records
                                </small>

                                <strong class="fs-5">
                                    {{ $availabilities->total() }}
                                </strong>

                            </div>

                        </div>


                        <div class="col-6">

                            <div class="bg-success bg-opacity-10 rounded p-2 text-center">

                                <small class="text-success d-block">
                                    Room Capacity
                                </small>

                                <strong class="fs-5 text-success">
                                    {{ $room->total_rooms ?? 0 }}
                                </strong>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Availability Table --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center">

                <div>

                    <h5 class="fw-bold mb-1">
                        Availability Calendar
                    </h5>

                    <small class="text-muted">
                        Manage daily room availability and pricing.
                    </small>

                </div>

            </div>

        </div>


        <div class="card-body p-0">

            @if($availabilities->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th class="ps-4">
                                    Date
                                </th>

                                <th>
                                    Price
                                </th>

                                <th>
                                    Total Rooms
                                </th>

                                <th>
                                    Available
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Availability
                                </th>

                                <th class="text-end pe-4">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($availabilities as $availability)

                                @php

                                    $date = $availability->date;

                                    $available = (int) ($availability->available_rooms ?? 0);

                                    $total = (int) ($availability->total_rooms ?? 0);

                                    $percentage = $total > 0
                                        ? round(($available / $total) * 100)
                                        : 0;

                                    if ($availability->is_closed) {

                                        $statusText = 'Closed';
                                        $statusClass = 'danger';

                                    } elseif ($availability->is_sold_out || $available <= 0) {

                                        $statusText = 'Sold Out';
                                        $statusClass = 'warning';

                                    } else {

                                        $statusText = 'Open';
                                        $statusClass = 'success';

                                    }

                                @endphp


                                <tr>

                                    {{-- Date --}}
                                    <td class="ps-4">

                                        <div class="fw-bold">

                                            {{ $date ? $date->format('d M Y') : 'N/A' }}

                                        </div>

                                        @if($date)

                                            <small class="text-muted">

                                                {{ $date->format('l') }}

                                            </small>

                                        @endif

                                    </td>


                                    {{-- Price --}}
                                    <td>

                                        @if($availability->price !== null)

                                            <span class="fw-semibold">

                                                ৳ {{ number_format($availability->price, 2) }}

                                            </span>

                                        @else

                                            <span class="text-muted">
                                                Default
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Total Rooms --}}
                                    <td>

                                        <span class="fw-semibold">

                                            {{ $total }}

                                        </span>

                                    </td>


                                    {{-- Available --}}
                                    <td>

                                        <span class="fw-semibold">

                                            {{ $available }}

                                        </span>

                                    </td>


                                    {{-- Status --}}
                                    <td>

                                        <span class="badge bg-{{ $statusClass }}">

                                            @if($statusText === 'Open')

                                                <i class="bi bi-check-circle me-1"></i>

                                            @elseif($statusText === 'Closed')

                                                <i class="bi bi-x-circle me-1"></i>

                                            @else

                                                <i class="bi bi-exclamation-circle me-1"></i>

                                            @endif

                                            {{ $statusText }}

                                        </span>

                                    </td>


                                    {{-- Availability Progress --}}
                                    <td style="min-width:150px;">

                                        <div class="d-flex justify-content-between mb-1">

                                            <small class="text-muted">
                                                {{ $percentage }}%
                                            </small>

                                        </div>

                                        <div
                                            class="progress"
                                            style="height:6px;"
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
                                                role="progressbar"
                                                style="width: {{ min($percentage, 100) }}%;"
                                            ></div>

                                        </div>

                                    </td>


                                    {{-- Actions --}}
                                    <td class="text-end pe-4">

                                        <div class="dropdown">

                                            <button
                                                class="btn btn-sm btn-light"
                                                type="button"
                                                data-bs-toggle="dropdown"
                                            >

                                                <i class="bi bi-three-dots-vertical"></i>

                                            </button>


                                            <ul class="dropdown-menu dropdown-menu-end">

                                                {{-- Edit --}}
                                                <li>

                                                    <a
                                                        class="dropdown-item"
                                                        href="{{ route(
                                                            'vendor.room-availabilities.edit',
                                                            $availability
                                                        ) }}"
                                                    >

                                                        <i class="bi bi-pencil me-2"></i>

                                                        Edit

                                                    </a>

                                                </li>


                                                {{-- Delete --}}
                                                <li>

                                                    <form
                                                        action="{{ route(
                                                            'vendor.room-availabilities.destroy',
                                                            $availability
                                                        ) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Are you sure you want to delete this availability record?')"
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

                    {{ $availabilities->links() }}

                </div>


            @else

                {{-- Empty State --}}
                <div class="text-center py-5">

                    <div
                        class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3"
                        style="width:75px;height:75px;"
                    >

                        <i class="bi bi-calendar-x fs-2 text-muted"></i>

                    </div>


                    <h5 class="fw-bold">
                        No availability records found
                    </h5>


                    <p class="text-muted mb-3">

                        No availability has been added for
                        <strong>{{ $room->name }}</strong> yet.

                    </p>


                    <a
                        href="{{ route('vendor.room-availabilities.create', $room) }}"
                        class="btn btn-primary"
                    >

                        <i class="bi bi-plus-lg me-1"></i>

                        Add First Availability

                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection