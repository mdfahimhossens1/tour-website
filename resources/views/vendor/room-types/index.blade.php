@extends('layouts.vendor')

@section('page')

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h4 class="fw-bold mb-1">
                Room Types
            </h4>

            <p class="text-muted mb-0">
                Manage the room types available for your rooms.
            </p>
        </div>

        <a
            href="{{ route('vendor.room-types.create') }}"
            class="btn btn-primary mt-3 mt-md-0"
        >
            <i class="bi bi-plus-lg me-1"></i>
            Add Room Type
        </a>

    </div>


    {{-- Success Message --}}
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


    {{-- Error Message --}}
    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="bi bi-exclamation-triangle me-1"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- Statistics --}}
    <div class="row g-3 mb-4">

        {{-- Total Types --}}
        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted">
                                Total Room Types
                            </small>

                            <h3 class="fw-bold mb-0">
                                {{ $roomTypes->total() }}
                            </h3>

                        </div>

                        <div
                            class="rounded-circle bg-primary bg-opacity-10
                                   text-primary d-flex align-items-center
                                   justify-content-center"
                            style="width:50px;height:50px;"
                        >

                            <i class="bi bi-grid-3x3-gap fs-4"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Current Page --}}
        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted">
                                Showing
                            </small>

                            <h3 class="fw-bold mb-0">
                                {{ $roomTypes->count() }}
                            </h3>

                        </div>

                        <div
                            class="rounded-circle bg-success bg-opacity-10
                                   text-success d-flex align-items-center
                                   justify-content-center"
                            style="width:50px;height:50px;"
                        >

                            <i class="bi bi-list-check fs-4"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Used Types --}}
        <div class="col-md-4">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted">
                                Types In Use
                            </small>

                            <h3 class="fw-bold mb-0">

                                {{ $roomTypes->where('rooms_count', '>', 0)->count() }}

                            </h3>

                        </div>

                        <div
                            class="rounded-circle bg-info bg-opacity-10
                                   text-info d-flex align-items-center
                                   justify-content-center"
                            style="width:50px;height:50px;"
                        >

                            <i class="bi bi-door-open fs-4"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Room Types Table --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="fw-bold mb-1">
                        All Room Types
                    </h5>

                    <small class="text-muted">
                        View and manage available room types.
                    </small>

                </div>

            </div>

        </div>


        <div class="card-body p-0">

            @if($roomTypes->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th
                                    class="ps-4"
                                    style="width:80px;"
                                >
                                    #
                                </th>

                                <th>
                                    Room Type
                                </th>

                                <th>
                                    Icon
                                </th>

                                <th>
                                    Rooms
                                </th>

                                <th>
                                    Created
                                </th>

                                <th class="text-end pe-4">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach($roomTypes as $roomType)

                                <tr>

                                    {{-- ID --}}
                                    <td class="ps-4">

                                        <span class="text-muted">
                                            #{{ $roomType->id }}
                                        </span>

                                    </td>


                                    {{-- Name --}}
                                    <td>

                                        <div class="d-flex align-items-center gap-3">

                                            <div
                                                class="rounded-circle bg-primary
                                                       bg-opacity-10 text-primary
                                                       d-flex align-items-center
                                                       justify-content-center"
                                                style="width:42px;height:42px;"
                                            >

                                                @if($roomType->icon)

                                                    <i class="{{ $roomType->icon }}"></i>

                                                @else

                                                    <i class="bi bi-grid"></i>

                                                @endif

                                            </div>


                                            <div>

                                                <div class="fw-bold">
                                                    {{ $roomType->name }}
                                                </div>

                                                <small class="text-muted">
                                                    Room Type
                                                </small>

                                            </div>

                                        </div>

                                    </td>


                                    {{-- Icon --}}
                                    <td>

                                        @if($roomType->icon)

                                            <code>
                                                {{ $roomType->icon }}
                                            </code>

                                        @else

                                            <span class="text-muted">
                                                Not set
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Rooms Count --}}
                                    <td>

                                        @if($roomType->rooms_count > 0)

                                            <span class="badge bg-success">
                                                {{ $roomType->rooms_count }}
                                                {{ $roomType->rooms_count == 1 ? 'Room' : 'Rooms' }}
                                            </span>

                                        @else

                                            <span class="badge bg-secondary">
                                                No Rooms
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Created --}}
                                    <td>

                                        <span class="text-muted small">

                                            {{ optional($roomType->created_at)->format('d M Y') }}

                                        </span>

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
                                                        href="{{ route('vendor.room-types.edit', $roomType) }}"
                                                    >

                                                        <i class="bi bi-pencil me-2"></i>

                                                        Edit

                                                    </a>

                                                </li>


                                                {{-- Delete --}}
                                                <li>

                                                    <form
                                                        action="{{ route('vendor.room-types.destroy', $roomType) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Are you sure you want to delete this room type?')"
                                                    >

                                                        @csrf

                                                        @method('DELETE')

                                                        <button
                                                            type="submit"
                                                            class="dropdown-item text-danger"
                                                            @if($roomType->rooms_count > 0)
                                                                disabled
                                                                title="This room type is currently being used."
                                                            @endif
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

                    {{ $roomTypes->links() }}

                </div>


            @else

                {{-- Empty State --}}
                <div class="text-center py-5">

                    <div
                        class="rounded-circle bg-light d-flex align-items-center
                               justify-content-center mx-auto mb-3"
                        style="width:75px;height:75px;"
                    >

                        <i class="bi bi-grid-3x3-gap fs-2 text-muted"></i>

                    </div>


                    <h5 class="fw-bold">
                        No Room Types Found
                    </h5>


                    <p class="text-muted mb-3">
                        No room types have been created yet.
                    </p>


                    <a
                        href="{{ route('vendor.room-types.create') }}"
                        class="btn btn-primary"
                    >

                        <i class="bi bi-plus-lg me-1"></i>

                        Add First Room Type

                    </a>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection