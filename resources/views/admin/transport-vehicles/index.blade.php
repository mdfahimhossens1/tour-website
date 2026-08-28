@extends('layouts.admin')

@section('title', 'Transport Vehicles')

@section('page')

<div class="container-fluid">

{{-- =========================================================
     Header
========================================================== --}}
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

    <div>
        <h4 class="fw-bold mb-1">
            Transport Vehicles
        </h4>

        <p class="text-muted mb-0">
            Manage vendor vehicles, approvals and availability.
        </p>
    </div>

    <div class="d-flex gap-2 mt-2 mt-md-0">

        <span class="badge bg-warning text-dark px-3 py-2">
            Pending:
            {{ $vehicles->where('status', 'pending')->count() }}
        </span>

        <span class="badge bg-success px-3 py-2">
            Approved:
            {{ $vehicles->where('status', 'approved')->count() }}
        </span>

    </div>

</div>


{{-- =========================================================
     Success Message
========================================================== --}}
@if(session('success'))

    <div class="alert alert-success alert-dismissible fade show">

        <i class="fas fa-check-circle me-2"></i>

        {{ session('success') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert">
        </button>

    </div>

@endif


{{-- =========================================================
     Validation Errors
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
            data-bs-dismiss="alert">
        </button>

    </div>

@endif


{{-- =========================================================
     Search & Filters
========================================================== --}}
<div class="card shadow-sm border-0 mb-4">

    <div class="card-body">

        <form
            method="GET"
            action="{{ route('admin.transport-vehicles.index') }}"
        >

            <div class="row g-3 align-items-end">

                {{-- Search --}}
                <div class="col-lg-5">

                    <label class="form-label fw-semibold">
                        Search
                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            value="{{ request('search') }}"
                            placeholder="Vehicle, registration, vendor..."
                        >

                    </div>

                </div>


                {{-- Status --}}
                <div class="col-lg-3">

                    <label class="form-label fw-semibold">
                        Status
                    </label>

                    <select
                        name="status"
                        class="form-select"
                    >

                        <option value="">
                            All Status
                        </option>

                        <option
                            value="pending"
                            @selected(request('status') === 'pending')
                        >
                            Pending
                        </option>

                        <option
                            value="approved"
                            @selected(request('status') === 'approved')
                        >
                            Approved
                        </option>

                        <option
                            value="rejected"
                            @selected(request('status') === 'rejected')
                        >
                            Rejected
                        </option>

                        <option
                            value="inactive"
                            @selected(request('status') === 'inactive')
                        >
                            Inactive
                        </option>

                    </select>

                </div>


                {{-- Vehicle Type --}}
                <div class="col-lg-2">

                    <label class="form-label fw-semibold">
                        Vehicle Type
                    </label>

                    <input
                        type="text"
                        name="vehicle_type"
                        class="form-control"
                        value="{{ request('vehicle_type') }}"
                        placeholder="e.g. Bus"
                    >

                </div>


                {{-- Buttons --}}
                <div class="col-lg-2">

                    <div class="d-flex gap-2">

                        <button
                            type="submit"
                            class="btn btn-dark flex-grow-1"
                        >

                            <i class="fas fa-filter me-1"></i>
                            Filter

                        </button>

                        <a
                            href="{{ route('admin.transport-vehicles.index') }}"
                            class="btn btn-outline-secondary"
                            title="Reset"
                        >

                            <i class="fas fa-sync-alt"></i>

                        </a>

                    </div>

                </div>

            </div>

        </form>

    </div>

</div>


{{-- =========================================================
     Vehicles Table
========================================================== --}}
<div class="card shadow-sm border-0">

    <div class="card-header bg-transparent py-3">

        <div class="d-flex justify-content-between align-items-center">

            <h5 class="mb-0 fw-bold">
                All Transport Vehicles
            </h5>

            <span class="text-muted small">
                Showing {{ $vehicles->firstItem() ?? 0 }}
                -
                {{ $vehicles->lastItem() ?? 0 }}
                of {{ $vehicles->total() }}
            </span>

        </div>

    </div>


    <div class="card-body p-0">

        @if($vehicles->count())

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th class="ps-3">
                                #
                            </th>

                            <th>
                                Vehicle
                            </th>

                            <th>
                                Vendor
                            </th>

                            <th>
                                Type
                            </th>

                            <th>
                                Registration
                            </th>

                            <th>
                                Capacity
                            </th>

                            <th>
                                Price
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Verified
                            </th>

                            <th class="text-end pe-3">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($vehicles as $vehicle)

                            <tr>

                                {{-- ID --}}
                                <td class="ps-3">

                                    <span class="text-muted">
                                        {{ $vehicle->id }}
                                    </span>

                                </td>


                                {{-- Vehicle --}}
                                <td>

                                    <div class="d-flex align-items-center gap-3">

                                        <div
                                            style="
                                                width:60px;
                                                height:45px;
                                                border-radius:8px;
                                                overflow:hidden;
                                                background:#f1f3f5;
                                                flex-shrink:0;
                                            "
                                        >

                                            @if($vehicle->featured_image)

                                                <img
                                                    src="{{ asset('storage/' . $vehicle->featured_image) }}"
                                                    alt="{{ $vehicle->name }}"
                                                    style="
                                                        width:100%;
                                                        height:100%;
                                                        object-fit:cover;
                                                    "
                                                >

                                            @else

                                                <div
                                                    class="w-100 h-100 d-flex align-items-center justify-content-center text-muted"
                                                >

                                                    <i class="fas fa-car fa-lg"></i>

                                                </div>

                                            @endif

                                        </div>


                                        <div>

                                            <div class="fw-semibold">
                                                {{ $vehicle->name }}
                                            </div>

                                            @if($vehicle->brand || $vehicle->model)

                                                <div class="small text-muted">

                                                    {{ $vehicle->brand }}

                                                    @if($vehicle->brand && $vehicle->model)
                                                        -
                                                    @endif

                                                    {{ $vehicle->model }}

                                                </div>

                                            @endif

                                        </div>

                                    </div>

                                </td>


                                {{-- Vendor --}}
                                <td>

                                    @if($vehicle->vendor)

                                        <div class="fw-semibold">
                                            {{ $vehicle->vendor->name }}
                                        </div>

                                        @if($vehicle->vendor->email)

                                            <div class="small text-muted">
                                                {{ $vehicle->vendor->email }}
                                            </div>

                                        @endif

                                    @else

                                        <span class="text-danger">
                                            No Vendor
                                        </span>

                                    @endif

                                </td>


                                {{-- Type --}}
                                <td>

                                    <span class="badge bg-light text-dark border">
                                        {{ ucfirst($vehicle->vehicle_type) }}
                                    </span>

                                </td>


                                {{-- Registration --}}
                                <td>

                                    {{ $vehicle->registration_number ?: 'N/A' }}

                                </td>


                                {{-- Capacity --}}
                                <td>

                                    <i class="fas fa-users me-1 text-muted"></i>

                                    {{ $vehicle->passenger_capacity }}

                                </td>


                                {{-- Price --}}
                                <td>

                                    <div class="fw-semibold">
                                        ৳{{ number_format((float)$vehicle->price_per_day, 2) }}
                                    </div>

                                    <div class="small text-muted">
                                        / day
                                    </div>

                                </td>


                                {{-- Status --}}
                                <td>

                                    @if($vehicle->status === 'approved')

                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i>
                                            Approved
                                        </span>

                                    @elseif($vehicle->status === 'pending')

                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-clock me-1"></i>
                                            Pending
                                        </span>

                                    @elseif($vehicle->status === 'rejected')

                                        <span class="badge bg-danger">
                                            <i class="fas fa-times me-1"></i>
                                            Rejected
                                        </span>

                                    @elseif($vehicle->status === 'inactive')

                                        <span class="badge bg-secondary">
                                            <i class="fas fa-ban me-1"></i>
                                            Inactive
                                        </span>

                                    @else

                                        <span class="badge bg-secondary">
                                            {{ ucfirst($vehicle->status ?? 'Unknown') }}
                                        </span>

                                    @endif

                                </td>


                                {{-- Verified --}}
                                <td>

                                    @if($vehicle->is_verified)

                                        <span class="text-success">

                                            <i class="fas fa-check-circle"></i>

                                            Verified

                                        </span>

                                    @else

                                        <span class="text-muted">

                                            <i class="far fa-circle"></i>

                                            No

                                        </span>

                                    @endif

                                </td>


                                {{-- Actions --}}
                                <td class="text-end pe-3">

                                    <div class="dropdown">

                                        <button
                                            class="btn btn-sm btn-outline-secondary"
                                            type="button"
                                            data-bs-toggle="dropdown"
                                        >

                                            <i class="fas fa-ellipsis-v"></i>

                                        </button>


                                        <ul class="dropdown-menu dropdown-menu-end">


                                            {{-- View --}}
                                            <li>

                                                <button
                                                    type="button"
                                                    class="dropdown-item"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#viewVehicleModal{{ $vehicle->id }}"
                                                >

                                                    <i class="fas fa-eye me-2 text-primary"></i>

                                                    View Details

                                                </button>

                                            </li>


                                            {{-- Edit --}}
                                            <li>

                                                <button
                                                    type="button"
                                                    class="dropdown-item"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editVehicleModal{{ $vehicle->id }}"
                                                >

                                                    <i class="fas fa-edit me-2 text-warning"></i>

                                                    Edit Vehicle

                                                </button>

                                            </li>


                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>


                                            {{-- Approve --}}
                                            @if($vehicle->status !== 'approved')

                                                <li>

                                                    <form
                                                        method="POST"
                                                        action="{{ route('admin.transport-vehicles.approve', $vehicle) }}"
                                                    >

                                                        @csrf

                                                        <button
                                                            type="submit"
                                                            class="dropdown-item text-success"
                                                            onclick="return confirm('Are you sure you want to approve this vehicle?')"
                                                        >

                                                            <i class="fas fa-check-circle me-2"></i>

                                                            Approve

                                                        </button>

                                                    </form>

                                                </li>

                                            @endif


                                            {{-- Reject --}}
                                            @if($vehicle->status !== 'rejected')

                                                <li>

                                                    <form
                                                        method="POST"
                                                        action="{{ route('admin.transport-vehicles.reject', $vehicle) }}"
                                                    >

                                                        @csrf

                                                        <button
                                                            type="submit"
                                                            class="dropdown-item text-danger"
                                                            onclick="return confirm('Are you sure you want to reject this vehicle?')"
                                                        >

                                                            <i class="fas fa-times-circle me-2"></i>

                                                            Reject

                                                        </button>

                                                    </form>

                                                </li>

                                            @endif


                                            {{-- Activate --}}
                                            @if($vehicle->status === 'inactive')

                                                <li>

                                                    <form
                                                        method="POST"
                                                        action="{{ route('admin.transport-vehicles.activate', $vehicle) }}"
                                                    >

                                                        @csrf

                                                        <button
                                                            type="submit"
                                                            class="dropdown-item text-success"
                                                        >

                                                            <i class="fas fa-toggle-on me-2"></i>

                                                            Activate

                                                        </button>

                                                    </form>

                                                </li>

                                            @elseif($vehicle->status === 'approved')

                                                {{-- Deactivate --}}

                                                <li>

                                                    <form
                                                        method="POST"
                                                        action="{{ route('admin.transport-vehicles.deactivate', $vehicle) }}"
                                                    >

                                                        @csrf

                                                        <button
                                                            type="submit"
                                                            class="dropdown-item text-secondary"
                                                            onclick="return confirm('Deactivate this vehicle?')"
                                                        >

                                                            <i class="fas fa-toggle-off me-2"></i>

                                                            Deactivate

                                                        </button>

                                                    </form>

                                                </li>

                                            @endif


                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>


                                            {{-- Delete --}}
                                            <li>

                                                <button
                                                    type="button"
                                                    class="dropdown-item text-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteVehicleModal{{ $vehicle->id }}"
                                                >

                                                    <i class="fas fa-trash me-2"></i>

                                                    Delete

                                                </button>

                                            </li>

                                        </ul>

                                    </div>

                                </td>

                            </tr>


                            {{-- =================================================
                                 VIEW MODAL
                            ================================================== --}}
                            <div
                                class="modal fade"
                                id="viewVehicleModal{{ $vehicle->id }}"
                                tabindex="-1"
                                aria-hidden="true"
                            >

                                <div class="modal-dialog modal-lg modal-dialog-centered">

                                    <div class="modal-content">

                                        <div class="modal-header">

                                            <h5 class="modal-title fw-bold">

                                                <i class="fas fa-car me-2"></i>

                                                Vehicle Details

                                            </h5>

                                            <button
                                                type="button"
                                                class="btn-close"
                                                data-bs-dismiss="modal"
                                            ></button>

                                        </div>


                                        <div class="modal-body">

                                            <div class="row g-4">


                                                {{-- Image --}}
                                                <div class="col-md-4">

                                                    <div
                                                        class="rounded overflow-hidden border"
                                                        style="height:220px;"
                                                    >

                                                        @if($vehicle->featured_image)

                                                            <img
                                                                src="{{ asset('storage/' . $vehicle->featured_image) }}"
                                                                alt="{{ $vehicle->name }}"
                                                                class="w-100 h-100"
                                                                style="object-fit:cover;"
                                                            >

                                                        @else

                                                            <div
                                                                class="w-100 h-100 d-flex align-items-center justify-content-center bg-light text-muted"
                                                            >

                                                                <i class="fas fa-car fa-4x"></i>

                                                            </div>

                                                        @endif

                                                    </div>

                                                </div>


                                                {{-- Main Details --}}
                                                <div class="col-md-8">

                                                    <h4 class="fw-bold mb-1">
                                                        {{ $vehicle->name }}
                                                    </h4>

                                                    <p class="text-muted mb-3">

                                                        {{ $vehicle->brand }}

                                                        @if($vehicle->brand && $vehicle->model)
                                                            -
                                                        @endif

                                                        {{ $vehicle->model }}

                                                    </p>


                                                    <div class="row g-3">

                                                        <div class="col-6">

                                                            <small class="text-muted d-block">
                                                                Vehicle Type
                                                            </small>

                                                            <strong>
                                                                {{ ucfirst($vehicle->vehicle_type) }}
                                                            </strong>

                                                        </div>


                                                        <div class="col-6">

                                                            <small class="text-muted d-block">
                                                                Registration
                                                            </small>

                                                            <strong>
                                                                {{ $vehicle->registration_number ?: 'N/A' }}
                                                            </strong>

                                                        </div>


                                                        <div class="col-6">

                                                            <small class="text-muted d-block">
                                                                Passenger Capacity
                                                            </small>

                                                            <strong>
                                                                {{ $vehicle->passenger_capacity }}
                                                            </strong>

                                                        </div>


                                                        <div class="col-6">

                                                            <small class="text-muted d-block">
                                                                Price Per Day
                                                            </small>

                                                            <strong>
                                                                ৳{{ number_format((float)$vehicle->price_per_day, 2) }}
                                                            </strong>

                                                        </div>


                                                        <div class="col-6">

                                                            <small class="text-muted d-block">
                                                                Price Per Hour
                                                            </small>

                                                            <strong>
                                                                @if($vehicle->price_per_hour)
                                                                    ৳{{ number_format((float)$vehicle->price_per_hour, 2) }}
                                                                @else
                                                                    N/A
                                                                @endif
                                                            </strong>

                                                        </div>


                                                        <div class="col-6">

                                                            <small class="text-muted d-block">
                                                                Driver
                                                            </small>

                                                            <strong>

                                                                @if($vehicle->with_driver)
                                                                    With Driver
                                                                @else
                                                                    Without Driver
                                                                @endif

                                                            </strong>

                                                        </div>

                                                    </div>

                                                </div>


                                                {{-- Vendor --}}
                                                <div class="col-12">

                                                    <div class="card border">

                                                        <div class="card-header bg-transparent">

                                                            <strong>
                                                                <i class="fas fa-user-tie me-2"></i>
                                                                Vendor Information
                                                            </strong>

                                                        </div>

                                                        <div class="card-body">

                                                            @if($vehicle->vendor)

                                                                <div class="row g-3">

                                                                    <div class="col-md-4">

                                                                        <small class="text-muted d-block">
                                                                            Vendor Name
                                                                        </small>

                                                                        <strong>
                                                                            {{ $vehicle->vendor->name }}
                                                                        </strong>

                                                                    </div>


                                                                    <div class="col-md-4">

                                                                        <small class="text-muted d-block">
                                                                            Email
                                                                        </small>

                                                                        <strong>
                                                                            {{ $vehicle->vendor->email ?? 'N/A' }}
                                                                        </strong>

                                                                    </div>


                                                                    <div class="col-md-4">

                                                                        <small class="text-muted d-block">
                                                                            Phone
                                                                        </small>

                                                                        <strong>
                                                                            {{ $vehicle->vendor->phone ?? 'N/A' }}
                                                                        </strong>

                                                                    </div>

                                                                </div>

                                                            @else

                                                                <span class="text-danger">
                                                                    Vendor information unavailable.
                                                                </span>

                                                            @endif

                                                        </div>

                                                    </div>

                                                </div>


                                                {{-- Location --}}
                                                <div class="col-md-6">

                                                    <div class="card border h-100">

                                                        <div class="card-header bg-transparent">

                                                            <strong>
                                                                <i class="fas fa-map-marker-alt me-2"></i>
                                                                Location
                                                            </strong>

                                                        </div>

                                                        <div class="card-body">

                                                            <p class="mb-1">

                                                                <strong>Division:</strong>

                                                                {{ $vehicle->division ?: 'N/A' }}

                                                            </p>

                                                            <p class="mb-1">

                                                                <strong>District:</strong>

                                                                {{ $vehicle->district ?: 'N/A' }}

                                                            </p>

                                                            <p class="mb-1">

                                                                <strong>Area:</strong>

                                                                {{ $vehicle->area ?: 'N/A' }}

                                                            </p>

                                                            <p class="mb-0">

                                                                <strong>Address:</strong>

                                                                {{ $vehicle->address ?: 'N/A' }}

                                                            </p>

                                                        </div>

                                                    </div>

                                                </div>


                                                {{-- Status --}}
                                                <div class="col-md-6">

                                                    <div class="card border h-100">

                                                        <div class="card-header bg-transparent">

                                                            <strong>
                                                                <i class="fas fa-info-circle me-2"></i>
                                                                Status
                                                            </strong>

                                                        </div>

                                                        <div class="card-body">

                                                            <p class="mb-2">

                                                                <strong>Status:</strong>

                                                                {{ ucfirst($vehicle->status ?? 'N/A') }}

                                                            </p>

                                                            <p class="mb-2">

                                                                <strong>Verified:</strong>

                                                                {{ $vehicle->is_verified ? 'Yes' : 'No' }}

                                                            </p>

                                                            <p class="mb-0">

                                                                <strong>Featured:</strong>

                                                                {{ $vehicle->is_featured ? 'Yes' : 'No' }}

                                                            </p>

                                                        </div>

                                                    </div>

                                                </div>


                                                {{-- Description --}}
                                                @if($vehicle->description)

                                                    <div class="col-12">

                                                        <div class="card border">

                                                            <div class="card-header bg-transparent">

                                                                <strong>
                                                                    Description
                                                                </strong>

                                                            </div>

                                                            <div class="card-body">

                                                                {!! nl2br(e($vehicle->description)) !!}

                                                            </div>

                                                        </div>

                                                    </div>

                                                @endif

                                            </div>

                                        </div>


                                        <div class="modal-footer">

                                            <button
                                                type="button"
                                                class="btn btn-secondary"
                                                data-bs-dismiss="modal"
                                            >
                                                Close
                                            </button>

                                        </div>

                                    </div>

                                </div>

                            </div>


                            {{-- =================================================
                                 EDIT MODAL
                            ================================================== --}}
                            <div
                                class="modal fade"
                                id="editVehicleModal{{ $vehicle->id }}"
                                tabindex="-1"
                                aria-hidden="true"
                            >

                                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">

                                    <div class="modal-content">

                                        <form
                                            method="POST"
                                            action="{{ route('admin.transport-vehicles.update', $vehicle) }}"
                                            enctype="multipart/form-data"
                                        >

                                            @csrf
                                            @method('PUT')


                                            <div class="modal-header">

                                                <h5 class="modal-title fw-bold">

                                                    <i class="fas fa-edit me-2"></i>

                                                    Edit Vehicle

                                                </h5>

                                                <button
                                                    type="button"
                                                    class="btn-close"
                                                    data-bs-dismiss="modal"
                                                ></button>

                                            </div>


                                            <div class="modal-body">

                                                <div class="row g-3">


                                                    <div class="col-md-6">

                                                        <label class="form-label">
                                                            Vehicle Name
                                                        </label>

                                                        <input
                                                            type="text"
                                                            name="name"
                                                            class="form-control"
                                                            value="{{ $vehicle->name }}"
                                                            required
                                                        >

                                                    </div>


                                                    <div class="col-md-6">

                                                        <label class="form-label">
                                                            Vehicle Type
                                                        </label>

                                                        <input
                                                            type="text"
                                                            name="vehicle_type"
                                                            class="form-control"
                                                            value="{{ $vehicle->vehicle_type }}"
                                                            required
                                                        >

                                                    </div>


                                                    <div class="col-md-6">

                                                        <label class="form-label">
                                                            Brand
                                                        </label>

                                                        <input
                                                            type="text"
                                                            name="brand"
                                                            class="form-control"
                                                            value="{{ $vehicle->brand }}"
                                                        >

                                                    </div>


                                                    <div class="col-md-6">

                                                        <label class="form-label">
                                                            Model
                                                        </label>

                                                        <input
                                                            type="text"
                                                            name="model"
                                                            class="form-control"
                                                            value="{{ $vehicle->model }}"
                                                        >

                                                    </div>


                                                    <div class="col-md-6">

                                                        <label class="form-label">
                                                            Registration Number
                                                        </label>

                                                        <input
                                                            type="text"
                                                            name="registration_number"
                                                            class="form-control"
                                                            value="{{ $vehicle->registration_number }}"
                                                        >

                                                    </div>


                                                    <div class="col-md-6">

                                                        <label class="form-label">
                                                            Passenger Capacity
                                                        </label>

                                                        <input
                                                            type="number"
                                                            name="passenger_capacity"
                                                            class="form-control"
                                                            min="1"
                                                            value="{{ $vehicle->passenger_capacity }}"
                                                            required
                                                        >

                                                    </div>


                                                    <div class="col-md-6">

                                                        <label class="form-label">
                                                            Price Per Day
                                                        </label>

                                                        <input
                                                            type="number"
                                                            step="0.01"
                                                            min="0"
                                                            name="price_per_day"
                                                            class="form-control"
                                                            value="{{ $vehicle->price_per_day }}"
                                                            required
                                                        >

                                                    </div>


                                                    <div class="col-md-6">

                                                        <label class="form-label">
                                                            Price Per Hour
                                                        </label>

                                                        <input
                                                            type="number"
                                                            step="0.01"
                                                            min="0"
                                                            name="price_per_hour"
                                                            class="form-control"
                                                            value="{{ $vehicle->price_per_hour }}"
                                                        >

                                                    </div>


                                                    <div class="col-md-4">

                                                        <label class="form-label">
                                                            Division
                                                        </label>

                                                        <input
                                                            type="text"
                                                            name="division"
                                                            class="form-control"
                                                            value="{{ $vehicle->division }}"
                                                        >

                                                    </div>


                                                    <div class="col-md-4">

                                                        <label class="form-label">
                                                            District
                                                        </label>

                                                        <input
                                                            type="text"
                                                            name="district"
                                                            class="form-control"
                                                            value="{{ $vehicle->district }}"
                                                        >

                                                    </div>


                                                    <div class="col-md-4">

                                                        <label class="form-label">
                                                            Area
                                                        </label>

                                                        <input
                                                            type="text"
                                                            name="area"
                                                            class="form-control"
                                                            value="{{ $vehicle->area }}"
                                                        >

                                                    </div>


                                                    <div class="col-12">

                                                        <label class="form-label">
                                                            Address
                                                        </label>

                                                        <textarea
                                                            name="address"
                                                            rows="2"
                                                            class="form-control"
                                                        >{{ $vehicle->address }}</textarea>

                                                    </div>


                                                    <div class="col-12">

                                                        <label class="form-label">
                                                            Description
                                                        </label>

                                                        <textarea
                                                            name="description"
                                                            rows="3"
                                                            class="form-control"
                                                        >{{ $vehicle->description }}</textarea>

                                                    </div>


                                                    <div class="col-md-6">

                                                        <label class="form-label">
                                                            Change Image
                                                        </label>

                                                        <input
                                                            type="file"
                                                            name="featured_image"
                                                            class="form-control"
                                                            accept=".jpg,.jpeg,.png,.webp"
                                                        >

                                                    </div>


                                                    <div class="col-md-6">

                                                        <label class="form-label d-block">
                                                            Driver Option
                                                        </label>

                                                        <div class="form-check form-switch mt-2">

                                                            <input
                                                                type="checkbox"
                                                                class="form-check-input"
                                                                name="with_driver"
                                                                value="1"
                                                                @checked($vehicle->with_driver)
                                                            >

                                                            <label class="form-check-label">
                                                                With Driver
                                                            </label>

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>


                                            <div class="modal-footer">

                                                <button
                                                    type="button"
                                                    class="btn btn-secondary"
                                                    data-bs-dismiss="modal"
                                                >
                                                    Cancel
                                                </button>

                                                <button
                                                    type="submit"
                                                    class="btn btn-primary"
                                                >

                                                    <i class="fas fa-save me-1"></i>

                                                    Update Vehicle

                                                </button>

                                            </div>

                                        </form>

                                    </div>

                                </div>

                            </div>


                            {{-- =================================================
                                 DELETE MODAL
                            ================================================== --}}
                            <div
                                class="modal fade"
                                id="deleteVehicleModal{{ $vehicle->id }}"
                                tabindex="-1"
                                aria-hidden="true"
                            >

                                <div class="modal-dialog modal-dialog-centered">

                                    <div class="modal-content">

                                        <div class="modal-header">

                                            <h5 class="modal-title fw-bold text-danger">

                                                <i class="fas fa-trash me-2"></i>

                                                Delete Vehicle

                                            </h5>

                                            <button
                                                type="button"
                                                class="btn-close"
                                                data-bs-dismiss="modal"
                                            ></button>

                                        </div>


                                        <div class="modal-body">

                                            <p class="mb-2">
                                                Are you sure you want to delete this vehicle?
                                            </p>

                                            <div class="alert alert-warning mb-0">

                                                <strong>
                                                    {{ $vehicle->name }}
                                                </strong>

                                                <br>

                                                <small>
                                                    {{ $vehicle->registration_number ?: 'No registration number' }}
                                                </small>

                                            </div>

                                            <p class="text-danger small mt-3 mb-0">

                                                <i class="fas fa-exclamation-triangle me-1"></i>

                                                This action cannot be undone.

                                            </p>

                                        </div>


                                        <div class="modal-footer">

                                            <button
                                                type="button"
                                                class="btn btn-secondary"
                                                data-bs-dismiss="modal"
                                            >
                                                Cancel
                                            </button>


                                            <form
                                                method="POST"
                                                action="{{ route('admin.transport-vehicles.destroy', $vehicle) }}"
                                            >

                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn btn-danger"
                                                >

                                                    <i class="fas fa-trash me-1"></i>

                                                    Delete Vehicle

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

            {{-- Empty State --}}
            <div class="text-center py-5">

                <div class="mb-3">

                    <i
                        class="fas fa-car-side fa-3x text-muted"
                    ></i>

                </div>

                <h5 class="fw-bold">
                    No Transport Vehicles Found
                </h5>

                <p class="text-muted mb-0">

                    No vehicles match your current search or filter.

                </p>

            </div>

        @endif

    </div>


    {{-- Pagination --}}
    @if($vehicles->hasPages())

        <div class="card-footer bg-transparent">

            {{ $vehicles->links() }}

        </div>

    @endif

</div>

</div>

@endsection
