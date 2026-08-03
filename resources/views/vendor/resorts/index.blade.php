@extends('layouts.vendor')

@section('page')

<div class="container-fluid py-4">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h4 class="fw-bold mb-1">
                My Resorts
            </h4>

            <p class="text-muted mb-0">
                Manage your resorts, rooms, facilities and bookings.
            </p>
        </div>

        <div class="mt-3 mt-md-0">

            <a href="{{ route('vendor.resorts.create') }}"
               class="btn btn-primary">

                <i class="bi bi-plus-lg me-1"></i>

                Add New Resort

            </a>

        </div>

    </div>


    {{-- =========================================================
        ALERTS
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show"
             role="alert">

            <i class="bi bi-check-circle me-1"></i>

            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show"
             role="alert">

            <i class="bi bi-exclamation-triangle me-1"></i>

            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    {{-- =========================================================
        STATISTICS
    ========================================================== --}}

    @php

        $totalResorts = $resorts->total();

        $activeResorts = $resorts
            ->where('status', 1)
            ->count();

        $featuredResorts = $resorts
            ->where('is_featured', 1)
            ->count();

        $verifiedResorts = $resorts
            ->where('is_verified', 1)
            ->count();

    @endphp


    <div class="row g-3 mb-4">

        {{-- Total --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted">
                                Total Resorts
                            </small>

                            <h3 class="fw-bold mb-0">
                                {{ $totalResorts }}
                            </h3>

                        </div>

                        <div
                            class="rounded-circle bg-primary bg-opacity-10
                                   text-primary d-flex align-items-center
                                   justify-content-center"
                            style="width:52px;height:52px;"
                        >

                            <i class="bi bi-building fs-4"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Active --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted">
                                Active Resorts
                            </small>

                            <h3 class="fw-bold mb-0">
                                {{ $activeResorts }}
                            </h3>

                        </div>

                        <div
                            class="rounded-circle bg-success bg-opacity-10
                                   text-success d-flex align-items-center
                                   justify-content-center"
                            style="width:52px;height:52px;"
                        >

                            <i class="bi bi-check-circle fs-4"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Featured --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted">
                                Featured Resorts
                            </small>

                            <h3 class="fw-bold mb-0">
                                {{ $featuredResorts }}
                            </h3>

                        </div>

                        <div
                            class="rounded-circle bg-warning bg-opacity-10
                                   text-warning d-flex align-items-center
                                   justify-content-center"
                            style="width:52px;height:52px;"
                        >

                            <i class="bi bi-star fs-4"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Verified --}}
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted">
                                Verified Resorts
                            </small>

                            <h3 class="fw-bold mb-0">
                                {{ $verifiedResorts }}
                            </h3>

                        </div>

                        <div
                            class="rounded-circle bg-info bg-opacity-10
                                   text-info d-flex align-items-center
                                   justify-content-center"
                            style="width:52px;height:52px;"
                        >

                            <i class="bi bi-patch-check fs-4"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        RESORT TABLE
    ========================================================== --}}

    <div class="card border-0 shadow-sm">

        {{-- Header --}}
        <div class="card-header bg-white border-0 py-3">

            <div class="d-flex flex-wrap justify-content-between align-items-center">

                <div>

                    <h5 class="fw-bold mb-1">
                        All Resorts
                    </h5>

                    <small class="text-muted">
                        Manage resorts belonging to your vendor account.
                    </small>

                </div>

                <div class="mt-2 mt-md-0">

                    <span class="badge bg-light text-dark border">

                        {{ $resorts->total() }}

                        {{ $resorts->total() == 1 ? 'Resort' : 'Resorts' }}

                    </span>

                </div>

            </div>

        </div>


        <div class="card-body p-0">

            @if($resorts->count())


                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th class="ps-4">
                                    Resort
                                </th>

                                <th>
                                    Destination
                                </th>

                                <th>
                                    Location
                                </th>

                                <th>
                                    Rooms
                                </th>

                                <th>
                                    Rating
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

                            @foreach($resorts as $resort)

                                <tr>


                                    {{-- =================================================
                                        RESORT
                                    ================================================== --}}

                                    <td class="ps-4">

                                        <div class="d-flex align-items-center gap-3">


                                            {{-- Image --}}

                                            @if($resort->featured_image)

                                                <img
                                                    src="{{ asset('storage/' . $resort->featured_image) }}"
                                                    alt="{{ $resort->name }}"
                                                    style="
                                                        width:65px;
                                                        height:52px;
                                                        object-fit:cover;
                                                        border-radius:10px;
                                                    "
                                                >

                                            @else

                                                <div
                                                    class="bg-light d-flex align-items-center
                                                           justify-content-center"
                                                    style="
                                                        width:65px;
                                                        height:52px;
                                                        border-radius:10px;
                                                    "
                                                >

                                                    <i class="bi bi-building text-muted fs-4"></i>

                                                </div>

                                            @endif


                                            {{-- Name --}}

                                            <div>

                                                <div class="fw-bold">

                                                    {{ $resort->name }}

                                                </div>


                                                @if($resort->slug)

                                                    <small class="text-muted">

                                                        {{ $resort->slug }}

                                                    </small>

                                                @endif


                                                {{-- Featured / Verified --}}

                                                <div class="mt-1">

                                                    @if($resort->is_featured)

                                                        <span class="badge bg-warning text-dark">
                                                            <i class="bi bi-star-fill me-1"></i>
                                                            Featured
                                                        </span>

                                                    @endif


                                                    @if($resort->is_verified)

                                                        <span class="badge bg-success">
                                                            <i class="bi bi-patch-check me-1"></i>
                                                            Verified
                                                        </span>

                                                    @endif

                                                </div>

                                            </div>

                                        </div>

                                    </td>


                                    {{-- =================================================
                                        DESTINATION
                                    ================================================== --}}

                                    <td>

                                        @if($resort->destination)

                                            <span class="fw-semibold">

                                                {{ $resort->destination->name }}

                                            </span>

                                        @else

                                            <span class="text-muted">
                                                N/A
                                            </span>

                                        @endif

                                    </td>


                                    {{-- =================================================
                                        LOCATION
                                    ================================================== --}}

                                    <td>

                                        <div>

                                            @if($resort->district)

                                                <div class="fw-semibold">

                                                    {{ $resort->district }}

                                                </div>

                                            @endif


                                            @if($resort->division)

                                                <small class="text-muted">

                                                    {{ $resort->division }}

                                                </small>

                                            @endif


                                            @if($resort->area)

                                                <small class="d-block text-muted">

                                                    {{ $resort->area }}

                                                </small>

                                            @endif


                                            @if(
                                                !$resort->district &&
                                                !$resort->division &&
                                                !$resort->area
                                            )

                                                <span class="text-muted">
                                                    N/A
                                                </span>

                                            @endif

                                        </div>

                                    </td>


                                    {{-- =================================================
                                        ROOMS
                                    ================================================== --}}

                                    <td>

                                        <div class="d-flex align-items-center gap-2">

                                            <span class="badge bg-primary">

                                                {{ $resort->rooms_count ?? $resort->rooms->count() }}

                                            </span>

                                            <span class="text-muted small">
                                                rooms
                                            </span>

                                        </div>

                                    </td>


                                    {{-- =================================================
                                        RATING
                                    ================================================== --}}

                                    <td>

                                        <div class="d-flex align-items-center gap-1">

                                            <i class="bi bi-star-fill text-warning"></i>

                                            <strong>

                                                {{ number_format($resort->rating ?? 0, 1) }}

                                            </strong>

                                        </div>

                                        <small class="text-muted">

                                            {{ $resort->total_reviews ?? 0 }}
                                            reviews

                                        </small>

                                    </td>


                                    {{-- =================================================
                                        STATUS
                                    ================================================== --}}

                                    <td>

                                        @if($resort->status)

                                            <span class="badge bg-success">

                                                <i class="bi bi-check-circle me-1"></i>

                                                Active

                                            </span>

                                        @else

                                            <span class="badge bg-danger">

                                                <i class="bi bi-x-circle me-1"></i>

                                                Inactive

                                            </span>

                                        @endif

                                    </td>


                                    {{-- =================================================
                                        ACTION
                                    ================================================== --}}

                                    <td class="text-end pe-4">

                                        <div class="dropdown">


                                            <button
                                                class="btn btn-sm btn-light border"
                                                type="button"
                                                data-bs-toggle="dropdown"
                                                aria-expanded="false"
                                            >

                                                <i class="bi bi-three-dots-vertical"></i>

                                            </button>


                                            <ul class="dropdown-menu dropdown-menu-end">


                                                {{-- View Rooms --}}

                                                <li>

                                                    <a
                                                        class="dropdown-item"
                                                        href="{{ route('vendor.rooms.index', ['resort' => $resort->id]) }}"
                                                    >

                                                        <i class="bi bi-door-open me-2"></i>

                                                        Manage Rooms

                                                    </a>

                                                </li>


                                                {{-- Edit --}}

                                                <li>

                                                    <a
                                                        class="dropdown-item"
                                                        href="{{ route('vendor.resorts.edit', $resort->slug) }}"
                                                    >

                                                        <i class="bi bi-pencil me-2"></i>

                                                        Edit Resort

                                                    </a>

                                                </li>


                                                {{-- Divider --}}

                                                <li>

                                                    <hr class="dropdown-divider">

                                                </li>


                                                {{-- Delete --}}

                                                <li>

                                                    <form
                                                        action="{{ route('vendor.resorts.delete', $resort->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Are you sure you want to delete this resort? All related rooms may also be affected.')"
                                                    >

                                                        @csrf

                                                        @method('DELETE')


                                                        <button
                                                            type="submit"
                                                            class="dropdown-item text-danger"
                                                        >

                                                            <i class="bi bi-trash me-2"></i>

                                                            Delete Resort

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


                {{-- =================================================
                    PAGINATION
                ================================================== --}}

                <div class="p-3">

                    {{ $resorts->links() }}

                </div>


            @else


                {{-- =================================================
                    EMPTY STATE
                ================================================== --}}

                <div class="text-center py-5">

                    <div
                        class="rounded-circle bg-light d-flex align-items-center
                               justify-content-center mx-auto mb-3"
                        style="width:80px;height:80px;"
                    >

                        <i class="bi bi-building fs-1 text-muted"></i>

                    </div>


                    <h5 class="fw-bold">
                        No Resorts Found
                    </h5>


                    <p class="text-muted mb-4">

                        You haven't created any resort yet.

                    </p>


                    <a
                        href="{{ route('vendor.resorts.create') }}"
                        class="btn btn-primary"
                    >

                        <i class="bi bi-plus-lg me-1"></i>

                        Create Your First Resort

                    </a>

                </div>


            @endif

        </div>

    </div>

</div>

@endsection
